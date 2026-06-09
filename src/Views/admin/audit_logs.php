<?php $logs = $logs ?? []; ?>
<div class="page-header">
    <div>
        <h2>Audit Logs</h2>
        <p>System activity trail — every create, update, delete and login action</p>
    </div>
    <div style="display:flex;gap:10px;align-items:center;">
        <form method="GET" style="display:flex;gap:8px;">
            <input type="text" name="search" class="form-control"
                   value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                   placeholder="Search action, user, model..."
                   style="width:220px;">
            <select name="action_type" class="form-select" style="width:160px;">
                <option value="">All Actions</option>
                <?php foreach (['room_created','room_updated','room_deleted','checkin','checkout','hotel_created','hotel_updated','hotel_approved','hotel_rejected','transfer_approved','transfer_rejected','booking_cancelled','user_created','user_deleted'] as $a): ?>
                <option value="<?= $a ?>" <?= ($_GET['action_type'] ?? '') === $a ? 'selected' : '' ?>><?= ucfirst(str_replace('_',' ',$a)) ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i></button>
            <a href="<?= BASE_URL ?>/admin/audit-logs" class="btn btn-light">Reset</a>
        </form>
    </div>
</div>

<!-- SUMMARY CARDS -->
<div class="row g-3 mb-4">
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-card-content">
                <div class="stat-label">TOTAL EVENTS</div>
                <div class="stat-value"><?= number_format($totalLogs ?? count($logs)) ?></div>
                <div class="sub">All time</div>
            </div>
            <div class="stat-icon icon-indigo"><i class="fa-solid fa-list-check"></i></div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-card-content">
                <div class="stat-label">TODAY</div>
                <div class="stat-value"><?= $todayLogs ?? 0 ?></div>
                <div class="sub">Actions today</div>
            </div>
            <div class="stat-icon icon-green"><i class="fa-solid fa-clock-rotate-left"></i></div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-card-content">
                <div class="stat-label">THIS WEEK</div>
                <div class="stat-value"><?= $weekLogs ?? 0 ?></div>
                <div class="sub">7-day activity</div>
            </div>
            <div class="stat-icon icon-cyan"><i class="fa-solid fa-calendar-week"></i></div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-card-content">
                <div class="stat-label">ACTIVE USERS</div>
                <div class="stat-value"><?= $activeUsers ?? 0 ?></div>
                <div class="sub">Logged actions this week</div>
            </div>
            <div class="stat-icon icon-amber"><i class="fa-solid fa-users-gear"></i></div>
        </div>
    </div>
</div>

<!-- LOGS TABLE -->
<div class="card">
    <div class="card-header">
        <span><i class="fa-solid fa-shield-halved me-2" style="color:var(--primary);"></i>Activity Log</span>
        <span style="font-size:12px;color:var(--text-muted);"><?= count($logs) ?> entries shown</span>
    </div>
    <div class="card-body" style="padding:0;">
        <table class="table mb-0">
            <thead><tr>
                <th style="width:160px;">Time</th>
                <th>User</th>
                <th>Action</th>
                <th>Target</th>
                <th>IP Address</th>
            </tr></thead>
            <tbody>
            <?php if (empty($logs)): ?>
            <tr><td colspan="5" style="text-align:center;padding:48px;color:#94a3b8;">
                <div style="width:52px;height:52px;background:#eef2ff;border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                    <i class="fa-solid fa-list-check" style="font-size:20px;color:#6366f1;"></i>
                </div>
                No audit log entries yet.
            </td></tr>
            <?php else: foreach ($logs as $log):
                // Color-code action type
                $aColors = [
                    'checkin'=>['#ecfdf5','#10b981'],'checkout'=>['#eff6ff','#3b82f6'],
                    'room_created'=>['#eef2ff','#6366f1'],'room_deleted'=>['#fef2f2','#ef4444'],
                    'hotel_approved'=>['#ecfdf5','#10b981'],'hotel_rejected'=>['#fef2f2','#ef4444'],
                    'transfer_approved'=>['#ecfdf5','#10b981'],'transfer_rejected'=>['#fef2f2','#ef4444'],
                    'booking_cancelled'=>['#fef2f2','#ef4444'],
                    'user_created'=>['#eef2ff','#6366f1'],'user_deleted'=>['#fef2f2','#ef4444'],
                ];
                $ac = $aColors[$log['action']] ?? ['#f8fafc','#64748b'];
            ?>
            <tr>
                <td style="font-size:11.5px;color:var(--text-muted);white-space:nowrap;">
                    <?= isset($log['created_at']) ? date('d M Y H:i', strtotime($log['created_at'])) : '—' ?>
                </td>
                <td>
                    <div class="identity-cell">
                        <div class="identity-avatar" style="width:28px;height:28px;font-size:11px;background:#eef2ff;color:var(--primary);">
                            <?= strtoupper(substr($log['user_name'] ?? '?', 0, 1)) ?>
                        </div>
                        <div class="identity-name" style="font-size:13px;"><?= htmlspecialchars($log['user_name'] ?? 'System') ?></div>
                    </div>
                </td>
                <td>
                    <span style="background:<?= $ac[0] ?>;color:<?= $ac[1] ?>;padding:3px 10px;border-radius:99px;font-size:12px;font-weight:700;white-space:nowrap;">
                        <?= htmlspecialchars(ucwords(str_replace('_',' ',$log['action']))) ?>
                    </span>
                </td>
                <td style="font-size:12px;color:var(--text-muted);">
                    <?php if ($log['model']): ?>
                    <span style="background:#f8fafc;border:1px solid #e8ecf0;padding:2px 8px;border-radius:5px;font-family:monospace;font-size:11px;">
                        <?= htmlspecialchars($log['model']) ?><?= $log['model_id'] ? ' #'.$log['model_id'] : '' ?>
                    </span>
                    <?php else: ?>—<?php endif; ?>
                </td>
                <td style="font-size:12px;color:var(--text-muted);font-family:monospace;">
                    <?= htmlspecialchars($log['ip_address'] ?? '—') ?>
                </td>
            </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
