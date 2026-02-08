# Daftara Inventory Management API — Implementation Plan

## Table of Contents

1. [Project Overview](#1-project-overview)
2. [Tech Stack](#2-tech-stack)
3. [Architecture](#3-architecture)
4. [Module Structure](#4-module-structure)
5. [Database Design](#5-database-design)
6. [Module Breakdown](#6-module-breakdown)
7. [API Endpoints](#7-api-endpoints)
8. [RBAC Design](#8-rbac-design)
9. [Request Lifecycle](#9-request-lifecycle)
10. [Caching Strategy](#10-caching-strategy)
11. [Event System](#11-event-system)
12. [Testing Strategy](#12-testing-strategy)
13. [Seeders](#13-seeders)
14. [Installation Command](#14-installation-command)
15. [API Documentation](#15-api-documentation)
16. [Implementation Order](#16-implementation-order)

---

## 1. Project Overview

A simplified RESTful API for managing inventory across multiple warehouses, built with Laravel 12 following strict Domain-Driven Design (DDD) architecture using the `cyberbugz/dust` modular framework package.

---

## 2. Tech Stack

| Layer              | Technology                          |
|--------------------|-------------------------------------|
| Framework          | Laravel 12                          |
| Modular Framework  | `cyberbugz/dust`                    |
| Authentication     | Laravel Sanctum (token-based)       |
| Authorization      | `spatie/laravel-permission`         |
| Activity Logging   | `spatie/laravel-activitylog`        |
| API Documentation  | `knuckleswtf/scribe`               |
| Routing            | `spatie/laravel-route-attributes` (via Dust's attribute-based routing) |
| Cache Driver       | File                                |
| Database           | MySQL 8.0                           |
| Testing            | PHPUnit (Laravel default)           |

---

## 3. Architecture

### 3.1 DDD Layered Architecture

Every module follows four strict layers:

| Layer            | Responsibility                                                       | Framework Dependencies |
|------------------|----------------------------------------------------------------------|------------------------|
| **Presentation** | HTTP interface — Controllers, Requests, Responses, Resources, DTOs   | Yes (Laravel/Dust)     |
| **Application**  | Use case orchestration — Services, Pipelines, DTOs                   | Minimal                |
| **Domain**       | Pure business logic — Entities, Contracts, Enums, Exceptions, DTOs   | **None**               |
| **Infrastructure** | Framework implementations — Models, Repos, Observers, Events, Providers, Migrations, Seeders, Factories | Yes (Laravel/Eloquent) |

### 3.2 Dependency Rule

Dependencies flow **inward only**:

```
Presentation → Application → Domain ← Infrastructure
```

- **Domain** defines interfaces (contracts).
- **Infrastructure** implements those contracts.
- **Application** depends on Domain contracts, never on Infrastructure directly.
- **Infrastructure/Providers** binds contracts to implementations via Service Providers.

### 3.3 Request Flow

```
HTTP Request
  → Presentation/Controller (extends Dust\Base\Controller)
    → Constructor injects: Response, Request (FormRequest), Service
    → handle() method:
        1. Forms Presentation/DTO from validated Request
        2. Calls Application/Service::handle(DTO)
        3. Service uses Domain/Contracts/Repository (bound to Infrastructure/Repository)
        4. Returns result
    → Abstract Controller passes result to Response::createResource()
    → Response::success() fires events if needed
  → HTTP Response (JSON)
```

---

## 4. Module Structure

### 4.1 Modules

| Module          | Responsibility                                                    |
|-----------------|-------------------------------------------------------------------|
| **Auth**        | User registration, login, logout, Sanctum tokens, RBAC setup     |
| **Warehouse**   | Warehouses, Inventory Items, Stock (WarehouseInventoryItems), Stock Transfers, Filters, Caching |
| **Notifications** | Notification channels, notifications, receivers, warehouse subscriptions, LowStockDetected dispatch |
| **Audit**       | Activity log configuration, log retrieval endpoint                |

### 4.2 Directory Structure Per Module

```
app/Modules/{Module}/
├── Presentation/
│   ├── Controllers/
│   │   └── Api/                         ← One controller per action (extends Dust\Base\Controller)
│   ├── Requests/
│   │   └── Api/                         ← FormRequest classes
│   ├── Responses/
│   │   └── Api/                         ← Response classes (extends Dust\Base\Response)
│   ├── Resources/                       ← Laravel API Resources
│   ├── DTOs/                            ← Input DTOs (fromRequest)
│   └── Middleware/
├── Application/
│   ├── Services/                        ← Per-action service with handle() method
│   ├── Pipelines/                       ← Filter pipe classes (Laravel Pipeline)
│   └── DTOs/                            ← Inter-layer DTOs if needed
├── Domain/
│   ├── Entities/                        ← Pure PHP domain objects (no Eloquent)
│   ├── Contracts/
│   │   ├── Events/                      ← Event interfaces
│   │   ├── Listeners/                   ← Listener interfaces
│   │   ├── Repositories/               ← Repository interfaces
│   │   └── Observers/                   ← Observer interfaces
│   ├── Enums/
│   ├── DTOs/                            ← Domain-level / output DTOs
│   └── Exceptions/
├── Infrastructure/
│   ├── Models/                          ← Eloquent Models
│   ├── Repositories/                    ← Implements Domain/Contracts/Repositories
│   ├── Observers/                       ← Implements Domain/Contracts/Observers
│   ├── Events/                          ← Implements Domain/Contracts/Events (Laravel events)
│   ├── Listeners/                       ← Implements Domain/Contracts/Listeners
│   ├── Providers/                       ← Service Providers (bindings)
│   ├── Seeders/
│   ├── Factories/
│   └── Migrations/
└── Tests/
    ├── Unit/
    └── Feature/
        └── Api/
```

---

## 5. Database Design

### 5.1 Tables

#### `users`

| Column             | Type              | Notes                              |
|--------------------|-------------------|------------------------------------|
| id                 | bigint UNSIGNED PK |                                   |
| name               | varchar(255)      |                                    |
| email              | varchar(255) UNIQUE |                                  |
| email_verified_at  | timestamp NULL    |                                    |
| password           | varchar(255)      |                                    |
| preferences        | json NOT NULL     | Default: `{"notification_channel": "email"}` |
| remember_token     | varchar(100) NULL |                                    |
| created_at         | timestamp NULL    |                                    |
| updated_at         | timestamp NULL    |                                    |

#### `warehouses`

| Column     | Type              | Notes         |
|------------|-------------------|---------------|
| id         | bigint UNSIGNED PK |              |
| name       | varchar(255) NULL |               |
| location   | varchar(255) NULL |               |
| created_at | timestamp NULL    |               |
| updated_at | timestamp NULL    |               |


#### `inventory_items`

| Column      | Type              | Notes                |
|-------------|-------------------|----------------------|
| id          | bigint UNSIGNED PK |                     |
| name        | varchar(255) NULL |                      |
| SKU         | varchar(255) UNIQUE |                    |
| price       | decimal(8,2) NULL | Changed from (10,0) |
| description | text NULL         |                      |
| created_by  | bigint UNSIGNED FK → users |             |
| created_at  | timestamp NULL    |                      |
| updated_at  | timestamp NULL    |                      |

#### `warehouse_inventory_items` (Stock)

| Column              | Type              | Notes                        |
|---------------------|-------------------|------------------------------|
| id                  | bigint UNSIGNED PK |                             |
| inventory_id        | bigint UNSIGNED FK → inventory_items |              |
| warehouse_id        | bigint UNSIGNED FK → warehouses |                   |
| stock               | int NULL          |                              |
| low_stock_threshold | int NULL          |                              |
| last_updated_by     | bigint UNSIGNED FK → users |                       |
| created_at          | timestamp NULL    |                              |
| updated_at          | timestamp NULL    |                              |

#### `stock_transfers`

| Column              | Type              | Notes                        |
|---------------------|-------------------|------------------------------|
| id                  | bigint UNSIGNED PK |                             |
| inventory_id        | bigint UNSIGNED FK → inventory_items |              |
| base_warehouse_id   | bigint UNSIGNED FK → warehouses |                   |
| target_warehouse_id | bigint UNSIGNED FK → warehouses |                   |
| amount              | int NULL          |                              |
| created_by          | bigint UNSIGNED FK → users |                       |
| created_at          | timestamp NULL    |                              |
| updated_at          | timestamp NULL    |                              |


#### `notification_channels`

| Column      | Type              | Notes          |
|-------------|-------------------|----------------|
| id          | bigint UNSIGNED PK |               |
| name        | varchar(255) UNIQUE |              |
| description | text NULL         |                |
| is_active   | tinyint(1) NULL   |                |
| created_at  | timestamp NULL    |                |
| updated_at  | timestamp NULL    |                |

> Seeded with: `email` only.

#### `notifications`

| Column     | Type              | Notes                             |
|------------|-------------------|-----------------------------------|
| id         | bigint UNSIGNED PK |                                  |
| type       | varchar(255) NULL | e.g., `low_stock`                 |
| subject    | varchar(255) NULL |                                   |
| content    | text NULL         |                                   |
| channel_id | bigint UNSIGNED FK → notification_channels |          |
| created_at | timestamp NULL    |                                   |

#### `notification_receivers`

| Column          | Type              | Notes                           |
|-----------------|-------------------|---------------------------------|
| id              | bigint UNSIGNED PK |                                |
| notifiable_type | varchar(255) NULL | Polymorphic (User for now)      |
| notifiable_id   | bigint UNSIGNED NULL |                              |
| notification_id | bigint UNSIGNED FK → notifications |                  |
| status          | varchar(255) NULL |                                 |
| read_at         | timestamp NULL    |                                 |
| sent_at         | timestamp NULL    |                                 |

#### `warehouse_notification_subscriptions` 

| Column       | Type              | Notes                              |
|--------------|-------------------|------------------------------------|
| id           | bigint UNSIGNED PK |                                   |
| user_id      | bigint UNSIGNED FK → users |                              |
| warehouse_id | bigint UNSIGNED FK → warehouses |                         |
| created_at   | timestamp NULL    |                                    |
| updated_at   | timestamp NULL    |                                    |


### 5.2 Spatie Permission Tables (Auto-generated)

- `permissions`
- `roles`
- `role_has_permissions`
- `model_has_roles`
- `model_has_permissions`

### 5.3 Spatie Activity Log Table (Auto-generated)

- `activity_log`

---

## 6. Module Breakdown

### 6.1 Auth Module

#### Domain Layer
- **Entities**: `UserEntity` — pure PHP object representing a user
- **Contracts/Repositories**: `UserRepositoryInterface`
- **Exceptions**: `InvalidCredentialsException`, `UserAlreadyExistsException`

#### Infrastructure Layer
- **Models**: `User` (Eloquent) — uses `HasRoles` (spatie), `HasApiTokens` (Sanctum)
- **Repositories**: `UserRepository implements UserRepositoryInterface`
- **Providers**: `AuthServiceProvider` — binds contracts, registers Sanctum guard
- **Migrations**: `create_users_table`, `create_personal_access_tokens_table`
- **Seeders**: `RolesAndPermissionsSeeder`, `UsersSeeder`

#### Application Layer
- **Services**: `RegisterService`, `LoginService`, `LogoutService`, `GetProfileService`
- **DTOs**: (if inter-layer transfer needed)

#### Presentation Layer
- **Controllers/Api**: `RegisterController`, `LoginController`, `LogoutController`, `GetProfileController`
- **Requests/Api**: `RegisterRequest`, `LoginRequest`
- **Responses/Api**: `RegisterResponse`, `LoginResponse`, `LogoutResponse`, `GetProfileResponse`
- **Resources**: `UserResource`
- **DTOs**: `RegisterDTO`, `LoginDTO`

---

### 6.2 Warehouse Module

#### Domain Layer
- **Entities**: `WarehouseEntity`, `InventoryItemEntity`, `WarehouseInventoryItemEntity`, `StockTransferEntity`
- **Contracts/Repositories**: `WarehouseRepositoryInterface`, `InventoryItemRepositoryInterface`, `WarehouseInventoryItemRepositoryInterface`, `StockTransferRepositoryInterface`
- **Contracts/Observers**: `WarehouseInventoryItemObserverInterface`
- **Enums**: (if needed)
- **Exceptions**: `InsufficientStockException`, `WarehouseNotFoundException`, `InventoryItemNotFoundException`, `DuplicateTransferException`
- **DTOs**: Domain output DTOs

#### Infrastructure Layer
- **Models**: `Warehouse`, `InventoryItem`, `WarehouseInventoryItem`, `StockTransfer` (Eloquent)
- **Repositories**: `WarehouseRepository`, `InventoryItemRepository`, `WarehouseInventoryItemRepository`, `StockTransferRepository`
- **Observers**: `WarehouseInventoryItemObserver implements WarehouseInventoryItemObserverInterface` — handles cache invalidation on any stock change
- **Providers**: `WarehouseServiceProvider` — binds all contracts to implementations, registers observers
- **Migrations**: All warehouse-related tables
- **Seeders**: `WarehousesSeeder`, `InventoryItemsSeeder`, `WarehouseInventoryItemsSeeder`
- **Factories**: `WarehouseFactory`, `InventoryItemFactory`, `WarehouseInventoryItemFactory`, `StockTransferFactory`

#### Application Layer
- **Services**:
  - Warehouse CRUD: `IndexWarehouseService`, `StoreWarehouseService`, `ShowWarehouseService`, `UpdateWarehouseService`, `DestroyWarehouseService`
  - Inventory Item CRUD: `IndexInventoryItemService`, `StoreInventoryItemService`, `ShowInventoryItemService`, `UpdateInventoryItemService`, `DestroyInventoryItemService`
  - Stock: `GetWarehouseInventoryService`, `GetAllInventoryService`
  - Transfer: `CreateStockTransferService`
- **Pipelines** (filter pipes for inventory search):
  - `FilterByWarehouse`
  - `FilterByName`
  - `FilterBySku`
  - `FilterByPriceMin`
  - `FilterByPriceMax`
- **DTOs**: `StoreWarehouseDTO`, `UpdateWarehouseDTO`, `StoreInventoryItemDTO`, `UpdateInventoryItemDTO`, `CreateStockTransferDTO`

#### Presentation Layer
- **Controllers/Api**:
  - Warehouse: `IndexWarehouseController`, `StoreWarehouseController`, `ShowWarehouseController`, `UpdateWarehouseController`, `DestroyWarehouseController`
  - Inventory: `IndexInventoryItemController`, `StoreInventoryItemController`, `ShowInventoryItemController`, `UpdateInventoryItemController`, `DestroyInventoryItemController`
  - Stock: `GetWarehouseInventoryController`, `GetAllInventoryController`
  - Transfer: `CreateStockTransferController`
- **Requests/Api**: Corresponding FormRequest for each controller
- **Responses/Api**: Corresponding Response for each controller — `CreateStockTransferResponse` fires `LowStockDetected` in `success()`
- **Resources**: `WarehouseResource`, `InventoryItemResource`, `WarehouseInventoryItemResource`, `StockTransferResource`
- **DTOs**: `StoreWarehouseInputDTO`, `CreateStockTransferInputDTO`, etc.

---

### 6.3 Notifications Module

#### Domain Layer
- **Entities**: `NotificationChannelEntity`, `NotificationEntity`, `NotificationReceiverEntity`, `WarehouseNotificationSubscriptionEntity`
- **Contracts/Repositories**: `NotificationChannelRepositoryInterface`, `NotificationRepositoryInterface`, `NotificationReceiverRepositoryInterface`, `WarehouseNotificationSubscriptionRepositoryInterface`
- **Contracts/Events**: `LowStockDetectedInterface`
- **Contracts/Listeners**: `SendLowStockNotificationListenerInterface`
- **Exceptions**: `SubscriptionAlreadyExistsException`, `NotificationChannelNotFoundException`

#### Infrastructure Layer
- **Models**: `NotificationChannel`, `Notification`, `NotificationReceiver`, `WarehouseNotificationSubscription` (Eloquent)
- **Repositories**: Implements all domain repository contracts
- **Events**: `LowStockDetected implements LowStockDetectedInterface` — Laravel event class
- **Listeners**: `SendLowStockNotification implements SendLowStockNotificationListenerInterface` — creates notification record, creates notification_receivers for each subscribed user (does NOT actually send email, just triggers the event and logs)
- **Providers**: `NotificationServiceProvider` — binds contracts, registers event-listener mappings
- **Migrations**: `create_notification_channels_table`, `create_notifications_table`, `create_notification_receivers_table`, `create_warehouse_notification_subscriptions_table`
- **Seeders**: `NotificationChannelsSeeder` (seeds `email` channel)

#### Application Layer
- **Services**: `SubscribeUserToWarehouseService`, `UnsubscribeUserFromWarehouseService`, `GetWarehouseSubscribersService`

#### Presentation Layer
- **Controllers/Api**: `SubscribeUserToWarehouseController`, `UnsubscribeUserFromWarehouseController`, `GetWarehouseSubscribersController`
- **Requests/Api**: `SubscribeUserRequest`, `UnsubscribeUserRequest`
- **Responses/Api**: Corresponding responses
- **Resources**: `NotificationResource`, `SubscriptionResource`
- **DTOs**: `SubscribeUserDTO`

---

### 6.4 Audit Module

#### Domain Layer
- **Entities**: `ActivityLogEntity`
- **Contracts/Repositories**: `ActivityLogRepositoryInterface`

#### Infrastructure Layer
- **Models**: Uses spatie's `Activity` model (or extends it)
- **Repositories**: `ActivityLogRepository implements ActivityLogRepositoryInterface`
- **Providers**: `AuditServiceProvider` — configures spatie/activitylog, binds contracts. Registers `LogsActivity` trait usage across all modules' Eloquent models.
- **Migrations**: Spatie auto-generated

#### Application Layer
- **Services**: `GetActivityLogsService`

#### Presentation Layer
- **Controllers/Api**: `GetActivityLogsController`
- **Requests/Api**: `GetActivityLogsRequest` (filters by subject_type, causer_id, date range, etc.)
- **Responses/Api**: `GetActivityLogsResponse`
- **Resources**: `ActivityLogResource`

---

## 7. API Endpoints

### 7.1 Auth Module

| Method | URI                | Controller            | Auth | Description           |
|--------|--------------------|-----------------------|------|-----------------------|
| POST   | `/api/register`    | RegisterController    | No   | Register a new user   |
| POST   | `/api/login`       | LoginController       | No   | Login, returns token  |
| POST   | `/api/logout`      | LogoutController      | Yes  | Revoke current token  |
| GET    | `/api/me`          | GetProfileController  | Yes  | Get authenticated user|

### 7.2 Warehouse Module — Warehouses

| Method | URI                          | Controller                | Auth | Permission           |
|--------|------------------------------|---------------------------|------|----------------------|
| GET    | `/api/warehouses`            | IndexWarehouseController  | Yes  | `view-warehouses`    |
| POST   | `/api/warehouses`            | StoreWarehouseController  | Yes  | `create-warehouses`  |
| GET    | `/api/warehouses/{id}`       | ShowWarehouseController   | Yes  | `view-warehouses`    |
| PUT    | `/api/warehouses/{id}`       | UpdateWarehouseController | Yes  | `update-warehouses`  |
| DELETE | `/api/warehouses/{id}`       | DestroyWarehouseController| Yes  | `delete-warehouses`  |

### 7.3 Warehouse Module — Inventory Items

| Method | URI                              | Controller                    | Auth | Permission               |
|--------|----------------------------------|-------------------------------|------|--------------------------|
| GET    | `/api/inventory-items`           | IndexInventoryItemController  | Yes  | `view-inventory-items`   |
| POST   | `/api/inventory-items`           | StoreInventoryItemController  | Yes  | `create-inventory-items` |
| GET    | `/api/inventory-items/{id}`      | ShowInventoryItemController   | Yes  | `view-inventory-items`   |
| PUT    | `/api/inventory-items/{id}`      | UpdateInventoryItemController | Yes  | `update-inventory-items` |
| DELETE | `/api/inventory-items/{id}`      | DestroyInventoryItemController| Yes  | `delete-inventory-items` |

### 7.4 Warehouse Module — Inventory (Stock)

| Method | URI                                    | Controller                    | Auth | Permission              |
|--------|----------------------------------------|-------------------------------|------|-------------------------|
| GET    | `/api/inventory`                       | GetAllInventoryController     | Yes  | `view-inventory`        |
| GET    | `/api/warehouses/{id}/inventory`       | GetWarehouseInventoryController| Yes | `view-inventory`        |

- `GET /api/inventory` — paginated, filterable (warehouse_id, name, SKU, price_min, price_max) via Pipeline pattern
- `GET /api/warehouses/{id}/inventory` — cached via file driver, invalidated by observer

### 7.5 Warehouse Module — Stock Transfers

| Method | URI                      | Controller                   | Auth | Permission              |
|--------|--------------------------|------------------------------|------|-------------------------|
| POST   | `/api/stock-transfers`   | CreateStockTransferController| Yes  | `create-stock-transfers`|

### 7.6 Notifications Module — Subscriptions

| Method | URI                                          | Controller                          | Auth | Permission                      |
|--------|----------------------------------------------|-------------------------------------|------|---------------------------------|
| POST   | `/api/warehouses/{id}/subscribers`           | SubscribeUserToWarehouseController  | Yes  | `manage-warehouse-subscriptions`|
| DELETE | `/api/warehouses/{id}/subscribers/{userId}`  | UnsubscribeUserFromWarehouseController | Yes | `manage-warehouse-subscriptions`|
| GET    | `/api/warehouses/{id}/subscribers`           | GetWarehouseSubscribersController   | Yes  | `manage-warehouse-subscriptions`|

### 7.7 Audit Module

| Method | URI                  | Controller               | Auth | Permission         |
|--------|----------------------|--------------------------|------|--------------------|
| GET    | `/api/activity-logs` | GetActivityLogsController| Yes  | `view-activity-logs`|

---

## 8. RBAC Design

### 8.1 Roles

| Role           | Description                                                     |
|----------------|-----------------------------------------------------------------|
| `superadmin`   | Full access to everything. Can manage subscriptions and roles.  |
| `manager`      | Can manage warehouses, inventory, transfers, view logs.         |
| `staff`        | Can view inventory, create transfers. Limited write access.     |

### 8.2 Permissions

| Permission                          | superadmin | manager | staff |
|-------------------------------------|:----------:|:-------:|:-----:|
| `view-warehouses`                   | ✓          | ✓       | ✓     |
| `create-warehouses`                 | ✓          | ✓       |       |
| `update-warehouses`                 | ✓          | ✓       |       |
| `delete-warehouses`                 | ✓          |         |       |
| `view-inventory-items`              | ✓          | ✓       | ✓     |
| `create-inventory-items`            | ✓          | ✓       |       |
| `update-inventory-items`            | ✓          | ✓       |       |
| `delete-inventory-items`            | ✓          |         |       |
| `view-inventory`                    | ✓          | ✓       | ✓     |
| `create-stock-transfers`            | ✓          | ✓       | ✓     |
| `manage-warehouse-subscriptions`    | ✓          |         |       |
| `view-activity-logs`                | ✓          | ✓       |       |

### 8.3 Seeded Users

| User              | Email                     | Role       |
|-------------------|---------------------------|------------|
| Super Admin       | superadmin@daftara.com    | superadmin |
| Warehouse Manager | manager@daftara.com       | manager    |
| Staff Member      | staff@daftara.com         | staff      |

> Password for all seeded users: `password`

---

## 9. Request Lifecycle

### 9.1 Controller Pattern (Dust)

Every controller follows this exact pattern:

```php
#[Guard('api')]
#[Middleware(['auth:sanctum'])]
#[Route(Http::POST, 'stock-transfers', 'api.stock-transfers.store')]
class CreateStockTransferController extends Controller
{
    public function __construct(
        CreateStockTransferResponse $response,     // 1st — Response
        CreateStockTransferRequest $request,        // 2nd — FormRequest
        protected CreateStockTransferService $service // 3rd — Service
    ) {
        parent::__construct($response, $request);
    }

    public function handle(ResponseInterface $response, Request $request): mixed
    {
        $dto = CreateStockTransferInputDTO::fromRequest($request);
        return $this->service->handle($dto);
    }
}
```

### 9.2 Response Pattern (Dust)

```php
class CreateStockTransferResponse extends Response
{
    protected function createResource(mixed $resource): mixed
    {
        return response()->json([
            'success' => true,
            'data' => new StockTransferResource($resource),
        ], SymfonyResponse::HTTP_CREATED);
    }

    protected function success(mixed $resource): void
    {
        // Fire LowStockDetected if stock is at or below threshold
        // This is handled here in the Presentation layer response
    }

    protected function handleErrorResponse(Throwable $e): false|JsonResponse
    {
        if ($e instanceof InsufficientStockException) {
            return response()->json([
                'message' => $e->getMessage(),
                'errors' => ['amount' => 'Insufficient stock in source warehouse.'],
            ], SymfonyResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        return parent::handleErrorResponse($e);
    }
}
```

### 9.3 Service Pattern

```php
class CreateStockTransferService
{
    public function __construct(
        protected WarehouseInventoryItemRepositoryInterface $stockRepo,
        protected StockTransferRepositoryInterface $transferRepo,
    ) {}

    public function handle(CreateStockTransferDTO $dto): StockTransfer
    {
        // 1. Validate stock availability
        // 2. Deduct from source warehouse
        // 3. Add to target warehouse
        // 4. Create transfer record
        // 5. Return transfer
    }
}
```

### 9.4 DTO Pattern

```php
class CreateStockTransferInputDTO
{
    public function __construct(
        public readonly int $inventoryId,
        public readonly int $baseWarehouseId,
        public readonly int $targetWarehouseId,
        public readonly int $amount,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            inventoryId: $request->input('inventory_id'),
            baseWarehouseId: $request->input('base_warehouse_id'),
            targetWarehouseId: $request->input('target_warehouse_id'),
            amount: $request->input('amount'),
        );
    }
}
```

### 9.5 Repository Contract Pattern

```php
// Domain/Contracts/Repositories/WarehouseRepositoryInterface.php
interface WarehouseRepositoryInterface
{
    public function findById(int $id): ?object;
    public function getAll(): Collection;
    public function create(array $data): object;
    public function update(int $id, array $data): object;
    public function delete(int $id): bool;
}

// Infrastructure/Repositories/WarehouseRepository.php
class WarehouseRepository extends Repository implements WarehouseRepositoryInterface
{
    public function __construct(Warehouse $model)
    {
        parent::__construct($model);
    }
    // Override or extend as needed
}
```

### 9.6 Observer Contract Pattern

```php
// Domain/Contracts/Observers/WarehouseInventoryItemObserverInterface.php
interface WarehouseInventoryItemObserverInterface
{
    public function updated(object $item): void;
    public function created(object $item): void;
    public function deleted(object $item): void;
}

// Infrastructure/Observers/WarehouseInventoryItemObserver.php
class WarehouseInventoryItemObserver implements WarehouseInventoryItemObserverInterface
{
    public function updated(object $item): void
    {
        $this->invalidateCache($item);
    }

    public function created(object $item): void
    {
        $this->invalidateCache($item);
    }

    public function deleted(object $item): void
    {
        $this->invalidateCache($item);
    }

    private function invalidateCache(object $item): void
    {
        Cache::forget("warehouse_{$item->warehouse_id}_inventory");
    }
}
```

---

## 10. Caching Strategy

### 10.1 Configuration

- **Driver**: File
- **TTL**: 60 minutes (standard)
- **Scope**: `GET /api/warehouses/{id}/inventory` endpoint only

### 10.2 Cache Key Pattern

```
warehouse_{id}_inventory
```

### 10.3 Invalidation

Handled by `WarehouseInventoryItemObserver` — any `created`, `updated`, or `deleted` event on the `WarehouseInventoryItem` model invalidates the cache for that warehouse.

Additionally, after a stock transfer, both the source and target warehouse caches are invalidated.

### 10.4 Implementation in Service

```php
class GetWarehouseInventoryService
{
    public function handle(int $warehouseId): mixed
    {
        return Cache::remember(
            "warehouse_{$warehouseId}_inventory",
            now()->addMinutes(60),
            fn () => $this->repository->getByWarehouseId($warehouseId)
        );
    }
}
```

---

## 11. Event System

### 11.1 LowStockDetected Flow

```
Stock Transfer Created
  → Stock deducted from source warehouse
  → Observer detects change (or Response::success() checks threshold)
  → If stock <= low_stock_threshold:
      → Dispatch LowStockDetected event (Infrastructure/Events)
      → SendLowStockNotification listener (Infrastructure/Listeners):
          1. Find subscribed users for that warehouse
          2. Create notification record in `notifications` table
          3. Create notification_receiver records for each subscribed user
          4. Log the notification (no actual email sent)
```

### 11.2 Event Contract

```php
// Domain/Contracts/Events/LowStockDetectedInterface.php
interface LowStockDetectedInterface
{
    public function getWarehouseId(): int;
    public function getInventoryItemId(): int;
    public function getCurrentStock(): int;
    public function getThreshold(): int;
}
```

### 11.3 Listener Contract

```php
// Domain/Contracts/Listeners/SendLowStockNotificationListenerInterface.php
interface SendLowStockNotificationListenerInterface
{
    public function handle(object $event): void;
}
```

---

## 12. Testing Strategy

### 12.1 Test Coverage

#### Auth Module Tests

| Test                             | Type    | Description                                |
|----------------------------------|---------|--------------------------------------------|
| `RegisterTest`                   | Feature | Successful registration, validation errors |
| `LoginTest`                      | Feature | Successful login, invalid credentials      |
| `LogoutTest`                     | Feature | Token revocation                           |
| `GetProfileTest`                 | Feature | Authenticated user profile                 |

#### Warehouse Module Tests

| Test                                    | Type    | Description                                          |
|-----------------------------------------|---------|------------------------------------------------------|
| `WarehouseCrudTest`                     | Feature | Full CRUD on warehouses with permission checks       |
| `InventoryItemCrudTest`                 | Feature | Full CRUD on inventory items with validation         |
| `GetAllInventoryTest`                   | Feature | Paginated list with filters (name, SKU, price range, warehouse) |
| `GetWarehouseInventoryTest`             | Feature | Cached warehouse inventory retrieval                 |
| `CreateStockTransferTest`               | Feature | Successful transfer between warehouses               |
| `StockTransferInsufficientStockTest`    | Unit    | Over-transfer returns error                          |
| `StockTransferValidationTest`           | Feature | Validates warehouses/items exist, quantity available  |
| `InventoryFilterPipelineTest`           | Unit    | Each filter pipe applies correctly                   |
| `CacheInvalidationTest`                 | Feature | Cache cleared after stock change                     |

#### Notifications Module Tests

| Test                                    | Type    | Description                                          |
|-----------------------------------------|---------|------------------------------------------------------|
| `LowStockDetectedEventTest`            | Feature | Event fired when stock drops below threshold         |
| `LowStockNotificationListenerTest`     | Feature | Listener creates notification records for subscribers |
| `WarehouseSubscriptionTest`            | Feature | Subscribe/unsubscribe users, permission checks       |

#### Audit Module Tests

| Test                             | Type    | Description                                |
|----------------------------------|---------|--------------------------------------------|
| `ActivityLogTest`                | Feature | Actions logged, retrievable via endpoint   |

### 12.2 Test Conventions

- Use `RefreshDatabase` trait
- Use factories for test data
- Group tests: `@group auth`, `@group warehouse`, `@group notifications`, `@group audit`
- Feature tests use `actingAs()` with Sanctum tokens
- Assert proper HTTP status codes, JSON structure, and database state

---

## 13. Seeders

### 13.1 Seeder Execution Order

1. `RolesAndPermissionsSeeder` — creates all roles and permissions
2. `NotificationChannelsSeeder` — seeds `email` channel
3. `UsersSeeder` — creates one user per role
4. `WarehousesSeeder` — sample warehouses (3-5)
5. `InventoryItemsSeeder` — sample inventory items (10-15)
6. `WarehouseInventoryItemsSeeder` — stock entries linking items to warehouses
7. `WarehouseNotificationSubscriptionsSeeder` — subscribe admin to all warehouses

### 13.2 Sample Data

**Warehouses**: Main Warehouse (Cairo), North Branch (Alexandria), South Branch (Aswan)

**Inventory Items**: Various products with different SKUs and prices

**Stock**: Distributed across warehouses with varying stock levels and thresholds

---

## 14. Installation Command

### `php artisan daftara:install`

```
daftara:install [--seed]
```

**Steps executed:**

1. Copy `.env.example` to `.env` (if `.env` doesn't exist)
2. `php artisan key:generate`
3. `php artisan migrate`
4. `php artisan db:seed --class=RolesAndPermissionsSeeder`
5. `php artisan db:seed --class=NotificationChannelsSeeder`
6. `php artisan db:seed --class=UsersSeeder`
7. If `--seed` flag:
   - `php artisan db:seed --class=WarehousesSeeder`
   - `php artisan db:seed --class=InventoryItemsSeeder`
   - `php artisan db:seed --class=WarehouseInventoryItemsSeeder`
   - `php artisan db:seed --class=WarehouseNotificationSubscriptionsSeeder`
8. `php artisan config:clear`
9. `php artisan cache:clear`
10. `php artisan route:clear`
11. `php artisan scribe:generate`
12. Output success message with seeded user credentials

---

## 15. API Documentation

### 15.1 Scribe (Auto-generated)

- Package: `knuckleswtf/scribe`
- Generate: `php artisan scribe:generate`
- Output: `/docs` endpoint serves interactive HTML documentation
- Automatically picks up: routes, FormRequest rules, response structures, route parameters
- Group endpoints by module using `@group` docblock annotations on controllers

### 15.2 README.md

The project README will include:
- Project description
- Requirements (PHP 8.2+, MySQL 8.0, Composer)
- Installation steps (`daftara:install`)
- API overview with endpoint summary
- Authentication guide (how to get a token)
- Running tests (`php artisan test`)
- Seeded user credentials

---

## 16. Implementation Order

### Phase 1: Project Setup
1. Fresh Laravel 12 project
2. Install packages: `cyberbugz/dust`, `spatie/laravel-permission`, `spatie/laravel-activitylog`, `laravel/sanctum`, `knuckleswtf/scribe`
3. Configure Dust: modules path, guards, attribute-based routing
4. Configure Sanctum for token-based auth
5. Configure spatie packages

### Phase 2: Auth Module
1. Create module structure (all 4 layers)
2. Migrations (users, personal_access_tokens)
3. Domain layer (entities, contracts)
4. Infrastructure layer (models, repositories, providers)
5. Application layer (services)
6. Presentation layer (controllers, requests, responses, resources, DTOs)
7. Seeders (roles, permissions, users)
8. Tests

### Phase 3: Warehouse Module
1. Create module structure
2. Migrations (warehouses, inventory_items, warehouse_inventory_items, stock_transfers)
3. Domain layer (entities, contracts for repos/observers, enums, exceptions)
4. Infrastructure layer (models, repositories, observers, providers, factories)
5. Application layer (services, pipelines, DTOs)
6. Presentation layer (controllers, requests, responses, resources, DTOs)
7. Caching implementation
8. Seeders
9. Tests

### Phase 4: Notifications Module
1. Create module structure
2. Migrations (notification_channels, notifications, notification_receivers, warehouse_notification_subscriptions)
3. Domain layer (entities, contracts for events/listeners/repos)
4. Infrastructure layer (models, repositories, events, listeners, providers)
5. Application layer (services)
6. Presentation layer (controllers, requests, responses, resources, DTOs)
7. Wire LowStockDetected event from Warehouse module's stock transfer response
8. Seeders
9. Tests

### Phase 5: Audit Module
1. Create module structure
2. Configure spatie/activitylog across all modules
3. Add `LogsActivity` trait to all Eloquent models
4. Domain layer (contracts)
5. Infrastructure layer (repository, provider)
6. Application layer (service)
7. Presentation layer (controller, request, response, resource)
8. Tests

### Phase 6: Finalization
1. Create `daftara:install` Artisan command
2. Generate Scribe documentation
3. Write README.md
4. Final test run: `php artisan test`
5. Code cleanup and review
