<?php

/** @var \Core\Router $router */

use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\AdminController;

$router->get('/', [HomeController::class, 'index']);
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/logout', [AuthController::class, 'logout']);

// Admin Routes
$router->get('/admin/dashboard', [AdminController::class, 'dashboard']);

// Public/Guest Routes
$router->get('/register-hotel', [HomeController::class, 'registerHotelForm']);
$router->post('/register-hotel', [HomeController::class, 'registerHotelSubmit']);
