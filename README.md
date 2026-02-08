# Daftara - Inventory Management API

A RESTful API for managing inventory across multiple warehouses, built with **Laravel 12** following **Domain-Driven Design (DDD)** architecture using the `cyberbugz/dust` modular framework.

## Key Features

- **Warehouse Management** - CRUD operations for warehouses
- **Inventory Items** - Manage products with SKU, pricing, and descriptions
- **Stock Tracking** - Track stock levels per warehouse with low-stock thresholds
- **Stock Transfers** - Transfer stock between warehouses with validation and atomic transactions
- **Low-Stock Email Notifications** - Automated email alerts when stock drops below thresholds (via Laravel Notifications)
- **Warehouse Subscriptions** - Users subscribe to warehouses to receive low-stock alerts
- **Activity Audit** - Automatic activity logging on all model changes with filterable API
- **Role-Based Access Control** - 3 roles (superadmin, manager, staff) with 12 granular permissions
- **API Authentication** - Laravel Sanctum token-based authentication
- **Caching** - Warehouse inventory cached for 60 minutes, auto-invalidated on changes
- **API Documentation** - Auto-generated Swagger/OpenAPI docs via Scribe

## Tech Stack

| Component | Technology |
|-----------|-----------|
| Framework | Laravel 12 |
| Architecture | DDD with `cyberbugz/dust` modular framework |
| Authentication | Laravel Sanctum |
| Authorization | Spatie Laravel Permission |
| Activity Logging | Spatie Laravel Activity Log |
| Email Notifications | Laravel Notifications (mail channel) |
| API Documentation | Scramble (Swagger/OpenAPI auto-generated) |
| Database | MySQL 8.0 |
| Testing | PHPUnit 11 |

## Quick Start

```bash
# Clone and install
git clone <repository-url>
cd daftara_task
composer install

# Install (creates .env, runs migrations, seeds roles/users)
php artisan daftara:install --seed

# Start the server
php artisan serve
```

After installation, 3 demo users are created:

| User | Email | Password | Role |
|------|-------|----------|------|
| Super Admin | superadmin@daftara.com | password | superadmin |
| Warehouse Manager | manager@daftara.com | password | manager |
| Staff Member | staff@daftara.com | password | staff |

## API Endpoints (21 total)

| Group | Method | URI | Permission |
|-------|--------|-----|------------|
| **Auth** | POST | `/api/register` | Public |
| | POST | `/api/login` | Public |
| | POST | `/api/logout` | Authenticated |
| | GET | `/api/me` | Authenticated |
| **Warehouses** | GET | `/api/warehouses` | `view-warehouses` |
| | POST | `/api/warehouses` | `create-warehouses` |
| | GET | `/api/warehouses/{id}` | `view-warehouses` |
| | PUT | `/api/warehouses/{id}` | `update-warehouses` |
| | DELETE | `/api/warehouses/{id}` | `delete-warehouses` |
| **Inventory Items** | GET | `/api/inventory-items` | `view-inventory-items` |
| | POST | `/api/inventory-items` | `create-inventory-items` |
| | GET | `/api/inventory-items/{id}` | `view-inventory-items` |
| | PUT | `/api/inventory-items/{id}` | `update-inventory-items` |
| | DELETE | `/api/inventory-items/{id}` | `delete-inventory-items` |
| **Inventory** | GET | `/api/inventory` | `view-inventory` |
| | GET | `/api/warehouses/{id}/inventory` | `view-inventory` |
| **Stock Transfers** | POST | `/api/stock-transfers` | `create-stock-transfers` |
| **Subscriptions** | GET | `/api/warehouses/{id}/subscribers` | `manage-warehouse-subscriptions` |
| | POST | `/api/warehouses/{id}/subscribers` | `manage-warehouse-subscriptions` |
| | DELETE | `/api/warehouses/{id}/subscribers/{userId}` | `manage-warehouse-subscriptions` |
| **Audit** | GET | `/api/activity-logs` | `view-activity-logs` |

## API Documentation

Interactive Swagger/OpenAPI docs (powered by Scramble) are available at:

```
http://localhost:8000/docs/api
```

OpenAPI spec (JSON): `http://localhost:8000/docs/api.json`

## Architecture

The project follows **Domain-Driven Design** with 4 modules:

```
app/Modules/
├── Auth/           # Authentication, users, roles
├── Warehouse/      # Warehouses, inventory, stock transfers
├── Notifications/  # Email notifications, subscriptions
└── Audit/          # Activity logging
```

Each module has 4 layers:

```
Presentation → Application → Domain ← Infrastructure
```

- **Presentation** - Controllers, Requests, Responses, Resources
- **Application** - Services, Pipelines, DTOs
- **Domain** - Contracts (interfaces), Exceptions (no framework dependencies)
- **Infrastructure** - Models, Repositories, Events, Listeners, Migrations, Seeders

## Running Tests

```bash
# Full test suite
php artisan test

# By module
php artisan test --group=auth
php artisan test --group=warehouse
php artisan test --group=notifications
php artisan test --group=audit
```

## Documentation

- [Installation Guide](docs/installation-guide.md) - Detailed setup instructions
- [API Documentation](docs/api-documentation.md) - Comprehensive API reference

## License

[Your License Here]
