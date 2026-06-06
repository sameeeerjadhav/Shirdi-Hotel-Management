<?php
// Inject CSRF meta tag and notification bell data into layout
use App\Middleware\CsrfMiddleware;
?>
<!-- DASHBOARD PAGE -->

<!-- SECTION: OVERVIEW -->
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h2 style="font-size:24px;font-weight:800;color:#1e293b;letter-spacing:-0.5px;">Good <?= date('H') < 12 ? 'Morning' : (date('H') < 17 ? 'Afternoon' : 'Evening') ?>, <?= htmlspecialchars(explode(' ', $_SESSION['name'])[0]) ?>! 👋</h2>
        <p style="font-size:14px;color:#64748b;margin-top:4px;"><?= date('l, d F Y') ?> &mdash; Platform Overview</p>
    </div>
    <div style="display:flex;gap:10px;">
        <?php if ($pendingTransfers > 0): ?>
        <a href="<?= BASE_URL ?>/admin/transfers" class="btn btn-primary" style="font-size:13px;padding:8px 16px;">
            <i class="fa-solid fa-right-left me-2"></i><?= $pendingTransfers ?> Pending Transfer<?= $pendingTransfers > 1 ? 's' : '' ?>
        </a>
        <?php endif; ?>
    </div>
</div>

<h6 class="section-label">OVERVIEW</h6>
<div class="row g-3 mb-4">
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="stat-icon icon-amber"><i class="fa-solid fa-building"></i></div>
            <div class="stat-card-content">
                <div class="stat-label">TOTAL HOTELS</div>
                <div class="stat-value"><?= $stats['total_hotels'] ?? 0 ?></div>
                <div class="sub"><?= $stats['active_hotels'] ?? 0 ?> active &bull; <?= $stats['pending_hotels'] ?? 0 ?> pending</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="stat-icon icon-indigo"><i class="fa-solid fa-bed"></i></div>
            <div class="stat-card-content">
                <div class="stat-label">TOTAL ROOMS</div>
                <div class="stat-value"><?= $stats['total_rooms'] ?? 0 ?></div>
                <div class="sub"><?= $stats['occupied_rooms'] ?? 0 ?> occupied</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="stat-icon icon-cyan"><i class="fa-solid fa-calendar-check"></i></div>
            <div class="stat-card-content">
                <div class="stat-label">AVAILABLE</div>
                <div class="stat-value"><?= $stats['available_rooms'] ?? 0 ?></div>
                <div class="sub">Ready for booking</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="stat-icon icon-green"><i class="fa-solid fa-indian-rupee-sign"></i></div>
            <div class="stat-card-content">
                <div class="stat-label">PLATFORM REVENUE</div>
                <div class="stat-value" style="font-size:20px;">₹<?= number_format($stats['platform_revenue'] ?? 0) ?></div>
                <div class="sub">Network earnings</div>
            </div>
        </div>
    </div>
</div>

<!-- REVENUE STATISTICS -->
<h6 class="section-label">REVENUE STATISTICS</h6>
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:#4338ca;color:white;border-radius:50%;width:48px;height:48px;font-size:20px;"><i class="fa-solid fa-indian-rupee-sign"></i></div>
            <div class="stat-card-content">
                <div class="stat-label">TOTAL REVENUE (30D)</div>
                <div class="stat-value" style="font-size:22px;">₹<?= number_format($revenue['total_revenue'] ?? 0) ?></div>
                <div class="sub"><?= $revenue['total_bookings'] ?? 0 ?> bookings</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:#10b981;color:white;border-radius:12px;width:48px;height:48px;font-size:20px;"><i class="fa-solid fa-check-double"></i></div>
            <div class="stat-card-content">
                <div class="stat-label">COLLECTED</div>
                <div class="stat-value" style="font-size:22px;">₹<?= number_format($revenue['collected'] ?? 0) ?></div>
                <div class="sub"><span style="background:#10b981;color:white;padding:2px 8px;border-radius:4px;font-size:11px;font-weight:700;"><?= $revenue['total_revenue'] > 0 ? round(($revenue['collected'] / $revenue['total_revenue']) * 100) : 0 ?>% collected</span></div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:#f59e0b;color:white;border-radius:50%;width:48px;height:48px;font-size:20px;"><i class="fa-regular fa-clock"></i></div>
            <div class="stat-card-content">
                <div class="stat-label">PENDING</div>
                <div class="stat-value" style="font-size:22px;">₹<?= number_format($revenue['pending'] ?? 0) ?></div>
                <div class="sub">Outstanding amount</div>
            </div>
        </div>
    </div>
</div>

<!-- OCCUPANCY PROGRESS -->
<?php
    $totalRooms    = $stats['total_rooms'] ?? 0;
    $occupiedRooms = $stats['occupied_rooms'] ?? 0;
    $occupancyPct  = $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100) : 0;
?>
<div class="card mb-4" style="border:1px solid #c4b5fd;">
    <div style="padding:20px 24px;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="section-label" style="margin-bottom:0;">OCCUPANCY PROGRESS</h6>
            <span style="font-size:20px;font-weight:800;color:#4338ca;"><?= $occupancyPct ?>%</span>
        </div>
        <div style="width:100%;height:14px;background:#e2e8f0;border-radius:99px;overflow:hidden;">
            <div style="width:<?= $occupancyPct ?>%;height:100%;background:linear-gradient(90deg,#8b5cf6,#c4b5fd);border-radius:99px;transition:width 1s ease;"></div>
        </div>
        <div style="font-size:12px;font-weight:600;color:#64748b;margin-top:8px;"><?= $occupiedRooms ?> of <?= $totalRooms ?> Rooms Occupied Across Network</div>
    </div>
</div>

<!-- REVENUE CHART -->
<div class="row g-3 mb-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <span>Revenue Trend (Last 30 Days)</span>
                <a href="<?= BASE_URL ?>/admin/finance" class="btn btn-light" style="font-size:12px;padding:5px 12px;">View Finance</a>
            </div>
            <div class="card-body">
                <div id="revenueChart"></div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header">Booking Status</div>
            <div class="card-body">
                <div id="bookingStatusChart"></div>
            </div>
        </div>
    </div>
</div>

<!-- RECENT HOTELS (PENDING) -->
<?php if (!empty($recentHotels)): ?>
<h6 class="section-label">PENDING APPROVALS</h6>
<div class="card mb-4">
    <div class="card-header">
        <div style="display:flex;align-items:center;gap:10px;">
            <div style="width:8px;height:8px;background:#f59e0b;border-radius:50%;animation:pulse 2s infinite;"></div>
            <span>Hotels Awaiting Approval</span>
        </div>
        <a href="<?= BASE_URL ?>/admin/hotels?status=pending" class="btn btn-light" style="font-size:12px;padding:5px 12px;">View All</a>
    </div>
    <div class="card-body" style="padding:0;">
        <table class="table mb-0">
            <thead><tr><th>Hotel</th><th>City</th><th>Owner</th><th>Date</th><th>Actions</th></tr></thead>
            <tbody>
                <?php foreach (array_slice($recentHotels, 0, 5) as $hotel): ?>
                <tr id="hotel-row-<?= $hotel['id'] ?>">
                    <td>
                        <div class="identity-cell">
                            <div class="identity-avatar icon-amber"><?= strtoupper(substr($hotel['name'], 0, 1)) ?></div>
                            <div>
                                <div class="identity-name"><?= htmlspecialchars($hotel['name']) ?></div>
                                <div class="identity-sub"><?= htmlspecialchars($hotel['hotel_code'] ?? '') ?></div>
                            </div>
                        </div>
                    </td>
                    <td><?= htmlspecialchars($hotel['city']) ?></td>
                    <td><?= htmlspecialchars($hotel['admin_name']) ?></td>
                    <td style="font-size:12px;color:#64748b;"><?= date('d M Y', strtotime($hotel['created_at'])) ?></td>
                    <td>
                        <button class="btn btn-primary" style="font-size:12px;padding:4px 12px;border-radius:6px;" onclick="approveHotel(<?= $hotel['id'] ?>)">Approve</button>
                        <button class="btn btn-light ms-1" style="font-size:12px;padding:4px 12px;border-radius:6px;" onclick="rejectHotel(<?= $hotel['id'] ?>)">Reject</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<!-- RECENT BOOKINGS -->
<h6 class="section-label">RECENT BOOKINGS</h6>
<div class="card">
    <div class="card-header">
        Latest Network Bookings
        <a href="<?= BASE_URL ?>/admin/bookings" class="btn btn-light" style="font-size:12px;padding:5px 12px;">View All</a>
    </div>
    <div class="card-body" style="padding:0;">
        <table class="table mb-0">
            <thead><tr><th>Booking Ref</th><th>Guest</th><th>Hotel</th><th>Dates</th><th>Amount</th><th>Status</th></tr></thead>
            <tbody>
                <?php if (empty($recentBooks)): ?>
                <tr><td colspan="6" style="text-align:center;padding:32px;color:#94a3b8;">No bookings yet. <a href="<?= BASE_URL ?>/search">Create a test booking →</a></td></tr>
                <?php else: ?>
                <?php foreach ($recentBooks as $b): ?>
                <tr>
                    <td><span style="font-family:monospace;font-size:13px;font-weight:700;color:#4338ca;"><?= $b['booking_ref'] ?></span></td>
                    <td>
                        <div class="identity-cell">
                            <div class="identity-avatar icon-indigo"><?= strtoupper(substr($b['guest_name'], 0, 1)) ?></div>
                            <div>
                                <div class="identity-name"><?= htmlspecialchars($b['guest_name']) ?></div>
                                <div class="identity-sub"><?= htmlspecialchars($b['guest_phone']) ?></div>
                            </div>
                        </div>
                    </td>
                    <td><?= htmlspecialchars($b['hotel_name']) ?></td>
                    <td style="font-size:12px;"><?= date('d M', strtotime($b['check_in_date'])) ?> – <?= date('d M Y', strtotime($b['check_out_date'])) ?></td>
                    <td style="font-weight:700;color:#10b981;">₹<?= number_format($b['total_amount']) ?></td>
                    <td>
                        <?php
                            $statusColors = ['confirmed'=>'badge-pending','checked_in'=>'badge-active','checked_out'=>'badge-cleaning','cancelled'=>'badge-occupied','transferred'=>'badge-cleaning'];
                            $sc = $statusColors[$b['status']] ?? 'badge-pending';
                        ?>
                        <span class="badge-pill <?= $sc ?>"><span class="dot"></span><?= ucfirst(str_replace('_',' ',$b['status'])) ?></span>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- CHARTS + ACTION SCRIPTS -->
<style>
    .section-label { font-size:11px;font-weight:800;color:#94a3b8;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:12px;margin-top:8px; }
    @keyframes pulse { 0%,100% { opacity:1; } 50% { opacity:0.4; } }
</style>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
const dailyRevenue = <?= $dailyRevenue ?>;
const categories   = dailyRevenue.map(d => d.date);
const revenues     = dailyRevenue.map(d => parseFloat(d.revenue));

new ApexCharts(document.querySelector("#revenueChart"), {
    chart: { type:'area', height:220, toolbar:{show:false}, sparkline:{enabled:false} },
    series: [{ name:'Revenue ₹', data: revenues }],
    xaxis: { categories, labels:{ style:{fontSize:'11px',colors:'#94a3b8'} } },
    yaxis: { labels:{ formatter:v=>'₹'+Number(v).toLocaleString('en-IN'), style:{fontSize:'11px',colors:'#94a3b8'} } },
    colors: ['#4338ca'],
    fill: { type:'gradient', gradient:{ shadeIntensity:1, opacityFrom:0.4, opacityTo:0.05 } },
    stroke: { width:2, curve:'smooth' },
    grid: { borderColor:'#f1f5f9', strokeDashArray:4 },
    dataLabels: { enabled:false },
    tooltip: { y:{ formatter:v=>'₹'+Number(v).toLocaleString('en-IN') } }
}).render();

new ApexCharts(document.querySelector("#bookingStatusChart"), {
    chart: { type:'donut', height:220 },
    series: [<?= $revenue['confirmed']??0 ?>, <?= $revenue['checked_in']??0 ?>, <?= $revenue['completed']??0 ?>, <?= $revenue['cancelled']??0 ?>],
    labels: ['Confirmed','Checked In','Completed','Cancelled'],
    colors: ['#f59e0b','#3b82f6','#10b981','#ef4444'],
    legend: { position:'bottom', fontSize:'12px' },
    plotOptions: { pie:{ donut:{ size:'65%' } } },
    dataLabels: { enabled:false },
}).render();

// Hotel approval actions
async function approveHotel(id) {
    const res = await fetch(`<?= BASE_URL ?>/admin/hotels/${id}/approve`, {
        method:'POST', headers:{'X-CSRF-Token':'<?= \App\Middleware\CsrfMiddleware::generate() ?>','Content-Type':'application/json'}
    });
    const data = await res.json();
    if (data.success) {
        document.getElementById('hotel-row-'+id).style.opacity='0.4';
        showToast('Hotel approved successfully!','success');
    }
}

async function rejectHotel(id) {
    if (!confirm('Are you sure you want to reject this hotel?')) return;
    const res = await fetch(`<?= BASE_URL ?>/admin/hotels/${id}/reject`, {
        method:'POST', headers:{'X-CSRF-Token':'<?= \App\Middleware\CsrfMiddleware::generate() ?>','Content-Type':'application/json'}
    });
    const data = await res.json();
    if (data.success) {
        document.getElementById('hotel-row-'+id).remove();
        showToast('Hotel rejected.','warning');
    }
}
</script>
