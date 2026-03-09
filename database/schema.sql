-- ============================================
-- TSILIZY LLC — Database Schema
-- MySQL 5.7+ / MariaDB 10.3+
-- ============================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ============================================
-- USERS & AUTHENTICATION
-- ============================================

-- Users table
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `first_name` VARCHAR(100) NOT NULL,
    `last_name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(20) NULL,
    `avatar` VARCHAR(255) NULL,
    `bio` TEXT NULL,
    `status` ENUM('active', 'suspended', 'banned') DEFAULT 'active',
    `email_verified_at` TIMESTAMP NULL,
    `last_login_at` TIMESTAMP NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    INDEX `idx_users_email` (`email`),
    INDEX `idx_users_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Roles table
CREATE TABLE IF NOT EXISTS `roles` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `slug` VARCHAR(100) NOT NULL UNIQUE,
    `description` TEXT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Permissions table
CREATE TABLE IF NOT EXISTS `permissions` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `slug` VARCHAR(100) NOT NULL UNIQUE,
    `group` VARCHAR(50) NOT NULL,
    `description` TEXT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_permissions_group` (`group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Role permissions pivot table
CREATE TABLE IF NOT EXISTS `role_permissions` (
    `role_id` INT UNSIGNED NOT NULL,
    `permission_id` INT UNSIGNED NOT NULL,
    PRIMARY KEY (`role_id`, `permission_id`),
    FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`permission_id`) REFERENCES `permissions`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- User roles pivot table
CREATE TABLE IF NOT EXISTS `user_roles` (
    `user_id` INT UNSIGNED NOT NULL,
    `role_id` INT UNSIGNED NOT NULL,
    PRIMARY KEY (`user_id`, `role_id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Password resets table
CREATE TABLE IF NOT EXISTS `password_resets` (
    `email` VARCHAR(255) NOT NULL,
    `token` VARCHAR(255) NOT NULL,
    `expires_at` TIMESTAMP NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_password_resets_email` (`email`),
    INDEX `idx_password_resets_token` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- CONTENT MANAGEMENT
-- ============================================

-- Pages table
CREATE TABLE IF NOT EXISTS `pages` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL UNIQUE,
    `content` LONGTEXT NULL,
    `excerpt` TEXT NULL,
    `featured_image` VARCHAR(255) NULL,
    `status` ENUM('draft', 'published', 'archived') DEFAULT 'draft',
    `seo_title` VARCHAR(255) NULL,
    `seo_description` TEXT NULL,
    `seo_keywords` VARCHAR(255) NULL,
    `template` VARCHAR(100) DEFAULT 'default',
    `order_index` INT DEFAULT 0,
    `created_by` INT UNSIGNED NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    INDEX `idx_pages_slug` (`slug`),
    INDEX `idx_pages_status` (`status`),
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Media table
CREATE TABLE IF NOT EXISTS `media` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `filename` VARCHAR(255) NOT NULL,
    `original_name` VARCHAR(255) NOT NULL,
    `path` VARCHAR(500) NOT NULL,
    `mime_type` VARCHAR(100) NOT NULL,
    `size` INT UNSIGNED NOT NULL,
    `alt_text` VARCHAR(255) NULL,
    `caption` TEXT NULL,
    `folder` VARCHAR(100) DEFAULT 'general',
    `uploaded_by` INT UNSIGNED NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    INDEX `idx_media_folder` (`folder`),
    FOREIGN KEY (`uploaded_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Announcements table
CREATE TABLE IF NOT EXISTS `announcements` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL UNIQUE,
    `content` LONGTEXT NOT NULL,
    `excerpt` TEXT NULL,
    `featured_image` VARCHAR(255) NULL,
    `type` ENUM('info', 'warning', 'success', 'urgent') DEFAULT 'info',
    `status` ENUM('draft', 'published', 'archived') DEFAULT 'draft',
    `is_pinned` TINYINT(1) DEFAULT 0,
    `publish_at` TIMESTAMP NULL,
    `expires_at` TIMESTAMP NULL,
    `created_by` INT UNSIGNED NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    INDEX `idx_announcements_status` (`status`),
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- BUSINESS MODULES
-- ============================================

-- Services table
CREATE TABLE IF NOT EXISTS `services` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL UNIQUE,
    `description` LONGTEXT NOT NULL,
    `short_description` TEXT NULL,
    `icon` VARCHAR(100) NULL,
    `image` VARCHAR(255) NULL,
    `price` DECIMAL(10,2) NULL,
    `duration` VARCHAR(100) NULL,
    `features` JSON NULL,
    `status` ENUM('active', 'inactive') DEFAULT 'active',
    `is_featured` TINYINT(1) DEFAULT 0,
    `order_index` INT DEFAULT 0,
    `seo_title` VARCHAR(255) NULL,
    `seo_description` TEXT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    INDEX `idx_services_status` (`status`),
    INDEX `idx_services_featured` (`is_featured`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Products table
CREATE TABLE IF NOT EXISTS `products` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL UNIQUE,
    `description` LONGTEXT NOT NULL,
    `short_description` TEXT NULL,
    `image` VARCHAR(255) NULL,
    `gallery` JSON NULL,
    `price` DECIMAL(10,2) NOT NULL,
    `sale_price` DECIMAL(10,2) NULL,
    `sku` VARCHAR(100) NULL,
    `stock_quantity` INT DEFAULT 0,
    `category` VARCHAR(100) NULL,
    `features` JSON NULL,
    `status` ENUM('active', 'inactive', 'out_of_stock') DEFAULT 'active',
    `is_featured` TINYINT(1) DEFAULT 0,
    `order_index` INT DEFAULT 0,
    `seo_title` VARCHAR(255) NULL,
    `seo_description` TEXT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    INDEX `idx_products_status` (`status`),
    INDEX `idx_products_category` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Portfolio table
CREATE TABLE IF NOT EXISTS `portfolio` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL UNIQUE,
    `description` LONGTEXT NOT NULL,
    `short_description` TEXT NULL,
    `client_name` VARCHAR(255) NULL,
    `image` VARCHAR(255) NULL,
    `gallery` JSON NULL,
    `category` VARCHAR(100) NULL,
    `tags` JSON NULL,
    `project_url` VARCHAR(500) NULL,
    `completion_date` DATE NULL,
    `status` ENUM('active', 'inactive') DEFAULT 'active',
    `is_featured` TINYINT(1) DEFAULT 0,
    `order_index` INT DEFAULT 0,
    `seo_title` VARCHAR(255) NULL,
    `seo_description` TEXT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    INDEX `idx_portfolio_category` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Projects table
CREATE TABLE IF NOT EXISTS `projects` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL UNIQUE,
    `description` LONGTEXT NOT NULL,
    `short_description` TEXT NULL,
    `client_id` INT UNSIGNED NULL,
    `image` VARCHAR(255) NULL,
    `budget` DECIMAL(12,2) NULL,
    `start_date` DATE NULL,
    `end_date` DATE NULL,
    `status` ENUM('pending', 'in_progress', 'completed', 'cancelled') DEFAULT 'pending',
    `progress` INT DEFAULT 0,
    `visibility` ENUM('public', 'private') DEFAULT 'private',
    `created_by` INT UNSIGNED NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    INDEX `idx_projects_status` (`status`),
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Partners table
CREATE TABLE IF NOT EXISTS `partners` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL UNIQUE,
    `description` TEXT NULL,
    `logo` VARCHAR(255) NULL,
    `website_url` VARCHAR(500) NULL,
    `type` ENUM('partner', 'sponsor', 'client') DEFAULT 'partner',
    `status` ENUM('active', 'inactive') DEFAULT 'active',
    `is_featured` TINYINT(1) DEFAULT 0,
    `order_index` INT DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    INDEX `idx_partners_type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Events table
CREATE TABLE IF NOT EXISTS `events` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL UNIQUE,
    `description` LONGTEXT NOT NULL,
    `short_description` TEXT NULL,
    `image` VARCHAR(255) NULL,
    `location` VARCHAR(500) NULL,
    `location_url` VARCHAR(500) NULL,
    `start_datetime` DATETIME NOT NULL,
    `end_datetime` DATETIME NULL,
    `registration_url` VARCHAR(500) NULL,
    `max_attendees` INT NULL,
    `current_attendees` INT DEFAULT 0,
    `is_free` TINYINT(1) DEFAULT 1,
    `price` DECIMAL(10,2) NULL,
    `status` ENUM('upcoming', 'ongoing', 'completed', 'cancelled') DEFAULT 'upcoming',
    `is_featured` TINYINT(1) DEFAULT 0,
    `seo_title` VARCHAR(255) NULL,
    `seo_description` TEXT NULL,
    `created_by` INT UNSIGNED NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    INDEX `idx_events_status` (`status`),
    INDEX `idx_events_start` (`start_datetime`),
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- CAREER & DOCUMENTS
-- ============================================

-- Job offers table
CREATE TABLE IF NOT EXISTS `job_offers` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL UNIQUE,
    `description` LONGTEXT NOT NULL,
    `requirements` LONGTEXT NULL,
    `benefits` LONGTEXT NULL,
    `department` VARCHAR(100) NULL,
    `location` VARCHAR(255) NULL,
    `type` ENUM('full_time', 'part_time', 'contract', 'internship', 'remote') DEFAULT 'full_time',
    `salary_min` DECIMAL(10,2) NULL,
    `salary_max` DECIMAL(10,2) NULL,
    `salary_currency` VARCHAR(10) DEFAULT 'EUR',
    `experience_level` ENUM('junior', 'mid', 'senior', 'lead') DEFAULT 'mid',
    `status` ENUM('open', 'closed', 'draft') DEFAULT 'draft',
    `applications_enabled` TINYINT(1) DEFAULT 1,
    `closes_at` DATE NULL,
    `seo_title` VARCHAR(255) NULL,
    `seo_description` TEXT NULL,
    `created_by` INT UNSIGNED NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    INDEX `idx_job_offers_status` (`status`),
    INDEX `idx_job_offers_type` (`type`),
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Job applications table
CREATE TABLE IF NOT EXISTS `job_applications` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `job_offer_id` INT UNSIGNED NOT NULL,
    `user_id` INT UNSIGNED NULL,
    `first_name` VARCHAR(100) NOT NULL,
    `last_name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(20) NULL,
    `cover_letter` LONGTEXT NULL,
    `resume_path` VARCHAR(255) NULL,
    `portfolio_url` VARCHAR(500) NULL,
    `linkedin_url` VARCHAR(500) NULL,
    `status` ENUM('pending', 'reviewed', 'shortlisted', 'interviewed', 'offered', 'hired', 'rejected') DEFAULT 'pending',
    `notes` TEXT NULL,
    `reviewed_by` INT UNSIGNED NULL,
    `reviewed_at` TIMESTAMP NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_job_applications_status` (`status`),
    FOREIGN KEY (`job_offer_id`) REFERENCES `job_offers`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`reviewed_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Contracts table
CREATE TABLE IF NOT EXISTS `contracts` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `reference` VARCHAR(50) NOT NULL UNIQUE,
    `title` VARCHAR(255) NOT NULL,
    `client_name` VARCHAR(255) NOT NULL,
    `client_email` VARCHAR(255) NULL,
    `client_address` TEXT NULL,
    `content` LONGTEXT NOT NULL,
    `terms` LONGTEXT NULL,
    `start_date` DATE NULL,
    `end_date` DATE NULL,
    `value` DECIMAL(12,2) NULL,
    `status` ENUM('draft', 'sent', 'signed', 'active', 'completed', 'cancelled') DEFAULT 'draft',
    `signed_at` TIMESTAMP NULL,
    `created_by` INT UNSIGNED NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    INDEX `idx_contracts_status` (`status`),
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Invoices table
CREATE TABLE IF NOT EXISTS `invoices` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `reference` VARCHAR(50) NOT NULL UNIQUE,
    `client_name` VARCHAR(255) NOT NULL,
    `client_email` VARCHAR(255) NULL,
    `client_address` TEXT NULL,
    `client_vat` VARCHAR(50) NULL,
    `items` JSON NOT NULL,
    `subtotal` DECIMAL(12,2) NOT NULL,
    `tax_rate` DECIMAL(5,2) DEFAULT 20.00,
    `tax_amount` DECIMAL(12,2) NOT NULL,
    `discount` DECIMAL(12,2) DEFAULT 0,
    `total` DECIMAL(12,2) NOT NULL,
    `currency` VARCHAR(10) DEFAULT 'EUR',
    `notes` TEXT NULL,
    `terms` TEXT NULL,
    `status` ENUM('draft', 'sent', 'paid', 'partial', 'overdue', 'cancelled') DEFAULT 'draft',
    `issue_date` DATE NOT NULL,
    `due_date` DATE NOT NULL,
    `paid_at` TIMESTAMP NULL,
    `created_by` INT UNSIGNED NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    INDEX `idx_invoices_status` (`status`),
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Agenda items table
CREATE TABLE IF NOT EXISTS `agenda_items` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT NULL,
    `start_datetime` DATETIME NOT NULL,
    `end_datetime` DATETIME NULL,
    `all_day` TINYINT(1) DEFAULT 0,
    `location` VARCHAR(255) NULL,
    `type` ENUM('meeting', 'task', 'reminder', 'event', 'other') DEFAULT 'task',
    `priority` ENUM('low', 'medium', 'high') DEFAULT 'medium',
    `color` VARCHAR(20) DEFAULT '#3B82F6',
    `is_completed` TINYINT(1) DEFAULT 0,
    `reminder_at` DATETIME NULL,
    `user_id` INT UNSIGNED NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    INDEX `idx_agenda_start` (`start_datetime`),
    INDEX `idx_agenda_user` (`user_id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- COMMUNICATION
-- ============================================

-- Contacts table
CREATE TABLE IF NOT EXISTS `contacts` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(20) NULL,
    `subject` VARCHAR(255) NULL,
    `message` LONGTEXT NOT NULL,
    `status` ENUM('new', 'read', 'replied', 'archived') DEFAULT 'new',
    `ip_address` VARCHAR(45) NULL,
    `replied_by` INT UNSIGNED NULL,
    `replied_at` TIMESTAMP NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    INDEX `idx_contacts_status` (`status`),
    FOREIGN KEY (`replied_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tickets table
CREATE TABLE IF NOT EXISTS `tickets` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `reference` VARCHAR(20) NOT NULL UNIQUE,
    `user_id` INT UNSIGNED NULL,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `subject` VARCHAR(255) NOT NULL,
    `message` LONGTEXT NOT NULL,
    `category` ENUM('general', 'technical', 'billing', 'partnership', 'other') DEFAULT 'general',
    `priority` ENUM('low', 'medium', 'high', 'urgent') DEFAULT 'medium',
    `status` ENUM('open', 'in_progress', 'waiting', 'resolved', 'closed') DEFAULT 'open',
    `assigned_to` INT UNSIGNED NULL,
    `resolved_at` TIMESTAMP NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    INDEX `idx_tickets_status` (`status`),
    INDEX `idx_tickets_priority` (`priority`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`assigned_to`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Ticket replies table
CREATE TABLE IF NOT EXISTS `ticket_replies` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `ticket_id` INT UNSIGNED NOT NULL,
    `user_id` INT UNSIGNED NULL,
    `message` LONGTEXT NOT NULL,
    `is_admin` TINYINT(1) DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`ticket_id`) REFERENCES `tickets`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Reviews table
CREATE TABLE IF NOT EXISTS `reviews` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` INT UNSIGNED NOT NULL,
    `rating` TINYINT UNSIGNED NOT NULL,
    `title` VARCHAR(255) NULL,
    `comment` TEXT NOT NULL,
    `status` ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    `is_featured` TINYINT(1) DEFAULT 0,
    `moderated_by` INT UNSIGNED NULL,
    `moderated_at` TIMESTAMP NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    INDEX `idx_reviews_status` (`status`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`moderated_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Newsletter subscribers table
CREATE TABLE IF NOT EXISTS `newsletter_subscribers` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `name` VARCHAR(255) NULL,
    `status` ENUM('active', 'unsubscribed', 'bounced') DEFAULT 'active',
    `token` VARCHAR(255) NOT NULL,
    `subscribed_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `unsubscribed_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    INDEX `idx_newsletter_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Newsletter campaigns table
CREATE TABLE IF NOT EXISTS `newsletter_campaigns` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `subject` VARCHAR(255) NOT NULL,
    `content` LONGTEXT NOT NULL,
    `status` ENUM('draft', 'scheduled', 'sending', 'sent') DEFAULT 'draft',
    `scheduled_at` TIMESTAMP NULL,
    `sent_at` TIMESTAMP NULL,
    `recipients_count` INT DEFAULT 0,
    `opens_count` INT DEFAULT 0,
    `clicks_count` INT DEFAULT 0,
    `created_by` INT UNSIGNED NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Surveys table
CREATE TABLE IF NOT EXISTS `surveys` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT NULL,
    `questions` JSON NOT NULL,
    `status` ENUM('draft', 'active', 'closed') DEFAULT 'draft',
    `is_anonymous` TINYINT(1) DEFAULT 0,
    `starts_at` TIMESTAMP NULL,
    `ends_at` TIMESTAMP NULL,
    `created_by` INT UNSIGNED NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    INDEX `idx_surveys_status` (`status`),
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Survey responses table
CREATE TABLE IF NOT EXISTS `survey_responses` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `survey_id` INT UNSIGNED NOT NULL,
    `user_id` INT UNSIGNED NULL,
    `answers` JSON NOT NULL,
    `ip_address` VARCHAR(45) NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`survey_id`) REFERENCES `surveys`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- SYSTEM
-- ============================================

-- Settings table
CREATE TABLE IF NOT EXISTS `settings` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `group` VARCHAR(50) NOT NULL,
    `key` VARCHAR(100) NOT NULL,
    `value` LONGTEXT NULL,
    `type` ENUM('string', 'text', 'number', 'boolean', 'json') DEFAULT 'string',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `idx_settings_group_key` (`group`, `key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Audit logs table
CREATE TABLE IF NOT EXISTS `audit_logs` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` INT UNSIGNED NULL,
    `action` VARCHAR(100) NOT NULL,
    `entity_type` VARCHAR(100) NULL,
    `entity_id` INT UNSIGNED NULL,
    `old_values` JSON NULL,
    `new_values` JSON NULL,
    `ip_address` VARCHAR(45) NULL,
    `user_agent` TEXT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_audit_user` (`user_id`),
    INDEX `idx_audit_action` (`action`),
    INDEX `idx_audit_entity` (`entity_type`, `entity_id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Analytics table
CREATE TABLE IF NOT EXISTS `analytics` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `page_path` VARCHAR(500) NOT NULL,
    `page_title` VARCHAR(255) NULL,
    `visitor_id` VARCHAR(100) NULL,
    `user_id` INT UNSIGNED NULL,
    `ip_address` VARCHAR(45) NULL,
    `user_agent` TEXT NULL,
    `referrer` VARCHAR(500) NULL,
    `country` VARCHAR(100) NULL,
    `city` VARCHAR(100) NULL,
    `device_type` ENUM('desktop', 'tablet', 'mobile') NULL,
    `browser` VARCHAR(100) NULL,
    `os` VARCHAR(100) NULL,
    `session_id` VARCHAR(100) NULL,
    `time_on_page` INT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_analytics_page` (`page_path`(255)),
    INDEX `idx_analytics_date` (`created_at`),
    INDEX `idx_analytics_visitor` (`visitor_id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- DEFAULT DATA
-- ============================================

-- Default roles
INSERT INTO `roles` (`name`, `slug`, `description`) VALUES
('Super Administrateur', 'super_admin', 'Accès complet à toutes les fonctionnalités'),
('Administrateur', 'admin', 'Gestion du contenu et des utilisateurs'),
('Éditeur', 'editor', 'Création et modification de contenu'),
('Utilisateur', 'user', 'Accès utilisateur standard');

-- Default permissions
INSERT INTO `permissions` (`name`, `slug`, `group`) VALUES
-- Users
('Voir les utilisateurs', 'users.view', 'users'),
('Créer des utilisateurs', 'users.create', 'users'),
('Modifier les utilisateurs', 'users.edit', 'users'),
('Supprimer les utilisateurs', 'users.delete', 'users'),
-- Pages
('Voir les pages', 'pages.view', 'content'),
('Créer des pages', 'pages.create', 'content'),
('Modifier les pages', 'pages.edit', 'content'),
('Supprimer les pages', 'pages.delete', 'content'),
-- Services
('Gérer les services', 'services.manage', 'business'),
-- Products
('Gérer les produits', 'products.manage', 'business'),
-- Portfolio
('Gérer le portfolio', 'portfolio.manage', 'business'),
-- Partners
('Gérer les partenaires', 'partners.manage', 'business'),
-- Events
('Gérer les événements', 'events.manage', 'business'),
-- Jobs
('Gérer les offres d''emploi', 'jobs.manage', 'career'),
('Voir les candidatures', 'applications.view', 'career'),
-- Documents
('Gérer les contrats', 'contracts.manage', 'documents'),
('Gérer les factures', 'invoices.manage', 'documents'),
-- Communication
('Voir les contacts', 'contacts.view', 'communication'),
('Gérer les tickets', 'tickets.manage', 'communication'),
('Modérer les avis', 'reviews.moderate', 'communication'),
('Gérer la newsletter', 'newsletter.manage', 'communication'),
('Gérer les sondages', 'surveys.manage', 'communication'),
-- System
('Voir les analytiques', 'analytics.view', 'system'),
('Gérer les paramètres', 'settings.manage', 'system'),
('Voir les journaux', 'audit.view', 'system');

-- Assign all permissions to super_admin and admin
INSERT INTO `role_permissions` (`role_id`, `permission_id`)
SELECT r.id, p.id FROM `roles` r, `permissions` p WHERE r.slug = 'super_admin';

INSERT INTO `role_permissions` (`role_id`, `permission_id`)
SELECT r.id, p.id FROM `roles` r, `permissions` p WHERE r.slug = 'admin';

-- Assign content permissions to editor
INSERT INTO `role_permissions` (`role_id`, `permission_id`)
SELECT r.id, p.id FROM `roles` r, `permissions` p 
WHERE r.slug = 'editor' AND p.`group` IN ('content', 'business');

-- Default admin user (password: Admin@123)
INSERT INTO `users` (`first_name`, `last_name`, `email`, `password`, `status`, `email_verified_at`) VALUES
('Admin', 'TSILIZY', 'admin@tsilizy.com', '$2y$12$G8rI.xVwBzqwt.v8kxX3Ieu3H5d0F1Z0h9rgv7oN5qE9f6D8g3k2a', 'active', NOW());

-- Assign super_admin role to default admin
INSERT INTO `user_roles` (`user_id`, `role_id`)
SELECT u.id, r.id FROM `users` u, `roles` r WHERE u.email = 'admin@tsilizy.com' AND r.slug = 'super_admin';

-- Default settings
INSERT INTO `settings` (`group`, `key`, `value`, `type`) VALUES
('general', 'site_name', 'TSILIZY LLC', 'string'),
('general', 'site_tagline', 'Excellence & Innovation', 'string'),
('general', 'site_email', 'contact@tsilizy.com', 'string'),
('general', 'site_phone', '+33 1 23 45 67 89', 'string'),
('general', 'site_address', 'Paris, France', 'string'),
('seo', 'meta_title', 'TSILIZY LLC | Excellence & Innovation', 'string'),
('seo', 'meta_description', 'Découvrez TSILIZY LLC, votre partenaire de confiance pour des solutions innovantes et un service d''excellence.', 'text'),
('seo', 'meta_keywords', 'tsilizy, entreprise, innovation, excellence, services', 'string'),
('features', 'enable_registration', '1', 'boolean'),
('features', 'enable_reviews', '1', 'boolean'),
('features', 'enable_newsletter', '1', 'boolean'),
('features', 'enable_jobs', '1', 'boolean'),
('features', 'enable_surveys', '1', 'boolean'),
('features', 'auto_approve_reviews', '0', 'boolean'),
('features', 'maintenance_mode', '0', 'boolean');

SET FOREIGN_KEY_CHECKS = 1;
