<?php
$isEdit = ($action === 'edit');
$h      = $hotel ?? [];
?>
<div class="page-header">
    <div>
        <h2><?= $isEdit ? 'Edit Hotel' : 'Add New Hotel' ?></h2>
        <p><?= $isEdit ? 'Update hotel information and settings' : 'Register a new hotel partner in the network' ?></p>
    </div>
    <a href="<?= BASE_URL ?>/admin/hotels" class="btn btn-light">
        <i class="fa-solid fa-arrow-left me-2"></i>Back to Hotels
    </a>
</div>

<form method="POST" action="<?= BASE_URL ?>/admin/hotels/<?= $isEdit ? $h['id'] . '/edit' : 'add' ?>" id="hotelForm">
    <?= \App\Middleware\CsrfMiddleware::field() ?>

    <div class="row g-4">
        <!-- LEFT COLUMN -->
        <div class="col-lg-8">

            <!-- Basic Info -->
            <div class="card mb-4">
                <div class="card-header">
                    <span><i class="fa-solid fa-building me-2" style="color:#4338ca;"></i>Hotel Information</span>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Hotel Name <span style="color:#ef4444;">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Hotel Sai Palace"
                                   value="<?= htmlspecialchars($h['name'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Admin Email <span style="color:#ef4444;">*</span></label>
                            <input type="email" name="email" class="form-control" placeholder="hotel@example.com"
                                   value="<?= htmlspecialchars($h['email'] ?? '') ?>" required
                                   <?= $isEdit ? 'readonly style="background:#f8fafc;"' : '' ?>>
                            <?php if (!$isEdit): ?>
                            <div class="form-text">A hotel admin account will be created with this email.</div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Contact Phone</label>
                            <input type="text" name="phone" class="form-control" placeholder="9876543210"
                                   value="<?= htmlspecialchars($h['phone'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Owner / Manager Name</label>
                            <input type="text" name="owner_name" class="form-control" placeholder="Full name"
                                   value="<?= htmlspecialchars($h['owner_name'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Star Rating</label>
                            <select name="star_rating" class="form-select">
                                <?php for ($s = 1; $s <= 5; $s++): ?>
                                <option value="<?= $s ?>" <?= ($h['star_rating'] ?? 3) == $s ? 'selected' : '' ?>>
                                    <?= str_repeat('★', $s) ?> <?= $s ?>-Star
                                </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3"
                                      placeholder="Brief description of the hotel..."><?= htmlspecialchars($h['description'] ?? '') ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Address -->
            <div class="card mb-4">
                <div class="card-header">
                    <span><i class="fa-solid fa-location-dot me-2" style="color:#4338ca;"></i>Address</span>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Street Address</label>
                            <input type="text" name="address" class="form-control"
                                   value="<?= htmlspecialchars($h['address'] ?? '') ?>" placeholder="Street, Area">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">City <span style="color:#ef4444;">*</span></label>
                            <input type="text" name="city" class="form-control" required
                                   value="<?= htmlspecialchars($h['city'] ?? '') ?>" placeholder="Shirdi">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">State</label>
                            <input type="text" name="state" class="form-control"
                                   value="<?= htmlspecialchars($h['state'] ?? '') ?>" placeholder="Maharashtra">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">PIN Code</label>
                            <input type="text" name="zip" class="form-control"
                                   value="<?= htmlspecialchars($h['zip'] ?? '') ?>" placeholder="423109">
                        </div>
                    </div>
                </div>
            </div>

            <!-- GST / Finance -->
            <div class="card mb-4">
                <div class="card-header">
                    <span><i class="fa-solid fa-file-invoice me-2" style="color:#4338ca;"></i>GST & Bank Details</span>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">GST Number</label>
                            <input type="text" name="gst_number" class="form-control" placeholder="27XXXXX..."
                                   value="<?= htmlspecialchars($h['gst_number'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">PAN Number</label>
                            <input type="text" name="pan_number" class="form-control" placeholder="XXXXX1234X"
                                   value="<?= htmlspecialchars($h['pan_number'] ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Bank Name</label>
                            <input type="text" name="bank_name" class="form-control" placeholder="SBI / HDFC..."
                                   value="<?= htmlspecialchars($h['bank_name'] ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Account Number</label>
                            <input type="text" name="bank_account" class="form-control"
                                   value="<?= htmlspecialchars($h['bank_account'] ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">IFSC Code</label>
                            <input type="text" name="bank_ifsc" class="form-control" placeholder="SBIN0000123"
                                   value="<?= htmlspecialchars($h['bank_ifsc'] ?? '') ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN -->
        <div class="col-lg-4">
            <!-- Commission -->
            <div class="card mb-4">
                <div class="card-header">
                    <span><i class="fa-solid fa-percent me-2" style="color:#4338ca;"></i>Platform Commission</span>
                </div>
                <div class="card-body">
                    <label class="form-label">Commission Rate (%)</label>
                    <div style="position:relative;">
                        <input type="number" name="commission_rate" class="form-control" min="0" max="50" step="0.5"
                               value="<?= htmlspecialchars($h['commission_rate'] ?? '10') ?>"
                               style="padding-right:36px;">
                        <span style="position:absolute;right:12px;top:10px;color:#94a3b8;font-weight:700;">%</span>
                    </div>
                    <div class="form-text" style="margin-top:8px;">Percentage of each booking that goes to the platform.</div>

                    <?php if ($isEdit): ?>
                    <hr style="border-color:#e2e8f0;margin:16px 0;">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="pending"   <?= ($h['status']??'')==='pending'   ? 'selected':'' ?>>Pending</option>
                        <option value="approved"  <?= ($h['status']??'')==='approved'  ? 'selected':'' ?>>Approved</option>
                        <option value="suspended" <?= ($h['status']??'')==='suspended' ? 'selected':'' ?>>Suspended</option>
                    </select>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Quick Info (edit mode only) -->
            <?php if ($isEdit): ?>
            <div class="card mb-4">
                <div class="card-header">Quick Info</div>
                <div class="card-body" style="padding:16px 20px;">
                    <div style="display:grid;gap:12px;">
                        <div style="display:flex;justify-content:space-between;font-size:13px;">
                            <span style="color:#64748b;">Hotel Code</span>
                            <span style="font-family:monospace;font-weight:700;color:#4338ca;"><?= htmlspecialchars($h['hotel_code'] ?? 'N/A') ?></span>
                        </div>
                        <div style="display:flex;justify-content:space-between;font-size:13px;">
                            <span style="color:#64748b;">Created</span>
                            <span style="font-weight:600;"><?= date('d M Y', strtotime($h['created_at'])) ?></span>
                        </div>
                        <div style="display:flex;justify-content:space-between;font-size:13px;">
                            <span style="color:#64748b;">Admin Email</span>
                            <span style="font-weight:600;"><?= htmlspecialchars($h['admin_email'] ?? $h['email']) ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Submit -->
            <div class="card">
                <div class="card-body">
                    <button type="submit" class="btn btn-primary w-100" style="padding:12px;">
                        <i class="fa-solid fa-<?= $isEdit ? 'floppy-disk' : 'plus' ?> me-2"></i>
                        <?= $isEdit ? 'Save Changes' : 'Add Hotel' ?>
                    </button>
                    <a href="<?= BASE_URL ?>/admin/hotels" class="btn btn-light w-100 mt-2">Cancel</a>
                </div>
            </div>
        </div>
    </div>
</form>
