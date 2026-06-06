<div style="display:flex; min-height:100vh; font-family:'Inter',sans-serif; overflow:hidden;">
    
    <!-- LEFT PANEL (BRANDING) -->
    <div style="flex:5.5; background:#5b50d6; position:relative; overflow:hidden; padding:48px; display:flex; flex-direction:column; justify-content:space-between; color:white; display:none; @media (min-width: 992px) { display: flex; }">
        <!-- Background Shapes -->
        <div style="position:absolute; top:-150px; right:-100px; width:500px; height:500px; background:rgba(255,255,255,0.06); border-radius:50%;"></div>
        <div style="position:absolute; bottom:-200px; left:-150px; width:600px; height:600px; background:rgba(255,255,255,0.04); border-radius:50%;"></div>
        
        <!-- Logo Area -->
        <div style="position:relative; z-index:10; display:flex; align-items:center; gap:12px;">
            <div style="width:36px; height:36px; background:white; color:#5b50d6; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:16px;">
                <i class="fa-solid fa-hotel"></i>
            </div>
            <div style="font-size:18px; font-weight:800; letter-spacing:-0.5px;">CH<span style="opacity:0.8;">NMS</span></div>
        </div>

        <!-- Main Content -->
        <div style="position:relative; z-index:10; max-width:480px; margin-top:-80px;">
            <h1 style="font-size:36px; font-weight:800; line-height:1.2; margin-bottom:16px; letter-spacing:-0.5px;">Manage your hotel network with confidence</h1>
            <p style="font-size:15px; color:rgba(255,255,255,0.8); line-height:1.6; margin-bottom:0;">
                A modern hospitality platform built for partners that move fast — from centralized inventory to multi-hotel booking, all in one place.
            </p>
        </div>

        <!-- Features List -->
        <div style="position:relative; z-index:10;">
            <div style="display:flex; align-items:center; gap:12px; margin-bottom:16px;">
                <div style="width:24px; height:24px; border-radius:50%; background:rgba(255,255,255,0.15); display:flex; align-items:center; justify-content:center; font-size:11px;">
                    <i class="fa-solid fa-check"></i>
                </div>
                <div style="font-size:14px; color:rgba(255,255,255,0.9);">Role-based access for Super Admin & Hotel Partners</div>
            </div>
            <div style="display:flex; align-items:center; gap:12px; margin-bottom:16px;">
                <div style="width:24px; height:24px; border-radius:50%; background:rgba(255,255,255,0.15); display:flex; align-items:center; justify-content:center; font-size:11px;">
                    <i class="fa-solid fa-check"></i>
                </div>
                <div style="font-size:14px; color:rgba(255,255,255,0.9);">Real-time room inventory & revenue settlements</div>
            </div>
            <div style="display:flex; align-items:center; gap:12px;">
                <div style="width:24px; height:24px; border-radius:50%; background:rgba(255,255,255,0.15); display:flex; align-items:center; justify-content:center; font-size:11px;">
                    <i class="fa-solid fa-check"></i>
                </div>
                <div style="font-size:14px; color:rgba(255,255,255,0.9);">Secure, session-based authentication</div>
            </div>
        </div>
    </div>

    <!-- RIGHT PANEL (FORM) -->
    <div style="flex:4.5; background:white; display:flex; align-items:center; justify-content:center; padding:40px; min-width:400px;">
        <div style="width:100%; max-width:400px;" class="fade-in">
            
            <h2 style="font-size:28px; font-weight:800; color:#111827; margin-bottom:8px; letter-spacing:-0.5px;">Welcome back</h2>
            <p style="font-size:14px; color:#6b7280; margin-bottom:32px;">Sign in to your account to continue</p>

            <?php if(isset($_SESSION['error'])): ?>
            <div style="background:#fee2e2; border:1px solid #fecaca; border-radius:8px; padding:12px 16px; display:flex; align-items:center; gap:12px; margin-bottom:24px;">
                <i class="fa-solid fa-circle-exclamation" style="color:#ef4444; font-size:14px;"></i>
                <span style="font-size:13px; color:#b91c1c; font-weight:500;"><?= $_SESSION['error']; unset($_SESSION['error']); ?></span>
            </div>
            <?php endif; ?>

            <form action="<?= BASE_URL ?>/login" method="POST">
                <div style="margin-bottom:20px;">
                    <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:8px;">Email Address</label>
                    <div style="position:relative;">
                        <i class="fa-regular fa-envelope" style="position:absolute; left:14px; top:12px; color:#9ca3af; font-size:14px;"></i>
                        <input type="email" name="email" required placeholder="admin@chnms.com"
                            style="width:100%; padding:10px 14px 10px 40px; background:#f3f4f6; border:1px solid transparent; border-radius:8px; color:#111827; font-size:14px; outline:none; transition:all 0.2s;"
                            onfocus="this.style.borderColor='#5b50d6'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(91,80,214,0.1)'" 
                            onblur="this.style.borderColor='transparent'; this.style.background='#f3f4f6'; this.style.boxShadow='none'">
                    </div>
                </div>

                <div style="margin-bottom:12px;">
                    <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:8px;">Password</label>
                    <div style="position:relative;">
                        <i class="fa-solid fa-lock" style="position:absolute; left:14px; top:12px; color:#9ca3af; font-size:14px;"></i>
                        <input type="password" name="password" required placeholder="••••••••"
                            style="width:100%; padding:10px 40px 10px 40px; background:#f3f4f6; border:1px solid transparent; border-radius:8px; color:#111827; font-size:14px; outline:none; transition:all 0.2s;"
                            onfocus="this.style.borderColor='#5b50d6'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(91,80,214,0.1)'" 
                            onblur="this.style.borderColor='transparent'; this.style.background='#f3f4f6'; this.style.boxShadow='none'">
                        <i class="fa-regular fa-eye" style="position:absolute; right:14px; top:12px; color:#9ca3af; font-size:14px; cursor:pointer;"></i>
                    </div>
                </div>

                <div style="text-align:right; margin-bottom:24px;">
                    <a href="#" style="font-size:13px; font-weight:600; color:#5b50d6; text-decoration:none;">Forgot password?</a>
                </div>

                <button type="submit"
                    style="width:100%; padding:12px; background:#5b50d6; color:white; border:none; border-radius:8px; font-size:14px; font-weight:600; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px; transition:background 0.2s;"
                    onmouseover="this.style.background='#4c41b8'" onmouseout="this.style.background='#5b50d6'">
                    Sign In <i class="fa-solid fa-arrow-right" style="font-size:12px;"></i>
                </button>
            </form>
            
            <div style="text-align:center; margin-top:32px; font-size:12px; color:#9ca3af;">
                &copy; 2026 CHNMS Portal. All rights reserved. <br>
                <a href="<?= BASE_URL ?>/" style="color:#9ca3af; text-decoration:none; margin-top:8px; display:inline-block;">&larr; Back to Home</a>
            </div>
        </div>
    </div>
</div>
