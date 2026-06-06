<?php

namespace App\Models;

use Core\Model;

class Hotel extends Model {
    protected $table = 'hotels';

    // Get all hotels with their admin user info — SAFE (original schema only)
    public function allWithAdmin($status = null) {
        $where  = $status ? 'h.status = ?' : '1=1';
        $params = $status ? [$status] : [];
        try {
            return $this->query(
                "SELECT h.id, h.name, h.email, h.phone, h.city, h.state, h.status,
                        h.admin_user_id, h.commission_rate, h.created_at,
                        u.name as admin_name, u.email as admin_email, u.phone as admin_phone,
                        COUNT(DISTINCT r.id) as room_count,
                        SUM(CASE WHEN r.status = 'occupied' THEN 1 ELSE 0 END) as occupied_count
                 FROM hotels h
                 LEFT JOIN users u  ON u.id  = h.admin_user_id
                 LEFT JOIN rooms r  ON r.hotel_id = h.id
                 WHERE {$where}
                 GROUP BY h.id
                 ORDER BY h.created_at DESC",
                $params
            );
        } catch (\Exception $e) {
            return [];
        }
    }

    // Get single hotel with full details — SAFE
    public function getWithDetails($id) {
        try {
            return $this->queryOne(
                "SELECT h.*, u.name as admin_name, u.email as admin_email, u.phone as admin_phone
                 FROM hotels h
                 LEFT JOIN users u ON u.id = h.admin_user_id
                 WHERE h.id = ?",
                [$id]
            );
        } catch (\Exception $e) {
            return $this->find($id);
        }
    }

    // Get hotel for a given admin user
    public function findByAdminUser($userId) {
        return $this->findBy('admin_user_id', $userId);
    }

    // Get stats (for admin dashboard) — SAFE: no platform_fee column
    public function getNetworkStats() {
        try {
            return $this->queryOne(
                "SELECT
                    COUNT(DISTINCT h.id) as total_hotels,
                    SUM(CASE WHEN h.status = 'approved'  THEN 1 ELSE 0 END) as active_hotels,
                    SUM(CASE WHEN h.status = 'pending'   THEN 1 ELSE 0 END) as pending_hotels,
                    COUNT(DISTINCT r.id) as total_rooms,
                    SUM(CASE WHEN r.status = 'occupied'  THEN 1 ELSE 0 END) as occupied_rooms,
                    SUM(CASE WHEN r.status = 'available' THEN 1 ELSE 0 END) as available_rooms,
                    COALESCE(SUM(b.total_amount), 0) as total_revenue,
                    COALESCE(SUM(b.total_amount) * 0.1, 0) as platform_revenue
                 FROM hotels h
                 LEFT JOIN rooms r    ON r.hotel_id = h.id
                 LEFT JOIN bookings b ON b.hotel_id = h.id AND b.status NOT IN ('cancelled')",
                []
            );
        } catch (\Exception $e) {
            return ['total_hotels'=>0,'active_hotels'=>0,'pending_hotels'=>0,'total_rooms'=>0,'occupied_rooms'=>0,'available_rooms'=>0,'total_revenue'=>0,'platform_revenue'=>0];
        }
    }

    // Get hotel performance stats — SAFE: no paid_amount column
    public function getHotelStats($hotelId) {
        try {
            return $this->queryOne(
                "SELECT
                    COUNT(DISTINCT r.id) as total_rooms,
                    SUM(CASE WHEN r.status = 'occupied'  THEN 1 ELSE 0 END) as occupied_rooms,
                    SUM(CASE WHEN r.status = 'available' THEN 1 ELSE 0 END) as available_rooms,
                    COALESCE(SUM(b.total_amount), 0) as total_revenue,
                    COALESCE(SUM(b.total_amount), 0) as collected_revenue,
                    COUNT(DISTINCT b.id) as total_bookings,
                    SUM(CASE WHEN b.status = 'confirmed' THEN 1 ELSE 0 END) as pending_checkins,
                    SUM(CASE WHEN DATE(b.check_in_date) = CURDATE() AND b.status = 'confirmed' THEN 1 ELSE 0 END) as today_checkins,
                    SUM(CASE WHEN DATE(b.check_out_date) = CURDATE() AND b.status = 'checked_in' THEN 1 ELSE 0 END) as today_checkouts
                 FROM hotels h
                 LEFT JOIN rooms r    ON r.hotel_id = h.id
                 LEFT JOIN bookings b ON b.hotel_id = h.id AND b.status NOT IN ('cancelled')
                 WHERE h.id = ?",
                [$hotelId]
            );
        } catch (\Exception $e) {
            return ['total_rooms'=>0,'occupied_rooms'=>0,'available_rooms'=>0,'total_revenue'=>0,'collected_revenue'=>0,'total_bookings'=>0,'pending_checkins'=>0,'today_checkins'=>0,'today_checkouts'=>0];
        }
    }

    public function approve($id) {
        return $this->update($id, ['status' => 'approved']);
    }

    public function reject($id) {
        return $this->update($id, ['status' => 'rejected']);
    }

    public function suspend($id) {
        // Try with new column, fall back to old schema
        try {
            return $this->update($id, ['status' => 'suspended', 'suspended_at' => date('Y-m-d H:i:s')]);
        } catch (\Exception $e) {
            return $this->update($id, ['status' => 'suspended']);
        }
    }

    // Search hotels — SAFE: no hotel_code (uses LIKE on name/city only)
    public function search($term, $status = null) {
        $params       = ['%' . $term . '%', '%' . $term . '%'];
        $statusClause = '';
        if ($status) {
            $statusClause = ' AND h.status = ?';
            $params[]     = $status;
        }
        try {
            return $this->query(
                "SELECT h.id, h.name, h.email, h.city, h.state, h.status,
                        h.admin_user_id, h.commission_rate, h.created_at,
                        COUNT(DISTINCT r.id) as room_count
                 FROM hotels h
                 LEFT JOIN rooms r ON r.hotel_id = h.id
                 WHERE (h.name LIKE ? OR h.city LIKE ?){$statusClause}
                 GROUP BY h.id
                 ORDER BY h.created_at DESC",
                $params
            );
        } catch (\Exception $e) {
            return [];
        }
    }

    // Get nearby hotels (for transfer engine)
    public function getNearby($city, $excludeHotelId, $limit = 5) {
        try {
            return $this->query(
                "SELECT h.id, h.name, h.city, h.commission_rate,
                        COUNT(DISTINCT r.id) as available_rooms
                 FROM hotels h
                 LEFT JOIN rooms r ON r.hotel_id = h.id AND r.status = 'available'
                 WHERE h.city = ? AND h.id != ? AND h.status = 'approved'
                 GROUP BY h.id
                 HAVING available_rooms > 0
                 ORDER BY available_rooms DESC
                 LIMIT " . (int)$limit,
                [$city, $excludeHotelId]
            );
        } catch (\Exception $e) {
            return [];
        }
    }
}
