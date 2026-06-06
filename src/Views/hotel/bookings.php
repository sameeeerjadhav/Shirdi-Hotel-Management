<?php
$statusBadge = [
    'confirmed'   => 'badge-pending',
    'checked_in'  => 'badge-active',
    'checked_out' => 'badge-cleaning',
    'cancelled'   => 'badge-occupied',
    'transferred' => 'badge-cleaning',
];
?>
<div class="page-header">
    <div>
        <h2>Hotel Bookings</h2>
        <p>Manage all reservations for <?= htmlspecialchars($_SESSION['hotel_name'] ?? 'your hotel') ?></p>
    </div>
</div>

<!-- STATUS FILTER TABS -->
<div style="display:flex;gap:8px;margin-bottom:20px;flex-wrap:wrap;">
    <?php foreach (['' => 'All', 'confirmed'=>'Confirmed', 'checked_in'=>'Checked In', 'checked_out'=>'Completed', 'cancelled'=>'Cancelled'] as $val => $label): ?>
    <a href="<?= BASE_URL ?>/hotel/bookings?status=<?= $val ?>"
       style="padding:6px 16px;border-radius:99px;font-size:13px;font-weight:600;text-decoration:none;
              background:<?= ($_GET['status']??'')===$val ? '#4338ca' : '#f1f5f9' ?>;
              color:<?= ($_GET['status']??'')===$val ? 'white' : '#64748b' ?>;">
        <?= $label ?>
    </a>
    <?php endforeach; ?>
</div>

<!-- BOOKINGS TABLE -->
<div class="card">
    <div class="card-header">
        <span>Reservation List</span>
    </div>
    <div class="card-body" style="padding:0;">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Booking Ref</th>
                    <th>Guest</th>
                    <th>Room</th>
                    <th>Dates</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($bookings)): ?>
                <tr>
                    <td colspan="7" style="text-align:center;padding:48px;color:#94a3b8;">
                        <div style="width:56px;height:56px;background:#eef2ff;border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                            <i class="fa-solid fa-calendar-check" style="font-size:22px;color:#6366f1;"></i>
                        </div>
                        No bookings found.
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($bookings as $b):
                    $ref = $b['booking_ref'] ?? ('#' . $b['id']);
                    $bc  = $statusBadge[$b['status']] ?? 'badge-pending';
                    $nights = max(1, (int)((strtotime($b['check_out_date']) - strtotime($b['check_in_date'])) / 86400));
                ?>
                <tr>
                    <td>
                        <span style="font-family:monospace;font-size:13px;font-weight:700;color:#4338ca;">
                            <?= htmlspecialchars($ref) ?>
                        </span>
                    </td>
                    <td>
                        <div class="identity-cell">
                            <div class="identity-avatar icon-indigo"><?= strtoupper(substr($b['guest_name'], 0, 1)) ?></div>
                            <div>
                                <div class="identity-name"><?= htmlspecialchars($b['guest_name']) ?></div>
                                <div class="identity-sub"><?= htmlspecialchars($b['guest_phone']) ?></div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div style="font-size:13px;font-weight:700;color:#1e293b;">Room <?= htmlspecialchars($b['room_number'] ?? '—') ?></div>
                        <div style="font-size:11px;color:#94a3b8;"><?= htmlspecialchars($b['room_type'] ?? '') ?></div>
                    </td>
                    <td style="font-size:12px;">
                        <?= date('d M Y', strtotime($b['check_in_date'])) ?><br>
                        <span style="color:#94a3b8;">→ <?= date('d M Y', strtotime($b['check_out_date'])) ?></span>
                        <div style="font-size:11px;color:#4338ca;font-weight:600;"><?= $nights ?> night<?= $nights>1?'s':'' ?></div>
                    </td>
                    <td style="font-weight:700;color:#10b981;">₹<?= number_format($b['total_amount']) ?></td>
                    <td><span class="badge-pill <?= $bc ?>"><span class="dot"></span><?= ucfirst(str_replace('_',' ',$b['status'])) ?></span></td>
                    <td>
                        <?php if ($b['status'] === 'confirmed'): ?>
                        <button onclick="checkIn(<?= $b['id'] ?>)" class="btn btn-primary" style="font-size:12px;padding:4px 10px;">
                            <i class="fa-solid fa-key me-1"></i>Check In
                        </button>
                        <?php elseif ($b['status'] === 'checked_in'): ?>
                        <button onclick="checkOut(<?= $b['id'] ?>)" class="btn btn-light" style="font-size:12px;padding:4px 10px;color:#8b5cf6;border-color:#c4b5fd;">
                            <i class="fa-solid fa-door-open me-1"></i>Check Out
                        </button>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
const CSRF = '<?= \App\Middleware\CsrfMiddleware::generate() ?>';

async function checkIn(id) {
    if (!confirm('Confirm check-in for this booking?')) return;
    const fd = new FormData(); fd.append('_csrf', CSRF);
    const res = await fetch(`<?= BASE_URL ?>/hotel/bookings/${id}/checkin`, {method:'POST', body:fd});
    const data= await res.json();
    if (data.success) { showToast('Guest checked in!','success'); setTimeout(()=>location.reload(),800); }
    else showToast(data.message||'Error','error');
}

async function checkOut(id) {
    if (!confirm('Confirm check-out for this booking?')) return;
    const fd = new FormData(); fd.append('_csrf', CSRF);
    const res = await fetch(`<?= BASE_URL ?>/hotel/bookings/${id}/checkout`, {method:'POST', body:fd});
    const data= await res.json();
    if (data.success) { showToast('Guest checked out successfully!','success'); setTimeout(()=>location.reload(),800); }
    else showToast(data.message||'Error','error');
}
</script>
