<!-- HOTELS MANAGEMENT PAGE -->
<div class="page-header">
    <div>
        <h2>Hotel Management</h2>
        <p>Manage partner hotels, approvals and performance</p>
    </div>
    <a href="<?= BASE_URL ?>/admin/hotels/add" class="btn btn-primary">
        <i class="fa-solid fa-plus me-2"></i>Add Hotel
    </a>
</div>

<!-- FILTER BAR -->
<div class="card mb-4" style="padding:16px 24px;">
    <form method="GET" class="d-flex gap-3 align-items-center flex-wrap">
        <div style="flex:1;min-width:200px;">
            <div style="position:relative;">
                <i class="fa-solid fa-magnifying-glass" style="position:absolute;left:12px;top:11px;color:#94a3b8;font-size:13px;"></i>
                <input type="text" name="search" placeholder="Search hotels, cities, codes..." value="<?= htmlspecialchars($search) ?>"
                    class="form-control" style="padding-left:36px;">
            </div>
        </div>
        <div>
            <select name="status" class="form-select" style="width:160px;">
                <option value="">All Status</option>
                <option value="pending"   <?= $status === 'pending'   ? 'selected' : '' ?>>Pending</option>
                <option value="approved"  <?= $status === 'approved'  ? 'selected' : '' ?>>Approved</option>
                <option value="suspended" <?= $status === 'suspended' ? 'selected' : '' ?>>Suspended</option>
                <option value="rejected"  <?= $status === 'rejected'  ? 'selected' : '' ?>>Rejected</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary" style="padding:9px 20px;">Filter</button>
        <a href="<?= BASE_URL ?>/admin/hotels" class="btn btn-light">Reset</a>
    </form>
</div>

<!-- STATUS TABS -->
<div style="display:flex;gap:8px;margin-bottom:20px;flex-wrap:wrap;">
    <?php
        $tabs = [''=>'All', 'pending'=>'Pending', 'approved'=>'Approved', 'suspended'=>'Suspended', 'rejected'=>'Rejected'];
        foreach ($tabs as $val => $label):
    ?>
    <a href="<?= BASE_URL ?>/admin/hotels?status=<?= $val ?>" 
       style="padding:6px 16px;border-radius:99px;font-size:13px;font-weight:600;text-decoration:none;
              background:<?= $status === $val ? '#4338ca' : '#f1f5f9' ?>;
              color:<?= $status === $val ? 'white' : '#64748b' ?>;">
        <?= $label ?>
    </a>
    <?php endforeach; ?>
</div>

<!-- HOTELS GRID -->
<?php if (empty($hotels)): ?>
<div class="card" style="padding:64px;text-align:center;">
    <div style="width:64px;height:64px;background:#eef2ff;border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
        <i class="fa-solid fa-building" style="font-size:28px;color:#6366f1;"></i>
    </div>
    <h3 style="font-size:18px;font-weight:700;color:#1e293b;">No hotels found</h3>
    <p style="color:#64748b;margin:8px 0 24px;">
        <?= $search ? "No hotels match your search for \"" . htmlspecialchars($search) . "\"" : "No hotels in this category yet." ?>
    </p>
    <a href="<?= BASE_URL ?>/admin/hotels/add" class="btn btn-primary" style="display:inline-block;">Add First Hotel</a>
</div>
<?php else: ?>
<div class="row g-3">
    <?php foreach ($hotels as $hotel): ?>
    <div class="col-md-6 col-lg-4">
        <div class="card h-100" style="position:relative;overflow:hidden;" id="hotel-card-<?= $hotel['id'] ?>">
            <!-- Status accent bar -->
            <div style="position:absolute;top:0;left:0;width:100%;height:4px;background:<?= 
                $hotel['status'] === 'approved'  ? '#10b981' :
                ($hotel['status'] === 'pending'  ? '#f59e0b' :
                ($hotel['status'] === 'suspended'? '#ef4444' : '#94a3b8')) ?>;"></div>

            <div style="padding:20px 20px 0;">
                <!-- Hotel Header -->
                <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:12px;">
                    <div style="display:flex;align-items:center;gap:12px;">
                        <div style="width:44px;height:44px;background:#eff6ff;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:20px;font-weight:800;color:#4338ca;">
                            <?= strtoupper(substr($hotel['name'], 0, 1)) ?>
                        </div>
                        <div>
                            <div style="font-size:15px;font-weight:700;color:#1e293b;"><?= htmlspecialchars($hotel['name']) ?></div>
                            <div style="font-size:12px;color:#94a3b8;margin-top:2px;"><?= htmlspecialchars($hotel['hotel_code'] ?? 'N/A') ?></div>
                        </div>
                    </div>
                    <?php
                        $statusMap = ['approved'=>'badge-active','pending'=>'badge-pending','suspended'=>'badge-occupied','rejected'=>'badge-occupied'];
                        $sc = $statusMap[$hotel['status']] ?? 'badge-pending';
                    ?>
                    <span class="badge-pill <?= $sc ?>"><span class="dot"></span><?= ucfirst($hotel['status']) ?></span>
                </div>

                <!-- Stats Row -->
                <div style="display:flex;gap:16px;padding:12px 0;border-top:1px solid #f1f5f9;border-bottom:1px solid #f1f5f9;margin-bottom:16px;">
                    <div style="text-align:center;flex:1;">
                        <div style="font-size:18px;font-weight:800;color:#1e293b;"><?= $hotel['room_count'] ?? 0 ?></div>
                        <div style="font-size:11px;color:#94a3b8;font-weight:600;">ROOMS</div>
                    </div>
                    <div style="text-align:center;flex:1;border-left:1px solid #f1f5f9;">
                        <div style="font-size:18px;font-weight:800;color:#10b981;"><?= $hotel['occupied_count'] ?? 0 ?></div>
                        <div style="font-size:11px;color:#94a3b8;font-weight:600;">OCCUPIED</div>
                    </div>
                    <div style="text-align:center;flex:1;border-left:1px solid #f1f5f9;">
                        <?php $stars = $hotel['star_rating'] ?? 3; ?>
                        <div style="font-size:14px;font-weight:800;color:#f59e0b;"><?= str_repeat('★', $stars) ?></div>
                        <div style="font-size:11px;color:#94a3b8;font-weight:600;">RATING</div>
                    </div>
                </div>

                <!-- Location -->
                <div style="font-size:13px;color:#64748b;margin-bottom:4px;">
                    <i class="fa-solid fa-location-dot" style="color:#94a3b8;margin-right:6px;"></i>
                    <?= htmlspecialchars($hotel['city'] . ', ' . $hotel['state']) ?>
                </div>
                <div style="font-size:13px;color:#64748b;margin-bottom:16px;">
                    <i class="fa-solid fa-user" style="color:#94a3b8;margin-right:6px;"></i>
                    <?= htmlspecialchars($hotel['admin_name'] ?? 'Not assigned') ?>
                </div>
            </div>

            <!-- Action Footer -->
            <div style="padding:12px 20px;background:#f8fafc;border-top:1px solid #e2e8f0;display:flex;gap:8px;">
                <a href="<?= BASE_URL ?>/admin/hotels/<?= $hotel['id'] ?>" class="btn btn-light" style="flex:1;text-align:center;font-size:13px;padding:6px 0;">View</a>
                <?php if ($hotel['status'] === 'pending'): ?>
                <button onclick="approveHotel(<?= $hotel['id'] ?>)" class="btn btn-primary" style="flex:1;font-size:13px;padding:6px 0;">Approve</button>
                <button onclick="rejectHotel(<?= $hotel['id'] ?>)" class="btn btn-light" style="font-size:13px;padding:6px 12px;color:#ef4444;border-color:#fecaca;">Reject</button>
                <?php elseif ($hotel['status'] === 'approved'): ?>
                <a href="<?= BASE_URL ?>/admin/hotels/<?= $hotel['id'] ?>/edit" class="btn btn-light" style="flex:1;text-align:center;font-size:13px;padding:6px 0;">Edit</a>
                <button onclick="suspendHotel(<?= $hotel['id'] ?>)" class="btn btn-light" style="font-size:13px;padding:6px 12px;color:#f59e0b;border-color:#fed7aa;">Suspend</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<script>
const CSRF = '<?= \App\Middleware\CsrfMiddleware::generate() ?>';

async function approveHotel(id) {
    const res = await fetch(`<?= BASE_URL ?>/admin/hotels/${id}/approve`, {
        method:'POST', headers:{'X-CSRF-Token': CSRF}
    });
    const data = await res.json();
    if (data.success) { 
        location.reload();
    }
}

async function rejectHotel(id) {
    if (!confirm('Reject this hotel registration?')) return;
    const res = await fetch(`<?= BASE_URL ?>/admin/hotels/${id}/reject`, {
        method:'POST', headers:{'X-CSRF-Token': CSRF}
    });
    const data = await res.json();
    if (data.success) { document.getElementById('hotel-card-'+id)?.remove(); showToast('Hotel rejected.','warning'); }
}

async function suspendHotel(id) {
    if (!confirm('Suspend this hotel? They will lose access.')) return;
    const res = await fetch(`<?= BASE_URL ?>/admin/hotels/${id}/suspend`, {
        method:'POST', headers:{'X-CSRF-Token': CSRF}
    });
    const data = await res.json();
    if (data.success) { location.reload(); }
}
</script>
