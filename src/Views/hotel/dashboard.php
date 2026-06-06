<div class="row g-4 fade-in">
    <div class="col-lg-3 col-sm-6">
        <div class="card stat-card">
            <div class="icon-wrap icon-indigo"><i class="fa-solid fa-door-open"></i></div>
            <div class="value"><?= $stats['total_rooms'] ?></div>
            <div class="label">Total Rooms</div>
            <div class="sub">In your inventory</div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="card stat-card">
            <div class="icon-wrap icon-red"><i class="fa-solid fa-bed"></i></div>
            <div class="value"><?= $stats['occupied_rooms'] ?></div>
            <div class="label">Occupied</div>
            <div class="sub">Guests currently in</div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="card stat-card">
            <div class="icon-wrap icon-green"><i class="fa-solid fa-circle-check"></i></div>
            <div class="value"><?= $stats['available_rooms'] ?></div>
            <div class="label">Available</div>
            <div class="sub">Ready for booking</div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="card stat-card">
            <div class="icon-wrap icon-amber"><i class="fa-solid fa-indian-rupee-sign"></i></div>
            <div class="value">₹<?= number_format($stats['revenue']) ?></div>
            <div class="label">Revenue</div>
            <div class="sub">Earnings to date</div>
        </div>
    </div>
</div>

<div class="row fade-in-2">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">Today's Activity</div>
            <div class="card-body">
                <div style="display:flex; align-items:center; justify-content:space-between; padding:12px 0; border-bottom:1px solid var(--border);">
                    <div style="display:flex; align-items:center; gap:12px;">
                        <div style="width:36px;height:36px;background:rgba(34,197,94,0.1);border-radius:9px;display:flex;align-items:center;justify-content:center;color:#22c55e;">
                            <i class="fa-solid fa-arrow-right-to-bracket"></i>
                        </div>
                        <span style="font-size:14px; font-weight:600; color:var(--text-heading);">Check-ins</span>
                    </div>
                    <span style="font-size:20px; font-weight:800; color:#22c55e;"><?= $stats['today_checkins'] ?></span>
                </div>
                <div style="display:flex; align-items:center; justify-content:space-between; padding:12px 0;">
                    <div style="display:flex; align-items:center; gap:12px;">
                        <div style="width:36px;height:36px;background:rgba(239,68,68,0.1);border-radius:9px;display:flex;align-items:center;justify-content:center;color:#ef4444;">
                            <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        </div>
                        <span style="font-size:14px; font-weight:600; color:var(--text-heading);">Check-outs</span>
                    </div>
                    <span style="font-size:20px; font-weight:800; color:#ef4444;"><?= $stats['today_checkouts'] ?></span>
                </div>
            </div>
        </div>
    </div>
</div>
