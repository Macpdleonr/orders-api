# Orders-API

![PHP](https://img.shields.io/badge/PHP-8.2.12-8892BF)
![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20)
![License](https://img.shields.io/badge/License-MIT-green)
![Status](https://img.shields.io/badge/status-active-success)

> 🇪🇸 **¿Prefieres español?** [Lee este README en español 🇪🇸](./README.es.md)

---
## 🔹 Description

This is a REST API developed in Laravel for managing Orders and Payments. The API allows you to:

- Create orders with customer name, total amount, and initial status `pending`.

- Record payments associated with an existing order.

- Connect to a mock external API to confirm transactions.

- Update the order status based on the payment outcome (`paid` or `failed`).

- Allow payment retries for failed orders.

- List orders with their current status, payment attempts made, and associated payments.

---

## 🚀 Installation

### 1. Clone the repository:

```bash
git clone https://github.com/Macpdleonr/orders-api.git
cd orders-api
```

### 2. Install dependencies:

```bash
composer install
```

### 3. Configure environment variables:

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Run migrations:

```bash
php artisan migrate
```

### 5. Start the server:

```bash
php artisan serve
```

---

## 📂 Structure

```bash
ORDERS-API/
├─ app/
│  ├─ Http/Controllers      # Main API Controllers:
│  │                          - OrderController: manages orders
│  │                          - PaymentController: manages payments
│  ├─ Models                # Data Models:
│  │                          - Order, Payment
│  └─ Services              # Additional services for business logic
│                             - PaymentService
├─ routes/
│  └─ api.php               # Definition of API endpoints
├─ database/
│  ├─ migrations            # Migrations to create tables
│  └─ database.sqlite       # SQLite database for development/testing
├─ tests/
│  └─ Feature               # Functional API Tests
│                             - CreateOrderTest, PaymentProcessTest
├─ .env.example             # Environment Variable Template
├─ composer.json            # Project Dependencies
└─ README.md                # Project Documentation
```

## 🌐 API Endpoints (v1)

This section describes the endpoints available for managing **Orders** and **Payments**.

---

### 🛍️ Orders

| Operation | Method | Route | Description |
| :--- | :--- | :--- | :--- |
| **List** orders | `GET` | `/api/v1/orders` | Gets a list of all orders. |
| **Create** order | `POST` | `/api/v1/orders` | Creates a new order. |
| **View details** | `GET` | `/api/v1/orders/{order_id}` | Displays the details of an order, including its associated payments. |

#### **List orders** (`GET /api/v1/orders`)

**Answer (200 OK):**

```json
[ 
    { 
        "id": 1, 
        "name": "Jose Test", 
        "amount": 130.45, 
        "status": "pending", 
        "payments": [] 
    }
]
```

#### **Create order** (`POST /api/v1/orders`)

**Request Body:**

```json
{ 
    "name": "Jose Test", 
    "amount": 130.45
}
```

**Response (201 Created):**

```json
{ 
    "data": { 
        "id": 1, 
        "name": "Jose Test", 
        "amount": 130.45, 
        "status": "pending", 
        "payments": []
    }
}
```

---

## ⚙️ Payment Models and Processing

### 🛑 Payment Processing Notes

* Connects to a mock external API using the `PaymentService`.
* If the payment is **successful**, the order status changes to `paid`.
* If the payment **fails**, the order status changes to `failed` and can be retried.

### 🗃️ Model and Relationship Structure

| Model | Key Fields | Relationships | Relevant Methods |
| :--- | :--- | :--- | :--- |
| **Order** | `id`, `name`, `amount`, `status` | `hasMany(Payment)` | `markAsPaid()`, `markAsFailed()` |
| **Payment** | `id`, `order_id`, `amount`, `success`, `external_transaction_id`, `response_payload` | `belongsTo(Order)` | - |

### ✅ Validations (Requests)

The following validation is applied to requests:

* **`OrderRequest`**:
    * `name`: **Required**, `string`, maximum 255 characters.
    * `amount`: **Required**, `numeric`, minimum 1.
* **`PaymentRequest`**:
    * **No additional validation required** (the amount is taken from the order).

---

## 🧪 Automated Tests

**Feature Tests** have been implemented to ensure the stability and correct flow of transactions.

* **`CreateOrderTest`**:
    * Verifies the successful creation of orders.
* **`PaymentProcessTest`**:
    * Verifies the **successful payment** flow and the update of the order status to `paid`.
    * Verify the **failed payment** flow, the status change to `failed`, and the ability to **successfully retry**.

To run all the tests, use the following command:

```bash
php artisan test
```
