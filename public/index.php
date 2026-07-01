<?php
/**
 * Main Entry Point - index.php
 */

require_once __DIR__ . '/../app/config/config.php';

// Autoload classes
spl_autoload_register(function ($class) {
    $file = str_replace('\\', '/', $class);
    $path = __DIR__ . '/../' . $file . '.php';
    if (file_exists($path)) {
        require_once $path;
    }
});

// Initialize database
\App\Core\Database::init(
    $_ENV['DB_HOST'] ?? 'localhost',
    $_ENV['DB_NAME'] ?? 'hana_eunhaeng',
    $_ENV['DB_USER'] ?? 'root',
    $_ENV['DB_PASS'] ?? ''
);

// Parse URL
$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$request_uri = str_replace('/index.php', '', $request_uri);
$request_uri = trim($request_uri, '/');

// Route handling
$parts = array_filter(explode('/', $request_uri));
$action = array_shift($parts) ?? 'home';
$method = array_shift($parts) ?? 'index';
$params = $parts;

// Route mapping
$routes = [
    'home' => ['controller' => 'HomeController', 'method' => 'index'],
    'dashboard' => ['controller' => 'DashboardController', 'method' => 'index'],
    'auth' => ['controller' => 'AuthController'],
    'accounts' => ['controller' => 'AccountController'],
    'transactions' => ['controller' => 'TransactionController'],
    'cards' => ['controller' => 'CardController'],
];

// Get controller
if (isset($routes[$action])) {
    $controller_name = 'App\\Controllers\\' . $routes[$action]['controller'];
    $default_method = $routes[$action]['method'] ?? $method;
} else {
    $controller_name = 'App\\Controllers\\' . ucfirst($action) . 'Controller';
    $default_method = $method;
}

// Check for POST method override
$method_name = $_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['_method']) 
    ? $_POST['_method'] . ucfirst($method) 
    : $method;

$method_name = str_replace('-', '', ucwords($method_name, '-'));
if (empty($method_name)) {
    $method_name = $default_method;
}

// Instantiate and call controller
try {
    if (class_exists($controller_name)) {
        $controller = new $controller_name();
        if (method_exists($controller, $method_name)) {
            call_user_func_array([$controller, $method_name], $params);
        } else {
            // Try alternate method names
            $alt_method = lcfirst($method_name);
            if (method_exists($controller, $alt_method)) {
                call_user_func_array([$controller, $alt_method], $params);
            } else {
                http_response_code(404);
                echo '<h1>404 - Method Not Found</h1>';
            }
        }
    } else {
        http_response_code(404);
        echo '<h1>404 - Controller Not Found</h1>';
    }
} catch (\Exception $e) {
    http_response_code(500);
    if ($_ENV['APP_DEBUG'] === 'true') {
        echo '<h1>500 - Server Error</h1>';
        echo '<pre>' . $e->getMessage() . '\n' . $e->getTraceAsString() . '</pre>';
    } else {
        echo '<h1>500 - Server Error</h1>';
    }
}
