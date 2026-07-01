<?php
/**
 * Database Schema Installer
 */

$schema = <<<'SQL'
-- Users Table
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `first_name` VARCHAR(255) NOT NULL,
  `last_name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) UNIQUE NOT NULL,
  `phone` VARCHAR(20),
  `password_hash` VARCHAR(255) NOT NULL,
  `date_of_birth` DATE,
  `street` VARCHAR(255),
  `city` VARCHAR(255),
  `state` VARCHAR(100),
  `zip_code` VARCHAR(20),
  `country` VARCHAR(100),
  `ssn_encrypted` VARCHAR(255),
  `verification_status` ENUM('unverified', 'pending', 'verified') DEFAULT 'unverified',
  `two_factor_enabled` BOOLEAN DEFAULT FALSE,
  `two_factor_method` ENUM('email', 'sms', 'authenticator') DEFAULT 'email',
  `last_login` TIMESTAMP NULL,
  `is_active` BOOLEAN DEFAULT TRUE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `email_idx` (`email`),
  INDEX `created_at_idx` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Accounts Table
CREATE TABLE IF NOT EXISTS `accounts` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT UNSIGNED NOT NULL,
  `account_name` VARCHAR(255) NOT NULL,
  `account_number` VARCHAR(17) UNIQUE NOT NULL,
  `account_type` ENUM('checking', 'savings', 'money_market', 'cd') DEFAULT 'checking',
  `balance` DECIMAL(15, 2) DEFAULT 0.00,
  `available_balance` DECIMAL(15, 2) DEFAULT 0.00,
  `interest_rate` DECIMAL(5, 3) DEFAULT 0.00,
  `routing_number` VARCHAR(9),
  `status` ENUM('active', 'inactive', 'closed', 'frozen') DEFAULT 'active',
  `opened_date` DATE NOT NULL,
  `closed_date` DATE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  INDEX `user_id_idx` (`user_id`),
  INDEX `account_number_idx` (`account_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Cards Table
CREATE TABLE IF NOT EXISTS `cards` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT UNSIGNED NOT NULL,
  `account_id` INT UNSIGNED NOT NULL,
  `card_number_encrypted` VARCHAR(255) NOT NULL,
  `card_type` ENUM('debit', 'credit', 'prepaid') DEFAULT 'debit',
  `card_brand` ENUM('visa', 'mastercard', 'amex', 'discover') DEFAULT 'visa',
  `holder_name` VARCHAR(255) NOT NULL,
  `expiry_month` TINYINT UNSIGNED,
  `expiry_year` YEAR,
  `cvv_encrypted` VARCHAR(255),
  `status` ENUM('active', 'inactive', 'locked', 'blocked', 'expired') DEFAULT 'active',
  `daily_limit` DECIMAL(10, 2) DEFAULT 1000.00,
  `monthly_limit` DECIMAL(10, 2) DEFAULT 10000.00,
  `issued_date` DATE NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`account_id`) REFERENCES `accounts`(`id`) ON DELETE CASCADE,
  INDEX `user_id_idx` (`user_id`),
  INDEX `account_id_idx` (`account_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Transactions Table
CREATE TABLE IF NOT EXISTS `transactions` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `account_id` INT UNSIGNED NOT NULL,
  `transaction_type` ENUM('deposit', 'withdrawal', 'transfer', 'payment', 'fee') DEFAULT 'deposit',
  `amount` DECIMAL(15, 2) NOT NULL,
  `description` TEXT,
  `reference_number` VARCHAR(50) UNIQUE,
  `status` ENUM('pending', 'completed', 'failed', 'cancelled') DEFAULT 'pending',
  `merchant_name` VARCHAR(255),
  `merchant_category` VARCHAR(100),
  `balance_after` DECIMAL(15, 2),
  `notes` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`account_id`) REFERENCES `accounts`(`id`) ON DELETE CASCADE,
  INDEX `account_id_idx` (`account_id`),
  INDEX `created_at_idx` (`created_at`),
  INDEX `status_idx` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Transfers Table
CREATE TABLE IF NOT EXISTS `transfers` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `from_account_id` INT UNSIGNED NOT NULL,
  `to_account_id` INT UNSIGNED NOT NULL,
  `amount` DECIMAL(15, 2) NOT NULL,
  `description` TEXT,
  `status` ENUM('pending', 'completed', 'failed') DEFAULT 'pending',
  `transfer_date` DATE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`from_account_id`) REFERENCES `accounts`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`to_account_id`) REFERENCES `accounts`(`id`) ON DELETE CASCADE,
  INDEX `from_account_idx` (`from_account_id`),
  INDEX `to_account_idx` (`to_account_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Beneficiaries Table
CREATE TABLE IF NOT EXISTS `beneficiaries` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT UNSIGNED NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `account_number` VARCHAR(17) NOT NULL,
  `routing_number` VARCHAR(9),
  `bank_name` VARCHAR(255),
  `account_type` VARCHAR(50),
  `verified` BOOLEAN DEFAULT FALSE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  INDEX `user_id_idx` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Audit Logs Table
CREATE TABLE IF NOT EXISTS `audit_logs` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT UNSIGNED,
  `action` VARCHAR(255) NOT NULL,
  `resource_type` VARCHAR(100),
  `resource_id` INT UNSIGNED,
  `old_values` JSON,
  `new_values` JSON,
  `ip_address` VARCHAR(45),
  `user_agent` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
  INDEX `user_id_idx` (`user_id`),
  INDEX `action_idx` (`action`),
  INDEX `created_at_idx` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Security Events Table
CREATE TABLE IF NOT EXISTS `security_events` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT UNSIGNED,
  `event_type` VARCHAR(100) NOT NULL,
  `description` TEXT,
  `severity` ENUM('low', 'medium', 'high', 'critical') DEFAULT 'medium',
  `ip_address` VARCHAR(45),
  `user_agent` TEXT,
  `resolved` BOOLEAN DEFAULT FALSE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
  INDEX `user_id_idx` (`user_id`),
  INDEX `event_type_idx` (`event_type`),
  INDEX `created_at_idx` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sessions Table
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` VARCHAR(128) PRIMARY KEY,
  `user_id` INT UNSIGNED,
  `data` LONGTEXT,
  `ip_address` VARCHAR(45),
  `user_agent` TEXT,
  `last_activity` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  INDEX `user_id_idx` (`user_id`),
  INDEX `last_activity_idx` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL;

echo "Database schema ready. Execute in MySQL to create tables.";
