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
        return $this->view('admin/placeholder', ['title' => 'Hotels - CHNMS', 'module' => 'Hotels Management']);
    }

    public function bookings() {
        return $this->view('admin/placeholder', ['title' => 'Bookings - CHNMS', 'module' => 'All Bookings']);
    }

    public function finance() {
        return $this->view('admin/placeholder', ['title' => 'Finance - CHNMS', 'module' => 'Finance & Revenue']);
    }
}
