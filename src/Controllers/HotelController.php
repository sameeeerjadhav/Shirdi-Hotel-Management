<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\Booking;
use App\Models\Notification;
use App\Models\AuditLog;
use App\Middleware\CsrfMiddleware;
use App\Utils\Sanitizer;
use App\Services\NotificationService;

class HotelController extends Controller {

    private Hotel $hotelModel;
    private Room $roomModel;
    private Booking $bookingModel;
    private Notification $notifModel;
    private int $hotelId;

    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 2) {
            $this->redirect('/login');
        }
        $this->hotelModel   = new Hotel();
        $this->roomModel    = new Room();
        $this->bookingModel = new Booking();
        $this->notifModel   = new Notification();

        // Load hotel for this admin
        $hotel = $this->hotelModel->findByAdminUser($_SESSION['user_id']);
        if (!$hotel) {
            $_SESSION['error'] = 'No hotel linked to your account. Contact admin.';
            session_destroy();
            $this->redirect('/login');
        }
        $_SESSION['hotel_id'] = $hotel['id'];
        $_SESSION['hotel_name'] = $hotel['name'];
        $this->hotelId = $hotel['id'];
    }

    // =============================================
    // DASHBOARD
    // =============================================
    public function dashboard() {
        $stats       = $this->hotelModel->getHotelStats($this->hotelId);
        $recentBooks = $this->bookingModel->allWithDetails(['hotel_id' => $this->hotelId, 'limit' => 5]);
        $revenue     = $this->bookingModel->getRevenueSummary($this->hotelId, 30);
        $dailyRev    = $this->bookingModel->getDailyRevenue($this->hotelId, 30);
        $roomStatus  = $this->roomModel->countByStatus($this->hotelId);
        $unreadCount = $this->notifModel->unreadCount($_SESSION['user_id']);

        return $this->view('hotel/dashboard', [
            'title'       => 'Hotel Dashboard - CHNMS',
            'stats'       => $stats,
            'recentBooks' => $recentBooks,
            'revenue'     => $revenue,
            'dailyRevenue'=> json_encode($dailyRev),
            'roomStatus'  => $roomStatus,
            'unreadCount' => $unreadCount,
        ]);
    }

    // =============================================
    // ROOMS MODULE
    // =============================================
    public function rooms() {
        $floorMap    = $this->roomModel->getFloorMap($this->hotelId);
        $roomTypes   = $this->roomModel->getTypes($this->hotelId);
        $statusCounts= $this->roomModel->countByStatus($this->hotelId);
        $unreadCount = $this->notifModel->unreadCount($_SESSION['user_id']);

        return $this->view('hotel/rooms', [
            'title'        => 'Room Management - CHNMS',
            'floorMap'     => $floorMap,
            'roomTypes'    => $roomTypes,
            'statusCounts' => $statusCounts,
            'hotelId'      => $this->hotelId,
            'unreadCount'  => $unreadCount,
        ]);
    }

    public function addRoom() {
        CsrfMiddleware::verify();
        $data = Sanitizer::cleanPost(['room_number','floor_number','room_type_id','max_guests','price_override','notes']);
        $errors = Sanitizer::validate($data, ['room_number' => 'required', 'room_type_id' => 'required']);

        if ($errors) {
            $this->json(['success' => false, 'errors' => $errors], 422);
        }

        $data['hotel_id']   = $this->hotelId;
        $data['status']     = 'available';
        $data['amenities']  = !empty($_POST['amenities']) ? json_encode($_POST['amenities']) : null;

        // Handle image upload
        if (!empty($_FILES['room_image']['name'])) {
            $data['images'] = json_encode([$this->uploadImage($_FILES['room_image'])]);
        }

        $id = $this->roomModel->create($data);
        AuditLog::record('room_created', 'Room', $id, null, $data);
        $this->json(['success' => true, 'message' => 'Room added successfully.', 'id' => $id]);
    }

    public function editRoom($id) {
        $room = $this->roomModel->getWithDetails($id);
        if (!$room || $room['hotel_id'] != $this->hotelId) {
            $this->json(['success' => false, 'message' => 'Room not found.'], 404);
        }
        $this->json(['success' => true, 'room' => $room]);
    }

    public function updateRoom($id) {
        CsrfMiddleware::verify();
        $old  = $this->roomModel->find($id);
        if (!$old || $old['hotel_id'] != $this->hotelId) {
            $this->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }
        $data = Sanitizer::cleanPost(['room_number','floor_number','room_type_id','max_guests','price_override','status','notes']);
        $this->roomModel->update($id, $data);
        AuditLog::record('room_updated', 'Room', $id, $old, $data);
        $this->json(['success' => true, 'message' => 'Room updated.']);
    }

    public function deleteRoom($id) {
        CsrfMiddleware::verify();
        $room = $this->roomModel->find($id);
        if (!$room || $room['hotel_id'] != $this->hotelId) {
            $this->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }
        $this->roomModel->delete($id);
        AuditLog::record('room_deleted', 'Room', $id);
        $this->json(['success' => true, 'message' => 'Room deleted.']);
    }

    public function updateRoomStatus($id) {
        CsrfMiddleware::verify();
        $status = Sanitizer::clean($_POST['status'] ?? '');
        $allowed = ['available','cleaning','maintenance','blocked'];
        if (!in_array($status, $allowed)) {
            $this->json(['success' => false, 'message' => 'Invalid status.'], 422);
        }
        $room = $this->roomModel->find($id);
        if (!$room || $room['hotel_id'] != $this->hotelId) {
            $this->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }
        $this->roomModel->changeStatus($id, $status);
        $this->json(['success' => true]);
    }

    public function addRoomType() {
        CsrfMiddleware::verify();
        $data = Sanitizer::cleanPost(['name','base_price','capacity','bed_type','description']);
        $errors = Sanitizer::validate($data, ['name' => 'required', 'base_price' => 'required|numeric']);
        if ($errors) { $this->json(['success' => false, 'errors' => $errors], 422); }
        $data['hotel_id']  = $this->hotelId;
        $data['amenities'] = !empty($_POST['amenities']) ? json_encode($_POST['amenities']) : null;
        $id = $this->roomModel->createType($data);
        $this->json(['success' => true, 'id' => $id]);
    }

    // =============================================
    // BOOKINGS MODULE
    // =============================================
    public function bookings() {
        $filters = [
            'hotel_id'  => $this->hotelId,
            'status'    => Sanitizer::clean($_GET['status'] ?? ''),
            'search'    => Sanitizer::clean($_GET['search'] ?? ''),
            'date_from' => Sanitizer::date($_GET['date_from'] ?? '') ?: null,
            'date_to'   => Sanitizer::date($_GET['date_to'] ?? '') ?: null,
            'limit'     => 25,
        ];
        $bookings    = $this->bookingModel->allWithDetails($filters);
        $revenue     = $this->bookingModel->getRevenueSummary($this->hotelId, 30);
        $unreadCount = $this->notifModel->unreadCount($_SESSION['user_id']);

        return $this->view('hotel/bookings', [
            'title'      => 'Bookings - CHNMS',
            'bookings'   => $bookings,
            'revenue'    => $revenue,
            'filters'    => $filters,
            'unreadCount'=> $unreadCount,
        ]);
    }

    public function checkIn($id) {
        CsrfMiddleware::verify();
        $booking = $this->bookingModel->find($id);
        if (!$booking || $booking['hotel_id'] != $this->hotelId) {
            $this->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }
        $this->bookingModel->checkIn($id);
        $notif = new NotificationService();
        $details = $this->bookingModel->getWithDetails($id);
        $notif->guestCheckedIn($details, $_SESSION['user_id']);
        AuditLog::record('checkin', 'Booking', $id);
        $this->json(['success' => true, 'message' => 'Guest checked in successfully.']);
    }

    public function checkOut($id) {
        CsrfMiddleware::verify();
        $booking = $this->bookingModel->find($id);
        if (!$booking || $booking['hotel_id'] != $this->hotelId) {
            $this->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }
        $extra  = Sanitizer::float($_POST['extra_charges']  ?? 0);
        $damage = Sanitizer::float($_POST['damage_charges'] ?? 0);
        $this->bookingModel->checkOut($id, $extra, $damage);
        $notif = new NotificationService();
        $details = $this->bookingModel->getWithDetails($id);
        $notif->guestCheckedOut($details, $_SESSION['user_id']);
        AuditLog::record('checkout', 'Booking', $id);
        $this->json(['success' => true, 'message' => 'Guest checked out successfully.']);
    }

    // =============================================
    // HELPERS
    // =============================================
    private function uploadImage($file) {
        $uploadDir = __DIR__ . '/../../../public/uploads/rooms/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
        $ext  = pathinfo($file['name'], PATHINFO_EXTENSION);
        $name = uniqid('room_') . '.' . $ext;
        move_uploaded_file($file['tmp_name'], $uploadDir . $name);
        return '/uploads/rooms/' . $name;
    }
}
