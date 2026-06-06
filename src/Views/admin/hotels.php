<div class="page-header fade-in">
    <div>
        <h2>Hotel Management</h2>
        <p>Review and manage all registered partner hotels in the network.</p>
    </div>
</div>

<div class="card fade-in-2">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Registered Hotels</span>
        <button class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-filter me-1"></i> Filter</button>
    </div>
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Hotel Name</th>
                    <th>Location</th>
                    <th>Total Rooms</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($hotels as $hotel): ?>
                <tr>
                    <td>
                        <div class="identity-cell">
                            <div class="identity-avatar" style="background:var(--primary-light);color:var(--primary);">
                                <?= strtoupper(substr($hotel['name'], 0, 1)) ?>
                            </div>
                            <div>
                                <div class="identity-name"><?= htmlspecialchars($hotel['name']) ?></div>
                                <div class="identity-sub"><?= htmlspecialchars($hotel['email']) ?></div>
                            </div>
                        </div>
                    </td>
                    <td><?= htmlspecialchars($hotel['city']) ?></td>
                    <td><span style="font-weight:600; color:var(--text-heading);"><?= htmlspecialchars($hotel['rooms']) ?></span></td>
                    <td>
                        <?php if($hotel['status'] == 'Active'): ?>
                            <span class="badge-pill badge-active"><span class="dot"></span> Active</span>
                        <?php else: ?>
                            <span class="badge-pill badge-pending"><span class="dot"></span> Pending</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <button class="btn-icon btn-icon-primary me-1" title="View"><i class="fa-solid fa-eye"></i></button>
                        <?php if($hotel['status'] == 'Pending'): ?>
                            <button class="btn-icon" style="background:rgba(34,197,94,0.1);color:#22c55e;" title="Approve"><i class="fa-solid fa-check"></i></button>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
