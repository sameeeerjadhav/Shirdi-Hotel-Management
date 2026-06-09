<?php
$u        = $user   ?? [];
$hotel    = $hotel  ?? null;   // Only for hotel admins
$isAdmin  = ($_SESSION['role_id'] == 1);
$isHotel  = ($_SESSION['role_id'] == 2);
$roleName = $isAdmin ? 'Super Admin' : ($_SESSION['hotel_name'] ?? 'Hotel Admin');

// Current avatar
$avatarUrl = !empty($u['avatar']) ? BASE_URL . '/' . ltrim($u['avatar'], '/') : null;
// Hotel logo (for hotel admins)
$logoUrl   = ($isHotel && !empty($hotel['cover_image'])) ? BASE_URL . '/' . ltrim($hotel['cover_image'], '/') : null;
?>
<div class="page-header">
    <div>
        <h2>My Profile</h2>
        <p>Manage your account settings, photo<?= $isHotel ? ', hotel logo' : '' ?> and security</p>
    </div>
</div>

<div class="row g-4">
    <!-- LEFT: Avatar Card + Hotel Logo (if hotel admin) -->
    <div class="col-lg-4">

        <!-- PROFILE PHOTO CARD -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="fa-solid fa-circle-user me-2" style="color:var(--primary);"></i>
                Profile Photo
            </div>
            <div class="card-body" style="text-align:center;padding:28px 24px;">
                <!-- Avatar display -->
                <div id="avatarPreviewWrap" style="position:relative;display:inline-block;margin-bottom:16px;">
                    <?php if ($avatarUrl): ?>
                    <img id="avatarPreview" src="<?= htmlspecialchars($avatarUrl) ?>"
                         style="width:96px;height:96px;border-radius:50%;object-fit:cover;border:3px solid #e8ecf0;box-shadow:0 4px 16px rgba(79,70,229,0.15);">
                    <?php else: ?>
                    <div id="avatarInitial" style="width:96px;height:96px;border-radius:50%;background:linear-gradient(135deg,#6366f1,#8b5cf6);
                                display:flex;align-items:center;justify-content:center;font-size:38px;font-weight:800;
                                color:white;box-shadow:0 4px 16px rgba(79,70,229,0.25);margin:0 auto;">
                        <?= strtoupper(substr($u['name'] ?? '?', 0, 1)) ?>
                    </div>
                    <img id="avatarPreview" src="" style="width:96px;height:96px;border-radius:50%;object-fit:cover;border:3px solid #e8ecf0;box-shadow:0 4px 16px rgba(79,70,229,0.15);display:none;">
                    <?php endif; ?>

                    <!-- Camera badge -->
                    <label for="avatarInput" style="position:absolute;bottom:2px;right:2px;width:28px;height:28px;
                           background:var(--primary);border-radius:50%;display:flex;align-items:center;justify-content:center;
                           cursor:pointer;box-shadow:0 2px 8px rgba(79,70,229,0.4);border:2px solid white;" title="Change photo">
                        <i class="fa-solid fa-camera" style="color:white;font-size:11px;"></i>
                    </label>
                </div>

                <div style="font-size:16px;font-weight:800;color:var(--text-heading);"><?= htmlspecialchars($u['name'] ?? '') ?></div>
                <div style="font-size:12px;color:var(--text-muted);margin-top:3px;"><?= $roleName ?></div>

                <!-- Upload form -->
                <form method="POST" action="<?= BASE_URL ?>/profile" enctype="multipart/form-data" id="avatarForm" style="margin-top:16px;">
                    <?= \App\Middleware\CsrfMiddleware::field() ?>
                    <input type="hidden" name="action" value="avatar">
                    <input type="file" id="avatarInput" name="avatar" accept="image/*"
                           style="display:none;" onchange="previewAndSubmit(this,'avatarPreview','avatarInitial','avatarForm')">
                    <div style="font-size:11.5px;color:var(--text-muted);margin-top:8px;">
                        JPG, PNG or WebP — max 2MB
                    </div>
                </form>

                <!-- Info tiles -->
                <div style="margin-top:16px;padding:10px 16px;background:#f8fafc;border-radius:10px;text-align:left;">
                    <div style="font-size:10px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.06em;margin-bottom:4px;">EMAIL</div>
                    <div style="font-size:13px;font-weight:600;color:var(--primary);word-break:break-all;"><?= htmlspecialchars($u['email'] ?? '') ?></div>
                </div>
                <div style="margin-top:8px;padding:10px 16px;background:#f8fafc;border-radius:10px;text-align:left;">
                    <div style="font-size:10px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.06em;margin-bottom:4px;">MEMBER SINCE</div>
                    <div style="font-size:13px;font-weight:600;color:var(--text-heading);"><?= isset($u['created_at']) ? date('d M Y', strtotime($u['created_at'])) : 'N/A' ?></div>
                </div>
                <?php if ($isAdmin): ?>
                <div style="margin-top:14px;">
                    <span style="background:#eef2ff;color:var(--primary);padding:5px 14px;border-radius:99px;font-size:12px;font-weight:700;">
                        <i class="fa-solid fa-shield-halved me-1"></i>Super Admin
                    </span>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- HOTEL LOGO CARD (Hotel Admins only) -->
        <?php if ($isHotel): ?>
        <div class="card">
            <div class="card-header">
                <i class="fa-solid fa-image me-2" style="color:var(--primary);"></i>
                Hotel Logo
            </div>
            <div class="card-body" style="text-align:center;padding:24px;">
                <!-- Logo preview -->
                <div style="position:relative;display:inline-block;margin-bottom:14px;">
                    <?php if ($logoUrl): ?>
                    <img id="logoPreview" src="<?= htmlspecialchars($logoUrl) ?>"
                         style="width:120px;height:120px;border-radius:16px;object-fit:contain;border:2px dashed #c7d2fe;background:#f8fafc;padding:8px;box-shadow:0 2px 12px rgba(99,102,241,0.1);">
                    <?php else: ?>
                    <div id="logoPlaceholder" style="width:120px;height:120px;border-radius:16px;border:2px dashed #c7d2fe;
                              background:linear-gradient(160deg,#f8f7ff,#eef2ff);display:flex;flex-direction:column;
                              align-items:center;justify-content:center;gap:6px;cursor:pointer;"
                         onclick="document.getElementById('logoInput').click()">
                        <i class="fa-solid fa-hotel" style="font-size:28px;color:#a5b4fc;"></i>
                        <span style="font-size:10px;color:#a5b4fc;font-weight:600;">Upload Logo</span>
                    </div>
                    <img id="logoPreview" src="" style="width:120px;height:120px;border-radius:16px;object-fit:contain;border:2px dashed #c7d2fe;background:#f8fafc;padding:8px;display:none;">
                    <?php endif; ?>

                    <!-- Edit badge -->
                    <label for="logoInput" style="position:absolute;bottom:4px;right:4px;width:28px;height:28px;
                           background:var(--primary);border-radius:50%;display:flex;align-items:center;justify-content:center;
                           cursor:pointer;box-shadow:0 2px 8px rgba(79,70,229,0.4);border:2px solid white;" title="Change logo">
                        <i class="fa-solid fa-pen" style="color:white;font-size:10px;"></i>
                    </label>
                </div>

                <div style="font-size:14px;font-weight:700;color:var(--text-heading);"><?= htmlspecialchars($_SESSION['hotel_name'] ?? 'My Hotel') ?></div>
                <div style="font-size:12px;color:var(--text-muted);margin-top:2px;">Hotel Logo</div>

                <!-- Upload form -->
                <form method="POST" action="<?= BASE_URL ?>/profile" enctype="multipart/form-data" id="logoForm" style="margin-top:14px;">
                    <?= \App\Middleware\CsrfMiddleware::field() ?>
                    <input type="hidden" name="action" value="hotel_logo">
                    <input type="file" id="logoInput" name="hotel_logo" accept="image/*"
                           style="display:none;" onchange="previewAndSubmit(this,'logoPreview','logoPlaceholder','logoForm')">
                    <button type="button" onclick="document.getElementById('logoInput').click()"
                            class="btn btn-light" style="font-size:12px;padding:7px 16px;">
                        <i class="fa-solid fa-upload me-1"></i><?= $logoUrl ? 'Change Logo' : 'Upload Logo' ?>
                    </button>
                    <div style="font-size:11px;color:var(--text-muted);margin-top:8px;">
                        PNG or SVG recommended — max 2MB
                    </div>
                </form>
            </div>
        </div>
        <?php endif; ?>

    </div>

    <!-- RIGHT: Forms -->
    <div class="col-lg-8">

        <!-- PROFILE DETAILS FORM -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="fa-solid fa-user me-2" style="color:var(--primary);"></i>Account Information
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
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-floppy-disk me-2"></i>Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- CHANGE PASSWORD FORM -->
        <div class="card">
            <div class="card-header">
                <i class="fa-solid fa-lock me-2" style="color:var(--primary);"></i>Change Password
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
                                        style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#94a3b8;font-size:13px;">
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
                                        style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#94a3b8;font-size:13px;">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>
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
                                        style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#94a3b8;font-size:13px;">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div style="margin-top:20px;display:flex;align-items:center;gap:12px;">
                        <button type="submit" class="btn btn-primary">
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
// Preview image and auto-submit the form
function previewAndSubmit(input, previewId, placeholderId, formId) {
    const file = input.files[0];
    if (!file) return;

    // Validate size (2MB)
    if (file.size > 2 * 1024 * 1024) {
        showToast('Image must be under 2MB.', 'error');
        input.value = '';
        return;
    }

    const reader = new FileReader();
    reader.onload = function(e) {
        const preview = document.getElementById(previewId);
        const holder  = document.getElementById(placeholderId);
        if (preview) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        }
        if (holder) holder.style.display = 'none';

        // Show uploading toast then submit
        showToast('Uploading image...', 'info');
        setTimeout(() => document.getElementById(formId).submit(), 400);
    };
    reader.readAsDataURL(file);
}

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
    if (pw.length >= 8)         score++;
    if (/[A-Z]/.test(pw))       score++;
    if (/[0-9]/.test(pw))       score++;
    if (/[^A-Za-z0-9]/.test(pw))score++;

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
