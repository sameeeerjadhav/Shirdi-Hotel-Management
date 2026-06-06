<?php
$b   = $booking ?? [];
$ref = $b['booking_ref'] ?? ('#' . $b['id']);
$nights = max(1, (int)((strtotime($b['check_out_date']) - strtotime($b['check_in_date'])) / 86400));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking Confirmed — CHNMS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: #f8fafc; min-height: 100vh;
               display: flex; align-items: center; justify-content: center; padding: 24px; }

        .card {
            background: white; border-radius: 24px; max-width: 560px; width: 100%;
            box-shadow: 0 8px 48px rgba(0,0,0,0.08); overflow: hidden;
        }

        /* Green success header */
        .success-header {
            background: linear-gradient(135deg, #10b981, #059669);
            padding: 40px 40px 32px;
            text-align: center; color: white;
        }
        .checkmark {
            width: 72px; height: 72px; border-radius: 50%;
            background: rgba(255,255,255,0.2);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 20px; font-size: 36px;
            animation: pop 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) both;
        }
        @keyframes pop { from { transform: scale(0); opacity: 0; } to { transform: scale(1); opacity: 1; } }
        .success-title { font-size: 24px; font-weight: 800; margin-bottom: 6px; }
        .success-sub   { font-size: 14px; opacity: 0.85; font-weight: 500; }
        .booking-ref   { display: inline-block; margin-top: 16px;
                         background: rgba(255,255,255,0.2); border-radius: 99px;
                         padding: 6px 20px; font-size: 15px; font-weight: 800; letter-spacing: 1px; }

        /* Body */
        .body { padding: 32px 40px; }

        .section-title {
            font-size: 11px; font-weight: 800; color: #94a3b8;
            text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 16px;
        }
        .detail-grid {
            display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 28px;
        }
        .detail-item { }
        .detail-label { font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin-bottom: 4px; }
        .detail-value { font-size: 14px; font-weight: 700; color: #1e293b; }
        .detail-sub   { font-size: 12px; color: #64748b; margin-top: 2px; }

        /* Billing box */
        .billing-box { background: #f8fafc; border-radius: 16px; padding: 20px; margin-bottom: 28px; }
        .billing-row { display: flex; justify-content: space-between; align-items: center; font-size: 13px; color: #64748b; }
        .billing-row + .billing-row { margin-top: 10px; }
        .billing-total { border-top: 1px solid #e2e8f0; margin-top: 14px; padding-top: 14px;
                         font-size: 16px; font-weight: 800; color: #1e293b; }
        .billing-total span:last-child { color: #10b981; }

        /* Status badge */
        .status-paid { background: #ecfdf5; color: #065f46; padding: 4px 12px;
                       border-radius: 99px; font-size: 12px; font-weight: 700; }

        /* Actions */
        .actions { display: flex; gap: 12px; margin-top: 8px; }
        .btn-primary {
            flex: 1; padding: 14px; border-radius: 12px; border: none; cursor: pointer;
            background: linear-gradient(135deg, #4338ca, #6366f1);
            color: white; font-size: 14px; font-weight: 700;
            text-decoration: none; text-align: center;
            transition: opacity 0.2s; display: block;
        }
        .btn-primary:hover { opacity: 0.9; }
        .btn-light {
            flex: 1; padding: 14px; border-radius: 12px;
            border: 1.5px solid #e2e8f0; background: white;
            color: #475569; font-size: 14px; font-weight: 700;
            cursor: pointer; text-decoration: none; text-align: center;
            transition: background 0.15s; display: block;
        }
        .btn-light:hover { background: #f8fafc; }

        /* Footer note */
        .note { margin-top: 24px; padding: 14px 20px; background: #fffbeb;
                border: 1px solid #fef08a; border-radius: 12px;
                font-size: 12px; color: #92400e; line-height: 1.6; text-align: center; }
    </style>
</head>
<body>
<div class="card">
    <!-- SUCCESS HEADER -->
    <div class="success-header">
        <div class="checkmark"><i class="fa-solid fa-check" style="font-size:34px;"></i></div>
        <div class="success-title">Booking Confirmed!</div>
        <div class="success-sub">Your reservation is all set. See you soon!</div>
        <div class="booking-ref"><?= htmlspecialchars($ref) ?></div>
    </div>

    <!-- BODY -->
    <div class="body">
        <!-- Hotel & Stay -->
        <div class="section-title">Stay Details</div>
        <div class="detail-grid">
            <div class="detail-item">
                <div class="detail-label">Hotel</div>
                <div class="detail-value"><?= htmlspecialchars($b['hotel_name'] ?? 'N/A') ?></div>
                <div class="detail-sub"><?= htmlspecialchars($b['hotel_city'] ?? '') ?></div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Room</div>
                <div class="detail-value">Room <?= htmlspecialchars($b['room_number'] ?? 'N/A') ?></div>
                <div class="detail-sub"><?= htmlspecialchars($b['room_type'] ?? '') ?></div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Check-In</div>
                <div class="detail-value"><?= date('D, d M Y', strtotime($b['check_in_date'])) ?></div>
                <div class="detail-sub">After 12:00 PM</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Check-Out</div>
                <div class="detail-value"><?= date('D, d M Y', strtotime($b['check_out_date'])) ?></div>
                <div class="detail-sub">Before 11:00 AM</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Duration</div>
                <div class="detail-value"><?= $nights ?> Night<?= $nights > 1 ? 's' : '' ?></div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Guest</div>
                <div class="detail-value"><?= htmlspecialchars($b['guest_name'] ?? 'N/A') ?></div>
                <div class="detail-sub"><?= htmlspecialchars($b['guest_phone'] ?? '') ?></div>
            </div>
        </div>

        <!-- Billing -->
        <div class="section-title">Payment Summary</div>
        <div class="billing-box">
            <div class="billing-row">
                <span>Room × <?= $nights ?> nights</span>
                <span>₹<?= number_format($b['total_amount'] ?? 0) ?></span>
            </div>
            <div class="billing-row billing-total">
                <span>Total Paid</span>
                <span>₹<?= number_format($b['total_amount'] ?? 0) ?></span>
            </div>
            <div class="billing-row" style="margin-top:10px;">
                <span style="font-size:12px;color:#94a3b8;">Payment Status</span>
                <span class="status-paid"><i class="fa-solid fa-check me-1"></i>Paid</span>
            </div>
        </div>

        <!-- Actions -->
        <div class="actions">
            <a href="<?= BASE_URL ?>/" class="btn-primary">Back to Home</a>
            <a href="<?= BASE_URL ?>/search" class="btn-light">Search More</a>
        </div>

        <!-- Note -->
        <div class="note">
            <i class="fa-solid fa-circle-info me-1" style="color:#b45309;"></i> A confirmation has been logged in the system. Please carry a valid ID proof at the time of check-in.
        </div>
    </div>
</div>
</body>
</html>
