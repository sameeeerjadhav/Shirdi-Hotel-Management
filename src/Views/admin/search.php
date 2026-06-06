<div class="page-header fade-in">
    <div>
        <h2>Internal Room Search</h2>
        <p>Find available rooms across the network for walk-in guests or offline bookings.</p>
    </div>
</div>

<div class="card fade-in-2 mb-4" style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: white; border: none;">
    <div class="card-body" style="padding: 32px;">
        <h3 style="font-size: 20px; font-weight: 700; margin-bottom: 24px; color: white;">Search Inventory</h3>
        
        <div class="row g-3">
            <div class="col-md-4">
                <label style="font-size: 13px; font-weight: 600; color: rgba(255,255,255,0.8); margin-bottom: 8px;">Location or Hotel</label>
                <div style="position: relative;">
                    <i class="fa-solid fa-location-dot" style="position: absolute; left: 16px; top: 14px; color: #94a3b8;"></i>
                    <input type="text" class="form-control" placeholder="City, Area, or Hotel Name" style="padding-left: 44px; height: 46px; border-radius: 10px; border: none;">
                </div>
            </div>
            <div class="col-md-3">
                <label style="font-size: 13px; font-weight: 600; color: rgba(255,255,255,0.8); margin-bottom: 8px;">Check-In</label>
                <div style="position: relative;">
                    <i class="fa-regular fa-calendar" style="position: absolute; left: 16px; top: 14px; color: #94a3b8;"></i>
                    <input type="date" class="form-control" style="padding-left: 44px; height: 46px; border-radius: 10px; border: none; color: #475569;">
                </div>
            </div>
            <div class="col-md-3">
                <label style="font-size: 13px; font-weight: 600; color: rgba(255,255,255,0.8); margin-bottom: 8px;">Check-Out</label>
                <div style="position: relative;">
                    <i class="fa-regular fa-calendar" style="position: absolute; left: 16px; top: 14px; color: #94a3b8;"></i>
                    <input type="date" class="form-control" style="padding-left: 44px; height: 46px; border-radius: 10px; border: none; color: #475569;">
                </div>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button class="btn btn-light w-100" style="height: 46px; border-radius: 10px; font-weight: 700; color: var(--primary);">
                    Search
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Placeholder Results -->
<div class="text-center fade-in-2" style="padding: 60px 20px;">
    <div style="width: 80px; height: 80px; background: white; color: var(--text-muted); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 32px; margin-bottom: 24px; box-shadow: var(--shadow-sm);">
        <i class="fa-solid fa-magnifying-glass"></i>
    </div>
    <h3 style="font-size: 18px; font-weight: 600; color: var(--text-heading); margin-bottom: 8px;">No Search Initiated</h3>
    <p style="color: var(--text-muted); font-size: 14px;">Enter details above to search through available network inventory.</p>
</div>
