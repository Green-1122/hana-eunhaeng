-- Hana-Eunhaeng Database Schema
-- Production-grade fintech banking platform

CREATE DATABASE IF NOT EXISTS `hana_eunhaeng` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `hana_eunhaeng`;

-- Users table
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `first_name` VARCHAR(100) NOT NULL,
  `last_name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(255) UNIQUE NOT NULL,
  `phone` VARCHAR(20),
  `password_hash` VARCHAR(255) NOT NULL,
  `ssn` VARCHAR(20),
  `date_of_birth` DATE,
  `address` VARCHAR(255),
  `city` VARCHAR(100),
  `state` VARCHAR(50),
  `zip_code` VARCHAR(10),
  `country` VARCHAR(100),
  `account_type` ENUM('personal', 'business', 'student', 'senior') DEFAULT 'personal',
  `employment_status` VARCHAR(50),
  `annual_income` DECIMAL(12, 2),
  `identity_verified` BOOLEAN DEFAULT FALSE,
  `email_verified_at` TIMESTAMP NULL,
  `two_factor_enabled` BOOLEAN DEFAULT FALSE,
  `two_factor_secret` VARCHAR(255),
  `last_login_at` TIMESTAMP NULL,
  `login_attempts` INT DEFAULT 0,
  `locked_until` TIMESTAMP NULL,
  `status` ENUM('active', 'suspended', 'closed', 'pending') DEFAULT 'pending',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `idx_email` (`email`),
  KEY `idx_status` (`status`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Accounts table
CREATE TABLE IF NOT EXISTS `accounts` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `account_number` VARCHAR(20) UNIQUE NOT NULL,
  `account_type` ENUM('checking', 'savings', 'money_market', 'cd') DEFAULT 'checking',
  `account_name` VARCHAR(100),
  `balance` DECIMAL(15, 2) DEFAULT 0,
  `available_balance` DECIMAL(15, 2) DEFAULT 0,
  `currency` VARCHAR(3) DEFAULT 'USD',
  `interest_rate` DECIMAL(5, 3) DEFAULT 0,
  `status` ENUM('active', 'frozen', 'closed', 'pending') DEFAULT 'active',
  `opening_date` DATE,
  `closing_date` DATE,
  `pin` VARCHAR(255),
  `is_primary` BOOLEAN DEFAULT FALSE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `idx_user_id` (`user_id`),
  KEY `idx_account_number` (`account_number`),
  KEY `idx_status` (`status`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Cards table
CREATE TABLE IF NOT EXISTS `cards` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `account_id` INT,
  `card_number` VARCHAR(19) UNIQUE NOT NULL,
  `card_type` ENUM('debit', 'credit', 'prepaid') DEFAULT 'debit',
  `card_brand` ENUM('visa', 'mastercard', 'amex', 'discover') DEFAULT 'visa',
  `holder_name` VARCHAR(100),
  `expiry_month` INT,
  `expiry_year` INT,
  `cvv_hash` VARCHAR(255),
  `status` ENUM('active', 'locked', 'expired', 'closed') DEFAULT 'active',
  `daily_limit` DECIMAL(10, 2),
  `international_enabled` BOOLEAN DEFAULT FALSE,
  `online_enabled` BOOLEAN DEFAULT TRUE,
  `contactless_enabled` BOOLEAN DEFAULT TRUE,
  `issued_date` DATE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `idx_user_id` (`user_id`),
  KEY `idx_card_number` (`card_number`),
  KEY `idx_status` (`status`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`account_id`) REFERENCES `accounts`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Transactions table
CREATE TABLE IF NOT EXISTS `transactions` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `account_id` INT NOT NULL,
  `card_id` INT,
  `transaction_type` ENUM('deposit', 'withdrawal', 'transfer', 'payment', 'fee', 'interest') DEFAULT 'transfer',
  `amount` DECIMAL(15, 2) NOT NULL,
  `currency` VARCHAR(3) DEFAULT 'USD',
  `description` VARCHAR(255),
  `merchant_name` VARCHAR(100),
  `merchant_category` VARCHAR(100),
  `reference_number` VARCHAR(50) UNIQUE,
  `status` ENUM('pending', 'completed', 'failed', 'reversed') DEFAULT 'pending',
  `failure_reason` VARCHAR(255),
  `balance_after` DECIMAL(15, 2),
  `recipient_account_id` INT,
  `recipient_bank` VARCHAR(100),
  `ip_address` VARCHAR(45),
  `device_info` TEXT,
  `location` VARCHAR(255),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `completed_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `idx_user_id` (`user_id`),
  KEY `idx_account_id` (`account_id`),
  KEY `idx_status` (`status`),
  KEY `idx_created_at` (`created_at`),
  KEY `idx_type` (`transaction_type`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`account_id`) REFERENCES `accounts`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`card_id`) REFERENCES `cards`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`recipient_account_id`) REFERENCES `accounts`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Beneficiaries table
CREATE TABLE IF NOT EXISTS `beneficiaries` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `nickname` VARCHAR(100),
  `account_number` VARCHAR(20) UNIQUE NOT NULL,
  `bank_name` VARCHAR(100),
  `routing_number` VARCHAR(20),
  `account_holder_name` VARCHAR(100),
  `account_type` VARCHAR(50),
  `is_verified` BOOLEAN DEFAULT FALSE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `idx_user_id` (`user_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Transfers table
CREATE TABLE IF NOT EXISTS `transfers` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `from_account_id` INT NOT NULL,
  `to_account_id` INT,
  `to_beneficiary_id` INT,
  `amount` DECIMAL(15, 2) NOT NULL,
  `currency` VARCHAR(3) DEFAULT 'USD',
  `description` VARCHAR(255),
  `transfer_type` ENUM('internal', 'external', 'scheduled', 'recurring') DEFAULT 'internal',
  `scheduled_date` DATE,
  `frequency` ENUM('once', 'daily', 'weekly', 'biweekly', 'monthly') DEFAULT 'once',
  `end_date` DATE,
  `status` ENUM('pending', 'processing', 'completed', 'failed', 'cancelled') DEFAULT 'pending',
  `failure_reason` VARCHAR(255),
  `reference_number` VARCHAR(50) UNIQUE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `completed_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `idx_from_account` (`from_account_id`),
  KEY `idx_to_account` (`to_account_id`),
  KEY `idx_status` (`status`),
  FOREIGN KEY (`from_account_id`) REFERENCES `accounts`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`to_account_id`) REFERENCES `accounts`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`to_beneficiary_id`) REFERENCES `beneficiaries`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Loans table
CREATE TABLE IF NOT EXISTS `loans` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `loan_type` ENUM('personal', 'auto', 'home', 'student') DEFAULT 'personal',
  `principal_amount` DECIMAL(15, 2) NOT NULL,
  `interest_rate` DECIMAL(5, 3) NOT NULL,
  `term_months` INT NOT NULL,
  `start_date` DATE,
  `maturity_date` DATE,
  `monthly_payment` DECIMAL(10, 2),
  `balance` DECIMAL(15, 2),
  `status` ENUM('active', 'paid_off', 'defaulted', 'pending') DEFAULT 'pending',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `idx_user_id` (`user_id`),
  KEY `idx_status` (`status`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bills table
CREATE TABLE IF NOT EXISTS `bills` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `account_id` INT,
  `biller_name` VARCHAR(100) NOT NULL,
  `account_number` VARCHAR(50),
  `bill_amount` DECIMAL(10, 2) NOT NULL,
  `due_date` DATE,
  `status` ENUM('pending', 'paid', 'overdue', 'cancelled') DEFAULT 'pending',
  `frequency` ENUM('once', 'monthly', 'quarterly', 'annual') DEFAULT 'once',
  `auto_pay_enabled` BOOLEAN DEFAULT FALSE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `idx_user_id` (`user_id`),
  KEY `idx_status` (`status`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`account_id`) REFERENCES `accounts`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Notifications table
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `title` VARCHAR(255),
  `message` TEXT,
  `type` ENUM('transaction', 'security', 'promotion', 'system', 'alert') DEFAULT 'transaction',
  `is_read` BOOLEAN DEFAULT FALSE,
  `action_url` VARCHAR(255),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  KEY `idx_user_id` (`user_id`),
  KEY `idx_is_read` (`is_read`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Audit logs table
CREATE TABLE IF NOT EXISTS `audit_logs` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `user_id` INT,
  `action` VARCHAR(100),
  `resource_type` VARCHAR(50),
  `resource_id` INT,
  `old_values` JSON,
  `new_values` JSON,
  `ip_address` VARCHAR(45),
  `user_agent` VARCHAR(255),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  KEY `idx_user_id` (`user_id`),
  KEY `idx_action` (`action`),
  KEY `idx_created_at` (`created_at`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Security events table
CREATE TABLE IF NOT EXISTS `security_events` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `user_id` INT,
  `event_type` ENUM('login_attempt', 'failed_login', 'password_change', 'card_used', 'suspicious_activity', 'device_added') DEFAULT 'login_attempt',
  `severity` ENUM('low', 'medium', 'high', 'critical') DEFAULT 'low',
  `description` TEXT,
  `ip_address` VARCHAR(45),
  `device_info` TEXT,
  `location` VARCHAR(255),
  `is_verified` BOOLEAN DEFAULT FALSE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  KEY `idx_user_id` (`user_id`),
  KEY `idx_event_type` (`event_type`),
  KEY `idx_severity` (`severity`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create indexes for performance
CREATE INDEX idx_accounts_user_status ON `accounts`(`user_id`, `status`);
CREATE INDEX idx_transactions_account_date ON `transactions`(`account_id`, `created_at`);
CREATE INDEX idx_transactions_user_date ON `transactions`(`user_id`, `created_at`);
CREATE INDEX idx_cards_user_status ON `cards`(`user_id`, `status`);

-- Create views for common queries
CREATE OR REPLACE VIEW `v_user_summary` AS
SELECT 
  u.id,
  u.first_name,
  u.last_name,
  u.email,
  u.status,
  COUNT(DISTINCT a.id) as account_count,
  SUM(a.balance) as total_balance,
  u.created_at
FROM `users` u
LEFT JOIN `accounts` a ON u.id = a.user_id
GROUP BY u.id;

CREATE OR REPLACE VIEW `v_account_summary` AS
SELECT 
  a.id,
  a.user_id,
  a.account_number,
  a.account_type,
  a.balance,
  a.status,
  COUNT(t.id) as transaction_count,
  MAX(t.created_at) as last_transaction,
  a.created_at
FROM `accounts` a
LEFT JOIN `transactions` t ON a.id = t.account_id
GROUP BY a.id;
