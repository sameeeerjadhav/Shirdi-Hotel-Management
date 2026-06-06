<div class="page-header">
    <div>
        <h2>User Management</h2>
        <p>Manage all platform users — Super Admins, Hotel Admins, and Guests</p>
    </div>
    <button onclick="document.getElementById('addUserModal').classList.add('open')" class="btn btn-primary">
        <i class="fa-solid fa-plus me-2"></i>Add User
    </button>
</div>

<!-- FILTER ROW -->
<div class="card mb-4" style="padding:16px 20px;">
    <form method="GET" class="d-flex gap-3 flex-wrap align-items-center">
        <div style="flex:1;min-width:200px;position:relative;">
            <i class="fa-solid fa-magnifying-glass" style="position:absolute;left:12px;top:11px;color:#94a3b8;font-size:13px;"></i>
            <input type="text" name="search" class="form-control" placeholder="Search name or email..."
                   value="<?= htmlspecialchars($search ?? '') ?>" style="padding-left:36px;">
        </div>
        <select name="role" class="form-select" style="width:160px;">
            <option value="0">All Roles</option>
            <option value="1" <?= ($role??0)==1?'selected':'' ?>>Super Admin</option>
            <option value="2" <?= ($role??0)==2?'selected':'' ?>>Hotel Admin</option>
            <option value="3" <?= ($role??0)==3?'selected':'' ?>>Guest</option>
        </select>
        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="<?= BASE_URL ?>/admin/users" class="btn btn-light">Reset</a>
    </form>
</div>

<!-- USERS TABLE -->
<div class="card">
    <div class="card-body" style="padding:0;">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Phone</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                <tr><td colspan="6" style="text-align:center;padding:48px;color:#94a3b8;">
                    <div style="font-size:32px;margin-bottom:12px;">👥</div>
                    No users found.
                </td></tr>
                <?php else: ?>
                <?php foreach ($users as $u):
                    $roleColors = [1=>['bg'=>'#f5f3ff','text'=>'#4338ca'], 2=>['bg'=>'#ecfdf5','text'=>'#065f46'], 3=>['bg'=>'#f8fafc','text'=>'#475569']];
                    $rc = $roleColors[$u['role_id']] ?? $roleColors[3];
                    $isSuspended = ($u['login_attempts'] ?? 0) >= 99;
                ?>
                <tr>
                    <td>
                        <div class="identity-cell">
                            <div class="identity-avatar icon-indigo"><?= strtoupper(substr($u['name'],0,1)) ?></div>
                            <div>
                                <div class="identity-name"><?= htmlspecialchars($u['name']) ?></div>
                                <?php if ($isSuspended): ?><div style="font-size:11px;color:#ef4444;font-weight:700;">SUSPENDED</div><?php endif; ?>
                            </div>
                        </div>
                    </td>
                    <td style="font-size:13px;color:#64748b;"><?= htmlspecialchars($u['email']) ?></td>
                    <td>
                        <span style="background:<?= $rc['bg'] ?>;color:<?= $rc['text'] ?>;padding:3px 12px;border-radius:99px;font-size:11px;font-weight:700;">
                            <?= htmlspecialchars($u['role_name'] ?? 'N/A') ?>
                        </span>
                    </td>
                    <td style="font-size:13px;color:#64748b;"><?= htmlspecialchars($u['phone'] ?? '—') ?></td>
                    <td style="font-size:12px;color:#94a3b8;"><?= date('d M Y', strtotime($u['created_at'])) ?></td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            <button onclick="resetPwd(<?= $u['id'] ?>, '<?= htmlspecialchars($u['name']) ?>')"
                                    class="btn btn-light" style="font-size:11px;padding:4px 10px;" title="Reset Password">
                                <i class="fa-solid fa-key"></i>
                            </button>
                            <?php if ($u['id'] != $_SESSION['user_id']): ?>
                            <button onclick="toggleUser(<?= $u['id'] ?>, <?= $isSuspended ? 'true' : 'false' ?>)"
                                    class="btn btn-light" style="font-size:11px;padding:4px 10px;color:<?= $isSuspended ? '#10b981' : '#f59e0b' ?>;"
                                    title="<?= $isSuspended ? 'Activate' : 'Suspend' ?>">
                                <i class="fa-solid fa-<?= $isSuspended ? 'check-circle' : 'ban' ?>"></i>
                            </button>
                            <button onclick="deleteUser(<?= $u['id'] ?>, '<?= htmlspecialchars($u['name']) ?>')"
                                    class="btn btn-light" style="font-size:11px;padding:4px 10px;color:#ef4444;border-color:#fecaca;" title="Delete">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ADD USER MODAL -->
<div id="addUserModal" style="display:none;position:fixed;inset:0;z-index:3000;align-items:center;justify-content:center;">
    <div style="position:absolute;inset:0;background:rgba(0,0,0,0.4);" onclick="document.getElementById('addUserModal').classList.remove('open')"></div>
    <div style="position:relative;background:white;border-radius:20px;width:460px;max-width:95%;padding:32px;box-shadow:0 24px 64px rgba(0,0,0,0.15);z-index:1;">
        <h3 style="font-size:18px;font-weight:800;color:#1e293b;margin-bottom:20px;">
            <i class="fa-solid fa-user-plus me-2" style="color:#4338ca;"></i>Add New User
        </h3>
        <form method="POST" action="<?= BASE_URL ?>/admin/users/create">
            <?= \App\Middleware\CsrfMiddleware::field() ?>
            <div style="display:grid;gap:14px;">
                <div>
                    <label class="form-label">Full Name *</label>
                    <input type="text" name="name" class="form-control" required placeholder="Full name">
                </div>
                <div>
                    <label class="form-label">Email *</label>
                    <input type="email" name="email" class="form-control" required placeholder="email@example.com">
                </div>
                <div>
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" placeholder="9876543210">
                </div>
                <div>
                    <label class="form-label">Role *</label>
                    <select name="role_id" class="form-select" required>
                        <option value="2">Hotel Admin</option>
                        <option value="1">Super Admin</option>
                        <option value="3">Guest</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Temporary Password *</label>
                    <input type="text" name="password" class="form-control" required
                           placeholder="Min 6 chars" minlength="6">
                </div>
            </div>
            <div style="display:flex;gap:10px;margin-top:20px;">
                <button type="submit" class="btn btn-primary" style="flex:1;padding:12px;">Create User</button>
                <button type="button" onclick="document.getElementById('addUserModal').classList.remove('open')"
                        class="btn btn-light" style="flex:1;padding:12px;">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- RESET PASSWORD MODAL -->
<div id="resetPwdModal" style="display:none;position:fixed;inset:0;z-index:3000;align-items:center;justify-content:center;">
    <div style="position:absolute;inset:0;background:rgba(0,0,0,0.4);" onclick="document.getElementById('resetPwdModal').style.display='none'"></div>
    <div style="position:relative;background:white;border-radius:20px;width:380px;padding:28px;box-shadow:0 24px 64px rgba(0,0,0,0.15);z-index:1;">
        <h3 style="font-size:16px;font-weight:800;margin-bottom:16px;">Reset Password for <span id="resetPwdName"></span></h3>
        <input type="password" id="resetPwdInput" class="form-control mb-3" placeholder="New password (min 6 chars)" minlength="6">
        <div style="display:flex;gap:10px;">
            <button onclick="submitReset()" class="btn btn-primary" style="flex:1;">Reset</button>
            <button onclick="document.getElementById('resetPwdModal').style.display='none'" class="btn btn-light" style="flex:1;">Cancel</button>
        </div>
    </div>
</div>

<style>
#addUserModal.open { display: flex !important; }
</style>

<script>
const CSRF = '<?= \App\Middleware\CsrfMiddleware::generate() ?>';
let resetUserId = null;

async function toggleUser(id, isSuspended) {
    const action = isSuspended ? 'activate' : 'suspend';
    if (!confirm(`${isSuspended ? 'Activate' : 'Suspend'} this user?`)) return;
    const res  = await fetch(`<?= BASE_URL ?>/admin/users/${id}/toggle`, {method:'POST', headers:{'X-CSRF-Token':CSRF}});
    const data = await res.json();
    if (data.success) { showToast(data.message, 'success'); setTimeout(()=>location.reload(), 800); }
}

function resetPwd(id, name) {
    resetUserId = id;
    document.getElementById('resetPwdName').textContent = name;
    document.getElementById('resetPwdInput').value = '';
    document.getElementById('resetPwdModal').style.display = 'flex';
}

async function submitReset() {
    const pwd = document.getElementById('resetPwdInput').value;
    if (pwd.length < 6) { showToast('Password must be at least 6 characters', 'error'); return; }
    const fd = new FormData(); fd.append('password', pwd); fd.append('_csrf', CSRF);
    const res  = await fetch(`<?= BASE_URL ?>/admin/users/${resetUserId}/password`, {method:'POST', body:fd});
    const data = await res.json();
    document.getElementById('resetPwdModal').style.display = 'none';
    showToast(data.message, data.success ? 'success' : 'error');
}

async function deleteUser(id, name) {
    if (!confirm(`Permanently delete user "${name}"? This cannot be undone.`)) return;
    const res  = await fetch(`<?= BASE_URL ?>/admin/users/${id}/delete`, {method:'POST', headers:{'X-CSRF-Token':CSRF}});
    const data = await res.json();
    if (data.success) { showToast(data.message, 'success'); setTimeout(()=>location.reload(), 800); }
    else showToast(data.message, 'error');
}
</script>
