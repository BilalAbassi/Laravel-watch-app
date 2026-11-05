<laravel-boost-guidelines>
=== foundation rules ===

# Laravel Boost Guidelines

## Foundational Context

This application is a Laravel application running on PHP 8.3. Always use the APIs that match the installed major version of each package — do not assume a version.

Before relying on a package's API, confirm its installed version:
- PHP packages: run `composer show --direct` to list direct dependencies with versions, or `composer show <vendor/package>` for a single package.
- JS packages: check `package.json` for the installed versions.

## Skills Activation

This project has domain-specific skills available in `**/skills/**`. You MUST activate the relevant skill whenever you work in that domain—don't wait until you're stuck.

## Conventions

- You must follow all existing code conventions used in this application. When creating or editing a file, check sibling files for the correct structure, approach, and naming.
- Use descriptive names for variables and methods. For example, `isRegisteredForDiscounts`, not `discount()`.
- Check for existing components to reuse before writing a new one.

## Verification Scripts

- Do not create verification scripts or tinker when tests cover that functionality and prove they work. Unit and feature tests are more important.

## Application Structure & Architecture

- Stick to existing directory structure; don't create new base folders without approval.
- Do not change the application's dependencies without approval.

## Frontend Bundling

- If a frontend change doesn't show in the UI or you get a "Unable to locate file in Vite manifest" error, run `npm run build` or ask the user to run `npm run dev` or `composer run dev`.

## Documentation Files

- You must only create documentation files if explicitly requested by the user.

=== boost rules ===

# Laravel Boost

## Tools

- Laravel Boost is an MCP server with tools designed specifically for this application. Prefer Boost tools over manual alternatives like shell commands or file reads.
- Use `database-query` to run read-only queries against the database instead of writing raw SQL in tinker.
- Use `database-schema` to inspect table structure before writing migrations or models.
- Use `get-absolute-url` to resolve the correct scheme, domain, and port for project URLs. Always use this before sharing a URL with the user.
- Use `browser-logs` to read browser logs, errors, and exceptions. Only recent logs are useful, ignore old entries.

## Searching Documentation (IMPORTANT)

- Use `search-docs` before changes that depend on Laravel ecosystem APIs, behavior, configuration, or version-specific syntax. Skip it for copy-only edits and other changes where package documentation is irrelevant. Reuse sufficient results already in context instead of searching again.
- Pass a `packages` array to scope results when you know which packages are relevant.
- Use multiple broad, topic-based queries: `['rate limiting', 'routing rate limiting', 'routing']`. Expect the most relevant results first.
- Do not add package names to queries because package info is already shared. Use `test resource table`, not `filament 4 test resource table`.

### Search Syntax

1. Use words for auto-stemmed AND logic: `rate limit` matches both "rate" AND "limit".
2. Use `"quoted phrases"` for exact position matching: `"infinite scroll"` requires adjacent words in order.
3. Combine words and phrases for mixed queries: `middleware "rate limit"`.
4. Use multiple queries for OR logic: `queries=["authentication", "middleware"]`.

## Project Rules

- This project contains committed, area-grouped rules in `.ai/rules` when that directory exists, including path-scoped framework guidelines under `.ai/rules/boost`. Before you enter plan mode or create/edit any file, you MUST first: open @.ai/rules/index.md (it maps file globs to rule files), read every rule file whose globs cover the path(s) in scope, and run `grep -rin 'keyword' .ai/rules` to catch what a path match alone misses. Do not write code until you have read and are following every matching rule. If `.ai/rules` does not exist, continue without it.

## Artisan

- Run Artisan commands directly via the command line (e.g., `php artisan route:list`). Use `php artisan list` to discover available commands and `php artisan [command] --help` to check parameters.
- Inspect routes with `php artisan route:list`. Filter with: `--method=GET`, `--name=users`, `--path=api`, `--except-vendor`, `--only-vendor`.
- Read configuration values using dot notation: `php artisan config:show app.name`, `php artisan config:show database.default`. Or read config files directly from the `config/` directory.

## Tinker

- Execute PHP in app context for debugging and testing code. Do not create models without user approval, prefer tests with factories instead. Prefer existing Artisan commands over custom tinker code.
- Always use single quotes to prevent shell expansion: `php artisan tinker --execute 'Your::code();'`
  - Double quotes for PHP strings inside: `php artisan tinker --execute 'User::where("active", true)->count();'`

=== php rules ===

# PHP

- Always use curly braces for control structures, even for single-line bodies.
- Use PHP 8 constructor property promotion: `public function __construct(public GitHub $github) { }`. Do not leave empty zero-parameter `__construct()` methods unless the constructor is private.
- Use explicit return type declarations and type hints for all method parameters: `function isAccessible(User $user, ?string $path = null): bool`
- Use TitleCase for Enum keys: `FavoritePerson`, `BestLake`, `Monthly`.
- Prefer PHPDoc blocks over inline comments. Only add inline comments for exceptionally complex logic.
- Use array shape type definitions in PHPDoc blocks.

=== deployments rules ===

# Deployment

- Laravel can be deployed using [Laravel Cloud](https://cloud.laravel.com/), which is the fastest way to deploy and scale production Laravel applications.
- Activate the `deploying-to-cloud` skill whenever deploying to Laravel Cloud, configuring Cloud environments or resources, using the Cloud CLI, or troubleshooting Cloud deployments.

=== laravel/core rules ===

# Do Things the Laravel Way

- Use `php artisan make:` commands to create new files (i.e. migrations, controllers, models, etc.). You can list available Artisan commands using `php artisan list` and check their parameters with `php artisan [command] --help`.
- If you're creating a generic PHP class, use `php artisan make:class`.
- Pass `--no-interaction` to all Artisan commands to ensure they work without user input. You should also pass the correct `--options` to ensure correct behavior.

### Model Creation

- When creating new models, create useful factories and seeders for them too. Ask the user if they need any other things, using `php artisan make:model --help` to check the available options.

## APIs & Eloquent Resources

- For APIs, default to using Eloquent API Resources and API versioning unless existing API routes do not, then you should follow existing application convention.

## URL Generation

- When generating links to other pages, prefer named routes and the `route()` function.

## Testing

- When creating models for tests, use the factories for the models. Check if the factory has custom states that can be used before manually setting up the model.
- Faker: Use methods such as `$this->faker->word()` or `fake()->randomDigit()`. Follow existing conventions whether to use `$this->faker` or `fake()`.
- When creating tests, make use of `php artisan make:test [options] {name}` to create a feature test, and pass `--unit` to create a unit test. Most tests should be feature tests.

=== pint/core rules ===

# Laravel Pint Code Formatter

- If you have modified any PHP files, you must run `vendor/bin/pint --dirty --format agent` before finalizing changes to ensure your code matches the project's expected style.
- Do not run `vendor/bin/pint --test --format agent`, simply run `vendor/bin/pint --format agent` to fix any formatting issues.

=== phpunit/core rules ===

# PHPUnit

- This project uses PHPUnit. Create tests with `php artisan make:test --phpunit {name}`.
- Do not include the test suite directory in `{name}`. Use `SomeFeatureTest`, not `Feature/SomeFeatureTest`.
- Read the `testing-best-practices` skill for guidance on coverage, naming, structure, dependency isolation, and review.

## Running Tests

- Run the narrowest set of tests that covers the change. Pass a file path or `--filter=testName` to `php artisan test --compact`.
- Rerun a test after each change to it.
- Run `vendor/bin/phpunit` to call the test runner directly. It accepts the same file path and `--filter=testName` arguments.

=== project memory & deployment guidelines ===

# Project Deployment & Environment Memory

- **Target Audience & Tone**:
  - Target audience is in Pakistan.
  - Always use simple, clear, and uncomplicated wording (avoid unnecessarily complex terms).
  - Currency format: USD with Pakistani localized addresses, major cities (Karachi, Lahore, Islamabad, Rawalpindi, etc.), and phone formats (+92 300 1234567).

- **Hosting Environment**:
  - Platform: **InfinityFree** (Free shared hosting on VistaPanel / ByetHost).
  - Live FTP / Upload method: **FileZilla FTP** into the `htdocs` directory.
  - Remote server has **NO SSH / Terminal access** (cannot run `composer`, `npm`, or `php artisan` on InfinityFree).
  - Always compile frontend assets locally (`npm run build`) before packing/uploading.
  - Root `.htaccess` and root `index.php` route traffic directly into `public/`.
  - Use `SESSION_DRIVER=file` and `CACHE_STORE=file` for shared hosting stability.

- **Live Database Setup**:
  - Host: `sql312.byetcluster.com`
  - Database: `if0_43273400_watches`
  - Username: `if0_43273400`
  - Standard Laravel tables (9): `users`, `sessions`, `password_reset_tokens`, `cache`, `cache_locks`, `jobs`, `job_batches`, `failed_jobs`, `migrations`.
  - E-Commerce tables (11): `brands`, `categories`, `products`, `product_images`, `category_product`, `coupons`, `orders`, `order_items`, `spec_attributes`, `spec_attribute_values`, `product_specifications`.
  - **Schema Reference**: Read `DATABASE_SCHEMA.md` in the root workspace for every table, column, type, and Eloquent relationship.
  - **InfinityFree SQL Import**: Import `database/timeyrah_schema.sql` via phpMyAdmin into `if0_43273400_watches` to create/update all 11 e-commerce tables and seed the 20 brands, 6 categories, 17 curated watches, watch gallery images, technical specification attributes & options, and 2 promo coupons (`TIMEYRAH10`, `VIP2026`).
  - **Never overwrite or break the live `.env` file** on the server during FileZilla updates.

- **E-Commerce Backend Architecture**:
  - Models (`app/Models/`): `Brand`, `Category`, `Product`, `ProductImage`, `Coupon`, `Order`, `OrderItem`, `User`, `SpecAttribute`, `SpecAttributeValue`, `ProductSpecification`.
  - Enums (`app/Enums/`): `OrderStatus` (`Pending`, `Processing`, `Delivered`, `Cancelled`), `PaymentMethod` (`COD`, `Bank`, `Card`), `CouponType` (`percent`, `fixed`).
  - Order Processing: `CheckoutRequest` validates customer & Pakistani delivery fields; `App\Actions\PlaceOrder` runs inside a DB transaction with row locking (`lockForUpdate`), verifies stock & decrements inventory, calculates authoritative totals from DB prices & `Coupon` records, and saves `Order` + `OrderItem` rows.
  - Seeders (`database/seeders/`): `ProductSeeder` (20 brands, 6 categories, 17 watches with exact IDs 1–17), `CouponSeeder` (`TIMEYRAH10`, `VIP2026`), and `SpecAttributeSeeder` (10 technical specification attributes with predefined horological options).

- **Authentication & Admin CMS Strategy**:
  - **Laravel Breeze** is installed and 100% active.
  - Role-based CMS access: `users.is_admin` boolean flag (`true` for Atelier admins).
  - Admin Atelier CMS (`/admin/*`):
    - `/admin/products`: dynamic product creation, editing, photo management, inventory/stock tracking, and category syncing.
    - `/admin/categories`: full CRUD for collection and filter categories with real-time watch usage counts, auto-slugs, and public filter syncing.
    - `/admin/specifications`: full CRUD for technical specification attributes (Movement, Case Material, Power Reserve, etc.) and predefined reusable options for watch curation forms.
  - Public uploads path: `public/uploads/watches/` stores watch photos directly without symlinks (`storage:link`), maintaining 100% InfinityFree compatibility.
  - Custom luxury TIMEYRAH dual-theme (Dark & Light mode) styling is preserved across `/login`, `/register`, `/forgot-password`, `/reset-password`, `/dashboard`, `/profile`, and `/admin/*`.
  - Customer dashboard (`/dashboard` via `DashboardController`) displays live order stats, order history with status badges & receipt links, active store coupons, and featured watches; profile settings (`/profile`) use matching TIMEYRAH cards and controls.
  - Full PHPUnit feature test coverage across Auth, Profile, Dashboard, Shop Catalog, Checkout, and Admin CMS (`tests/Feature/Auth/*`, `tests/Feature/ProfileTest.php`, `tests/Feature/DashboardControllerTest.php`, `tests/Feature/ShopCatalogTest.php`, `tests/Feature/CheckoutOrderTest.php`, `tests/Feature/Admin/ProductManagementTest.php`, `tests/Feature/Admin/CategoryManagementTest.php`, `tests/Feature/Admin/SpecAttributeManagementTest.php`).
  - Standard `users` and `password_reset_tokens` tables already exist both locally (SQLite) and on InfinityFree MySQL (`sql312.byetcluster.com`).

- **Project Documentation & Complete Memory**:
  - Refer to `PROJECT_OVERVIEW.md` for complete sitemap, feature breakdowns, resolved bugs history, and deployment steps, and `DATABASE_SCHEMA.md` for full table and relationship specs.

</laravel-boost-guidelines>
