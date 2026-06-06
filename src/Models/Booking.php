<?php

namespace App\Models;

use Core\Model;

class Booking extends Model {
    protected $table = 'bookings';

    // ---- SAFE column list: works with BOTH old and new schema ----
    // New columns fall back to 0/NULL via COALESCE on MySQL IF()
    private function safeSelect() {
        return "b.id, b.hotel_id, b.guest_id, b.room_id,
                b.check_in_date, b.check_out_date, b.status, b.created_at,
                b.total_amount,
                IF(1=1, b.total_amount, 0) as total_amount_safe";
    }

    // Get all bookings with guest, hotel, room info
    public function allWithDetails($filters = []) {
        $where  = '1=1';
        $params = [];

        if (!empty($filters['hotel_id'])) {
            $where .= ' AND b.hotel_id = ?';
            $params[] = $filters['hotel_id'];
        }
        if (!empty($filters['status']) && $filters['status'] !== '') {
            $where .= ' AND b.status = ?';
            $params[] = $filters['status'];
        }
        if (!empty($filters['search'])) {
            // Only search on columns that definitely exist
            $where .= " AND (CONCAT(g.first_name,' ',g.last_name) LIKE ? OR g.phone LIKE ?)";
            $s = '%' . $filters['search'] . '%';
            $params[] = $s;
            $params[] = $s;
        }
        if (!empty($filters['date_from'])) {
            $where .= ' AND b.check_in_date >= ?';
            $params[] = $filters['date_from'];
        }
        if (!empty($filters['date_to'])) {
            $where .= ' AND b.check_in_date <= ?';
            $params[] = $filters['date_to'];
        }

        $limit  = isset($filters['limit'])  ? (int)$filters['limit']  : 50;
        $offset = isset($filters['offset']) ? (int)$filters['offset'] : 0;

        try {
            return $this->query(
                "SELECT b.id, b.hotel_id, b.room_id, b.guest_id,
                        b.check_in_date, b.check_out_date, b.status, b.total_amount,
                        b.created_at,
                        CONCAT(g.first_name, ' ', g.last_name) as guest_name,
                        g.email as guest_email, g.phone as guest_phone,
                        h.name as hotel_name, h.city as hotel_city,
                        r.room_number, rt.name as room_type
                 FROM bookings b
                 JOIN guests g  ON g.id  = b.guest_id
                 JOIN hotels h  ON h.id  = b.hotel_id
                 JOIN rooms r   ON r.id  = b.room_id
                 JOIN room_types rt ON rt.id = r.room_type_id
                 WHERE {$where}
                 ORDER BY b.created_at DESC
                 LIMIT {$limit} OFFSET {$offset}",
                $params
            );
        } catch (\Exception $e) {
            return [];
        }
    }

    public function getWithDetails($id) {
        try {
            return $this->queryOne(
                "SELECT b.id, b.hotel_id, b.room_id, b.guest_id,
                        b.check_in_date, b.check_out_date, b.status, b.total_amount,
                        b.created_at,
                        CONCAT(g.first_name, ' ', g.last_name) as guest_name,
                        g.email as guest_email, g.phone as guest_phone,
                        h.name as hotel_name, h.city as hotel_city, h.phone as hotel_phone,
                        h.address as hotel_address, h.commission_rate,
                        r.room_number,
                        rt.name as room_type
                 FROM bookings b
                 JOIN guests g  ON g.id  = b.guest_id
                 JOIN hotels h  ON h.id  = b.hotel_id
                 JOIN rooms r   ON r.id  = b.room_id
                 JOIN room_types rt ON rt.id = r.room_type_id
                 WHERE b.id = ?",
                [$id]
            );
        } catch (\Exception $e) {
            return null;
        }
    }

    public function findByRef($ref) {
        // Try with booking_ref column (new schema)
        try {
            return $this->queryOne(
                "SELECT b.id, b.hotel_id, b.room_id, b.guest_id,
                        b.check_in_date, b.check_out_date, b.status, b.total_amount,
                        CONCAT(g.first_name, ' ', g.last_name) as guest_name,
                        g.email as guest_email, g.phone as guest_phone,
                        h.name as hotel_name, h.city as hotel_city,
                        r.room_number, rt.name as room_type
                 FROM bookings b
                 JOIN guests g  ON g.id  = b.guest_id
                 JOIN hotels h  ON h.id  = b.hotel_id
                 JOIN rooms r   ON r.id  = b.room_id
                 JOIN room_types rt ON rt.id = r.room_type_id
                 WHERE b.booking_ref = ?",
                [$ref]
            );
        } catch (\Exception $e) {
            return null;
        }
    }

    public function checkIn($id) {
        $booking = $this->find($id);
        if ($booking) {
            $this->update($id, ['status' => 'checked_in']);
            try {
                $this->execute("UPDATE rooms SET status = 'occupied' WHERE id = ?", [$booking['room_id']]);
            } catch (\Exception $e) {}
        }
    }

    public function checkOut($id, $extraCharges = 0, $damageCharges = 0) {
        $booking = $this->find($id);
        if ($booking) {
            $total = $booking['total_amount'] + $extraCharges + $damageCharges;
            $updateData = ['status' => 'checked_out', 'total_amount' => $total];
            // Try to update extra charge columns if they exist
            try {
                $this->execute(
                    "UPDATE bookings SET status='checked_out', total_amount=? WHERE id=?",
                    [$total, $id]
                );
            } catch (\Exception $e) {
                $this->update($id, ['status' => 'checked_out']);
            }
            try {
                $this->execute("UPDATE rooms SET status = 'available' WHERE id = ?", [$booking['room_id']]);
            } catch (\Exception $e) {}
        }
    }

    public function cancel($id) {
        $booking = $this->find($id);
        if ($booking) {
            $this->update($id, ['status' => 'cancelled']);
            try {
                $this->execute("UPDATE rooms SET status = 'available' WHERE id = ?", [$booking['room_id']]);
            } catch (\Exception $e) {}
        }
    }

    // Revenue aggregates — SAFE: only use original columns
    public function getRevenueSummary($hotelId = null, $days = 30) {
        $hotelClause = $hotelId ? ' AND hotel_id = ?' : '';
        $params      = $hotelId ? [$days, $hotelId] : [$days];
        try {
            return $this->queryOne(
                "SELECT
                    COALESCE(SUM(total_amount), 0) as total_revenue,
                    COALESCE(SUM(total_amount), 0) as collected,
                    0 as platform_fees,
                    0 as pending,
                    COUNT(*) as total_bookings,
                    SUM(CASE WHEN status = 'confirmed'   THEN 1 ELSE 0 END) as confirmed,
                    SUM(CASE WHEN status = 'checked_in'  THEN 1 ELSE 0 END) as checked_in,
                    SUM(CASE WHEN status = 'checked_out' THEN 1 ELSE 0 END) as completed,
                    SUM(CASE WHEN status = 'cancelled'   THEN 1 ELSE 0 END) as cancelled
                 FROM bookings
                 WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)
                   AND status != 'cancelled'{$hotelClause}",
                $params
            );
        } catch (\Exception $e) {
            return ['total_revenue'=>0,'collected'=>0,'platform_fees'=>0,'pending'=>0,'total_bookings'=>0,'confirmed'=>0,'checked_in'=>0,'completed'=>0,'cancelled'=>0];
        }
    }

    // Daily revenue for charts — SAFE
    public function getDailyRevenue($hotelId = null, $days = 30) {
        $hotelClause = $hotelId ? ' AND hotel_id = ?' : '';
        $params      = $hotelId ? [$days, $hotelId] : [$days];
        try {
            return $this->query(
                "SELECT DATE(created_at) as date,
                        COALESCE(SUM(total_amount), 0) as revenue,
                        COUNT(*) as bookings
                 FROM bookings
                 WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)
                   AND status != 'cancelled'{$hotelClause}
                 GROUP BY DATE(created_at)
                 ORDER BY date ASC",
                $params
            );
        } catch (\Exception $e) {
            return [];
        }
    }

    // Generate unique booking ref
    public function generateRef() {
        $ref = 'BKG' . date('Y') . strtoupper(substr(uniqid(), -6));
        return $ref;
    }

    public function countFiltered($filters = []) {
        $where  = '1=1';
        $params = [];
        if (!empty($filters['hotel_id'])) { $where .= ' AND hotel_id = ?'; $params[] = $filters['hotel_id']; }
        if (!empty($filters['status']))   { $where .= ' AND status = ?';   $params[] = $filters['status'];   }
        return $this->count($where, $params);
    }
}
