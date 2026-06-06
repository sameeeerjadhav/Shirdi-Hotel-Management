<div class="row fade-in">
    <div class="col-lg-3 col-md-6">
        <div class="card p-1">
            <div class="card-body stat-card">
                <div class="stat-icon blue">
                    <i class="fa-solid fa-door-open"></i>
                </div>
                <div class="stat-details">
                    <h3><?= $stats['total_rooms'] ?></h3>
                    <p>Total Rooms</p>
                    <div class="sub-text">In your inventory</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card p-1">
            <div class="card-body stat-card">
                <div class="stat-icon red">
                    <i class="fa-solid fa-bed"></i>
                </div>
                <div class="stat-details">
                    <h3><?= $stats['occupied_rooms'] ?></h3>
                    <p>Occupied</p>
                    <div class="sub-text">Guests currently in</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card p-1">
            <div class="card-body stat-card">
                <div class="stat-icon green">
                    <i class="fa-solid fa-check"></i>
                </div>
                <div class="stat-details">
                    <h3><?= $stats['available_rooms'] ?></h3>
                    <p>Available</p>
                    <div class="sub-text">Ready for booking</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card p-1">
            <div class="card-body stat-card">
                <div class="stat-icon orange">
                    <i class="fa-solid fa-wallet"></i>
                </div>
                <div class="stat-details">
                    <h3>₹<?= number_format($stats['revenue'], 2) ?></h3>
                    <p>Revenue</p>
                    <div class="sub-text">Earnings to date</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-2 fade-in">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                Today's Activity
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between border-bottom pb-3 mb-3" style="border-color: var(--border-color) !important;">
                    <span style="font-size: 15px; font-weight: 500;">Check-ins</span>
                    <span class="status-badge active fs-6"><span class="dot"></span> <?= $stats['today_checkins'] ?></span>
                </div>
                <div class="d-flex justify-content-between">
                    <span style="font-size: 15px; font-weight: 500;">Check-outs</span>
                    <span class="status-badge inactive fs-6"><span class="dot"></span> <?= $stats['today_checkouts'] ?></span>
                </div>
            </div>
        </div>
    </div>
</div>
