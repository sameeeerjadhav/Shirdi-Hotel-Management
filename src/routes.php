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
$router->get('/admin/hotels', [AdminController::class, 'hotels']);
$router->get('/admin/bookings', [AdminController::class, 'bookings']);
$router->get('/admin/finance', [AdminController::class, 'finance']);
$router->get('/admin/search', [AdminController::class, 'search']);

// Hotel Admin Routes
use App\Controllers\HotelController;
$router->get('/hotel/dashboard', [HotelController::class, 'dashboard']);
$router->get('/hotel/rooms', [HotelController::class, 'rooms']);
$router->post('/hotel/rooms/add', [HotelController::class, 'addRoom']);
$router->get('/hotel/bookings', [HotelController::class, 'bookings']);

// Public/Guest Routes
use App\Controllers\BookingController;

$router->get('/register-hotel', [HomeController::class, 'registerHotelForm']);
$router->post('/register-hotel', [HomeController::class, 'registerHotelSubmit']);

$router->get('/search', [BookingController::class, 'search']);
$router->get('/checkout/{hotel_id}', [BookingController::class, 'checkoutForm']);
$router->post('/checkout/{hotel_id}', [BookingController::class, 'processCheckout']);
