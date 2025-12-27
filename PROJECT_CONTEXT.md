# Monte Carmelo - Web Institucional & Catálogo B2B

## 1. Business Logic
- **Cliente:** Fábrica de embutidos y quesos premium.
- **Objetivo:** Catálogo digital con cierre de ventas por WhatsApp (sin pasarela de pago).
- **Tipos de Venta:** Por peso (kg) para fiambres, por unidad para vinos/tablas.

## 2. Tech Stack
- **Framework:** Laravel 11.x
- **Frontend:** Livewire (Volt Functional API) + MaryUI.
- **Styling:** Tailwind CSS v4 + DaisyUI v5 (Beta).
- **Database:** SQLite (con modo WAL activado).
- **Dev Tools:** Warp, Gemini CLI, Antigravity.

## 3. UI/UX Design System (MOCKUP BASED)
- **Visual Identity:** "Dark Luxury Editorial" (Estilo revista, no e-commerce genérico).
- **Color Palette (Strict):**
  - `Background`: #121212 (Casi negro, mate).
  - `Primary (Gold)`: #D4AF37 (Usado en textos destacados, bordes y botones outline).
  - `Text Body`: #E5E5E5 (Gris claro para lectura).
  - `Surface`: #1E1E1E (Para tarjetas o secciones alternas).
- **Typography:**
  - **Headings:** 'Playfair Display' (Serif). Debe usarse en mayúsculas con `tracking-widest` para títulos elegantes.
  - **Body:** 'Lato' (Sans-serif). Limpio y moderno.
- **Key UI Patterns:**
  - **Zig-Zag Layout:** En listas de productos, alternar imagen izquierda/texto derecha y viceversa.
  - **Polaroid Style:** Imágenes con borde blanco (`p-2 bg-white`) y rotación ligera (`rotate-2` o `-rotate-2`).
  - **Gold Inputs:** Los formularios no tienen fondo, solo una línea inferior dorada (`border-b-primary`).

## 4. Coding Standards (STRICT)
- **Language:** Code & Function names MUST be in **English** (e.g., `calculateTotal`).
- **Comments:** All complex logic must be commented in **Spanish** for team clarity.
- **Structure:** Use Volt functional API consistently.
- **Styling:** ALWAYS include `@source` in `app.css` for any new external UI library to ensure Tailwind v4 scans the classes.
- **Components:** Use `mary-` prefix for all MaryUI components.

## 5. Current Status
- [x] Installation of dependencies (MaryUI, Volt, Livewire).
- [x] Database Schema setup (Products table).
- [x] Layout & UI Shell (Navbar, Footer, Drawer).
- [x] Home Page implementation (Mockup Based).
- [x] Catalog Page (Zig-Zag Editorial implementation).
- [x] Contact & B2B Leads Page.