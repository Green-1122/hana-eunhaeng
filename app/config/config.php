<?php
/**
 * Application Configuration
 */

// Define constants
define('APP_PATH', dirname(dirname(__DIR__)) . '/app');
define('PUBLIC_PATH', dirname(dirname(__DIR__)) . '/public');
define('ROOT_PATH', dirname(dirname(__DIR__)));

// Load environment variables
if (file_exists(ROOT_PATH . '/.env')) {
    $lines = file(ROOT_PATH . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            [$key, $value] = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($value);
        }
    }
}

// Session configuration
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_secure', $_ENV['SESSION_SECURE'] ?? '1');
ini_set('session.cookie_samesite', $_ENV['SESSION_SAME_SITE'] ?? 'Lax');
ini_set('session.gc_maxlifetime', ($_ENV['SESSION_LIFETIME'] ?? 1440) * 60);

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Error handling
error_reporting(E_ALL);
ini_set('display_errors', $_ENV['APP_DEBUG'] === 'true' ? '1' : '0');
ini_set('log_errors', '1');
ini_set('error_log', ($_ENV['LOG_PATH'] ?? ROOT_PATH . '/storage/logs/') . 'error.log');

// Timezone
date_default_timezone_set('UTC');

// Security headers
if (!headers_sent()) {
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: DENY');
    header('X-XSS-Protection: 1; mode=block');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Permissions-Policy: geolocation=(), microphone=(), camera=()');
}