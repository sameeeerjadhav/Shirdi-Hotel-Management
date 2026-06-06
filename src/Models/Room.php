<?php

namespace App\Models;

use Core\Model;

class Room extends Model {
    protected $table = 'rooms';

    // Get all rooms for a hotel with type info
    public function byHotel($hotelId, $status = null) {
        $params = [$hotelId];
        $statusClause = '';
        if ($status) {
            $statusClause = " AND r.status = ?";
            $params[] = $status;
        }
        return $this->query(
            "SELECT r.*, rt.name as type_name, rt.base_price, rt.bed_type, rt.capacity
             FROM rooms r
             JOIN room_types rt ON rt.id = r.room_type_id
             WHERE r.hotel_id = ?{$statusClause}
             ORDER BY r.floor_number, r.room_number",
            $params
        );
    }

    // Get a single room with full details
    public function getWithDetails($id) {
        return $this->queryOne(
            "SELECT r.*, rt.name as type_name, rt.base_price, rt.bed_type, rt.capacity, rt.amenities as type_amenities,
                    h.name as hotel_name, h.city as hotel_city
             FROM rooms r
             JOIN room_types rt ON rt.id = r.room_type_id
             JOIN hotels h ON h.id = r.hotel_id
             WHERE r.id = ?",
            [$id]
        );
    }

    // Find available rooms for booking
    public function available($hotelId, $checkIn, $checkOut, $guestCount = 1) {
        return $this->query(
            "SELECT r.*, rt.name as type_name, rt.bed_type,
                    COALESCE(r.price_override, rt.base_price) as price
             FROM rooms r
             JOIN room_types rt ON rt.id = r.room_type_id
             WHERE r.hotel_id = ?
               AND r.max_guests >= ?
               AND r.status = 'available'
               AND r.id NOT IN (
                   SELECT room_id FROM bookings
                   WHERE hotel_id = ?
                     AND status NOT IN ('cancelled', 'checked_out')
                     AND NOT (check_out_date <= ? OR check_in_date >= ?)
               )
             ORDER BY price ASC",
            [$hotelId, $guestCount, $hotelId, $checkIn, $checkOut]
        );
    }

    // Search available rooms across all hotels
    public function searchAvailable($checkIn, $checkOut, $guestCount = 1, $city = null, $roomType = null) {
        $params = [$guestCount, $checkIn, $checkOut];
        $cityClause = '';
        $typeClause = '';
        if ($city) {
            $cityClause = " AND h.city LIKE ?";
            $params[] = "%{$city}%";
        }
        if ($roomType) {
            $typeClause = " AND rt.name LIKE ?";
            $params[] = "%{$roomType}%";
        }
        return $this->query(
            "SELECT r.*, rt.name as type_name, rt.bed_type, rt.amenities,
                    COALESCE(r.price_override, rt.base_price) as price,
                    h.name as hotel_name, h.city, h.star_rating, h.cover_image, h.id as hotel_id
             FROM rooms r
             JOIN room_types rt ON rt.id = r.room_type_id
             JOIN hotels h ON h.id = r.hotel_id
             WHERE h.status = 'approved'
               AND r.max_guests >= ?
               AND r.status = 'available'
               AND r.id NOT IN (
                   SELECT room_id FROM bookings
                   WHERE status NOT IN ('cancelled', 'checked_out')
                     AND NOT (check_out_date <= ? OR check_in_date >= ?)
               ){$cityClause}{$typeClause}
             ORDER BY price ASC",
            $params
        );
    }

    // Get room types for a hotel
    public function getTypes($hotelId) {
        return $this->query(
            "SELECT * FROM room_types WHERE hotel_id = ? ORDER BY base_price",
            [$hotelId]
        );
    }

    // Create room type
    public function createType(array $data) {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $stmt = $this->pdo->prepare("INSERT INTO room_types ({$columns}) VALUES ({$placeholders})");
        $stmt->execute(array_values($data));
        return $this->pdo->lastInsertId();
    }

    public function changeStatus($id, $status) {
        return $this->update($id, ['status' => $status]);
    }

    // Floor-grouped room map for visual board
    public function getFloorMap($hotelId) {
        $rooms = $this->byHotel($hotelId);
        $floors = [];
        foreach ($rooms as $room) {
            $floors[$room['floor_number']][] = $room;
        }
        ksort($floors);
        return $floors;
    }

    // Count by status for dashboard
    public function countByStatus($hotelId) {
        $rows = $this->query(
            "SELECT status, COUNT(*) as count FROM rooms WHERE hotel_id = ? GROUP BY status",
            [$hotelId]
        );
        $map = [];
        foreach ($rows as $r) {
            $map[$r['status']] = $r['count'];
        }
        return $map;
    }
}
