<div class="row g-4 fade-in">
    <div class="col-lg-3 col-sm-6">
        <div class="card stat-card">
            <div class="icon-wrap icon-indigo"><i class="fa-solid fa-building"></i></div>
            <div class="value"><?= $stats['total_hotels'] ?></div>
            <div class="label">Total Hotels</div>
            <div class="sub">Registered partners</div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="card stat-card">
            <div class="icon-wrap icon-green"><i class="fa-solid fa-bed"></i></div>
            <div class="value"><?= $stats['total_rooms'] ?></div>
            <div class="label">Total Rooms</div>
            <div class="sub">Across network</div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="card stat-card">
            <div class="icon-wrap icon-amber"><i class="fa-solid fa-bed-pulse"></i></div>
            <div class="value"><?= $stats['occupied_rooms'] ?></div>
            <div class="label">Occupied</div>
            <div class="sub">Currently booked</div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="card stat-card">
            <div class="icon-wrap icon-cyan"><i class="fa-solid fa-indian-rupee-sign"></i></div>
            <div class="value">₹<?= number_format($stats['revenue']) ?></div>
            <div class="label">Total Revenue</div>
            <div class="sub">Network earnings</div>
        </div>
    </div>
</div>

<div class="card fade-in-2">
    <div class="card-header">
        Recent Hotel Registrations
        <button class="btn btn-light" style="font-size:13px; padding:6px 14px;">View All</button>
    </div>
    <div class="card-body" style="padding:0;">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Hotel</th>
                    <th>City</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="identity-cell">
                            <div class="identity-avatar" style="background:rgba(99,102,241,0.1);color:#6366f1;">G</div>
                            <div>
                                <div class="identity-name">Grand Plaza Hotel</div>
                                <div class="identity-sub">grand.plaza@chnms.com</div>
                            </div>
                        </div>
                    </td>
                    <td>Mumbai</td>
                    <td><span class="badge-pill badge-pending"><span class="dot"></span> Pending</span></td>
                    <td>
                        <button class="btn-icon btn-icon-primary me-1"><i class="fa-solid fa-check"></i></button>
                        <button class="btn-icon btn-icon-danger"><i class="fa-solid fa-xmark"></i></button>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="identity-cell">
                            <div class="identity-avatar" style="background:rgba(34,197,94,0.1);color:#22c55e;">S</div>
                            <div>
                                <div class="identity-name">Sea View Resort</div>
                                <div class="identity-sub">seaview@chnms.com</div>
                            </div>
                        </div>
                    </td>
                    <td>Goa</td>
                    <td><span class="badge-pill badge-active"><span class="dot"></span> Active</span></td>
                    <td>
                        <button class="btn-icon btn-icon-primary"><i class="fa-solid fa-eye"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
