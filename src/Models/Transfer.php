<?php

namespace App\Models;

use Core\Model;

class Transfer extends Model {
    protected $table = 'booking_transfers';

    public function allWithDetails($status = null) {
        $where  = $status ? 't.status = ?' : '1=1';
        $params = $status ? [$status] : [];

        try {
            // Try with new columns (requested_by, admin_notes)
            return $this->query(
                "SELECT t.id, t.booking_id, t.from_hotel_id, t.to_hotel_id,
                        t.reason, t.transfer_date, t.status,
                        b.check_in_date, b.check_out_date, b.total_amount,
                        CONCAT(g.first_name, ' ', g.last_name) as guest_name,
                        fh.name as from_hotel_name, fh.city as from_city,
                        th.name as to_hotel_name, th.city as to_city
                 FROM booking_transfers t
                 JOIN bookings b ON b.id = t.booking_id
                 JOIN guests g   ON g.id = b.guest_id
                 JOIN hotels fh  ON fh.id = t.from_hotel_id
                 JOIN hotels th  ON th.id = t.to_hotel_id
                 WHERE {$where}
                 ORDER BY t.transfer_date DESC",
                $params
            );
        } catch (\Exception $e) {
            return [];
        }
    }

    public function pending() {
        return $this->allWithDetails('pending');
    }

    public function approve($id, $adminNotes = '') {
        try {
            return $this->update($id, ['status' => 'accepted', 'admin_notes' => $adminNotes]);
        } catch (\Exception $e) {
            return $this->update($id, ['status' => 'accepted']);
        }
    }

    public function reject($id, $adminNotes = '') {
        try {
            return $this->update($id, ['status' => 'rejected', 'admin_notes' => $adminNotes]);
        } catch (\Exception $e) {
            return $this->update($id, ['status' => 'rejected']);
        }
    }

    public function countPending() {
        try {
            return $this->count("status = 'pending'");
        } catch (\Exception $e) {
            return 0;
        }
    }
}
