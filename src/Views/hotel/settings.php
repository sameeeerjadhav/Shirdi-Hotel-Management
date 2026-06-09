<?php $h = $hotel ?? []; ?>
<div class="page-header">
    <div>
        <h2>Hotel Settings</h2>
        <p>Update your hotel's profile, contact details and bank information</p>
    </div>
</div>

<div class="row g-4">
    <!-- LEFT: Hotel Status + Quick Info -->
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fa-solid fa-building me-2" style="color:var(--primary);"></i>Hotel Status
            </div>
            <div class="card-body" style="text-align:center;padding:28px 24px;">
                <?php
                $statusColor = ['approved'=>'#10b981','pending'=>'#f59e0b','rejected'=>'#ef4444','suspended'=>'#94a3b8'];
                $statusLabel = ['approved'=>'Live & Active','pending'=>'Pending Approval','rejected'=>'Rejected','suspended'=>'Suspended'];
                $st = $h['status'] ?? 'pending';
                $sc = $statusColor[$st] ?? '#94a3b8';
                $sl = $statusLabel[$st] ?? ucfirst($st);
                ?>
                <!-- Status ring -->
                <div style="width:72px;height:72px;border-radius:50%;background:<?= $sc ?>22;border:3px solid <?= $sc ?>;
                            display:flex;align-items:center;justify-content:center;margin:0 auto 14px;">
                    <i class="fa-solid fa-<?= $st === 'approved' ? 'circle-check' : 'clock' ?>"
                       style="font-size:28px;color:<?= $sc ?>;"></i>
                </div>
                <div style="font-size:16px;font-weight:800;color:var(--text-heading);"><?= htmlspecialchars($h['name'] ?? 'My Hotel') ?></div>
                <div style="font-size:12px;color:var(--text-muted);margin-top:4px;"><?= htmlspecialchars($h['hotel_code'] ?? '') ?></div>
                <div style="margin-top:12px;">
                    <span style="background:<?= $sc ?>18;color:<?= $sc ?>;padding:5px 14px;border-radius:99px;font-size:12px;font-weight:700;">
                        <?= $sl ?>
                    </span>
                </div>

                <!-- Quick info tiles -->
                <div style="margin-top:20px;text-align:left;">
                    <div style="padding:10px 14px;background:#f8fafc;border-radius:10px;margin-bottom:8px;">
                        <div style="font-size:10px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.06em;margin-bottom:3px;">Star Rating</div>
                        <div style="font-size:13px;font-weight:700;color:var(--text-heading);">
                            <?php for($i=1;$i<=5;$i++): ?>
                            <i class="fa-<?= $i<=($h['star_rating']??3)?'solid':'regular' ?> fa-star"
                               style="color:#f59e0b;font-size:12px;"></i>
                            <?php endfor; ?>
                            <?= $h['star_rating'] ?? 3 ?> Star
                        </div>
                    </div>
                    <div style="padding:10px 14px;background:#f8fafc;border-radius:10px;margin-bottom:8px;">
                        <div style="font-size:10px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.06em;margin-bottom:3px;">Location</div>
                        <div style="font-size:13px;font-weight:600;color:var(--text-heading);"><?= htmlspecialchars(($h['city'] ?? '') . ', ' . ($h['state'] ?? '')) ?></div>
                    </div>
                    <div style="padding:10px 14px;background:#f8fafc;border-radius:10px;">
                        <div style="font-size:10px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.06em;margin-bottom:3px;">Commission Rate</div>
                        <div style="font-size:13px;font-weight:700;color:var(--primary);"><?= $h['commission_rate'] ?? 10 ?>%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- RIGHT: Settings Forms -->
    <div class="col-lg-8">

        <!-- BASIC INFO -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="fa-solid fa-pen-to-square me-2" style="color:var(--primary);"></i>Basic Information
            </div>
            <div class="card-body">
                <form method="POST" action="<?= BASE_URL ?>/hotel/settings">
                    <?= \App\Middleware\CsrfMiddleware::field() ?>
                    <input type="hidden" name="action" value="basic">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Hotel Name <span style="color:#ef4444;">*</span></label>
                            <input type="text" name="name" class="form-control" required
                                   value="<?= htmlspecialchars($h['name'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Owner Name</label>
                            <input type="text" name="owner_name" class="form-control"
                                   value="<?= htmlspecialchars($h['owner_name'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control"
                                   value="<?= htmlspecialchars($h['email'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control"
                                   value="<?= htmlspecialchars($h['phone'] ?? '') ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3"
                                      placeholder="Brief description of your hotel..."><?= htmlspecialchars($h['description'] ?? '') ?></textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">City</label>
                            <input type="text" name="city" class="form-control"
                                   value="<?= htmlspecialchars($h['city'] ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">State</label>
                            <input type="text" name="state" class="form-control"
                                   value="<?= htmlspecialchars($h['state'] ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">PIN Code</label>
                            <input type="text" name="pincode" class="form-control"
                                   value="<?= htmlspecialchars($h['pincode'] ?? '') ?>">
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

        <!-- TAX & BANKING -->
        <div class="card">
            <div class="card-header">
                <i class="fa-solid fa-landmark me-2" style="color:var(--primary);"></i>Tax & Banking Details
            </div>
            <div class="card-body">
                <form method="POST" action="<?= BASE_URL ?>/hotel/settings">
                    <?= \App\Middleware\CsrfMiddleware::field() ?>
                    <input type="hidden" name="action" value="banking">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">GST Number</label>
                            <input type="text" name="gst_number" class="form-control"
                                   value="<?= htmlspecialchars($h['gst_number'] ?? '') ?>"
                                   placeholder="e.g. 27ABCDE1234F1Z5">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">PAN Number</label>
                            <input type="text" name="pan_number" class="form-control"
                                   value="<?= htmlspecialchars($h['pan_number'] ?? '') ?>"
                                   placeholder="e.g. ABCDE1234F">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Bank Name</label>
                            <input type="text" name="bank_name" class="form-control"
                                   value="<?= htmlspecialchars($h['bank_name'] ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Account Number</label>
                            <input type="text" name="bank_account" class="form-control"
                                   value="<?= htmlspecialchars($h['bank_account'] ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">IFSC Code</label>
                            <input type="text" name="bank_ifsc" class="form-control"
                                   value="<?= htmlspecialchars($h['bank_ifsc'] ?? '') ?>">
                        </div>
                    </div>
                    <div style="margin-top:20px;">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-floppy-disk me-2"></i>Save Banking Details
                        </button>
                        <div style="font-size:11.5px;color:var(--text-muted);margin-top:8px;">
                            <i class="fa-solid fa-lock me-1"></i>Banking details are stored securely and only used for payouts.
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
