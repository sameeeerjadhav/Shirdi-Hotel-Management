<?php

namespace App\Models;

use Core\Model;

class Hotel extends Model {
    protected $table = 'hotels';

    // Get all hotels with their admin user info
    public function allWithAdmin($status = null) {
        $where = $status ? "h.status = ?" : "1=1";
        $params = $status ? [$status] : [];
        return $this->query(
            "SELECT h.*, u.name as admin_name, u.email as admin_email, u.phone as admin_phone,
                    COUNT(DISTINCT r.id) as room_count,
                    SUM(CASE WHEN r.status = 'occupied' THEN 1 ELSE 0 END) as occupied_count
             FROM hotels h
             LEFT JOIN users u ON u.id = h.admin_user_id
             LEFT JOIN rooms r ON r.hotel_id = h.id
             WHERE {$where}
             GROUP BY h.id
             ORDER BY h.created_at DESC",
            $params
        );
    }

    // Get single hotel with full details
    public function getWithDetails($id) {
        return $this->queryOne(
            "SELECT h.*, u.name as admin_name, u.email as admin_email, u.phone as admin_phone
             FROM hotels h
             LEFT JOIN users u ON u.id = h.admin_user_id
             WHERE h.id = ?",
            [$id]
        );
    }

    // Get hotel for a given admin user
    public function findByAdminUser($userId) {
        return $this->findBy('admin_user_id', $userId);
    }

    // Get stats (for admin dashboard)
    public function getNetworkStats() {
        return $this->queryOne(
            "SELECT
                COUNT(DISTINCT h.id) as total_hotels,
                SUM(CASE WHEN h.status = 'approved' THEN 1 ELSE 0 END) as active_hotels,
                SUM(CASE WHEN h.status = 'pending' THEN 1 ELSE 0 END) as pending_hotels,
                COUNT(DISTINCT r.id) as total_rooms,
                SUM(CASE WHEN r.status = 'occupied' THEN 1 ELSE 0 END) as occupied_rooms,
                SUM(CASE WHEN r.status = 'available' THEN 1 ELSE 0 END) as available_rooms,
                COALESCE(SUM(b.total_amount), 0) as total_revenue,
                COALESCE(SUM(b.platform_fee), 0) as platform_revenue
             FROM hotels h
             LEFT JOIN rooms r ON r.hotel_id = h.id
             LEFT JOIN bookings b ON b.hotel_id = h.id AND b.status NOT IN ('cancelled')",
            []
        );
    }

    // Get hotel performance stats (for a single hotel)
    public function getHotelStats($hotelId) {
        return $this->queryOne(
            "SELECT
                COUNT(DISTINCT r.id) as total_rooms,
                SUM(CASE WHEN r.status = 'occupied' THEN 1 ELSE 0 END) as occupied_rooms,
                SUM(CASE WHEN r.status = 'available' THEN 1 ELSE 0 END) as available_rooms,
                COALESCE(SUM(b.total_amount), 0) as total_revenue,
                COALESCE(SUM(b.paid_amount), 0) as collected_revenue,
                COUNT(DISTINCT b.id) as total_bookings,
                SUM(CASE WHEN b.status = 'confirmed' THEN 1 ELSE 0 END) as pending_checkins,
                SUM(CASE WHEN DATE(b.check_in_date) = CURDATE() AND b.status = 'confirmed' THEN 1 ELSE 0 END) as today_checkins,
                SUM(CASE WHEN DATE(b.check_out_date) = CURDATE() AND b.status = 'checked_in' THEN 1 ELSE 0 END) as today_checkouts
             FROM hotels h
             LEFT JOIN rooms r ON r.hotel_id = h.id
             LEFT JOIN bookings b ON b.hotel_id = h.id AND b.status NOT IN ('cancelled')
             WHERE h.id = ?",
            [$hotelId]
        );
    }

    public function approve($id) {
        return $this->update($id, ['status' => 'approved']);
    }

    public function reject($id) {
        return $this->update($id, ['status' => 'rejected']);
    }

    public function suspend($id) {
        return $this->update($id, ['status' => 'suspended', 'suspended_at' => date('Y-m-d H:i:s')]);
    }

    // Search hotels
    public function search($term, $status = null) {
        $params = ["%{$term}%", "%{$term}%", "%{$term}%"];
        $statusClause = '';
        if ($status) {
            $statusClause = " AND h.status = ?";
            $params[] = $status;
        }
        return $this->query(
            "SELECT h.*, COUNT(DISTINCT r.id) as room_count
             FROM hotels h
             LEFT JOIN rooms r ON r.hotel_id = h.id
             WHERE (h.name LIKE ? OR h.city LIKE ? OR h.hotel_code LIKE ?){$statusClause}
             GROUP BY h.id
             ORDER BY h.created_at DESC",
            $params
        );
    }

    // Get nearby hotels (for transfer engine)
    public function getNearby($city, $excludeHotelId, $limit = 5) {
        return $this->query(
            "SELECT h.*, COUNT(DISTINCT r.id) as available_rooms
             FROM hotels h
             LEFT JOIN rooms r ON r.hotel_id = h.id AND r.status = 'available'
             WHERE h.city = ? AND h.id != ? AND h.status = 'approved'
             GROUP BY h.id
             HAVING available_rooms > 0
             ORDER BY available_rooms DESC
             LIMIT ?",
            [$city, $excludeHotelId, $limit]
        );
    }
}
