<?php
$bookingStatusBadge = [
    'confirmed'   => 'badge-pending',
    'checked_in'  => 'badge-active',
    'checked_out' => 'badge-cleaning',
    'cancelled'   => 'badge-occupied',
    'transferred' => 'badge-cleaning',
    'no_show'     => 'badge-occupied',
];
$r = $revenue ?? [];
?>
<div class="page-header">
    <div>
        <h2>Bookings</h2>
        <p>Manage all network bookings</p>
    </div>
</div>

<!-- REVENUE SUMMARY -->
<div class="row g-3 mb-4">
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-icon icon-indigo"><i class="fa-solid fa-calendar-check"></i></div>
            <div class="stat-card-content">
                <div class="stat-label">TOTAL BOOKINGS</div>
                <div class="stat-value"><?= $r['total_bookings'] ?? 0 ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-icon icon-green"><i class="fa-solid fa-indian-rupee-sign"></i></div>
            <div class="stat-card-content">
                <div class="stat-label">REVENUE</div>
                <div class="stat-value" style="font-size:18px;">₹<?= number_format($r['total_revenue'] ?? 0) ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-icon icon-amber"><i class="fa-solid fa-clock"></i></div>
            <div class="stat-card-content">
                <div class="stat-label">CONFIRMED</div>
                <div class="stat-value"><?= $r['confirmed'] ?? 0 ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-icon icon-cyan"><i class="fa-solid fa-bed"></i></div>
            <div class="stat-card-content">
                <div class="stat-label">CHECKED IN</div>
                <div class="stat-value"><?= $r['checked_in'] ?? 0 ?></div>
            </div>
        </div>
    </div>
</div>

<!-- FILTERS -->
<div class="card mb-4" style="padding:16px 20px;">
    <form method="GET" class="d-flex gap-3 flex-wrap align-items-center">
        <div style="flex:1;min-width:200px;position:relative;">
            <i class="fa-solid fa-magnifying-glass" style="position:absolute;left:12px;top:11px;color:#94a3b8;font-size:13px;"></i>
            <input type="text" name="search" class="form-control" placeholder="Search guest name, phone..."
                   value="<?= htmlspecialchars($filters['search'] ?? '') ?>" style="padding-left:36px;">
        </div>
        <div>
            <select name="status" class="form-select" style="width:160px;">
                <option value="">All Status</option>
                <?php foreach (['confirmed','checked_in','checked_out','cancelled'] as $st): ?>
                <option value="<?= $st ?>" <?= ($filters['status']??'')===$st?'selected':'' ?>><?= ucfirst(str_replace('_',' ',$st)) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <input type="date" name="date_from" class="form-control" style="width:160px;" value="<?= $filters['date_from'] ?? '' ?>">
        <input type="date" name="date_to"   class="form-control" style="width:160px;" value="<?= $filters['date_to']   ?? '' ?>">
        <button type="submit" class="btn btn-primary" style="padding:9px 20px;">Filter</button>
        <a href="<?= BASE_URL ?>/admin/bookings" class="btn btn-light">Reset</a>
    </form>
</div>

<!-- STATUS TABS -->
<div style="display:flex;gap:8px;margin-bottom:20px;flex-wrap:wrap;">
    <?php foreach (['' => 'All', 'confirmed'=>'Confirmed', 'checked_in'=>'Checked In', 'checked_out'=>'Completed', 'cancelled'=>'Cancelled'] as $val => $label): ?>
    <a href="<?= BASE_URL ?>/admin/bookings?status=<?= $val ?>"
       style="padding:6px 16px;border-radius:99px;font-size:13px;font-weight:600;text-decoration:none;
              background:<?= ($filters['status']??'')===$val ? '#4338ca' : '#f1f5f9' ?>;
              color:<?= ($filters['status']??'')===$val ? 'white' : '#64748b' ?>;">
        <?= $label ?>
    </a>
    <?php endforeach; ?>
</div>

<!-- TABLE -->
<div class="card">
    <div class="card-body" style="padding:0;">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Booking</th>
                    <th>Guest</th>
                    <th>Hotel / Room</th>
                    <th>Dates</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($bookings)): ?>
                <tr><td colspan="7" style="text-align:center;padding:48px;color:#94a3b8;">
                    <div style="font-size:32px;margin-bottom:12px;">📋</div>
                    No bookings found.
                </td></tr>
                <?php else: ?>
                <?php foreach ($bookings as $b):
                    $ref = $b['booking_ref'] ?? ('#' . $b['id']);
                    $bc  = $bookingStatusBadge[$b['status']] ?? 'badge-pending';
                ?>
                <tr>
                    <td>
                        <a href="<?= BASE_URL ?>/admin/bookings/<?= $b['id'] ?>"
                           style="font-family:monospace;font-weight:700;color:#4338ca;font-size:13px;">
                            <?= htmlspecialchars($ref) ?>
                        </a>
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
                        <div style="font-size:13px;font-weight:600;"><?= htmlspecialchars($b['hotel_name']) ?></div>
                        <div style="font-size:11px;color:#94a3b8;">Room <?= htmlspecialchars($b['room_number'] ?? '—') ?></div>
                    </td>
                    <td style="font-size:12px;">
                        <?= date('d M Y', strtotime($b['check_in_date'])) ?><br>
                        <span style="color:#94a3b8;">→ <?= date('d M Y', strtotime($b['check_out_date'])) ?></span>
                    </td>
                    <td style="font-weight:700;color:#10b981;">₹<?= number_format($b['total_amount']) ?></td>
                    <td><span class="badge-pill <?= $bc ?>"><span class="dot"></span><?= ucfirst(str_replace('_',' ',$b['status'])) ?></span></td>
                    <td>
                        <a href="<?= BASE_URL ?>/admin/bookings/<?= $b['id'] ?>" class="btn btn-light" style="font-size:12px;padding:4px 10px;">View</a>
                        <?php if ($b['status'] === 'confirmed' || $b['status'] === 'checked_in'): ?>
                        <button onclick="cancelBooking(<?= $b['id'] ?>)" class="btn btn-light ms-1" style="font-size:12px;padding:4px 10px;color:#ef4444;border-color:#fecaca;">Cancel</button>
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
async function cancelBooking(id) {
    if (!confirm('Cancel this booking? This cannot be undone.')) return;
    const res  = await fetch(`<?= BASE_URL ?>/admin/bookings/${id}/cancel`, {method:'POST', headers:{'X-CSRF-Token':CSRF}});
    const data = await res.json();
    if (data.success) { showToast('Booking cancelled.','warning'); setTimeout(()=>location.reload(),800); }
}
</script>
