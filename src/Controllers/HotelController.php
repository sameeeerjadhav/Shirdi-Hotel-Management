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
        try {
            $hotel = $this->hotelModel->findByAdminUser($_SESSION['user_id']);
        } catch (\Exception $e) {
            $hotel = null;
        }

        if (!$hotel) {
            // If no hotel linked, show friendly message instead of crashing
            if (!isset($_SESSION['hotel_id'])) {
                $_SESSION['error'] = 'No hotel linked to your account. Contact the super admin.';
                session_destroy();
                $this->redirect('/login');
            }
            // Reload from cached session if available
            $hotel = ['id' => $_SESSION['hotel_id'], 'name' => $_SESSION['hotel_name'] ?? 'My Hotel'];
        }

        $_SESSION['hotel_id']   = $hotel['id'];
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
    // FINANCE MODULE
    // =============================================
    public function finance() {
        $revenue     = $this->bookingModel->getRevenueSummary($this->hotelId, 30);
        $dailyRev    = $this->bookingModel->getDailyRevenue($this->hotelId, 30);
        $bookings    = $this->bookingModel->allWithDetails(['hotel_id' => $this->hotelId, 'limit' => 50]);
        $unreadCount = $this->notifModel->unreadCount($_SESSION['user_id']);

        return $this->view('hotel/finance', [
            'title'        => 'Finance - ' . ($_SESSION['hotel_name'] ?? 'Hotel'),
            'revenue'      => $revenue,
            'dailyRevenue' => json_encode($dailyRev),
            'bookings'     => $bookings,
            'unreadCount'  => $unreadCount,
        ]);
    }

    // =============================================
    // GUESTS MODULE
    // =============================================
    public function guests() {
        $search = trim($_GET['search'] ?? '');
        try {
            $pdo = $this->bookingModel->getPdo();
            $where = "b.hotel_id = ?";
            $params = [$this->hotelId];
            if ($search) {
                $where .= " AND (b.guest_name LIKE ? OR b.guest_email LIKE ? OR b.guest_phone LIKE ?)";
                $s = '%'.$search.'%';
                $params = array_merge($params, [$s, $s, $s]);
            }
            $stmt = $pdo->prepare(
                "SELECT b.guest_name, b.guest_email, b.guest_phone,
                        COUNT(b.id)          AS stay_count,
                        SUM(b.total_amount)  AS total_spent,
                        MAX(b.check_in)      AS last_stay
                 FROM bookings b
                 WHERE {$where}
                 GROUP BY b.guest_email, b.guest_name, b.guest_phone
                 ORDER BY last_stay DESC
                 LIMIT 100"
            );
            $stmt->execute($params);
            $guests = $stmt->fetchAll();

            // Stats
            $stats = $pdo->prepare(
                "SELECT
                    SUM(CASE WHEN status='checked_in' THEN 1 ELSE 0 END)                          AS checked_in,
                    SUM(CASE WHEN MONTH(created_at)=MONTH(NOW()) AND YEAR(created_at)=YEAR(NOW()) THEN 1 ELSE 0 END) AS this_month,
                    SUM(CASE WHEN cnt > 1 THEN 1 ELSE 0 END) AS repeat_count
                 FROM (
                     SELECT guest_email, MAX(status) AS status, created_at, COUNT(*) AS cnt
                     FROM bookings WHERE hotel_id = ?
                     GROUP BY guest_email
                 ) x"
            );
            $stats->execute([$this->hotelId]);
            $guestStats = $stats->fetch();
            $guestStats['repeat'] = $guestStats['repeat_count'] ?? 0;
        } catch (\Exception $e) {
            $guests = [];
            $guestStats = ['checked_in'=>0,'this_month'=>0,'repeat'=>0];
        }
        $unreadCount = $this->notifModel->unreadCount($_SESSION['user_id']);

        return $this->view('hotel/guests', [
            'title'       => 'Guests - ' . ($_SESSION['hotel_name'] ?? 'Hotel'),
            'guests'      => $guests,
            'guestStats'  => $guestStats,
            'search'      => $search,
            'unreadCount' => $unreadCount,
        ]);
    }

    // =============================================
    // TRANSFERS MODULE
    // =============================================
    public function transfers() {
        $status = $_GET['status'] ?? 'all';
        try {
            $pdo = $this->bookingModel->getPdo();
            $where = "tr.hotel_id = ?";
            $params = [$this->hotelId];
            if ($status !== 'all') {
                $where .= " AND tr.status = ?";
                $params[] = $status;
            }
            $stmt = $pdo->prepare(
                "SELECT tr.*, b.booking_ref, b.guest_name,
                        fr.room_number AS from_room, tor.room_number AS to_room
                 FROM transfer_requests tr
                 LEFT JOIN bookings b ON b.id = tr.booking_id
                 LEFT JOIN rooms fr   ON fr.id = tr.from_room_id
                 LEFT JOIN rooms tor  ON tor.id = tr.to_room_id
                 WHERE {$where}
                 ORDER BY tr.created_at DESC"
            );
            $stmt->execute($params);
            $transfers = $stmt->fetchAll();

            // Pending count for badge
            $ps = $pdo->prepare("SELECT COUNT(*) FROM transfer_requests WHERE hotel_id = ? AND status='pending'");
            $ps->execute([$this->hotelId]);
            $pendingCount = (int) $ps->fetchColumn();
        } catch (\Exception $e) {
            $transfers    = [];
            $pendingCount = 0;
        }
        $unreadCount = $this->notifModel->unreadCount($_SESSION['user_id']);

        return $this->view('hotel/transfers', [
            'title'        => 'Transfers - ' . ($_SESSION['hotel_name'] ?? 'Hotel'),
            'transfers'    => $transfers,
            'activeFilter' => $status,
            'pendingCount' => $pendingCount,
            'unreadCount'  => $unreadCount,
        ]);
    }

    // =============================================
    // SETTINGS MODULE
    // =============================================
    public function settings() {
        $hotel = $this->hotelModel->find($this->hotelId);
        $unreadCount = $this->notifModel->unreadCount($_SESSION['user_id']);

        return $this->view('hotel/settings', [
            'title'      => 'Settings - ' . ($_SESSION['hotel_name'] ?? 'Hotel'),
            'hotel'      => $hotel,
            'unreadCount'=> $unreadCount,
        ]);
    }

    public function updateSettings() {
        CsrfMiddleware::verify();
        $action = $_POST['action'] ?? 'basic';

        if ($action === 'basic') {
            $data = [
                'name'        => trim(strip_tags($_POST['name']        ?? '')),
                'owner_name'  => trim(strip_tags($_POST['owner_name']  ?? '')),
                'email'       => trim(strip_tags($_POST['email']       ?? '')),
                'phone'       => trim(strip_tags($_POST['phone']       ?? '')),
                'description' => trim(strip_tags($_POST['description'] ?? '')),
                'city'        => trim(strip_tags($_POST['city']        ?? '')),
                'state'       => trim(strip_tags($_POST['state']       ?? '')),
                'pincode'     => trim(strip_tags($_POST['pincode']     ?? '')),
            ];
            if (empty($data['name'])) {
                $_SESSION['error'] = 'Hotel name cannot be empty.';
                $this->redirect('/hotel/settings');
            }
            $this->hotelModel->update($this->hotelId, $data);
            $_SESSION['hotel_name'] = $data['name'];
            $_SESSION['success']    = 'Hotel information updated.';
        }

        if ($action === 'banking') {
            $data = [
                'gst_number'  => trim(strip_tags($_POST['gst_number']  ?? '')),
                'pan_number'  => trim(strip_tags($_POST['pan_number']  ?? '')),
                'bank_name'   => trim(strip_tags($_POST['bank_name']   ?? '')),
                'bank_account'=> trim(strip_tags($_POST['bank_account']?? '')),
                'bank_ifsc'   => trim(strip_tags($_POST['bank_ifsc']   ?? '')),
            ];
            $this->hotelModel->update($this->hotelId, $data);
            $_SESSION['success'] = 'Banking details updated.';
        }

        $this->redirect('/hotel/settings');
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
