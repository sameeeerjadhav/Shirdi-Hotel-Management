<?php
$statusColors = [
    'approved'  => ['bg'=>'#ecfdf5','text'=>'#065f46','border'=>'#10b981'],
    'pending'   => ['bg'=>'#fffbeb','text'=>'#92400e','border'=>'#f59e0b'],
    'suspended' => ['bg'=>'#fef2f2','text'=>'#991b1b','border'=>'#ef4444'],
    'rejected'  => ['bg'=>'#f8fafc','text'=>'#64748b','border'=>'#94a3b8'],
];
$sc   = $statusColors[$hotel['status']] ?? $statusColors['pending'];
$s    = $stats ?? [];
$occ  = ($s['total_rooms'] ?? 0) > 0 ? round(($s['occupied_rooms'] / $s['total_rooms']) * 100) : 0;
?>
<div class="page-header">
    <div>
        <h2><?= htmlspecialchars($hotel['name']) ?></h2>
        <p>
            <i class="fa-solid fa-location-dot me-1" style="color:#94a3b8;"></i>
            <?= htmlspecialchars($hotel['city'] . ', ' . $hotel['state']) ?>
            &nbsp;&bull;&nbsp;
            <span style="background:<?= $sc['bg'] ?>;color:<?= $sc['text'] ?>;border:1px solid <?= $sc['border'] ?>;padding:2px 10px;border-radius:99px;font-size:12px;font-weight:700;"><?= ucfirst($hotel['status']) ?></span>
        </p>
    </div>
    <div style="display:flex;gap:10px;">
        <a href="<?= BASE_URL ?>/admin/hotels/<?= $hotel['id'] ?>/edit" class="btn btn-light">
            <i class="fa-solid fa-pen me-2"></i>Edit
        </a>
        <a href="<?= BASE_URL ?>/admin/hotels" class="btn btn-light">
            <i class="fa-solid fa-arrow-left me-2"></i>Back
        </a>
    </div>
</div>

<!-- STAT CARDS -->
<div class="row g-3 mb-4">
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-icon icon-indigo"><i class="fa-solid fa-bed"></i></div>
            <div class="stat-card-content">
                <div class="stat-label">TOTAL ROOMS</div>
                <div class="stat-value"><?= $s['total_rooms'] ?? 0 ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-icon icon-amber"><i class="fa-solid fa-calendar-check"></i></div>
            <div class="stat-card-content">
                <div class="stat-label">TODAY CHECK-INS</div>
                <div class="stat-value"><?= $s['today_checkins'] ?? 0 ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-icon icon-green"><i class="fa-solid fa-indian-rupee-sign"></i></div>
            <div class="stat-card-content">
                <div class="stat-label">REVENUE</div>
                <div class="stat-value" style="font-size:18px;">₹<?= number_format($s['total_revenue'] ?? 0) ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-icon icon-cyan"><i class="fa-solid fa-book-open"></i></div>
            <div class="stat-card-content">
                <div class="stat-label">BOOKINGS</div>
                <div class="stat-value"><?= $s['total_bookings'] ?? 0 ?></div>
            </div>
        </div>
    </div>
</div>

<!-- OCCUPANCY BAR -->
<div class="card mb-4" style="border:1px solid #c4b5fd;">
    <div style="padding:16px 24px;">
        <div class="d-flex justify-content-between mb-2">
            <span style="font-size:13px;font-weight:700;color:#64748b;">Occupancy</span>
            <span style="font-size:18px;font-weight:800;color:#4338ca;"><?= $occ ?>%</span>
        </div>
        <div style="height:10px;background:#e2e8f0;border-radius:99px;overflow:hidden;">
            <div style="width:<?= $occ ?>%;height:100%;background:linear-gradient(90deg,#8b5cf6,#c4b5fd);border-radius:99px;"></div>
        </div>
        <div style="font-size:12px;color:#94a3b8;margin-top:6px;"><?= $s['occupied_rooms']??0 ?> of <?= $s['total_rooms']??0 ?> rooms occupied</div>
    </div>
</div>

<div class="row g-4">
    <!-- HOTEL DETAILS -->
    <div class="col-md-5">
        <div class="card mb-4">
            <div class="card-header">Hotel Details</div>
            <div class="card-body" style="padding:0;">
                <table class="table mb-0">
                    <tbody>
                    <?php
                    $fields = [
                        'Email'          => $hotel['email'],
                        'Phone'          => $hotel['phone'],
                        'Address'        => ($hotel['address'] ?? '') . ', ' . ($hotel['city'] ?? '') . ', ' . ($hotel['state'] ?? ''),
                        'Hotel Code'     => $hotel['hotel_code'] ?? 'N/A',
                        'Commission'     => ($hotel['commission_rate'] ?? 0) . '%',
                        'Admin'          => $hotel['admin_name'] ?? 'N/A',
                        'Admin Email'    => $hotel['admin_email'] ?? 'N/A',
                        'GST Number'     => $hotel['gst_number'] ?? 'N/A',
                        'Created'        => date('d M Y', strtotime($hotel['created_at'])),
                    ];
                    foreach ($fields as $label => $value): ?>
                    <tr>
                        <td style="font-size:12px;font-weight:700;color:#94a3b8;text-transform:uppercase;width:40%;padding:10px 16px;"><?= $label ?></td>
                        <td style="font-size:13px;font-weight:600;color:#1e293b;padding:10px 16px;"><?= htmlspecialchars($value ?: '—') ?></td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- QUICK ACTIONS -->
        <div class="card">
            <div class="card-header">Quick Actions</div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:10px;">
                <?php if ($hotel['status'] === 'pending'): ?>
                <button onclick="quickAction('approve')" class="btn btn-primary">
                    <i class="fa-solid fa-check me-2"></i>Approve Hotel
                </button>
                <button onclick="quickAction('reject')" class="btn btn-light" style="color:#ef4444;border-color:#fecaca;">
                    <i class="fa-solid fa-times me-2"></i>Reject Registration
                </button>
                <?php elseif ($hotel['status'] === 'approved'): ?>
                <button onclick="quickAction('suspend')" class="btn btn-light" style="color:#f59e0b;border-color:#fed7aa;">
                    <i class="fa-solid fa-ban me-2"></i>Suspend Hotel
                </button>
                <?php elseif ($hotel['status'] === 'suspended'): ?>
                <button onclick="quickAction('approve')" class="btn btn-primary">
                    <i class="fa-solid fa-check me-2"></i>Re-Activate Hotel
                </button>
                <?php endif; ?>
                <a href="<?= BASE_URL ?>/admin/hotels/<?= $hotel['id'] ?>/edit" class="btn btn-light text-center">
                    <i class="fa-solid fa-pen me-2"></i>Edit Details
                </a>
            </div>
        </div>
    </div>

    <!-- ROOMS + RECENT BOOKINGS -->
    <div class="col-md-7">
        <!-- ROOMS -->
        <div class="card mb-4">
            <div class="card-header">
                Rooms (<?= count($rooms) ?>)
                <span style="font-size:12px;color:#94a3b8;">All room types</span>
            </div>
            <div class="card-body" style="padding:0;">
                <?php if (empty($rooms)): ?>
                <div style="padding:32px;text-align:center;color:#94a3b8;">No rooms added yet</div>
                <?php else: ?>
                <div style="display:flex;flex-wrap:wrap;gap:8px;padding:16px;">
                    <?php
                    $colorMap = [
                        'available'   => ['bg'=>'#ecfdf5','text'=>'#065f46','border'=>'#10b981'],
                        'occupied'    => ['bg'=>'#fef2f2','text'=>'#991b1b','border'=>'#ef4444'],
                        'reserved'    => ['bg'=>'#eff6ff','text'=>'#1d4ed8','border'=>'#3b82f6'],
                        'cleaning'    => ['bg'=>'#fffbeb','text'=>'#92400e','border'=>'#f59e0b'],
                        'maintenance' => ['bg'=>'#f5f3ff','text'=>'#5b21b6','border'=>'#8b5cf6'],
                        'blocked'     => ['bg'=>'#f8fafc','text'=>'#475569','border'=>'#94a3b8'],
                    ];
                    foreach ($rooms as $room):
                        $rc = $colorMap[$room['status']] ?? $colorMap['available'];
                    ?>
                    <div style="padding:8px 12px;border:1.5px solid <?= $rc['border'] ?>;background:<?= $rc['bg'] ?>;
                                border-radius:8px;text-align:center;min-width:56px;">
                        <div style="font-size:14px;font-weight:800;color:<?= $rc['text'] ?>;"><?= htmlspecialchars($room['room_number']) ?></div>
                        <div style="font-size:10px;color:<?= $rc['text'] ?>;opacity:0.8;"><?= htmlspecialchars($room['type_name']) ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- RECENT BOOKINGS -->
        <div class="card">
            <div class="card-header">
                Recent Bookings
                <a href="<?= BASE_URL ?>/admin/bookings?hotel_id=<?= $hotel['id'] ?>" class="btn btn-light" style="font-size:12px;padding:4px 12px;">View All</a>
            </div>
            <div class="card-body" style="padding:0;">
                <?php if (empty($bookings)): ?>
                <div style="padding:32px;text-align:center;color:#94a3b8;">No bookings for this hotel yet.</div>
                <?php else: ?>
                <table class="table mb-0">
                    <thead><tr><th>Guest</th><th>Dates</th><th>Amount</th><th>Status</th></tr></thead>
                    <tbody>
                    <?php foreach ($bookings as $b):
                        $bs = ['confirmed'=>'badge-pending','checked_in'=>'badge-active','checked_out'=>'badge-cleaning','cancelled'=>'badge-occupied'];
                        $bc = $bs[$b['status']] ?? 'badge-pending';
                    ?>
                    <tr>
                        <td>
                            <div style="font-size:13px;font-weight:700;color:#1e293b;"><?= htmlspecialchars($b['guest_name']) ?></div>
                            <div style="font-size:11px;color:#94a3b8;"><?= htmlspecialchars($b['guest_phone']) ?></div>
                        </td>
                        <td style="font-size:12px;"><?= date('d M', strtotime($b['check_in_date'])) ?> – <?= date('d M', strtotime($b['check_out_date'])) ?></td>
                        <td style="font-weight:700;color:#10b981;">₹<?= number_format($b['total_amount']) ?></td>
                        <td><span class="badge-pill <?= $bc ?>"><span class="dot"></span><?= ucfirst(str_replace('_',' ',$b['status'])) ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
const CSRF = '<?= \App\Middleware\CsrfMiddleware::generate() ?>';
const HID  = <?= $hotel['id'] ?>;

async function quickAction(action) {
    const confirmMsg = {
        approve: 'Approve this hotel?',
        reject:  'Reject this hotel registration?',
        suspend: 'Suspend this hotel? They will lose access.',
    };
    if (!confirm(confirmMsg[action])) return;

    const res  = await fetch(`<?= BASE_URL ?>/admin/hotels/${HID}/${action}`, {
        method: 'POST', headers: {'X-CSRF-Token': CSRF}
    });
    const data = await res.json();
    if (data.success) { showToast(data.message || 'Done!', 'success'); setTimeout(() => location.reload(), 1000); }
}
</script>
