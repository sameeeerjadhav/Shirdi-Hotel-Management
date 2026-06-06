<?php

session_start();

// Calculate Base URL dynamically to support subdirectories (like public_html/hotel/)
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
// Get the directory of the current executing script (index.php) and remove '/public' if it's there
$scriptDir = dirname($_SERVER['SCRIPT_NAME']);
$baseDir = str_replace('\\', '/', $scriptDir);
// If the rewrite rule is active, SCRIPT_NAME might be /hotel/public/index.php
if (preg_match('/(.*)\/public$/', $baseDir, $matches)) {
    $baseDir = $matches[1];
} elseif ($baseDir === '/public') {
    $baseDir = '';
}
if ($baseDir === '/') {
    $baseDir = '';
}
define('BASE_URL', $protocol . "://" . $host . $baseDir);
define('BASE_PATH', $baseDir);

// Simple Autoloader
spl_autoload_register(function ($class) {
    // Map namespaces to directories
    $prefixMap = [
        'App\\' => __DIR__ . '/../src/',
        'Core\\' => __DIR__ . '/../core/',
    ];

    foreach ($prefixMap as $prefix => $baseDirNs) {
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            continue;
        }

        $relativeClass = substr($class, $len);
        $file = $baseDirNs . str_replace('\\', '/', $relativeClass) . '.php';

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
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Strip the BASE_PATH from the URI so the router can match exactly '/' or '/login'
if (BASE_PATH !== '' && strpos($uri, BASE_PATH) === 0) {
    $uri = substr($uri, strlen(BASE_PATH));
}
if (empty($uri)) {
    $uri = '/';
}

$router->dispatch($method, $uri);
