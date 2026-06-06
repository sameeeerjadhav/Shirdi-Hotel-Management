<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\Booking;
use App\Models\Notification;
use App\Models\Transfer;
use App\Models\AuditLog;
use App\Models\User;
use App\Middleware\CsrfMiddleware;
use App\Utils\Sanitizer;
use App\Services\NotificationService;

class AdminController extends Controller {

    private Hotel $hotelModel;
    private Room $roomModel;
    private Booking $bookingModel;
    private Notification $notifModel;
    private Transfer $transferModel;

    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
            $this->redirect('/login');
        }
        $this->hotelModel    = new Hotel();
        $this->roomModel     = new Room();
        $this->bookingModel  = new Booking();
        $this->notifModel    = new Notification();
        $this->transferModel = new Transfer();
    }

    // =============================================
    // DASHBOARD
    // =============================================
    public function dashboard() {
        try {
            $stats        = $this->hotelModel->getNetworkStats();
            $recentHotels = $this->hotelModel->allWithAdmin('pending');
            $recentBooks  = $this->bookingModel->allWithDetails(['limit' => 5]);
            $revenue      = $this->bookingModel->getRevenueSummary(null, 30);
            $dailyRevenue = $this->bookingModel->getDailyRevenue(null, 30);
        } catch (\Exception $e) {
            // Tables may not have new columns yet — use safe defaults
            $stats        = ['total_hotels'=>0,'active_hotels'=>0,'pending_hotels'=>0,'total_rooms'=>0,'occupied_rooms'=>0,'available_rooms'=>0,'total_revenue'=>0,'platform_revenue'=>0];
            $recentHotels = [];
            $recentBooks  = [];
            $revenue      = ['total_revenue'=>0,'collected'=>0,'pending'=>0,'platform_fees'=>0,'total_bookings'=>0,'confirmed'=>0,'checked_in'=>0,'completed'=>0,'cancelled'=>0];
            $dailyRevenue = [];
        }

        try {
            $pendingTransfers = $this->transferModel->countPending();
            $unreadCount      = $this->notifModel->unreadCount($_SESSION['user_id']);
        } catch (\Exception $e) {
            $pendingTransfers = 0;
            $unreadCount      = 0;
        }

        return $this->view('admin/dashboard', [
            'title'            => 'Admin Dashboard - CHNMS',
            'stats'            => $stats,
            'recentHotels'     => $recentHotels,
            'recentBooks'      => $recentBooks,
            'revenue'          => $revenue,
            'dailyRevenue'     => json_encode($dailyRevenue),
            'pendingTransfers' => $pendingTransfers,
            'unreadCount'      => $unreadCount,
        ]);
    }

    // =============================================
    // HOTELS MODULE
    // =============================================
    public function hotels() {
        $status = Sanitizer::clean($_GET['status'] ?? '');
        $search = Sanitizer::clean($_GET['search'] ?? '');

        try {
            if ($search) {
                $hotels = $this->hotelModel->search($search, $status ?: null);
            } else {
                $hotels = $this->hotelModel->allWithAdmin($status ?: null);
            }
        } catch (\Exception $e) {
            $hotels = [];
        }

        $unreadCount = $this->notifModel->unreadCount($_SESSION['user_id']);

        return $this->view('admin/hotels', [
            'title'       => 'Hotel Management - CHNMS',
            'hotels'      => $hotels,
            'status'      => $status,
            'search'      => $search,
            'unreadCount' => $unreadCount,
        ]);
    }

    public function hotelDetail($id) {
        $hotel   = $this->hotelModel->getWithDetails($id);
        if (!$hotel) { $_SESSION['error'] = 'Hotel not found.'; $this->redirect('/admin/hotels'); }
        $stats   = $this->hotelModel->getHotelStats($id);
        $rooms   = $this->roomModel->byHotel($id);
        $bookings= $this->bookingModel->allWithDetails(['hotel_id' => $id, 'limit' => 10]);

        return $this->view('admin/hotel_detail', [
            'title'   => $hotel['name'] . ' - CHNMS',
            'hotel'   => $hotel,
            'stats'   => $stats,
            'rooms'   => $rooms,
            'bookings'=> $bookings,
        ]);
    }

    public function addHotelForm() {
        return $this->view('admin/hotel_form', [
            'title'  => 'Add Hotel - CHNMS',
            'hotel'  => null,
            'action' => 'add',
        ]);
    }

    public function storeHotel() {
        CsrfMiddleware::verify();

        $data   = Sanitizer::cleanPost(['name','email','phone','owner_name','address','city','state','zip','gst_number','pan_number','bank_name','bank_account','bank_ifsc','star_rating','commission_rate','description']);
        $errors = Sanitizer::validate($data, ['name'=>'required','email'=>'required|email','city'=>'required']);

        // Create hotel admin user
        $userEmail    = $data['email'];
        $userModel    = new User();
        $existingUser = $userModel->findByEmail($userEmail);

        if ($existingUser) {
            $adminUserId = $existingUser['id'];
        } else {
            $tempPass    = bin2hex(random_bytes(6));
            $adminUserId = $userModel->create([
                'role_id'       => 2,
                'name'          => $data['owner_name'] ?: $data['name'],
                'email'         => $userEmail,
                'password_hash' => password_hash($tempPass, PASSWORD_DEFAULT),
                'phone'         => $data['phone'],
            ]);
        }

        $data['admin_user_id'] = $adminUserId;
        $data['status']        = 'pending';
        $hotelId               = $this->hotelModel->create($data);

        // Update user's hotel_id
        $userModel->update($adminUserId, ['hotel_id' => $hotelId]);

        AuditLog::record('hotel_created', 'Hotel', $hotelId, null, $data);
        $_SESSION['success'] = 'Hotel added successfully and is pending approval.';
        $this->redirect('/admin/hotels');
    }

    public function editHotelForm($id) {
        $hotel = $this->hotelModel->getWithDetails($id);
        if (!$hotel) { $_SESSION['error'] = 'Hotel not found.'; $this->redirect('/admin/hotels'); }
        return $this->view('admin/hotel_form', [
            'title'  => 'Edit Hotel - CHNMS',
            'hotel'  => $hotel,
            'action' => 'edit',
        ]);
    }

    public function updateHotel($id) {
        CsrfMiddleware::verify();
        $old  = $this->hotelModel->find($id);
        $data = Sanitizer::cleanPost(['name','email','phone','owner_name','address','city','state','zip','gst_number','pan_number','bank_name','bank_account','bank_ifsc','star_rating','commission_rate','description']);
        $this->hotelModel->update($id, $data);
        AuditLog::record('hotel_updated', 'Hotel', $id, $old, $data);
        $_SESSION['success'] = 'Hotel updated successfully.';
        $this->redirect('/admin/hotels/' . $id);
    }

    public function approveHotel($id) {
        CsrfMiddleware::verify();
        $hotel = $this->hotelModel->getWithDetails($id);
        $this->hotelModel->approve($id);
        try {
            $notif = new NotificationService();
            $notif->hotelApproved($hotel, $hotel['admin_user_id']);
            AuditLog::record('hotel_approved', 'Hotel', $id);
        } catch (\Exception $e) {}
        $this->json(['success' => true, 'message' => 'Hotel approved.']);
    }

    public function rejectHotel($id) {
        CsrfMiddleware::verify();
        $this->hotelModel->reject($id);
        try { AuditLog::record('hotel_rejected', 'Hotel', $id); } catch (\Exception $e) {}
        $this->json(['success' => true, 'message' => 'Hotel rejected.']);
    }

    public function suspendHotel($id) {
        CsrfMiddleware::verify();
        $this->hotelModel->suspend($id);
        try { AuditLog::record('hotel_suspended', 'Hotel', $id); } catch (\Exception $e) {}
        $this->json(['success' => true, 'message' => 'Hotel suspended.']);
    }

    public function deleteHotel($id) {
        CsrfMiddleware::verify();
        $this->hotelModel->delete($id);
        try { AuditLog::record('hotel_deleted', 'Hotel', $id); } catch (\Exception $e) {}
        $_SESSION['success'] = 'Hotel deleted.';
        $this->redirect('/admin/hotels');
    }

    // =============================================
    // BOOKINGS MODULE
    // =============================================
    public function bookings() {
        $filters = [
            'status'    => Sanitizer::clean($_GET['status'] ?? ''),
            'search'    => Sanitizer::clean($_GET['search'] ?? ''),
            'date_from' => Sanitizer::date($_GET['date_from'] ?? '') ?: null,
            'date_to'   => Sanitizer::date($_GET['date_to'] ?? '') ?: null,
            'limit'     => 25,
            'offset'    => (max(1, (int)($_GET['page'] ?? 1)) - 1) * 25,
        ];

        try { $bookings = $this->bookingModel->allWithDetails($filters); } catch (\Exception $e) { $bookings = []; }
        try { $revenue  = $this->bookingModel->getRevenueSummary(); }     catch (\Exception $e) { $revenue  = ['total_revenue'=>0,'collected'=>0,'total_bookings'=>0,'confirmed'=>0,'checked_in'=>0,'completed'=>0,'cancelled'=>0]; }
        $unreadCount = $this->notifModel->unreadCount($_SESSION['user_id']);

        return $this->view('admin/bookings', [
            'title'      => 'Bookings - CHNMS',
            'bookings'   => $bookings,
            'revenue'    => $revenue,
            'filters'    => $filters,
            'unreadCount'=> $unreadCount,
        ]);
    }

    public function bookingDetail($id) {
        try { $booking = $this->bookingModel->getWithDetails($id); } catch (\Exception $e) { $booking = null; }
        if (!$booking) { $_SESSION['error'] = 'Booking not found.'; $this->redirect('/admin/bookings'); }
        $ref = $booking['booking_ref'] ?? ('#' . $booking['id']);
        return $this->view('admin/booking_detail', [
            'title'   => 'Booking ' . $ref . ' - CHNMS',
            'booking' => $booking,
        ]);
    }

    public function cancelBooking($id) {
        CsrfMiddleware::verify();
        $this->bookingModel->cancel($id);
        try { AuditLog::record('booking_cancelled', 'Booking', $id); } catch (\Exception $e) {}
        $this->json(['success' => true]);
    }

    // =============================================
    // FINANCE MODULE
    // =============================================
    public function finance() {
        try { $revenue      = $this->bookingModel->getRevenueSummary(null, 30); } catch (\Exception $e) { $revenue = ['total_revenue'=>0,'collected'=>0,'platform_fees'=>0,'pending'=>0,'total_bookings'=>0,'confirmed'=>0,'checked_in'=>0,'completed'=>0,'cancelled'=>0]; }
        try { $dailyRevenue = $this->bookingModel->getDailyRevenue(null, 30); }  catch (\Exception $e) { $dailyRevenue = []; }
        try { $bookings     = $this->bookingModel->allWithDetails(['limit' => 15]); } catch (\Exception $e) { $bookings = []; }
        $unreadCount = $this->notifModel->unreadCount($_SESSION['user_id']);

        return $this->view('admin/finance', [
            'title'        => 'Finance - CHNMS',
            'revenue'      => $revenue,
            'dailyRevenue' => json_encode($dailyRevenue),
            'bookings'     => $bookings,
            'unreadCount'  => $unreadCount,
        ]);
    }

    // =============================================
    // ROOM MONITOR
    // =============================================
    public function roomMonitor() {
        $hotels   = $this->hotelModel->allWithAdmin('approved');
        $selected = Sanitizer::int($_GET['hotel_id'] ?? 0);
        $statusF  = Sanitizer::clean($_GET['status'] ?? '');
        $rooms    = [];
        $hotelId  = $selected ?: ($hotels[0]['id'] ?? null);

        if ($hotelId) {
            $rooms = $this->roomModel->byHotel($hotelId, $statusF ?: null);
            // Attach current booking info to occupied rooms
            foreach ($rooms as &$room) {
                if ($room['status'] === 'occupied') {
                    $room['current_booking'] = $this->bookingModel->queryOne(
                        "SELECT b.*, CONCAT(g.first_name,' ',g.last_name) as guest_name, g.phone as guest_phone
                         FROM bookings b JOIN guests g ON g.id=b.guest_id
                         WHERE b.room_id=? AND b.status='checked_in' LIMIT 1",
                        [$room['id']]
                    );
                }
            }
        }

        return $this->view('admin/room_monitor', [
            'title'    => 'Room Monitor - CHNMS',
            'hotels'   => $hotels,
            'rooms'    => $rooms,
            'hotelId'  => $hotelId,
            'statusF'  => $statusF,
        ]);
    }

    // =============================================
    // TRANSFERS
    // =============================================
    public function transfers() {
        $status = Sanitizer::clean($_GET['status'] ?? 'pending');

        try {
            $transfers = $this->transferModel->allWithDetails($status ?: null);
            $counts    = [
                'pending'  => $this->transferModel->count("status = 'pending'"),
                'accepted' => $this->transferModel->count("status = 'accepted'"),
                'rejected' => $this->transferModel->count("status = 'rejected'"),
            ];
        } catch (\Exception $e) {
            $transfers = [];
            $counts    = ['pending' => 0, 'accepted' => 0, 'rejected' => 0];
        }

        return $this->view('admin/transfers', [
            'title'     => 'Transfer Center - CHNMS',
            'transfers' => $transfers,
            'status'    => $status,
            'counts'    => $counts,
        ]);
    }

    public function approveTransfer($id) {
        CsrfMiddleware::verify();
        $notes = Sanitizer::clean($_POST['notes'] ?? '');
        $this->transferModel->approve($id, $notes);
        AuditLog::record('transfer_approved', 'Transfer', $id);
        $this->json(['success' => true]);
    }

    public function rejectTransfer($id) {
        CsrfMiddleware::verify();
        $notes = Sanitizer::clean($_POST['notes'] ?? '');
        $this->transferModel->reject($id, $notes);
        AuditLog::record('transfer_rejected', 'Transfer', $id);
        $this->json(['success' => true]);
    }

    // =============================================
    // SEARCH
    // =============================================
    public function search() {
        return $this->view('admin/search', ['title' => 'Room Search - CHNMS']);
    }
}
