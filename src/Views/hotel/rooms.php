<div class="row fade-in">
    <div class="col-12 mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h2 style="color: #e14eca;">Room Management</h2>
            <p class="text-muted">Add, edit, and track the live status of all rooms in your hotel.</p>
        </div>
        <button class="btn btn-lg shadow-glass" style="background-color: #e14eca; color: white; font-weight: bold;" data-bs-toggle="modal" data-bs-target="#addRoomModal">
            + Add New Room
        </button>
    </div>
</div>

<div class="row fade-in">
    <div class="col-md-12">
        <div class="card glass-card">
            <div class="card-header border-bottom border-secondary pb-3">
                <h4 class="card-title text-white mb-0">Current Inventory</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="text-primary">
                            <tr>
                                <th>Room Number</th>
                                <th>Type</th>
                                <th>Price / Night</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($rooms as $room): ?>
                            <tr>
                                <td class="fw-bold fs-5 text-white"><?= htmlspecialchars($room['number']) ?></td>
                                <td><?= htmlspecialchars($room['type']) ?></td>
                                <td>₹<?= number_format($room['price'], 2) ?></td>
                                <td>
                                    <?php 
                                        $badgeClass = 'bg-secondary';
                                        if($room['status'] == 'available') $badgeClass = 'bg-success';
                                        if($room['status'] == 'occupied') $badgeClass = 'bg-danger';
                                        if($room['status'] == 'cleaning') $badgeClass = 'bg-warning text-dark';
                                    ?>
                                    <span class="badge <?= $badgeClass ?> px-3 py-2 text-uppercase" style="letter-spacing: 1px;">
                                        <?= htmlspecialchars($room['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-info rounded-pill px-3">Edit</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Room Modal -->
<div class="modal fade" id="addRoomModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content glass-card" style="background-color: #27293d; border: 1px solid rgba(225,78,202,0.5);">
      <div class="modal-header border-bottom border-secondary">
        <h5 class="modal-title text-white">Add New Room</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="<?= BASE_URL ?>/hotel/rooms/add" method="POST">
          <div class="modal-body">
              <div class="mb-3">
                  <label class="form-label text-white">Room Number</label>
                  <input type="text" name="room_number" class="form-control" required>
              </div>
              <div class="mb-3">
                  <label class="form-label text-white">Room Type</label>
                  <select name="room_type_id" class="form-control" required>
                      <option value="1">Deluxe - ₹2500</option>
                      <option value="2">Suite - ₹5000</option>
                  </select>
              </div>
          </div>
          <div class="modal-footer border-top border-secondary">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn" style="background-color: #e14eca; color: white;">Save Room</button>
          </div>
      </form>
    </div>
  </div>
</div>
