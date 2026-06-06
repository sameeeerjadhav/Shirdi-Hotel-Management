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
            --primary:       #4f46e5;
            --primary-hover: #4338ca;
            --primary-light: #eef2ff;
            --success:  #10b981;
            --warning:  #f59e0b;
            --danger:   #ef4444;
            --info:     #3b82f6;
            --bg:       #f5f6fa;
            --card-bg:  #ffffff;
            --border:   #e8ecf0;
            --text-heading: #1a1d23;
            --text-body:    #4b5563;
            --text-muted:   #9ca3af;
            --sidebar-width: 240px;
            --topbar-h:      64px;
            --radius:    12px;
            --radius-sm:  8px;
            --shadow:    0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--bg);
            color: var(--text-body);
            display: flex;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            overflow: hidden;
        }

        /* ═══════════════════════════════
           SIDEBAR
        ═══════════════════════════════ */
        .sidebar {
            width: var(--sidebar-width);
            background: #fff;
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            height: 100vh;
            position: fixed;
            left: 0; top: 0;
            z-index: 100;
            overflow-y: auto;
            overflow-x: hidden;
        }

        /* Logo */
        .sidebar-logo {
            padding: 20px 20px 16px;
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 1px solid var(--border);
            margin-bottom: 8px;
        }
        .logo-icon {
            width: 34px; height: 34px;
            background: var(--primary);
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            color: white; font-size: 15px;
            flex-shrink: 0;
        }
        .logo-text {
            font-size: 17px; font-weight: 800;
            color: var(--text-heading);
            letter-spacing: -0.03em;
            line-height: 1.1;
        }
        .logo-text span {
            color: var(--primary);
            font-size: 10px;
            display: block;
            font-weight: 600;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            margin-top: 1px;
        }

        /* Section labels */
        .sidebar-section {
            padding: 20px 20px 6px;
            font-size: 10px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        /* Nav links — LEFT BORDER STYLE (like reference) */
        .nav-item { padding: 1px 0; }
        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 20px;
            border-left: 3px solid transparent;
            color: var(--text-body);
            font-size: 13.5px;
            font-weight: 500;
            transition: all 0.15s ease;
            text-decoration: none;
            border-radius: 0;
            margin: 0;
            white-space: nowrap;
        }
        .nav-link i {
            width: 18px;
            text-align: center;
            font-size: 14px;
            color: var(--text-muted);
            transition: color 0.15s;
            flex-shrink: 0;
        }
        .nav-link:hover {
            background: #f9fafb;
            color: var(--text-heading);
            border-left-color: #d1d5db;
        }
        .nav-link:hover i { color: var(--text-body); }

        /* ACTIVE STATE — exact reference style */
        .nav-link.active {
            background: var(--primary-light);
            color: var(--primary);
            font-weight: 600;
            border-left-color: var(--primary);
        }
        .nav-link.active i { color: var(--primary); }

        .nav-link.danger { color: #ef4444; }
        .nav-link.danger i { color: #fca5a5; }
        .nav-link.danger:hover { background: #fef2f2; border-left-color: #ef4444; }

        .sidebar-footer {
            margin-top: auto;
            padding: 12px 0;
            border-top: 1px solid var(--border);
        }

        /* ═══════════════════════════════
           MAIN WRAPPER
        ═══════════════════════════════ */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            flex: 1;
            display: flex;
            flex-direction: column;
            height: 100vh;
            overflow: hidden;
        }

        /* ═══════════════════════════════
           TOPBAR — clean, reference style
        ═══════════════════════════════ */
        .topbar {
            height: var(--topbar-h);
            background: #fff;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            flex-shrink: 0;
            position: sticky; top: 0; z-index: 50;
        }
        .topbar-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-heading);
            letter-spacing: -0.02em;
        }
        .topbar-subtitle {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 1px;
            font-weight: 400;
        }

        /* User chip */
        .user-chip {
            display: flex; align-items: center; gap: 10px;
            padding: 5px 12px 5px 5px;
            border-radius: 99px;
            border: 1px solid var(--border);
            cursor: pointer;
            transition: background 0.15s, border-color 0.15s;
            background: #fff;
        }
        .user-chip:hover { background: #f9fafb; border-color: #d1d5db; }
        .avatar {
            width: 32px; height: 32px;
            background: linear-gradient(135deg, var(--primary), #818cf8);
            color: white;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 13px; font-weight: 700;
            flex-shrink: 0;
        }
        .user-chip-name { font-size: 13px; font-weight: 600; color: var(--text-heading); line-height: 1.2; }
        .user-chip-role { font-size: 11px; color: var(--text-muted); font-weight: 400; }

        /* ═══════════════════════════════
           CONTENT AREA
        ═══════════════════════════════ */
        .content-area {
            flex: 1;
            overflow-y: auto;
            padding: 28px 32px;
        }

        /* ═══════════════════════════════
           CARDS
        ═══════════════════════════════ */
        .card {
            background: linear-gradient(160deg, #ffffff 0%, #f9f8ff 100%);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            margin-bottom: 20px;
        }
        .card-header {
            background: linear-gradient(135deg, #ffffff 0%, #f5f3ff 100%);
            border-bottom: 1px solid var(--border);
            padding: 16px 20px;
            font-size: 14px;
            font-weight: 700;
            color: var(--text-heading);
            display: flex; align-items: center; justify-content: space-between;
            border-radius: var(--radius) var(--radius) 0 0;
        }
        .card-body { padding: 20px; }

        /* ═══════════════════════════════
           STAT CARDS — reference style
           (left colored border + icon on right)
        ═══════════════════════════════ */
        .stat-card {
            background: linear-gradient(160deg, #ffffff 0%, #f5f4ff 100%);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 20px 22px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
            transition: box-shadow 0.2s, transform 0.2s;
        }
        .stat-card:hover {
            box-shadow: 0 6px 20px rgba(79,70,229,0.10);
            transform: translateY(-2px);
        }

        /* TOP colored strip */
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 3px;
            background: linear-gradient(90deg, #6366f1, #4338ca);
        }

        /* Per-column gradient strips */
        .col-md-3:nth-child(1) .stat-card::before,
        .col-md-4:nth-child(1) .stat-card::before,
        .col-6:nth-child(1) .stat-card::before { background: linear-gradient(90deg,#f59e0b,#f97316); }

        .col-md-3:nth-child(2) .stat-card::before,
        .col-md-4:nth-child(2) .stat-card::before,
        .col-6:nth-child(2) .stat-card::before { background: linear-gradient(90deg,#6366f1,#4338ca); }

        .col-md-3:nth-child(3) .stat-card::before,
        .col-md-4:nth-child(3) .stat-card::before,
        .col-6:nth-child(3) .stat-card::before { background: linear-gradient(90deg,#10b981,#059669); }

        .col-md-3:nth-child(4) .stat-card::before,
        .col-md-4:nth-child(4) .stat-card::before,
        .col-6:nth-child(4) .stat-card::before { background: linear-gradient(90deg,#3b82f6,#2563eb); }

        .stat-card-content { flex: 1; }
        .stat-card .stat-label, .stat-card .label {
            font-size: 11px; font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.07em;
            margin-bottom: 8px;
        }
        .stat-card .stat-value, .stat-card .value {
            font-size: 30px; font-weight: 800;
            color: var(--text-heading);
            letter-spacing: -0.03em;
            line-height: 1;
        }
        .stat-card .sub {
            font-size: 12px; color: var(--text-muted);
            margin-top: 5px; font-weight: 400;
        }

        /* Stat icon — right side, light tinted circle */
        .stat-card .stat-icon, .stat-card .icon-wrap {
            width: 44px; height: 44px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
            margin-left: 16px;
        }
        .icon-indigo { background: #eef2ff; color: #4f46e5; }
        .icon-green  { background: #ecfdf5; color: #10b981; }
        .icon-amber  { background: #fffbeb; color: #f59e0b; }
        .icon-red    { background: #fef2f2; color: #ef4444; }
        .icon-cyan   { background: #eff6ff; color: #3b82f6; }

        /* ═══════════════════════════════
           TABLES
        ═══════════════════════════════ */
        .table { color: var(--text-body); font-size: 13.5px; width: 100%; margin-bottom: 0; border-collapse: collapse; }
        .table thead th {
            color: var(--text-muted);
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            padding: 12px 20px;
            border-bottom: 1px solid var(--border);
            background: #fafafa;
            white-space: nowrap;
        }
        .table tbody td {
            padding: 13px 20px;
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
            color: var(--text-body);
            font-weight: 400;
        }
        .table tbody tr:last-child td { border-bottom: none; }
        .table tbody tr:hover td { background: #f9fafb; }

        /* ═══════════════════════════════
           BADGES
        ═══════════════════════════════ */
        .badge-pill {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 4px 10px;
            border-radius: 99px;
            font-size: 11.5px;
            font-weight: 600;
        }
        .badge-pill .dot { width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0; }
        .badge-active   { background: #ecfdf5; color: #059669; }
        .badge-active .dot   { background: #10b981; }
        .badge-pending  { background: #fef3c7; color: #d97706; }
        .badge-pending .dot  { background: #f59e0b; }
        .badge-occupied { background: #fee2e2; color: #dc2626; }
        .badge-occupied .dot { background: #ef4444; }
        .badge-cleaning { background: #eff6ff; color: #2563eb; }
        .badge-cleaning .dot { background: #3b82f6; }

        /* ═══════════════════════════════
           FORMS
        ═══════════════════════════════ */
        .form-label {
            font-size: 12.5px; font-weight: 600;
            color: var(--text-heading);
            margin-bottom: 6px;
            display: block;
        }
        .form-control, .form-select {
            width: 100%;
            border: 1px solid #d1d5db;
            border-radius: var(--radius-sm);
            font-size: 13.5px;
            color: var(--text-heading);
            padding: 9px 14px;
            transition: border-color 0.15s, box-shadow 0.15s;
            background: linear-gradient(160deg, #ffffff, #faf9ff);
            font-family: inherit;
        }
        .form-control::placeholder { color: #9ca3af; }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            outline: none;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12);
        }
        .form-text { font-size: 11.5px; color: var(--text-muted); margin-top: 4px; }

        /* ═══════════════════════════════
           BUTTONS
        ═══════════════════════════════ */
        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 22px;
            border-radius: 99px;                            /* pill shape */
            border: none;
            cursor: pointer;
            font-family: inherit;
            font-size: 13.5px;
            font-weight: 700;
            color: #fff;
            text-decoration: none;
            letter-spacing: 0.01em;
            white-space: nowrap;

            /* ✦ THE GRADIENT — matches the reference exactly */
            background: linear-gradient(135deg, #6366f1 0%, #4338ca 50%, #3730a3 100%);

            /* ✦ GLOW + depth */
            box-shadow:
                0 4px 15px rgba(67, 56, 202, 0.45),
                0 1px 3px rgba(67, 56, 202, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.15);

            transition: all 0.18s ease;
            position: relative;
            overflow: hidden;
        }

        /* Inner shine layer */
        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 50%;
            background: linear-gradient(to bottom, rgba(255,255,255,0.12), transparent);
            border-radius: 99px 99px 0 0;
            pointer-events: none;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #818cf8 0%, #4f46e5 50%, #3730a3 100%);
            box-shadow:
                0 6px 20px rgba(67, 56, 202, 0.55),
                0 2px 6px rgba(67, 56, 202, 0.35),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
            transform: translateY(-1px);
            color: #fff;
        }

        .btn-primary:active {
            transform: translateY(0);
            box-shadow:
                0 2px 8px rgba(67, 56, 202, 0.4),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
        }

        /* Badge inside primary button (like the "0" count in reference) */
        .btn-primary .btn-badge {
            background: rgba(255, 255, 255, 0.22);
            color: #fff;
            border-radius: 99px;
            font-size: 11px;
            font-weight: 800;
            padding: 2px 8px;
            min-width: 20px;
            text-align: center;
            line-height: 1.4;
            backdrop-filter: blur(4px);
        }
        .btn-light {
            background: linear-gradient(160deg, #ffffff 0%, #f5f4ff 100%);
            border: 1px solid #ddd9f5;
            color: var(--text-body);
            border-radius: 99px;
            font-size: 13.5px;
            font-weight: 500;
            padding: 9px 18px;
            cursor: pointer;
            transition: all 0.15s ease;
            display: inline-flex; align-items: center; gap: 6px;
            text-decoration: none;
            font-family: inherit;
            box-shadow: 0 1px 3px rgba(99,102,241,0.08), inset 0 1px 0 rgba(255,255,255,0.9);
        }
        .btn-light:hover {
            background: linear-gradient(160deg, #f0effe 0%, #ece9ff 100%);
            border-color: #c4bfee;
            color: var(--text-heading);
            box-shadow: 0 2px 8px rgba(99,102,241,0.14);
        }

        .btn-outline-primary {
            background: linear-gradient(160deg, #eef2ff 0%, #e8e4ff 100%);
            border: 1px solid #a5b4fc;
            color: var(--primary);
            border-radius: 99px;
            font-size: 13px; font-weight: 600;
            padding: 9px 18px; cursor: pointer;
            transition: all 0.15s ease;
            display: inline-flex; align-items: center; gap: 6px;
            text-decoration: none; font-family: inherit;
            box-shadow: 0 1px 3px rgba(99,102,241,0.12), inset 0 1px 0 rgba(255,255,255,0.8);
        }
        .btn-outline-primary:hover {
            background: linear-gradient(160deg, #e0e7ff 0%, #ddd6ff 100%);
            border-color: #818cf8;
            box-shadow: 0 3px 10px rgba(99,102,241,0.2);
        }

        /* ═══════════════════════════════
           IDENTITY CELL (table rows)
        ═══════════════════════════════ */
        .identity-cell { display: flex; align-items: center; gap: 12px; }
        .identity-avatar {
            width: 36px; height: 36px;
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            font-size: 13px; font-weight: 700;
            flex-shrink: 0;
        }
        .identity-name { font-size: 13.5px; font-weight: 600; color: var(--text-heading); line-height: 1.2; }
        .identity-sub  { font-size: 11.5px; color: var(--text-muted); margin-top: 2px; }

        /* ═══════════════════════════════
           PAGE HEADER
        ═══════════════════════════════ */
        .page-header {
            margin-bottom: 22px;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
        }
        .page-header h2 {
            font-size: 20px;
            font-weight: 800;
            color: var(--text-heading);
            letter-spacing: -0.02em;
            line-height: 1.2;
        }
        .page-header p {
            font-size: 13px;
            color: var(--text-muted);
            margin-top: 4px;
            font-weight: 400;
        }

        /* ═══════════════════════════════
           ANIMATIONS
        ═══════════════════════════════ */
        @keyframes fadeUp { from { opacity:0; transform:translateY(8px); } to { opacity:1; transform:translateY(0); } }
        .fade-in   { animation: fadeUp 0.25s ease both; }
        .fade-in-2 { animation: fadeUp 0.25s 0.04s ease both; }
        .fade-in-3 { animation: fadeUp 0.25s 0.08s ease both; }

        /* ═══════════════════════════════
           TOAST
        ═══════════════════════════════ */
        #toast-container { position:fixed; top:20px; right:20px; z-index:9999; display:flex; flex-direction:column; gap:8px; }
        .toast-item {
            display:flex; align-items:center; gap:12px;
            padding:12px 16px;
            border-radius:10px;
            background:white;
            box-shadow: 0 4px 20px rgba(0,0,0,0.10);
            border-left:4px solid;
            min-width:280px;
            animation:slideInRight 0.25s ease;
            font-size:13.5px; font-weight:500; color:var(--text-heading);
        }
        .toast-item.success { border-color:#10b981; }
        .toast-item.error   { border-color:#ef4444; }
        .toast-item.warning { border-color:#f59e0b; }
        .toast-item.info    { border-color:#3b82f6; }
        @keyframes slideInRight { from { opacity:0; transform:translateX(40px); } to { opacity:1; transform:translateX(0); } }
        @keyframes slideOut     { from { opacity:1; } to { opacity:0; transform:translateX(40px); } }

        /* ═══════════════════════════════
           NOTIFICATION BELL
        ═══════════════════════════════ */
        .notif-bell { position:relative; cursor:pointer; }
        .notif-badge { position:absolute; top:-4px; right:-4px; background:#ef4444; color:white; border-radius:99px; font-size:10px; font-weight:700; padding:1px 5px; min-width:16px; text-align:center; display:none; }
        .notif-badge.has-unread { display:block; }
        .notif-drawer { position:fixed; top:0; right:-380px; width:360px; height:100vh; background:white; box-shadow:-4px 0 24px rgba(0,0,0,0.08); border-left:1px solid var(--border); z-index:1000; transition:right 0.3s ease; overflow-y:auto; }
        .notif-drawer.open { right:0; }
        .notif-item { padding:14px 20px; border-bottom:1px solid var(--border); cursor:pointer; transition:background 0.15s; }
        .notif-item:hover { background:#f9fafb; }
        .notif-item.unread { background:#eef2ff; }

        /* ═══════════════════════════════
           SKELETON
        ═══════════════════════════════ */
        .skeleton { background: linear-gradient(90deg, #f1f5f9 25%, #e8ecf0 50%, #f1f5f9 75%); background-size: 200% 100%; animation: skeleton-shimmer 1.5s infinite; border-radius: 6px; }
        @keyframes skeleton-shimmer { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }

        /* ═══════════════════════════════
           USER DROPDOWN
        ═══════════════════════════════ */
        .user-dropdown { position: relative; }
        .user-chip { cursor: pointer; transition: background 0.15s; border-radius: 99px; }
        .user-chip:hover { background: #f9fafb; }
        .user-dropdown-menu {
            display: none;
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            width: 240px;
            background: white;
            border-radius: 14px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.10), 0 0 0 1px var(--border);
            z-index: 2000;
            overflow: hidden;
            animation: dropDown 0.15s ease;
        }
        .user-dropdown-menu.open { display: block; }
        @keyframes dropDown {
            from { opacity: 0; transform: translateY(-6px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .dropdown-item {
            display: flex; align-items: center; gap: 12px;
            padding: 10px 16px;
            text-decoration: none; color: var(--text-body);
            font-size: 13px; transition: background 0.12s; cursor: pointer;
        }
        .dropdown-item:hover { background: #f9fafb; }
        .dropdown-item i {
            width: 30px; height: 30px;
            background: var(--primary-light);
            border-radius: 7px;
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; color: var(--primary);
            flex-shrink: 0;
        }
        .dropdown-item-title { font-size: 13px; font-weight: 600; color: var(--text-heading); }
        .dropdown-item-sub   { font-size: 11px; color: var(--text-muted); margin-top: 1px; }

        /* ═══════════════════════════════
           ALERTS
        ═══════════════════════════════ */
        .alert { padding: 12px 16px; border-radius: var(--radius-sm); font-size: 13.5px; font-weight: 500; margin-bottom: 16px; }
        .alert-danger  { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
        .alert-success { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
    </style>
</head>

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
            <button onclick="toggleNotifDrawer()" style="background:#f1f5f9;border:none;width:28px;height:28px;border-radius:6px;display:flex;align-items:center;justify-content:center;color:#64748b;cursor:pointer;font-size:12px;"><i class="fa-solid fa-xmark"></i></button>
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
    const faIcons = {
        success: '<i class="fa-solid fa-circle-check" style="color:#10b981;font-size:15px;"></i>',
        error:   '<i class="fa-solid fa-circle-xmark" style="color:#ef4444;font-size:15px;"></i>',
        warning: '<i class="fa-solid fa-triangle-exclamation" style="color:#f59e0b;font-size:15px;"></i>',
        info:    '<i class="fa-solid fa-circle-info" style="color:#3b82f6;font-size:15px;"></i>',
    };
    const t = document.createElement('div');
    t.className = 'toast-item ' + type;
    t.innerHTML = `${faIcons[type]||faIcons.info} ${msg}`;
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
        list.innerHTML = '<div style="padding:40px;text-align:center;color:#94a3b8;font-size:13px;"><i class="fa-regular fa-bell" style="font-size:28px;display:block;margin-bottom:10px;"></i>No notifications yet</div>';
        return;
    }
    const faIconMap = {
        booking_created:  '<i class="fa-solid fa-calendar-check" style="color:#4338ca;"></i>',
        payment_received: '<i class="fa-solid fa-indian-rupee-sign" style="color:#10b981;"></i>',
        hotel_approved:   '<i class="fa-solid fa-building-circle-check" style="color:#10b981;"></i>',
        transfer_request: '<i class="fa-solid fa-right-left" style="color:#f59e0b;"></i>',
        checkin:          '<i class="fa-solid fa-door-open" style="color:#3b82f6;"></i>',
        checkout:         '<i class="fa-solid fa-door-closed" style="color:#64748b;"></i>',
    };
    list.innerHTML = data.notifications.map(n => `
        <div class="notif-item ${n.is_read == 0 ? 'unread' : ''}" onclick="readNotif(${n.id},'${n.link || ''}')">
            <div style="display:flex;gap:12px;align-items:flex-start;">
                <div style="width:34px;height:34px;background:#eef2ff;border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:14px;flex-shrink:0;">${faIconMap[n.type]||'<i class="fa-solid fa-bell" style="color:#4338ca;"></i>'}</div>
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
