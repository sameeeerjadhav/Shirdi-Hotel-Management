<div class="page-header fade-in">
    <div>
        <h2>Network Bookings</h2>
        <p>Monitor all reservations across the entire CHNMS hotel network.</p>
    </div>
</div>

<div class="card fade-in-2">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Recent Bookings</span>
        <button class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-download me-1"></i> Export</button>
    </div>
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Guest</th>
                    <th>Hotel</th>
                    <th>Stay Dates</th>
                    <th>Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($bookings as $booking): ?>
                <tr>
                    <td><span style="font-family:monospace;font-weight:600;color:var(--text-heading);"><?= htmlspecialchars($booking['id']) ?></span></td>
                    <td>
                        <div style="font-weight:600;color:var(--text-heading);"><?= htmlspecialchars($booking['guest']) ?></div>
                    </td>
                    <td><span style="font-weight:500;color:var(--primary);"><?= htmlspecialchars($booking['hotel']) ?></span></td>
                    <td>
                        <div style="font-size:13px;color:var(--text-body);">
                            <div><i class="fa-solid fa-arrow-right-to-bracket text-muted" style="width:14px;"></i> <?= htmlspecialchars($booking['checkin']) ?></div>
                            <div><i class="fa-solid fa-arrow-right-from-bracket text-muted" style="width:14px;"></i> <?= htmlspecialchars($booking['checkout']) ?></div>
                        </div>
                    </td>
                    <td><span style="font-weight:700;color:var(--text-heading);">₹<?= number_format($booking['amount']) ?></span></td>
                    <td>
                        <?php if($booking['status'] == 'Confirmed' || $booking['status'] == 'Completed'): ?>
                            <span class="badge-pill badge-active"><span class="dot"></span> <?= htmlspecialchars($booking['status']) ?></span>
                        <?php else: ?>
                            <span class="badge-pill badge-pending"><span class="dot"></span> <?= htmlspecialchars($booking['status']) ?></span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
