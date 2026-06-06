<?php

namespace App\Controllers;

use Core\Controller;
// use App\Models\Hotel;
// use App\Models\Booking;

class AdminController extends Controller {
    public function __construct() {
        // Protect this controller
        if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
            $this->redirect('/login');
        }
    }

    public function dashboard() {
        // We will fetch real stats here later
        $stats = [
            'total_hotels' => 5,
            'total_rooms' => 150,
            'occupied_rooms' => 80,
            'revenue' => 45000.00
        ];

        return $this->view('admin/dashboard', [
            'title' => 'Admin Dashboard - CHNMS',
            'stats' => $stats
        ]);
    }

    public function hotels() {
        $hotels = [
            ['id' => 1, 'name' => 'Grand Plaza Hotel', 'city' => 'Mumbai', 'email' => 'grand.plaza@chnms.com', 'status' => 'Pending', 'rooms' => 45],
            ['id' => 2, 'name' => 'Sea View Resort', 'city' => 'Goa', 'email' => 'seaview@chnms.com', 'status' => 'Active', 'rooms' => 120],
            ['id' => 3, 'name' => 'Mountain Retreat', 'city' => 'Shimla', 'email' => 'retreat@chnms.com', 'status' => 'Active', 'rooms' => 35],
        ];
        return $this->view('admin/hotels', ['title' => 'Hotels - CHNMS', 'hotels' => $hotels]);
    }

    public function bookings() {
        $bookings = [
            ['id' => 'BKG-001', 'guest' => 'Raj Sharma', 'hotel' => 'Sea View Resort', 'checkin' => '2026-06-10', 'checkout' => '2026-06-12', 'amount' => 15000, 'status' => 'Confirmed'],
            ['id' => 'BKG-002', 'guest' => 'Anita Desai', 'hotel' => 'Grand Plaza Hotel', 'checkin' => '2026-06-15', 'checkout' => '2026-06-16', 'amount' => 2500, 'status' => 'Pending'],
            ['id' => 'BKG-003', 'guest' => 'Vikram Singh', 'hotel' => 'Mountain Retreat', 'checkin' => '2026-06-05', 'checkout' => '2026-06-08', 'amount' => 12000, 'status' => 'Completed'],
        ];
        return $this->view('admin/bookings', ['title' => 'Bookings - CHNMS', 'bookings' => $bookings]);
    }

    public function finance() {
        $transactions = [
            ['id' => 'TXN-9982', 'hotel' => 'Sea View Resort', 'type' => 'Platform Fee', 'amount' => 1500, 'date' => '2026-06-05', 'status' => 'Paid'],
            ['id' => 'TXN-9983', 'hotel' => 'Mountain Retreat', 'type' => 'Platform Fee', 'amount' => 1200, 'date' => '2026-06-06', 'status' => 'Pending'],
        ];
        return $this->view('admin/finance', ['title' => 'Finance - CHNMS', 'transactions' => $transactions]);
    }

    public function search() {
        return $this->view('admin/search', ['title' => 'Room Search - CHNMS']);
    }
}
