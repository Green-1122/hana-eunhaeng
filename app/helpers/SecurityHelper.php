<?php
/**
 * Security Helper - CSRF, encryption, hashing
 */

namespace App\Helpers;

class SecurityHelper
{
    /**
     * Generate CSRF token
     */
    public static function generateCsrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Verify CSRF token
     */
    public static function verifyCsrfToken(string $token): bool
    {
        return hash_equals($_SESSION['csrf_token'] ?? '', $token);
    }

    /**
     * Hash password
     */
    public static function hashPassword(string $password): string
    {
        $rounds = (int) ($_ENV['BCRYPT_ROUNDS'] ?? 12);
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => $rounds]);
    }

    /**
     * Verify password
     */
    public static function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    /**
     * Sanitize input
     */
    public static function sanitize(string $input): string
    {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Validate email
     */
    public static function validateEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Generate random token
     */
    public static function generateToken(int $length = 32): string
    {
        return bin2hex(random_bytes($length / 2));
    }

    /**
     * Encrypt string
     */
    public static function encrypt(string $data, string $key): string
    {
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $encrypted = openssl_encrypt($data, 'aes-256-cbc', $key, 0, $iv);
        return base64_encode($iv . $encrypted);
    }

    /**
     * Decrypt string
     */
    public static function decrypt(string $encrypted, string $key): string|false
    {
        $data = base64_decode($encrypted);
        $iv = substr($data, 0, openssl_cipher_iv_length('aes-256-cbc'));
        $encrypted = substr($data, openssl_cipher_iv_length('aes-256-cbc'));
        return openssl_decrypt($encrypted, 'aes-256-cbc', $key, 0, $iv);
    }
}
