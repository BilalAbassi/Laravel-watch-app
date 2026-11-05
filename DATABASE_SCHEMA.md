# TIMEYRAH Database Schema Reference

This document is the single source of truth for every database table, column, data type, and Eloquent relationship in **TIMEYRAH**.

- **Local Development Database**: SQLite (`database/database.sqlite`)
- **Live Hosting Database (InfinityFree)**: MySQL (`sql312.byetcluster.com` → `if0_43273400_watches`)
- **1-Click phpMyAdmin Import File**: [`database/timeyrah_schema.sql`](database/timeyrah_schema.sql)

---

## Entity-Relationship Overview

```
users (1) ─────────────── (0..many) orders (1) ─────────────── (1..many) order_items
                                                                              │
brands (1) ────────────── (0..many) products (1) ───────────── (0..many) ─────┘
                                       │
categories (1) ────────── (0..many) ───┤ (primary category_id)
categories (many) ─────── (many) ──────┤ (via category_product pivot)
                                       │
                                    (0..many) product_images

coupons (standalone lookup table checked by coupon_code during checkout)
```

---

## Eloquent Relationships Summary

| Model | Method | Relationship Type | Related Model | Notes |
| :--- | :--- | :--- | :--- | :--- |
| `App\Models\Brand` | `products()` | `hasMany` | `Product` | All watches made by this brand (`products.brand_id`) |
| `App\Models\Category` | `primaryProducts()` | `hasMany` | `Product` | Watches whose main category is this category (`products.category_id`) |
| `App\Models\Category` | `products()` | `belongsToMany` | `Product` | All watches tagged with this category via `category_product` |
| `App\Models\Product` | `brand()` | `belongsTo` | `Brand` | Maker of the watch (`brand_id`) |
| `App\Models\Product` | `category()` | `belongsTo` | `Category` | Main category of the watch (`category_id`) |
| `App\Models\Product` | `categories()` | `belongsToMany` | `Category` | Multi-tag filter categories (`STRAP`, `ANALOG`, `QUARTZ`, etc.) |
| `App\Models\Product` | `images()` | `hasMany` | `ProductImage` | Watch gallery photos ordered by `sort_order` |
| `App\Models\ProductImage` | `product()` | `belongsTo` | `Product` | Parent watch (`product_id`) |
| `App\Models\User` | `orders()` | `hasMany` | `Order` | Orders placed while signed in (`orders.user_id`) |
| `App\Models\Order` | `user()` | `belongsTo` | `User` | Signed-in customer account (`NULL` for guest orders) |
| `App\Models\Order` | `items()` | `hasMany` | `OrderItem` | Line items saved inside the order (`order_items.order_id`) |
| `App\Models\OrderItem` | `order()` | `belongsTo` | `Order` | Parent order (`order_id`) |
| `App\Models\OrderItem` | `product()` | `belongsTo` | `Product` | Linked catalog watch (`product_id`, `NULL` if deleted later) |
| `App\Models\Product` | `specifications()` | `hasMany` | `ProductSpecification` | Dynamic horology specs saved on this watch |
| `App\Models\Product` | `specAttributes()` | `belongsToMany` | `SpecAttribute` | Attributes linked via `product_specifications` pivot |
| `App\Models\SpecAttribute` | `values()` | `hasMany` | `SpecAttributeValue` | Predefined suggested options for quick selection |
| `App\Models\SpecAttribute` | `products()` | `belongsToMany` | `Product` | Watches using this attribute via `product_specifications` |
| `App\Models\SpecAttributeValue` | `attribute()` | `belongsTo` | `SpecAttribute` | Parent attribute (`spec_attribute_id`) |
| `App\Models\ProductSpecification` | `product()` | `belongsTo` | `Product` | Target watch (`product_id`) |
| `App\Models\ProductSpecification` | `attribute()` | `belongsTo` | `SpecAttribute` | Attribute definition (`spec_attribute_id`) |

---

## E-Commerce Tables (8 Tables)

### 1. `brands`
Stores all watch brands shown in the navbar mega-menu and shop filter sidebar (seeded with 20 brands: Rado, Citizen, Seiko, Casio G Shock, Omax, Fossil, etc.).

| Column | MySQL Type | Nullable | Default | Description |
| :--- | :--- | :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED` | No | Auto Increment | Primary key |
| `name` | `VARCHAR(100)` | No | — | Unique brand name (e.g. `Casio G Shock`) |
| `slug` | `VARCHAR(120)` | No | — | Unique URL-friendly slug (e.g. `casio-g-shock`) |
| `logo` | `VARCHAR(255)` | Yes | `NULL` | Optional path or URL to brand logo |
| `is_active` | `TINYINT(1)` | No | `1` | `1` = visible in store, `0` = hidden |
| `created_at` | `TIMESTAMP` | Yes | `NULL` | Created date/time |
| `updated_at` | `TIMESTAMP` | Yes | `NULL` | Last updated date/time |

---

### 2. `categories`
Stores watch filter categories (`STRAP`, `METAL CHAIN`, `ANALOG`, `QUARTZ`, `DIGITAL`, `AUTOMATIC`).

| Column | MySQL Type | Nullable | Default | Description |
| :--- | :--- | :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED` | No | Auto Increment | Primary key |
| `name` | `VARCHAR(100)` | No | — | Unique category code (e.g. `AUTOMATIC`) |
| `slug` | `VARCHAR(120)` | No | — | Unique URL slug (e.g. `automatic`) |
| `is_active` | `TINYINT(1)` | No | `1` | `1` = visible in filter list, `0` = hidden |
| `created_at` | `TIMESTAMP` | Yes | `NULL` | Created date/time |
| `updated_at` | `TIMESTAMP` | Yes | `NULL` | Last updated date/time |

---

### 3. `products`
Stores every watch in the TIMEYRAH catalog, including pricing, stock count, and technical watch specifications.
> **Pricing Note**: `price` is the regular retail price, and `sale_price` is the discounted selling price (when on sale). Use `$product->current_price` in PHP to get the active price a customer pays.

| Column | MySQL Type | Nullable | Default | Description |
| :--- | :--- | :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED` | No | Auto Increment | Primary key (IDs 1–17 match curated watches) |
| `title` | `VARCHAR(255)` | No | — | Full watch model title |
| `slug` | `VARCHAR(255)` | No | — | Unique URL slug |
| `sku` | `VARCHAR(50)` | No | — | Unique stock code (e.g. `TY-RAD-1`) |
| `brand_id` | `BIGINT UNSIGNED` | No | — | Foreign key → `brands.id` (`RESTRICT` on delete) |
| `category_id` | `BIGINT UNSIGNED` | Yes | `NULL` | Foreign key → `categories.id` (`SET NULL` on delete) |
| `price` | `DECIMAL(10,2)` | No | — | Regular retail price in USD |
| `sale_price` | `DECIMAL(10,2)` | Yes | `NULL` | Optional discounted price in USD |
| `stock` | `INT UNSIGNED` | No | `0` | Available units in stock (decremented on order) |
| `movement` | `VARCHAR(255)` | Yes | `NULL` | Movement caliber (Quartz, Automatic, Solar, etc.) |
| `case_size` | `VARCHAR(100)` | Yes | `NULL` | Case diameter (e.g. `43.0 mm`) |
| `case_material` | `VARCHAR(255)` | Yes | `NULL` | Case build (316L Steel, Ceramic, Resin, etc.) |
| `strap_type` | `VARCHAR(255)` | Yes | `NULL` | Strap or bracelet description |
| `glass` | `VARCHAR(255)` | Yes | `NULL` | Crystal material (Sapphire, Mineral, Hardlex) |
| `water_resistance` | `VARCHAR(255)` | Yes | `NULL` | Water rating (e.g. `200m / 20 BAR`) |
| `warranty` | `VARCHAR(255)` | Yes | `NULL` | Official warranty coverage |
| `gender` | `VARCHAR(20)` | Yes | `NULL` | `Men's`, `Women's`, or `Unisex` |
| `badge` | `VARCHAR(100)` | Yes | `NULL` | Highlight badge (`Best Seller`, `Solar Powered`, etc.) |
| `rating` | `DECIMAL(3,2)` | No | `0.00` | Average customer rating (`0.00` – `5.00`) |
| `reviews_count` | `INT UNSIGNED` | No | `0` | Number of customer reviews |
| `description` | `TEXT` | Yes | `NULL` | Simple, clear product description |
| `is_featured` | `TINYINT(1)` | No | `0` | `1` = prioritized in Featured sort |
| `is_active` | `TINYINT(1)` | No | `1` | `1` = visible in shop, `0` = hidden |
| `created_at` | `TIMESTAMP` | Yes | `NULL` | Created date/time |
| `updated_at` | `TIMESTAMP` | Yes | `NULL` | Last updated date/time |

---

### 4. `product_images`
Stores one or more gallery photos per watch. Relative paths (like `watches/titlon-dive.png`) resolve via `asset()`, while full `https://` URLs are used directly.

| Column | MySQL Type | Nullable | Default | Description |
| :--- | :--- | :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED` | No | Auto Increment | Primary key |
| `product_id` | `BIGINT UNSIGNED` | No | — | Foreign key → `products.id` (`CASCADE` on delete) |
| `image_path` | `VARCHAR(500)` | No | — | Relative `public/` path or full image URL |
| `is_primary` | `TINYINT(1)` | No | `0` | `1` = main card thumbnail photo |
| `sort_order` | `SMALLINT UNSIGNED` | No | `0` | Display order (`0` first, `1` second, etc.) |
| `created_at` | `TIMESTAMP` | Yes | `NULL` | Created date/time |
| `updated_at` | `TIMESTAMP` | Yes | `NULL` | Last updated date/time |

---

### 5. `category_product` (Pivot Table)
Connects a watch to multiple categories at once (for example, a Casio G-Shock belongs to `DIGITAL`, `ANALOG`, `QUARTZ`, and `STRAP`).

| Column | MySQL Type | Nullable | Default | Description |
| :--- | :--- | :--- | :--- | :--- |
| `category_id` | `BIGINT UNSIGNED` | No | — | Foreign key → `categories.id` (`CASCADE` on delete) |
| `product_id` | `BIGINT UNSIGNED` | No | — | Foreign key → `products.id` (`CASCADE` on delete) |

*Composite Primary Key*: `(category_id, product_id)`

---

### 6. `coupons`
Stores promo discount codes checked during checkout (`TIMEYRAH10` for 10% off and `VIP2026` for $200 off).

| Column | MySQL Type | Nullable | Default | Description |
| :--- | :--- | :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED` | No | Auto Increment | Primary key |
| `code` | `VARCHAR(30)` | No | — | Unique uppercase code (e.g. `TIMEYRAH10`) |
| `type` | `VARCHAR(10)` | No | — | `percent` or `fixed` (`App\Enums\CouponType`) |
| `value` | `DECIMAL(10,2)` | No | — | Percentage (`10.00`) or fixed USD amount (`200.00`) |
| `min_spend` | `DECIMAL(10,2)` | No | `0.00` | Minimum cart subtotal required |
| `expires_at` | `TIMESTAMP` | Yes | `NULL` | Optional expiry date (`NULL` = never expires) |
| `is_active` | `TINYINT(1)` | No | `1` | `1` = active, `0` = disabled |
| `created_at` | `TIMESTAMP` | Yes | `NULL` | Created date/time |
| `updated_at` | `TIMESTAMP` | Yes | `NULL` | Last updated date/time |

---

### 7. `orders`
Stores customer orders for both guest shoppers (`user_id = NULL`) and logged-in customers, tailored for delivery across Pakistani cities (Karachi, Lahore, Islamabad, Rawalpindi, etc.) and Cash on Delivery (`COD`).

| Column | MySQL Type | Nullable | Default | Description |
| :--- | :--- | :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED` | No | Auto Increment | Primary key |
| `order_number` | `VARCHAR(30)` | No | — | Unique receipt code (e.g. `TY-ORD-2026-A84F29C`) |
| `user_id` | `BIGINT UNSIGNED` | Yes | `NULL` | Foreign key → `users.id` (`SET NULL` on delete) |
| `customer_name` | `VARCHAR(255)` | No | — | Customer full name (e.g. `Ali Khan`) |
| `customer_email` | `VARCHAR(255)` | No | — | Email for order receipt & updates |
| `customer_phone` | `VARCHAR(30)` | No | — | Mobile number for courier (`+92 300 1234567`) |
| `delivery_city` | `VARCHAR(100)` | No | — | City (`Karachi`, `Lahore`, `Islamabad`, etc.) |
| `delivery_address` | `TEXT` | No | — | Street, house/flat, and sector/phase address |
| `delivery_state` | `VARCHAR(100)` | Yes | `NULL` | Optional province/state (`Sindh`, `Punjab`, etc.) |
| `postal_code` | `VARCHAR(20)` | Yes | `NULL` | Postal / ZIP code (e.g. `75500`) |
| `country` | `VARCHAR(100)` | No | `'Pakistan'` | Destination country |
| `delivery_notes` | `TEXT` | Yes | `NULL` | Optional nearest landmark or courier instructions |
| `payment_method` | `VARCHAR(10)` | No | `'COD'` | `COD`, `Bank`, or `Card` (`App\Enums\PaymentMethod`) |
| `shipping_method` | `VARCHAR(20)` | Yes | `NULL` | `armored` (Free) or `concierge` ($150 Express) |
| `coupon_code` | `VARCHAR(30)` | Yes | `NULL` | Applied coupon code if used |
| `subtotal` | `DECIMAL(10,2)` | No | `0.00` | Sum of watch line totals before shipping/discount |
| `shipping_cost` | `DECIMAL(10,2)` | No | `0.00` | Courier fee (`0.00` standard, `150.00` express) |
| `discount_amount` | `DECIMAL(10,2)` | No | `0.00` | Coupon savings deducted from subtotal |
| `total_amount` | `DECIMAL(10,2)` | No | — | Final payable total (`subtotal - discount + shipping`) |
| `order_status` | `VARCHAR(20)` | No | `'Pending'` | `Pending`, `Processing`, `Delivered`, `Cancelled` (`App\Enums\OrderStatus`) |
| `engraving` | `VARCHAR(50)` | Yes | `NULL` | Optional custom case-back engraving text |
| `wrist_size` | `VARCHAR(20)` | Yes | `NULL` | Optional bracelet sizing request |
| `created_at` | `TIMESTAMP` | Yes | `NULL` | Order placement timestamp |
| `updated_at` | `TIMESTAMP` | Yes | `NULL` | Last status update timestamp |

---

### 8. `order_items`
Stores each watch purchased inside an order with a snapshot of its title and price at the time of purchase (so future price changes never alter past receipts).

| Column | MySQL Type | Nullable | Default | Description |
| :--- | :--- | :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED` | No | Auto Increment | Primary key |
| `order_id` | `BIGINT UNSIGNED` | No | — | Foreign key → `orders.id` (`CASCADE` on delete) |
| `product_id` | `BIGINT UNSIGNED` | Yes | `NULL` | Foreign key → `products.id` (`SET NULL` on delete) |
| `product_title` | `VARCHAR(255)` | No | — | Snapshot of watch title when ordered |
| `unit_price` | `DECIMAL(10,2)` | No | — | Verified database unit price when ordered |
| `quantity` | `SMALLINT UNSIGNED` | No | — | Number of watches ordered |
| `dial_variant` | `VARCHAR(50)` | Yes | `NULL` | Selected dial/strap option (e.g. `Signature Dial`) |
| `line_total` | `DECIMAL(10,2)` | No | — | `unit_price * quantity` |
| `created_at` | `TIMESTAMP` | Yes | `NULL` | Created date/time |
| `updated_at` | `TIMESTAMP` | Yes | `NULL` | Last updated date/time |

---

## Technical Specifications & Attribute Options (3 Tables)

### 9. `spec_attributes`
Defines horological specification attributes (e.g. `Movement`, `Case Material`, `Case Size`, `Crystal Glass`, `Water Resistance`, `Power Reserve`, `Lug Width`). Managed via Admin CMS (`/admin/specifications`).

| Column | MySQL Type | Nullable | Default | Description |
| :--- | :--- | :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED` | No | Auto Increment | Primary key |
| `name` | `VARCHAR(100)` | No | — | Attribute name (e.g. `Power Reserve`) |
| `slug` | `VARCHAR(120)` | No | — | Unique URL slug (e.g. `power-reserve`) |
| `sort_order` | `INT` | No | `0` | Display sorting priority in watch forms |
| `is_active` | `TINYINT(1)` | No | `1` | `1` = active in watch forms, `0` = hidden |
| `created_at` | `TIMESTAMP` | Yes | `NULL` | Created date/time |
| `updated_at` | `TIMESTAMP` | Yes | `NULL` | Last updated date/time |

---

### 10. `spec_attribute_values`
Predefined suggested options for quick one-click dropdown selection when curating a timepiece.

| Column | MySQL Type | Nullable | Default | Description |
| :--- | :--- | :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED` | No | Auto Increment | Primary key |
| `spec_attribute_id` | `BIGINT UNSIGNED` | No | — | Foreign key → `spec_attributes.id` (`CASCADE` on delete) |
| `value` | `VARCHAR(255)` | No | — | Suggested option (e.g. `80 Hours (Powermatic 80)`) |
| `sort_order` | `INT` | No | `0` | Order in datalist suggestions |
| `created_at` | `TIMESTAMP` | Yes | `NULL` | Created date/time |
| `updated_at` | `TIMESTAMP` | Yes | `NULL` | Last updated date/time |

---

### 11. `product_specifications`
Stores dynamic key-value specifications attached to each watch.

| Column | MySQL Type | Nullable | Default | Description |
| :--- | :--- | :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED` | No | Auto Increment | Primary key |
| `product_id` | `BIGINT UNSIGNED` | No | — | Foreign key → `products.id` (`CASCADE` on delete) |
| `spec_attribute_id` | `BIGINT UNSIGNED` | No | — | Foreign key → `spec_attributes.id` (`CASCADE` on delete) |
| `value` | `VARCHAR(255)` | No | — | Value for this timepiece (from presets or custom) |
| `created_at` | `TIMESTAMP` | Yes | `NULL` | Created date/time |
| `updated_at` | `TIMESTAMP` | Yes | `NULL` | Last updated date/time |

---

### 12. `page_sections`
Stores dynamic page builder content, SEO meta tags, banners, images, category section, and reorderable layout for frontend pages.

| Column | MySQL Type | Nullable | Default | Description |
| :--- | :--- | :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED` | No | Auto Increment | Primary key |
| `page` | `VARCHAR(50)` | No | `'home'` | Page identifier (`home`, `shop`, etc.) |
| `section_key` | `VARCHAR(50)` | No | — | Section identifier (`seo`, `hero_banner`, `shop_by_category`, `featured_watches`, `promo_banner`, `brand_showcase`) |
| `title` | `VARCHAR(255)` | Yes | `NULL` | Main heading or meta title |
| `subtitle` | `TEXT` | Yes | `NULL` | Subheading or meta subtitle |
| `content` | `JSON` | Yes | `NULL` | Structured section details (badges, accents, buttons, category IDs) |
| `image_path` | `VARCHAR(255)` | Yes | `NULL` | Public upload path in `public/uploads/pages/` |
| `sort_order` | `INT` | No | `0` | Order of appearance on the page |
| `is_active` | `TINYINT(1)` | No | `1` | Visibility toggle (1 = visible, 0 = hidden) |
| `created_at` | `TIMESTAMP` | Yes | `NULL` | Created date/time |
| `updated_at` | `TIMESTAMP` | Yes | `NULL` | Last updated date/time |

*Composite Unique Key*: `(page, section_key)` ensures each section key only appears once per page.

---

## Standard Laravel Tables Already on InfinityFree (9 Tables)

These standard Laravel + Breeze tables already exist in both local SQLite and live InfinityFree MySQL (`if0_43273400_watches`):
1. `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`)
1. `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `is_admin`, `remember_token`, `created_at`, `updated_at`)
   - `is_admin` (`TINYINT(1)`, default `0`): Flag designating administrator access to the Atelier CMS (`/admin/products`). When `1`, the user can create, update, manage stock, upload photos, and delete timepieces.
2. `password_reset_tokens` (`email`, `token`, `created_at`)
3. `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`)
4. `cache` (`key`, `value`, `expiration`)
5. `cache_locks` (`key`, `owner`, `expiration`)
6. `jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`)
7. `job_batches` (`id`, `name`, `total_jobs`, `pending_jobs`, `failed_jobs`, `failed_job_ids`, `options`, `cancelled_at`, `created_at`, `finished_at`)
8. `failed_jobs` (`id`, `uuid`, `connection`, `queue`, `payload`, `exception`, `failed_at`)
9. `migrations` (`id`, `migration`, `batch`)

---

## How to Import on InfinityFree (phpMyAdmin)
## Media & Image Storage Architecture

- **Public Upload Directory**: `public/uploads/watches/`
- **Zero-Symlink InfinityFree Compatibility**:
  - Shared hosting on InfinityFree cannot execute `php artisan storage:link`.
  - All admin product image uploads are moved directly into `public/uploads/watches/watch_{id}_{timestamp}_{random}.{ext}`.
  - The `ProductImage::url()` accessor returns `asset($this->image_path)`, resolving directly through `public/` on both local development and live VistaPanel hosting.
  - Deleting an image or a watch in the CMS automatically removes the corresponding physical file from `public/uploads/watches/`.

---

## How to Import & Update on InfinityFree (phpMyAdmin)

Because InfinityFree free shared hosting does not provide SSH/terminal access:
1. Open **VistaPanel** → **phpMyAdmin** → select database `if0_43273400_watches`.
2. Click the **Import** tab at the top.
3. Choose [`database/timeyrah_schema.sql`](database/timeyrah_schema.sql) and click **Import / Go**.
4. All 8 e-commerce tables (`brands`, `categories`, `products`, `product_images`, `category_product`, `coupons`, `orders`, `order_items`) and all seeded watches & coupons will be created without touching existing `users` accounts.
4. All 8 e-commerce tables (`brands`, `categories`, `products`, `product_images`, `category_product`, `coupons`, `orders`, `order_items`) and all seeded watches & coupons will be created/updated.
5. If upgrading an existing `users` table on InfinityFree without dropping it, run this one-line SQL query in phpMyAdmin's **SQL** tab:
   ```sql
   ALTER TABLE `users` ADD COLUMN `is_admin` TINYINT(1) NOT NULL DEFAULT 0 AFTER `password`;
   UPDATE `users` SET `is_admin` = 1 WHERE `email` = 'alexander.sterling@horology.vip';
   ```

