<?php

/** @var \Core\Router $router */

use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\AdminController;
use App\Controllers\HotelController;
use App\Controllers\BookingController;
use App\Controllers\NotificationController;

// =============================================
// PUBLIC ROUTES
// =============================================
$router->get('/',               [HomeController::class, 'index']);
$router->get('/login',          [AuthController::class, 'showLogin']);
$router->post('/login',         [AuthController::class, 'login']);
$router->get('/logout',         [AuthController::class, 'logout']);
$router->get('/register-hotel', [HomeController::class, 'registerHotelForm']);
$router->post('/register-hotel',[HomeController::class, 'registerHotelSubmit']);

// Guest search & booking
$router->get('/search',                        [BookingController::class, 'search']);
$router->get('/checkout',                      [BookingController::class, 'checkoutForm']);
$router->post('/booking/create-order',         [BookingController::class, 'createOrder']);
$router->post('/booking/verify-payment',       [BookingController::class, 'verifyPayment']);
$router->get('/booking/confirmation/{ref}',    [BookingController::class, 'confirmation']);
$router->get('/booking/transfer-suggestions',  [BookingController::class, 'transferSuggestions']);

// =============================================
// ADMIN ROUTES
// =============================================

// Dashboard
$router->get('/admin/dashboard', [AdminController::class, 'dashboard']);

// Hotels
$router->get('/admin/hotels',           [AdminController::class, 'hotels']);
$router->get('/admin/hotels/add',       [AdminController::class, 'addHotelForm']);
$router->post('/admin/hotels/add',      [AdminController::class, 'storeHotel']);
$router->get('/admin/hotels/{id}',      [AdminController::class, 'hotelDetail']);
$router->get('/admin/hotels/{id}/edit', [AdminController::class, 'editHotelForm']);
$router->post('/admin/hotels/{id}/edit',[AdminController::class, 'updateHotel']);
$router->post('/admin/hotels/{id}/approve', [AdminController::class, 'approveHotel']);
$router->post('/admin/hotels/{id}/reject',  [AdminController::class, 'rejectHotel']);
$router->post('/admin/hotels/{id}/suspend', [AdminController::class, 'suspendHotel']);
$router->post('/admin/hotels/{id}/delete',  [AdminController::class, 'deleteHotel']);

// Bookings
$router->get('/admin/bookings',              [AdminController::class, 'bookings']);
$router->get('/admin/bookings/{id}',         [AdminController::class, 'bookingDetail']);
$router->post('/admin/bookings/{id}/cancel', [AdminController::class, 'cancelBooking']);

// Finance
$router->get('/admin/finance', [AdminController::class, 'finance']);

// Room Monitor
$router->get('/admin/room-monitor', [AdminController::class, 'roomMonitor']);

// Transfers
$router->get('/admin/transfers',                  [AdminController::class, 'transfers']);
$router->post('/admin/transfers/{id}/approve',    [AdminController::class, 'approveTransfer']);
$router->post('/admin/transfers/{id}/reject',     [AdminController::class, 'rejectTransfer']);

// Search
$router->get('/admin/search', [AdminController::class, 'search']);

// =============================================
// HOTEL PARTNER ROUTES
// =============================================

// Dashboard
$router->get('/hotel/dashboard', [HotelController::class, 'dashboard']);

// Rooms
$router->get('/hotel/rooms',                      [HotelController::class, 'rooms']);
$router->post('/hotel/rooms/add',                 [HotelController::class, 'addRoom']);
$router->get('/hotel/rooms/{id}',                 [HotelController::class, 'editRoom']);
$router->post('/hotel/rooms/{id}/update',         [HotelController::class, 'updateRoom']);
$router->post('/hotel/rooms/{id}/delete',         [HotelController::class, 'deleteRoom']);
$router->post('/hotel/rooms/{id}/status',         [HotelController::class, 'updateRoomStatus']);
$router->post('/hotel/room-types/add',            [HotelController::class, 'addRoomType']);

// Bookings
$router->get('/hotel/bookings',               [HotelController::class, 'bookings']);
$router->post('/hotel/bookings/{id}/checkin', [HotelController::class, 'checkIn']);
$router->post('/hotel/bookings/{id}/checkout',[HotelController::class, 'checkOut']);

// =============================================
// NOTIFICATIONS API
// =============================================
$router->get('/api/notifications',          [NotificationController::class, 'index']);
$router->post('/api/notifications/{id}/read',[NotificationController::class, 'markRead']);
$router->post('/api/notifications/read-all', [NotificationController::class, 'markAllRead']);
