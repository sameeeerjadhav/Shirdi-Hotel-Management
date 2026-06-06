<?php

namespace App\Services;

use App\Models\Hotel;
use App\Models\Room;
use App\Models\Booking;

class TransferEngine {

    private Hotel $hotelModel;
    private Room $roomModel;

    public function __construct() {
        $this->hotelModel = new Hotel();
        $this->roomModel  = new Room();
    }

    /**
     * Find alternative hotels when a hotel has no available rooms.
     * Returns ranked list of alternatives by availability and proximity.
     */
    public function findAlternatives($fromHotelId, $checkIn, $checkOut, $guestCount = 1, $roomType = null) {
        $fromHotel = $this->hotelModel->find($fromHotelId);
        if (!$fromHotel) return [];

        // Get all approved hotels in same city except the source hotel
        $candidates = $this->hotelModel->getNearby($fromHotel['city'], $fromHotelId, 10);

        $alternatives = [];
        foreach ($candidates as $hotel) {
            $available = $this->roomModel->available($hotel['id'], $checkIn, $checkOut, $guestCount);

            // Filter by room type if specified
            if ($roomType) {
                $available = array_filter($available, function($r) use ($roomType) { return stripos($r['type_name'], $roomType) !== false; });
                $available = array_values($available);
            }

            if (count($available) === 0) continue;

            $minPrice = min(array_column($available, 'price'));
            $alternatives[] = [
                'hotel'           => $hotel,
                'available_rooms' => count($available),
                'rooms'           => $available,
                'min_price'       => $minPrice,
                'score'           => $this->score($hotel, count($available), $minPrice),
            ];
        }

        // Sort by score descending
        usort($alternatives, function($a, $b) { return $b['score'] <=> $a['score']; });

        return array_slice($alternatives, 0, 5);
    }

    /**
     * Score a candidate hotel (higher = better suggestion)
     */
    private function score($hotel, $availableRooms, $minPrice) {
        $score = 0;
        $score += $hotel['star_rating'] * 20;   // star rating weight
        $score += min($availableRooms, 10) * 5; // availability weight
        $score -= ($minPrice / 1000);            // price penalty
        return $score;
    }
}
