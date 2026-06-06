<div class="row fade-in mb-4">
    <div class="col-12 text-center">
        <h2 style="color: #e14eca;">Find Your Perfect Stay</h2>
        <p class="text-muted">Search through our premium network of partner hotels.</p>
    </div>
</div>

<div class="row justify-content-center fade-in mb-5">
    <div class="col-md-10">
        <div class="card glass-card p-4">
            <form action="/search" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label text-white">City / Location</label>
                    <input type="text" name="city" class="form-control" value="<?= htmlspecialchars($city ?? '') ?>" placeholder="e.g. Mumbai" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-white">Check-in</label>
                    <input type="date" name="checkin" class="form-control" value="<?= htmlspecialchars($checkin ?? '') ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-white">Check-out</label>
                    <input type="date" name="checkout" class="form-control" value="<?= htmlspecialchars($checkout ?? '') ?>" required>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn w-100" style="background-color: #e14eca; color: white; font-weight: bold;">Search</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php if (isset($_GET['city'])): ?>
<div class="row fade-in">
    <div class="col-12">
        <h4 class="text-white mb-4">Search Results for "<?= htmlspecialchars($city) ?>"</h4>
    </div>
    
    <?php if (empty($hotels)): ?>
        <div class="col-12">
            <div class="alert alert-warning">No hotels found in this city.</div>
        </div>
    <?php else: ?>
        <?php foreach($hotels as $hotel): ?>
        <div class="col-md-6 mb-4">
            <div class="card glass-card h-100">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="card-title text-white mb-0" style="font-weight: 600;"><?= htmlspecialchars($hotel['name']) ?></h5>
                        <span class="badge bg-warning text-dark">⭐ <?= $hotel['rating'] ?></span>
                    </div>
                    <p class="text-muted"><i class="fa fa-map-marker"></i> <?= htmlspecialchars($hotel['city']) ?></p>
                    
                    <div class="mt-auto pt-3 border-top border-secondary d-flex justify-content-between align-items-center">
                        <div>
                            <span class="fs-4 fw-bold text-white">₹<?= number_format($hotel['price']) ?></span>
                            <span class="text-muted small">/ night</span>
                        </div>
                        <?php if($hotel['available_rooms'] > 0): ?>
                            <a href="/checkout/<?= $hotel['id'] ?>" class="btn btn-success px-4" style="border-radius: 20px;">Book Now</a>
                        <?php else: ?>
                            <a href="/checkout/<?= $hotel['id'] ?>" class="btn btn-danger px-4" style="border-radius: 20px;">Sold Out - Find Alternative</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<?php endif; ?>
