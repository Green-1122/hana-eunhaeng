<?php
/**
 * Application Configuration
 */

// Load environment variables
if (file_exists(__DIR__ . '/.env')) {
    $env_file = file_get_contents(__DIR__ . '/.env');
    $lines = explode("\n", $env_file);
    
    foreach ($lines as $line) {
        if (empty($line) || strpos(trim($line), '#') === 0) continue;
        
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($value);
        }
    }
}

// Set default values
$_ENV['APP_NAME'] = $_ENV['APP_NAME'] ?? 'Hana-Eunhaeng';
$_ENV['APP_ENV'] = $_ENV['APP_ENV'] ?? 'development';
$_ENV['APP_DEBUG'] = $_ENV['APP_DEBUG'] ?? 'true';
$_ENV['APP_URL'] = $_ENV['APP_URL'] ?? 'http://localhost:8000';

// Database defaults
$_ENV['DB_HOST'] = $_ENV['DB_HOST'] ?? 'localhost';
$_ENV['DB_PORT'] = $_ENV['DB_PORT'] ?? 3306;
$_ENV['DB_NAME'] = $_ENV['DB_NAME'] ?? 'hana_eunhaeng';
$_ENV['DB_USER'] = $_ENV['DB_USER'] ?? 'root';
$_ENV['DB_PASS'] = $_ENV['DB_PASS'] ?? '';

// Session configuration
$_ENV['SESSION_SECURE'] = $_ENV['SESSION_SECURE'] ?? 'false';
$_ENV['SESSION_SAME_SITE'] = $_ENV['SESSION_SAME_SITE'] ?? 'Lax';
$_ENV['SESSION_LIFETIME'] = $_ENV['SESSION_LIFETIME'] ?? 1440;
$_ENV['BCRYPT_ROUNDS'] = $_ENV['BCRYPT_ROUNDS'] ?? 12;

// Start session
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_secure', $_ENV['SESSION_SECURE'] === 'true' ? 1 : 0);
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_samesite', $_ENV['SESSION_SAME_SITE']);
    session_set_cookie_params([
        'lifetime' => $_ENV['SESSION_LIFETIME'] * 60,
        'secure' => $_ENV['SESSION_SECURE'] === 'true',
        'httponly' => true,
        'samesite' => $_ENV['SESSION_SAME_SITE']
    ]);
    session_start();
}

// Error handling
if ($_ENV['APP_DEBUG'] === 'true') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(E_ALL);
}

// Set timezone
date_default_timezone_set('UTC');

// Security headers
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Content-Security-Policy: default-src \'self\'; script-src \'self\' \'unsafe-inline\' cdnjs.cloudflare.com; style-src \'self\' \'unsafe-inline\' cdnjs.cloudflare.com; img-src \'self\' data: https:; font-src \'self\' cdnjs.cloudflare.com');

// Charset
header('Content-Type: text/html; charset=utf-8');
