<?php
/**
 * Authentication Middleware
 */

namespace App\Middleware;

class AuthMiddleware
{
    /**
     * Check if user is authenticated
     */
    public static function isAuthenticated(): bool
    {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }

    /**
     * Get current user ID
     */
    public static function getUserId(): ?int
    {
        return $_SESSION['user_id'] ?? null;
    }

    /**
     * Get current user data
     */
    public static function getUser(): array|null
    {
        return $_SESSION['user'] ?? null;
    }

    /**
     * Require authentication
     */
    public static function requireAuth(): void
    {
        if (!self::isAuthenticated()) {
            http_response_code(401);
            header('Location: /auth/login');
            exit;
        }
    }

    /**
     * Require guest (not authenticated)
     */
    public static function requireGuest(): void
    {
        if (self::isAuthenticated()) {
            header('Location: /dashboard');
            exit;
        }
    }

    /**
     * Set user session
     */
    public static function setUser(array $userData): void
    {
        $_SESSION['user_id'] = $userData['id'];
        $_SESSION['user'] = $userData;
        $_SESSION['last_activity'] = time();
    }

    /**
     * Clear user session
     */
    public static function logout(): void
    {
        unset($_SESSION['user_id']);
        unset($_SESSION['user']);
        session_destroy();
    }

    /**
     * Check session timeout
     */
    public static function checkSessionTimeout(int $timeout = 1800): bool
    {
        $sessionTimeout = $_ENV['SESSION_LIFETIME'] ? ($_ENV['SESSION_LIFETIME'] * 60) : 1440 * 60;
        
        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $sessionTimeout)) {
            self::logout();
            return false;
        }

        $_SESSION['last_activity'] = time();
        return true;
    }
}
