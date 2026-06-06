<?php

namespace App\Controllers;

use Core\Controller;
use App\Services\TransferEngine;

class BookingController extends Controller {

    public function search() {
        $city = $_GET['city'] ?? '';
        $checkin = $_GET['checkin'] ?? '';
        $checkout = $_GET['checkout'] ?? '';

        // Mock data for search results
        $hotels = [];
        if (!empty($city)) {
            $hotels = [
                ['id' => 1, 'name' => 'Grand Plaza Hotel', 'city' => 'Mumbai', 'price' => 3500, 'rating' => 4.5, 'available_rooms' => 5],
                ['id' => 2, 'name' => 'Sea View Resort', 'city' => 'Mumbai', 'price' => 4200, 'rating' => 4.8, 'available_rooms' => 0] // Full
            ];
        }

        return $this->view('guest/search', [
            'title' => 'Search Hotels - CHNMS',
            'hotels' => $hotels,
            'city' => $city,
            'checkin' => $checkin,
            'checkout' => $checkout
        ]);
    }

    public function checkoutForm($hotel_id) {
        // Mock hotel details
        $hotel = ['id' => $hotel_id, 'name' => 'Grand Plaza Hotel', 'price' => 3500];

        // Check if hotel is full
        if ($hotel_id == 2) {
            // Initiate Smart Transfer Engine
            $engine = new TransferEngine();
            $alternative = $engine->findAlternative('Mumbai', 4200);

            if ($alternative) {
                $_SESSION['transfer_alert'] = "The hotel you selected is fully booked. We have found a great alternative for you!";
                $this->redirect('/checkout/' . $alternative['id']);
            } else {
                $_SESSION['error'] = "The hotel is fully booked and no alternatives are available right now.";
                $this->redirect('/search');
            }
        }

        return $this->view('guest/checkout', [
            'title' => 'Checkout - CHNMS',
            'hotel' => $hotel
        ]);
    }

    public function processCheckout($hotel_id) {
        // Here we would handle Razorpay payment initialization and insert into `bookings` table.
        // For now, mock success.
        
        $_SESSION['success'] = "Booking confirmed successfully!";
        $this->redirect('/');
    }
}
