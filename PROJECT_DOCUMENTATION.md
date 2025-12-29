# Documentación del Proyecto: Monte Carmelo Web

**Fecha:** 29 de Diciembre de 2025  
**Versión:** 1.2.0 (Admin Panel Release)  
**Estado:** En Desarrollo / MVP Completo

---

## 1. Visión General
**Monte Carmelo** es una plataforma web para una fábrica de embutidos y quesos premium. A diferencia de un e-commerce tradicional, funciona como un **Catálogo Digital B2B/B2C** donde la conversión final (el cierre de venta y pago) se realiza a través de **WhatsApp**, manteniendo un trato personalizado.

El sistema cuenta con un **Frontend** estilo "Editorial Dark Luxury" para el cliente final y un **Panel de Administración** completo para la gestión del negocio.

---

## 2. Arquitectura & Tech Stack

El proyecto utiliza un stack moderno, reactivo y ligero, priorizando la velocidad de desarrollo sin sacrificar robustez.

*   **Backend:** Laravel 12.x
*   **Frontend Interactivo:** Livewire + Volt (Functional API).
*   **UI Components:** MaryUI (basado en DaisyUI/Tailwind).
*   **Estilos:** Tailwind CSS v4.
*   **Base de Datos:** SQLite (Configuración local/MVP).
*   **Gráficos:** Chart.js (integrado con Alpine.js).
*   **Autenticación:** Laravel Breeze (Livewire stack).

---

## 3. Estructura de Datos (Schema)

Hemos migrado de un esquema simple a uno relacional robusto para soportar el panel de administración.

### Modelos Principales
*   **User:** Usuarios del sistema. Se agregó columna `role` ('admin' | 'client').
*   **Category:** Categorización principal (Ej: Fiambres, Quesos). Incluye `image_path` y `color`.
*   **Product:** El núcleo del catálogo.
    *   `category_id`: Relación BelongsTo Category.
    *   `unit_type`: 'kg' (peso), 'unit' (unidades), 'pack'.
    *   `price`: Almacenado en centavos (integer) para precisión.
*   **Tag:** Etiquetas transversales (Ej: "Sin TACC", "Premium", "Oferta"). Relación Many-to-Many con Products.
*   **Order:** Registro de intenciones de compra.
    *   `items`: JSON Snapshot de los productos al momento de la orden.
    *   `status`: 'pending', 'contacted', 'completed', 'cancelled'.
    *   `total`: Integer (centavos).

---

## 4. Módulos Implementados

### A. Frontend (Público)
*   **Diseño Editorial:** Estética oscura (#121212), tipografía Serif (Playfair Display) y detalles dorados (#D4AF37).
*   **Catálogo Interactivo:** Listado estilo Zig-Zag con filtrado por categoría y búsqueda en tiempo real.
*   **Carrito de Compras (Session-Based):**
    *   No requiere registro para llenar el carrito.
    *   **Slide-over:** Panel lateral reactivo para ver el resumen.
    *   **WhatsApp Checkout:** Genera un link con el mensaje pre-formateado ("Hola, quiero pedir X, Y...").
    *   **Sincronización:** Badge en Navbar se actualiza en tiempo real via eventos de Livewire.

### B. Backend (Panel de Administración)
Accesible vía `/admin/dashboard` (requiere rol `admin`).

1.  **Dashboard:**
    *   **KPIs:** Ventas del mes, Ingresos estimados, Producto Top.
    *   **Gráficos:** Evolución de pedidos (Líneas) y Ventas por Categoría (Dona).
2.  **Gestor de Pedidos (`/admin/orders`):**
    *   Listado de intenciones de compra.
    *   Visualización de items pedidos.
    *   Gestión de estado (Nuevo -> Contactado -> Completado).
    *   Acceso directo al WhatsApp del cliente.
3.  **Gestión de Catálogo:**
    *   **Productos:** CRUD completo con upload de imágenes, selector de unidad (kg/u) y multi-select de etiquetas.
    *   **Categorías & Etiquetas:** ABM simple para organizar el inventario.

---

## 5. Flujos de Trabajo Clave

### Ciclo de Vida del Pedido
1.  **Cliente:** Navega el catálogo -> Agrega items (gramos o unidades) -> Abre Carrito -> Clic en "Finalizar en WhatsApp".
    *   *Backend:* Se crea un registro `Order` con estado `pending`.
    *   *Frontend:* Se redirige a WhatsApp Web/App con el detalle.
2.  **Admin:** Recibe mensaje -> Ingresa a `/admin/orders` -> Verifica el pedido.
3.  **Gestión:**
    *   Cambia estado a `contacted` tras responder.
    *   Coordina pago/envío por chat.
    *   Cambia estado a `completed` al cerrar venta.

### Precios y Unidades
*   **Base de Datos:** Todo precio se guarda en centavos ($100.00 = `10000`).
*   **Frontend:** Se formatea automáticamante.
*   **Lógica Carrito:**
    *   Si es 'kg': El input es en gramos (ej: 250g). Precio = `(PrecioKg * Gramos) / 1000`.
    *   Si es 'unit': Precio = `PrecioUnit * Cantidad`.

---

## 6. Próximos Pasos (Roadmap)

1.  **Refinamiento de UX Móvil:** Asegurar que el dashboard sea 100% usable en celulares (actualmente 90%).
2.  **Notificaciones:** Enviar email al admin cuando entra un nuevo pedido (además del WhatsApp).
3.  **Roles y Permisos:** Refinar middleware para asegurar que solo `admin` entre al panel (actualmente lógica básica).
4.  **SEO:** Agregar meta tags dinámicos por producto.

---

**Nota para el equipo:**
El proyecto sigue estrictamente la convención de **Livewire Volt**. Evitar crear controladores tradicionales salvo casos excepcionales. Mantener la lógica de UI en los componentes Blade/Volt y la lógica de negocio compleja en `App\Services`.
