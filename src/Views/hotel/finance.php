<?php $r = $revenue ?? []; ?>
<div class="page-header">
    <div>
        <h2>Finance & Revenue</h2>
        <p>Your hotel's earnings, payouts and transaction history</p>
    </div>
    <div style="display:flex;gap:10px;">
        <a href="<?= BASE_URL ?>/hotel/bookings" class="btn btn-light">
            <i class="fa-solid fa-calendar-check me-1"></i>All Bookings
        </a>
    </div>
</div>

<!-- STAT CARDS -->
<div class="row g-3 mb-4">
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-card-content">
                <div class="stat-label">TOTAL REVENUE (30D)</div>
                <div class="stat-value">₹<?= number_format($r['total_revenue'] ?? 0) ?></div>
                <div class="sub"><?= $r['total_bookings'] ?? 0 ?> bookings</div>
            </div>
            <div class="stat-icon icon-indigo"><i class="fa-solid fa-indian-rupee-sign"></i></div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-card-content">
                <div class="stat-label">CONFIRMED</div>
                <div class="stat-value"><?= $r['confirmed'] ?? 0 ?></div>
                <div class="sub">Active bookings</div>
            </div>
            <div class="stat-icon icon-green"><i class="fa-solid fa-check-double"></i></div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-card-content">
                <div class="stat-label">CHECKED IN</div>
                <div class="stat-value"><?= $r['checked_in'] ?? 0 ?></div>
                <div class="sub">Currently staying</div>
            </div>
            <div class="stat-icon icon-cyan"><i class="fa-solid fa-bed"></i></div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-card-content">
                <div class="stat-label">CANCELLED</div>
                <div class="stat-value"><?= $r['cancelled'] ?? 0 ?></div>
                <div class="sub">Refunded</div>
            </div>
            <div class="stat-icon icon-amber"><i class="fa-solid fa-circle-xmark"></i></div>
        </div>
    </div>
</div>

<!-- REVENUE CHART -->
<div class="card mb-4">
    <div class="card-header">
        <span><i class="fa-solid fa-chart-area me-2" style="color:var(--primary);"></i>Revenue Trend — Last 30 Days</span>
    </div>
    <div class="card-body"><div id="hotelFinanceChart"></div></div>
</div>

<!-- TRANSACTIONS TABLE -->
<div class="card">
    <div class="card-header">
        <span><i class="fa-solid fa-receipt me-2" style="color:var(--primary);"></i>Transaction History</span>
    </div>
    <div class="card-body" style="padding:0;">
        <table class="table mb-0">
            <thead><tr>
                <th>Booking Ref</th><th>Guest</th><th>Room</th>
                <th>Nights</th><th>Amount</th><th>Status</th><th>Date</th>
            </tr></thead>
            <tbody>
            <?php if (empty($bookings)): ?>
            <tr><td colspan="7" style="text-align:center;padding:48px;color:#94a3b8;">
                <div style="width:52px;height:52px;background:#eef2ff;border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                    <i class="fa-solid fa-receipt" style="font-size:20px;color:#6366f1;"></i>
                </div>
                No transactions yet.
            </td></tr>
            <?php else: foreach ($bookings as $b):
                $ref = $b['booking_ref'] ?? ('#'.$b['id']);
                $bsc = ['confirmed'=>'badge-pending','checked_in'=>'badge-active','checked_out'=>'badge-cleaning','cancelled'=>'badge-occupied'];
                $bc  = $bsc[$b['status']] ?? 'badge-pending';
            ?>
            <tr>
                <td><span style="font-family:monospace;font-weight:700;color:var(--primary);"><?= htmlspecialchars($ref) ?></span></td>
                <td><?= htmlspecialchars($b['guest_name'] ?? '—') ?></td>
                <td><?= htmlspecialchars($b['room_number'] ?? '—') ?></td>
                <td><?= $b['nights'] ?? '—' ?></td>
                <td style="font-weight:700;color:#10b981;">₹<?= number_format($b['total_amount']) ?></td>
                <td><span class="badge-pill <?= $bc ?>"><span class="dot"></span><?= ucfirst(str_replace('_',' ',$b['status'])) ?></span></td>
                <td style="font-size:12px;color:#64748b;"><?= date('d M Y', strtotime($b['created_at'])) ?></td>
            </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
const hotelDaily = <?= $dailyRevenue ?? '[]' ?>;
new ApexCharts(document.querySelector("#hotelFinanceChart"), {
    chart: { type:'area', height:240, toolbar:{show:false}, fontFamily:'Inter,sans-serif' },
    series: [{ name:'Revenue ₹', data: hotelDaily.map(d=>parseFloat(d.revenue)) }],
    xaxis: { categories: hotelDaily.map(d=>d.date), labels:{ style:{fontSize:'11px',colors:'#94a3b8'} } },
    yaxis: { labels:{ formatter:v=>'₹'+Number(v).toLocaleString('en-IN'), style:{fontSize:'11px',colors:'#94a3b8'} } },
    colors: ['#6366f1'],
    fill:   { type:'gradient', gradient:{ shadeIntensity:1, opacityFrom:0.35, opacityTo:0.02 } },
    stroke: { width:2.5, curve:'smooth' },
    grid:   { borderColor:'#f1f5f9', strokeDashArray:4 },
    dataLabels: { enabled:false },
    tooltip:    { y:{ formatter:v=>'₹'+Number(v).toLocaleString('en-IN') } }
}).render();
</script>
