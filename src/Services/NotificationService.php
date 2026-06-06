<?php

namespace App\Services;

use App\Models\Notification;

class NotificationService {

    private Notification $model;

    public function __construct() {
        $this->model = new Notification();
    }

    // Booking created — notify hotel admin
    public function bookingCreated($booking, $hotelAdminUserId) {
        $this->model->send(
            $hotelAdminUserId,
            'booking_created',
            'New Booking Received',
            "Booking #{$booking['booking_ref']} received from {$booking['guest_name']} for room {$booking['room_number']}.",
            '/hotel/bookings'
        );
    }

    // Payment received
    public function paymentReceived($booking, $amount, $adminUserId) {
        $this->model->send(
            $adminUserId,
            'payment_received',
            'Payment Received',
            "₹" . number_format($amount) . " received for booking #{$booking['booking_ref']}.",
            '/admin/finance'
        );
    }

    // Hotel approval
    public function hotelApproved($hotel, $hotelAdminUserId) {
        $this->model->send(
            $hotelAdminUserId,
            'hotel_approved',
            'Hotel Approved! 🎉',
            "Congratulations! {$hotel['name']} has been approved and is now live on CHNMS.",
            '/hotel/dashboard'
        );
    }

    // Transfer request
    public function transferRequested($transfer, $adminUserId) {
        $this->model->send(
            $adminUserId,
            'transfer_request',
            'Transfer Request Pending',
            "A booking transfer request is waiting for your approval.",
            '/admin/transfers'
        );
    }

    // Check-in
    public function guestCheckedIn($booking, $hotelAdminUserId) {
        $this->model->send(
            $hotelAdminUserId,
            'checkin',
            'Guest Checked In',
            "{$booking['guest_name']} has checked in to room {$booking['room_number']}.",
            '/hotel/bookings'
        );
    }

    // Check-out
    public function guestCheckedOut($booking, $hotelAdminUserId) {
        $this->model->send(
            $hotelAdminUserId,
            'checkout',
            'Guest Checked Out',
            "{$booking['guest_name']} has checked out. Final amount: ₹" . number_format($booking['total_amount']),
            '/hotel/bookings'
        );
    }
}
