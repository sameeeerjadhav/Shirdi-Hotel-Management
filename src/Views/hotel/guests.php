<div class="page-header">
    <div>
        <h2>Guest Management</h2>
        <p>All guests who have booked or stayed at your hotel</p>
    </div>
    <div style="display:flex;gap:10px;align-items:center;">
        <a href="<?= BASE_URL ?>/hotel/export/guests" class="btn btn-light">
            <i class="fa-solid fa-file-csv me-1"></i>Export CSV
        </a>
        <form method="GET" style="display:flex;gap:8px;">
            <input type="text" name="search" class="form-control"
                   value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                   placeholder="Search name, email, phone..."
                   style="width:220px;">
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </form>
    </div>
</div>

<!-- STAT CARDS -->
<div class="row g-3 mb-4">
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-card-content">
                <div class="stat-label">TOTAL GUESTS</div>
                <div class="stat-value"><?= count($guests) ?></div>
                <div class="sub">All time</div>
            </div>
            <div class="stat-icon icon-indigo"><i class="fa-solid fa-users"></i></div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-card-content">
                <div class="stat-label">CURRENTLY STAYING</div>
                <div class="stat-value"><?= $guestStats['checked_in'] ?? 0 ?></div>
                <div class="sub">Active guests</div>
            </div>
            <div class="stat-icon icon-green"><i class="fa-solid fa-bed"></i></div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-card-content">
                <div class="stat-label">THIS MONTH</div>
                <div class="stat-value"><?= $guestStats['this_month'] ?? 0 ?></div>
                <div class="sub">New guests</div>
            </div>
            <div class="stat-icon icon-cyan"><i class="fa-solid fa-calendar-day"></i></div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-card-content">
                <div class="stat-label">REPEAT GUESTS</div>
                <div class="stat-value"><?= $guestStats['repeat'] ?? 0 ?></div>
                <div class="sub">2+ bookings</div>
            </div>
            <div class="stat-icon icon-amber"><i class="fa-solid fa-star"></i></div>
        </div>
    </div>
</div>

<!-- GUEST TABLE -->
<div class="card">
    <div class="card-header">
        <span><i class="fa-solid fa-users me-2" style="color:var(--primary);"></i>Guest List</span>
        <span style="font-size:12px;color:var(--text-muted);"><?= count($guests) ?> guest(s)</span>
    </div>
    <div class="card-body" style="padding:0;">
        <table class="table mb-0">
            <thead><tr>
                <th>Guest</th><th>Email</th><th>Phone</th>
                <th>Total Stays</th><th>Total Spent</th><th>Last Stay</th><th>Action</th>
            </tr></thead>
            <tbody>
            <?php if (empty($guests)): ?>
            <tr><td colspan="7" style="text-align:center;padding:48px;color:#94a3b8;">
                <div style="width:52px;height:52px;background:#eef2ff;border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                    <i class="fa-solid fa-users" style="font-size:20px;color:#6366f1;"></i>
                </div>
                No guests found.
            </td></tr>
            <?php else: foreach ($guests as $g): ?>
            <tr>
                <td>
                    <div class="identity-cell">
                        <div class="identity-avatar" style="background:<?= '#'.substr(md5($g['guest_name']),0,6) ?>22;color:#<?= substr(md5($g['guest_name']),0,6) ?>;">
                            <?= strtoupper(substr($g['guest_name'],0,1)) ?>
                        </div>
                        <div>
                            <div class="identity-name"><?= htmlspecialchars($g['guest_name']) ?></div>
                            <div class="identity-sub">Guest</div>
                        </div>
                    </div>
                </td>
                <td style="font-size:13px;"><?= htmlspecialchars($g['guest_email'] ?? '—') ?></td>
                <td style="font-size:13px;"><?= htmlspecialchars($g['guest_phone'] ?? '—') ?></td>
                <td>
                    <span class="badge-pill <?= ($g['stay_count']??0) > 1 ? 'badge-active' : 'badge-cleaning' ?>">
                        <span class="dot"></span><?= $g['stay_count'] ?? 1 ?> stay(s)
                    </span>
                </td>
                <td style="font-weight:700;color:#10b981;">₹<?= number_format($g['total_spent'] ?? 0) ?></td>
                <td style="font-size:12px;color:var(--text-muted);"><?= isset($g['last_stay']) ? date('d M Y', strtotime($g['last_stay'])) : '—' ?></td>
                <td>
                    <a href="<?= BASE_URL ?>/hotel/bookings?search=<?= urlencode($g['guest_email'] ?? $g['guest_name']) ?>"
                       class="btn btn-light" style="font-size:12px;padding:5px 12px;">
                        <i class="fa-solid fa-calendar me-1"></i>Bookings
                    </a>
                </td>
            </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
