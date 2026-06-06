<div class="row justify-content-center mt-4 mb-5">
    <div class="col-md-8">
        <div class="card shadow-lg p-4" style="background-color: #27293d; border: 1px solid #e14eca;">
            <div class="text-center mb-4">
                <h3 style="color: #e14eca;">Partner with CHNMS</h3>
                <p class="text-muted">Register your hotel to join our centralized booking network.</p>
            </div>
            <form action="/register-hotel" method="POST" enctype="multipart/form-data">
                
                <h5 class="text-white border-bottom border-secondary pb-2 mb-3">Hotel Administrator Details</h5>
                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-white">Admin Full Name</label>
                        <input type="text" name="admin_name" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-white">Admin Email</label>
                        <input type="email" name="admin_email" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-white">Admin Phone</label>
                        <input type="text" name="admin_phone" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-white">Password</label>
                        <input type="password" name="admin_password" class="form-control" required>
                    </div>
                </div>

                <h5 class="text-white border-bottom border-secondary pb-2 mb-3">Hotel Details</h5>
                <div class="row mb-4">
                    <div class="col-md-12 mb-3">
                        <label class="form-label text-white">Hotel Name</label>
                        <input type="text" name="hotel_name" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-white">Hotel Official Email</label>
                        <input type="email" name="hotel_email" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-white">Hotel Phone Number</label>
                        <input type="text" name="hotel_phone" class="form-control" required>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label text-white">Address</label>
                        <textarea name="address" class="form-control" rows="2" required></textarea>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label text-white">City</label>
                        <input type="text" name="city" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label text-white">State</label>
                        <input type="text" name="state" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label text-white">Zip Code</label>
                        <input type="text" name="zip" class="form-control" required>
                    </div>
                </div>

                <h5 class="text-white border-bottom border-secondary pb-2 mb-3">Verification Documents</h5>
                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-white">Trade License (PDF/Image)</label>
                        <input type="file" name="trade_license" class="form-control text-white" accept=".pdf,.jpg,.jpeg,.png" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-white">GST Certificate (PDF/Image)</label>
                        <input type="file" name="gst_certificate" class="form-control text-white" accept=".pdf,.jpg,.jpeg,.png" required>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-lg px-5" style="background-color: #e14eca; color: white; font-weight: bold;">Submit Registration</button>
                </div>
            </form>
        </div>
    </div>
</div>
