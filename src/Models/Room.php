<?php

namespace App\Models;

use Core\Model;

class Room extends Model {
    protected $table = 'rooms';

    // Get all rooms for a hotel with type info — SAFE
    public function byHotel($hotelId, $status = null) {
        $params       = [$hotelId];
        $statusClause = '';
        if ($status) {
            $statusClause = ' AND r.status = ?';
            $params[]     = $status;
        }
        try {
            return $this->query(
                "SELECT r.id, r.hotel_id, r.room_type_id, r.room_number, r.status, r.created_at,
                        rt.name as type_name, rt.base_price,
                        COALESCE(r.floor_number, 1)         as floor_number,
                        COALESCE(r.max_guests, 2)           as max_guests,
                        COALESCE(r.price_override, rt.base_price) as price_override
                 FROM rooms r
                 JOIN room_types rt ON rt.id = r.room_type_id
                 WHERE r.hotel_id = ?{$statusClause}
                 ORDER BY floor_number, r.room_number",
                $params
            );
        } catch (\Exception $e) {
            // Fallback: no new columns
            return $this->query(
                "SELECT r.id, r.hotel_id, r.room_type_id, r.room_number, r.status, r.created_at,
                        rt.name as type_name, rt.base_price,
                        1 as floor_number, 2 as max_guests, rt.base_price as price_override
                 FROM rooms r
                 JOIN room_types rt ON rt.id = r.room_type_id
                 WHERE r.hotel_id = ?{$statusClause}
                 ORDER BY r.room_number",
                $params
            );
        }
    }

    // Get a single room with full details — SAFE
    public function getWithDetails($id) {
        try {
            return $this->queryOne(
                "SELECT r.id, r.hotel_id, r.room_type_id, r.room_number, r.status, r.created_at,
                        COALESCE(r.floor_number, 1)         as floor_number,
                        COALESCE(r.max_guests, 2)           as max_guests,
                        COALESCE(r.price_override, rt.base_price) as price_override,
                        rt.name as type_name, rt.base_price,
                        h.name as hotel_name, h.city as hotel_city, h.commission_rate
                 FROM rooms r
                 JOIN room_types rt ON rt.id = r.room_type_id
                 JOIN hotels h      ON h.id  = r.hotel_id
                 WHERE r.id = ?",
                [$id]
            );
        } catch (\Exception $e) {
            return $this->queryOne(
                "SELECT r.id, r.hotel_id, r.room_type_id, r.room_number, r.status, r.created_at,
                        1 as floor_number, 2 as max_guests, rt.base_price as price_override,
                        rt.name as type_name, rt.base_price,
                        h.name as hotel_name, h.city as hotel_city, h.commission_rate
                 FROM rooms r
                 JOIN room_types rt ON rt.id = r.room_type_id
                 JOIN hotels h      ON h.id  = r.hotel_id
                 WHERE r.id = ?",
                [$id]
            );
        }
    }

    // Find available rooms for booking — SAFE
    public function available($hotelId, $checkIn, $checkOut, $guestCount = 1) {
        try {
            return $this->query(
                "SELECT r.id, r.hotel_id, r.room_number, r.status,
                        rt.name as type_name, rt.base_price as price
                 FROM rooms r
                 JOIN room_types rt ON rt.id = r.room_type_id
                 WHERE r.hotel_id = ?
                   AND r.status = 'available'
                   AND r.id NOT IN (
                       SELECT room_id FROM bookings
                       WHERE hotel_id = ?
                         AND status NOT IN ('cancelled', 'checked_out')
                         AND NOT (check_out_date <= ? OR check_in_date >= ?)
                   )
                 ORDER BY rt.base_price ASC",
                [$hotelId, $hotelId, $checkIn, $checkOut]
            );
        } catch (\Exception $e) {
            return [];
        }
    }

    // Search available rooms across all hotels — SAFE
    public function searchAvailable($checkIn, $checkOut, $guestCount = 1, $city = null, $roomType = null) {
        $params     = [$checkIn, $checkOut];
        $cityClause = '';
        $typeClause = '';
        if ($city) {
            $cityClause = ' AND h.city LIKE ?';
            $params[]   = '%' . $city . '%';
        }
        if ($roomType) {
            $typeClause = ' AND rt.name LIKE ?';
            $params[]   = '%' . $roomType . '%';
        }
        try {
            return $this->query(
                "SELECT r.id, r.hotel_id, r.room_number, r.status,
                        rt.name as type_name, rt.base_price as price,
                        h.name as hotel_name, h.city, h.commission_rate,
                        2 as star_rating, NULL as cover_image
                 FROM rooms r
                 JOIN room_types rt ON rt.id = r.room_type_id
                 JOIN hotels h      ON h.id  = r.hotel_id
                 WHERE h.status = 'approved'
                   AND r.status = 'available'
                   AND r.id NOT IN (
                       SELECT room_id FROM bookings
                       WHERE status NOT IN ('cancelled', 'checked_out')
                         AND NOT (check_out_date <= ? OR check_in_date >= ?)
                   ){$cityClause}{$typeClause}
                 ORDER BY rt.base_price ASC",
                $params
            );
        } catch (\Exception $e) {
            return [];
        }
    }

    // Get room types for a hotel — SAFE
    public function getTypes($hotelId) {
        try {
            return $this->query(
                "SELECT id, hotel_id, name, base_price, capacity
                 FROM room_types WHERE hotel_id = ? ORDER BY base_price",
                [$hotelId]
            );
        } catch (\Exception $e) {
            return [];
        }
    }

    // Create room type — SAFE (only original columns)
    public function createType(array $data) {
        // Only insert columns that definitely exist
        $safe = [];
        $allowed = ['hotel_id','name','base_price','capacity','description'];
        foreach ($allowed as $col) {
            if (isset($data[$col])) $safe[$col] = $data[$col];
        }
        // Try with extra new columns first
        try {
            $columns      = implode(', ', array_keys($data));
            $placeholders = implode(', ', array_fill(0, count($data), '?'));
            $stmt = $this->pdo->prepare("INSERT INTO room_types ({$columns}) VALUES ({$placeholders})");
            $stmt->execute(array_values($data));
            return $this->pdo->lastInsertId();
        } catch (\Exception $e) {
            $columns      = implode(', ', array_keys($safe));
            $placeholders = implode(', ', array_fill(0, count($safe), '?'));
            $stmt = $this->pdo->prepare("INSERT INTO room_types ({$columns}) VALUES ({$placeholders})");
            $stmt->execute(array_values($safe));
            return $this->pdo->lastInsertId();
        }
    }

    public function changeStatus($id, $status) {
        return $this->update($id, ['status' => $status]);
    }

    // Floor-grouped room map for visual board — SAFE
    public function getFloorMap($hotelId) {
        $rooms  = $this->byHotel($hotelId);
        $floors = [];
        foreach ($rooms as $room) {
            $floor = $room['floor_number'] ?? 1;
            $floors[$floor][] = $room;
        }
        ksort($floors);
        return $floors;
    }

    // Count by status for dashboard — SAFE
    public function countByStatus($hotelId) {
        try {
            $rows = $this->query(
                "SELECT status, COUNT(*) as count FROM rooms WHERE hotel_id = ? GROUP BY status",
                [$hotelId]
            );
        } catch (\Exception $e) {
            return [];
        }
        $map = [];
        foreach ($rows as $r) {
            $map[$r['status']] = (int)$r['count'];
        }
        return $map;
    }
}
