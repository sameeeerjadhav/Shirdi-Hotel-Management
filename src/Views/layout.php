<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'CHNMS') ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts: Inter (similar to the screenshot) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom Light UI CSS -->
    <style>
        :root {
            --bg-color: #f4f7fe;
            --sidebar-bg: #ffffff;
            --sidebar-text: #a0aec0;
            --sidebar-active-bg: #e9ecef;
            --sidebar-active-text: #4318ff;
            --text-main: #2b3674;
            --text-muted: #a3aed1;
            --card-bg: #ffffff;
            --border-color: #e2e8f0;
            --primary-color: #4318ff;
        }
        body {
            background-color: var(--bg-color);
            color: var(--text-main);
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            height: 100vh;
            overflow: hidden;
        }
        
        /* Sidebar Styles */
        .sidebar {
            width: 260px;
            background: var(--sidebar-bg);
            padding: 20px 15px;
            height: 100vh;
            border-right: 1px solid var(--border-color);
            transition: 0.3s;
            overflow-y: auto;
        }
        .sidebar-brand {
            color: var(--text-main);
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 40px;
            padding-left: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .sidebar-brand i {
            color: var(--primary-color);
        }
        .sidebar-heading {
            font-size: 12px;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
            padding-left: 15px;
            margin-top: 25px;
        }
        .sidebar .nav-link {
            color: var(--sidebar-text);
            padding: 12px 15px;
            margin-bottom: 5px;
            border-radius: 10px;
            font-weight: 500;
            font-size: 15px;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .sidebar .nav-link i {
            font-size: 18px;
            width: 20px;
            text-align: center;
        }
        .sidebar .nav-link:hover {
            color: var(--text-main);
            background: rgba(0,0,0,0.02);
        }
        .sidebar .nav-link.active {
            color: var(--primary-color);
            background: rgba(67, 24, 255, 0.08);
            font-weight: 600;
        }
        
        /* Main Content */
        .main-content {
            flex-grow: 1;
            padding: 0 30px 30px 30px;
            overflow-y: auto;
        }
        
        /* Navbar */
        .navbar {
            background: transparent !important;
            padding: 25px 0;
            margin-bottom: 10px;
        }
        .navbar-brand {
            color: var(--text-main) !important;
            font-size: 24px;
            font-weight: 700;
        }
        .breadcrumb-text {
            color: var(--text-muted);
            font-size: 14px;
            font-weight: 500;
        }
        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            background: white;
            padding: 5px 15px 5px 5px;
            border-radius: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        }
        .user-avatar {
            width: 35px;
            height: 35px;
            background: var(--primary-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 14px;
        }
        .user-name {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-main);
        }

        /* Cards */
        .card {
            background: var(--card-bg);
            border: none;
            border-radius: 16px;
            box-shadow: 0 5px 14px rgba(0,0,0,0.02);
            margin-bottom: 25px;
        }
        .card-header {
            background: transparent;
            border-bottom: 1px solid var(--border-color);
            padding: 20px 25px;
            font-weight: 700;
            font-size: 18px;
            color: var(--text-main);
        }
        .card-body {
            padding: 25px;
        }
        
        /* Dashboard Stat Cards */
        .stat-card {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .stat-icon {
            width: 55px;
            height: 55px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }
        .stat-icon.blue { background: rgba(67, 24, 255, 0.1); color: var(--primary-color); }
        .stat-icon.green { background: rgba(5, 205, 153, 0.1); color: #05cd99; }
        .stat-icon.orange { background: rgba(255, 153, 32, 0.1); color: #ff9920; }
        .stat-icon.red { background: rgba(238, 93, 80, 0.1); color: #ee5d50; }
        
        .stat-details h3 {
            font-size: 28px;
            font-weight: 700;
            margin: 0;
            color: var(--text-main);
        }
        .stat-details p {
            font-size: 13px;
            color: var(--text-muted);
            margin: 0;
            font-weight: 500;
            text-transform: uppercase;
        }
        .stat-details .sub-text {
            font-size: 12px;
            color: #a3aed1;
            margin-top: 4px;
            text-transform: none;
        }

        /* Tables */
        .table {
            color: var(--text-main);
            margin-bottom: 0;
        }
        .table thead th {
            border-bottom: 1px solid var(--border-color);
            color: var(--text-muted);
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            padding: 15px;
            background: transparent;
        }
        .table tbody td {
            border-bottom: 1px solid var(--border-color);
            padding: 15px;
            vertical-align: middle;
            font-size: 14px;
            font-weight: 500;
        }
        .table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Badges */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }
        .status-badge.active { background: rgba(5, 205, 153, 0.1); color: #05cd99; }
        .status-badge.inactive { background: rgba(238, 93, 80, 0.1); color: #ee5d50; }
        .status-badge .dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
        }
        .status-badge.active .dot { background: #05cd99; }
        .status-badge.inactive .dot { background: #ee5d50; }

        /* Forms */
        .form-control, .form-select {
            background: #ffffff;
            border: 1px solid var(--border-color);
            color: var(--text-main);
            border-radius: 10px;
            padding: 10px 15px;
        }
        .form-label {
            font-weight: 600;
            font-size: 14px;
            color: var(--text-main);
        }
        
        /* Buttons */
        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            border-radius: 10px;
            font-weight: 500;
            padding: 10px 20px;
        }
        
        .fade-in {
            animation: fadeIn 0.4s ease-out forwards;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

    <?php if (isset($_SESSION['user_id'])): ?>
    <div class="sidebar">
        <div class="sidebar-brand">
            <i class="fa-solid fa-hotel"></i> CHNMS
        </div>
        
        <div class="sidebar-heading">Main</div>
        <ul class="nav flex-column mb-3">
            <?php if ($_SESSION['role_id'] == 1): // Super Admin ?>
                <li class="nav-item">
                    <a class="nav-link active" href="<?= BASE_URL ?>/admin/dashboard">
                        <i class="fa-solid fa-border-all"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/admin/hotels">
                        <i class="fa-solid fa-building"></i> Hotels
                    </a>
                </li>
            <?php elseif ($_SESSION['role_id'] == 2): // Hotel Admin ?>
                <li class="nav-item">
                    <a class="nav-link active" href="<?= BASE_URL ?>/hotel/dashboard">
                        <i class="fa-solid fa-border-all"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/hotel/rooms">
                        <i class="fa-solid fa-bed"></i> Rooms
                    </a>
                </li>
            <?php endif; ?>
        </ul>

        <div class="sidebar-heading">Account</div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link text-danger" href="<?= BASE_URL ?>/logout">
                    <i class="fa-solid fa-sign-out-alt"></i> Logout
                </a>
            </li>
        </ul>
    </div>
    <?php endif; ?>

    <div class="main-content">
        <?php if (isset($_SESSION['user_id'])): ?>
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid px-0">
                <div>
                    <div class="breadcrumb-text mb-1">Pages / <?= htmlspecialchars($title ?? 'Dashboard') ?></div>
                    <span class="navbar-brand mb-0"><?= htmlspecialchars($title ?? 'Dashboard') ?></span>
                </div>
                <div class="d-flex align-items-center">
                    <div class="user-profile">
                        <span style="font-size: 13px; font-weight: 600; color: var(--primary-color); padding-left: 10px;">Role: <?= $_SESSION['role_id'] == 1 ? 'Admin' : 'Hotel' ?></span>
                        <div class="user-avatar ms-2">
                            <?= strtoupper(substr($_SESSION['name'], 0, 1)) ?>
                        </div>
                        <span class="user-name pe-2"><?= htmlspecialchars($_SESSION['name']) ?></span>
                    </div>
                </div>
            </div>
        </nav>
        <?php endif; ?>

        <div>
            <?php if(isset($_SESSION['error'])): ?>
                <div class="alert alert-danger" style="border-radius: 10px; border: none;"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>
            <?php if(isset($_SESSION['success'])): ?>
                <div class="alert alert-success" style="border-radius: 10px; border: none; background: rgba(5,205,153,0.1); color: #05cd99; font-weight: 600;"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
            <?php endif; ?>

            <?= $content ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
