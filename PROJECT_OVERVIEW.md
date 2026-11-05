# TIMEYRAH — Complete Website Overview & Project Memory

This document contains everything about the **TIMEYRAH** luxury and everyday watch e-commerce project: what we created, how the database backend works, all bugs we resolved, hosting details, and step-by-step instructions for local work and live deployment on InfinityFree.

---

## 1. Project Information & Target Audience

- **Project Name:** TIMEYRAH (Luxury & Everyday Watches E-Commerce)
- **Target Audience:** Customers in **Pakistan**.
- **Wording & Tone:** Simple, clear, easy English (no overly complicated words).
- **Currency & Localization:** USD formatted (`$110.00`, `$450.00`) with Pakistani localized addresses (Karachi, Lahore, Islamabad, Rawalpindi, Peshawar, Faisalabad, Quetta, Multan), Cash on Delivery (`COD`) as the default payment method, and Pakistani phone format (`+92 300 1234567`).
- **Theme & Colors:**
  - Dark Mode & Light Mode toggle button available in the navbar.
  - Signature warm luxury brown & copper accents (`#1c1814` / `#cb8665`) used across both light and dark themes.

---

## 2. Technology Stack

- **Backend Framework:** Laravel (running on PHP 8.3)
- **Frontend Styling:** Tailwind CSS v4 (`@tailwindcss/vite`)
- **Interactivity:** Alpine.js (reactive cart drawer, live price calculations, image gallery switcher, mobile menu)
- **Local Database:** SQLite (`database/database.sqlite`)
- **Live Database:** MySQL on InfinityFree (`sql312.byetcluster.com` → `if0_43273400_watches`)
- **Session & Cache Driver:** `file` (for shared hosting stability)

---

## 3. Database Architecture & Eloquent Backend

Full column-by-column documentation is stored in [`DATABASE_SCHEMA.md`](DATABASE_SCHEMA.md), and the 1-click phpMyAdmin MySQL import file is stored in [`database/timeyrah_schema.sql`](database/timeyrah_schema.sql).

### E-Commerce Database Tables (8 Tables)
1. **`brands`** — 20 watch brands (`Rado`, `Citizen`, `Seiko`, `Casio General`, `Casio Edifice`, `Casio G Shock`, `Omax General`, `Omax Masterpiece`, `Omax Premium`, `Mathey Tissot`, `Ferro`, `Crysma`, `Kenneth Cole`, `Fossil`, `Daniel Klein`, `Swiss Military Hanowa`, `Santa Barbara`, `Rolex`, `Longines`, `IWC`).
2. **`categories`** — 6 watch categories (`STRAP`, `METAL CHAIN`, `ANALOG`, `QUARTZ`, `DIGITAL`, `AUTOMATIC`).
3. **`products`** — All 17 curated watches with regular `price`, discounted `sale_price`, `stock`, `sku`, `movement`, `case_size`, `water_resistance`, `description`, `is_featured`, and `is_active`.
4. **`product_images`** — Watch gallery photos (`image_path`, `is_primary`, `sort_order`).
5. **`category_product`** — Many-to-many pivot table linking each watch to multiple filter categories.
6. **`coupons`** — Promo codes (`TIMEYRAH10` for 10% off, `VIP2026` for $200 fixed discount) with `min_spend`, `expires_at`, and `is_active`.
7. **`orders`** — Real customer orders supporting both guest checkout (`user_id = null`) and signed-in customers (`user_id`), Pakistani delivery details, `payment_method` (`COD`, `Bank`, `Card`), `shipping_cost`, `discount_amount`, `total_amount`, and `order_status` (`Pending`, `Processing`, `Delivered`, `Cancelled`).
8. **`order_items`** — Line items inside each order (`order_id`, `product_id`, `product_title`, `unit_price`, `quantity`, `dial_variant`, `line_total`).

### Backend Classes Created
- **Models (`app/Models/`):** `Brand`, `Category`, `Product`, `ProductImage`, `Coupon`, `Order`, `OrderItem`, and `User` (`orders()` relation).
- **Enums (`app/Enums/`):** `OrderStatus`, `PaymentMethod`, `CouponType`.
- **Validation & Order Action:**
  - `App\Http\Requests\CheckoutRequest` — Validates customer contact, Pakistani address/city, shipping, and cart inputs.
  - `App\Actions\PlaceOrder` — Runs inside a database transaction (`DB::transaction` + `lockForUpdate`), ignores untrusted client-side prices in favor of database `Product::current_price`, checks & decrements `stock`, applies valid `Coupon` discounts, and creates `Order` + `OrderItem` rows.
- **Seeders (`database/seeders/`):** `ProductSeeder` and `CouponSeeder` (called from `DatabaseSeeder`).

---

## 4. Website Structure & Pages

### 1. Home Page (`/` — `resources/views/home.blade.php`)
- **Top Bar & Navigation:** Center navbar with brand logo, mega-menu brand links, dark/light theme switch, user account dropdown, and live cart icon.
- **Collection Bar & Hero Banner:** Quick category bar and luxury watch showcase with "Explore Collection" call to action.
- **Interactive Cart Drawer:** Slide-out drawer (`components/cart-drawer.blade.php`) synced with `localStorage` and session endpoints (`/cart/sync`, `/cart/data`, `/cart/clear`).

### 2. Shop Catalog (`/shop` — `resources/views/shop.blade.php`)
- **Database-Driven Eloquent Catalog:** Powered by `ShopController@index` querying active `Product` models eager-loaded with `brand`, `categories`, and `images`.
- **Dynamic Filters & Sorting:** Filter by Brand, Category, Search query, Sale-aware Price Range (`COALESCE(sale_price, price)`), and Movement; sort by Featured, Price Low–High, Price High–Low, Rating, or Newest.
- **Server-Side Pagination:** 9 watches per page (`LengthAwarePaginator`) using `resources/views/pagination/horology.blade.php`, plus JSON API support when `Accept: application/json` is requested.

### 3. Product Detail Page (`/product/{id}` — `resources/views/product-detail.blade.php`)
- **ID or Slug Lookup:** `ShopController@show` loads any active watch by numeric `id` or `slug` (`404` if inactive or missing) along with up to 4 related watches from the same brand or shared categories.
- **Interactive Gallery, Variant Selector & Buy Now:** Supports `ADD TO CART` and direct `BUY NOW` (`/checkout?product_id=...`).

### 4. Checkout Page (`/checkout` — `resources/views/checkout.blade.php`)
- **Pakistani Checkout Experience:** Defaults to Pakistan and **Cash on Delivery (COD)**, with `+92 300 1234567` phone format, major Pakistani cities, free standard courier delivery (`armored`) or express urgent delivery (`concierge`, +$150), custom case-back engraving, and promo code support (`TIMEYRAH10`, `VIP2026`).
- **Real Database Order Saving (`POST /checkout`):** Validates via `CheckoutRequest`, saves to `orders` and `order_items`, deducts `products.stock`, clears session cart, and redirects to `/order-confirmation/{orderNumber}`.

### 5. Order Confirmation Page (`/order-confirmation/{orderNumber?}` — `resources/views/order-confirmation.blade.php`)
- Loads the saved `Order` and `OrderItem` records directly from the database by `order_number` and displays the official order receipt, delivery status timeline, payment method (`Cash on Delivery`, `Bank Transfer`, or `Card`), and printable warranty certificate.

### 6. Authentication, Customer Dashboard & Profile (Laravel Breeze — 100% Active & Themed)
- Custom luxury TIMEYRAH dual-theme (Dark & Light mode) views across `/login`, `/register`, `/forgot-password`, `/reset-password/{token}`, `/dashboard`, and `/profile`.
- **Customer Dashboard (`/dashboard` — `DashboardController` + `resources/views/dashboard.blade.php`)**:
  - Personalized welcome header with quick links to **Shop Watches** and **Edit Profile**.
  - 4 real-time summary cards (**Total Orders**, **Active Orders**, **Delivered**, and **Total Spent**).
  - **My Watch Orders** list displaying watch photo, order number, status badge (`Pending`, `Processing`, `Delivered`, `Cancelled`), city, payment method, and direct **View Receipt** link (`/order-confirmation/{orderNumber}`).
  - **Member Privileges** card with one-click copy buttons for active database coupons (`TIMEYRAH10`, `VIP2026`), plus **Featured Original Watches** and the **TIMEYRAH Pakistan Promise**.
- **Account Profile (`/profile` — `resources/views/profile/edit.blade.php`)**: Matching TIMEYRAH dark/light copper-bronze cards, inputs, buttons, and delete-confirmation modal.

### 7. Admin Atelier CMS & Page Customizer (`/admin/*`)
- **Watches Catalog (`/admin/products`)**: Dynamic product creation, editing, photo management, inventory/stock tracking, and category syncing.
- **Collection & Filter Categories (`/admin/categories`)**: Full CRUD for collection and filter categories with real-time watch usage counts, auto-slugs, and public filter syncing.
- **Technical Specifications (`/admin/specifications`)**: Full CRUD for technical specification attributes (Movement, Case Material, Power Reserve, etc.) and predefined reusable options.
- **Page Customizer & Side Panel Form (`/admin/customizer`)**: Interactive split layout with side panel form and live responsive preview iframe (Desktop, Tablet, Mobile) to customize:
  - **SEO & Meta Tags**: Meta title, subtitle, meta description, keywords, OpenGraph social share image upload.
  - **Hero Header Banner**: Eyebrow badge, brand headline, accents, description, button text/link, center hero watch image upload.
  - **Shop by Category Section**: Section badge, title, subtitle, and multi-select category checkboxes.
  - **Featured Curated Watches**: Section badge, title, subtitle, and maximum count limit.
  - **Promo Story Banner ("Make Your Mark")**: Headline, story, button text/link, warranty accent, and promo image upload.
  - **Section Below Section (Order & Toggle)**: Rearrange the vertical order of sections (move up/down) and toggle section visibility on/off.
  - **Zero-Symlink Upload Architecture**: Images saved directly to `public/uploads/pages/` for 100% InfinityFree shared hosting compatibility.

---

## 5. Key Bugs Fixed & Solutions Applied

| Problem / Bug | Root Cause | Solution Applied |
| :--- | :--- | :--- |
| **Raw JavaScript displayed at top of Checkout** | Alpine JS initialization script had missing HTML wrapping tags and quote conflicts. | Encapsulated Alpine logic cleanly in `checkout.blade.php`. |
| **Casio G-Shock Main Image Broken** | Watch #2 title had unescaped quotes (`"CasiOak"`), breaking inline attributes. | Added fallback `src`, `onerror` handler, and JSON encoding. |
| **Non-Watch Images in Gallery** | Placeholder external links pointed to non-watch items. | Replaced fallback images with genuine local watch photos (`cat_sports.jpg`, `astra-black.png`, `titlon-dive.png`, `saisae-moonphase.png`). |
| **`500 Server Error` on `127.0.0.1:8000`** | Local `.env` pointed to InfinityFree remote MySQL (`sql312.byetcluster.com`), which blocks external connections. | Restored local `.env` to `DB_CONNECTION=sqlite`, `SESSION_DRIVER=file`, `APP_DEBUG=true`. |
| **Blade `@error` Conflict in Shop** | `@error="$el.src = ..."` was compiled as Laravel's `@error` directive. | Replaced with `x-on:error` and native `onerror`. |
| **Breeze CSS & Tailwind v4 Preservation** | Breeze installer overwrote `app.css` and downgraded Tailwind. | Restored 1,160+ lines of dual-theme CSS in `app.css`, kept `@tailwindcss/vite`, and bundled Alpine via npm. |
| **Login Stuck on Loading (Wrong Credentials)** | Stale `artisan serve` process held remote MySQL env vars + stray `'/.'` typo in `AuthenticatedSessionController.php`. | Removed typo and restarted local server with SQLite config. |
| **Card Digits Shown on COD Receipts** | `order-confirmation.blade.php` hardcoded `(•••• 4242)` next to all payment methods. | Updated view & controller so card last-four digits only render for `Card` payments, while `Cash on Delivery` and `Bank Transfer` display cleanly. |

---

## 6. Environment & Hosting Configuration

### A. Local Machine (`.env`)
```ini
APP_NAME=TIMEYRAH
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=sqlite
SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
```

### B. InfinityFree Production Server
- **Hosting Service:** InfinityFree (VistaPanel / ByetHost shared hosting)
- **Upload Location:** `/htdocs/` via **FileZilla FTP**
- **Remote Terminal/SSH:** **None available** (cannot run `npm`, `composer`, or `php artisan` on the server)
- **Database Host:** `sql312.byetcluster.com`
- **Database Name:** `if0_43273400_watches`
- **Database Username:** `if0_43273400`

---

## 7. How to Deploy Updates to InfinityFree (phpMyAdmin + FileZilla)

1. **Import the Database Schema & Watch Catalog on InfinityFree:**
   - Open **VistaPanel** → **phpMyAdmin** → select database `if0_43273400_watches`.
   - Click **Import**, select [`database/timeyrah_schema.sql`](database/timeyrah_schema.sql), and click **Import / Go**.
   - All 8 e-commerce tables and all 17 watches, 20 brands, 6 categories, images, and 2 coupons will be imported safely without affecting existing `users`.
2. **Build Frontend Assets Locally:**
   ```bash
   npm run build
   ```
3. **Clear Local Compiled Caches Before Uploading:**
   ```bash
   php artisan optimize:clear
   ```
4. **Upload Files via FileZilla into `/htdocs/`:**
   - Upload `app/`, `database/`, `resources/views/`, `routes/`, and `public/build/`.
   - **IMPORTANT:** Never overwrite the live `.env` file on InfinityFree with your local `.env`.

---

## 8. Admin CMS System (Atelier Inventory & Dynamic Product Management)

TIMEYRAH includes a built-in luxury Admin CMS atelier accessible at `/admin/*`:
- **Role-Based Protection:** Protected via `EnsureUserIsAdmin` middleware (`'admin'`), checking `users.is_admin = 1`. Unauthorized customers or guests receive an immediate 403 Forbidden or redirect to login.
- **Dynamic Watch Addition & Editing (`/admin/products`):**
  - Create new timepieces (`/admin/products/create`) with live Alpine.js image preview, automatic slug & SKU generation, brand & multi-category association, pricing, sale prices, stock level, and dynamic technical specifications.
  - Edit existing timepieces (`/admin/products/{product}/edit`) with gallery management, primary card photo selector, and instant live store updates.
  - Delete watches (`DELETE /admin/products/{product}`) with automatic disk cleanup of associated images from `public/uploads/watches/`.
- **Collection & Filter Categories CRUD (`/admin/categories`):**
  - View all categories with real-time watch usage counts, search filter, and active status toggles.
  - Create and edit categories with auto-slug generation (e.g. `SOLAR POWERED`, `CERAMIC`, `LIMITED EDITION`).
  - Newly added categories immediately appear in public `/shop` sidebar filters and in watch curation forms.
  - Safe deletion: detaches linked watches without breaking them.
- **Technical Specifications & Options CRUD (`/admin/specifications`):**
  - Full e-commerce specification attributes management (e.g. `Movement`, `Case Material`, `Case Size`, `Crystal Glass`, `Water Resistance`, `Power Reserve`, `Lug Width`, `Bezel Material`).
  - Predefined suggested options per attribute (e.g. `Swiss Automatic`, `316L Stainless Steel`, `80 Hours (Powermatic 80)`) for one-click dropdown suggestions in watch curation forms.
  - Ability to enter any custom specification values and add arbitrary custom specification fields per watch on the fly.
- **Page Content Customizer & Side Panel (`/admin/customizer`):**
  - Full-featured page layout and content customizer with real-time reactive image preview, device-responsive controls, and section reordering.
  - **Hero Header Banner Customization:** Live editing of eyebrow badges, brand title, subtitle, dual accent phrases, body description, and CTA buttons.
  - **Center Hero Watch Multi-Screen Positioning & Uploader:**
    - High-capacity image uploads (supports PNG, JPG, WEBP, AVIF, SVG up to 20MB).
    - 1-click **"↺ Reset to Default"** button to instantly restore the original hero watch.
    - 1-click **"🏪 Pick from Store Catalog"** dropdown to feature any active atelier watch directly.
    - Dedicated device tabs (`🖥️ Desktop`, `📱 Tablet`, `📱 Mobile`) to configure horizontal (`left`), vertical (`top` / `margin-top`), and dimensions (`width`) of the hero watch image across all screen sizes, with intelligent automatic fallbacks when left blank.
  - **SEO & Social Share Meta:** Configure browser page titles, Google snippet descriptions, social sharing banners, and search keywords.
  - **Category Showcase & Promo Banners:** Pick curated categories, customize banner headlines, and set promotional image banners.
- **Zero-Symlink Architecture for InfinityFree:**
  - Image uploads save directly into `public/uploads/watches/`.
  - No `storage:link` required, ensuring complete compatibility with shared hosting.
- **Dual-Theme Luxury Atelier UI:**
  - Styled with the TIMEYRAH dark obsidian & warm light mode aesthetic, with search toolbar, brand & category filter pills, stock badges (out-of-stock, low stock), and quick links to live store views.
- **Admin Navigation Integration:**
  - Authenticated admin users see an "Admin Atelier" badge and direct CMS link in both the navbar dropdown and the mobile navigation drawer, plus top tabs for **Watches / Catalog**, **Categories**, and **Specifications**.

---

## 9. Future Roadmap (Optional Next Enhancements)

- **Admin Order & Coupon Management:** Dedicated screens to manage customer orders (`Pending` → `Processing` → `Delivered`) and create new discount coupon codes.
- **Pakistani Online Payment Gateways:** Optional JazzCash / EasyPaisa / Safepay integration alongside Cash on Delivery.
