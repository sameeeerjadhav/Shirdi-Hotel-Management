<!-- TRANSFER CENTER -->
<div class="page-header">
    <div>
        <h2>Transfer Center</h2>
        <p>Manage guest transfer requests between hotel properties</p>
    </div>
</div>

<!-- COUNT TABS -->
<div style="display:flex;gap:8px;margin-bottom:24px;flex-wrap:wrap;">
    <?php foreach (['pending'=>'Pending','accepted'=>'Accepted','rejected'=>'Rejected'] as $val=>$label): ?>
    <a href="<?= BASE_URL ?>/admin/transfers?status=<?= $val ?>"
       style="padding:8px 20px;border-radius:99px;font-size:13px;font-weight:700;text-decoration:none;
              background:<?= $status===$val ? '#4338ca' : '#f1f5f9' ?>;
              color:<?= $status===$val ? 'white' : '#64748b' ?>;">
        <?= $label ?>
        <span style="background:<?= $status===$val ? 'rgba(255,255,255,0.25)' : '#e2e8f0' ?>;padding:1px 8px;border-radius:99px;margin-left:6px;">
            <?= $counts[$val] ?? 0 ?>
        </span>
    </a>
    <?php endforeach; ?>
</div>

<!-- TRANSFERS TABLE -->
<div class="card">
    <div class="card-body" style="padding:0;">
        <?php if (empty($transfers)): ?>
        <div style="padding:64px;text-align:center;">
            <div style="font-size:48px;margin-bottom:16px;">↔️</div>
            <h3 style="font-size:18px;font-weight:700;color:#1e293b;">No transfers found</h3>
            <p style="color:#64748b;margin-top:8px;">
                <?= $status === 'pending' ? 'No pending transfer requests at this time.' : 'No ' . $status . ' transfers.' ?>
            </p>
        </div>
        <?php else: ?>
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Transfer ID</th>
                    <th>Guest</th>
                    <th>From Hotel</th>
                    <th>To Hotel</th>
                    <th>Dates</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <?php if ($status === 'pending'): ?><th>Actions</th><?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($transfers as $t):
                    $sc = ['pending'=>'badge-pending','accepted'=>'badge-active','rejected'=>'badge-occupied'];
                    $bc = $sc[$t['status']] ?? 'badge-pending';
                ?>
                <tr id="transfer-row-<?= $t['id'] ?>">
                    <td><span style="font-family:monospace;font-weight:700;color:#4338ca;">#TRF<?= str_pad($t['id'],4,'0',STR_PAD_LEFT) ?></span></td>
                    <td>
                        <div style="font-size:13px;font-weight:700;color:#1e293b;"><?= htmlspecialchars($t['guest_name']) ?></div>
                    </td>
                    <td>
                        <div style="font-size:13px;font-weight:600;"><?= htmlspecialchars($t['from_hotel_name']) ?></div>
                        <div style="font-size:11px;color:#94a3b8;"><?= htmlspecialchars($t['from_city']) ?></div>
                    </td>
                    <td>
                        <div style="font-size:13px;font-weight:600;"><?= htmlspecialchars($t['to_hotel_name']) ?></div>
                        <div style="font-size:11px;color:#94a3b8;"><?= htmlspecialchars($t['to_city']) ?></div>
                    </td>
                    <td style="font-size:12px;">
                        <?= date('d M Y', strtotime($t['check_in_date'])) ?><br>
                        <span style="color:#94a3b8;">→ <?= date('d M Y', strtotime($t['check_out_date'])) ?></span>
                    </td>
                    <td style="font-weight:700;color:#10b981;">₹<?= number_format($t['total_amount']) ?></td>
                    <td><span class="badge-pill <?= $bc ?>"><span class="dot"></span><?= ucfirst($t['status']) ?></span></td>
                    <?php if ($status === 'pending'): ?>
                    <td>
                        <button onclick="approveTransfer(<?= $t['id'] ?>)" class="btn btn-primary" style="font-size:12px;padding:4px 12px;">
                            <i class="fa-solid fa-check me-1"></i>Approve
                        </button>
                        <button onclick="rejectTransfer(<?= $t['id'] ?>)" class="btn btn-light ms-1" style="font-size:12px;padding:4px 12px;color:#ef4444;border-color:#fecaca;">
                            Reject
                        </button>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>

<script>
const CSRF = '<?= \App\Middleware\CsrfMiddleware::generate() ?>';

async function approveTransfer(id) {
    const res  = await fetch(`<?= BASE_URL ?>/admin/transfers/${id}/approve`, {method:'POST', headers:{'X-CSRF-Token':CSRF}});
    const data = await res.json();
    if (data.success) { showToast('Transfer approved!','success'); setTimeout(()=>location.reload(),800); }
}

async function rejectTransfer(id) {
    if (!confirm('Reject this transfer request?')) return;
    const res  = await fetch(`<?= BASE_URL ?>/admin/transfers/${id}/reject`, {method:'POST', headers:{'X-CSRF-Token':CSRF}});
    const data = await res.json();
    if (data.success) { showToast('Transfer rejected.','warning'); setTimeout(()=>location.reload(),800); }
}
</script>
