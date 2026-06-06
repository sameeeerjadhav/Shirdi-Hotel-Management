<?php
$ref = $booking['booking_ref'] ?? ('#' . $booking['id']);
$statusColors = [
    'confirmed'   => ['bg'=>'#fffbeb','text'=>'#92400e','border'=>'#f59e0b'],
    'checked_in'  => ['bg'=>'#ecfdf5','text'=>'#065f46','border'=>'#10b981'],
    'checked_out' => ['bg'=>'#eff6ff','text'=>'#1d4ed8','border'=>'#3b82f6'],
    'cancelled'   => ['bg'=>'#fef2f2','text'=>'#991b1b','border'=>'#ef4444'],
];
$sc = $statusColors[$booking['status']] ?? $statusColors['confirmed'];
?>
<div class="page-header">
    <div>
        <h2 style="font-family:monospace;">Booking <?= htmlspecialchars($ref) ?></h2>
        <p>
            <span style="background:<?= $sc['bg'] ?>;color:<?= $sc['text'] ?>;border:1px solid <?= $sc['border'] ?>;
                         padding:3px 12px;border-radius:99px;font-size:12px;font-weight:700;">
                <?= ucfirst(str_replace('_',' ',$booking['status'])) ?>
            </span>
            &nbsp;&bull;&nbsp;Created <?= date('d M Y, g:i A', strtotime($booking['created_at'])) ?>
        </p>
    </div>
    <a href="<?= BASE_URL ?>/admin/bookings" class="btn btn-light">
        <i class="fa-solid fa-arrow-left me-2"></i>All Bookings
    </a>
</div>

<div class="row g-4">
    <!-- LEFT: Details -->
    <div class="col-md-7">

        <!-- Guest Info -->
        <div class="card mb-4">
            <div class="card-header"><i class="fa-solid fa-user me-2" style="color:#4338ca;"></i>Guest Information</div>
            <div class="card-body" style="padding:0;">
                <table class="table mb-0">
                    <tbody>
                    <?php
                    $guestFields = [
                        'Full Name'    => htmlspecialchars($booking['guest_name']),
                        'Email'        => htmlspecialchars($booking['guest_email']),
                        'Phone'        => htmlspecialchars($booking['guest_phone']),
                        'ID Proof'     => htmlspecialchars(($booking['id_proof_type'] ?? '') . ': ' . ($booking['id_proof_number'] ?? 'N/A')),
                    ];
                    foreach ($guestFields as $label => $val): ?>
                    <tr>
                        <td style="font-size:12px;font-weight:700;color:#94a3b8;text-transform:uppercase;width:35%;padding:10px 16px;"><?= $label ?></td>
                        <td style="font-size:13px;font-weight:600;color:#1e293b;padding:10px 16px;"><?= $val ?: '—' ?></td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Stay Details -->
        <div class="card mb-4">
            <div class="card-header"><i class="fa-solid fa-bed me-2" style="color:#4338ca;"></i>Stay Details</div>
            <div class="card-body" style="padding:0;">
                <table class="table mb-0">
                    <tbody>
                    <?php
                    $checkIn  = date('D, d M Y', strtotime($booking['check_in_date']));
                    $checkOut = date('D, d M Y', strtotime($booking['check_out_date']));
                    $nights   = max(1, (int)(( strtotime($booking['check_out_date']) - strtotime($booking['check_in_date'])) / 86400));
                    $stayFields = [
                        'Hotel'        => htmlspecialchars($booking['hotel_name']),
                        'City'         => htmlspecialchars($booking['hotel_city']),
                        'Room'         => 'Room ' . htmlspecialchars($booking['room_number'] ?? 'N/A'),
                        'Room Type'    => htmlspecialchars($booking['room_type'] ?? 'N/A'),
                        'Check-In'     => $checkIn,
                        'Check-Out'    => $checkOut,
                        'Duration'     => $nights . ' Night' . ($nights > 1 ? 's' : ''),
                    ];
                    foreach ($stayFields as $label => $val): ?>
                    <tr>
                        <td style="font-size:12px;font-weight:700;color:#94a3b8;text-transform:uppercase;width:35%;padding:10px 16px;"><?= $label ?></td>
                        <td style="font-size:13px;font-weight:600;color:#1e293b;padding:10px 16px;"><?= $val ?></td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- RIGHT: Financials + Actions -->
    <div class="col-md-5">
        <!-- Billing -->
        <div class="card mb-4">
            <div class="card-header"><i class="fa-solid fa-receipt me-2" style="color:#4338ca;"></i>Billing Summary</div>
            <div class="card-body">
                <div style="display:flex;flex-direction:column;gap:12px;">
                    <div style="display:flex;justify-content:space-between;font-size:14px;">
                        <span style="color:#64748b;">Room Rate × <?= $nights ?> nights</span>
                        <span style="font-weight:700;">₹<?= number_format($booking['total_amount']) ?></span>
                    </div>
                    <hr style="border-color:#f1f5f9;margin:4px 0;">
                    <div style="display:flex;justify-content:space-between;font-size:16px;font-weight:800;">
                        <span>Total</span>
                        <span style="color:#4338ca;">₹<?= number_format($booking['total_amount']) ?></span>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:13px;">
                        <span style="color:#64748b;">Payment Status</span>
                        <span style="font-weight:700;color:<?= ($booking['payment_status']??'') === 'paid' ? '#10b981' : '#f59e0b' ?>;">
                            <?= ucfirst($booking['payment_status'] ?? 'Pending') ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="card">
            <div class="card-header">Actions</div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:10px;">
                <?php if (in_array($booking['status'], ['confirmed','checked_in'])): ?>
                <button onclick="cancelBooking(<?= $booking['id'] ?>)" class="btn btn-light" style="color:#ef4444;border-color:#fecaca;">
                    <i class="fa-solid fa-times me-2"></i>Cancel Booking
                </button>
                <?php endif; ?>
                <a href="<?= BASE_URL ?>/admin/bookings" class="btn btn-light text-center">Back to Bookings</a>
            </div>
        </div>
    </div>
</div>

<script>
const CSRF = '<?= \App\Middleware\CsrfMiddleware::generate() ?>';
async function cancelBooking(id) {
    if (!confirm('Cancel this booking?')) return;
    const res = await fetch(`<?= BASE_URL ?>/admin/bookings/${id}/cancel`, {method:'POST', headers:{'X-CSRF-Token':CSRF}});
    const data= await res.json();
    if (data.success) { showToast('Booking cancelled.','warning'); setTimeout(()=>location.reload(),1000); }
}
</script>
