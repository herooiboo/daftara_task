# API Documentation

Base URL: `http://localhost:8000/api/v1`

> Interactive Swagger/OpenAPI documentation is also available at `/docs/api` when the server is running.

## Authentication

The API uses **Laravel Sanctum** bearer tokens.

### Getting a Token

1. **Register** or **Login** to get a token
2. Include the token in all subsequent requests:
   ```
   Authorization: Bearer {your-token}
   ```

### Response Format

All responses follow this structure:

**Success:**
```json
{
  "success": true,
  "data": { ... }
}
```

**Error:**
```json
{
  "message": "Error description",
  "errors": {
    "field": ["Validation error message"]
  }
}
```

---

## Auth

### POST /api/v1/auth/register

Register a new user account. The user is automatically assigned the `staff` role.

**Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Validation:**
| Field | Rules |
|-------|-------|
| name | required, string, max:255 |
| email | required, email, unique:users, max:255 |
| password | required, min:8, confirmed |

**Response (201):**
```json
{
  "success": true,
  "data": {
    "token": "1|abc123...",
    "user": {
      "id": 4,
      "name": "John Doe",
      "email": "john@example.com"
    }
  }
}
```

---

### POST /api/v1/auth/login

Authenticate and receive an API token.

**Body:**
```json
{
  "email": "superadmin@daftara.com",
  "password": "password"
}
```

**Validation:**
| Field | Rules |
|-------|-------|
| email | required, email |
| password | required, string |

**Response (200):**
```json
{
  "success": true,
  "data": {
    "token": "1|abc123...",
    "user": {
      "id": 1,
      "name": "Super Admin",
      "email": "superadmin@daftara.com"
    }
  }
}
```

**Error (401):** Invalid credentials.

---

### POST /api/v1/auth/logout

Revoke the current API token. **Requires authentication.**

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "success": true,
  "data": {
    "message": "Logged out successfully."
  }
}
```

---

### GET /api/v1/auth/me

Get the authenticated user's profile. **Requires authentication.**

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Super Admin",
    "email": "superadmin@daftara.com",
    "roles": ["superadmin"],
    "permissions": ["view-warehouses", "create-warehouses", ...]
  }
}
```

---

## Warehouses

All warehouse endpoints require `auth:sanctum`.

### GET /api/v1/warehouse/warehouses

List all warehouses. **Permission:** `view-warehouses`

**Query Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| name | string | Filter by name (partial match) |

**Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Main Warehouse",
      "location": "Cairo",
      "created_at": "2026-02-08T00:00:00.000000Z",
      "updated_at": "2026-02-08T00:00:00.000000Z"
    }
  ]
}
```

---

### POST /api/v1/warehouse/warehouses

Create a new warehouse. **Permission:** `create-warehouses`

**Body:**
```json
{
  "name": "New Warehouse",
  "location": "Alexandria"
}
```

**Validation:**
| Field | Rules |
|-------|-------|
| name | required, string, max:255 |
| location | nullable, string, max:255 |

**Response (201):**
```json
{
  "success": true,
  "data": {
    "id": 4,
    "name": "New Warehouse",
    "location": "Alexandria",
    "created_at": "2026-02-08T00:00:00.000000Z",
    "updated_at": "2026-02-08T00:00:00.000000Z"
  }
}
```

---

### GET /api/v1/warehouse/warehouses/{id}

Get a single warehouse. **Permission:** `view-warehouses`

**Response (200):** Single warehouse object.

---

### PUT /api/v1/warehouse/warehouses/{id}

Update a warehouse. **Permission:** `update-warehouses`

**Body:**
```json
{
  "name": "Updated Name",
  "location": "Updated Location"
}
```

**Response (200):** Updated warehouse object.

---

### DELETE /api/v1/warehouse/warehouses/{id}

Delete a warehouse. **Permission:** `delete-warehouses`

**Response (200):**
```json
{
  "success": true,
  "data": {
    "message": "Warehouse deleted successfully."
  }
}
```

---

## Inventory Items

All inventory item endpoints require `auth:sanctum`.

### GET /api/v1/warehouse/inventory-items

List all inventory items. **Permission:** `view-inventory-items`

**Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Laptop",
      "SKU": "LAP-001",
      "price": 999.99,
      "description": "High-performance laptop",
      "created_at": "2026-02-08T00:00:00.000000Z",
      "updated_at": "2026-02-08T00:00:00.000000Z"
    }
  ]
}
```

---

### POST /api/v1/warehouse/inventory-items

Create a new inventory item. **Permission:** `create-inventory-items`

**Body:**
```json
{
  "name": "Keyboard",
  "SKU": "KBD-001",
  "price": 49.99,
  "description": "Mechanical keyboard"
}
```

**Validation:**
| Field | Rules |
|-------|-------|
| name | required, string, max:255 |
| SKU | required, string, unique:inventory_items |
| price | required, numeric, min:0 |
| description | nullable, string |

**Response (201):** Created inventory item object.

---

### GET /api/v1/warehouse/inventory-items/{id}

Get a single inventory item. **Permission:** `view-inventory-items`

---

### PUT /api/v1/warehouse/inventory-items/{id}

Update an inventory item. **Permission:** `update-inventory-items`

---

### DELETE /api/v1/warehouse/inventory-items/{id}

Delete an inventory item. **Permission:** `delete-inventory-items`

---

## Inventory (Stock)

### GET /api/v1/warehouse/inventory

Get all stock levels across all warehouses. Paginated and filterable. **Permission:** `view-inventory`

**Query Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| warehouse_id | integer | Filter by warehouse |
| name | string | Filter by item name (partial match) |
| sku | string | Filter by SKU (partial match) |
| price_min | numeric | Minimum price filter |
| price_max | numeric | Maximum price filter |
| page | integer | Page number (default: 1) |

**Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "warehouse_id": 1,
      "inventory_item_id": 1,
      "stock": 150,
      "low_stock_threshold": 20,
      "inventory_item": {
        "id": 1,
        "name": "Laptop",
        "SKU": "LAP-001",
        "price": 999.99
      }
    }
  ]
}
```

---

### GET /api/v1/warehouse/warehouses/{id}/inventory

Get inventory for a specific warehouse. Cached for 60 minutes. **Permission:** `view-inventory`

Cache is automatically invalidated when stock levels change.

**Response (200):** Array of warehouse inventory items with item details.

---

## Stock Transfers

### GET /api/v1/warehouse/stock-transfers

List all stock transfers with optional filters. **Permission:** `view-stock-transfers`

**Query Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| inventory_id | integer | Filter by inventory item ID |
| base_warehouse_id | integer | Filter by source warehouse ID |
| target_warehouse_id | integer | Filter by target warehouse ID |
| created_by | integer | Filter by user who created the transfer |
| date_from | date | Filter from date (Y-m-d) |
| date_to | date | Filter to date (Y-m-d) |
| page | integer | Page number (default: 1) |

**Response (200):** Paginated list of stock transfers.

---

### POST /api/v1/warehouse/stock-transfers

Transfer stock from one warehouse to another. **Permission:** `create-stock-transfers`

This operation is atomic (all-or-nothing within a database transaction). If the resulting stock drops below the configured low-stock threshold, a `LowStockDetected` event is dispatched and email notifications are sent to subscribed users.

**Body:**
```json
{
  "inventory_id": 1,
  "base_warehouse_id": 1,
  "target_warehouse_id": 2,
  "amount": 50
}
```

**Validation:**
| Field | Rules |
|-------|-------|
| inventory_id | required, integer, exists:inventory_items,id |
| base_warehouse_id | required, integer, exists:warehouses,id, different:target_warehouse_id |
| target_warehouse_id | required, integer, exists:warehouses,id |
| amount | required, numeric, min:0.01 |

**Response (201):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "inventory_id": 1,
    "base_warehouse_id": 1,
    "target_warehouse_id": 2,
    "amount": 50,
    "created_by": 1,
    "created_at": "2026-02-08T00:00:00.000000Z"
  }
}
```

> **Note:** The response uses `StockTransferEntityResource` which returns domain entity data (no nested relationships). For full details with relationships, use the GET endpoint.

**Error (422):** Insufficient stock in source warehouse.

---

## Warehouse Subscriptions

Manage which users receive low-stock email notifications for a warehouse. All endpoints require `manage-warehouse-subscriptions` permission.

### GET /api/v1/notifications/warehouses/{id}/notification-subscribers

List all users subscribed to a warehouse's notifications.

**Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "user_id": 1,
      "warehouse_id": 1,
      "user": {
        "id": 1,
        "name": "Super Admin",
        "email": "superadmin@daftara.com"
      },
      "created_at": "2026-02-08T00:00:00.000000Z"
    }
  ]
}
```

---

### POST /api/v1/notifications/warehouses/{id}/notification-subscribers

Subscribe multiple users to a warehouse's low-stock notifications.

**Body:**
```json
{
  "user_ids": [2, 3, 4]
}
```

**Validation:**
| Field | Rules |
|-------|-------|
| user_ids | required, array, min:1 |
| user_ids.* | required, integer, exists:users,id |

**Response (201):**
```json
{
  "success": true,
  "data": {
    "subscribed_count": 3,
    "subscriptions": [
      {
        "id": 1,
        "user_id": 2,
        "warehouse_id": 1
      },
      {
        "id": 2,
        "user_id": 3,
        "warehouse_id": 1
      }
    ]
  }
}
```

**Error (409):** One or more users are already subscribed.

---

### DELETE /api/v1/notifications/warehouses/{id}/notification-subscribers

Unsubscribe multiple users from a warehouse's notifications.

**Body:**
```json
{
  "user_ids": [2, 3]
}
```

**Validation:**
| Field | Rules |
|-------|-------|
| user_ids | required, array, min:1 |
| user_ids.* | required, integer, exists:users,id |

**Response (200):**
```json
{
  "success": true,
  "data": {
    "unsubscribed_count": 2,
    "message": "Successfully unsubscribed 2 user(s) from warehouse notifications."
  }
}
```

---

## Audit (Activity Logs)

### GET /api/v1/audit/activity-logs

Retrieve activity logs with optional filters. **Permission:** `view-activity-logs`

All model changes (create, update, delete) are automatically logged for: User, Warehouse, InventoryItem, WarehouseInventoryItem, StockTransfer.

**Query Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| subject_type | string | Filter by model class (e.g. `Warehouse`, `InventoryItem`) |
| causer_id | integer | Filter by user who performed the action |
| event | string | Filter by event type: `created`, `updated`, `deleted` |
| date_from | date | Filter from date (Y-m-d) |
| date_to | date | Filter to date (Y-m-d) |

**Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "log_name": "default",
      "description": "updated",
      "subject_type": "App\\Modules\\Warehouse\\Infrastructure\\Models\\Warehouse",
      "subject_id": 1,
      "event": "updated",
      "causer_type": "App\\Modules\\Auth\\Infrastructure\\Models\\User",
      "causer_id": 1,
      "properties": {
        "old": { "name": "Old Name" },
        "attributes": { "name": "New Name" }
      },
      "created_at": "2026-02-08T00:00:00.000000Z"
    }
  ]
}
```

---

## Roles & Permissions

### Roles

| Role | Description |
|------|-------------|
| superadmin | Full access to all endpoints |
| manager | Manage warehouses, inventory, transfers; view logs |
| staff | View inventory, create transfers only |

### Permissions Matrix

| Permission | superadmin | manager | staff |
|------------|:---:|:---:|:---:|
| view-warehouses | x | x | x |
| create-warehouses | x | x | |
| update-warehouses | x | x | |
| delete-warehouses | x | | |
| view-inventory-items | x | x | x |
| create-inventory-items | x | x | |
| update-inventory-items | x | x | |
| delete-inventory-items | x | | |
| view-inventory | x | x | x |
| create-stock-transfers | x | x | x |
| manage-warehouse-subscriptions | x | | |
| view-activity-logs | x | x | |

---

## Error Codes

| HTTP Status | Meaning |
|-------------|---------|
| 200 | Success |
| 201 | Created |
| 401 | Unauthenticated (missing or invalid token) |
| 403 | Forbidden (insufficient permissions) |
| 404 | Resource not found |
| 409 | Conflict (e.g. duplicate subscription) |
| 422 | Validation error or business rule violation |
| 500 | Server error |
