<div class="row justify-content-center mt-5">
    <div class="col-md-4">
        <div class="card shadow-lg p-4" style="background-color: #27293d; border: 1px solid #e14eca;">
            <div class="text-center mb-4">
                <h3 class="text-white">CHNMS Login</h3>
                <p class="text-muted">Enter your credentials to access the system</p>
            </div>
            <form action="/login" method="POST">
                <div class="mb-3">
                    <label class="form-label text-white">Email Address</label>
                    <input type="email" name="email" class="form-control" required placeholder="admin@chnms.com">
                </div>
                <div class="mb-4">
                    <label class="form-label text-white">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn w-100" style="background-color: #e14eca; color: white; font-weight: bold;">LOGIN</button>
            </form>
        </div>
    </div>
</div>
