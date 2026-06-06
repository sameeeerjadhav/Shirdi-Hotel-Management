<?php

namespace App\Models;

use Core\Model;

class Payment extends Model {
    protected $table = 'payments';

    public function byBooking($bookingId) {
        return $this->query(
            "SELECT * FROM payments WHERE booking_id = ? ORDER BY created_at DESC",
            [$bookingId]
        );
    }

    public function markPaid($id, $razorpayPaymentId = null) {
        return $this->update($id, [
            'status'              => 'captured',
            'razorpay_payment_id' => $razorpayPaymentId,
            'paid_at'             => date('Y-m-d H:i:s'),
        ]);
    }

    public function getTotalCollected($hotelId = null, $days = 30) {
        $clause = $hotelId ? " AND b.hotel_id = ?" : "";
        $params = $hotelId ? [$days, $hotelId] : [$days];
        return $this->queryOne(
            "SELECT COALESCE(SUM(p.amount), 0) as total
             FROM payments p
             JOIN bookings b ON b.id = p.booking_id
             WHERE p.status = 'captured'
               AND p.paid_at >= DATE_SUB(NOW(), INTERVAL ? DAY){$clause}",
            $params
        );
    }
}
