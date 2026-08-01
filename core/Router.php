<?php
namespace App\Core;

class Router
{
    protected $routes = [];

    public function __construct()
    {
        // Basic routing map: path => controller@method
        $this->routes = [
            '/' => 'Controllers\\DashboardController@index',
            '/login' => 'Controllers\\AuthController@loginForm',
            '/login.post' => 'Controllers\\AuthController@login',
            '/logout' => 'Controllers\\AuthController@logout',
        ];
    }

    protected function currentPath(): string
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        return rtrim($uri, '/') === '' ? '/' : rtrim($uri, '/');
    }

    public function dispatch()
    {
        $path = $this->currentPath();
        $method = $_SERVER['REQUEST_METHOD'];
        $key = $path . ($method === 'POST' ? '.post' : '');

        $target = $this->routes[$key] ?? $this->routes[$path] ?? null;
        if (!$target) {
            http_response_code(404);
            echo "404 Not Found";
            exit;
        }

        [$class, $action] = explode('@', $target);
        $fqcn = "App\\$class";
        if (!class_exists($fqcn)) {
            http_response_code(500);
            echo "Controller $fqcn not found";
            exit;
        }

        $controller = new $fqcn();
        if (!method_exists($controller, $action)) {
            http_response_code(500);
            echo "Action $action not found in $fqcn";
            exit;
        }

        // call action
        call_user_func([$controller, $action]);
    }
}
