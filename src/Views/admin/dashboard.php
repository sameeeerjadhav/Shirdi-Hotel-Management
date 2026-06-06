<div class="row fade-in">
    <div class="col-lg-3 col-md-6">
        <div class="card p-1">
            <div class="card-body stat-card">
                <div class="stat-icon blue">
                    <i class="fa-solid fa-hotel"></i>
                </div>
                <div class="stat-details">
                    <h3><?= $stats['total_hotels'] ?></h3>
                    <p>Total Hotels</p>
                    <div class="sub-text">All registered partners</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card p-1">
            <div class="card-body stat-card">
                <div class="stat-icon green">
                    <i class="fa-solid fa-bed"></i>
                </div>
                <div class="stat-details">
                    <h3><?= $stats['total_rooms'] ?></h3>
                    <p>Total Rooms</p>
                    <div class="sub-text">Across network</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card p-1">
            <div class="card-body stat-card">
                <div class="stat-icon orange">
                    <i class="fa-solid fa-bed-pulse"></i>
                </div>
                <div class="stat-details">
                    <h3><?= $stats['occupied_rooms'] ?></h3>
                    <p>Occupied</p>
                    <div class="sub-text">Currently booked</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card p-1">
            <div class="card-body stat-card">
                <div class="stat-icon red">
                    <i class="fa-solid fa-wallet"></i>
                </div>
                <div class="stat-details">
                    <h3>₹<?= number_format($stats['revenue'], 2) ?></h3>
                    <p>Total Revenue</p>
                    <div class="sub-text">Network earnings</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mt-2 fade-in">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Recent Hotel Registrations</span>
        <button class="btn btn-sm btn-outline-secondary" style="border-radius: 8px;">View All</button>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Hotel</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="user-avatar" style="background: rgba(67, 24, 255, 0.1); color: var(--primary-color);">G</div>
                                <div>
                                    <span style="font-weight: 600; font-size: 14px;">Grand Plaza Hotel</span><br>
                                    <span style="font-size: 13px; color: var(--text-muted);">grand.plaza@chnms.com</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span style="font-size: 14px;">Mumbai</span>
                        </td>
                        <td>
                            <span class="status-badge inactive"><span class="dot"></span> Pending</span>
                        </td>
                        <td>
                            <button class="btn btn-sm text-primary" style="background: rgba(67,24,255,0.1);"><i class="fa-solid fa-check"></i></button>
                            <button class="btn btn-sm text-danger" style="background: rgba(238,93,80,0.1);"><i class="fa-solid fa-xmark"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="user-avatar" style="background: rgba(5, 205, 153, 0.1); color: #05cd99;">S</div>
                                <div>
                                    <span style="font-weight: 600; font-size: 14px;">Sea View Resort</span><br>
                                    <span style="font-size: 13px; color: var(--text-muted);">seaview@chnms.com</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span style="font-size: 14px;">Goa</span>
                        </td>
                        <td>
                            <span class="status-badge active"><span class="dot"></span> Active</span>
                        </td>
                        <td>
                            <button class="btn btn-sm text-primary" style="background: rgba(67,24,255,0.1);">View</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
