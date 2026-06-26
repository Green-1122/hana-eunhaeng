-- Hana-Eunhaeng Banking Platform - Complete Database Schema
-- Production-Grade Fintech Database Design
-- MySQL 8.0+

-- ============================================================================
-- USERS TABLE - Core user information
-- ============================================================================
CREATE TABLE IF NOT EXISTS `he_users` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `email` VARCHAR(255) NOT NULL UNIQUE,
  `phone` VARCHAR(20) UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `first_name` VARCHAR(100) NOT NULL,
  `last_name` VARCHAR(100) NOT NULL,
  `date_of_birth` DATE NOT NULL,
  `ssn_hash` VARCHAR(255) NOT NULL UNIQUE,
  `gender` ENUM('M', 'F', 'Other', 'Prefer Not to Say') DEFAULT 'Prefer Not to Say',
  `address_street` VARCHAR(255),
  `address_city` VARCHAR(100),
  `address_state` VARCHAR(50),
  `address_zip` VARCHAR(20),
  `address_country` VARCHAR(100),
  `occupation` VARCHAR(100),
  `employment_status` ENUM('Employed', 'Self-Employed', 'Retired', 'Student', 'Unemployed') DEFAULT 'Employed',
  `annual_income` DECIMAL(15, 2),
  `profile_picture` VARCHAR(255),
  `bio` TEXT,
  `preferred_language` VARCHAR(10) DEFAULT 'en',
  `timezone` VARCHAR(50) DEFAULT 'UTC',
  `status` ENUM('Active', 'Inactive', 'Suspended', 'Closed') DEFAULT 'Active',
  `kyc_status` ENUM('Pending', 'Verified', 'Rejected') DEFAULT 'Pending',
  `kyc_verified_at` TIMESTAMP NULL,
  `aml_status` ENUM('Clear', 'Flagged', 'Review') DEFAULT 'Clear',
  `email_verified_at` TIMESTAMP NULL,
  `phone_verified_at` TIMESTAMP NULL,
  `two_factor_enabled` BOOLEAN DEFAULT FALSE,
  `two_factor_method` ENUM('SMS', 'Email', 'Authenticator') DEFAULT 'SMS',
  `two_factor_secret` VARCHAR(255),
  `last_login_at` TIMESTAMP NULL,
  `last_login_ip` VARCHAR(45),
  `last_login_device` VARCHAR(255),
  `failed_login_attempts` INT DEFAULT 0,
  `locked_until` TIMESTAMP NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  
  INDEX idx_email (`email`),
  INDEX idx_username (`username`),
  INDEX idx_status (`status`),
  INDEX idx_kyc_status (`kyc_status`),
  INDEX idx_created_at (`created_at`),
  FULLTEXT INDEX ft_name (`first_name`, `last_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- ACCOUNTS TABLE - User bank accounts
-- ============================================================================
CREATE TABLE IF NOT EXISTS `he_accounts` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `account_number` VARCHAR(20) NOT NULL UNIQUE,
  `account_type` ENUM('Checking', 'Savings', 'Money Market', 'Certificate of Deposit') DEFAULT 'Checking',
  `account_name` VARCHAR(100) NOT NULL,
  `currency` VARCHAR(3) DEFAULT 'USD',
  `balance` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
  `available_balance` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
  `pending_balance` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
  `interest_rate` DECIMAL(5, 4) DEFAULT 0.0000,
  `last_interest_posted_at` TIMESTAMP NULL,
  `overdraft_limit` DECIMAL(15, 2) DEFAULT 0.00,
  `overdraft_protection_enabled` BOOLEAN DEFAULT FALSE,
  `min_balance_required` DECIMAL(15, 2) DEFAULT 0.00,
  `monthly_fee` DECIMAL(10, 2) DEFAULT 0.00,
  `is_primary` BOOLEAN DEFAULT FALSE,
  `is_locked` BOOLEAN DEFAULT FALSE,
  `locked_reason` VARCHAR(255),
  `status` ENUM('Active', 'Frozen', 'Closed', 'Dormant') DEFAULT 'Active',
  `opened_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `closed_at` TIMESTAMP NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  FOREIGN KEY (`user_id`) REFERENCES `he_users`(`id`) ON DELETE CASCADE,
  INDEX idx_user_id (`user_id`),
  INDEX idx_account_number (`account_number`),
  INDEX idx_status (`status`),
  UNIQUE INDEX idx_user_primary (`user_id`, `is_primary`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- CARDS TABLE - Debit and credit cards
-- ============================================================================
CREATE TABLE IF NOT EXISTS `he_cards` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `account_id` BIGINT UNSIGNED NOT NULL,
  `card_number_hash` VARCHAR(255) NOT NULL UNIQUE,
  `card_token` VARCHAR(255) UNIQUE,
  `card_type` ENUM('Debit', 'Credit', 'Prepaid') DEFAULT 'Debit',
  `card_brand` ENUM('Visa', 'Mastercard', 'American Express', 'Discover') NOT NULL,
  `card_name` VARCHAR(100),
  `cardholder_name` VARCHAR(100) NOT NULL,
  `expiry_month` INT NOT NULL,
  `expiry_year` INT NOT NULL,
  `cvv_hash` VARCHAR(255) NOT NULL,
  `last_four` VARCHAR(4) NOT NULL,
  `card_status` ENUM('Active', 'Blocked', 'Expired', 'Inactive') DEFAULT 'Active',
  `is_primary` BOOLEAN DEFAULT FALSE,
  `daily_limit` DECIMAL(15, 2) DEFAULT 5000.00,
  `monthly_limit` DECIMAL(15, 2) DEFAULT 50000.00,
  `is_contactless_enabled` BOOLEAN DEFAULT TRUE,
  `is_international_enabled` BOOLEAN DEFAULT TRUE,
  `is_online_shopping_enabled` BOOLEAN DEFAULT TRUE,
  `is_atm_enabled` BOOLEAN DEFAULT TRUE,
  `physical_card_delivered` BOOLEAN DEFAULT FALSE,
  `delivered_at` TIMESTAMP NULL,
  `issued_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `activated_at` TIMESTAMP NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  FOREIGN KEY (`user_id`) REFERENCES `he_users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`account_id`) REFERENCES `he_accounts`(`id`) ON DELETE CASCADE,
  INDEX idx_user_id (`user_id`),
  INDEX idx_account_id (`account_id`),
  INDEX idx_card_status (`card_status`),
  INDEX idx_expiry (`expiry_year`, `expiry_month`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TRANSACTIONS TABLE - All account transactions
-- ============================================================================
CREATE TABLE IF NOT EXISTS `he_transactions` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `account_id` BIGINT UNSIGNED NOT NULL,
  `transaction_type` ENUM('Deposit', 'Withdrawal', 'Transfer', 'Payment', 'Fee', 'Interest', 'Dividend', 'Refund') NOT NULL,
  `transaction_method` ENUM('ATM', 'Online Transfer', 'Mobile App', 'Card', 'Check', 'ACH', 'Wire', 'Crypto') DEFAULT 'Online Transfer',
  `amount` DECIMAL(15, 2) NOT NULL,
  `currency` VARCHAR(3) DEFAULT 'USD',
  `description` VARCHAR(255),
  `reference_number` VARCHAR(50) UNIQUE,
  `status` ENUM('Pending', 'Processing', 'Completed', 'Failed', 'Reversed', 'On Hold') DEFAULT 'Pending',
  `merchant_name` VARCHAR(255),
  `merchant_category` VARCHAR(100),
  `counterparty_account_number` VARCHAR(20),
  `counterparty_bank` VARCHAR(255),
  `counterparty_name` VARCHAR(255),
  `notes` TEXT,
  `tags` VARCHAR(255),
  `is_recurring` BOOLEAN DEFAULT FALSE,
  `recurring_id` BIGINT UNSIGNED,
  `fee_applied` DECIMAL(10, 2) DEFAULT 0.00,
  `exchange_rate` DECIMAL(10, 6),
  `original_amount` DECIMAL(15, 2),
  `original_currency` VARCHAR(3),
  `balance_after` DECIMAL(15, 2),
  `card_id` BIGINT UNSIGNED,
  `recipient_id` BIGINT UNSIGNED,
  `initiated_by_user_id` BIGINT UNSIGNED,
  `approved_by_user_id` BIGINT UNSIGNED,
  `reversed_by_user_id` BIGINT UNSIGNED,
  `reversal_reason` VARCHAR(255),
  `initiated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `completed_at` TIMESTAMP NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  FOREIGN KEY (`account_id`) REFERENCES `he_accounts`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`card_id`) REFERENCES `he_cards`(`id`),
  FOREIGN KEY (`recipient_id`) REFERENCES `he_recipients`(`id`),
  FOREIGN KEY (`initiated_by_user_id`) REFERENCES `he_users`(`id`),
  INDEX idx_account_id (`account_id`),
  INDEX idx_status (`status`),
  INDEX idx_transaction_type (`transaction_type`),
  INDEX idx_created_at (`created_at`),
  INDEX idx_initiated_at (`initiated_at`),
  INDEX idx_reference_number (`reference_number`),
  INDEX idx_date_range (`created_at`, `status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- RECIPIENTS TABLE - Saved transfer recipients
-- ============================================================================
CREATE TABLE IF NOT EXISTS `he_recipients` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `recipient_name` VARCHAR(100) NOT NULL,
  `recipient_type` ENUM('Internal', 'External', 'Business') DEFAULT 'External',
  `account_number` VARCHAR(20) NOT NULL,
  `routing_number` VARCHAR(20),
  `account_holder_name` VARCHAR(100),
  `bank_name` VARCHAR(255),
  `swift_code` VARCHAR(20),
  `iban` VARCHAR(50),
  `country` VARCHAR(100) DEFAULT 'United States',
  `is_verified` BOOLEAN DEFAULT FALSE,
  `verified_at` TIMESTAMP NULL,
  `is_favorite` BOOLEAN DEFAULT FALSE,
  `nickname` VARCHAR(100),
  `transfer_limit` DECIMAL(15, 2),
  `daily_limit` DECIMAL(15, 2),
  `status` ENUM('Active', 'Inactive', 'Blocked') DEFAULT 'Active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  FOREIGN KEY (`user_id`) REFERENCES `he_users`(`id`) ON DELETE CASCADE,
  INDEX idx_user_id (`user_id`),
  INDEX idx_status (`status`),
  INDEX idx_is_favorite (`is_favorite`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- RECURRING TRANSFERS TABLE - Automated recurring payments
-- ============================================================================
CREATE TABLE IF NOT EXISTS `he_recurring_transfers` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `from_account_id` BIGINT UNSIGNED NOT NULL,
  `recipient_id` BIGINT UNSIGNED NOT NULL,
  `amount` DECIMAL(15, 2) NOT NULL,
  `frequency` ENUM('Daily', 'Weekly', 'Bi-Weekly', 'Monthly', 'Quarterly', 'Annually') NOT NULL,
  `start_date` DATE NOT NULL,
  `end_date` DATE,
  `next_execution_date` DATE NOT NULL,
  `last_execution_date` DATE,
  `description` VARCHAR(255),
  `status` ENUM('Active', 'Paused', 'Completed', 'Cancelled') DEFAULT 'Active',
  `execution_count` INT DEFAULT 0,
  `failed_execution_count` INT DEFAULT 0,
  `failed_reason` VARCHAR(255),
  `requires_approval` BOOLEAN DEFAULT FALSE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  FOREIGN KEY (`user_id`) REFERENCES `he_users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`from_account_id`) REFERENCES `he_accounts`(`id`),
  FOREIGN KEY (`recipient_id`) REFERENCES `he_recipients`(`id`),
  INDEX idx_user_id (`user_id`),
  INDEX idx_next_execution_date (`next_execution_date`),
  INDEX idx_status (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- LOANS TABLE - Loan products and accounts
-- ============================================================================
CREATE TABLE IF NOT EXISTS `he_loans` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `loan_type` ENUM('Personal', 'Mortgage', 'Auto', 'Student', 'Home Equity', 'Business') NOT NULL,
  `loan_number` VARCHAR(20) NOT NULL UNIQUE,
  `principal_amount` DECIMAL(15, 2) NOT NULL,
  `current_balance` DECIMAL(15, 2) NOT NULL,
  `interest_rate` DECIMAL(5, 4) NOT NULL,
  `annual_percentage_rate` DECIMAL(5, 4),
  `term_months` INT,
  `monthly_payment` DECIMAL(15, 2),
  `start_date` DATE NOT NULL,
  `maturity_date` DATE,
  `next_payment_date` DATE,
  `last_payment_date` DATE,
  `total_payments_made` INT DEFAULT 0,
  `remaining_payments` INT,
  `status` ENUM('Active', 'Paid Off', 'Delinquent', 'Defaulted', 'Cancelled') DEFAULT 'Active',
  `payment_method` ENUM('Auto-Pay', 'Manual') DEFAULT 'Manual',
  `auto_pay_account_id` BIGINT UNSIGNED,
  `late_fees_waived` DECIMAL(15, 2) DEFAULT 0.00,
  `deferment_available` BOOLEAN DEFAULT FALSE,
  `forbearance_available` BOOLEAN DEFAULT FALSE,
  `collateral_description` VARCHAR(255),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  FOREIGN KEY (`user_id`) REFERENCES `he_users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`auto_pay_account_id`) REFERENCES `he_accounts`(`id`),
  INDEX idx_user_id (`user_id`),
  INDEX idx_loan_number (`loan_number`),
  INDEX idx_status (`status`),
  INDEX idx_next_payment_date (`next_payment_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- BILLS TABLE - Bill payment management
-- ============================================================================
CREATE TABLE IF NOT EXISTS `he_bills` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `biller_name` VARCHAR(255) NOT NULL,
  `biller_category` VARCHAR(100),
  `account_number` VARCHAR(100),
  `amount` DECIMAL(15, 2),
  `due_date` DATE,
  `is_recurring` BOOLEAN DEFAULT FALSE,
  `frequency` ENUM('One-Time', 'Weekly', 'Bi-Weekly', 'Monthly', 'Quarterly', 'Annually') DEFAULT 'One-Time',
  `next_due_date` DATE,
  `last_paid_date` DATE,
  `status` ENUM('Pending', 'Scheduled', 'Paid', 'Overdue', 'Cancelled') DEFAULT 'Pending',
  `is_autopay_enabled` BOOLEAN DEFAULT FALSE,
  `payment_method` ENUM('Account Transfer', 'Card') DEFAULT 'Account Transfer',
  `payment_from_account_id` BIGINT UNSIGNED,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  FOREIGN KEY (`user_id`) REFERENCES `he_users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`payment_from_account_id`) REFERENCES `he_accounts`(`id`),
  INDEX idx_user_id (`user_id`),
  INDEX idx_status (`status`),
  INDEX idx_due_date (`due_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- SECURITY & AUDIT TABLES
-- ============================================================================

-- Login History
CREATE TABLE IF NOT EXISTS `he_login_history` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `ip_address` VARCHAR(45),
  `user_agent` TEXT,
  `device_name` VARCHAR(255),
  `browser` VARCHAR(100),
  `os` VARCHAR(100),
  `login_method` ENUM('Username/Password', '2FA SMS', '2FA Email', 'Biometric', 'SSO') DEFAULT 'Username/Password',
  `status` ENUM('Success', 'Failed', 'Blocked', '2FA Required') DEFAULT 'Success',
  `failure_reason` VARCHAR(255),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  
  FOREIGN KEY (`user_id`) REFERENCES `he_users`(`id`) ON DELETE CASCADE,
  INDEX idx_user_id (`user_id`),
  INDEX idx_created_at (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Account Activity Audit Log
CREATE TABLE IF NOT EXISTS `he_audit_log` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` BIGINT UNSIGNED,
  `action` VARCHAR(255) NOT NULL,
  `entity_type` VARCHAR(100),
  `entity_id` BIGINT UNSIGNED,
  `old_values` JSON,
  `new_values` JSON,
  `ip_address` VARCHAR(45),
  `user_agent` TEXT,
  `status` ENUM('Success', 'Failed') DEFAULT 'Success',
  `description` VARCHAR(255),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  
  FOREIGN KEY (`user_id`) REFERENCES `he_users`(`id`),
  INDEX idx_user_id (`user_id`),
  INDEX idx_entity (`entity_type`, `entity_id`),
  INDEX idx_action (`action`),
  INDEX idx_created_at (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Suspicious Activity / Fraud Detection
CREATE TABLE IF NOT EXISTS `he_fraud_alerts` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `alert_type` ENUM('High Value Transaction', 'Unusual Location', 'Unusual Time', 'Multiple Failed Attempts', 'Card Cloning', 'Identity Verification', 'Velocity Check') NOT NULL,
  `severity` ENUM('Low', 'Medium', 'High', 'Critical') DEFAULT 'Medium',
  `description` VARCHAR(255),
  `transaction_id` BIGINT UNSIGNED,
  `is_resolved` BOOLEAN DEFAULT FALSE,
  `action_taken` VARCHAR(255),
  `resolved_at` TIMESTAMP NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  
  FOREIGN KEY (`user_id`) REFERENCES `he_users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`transaction_id`) REFERENCES `he_transactions`(`id`),
  INDEX idx_user_id (`user_id`),
  INDEX idx_severity (`severity`),
  INDEX idx_is_resolved (`is_resolved`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- NOTIFICATIONS & PREFERENCES
-- ============================================================================

CREATE TABLE IF NOT EXISTS `he_notifications` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `notification_type` ENUM('Transaction', 'Security', 'Promotional', 'Alert', 'Reminder') DEFAULT 'Transaction',
  `title` VARCHAR(255) NOT NULL,
  `message` TEXT NOT NULL,
  `icon` VARCHAR(100),
  `channel` ENUM('In-App', 'Email', 'SMS', 'Push') DEFAULT 'In-App',
  `is_read` BOOLEAN DEFAULT FALSE,
  `read_at` TIMESTAMP NULL,
  `action_url` VARCHAR(255),
  `priority` ENUM('Low', 'Medium', 'High', 'Urgent') DEFAULT 'Medium',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `expires_at` TIMESTAMP NULL,
  
  FOREIGN KEY (`user_id`) REFERENCES `he_users`(`id`) ON DELETE CASCADE,
  INDEX idx_user_id (`user_id`),
  INDEX idx_is_read (`is_read`),
  INDEX idx_created_at (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- User Notification Preferences
CREATE TABLE IF NOT EXISTS `he_notification_preferences` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` BIGINT UNSIGNED NOT NULL UNIQUE,
  `transaction_alerts` BOOLEAN DEFAULT TRUE,
  `transaction_alerts_channel` SET('Email', 'SMS', 'Push', 'In-App') DEFAULT 'Email,In-App',
  `security_alerts` BOOLEAN DEFAULT TRUE,
  `security_alerts_channel` SET('Email', 'SMS', 'Push', 'In-App') DEFAULT 'Email,SMS,In-App',
  `promotional_emails` BOOLEAN DEFAULT TRUE,
  `newsletter` BOOLEAN DEFAULT FALSE,
  `high_transaction_threshold` DECIMAL(15, 2) DEFAULT 5000.00,
  `alert_unusual_activity` BOOLEAN DEFAULT TRUE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  FOREIGN KEY (`user_id`) REFERENCES `he_users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- SUPPORT & DOCUMENTS
-- ============================================================================

CREATE TABLE IF NOT EXISTS `he_support_tickets` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `ticket_number` VARCHAR(20) NOT NULL UNIQUE,
  `subject` VARCHAR(255) NOT NULL,
  `category` VARCHAR(100),
  `description` TEXT NOT NULL,
  `priority` ENUM('Low', 'Medium', 'High', 'Urgent') DEFAULT 'Medium',
  `status` ENUM('Open', 'In Progress', 'Waiting for User', 'Resolved', 'Closed') DEFAULT 'Open',
  `assigned_to_user_id` BIGINT UNSIGNED,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `resolved_at` TIMESTAMP NULL,
  
  FOREIGN KEY (`user_id`) REFERENCES `he_users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`assigned_to_user_id`) REFERENCES `he_users`(`id`),
  INDEX idx_user_id (`user_id`),
  INDEX idx_status (`status`),
  INDEX idx_ticket_number (`ticket_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- ADMIN & SETTINGS
-- ============================================================================

CREATE TABLE IF NOT EXISTS `he_admin_users` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` BIGINT UNSIGNED NOT NULL UNIQUE,
  `role` ENUM('Super Admin', 'Admin', 'Moderator', 'Analyst') DEFAULT 'Admin',
  `permissions` JSON,
  `is_active` BOOLEAN DEFAULT TRUE,
  `last_login_at` TIMESTAMP NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  FOREIGN KEY (`user_id`) REFERENCES `he_users`(`id`) ON DELETE CASCADE,
  INDEX idx_role (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `he_system_settings` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `setting_key` VARCHAR(255) NOT NULL UNIQUE,
  `setting_value` JSON,
  `description` VARCHAR(255),
  `updated_by_user_id` BIGINT UNSIGNED,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  FOREIGN KEY (`updated_by_user_id`) REFERENCES `he_users`(`id`),
  INDEX idx_setting_key (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- CREATE INDEXES FOR BETTER PERFORMANCE
-- ============================================================================

CREATE INDEX idx_accounts_user_primary ON `he_accounts`(`user_id`, `is_primary`);
CREATE INDEX idx_transactions_account_date ON `he_transactions`(`account_id`, `created_at` DESC);
CREATE INDEX idx_transactions_status_type ON `he_transactions`(`status`, `transaction_type`);
CREATE INDEX idx_cards_active ON `he_cards`(`user_id`, `card_status`) WHERE card_status = 'Active';

-- ============================================================================
-- VIEWS FOR COMMON QUERIES
-- ============================================================================

CREATE OR REPLACE VIEW `vw_user_dashboard` AS
SELECT 
  u.id,
  u.username,
  u.email,
  u.first_name,
  u.last_name,
  COUNT(DISTINCT a.id) as total_accounts,
  SUM(a.balance) as total_balance,
  COUNT(DISTINCT c.id) as total_cards,
  u.last_login_at,
  u.created_at
FROM `he_users` u
LEFT JOIN `he_accounts` a ON u.id = a.user_id AND a.status = 'Active'
LEFT JOIN `he_cards` c ON u.id = c.user_id AND c.card_status = 'Active'
GROUP BY u.id;

CREATE OR REPLACE VIEW `vw_account_summary` AS
SELECT 
  a.id,
  a.user_id,
  a.account_number,
  a.account_type,
  a.balance,
  a.available_balance,
  a.interest_rate,
  COUNT(DISTINCT t.id) as transaction_count,
  MAX(t.created_at) as last_transaction_date,
  a.status
FROM `he_accounts` a
LEFT JOIN `he_transactions` t ON a.id = t.account_id
GROUP BY a.id;
