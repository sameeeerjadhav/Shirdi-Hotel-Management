<!-- REGISTER HOTEL PAGE — PUBLIC, NO SIDEBAR -->
<div style="min-height:100vh;background:#f8fafc;font-family:'Inter',sans-serif;padding:32px 16px;">
    <div style="max-width:680px;margin:0 auto;">

        <!-- HEADER -->
        <div style="margin-bottom:28px;">
            <a href="<?= BASE_URL ?>/" style="font-size:13px;color:#64748b;text-decoration:none;display:inline-flex;align-items:center;gap:6px;margin-bottom:20px;">
                <i class="fa-solid fa-arrow-left"></i> Back to Home
            </a>
            <div style="display:flex;align-items:center;gap:14px;margin-bottom:6px;">
                <div style="width:44px;height:44px;background:linear-gradient(135deg,#6366f1,#4f46e5);border-radius:12px;display:flex;align-items:center;justify-content:center;color:white;font-size:20px;">
                    <i class="fa-solid fa-hotel"></i>
                </div>
                <h1 style="font-size:24px;font-weight:800;color:#0f172a;letter-spacing:-0.5px;">Partner with CHNMS</h1>
            </div>
            <p style="font-size:14px;color:#64748b;">Register your hotel to join our centralized booking network.</p>
        </div>

        <form action="<?= BASE_URL ?>/register-hotel" method="POST" enctype="multipart/form-data">
            <!-- SECTION 1: Admin Info -->
            <div style="background:white;border:1px solid #e2e8f0;border-radius:14px;padding:24px;margin-bottom:16px;box-shadow:0 1px 4px rgba(0,0,0,0.04);">
                <div style="font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.7px;margin-bottom:20px;padding-bottom:12px;border-bottom:1px solid #f1f5f9;">Administrator Details</div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div>
                        <label style="display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:6px;">Full Name</label>
                        <input type="text" name="admin_name" required placeholder="Rajesh Kumar"
                            style="width:100%;padding:10px 14px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:9px;color:#0f172a;font-size:14px;outline:none;font-family:inherit;"
                            onfocus="this.style.borderColor='#6366f1';this.style.boxShadow='0 0 0 3px rgba(99,102,241,0.1)'"
                            onblur="this.style.borderColor='#e2e8f0';this.style.boxShadow='none'">
                    </div>
                    <div>
                        <label style="display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:6px;">Email Address</label>
                        <input type="email" name="admin_email" required placeholder="admin@hotel.com"
                            style="width:100%;padding:10px 14px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:9px;color:#0f172a;font-size:14px;outline:none;font-family:inherit;"
                            onfocus="this.style.borderColor='#6366f1';this.style.boxShadow='0 0 0 3px rgba(99,102,241,0.1)'"
                            onblur="this.style.borderColor='#e2e8f0';this.style.boxShadow='none'">
                    </div>
                    <div>
                        <label style="display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:6px;">Phone Number</label>
                        <input type="text" name="admin_phone" required placeholder="+91 98765 43210"
                            style="width:100%;padding:10px 14px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:9px;color:#0f172a;font-size:14px;outline:none;font-family:inherit;"
                            onfocus="this.style.borderColor='#6366f1';this.style.boxShadow='0 0 0 3px rgba(99,102,241,0.1)'"
                            onblur="this.style.borderColor='#e2e8f0';this.style.boxShadow='none'">
                    </div>
                    <div>
                        <label style="display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:6px;">Password</label>
                        <input type="password" name="admin_password" required placeholder="••••••••"
                            style="width:100%;padding:10px 14px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:9px;color:#0f172a;font-size:14px;outline:none;font-family:inherit;"
                            onfocus="this.style.borderColor='#6366f1';this.style.boxShadow='0 0 0 3px rgba(99,102,241,0.1)'"
                            onblur="this.style.borderColor='#e2e8f0';this.style.boxShadow='none'">
                    </div>
                </div>
            </div>

            <!-- SECTION 2: Hotel Details -->
            <div style="background:white;border:1px solid #e2e8f0;border-radius:14px;padding:24px;margin-bottom:16px;box-shadow:0 1px 4px rgba(0,0,0,0.04);">
                <div style="font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.7px;margin-bottom:20px;padding-bottom:12px;border-bottom:1px solid #f1f5f9;">Hotel Information</div>
                <div style="display:grid;gap:16px;">
                    <div>
                        <label style="display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:6px;">Hotel Name</label>
                        <input type="text" name="hotel_name" required placeholder="Grand Palace Hotel"
                            style="width:100%;padding:10px 14px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:9px;color:#0f172a;font-size:14px;outline:none;font-family:inherit;"
                            onfocus="this.style.borderColor='#6366f1';this.style.boxShadow='0 0 0 3px rgba(99,102,241,0.1)'"
                            onblur="this.style.borderColor='#e2e8f0';this.style.boxShadow='none'">
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                        <div>
                            <label style="display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:6px;">Official Email</label>
                            <input type="email" name="hotel_email" required placeholder="info@grandpalace.com"
                                style="width:100%;padding:10px 14px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:9px;color:#0f172a;font-size:14px;outline:none;font-family:inherit;"
                                onfocus="this.style.borderColor='#6366f1';this.style.boxShadow='0 0 0 3px rgba(99,102,241,0.1)'"
                                onblur="this.style.borderColor='#e2e8f0';this.style.boxShadow='none'">
                        </div>
                        <div>
                            <label style="display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:6px;">Hotel Phone</label>
                            <input type="text" name="hotel_phone" required placeholder="+91 22 1234 5678"
                                style="width:100%;padding:10px 14px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:9px;color:#0f172a;font-size:14px;outline:none;font-family:inherit;"
                                onfocus="this.style.borderColor='#6366f1';this.style.boxShadow='0 0 0 3px rgba(99,102,241,0.1)'"
                                onblur="this.style.borderColor='#e2e8f0';this.style.boxShadow='none'">
                        </div>
                    </div>
                    <div>
                        <label style="display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:6px;">Address</label>
                        <textarea name="address" rows="2" required placeholder="Street address, landmark..."
                            style="width:100%;padding:10px 14px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:9px;color:#0f172a;font-size:14px;outline:none;font-family:inherit;resize:none;"
                            onfocus="this.style.borderColor='#6366f1';this.style.boxShadow='0 0 0 3px rgba(99,102,241,0.1)'"
                            onblur="this.style.borderColor='#e2e8f0';this.style.boxShadow='none'"></textarea>
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;">
                        <div>
                            <label style="display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:6px;">City</label>
                            <input type="text" name="city" required placeholder="Mumbai"
                                style="width:100%;padding:10px 14px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:9px;color:#0f172a;font-size:14px;outline:none;font-family:inherit;"
                                onfocus="this.style.borderColor='#6366f1';this.style.boxShadow='0 0 0 3px rgba(99,102,241,0.1)'"
                                onblur="this.style.borderColor='#e2e8f0';this.style.boxShadow='none'">
                        </div>
                        <div>
                            <label style="display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:6px;">State</label>
                            <input type="text" name="state" required placeholder="Maharashtra"
                                style="width:100%;padding:10px 14px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:9px;color:#0f172a;font-size:14px;outline:none;font-family:inherit;"
                                onfocus="this.style.borderColor='#6366f1';this.style.boxShadow='0 0 0 3px rgba(99,102,241,0.1)'"
                                onblur="this.style.borderColor='#e2e8f0';this.style.boxShadow='none'">
                        </div>
                        <div>
                            <label style="display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:6px;">PIN Code</label>
                            <input type="text" name="zip" required placeholder="400001"
                                style="width:100%;padding:10px 14px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:9px;color:#0f172a;font-size:14px;outline:none;font-family:inherit;"
                                onfocus="this.style.borderColor='#6366f1';this.style.boxShadow='0 0 0 3px rgba(99,102,241,0.1)'"
                                onblur="this.style.borderColor='#e2e8f0';this.style.boxShadow='none'">
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 3: Documents -->
            <div style="background:white;border:1px solid #e2e8f0;border-radius:14px;padding:24px;margin-bottom:24px;box-shadow:0 1px 4px rgba(0,0,0,0.04);">
                <div style="font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.7px;margin-bottom:20px;padding-bottom:12px;border-bottom:1px solid #f1f5f9;">Verification Documents</div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div>
                        <label style="display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:6px;">Trade License</label>
                        <label style="display:flex;align-items:center;gap:10px;padding:12px 14px;background:#f8fafc;border:1.5px dashed #cbd5e1;border-radius:9px;cursor:pointer;transition:border-color 0.2s;">
                            <i class="fa-solid fa-cloud-arrow-up" style="color:#6366f1;font-size:16px;"></i>
                            <span style="font-size:13px;color:#64748b;">Upload PDF or Image</span>
                            <input type="file" name="trade_license" accept=".pdf,.jpg,.jpeg,.png" required style="display:none;">
                        </label>
                    </div>
                    <div>
                        <label style="display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:6px;">GST Certificate</label>
                        <label style="display:flex;align-items:center;gap:10px;padding:12px 14px;background:#f8fafc;border:1.5px dashed #cbd5e1;border-radius:9px;cursor:pointer;transition:border-color 0.2s;">
                            <i class="fa-solid fa-cloud-arrow-up" style="color:#6366f1;font-size:16px;"></i>
                            <span style="font-size:13px;color:#64748b;">Upload PDF or Image</span>
                            <input type="file" name="gst_certificate" accept=".pdf,.jpg,.jpeg,.png" required style="display:none;">
                        </label>
                    </div>
                </div>
            </div>

            <button type="submit" style="width:100%;padding:14px;background:linear-gradient(135deg,#6366f1,#4f46e5);color:white;border:none;border-radius:10px;font-size:15px;font-weight:700;cursor:pointer;box-shadow:0 4px 16px rgba(99,102,241,0.35);">
                Submit Registration &rarr;
            </button>
        </form>
    </div>
</div>
