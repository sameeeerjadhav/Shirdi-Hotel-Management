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
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --primary-light: #e0e7ff;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #0ea5e9;
            --bg: #f3f4f6; /* Very clean light grey */
            --sidebar-bg: #ffffff;
            --card-bg: #ffffff;
            --text-heading: #111827;
            --text-body: #374151;
            --text-muted: #6b7280;
            --border: #e5e7eb;
            --sidebar-width: 260px;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --radius: 12px;
            --radius-sm: 8px;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background: var(--bg);
            color: var(--text-body);
            display: flex;
            min-height: 100vh;
            overflow: hidden;
            -webkit-font-smoothing: antialiased;
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
            padding: 24px 24px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 8px;
        }
        .logo-icon {
            width: 32px; height: 32px;
            background: var(--primary);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            color: white; font-size: 16px;
            box-shadow: var(--shadow-sm);
        }
        .logo-text { font-size: 18px; font-weight: 700; color: var(--text-heading); letter-spacing: -0.02em; }
        .logo-text span { color: var(--primary); }

        .sidebar-section {
            padding: 16px 24px 8px;
            font-size: 11px;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .nav-item { padding: 2px 12px; }
        .nav-link {
            display: flex; align-items: center; gap: 12px;
            padding: 8px 12px;
            border-radius: var(--radius-sm);
            color: var(--text-body);
            font-size: 14px;
            font-weight: 500;
            transition: all 0.15s ease;
            text-decoration: none;
        }
        .nav-link i { width: 20px; text-align: center; font-size: 16px; color: #9ca3af; transition: color 0.15s; }
        .nav-link:hover { background: #f3f4f6; color: var(--text-heading); }
        .nav-link:hover i { color: var(--text-body); }
        .nav-link.active { background: var(--primary-light); color: var(--primary); font-weight: 600; }
        .nav-link.active i { color: var(--primary); }
        .nav-link.danger { color: var(--danger); }
        .nav-link.danger i { color: #fca5a5; }
        .nav-link.danger:hover { background: #fef2f2; color: var(--danger); }

        .sidebar-footer {
            margin-top: auto;
            padding: 16px 12px;
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
            height: 72px;
            background: white;
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 32px;
            flex-shrink: 0;
            position: sticky; top: 0; z-index: 50;
        }
        .topbar-title { font-size: 18px; font-weight: 600; color: var(--text-heading); letter-spacing: -0.01em; }
        .topbar-subtitle { font-size: 13px; color: var(--text-muted); margin-top: 2px; }
        .user-chip {
            display: flex; align-items: center; gap: 12px;
            padding: 6px 16px 6px 6px;
            border-radius: 40px;
            cursor: pointer;
            transition: background 0.15s;
        }
        .user-chip:hover { background: #f3f4f6; }
        .avatar {
            width: 36px; height: 36px;
            background: var(--primary-light);
            color: var(--primary);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 14px; font-weight: 600;
        }
        .user-chip-name { font-size: 14px; font-weight: 600; color: var(--text-heading); line-height: 1.2; }
        .user-chip-role { font-size: 12px; color: var(--text-muted); }

        /* ===== CONTENT ===== */
        .content-area {
            flex: 1;
            overflow-y: auto;
            padding: 32px;
        }

        /* ===== CARDS ===== */
        .card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
            margin-bottom: 24px;
        }
        .card-header {
            background: transparent;
            border-bottom: 1px solid var(--border);
            padding: 16px 24px;
            font-size: 16px;
            font-weight: 600;
            color: var(--text-heading);
            display: flex; align-items: center; justify-content: space-between;
        }
        .card-body { padding: 24px; }

        /* ===== STAT CARDS ===== */
        .stat-card { 
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
            padding: 24px; 
            display: flex;
            flex-direction: column;
        }
        .stat-card .stat-icon, .stat-card .icon-wrap {
            width: 48px; height: 48px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; margin-bottom: 16px;
        }
        .stat-card .stat-value, .stat-card .value { font-size: 28px; font-weight: 700; color: var(--text-heading); letter-spacing: -0.02em; line-height: 1; margin-bottom: 8px; }
        .stat-card .stat-label, .stat-card .label { font-size: 14px; font-weight: 500; color: var(--text-muted); }
        .stat-card .sub { font-size: 13px; color: var(--text-muted); margin-top: 8px; }
        
        .icon-indigo { background: #e0e7ff; color: #4f46e5; }
        .icon-green  { background: #d1fae5; color: #10b981; }
        .icon-amber  { background: #fef3c7; color: #f59e0b; }
        .icon-red    { background: #fee2e2; color: #ef4444; }
        .icon-cyan   { background: #cffafe; color: #0ea5e9; }

        /* ===== TABLES ===== */
        .table { color: var(--text-body); font-size: 14px; width: 100%; margin-bottom: 0; }
        .table thead th {
            color: var(--text-muted); font-size: 12px; font-weight: 600;
            text-transform: uppercase; letter-spacing: 0.05em;
            padding: 12px 24px; border-bottom: 1px solid var(--border);
            background: #f9fafb;
        }
        .table tbody td {
            padding: 16px 24px; border-bottom: 1px solid var(--border);
            vertical-align: middle; color: var(--text-heading);
        }
        .table tbody tr:last-child td { border-bottom: none; }
        .table tbody tr:hover td { background: #f9fafb; }

        /* ===== BADGES ===== */
        .badge-pill {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 4px 10px; border-radius: 9999px;
            font-size: 12px; font-weight: 600;
        }
        .badge-pill .dot { width: 6px; height: 6px; border-radius: 50%; }
        .badge-active { background: #d1fae5; color: #065f46; }
        .badge-active .dot { background: #10b981; }
        .badge-pending { background: #fef3c7; color: #92400e; }
        .badge-pending .dot { background: #f59e0b; }
        .badge-occupied { background: #fee2e2; color: #991b1b; }
        .badge-occupied .dot { background: #ef4444; }
        .badge-cleaning { background: #cffafe; color: #164e63; }
        .badge-cleaning .dot { background: #0ea5e9; }

        /* ===== FORMS ===== */
        .form-label { font-size: 14px; font-weight: 500; color: var(--text-heading); margin-bottom: 6px; }
        .form-control, .form-select {
            border: 1px solid #d1d5db; border-radius: var(--radius-sm);
            font-size: 14px; color: var(--text-heading);
            padding: 10px 14px; transition: all 0.15s ease;
            background: white; box-shadow: var(--shadow-sm);
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary); outline: none;
            box-shadow: 0 0 0 3px rgba(79,70,229,0.1);
        }

        /* ===== BUTTONS ===== */
        .btn-primary {
            background: var(--primary);
            color: white; border: 1px solid transparent; border-radius: var(--radius-sm);
            font-size: 14px; font-weight: 500; padding: 8px 16px;
            box-shadow: var(--shadow-sm);
            transition: all 0.15s ease;
        }
        .btn-primary:hover { background: var(--primary-hover); color: white; }
        
        .btn-outline-primary {
            color: var(--text-heading); border: 1px solid #d1d5db;
            background: white; border-radius: var(--radius-sm);
            font-size: 14px; font-weight: 500; padding: 8px 16px;
            box-shadow: var(--shadow-sm);
            transition: all 0.15s ease;
        }
        .btn-outline-primary:hover { background: #f9fafb; color: var(--text-heading); }
        
        .btn-light { background: white; border: 1px solid #d1d5db; color: var(--text-heading); border-radius: var(--radius-sm); font-size: 14px; font-weight: 500; box-shadow: var(--shadow-sm); }
        .btn-light:hover { background: #f9fafb; }
        
        .btn-icon { width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: var(--radius-sm); border: none; cursor: pointer; transition: all 0.15s; font-size: 14px; }
        .btn-icon-primary { background: #f3f4f6; color: var(--text-muted); }
        .btn-icon-primary:hover { background: #e5e7eb; color: var(--text-heading); }

        /* ===== ROW IDENTITY ===== */
        .identity-cell { display: flex; align-items: center; gap: 12px; }
        .identity-avatar {
            width: 36px; height: 36px; border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 14px; font-weight: 600;
        }
        .identity-name { font-size: 14px; font-weight: 600; color: var(--text-heading); line-height: 1.2; }
        .identity-sub { font-size: 13px; color: var(--text-muted); margin-top: 2px; }

        /* ===== PAGE HEADER ===== */
        .page-header { margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; }
        .page-header h2 { font-size: 24px; font-weight: 700; color: var(--text-heading); letter-spacing: -0.02em; line-height: 1.2; }
        .page-header p { font-size: 14px; color: var(--text-muted); margin-top: 4px; }

        /* Animations */
        @keyframes fadeUp { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:translateY(0); } }
        .fade-in { animation: fadeUp 0.3s ease both; }
        .fade-in-2 { animation: fadeUp 0.3s 0.05s ease both; }
        .fade-in-3 { animation: fadeUp 0.3s 0.1s ease both; }
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
