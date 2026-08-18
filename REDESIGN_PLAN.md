# REDESIGN PLAN — Monte Carmelo "Quiet Luxury 2026"

> Documento maestro del rediseño completo (público + admin).
> Revisado: 2026-08-13 · Alcance: Full redesign en una sola ejecución.
> Stack: Laravel 12 · Livewire 3 (Volt) · Tailwind v4 · GSAP · Lenis.

---

## 1. Contexto del negocio

Monte Carmelo es una **fábrica y boutique de charcutería de autor, chacinados
artesanales y quesos seleccionados**, ubicada en **Avellaneda / Reconquista,
Santa Fe, Argentina**. La cuchillería artesanal es un producto secundario.

- **B2C**: Tablas gourmet, fiambres premium, quesos para consumidor final.
- **B2B**: Venta mayorista a almacenes gourmet, fiambrerías, restaurantes.
- **Checkout**: Cierre por **WhatsApp** (sin pasarela de pago).
- **Instagram**: [@montecarmeloarg](https://www.instagram.com/montecarmeloarg/)

### Productos principales

| Línea | Ejemplos |
|-------|----------|
| Embutidos & Charcutería | Bondiola Feteada, Lomo a las Hierbas, Jamón Crudo Reserva, Salame Picado Grueso |
| Quesos Artesanales | Queso Pategrás Selección |
| Picadas & Tablas Gourmet | Tabla "Monte Carmelo" (4+ personas) |
| Cuchillería (secundario) | Cuchillos artesanales, tablas de corte |

### Mensajes clave

- *"Charcuterie & Premium Goods"*
- *"Artesanos del sabor. Una experiencia culinaria premium que honra la
  tradición y la excelencia en cada detalle."*
- *"El destino nos marcó el camino, pero la pasión nos mantuvo en él."*

---

## 2. Stack técnico

| Capa | Tecnología | Rol |
|------|-----------|-----|
| Backend | **Laravel 12** + **Livewire 3 (Volt)** | Server-driven UI |
| UI Base | **MaryUI v2.5** + **DaisyUI v5** | Componentes admin/formularios |
| CSS | **Tailwind CSS v4** (`@theme montecarmelo`) | Design tokens, responsive |
| Animación JS | **GSAP** (core + ScrollTrigger + SplitText) | Timelines, scroll storytelling, text reveal |
| Smooth Scroll | **Lenis** (by Darkroom) | Inertia scroll, <4kb |
| Animación CSS | **Scroll-Driven Animations** (`animation-timeline`) | Reveals on-scroll sin JS |
| Transiciones | **View Transitions API** | Morphing entre páginas con `wire:navigate` |
| DB | **SQLite** (WAL) | Base de datos |
| Build | **Vite 7** | Bundling |

```bash
npm install gsap lenis
```

---

## 3. Problemas detectados (auditoría)

### 3.1 CSS indefinido (rompe admin)

| Clase rota | Solución |
|-----------|----------|
| `.card-modern` | Definir en `@layer components` |
| `.glass-panel` | Definir con `backdrop-blur` + bordes |
| `.heading-modern` | Definir con serif + tracking |
| `.nav-link` | Definir con hover dorado + underline |
| `.table-glass` | Definir con fondo translúcido |

### 3.2 Datos falsos hardcodeados

| Dato falso | Ubicación |
|-----------|-----------|
| `Av. Libertador 1234, Buenos Aires` | Footer, contacto | → `Pje. 44-46, S3560 Reconquista, Santa Fe` |
| `+54 9 11 1234-5678` | Footer | → `+54 9 3482 53-5220` |
| `info@montecarmelo.com` | Footer | → `contacto@montecarmelo.com.ar` |
| Teléfonos inventados | Contacto | → `+54 9 3482 53-5220` |
| `Sucursal Centro / Norte` | Contacto | → Sucursal única en Reconquista |
| 8 URLs de Unsplash genérico | Galería | → Imágenes AI + fotos reales de FB |
| `rand(30, 90)` días curado | Catálogo | → Campo real `curing_days` |
| `5491112345678` | config/montecarmelo.php | → `5493482535220` |
| Links `#` en Nosotros/Historia | Navbar, footer, sidebar | → Anclas reales |

> ✅ **Datos reales confirmados por el cliente.**

### 3.3 Problemas de UX

1. **Sin PDP**: Productos no clickeables.
2. **Secciones redundantes**: "Somos" y "Story" dicen lo mismo → **fusionar**.
3. **Productos destacados**: Se cargan (`is_featured`) pero nunca se renderizan.
4. **3 lenguajes visuales**: Home (custom), catálogo (MaryUI), admin (roto).
5. **Galería no administrable**: Imágenes hardcodeadas en el template.
6. **Sin motor de animación**: Solo CSS básico y Alpine transitions.
7. **SEO**: Sin JSON-LD, sin sitemap, favicon default.

---

## 4. Estética: "Quiet Luxury 2026"

### Concepto

**Dark Editorial + Tactile Depth**. Revista gastronómica de alta gama con
carácter artesanal argentino. Cada pantalla es una página editorial.

### Principios

1. **Una idea por pantalla** — ritmo pausado, mucho aire.
2. **Tipografía protagonista** — Playfair Display revelado línea a línea.
3. **Movimiento con propósito** — animaciones que refuerzan el "proceso lento".
4. **Tactile depth** — micro-sombras, noise overlay 3%, bordes 1px sutiles.
5. **Gold como acento** — `#D4AF37` solo donde guía la mirada.

### Paleta

```
#121212  Fondo principal (Dark Slate)
#1E1E1E  Superficies/cards (Dark Zinc)
#0A0A0A  Footer (Near Black)
#D4AF37  Dorado principal
#B5952F  Dorado hover
#F3E5AB  Dorado claro (gradientes)
#8C1C13  Rojo vino (glow sutil)
#E5E5E5  Texto cuerpo
#A3A3A3  Texto muted
#525252  Texto placeholder
rgba(255,255,255,0.08)  Bordes sutiles
```

### Tipografía

| Uso | Fuente | Peso | Estilo |
|-----|--------|------|--------|
| Títulos | Playfair Display | 600–700 | `uppercase`, `tracking-[0.2em]`–`[0.3em]` |
| Cuerpo | Lato | 300–400 | `font-light`, `leading-relaxed` |
| CTAs | Playfair Display | 700 | `uppercase`, `tracking-[0.3em]`, `text-xs` |

---

## 5. Nueva estructura del HOME

Rediseño simplificado: 5 secciones potentes en vez de 6 redundantes.

```
┌─────────────────────────────────────────────┐
│  1. HERO SLIDER                             │
│     SplitText reveal + Ken Burns + parallax │
├─────────────────────────────────────────────┤
│  2. NUESTRA HISTORIA                        │
│     (fusión de "Somos" + "Story")           │
│     Fleur-de-lis animada + texto reveal     │
│     + polaroid stack + quote + CTA          │
├─────────────────────────────────────────────┤
│  3. PRODUCTOS DESTACADOS (NUEVO)            │
│     3 cards featured con 3D tilt + border   │
│     beam + CTA "Ver catálogo"               │
├─────────────────────────────────────────────┤
│  4. GALERÍA                                 │
│     Masonry + reveals escalonados +         │
│     lightbox + admin-managed                │
├─────────────────────────────────────────────┤
│  5. CTA CONTACTO                            │
│     Formulario gold-line + botón magnético  │
└─────────────────────────────────────────────┘
```

### Cambios vs. estructura actual:

| Antes | Ahora | Razón |
|-------|-------|-------|
| "Somos" (sección 2) | Fusionada → "Nuestra Historia" | Redundante con "Story" |
| "Story" (sección 3) | Fusionada → "Nuestra Historia" | Idem |
| "El Taller" (propuesto) | **Eliminada del home** | Cuchillería es producto secundario |
| Sin productos destacados | **"Productos Destacados" (NUEVA)** | Ya se cargaban pero no se mostraban |
| Galería hardcodeada | **Galería admin-managed** | Modelo `GalleryImage` para gestión |

---

## 6. Técnicas de animación 2026

### 6.1 Motor GSAP + Lenis (resources/js/animations.js)

```javascript
import Lenis from 'lenis';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { SplitText } from 'gsap/SplitText';

gsap.registerPlugin(ScrollTrigger, SplitText);

const lenis = new Lenis({
  duration: 1.2,
  easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
});

// Sync frame-perfect
lenis.on('scroll', ScrollTrigger.update);
gsap.ticker.add((time) => lenis.raf(time * 1000));
gsap.ticker.lagSmoothing(0);

// Accesibilidad
const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
if (reduced) {
  lenis.destroy();
  gsap.globalTimeline.timeScale(20);
}
```

### 6.2 CSS Scroll-Driven Animations

```css
.reveal-on-scroll {
  animation: fade-slide-in linear forwards;
  animation-timeline: view();
  animation-range: entry 10% cover 30%;
}
```

### 6.3 View Transitions (wire:navigate)

```css
@view-transition { navigation: auto; }
.product-hero-img { view-transition-name: product-hero; }
```

### 6.4 Micro-interacciones

- **Botones magnéticos**: CTAs que atraen hacia el cursor.
- **3D card tilt**: Productos con rotación 3D + reflejo dinámico.
- **Border beam**: Borde dorado rotativo en cards destacados (conic-gradient).
- **Scroll progress**: Barra dorada fija en top (CSS puro, `animation-timeline: scroll()`).
- **SplitText**: Títulos revelados palabra a palabra.

### 6.5 Accesibilidad obligatoria

```css
@media (prefers-reduced-motion: reduce) {
  *, ::before, ::after {
    animation-duration: 0.01ms !important;
    transition-duration: 0.01ms !important;
    scroll-behavior: auto !important;
  }
}
```

---

## 7. Datos de contacto confirmados (Settings seeder)

> ✅ Datos reales proporcionados por el cliente el 2026-08-13.

```
contact.email          = contacto@montecarmelo.com.ar
contact.phone          = +54 9 3482 53-5220
contact.whatsapp       = 5493482535220
contact.whatsapp_display = +54 9 3482 53-5220

store.name             = Monte Carmelo
store.address          = Pje. 44-46, S3560 Reconquista, Santa Fe
store.hours            = Lunes a Viernes de 08:00 a 17:00
store.city             = Reconquista
store.province         = Santa Fe

social.instagram       = https://www.instagram.com/montecarmeloarg/
social.facebook        = https://www.facebook.com/montecarmeloarg

brand.name             = Monte Carmelo
brand.tagline          = Charcuterie & Premium Goods
brand.description      = Línea premium desarrollada para concretar el sueño de una familia ⚜️ en Reconquista, Santa Fe.
brand.slogan           = Artesanos del sabor
```

> **Nota**: Una sola sucursal confirmada (Reconquista). La página de contacto
> se simplifica a una sola tarjeta de local en lugar de dos.

---

## 8. Fases de ejecución

> Ejecución completa en una sola sesión, fase por fase.

---

### Fase 0 — Fundación técnica

**0.1 Dependencias**
```bash
npm install gsap lenis
```

**0.2 Motor de animación** (`resources/js/animations.js`)
- GSAP + ScrollTrigger + SplitText registrados.
- Lenis sincronizado con ScrollTrigger.
- `prefers-reduced-motion` → destruir Lenis, acelerar GSAP.
- Helpers exportados: `revealUp()`, `revealStagger()`, `splitReveal()`,
  `parallax()`, `magneticButton()`, `tiltCard()`.
- Import desde `app.js`.

**0.3 Design system CSS** (`resources/css/app.css`)

Nuevos tokens en `@theme`:
```css
--color-accent-wine: #8C1C13;
--color-gold-light: #F3E5AB;
--color-footer-bg: #0A0A0A;
--color-border-subtle: rgba(255, 255, 255, 0.08);
```

Clases faltantes en `@layer components`:

| Clase | Spec |
|-------|------|
| `.card-modern` | `bg-background-card`, border sutil, `rounded-xl`, `p-6` |
| `.glass-panel` | `bg-white/5`, `backdrop-blur-md`, `border-white/10` |
| `.heading-modern` | serif, `uppercase`, `tracking-[0.2em]`, primary |
| `.nav-link` | sans, `text-sm`, `uppercase`, hover primary, underline animado |
| `.table-glass` | `bg-white/5`, `backdrop-blur-sm`, `rounded-lg` |
| `.btn-magnetic` | Extiende `.btn-luxury` |
| `.reveal-on-scroll` | `animation-timeline: view()` |
| `.border-beam` | `::before` con `conic-gradient` rotativo dorado |
| `.scroll-progress` | Barra fija top, `animation-timeline: scroll()` |

Reemplazar **todos los hex hardcodeados** por tokens del theme.
Agregar `@media (prefers-reduced-motion: reduce)` global.

**0.4 Modelo Setting** (config-driven)
- `php artisan make:model Setting -m`
- Campos: `key` (unique), `value` (text), `group` (string).
- Seeder con datos reales (pendiente del cliente).
- Facade: `Settings::get('contact.whatsapp')`

**0.5 Modelo GalleryImage** (galería administrable)
- `php artisan make:model GalleryImage -m`
- Campos: `title`, `image_path`, `order`, `is_active`, `category` (nullable).
- Admin CRUD para gestionar orden y cambiar imágenes.
- Las 8 imágenes AI generadas se usan como seed inicial.

---

### Fase 1 — Home

**1.1 Hero Slider**
- SplitText en título (reveal palabra a palabra, stagger 0.05s).
- Ken Burns: `scale(1) → scale(1.08)` durante 6s por slide.
- Parallax del contenido (texto a 0.5x velocidad del scroll).
- Progress bar por slide (línea dorada sincronizada con timer 6s).
- Dots rediseñados: líneas horizontales con expansión animada.
- `fetchpriority="high"` en primera imagen.
- Fallback estático con SplitText si no hay slides.

**1.2 Nuestra Historia** (FUSIÓN de Somos + Story)

Layout editorial en una sola sección poderosa:

```
┌─────────────────────────────────────────────────────┐
│              ✦ (Fleur-de-lis animada)               │
│                                                     │
│               NUESTRA HISTORIA                      │
│                                                     │
│  "En Monte Carmelo recuperamos la tradición..."     │
│  (splitReveal línea a línea)                        │
│                                                     │
│  ┌────────────┐                                     │
│  │  Polaroid  │   "El destino nos marcó el camino,  │
│  │  Stack     │    pero la pasión nos mantuvo        │
│  │  parallax  │    en él."                           │
│  └────────────┘                                     │
│                                                     │
│            [ CONOCENOS ← magnético ]                │
└─────────────────────────────────────────────────────┘
```

- Fleur-de-lis SVG con trazo animado (stroke-dasharray → dashoffset).
- Título con `revealUp()`.
- Texto descriptivo con `splitReveal()`.
- Polaroid stack con `parallax()` diferencial.
- Quote con `splitReveal()`.
- CTA con `magneticButton()`.
- Reemplazar todos los hex → tokens.

**1.3 Productos Destacados** (SECCIÓN NUEVA)

Muestra los 3 productos `is_featured` que ya se cargan pero nunca se renderizan:

```
┌─────────────────────────────────────────────────────┐
│           SELECCIÓN DEL MAESTRO                     │
│                                                     │
│  ┌──────────┐  ┌──────────┐  ┌──────────┐         │
│  │ Bondiola │  │ Jamón    │  │ Tabla MC │         │
│  │          │  │ Crudo    │  │          │         │
│  │ 3D tilt  │  │ 3D tilt  │  │ 3D tilt  │         │
│  │ + border │  │ + border │  │ + border │         │
│  │   beam   │  │   beam   │  │   beam   │         │
│  │          │  │          │  │          │         │
│  │ $2.450/kg│  │ $3.200/kg│  │ $8.500   │         │
│  └──────────┘  └──────────┘  └──────────┘         │
│                                                     │
│           [ VER CATÁLOGO COMPLETO ]                 │
└─────────────────────────────────────────────────────┘
```

- 3 cards con `tiltCard()` (3D rotation + reflejo).
- `border-beam` dorado en cada card (conic-gradient rotativo).
- `revealStagger()` al entrar en viewport.
- Click → PDP con View Transition (morphing de imagen).
- CTA inferior → `/products`.

**1.4 Galería** (rediseño completo)

- **Layout masonry** (CSS `columns` o grid asimétrico).
- Imágenes desde modelo `GalleryImage` (admin-managed).
- `gsap.batch()` reveals escalonados (scale 0.95→1, opacity 0→1, stagger 0.08s).
- Lightbox: click → overlay fullscreen con transición, nav con flechas, close con Escape.
- Hover: `scale(1.05)` + overlay sutil dorado.
- Imágenes AI generadas como seed inicial (8 fotos).

**1.5 CTA Contacto**
- Inputs `.input-gold-line` con focus animado (línea se expande del centro).
- `.btn-magnetic` en submit.
- Visual unificado con el home.

---

### Fase 2 — Catálogo + PDP

**2.1 Catálogo refinado**
- Filtros: chips de categoría con transición + buscador `.input-gold-line`.
- Zig-zag con `revealUp()` por fila + parallax en imágenes.
- Eliminar `rand(30, 90)` → campo real `curing_days`.
- `tiltCard()` en hover sobre imagen.
- View Transition al clickear → PDP (morphing de imagen).

**2.2 PDP** (`/products/{slug}`)

Ruta nueva: `Volt::route('/products/{slug}', 'catalog.show');`

```
┌─────────────────────────────────────────────────────┐
│  ┌──────────────────┐  ┌──────────────────────────┐ │
│  │                  │  │ BONDIOLA FETEADA AL VACÍO │ │
│  │  Imagen hero     │  │ Categoría: Embutidos      │ │
│  │  view-transition │  │ Curación: 45 días          │ │
│  │                  │  │ Origen: Avellaneda, SF     │ │
│  │                  │  │ Formato: Al vacío          │ │
│  └──────────────────┘  │ $2.450 / kg               │ │
│                        │ [- 200g +]                 │ │
│                        │ [AGREGAR AL PEDIDO]        │ │
│                        │ [CONSULTAR POR WHATSAPP]   │ │
│                        └──────────────────────────┘ │
│  ── PRODUCTOS RELACIONADOS ──                       │
│  [card] [card] [card]                               │
└─────────────────────────────────────────────────────┘
```

- `view-transition-name: product-hero` para morphing desde catálogo.
- Specs reales (campos de migración 2.3).
- Selector cantidad: kg (100g, 200g, 500g, 1kg) / unit (1, 2, 3…).
- CTA: `Cart::add()` + toast, CTA secundario WhatsApp.
- Relacionados: 3 de la misma categoría.

**2.3 Migración: campos de producto**

```bash
php artisan make:migration add_details_to_products_table
```

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `curing_days` | `integer nullable` | Días de curación |
| `origin` | `string nullable` | Procedencia |
| `format` | `string nullable` | Presentación |
| `short_description` | `text nullable` | Descripción corta |

**2.4 Categoría Cuchillería**
- Agregar categoría + productos ejemplo en seeders.
- Aparece en catálogo como una categoría más (sin sección hero dedicada).

---

### Fase 3 — Navbar / Footer / Contacto / Auth

**3.1 Navbar**
- Datos desde Settings.
- Estado activo con underline dorado animado.
- `.nav-link` definida (Fase 0).
- Links arreglados: "Nosotros" → `/#nuestra-historia`, "Historia" eliminado.
- Scroll behavior: navbar sólida al scrollear (`bg-background-main/95 backdrop-blur-md`).
- Mobile drawer unificado con theme.

**3.2 Footer**
- Todos los datos desde Settings.
- Instagram real.
- Links arreglados.
- Hex → tokens.
- Reveal on scroll por columna.

**3.3 Contacto** (rediseño editorial)
- Sucursales: cards `.card-modern` con datos reales desde Settings.
- Form B2B: `.input-gold-line` + `.btn-luxury` (no MaryUI genérico).
- Tabla `leads` + modelo + email al admin.
- Reveal animations escalonado.

**3.4 Auth**
- Login/registro con lenguaje visual del theme.
- WhatsApp desde Settings.

---

### Fase 4 — Admin + SEO + QA

**4.1 Admin**
- Clases CSS arregladas (Fase 0).
- Dashboard: pedidos del día, total productos, leads nuevos.
- **CRUD GalleryImage**: reordenar (drag & drop o campo `order`), subir/cambiar
  imágenes, activar/desactivar. El admin puede reemplazar las imágenes AI por
  fotos reales cuando las tenga.

**4.2 SEO**
- JSON-LD: `FoodEstablishment` (layout), `Product` (PDP).
- Meta tags dinámicas por página.
- Sitemap.xml.
- Favicon de marca.
- Robots.txt (bloquear admin).

**4.3 Rendimiento**
- `fetchpriority="high"` en hero.
- `loading="lazy"` debajo del fold.
- WebP, sizes apropiados.
- INP: animaciones solo en `transform` + `opacity`.

**4.4 QA**

```bash
npm run build && composer test && vendor/bin/pint
```

Flujo de prueba:
1. Home → scroll completo → animaciones.
2. Hero slider → autoplay, dots, flechas.
3. Productos destacados → click → PDP → View Transition.
4. Catálogo → filtrar, buscar, paginar.
5. PDP → agregar al carrito → WhatsApp checkout.
6. Contacto → formulario B2B → lead en DB.
7. Admin → CRUD completo + gestión galería.
8. `prefers-reduced-motion` → sin animaciones.
9. Mobile responsive: 375px, 768px, 1024px.
10. Contraste AA/AAA.

---

## 9. Imágenes generadas (seed galería)

8 imágenes AI generadas para el seed de `GalleryImage`:

1. **Tabla gourmet** — Picada completa sobre pizarra oscura (4:3)
2. **Bondiola feteada** — Macro detalle con veta de grasa (1:1)
3. **Salame artesanal** — Entero y cortado, pimienta visible (1:1)
4. **Queso pategrás** — Horma cortada con ojos bien formados (1:1)
5. **Jamón crudo** — Pata en jamonero con corte fino (3:4)
6. **Picada con vino** — Vista cenital, tabla completa + Malbec (4:3)
7. **Lomo a las hierbas** — Pieza curada con costra de hierbas (1:1)
8. **Taller artesanal** — Embutidos colgados, escena de workshop (16:9, hero)

> Estas imágenes se pueden reemplazar desde el admin cuando el cliente
> tenga fotos reales.

---

## 10. Riesgos y mitigaciones

| Riesgo | Mitigación |
|--------|-----------|
| Datos de contacto | ✅ **Datos reales confirmados** — cargados en Settings seeder. |
| Fotos AI como placeholder | Admin CRUD permite reemplazar en cualquier momento. |
| SplitText requiere DOM ready | Ejecutar post-DOM, re-ejecutar en `wire:navigate`. |
| Lenis conflicto con modales | Pausar Lenis al abrir drawer/modal. |
| `@starting-style` Safari <18.4 | Fallback Alpine `x-transition`. |

---

## 11. Estado de ejecución

| Fase | Alcance | Estado |
|------|---------|--------|
| 0 — Fundación | GSAP+Lenis, CSS, Settings, GalleryImage | ✅ Lista |
| 1 — Home | Hero, Nuestra Historia, Productos Destacados, Galería, CTA | ✅ Lista |
| 2 — Catálogo + PDP | Filtros, PDP, migración, Cuchillería | ✅ Lista |
| 3 — Nav / Footer / Contacto | Settings-driven, leads, links | ✅ Lista |
| 4 — Admin + SEO + QA | CRUD galería, JSON-LD, QA | 🔶 En revisión (QA pendiente) |