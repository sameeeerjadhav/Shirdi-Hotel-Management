<!-- CHECKOUT PAGE — PUBLIC, NO SIDEBAR -->
<div style="min-height:100vh;background:#0f172a;font-family:'Inter',sans-serif;padding:24px 16px;">

    <!-- NAV -->
    <nav style="max-width:960px;margin:0 auto 32px;display:flex;align-items:center;justify-content:space-between;">
        <a href="<?= BASE_URL ?>/search" style="font-size:13px;color:#64748b;text-decoration:none;display:flex;align-items:center;gap:6px;">
            <i class="fa-solid fa-arrow-left"></i> Back to Search
        </a>
        <span style="font-size:15px;font-weight:700;color:white;">Secure Checkout</span>
    </nav>

    <?php if(isset($_SESSION['transfer_alert'])): ?>
    <div style="max-width:960px;margin:0 auto 20px;background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.2);border-radius:12px;padding:16px 20px;display:flex;align-items:center;gap:14px;">
        <div style="width:40px;height:40px;background:rgba(34,197,94,0.15);border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i class="fa-solid fa-right-left" style="font-size:16px;color:#16a34a;"></i>
        </div>
        <div>
            <div style="font-size:14px;font-weight:700;color:#22c55e;margin-bottom:2px;">Smart Transfer Applied</div>
            <div style="font-size:13px;color:#86efac;"><?= $_SESSION['transfer_alert']; unset($_SESSION['transfer_alert']); ?></div>
        </div>
    </div>
    <?php endif; ?>

    <div style="max-width:960px;margin:0 auto;display:grid;grid-template-columns:1fr 340px;gap:20px;">
        <!-- FORM -->
        <div style="background:#1e293b;border:1px solid rgba(255,255,255,0.06);border-radius:16px;padding:28px;">
            <h2 style="font-size:18px;font-weight:700;color:white;margin-bottom:24px;">Guest Details</h2>
            <form action="<?= BASE_URL ?>/checkout/<?= $hotel['id'] ?>" method="POST">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:24px;">
                    <div>
                        <label style="display:block;font-size:12px;font-weight:600;color:#94a3b8;margin-bottom:7px;">First Name</label>
                        <input type="text" name="first_name" required placeholder="Raj"
                            style="width:100%;padding:10px 14px;background:#0f172a;border:1px solid rgba(255,255,255,0.07);border-radius:9px;color:white;font-size:14px;outline:none;"
                            onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='rgba(255,255,255,0.07)'">
                    </div>
                    <div>
                        <label style="display:block;font-size:12px;font-weight:600;color:#94a3b8;margin-bottom:7px;">Last Name</label>
                        <input type="text" name="last_name" required placeholder="Sharma"
                            style="width:100%;padding:10px 14px;background:#0f172a;border:1px solid rgba(255,255,255,0.07);border-radius:9px;color:white;font-size:14px;outline:none;"
                            onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='rgba(255,255,255,0.07)'">
                    </div>
                    <div>
                        <label style="display:block;font-size:12px;font-weight:600;color:#94a3b8;margin-bottom:7px;">Email</label>
                        <input type="email" name="email" required placeholder="raj@example.com"
                            style="width:100%;padding:10px 14px;background:#0f172a;border:1px solid rgba(255,255,255,0.07);border-radius:9px;color:white;font-size:14px;outline:none;"
                            onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='rgba(255,255,255,0.07)'">
                    </div>
                    <div>
                        <label style="display:block;font-size:12px;font-weight:600;color:#94a3b8;margin-bottom:7px;">Phone</label>
                        <input type="text" name="phone" required placeholder="+91 98765 43210"
                            style="width:100%;padding:10px 14px;background:#0f172a;border:1px solid rgba(255,255,255,0.07);border-radius:9px;color:white;font-size:14px;outline:none;"
                            onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='rgba(255,255,255,0.07)'">
                    </div>
                    <div>
                        <label style="display:block;font-size:12px;font-weight:600;color:#94a3b8;margin-bottom:7px;">ID Proof Type</label>
                        <select name="id_proof_type" style="width:100%;padding:10px 14px;background:#0f172a;border:1px solid rgba(255,255,255,0.07);border-radius:9px;color:white;font-size:14px;outline:none;">
                            <option value="Aadhar">Aadhar Card</option>
                            <option value="Passport">Passport</option>
                            <option value="Driver License">Driver's License</option>
                        </select>
                    </div>
                    <div>
                        <label style="display:block;font-size:12px;font-weight:600;color:#94a3b8;margin-bottom:7px;">ID Number</label>
                        <input type="text" name="id_proof_number" required placeholder="XXXX XXXX XXXX"
                            style="width:100%;padding:10px 14px;background:#0f172a;border:1px solid rgba(255,255,255,0.07);border-radius:9px;color:white;font-size:14px;outline:none;"
                            onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='rgba(255,255,255,0.07)'">
                    </div>
                </div>
                <button type="submit" style="width:100%;padding:13px;background:linear-gradient(135deg,#6366f1,#4f46e5);color:white;border:none;border-radius:10px;font-size:15px;font-weight:700;cursor:pointer;box-shadow:0 4px 16px rgba(99,102,241,0.4);">
                    Confirm & Pay ₹<?= number_format($hotel['price']) ?> &rarr;
                </button>
            </form>
        </div>

        <!-- SUMMARY -->
        <div>
            <div style="background:#1e293b;border:1px solid rgba(255,255,255,0.06);border-radius:16px;padding:24px;position:sticky;top:20px;">
                <h3 style="font-size:15px;font-weight:700;color:white;margin-bottom:20px;">Booking Summary</h3>
                <div style="width:100%;height:120px;background:rgba(99,102,241,0.1);border-radius:10px;display:flex;align-items:center;justify-content:center;margin-bottom:18px;">
                    <i class="fa-solid fa-hotel" style="font-size:40px;color:#6366f1;opacity:0.5;"></i>
                </div>
                <div style="font-size:16px;font-weight:700;color:white;margin-bottom:4px;"><?= htmlspecialchars($hotel['name']) ?></div>
                <div style="font-size:13px;color:#64748b;margin-bottom:20px;padding-bottom:20px;border-bottom:1px solid rgba(255,255,255,0.06);">
                    <i class="fa-solid fa-location-dot me-1" style="color:#6366f1;"></i> Standard Room
                </div>
                <div style="display:flex;justify-content:space-between;font-size:13px;color:#94a3b8;margin-bottom:10px;">
                    <span>1 Night × ₹<?= number_format($hotel['price']) ?></span>
                    <span>₹<?= number_format($hotel['price']) ?></span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:13px;color:#94a3b8;margin-bottom:16px;padding-bottom:16px;border-bottom:1px solid rgba(255,255,255,0.06);">
                    <span>Taxes & fees</span>
                    <span>₹0</span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:16px;font-weight:800;color:white;">
                    <span>Total</span>
                    <span>₹<?= number_format($hotel['price']) ?></span>
                </div>
            </div>
        </div>
    </div>
</div>
