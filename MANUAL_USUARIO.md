# Manual de Usuario - Panel de Administración Monte Carmelo

Bienvenido al sistema de administración de Monte Carmelo. Este panel le permite gestionar pedidos, productos y visualizar métricas clave de su negocio.

## 1. Acceso al Sistema
*   **URL:** `/login`
*   **Credenciales:** Ingrese con su correo electrónico y contraseña de administrador.
*   **Pantalla de Login:** Ahora cuenta con un fondo temático de charcutería premium.

## 2. Dashboard (Panel Principal)
Al ingresar, verá un resumen general del estado del negocio:
*   **Estadísticas Rápidas:**
    *   **Pendientes:** Cantidad de pedidos que requieren atención.
    *   **Ventas Hoy:** Cantidad de pedidos realizados en el día.
    *   **Ingresos Mes:** Total estimado de ventas del mes en curso.
*   **Gráficos:** Visualización de tendencias de pedidos (según disponibilidad).

## 3. Gestión de Pedidos (`/admin/orders`)
Esta es la sección operativa más importante.
*   **Listado:** Verá una tabla con los últimos pedidos, incluyendo fecha, cliente, total y estado.
*   **Filtros:** Puede filtrar por estado (`Nuevo`, `Contactado`, `Completado`, `Cancelado`) o buscar por nombre de cliente.
*   **Estado Visual:** Los estados tienen colores distintivos (Azul para nuevos, Dorado para contactados, Verde para completados).
*   **Acciones Rápidas:**
    *   **Ver Detalles (Ojo):** Abre un panel lateral con toda la información del pedido (items, totales, notas).
    *   **WhatsApp:** Si el cliente cargó su teléfono, verá un botón de WhatsApp. Al hacer clic, se abrirá un chat con un mensaje pre-cargado: *"Hola {nombre}, te escribo de Monte Carmelo por tu pedido #{id}..."*.
*   **Edición:** Desde el panel lateral puede cambiar el estado del pedido y agregar notas internas.

## 4. Gestión de Productos (`/admin/products`)
*   **Agregar Producto:** Utilice el botón "Nuevo Producto" para cargar items al catálogo.
*   **Edición:** Puede modificar precios, descripciones, categorías y subir fotos.
*   **Fotos:** Las imágenes se ajustan automáticamente al diseño del sitio.
*   **Destacados:** Marque productos como "Destacados" para que aparezcan en la página principal.

## 5. Sitio Web (Frontend)
El sitio público ha sido actualizado con la nueva identidad de marca:
*   **Hero Section:** Imagen de fondo de alta calidad y tipografía elegante.
*   **Sección Somos:** Texto institucional actualizado.

## 6. Gestión de Hero Slider (`/admin/slides`)
**Nuevo:** Ahora puede controlar las imágenes principales del inicio (Hero) desde el panel.
*   **Crear Slide:** Suba una imagen, agregue un título, descripción y opcionalmente un botón con enlace.
*   **Orden:** Utilice las flechas "Arriba/Abajo" para reordenar en qué secuencia aparecen las imágenes.
*   **Activar/Desactivar:** Puede ocultar temporalmente un slide sin borrarlo usando el interruptor de estado.
*   **Eliminar:** Cuenta con un sistema de seguridad (modal) para confirmar antes de borrar una imagen permanentemente.

## 7. Gestión de Usuarios y Clientes B2B (`/admin/users`)
Esta sección permite administrar los accesos al sistema.
*   **Crear Usuario Interno:** Utilice el botón "Nuevo Usuario" para registrar administradores o empleados manualmente. Puede asignar rol de `Usuario` o `Administrador` y definir su contraseña.
*   **Registro B2B (Mayorista):**
    *   Los clientes ahora pueden solicitar cuenta desde `/register-b2b`.
    *   **Proceso:** El cliente completa sus datos (Razón Social, CUIT, Teléfono) y es redirigido a WhatsApp para notificarle.
    *   **Aprobación:** La cuenta se crea en estado **PENDIENTE** (Inactivo).
*   **Notificaciones:** En el menú lateral verá un **globo rojo** indicando cuántos usuarios nuevos están esperando aprobación.
*   **Aprobar Cuentas:**
    1.  Vaya a la pestaña "Pendientes".
    2.  Verifique los datos del cliente (haciendo hover o viendo la tabla).
    3.  Haga clic en el botón **Verde (Check)** para activar la cuenta. El usuario ahora podrá iniciar sesión.


---
**Soporte Técnico:**
Ante cualquier inconveniente con el sistema, contacte al desarrollador administrador.
