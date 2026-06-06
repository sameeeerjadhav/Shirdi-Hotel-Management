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
            --primary: #4338ca; /* Indigo/Purple from sidebar active state */
            --primary-hover: #3730a3;
            --primary-light: #f3f4f6; /* Faint grey/purple for active sidebar items */
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #3b82f6; /* Bright blue */
            --bg: #f8f9fc; /* Very clean faint blue/grey */
            --sidebar-bg: #ffffff;
            --card-bg: #ffffff;
            --text-heading: #1e293b;
            --text-body: #475569;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --sidebar-width: 260px;
            --shadow-sm: 0 2px 4px rgba(0,0,0,0.02);
            --shadow-md: 0 4px 12px rgba(0,0,0,0.04);
            --shadow-lg: 0 10px 25px rgba(0,0,0,0.05);
            --radius: 16px; /* High border radius */
            --radius-sm: 10px;
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
            padding: 24px 20px 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 12px;
        }
        .logo-icon {
            width: 32px; height: 32px;
            background: var(--primary);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            color: white; font-size: 16px;
        }
        .logo-text { font-size: 20px; font-weight: 800; color: var(--primary); letter-spacing: -0.02em; text-align: center;}
        .logo-text span { color: var(--danger); font-size: 14px; display: block; font-weight: 600; letter-spacing: 0; margin-top: 2px;}

        .sidebar-section {
            padding: 16px 28px 8px;
            font-size: 11px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .nav-item { padding: 4px 16px; }
        .nav-link {
            display: flex; align-items: center; gap: 14px;
            padding: 10px 16px;
            border-radius: 9999px; /* Pill shape */
            color: #64748b;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
            text-decoration: none;
        }
        .nav-link i { width: 20px; text-align: center; font-size: 16px; color: #94a3b8; transition: color 0.2s; }
        .nav-link:hover { background: #f8fafc; color: var(--text-heading); }
        .nav-link:hover i { color: var(--text-body); }
        .nav-link.active { background: #f5f3ff; color: var(--primary); font-weight: 600; }
        .nav-link.active i { color: var(--primary); }
        .nav-link.danger { color: var(--danger); }
        .nav-link.danger i { color: #fca5a5; }
        .nav-link.danger:hover { background: #fef2f2; color: var(--danger); }

        .sidebar-footer {
            margin-top: auto;
            padding: 16px;
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
            height: 76px;
            background: white;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 36px;
            flex-shrink: 0;
            position: sticky; top: 0; z-index: 50;
        }
        .topbar-title { font-size: 20px; font-weight: 700; color: var(--text-heading); letter-spacing: -0.01em; }
        .topbar-subtitle { font-size: 13px; color: var(--text-muted); margin-top: 2px; font-weight: 500;}
        .user-chip {
            display: flex; align-items: center; gap: 12px;
            padding: 6px 16px 6px 6px;
            border-radius: 9999px;
            border: 1px solid var(--border);
            cursor: pointer;
            transition: background 0.15s;
        }
        .user-chip:hover { background: #f8fafc; }
        .avatar {
            width: 34px; height: 34px;
            background: #d8b4e2; /* Purple avatar background */
            color: white;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 14px; font-weight: 600;
        }
        .user-chip-name { font-size: 14px; font-weight: 600; color: var(--text-heading); line-height: 1.2; }
        .user-chip-role { font-size: 11px; color: var(--text-muted); }

        /* ===== CONTENT ===== */
        .content-area {
            flex: 1;
            overflow-y: auto;
            padding: 32px 36px;
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
            padding: 20px 24px;
            font-size: 16px;
            font-weight: 700;
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
            align-items: center;
            gap: 20px;
            position: relative;
            overflow: hidden;
        }
        /* Dynamic top border for stat cards */
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 4px;
            background: var(--info);
        }
        .col-md-4:nth-child(1) .stat-card::before { background: #f59e0b; } /* Orange */
        .col-md-4:nth-child(2) .stat-card::before { background: #3b82f6; } /* Blue */
        .col-md-4:nth-child(3) .stat-card::before { background: #8b5cf6; } /* Purple */
        .col-md-4:nth-child(4) .stat-card::before { background: #10b981; } /* Green */
        .col-md-3:nth-child(1) .stat-card::before { background: #f59e0b; }
        .col-md-3:nth-child(2) .stat-card::before { background: #3b82f6; }
        .col-md-3:nth-child(3) .stat-card::before { background: #8b5cf6; }
        .col-md-3:nth-child(4) .stat-card::before { background: #10b981; }

        .stat-card .stat-icon, .stat-card .icon-wrap {
            width: 56px; height: 56px;
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 24px; flex-shrink: 0;
        }
        .stat-card-content { flex: 1; }
        .stat-card .stat-label, .stat-card .label { font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px; }
        .stat-card .stat-value, .stat-card .value { font-size: 28px; font-weight: 800; color: var(--text-heading); letter-spacing: -0.02em; line-height: 1; }
        .stat-card .sub { font-size: 12px; color: var(--text-muted); margin-top: 6px; }
        
        .icon-indigo { background: #eff6ff; color: #3b82f6; }
        .icon-green  { background: #ecfdf5; color: #10b981; }
        .icon-amber  { background: #fffbeb; color: #f59e0b; }
        .icon-red    { background: #fef2f2; color: #ef4444; }
        .icon-cyan   { background: #f5f3ff; color: #8b5cf6; }

        /* ===== TABLES ===== */
        .table { color: var(--text-body); font-size: 14px; width: 100%; margin-bottom: 0; }
        .table thead th {
            color: #94a3b8; font-size: 11px; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.05em;
            padding: 16px 24px; border-bottom: 1px solid var(--border);
            background: white;
        }
        .table tbody td {
            padding: 16px 24px; border-bottom: 1px solid var(--border);
            vertical-align: middle; color: var(--text-heading); font-weight: 500;
        }
        .table tbody tr:last-child td { border-bottom: none; }
        .table tbody tr:hover td { background: #f8fafc; }

        /* ===== BADGES ===== */
        .badge-pill {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 6px 12px; border-radius: 9999px;
            font-size: 12px; font-weight: 600;
        }
        .badge-pill .dot { width: 6px; height: 6px; border-radius: 50%; }
        .badge-active { background: #ecfdf5; color: #059669; }
        .badge-active .dot { background: #10b981; }
        .badge-pending { background: #fffbeb; color: #d97706; }
        .badge-pending .dot { background: #f59e0b; }
        .badge-occupied { background: #fef2f2; color: #dc2626; }
        .badge-occupied .dot { background: #ef4444; }
        .badge-cleaning { background: #eff6ff; color: #2563eb; }
        .badge-cleaning .dot { background: #3b82f6; }

        /* ===== FORMS ===== */
        .form-section {
            background: #f8fafc; border: 1px solid var(--border); border-radius: var(--radius);
            padding: 24px; margin-bottom: 24px;
        }
        .form-label { font-size: 13px; font-weight: 600; color: var(--text-heading); margin-bottom: 8px; }
        .form-control, .form-select {
            border: 1px solid #cbd5e1; border-radius: var(--radius-sm);
            font-size: 14px; color: var(--text-heading);
            padding: 12px 16px; transition: all 0.2s ease;
            background: white;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary); outline: none;
            box-shadow: 0 0 0 3px rgba(67, 56, 202, 0.1);
        }

        /* ===== BUTTONS ===== */
        .btn-primary {
            background: var(--primary);
            color: white; border: 1px solid transparent; border-radius: var(--radius-sm);
            font-size: 14px; font-weight: 600; padding: 10px 20px;
            box-shadow: 0 2px 4px rgba(67, 56, 202, 0.2);
            transition: all 0.2s ease;
        }
        .btn-primary:hover { background: var(--primary-hover); color: white; transform: translateY(-1px); box-shadow: 0 4px 6px rgba(67, 56, 202, 0.25); }
        
        .btn-outline-primary {
            color: var(--primary); border: 1px solid var(--border);
            background: white; border-radius: var(--radius-sm);
            font-size: 14px; font-weight: 600; padding: 10px 20px;
            transition: all 0.2s ease;
        }
        .btn-outline-primary:hover { background: #f8fafc; color: var(--primary-hover); }
        
        .btn-light { background: white; border: 1px solid var(--border); color: var(--text-body); border-radius: var(--radius-sm); font-size: 14px; font-weight: 600; }
        .btn-light:hover { background: #f8fafc; }
        
        .btn-icon { width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center; border-radius: var(--radius-sm); border: none; cursor: pointer; transition: all 0.2s; font-size: 15px; }
        .btn-icon-primary { background: #f1f5f9; color: var(--text-muted); }
        .btn-icon-primary:hover { background: #e2e8f0; color: var(--text-heading); }

        /* ===== ROW IDENTITY ===== */
        .identity-cell { display: flex; align-items: center; gap: 14px; }
        .identity-avatar {
            width: 40px; height: 40px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 14px; font-weight: 700; background: #f1f5f9; color: var(--primary);
        }
        .identity-name { font-size: 14px; font-weight: 600; color: var(--text-heading); line-height: 1.2; }
        .identity-sub { font-size: 12px; color: var(--text-muted); margin-top: 4px; }

        /* ===== PAGE HEADER ===== */
        .page-header { margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; }
        .page-header h2 { font-size: 24px; font-weight: 800; color: var(--text-heading); letter-spacing: -0.02em; line-height: 1.2; }
        .page-header p { font-size: 14px; color: var(--text-muted); margin-top: 6px; font-weight: 500;}

        /* Animations */
        @keyframes fadeUp { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:translateY(0); } }
        .fade-in { animation: fadeUp 0.3s ease both; }
        .fade-in-2 { animation: fadeUp 0.3s 0.05s ease both; }
        .fade-in-3 { animation: fadeUp 0.3s 0.1s ease both; }

        /* Toast Notifications */
        #toast-container { position:fixed; top:20px; right:20px; z-index:9999; display:flex; flex-direction:column; gap:10px; }
        .toast-item { display:flex; align-items:center; gap:12px; padding:14px 18px; border-radius:12px; background:white; box-shadow:0 8px 32px rgba(0,0,0,0.12); border-left:4px solid; min-width:300px; animation:slideInRight 0.3s ease; font-size:14px; font-weight:500; color:#1e293b; }
        .toast-item.success { border-color:#10b981; }
        .toast-item.error   { border-color:#ef4444; }
        .toast-item.warning { border-color:#f59e0b; }
        .toast-item.info    { border-color:#3b82f6; }
        @keyframes slideInRight { from { opacity:0; transform:translateX(60px); } to { opacity:1; transform:translateX(0); } }
        @keyframes slideOut { from { opacity:1; } to { opacity:0; transform:translateX(60px); } }

        /* Notification Bell */
        .notif-bell { position:relative; cursor:pointer; }
        .notif-badge { position:absolute; top:-4px; right:-4px; background:#ef4444; color:white; border-radius:99px; font-size:10px; font-weight:700; padding:1px 5px; min-width:16px; text-align:center; display:none; }
        .notif-badge.has-unread { display:block; }
        .notif-drawer { position:fixed; top:0; right:-380px; width:360px; height:100vh; background:white; box-shadow:-8px 0 40px rgba(0,0,0,0.1); z-index:1000; transition:right 0.3s ease; overflow-y:auto; }
        .notif-drawer.open { right:0; }
        .notif-item { padding:14px 20px; border-bottom:1px solid #f1f5f9; cursor:pointer; transition:background 0.15s; }
        .notif-item:hover { background:#f8fafc; }
        .notif-item.unread { background:#eff6ff; }

        /* Skeleton */
        .skeleton { background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%); background-size: 200% 100%; animation: skeleton-shimmer 1.5s infinite; border-radius: 6px; }
        @keyframes skeleton-shimmer { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }

        /* ===== USER DROPDOWN ===== */
        .user-dropdown { position: relative; }
        .user-chip { cursor: pointer; transition: background 0.15s; border-radius: 12px; padding: 6px 10px; }
        .user-chip:hover { background: #f1f5f9; }
        .user-dropdown-menu {
            display: none;
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            width: 260px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 8px 40px rgba(0,0,0,0.12), 0 0 0 1px rgba(0,0,0,0.04);
            z-index: 2000;
            overflow: hidden;
            animation: dropDown 0.18s ease;
        }
        .user-dropdown-menu.open { display: block; }
        @keyframes dropDown {
            from { opacity: 0; transform: translateY(-8px) scale(0.97); }
            to   { opacity: 1; transform: translateY(0)  scale(1); }
        }
        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 16px;
            text-decoration: none;
            color: #374151;
            font-size: 13px;
            transition: background 0.13s;
            cursor: pointer;
        }
        .dropdown-item:hover { background: #f8fafc; }
        .dropdown-item i {
            width: 32px; height: 32px;
            background: #f1f5f9;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 13px; color: #4338ca;
            flex-shrink: 0;
        }
        .dropdown-item-title { font-size: 13px; font-weight: 700; color: #1e293b; }
        .dropdown-item-sub   { font-size: 11px; color: #94a3b8; margin-top: 1px; }
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

    <?php
    $curPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $isActive = function($p) use ($curPath) { return (strpos($curPath, $p) === 0) ? 'active' : ''; };
    ?>
    <?php if ($_SESSION['role_id'] == 1): // Super Admin ?>
    <div class="sidebar-section">Main</div>
    <ul class="nav flex-column">
        <li class="nav-item"><a class="nav-link <?= $isActive('/admin/dashboard') ?>" href="<?= BASE_URL ?>/admin/dashboard"><i class="fa-solid fa-gauge-high"></i> Dashboard</a></li>
    </ul>
    <div class="sidebar-section">Operations</div>
    <ul class="nav flex-column">
        <li class="nav-item"><a class="nav-link <?= $isActive('/admin/hotels') ?>" href="<?= BASE_URL ?>/admin/hotels"><i class="fa-solid fa-building"></i> Hotels</a></li>
        <li class="nav-item"><a class="nav-link <?= $isActive('/admin/bookings') ?>" href="<?= BASE_URL ?>/admin/bookings"><i class="fa-solid fa-calendar-check"></i> Bookings</a></li>
        <li class="nav-item"><a class="nav-link <?= $isActive('/admin/room-monitor') ?>" href="<?= BASE_URL ?>/admin/room-monitor"><i class="fa-solid fa-hotel"></i> Room Monitor</a></li>
        <li class="nav-item"><a class="nav-link <?= $isActive('/admin/transfers') ?>" href="<?= BASE_URL ?>/admin/transfers"><i class="fa-solid fa-right-left"></i> Transfers <?php if(isset($pendingTransfers) && $pendingTransfers > 0): ?><span style="background:#ef4444;color:white;border-radius:99px;font-size:10px;padding:1px 6px;margin-left:4px;font-weight:700;"><?= $pendingTransfers ?></span><?php endif; ?></a></li>
    </ul>
    <div class="sidebar-section">Finance</div>
    <ul class="nav flex-column">
        <li class="nav-item"><a class="nav-link <?= $isActive('/admin/finance') ?>" href="<?= BASE_URL ?>/admin/finance"><i class="fa-solid fa-chart-line"></i> Finance</a></li>
        <li class="nav-item"><a class="nav-link <?= $isActive('/admin/search') ?>" href="<?= BASE_URL ?>/admin/search"><i class="fa-solid fa-magnifying-glass"></i> Room Search</a></li>
    </ul>
    <div class="sidebar-section">Management</div>
    <ul class="nav flex-column">
        <li class="nav-item"><a class="nav-link <?= $isActive('/admin/users') ?>" href="<?= BASE_URL ?>/admin/users"><i class="fa-solid fa-users"></i> Users</a></li>
    </ul>
    <?php elseif ($_SESSION['role_id'] == 2): // Hotel Admin ?>
    <div class="sidebar-section">Main</div>
    <ul class="nav flex-column">
        <li class="nav-item"><a class="nav-link <?= $isActive('/hotel/dashboard') ?>" href="<?= BASE_URL ?>/hotel/dashboard"><i class="fa-solid fa-gauge-high"></i> Dashboard</a></li>
    </ul>
    <div class="sidebar-section">Operations</div>
    <ul class="nav flex-column">
        <li class="nav-item"><a class="nav-link <?= $isActive('/hotel/rooms') ?>" href="<?= BASE_URL ?>/hotel/rooms"><i class="fa-solid fa-bed"></i> Rooms</a></li>
        <li class="nav-item"><a class="nav-link <?= $isActive('/hotel/bookings') ?>" href="<?= BASE_URL ?>/hotel/bookings"><i class="fa-solid fa-calendar-check"></i> Bookings</a></li>
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
        <div style="display:flex;align-items:center;gap:12px;">
            <!-- Notification Bell -->
            <div class="notif-bell" onclick="toggleNotifDrawer()" title="Notifications">
                <div style="width:38px;height:38px;background:#f1f5f9;border-radius:10px;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:background 0.15s;" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">
                    <i class="fa-regular fa-bell" style="font-size:16px;color:#64748b;"></i>
                </div>
                <span class="notif-badge <?= isset($unreadCount) && $unreadCount > 0 ? 'has-unread' : '' ?>" id="notifBadge"><?= $unreadCount ?? 0 ?></span>
            </div>

            <!-- USER DROPDOWN -->
            <div class="user-dropdown" id="userDropdown">
                <div class="user-chip" onclick="toggleUserMenu()" id="userChipBtn">
                    <div class="avatar"><?= strtoupper(substr($_SESSION['name'], 0, 1)) ?></div>
                    <div>
                        <div class="user-chip-name"><?= htmlspecialchars($_SESSION['name']) ?></div>
                        <div class="user-chip-role"><?= $_SESSION['role_id'] == 1 ? 'Super Admin' : ($_SESSION['hotel_name'] ?? 'Hotel Admin') ?></div>
                    </div>
                    <i class="fa-solid fa-chevron-down" id="dropChevron" style="font-size:11px;color:#94a3b8;transition:transform 0.2s;"></i>
                </div>

                <!-- DROPDOWN MENU -->
                <div class="user-dropdown-menu" id="userDropdownMenu">
                    <!-- User Info Header -->
                    <div style="padding:16px 20px;border-bottom:1px solid #f1f5f9;">
                        <div style="display:flex;align-items:center;gap:12px;">
                            <div style="width:42px;height:42px;border-radius:50%;background:linear-gradient(135deg,#4338ca,#8b5cf6);display:flex;align-items:center;justify-content:center;font-size:18px;font-weight:800;color:white;flex-shrink:0;">
                                <?= strtoupper(substr($_SESSION['name'], 0, 1)) ?>
                            </div>
                            <div>
                                <div style="font-size:14px;font-weight:800;color:#1e293b;"><?= htmlspecialchars($_SESSION['name']) ?></div>
                                <div style="font-size:12px;color:#94a3b8;margin-top:1px;"><?= $_SESSION['role_id'] == 1 ? 'Super Admin' : ($_SESSION['hotel_name'] ?? 'Hotel Admin') ?></div>
                            </div>
                        </div>
                    </div>

                    <!-- Menu Items -->
                    <div style="padding:8px 0;">
                        <a href="<?= BASE_URL ?>/profile" class="dropdown-item">
                            <i class="fa-solid fa-user"></i>
                            <div>
                                <div class="dropdown-item-title">My Profile</div>
                                <div class="dropdown-item-sub">Account settings &amp; password</div>
                            </div>
                        </a>
                    </div>

                    <!-- Sign Out -->
                    <div style="padding:8px 0;border-top:1px solid #f1f5f9;">
                        <a href="<?= BASE_URL ?>/logout" class="dropdown-item">
                            <i class="fa-solid fa-arrow-right-from-bracket" style="color:#ef4444;"></i>
                            <div>
                                <div class="dropdown-item-title" style="color:#ef4444;">Sign Out</div>
                                <div class="dropdown-item-sub">End your session</div>
                            </div>
                        </a>
                    </div>
                </div>
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

<!-- Notification Drawer -->
<div class="notif-drawer" id="notifDrawer">
    <div style="padding:20px;border-bottom:1px solid #e2e8f0;display:flex;justify-content:space-between;align-items:center;">
        <div style="font-size:16px;font-weight:800;color:#1e293b;">Notifications</div>
        <div style="display:flex;gap:8px;">
            <button onclick="markAllRead()" style="font-size:12px;font-weight:600;color:#4338ca;background:none;border:none;cursor:pointer;">Mark all read</button>
            <button onclick="toggleNotifDrawer()" style="background:none;border:none;font-size:18px;color:#64748b;cursor:pointer;">✕</button>
        </div>
    </div>
    <div id="notifList" style="padding:0;"><div style="padding:32px;text-align:center;color:#94a3b8;">Loading...</div></div>
</div>
<div id="notifOverlay" onclick="toggleNotifDrawer()" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.2);z-index:999;"></div>

<!-- Toast Container -->
<div id="toast-container"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
const BASE_URL = '<?= BASE_URL ?>';

// ---- Toast System ----
function showToast(msg, type = 'info') {
    const icons = {success:'✓', error:'✕', warning:'⚠', info:'ℹ'};
    const t = document.createElement('div');
    t.className = 'toast-item ' + type;
    t.innerHTML = `<span style="font-size:16px;">${icons[type]||'ℹ'}</span> ${msg}`;
    document.getElementById('toast-container').appendChild(t);
    setTimeout(() => { t.style.animation='slideOut 0.3s ease forwards'; setTimeout(()=>t.remove(),300); }, 3500);
}

// ---- User Dropdown ----
function toggleUserMenu(e) {
    if (e) e.stopPropagation();
    const menu    = document.getElementById('userDropdownMenu');
    const chevron = document.getElementById('dropChevron');
    const isOpen  = menu.classList.contains('open');
    menu.classList.toggle('open', !isOpen);
    if (chevron) chevron.style.transform = isOpen ? '' : 'rotate(180deg)';
}

// Close dropdown when clicking anywhere outside
document.addEventListener('click', function(e) {
    const dropdown = document.getElementById('userDropdown');
    const menu     = document.getElementById('userDropdownMenu');
    const chevron  = document.getElementById('dropChevron');
    if (dropdown && !dropdown.contains(e.target)) {
        menu && menu.classList.remove('open');
        if (chevron) chevron.style.transform = '';
    }
});

// ---- Notifications ----
let notifOpen = false;
async function loadNotifications() {
    const res  = await fetch(BASE_URL + '/api/notifications');
    const data = await res.json();
    const badge = document.getElementById('notifBadge');
    if (badge) {
        badge.textContent = data.unread;
        badge.classList.toggle('has-unread', data.unread > 0);
    }
    const list = document.getElementById('notifList');
    if (!list) return;
    if (!data.notifications || data.notifications.length === 0) {
        list.innerHTML = '<div style="padding:40px;text-align:center;color:#94a3b8;font-size:14px;">🔔 No notifications yet</div>';
        return;
    }
    const icons = {booking_created:'📋',payment_received:'💰',hotel_approved:'✅',transfer_request:'↔️',checkin:'🔑',checkout:'🚪'};
    list.innerHTML = data.notifications.map(n => `
        <div class="notif-item ${n.is_read == 0 ? 'unread' : ''}" onclick="readNotif(${n.id},'${n.link || ''}')">
            <div style="display:flex;gap:12px;align-items:flex-start;">
                <span style="font-size:20px;flex-shrink:0;">${icons[n.type]||'🔔'}</span>
                <div>
                    <div style="font-size:13px;font-weight:700;color:#1e293b;margin-bottom:2px;">${n.title}</div>
                    <div style="font-size:12px;color:#64748b;">${n.message||''}</div>
                    <div style="font-size:11px;color:#94a3b8;margin-top:4px;">${new Date(n.created_at).toLocaleString('en-IN')}</div>
                </div>
            </div>
        </div>`).join('');
}

async function readNotif(id, link) {
    await fetch(`${BASE_URL}/api/notifications/${id}/read`, {method:'POST'});
    if (link) window.location.href = BASE_URL + link;
    else loadNotifications();
}

async function markAllRead() {
    await fetch(`${BASE_URL}/api/notifications/read-all`, {method:'POST'});
    loadNotifications();
}

function toggleNotifDrawer() {
    notifOpen = !notifOpen;
    document.getElementById('notifDrawer').classList.toggle('open', notifOpen);
    document.getElementById('notifOverlay').style.display = notifOpen ? 'block' : 'none';
    if (notifOpen) loadNotifications();
}

// Poll notifications every 30 seconds
if (document.getElementById('notifBadge')) {
    setInterval(loadNotifications, 30000);
}

// ---- Show PHP flash messages as toast ----
<?php if(isset($_SESSION['success'])): ?>
showToast(<?= json_encode($_SESSION['success']) ?>, 'success');
<?php unset($_SESSION['success']); endif; ?>
<?php if(isset($_SESSION['error'])): ?>
showToast(<?= json_encode($_SESSION['error']) ?>, 'error');
<?php unset($_SESSION['error']); endif; ?>
</script>
</body>
</html>
