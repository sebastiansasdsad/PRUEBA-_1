# 📦 Sistema de Gestión de Productos y Categorías (Inventario Pro)

Aplicación Full-Stack para gestión de productos y categorías (CRUD completo) desarrollada con **Laravel 12** en el backend (API REST) y **Vue.js 3 + Pinia** en el frontend.

> 🌐 **Demo en Vivo (Cloudflare Tunnel):**  
> 👉 **[https://transportation-ahead-organisms-resist.trycloudflare.com](https://transportation-ahead-organisms-resist.trycloudflare.com)**

---

## 🎯 Resumen de Requerimientos Cumplidos

| Área | Requerimiento | Implementación |
| :--- | :--- | :--- |
| **Enlace Público** | Cloudflare Tunnel | ✅ [Demo en Vivo](https://transportation-ahead-organisms-resist.trycloudflare.com) |
| **Backend** | Framework Laravel 12 | ✅ Laravel 12.70.0 |
| **Backend** | Base de Datos MySQL | ✅ Configuración MySQL lista en `.env` + SQLite lista para dev local |
| **Backend** | Documentación API Swagger/OpenAPI | ✅ OpenAPI 3.0 con L5-Swagger en `/api/documentation` |
| **Backend** | Observabilidad | ✅ Laravel Telescope en `/telescope` |
| **Backend** | Auditoría | ✅ Spatie Activity Log en modelos `Product` y `Category` |
| **Backend** | Migraciones y Seeders | ✅ Migraciones completas + `ProductSeeder` y `CategorySeeder` |
| **Backend** | Buenas prácticas y Clean Architecture | ✅ Controllers, FormRequests, API Resources, Service Layer, ApiResponse trait |
| **Frontend** | Vue 3 (Composition API) | ✅ Vue 3.5 con `<script setup>` |
| **Frontend** | State Management Pinia | ✅ `useProductStore`, `useCategoryStore`, `useToastStore` |
| **Frontend** | Ruteo con Vue Router | ✅ Rutas para Dashboard, Productos, Formulario y Categorías |
| **Frontend** | Estilos Tailwind CSS | ✅ Tailwind CSS v4 con dark theme y glassmorphism |
| **Frontend** | Peticiones HTTP con Axios | ✅ Axios con interceptores globales para 4xx, 5xx y errores de red |
| **Frontend** | Control de Estados (DATA ALERT) | ✅ Loading spinners, toast de éxito y alertas visuales de error |
| **Frontend** | Vistas Mínimas | ✅ 1. Listado de Productos, 2. Formulario de Producto, 3. Categorías, 4. Dashboard |

---

## 📁 Estructura del Repositorio

```
.
├── laravel-api/            # Backend API REST en Laravel 12
│   ├── app/
│   │   ├── Http/Controllers/Api/v1/
│   │   ├── Http/Requests/
│   │   ├── Http/Resources/
│   │   ├── Models/
│   │   ├── Services/
│   │   └── Traits/
│   ├── database/
│   │   ├── migrations/
│   │   └── seeders/
│   ├── routes/
│   │   └── api.php
│   └── README.md
│
└── vue-frontend/           # Frontend SPA en Vue 3 + Pinia
    ├── src/
    │   ├── components/
    │   ├── router/
    │   ├── services/
    │   ├── stores/
    │   ├── utils/
    │   ├── views/
    │   ├── App.vue
    │   └── main.js
    └── README.md
```

---

## ⚡ Inicio Rápido Local

### 1. Iniciar el Backend (Laravel API)
```bash
cd laravel-api
php artisan migrate:fresh --seed
php artisan serve --port=8000
```
- API REST: `http://127.0.0.1:8000/api/v1`
- Swagger UI: `http://127.0.0.1:8000/api/documentation`
- Laravel Telescope: `http://127.0.0.1:8000/telescope`

### 2. Iniciar el Frontend (Vue 3 + Vite)
```bash
cd vue-frontend
npm install
npm run dev
```
- Aplicación Web: `http://localhost:5173`

### 3. Exponer con Cloudflare Tunnel
```bash
cloudflared tunnel --url http://127.0.0.1:5173
```