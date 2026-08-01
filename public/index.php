<?php
// public/index.php — Front controller
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use App\Core\Router;

$root = dirname(__DIR__);
if (file_exists($root.'/.env')) {
    $dotenv = Dotenv::createImmutable($root);
    $dotenv->safeLoad();
}

// Start session securely
session_start([
    'cookie_httponly' => true,
    'cookie_samesite' => 'Lax',
]);

$router = new Router();
$router->dispatch();
