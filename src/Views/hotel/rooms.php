<div class="row fade-in">
    <div class="col-12 mb-4 d-flex justify-content-between align-items-center">
        <div>
            <!-- No title needed here since it's in the navbar -->
        </div>
        <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addRoomModal">
            <i class="fa-solid fa-plus me-2"></i> Add New Room
        </button>
    </div>
</div>

<div class="row fade-in">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                Current Inventory
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
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
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="user-avatar" style="background: rgba(67, 24, 255, 0.1); color: var(--primary-color);">R</div>
                                        <span class="fw-bold fs-6"><?= htmlspecialchars($room['number']) ?></span>
                                    </div>
                                </td>
                                <td><?= htmlspecialchars($room['type']) ?></td>
                                <td>₹<?= number_format($room['price'], 2) ?></td>
                                <td>
                                    <?php 
                                        if($room['status'] == 'available') {
                                            echo '<span class="status-badge active"><span class="dot"></span> Available</span>';
                                        } elseif($room['status'] == 'occupied') {
                                            echo '<span class="status-badge inactive" style="background: rgba(255, 153, 32, 0.1); color: #ff9920;"><span class="dot" style="background: #ff9920;"></span> Occupied</span>';
                                        } else {
                                            echo '<span class="status-badge inactive"><span class="dot"></span> Cleaning</span>';
                                        }
                                    ?>
                                </td>
                                <td>
                                    <button class="btn btn-sm text-primary" style="background: rgba(67,24,255,0.1);"><i class="fa-solid fa-pen"></i> Edit</button>
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
    <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
      <div class="modal-header" style="border-bottom: 1px solid var(--border-color); padding: 20px 25px;">
        <h5 class="modal-title fw-bold" style="color: var(--text-main);">Add New Room</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="<?= BASE_URL ?>/hotel/rooms/add" method="POST">
          <div class="modal-body" style="padding: 25px;">
              <div class="mb-3">
                  <label class="form-label">Room Number</label>
                  <input type="text" name="room_number" class="form-control" required placeholder="e.g. 101">
              </div>
              <div class="mb-3">
                  <label class="form-label">Room Type</label>
                  <select name="room_type_id" class="form-select" required>
                      <option value="1">Deluxe - ₹2500</option>
                      <option value="2">Suite - ₹5000</option>
                  </select>
              </div>
          </div>
          <div class="modal-footer" style="border-top: 1px solid var(--border-color); padding: 20px 25px;">
            <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal" style="color: var(--text-muted);">Cancel</button>
            <button type="submit" class="btn btn-primary fw-bold px-4">Save Room</button>
          </div>
      </form>
    </div>
  </div>
</div>
