<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\Booking;
use App\Models\Guest;
use App\Models\Payment;
use App\Models\Notification;
use App\Models\AuditLog;
use App\Services\RazorpayService;
use App\Services\TransferEngine;
use App\Services\NotificationService;
use App\Middleware\CsrfMiddleware;
use App\Utils\Sanitizer;

class BookingController extends Controller {

    private Hotel $hotelModel;
    private Room $roomModel;
    private Booking $bookingModel;
    private Guest $guestModel;
    private Payment $paymentModel;

    public function __construct() {
        $this->hotelModel   = new Hotel();
        $this->roomModel    = new Room();
        $this->bookingModel = new Booking();
        $this->guestModel   = new Guest();
        $this->paymentModel = new Payment();
    }

    // =============================================
    // SEARCH (public page)
    // =============================================
    public function search() {
        $city      = Sanitizer::clean($_GET['city'] ?? '');
        $checkIn   = Sanitizer::date($_GET['check_in'] ?? '') ?: date('Y-m-d');
        $checkOut  = Sanitizer::date($_GET['check_out'] ?? '') ?: date('Y-m-d', strtotime('+1 day'));
        $guests    = Sanitizer::int($_GET['guests'] ?? 1) ?: 1;
        $roomType  = Sanitizer::clean($_GET['room_type'] ?? '');
        $results   = [];

        if ($city || $checkIn) {
            $rooms = $this->roomModel->searchAvailable($checkIn, $checkOut, $guests, $city ?: null, $roomType ?: null);

            // Group rooms by hotel
            $byHotel = [];
            foreach ($rooms as $room) {
                $byHotel[$room['hotel_id']]['hotel']  = [
                    'id'         => $room['hotel_id'],
                    'name'       => $room['hotel_name'],
                    'city'       => $room['city'],
                    'star_rating'=> $room['star_rating'],
                    'cover_image'=> $room['cover_image'],
                ];
                $byHotel[$room['hotel_id']]['rooms'][] = $room;
            }

            foreach ($byHotel as $hotelId => $entry) {
                $minPrice = min(array_column($entry['rooms'], 'price'));
                $results[] = [
                    'hotel'       => $entry['hotel'],
                    'rooms'       => $entry['rooms'],
                    'min_price'   => $minPrice,
                    'room_count'  => count($entry['rooms']),
                ];
            }
        }

        return $this->view('guest/search', [
            'title'    => 'Search Rooms - CHNMS',
            'results'  => $results,
            'city'     => $city,
            'checkIn'  => $checkIn,
            'checkOut' => $checkOut,
            'guests'   => $guests,
            'roomType' => $roomType,
        ]);
    }

    // =============================================
    // CHECKOUT FORM
    // =============================================
    public function checkoutForm($hotelId = null, $roomId = null) {
        $hotelId  = Sanitizer::int($_GET['hotel_id'] ?? $hotelId ?? 0);
        $roomId   = Sanitizer::int($_GET['room_id'] ?? $roomId ?? 0);
        $checkIn  = Sanitizer::date($_GET['check_in'] ?? '') ?: date('Y-m-d');
        $checkOut = Sanitizer::date($_GET['check_out'] ?? '') ?: date('Y-m-d', strtotime('+1 day'));
        $guests   = Sanitizer::int($_GET['guests'] ?? 1) ?: 1;

        $room  = $this->roomModel->getWithDetails($roomId);
        $hotel = $this->hotelModel->getWithDetails($hotelId);

        if (!$room || !$hotel) {
            $_SESSION['error'] = 'Room not found. Please search again.';
            $this->redirect('/search');
        }

        $nights     = max(1, (int) ((strtotime($checkOut) - strtotime($checkIn)) / 86400));
        $price      = $room['price_override'] ?? $room['base_price'];
        $subtotal   = $price * $nights;
        $platformFee= round($subtotal * ($hotel['commission_rate'] / 100), 2);
        $total      = $subtotal + $platformFee;

        $razorpay = new RazorpayService();

        return $this->view('guest/checkout', [
            'title'       => 'Checkout - CHNMS',
            'room'        => $room,
            'hotel'       => $hotel,
            'checkIn'     => $checkIn,
            'checkOut'    => $checkOut,
            'guests'      => $guests,
            'nights'      => $nights,
            'price'       => $price,
            'subtotal'    => $subtotal,
            'platformFee' => $platformFee,
            'total'       => $total,
            'razorpayKey' => $razorpay->getKeyId(),
        ]);
    }

    // =============================================
    // PROCESS CHECKOUT — Create Order
    // =============================================
    public function createOrder() {
        CsrfMiddleware::verify();

        $data = Sanitizer::cleanPost(['hotel_id','room_id','check_in','check_out','guests','first_name','last_name','email','phone','id_proof_type','id_proof_number']);
        $errors = Sanitizer::validate($data, [
            'first_name' => 'required',
            'last_name'  => 'required',
            'email'      => 'required|email',
            'phone'      => 'required',
            'hotel_id'   => 'required',
            'room_id'    => 'required',
        ]);

        if ($errors) { $this->json(['success' => false, 'errors' => $errors], 422); }

        $hotelId  = (int) $data['hotel_id'];
        $roomId   = (int) $data['room_id'];
        $checkIn  = Sanitizer::date($data['check_in']);
        $checkOut = Sanitizer::date($data['check_out']);
        $guests   = max(1, (int) $data['guests']);

        $room     = $this->roomModel->getWithDetails($roomId);
        $hotel    = $this->hotelModel->find($hotelId);

        if (!$room || !$hotel) { $this->json(['success' => false, 'message' => 'Invalid room or hotel.'], 400); }

        $nights      = max(1, (int) ((strtotime($checkOut) - strtotime($checkIn)) / 86400));
        $price       = $room['price_override'] ?? $room['base_price'];
        $subtotal    = $price * $nights;
        $platformFee = round($subtotal * ($hotel['commission_rate'] / 100), 2);
        $total       = $subtotal + $platformFee;

        // Create Razorpay order
        $bookingRef = $this->bookingModel->generateRef();
        $razorpay   = new RazorpayService();
        $order      = $razorpay->createOrder($total, $bookingRef, [
            'hotel'    => $hotel['name'],
            'room'     => $room['room_number'],
            'check_in' => $checkIn,
        ]);

        if (!$order) { $this->json(['success' => false, 'message' => 'Payment gateway error. Try again.'], 500); }

        // Save guest
        $guestId = $this->guestModel->firstOrCreate([
            'first_name'      => $data['first_name'],
            'last_name'       => $data['last_name'],
            'email'           => $data['email'],
            'phone'           => $data['phone'],
            'id_proof_type'   => $data['id_proof_type'],
            'id_proof_number' => $data['id_proof_number'],
        ]);

        // Create booking
        $bookingId = $this->bookingModel->create([
            'booking_ref'      => $bookingRef,
            'hotel_id'         => $hotelId,
            'room_id'          => $roomId,
            'guest_id'         => $guestId,
            'check_in_date'    => $checkIn,
            'check_out_date'   => $checkOut,
            'num_guests'       => $guests,
            'room_rate'        => $price,
            'total_amount'     => $total,
            'platform_fee'     => $platformFee,
            'status'           => 'confirmed',
            'payment_status'   => 'pending',
            'razorpay_order_id'=> $order['id'],
        ]);

        // Reserve room
        $this->roomModel->changeStatus($roomId, 'reserved');

        $this->json([
            'success'       => true,
            'order_id'      => $order['id'],
            'booking_id'    => $bookingId,
            'booking_ref'   => $bookingRef,
            'amount'        => $order['amount'],
            'currency'      => 'INR',
        ]);
    }

    // =============================================
    // VERIFY PAYMENT (Razorpay callback)
    // =============================================
    public function verifyPayment() {
        CsrfMiddleware::verify();

        $razorpayOrderId   = Sanitizer::clean($_POST['razorpay_order_id']   ?? '');
        $razorpayPaymentId = Sanitizer::clean($_POST['razorpay_payment_id'] ?? '');
        $razorpaySignature = $_POST['razorpay_signature'] ?? '';
        $bookingId         = Sanitizer::int($_POST['booking_id'] ?? 0);

        $razorpay = new RazorpayService();

        if (!$razorpay->verifySignature($razorpayOrderId, $razorpayPaymentId, $razorpaySignature)) {
            $this->json(['success' => false, 'message' => 'Payment verification failed. Contact support.'], 400);
        }

        // Update booking payment
        $booking = $this->bookingModel->find($bookingId);
        $this->bookingModel->update($bookingId, [
            'payment_status'     => 'paid',
            'paid_amount'        => $booking['total_amount'],
            'razorpay_payment_id'=> $razorpayPaymentId,
        ]);

        // Record payment
        $this->paymentModel->create([
            'booking_id'          => $bookingId,
            'amount'              => $booking['total_amount'],
            'payment_method'      => 'razorpay',
            'razorpay_payment_id' => $razorpayPaymentId,
            'razorpay_order_id'   => $razorpayOrderId,
            'status'              => 'captured',
            'paid_at'             => date('Y-m-d H:i:s'),
        ]);

        // Notify hotel admin
        $bookingDetails = $this->bookingModel->getWithDetails($bookingId);
        $hotel          = $this->hotelModel->find($bookingDetails['hotel_id']);
        $notif          = new NotificationService();
        $notif->bookingCreated($bookingDetails, $hotel['admin_user_id']);

        AuditLog::record('payment_received', 'Booking', $bookingId);

        $this->json([
            'success'     => true,
            'booking_ref' => $bookingDetails['booking_ref'],
            'redirect'    => BASE_URL . '/booking/confirmation/' . $bookingDetails['booking_ref'],
        ]);
    }

    // =============================================
    // BOOKING CONFIRMATION PAGE
    // =============================================
    public function confirmation($ref) {
        $booking = $this->bookingModel->findByRef($ref);
        if (!$booking) {
            $_SESSION['error'] = 'Booking not found.';
            $this->redirect('/');
        }
        return $this->view('guest/booking_confirmation', [
            'title'   => 'Booking Confirmed! - CHNMS',
            'booking' => $booking,
        ]);
    }

    // =============================================
    // TRANSFER SUGGESTIONS (AJAX)
    // =============================================
    public function transferSuggestions() {
        $hotelId  = Sanitizer::int($_GET['hotel_id']  ?? 0);
        $checkIn  = Sanitizer::date($_GET['check_in'] ?? '') ?: date('Y-m-d');
        $checkOut = Sanitizer::date($_GET['check_out']?? '') ?: date('Y-m-d', strtotime('+1 day'));
        $guests   = Sanitizer::int($_GET['guests']    ?? 1) ?: 1;
        $roomType = Sanitizer::clean($_GET['room_type']?? '');

        $engine       = new TransferEngine();
        $alternatives = $engine->findAlternatives($hotelId, $checkIn, $checkOut, $guests, $roomType ?: null);

        $this->json(['success' => true, 'alternatives' => $alternatives]);
    }
}
