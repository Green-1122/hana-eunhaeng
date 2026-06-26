<?php
/**
 * Router - Handles URL routing
 */

namespace App\Core;

class Router
{
    private static array $routes = [];
    private static string $currentRoute = '';
    private static array $params = [];

    /**
     * Register GET route
     */
    public static function get(string $path, string $action): void
    {
        self::$routes['GET'][$path] = $action;
    }

    /**
     * Register POST route
     */
    public static function post(string $path, string $action): void
    {
        self::$routes['POST'][$path] = $action;
    }

    /**
     * Register route for any method
     */
    public static function any(string $path, string $action): void
    {
        self::$routes['ANY'][$path] = $action;
    }

    /**
     * Match and dispatch route
     */
    public static function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $path = str_replace('/public', '', $path) ?: '/';

        $route = self::matchRoute($method, $path);

        if ($route) {
            self::executeRoute($route);
        } else {
            http_response_code(404);
            echo "Route not found: $path";
        }
    }

    /**
     * Match route
     */
    private static function matchRoute(string $method, string $path): ?string
    {
        // Try exact match first
        if (isset(self::$routes[$method][$path])) {
            return self::$routes[$method][$path];
        }

        // Try ANY routes
        if (isset(self::$routes['ANY'][$path])) {
            return self::$routes['ANY'][$path];
        }

        // Try pattern matching
        foreach (self::$routes[$method] ?? [] as $pattern => $action) {
            if (self::matchPattern($pattern, $path)) {
                return $action;
            }
        }

        return null;
    }

    /**
     * Match route pattern
     */
    private static function matchPattern(string $pattern, string $path): bool
    {
        $pattern = preg_replace('/\{[^}]+\}/', '([^/]+)', $pattern);
        $pattern = str_replace('/', '\/', $pattern);
        
        return preg_match('/^' . $pattern . '$/', $path, self::$params);
    }

    /**
     * Execute route action
     */
    private static function executeRoute(string $action): void
    {
        [$controller, $method] = explode('@', $action);
        $controllerClass = "App\\Controllers\\$controller";

        if (!class_exists($controllerClass)) {
            throw new \Exception("Controller $controller not found");
        }

        $instance = new $controllerClass();
        $instance->$method();
    }

    /**
     * Get route parameter
     */
    public static function getParam(int $index = 0): ?string
    {
        return self::$params[$index + 1] ?? null;
    }
}