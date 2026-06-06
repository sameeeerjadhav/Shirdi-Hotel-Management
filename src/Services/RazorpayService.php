<?php

namespace App\Services;

class RazorpayService {

    private string $keyId;
    private string $keySecret;
    private string $baseUrl = 'https://api.razorpay.com/v1/';

    public function __construct() {
        // Store in config/database.php or environment
        $this->keyId     = defined('RAZORPAY_KEY_ID')     ? RAZORPAY_KEY_ID     : 'rzp_test_XXXXXXXXXXXX';
        $this->keySecret = defined('RAZORPAY_KEY_SECRET')  ? RAZORPAY_KEY_SECRET : 'XXXXXXXXXXXXXXXXXXXX';
    }

    /**
     * Create a Razorpay order
     * @param float $amount Amount in Rupees (will be converted to paise)
     * @param string $receipt Booking reference
     * @return array|null
     */
    public function createOrder(float $amount, string $receipt, array $notes = []) {
        $payload = json_encode([
            'amount'   => (int) ($amount * 100), // in paise
            'currency' => 'INR',
            'receipt'  => $receipt,
            'notes'    => $notes,
        ]);

        $response = $this->request('POST', 'orders', $payload);
        return $response;
    }

    /**
     * Verify payment signature from Razorpay callback
     */
    public function verifySignature(string $orderId, string $paymentId, string $signature): bool {
        $data     = $orderId . '|' . $paymentId;
        $expected = hash_hmac('sha256', $data, $this->keySecret);
        return hash_equals($expected, $signature);
    }

    /**
     * Fetch payment details from Razorpay
     */
    public function fetchPayment(string $paymentId) {
        return $this->request('GET', "payments/{$paymentId}");
    }

    /**
     * Initiate a refund
     */
    public function refund(string $paymentId, float $amount = null) {
        $payload = $amount ? json_encode(['amount' => (int)($amount * 100)]) : '{}';
        return $this->request('POST', "payments/{$paymentId}/refund", $payload);
    }

    private function request(string $method, string $endpoint, string $payload = '') {
        $ch = curl_init($this->baseUrl . $endpoint);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERPWD        => "{$this->keyId}:{$this->keySecret}",
            CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
            CURLOPT_CUSTOMREQUEST  => $method,
        ]);
        if ($method === 'POST' && $payload) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        }
        $response = curl_exec($ch);
        $error    = curl_error($ch);
        curl_close($ch);
        if ($error) return null;
        return json_decode($response, true);
    }

    public function getKeyId(): string {
        return $this->keyId;
    }
}
