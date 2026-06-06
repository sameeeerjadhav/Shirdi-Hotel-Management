<div class="page-header fade-in">
    <div>
        <h2>Room Management</h2>
        <p>Add, edit, and track the live status of all rooms in your property.</p>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRoomModal">
        <i class="fa-solid fa-plus me-2"></i>Add Room
    </button>
</div>

<div class="card fade-in-2">
    <div class="card-header">Current Inventory</div>
    <div class="card-body" style="padding:0;">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Room</th>
                    <th>Type</th>
                    <th>Price / Night</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($rooms as $room): ?>
                <tr>
                    <td>
                        <div class="identity-cell">
                            <div class="identity-avatar" style="background:rgba(99,102,241,0.1);color:#6366f1;">
                                <?= strtoupper(substr($room['number'], 0, 1)) ?>
                            </div>
                            <span class="identity-name"><?= htmlspecialchars($room['number']) ?></span>
                        </div>
                    </td>
                    <td><?= htmlspecialchars($room['type']) ?></td>
                    <td style="font-weight:600; color:var(--text-heading);">₹<?= number_format($room['price'], 2) ?></td>
                    <td>
                        <?php if($room['status'] == 'available'): ?>
                            <span class="badge-pill badge-active"><span class="dot"></span> Available</span>
                        <?php elseif($room['status'] == 'occupied'): ?>
                            <span class="badge-pill badge-occupied"><span class="dot"></span> Occupied</span>
                        <?php else: ?>
                            <span class="badge-pill badge-cleaning"><span class="dot"></span> Cleaning</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <button class="btn-icon btn-icon-primary"><i class="fa-solid fa-pen"></i></button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Room Modal -->
<div class="modal fade" id="addRoomModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add New Room</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="<?= BASE_URL ?>/hotel/rooms/add" method="POST">
          <div class="modal-body">
              <div class="mb-3">
                  <label class="form-label">Room Number</label>
                  <input type="text" name="room_number" class="form-control" required placeholder="e.g. 101">
              </div>
              <div class="mb-3">
                  <label class="form-label">Room Type</label>
                  <select name="room_type_id" class="form-select" required>
                      <option value="1">Deluxe — ₹2,500 / night</option>
                      <option value="2">Suite — ₹5,000 / night</option>
                  </select>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Save Room</button>
          </div>
      </form>
    </div>
  </div>
</div>
