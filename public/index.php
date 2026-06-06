<?php

session_start();

// Simple Autoloader
spl_autoload_register(function ($class) {
    // Map namespaces to directories
    $prefixMap = [
        'App\\' => __DIR__ . '/../src/',
        'Core\\' => __DIR__ . '/../core/',
    ];

    foreach ($prefixMap as $prefix => $baseDir) {
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            continue;
        }

        $relativeClass = substr($class, $len);
        $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

        if (file_exists($file)) {
            require $file;
            return;
        }
    }
});

use Core\Router;

$router = new Router();

// Load routes
require __DIR__ . '/../src/routes.php';

// Dispatch
$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Handle URL rewriting fallback if needed, but assuming a simple setup:
// If running via PHP dev server: php -S localhost:8000 -t public
$router->dispatch($method, $uri);
