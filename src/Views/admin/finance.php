<?php $r = $revenue ?? []; ?>
<div class="page-header">
    <div>
        <h2>Finance & Revenue</h2>
        <p>Platform earnings and transaction overview</p>
    </div>
</div>

<!-- REVENUE CARDS -->
<div class="row g-3 mb-4">
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-icon icon-indigo"><i class="fa-solid fa-indian-rupee-sign"></i></div>
            <div class="stat-card-content">
                <div class="stat-label">TOTAL REVENUE (30D)</div>
                <div class="stat-value" style="font-size:20px;">₹<?= number_format($r['total_revenue'] ?? 0) ?></div>
                <div class="sub"><?= $r['total_bookings'] ?? 0 ?> bookings</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-icon icon-green"><i class="fa-solid fa-check-double"></i></div>
            <div class="stat-card-content">
                <div class="stat-label">CONFIRMED</div>
                <div class="stat-value" style="font-size:20px;"><?= $r['confirmed'] ?? 0 ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-icon icon-cyan"><i class="fa-solid fa-bed"></i></div>
            <div class="stat-card-content">
                <div class="stat-label">CHECKED IN</div>
                <div class="stat-value" style="font-size:20px;"><?= $r['checked_in'] ?? 0 ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-icon icon-amber"><i class="fa-solid fa-times-circle"></i></div>
            <div class="stat-card-content">
                <div class="stat-label">CANCELLED</div>
                <div class="stat-value" style="font-size:20px;"><?= $r['cancelled'] ?? 0 ?></div>
            </div>
        </div>
    </div>
</div>

<!-- REVENUE CHART -->
<div class="card mb-4">
    <div class="card-header">Revenue Trend — Last 30 Days</div>
    <div class="card-body"><div id="financeChart"></div></div>
</div>

<!-- RECENT TRANSACTIONS -->
<div class="card">
    <div class="card-header">
        Recent Transactions
        <a href="<?= BASE_URL ?>/admin/bookings" class="btn btn-light" style="font-size:12px;padding:5px 12px;">All Bookings</a>
    </div>
    <div class="card-body" style="padding:0;">
        <table class="table mb-0">
            <thead><tr><th>Booking</th><th>Guest</th><th>Hotel</th><th>Amount</th><th>Status</th><th>Date</th></tr></thead>
            <tbody>
                <?php if (empty($bookings)): ?>
                <tr><td colspan="6" style="text-align:center;padding:40px;color:#94a3b8;">No transactions yet.</td></tr>
                <?php else: ?>
                <?php foreach ($bookings as $b):
                    $ref = $b['booking_ref'] ?? ('#' . $b['id']);
                    $bsc = ['confirmed'=>'badge-pending','checked_in'=>'badge-active','checked_out'=>'badge-cleaning','cancelled'=>'badge-occupied'];
                    $bc  = $bsc[$b['status']] ?? 'badge-pending';
                ?>
                <tr>
                    <td><a href="<?= BASE_URL ?>/admin/bookings/<?= $b['id'] ?>" style="font-family:monospace;font-weight:700;color:#4338ca;"><?= htmlspecialchars($ref) ?></a></td>
                    <td><?= htmlspecialchars($b['guest_name']) ?></td>
                    <td><?= htmlspecialchars($b['hotel_name']) ?></td>
                    <td style="font-weight:700;color:#10b981;">₹<?= number_format($b['total_amount']) ?></td>
                    <td><span class="badge-pill <?= $bc ?>"><span class="dot"></span><?= ucfirst(str_replace('_',' ',$b['status'])) ?></span></td>
                    <td style="font-size:12px;color:#64748b;"><?= date('d M Y', strtotime($b['created_at'])) ?></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
const daily = <?= $dailyRevenue ?>;
new ApexCharts(document.querySelector("#financeChart"), {
    chart: { type:'area', height:240, toolbar:{show:false} },
    series: [{ name:'Revenue ₹', data: daily.map(d=>parseFloat(d.revenue)) }],
    xaxis: { categories: daily.map(d=>d.date), labels:{ style:{fontSize:'11px',colors:'#94a3b8'} } },
    yaxis: { labels:{ formatter:v=>'₹'+Number(v).toLocaleString('en-IN'), style:{fontSize:'11px',colors:'#94a3b8'} } },
    colors: ['#4338ca'],
    fill:   { type:'gradient', gradient:{ shadeIntensity:1, opacityFrom:0.4, opacityTo:0.05 } },
    stroke: { width:2, curve:'smooth' },
    grid:   { borderColor:'#f1f5f9', strokeDashArray:4 },
    dataLabels: { enabled:false },
    tooltip:    { y:{ formatter:v=>'₹'+Number(v).toLocaleString('en-IN') } }
}).render();
</script>
