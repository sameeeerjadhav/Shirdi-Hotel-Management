<div class="row justify-content-center mt-4 fade-in">
    <div class="col-md-8">
        
        <?php if(isset($_SESSION['transfer_alert'])): ?>
            <div class="alert alert-success d-flex align-items-center mb-4 border-0 shadow-glass" style="background: rgba(0, 242, 195, 0.2); color: #00f2c3;">
                <span style="font-size: 24px; margin-right: 15px;">🔄</span>
                <div>
                    <strong>Smart Transfer Triggered!</strong><br>
                    <?= $_SESSION['transfer_alert']; unset($_SESSION['transfer_alert']); ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="card glass-card p-4">
            <h3 class="text-white mb-4 border-bottom border-secondary pb-3">Complete Your Booking</h3>
            
            <div class="bg-dark p-3 rounded mb-4 border border-secondary">
                <h5 class="text-white" style="color: #e14eca !important;"><?= htmlspecialchars($hotel['name']) ?></h5>
                <div class="d-flex justify-content-between mt-2">
                    <span class="text-muted">Total Amount</span>
                    <span class="text-white fw-bold fs-5">₹<?= number_format($hotel['price'], 2) ?></span>
                </div>
            </div>

            <form action="<?= BASE_URL ?>/checkout/<?= $hotel['id'] ?>" method="POST">
                <h5 class="text-white mb-3">Guest Details</h5>
                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-white">First Name</label>
                        <input type="text" name="first_name" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-white">Last Name</label>
                        <input type="text" name="last_name" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-white">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-white">Phone</label>
                        <input type="text" name="phone" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-white">ID Proof Type</label>
                        <select name="id_proof_type" class="form-control" required>
                            <option value="Aadhar">Aadhar Card</option>
                            <option value="Passport">Passport</option>
                            <option value="Driver License">Driver's License</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-white">ID Proof Number</label>
                        <input type="text" name="id_proof_number" class="form-control" required>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-lg w-100" style="background-color: #00f2c3; color: #1e1e2f; font-weight: bold;">
                    Confirm Booking & Pay ₹<?= number_format($hotel['price'], 2) ?>
                </button>
            </form>
        </div>
    </div>
</div>
