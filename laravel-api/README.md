# 🚀 Inventario Pro — API REST (Backend Laravel 12)

API RESTful robusta y escalable construida con **Laravel 12**, implementando arquitectura limpia (Clean Architecture), auditoría con **Spatie Activity Log**, observabilidad con **Laravel Telescope**, documentación interactiva con **OpenAPI / Swagger**, y un estándar uniforme de respuestas JSON.

---

## 📋 Tabla de Contenidos

- [Características Principales](#-características-principales)
- [Arquitectura Limpia (Clean Architecture)](#-arquitectura-limpia)
- [Requisitos Previos](#-requisitos-previos)
- [Instalación y Configuración](#-instalación-y-configuración)
- [Configuración de Base de Datos (MySQL & SQLite)](#-configuración-de-base-de-datos)
- [Migraciones y Seeders](#-migraciones-y-seeders)
- [Documentación Interactiva (Swagger / OpenAPI)](#-documentación-interactiva-swagger--openapi)
- [Observabilidad (Laravel Telescope)](#-observabilidad-laravel-telescope)
- [Auditoría (Spatie Activity Log)](#-auditoría-spatie-activity-log)
- [Formato de Respuestas y Manejo de Errores](#-formato-de-respuestas-y-manejo-de-errores)
- [Endpoints de la API](#-endpoints-de-la-api)
- [Pruebas Automatizadas (PHPUnit)](#-pruebas-automatizadas)

---

## ✨ Características Principales

- **Framework**: Laravel 12.x sobre PHP 8.2+
- **Patrón de Arquitectura**: Clean Architecture (Controllers ➔ FormRequests ➔ Services ➔ Eloquent Models / Resources)
- **Base de Datos**: MySQL (compatible out-of-the-box también con SQLite para entorno local o CI)
- **Documentación API**: OpenAPI 3.0 generado con L5-Swagger con UI interactiva
- **Observabilidad**: Laravel Telescope integrado para inspección de queries, requests, excepciones, logs y modelos
- **Auditoría**: Spatie Activity Log para rastreo automático de cambios y eventos en categorías y productos
- **Validaciones Rigurosas**: Form Requests con mensajes de validación contextuales y claros en español
- **Respuestas Uniformes**: Trait `ApiResponse` con estándar JSON consistente para éxitos y fallos

---

## 🏛 Arquitectura Limpia

La arquitectura sigue una separación estricta de responsabilidades:

```
laravel-api/
  ├── app/
  │   ├── Http/
  │   │   ├── Controllers/
  │   │   │   ├── Controller.php               # Info OpenAPI global
  │   │   │   └── Api/v1/
  │   │   │       ├── CategoryController.php   # Controlador CRUD de Categorías
  │   │   │       ├── ProductController.php    # Controlador CRUD de Productos
  │   │   │       └── DashboardController.php  # Controlador de Estadísticas
  │   │   ├── Requests/
  │   │   │   ├── Category/
  │   │   │   │   ├── StoreCategoryRequest.php
  │   │   │   │   └── UpdateCategoryRequest.php
  │   │   │   └── Product/
  │   │   │       ├── StoreProductRequest.php
  │   │   │       └── UpdateProductRequest.php
  │   │   └── Resources/
  │   │       ├── CategoryResource.php         # Transformación de datos
  │   │       └── ProductResource.php
  │   ├── Models/
  │   │   ├── Category.php                     # Eloquent Model + Activity Log
  │   │   └── Product.php                      # Eloquent Model + Activity Log
  │   ├── Services/
  │   │   ├── CategoryService.php              # Lógica de negocio y transacciones
  │   │   ├── ProductService.php               # Lógica de negocio, filtros y búsquedas
  │   │   └── DashboardService.php             # Métricas agregadas de inventario
  │   └── Traits/
  │       └── ApiResponse.php                  # Formato unificado de respuesta JSON
  ├── database/
  │   ├── migrations/                          # Migraciones de esquema
  │   └── seeders/                             # Datos de prueba iniciales
  ├── routes/
  │   └── api.php                              # Rutas versión 1 (/api/v1/...)
  └── tests/
      └── Feature/                             # Suite completa de pruebas funcionales
```

---

## ⚙️ Requisitos Previos

- **PHP**: >= 8.2 (extensiones: `pdo`, `pdo_mysql`, `pdo_sqlite`, `curl`, `mbstring`, `openssl`)
- **Composer**: >= 2.x
- **MySQL**: >= 8.0 (opcionalmente SQLite para entorno ligero)

---

## 🛠 Instalación y Configuración

1. **Clonar o ubicarse en la carpeta del backend**:
   ```bash
   cd laravel-api
   ```

2. **Instalar dependencias de Composer**:
   ```bash
   composer install
   ```

3. **Configurar archivo de entorno**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

---

## 🗄 Configuración de Base de Datos

### Opción A: MySQL (Requerimiento de Producción)
En el archivo `.env`:
```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=inventario_db
DB_USERNAME=root
DB_PASSWORD=tu_password
```

Crea la base de datos en MySQL:
```sql
CREATE DATABASE inventario_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Opción B: SQLite (Desarrollo Rápido Local)
```ini
DB_CONNECTION=sqlite
```
*(El archivo `database/database.sqlite` se crea automáticamente).*

---

## 🔄 Migraciones y Seeders

Ejecuta las migraciones y seeders con los datos demostrativos (incluyendo el producto de ejemplo requerido: `"Camiseta Overskull"`):

```bash
php artisan migrate:fresh --seed
```

### Seeders Incluidos:
- **`CategorySeeder`**: Inserta categorías representativas (`Ropa Urbana`, `Calzado`, `Accesorios`, `Electrónica`).
- **`ProductSeeder`**: Inserta productos con stock, precios y descripciones realistas.

---

## 📖 Documentación Interactiva (Swagger / OpenAPI)

La API cuenta con especificación OpenAPI 3.0 completa generada mediante anotaciones PHP 8.

1. **Generar la documentación**:
   ```bash
   php artisan l5-swagger:generate
   ```

2. **Acceder a la interfaz interactiva Swagger UI**:
   Inicia el servidor y navega a:
   👉 **`http://localhost:8000/api/documentation`**

Desde Swagger UI puedes probar todos los métodos HTTP (GET, POST, PUT, DELETE), ver los schemas de respuesta, códigos de estado HTTP y payloads requeridos.

---

## 🔍 Observabilidad (Laravel Telescope)

Se encuentra integrado **Laravel Telescope** para auditoría y observabilidad en tiempo real:

👉 **`http://localhost:8000/telescope`**

Telescope registra automáticamente:
- **Queries**: Tiempo de ejecución y sentencias SQL ejecutadas.
- **Requests**: Peticiones entrantes, headers, payload, código HTTP y tiempo de respuesta.
- **Models**: Eventos de creación, actualización y eliminación.
- **Exceptions**: Stacktrace completo de excepciones no controladas.
- **Logs**: Registros de actividad del sistema.

---

## 🛡 Auditoría (Spatie Activity Log)

Tanto el modelo `Product` como `Category` implementan el trait `Spatie\Activitylog\Traits\LogsActivity`.

- Cada creación, actualización o borrado se audita automáticamente en la tabla `activity_log`.
- Se registran los atributos antes y después de cada cambio (`logOnlyDirty`).
- Es posible consultar la auditoría programáticamente mediante la relación `$model->activities`.

---

## 📦 Formato de Respuestas y Manejo de Errores

Todas las respuestas de la API siguen un formato JSON homogéneo:

### Respuesta Exitosa (200 / 201)
```json
{
  "success": true,
  "message": "Operación exitosa",
  "data": { ... },
  "code": 200
}
```

### Respuesta de Error de Validación (422)
```json
{
  "success": false,
  "message": "Los datos proporcionados no son válidos.",
  "errors": {
    "name": [
      "El nombre del producto es obligatorio."
    ],
    "price": [
      "El precio debe ser un número válido."
    ]
  },
  "code": 422
}
```

### Respuesta de Recurso No Encontrado (404)
```json
{
  "success": false,
  "message": "El recurso solicitado no fue encontrado.",
  "code": 404
}
```

---

## 📡 Endpoints de la API

Base URL: `/api/v1`

| Método | Endpoint | Descripción | Body Requerido |
| :--- | :--- | :--- | :--- |
| `GET` | `/dashboard/stats` | Métricas y estadísticas agregadas | Ninguno |
| `GET` | `/categories` | Listar todas las categorías | Ninguno |
| `POST` | `/categories` | Crear una nueva categoría | `{ name, description? }` |
| `GET` | `/categories/{id}` | Obtener detalle de una categoría | Ninguno |
| `PUT` | `/categories/{id}` | Actualizar una categoría | `{ name, description? }` |
| `DELETE`| `/categories/{id}` | Eliminar una categoría | Ninguno |
| `GET` | `/products` | Listar productos (filtros: `search`, `category_id`) | Query params opcionales |
| `POST` | `/products` | Crear un nuevo producto | `{ name, description?, price, stock, category_id }` |
| `GET` | `/products/{id}` | Obtener detalle de un producto | Ninguno |
| `PUT` | `/products/{id}` | Actualizar un producto existente | `{ name, description?, price, stock, category_id }` |
| `DELETE`| `/products/{id}` | Eliminar un producto | Ninguno |

### Ejemplo de Payload para Crear Producto:
```json
{
  "name": "Camiseta Overskull",
  "description": "Camiseta negra edición limitada",
  "price": 89.90,
  "stock": 50,
  "category_id": 1
}
```

---

## 🧪 Pruebas Automatizadas

La aplicación cuenta con una suite completa de pruebas funcionales (Feature Tests) que cubren el 100% de los flujos requeridos:

```bash
php artisan test
```

### Casos de Prueba Incluidos:
- ✅ `Tests\Feature\CategoryApiTest`: Listado, creación, actualización, eliminación y validaciones de unicidad.
- ✅ `Tests\Feature\ProductApiTest`: Listado, creación con datos de ejemplo, validaciones de tipos/rangos, actualización, eliminación y filtros de búsqueda.
- ✅ `Tests\Feature\DashboardApiTest`: Cálculo exacto de inventario valorizado y conteo de productos/categorías.
- ✅ `Tests\Feature\AuditLogTest`: Verificación del guardado automático en `activity_log`.

---

## 🚀 Puesta en Marcha del Servidor

Para iniciar el servidor de desarrollo de Laravel:
```bash
php artisan serve --port=8000
```
API disponible en: `http://127.0.0.1:8000`
