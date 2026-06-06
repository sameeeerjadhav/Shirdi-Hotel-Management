<div class="row fade-in">
    <div class="col-12 mb-4">
        <h2 style="color: #e14eca;">Hotel Dashboard</h2>
        <p class="text-muted">Manage your rooms, check live occupancy, and view daily stats.</p>
    </div>
</div>

<div class="row fade-in">
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card card-stats p-3 border-0 shadow-glass glass-card" style="border-left: 4px solid #00f2c3 !important;">
            <div class="card-body">
                <div class="row">
                    <div class="col-5">
                        <div class="icon-big text-center icon-warning">
                            <i class="text-success" style="font-size: 32px;">🛏️</i>
                        </div>
                    </div>
                    <div class="col-7">
                        <div class="numbers">
                            <p class="card-category text-muted mb-0">Total Rooms</p>
                            <h3 class="card-title text-white mb-0"><?= $stats['total_rooms'] ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card card-stats p-3 border-0 shadow-glass glass-card" style="border-left: 4px solid #fd5d93 !important;">
            <div class="card-body">
                <div class="row">
                    <div class="col-5">
                        <div class="icon-big text-center icon-warning">
                            <i class="text-danger" style="font-size: 32px;">🗝️</i>
                        </div>
                    </div>
                    <div class="col-7">
                        <div class="numbers">
                            <p class="card-category text-muted mb-0">Occupied</p>
                            <h3 class="card-title text-white mb-0"><?= $stats['occupied_rooms'] ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card card-stats p-3 border-0 shadow-glass glass-card" style="border-left: 4px solid #ff8d72 !important;">
            <div class="card-body">
                <div class="row">
                    <div class="col-5">
                        <div class="icon-big text-center icon-warning">
                            <i class="text-warning" style="font-size: 32px;">🚪</i>
                        </div>
                    </div>
                    <div class="col-7">
                        <div class="numbers">
                            <p class="card-category text-muted mb-0">Available</p>
                            <h3 class="card-title text-white mb-0"><?= $stats['available_rooms'] ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card card-stats p-3 border-0 shadow-glass glass-card" style="border-left: 4px solid #1d8cf8 !important;">
            <div class="card-body">
                <div class="row">
                    <div class="col-5">
                        <div class="icon-big text-center icon-warning">
                            <i class="text-info" style="font-size: 32px;">💰</i>
                        </div>
                    </div>
                    <div class="col-7">
                        <div class="numbers">
                            <p class="card-category text-muted mb-0">Revenue</p>
                            <h3 class="card-title text-white mb-0">₹<?= number_format($stats['revenue'], 2) ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-2 fade-in">
    <div class="col-md-6">
        <div class="card glass-card">
            <div class="card-header">
                <h4 class="card-title text-white mb-0">Today's Activity</h4>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between border-bottom border-secondary pb-3 mb-3">
                    <span class="text-white fs-5">Check-ins</span>
                    <span class="badge bg-success fs-5"><?= $stats['today_checkins'] ?></span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-white fs-5">Check-outs</span>
                    <span class="badge bg-danger fs-5"><?= $stats['today_checkouts'] ?></span>
                </div>
            </div>
        </div>
    </div>
</div>
