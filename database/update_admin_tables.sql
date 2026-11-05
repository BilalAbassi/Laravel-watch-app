-- =============================================================================
-- TIMEYRAH — 100% InfinityFree-Safe SQL Update (No Errno 150 Foreign Key Errors)
-- Database: if0_43273400_watches
-- Run this in phpMyAdmin SQL tab
-- =============================================================================

SET FOREIGN_KEY_CHECKS = 0;

-- 1. Watch Product Specifications Pivot/Values Table (Indexed without strict FK constraints)
CREATE TABLE IF NOT EXISTS `product_specifications` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` BIGINT UNSIGNED NOT NULL,
  `spec_attribute_id` BIGINT UNSIGNED NOT NULL,
  `custom_value` VARCHAR(255) NOT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_specifications_product_id_index` (`product_id`),
  KEY `product_specifications_spec_attribute_id_index` (`spec_attribute_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Page Sections & Page Customizer CMS Table
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

-- 3. Seed default Homepage Sections (Hero Banner, Categories, Story Banner, SEO)
INSERT INTO `page_sections` (`page`, `section_key`, `title`, `subtitle`, `content`, `image_path`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
('home', 'seo', 'TIMEYRAH — Precision in Motion | Luxury & Everyday Watches Pakistan', 'Official online boutique for authentic Swiss, Japanese, and international horology in Pakistan.', '{\"meta_description\":\"Shop 100% original watches in Pakistan. Featuring Rado, Citizen, Seiko, Casio G-Shock, and Tissot with official brand warranty and Cash on Delivery across Karachi, Lahore, Islamabad, and nationwide.\",\"keywords\":\"watches pakistan, luxury watches karachi, original watches lahore, casio g shock pakistan, seiko watches, rado captain cook\"}', 'images/timeyar-circle-emblem.png', 0, 1, NOW(), NOW()),
('home', 'hero_banner', 'TIMEYRAH.', 'More than Just a Watch. Experience the Caliber.', '{\"badge\":\"TIME-REDEFINED • 100% ORIGINAL\",\"heading_accent_1\":\"PRECISION IN\",\"heading_accent_2\":\"MOTION\",\"description\":\"At TIMEYRAH, we believe a watch is more than an accessory — it is a symbol of style, premium quality, and timeless craftsmanship with nationwide delivery across Pakistan.\",\"button_text\":\"Explore The Collection\",\"button_url\":\"#collection\",\"secondary_button_text\":\"Shop All Watches\",\"secondary_button_url\":\"/shop\"}', 'images/hero/hero-watch.png', 1, 1, NOW(), NOW()),
('home', 'shop_by_category', 'THE EXCLUSIVE HOROLOGY COLLECTION', 'Explore iconic timepieces curated across luxury categories.', '{\"badge\":\"SHOP BY CATEGORY\"}', NULL, 2, 1, NOW(), NOW()),
('home', 'featured_watches', 'CURATED ATELIER TIMEPIECES', 'Handpicked precision movements and iconic complications available with nationwide Cash on Delivery.', '{\"badge\":\"FEATURED RELEASES\",\"limit\":8}', NULL, 3, 1, NOW(), NOW()),
('home', 'promo_banner', 'MAKE YOUR MARK WITH TIMEYRAH', 'Every second tells a story. Craft yours with an authentic timepiece delivered to your doorstep.', '{\"badge\":\"CRAFTSMANSHIP & HERITAGE\",\"button_text\":\"Discover The Atelier\",\"button_url\":\"/shop\",\"accent_badge\":\"OFFICIAL WARRANTY INCLUDED\"}', 'images/make-banner.jpg', 4, 1, NOW(), NOW()),
('home', 'brand_showcase', 'ICONIC WATCHMAKERS & MANUFACTURES', 'Explore timepieces from Rado, Citizen, Seiko, Casio, Fossil, and premium ateliers.', '{\"badge\":\"OFFICIAL BRANDS\"}', NULL, 5, 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE `title` = VALUES(`title`), `subtitle` = VALUES(`subtitle`);

-- 4. Seed standard Specification Attributes & Predefined Values (if not already seeded)
INSERT INTO `spec_attributes` (`id`, `name`, `slug`, `category`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Movement Type', 'movement-type', 'technical', 1, NOW(), NOW()),
(2, 'Case Material', 'case-material', 'physical', 2, NOW(), NOW()),
(3, 'Case Diameter', 'case-diameter', 'dimensions', 3, NOW(), NOW()),
(4, 'Water Resistance', 'water-resistance', 'durability', 4, NOW(), NOW()),
(5, 'Crystal / Glass', 'crystal-glass', 'physical', 5, NOW(), NOW()),
(6, 'Power Reserve', 'power-reserve', 'technical', 6, NOW(), NOW()),
(7, 'Strap / Bracelet', 'strap-material', 'physical', 7, NOW(), NOW()),
(8, 'Dial Color', 'dial-color', 'aesthetic', 8, NOW(), NOW()),
(9, 'Clasp Type', 'clasp-type', 'physical', 9, NOW(), NOW()),
(10, 'Origin / Manufacture', 'country-of-origin', 'heritage', 10, NOW(), NOW())
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

INSERT INTO `spec_attribute_values` (`id`, `spec_attribute_id`, `value`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 1, 'Automatic Self-Winding', 1, NOW(), NOW()),
(2, 1, 'Manual Hand-Wind', 2, NOW(), NOW()),
(3, 1, 'Japanese Quartz Caliber', 3, NOW(), NOW()),
(4, 1, 'Swiss Quartz Movement', 4, NOW(), NOW()),
(5, 1, 'Solar-Powered Eco-Drive', 5, NOW(), NOW()),
(6, 2, '316L Stainless Steel', 1, NOW(), NOW()),
(7, 2, 'High-Tech Ceramic', 2, NOW(), NOW()),
(8, 2, 'Titanium Grade 5', 3, NOW(), NOW()),
(9, 2, 'Carbon Core Guard', 4, NOW(), NOW()),
(10, 4, '300m / 30 ATM (Pro Diver)', 1, NOW(), NOW()),
(11, 4, '200m / 20 ATM (ISO Diver)', 2, NOW(), NOW()),
(12, 4, '100m / 10 ATM (Everyday)', 3, NOW(), NOW()),
(13, 5, 'Sapphire Crystal (Anti-Reflective)', 1, NOW(), NOW()),
(14, 5, 'Mineral Crystal', 2, NOW(), NOW()),
(15, 6, '80 Hours (Powermatic 80)', 1, NOW(), NOW()),
(16, 6, '40-42 Hours (Standard Caliber)', 2, NOW(), NOW()),
(17, 7, 'Solid Link Stainless Steel', 1, NOW(), NOW()),
(18, 7, 'Italian Leather Strap', 2, NOW(), NOW()),
(19, 7, 'Vulcanized Fluoroelastomer FKM Rubber', 3, NOW(), NOW()),
(20, 10, 'Swiss Made (La Chaux-de-Fonds)', 1, NOW(), NOW()),
(21, 10, 'Japanese Manufacture (Tokyo)', 2, NOW(), NOW())
ON DUPLICATE KEY UPDATE `value` = VALUES(`value`);

SET FOREIGN_KEY_CHECKS = 1;
