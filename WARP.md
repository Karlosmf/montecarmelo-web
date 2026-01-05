# WARP.md

This file provides guidance to WARP (warp.dev) when working with code in this repository.

## Project Overview
Monte Carmelo is a premium charcuterie and cheese catalog website for a B2B business. Sales close via WhatsApp (no payment gateway). Products are sold by weight (kg for cold cuts) or by unit (wines/boards).

**Tech Stack:**
- Laravel 12.x (PHP 8.2+)
- Livewire 3 with Volt Functional API
- MaryUI component library
- Tailwind CSS v4 + DaisyUI v5
- SQLite with WAL mode
- Vite for asset bundling

## Development Commands

### Initial Setup
```bash
composer install
npm install
php artisan migrate --seed
```

### Development Server
Start all services concurrently (server, queue, logs, vite):
```bash
composer dev
```

Or start individually:
```bash
php artisan serve          # Laravel server
npm run dev                # Vite dev server
php artisan queue:listen   # Queue worker
php artisan pail           # Real-time logs
```

### Building Assets
```bash
npm run build              # Production build
```

### Testing
```bash
composer test              # Run PHPUnit test suite
php artisan test           # Alternative test command
```

### Code Quality
```bash
./vendor/bin/pint          # Laravel Pint code formatter
```

### Database
```bash
php artisan migrate        # Run migrations
php artisan migrate:fresh --seed  # Fresh database with seed data
php artisan tinker         # Laravel REPL
```

## Architecture & Code Structure

### Frontend Architecture
**Livewire Volt Functional API** - All components use Volt's functional API (not class-based). Components are single-file Blade templates with inline PHP logic at the top using `function Livewire\Volt\{...}`.

**Component Locations:**
- `resources/views/livewire/` - Page components (home.blade.php, catalog/index.blade.php, contact.blade.php)
- `resources/views/livewire/components/` - Reusable UI components (navbar, footer, cart-drawer)
- `resources/views/components/` - Blade components and layouts

**Routing:** Routes defined in `routes/web.php` using `Volt::route()` syntax, not traditional controller routes.

### Design System
The UI follows a strict **"Dark Luxury Editorial"** aesthetic (magazine-style, not generic e-commerce):

**Color Palette (Strict):**
- Background: `#121212` (near-black)
- Surface: `#1E1E1E` (cards/alternate sections)
- Primary Gold: `#D4AF37` (accents, borders, buttons)
- Text Body: `#E5E5E5`
- Text Muted: `#A3A3A3`

**Typography:**
- Headings: 'Playfair Display' (serif) - use uppercase with `tracking-widest`
- Body: 'Lato' (sans-serif)

**Key UI Patterns:**
- Zig-zag layout for product listings (alternating image left/right)
- Polaroid-style images: white border with slight rotation (`rotate-2` / `-rotate-2`)
- Gold-line inputs: transparent background with gold bottom border only

**Custom CSS Classes:** See `resources/css/app.css` for utility classes like `.h1-hero`, `.h2-section`, `.btn-primary-outline`, `.input-gold-line`, `.polaroid-base`, `.img-product-clean`

### Backend Architecture

**Models:**
- `app/Models/Product.php` - Product model with fields: name, slug, description, price (in cents), unit_type (kg/unit/pack), category, image_path, is_active, is_featured

**Services:**
- `app/Services/CartService.php` - Session-based shopping cart
  - Methods: `add()`, `remove()`, `getDetails()`, `total()`, `count()`, `clear()`, `getWhatsAppLink()`
  - Handles quantity calculations for weight-based products (grams to kg conversion)
  - Generates WhatsApp message with order details

**Facades:**
- `app/Facades/Cart.php` - Facade for CartService

**Providers:**
- `app/Providers/VoltServiceProvider.php` - Mounts Volt components from livewire and pages directories
- `app/Providers/AppServiceProvider.php` - Standard Laravel service registration

### Database Schema
Products table (see `database/migrations/2025_12_26_214844_create_products_table.php`):
- Prices stored as integers (cents) to avoid float precision issues
- `unit_type` enum: 'kg', 'unit', 'pack'
- `category` stored as simple string
- Boolean flags: `is_active`, `is_featured`

SQLite is the database (configured in `.env` with `DB_CONNECTION=sqlite`).

## Coding Standards

### Language Rules (STRICT)
- **Function/variable names:** English only (e.g., `calculateTotal`, `$productList`)
- **Comments:** Spanish only for team clarity
- **Commit messages:** Include co-author line: `Co-Authored-By: Warp <agent@warp.dev>`

### Laravel/Livewire Conventions
- Use Volt Functional API consistently - no class-based Livewire components
- Leverage MaryUI components with `mary-` prefix (e.g., `<x-mary-icon>`)
- Session-based cart (no authentication required for browsing/cart)

### Styling
- **ALWAYS** add `@source` directive in `app.css` when importing new external UI libraries to ensure Tailwind v4 scans classes
- Use design system color variables defined in `@theme montecarmelo` block
- Follow established UI patterns (zig-zag, polaroid, gold-line inputs)

### Price Handling
Prices are stored as **integers in cents**. Always multiply user input by 100 when storing and divide by 100 when displaying.

Weight-based products:
- Display in grams for UI (e.g., 250g, 500g)
- Calculate price: `(price_per_kg * grams) / 1000`

## Key Technical Details

### Tailwind CSS v4 Configuration
Uses new `@import "tailwindcss"` syntax (not classic tailwind.config.js). DaisyUI configured via `@plugin` directive in `app.css`.

### Volt Component Structure
```php
<?php
use function Livewire\Volt\{state, layout, uses};

uses([Toast::class]);
layout('components.layouts.app');

state(['products' => fn() => Product::all()]);

$handleAction = function () {
    // Action logic
};
?>

<!-- Blade template below -->
```

### WhatsApp Integration
Cart generates WhatsApp deep links via `CartService::getWhatsAppLink()` - formats order as text message with product list and estimated total. No payment processing on site.

### Asset Management
Vite compiles `resources/css/app.css` and `resources/js/app.js`. Assets referenced via `@vite()` directive in Blade templates.

## File Structure
```
app/
├── Facades/           # Service facades (Cart)
├── Http/Controllers/  # Minimal - mostly using Volt components
├── Models/            # Eloquent models (Product, User)
├── Providers/         # Service providers (Volt, App)
└── Services/          # Business logic (CartService)

database/
├── factories/         # Model factories
├── migrations/        # Database migrations
└── seeders/          # Data seeders (ProductSeeder)

resources/
├── css/
│   └── app.css       # Tailwind config + custom theme
├── js/
│   └── app.js        # Frontend JS
└── views/
    ├── components/   # Blade components & layouts
    └── livewire/     # Volt functional components
        ├── catalog/  # Product catalog pages
        └── components/  # Reusable Livewire components

routes/
└── web.php          # Routes using Volt::route()
```

## Testing Configuration
PHPUnit configured in `phpunit.xml`:
- Unit tests: `tests/Unit/`
- Feature tests: `tests/Feature/`
- Test environment uses SQLite in-memory database
