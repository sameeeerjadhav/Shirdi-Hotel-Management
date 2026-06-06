<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'CHNMS') ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-bg: #1e1e2f;
            --sidebar-bg: #27293d;
            --accent-color: #e14eca;
            --text-color: #ffffff;
            --card-bg: rgba(39, 41, 61, 0.8);
        }
        body {
            background: linear-gradient(135deg, #1e1e2f 0%, #1a1a24 100%);
            color: var(--text-color);
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            height: 100vh;
            overflow: hidden;
        }
        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            background: var(--sidebar-bg);
            padding-top: 20px;
            height: 100vh;
            box-shadow: 0 2px 22px 0 rgba(0,0,0,0.2), 0 2px 30px 0 rgba(0,0,0,0.35);
            transition: 0.3s;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.7);
            padding: 12px 25px;
            margin: 8px 15px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: #fff;
            background: var(--accent-color);
            box-shadow: 0 4px 20px 0 rgba(0,0,0,.14), 0 7px 10px -5px rgba(225,78,202,.4);
            transform: translateX(5px);
        }
        .sidebar-brand {
            color: #fff;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 30px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        /* Main Content */
        .main-content {
            flex-grow: 1;
            padding: 20px;
            overflow-y: auto;
        }
        .navbar {
            background: transparent !important;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 30px;
        }
        .card {
            background: var(--card-bg);
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 20px 0 rgba(0,0,0,0.2);
            margin-bottom: 30px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px 0 rgba(0,0,0,0.3);
        }
        .glass-card {
            background: rgba(39, 41, 61, 0.6);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        .card-header {
            background: transparent;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .table {
            color: var(--text-color);
        }
        .table tbody tr:hover {
            color: #fff;
            background-color: rgba(255,255,255,0.05);
        }
        .form-control {
            background: #1e1e2f;
            border: 1px solid #2b3553;
            color: #fff;
        }
        .form-control:focus {
            background: #1e1e2f;
            color: #fff;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.25rem rgba(225, 78, 202, 0.25);
        }
        
        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in {
            animation: fadeIn 0.6s ease-out forwards;
        }
    </style>
</head>
<body>

    <?php if (isset($_SESSION['user_id'])): ?>
    <div class="sidebar">
        <div class="sidebar-brand">CHNMS</div>
        <ul class="nav flex-column">
            <?php if ($_SESSION['role_id'] == 1): // Super Admin ?>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/admin/dashboard">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/admin/hotels">Hotels</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/admin/bookings">Bookings</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/admin/finance">Finance</a></li>
            <?php elseif ($_SESSION['role_id'] == 2): // Hotel Admin ?>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/hotel/dashboard">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/hotel/rooms">Rooms</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/hotel/bookings">Bookings</a></li>
            <?php endif; ?>
            <li class="nav-item"><a class="nav-link text-danger" href="<?= BASE_URL ?>/logout">Logout</a></li>
        </ul>
    </div>
    <?php endif; ?>

    <div class="main-content">
        <?php if (isset($_SESSION['user_id'])): ?>
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container-fluid">
                <span class="navbar-brand mb-0 h1"><?= htmlspecialchars($title ?? 'Dashboard') ?></span>
                <div class="d-flex">
                    <span class="text-white me-3 mt-2">Welcome, <?= htmlspecialchars($_SESSION['name']) ?></span>
                </div>
            </div>
        </nav>
        <?php endif; ?>

        <div class="container-fluid">
            <?php if(isset($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>
            <?php if(isset($_SESSION['success'])): ?>
                <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
            <?php endif; ?>

            <?= $content ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
