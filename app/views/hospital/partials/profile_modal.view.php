<div class="modal" id="profile-modal">
        <div class="modal-content" style="max-width: 500px;">
            <div class="modal-header">
                <h3>Edit Hospital Profile</h3>
                <button class="modal-close" onclick="closeProfileModal()">×</button>
            </div>
            <div>
                <div class="form-group">
                    <label class="form-label">Hospital Name</label>
                    <input type="text" class="form-input" id="profile-name"
                        value="<?php echo htmlspecialchars($hospital_details['name']); ?>">
                </div>
                <?php
                $modalAddress = $hospital_details['address'] ?? '';
                $modalPhone = $hospital_details['phone'] ?? '';
                if ($modalAddress && strpos($modalAddress, '[Phone]:') !== false) {
                    $parts = explode(' | [Address]: ', $modalAddress);
                    $modalPhone = str_replace('[Phone]: ', '', $parts[0]);
                    $modalAddress = $parts[1] ?? '';
                }
                ?>
                <div class="form-group">
                    <label class="form-label">Address</label>
                    <input type="text" class="form-input" id="profile-address"
                        value="<?php echo htmlspecialchars($modalAddress); ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Phone Number</label>
                    <input type="text" class="form-input" id="profile-phone"
                        value="<?php echo htmlspecialchars($modalPhone); ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Email (Login Account)</label>
                    <input type="text" class="form-input"
                        value="<?php echo htmlspecialchars($hospital_details['email']); ?>" disabled
                        style="background: #f8f9fa;">
                </div>
                <button class="btn btn-primary" onclick="saveProfile()" style="width: 100%;">Update Information</button>
            </div>
        </div>
    </div>