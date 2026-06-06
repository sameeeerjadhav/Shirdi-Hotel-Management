<div class="row">
    <div class="col-12 mb-4">
        <h2 style="color: #e14eca;">Super Admin Dashboard</h2>
        <p class="text-muted">Overview of all registered hotels and network status.</p>
    </div>
</div>

<div class="row">
    <div class="col-lg-3 col-md-6">
        <div class="card card-stats p-3 border-0 shadow" style="border-left: 4px solid #e14eca !important;">
            <div class="card-body">
                <div class="row">
                    <div class="col-5">
                        <div class="icon-big text-center icon-warning">
                            <i class="text-warning" style="font-size: 32px;">🏢</i>
                        </div>
                    </div>
                    <div class="col-7">
                        <div class="numbers">
                            <p class="card-category text-muted mb-0">Total Hotels</p>
                            <h3 class="card-title text-white mb-0"><?= $stats['total_hotels'] ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card card-stats p-3 border-0 shadow" style="border-left: 4px solid #00f2c3 !important;">
            <div class="card-body">
                <div class="row">
                    <div class="col-5">
                        <div class="icon-big text-center icon-warning">
                            <i class="text-success" style="font-size: 32px;">🛏️</i>
                        </div>
                    </div>
                    <div class="col-7">
                        <div class="numbers">
                            <p class="card-category text-muted mb-0">Total Rooms</p>
                            <h3 class="card-title text-white mb-0"><?= $stats['total_rooms'] ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card card-stats p-3 border-0 shadow" style="border-left: 4px solid #fd5d93 !important;">
            <div class="card-body">
                <div class="row">
                    <div class="col-5">
                        <div class="icon-big text-center icon-warning">
                            <i class="text-danger" style="font-size: 32px;">🗝️</i>
                        </div>
                    </div>
                    <div class="col-7">
                        <div class="numbers">
                            <p class="card-category text-muted mb-0">Occupied</p>
                            <h3 class="card-title text-white mb-0"><?= $stats['occupied_rooms'] ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card card-stats p-3 border-0 shadow" style="border-left: 4px solid #1d8cf8 !important;">
            <div class="card-body">
                <div class="row">
                    <div class="col-5">
                        <div class="icon-big text-center icon-warning">
                            <i class="text-info" style="font-size: 32px;">💰</i>
                        </div>
                    </div>
                    <div class="col-7">
                        <div class="numbers">
                            <p class="card-category text-muted mb-0">Revenue</p>
                            <h3 class="card-title text-white mb-0">$<?= number_format($stats['revenue'], 2) ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title text-white mb-0">Recent Hotel Registrations</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="text-primary">
                            <tr>
                                <th>Name</th>
                                <th>City</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Grand Plaza Hotel</td>
                                <td>Mumbai</td>
                                <td><span class="badge bg-warning">Pending Approval</span></td>
                                <td>
                                    <button class="btn btn-sm btn-success">Approve</button>
                                    <button class="btn btn-sm btn-danger">Reject</button>
                                </td>
                            </tr>
                            <tr>
                                <td>Sea View Resort</td>
                                <td>Goa</td>
                                <td><span class="badge bg-success">Approved</span></td>
                                <td>
                                    <button class="btn btn-sm btn-info">View Details</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
