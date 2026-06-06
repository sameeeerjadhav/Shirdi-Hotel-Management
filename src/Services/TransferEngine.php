<?php

namespace App\Services;

class TransferEngine {
    
    /**
     * Finds an alternative hotel if the target hotel is full.
     * Criteria: Same city, available rooms, similar price range (+/- 20%).
     */
    public function findAlternative($city, $targetPrice) {
        // Mocking a database query to find alternative hotels.
        // In reality, this would be:
        // SELECT * FROM hotels h 
        // JOIN rooms r ON h.id = r.hotel_id 
        // WHERE h.city = :city AND r.status = 'available' AND r.price BETWEEN :min AND :max

        $alternatives = [
            ['id' => 3, 'name' => 'City Center Inn', 'city' => 'Mumbai', 'price' => 4000, 'available_rooms' => 2]
        ];

        // Return the best match (e.g. first one for now)
        if (!empty($alternatives)) {
            return $alternatives[0];
        }

        return null;
    }
}
