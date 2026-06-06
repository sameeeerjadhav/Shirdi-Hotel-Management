<!-- SECTION 1: OVERVIEW -->
<h6 style="font-size:11px; font-weight:800; color:#94a3b8; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:16px; margin-top:8px;">OVERVIEW</h6>
<div class="row g-4 mb-4 fade-in">
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fffbeb; color:#f59e0b;"><i class="fa-regular fa-comment-dots"></i></div>
            <div class="stat-card-content">
                <div class="stat-label">TOTAL HOTELS</div>
                <div class="stat-value"><?= $stats['total_hotels'] ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="stat-icon" style="background:#eff6ff; color:#3b82f6;"><i class="fa-solid fa-phone"></i></div>
            <div class="stat-card-content">
                <div class="stat-label">TOTAL ROOMS</div>
                <div class="stat-value"><?= $stats['total_rooms'] ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="stat-icon" style="background:#f5f3ff; color:#8b5cf6;"><i class="fa-solid fa-users"></i></div>
            <div class="stat-card-content">
                <div class="stat-label">OCCUPIED</div>
                <div class="stat-value"><?= $stats['occupied_rooms'] ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="stat-icon" style="background:#ecfdf5; color:#10b981;"><i class="fa-solid fa-check"></i></div>
            <div class="stat-card-content">
                <div class="stat-label">AVAILABLE</div>
                <div class="stat-value"><?= $stats['total_rooms'] - $stats['occupied_rooms'] ?></div>
            </div>
        </div>
    </div>
</div>

<!-- SECTION 2: NETWORK STATISTICS -->
<h6 style="font-size:11px; font-weight:800; color:#94a3b8; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:16px; margin-top:32px;">NETWORK STATISTICS</h6>
<div class="row g-4 mb-4 fade-in-2">
    <div class="col-md-4">
        <div class="stat-card" style="padding:16px 24px; border-radius:16px;">
            <style> .col-md-4 .stat-card::before { display: none; } </style>
            <div class="stat-icon" style="background:#4f46e5; color:white; border-radius:50%; width:44px; height:44px; font-size:18px;"><i class="fa-solid fa-indian-rupee-sign"></i></div>
            <div class="stat-card-content">
                <div class="stat-label">TOTAL REVENUE</div>
                <div class="stat-value" style="font-size:24px;">₹<?= number_format($stats['revenue']) ?></div>
                <div class="sub" style="font-size:11px;">Expected revenue</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card" style="padding:16px 24px; border-radius:16px;">
            <div class="stat-icon" style="background:#10b981; color:white; border-radius:12px; width:44px; height:44px; font-size:18px;"><i class="fa-solid fa-indian-rupee-sign"></i></div>
            <div class="stat-card-content">
                <div class="stat-label">COLLECTED</div>
                <div class="stat-value" style="font-size:24px;">₹<?= number_format($stats['revenue'] * 0.8) ?></div>
                <div class="sub" style="font-size:11px;"><span style="background:#10b981; color:white; padding:2px 6px; border-radius:4px; font-weight:600;">80% collected</span></div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card" style="padding:16px 24px; border-radius:16px;">
            <div class="stat-icon" style="background:#f59e0b; color:white; border-radius:50%; width:44px; height:44px; font-size:18px;"><i class="fa-regular fa-clock"></i></div>
            <div class="stat-card-content">
                <div class="stat-label">PENDING</div>
                <div class="stat-value" style="font-size:24px;">₹<?= number_format($stats['revenue'] * 0.2) ?></div>
                <div class="sub" style="font-size:11px;">Outstanding amount</div>
            </div>
        </div>
    </div>
</div>

<!-- SECTION 3: PROGRESS -->
<div class="card mb-4 fade-in-3" style="border:1px solid #c4b5fd; border-radius:16px; box-shadow:0 4px 12px rgba(139,92,246,0.05);">
    <div style="padding:20px 24px;">
        <h6 style="font-size:11px; font-weight:800; color:#1e293b; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:12px;">OCCUPANCY PROGRESS</h6>
        <div style="width:100%; height:16px; background:#e2e8f0; border-radius:99px; overflow:hidden;">
            <?php 
                $percentage = $stats['total_rooms'] > 0 ? ($stats['occupied_rooms'] / $stats['total_rooms']) * 100 : 0;
            ?>
            <div style="width:<?= $percentage ?>%; height:100%; background:linear-gradient(90deg, #8b5cf6, #c4b5fd); border-radius:99px;"></div>
        </div>
        <div style="font-size:11px; font-weight:600; color:#64748b; margin-top:8px;">
            <?= $stats['occupied_rooms'] ?> of <?= $stats['total_rooms'] ?> Rooms Occupied
        </div>
    </div>
</div>

<!-- SECTION 4: TABLE -->
<h6 style="font-size:11px; font-weight:800; color:#94a3b8; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:16px; margin-top:32px;">RECENT REGISTRATIONS</h6>
<div class="card fade-in-3">
    <div class="card-header" style="padding:16px 24px; display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid var(--border);">
        <div style="display:flex; align-items:center; gap:12px;">
            <div style="width:32px; height:32px; background:#ecfdf5; color:#10b981; border-radius:8px; display:flex; align-items:center; justify-content:center;"><i class="fa-solid fa-building"></i></div>
            <span style="font-size:13px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.05em;">LATEST HOTELS</span>
        </div>
        <div style="font-size:12px; font-weight:700; color:#1e293b; display:flex; align-items:center; gap:6px;">
            All Time <i class="fa-solid fa-chevron-down"></i>
        </div>
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
