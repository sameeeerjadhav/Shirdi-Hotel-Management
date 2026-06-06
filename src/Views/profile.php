<?php
$u = $user ?? [];
$roleName = $_SESSION['role_id'] == 1 ? 'Super Admin' : ($_SESSION['hotel_name'] ?? 'Hotel Admin');
?>
<div class="page-header">
    <div>
        <h2>My Profile</h2>
        <p>Manage your account settings and security</p>
    </div>
</div>

<div class="row g-4">
    <!-- LEFT: Avatar + Role Info -->
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-body" style="text-align:center;padding:32px 24px;">
                <!-- Avatar -->
                <div style="width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,#4338ca,#8b5cf6);
                            display:flex;align-items:center;justify-content:center;font-size:36px;font-weight:800;
                            color:white;margin:0 auto 16px;box-shadow:0 8px 24px rgba(67,56,202,0.3);">
                    <?= strtoupper(substr($u['name'] ?? '?', 0, 1)) ?>
                </div>
                <div style="font-size:18px;font-weight:800;color:#1e293b;"><?= htmlspecialchars($u['name'] ?? '') ?></div>
                <div style="font-size:13px;color:#94a3b8;margin-top:4px;"><?= $roleName ?></div>
                <div style="margin-top:16px;padding:10px 16px;background:#f8fafc;border-radius:10px;">
                    <div style="font-size:12px;color:#94a3b8;margin-bottom:4px;">EMAIL</div>
                    <div style="font-size:13px;font-weight:700;color:#4338ca;"><?= htmlspecialchars($u['email'] ?? '') ?></div>
                </div>
                <div style="margin-top:8px;padding:10px 16px;background:#f8fafc;border-radius:10px;">
                    <div style="font-size:12px;color:#94a3b8;margin-bottom:4px;">MEMBER SINCE</div>
                    <div style="font-size:13px;font-weight:700;color:#1e293b;"><?= isset($u['created_at']) ? date('d M Y', strtotime($u['created_at'])) : 'N/A' ?></div>
                </div>
                <?php if ($_SESSION['role_id'] == 1): ?>
                <div style="margin-top:16px;">
                    <span style="background:#ede9fe;color:#4338ca;padding:6px 14px;border-radius:99px;font-size:12px;font-weight:700;">
                        <i class="fa-solid fa-shield-halved me-1"></i>Super Admin
                    </span>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- RIGHT: Forms -->
    <div class="col-lg-8">

        <!-- PROFILE DETAILS FORM -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="fa-solid fa-user me-2" style="color:#4338ca;"></i>Account Information
            </div>
            <div class="card-body">
                <form method="POST" action="<?= BASE_URL ?>/profile">
                    <?= \App\Middleware\CsrfMiddleware::field() ?>
                    <input type="hidden" name="action" value="profile">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Full Name <span style="color:#ef4444;">*</span></label>
                            <input type="text" name="name" class="form-control" required
                                   value="<?= htmlspecialchars($u['name'] ?? '') ?>"
                                   placeholder="Your full name">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email Address</label>
                            <input type="email" class="form-control" value="<?= htmlspecialchars($u['email'] ?? '') ?>"
                                   disabled style="background:#f8fafc;cursor:not-allowed;" title="Email cannot be changed">
                            <div class="form-text">Email address cannot be changed.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone" class="form-control"
                                   value="<?= htmlspecialchars($u['phone'] ?? '') ?>"
                                   placeholder="e.g. 9876543210">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Role</label>
                            <input type="text" class="form-control" value="<?= $roleName ?>"
                                   disabled style="background:#f8fafc;cursor:not-allowed;">
                        </div>
                    </div>

                    <div style="margin-top:20px;">
                        <button type="submit" class="btn btn-primary" style="padding:10px 28px;">
                            <i class="fa-solid fa-floppy-disk me-2"></i>Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- CHANGE PASSWORD FORM -->
        <div class="card">
            <div class="card-header">
                <i class="fa-solid fa-lock me-2" style="color:#4338ca;"></i>Change Password
            </div>
            <div class="card-body">
                <form method="POST" action="<?= BASE_URL ?>/profile" id="passwordForm">
                    <?= \App\Middleware\CsrfMiddleware::field() ?>
                    <input type="hidden" name="action" value="password">

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Current Password <span style="color:#ef4444;">*</span></label>
                            <div style="position:relative;">
                                <input type="password" name="current_password" id="currentPwd" class="form-control"
                                       placeholder="Enter your current password" required>
                                <button type="button" onclick="togglePwd('currentPwd',this)"
                                        style="position:absolute;right:12px;top:10px;background:none;border:none;cursor:pointer;color:#94a3b8;font-size:13px;">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">New Password <span style="color:#ef4444;">*</span></label>
                            <div style="position:relative;">
                                <input type="password" name="new_password" id="newPwd" class="form-control"
                                       placeholder="Min. 8 characters" required minlength="8"
                                       oninput="checkStrength(this.value)">
                                <button type="button" onclick="togglePwd('newPwd',this)"
                                        style="position:absolute;right:12px;top:10px;background:none;border:none;cursor:pointer;color:#94a3b8;font-size:13px;">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>
                            <!-- Strength bar -->
                            <div style="margin-top:6px;height:4px;background:#e2e8f0;border-radius:99px;overflow:hidden;">
                                <div id="strengthBar" style="height:100%;width:0;background:#ef4444;border-radius:99px;transition:width 0.3s,background 0.3s;"></div>
                            </div>
                            <div id="strengthText" style="font-size:11px;color:#94a3b8;margin-top:4px;"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Confirm New Password <span style="color:#ef4444;">*</span></label>
                            <div style="position:relative;">
                                <input type="password" name="confirm_password" id="confirmPwd" class="form-control"
                                       placeholder="Repeat new password" required>
                                <button type="button" onclick="togglePwd('confirmPwd',this)"
                                        style="position:absolute;right:12px;top:10px;background:none;border:none;cursor:pointer;color:#94a3b8;font-size:13px;">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div style="margin-top:20px;display:flex;align-items:center;gap:12px;">
                        <button type="submit" class="btn btn-primary" style="padding:10px 28px;">
                            <i class="fa-solid fa-key me-2"></i>Change Password
                        </button>
                        <div style="font-size:12px;color:#94a3b8;">
                            <i class="fa-solid fa-info-circle me-1"></i>You'll stay logged in after changing your password.
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Toggle password visibility
function togglePwd(id, btn) {
    const input = document.getElementById(id);
    const icon  = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'fa-regular fa-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'fa-regular fa-eye';
    }
}

// Password strength checker
function checkStrength(pw) {
    const bar  = document.getElementById('strengthBar');
    const text = document.getElementById('strengthText');
    let score  = 0;
    if (pw.length >= 8)  score++;
    if (/[A-Z]/.test(pw)) score++;
    if (/[0-9]/.test(pw)) score++;
    if (/[^A-Za-z0-9]/.test(pw)) score++;

    const levels = [
        {pct:'25%',  bg:'#ef4444', label:'Weak'},
        {pct:'50%',  bg:'#f59e0b', label:'Fair'},
        {pct:'75%',  bg:'#3b82f6', label:'Good'},
        {pct:'100%', bg:'#10b981', label:'Strong'},
    ];
    const level = levels[score - 1] || {pct:'0', bg:'#ef4444', label:''};
    bar.style.width      = level.pct;
    bar.style.background = level.bg;
    text.textContent     = level.label;
    text.style.color     = level.bg;
}

// Validate passwords match on submit
document.getElementById('passwordForm').addEventListener('submit', function(e) {
    const np = document.getElementById('newPwd').value;
    const cp = document.getElementById('confirmPwd').value;
    if (np !== cp) {
        e.preventDefault();
        showToast('New passwords do not match.', 'error');
    }
});
</script>
