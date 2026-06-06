<?php

namespace App\Models;

use Core\Model;

class Guest extends Model {
    protected $table = 'guests';

    public function findByEmail($email) {
        return $this->findBy('email', $email);
    }

    public function findByPhone($phone) {
        return $this->findBy('phone', $phone);
    }

    public function getFullName($id) {
        $g = $this->find($id);
        return $g ? $g['first_name'] . ' ' . $g['last_name'] : '';
    }

    public function getWithBookings($id) {
        $guest = $this->find($id);
        if (!$guest) return null;
        $guest['bookings'] = $this->query(
            "SELECT b.*, h.name as hotel_name, r.room_number, rt.name as room_type
             FROM bookings b
             JOIN hotels h ON h.id = b.hotel_id
             JOIN rooms r ON r.id = b.room_id
             JOIN room_types rt ON rt.id = r.room_type_id
             WHERE b.guest_id = ?
             ORDER BY b.created_at DESC",
            [$id]
        );
        return $guest;
    }

    // Find or create guest (used in checkout)
    public function firstOrCreate(array $data) {
        $existing = null;
        if (!empty($data['email'])) {
            $existing = $this->findByEmail($data['email']);
        } elseif (!empty($data['phone'])) {
            $existing = $this->findByPhone($data['phone']);
        }
        if ($existing) {
            return $existing['id'];
        }
        return $this->create($data);
    }
}
