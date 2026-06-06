<div class="row align-items-center" style="min-height: 80vh;">
    <div class="col-md-6 text-center text-md-start">
        <h1 class="display-4 fw-bold" style="color: #e14eca;">Centralized Hotel Network Management System</h1>
        <p class="lead text-white mt-4">Enterprise-grade solution for managing partner hotels, automated room tracking, and smart booking transfers.</p>
        <div class="mt-5">
            <?php if(isset($_SESSION['user_id'])): ?>
                <?php if($_SESSION['role_id'] == 1): ?>
                    <a href="/admin/dashboard" class="btn btn-lg px-5 py-3 mb-3" style="background-color: #e14eca; color: white; font-weight: bold; border-radius: 30px;">Go to Admin Dashboard</a>
                <?php else: ?>
                    <a href="/hotel/dashboard" class="btn btn-lg px-5 py-3 mb-3" style="background-color: #e14eca; color: white; font-weight: bold; border-radius: 30px;">Go to Hotel Dashboard</a>
                <?php endif; ?>
            <?php else: ?>
                <a href="/login" class="btn btn-lg px-5 py-3 mb-3 me-2" style="background-color: transparent; border: 2px solid #e14eca; color: white; font-weight: bold; border-radius: 30px;">Login to Portal</a>
            <?php endif; ?>
            
            <a href="/search" class="btn btn-lg px-5 py-3 mb-3 shadow-glass" style="background-color: #00f2c3; color: #1e1e2f; font-weight: bold; border-radius: 30px;">Find a Room</a>
        </div>
    </div>
    <div class="col-md-6 d-none d-md-block text-center">
        <!-- Dashboard Illustration Mockup -->
        <div class="p-3 shadow-lg" style="background: #27293d; border-radius: 20px; border: 1px solid rgba(255,255,255,0.1);">
            <div class="d-flex mb-3">
                <div style="width: 15px; height: 15px; background: #ff5f56; border-radius: 50%; margin-right: 8px;"></div>
                <div style="width: 15px; height: 15px; background: #ffbd2e; border-radius: 50%; margin-right: 8px;"></div>
                <div style="width: 15px; height: 15px; background: #27c93f; border-radius: 50%;"></div>
            </div>
            <div class="row g-2">
                <div class="col-4"><div style="height: 60px; background: rgba(225,78,202,0.2); border-radius: 8px;"></div></div>
                <div class="col-4"><div style="height: 60px; background: rgba(225,78,202,0.2); border-radius: 8px;"></div></div>
                <div class="col-4"><div style="height: 60px; background: rgba(225,78,202,0.2); border-radius: 8px;"></div></div>
                <div class="col-12 mt-3"><div style="height: 150px; background: rgba(255,255,255,0.05); border-radius: 8px;"></div></div>
            </div>
        </div>
    </div>
</div>
