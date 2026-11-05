-- =============================================================================
-- TIMEYRAH Watches E-Commerce Schema & Catalog Seed (MySQL / MariaDB)
-- Target Database: InfinityFree (if0_43273400_watches) via phpMyAdmin Import
-- Safe to import on an existing database: preserves `users` and standard tables
-- =============================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- -----------------------------------------------------------------------------
-- 1. brands
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `brands` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(120) NOT NULL,
  `logo` VARCHAR(255) DEFAULT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `brands_name_unique` (`name`),
  UNIQUE KEY `brands_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- 2. categories
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `categories` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(120) NOT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_name_unique` (`name`),
  UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- 3. products
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `products` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL,
  `sku` VARCHAR(50) NOT NULL,
  `brand_id` BIGINT UNSIGNED NOT NULL,
  `category_id` BIGINT UNSIGNED DEFAULT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  `sale_price` DECIMAL(10,2) DEFAULT NULL,
  `stock` INT UNSIGNED NOT NULL DEFAULT 0,
  `movement` VARCHAR(255) DEFAULT NULL,
  `case_size` VARCHAR(100) DEFAULT NULL,
  `case_material` VARCHAR(255) DEFAULT NULL,
  `strap_type` VARCHAR(255) DEFAULT NULL,
  `glass` VARCHAR(255) DEFAULT NULL,
  `water_resistance` VARCHAR(255) DEFAULT NULL,
  `warranty` VARCHAR(255) DEFAULT NULL,
  `gender` VARCHAR(20) DEFAULT NULL,
  `badge` VARCHAR(100) DEFAULT NULL,
  `rating` DECIMAL(3,2) NOT NULL DEFAULT 0.00,
  `reviews_count` INT UNSIGNED NOT NULL DEFAULT 0,
  `description` TEXT DEFAULT NULL,
  `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_slug_unique` (`slug`),
  UNIQUE KEY `products_sku_unique` (`sku`),
  KEY `products_brand_id_foreign` (`brand_id`),
  KEY `products_category_id_foreign` (`category_id`),
  KEY `products_is_active_is_featured_index` (`is_active`, `is_featured`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- 4. product_images
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `product_images` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` BIGINT UNSIGNED NOT NULL,
  `image_path` VARCHAR(500) NOT NULL,
  `is_primary` TINYINT(1) NOT NULL DEFAULT 0,
  `sort_order` SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_images_product_id_foreign` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- 5. category_product (Many-to-Many Pivot)
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `category_product` (
  `category_id` BIGINT UNSIGNED NOT NULL,
  `product_id` BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (`category_id`, `product_id`),
  KEY `category_product_product_id_foreign` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- 6. coupons
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `coupons` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(30) NOT NULL,
  `type` VARCHAR(10) NOT NULL,
  `value` DECIMAL(10,2) NOT NULL,
  `min_spend` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `expires_at` TIMESTAMP NULL DEFAULT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `coupons_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- 7. orders
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `orders` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_number` VARCHAR(30) NOT NULL,
  `user_id` BIGINT UNSIGNED DEFAULT NULL,
  `customer_name` VARCHAR(255) NOT NULL,
  `customer_email` VARCHAR(255) NOT NULL,
  `customer_phone` VARCHAR(30) NOT NULL,
  `delivery_city` VARCHAR(100) NOT NULL,
  `delivery_address` TEXT NOT NULL,
  `delivery_state` VARCHAR(100) DEFAULT NULL,
  `postal_code` VARCHAR(20) DEFAULT NULL,
  `country` VARCHAR(100) NOT NULL DEFAULT 'Pakistan',
  `delivery_notes` TEXT DEFAULT NULL,
  `payment_method` VARCHAR(10) NOT NULL DEFAULT 'COD',
  `shipping_method` VARCHAR(20) DEFAULT NULL,
  `coupon_code` VARCHAR(30) DEFAULT NULL,
  `subtotal` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `shipping_cost` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `discount_amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `total_amount` DECIMAL(10,2) NOT NULL,
  `order_status` VARCHAR(20) NOT NULL DEFAULT 'Pending',
  `engraving` VARCHAR(50) DEFAULT NULL,
  `wrist_size` VARCHAR(20) DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_order_number_unique` (`order_number`),
  KEY `orders_user_id_index` (`user_id`),
  KEY `orders_order_status_index` (`order_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- 8. order_items
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` BIGINT UNSIGNED NOT NULL,
  `product_id` BIGINT UNSIGNED DEFAULT NULL,
  `product_title` VARCHAR(255) NOT NULL,
  `unit_price` DECIMAL(10,2) NOT NULL,
  `quantity` SMALLINT UNSIGNED NOT NULL,
  `dial_variant` VARCHAR(50) DEFAULT NULL,
  `line_total` DECIMAL(10,2) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_product_id_foreign` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================================================
-- Seed Data: Brands (20), Categories (6), Products (17), Images (32), Coupons (2)
-- =============================================================================

INSERT INTO `brands` (`id`, `name`, `slug`, `logo`, `is_active`, `created_at`, `updated_at`) VALUES (1, 'Rado', 'rado', NULL, 1, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `name` = VALUES(`name`), `slug` = VALUES(`slug`), `logo` = VALUES(`logo`), `is_active` = VALUES(`is_active`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `brands` (`id`, `name`, `slug`, `logo`, `is_active`, `created_at`, `updated_at`) VALUES (2, 'Citizen', 'citizen', NULL, 1, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `name` = VALUES(`name`), `slug` = VALUES(`slug`), `logo` = VALUES(`logo`), `is_active` = VALUES(`is_active`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `brands` (`id`, `name`, `slug`, `logo`, `is_active`, `created_at`, `updated_at`) VALUES (3, 'Seiko', 'seiko', NULL, 1, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `name` = VALUES(`name`), `slug` = VALUES(`slug`), `logo` = VALUES(`logo`), `is_active` = VALUES(`is_active`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `brands` (`id`, `name`, `slug`, `logo`, `is_active`, `created_at`, `updated_at`) VALUES (4, 'Casio General', 'casio-general', NULL, 1, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `name` = VALUES(`name`), `slug` = VALUES(`slug`), `logo` = VALUES(`logo`), `is_active` = VALUES(`is_active`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `brands` (`id`, `name`, `slug`, `logo`, `is_active`, `created_at`, `updated_at`) VALUES (5, 'Casio Edifice', 'casio-edifice', NULL, 1, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `name` = VALUES(`name`), `slug` = VALUES(`slug`), `logo` = VALUES(`logo`), `is_active` = VALUES(`is_active`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `brands` (`id`, `name`, `slug`, `logo`, `is_active`, `created_at`, `updated_at`) VALUES (6, 'Casio G Shock', 'casio-g-shock', NULL, 1, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `name` = VALUES(`name`), `slug` = VALUES(`slug`), `logo` = VALUES(`logo`), `is_active` = VALUES(`is_active`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `brands` (`id`, `name`, `slug`, `logo`, `is_active`, `created_at`, `updated_at`) VALUES (7, 'Omax General', 'omax-general', NULL, 1, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `name` = VALUES(`name`), `slug` = VALUES(`slug`), `logo` = VALUES(`logo`), `is_active` = VALUES(`is_active`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `brands` (`id`, `name`, `slug`, `logo`, `is_active`, `created_at`, `updated_at`) VALUES (8, 'Omax Masterpiece', 'omax-masterpiece', NULL, 1, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `name` = VALUES(`name`), `slug` = VALUES(`slug`), `logo` = VALUES(`logo`), `is_active` = VALUES(`is_active`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `brands` (`id`, `name`, `slug`, `logo`, `is_active`, `created_at`, `updated_at`) VALUES (9, 'Omax Premium', 'omax-premium', NULL, 1, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `name` = VALUES(`name`), `slug` = VALUES(`slug`), `logo` = VALUES(`logo`), `is_active` = VALUES(`is_active`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `brands` (`id`, `name`, `slug`, `logo`, `is_active`, `created_at`, `updated_at`) VALUES (10, 'Mathey Tissot', 'mathey-tissot', NULL, 1, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `name` = VALUES(`name`), `slug` = VALUES(`slug`), `logo` = VALUES(`logo`), `is_active` = VALUES(`is_active`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `brands` (`id`, `name`, `slug`, `logo`, `is_active`, `created_at`, `updated_at`) VALUES (11, 'Ferro', 'ferro', NULL, 1, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `name` = VALUES(`name`), `slug` = VALUES(`slug`), `logo` = VALUES(`logo`), `is_active` = VALUES(`is_active`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `brands` (`id`, `name`, `slug`, `logo`, `is_active`, `created_at`, `updated_at`) VALUES (12, 'Crysma', 'crysma', NULL, 1, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `name` = VALUES(`name`), `slug` = VALUES(`slug`), `logo` = VALUES(`logo`), `is_active` = VALUES(`is_active`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `brands` (`id`, `name`, `slug`, `logo`, `is_active`, `created_at`, `updated_at`) VALUES (13, 'Kenneth Cole', 'kenneth-cole', NULL, 1, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `name` = VALUES(`name`), `slug` = VALUES(`slug`), `logo` = VALUES(`logo`), `is_active` = VALUES(`is_active`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `brands` (`id`, `name`, `slug`, `logo`, `is_active`, `created_at`, `updated_at`) VALUES (14, 'Fossil', 'fossil', NULL, 1, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `name` = VALUES(`name`), `slug` = VALUES(`slug`), `logo` = VALUES(`logo`), `is_active` = VALUES(`is_active`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `brands` (`id`, `name`, `slug`, `logo`, `is_active`, `created_at`, `updated_at`) VALUES (15, 'Daniel Klein', 'daniel-klein', NULL, 1, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `name` = VALUES(`name`), `slug` = VALUES(`slug`), `logo` = VALUES(`logo`), `is_active` = VALUES(`is_active`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `brands` (`id`, `name`, `slug`, `logo`, `is_active`, `created_at`, `updated_at`) VALUES (16, 'Swiss Military Hanowa', 'swiss-military-hanowa', NULL, 1, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `name` = VALUES(`name`), `slug` = VALUES(`slug`), `logo` = VALUES(`logo`), `is_active` = VALUES(`is_active`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `brands` (`id`, `name`, `slug`, `logo`, `is_active`, `created_at`, `updated_at`) VALUES (17, 'Santa Barbara', 'santa-barbara', NULL, 1, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `name` = VALUES(`name`), `slug` = VALUES(`slug`), `logo` = VALUES(`logo`), `is_active` = VALUES(`is_active`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `brands` (`id`, `name`, `slug`, `logo`, `is_active`, `created_at`, `updated_at`) VALUES (18, 'Rolex', 'rolex', NULL, 1, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `name` = VALUES(`name`), `slug` = VALUES(`slug`), `logo` = VALUES(`logo`), `is_active` = VALUES(`is_active`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `brands` (`id`, `name`, `slug`, `logo`, `is_active`, `created_at`, `updated_at`) VALUES (19, 'Longines', 'longines', NULL, 1, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `name` = VALUES(`name`), `slug` = VALUES(`slug`), `logo` = VALUES(`logo`), `is_active` = VALUES(`is_active`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `brands` (`id`, `name`, `slug`, `logo`, `is_active`, `created_at`, `updated_at`) VALUES (20, 'IWC', 'iwc', NULL, 1, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `name` = VALUES(`name`), `slug` = VALUES(`slug`), `logo` = VALUES(`logo`), `is_active` = VALUES(`is_active`), `updated_at` = VALUES(`updated_at`);

INSERT INTO `categories` (`id`, `name`, `slug`, `is_active`, `created_at`, `updated_at`) VALUES (1, 'STRAP', 'strap', 1, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `name` = VALUES(`name`), `slug` = VALUES(`slug`), `is_active` = VALUES(`is_active`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `categories` (`id`, `name`, `slug`, `is_active`, `created_at`, `updated_at`) VALUES (2, 'METAL CHAIN', 'metal-chain', 1, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `name` = VALUES(`name`), `slug` = VALUES(`slug`), `is_active` = VALUES(`is_active`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `categories` (`id`, `name`, `slug`, `is_active`, `created_at`, `updated_at`) VALUES (3, 'ANALOG', 'analog', 1, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `name` = VALUES(`name`), `slug` = VALUES(`slug`), `is_active` = VALUES(`is_active`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `categories` (`id`, `name`, `slug`, `is_active`, `created_at`, `updated_at`) VALUES (4, 'QUARTZ', 'quartz', 1, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `name` = VALUES(`name`), `slug` = VALUES(`slug`), `is_active` = VALUES(`is_active`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `categories` (`id`, `name`, `slug`, `is_active`, `created_at`, `updated_at`) VALUES (5, 'DIGITAL', 'digital', 1, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `name` = VALUES(`name`), `slug` = VALUES(`slug`), `is_active` = VALUES(`is_active`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `categories` (`id`, `name`, `slug`, `is_active`, `created_at`, `updated_at`) VALUES (6, 'AUTOMATIC', 'automatic', 1, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `name` = VALUES(`name`), `slug` = VALUES(`slug`), `is_active` = VALUES(`is_active`), `updated_at` = VALUES(`updated_at`);

INSERT INTO `products` (`id`, `title`, `slug`, `sku`, `brand_id`, `category_id`, `price`, `sale_price`, `stock`, `movement`, `case_size`, `case_material`, `strap_type`, `glass`, `water_resistance`, `warranty`, `gender`, `badge`, `rating`, `reviews_count`, `description`, `is_featured`, `is_active`, `created_at`, `updated_at`) VALUES (1, 'Rado Captain Cook High-Tech Ceramic Diver', 'rado-captain-cook-high-tech-ceramic-diver', 'TY-RAD-1', 1, 6, 4200.00, 3850.00, 14, 'Rado Calibre R763 (80-hour reserve)', '43.0 mm', 'Monobloc High-Tech Ceramic', 'Matte Plasma Ceramic Bracelet', 'Domed Sapphire Crystal with double Anti-Reflective coating', '300m / 30 BAR Diver', '5 Years International Warranty', 'Men\'s', 'High-Tech Ceramic', 4.95, 64, 'The Captain Cook High-Tech Ceramic is the culmination of years of research and development. Featuring Rados innovative ceramic monobloc case and an automatic caliber with 80 hours power reserve.', 1, 1, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `title` = VALUES(`title`), `slug` = VALUES(`slug`), `sku` = VALUES(`sku`), `brand_id` = VALUES(`brand_id`), `category_id` = VALUES(`category_id`), `price` = VALUES(`price`), `sale_price` = VALUES(`sale_price`), `stock` = VALUES(`stock`), `movement` = VALUES(`movement`), `case_size` = VALUES(`case_size`), `case_material` = VALUES(`case_material`), `strap_type` = VALUES(`strap_type`), `glass` = VALUES(`glass`), `water_resistance` = VALUES(`water_resistance`), `warranty` = VALUES(`warranty`), `gender` = VALUES(`gender`), `badge` = VALUES(`badge`), `rating` = VALUES(`rating`), `reviews_count` = VALUES(`reviews_count`), `description` = VALUES(`description`), `is_featured` = VALUES(`is_featured`), `is_active` = VALUES(`is_active`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `products` (`id`, `title`, `slug`, `sku`, `brand_id`, `category_id`, `price`, `sale_price`, `stock`, `movement`, `case_size`, `case_material`, `strap_type`, `glass`, `water_resistance`, `warranty`, `gender`, `badge`, `rating`, `reviews_count`, `description`, `is_featured`, `is_active`, `created_at`, `updated_at`) VALUES (2, 'Casio G-Shock GA-2100-1A1 Carbon Core "CasiOak"', 'casio-g-shock-ga-2100-1a1-carbon-core-casioak', 'TY-CAS-2', 6, 5, 135.00, 110.00, 45, 'Casio Module 5611 Multifunction Quartz', '45.4 mm (thin 11.8mm depth)', 'Carbon-Reinforced Fine Resin', 'Resin Band with Matte Black Finish', 'Mineral Glass', '200m / 20 BAR Shock Resistant', '2 Years Official Casio Warranty', 'Unisex', 'Best Seller', 4.90, 284, 'The iconic all-black stealth CasiOak with Carbon Core Guard structure. Slim 11.8mm profile, double LED lighting, 200m water resistance, and unmatched shock-absorbing toughness.', 1, 1, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `title` = VALUES(`title`), `slug` = VALUES(`slug`), `sku` = VALUES(`sku`), `brand_id` = VALUES(`brand_id`), `category_id` = VALUES(`category_id`), `price` = VALUES(`price`), `sale_price` = VALUES(`sale_price`), `stock` = VALUES(`stock`), `movement` = VALUES(`movement`), `case_size` = VALUES(`case_size`), `case_material` = VALUES(`case_material`), `strap_type` = VALUES(`strap_type`), `glass` = VALUES(`glass`), `water_resistance` = VALUES(`water_resistance`), `warranty` = VALUES(`warranty`), `gender` = VALUES(`gender`), `badge` = VALUES(`badge`), `rating` = VALUES(`rating`), `reviews_count` = VALUES(`reviews_count`), `description` = VALUES(`description`), `is_featured` = VALUES(`is_featured`), `is_active` = VALUES(`is_active`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `products` (`id`, `title`, `slug`, `sku`, `brand_id`, `category_id`, `price`, `sale_price`, `stock`, `movement`, `case_size`, `case_material`, `strap_type`, `glass`, `water_resistance`, `warranty`, `gender`, `badge`, `rating`, `reviews_count`, `description`, `is_featured`, `is_active`, `created_at`, `updated_at`) VALUES (3, 'Seiko Prospex Speedtimer Solar Chronograph SSC813', 'seiko-prospex-speedtimer-solar-chronograph-ssc813', 'TY-SEI-3', 3, 2, 750.00, 675.00, 20, 'Seiko Solar Caliber V192 Chronograph', '39.0 mm', '316L Stainless Steel', 'Three-Link Steel Bracelet', 'Curved Sapphire Crystal with Anti-Reflective coating', '100m / 10 BAR', '3 Years International Seiko Warranty', 'Men\'s', 'Solar Powered', 4.92, 119, 'The celebrated "Seitona" Panda dial chronograph. Solar-powered caliber V192 charges from any light source with a 6-month reserve when fully charged.', 1, 1, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `title` = VALUES(`title`), `slug` = VALUES(`slug`), `sku` = VALUES(`sku`), `brand_id` = VALUES(`brand_id`), `category_id` = VALUES(`category_id`), `price` = VALUES(`price`), `sale_price` = VALUES(`sale_price`), `stock` = VALUES(`stock`), `movement` = VALUES(`movement`), `case_size` = VALUES(`case_size`), `case_material` = VALUES(`case_material`), `strap_type` = VALUES(`strap_type`), `glass` = VALUES(`glass`), `water_resistance` = VALUES(`water_resistance`), `warranty` = VALUES(`warranty`), `gender` = VALUES(`gender`), `badge` = VALUES(`badge`), `rating` = VALUES(`rating`), `reviews_count` = VALUES(`reviews_count`), `description` = VALUES(`description`), `is_featured` = VALUES(`is_featured`), `is_active` = VALUES(`is_active`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `products` (`id`, `title`, `slug`, `sku`, `brand_id`, `category_id`, `price`, `sale_price`, `stock`, `movement`, `case_size`, `case_material`, `strap_type`, `glass`, `water_resistance`, `warranty`, `gender`, `badge`, `rating`, `reviews_count`, `description`, `is_featured`, `is_active`, `created_at`, `updated_at`) VALUES (4, 'Citizen Tsuyosa Automatic Sunray Yellow Dial', 'citizen-tsuyosa-automatic-sunray-yellow-dial', 'TY-CIT-4', 2, 6, 495.00, 450.00, 18, 'Citizen Miyota Caliber 8210 Automatic', '40.0 mm', 'Stainless Steel with Crown at 4 oclock', 'Integrated Stainless Steel Rounded Links', 'Sapphire Crystal with Date Magnifier', '50m / 5 BAR', '5 Years Official Citizen Warranty', 'Men\'s', 'Integrated Bracelet', 4.88, 87, 'The Citizen Tsuyosa delivers ultra-contemporary sports-chic styling with a vibrant sunray dial, exhibition case back, and integrated stainless steel president-style bracelet.', 1, 1, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `title` = VALUES(`title`), `slug` = VALUES(`slug`), `sku` = VALUES(`sku`), `brand_id` = VALUES(`brand_id`), `category_id` = VALUES(`category_id`), `price` = VALUES(`price`), `sale_price` = VALUES(`sale_price`), `stock` = VALUES(`stock`), `movement` = VALUES(`movement`), `case_size` = VALUES(`case_size`), `case_material` = VALUES(`case_material`), `strap_type` = VALUES(`strap_type`), `glass` = VALUES(`glass`), `water_resistance` = VALUES(`water_resistance`), `warranty` = VALUES(`warranty`), `gender` = VALUES(`gender`), `badge` = VALUES(`badge`), `rating` = VALUES(`rating`), `reviews_count` = VALUES(`reviews_count`), `description` = VALUES(`description`), `is_featured` = VALUES(`is_featured`), `is_active` = VALUES(`is_active`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `products` (`id`, `title`, `slug`, `sku`, `brand_id`, `category_id`, `price`, `sale_price`, `stock`, `movement`, `case_size`, `case_material`, `strap_type`, `glass`, `water_resistance`, `warranty`, `gender`, `badge`, `rating`, `reviews_count`, `description`, `is_featured`, `is_active`, `created_at`, `updated_at`) VALUES (5, 'Casio Edifice Solar Bluetooth Chronograph ECB-900', 'casio-edifice-solar-bluetooth-chronograph-ecb-900', 'TY-CAS-5', 5, 2, 260.00, 220.00, 22, 'Tough Solar Quartz with Bluetooth Link', '48.0 mm', 'Stainless Steel with Tachymeter Bezel', 'Solid Stainless Steel Band', 'Mineral Crystal Glass', '100m / 10 BAR', '2 Years Official Warranty', 'Men\'s', 'Bluetooth Sync', 4.82, 53, 'Motorsport-inspired speed and intelligence. Connects seamlessly with your smartphone for automatic time zone correction and lap time measurement down to 1/1000th second.', 0, 1, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `title` = VALUES(`title`), `slug` = VALUES(`slug`), `sku` = VALUES(`sku`), `brand_id` = VALUES(`brand_id`), `category_id` = VALUES(`category_id`), `price` = VALUES(`price`), `sale_price` = VALUES(`sale_price`), `stock` = VALUES(`stock`), `movement` = VALUES(`movement`), `case_size` = VALUES(`case_size`), `case_material` = VALUES(`case_material`), `strap_type` = VALUES(`strap_type`), `glass` = VALUES(`glass`), `water_resistance` = VALUES(`water_resistance`), `warranty` = VALUES(`warranty`), `gender` = VALUES(`gender`), `badge` = VALUES(`badge`), `rating` = VALUES(`rating`), `reviews_count` = VALUES(`reviews_count`), `description` = VALUES(`description`), `is_featured` = VALUES(`is_featured`), `is_active` = VALUES(`is_active`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `products` (`id`, `title`, `slug`, `sku`, `brand_id`, `category_id`, `price`, `sale_price`, `stock`, `movement`, `case_size`, `case_material`, `strap_type`, `glass`, `water_resistance`, `warranty`, `gender`, `badge`, `rating`, `reviews_count`, `description`, `is_featured`, `is_active`, `created_at`, `updated_at`) VALUES (6, 'Casio Vintage Digital Illuminator A168WA', 'casio-vintage-digital-illuminator-a168wa', 'TY-CAS-6', 4, 5, 45.00, 38.00, 120, 'Casio Module 3298 Digital Quartz (7-year battery)', '36.3 mm', 'Chrome Plated Resin Case', 'Adjustable Stainless Steel Mesh Clasp', 'Resin Glass', 'Daily Splash Resistant', '2 Years Official Warranty', 'Unisex', 'Classic Icon', 4.94, 512, 'The timeless 80s retro digital masterpiece. Features Casio electroluminescent backlight, daily alarm, 1/100s stopwatch, and auto calendar.', 0, 1, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `title` = VALUES(`title`), `slug` = VALUES(`slug`), `sku` = VALUES(`sku`), `brand_id` = VALUES(`brand_id`), `category_id` = VALUES(`category_id`), `price` = VALUES(`price`), `sale_price` = VALUES(`sale_price`), `stock` = VALUES(`stock`), `movement` = VALUES(`movement`), `case_size` = VALUES(`case_size`), `case_material` = VALUES(`case_material`), `strap_type` = VALUES(`strap_type`), `glass` = VALUES(`glass`), `water_resistance` = VALUES(`water_resistance`), `warranty` = VALUES(`warranty`), `gender` = VALUES(`gender`), `badge` = VALUES(`badge`), `rating` = VALUES(`rating`), `reviews_count` = VALUES(`reviews_count`), `description` = VALUES(`description`), `is_featured` = VALUES(`is_featured`), `is_active` = VALUES(`is_active`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `products` (`id`, `title`, `slug`, `sku`, `brand_id`, `category_id`, `price`, `sale_price`, `stock`, `movement`, `case_size`, `case_material`, `strap_type`, `glass`, `water_resistance`, `warranty`, `gender`, `badge`, `rating`, `reviews_count`, `description`, `is_featured`, `is_active`, `created_at`, `updated_at`) VALUES (7, 'Mathey-Tissot Mathy Vintage Automatic GMT H903ATAN', 'mathey-tissot-mathy-vintage-automatic-gmt-h903atan', 'TY-MAT-7', 10, 6, 690.00, 590.00, 12, 'Swiss Automatic Caliber with GMT Hand', '42.0 mm', '316L Stainless Steel', 'Jubilee-style Stainless Steel Bracelet', 'Sapphire Coated Crystal Glass', '100m / 10 BAR', '3 Years International Warranty', 'Men\'s', 'Swiss Made', 4.85, 42, 'Swiss horological pedigree since 1886. Robust 42mm diver design with bi-directional 24-hour Pepsi bezel and Swiss automatic caliber with GMT dual time indication.', 1, 1, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `title` = VALUES(`title`), `slug` = VALUES(`slug`), `sku` = VALUES(`sku`), `brand_id` = VALUES(`brand_id`), `category_id` = VALUES(`category_id`), `price` = VALUES(`price`), `sale_price` = VALUES(`sale_price`), `stock` = VALUES(`stock`), `movement` = VALUES(`movement`), `case_size` = VALUES(`case_size`), `case_material` = VALUES(`case_material`), `strap_type` = VALUES(`strap_type`), `glass` = VALUES(`glass`), `water_resistance` = VALUES(`water_resistance`), `warranty` = VALUES(`warranty`), `gender` = VALUES(`gender`), `badge` = VALUES(`badge`), `rating` = VALUES(`rating`), `reviews_count` = VALUES(`reviews_count`), `description` = VALUES(`description`), `is_featured` = VALUES(`is_featured`), `is_active` = VALUES(`is_active`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `products` (`id`, `title`, `slug`, `sku`, `brand_id`, `category_id`, `price`, `sale_price`, `stock`, `movement`, `case_size`, `case_material`, `strap_type`, `glass`, `water_resistance`, `warranty`, `gender`, `badge`, `rating`, `reviews_count`, `description`, `is_featured`, `is_active`, `created_at`, `updated_at`) VALUES (8, 'Fossil Grant Chronograph Luggage Leather FS4813', 'fossil-grant-chronograph-luggage-leather-fs4813', 'TY-FOS-8', 14, 1, 170.00, 145.00, 30, 'Precision Quartz Chronograph (Sub-dials for 24h & stop-second)', '44.0 mm', 'Polished Stainless Steel', '22mm Genuine Calfskin Leather with Buckle', 'Mineral Hardened Crystal', '50m / 5 BAR', '2 Years Official Warranty', 'Men\'s', 'Italian Leather', 4.79, 163, 'Classic roman numeral markers with vintage aeronautical chronometer styling on a genuine rich amber brown calfskin leather strap.', 0, 1, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `title` = VALUES(`title`), `slug` = VALUES(`slug`), `sku` = VALUES(`sku`), `brand_id` = VALUES(`brand_id`), `category_id` = VALUES(`category_id`), `price` = VALUES(`price`), `sale_price` = VALUES(`sale_price`), `stock` = VALUES(`stock`), `movement` = VALUES(`movement`), `case_size` = VALUES(`case_size`), `case_material` = VALUES(`case_material`), `strap_type` = VALUES(`strap_type`), `glass` = VALUES(`glass`), `water_resistance` = VALUES(`water_resistance`), `warranty` = VALUES(`warranty`), `gender` = VALUES(`gender`), `badge` = VALUES(`badge`), `rating` = VALUES(`rating`), `reviews_count` = VALUES(`reviews_count`), `description` = VALUES(`description`), `is_featured` = VALUES(`is_featured`), `is_active` = VALUES(`is_active`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `products` (`id`, `title`, `slug`, `sku`, `brand_id`, `category_id`, `price`, `sale_price`, `stock`, `movement`, `case_size`, `case_material`, `strap_type`, `glass`, `water_resistance`, `warranty`, `gender`, `badge`, `rating`, `reviews_count`, `description`, `is_featured`, `is_active`, `created_at`, `updated_at`) VALUES (9, 'Omax Masterpiece Dual-Tone Luxury Dress Watch', 'omax-masterpiece-dual-tone-luxury-dress-watch', 'TY-OMA-9', 8, 2, 110.00, 85.00, 25, 'Japanese Quartz Movement', '41.0 mm', 'Two-Tone Stainless Steel with PVD Gold Fluted Bezel', 'Two-Tone Stainless Steel Bracelet', 'Hardened Crystal Glass with Cyclops Lens', '50m Water Proof', '2 Years Omax Warranty', 'Men\'s', 'Water Resistant', 4.72, 74, 'Swiss design legacy with Japanese precision movement. Features gold-tone fluted bezel, diamond index markers, and day-date display.', 0, 1, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `title` = VALUES(`title`), `slug` = VALUES(`slug`), `sku` = VALUES(`sku`), `brand_id` = VALUES(`brand_id`), `category_id` = VALUES(`category_id`), `price` = VALUES(`price`), `sale_price` = VALUES(`sale_price`), `stock` = VALUES(`stock`), `movement` = VALUES(`movement`), `case_size` = VALUES(`case_size`), `case_material` = VALUES(`case_material`), `strap_type` = VALUES(`strap_type`), `glass` = VALUES(`glass`), `water_resistance` = VALUES(`water_resistance`), `warranty` = VALUES(`warranty`), `gender` = VALUES(`gender`), `badge` = VALUES(`badge`), `rating` = VALUES(`rating`), `reviews_count` = VALUES(`reviews_count`), `description` = VALUES(`description`), `is_featured` = VALUES(`is_featured`), `is_active` = VALUES(`is_active`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `products` (`id`, `title`, `slug`, `sku`, `brand_id`, `category_id`, `price`, `sale_price`, `stock`, `movement`, `case_size`, `case_material`, `strap_type`, `glass`, `water_resistance`, `warranty`, `gender`, `badge`, `rating`, `reviews_count`, `description`, `is_featured`, `is_active`, `created_at`, `updated_at`) VALUES (10, 'Omax Premium Open-Heart Automatic Skeleton', 'omax-premium-open-heart-automatic-skeleton', 'TY-OMA-10', 9, 6, 220.00, 175.00, 16, 'Automatic 21-Jewel Mechanical Caliber', '42.5 mm', 'Gunmetal Ion-Plated Stainless Steel', 'Gunmetal Stainless Steel Deployment', 'Scratch Resistant Mineral Glass', '50m / 5 BAR', '2 Years Omax Warranty', 'Men\'s', 'Open Heart Caliber', 4.81, 38, 'Exquisite open-heart escapement window reveals the mechanical heartbeat. Fitted with an all-black gunmetal case and exhibition back.', 1, 1, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `title` = VALUES(`title`), `slug` = VALUES(`slug`), `sku` = VALUES(`sku`), `brand_id` = VALUES(`brand_id`), `category_id` = VALUES(`category_id`), `price` = VALUES(`price`), `sale_price` = VALUES(`sale_price`), `stock` = VALUES(`stock`), `movement` = VALUES(`movement`), `case_size` = VALUES(`case_size`), `case_material` = VALUES(`case_material`), `strap_type` = VALUES(`strap_type`), `glass` = VALUES(`glass`), `water_resistance` = VALUES(`water_resistance`), `warranty` = VALUES(`warranty`), `gender` = VALUES(`gender`), `badge` = VALUES(`badge`), `rating` = VALUES(`rating`), `reviews_count` = VALUES(`reviews_count`), `description` = VALUES(`description`), `is_featured` = VALUES(`is_featured`), `is_active` = VALUES(`is_active`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `products` (`id`, `title`, `slug`, `sku`, `brand_id`, `category_id`, `price`, `sale_price`, `stock`, `movement`, `case_size`, `case_material`, `strap_type`, `glass`, `water_resistance`, `warranty`, `gender`, `badge`, `rating`, `reviews_count`, `description`, `is_featured`, `is_active`, `created_at`, `updated_at`) VALUES (11, 'Omax General Crystal Classic Quartz', 'omax-general-crystal-classic-quartz', 'TY-OMA-11', 7, 1, 60.00, 45.00, 50, 'Seiko Epson Quartz Caliber', '39.0 mm', 'Polished Alloy Steel', 'Genuine Black Leather Band', 'Mineral Crystal', '30m Water Resistant', '1 Year Official Warranty', 'Unisex', 'Daily Essential', 4.68, 61, 'Reliable, affordable everyday analog timekeeper with clean minimalist dial and genuine textured leather strap.', 0, 1, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `title` = VALUES(`title`), `slug` = VALUES(`slug`), `sku` = VALUES(`sku`), `brand_id` = VALUES(`brand_id`), `category_id` = VALUES(`category_id`), `price` = VALUES(`price`), `sale_price` = VALUES(`sale_price`), `stock` = VALUES(`stock`), `movement` = VALUES(`movement`), `case_size` = VALUES(`case_size`), `case_material` = VALUES(`case_material`), `strap_type` = VALUES(`strap_type`), `glass` = VALUES(`glass`), `water_resistance` = VALUES(`water_resistance`), `warranty` = VALUES(`warranty`), `gender` = VALUES(`gender`), `badge` = VALUES(`badge`), `rating` = VALUES(`rating`), `reviews_count` = VALUES(`reviews_count`), `description` = VALUES(`description`), `is_featured` = VALUES(`is_featured`), `is_active` = VALUES(`is_active`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `products` (`id`, `title`, `slug`, `sku`, `brand_id`, `category_id`, `price`, `sale_price`, `stock`, `movement`, `case_size`, `case_material`, `strap_type`, `glass`, `water_resistance`, `warranty`, `gender`, `badge`, `rating`, `reviews_count`, `description`, `is_featured`, `is_active`, `created_at`, `updated_at`) VALUES (12, 'Swiss Military Hanowa Helvetus Automatic 06-5316', 'swiss-military-hanowa-helvetus-automatic-06-5316', 'TY-SWI-12', 16, 6, 590.00, 520.00, 15, 'Swiss Made STP1-11 Automatic (28,800 bph)', '40.0 mm', '316L Brushed Surgical Steel', 'Brown Saddle Stitched Swiss Leather', 'Anti-Reflective Sapphire Crystal', '100m / 10 BAR', '3 Years Swiss Military Warranty', 'Men\'s', 'Swiss Made', 4.89, 45, 'Officially licensed by the Swiss Confederation. Rugged elegance built with military-grade precision, sapphire crystal, and Swiss automatic movement.', 1, 1, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `title` = VALUES(`title`), `slug` = VALUES(`slug`), `sku` = VALUES(`sku`), `brand_id` = VALUES(`brand_id`), `category_id` = VALUES(`category_id`), `price` = VALUES(`price`), `sale_price` = VALUES(`sale_price`), `stock` = VALUES(`stock`), `movement` = VALUES(`movement`), `case_size` = VALUES(`case_size`), `case_material` = VALUES(`case_material`), `strap_type` = VALUES(`strap_type`), `glass` = VALUES(`glass`), `water_resistance` = VALUES(`water_resistance`), `warranty` = VALUES(`warranty`), `gender` = VALUES(`gender`), `badge` = VALUES(`badge`), `rating` = VALUES(`rating`), `reviews_count` = VALUES(`reviews_count`), `description` = VALUES(`description`), `is_featured` = VALUES(`is_featured`), `is_active` = VALUES(`is_active`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `products` (`id`, `title`, `slug`, `sku`, `brand_id`, `category_id`, `price`, `sale_price`, `stock`, `movement`, `case_size`, `case_material`, `strap_type`, `glass`, `water_resistance`, `warranty`, `gender`, `badge`, `rating`, `reviews_count`, `description`, `is_featured`, `is_active`, `created_at`, `updated_at`) VALUES (13, 'Daniel Klein Exclusive Multi-Function Chrono DK1289', 'daniel-klein-exclusive-multi-function-chrono-dk1289', 'TY-DAN-13', 15, 2, 95.00, 78.00, 35, 'High-Precision Multi-Function Quartz', '43.0 mm', 'Stainless Steel with IP Gunmetal Coating', 'Matching Stainless Steel Mesh Bracelet', 'Curved Hardened Crystal', '50m Water Resistant', '2 Years Official Warranty', 'Men\'s', 'Modern Fashion', 4.75, 92, 'Sleek metropolitan aesthetic featuring dual-time complication, day-night indicator, and solid stainless steel bracelet.', 0, 1, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `title` = VALUES(`title`), `slug` = VALUES(`slug`), `sku` = VALUES(`sku`), `brand_id` = VALUES(`brand_id`), `category_id` = VALUES(`category_id`), `price` = VALUES(`price`), `sale_price` = VALUES(`sale_price`), `stock` = VALUES(`stock`), `movement` = VALUES(`movement`), `case_size` = VALUES(`case_size`), `case_material` = VALUES(`case_material`), `strap_type` = VALUES(`strap_type`), `glass` = VALUES(`glass`), `water_resistance` = VALUES(`water_resistance`), `warranty` = VALUES(`warranty`), `gender` = VALUES(`gender`), `badge` = VALUES(`badge`), `rating` = VALUES(`rating`), `reviews_count` = VALUES(`reviews_count`), `description` = VALUES(`description`), `is_featured` = VALUES(`is_featured`), `is_active` = VALUES(`is_active`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `products` (`id`, `title`, `slug`, `sku`, `brand_id`, `category_id`, `price`, `sale_price`, `stock`, `movement`, `case_size`, `case_material`, `strap_type`, `glass`, `water_resistance`, `warranty`, `gender`, `badge`, `rating`, `reviews_count`, `description`, `is_featured`, `is_active`, `created_at`, `updated_at`) VALUES (14, 'Kenneth Cole Skeleton Automatic Dial Leather Watch', 'kenneth-cole-skeleton-automatic-dial-leather-watch', 'TY-KEN-14', 13, 6, 240.00, 195.00, 20, 'Self-Winding 21-Jewel Automatic Mechanical', '44.0 mm', 'Polished Stainless Steel with Rose Gold Accents', 'Black Alligator-Embossed Leather Strap', 'Hardened Mineral Crystal', '30m Water Resistant', '2 Years Official Warranty', 'Men\'s', 'Full Skeleton', 4.84, 67, 'Signature transparent skeleton dial showcasing the balance wheel and intricate mechanical geartrain. Premium alligator-embossed genuine leather strap.', 1, 1, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `title` = VALUES(`title`), `slug` = VALUES(`slug`), `sku` = VALUES(`sku`), `brand_id` = VALUES(`brand_id`), `category_id` = VALUES(`category_id`), `price` = VALUES(`price`), `sale_price` = VALUES(`sale_price`), `stock` = VALUES(`stock`), `movement` = VALUES(`movement`), `case_size` = VALUES(`case_size`), `case_material` = VALUES(`case_material`), `strap_type` = VALUES(`strap_type`), `glass` = VALUES(`glass`), `water_resistance` = VALUES(`water_resistance`), `warranty` = VALUES(`warranty`), `gender` = VALUES(`gender`), `badge` = VALUES(`badge`), `rating` = VALUES(`rating`), `reviews_count` = VALUES(`reviews_count`), `description` = VALUES(`description`), `is_featured` = VALUES(`is_featured`), `is_active` = VALUES(`is_active`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `products` (`id`, `title`, `slug`, `sku`, `brand_id`, `category_id`, `price`, `sale_price`, `stock`, `movement`, `case_size`, `case_material`, `strap_type`, `glass`, `water_resistance`, `warranty`, `gender`, `badge`, `rating`, `reviews_count`, `description`, `is_featured`, `is_active`, `created_at`, `updated_at`) VALUES (15, 'Santa Barbara Polo & Racquet Club Royal Steel SB-1022', 'santa-barbara-polo-racquet-club-royal-steel-sb-1022', 'TY-SAN-15', 17, 2, 165.00, 135.00, 19, 'Japanese Quartz Multi-Eye Movement', '42.0 mm', 'Stainless Steel with Polished & Brushed Finish', 'Solid Steel Link Bracelet with Safety Clasp', 'Scratch Resistant Mineral Glass', '50m / 5 BAR', '2 Years Official Warranty', 'Men\'s', 'Equestrian Heritage', 4.77, 48, 'Inspired by the historic California polo club founded in 1911. Features a rich emerald green sunray dial, gold hands, and solid stainless steel link bracelet.', 0, 1, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `title` = VALUES(`title`), `slug` = VALUES(`slug`), `sku` = VALUES(`sku`), `brand_id` = VALUES(`brand_id`), `category_id` = VALUES(`category_id`), `price` = VALUES(`price`), `sale_price` = VALUES(`sale_price`), `stock` = VALUES(`stock`), `movement` = VALUES(`movement`), `case_size` = VALUES(`case_size`), `case_material` = VALUES(`case_material`), `strap_type` = VALUES(`strap_type`), `glass` = VALUES(`glass`), `water_resistance` = VALUES(`water_resistance`), `warranty` = VALUES(`warranty`), `gender` = VALUES(`gender`), `badge` = VALUES(`badge`), `rating` = VALUES(`rating`), `reviews_count` = VALUES(`reviews_count`), `description` = VALUES(`description`), `is_featured` = VALUES(`is_featured`), `is_active` = VALUES(`is_active`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `products` (`id`, `title`, `slug`, `sku`, `brand_id`, `category_id`, `price`, `sale_price`, `stock`, `movement`, `case_size`, `case_material`, `strap_type`, `glass`, `water_resistance`, `warranty`, `gender`, `badge`, `rating`, `reviews_count`, `description`, `is_featured`, `is_active`, `created_at`, `updated_at`) VALUES (16, 'Ferro Distinct Single-Hand Retrograde Dial', 'ferro-distinct-single-hand-retrograde-dial', 'TY-FER-16', 11, 1, 190.00, 160.00, 14, 'Swiss Ronda 515 Quartz Movement', '41.0 mm', 'Satin-Brushed Titanium-Grey PVD Steel', 'Vintage Italian Waxed Saddle Leather', 'Sapphire Coated Crystal', '50m / 5 BAR', '2 Years International Warranty', 'Unisex', 'Single Hand Horology', 4.83, 39, 'Minimalist single-hand gauge design reminiscent of classic motorsport tachometers. Italian oil-waxed leather strap that patinas beautifully over time.', 0, 1, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `title` = VALUES(`title`), `slug` = VALUES(`slug`), `sku` = VALUES(`sku`), `brand_id` = VALUES(`brand_id`), `category_id` = VALUES(`category_id`), `price` = VALUES(`price`), `sale_price` = VALUES(`sale_price`), `stock` = VALUES(`stock`), `movement` = VALUES(`movement`), `case_size` = VALUES(`case_size`), `case_material` = VALUES(`case_material`), `strap_type` = VALUES(`strap_type`), `glass` = VALUES(`glass`), `water_resistance` = VALUES(`water_resistance`), `warranty` = VALUES(`warranty`), `gender` = VALUES(`gender`), `badge` = VALUES(`badge`), `rating` = VALUES(`rating`), `reviews_count` = VALUES(`reviews_count`), `description` = VALUES(`description`), `is_featured` = VALUES(`is_featured`), `is_active` = VALUES(`is_active`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `products` (`id`, `title`, `slug`, `sku`, `brand_id`, `category_id`, `price`, `sale_price`, `stock`, `movement`, `case_size`, `case_material`, `strap_type`, `glass`, `water_resistance`, `warranty`, `gender`, `badge`, `rating`, `reviews_count`, `description`, `is_featured`, `is_active`, `created_at`, `updated_at`) VALUES (17, 'Crysma Elegance Sunray Rose Gold Mesh Watch', 'crysma-elegance-sunray-rose-gold-mesh-watch', 'TY-CRY-17', 12, 2, 120.00, 95.00, 28, 'Slim Japanese Quartz Caliber', '36.0 mm (Ultra-slim 6.8mm)', 'Rose Gold PVD Stainless Steel', 'Rose Gold Milanese Magnetic Mesh Band', 'Hardened Mineral Crystal', '30m Water Resistant', '2 Years Official Warranty', 'Women\'s', 'Rose Gold PVD', 4.76, 58, 'Ultra-thin modern dress watch with warm rose gold case, minimalist baton hands, and a supple Milanese mesh bracelet.', 0, 1, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `title` = VALUES(`title`), `slug` = VALUES(`slug`), `sku` = VALUES(`sku`), `brand_id` = VALUES(`brand_id`), `category_id` = VALUES(`category_id`), `price` = VALUES(`price`), `sale_price` = VALUES(`sale_price`), `stock` = VALUES(`stock`), `movement` = VALUES(`movement`), `case_size` = VALUES(`case_size`), `case_material` = VALUES(`case_material`), `strap_type` = VALUES(`strap_type`), `glass` = VALUES(`glass`), `water_resistance` = VALUES(`water_resistance`), `warranty` = VALUES(`warranty`), `gender` = VALUES(`gender`), `badge` = VALUES(`badge`), `rating` = VALUES(`rating`), `reviews_count` = VALUES(`reviews_count`), `description` = VALUES(`description`), `is_featured` = VALUES(`is_featured`), `is_active` = VALUES(`is_active`), `updated_at` = VALUES(`updated_at`);

INSERT INTO `product_images` (`id`, `product_id`, `image_path`, `is_primary`, `sort_order`, `created_at`, `updated_at`) VALUES (1, 1, 'watches/titlon-dive.png', 1, 0, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `product_id` = VALUES(`product_id`), `image_path` = VALUES(`image_path`), `is_primary` = VALUES(`is_primary`), `sort_order` = VALUES(`sort_order`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `product_images` (`id`, `product_id`, `image_path`, `is_primary`, `sort_order`, `created_at`, `updated_at`) VALUES (2, 1, 'watches/astra-black.png', 0, 1, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `product_id` = VALUES(`product_id`), `image_path` = VALUES(`image_path`), `is_primary` = VALUES(`is_primary`), `sort_order` = VALUES(`sort_order`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `product_images` (`id`, `product_id`, `image_path`, `is_primary`, `sort_order`, `created_at`, `updated_at`) VALUES (3, 1, 'watches/nicva-shin.png', 0, 2, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `product_id` = VALUES(`product_id`), `image_path` = VALUES(`image_path`), `is_primary` = VALUES(`is_primary`), `sort_order` = VALUES(`sort_order`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `product_images` (`id`, `product_id`, `image_path`, `is_primary`, `sort_order`, `created_at`, `updated_at`) VALUES (4, 1, 'watches/saisae-moonphase.png', 0, 3, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `product_id` = VALUES(`product_id`), `image_path` = VALUES(`image_path`), `is_primary` = VALUES(`is_primary`), `sort_order` = VALUES(`sort_order`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `product_images` (`id`, `product_id`, `image_path`, `is_primary`, `sort_order`, `created_at`, `updated_at`) VALUES (5, 2, 'images/footer/cat_sports.jpg', 1, 0, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `product_id` = VALUES(`product_id`), `image_path` = VALUES(`image_path`), `is_primary` = VALUES(`is_primary`), `sort_order` = VALUES(`sort_order`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `product_images` (`id`, `product_id`, `image_path`, `is_primary`, `sort_order`, `created_at`, `updated_at`) VALUES (6, 2, 'watches/astra-black.png', 0, 1, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `product_id` = VALUES(`product_id`), `image_path` = VALUES(`image_path`), `is_primary` = VALUES(`is_primary`), `sort_order` = VALUES(`sort_order`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `product_images` (`id`, `product_id`, `image_path`, `is_primary`, `sort_order`, `created_at`, `updated_at`) VALUES (7, 2, 'watches/titlon-dive.png', 0, 2, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `product_id` = VALUES(`product_id`), `image_path` = VALUES(`image_path`), `is_primary` = VALUES(`is_primary`), `sort_order` = VALUES(`sort_order`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `product_images` (`id`, `product_id`, `image_path`, `is_primary`, `sort_order`, `created_at`, `updated_at`) VALUES (8, 2, 'watches/saisae-moonphase.png', 0, 3, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `product_id` = VALUES(`product_id`), `image_path` = VALUES(`image_path`), `is_primary` = VALUES(`is_primary`), `sort_order` = VALUES(`sort_order`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `product_images` (`id`, `product_id`, `image_path`, `is_primary`, `sort_order`, `created_at`, `updated_at`) VALUES (9, 3, 'watches/nicva-shin.png', 1, 0, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `product_id` = VALUES(`product_id`), `image_path` = VALUES(`image_path`), `is_primary` = VALUES(`is_primary`), `sort_order` = VALUES(`sort_order`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `product_images` (`id`, `product_id`, `image_path`, `is_primary`, `sort_order`, `created_at`, `updated_at`) VALUES (10, 3, 'watches/astra-black.png', 0, 1, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `product_id` = VALUES(`product_id`), `image_path` = VALUES(`image_path`), `is_primary` = VALUES(`is_primary`), `sort_order` = VALUES(`sort_order`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `product_images` (`id`, `product_id`, `image_path`, `is_primary`, `sort_order`, `created_at`, `updated_at`) VALUES (11, 3, 'watches/titlon-dive.png', 0, 2, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `product_id` = VALUES(`product_id`), `image_path` = VALUES(`image_path`), `is_primary` = VALUES(`is_primary`), `sort_order` = VALUES(`sort_order`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `product_images` (`id`, `product_id`, `image_path`, `is_primary`, `sort_order`, `created_at`, `updated_at`) VALUES (12, 3, 'watches/saisae-moonphase.png', 0, 3, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `product_id` = VALUES(`product_id`), `image_path` = VALUES(`image_path`), `is_primary` = VALUES(`is_primary`), `sort_order` = VALUES(`sort_order`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `product_images` (`id`, `product_id`, `image_path`, `is_primary`, `sort_order`, `created_at`, `updated_at`) VALUES (13, 4, 'https://images.unsplash.com/photo-1533139502658-0198f920d8e8?auto=format&fit=crop&w=600&q=80', 1, 0, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `product_id` = VALUES(`product_id`), `image_path` = VALUES(`image_path`), `is_primary` = VALUES(`is_primary`), `sort_order` = VALUES(`sort_order`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `product_images` (`id`, `product_id`, `image_path`, `is_primary`, `sort_order`, `created_at`, `updated_at`) VALUES (14, 5, 'https://images.unsplash.com/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=600&q=80', 1, 0, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `product_id` = VALUES(`product_id`), `image_path` = VALUES(`image_path`), `is_primary` = VALUES(`is_primary`), `sort_order` = VALUES(`sort_order`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `product_images` (`id`, `product_id`, `image_path`, `is_primary`, `sort_order`, `created_at`, `updated_at`) VALUES (15, 6, 'https://images.unsplash.com/photo-1526045431048-f857369baa09?auto=format&fit=crop&w=600&q=80', 1, 0, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `product_id` = VALUES(`product_id`), `image_path` = VALUES(`image_path`), `is_primary` = VALUES(`is_primary`), `sort_order` = VALUES(`sort_order`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `product_images` (`id`, `product_id`, `image_path`, `is_primary`, `sort_order`, `created_at`, `updated_at`) VALUES (16, 7, 'https://images.unsplash.com/photo-1542496658-e33a6d0d50f6?auto=format&fit=crop&w=600&q=80', 1, 0, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `product_id` = VALUES(`product_id`), `image_path` = VALUES(`image_path`), `is_primary` = VALUES(`is_primary`), `sort_order` = VALUES(`sort_order`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `product_images` (`id`, `product_id`, `image_path`, `is_primary`, `sort_order`, `created_at`, `updated_at`) VALUES (17, 8, 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=600&q=80', 1, 0, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `product_id` = VALUES(`product_id`), `image_path` = VALUES(`image_path`), `is_primary` = VALUES(`is_primary`), `sort_order` = VALUES(`sort_order`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `product_images` (`id`, `product_id`, `image_path`, `is_primary`, `sort_order`, `created_at`, `updated_at`) VALUES (18, 9, 'https://images.unsplash.com/photo-1614164185128-e4ec99c436d7?auto=format&fit=crop&w=600&q=80', 1, 0, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `product_id` = VALUES(`product_id`), `image_path` = VALUES(`image_path`), `is_primary` = VALUES(`is_primary`), `sort_order` = VALUES(`sort_order`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `product_images` (`id`, `product_id`, `image_path`, `is_primary`, `sort_order`, `created_at`, `updated_at`) VALUES (19, 10, 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=600&q=80', 1, 0, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `product_id` = VALUES(`product_id`), `image_path` = VALUES(`image_path`), `is_primary` = VALUES(`is_primary`), `sort_order` = VALUES(`sort_order`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `product_images` (`id`, `product_id`, `image_path`, `is_primary`, `sort_order`, `created_at`, `updated_at`) VALUES (20, 11, 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=600&q=80', 1, 0, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `product_id` = VALUES(`product_id`), `image_path` = VALUES(`image_path`), `is_primary` = VALUES(`is_primary`), `sort_order` = VALUES(`sort_order`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `product_images` (`id`, `product_id`, `image_path`, `is_primary`, `sort_order`, `created_at`, `updated_at`) VALUES (21, 12, 'https://images.unsplash.com/photo-1542496658-e33a6d0d50f6?auto=format&fit=crop&w=600&q=80', 1, 0, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `product_id` = VALUES(`product_id`), `image_path` = VALUES(`image_path`), `is_primary` = VALUES(`is_primary`), `sort_order` = VALUES(`sort_order`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `product_images` (`id`, `product_id`, `image_path`, `is_primary`, `sort_order`, `created_at`, `updated_at`) VALUES (22, 13, 'https://images.unsplash.com/photo-1533139502658-0198f920d8e8?auto=format&fit=crop&w=600&q=80', 1, 0, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `product_id` = VALUES(`product_id`), `image_path` = VALUES(`image_path`), `is_primary` = VALUES(`is_primary`), `sort_order` = VALUES(`sort_order`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `product_images` (`id`, `product_id`, `image_path`, `is_primary`, `sort_order`, `created_at`, `updated_at`) VALUES (23, 14, 'watches/saisae-moonphase.png', 1, 0, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `product_id` = VALUES(`product_id`), `image_path` = VALUES(`image_path`), `is_primary` = VALUES(`is_primary`), `sort_order` = VALUES(`sort_order`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `product_images` (`id`, `product_id`, `image_path`, `is_primary`, `sort_order`, `created_at`, `updated_at`) VALUES (24, 14, 'watches/astra-black.png', 0, 1, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `product_id` = VALUES(`product_id`), `image_path` = VALUES(`image_path`), `is_primary` = VALUES(`is_primary`), `sort_order` = VALUES(`sort_order`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `product_images` (`id`, `product_id`, `image_path`, `is_primary`, `sort_order`, `created_at`, `updated_at`) VALUES (25, 14, 'watches/titlon-dive.png', 0, 2, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `product_id` = VALUES(`product_id`), `image_path` = VALUES(`image_path`), `is_primary` = VALUES(`is_primary`), `sort_order` = VALUES(`sort_order`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `product_images` (`id`, `product_id`, `image_path`, `is_primary`, `sort_order`, `created_at`, `updated_at`) VALUES (26, 14, 'watches/nicva-shin.png', 0, 3, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `product_id` = VALUES(`product_id`), `image_path` = VALUES(`image_path`), `is_primary` = VALUES(`is_primary`), `sort_order` = VALUES(`sort_order`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `product_images` (`id`, `product_id`, `image_path`, `is_primary`, `sort_order`, `created_at`, `updated_at`) VALUES (27, 15, 'watches/nicva-shin.png', 1, 0, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `product_id` = VALUES(`product_id`), `image_path` = VALUES(`image_path`), `is_primary` = VALUES(`is_primary`), `sort_order` = VALUES(`sort_order`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `product_images` (`id`, `product_id`, `image_path`, `is_primary`, `sort_order`, `created_at`, `updated_at`) VALUES (28, 15, 'watches/astra-black.png', 0, 1, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `product_id` = VALUES(`product_id`), `image_path` = VALUES(`image_path`), `is_primary` = VALUES(`is_primary`), `sort_order` = VALUES(`sort_order`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `product_images` (`id`, `product_id`, `image_path`, `is_primary`, `sort_order`, `created_at`, `updated_at`) VALUES (29, 15, 'watches/titlon-dive.png', 0, 2, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `product_id` = VALUES(`product_id`), `image_path` = VALUES(`image_path`), `is_primary` = VALUES(`is_primary`), `sort_order` = VALUES(`sort_order`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `product_images` (`id`, `product_id`, `image_path`, `is_primary`, `sort_order`, `created_at`, `updated_at`) VALUES (30, 15, 'watches/saisae-moonphase.png', 0, 3, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `product_id` = VALUES(`product_id`), `image_path` = VALUES(`image_path`), `is_primary` = VALUES(`is_primary`), `sort_order` = VALUES(`sort_order`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `product_images` (`id`, `product_id`, `image_path`, `is_primary`, `sort_order`, `created_at`, `updated_at`) VALUES (31, 16, 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=600&q=80', 1, 0, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `product_id` = VALUES(`product_id`), `image_path` = VALUES(`image_path`), `is_primary` = VALUES(`is_primary`), `sort_order` = VALUES(`sort_order`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `product_images` (`id`, `product_id`, `image_path`, `is_primary`, `sort_order`, `created_at`, `updated_at`) VALUES (32, 17, 'https://images.unsplash.com/photo-1614164185128-e4ec99c436d7?auto=format&fit=crop&w=600&q=80', 1, 0, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `product_id` = VALUES(`product_id`), `image_path` = VALUES(`image_path`), `is_primary` = VALUES(`is_primary`), `sort_order` = VALUES(`sort_order`), `updated_at` = VALUES(`updated_at`);

INSERT IGNORE INTO `category_product` (`category_id`, `product_id`) VALUES (2, 1);
INSERT IGNORE INTO `category_product` (`category_id`, `product_id`) VALUES (3, 1);
INSERT IGNORE INTO `category_product` (`category_id`, `product_id`) VALUES (6, 1);
INSERT IGNORE INTO `category_product` (`category_id`, `product_id`) VALUES (1, 2);
INSERT IGNORE INTO `category_product` (`category_id`, `product_id`) VALUES (3, 2);
INSERT IGNORE INTO `category_product` (`category_id`, `product_id`) VALUES (4, 2);
INSERT IGNORE INTO `category_product` (`category_id`, `product_id`) VALUES (5, 2);
INSERT IGNORE INTO `category_product` (`category_id`, `product_id`) VALUES (2, 3);
INSERT IGNORE INTO `category_product` (`category_id`, `product_id`) VALUES (3, 3);
INSERT IGNORE INTO `category_product` (`category_id`, `product_id`) VALUES (4, 3);
INSERT IGNORE INTO `category_product` (`category_id`, `product_id`) VALUES (2, 4);
INSERT IGNORE INTO `category_product` (`category_id`, `product_id`) VALUES (3, 4);
INSERT IGNORE INTO `category_product` (`category_id`, `product_id`) VALUES (6, 4);
INSERT IGNORE INTO `category_product` (`category_id`, `product_id`) VALUES (2, 5);
INSERT IGNORE INTO `category_product` (`category_id`, `product_id`) VALUES (3, 5);
INSERT IGNORE INTO `category_product` (`category_id`, `product_id`) VALUES (4, 5);
INSERT IGNORE INTO `category_product` (`category_id`, `product_id`) VALUES (5, 5);
INSERT IGNORE INTO `category_product` (`category_id`, `product_id`) VALUES (2, 6);
INSERT IGNORE INTO `category_product` (`category_id`, `product_id`) VALUES (4, 6);
INSERT IGNORE INTO `category_product` (`category_id`, `product_id`) VALUES (5, 6);
INSERT IGNORE INTO `category_product` (`category_id`, `product_id`) VALUES (2, 7);
INSERT IGNORE INTO `category_product` (`category_id`, `product_id`) VALUES (3, 7);
INSERT IGNORE INTO `category_product` (`category_id`, `product_id`) VALUES (6, 7);
INSERT IGNORE INTO `category_product` (`category_id`, `product_id`) VALUES (1, 8);
INSERT IGNORE INTO `category_product` (`category_id`, `product_id`) VALUES (3, 8);
INSERT IGNORE INTO `category_product` (`category_id`, `product_id`) VALUES (4, 8);
INSERT IGNORE INTO `category_product` (`category_id`, `product_id`) VALUES (2, 9);
INSERT IGNORE INTO `category_product` (`category_id`, `product_id`) VALUES (3, 9);
INSERT IGNORE INTO `category_product` (`category_id`, `product_id`) VALUES (4, 9);
INSERT IGNORE INTO `category_product` (`category_id`, `product_id`) VALUES (2, 10);
INSERT IGNORE INTO `category_product` (`category_id`, `product_id`) VALUES (3, 10);
INSERT IGNORE INTO `category_product` (`category_id`, `product_id`) VALUES (6, 10);
INSERT IGNORE INTO `category_product` (`category_id`, `product_id`) VALUES (1, 11);
INSERT IGNORE INTO `category_product` (`category_id`, `product_id`) VALUES (3, 11);
INSERT IGNORE INTO `category_product` (`category_id`, `product_id`) VALUES (4, 11);
INSERT IGNORE INTO `category_product` (`category_id`, `product_id`) VALUES (1, 12);
INSERT IGNORE INTO `category_product` (`category_id`, `product_id`) VALUES (3, 12);
INSERT IGNORE INTO `category_product` (`category_id`, `product_id`) VALUES (6, 12);
INSERT IGNORE INTO `category_product` (`category_id`, `product_id`) VALUES (2, 13);
INSERT IGNORE INTO `category_product` (`category_id`, `product_id`) VALUES (3, 13);
INSERT IGNORE INTO `category_product` (`category_id`, `product_id`) VALUES (4, 13);
INSERT IGNORE INTO `category_product` (`category_id`, `product_id`) VALUES (1, 14);
INSERT IGNORE INTO `category_product` (`category_id`, `product_id`) VALUES (3, 14);
INSERT IGNORE INTO `category_product` (`category_id`, `product_id`) VALUES (6, 14);
INSERT IGNORE INTO `category_product` (`category_id`, `product_id`) VALUES (2, 15);
INSERT IGNORE INTO `category_product` (`category_id`, `product_id`) VALUES (3, 15);
INSERT IGNORE INTO `category_product` (`category_id`, `product_id`) VALUES (4, 15);
INSERT IGNORE INTO `category_product` (`category_id`, `product_id`) VALUES (1, 16);
INSERT IGNORE INTO `category_product` (`category_id`, `product_id`) VALUES (3, 16);
INSERT IGNORE INTO `category_product` (`category_id`, `product_id`) VALUES (4, 16);
INSERT IGNORE INTO `category_product` (`category_id`, `product_id`) VALUES (2, 17);
INSERT IGNORE INTO `category_product` (`category_id`, `product_id`) VALUES (3, 17);
INSERT IGNORE INTO `category_product` (`category_id`, `product_id`) VALUES (4, 17);

INSERT INTO `coupons` (`id`, `code`, `type`, `value`, `min_spend`, `expires_at`, `is_active`, `created_at`, `updated_at`) VALUES (1, 'TIMEYRAH10', 'percent', 10.00, 0.00, NULL, 1, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `type` = VALUES(`type`), `value` = VALUES(`value`), `min_spend` = VALUES(`min_spend`), `expires_at` = VALUES(`expires_at`), `is_active` = VALUES(`is_active`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `coupons` (`id`, `code`, `type`, `value`, `min_spend`, `expires_at`, `is_active`, `created_at`, `updated_at`) VALUES (2, 'VIP2026', 'fixed', 200.00, 0.00, NULL, 1, '2026-11-03 12:00:00', '2026-11-03 12:00:00') ON DUPLICATE KEY UPDATE `type` = VALUES(`type`), `value` = VALUES(`value`), `min_spend` = VALUES(`min_spend`), `expires_at` = VALUES(`expires_at`), `is_active` = VALUES(`is_active`), `updated_at` = VALUES(`updated_at`);

-- -----------------------------------------------------------------------------
-- 9. spec_attributes (Technical Specification Attributes)
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `spec_attributes` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(120) NOT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `spec_attributes_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- 10. spec_attribute_values (Predefined Option Values)
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `spec_attribute_values` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `spec_attribute_id` BIGINT UNSIGNED NOT NULL,
  `value` VARCHAR(255) NOT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `spec_attribute_values_spec_attribute_id_foreign` (`spec_attribute_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- 11. product_specifications (Attached Specifications per Watch)
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `product_specifications` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` BIGINT UNSIGNED NOT NULL,
  `spec_attribute_id` BIGINT UNSIGNED NOT NULL,
  `value` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_specifications_product_id_spec_attribute_id_unique` (`product_id`,`spec_attribute_id`),
  KEY `product_specifications_product_id_index` (`product_id`),
  KEY `product_specifications_spec_attribute_id_index` (`spec_attribute_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- 12. page_sections (Page Builder & CMS Content Customizer)
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `page_sections` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `page` VARCHAR(50) NOT NULL DEFAULT 'home',
  `section_key` VARCHAR(50) NOT NULL,
  `title` VARCHAR(255) DEFAULT NULL,
  `subtitle` TEXT DEFAULT NULL,
  `content` JSON DEFAULT NULL,
  `image_path` VARCHAR(255) DEFAULT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `page_sections_page_section_key_unique` (`page`,`section_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `page_sections` (`page`, `section_key`, `title`, `subtitle`, `content`, `image_path`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
('home', 'seo', 'TIMEYRAH — Precision in Motion | Luxury & Everyday Watches Pakistan', 'Official online boutique for authentic Swiss, Japanese, and international horology in Pakistan.', '{\"meta_description\":\"Shop 100% original watches in Pakistan. Featuring Rado, Citizen, Seiko, Casio G-Shock, and Tissot with official brand warranty and Cash on Delivery across Karachi, Lahore, Islamabad, and nationwide.\",\"keywords\":\"watches pakistan, luxury watches karachi, original watches lahore, casio g shock pakistan, seiko watches, rado captain cook\"}', 'images/timeyar-circle-emblem.png', 0, 1, NOW(), NOW()),
('home', 'hero_banner', 'TIMEYRAH.', 'More than Just a Watch. Experience the Caliber.', '{\"badge\":\"TIME-REDEFINED • 100% ORIGINAL\",\"heading_accent_1\":\"PRECISION IN\",\"heading_accent_2\":\"MOTION\",\"description\":\"At TIMEYRAH, we believe a watch is more than an accessory — it is a symbol of style, premium quality, and timeless craftsmanship with nationwide delivery across Pakistan.\",\"button_text\":\"Explore The Collection\",\"button_url\":\"#collection\",\"secondary_button_text\":\"Shop All Watches\",\"secondary_button_url\":\"/shop\"}', 'images/hero/hero-watch.png', 1, 1, NOW(), NOW()),
('home', 'shop_by_category', 'THE EXCLUSIVE HOROLOGY COLLECTION', 'Explore iconic timepieces curated across luxury categories.', '{\"badge\":\"SHOP BY CATEGORY\"}', NULL, 2, 1, NOW(), NOW()),
('home', 'featured_watches', 'CURATED ATELIER TIMEPIECES', 'Handpicked precision movements and iconic complications available with nationwide Cash on Delivery.', '{\"badge\":\"FEATURED RELEASES\",\"limit\":8}', NULL, 3, 1, NOW(), NOW()),
('home', 'promo_banner', 'MAKE YOUR MARK WITH TIMEYRAH', 'Every second tells a story. Craft yours with an authentic timepiece delivered to your doorstep.', '{\"badge\":\"CRAFTSMANSHIP & HERITAGE\",\"button_text\":\"Discover The Atelier\",\"button_url\":\"/shop\",\"accent_badge\":\"OFFICIAL WARRANTY INCLUDED\"}', 'images/make-banner.jpg', 4, 1, NOW(), NOW()),
('home', 'brand_showcase', 'ICONIC WATCHMAKERS & MANUFACTURES', 'Explore timepieces from Rado, Citizen, Seiko, Casio, Fossil, and premium ateliers.', '{\"badge\":\"OFFICIAL BRANDS\"}', NULL, 5, 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE `title` = VALUES(`title`), `subtitle` = VALUES(`subtitle`);

-- -----------------------------------------------------------------------------
-- Optional: Ensure `is_admin` column on existing `users` table for Admin CMS
-- (Run this if you already have a `users` table on InfinityFree and need to enable admin)
-- -----------------------------------------------------------------------------
-- ALTER TABLE `users` ADD COLUMN `is_admin` TINYINT(1) NOT NULL DEFAULT 0 AFTER `password`;
-- UPDATE `users` SET `is_admin` = 1 WHERE `email` = 'alexander.sterling@horology.vip';

SET FOREIGN_KEY_CHECKS = 1;
