<?php $transfers = $transfers ?? []; ?>
<div class="page-header">
    <div>
        <h2>Transfer Requests</h2>
        <p>Room transfer requests made for your hotel's guests</p>
    </div>
</div>

<!-- STATUS FILTER -->
<div style="display:flex;gap:8px;margin-bottom:20px;flex-wrap:wrap;">
    <?php $statuses = ['all'=>'All','pending'=>'Pending','approved'=>'Approved','rejected'=>'Rejected']; ?>
    <?php foreach ($statuses as $val => $label):
        $active = ($activeFilter ?? 'all') === $val;
    ?>
    <a href="?status=<?= $val ?>" class="btn <?= $active ? 'btn-primary' : 'btn-light' ?>" style="font-size:12px;padding:7px 16px;">
        <?= $label ?>
        <?php if ($val === 'pending' && ($pendingCount ?? 0) > 0): ?>
        <span class="btn-badge"><?= $pendingCount ?></span>
        <?php endif; ?>
    </a>
    <?php endforeach; ?>
</div>

<!-- TRANSFER TABLE -->
<div class="card">
    <div class="card-header">
        <span><i class="fa-solid fa-right-left me-2" style="color:var(--primary);"></i>Transfer Requests</span>
        <span style="font-size:12px;color:var(--text-muted);"><?= count($transfers) ?> record(s)</span>
    </div>
    <div class="card-body" style="padding:0;">
        <table class="table mb-0">
            <thead><tr>
                <th>Booking</th><th>Guest</th><th>From Room</th>
                <th>To Room</th><th>Reason</th><th>Status</th><th>Date</th>
            </tr></thead>
            <tbody>
            <?php if (empty($transfers)): ?>
            <tr><td colspan="7" style="text-align:center;padding:48px;color:#94a3b8;">
                <div style="width:52px;height:52px;background:#eef2ff;border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                    <i class="fa-solid fa-right-left" style="font-size:20px;color:#6366f1;"></i>
                </div>
                No transfer requests found.
            </td></tr>
            <?php else: foreach ($transfers as $t):
                $sc = ['pending'=>'badge-pending','approved'=>'badge-active','rejected'=>'badge-occupied'];
                $bc = $sc[$t['status']] ?? 'badge-pending';
            ?>
            <tr>
                <td><span style="font-family:monospace;font-weight:700;color:var(--primary);"><?= htmlspecialchars($t['booking_ref'] ?? '#'.$t['booking_id']) ?></span></td>
                <td><?= htmlspecialchars($t['guest_name'] ?? '—') ?></td>
                <td>
                    <span style="background:#fef3c7;color:#92400e;padding:3px 8px;border-radius:6px;font-size:12px;font-weight:600;">
                        <?= htmlspecialchars($t['from_room'] ?? '—') ?>
                    </span>
                </td>
                <td>
                    <span style="background:#ecfdf5;color:#065f46;padding:3px 8px;border-radius:6px;font-size:12px;font-weight:600;">
                        <?= htmlspecialchars($t['to_room'] ?? '—') ?>
                    </span>
                </td>
                <td style="font-size:12px;color:var(--text-muted);max-width:160px;">
                    <?= htmlspecialchars($t['reason'] ?? '—') ?>
                </td>
                <td><span class="badge-pill <?= $bc ?>"><span class="dot"></span><?= ucfirst($t['status']) ?></span></td>
                <td style="font-size:12px;color:var(--text-muted);"><?= isset($t['created_at']) ? date('d M Y', strtotime($t['created_at'])) : '—' ?></td>
            </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
