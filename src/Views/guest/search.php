<!-- SEARCH PAGE — PUBLIC, NO SIDEBAR -->
<div style="min-height:100vh; background:#0f172a; font-family:'Inter',sans-serif; padding:0;">

    <!-- TOP NAV -->
    <nav style="padding:16px 40px; display:flex; align-items:center; justify-content:space-between; border-bottom:1px solid rgba(255,255,255,0.06);">
        <a href="<?= BASE_URL ?>/" style="display:flex;align-items:center;gap:10px;text-decoration:none;">
            <div style="width:34px;height:34px;background:linear-gradient(135deg,#6366f1,#4f46e5);border-radius:9px;display:flex;align-items:center;justify-content:center;color:white;font-size:15px;"><i class="fa-solid fa-hotel"></i></div>
            <span style="font-size:17px;font-weight:700;color:white;">CH<span style="color:#6366f1;">NMS</span></span>
        </a>
        <a href="<?= BASE_URL ?>/login" style="font-size:13px;font-weight:600;color:#64748b;text-decoration:none;">Sign In &rarr;</a>
    </nav>

    <!-- SEARCH HERO -->
    <div style="padding:48px 40px 32px; text-align:center;">
        <h1 style="font-size:36px;font-weight:800;color:white;letter-spacing:-1px;margin-bottom:10px;">Find Your Perfect Room</h1>
        <p style="font-size:16px;color:#64748b;margin-bottom:32px;">Search across our entire network of premium partner hotels.</p>

        <!-- SEARCH BAR -->
        <div style="max-width:720px;margin:0 auto;">
            <form action="<?= BASE_URL ?>/search" method="GET">
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr auto;gap:10px;background:#1e293b;border:1px solid rgba(255,255,255,0.07);border-radius:14px;padding:12px;">
                    <div>
                        <label style="display:block;font-size:10px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:4px;padding-left:2px;">City</label>
                        <input type="text" name="city" value="<?= htmlspecialchars($city ?? '') ?>" placeholder="Mumbai, Goa..."
                            style="width:100%;background:transparent;border:none;color:white;font-size:14px;font-weight:500;outline:none;" required>
                    </div>
                    <div style="border-left:1px solid rgba(255,255,255,0.07);padding-left:12px;">
                        <label style="display:block;font-size:10px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:4px;">Check-in</label>
                        <input type="date" name="checkin" value="<?= htmlspecialchars($checkin ?? '') ?>"
                            style="width:100%;background:transparent;border:none;color:white;font-size:14px;font-weight:500;outline:none;color-scheme:dark;" required>
                    </div>
                    <div style="border-left:1px solid rgba(255,255,255,0.07);padding-left:12px;">
                        <label style="display:block;font-size:10px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:4px;">Check-out</label>
                        <input type="date" name="checkout" value="<?= htmlspecialchars($checkout ?? '') ?>"
                            style="width:100%;background:transparent;border:none;color:white;font-size:14px;font-weight:500;outline:none;color-scheme:dark;" required>
                    </div>
                    <button type="submit" style="padding:10px 22px;background:linear-gradient(135deg,#6366f1,#4f46e5);color:white;border:none;border-radius:10px;font-size:14px;font-weight:700;cursor:pointer;white-space:nowrap;box-shadow:0 4px 12px rgba(99,102,241,0.4);">
                        Search
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- RESULTS -->
    <?php if (isset($_GET['city'])): ?>
    <div style="max-width:960px;margin:0 auto;padding:0 40px 48px;">
        <div style="font-size:14px;color:#64748b;margin-bottom:16px;"><?= count($hotels) ?> hotels found in "<?= htmlspecialchars($city) ?>"</div>
        
        <?php if (empty($hotels)): ?>
            <div style="text-align:center;padding:60px;color:#64748b;">No hotels found for this search.</div>
        <?php else: ?>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:16px;">
            <?php foreach($hotels as $h): ?>
            <div style="background:#1e293b;border:1px solid rgba(255,255,255,0.06);border-radius:14px;padding:22px;transition:transform 0.2s,box-shadow 0.2s;"
                onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 12px 40px rgba(0,0,0,0.3)'"
                onmouseout="this.style.transform='none';this.style.boxShadow='none'">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
                    <div style="width:42px;height:42px;background:rgba(99,102,241,0.15);border-radius:10px;display:flex;align-items:center;justify-content:center;color:#818cf8;font-size:18px;">
                        <i class="fa-solid fa-hotel"></i>
                    </div>
                    <div style="background:rgba(245,158,11,0.1);color:#f59e0b;padding:4px 10px;border-radius:20px;font-size:12px;font-weight:700;">
                        ⭐ <?= $h['rating'] ?>
                    </div>
                </div>
                <div style="font-size:16px;font-weight:700;color:white;margin-bottom:4px;"><?= htmlspecialchars($h['name']) ?></div>
                <div style="font-size:13px;color:#64748b;margin-bottom:16px;"><i class="fa-solid fa-location-dot me-1" style="color:#6366f1;"></i><?= htmlspecialchars($h['city']) ?></div>
                <div style="display:flex;align-items:center;justify-content:space-between;">
                    <div>
                        <span style="font-size:20px;font-weight:800;color:white;">₹<?= number_format($h['price']) ?></span>
                        <span style="font-size:12px;color:#64748b;"> /night</span>
                    </div>
                    <?php if ($h['available_rooms'] > 0): ?>
                        <a href="<?= BASE_URL ?>/checkout/<?= $h['id'] ?>" style="padding:8px 18px;background:linear-gradient(135deg,#6366f1,#4f46e5);color:white;border-radius:8px;font-size:13px;font-weight:700;text-decoration:none;">Book</a>
                    <?php else: ?>
                        <a href="<?= BASE_URL ?>/checkout/<?= $h['id'] ?>" style="padding:8px 18px;background:rgba(239,68,68,0.1);color:#ef4444;border-radius:8px;font-size:13px;font-weight:700;text-decoration:none;">Find Alt.</a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

</div>
