-- Back up the live database first, then select the Laravel database in phpMyAdmin
-- and run this script. It is based on kics_ssrlstaff.sql.
--
-- The dump already contains the ERP image/visibility columns, the employee
-- visibility column, and the events table. This script creates missing feature
-- tables and staff profile columns, then records applied schemas in Laravel's
-- migration ledger so Laravel does not try to recreate them.

CREATE TABLE IF NOT EXISTS `announcement_popups` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(255) NULL,
    `image_path` VARCHAR(255) NOT NULL,
    `link_url` VARCHAR(255) NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `page_hero_banners` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `page_key` VARCHAR(255) NOT NULL,
    `image_path` VARCHAR(255) NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `page_hero_banners_page_key_unique` (`page_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Staff-entered profile fields. Each column is added only if it is missing.
SET @schema_name := DATABASE();
SET @ddl := IF((SELECT COUNT(*) FROM information_schema.columns WHERE table_schema=@schema_name AND table_name='people' AND column_name='about_me')=0, 'ALTER TABLE `people` ADD COLUMN `about_me` LONGTEXT NULL', 'SELECT 1'); PREPARE stmt FROM @ddl; EXECUTE stmt; DEALLOCATE PREPARE stmt;
SET @ddl := IF((SELECT COUNT(*) FROM information_schema.columns WHERE table_schema=@schema_name AND table_name='people' AND column_name='education')=0, 'ALTER TABLE `people` ADD COLUMN `education` LONGTEXT NULL', 'SELECT 1'); PREPARE stmt FROM @ddl; EXECUTE stmt; DEALLOCATE PREPARE stmt;
SET @ddl := IF((SELECT COUNT(*) FROM information_schema.columns WHERE table_schema=@schema_name AND table_name='people' AND column_name='achievements')=0, 'ALTER TABLE `people` ADD COLUMN `achievements` LONGTEXT NULL', 'SELECT 1'); PREPARE stmt FROM @ddl; EXECUTE stmt; DEALLOCATE PREPARE stmt;
SET @ddl := IF((SELECT COUNT(*) FROM information_schema.columns WHERE table_schema=@schema_name AND table_name='people' AND column_name='certifications')=0, 'ALTER TABLE `people` ADD COLUMN `certifications` LONGTEXT NULL', 'SELECT 1'); PREPARE stmt FROM @ddl; EXECUTE stmt; DEALLOCATE PREPARE stmt;
SET @ddl := IF((SELECT COUNT(*) FROM information_schema.columns WHERE table_schema=@schema_name AND table_name='people' AND column_name='publications')=0, 'ALTER TABLE `people` ADD COLUMN `publications` LONGTEXT NULL', 'SELECT 1'); PREPARE stmt FROM @ddl; EXECUTE stmt; DEALLOCATE PREPARE stmt;
SET @ddl := IF((SELECT COUNT(*) FROM information_schema.columns WHERE table_schema=@schema_name AND table_name='people' AND column_name='work_experience')=0, 'ALTER TABLE `people` ADD COLUMN `work_experience` LONGTEXT NULL', 'SELECT 1'); PREPARE stmt FROM @ddl; EXECUTE stmt; DEALLOCATE PREPARE stmt;
SET @ddl := IF((SELECT COUNT(*) FROM information_schema.columns WHERE table_schema=@schema_name AND table_name='people' AND column_name='projects')=0, 'ALTER TABLE `people` ADD COLUMN `projects` LONGTEXT NULL', 'SELECT 1'); PREPARE stmt FROM @ddl; EXECUTE stmt; DEALLOCATE PREPARE stmt;
SET @ddl := IF((SELECT COUNT(*) FROM information_schema.columns WHERE table_schema=@schema_name AND table_name='people' AND column_name='profile_photo_path')=0, 'ALTER TABLE `people` ADD COLUMN `profile_photo_path` VARCHAR(255) NULL', 'SELECT 1'); PREPARE stmt FROM @ddl; EXECUTE stmt; DEALLOCATE PREPARE stmt;
SET @ddl := IF((SELECT COUNT(*) FROM information_schema.columns WHERE table_schema=@schema_name AND table_name='people' AND column_name='linkedin_url')=0, 'ALTER TABLE `people` ADD COLUMN `linkedin_url` VARCHAR(255) NULL', 'SELECT 1'); PREPARE stmt FROM @ddl; EXECUTE stmt; DEALLOCATE PREPARE stmt;
SET @ddl := IF((SELECT COUNT(*) FROM information_schema.columns WHERE table_schema=@schema_name AND table_name='people' AND column_name='github_url')=0, 'ALTER TABLE `people` ADD COLUMN `github_url` VARCHAR(255) NULL', 'SELECT 1'); PREPARE stmt FROM @ddl; EXECUTE stmt; DEALLOCATE PREPARE stmt;
SET @ddl := IF((SELECT COUNT(*) FROM information_schema.columns WHERE table_schema=@schema_name AND table_name='people' AND column_name='website_url')=0, 'ALTER TABLE `people` ADD COLUMN `website_url` VARCHAR(255) NULL', 'SELECT 1'); PREPARE stmt FROM @ddl; EXECUTE stmt; DEALLOCATE PREPARE stmt;
SET @ddl := IF((SELECT COUNT(*) FROM information_schema.columns WHERE table_schema=@schema_name AND table_name='people' AND column_name='profile_visible')=0, 'ALTER TABLE `people` ADD COLUMN `profile_visible` TINYINT(1) NOT NULL DEFAULT 1', 'SELECT 1'); PREPARE stmt FROM @ddl; EXECUTE stmt; DEALLOCATE PREPARE stmt;
SET @ddl := IF((SELECT COUNT(*) FROM information_schema.columns WHERE table_schema=@schema_name AND table_name='people' AND column_name='profile_edit_locked')=0, 'ALTER TABLE `people` ADD COLUMN `profile_edit_locked` TINYINT(1) NOT NULL DEFAULT 0', 'SELECT 1'); PREPARE stmt FROM @ddl; EXECUTE stmt; DEALLOCATE PREPARE stmt;
SET @ddl := IF((SELECT COUNT(*) FROM information_schema.columns WHERE table_schema=@schema_name AND table_name='people' AND column_name='profile_updated_at')=0, 'ALTER TABLE `people` ADD COLUMN `profile_updated_at` TIMESTAMP NULL', 'SELECT 1'); PREPARE stmt FROM @ddl; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @manual_migration_batch := (
    SELECT COALESCE(MAX(`batch`), 0) + 1
    FROM `migrations`
);

INSERT INTO `migrations` (`migration`, `batch`)
SELECT pending.`migration`, @manual_migration_batch
FROM (
    SELECT '2026_07_10_000000_add_image_fields_to_erp_tables' AS `migration`
    UNION ALL SELECT '2026_07_15_create_events_table'
    UNION ALL SELECT '2026_08_17_000001_add_missing_erp_visibility_and_media_columns'
    UNION ALL SELECT '2026_08_18_000001_add_is_visible_to_employees_table'
    UNION ALL SELECT '2026_09_30_000001_create_announcement_popups_table'
    UNION ALL SELECT '2026_10_06_000002_create_page_hero_banners_table'
    UNION ALL SELECT '2026_10_07_000001_add_staff_profile_fields_to_people_table'
) AS pending
WHERE NOT EXISTS (
    SELECT 1
    FROM `migrations` AS existing
    WHERE existing.`migration` = pending.`migration`
);
