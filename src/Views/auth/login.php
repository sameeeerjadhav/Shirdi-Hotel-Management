<!-- LOGIN PAGE -->
<div style="min-height:100vh; background:#0f172a; display:flex; align-items:center; justify-content:center; padding:24px;">
    <div style="width:100%; max-width:400px;" class="fade-in">
        <!-- LOGO -->
        <div style="text-align:center; margin-bottom:32px;">
            <div style="width:52px;height:52px;background:linear-gradient(135deg,#6366f1,#4f46e5);border-radius:14px;display:inline-flex;align-items:center;justify-content:center;color:white;font-size:22px;box-shadow:0 8px 24px rgba(99,102,241,0.4);margin-bottom:16px;">
                <i class="fa-solid fa-hotel"></i>
            </div>
            <h1 style="font-size:22px; font-weight:800; color:white; letter-spacing:-0.5px;">Sign in to CHNMS</h1>
            <p style="font-size:14px; color:#64748b; margin-top:6px;">Enter your credentials to continue</p>
        </div>

        <!-- CARD -->
        <div style="background:#1e293b; border:1px solid rgba(255,255,255,0.06); border-radius:16px; padding:32px; box-shadow:0 25px 60px rgba(0,0,0,0.3);">
            <form action="<?= BASE_URL ?>/login" method="POST">
                <div style="margin-bottom:18px;">
                    <label style="display:block; font-size:13px; font-weight:600; color:#e2e8f0; margin-bottom:7px;">Email Address</label>
                    <input type="email" name="email" required placeholder="admin@chnms.com"
                        style="width:100%; padding:11px 14px; background:#0f172a; border:1px solid rgba(255,255,255,0.08); border-radius:9px; color:white; font-size:14px; outline:none; transition:border-color 0.2s;"
                        onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='rgba(255,255,255,0.08)'">
                </div>
                <div style="margin-bottom:24px;">
                    <label style="display:block; font-size:13px; font-weight:600; color:#e2e8f0; margin-bottom:7px;">Password</label>
                    <input type="password" name="password" required placeholder="••••••••"
                        style="width:100%; padding:11px 14px; background:#0f172a; border:1px solid rgba(255,255,255,0.08); border-radius:9px; color:white; font-size:14px; outline:none; transition:border-color 0.2s;"
                        onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='rgba(255,255,255,0.08)'">
                </div>
                <button type="submit"
                    style="width:100%; padding:12px; background:linear-gradient(135deg,#6366f1,#4f46e5); color:white; border:none; border-radius:9px; font-size:15px; font-weight:700; cursor:pointer; box-shadow:0 4px 16px rgba(99,102,241,0.4);">
                    Sign In &rarr;
                </button>
            </form>
        </div>

        <div style="text-align:center; margin-top:20px;">
            <a href="<?= BASE_URL ?>/" style="font-size:13px; color:#64748b; text-decoration:none;">&larr; Back to Home</a>
        </div>
    </div>
</div>
