<!-- LANDING PAGE -->
<div style="width:100%; min-height:100vh; background:#0f172a; display:flex; flex-direction:column;">

    <!-- NAV -->
    <nav style="padding:18px 48px; display:flex; align-items:center; justify-content:space-between; border-bottom:1px solid rgba(255,255,255,0.06);">
        <div style="display:flex; align-items:center; gap:10px;">
            <div style="width:36px;height:36px;background:linear-gradient(135deg,#6366f1,#4f46e5);border-radius:9px;display:flex;align-items:center;justify-content:center;color:white;">
                <i class="fa-solid fa-hotel" style="font-size:16px;"></i>
            </div>
            <span style="font-size:18px; font-weight:700; color:white;">CH<span style="color:#6366f1;">NMS</span></span>
        </div>
        <div style="display:flex; gap:12px;">
            <a href="<?= BASE_URL ?>/search" style="padding:8px 18px; border-radius:8px; font-size:14px; font-weight:600; color:#94a3b8; text-decoration:none; transition:color 0.2s;" onmouseover="this.style.color='white'" onmouseout="this.style.color='#94a3b8'">Find a Room</a>
            <a href="<?= BASE_URL ?>/register-hotel" style="padding:8px 18px; border-radius:8px; font-size:14px; font-weight:600; color:#94a3b8; text-decoration:none; transition:color 0.2s;" onmouseover="this.style.color='white'" onmouseout="this.style.color='#94a3b8'">Partner with Us</a>
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="<?= BASE_URL ?>/<?= $_SESSION['role_id'] == 1 ? 'admin' : 'hotel' ?>/dashboard" style="padding:9px 20px; background:linear-gradient(135deg,#6366f1,#4f46e5); color:white; border-radius:8px; font-size:14px; font-weight:600; text-decoration:none; box-shadow:0 4px 12px rgba(99,102,241,0.3);">Dashboard</a>
            <?php else: ?>
                <a href="<?= BASE_URL ?>/login" style="padding:9px 20px; background:linear-gradient(135deg,#6366f1,#4f46e5); color:white; border-radius:8px; font-size:14px; font-weight:600; text-decoration:none; box-shadow:0 4px 12px rgba(99,102,241,0.3);">Sign In</a>
            <?php endif; ?>
        </div>
    </nav>

    <!-- HERO -->
    <div style="flex:1; display:flex; align-items:center; justify-content:center; padding:60px 48px; text-align:center;">
        <div style="max-width:700px;" class="fade-in">
            <div style="display:inline-flex;align-items:center;gap:8px;background:rgba(99,102,241,0.1);border:1px solid rgba(99,102,241,0.2);padding:6px 14px;border-radius:20px;margin-bottom:28px;">
                <div style="width:7px;height:7px;background:#6366f1;border-radius:50%;"></div>
                <span style="font-size:12px;font-weight:600;color:#a5b4fc;letter-spacing:0.5px;">ENTERPRISE HOTEL NETWORK</span>
            </div>
            <h1 style="font-size:52px; font-weight:800; color:white; line-height:1.15; letter-spacing:-1.5px; margin-bottom:20px;">
                Manage Every Hotel.<br><span style="background:linear-gradient(90deg,#6366f1,#a78bfa);-webkit-background-clip:text;-webkit-text-fill-color:transparent;">From One Place.</span>
            </h1>
            <p style="font-size:18px; color:#94a3b8; line-height:1.6; margin-bottom:40px;">
                Enterprise-grade centralized booking, real-time room tracking, and smart transfer engine for your hotel network.
            </p>
            <div style="display:flex;gap:14px;justify-content:center;flex-wrap:wrap;">
                <a href="<?= BASE_URL ?>/search" style="padding:14px 30px;background:linear-gradient(135deg,#6366f1,#4f46e5);color:white;border-radius:10px;font-size:15px;font-weight:700;text-decoration:none;box-shadow:0 6px 20px rgba(99,102,241,0.4);">
                    <i class="fa-solid fa-magnifying-glass me-2"></i> Find a Room
                </a>
                <a href="<?= BASE_URL ?>/register-hotel" style="padding:14px 30px;background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);color:white;border-radius:10px;font-size:15px;font-weight:700;text-decoration:none;">
                    <i class="fa-solid fa-building me-2"></i> Partner With Us
                </a>
            </div>

            <!-- STATS ROW -->
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-top:56px;max-width:500px;margin-left:auto;margin-right:auto;" class="fade-in-2">
                <div style="padding:16px;background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.07);border-radius:12px;">
                    <div style="font-size:24px;font-weight:800;color:white;">50+</div>
                    <div style="font-size:12px;color:#94a3b8;margin-top:3px;">Partner Hotels</div>
                </div>
                <div style="padding:16px;background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.07);border-radius:12px;">
                    <div style="font-size:24px;font-weight:800;color:white;">2K+</div>
                    <div style="font-size:12px;color:#94a3b8;margin-top:3px;">Rooms Managed</div>
                </div>
                <div style="padding:16px;background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.07);border-radius:12px;">
                    <div style="font-size:24px;font-weight:800;color:white;">99.9%</div>
                    <div style="font-size:12px;color:#94a3b8;margin-top:3px;">Uptime</div>
                </div>
            </div>
        </div>
    </div>
</div>
