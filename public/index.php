<?php

session_start();

// ---- GLOBAL ERROR HANDLER (shows friendly message instead of blank 500) ----
error_reporting(E_ALL);
ini_set('display_errors', '0');   // Never show errors to browser in production
ini_set('log_errors', '1');       // Always log them

set_exception_handler(function($e) {
    http_response_code(500);
    error_log('[CHNMS FATAL] ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
    echo '<!DOCTYPE html><html><head><title>Error - CHNMS</title>
    <style>body{font-family:sans-serif;display:flex;align-items:center;justify-content:center;height:100vh;margin:0;background:#f8fafc;}
    .box{background:white;border-radius:16px;padding:40px;max-width:520px;text-align:center;box-shadow:0 4px 32px rgba(0,0,0,.08);}
    h2{color:#ef4444;margin-bottom:12px;font-size:22px;}p{color:#64748b;font-size:14px;line-height:1.6;}
    a{display:inline-block;margin-top:20px;padding:10px 24px;background:#4338ca;color:white;border-radius:8px;text-decoration:none;font-weight:600;}</style>
    </head><body><div class="box">
    <div style="font-size:48px;margin-bottom:16px;">⚠️</div>
    <h2>Something went wrong</h2>
    <p>The server encountered an error. Our team has been notified. Please try again in a moment.</p>
    <p style="font-size:12px;color:#94a3b8;margin-top:12px;">Error: ' . htmlspecialchars($e->getMessage()) . '</p>
    <a href="/">Go Home</a></div></body></html>';
    exit();
});

set_error_handler(function($errno, $errstr, $errfile, $errline) {
    if ($errno === E_ERROR || $errno === E_PARSE || $errno === E_CORE_ERROR) {
        throw new \ErrorException($errstr, $errno, $errno, $errfile, $errline);
    }
    error_log("[CHNMS WARNING] {$errstr} in {$errfile}:{$errline}");
    return true;
});

// ---- BASE URL DETECTION ----
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
$host     = $_SERVER['HTTP_HOST'];
$scriptDir = dirname($_SERVER['SCRIPT_NAME']);
$baseDir   = str_replace('\\', '/', $scriptDir);
if (preg_match('/(.*)\\/public$/', $baseDir, $matches)) {
    $baseDir = $matches[1];
} elseif ($baseDir === '/public') {
    $baseDir = '';
}
if ($baseDir === '/') {
    $baseDir = '';
}
define('BASE_URL',  $protocol . '://' . $host . $baseDir);
define('BASE_PATH', $baseDir);

// Razorpay keys (set your real keys here)
define('RAZORPAY_KEY_ID',     'rzp_test_XXXXXXXXXXXX');
define('RAZORPAY_KEY_SECRET', 'XXXXXXXXXXXXXXXXXXXX');

// ---- PSR-4 AUTOLOADER ----
spl_autoload_register(function ($class) {
    $prefixMap = [
        'App\\'  => __DIR__ . '/../src/',
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
$uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Strip BASE_PATH prefix so router matches '/' or '/login'
if (BASE_PATH !== '' && strpos($uri, BASE_PATH) === 0) {
    $uri = substr($uri, strlen(BASE_PATH));
}
if (empty($uri)) {
    $uri = '/';
}

$router->dispatch($method, $uri);
