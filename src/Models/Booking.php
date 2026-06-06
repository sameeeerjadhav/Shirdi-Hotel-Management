<?php

namespace App\Models;

use Core\Model;

class Booking extends Model {
    protected $table = 'bookings';

    // Get all bookings with guest, hotel, room info
    public function allWithDetails($filters = []) {
        $where = '1=1';
        $params = [];

        if (!empty($filters['hotel_id'])) {
            $where .= " AND b.hotel_id = ?";
            $params[] = $filters['hotel_id'];
        }
        if (!empty($filters['status'])) {
            $where .= " AND b.status = ?";
            $params[] = $filters['status'];
        }
        if (!empty($filters['payment_status'])) {
            $where .= " AND b.payment_status = ?";
            $params[] = $filters['payment_status'];
        }
        if (!empty($filters['search'])) {
            $where .= " AND (b.booking_ref LIKE ? OR CONCAT(g.first_name,' ',g.last_name) LIKE ? OR g.phone LIKE ?)";
            $s = "%{$filters['search']}%";
            $params = array_merge($params, [$s, $s, $s]);
        }
        if (!empty($filters['date_from'])) {
            $where .= " AND b.check_in_date >= ?";
            $params[] = $filters['date_from'];
        }
        if (!empty($filters['date_to'])) {
            $where .= " AND b.check_in_date <= ?";
            $params[] = $filters['date_to'];
        }

        $limit = $filters['limit'] ?? 50;
        $offset = $filters['offset'] ?? 0;

        return $this->query(
            "SELECT b.*,
                    CONCAT(g.first_name, ' ', g.last_name) as guest_name,
                    g.email as guest_email, g.phone as guest_phone,
                    h.name as hotel_name, h.city as hotel_city,
                    r.room_number, rt.name as room_type
             FROM bookings b
             JOIN guests g ON g.id = b.guest_id
             JOIN hotels h ON h.id = b.hotel_id
             JOIN rooms r ON r.id = b.room_id
             JOIN room_types rt ON rt.id = r.room_type_id
             WHERE {$where}
             ORDER BY b.created_at DESC
             LIMIT {$limit} OFFSET {$offset}",
            $params
        );
    }

    public function getWithDetails($id) {
        return $this->queryOne(
            "SELECT b.*,
                    CONCAT(g.first_name, ' ', g.last_name) as guest_name,
                    g.email as guest_email, g.phone as guest_phone,
                    g.id_proof_type, g.id_proof_number,
                    h.name as hotel_name, h.city as hotel_city, h.phone as hotel_phone,
                    h.address as hotel_address, h.commission_rate,
                    r.room_number, r.floor_number,
                    rt.name as room_type, rt.bed_type
             FROM bookings b
             JOIN guests g ON g.id = b.guest_id
             JOIN hotels h ON h.id = b.hotel_id
             JOIN rooms r ON r.id = b.room_id
             JOIN room_types rt ON rt.id = r.room_type_id
             WHERE b.id = ?",
            [$id]
        );
    }

    public function findByRef($ref) {
        return $this->queryOne(
            "SELECT b.*,
                    CONCAT(g.first_name, ' ', g.last_name) as guest_name,
                    g.email as guest_email, g.phone as guest_phone,
                    h.name as hotel_name, h.city as hotel_city,
                    r.room_number, rt.name as room_type
             FROM bookings b
             JOIN guests g ON g.id = b.guest_id
             JOIN hotels h ON h.id = b.hotel_id
             JOIN rooms r ON r.id = b.room_id
             JOIN room_types rt ON rt.id = r.room_type_id
             WHERE b.booking_ref = ?",
            [$ref]
        );
    }

    public function checkIn($id) {
        // Mark booking checked in, update room status to occupied
        $booking = $this->find($id);
        if ($booking) {
            $this->update($id, ['status' => 'checked_in']);
            $this->execute("UPDATE rooms SET status = 'occupied' WHERE id = ?", [$booking['room_id']]);
        }
    }

    public function checkOut($id, $extraCharges = 0, $damageCharges = 0) {
        $booking = $this->find($id);
        if ($booking) {
            $total = $booking['total_amount'] + $extraCharges + $damageCharges;
            $this->update($id, [
                'status' => 'checked_out',
                'extra_charges' => $extraCharges,
                'damage_charges' => $damageCharges,
                'total_amount' => $total,
            ]);
            $this->execute("UPDATE rooms SET status = 'available' WHERE id = ?", [$booking['room_id']]);
        }
    }

    public function cancel($id) {
        $booking = $this->find($id);
        if ($booking) {
            $this->update($id, ['status' => 'cancelled']);
            $this->execute("UPDATE rooms SET status = 'available' WHERE id = ?", [$booking['room_id']]);
        }
    }

    // Revenue aggregates
    public function getRevenueSummary($hotelId = null, $days = 30) {
        $hotelClause = $hotelId ? " AND hotel_id = ?" : "";
        $params = $hotelId ? [$days, $hotelId] : [$days];
        return $this->queryOne(
            "SELECT
                COALESCE(SUM(total_amount), 0) as total_revenue,
                COALESCE(SUM(paid_amount), 0) as collected,
                COALESCE(SUM(platform_fee), 0) as platform_fees,
                COALESCE(SUM(total_amount - paid_amount), 0) as pending,
                COUNT(*) as total_bookings,
                SUM(CASE WHEN status = 'confirmed' THEN 1 ELSE 0 END) as confirmed,
                SUM(CASE WHEN status = 'checked_in' THEN 1 ELSE 0 END) as checked_in,
                SUM(CASE WHEN status = 'checked_out' THEN 1 ELSE 0 END) as completed,
                SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled
             FROM bookings
             WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)
               AND status != 'cancelled'{$hotelClause}",
            $params
        );
    }

    // Daily revenue for charts
    public function getDailyRevenue($hotelId = null, $days = 30) {
        $hotelClause = $hotelId ? " AND hotel_id = ?" : "";
        $params = $hotelId ? [$days, $hotelId] : [$days];
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
    }

    // Generate unique booking ref
    public function generateRef() {
        do {
            $ref = 'BKG' . date('Y') . strtoupper(substr(uniqid(), -6));
        } while ($this->findBy('booking_ref', $ref));
        return $ref;
    }

    // Count total for pagination
    public function countFiltered($filters = []) {
        $where = '1=1';
        $params = [];
        if (!empty($filters['hotel_id'])) { $where .= " AND hotel_id = ?"; $params[] = $filters['hotel_id']; }
        if (!empty($filters['status']))    { $where .= " AND status = ?";   $params[] = $filters['status'];   }
        return $this->count($where, $params);
    }
}
