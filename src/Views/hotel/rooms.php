<!-- HOTEL PARTNER: ROOM MANAGEMENT - VISUAL BOARD -->
<div class="page-header">
    <div>
        <h2>Room Management</h2>
        <p>Visual floor map &mdash; click any room to manage it</p>
    </div>
    <div style="display:flex;gap:10px;">
        <button onclick="openAddRoomModal()" class="btn btn-primary">
            <i class="fa-solid fa-plus me-2"></i>Add Room
        </button>
        <button onclick="openAddTypeModal()" class="btn btn-light">
            <i class="fa-solid fa-layer-group me-2"></i>Add Room Type
        </button>
    </div>
</div>

<!-- STATUS SUMMARY CARDS -->
<div class="row g-3 mb-4">
    <?php
        $statusConfig = [
            'available'   => ['label'=>'Available',   'icon'=>'fa-door-open',  'color'=>'#10b981', 'bg'=>'#ecfdf5'],
            'occupied'    => ['label'=>'Occupied',    'icon'=>'fa-bed',        'color'=>'#ef4444', 'bg'=>'#fef2f2'],
            'reserved'    => ['label'=>'Reserved',    'icon'=>'fa-calendar',   'color'=>'#3b82f6', 'bg'=>'#eff6ff'],
            'cleaning'    => ['label'=>'Cleaning',    'icon'=>'fa-broom',      'color'=>'#f59e0b', 'bg'=>'#fffbeb'],
            'maintenance' => ['label'=>'Maintenance', 'icon'=>'fa-wrench',     'color'=>'#8b5cf6', 'bg'=>'#f5f3ff'],
            'blocked'     => ['label'=>'Blocked',     'icon'=>'fa-ban',        'color'=>'#94a3b8', 'bg'=>'#f1f5f9'],
        ];
        foreach ($statusConfig as $status => $cfg):
            $count = $statusCounts[$status] ?? 0;
    ?>
    <div class="col-md-2 col-4">
        <div class="card" style="padding:14px;text-align:center;border-left:4px solid <?= $cfg['color'] ?>;">
            <div style="width:36px;height:36px;background:<?= $cfg['bg'] ?>;border-radius:8px;display:flex;align-items:center;justify-content:center;margin:0 auto 8px;">
                <i class="fa-solid <?= $cfg['icon'] ?>" style="color:<?= $cfg['color'] ?>;font-size:14px;"></i>
            </div>
            <div style="font-size:22px;font-weight:800;color:#1e293b;"><?= $count ?></div>
            <div style="font-size:11px;color:#94a3b8;font-weight:600;text-transform:uppercase;"><?= $cfg['label'] ?></div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- STATUS LEGEND -->
<div style="display:flex;gap:16px;flex-wrap:wrap;margin-bottom:20px;">
    <?php foreach ($statusConfig as $status => $cfg): ?>
    <div style="display:flex;align-items:center;gap:6px;font-size:12px;font-weight:600;color:#64748b;">
        <div style="width:12px;height:12px;border-radius:3px;background:<?= $cfg['color'] ?>;"></div>
        <?= $cfg['label'] ?>
    </div>
    <?php endforeach; ?>
</div>

<!-- FLOOR MAP -->
<?php if (empty($floorMap)): ?>
<div class="card" style="padding:64px;text-align:center;">
    <div style="width:64px;height:64px;background:#eef2ff;border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
        <i class="fa-solid fa-bed" style="font-size:28px;color:#6366f1;"></i>
    </div>
    <h3 style="font-size:18px;font-weight:700;color:#1e293b;">No rooms added yet</h3>
    <p style="color:#64748b;margin:8px 0 24px;">Add your first room type, then add rooms to your hotel.</p>
    <button onclick="openAddTypeModal()" class="btn btn-primary" style="display:inline-block;margin-right:10px;">Add Room Type</button>
    <button onclick="openAddRoomModal()" class="btn btn-light" style="display:inline-block;">Add Room</button>
</div>
<?php else: ?>
<?php foreach ($floorMap as $floor => $rooms): ?>
<div class="card mb-3">
    <div class="card-header">
        <div style="display:flex;align-items:center;gap:10px;">
            <div style="width:28px;height:28px;background:#4338ca;color:white;border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:800;">
                <?= $floor ?>
            </div>
            <span>Floor <?= $floor ?></span>
            <span style="font-size:12px;color:#94a3b8;margin-left:4px;">(<?= count($rooms) ?> rooms)</span>
        </div>
    </div>
    <div class="card-body">
        <div style="display:flex;flex-wrap:wrap;gap:12px;">
            <?php
                $colorMap = [
                    'available'   => ['border'=>'#10b981','bg'=>'#f0fdf4','text'=>'#065f46','icon'=>'<i class="fa-solid fa-check"></i>'],
                    'occupied'    => ['border'=>'#ef4444','bg'=>'#fef2f2','text'=>'#991b1b','icon'=>'●'],
                    'reserved'    => ['border'=>'#3b82f6','bg'=>'#eff6ff','text'=>'#1d4ed8','icon'=>'◷'],
                    'cleaning'    => ['border'=>'#f59e0b','bg'=>'#fffbeb','text'=>'#92400e','icon'=>'◌'],
                    'maintenance' => ['border'=>'#8b5cf6','bg'=>'#f5f3ff','text'=>'#5b21b6','icon'=>'⚙'],
                    'blocked'     => ['border'=>'#94a3b8','bg'=>'#f8fafc','text'=>'#475569','icon'=>'✗'],
                ];
                foreach ($rooms as $room):
                    $c = $colorMap[$room['status']] ?? $colorMap['available'];
            ?>
            <div onclick="openRoomDrawer(<?= htmlspecialchars(json_encode($room)) ?>)"
                 style="width:90px;height:90px;border:2px solid <?= $c['border'] ?>;background:<?= $c['bg'] ?>;border-radius:12px;
                        display:flex;flex-direction:column;align-items:center;justify-content:center;cursor:pointer;
                        transition:all 0.2s;position:relative;padding:8px;"
                 onmouseover="this.style.transform='scale(1.05)';this.style.boxShadow='0 8px 16px rgba(0,0,0,0.1)'"
                 onmouseout="this.style.transform='scale(1)';this.style.boxShadow='none'">
                <div style="font-size:18px;margin-bottom:2px;"><?= $c['icon'] ?></div>
                <div style="font-size:14px;font-weight:800;color:<?= $c['text'] ?>;"><?= htmlspecialchars($room['room_number']) ?></div>
                <div style="font-size:10px;color:<?= $c['text'] ?>;opacity:0.8;text-align:center;"><?= htmlspecialchars($room['type_name']) ?></div>
                <div style="font-size:10px;color:<?= $c['text'] ?>;font-weight:700;">₹<?= number_format($room['price_override'] ?? $room['base_price']) ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endforeach; ?>
<?php endif; ?>

<!-- ROOM DETAIL DRAWER -->
<div id="roomDrawer" style="position:fixed;top:0;right:-400px;width:380px;height:100vh;background:white;
     box-shadow:-8px 0 40px rgba(0,0,0,0.1);z-index:1000;transition:right 0.3s ease;overflow-y:auto;padding:24px;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;">
        <h3 style="font-size:18px;font-weight:800;color:#1e293b;" id="drawerTitle">Room Details</h3>
        <button onclick="closeDrawer()" style="background:#f1f5f9;border:none;width:28px;height:28px;border-radius:6px;display:flex;align-items:center;justify-content:center;color:#64748b;cursor:pointer;font-size:12px;"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div id="drawerContent"></div>
</div>
<div id="drawerOverlay" onclick="closeDrawer()" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.3);z-index:999;"></div>

<!-- ADD ROOM MODAL -->
<div class="modal fade" id="addRoomModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Add New Room</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <form id="addRoomForm">
                    <?= \App\Middleware\CsrfMiddleware::field() ?>
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label">Room Number *</label>
                            <input type="text" name="room_number" class="form-control" placeholder="101" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Floor *</label>
                            <input type="number" name="floor_number" class="form-control" value="1" min="1" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Room Type *</label>
                            <select name="room_type_id" class="form-select" required>
                                <option value="">Select type</option>
                                <?php foreach ($roomTypes as $type): ?>
                                <option value="<?= $type['id'] ?>"><?= htmlspecialchars($type['name']) ?> — ₹<?= number_format($type['base_price']) ?>/night</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Max Guests</label>
                            <input type="number" name="max_guests" class="form-control" value="2" min="1">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Price Override (₹)</label>
                            <input type="number" name="price_override" class="form-control" placeholder="Leave blank for type price">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="Room notes..."></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitAddRoom()">Add Room</button>
            </div>
        </div>
    </div>
</div>

<!-- ADD ROOM TYPE MODAL -->
<div class="modal fade" id="addTypeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Add Room Type</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <form id="addTypeForm">
                    <?= \App\Middleware\CsrfMiddleware::field() ?>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Type Name *</label>
                            <input type="text" name="name" class="form-control" placeholder="Deluxe, Suite, Standard..." required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Base Price (₹/night) *</label>
                            <input type="number" name="base_price" class="form-control" placeholder="2500" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Capacity</label>
                            <input type="number" name="capacity" class="form-control" value="2" min="1">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Bed Type</label>
                            <select name="bed_type" class="form-select">
                                <option value="double">Double</option>
                                <option value="single">Single</option>
                                <option value="twin">Twin</option>
                                <option value="queen">Queen</option>
                                <option value="king">King</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitAddType()">Add Type</button>
            </div>
        </div>
    </div>
</div>

<script>
const CSRF = '<?= \App\Middleware\CsrfMiddleware::generate() ?>';
const BASE = '<?= BASE_URL ?>';

function openAddRoomModal() { new bootstrap.Modal(document.getElementById('addRoomModal')).show(); }
function openAddTypeModal() { new bootstrap.Modal(document.getElementById('addTypeModal')).show(); }

function openRoomDrawer(room) {
    const statusLabels = {available:'Available',occupied:'Occupied',reserved:'Reserved',cleaning:'Cleaning',maintenance:'Maintenance',blocked:'Blocked'};
    const statusColors = {available:'#10b981',occupied:'#ef4444',reserved:'#3b82f6',cleaning:'#f59e0b',maintenance:'#8b5cf6',blocked:'#94a3b8'};
    const c = statusColors[room.status] || '#94a3b8';

    document.getElementById('drawerTitle').textContent = 'Room ' + room.room_number;
    document.getElementById('drawerContent').innerHTML = `
        <div style="text-align:center;padding:20px 0;border-bottom:1px solid #e2e8f0;margin-bottom:20px;">
            <div style="width:64px;height:64px;border-radius:16px;background:${c}22;border:2px solid ${c};display:flex;align-items:center;justify-content:center;margin:0 auto 12px;font-size:24px;font-weight:800;color:${c};">${room.room_number}</div>
            <span style="background:${c}22;color:${c};padding:4px 12px;border-radius:99px;font-size:12px;font-weight:700;">${statusLabels[room.status]}</span>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:20px;">
            <div style="background:#f8fafc;padding:12px;border-radius:8px;"><div style="font-size:11px;color:#94a3b8;font-weight:700;text-transform:uppercase;">Type</div><div style="font-weight:700;color:#1e293b;margin-top:4px;">${room.type_name}</div></div>
            <div style="background:#f8fafc;padding:12px;border-radius:8px;"><div style="font-size:11px;color:#94a3b8;font-weight:700;text-transform:uppercase;">Floor</div><div style="font-weight:700;color:#1e293b;margin-top:4px;">${room.floor_number}</div></div>
            <div style="background:#f8fafc;padding:12px;border-radius:8px;"><div style="font-size:11px;color:#94a3b8;font-weight:700;text-transform:uppercase;">Price</div><div style="font-weight:800;color:#4338ca;margin-top:4px;">₹${Number(room.price_override || room.base_price).toLocaleString('en-IN')}/night</div></div>
            <div style="background:#f8fafc;padding:12px;border-radius:8px;"><div style="font-size:11px;color:#94a3b8;font-weight:700;text-transform:uppercase;">Max Guests</div><div style="font-weight:700;color:#1e293b;margin-top:4px;">${room.max_guests}</div></div>
        </div>
        <div style="margin-bottom:16px;">
            <label style="font-size:12px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.05em;display:block;margin-bottom:8px;">Change Status</label>
            <div style="display:flex;flex-wrap:wrap;gap:8px;">
                ${['available','cleaning','maintenance','blocked'].map(s => 
                    `<button onclick="changeStatus(${room.id},'${s}')" style="padding:6px 14px;border-radius:8px;border:1.5px solid ${statusColors[s]};background:${statusColors[s]}22;color:${statusColors[s]};font-size:12px;font-weight:700;cursor:pointer;">${statusLabels[s]}</button>`
                ).join('')}
            </div>
        </div>
        <div style="display:flex;gap:8px;">
            <button onclick="deleteRoom(${room.id})" style="flex:1;padding:10px;background:#fef2f2;border:1.5px solid #fecaca;color:#ef4444;border-radius:8px;font-weight:700;cursor:pointer;font-size:13px;">Delete Room</button>
        </div>
    `;
    document.getElementById('roomDrawer').style.right = '0';
    document.getElementById('drawerOverlay').style.display = 'block';
}

function closeDrawer() {
    document.getElementById('roomDrawer').style.right = '-400px';
    document.getElementById('drawerOverlay').style.display = 'none';
}

async function changeStatus(id, status) {
    const fd = new FormData();
    fd.append('status', status);
    fd.append('_csrf', CSRF);
    const res = await fetch(`${BASE}/hotel/rooms/${id}/status`, { method:'POST', body:fd });
    const data = await res.json();
    if (data.success) { closeDrawer(); location.reload(); }
}

async function deleteRoom(id) {
    if (!confirm('Delete this room? This action cannot be undone.')) return;
    const fd = new FormData();
    fd.append('_csrf', CSRF);
    const res = await fetch(`${BASE}/hotel/rooms/${id}/delete`, { method:'POST', body:fd });
    const data = await res.json();
    if (data.success) { closeDrawer(); location.reload(); showToast('Room deleted.','warning'); }
}

async function submitAddRoom() {
    const form = document.getElementById('addRoomForm');
    const fd   = new FormData(form);
    const res  = await fetch(`${BASE}/hotel/rooms/add`, { method:'POST', body:fd });
    const data = await res.json();
    if (data.success) {
        bootstrap.Modal.getInstance(document.getElementById('addRoomModal')).hide();
        showToast('Room added!','success');
        setTimeout(() => location.reload(), 800);
    } else {
        showToast(Object.values(data.errors || {}).join(', '),'error');
    }
}

async function submitAddType() {
    const form = document.getElementById('addTypeForm');
    const fd   = new FormData(form);
    const res  = await fetch(`${BASE}/hotel/room-types/add`, { method:'POST', body:fd });
    const data = await res.json();
    if (data.success) {
        bootstrap.Modal.getInstance(document.getElementById('addTypeModal')).hide();
        showToast('Room type added!','success');
        setTimeout(() => location.reload(), 800);
    } else {
        showToast(Object.values(data.errors || {}).join(', '),'error');
    }
}
</script>
