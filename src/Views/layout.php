<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'CHNMS') ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --primary-light: rgba(99,102,241,0.08);
            --success: #22c55e;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #06b6d4;
            --bg: #f8fafc;
            --sidebar-bg: #ffffff;
            --card-bg: #ffffff;
            --text-heading: #0f172a;
            --text-body: #475569;
            --text-muted: #94a3b8;
            --border: #e2e8f0;
            --sidebar-width: 255px;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
            --shadow-md: 0 4px 16px rgba(0,0,0,0.06);
            --shadow-lg: 0 10px 40px rgba(0,0,0,0.08);
            --radius: 12px;
            --radius-sm: 8px;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--bg);
            color: var(--text-body);
            display: flex;
            min-height: 100vh;
            overflow: hidden;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            height: 100vh;
            position: fixed;
            left: 0; top: 0;
            z-index: 100;
            overflow-y: auto;
        }
        .sidebar-logo {
            padding: 24px 20px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 1px solid var(--border);
            margin-bottom: 10px;
        }
        .logo-icon {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            color: white; font-size: 16px;
            box-shadow: 0 4px 12px rgba(99,102,241,0.3);
        }
        .logo-text { font-size: 17px; font-weight: 700; color: var(--text-heading); letter-spacing: -0.3px; }
        .logo-text span { color: var(--primary); }

        .sidebar-section {
            padding: 6px 12px 4px;
            font-size: 10.5px;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-top: 14px;
        }
        .nav-item { padding: 2px 8px; }
        .nav-link {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 12px;
            border-radius: var(--radius-sm);
            color: var(--text-body);
            font-size: 14px;
            font-weight: 500;
            transition: all 0.15s ease;
            text-decoration: none;
        }
        .nav-link i { width: 18px; text-align: center; font-size: 15px; color: var(--text-muted); transition: color 0.15s; }
        .nav-link:hover { background: var(--primary-light); color: var(--primary); }
        .nav-link:hover i { color: var(--primary); }
        .nav-link.active { background: var(--primary-light); color: var(--primary); font-weight: 600; }
        .nav-link.active i { color: var(--primary); }
        .nav-link.danger { color: var(--danger); }
        .nav-link.danger i { color: var(--danger); }
        .nav-link.danger:hover { background: rgba(239,68,68,0.08); }

        .sidebar-footer {
            margin-top: auto;
            padding: 12px;
            border-top: 1px solid var(--border);
        }

        /* ===== MAIN AREA ===== */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            flex: 1;
            display: flex;
            flex-direction: column;
            height: 100vh;
            overflow: hidden;
        }

        /* ===== TOPBAR ===== */
        .topbar {
            height: 64px;
            background: white;
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 28px;
            flex-shrink: 0;
        }
        .topbar-title { font-size: 18px; font-weight: 700; color: var(--text-heading); }
        .topbar-subtitle { font-size: 13px; color: var(--text-muted); }
        .user-chip {
            display: flex; align-items: center; gap: 10px;
            background: var(--bg);
            border: 1px solid var(--border);
            padding: 6px 14px 6px 6px;
            border-radius: 40px;
            cursor: pointer;
        }
        .avatar {
            width: 32px; height: 32px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 13px; font-weight: 700;
        }
        .user-chip-name { font-size: 13px; font-weight: 600; color: var(--text-heading); }
        .user-chip-role { font-size: 11px; color: var(--text-muted); }

        /* ===== CONTENT ===== */
        .content-area {
            flex: 1;
            overflow-y: auto;
            padding: 24px 28px;
        }

        /* ===== CARDS ===== */
        .card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
            margin-bottom: 20px;
            transition: box-shadow 0.2s ease;
        }
        .card:hover { box-shadow: var(--shadow-md); }
        .card-header {
            background: transparent;
            border-bottom: 1px solid var(--border);
            padding: 16px 20px;
            font-size: 15px;
            font-weight: 600;
            color: var(--text-heading);
            display: flex; align-items: center; justify-content: space-between;
        }
        .card-body { padding: 20px; }

        /* ===== STAT CARDS ===== */
        .stat-card { padding: 20px; }
        .stat-card .icon-wrap {
            width: 48px; height: 48px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; margin-bottom: 14px;
        }
        .stat-card .value { font-size: 26px; font-weight: 800; color: var(--text-heading); }
        .stat-card .label { font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-top: 2px; }
        .stat-card .sub { font-size: 12px; color: var(--text-muted); margin-top: 6px; }
        .icon-indigo { background: rgba(99,102,241,0.1); color: #6366f1; }
        .icon-green  { background: rgba(34,197,94,0.1); color: #22c55e; }
        .icon-amber  { background: rgba(245,158,11,0.1); color: #f59e0b; }
        .icon-red    { background: rgba(239,68,68,0.1); color: #ef4444; }
        .icon-cyan   { background: rgba(6,182,212,0.1); color: #06b6d4; }

        /* ===== TABLES ===== */
        .table { color: var(--text-body); font-size: 14px; }
        .table thead th {
            color: var(--text-muted); font-size: 11px; font-weight: 600;
            text-transform: uppercase; letter-spacing: 0.5px;
            padding: 13px 16px; border-bottom: 1px solid var(--border);
            background: transparent;
        }
        .table tbody td {
            padding: 13px 16px; border-bottom: 1px solid var(--border);
            vertical-align: middle; color: var(--text-body);
        }
        .table tbody tr:last-child td { border-bottom: none; }
        .table tbody tr:hover td { background: #f8fafc; }

        /* ===== BADGES ===== */
        .badge-pill {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 4px 10px; border-radius: 20px;
            font-size: 12px; font-weight: 600;
        }
        .badge-pill .dot { width: 6px; height: 6px; border-radius: 50%; }
        .badge-active { background: rgba(34,197,94,0.1); color: #16a34a; }
        .badge-active .dot { background: #22c55e; }
        .badge-pending { background: rgba(245,158,11,0.1); color: #b45309; }
        .badge-pending .dot { background: #f59e0b; }
        .badge-occupied { background: rgba(239,68,68,0.1); color: #dc2626; }
        .badge-occupied .dot { background: #ef4444; }
        .badge-cleaning { background: rgba(6,182,212,0.1); color: #0891b2; }
        .badge-cleaning .dot { background: #06b6d4; }

        /* ===== FORMS ===== */
        .form-label { font-size: 13px; font-weight: 600; color: var(--text-heading); margin-bottom: 6px; }
        .form-control, .form-select {
            border: 1px solid var(--border); border-radius: var(--radius-sm);
            font-size: 14px; color: var(--text-heading);
            padding: 9px 14px; transition: border-color 0.15s, box-shadow 0.15s;
            background: white;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary); outline: none;
            box-shadow: 0 0 0 3px rgba(99,102,241,0.12);
        }
        .form-section-title {
            font-size: 13px; font-weight: 700; color: var(--text-heading);
            text-transform: uppercase; letter-spacing: 0.5px;
            padding-bottom: 10px; margin-bottom: 16px;
            border-bottom: 1px solid var(--border);
        }

        /* ===== BUTTONS ===== */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white; border: none; border-radius: var(--radius-sm);
            font-size: 14px; font-weight: 600; padding: 9px 20px;
            box-shadow: 0 2px 8px rgba(99,102,241,0.3);
            transition: all 0.2s;
        }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 4px 16px rgba(99,102,241,0.4); color: white; }
        .btn-outline-primary {
            color: var(--primary); border: 1.5px solid var(--primary);
            background: transparent; border-radius: var(--radius-sm);
            font-size: 14px; font-weight: 600; padding: 9px 20px;
            transition: all 0.2s;
        }
        .btn-outline-primary:hover { background: var(--primary); color: white; }
        .btn-light { background: var(--bg); border: 1px solid var(--border); color: var(--text-body); border-radius: var(--radius-sm); font-size: 14px; font-weight: 500; }
        .btn-icon { width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: var(--radius-sm); border: none; cursor: pointer; transition: all 0.15s; font-size: 13px; }
        .btn-icon-primary { background: var(--primary-light); color: var(--primary); }
        .btn-icon-danger { background: rgba(239,68,68,0.08); color: var(--danger); }

        /* ===== MODALS ===== */
        .modal-content { border: none; border-radius: 16px; box-shadow: var(--shadow-lg); }
        .modal-header { border-bottom: 1px solid var(--border); padding: 20px 24px; }
        .modal-title { font-size: 16px; font-weight: 700; color: var(--text-heading); }
        .modal-body { padding: 24px; }
        .modal-footer { border-top: 1px solid var(--border); padding: 16px 24px; }

        /* ===== ALERTS ===== */
        .alert { border: none; border-radius: var(--radius-sm); font-size: 14px; font-weight: 500; padding: 12px 16px; }
        .alert-success { background: rgba(34,197,94,0.1); color: #16a34a; }
        .alert-danger { background: rgba(239,68,68,0.1); color: #dc2626; }
        .alert-info { background: rgba(6,182,212,0.1); color: #0891b2; }

        /* ===== ROW IDENTITY ===== */
        .identity-cell { display: flex; align-items: center; gap: 12px; }
        .identity-avatar {
            width: 34px; height: 34px; border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            font-size: 13px; font-weight: 700;
        }
        .identity-name { font-size: 14px; font-weight: 600; color: var(--text-heading); }
        .identity-sub { font-size: 12px; color: var(--text-muted); }

        /* ===== PAGE HEADER ===== */
        .page-header { margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; }
        .page-header h2 { font-size: 20px; font-weight: 700; color: var(--text-heading); }
        .page-header p { font-size: 13px; color: var(--text-muted); margin-top: 2px; }

        /* ===== FULLPAGE (no sidebar) ===== */
        .fullpage-wrap {
            width: 100%; min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            background: var(--bg);
        }

        /* Animations */
        @keyframes fadeUp { from { opacity:0; transform:translateY(16px); } to { opacity:1; transform:translateY(0); } }
        .fade-in { animation: fadeUp 0.4s ease both; }
        .fade-in-2 { animation: fadeUp 0.4s 0.1s ease both; }
        .fade-in-3 { animation: fadeUp 0.4s 0.2s ease both; }
    </style>
</head>
<body>

<?php if (isset($_SESSION['user_id'])): ?>
<!-- SIDEBAR -->
<aside class="sidebar">
    <div class="sidebar-logo">
        <div class="logo-icon"><i class="fa-solid fa-hotel"></i></div>
        <div class="logo-text">CH<span>NMS</span></div>
    </div>

    <?php if ($_SESSION['role_id'] == 1): // Super Admin ?>
    <div class="sidebar-section">Main</div>
    <ul class="nav flex-column">
        <li class="nav-item"><a class="nav-link active" href="<?= BASE_URL ?>/admin/dashboard"><i class="fa-solid fa-gauge-high"></i> Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/admin/hotels"><i class="fa-solid fa-building"></i> Hotels</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/admin/bookings"><i class="fa-solid fa-calendar-check"></i> Bookings</a></li>
    </ul>
    <div class="sidebar-section">Management</div>
    <ul class="nav flex-column">
        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/admin/finance"><i class="fa-solid fa-chart-line"></i> Finance</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/admin/search"><i class="fa-solid fa-magnifying-glass"></i> Room Search</a></li>
    </ul>
    <?php elseif ($_SESSION['role_id'] == 2): // Hotel Admin ?>
    <div class="sidebar-section">Main</div>
    <ul class="nav flex-column">
        <li class="nav-item"><a class="nav-link active" href="<?= BASE_URL ?>/hotel/dashboard"><i class="fa-solid fa-gauge-high"></i> Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/hotel/rooms"><i class="fa-solid fa-bed"></i> Rooms</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/hotel/bookings"><i class="fa-solid fa-calendar-check"></i> Bookings</a></li>
    </ul>
    <?php endif; ?>

    <div class="sidebar-footer">
        <a class="nav-link danger" href="<?= BASE_URL ?>/logout"><i class="fa-solid fa-arrow-right-from-bracket"></i> Sign Out</a>
    </div>
</aside>

<!-- MAIN WRAPPER -->
<div class="main-wrapper">
    <div class="topbar">
        <div>
            <div class="topbar-title"><?= htmlspecialchars($title ?? 'Dashboard') ?></div>
            <div class="topbar-subtitle"><?= date('l, d F Y') ?></div>
        </div>
        <div class="user-chip">
            <div class="avatar"><?= strtoupper(substr($_SESSION['name'], 0, 1)) ?></div>
            <div>
                <div class="user-chip-name"><?= htmlspecialchars($_SESSION['name']) ?></div>
                <div class="user-chip-role"><?= $_SESSION['role_id'] == 1 ? 'Super Admin' : 'Hotel Admin' ?></div>
            </div>
        </div>
    </div>

    <div class="content-area">
        <?php if(isset($_SESSION['error'])): ?>
            <div class="alert alert-danger fade-in mb-3"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        <?php if(isset($_SESSION['success'])): ?>
            <div class="alert alert-success fade-in mb-3"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        <?= $content ?>
    </div>
</div>

<?php else: ?>
<!-- NO SIDEBAR: public pages rendered without sidebar -->
<div style="width:100%; min-height:100vh; background:var(--bg);">
    <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-danger" style="margin:16px;"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    <?php if(isset($_SESSION['success'])): ?>
        <div class="alert alert-success" style="margin:16px;"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    <?= $content ?>
</div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
