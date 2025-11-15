# Orders-API

![PHP](https://img.shields.io/badge/PHP-8.2.12-8892BF)
![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20)
![License](https://img.shields.io/badge/License-MIT-green)
![Status](https://img.shields.io/badge/status-active-success)

> 🇬🇧 **Prefer English?** [Read this README in English 🇬🇧](./README.md)

---
## 🔹 Descripción

Esta es una API REST desarrollada en Laravel para gestionar Pedidos (Orders) y Pagos (Payments). La API permite:

- Crear pedidos con nombre de cliente, monto total y estado inicial `pending`.

- Registrar pagos asociados a un pedido existente.

- Conectar con una API externa simulada para confirmar transacciones.

- Actualizar el estado del pedido según el resultado del pago (`paid` o `failed`).

- Permitir reintentos de pago en pedidos fallidos.

- Listar pedidos con su estado actual, intentos de pago realizados y pagos asociados.

---

## 🚀 Instalación

### 1. Clonar el repositorio:

```bash
git clone https://github.com/Macpdleonr/orders-api.git
cd orders-api
```

### 2. Instalar dependencias:

```bash
composer install
```

### 3. Configurar variables de entorno:

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Ejecutar migraciones:

```bash
php artisan migrate
```

### 5. Levantar el servidor:
```bash
php artisan serve
```

---

## 📂 Estructura

```bash
ORDERS-API/
├─ app/
│  ├─ Http/Controllers      # Controladores principales de la API:
│  │                          - OrderController: gestiona órdenes
│  │                          - PaymentController: gestiona pagos
│  ├─ Models                # Modelos de datos:
│  │                          - Order, Payment
│  └─ Services              # Servicios adicionales para lógica de negocio
│                             - PaymentService
├─ routes/
│  └─ api.php               # Definición de endpoints de la API
├─ database/
│  ├─ migrations            # Migraciones para crear tablas
│  └─ database.sqlite       # Base de datos SQLite para desarrollo/testing
├─ tests/
│  └─ Feature               # Tests funcionales de la API
│                             - CreateOrderTest, PaymentProcessTest
├─ .env.example             # Plantilla de variables de entorno
├─ composer.json            # Dependencias del proyecto
└─ README.md                # Documentación del proyecto
```

## 🌐 Endpoints de la API (v1)

Esta sección describe los endpoints disponibles para la gestión de **Pedidos** y **Pagos**.

---

### 🛍️ Pedidos (`Orders`)

| Operación | Método | Ruta | Descripción |
| :--- | :--- | :--- | :--- |
| **Listar** pedidos | `GET` | `/api/v1/orders` | Obtiene una lista de todos los pedidos. |
| **Crear** pedido | `POST` | `/api/v1/orders` | Crea un nuevo pedido. |
| **Ver detalle** | `GET` | `/api/v1/orders/{order_id}` | Muestra el detalle de un pedido, incluyendo sus pagos asociados. |

#### **Listar pedidos** (`GET /api/v1/orders`)

**Respuesta (200 OK):**
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

#### **Crear pedido** (`POST /api/v1/orders`)

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

### 💳 Pagos (`Payments`)

#### **Registrar pago** (`POST /api/v1/orders/{order_id}/payments`)

> 📝 **Nota:** Esta ruta no requiere un cuerpo de solicitud (`body`), ya que el monto se toma automáticamente del pedido creado.

**Response (201 Created):**

```json
{
  "data": {
    "id": 1,
    "amount": 130.45,
    "success": true,
    "external_transaction_id": "tx_123",
    "response_payload": {"id":"tx_123"}
  }
}
```

---

## ⚙️ Modelos y Procesamiento de Pagos

### 🛑 Notas sobre el Procesamiento de Pagos

* Se conecta a una **API externa simulada** mediante el servicio `PaymentService`.
* Si el pago es **exitoso** :white_check_mark:, el pedido cambia su estado a `paid`.
* Si el pago **falla** :x:, el pedido cambia a `failed` y permite ser reintentado.

### 🗃️ Estructura de Modelos y Relaciones

| Modelo | Campos Clave | Relaciones | Métodos Relevantes |
| :--- | :--- | :--- | :--- |
| **Order** | `id`, `name`, `amount`, `status` | `hasMany(Payment)` | `markAsPaid()`, `markAsFailed()` |
| **Payment** | `id`, `order_id`, `amount`, `success`, `external_transaction_id`, `response_payload` | `belongsTo(Order)` | - |

### ✅ Validaciones (Requests)

Se aplica la siguiente validación a las solicitudes:

* **`OrderRequest`**:
    * `name`: **Obligatorio**, `string`, máximo 255 caracteres.
    * `amount`: **Obligatorio**, `numérico`, mínimo 1.
* **`PaymentRequest`**:
    * **No requiere** validación adicional (el monto se toma del pedido).

---

## 🧪 Pruebas Automatizadas

Se han implementado **Feature Tests** para asegurar la estabilidad y el correcto flujo de las transacciones.

* **`CreateOrderTest`**:
    * Verifica la creación exitosa de pedidos.
* **`PaymentProcessTest`**:
    * Verifica el flujo de **pago exitoso** y la actualización del estado del pedido a `paid`.
    * Verifica el flujo de **pago fallido**, el cambio de estado a `failed` y la capacidad de **reintento exitoso**.

Para ejecutar todas las pruebas, usa el siguiente comando:

```bash
php artisan test
```
