# 🎨 Inventario Pro — Frontend (Vue 3 + Pinia + Tailwind CSS)

Aplicación Single Page Application (SPA) moderna, fluida y de alta estética visual construida con **Vue 3 (Composition API)**, gestión de estados centralizada con **Pinia**, ruteo con **Vue Router**, diseño dark-mode con **Tailwind CSS**, y cliente HTTP **Axios** con interceptores globales para manejo de errores.

---

## 📋 Tabla de Contenidos

- [Características Principales](#-características-principales)
- [Estructura del Proyecto](#-estructura-del-proyecto)
- [Vistas Implementadas](#-vistas-implementadas)
- [Gestión de Estados (Pinia)](#-gestión-de-estados-pinia)
- [Manejo Global de Solicitudes y Alertas (DATA ALERT)](#-manejo-global-de-solicitudes-y-alertas-data-alert)
- [Instalación y Puesta en Marcha](#-instalación-y-puesta-en-marcha)
- [Compilación para Producción](#-compilación-para-producción)

---

## ✨ Características Principales

- **Framework**: Vue 3.5+ utilizando Composition API con la sintaxis concisa y reactiva `<script setup>`.
- **State Management**: Pinia con stores modulares y tipados (`useProductStore`, `useCategoryStore`, `useToastStore`).
- **Ruteo**: Vue Router 4 con lazy loading de componentes y actualización dinámica del título de la página.
- **Estilos y Diseño**: Tailwind CSS v4 con variables CSS personalizadas, paleta moderna dark mode, bordes con sutil glow y efectos de micro-animación.
- **Cliente HTTP**: Axios con interceptor global configurado para capturar y notificar automáticamente errores de red (4xx, 5xx) y respuestas 422 de validación.
- **Componentes Reutilizables**:
  - `LoadingSpinner`: Indicador animado para estados de carga.
  - `ToastNotification`: Notificaciones emergentes con auto-dismiss y animación de salida.
  - `ConfirmModal`: Ventana modal accesible con Teleport para confirmaciones críticas (borrado de registros).
  - `AppSidebar`: Menú de navegación lateral responsive con soporte para pantallas móviles.

---

## 📁 Estructura del Proyecto

```
vue-frontend/
  ├── src/
  │   ├── components/
  │   │   ├── AppSidebar.vue           # Navegación lateral persistente
  │   │   ├── ConfirmModal.vue         # Modal genérico con Teleport
  │   │   ├── LoadingSpinner.vue       # Spinner animado para estados loading
  │   │   └── ToastNotification.vue    # Notificaciones flotantes
  │   ├── router/
  │   │   └── index.js                 # Rutas de la aplicación y meta titles
  │   ├── services/
  │   │   ├── api.js                   # Instancia Axios con interceptores globales
  │   │   ├── categoryService.js       # Endpoints de categorías
  │   │   ├── productService.js        # Endpoints de productos
  │   │   └── dashboardService.js      # Endpoint de métricas de dashboard
  │   ├── stores/
  │   │   ├── categories.js            # Store Pinia para CRUD de categorías
  │   │   ├── products.js              # Store Pinia para CRUD de productos
  │   │   └── toast.js                 # Store Pinia para alertas visuales
  │   ├── utils/
  │   │   └── formatters.js            # Formato de moneda (MXN), fechas y textos
  │   ├── views/
  │   │   ├── DashboardView.vue        # Dashboard principal con métricas
  │   │   ├── ProductsView.vue         # Listado, búsqueda y filtros de productos
  │   │   ├── ProductFormView.vue      # Formulario para Crear / Editar producto
  │   │   └── CategoriesView.vue       # Listado y modal para crear/editar categorías
  │   ├── App.vue                      # Layout raíz
  │   ├── main.js                      # Inicialización de Vue, Pinia y Router
  │   └── style.css                    # Tokens de diseño y estilos globales
  ├── index.html                       # HTML principal con SEO tags y fuente Inter
  ├── vite.config.js                   # Configuración de Vite con proxy hacia Laravel
  └── package.json
```

---

## 🖥 Vistas Implementadas

### 1. 📊 Dashboard Inicial (`/`)
- Muestra tarjetas de estadísticas con colores acentuados:
  - Total de productos registrados.
  - Total de categorías.
  - Unidades totales en stock.
  - Valor total del inventario calculado en tiempo real.
- Tabla con los productos agregados recientemente.
- Resumen interactivo de categorías con conteo de productos asignados.

### 2. 📦 Listado de Productos (`/products`)
- Tabla completa de productos con ID, Nombre, Categoría, Precio formateado en moneda y Stock.
- Barra de búsqueda en tiempo real por texto (nombre o descripción).
- Filtro desplegable por categoría.
- Indicador visual para stock bajo (rojo si stock < 10, verde si es normal).
- Botones de acción directa: Editar (redirige al formulario) y Eliminar (abre modal de confirmación).

### 3. 📝 Formulario de Producto (`/products/create` y `/products/:id/edit`)
- Reutilizable tanto para creación como para edición con detección automática del parámetro de ruta.
- Validación reactiva campo por campo (muestra mensajes de error específicos bajo cada input).
- Campos controlados:
  - **Nombre**: obligatorio, máximo 255 caracteres.
  - **Descripción**: opcional, área de texto.
  - **Precio**: obligatorio, número decimal >= 0.
  - **Stock**: obligatorio, número entero >= 0.
  - **Categoría**: selector poblado dinámicamente desde el store de categorías.
- Deshabilitación de botones y spinner en el botón de guardar durante el envío.

### 4. 📂 Listado de Categorías (`/categories`)
- Visualización en grid de tarjetas con nombre, descripción, fecha de creación y badge con la cantidad de productos vinculados.
- Modal emergente para crear o editar una categoría sin abandonar la vista.
- Confirmación de eliminación con aviso preventivo si la categoría tiene productos asignados.

---

## 🧠 Gestión de Estados (Pinia)

Cada store encapsula su estado reactivo, getters computados y acciones asíncronas:

```javascript
// Ejemplo de uso en cualquier componente
import { useProductStore } from '@/stores/products'
const productStore = useProductStore()

// Reactividad directa
productStore.fetchProducts()
```

### Stores Disponibles:
1. **`useProductStore`**:
   - Estado: `products`, `currentProduct`, `loading`, `saving`, `errors`.
   - Getters: `productCount`, `totalStock`, `totalValue`, `getProductById`.
   - Acciones: `fetchProducts`, `fetchProduct`, `createProduct`, `updateProduct`, `deleteProduct`.
2. **`useCategoryStore`**:
   - Estado: `categories`, `loading`, `saving`, `errors`.
   - Getters: `categoryCount`, `categoryOptions`.
   - Acciones: `fetchCategories`, `createCategory`, `updateCategory`, `deleteCategory`.
3. **`useToastStore`**:
   - Cola de notificaciones con métodos `success(title, msg)`, `error(title, msg)`, `warning(...)` e `info(...)`.

---

## 🔔 Manejo Global de Solicitudes y Alertas (DATA ALERT)

Conforme a los requerimientos, la aplicación gestiona rigurosamente los 3 estados en cada petición HTTP:

1. **Estado de Carga (`loading` / `saving`)**:
   - En vistas principales: `LoadingSpinner` centrado mientras se obtienen los datos.
   - En formularios: Spinner inline en el botón de acción mientras se deshabilita para evitar dobles clics.
2. **Estado de Éxito (`success`)**:
   - Disparo automático de notificación Toast verde con mensaje descriptivo y auto-cierre después de 5 segundos.
3. **Estado de Error (`error`)**:
   - **Interceptor de Axios (`src/services/api.js`)**:
     - **Errores de Red**: Muestra Toast de error alertando problemas de conexión.
     - **Error 422**: Muestra advertencia visual de validación y alimenta el objeto reactivo `errors` del formulario.
     - **Error 404**: Muestra alerta de recurso inexistente.
     - **Error 500**: Muestra alerta informativa de fallo en el servidor.

---

## 🚀 Instalación y Puesta en Marcha

1. **Ubicarse en el directorio del frontend**:
   ```bash
   cd vue-frontend
   ```

2. **Instalar dependencias**:
   ```bash
   npm install
   ```

3. **Iniciar el servidor de desarrollo Vite**:
   ```bash
   npm run dev
   ```
   Disponible en: 👉 **`http://localhost:5173`**

> **Nota sobre el Proxy:** `vite.config.js` tiene configurado un proxy automático para redirigir cualquier petición a `/api` hacia el backend en `http://127.0.0.1:8000`, evitando problemas de CORS en desarrollo.

---

## 📦 Compilación para Producción

Para compilar los assets optimizados y minificados:
```bash
npm run build
```
Los archivos finales se generarán en la carpeta `dist/`.
