<?php

namespace App\Controllers;

use Core\Controller;

class HotelController extends Controller {
    public function __construct() {
        // Protect this controller
        if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 2) {
            $this->redirect('/login');
        }
    }

    public function dashboard() {
        // Mock data for hotel admin dashboard
        $stats = [
            'total_rooms' => 45,
            'occupied_rooms' => 12,
            'available_rooms' => 33,
            'today_checkins' => 5,
            'today_checkouts' => 2,
            'revenue' => 12500.00
        ];

        return $this->view('hotel/dashboard', [
            'title' => 'Hotel Dashboard - CHNMS',
            'stats' => $stats
        ]);
    }

    public function rooms() {
        // Mock data for rooms
        $rooms = [
            ['id' => 1, 'number' => '101', 'type' => 'Deluxe', 'price' => 2500, 'status' => 'available'],
            ['id' => 2, 'number' => '102', 'type' => 'Deluxe', 'price' => 2500, 'status' => 'occupied'],
            ['id' => 3, 'number' => '201', 'type' => 'Suite', 'price' => 5000, 'status' => 'cleaning'],
            ['id' => 4, 'number' => '202', 'type' => 'Suite', 'price' => 5000, 'status' => 'available']
        ];

        return $this->view('hotel/rooms', [
            'title' => 'Room Management - CHNMS',
            'rooms' => $rooms
        ]);
    }

    public function addRoom() {
        // Here we would validate and insert into the `rooms` table
        $_SESSION['success'] = "Room added successfully.";
        $this->redirect('/hotel/rooms');
    }

    public function bookings() {
        $bookings = [
            ['id' => 'BKG-001', 'guest' => 'Raj Sharma', 'room' => '101', 'checkin' => '2026-06-10', 'checkout' => '2026-06-12', 'amount' => 5000, 'status' => 'Confirmed'],
            ['id' => 'BKG-004', 'guest' => 'Sunil Shetty', 'room' => '201', 'checkin' => '2026-06-15', 'checkout' => '2026-06-16', 'amount' => 5000, 'status' => 'Pending'],
        ];
        return $this->view('hotel/bookings', ['title' => 'Bookings - CHNMS', 'bookings' => $bookings]);
    }
}
