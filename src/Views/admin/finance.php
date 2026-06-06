<div class="page-header fade-in">
    <div>
        <h2>Finance & Revenue</h2>
        <p>Monitor platform fees, revenue share, and recent transactions.</p>
    </div>
</div>

<div class="row fade-in-2 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(34, 197, 94, 0.1); color: var(--success);">
                <i class="fa-solid fa-indian-rupee-sign"></i>
            </div>
            <div class="stat-label">Total Platform Revenue</div>
            <div class="stat-value">₹8,450.00</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(245, 158, 11, 0.1); color: var(--warning);">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>
            <div class="stat-label">Pending Settlements</div>
            <div class="stat-value">₹1,200.00</div>
        </div>
    </div>
</div>

<div class="card fade-in-2">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Recent Transactions</span>
        <button class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-download me-1"></i> Statement</button>
    </div>
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Transaction ID</th>
                    <th>Date</th>
                    <th>Hotel Partner</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($transactions as $txn): ?>
                <tr>
                    <td><span style="font-family:monospace;font-weight:600;color:var(--text-heading);"><?= htmlspecialchars($txn['id']) ?></span></td>
                    <td><?= htmlspecialchars($txn['date']) ?></td>
                    <td><span style="font-weight:500;color:var(--text-heading);"><?= htmlspecialchars($txn['hotel']) ?></span></td>
                    <td><?= htmlspecialchars($txn['type']) ?></td>
                    <td><span style="font-weight:700;color:var(--success);">+₹<?= number_format($txn['amount']) ?></span></td>
                    <td>
                        <?php if($txn['status'] == 'Paid'): ?>
                            <span class="badge-pill badge-active"><span class="dot"></span> Paid</span>
                        <?php else: ?>
                            <span class="badge-pill badge-pending"><span class="dot"></span> Pending</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
