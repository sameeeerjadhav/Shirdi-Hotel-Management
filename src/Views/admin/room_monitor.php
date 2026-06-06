<!-- ROOM MONITOR -->
<div class="page-header">
    <div>
        <h2>Room Monitor</h2>
        <p>Live occupancy view across all hotel properties</p>
    </div>
</div>

<!-- HOTEL SELECTOR -->
<div class="card mb-4" style="padding:16px 20px;">
    <form method="GET" class="d-flex gap-3 align-items-center flex-wrap">
        <div style="flex:1;min-width:200px;">
            <label style="font-size:12px;font-weight:700;color:#64748b;text-transform:uppercase;margin-bottom:6px;display:block;">Select Hotel</label>
            <select name="hotel_id" class="form-select" onchange="this.form.submit()">
                <?php foreach ($hotels as $h): ?>
                <option value="<?= $h['id'] ?>" <?= $hotelId == $h['id'] ? 'selected':'' ?>>
                    <?= htmlspecialchars($h['name']) ?> — <?= htmlspecialchars($h['city']) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label style="font-size:12px;font-weight:700;color:#64748b;text-transform:uppercase;margin-bottom:6px;display:block;">Filter Status</label>
            <select name="status" class="form-select" style="width:160px;" onchange="this.form.submit()">
                <option value="">All Rooms</option>
                <?php foreach (['available','occupied','reserved','cleaning','maintenance','blocked'] as $st): ?>
                <option value="<?= $st ?>" <?= $statusF===$st?'selected':'' ?>><?= ucfirst($st) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>
</div>

<!-- LEGEND -->
<?php
$colorMap = [
    'available'   => ['border'=>'#10b981','bg'=>'#f0fdf4','text'=>'#065f46'],
    'occupied'    => ['border'=>'#ef4444','bg'=>'#fef2f2','text'=>'#991b1b'],
    'reserved'    => ['border'=>'#3b82f6','bg'=>'#eff6ff','text'=>'#1d4ed8'],
    'cleaning'    => ['border'=>'#f59e0b','bg'=>'#fffbeb','text'=>'#92400e'],
    'maintenance' => ['border'=>'#8b5cf6','bg'=>'#f5f3ff','text'=>'#5b21b6'],
    'blocked'     => ['border'=>'#94a3b8','bg'=>'#f8fafc','text'=>'#475569'],
];
?>
<div style="display:flex;gap:12px;flex-wrap:wrap;margin-bottom:20px;">
    <?php foreach ($colorMap as $status => $c): ?>
    <div style="display:flex;align-items:center;gap:6px;font-size:12px;font-weight:600;color:#64748b;">
        <div style="width:12px;height:12px;border-radius:3px;background:<?= $c['border'] ?>;"></div>
        <?= ucfirst($status) ?>
    </div>
    <?php endforeach; ?>
</div>

<!-- ROOM GRID -->
<?php if (empty($rooms)): ?>
<div class="card" style="padding:64px;text-align:center;">
    <div style="font-size:48px;margin-bottom:16px;">🏨</div>
    <h3 style="font-size:18px;font-weight:700;color:#1e293b;">No rooms found</h3>
    <p style="color:#64748b;">
        <?= empty($hotels) ? 'No approved hotels in the network yet.' : 'No rooms added to this hotel yet.' ?>
    </p>
</div>
<?php else: ?>
<?php
// Group by floor
$floors = [];
foreach ($rooms as $room) {
    $floor = $room['floor_number'] ?? 1;
    $floors[$floor][] = $room;
}
ksort($floors);
?>
<?php foreach ($floors as $floor => $floorRooms): ?>
<div class="card mb-3">
    <div class="card-header">
        <div style="display:flex;align-items:center;gap:10px;">
            <div style="width:28px;height:28px;background:#4338ca;color:white;border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:800;"><?= $floor ?></div>
            <span>Floor <?= $floor ?></span>
            <span style="font-size:12px;color:#94a3b8;">(<?= count($floorRooms) ?> rooms)</span>
        </div>
    </div>
    <div class="card-body">
        <div style="display:flex;flex-wrap:wrap;gap:12px;">
            <?php foreach ($floorRooms as $room):
                $c = $colorMap[$room['status']] ?? $colorMap['available'];
            ?>
            <div style="width:100px;border:2px solid <?= $c['border'] ?>;background:<?= $c['bg'] ?>;border-radius:12px;
                        padding:12px 8px;text-align:center;position:relative;">
                <div style="font-size:16px;font-weight:800;color:<?= $c['text'] ?>;"><?= htmlspecialchars($room['room_number']) ?></div>
                <div style="font-size:10px;color:<?= $c['text'] ?>;margin-top:2px;opacity:0.9;"><?= htmlspecialchars($room['type_name']) ?></div>
                <div style="font-size:10px;color:<?= $c['text'] ?>;font-weight:700;margin-top:2px;">₹<?= number_format($room['base_price']) ?></div>
                <?php if ($room['status'] === 'occupied' && !empty($room['current_booking'])): ?>
                <div style="position:absolute;top:-6px;right:-6px;width:18px;height:18px;background:#ef4444;border-radius:50%;display:flex;align-items:center;justify-content:center;" title="<?= htmlspecialchars($room['current_booking']['guest_name']) ?>">
                    <i class="fa-solid fa-person" style="color:white;font-size:9px;"></i>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Occupied room guest info -->
        <?php foreach ($floorRooms as $room): ?>
        <?php if ($room['status'] === 'occupied' && !empty($room['current_booking'])): ?>
        <?php $cb = $room['current_booking']; ?>
        <div style="margin-top:12px;padding:10px 14px;background:#fef2f2;border:1px solid #fecaca;border-radius:8px;display:flex;align-items:center;gap:12px;">
            <div style="font-size:20px;font-weight:800;color:#4338ca;min-width:40px;"><?= htmlspecialchars($room['room_number']) ?></div>
            <div>
                <div style="font-size:13px;font-weight:700;color:#1e293b;"><?= htmlspecialchars($cb['guest_name']) ?></div>
                <div style="font-size:11px;color:#64748b;"><?= htmlspecialchars($cb['guest_phone'] ?? '') ?> &bull; Checkout: <?= date('d M Y', strtotime($cb['check_out_date'])) ?></div>
            </div>
        </div>
        <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>
<?php endforeach; ?>
<?php endif; ?>
