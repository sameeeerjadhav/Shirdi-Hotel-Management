<?php

namespace App\Models;

use Core\Model;

class Transfer extends Model {
    protected $table = 'booking_transfers';

    public function allWithDetails($status = null) {
        $where = $status ? "t.status = ?" : "1=1";
        $params = $status ? [$status] : [];
        return $this->query(
            "SELECT t.*,
                    b.booking_ref, b.check_in_date, b.check_out_date, b.total_amount,
                    CONCAT(g.first_name, ' ', g.last_name) as guest_name,
                    fh.name as from_hotel_name, fh.city as from_city,
                    th.name as to_hotel_name, th.city as to_city,
                    u.name as requested_by_name
             FROM booking_transfers t
             JOIN bookings b ON b.id = t.booking_id
             JOIN guests g ON g.id = b.guest_id
             JOIN hotels fh ON fh.id = t.from_hotel_id
             JOIN hotels th ON th.id = t.to_hotel_id
             LEFT JOIN users u ON u.id = t.requested_by
             WHERE {$where}
             ORDER BY t.transfer_date DESC",
            $params
        );
    }

    public function pending() {
        return $this->allWithDetails('pending');
    }

    public function approve($id, $adminNotes = '') {
        return $this->update($id, ['status' => 'accepted', 'admin_notes' => $adminNotes]);
    }

    public function reject($id, $adminNotes = '') {
        return $this->update($id, ['status' => 'rejected', 'admin_notes' => $adminNotes]);
    }

    public function countPending() {
        return $this->count("status = 'pending'");
    }
}
