<div class="modal" id="profile-modal">
        <div class="modal-content" style="max-width: 500px;">
            <div class="modal-header">
                <h3>Edit Hospital Profile</h3>
                <button class="modal-close" onclick="closeProfileModal()">×</button>
            </div>
            <div>
                <div class="form-group">
                    <label class="form-label">Hospital Name</label>
                    <input type="text" class="form-input" id="profile-name" value="<?php echo htmlspecialchars($hospital_details['name']); ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Address</label>
                    <input type="text" class="form-input" id="profile-address" value="<?php echo htmlspecialchars($hospital_details['address'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Phone Number</label>
                    <input type="text" class="form-input" id="profile-phone" value="<?php echo htmlspecialchars($hospital_details['contact_number'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">District</label>
                    <input type="text" class="form-input" id="profile-district" value="<?php echo htmlspecialchars($hospital_details['district'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Facility Type</label>
                    <input type="text" class="form-input" id="profile-facility-type" value="<?php echo htmlspecialchars($hospital_details['facility_type'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">CMO Name</label>
                    <input type="text" class="form-input" id="profile-cmo-name" value="<?php echo htmlspecialchars($hospital_details['cmo_name'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">CMO NIC</label>
                    <input type="text" class="form-input" id="profile-cmo-nic" value="<?php echo htmlspecialchars($hospital_details['cmo_nic'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Medical License Number</label>
                    <input type="text" class="form-input" id="profile-medical-license" value="<?php echo htmlspecialchars($hospital_details['medical_license_number'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Verification Status</label>
                    <input type="text" class="form-input" id="profile-verification-status" value="<?php echo htmlspecialchars($hospital_details['verification_status'] ?? ''); ?>" disabled>
                </div>
                <div class="form-group">
                    <label class="form-label">Email (Login Account)</label>
                    <input type="text" class="form-input" value="<?php echo htmlspecialchars($hospital_details['email']); ?>" disabled style="background: #f8f9fa;">
                </div>
                <button class="btn btn-primary" onclick="saveProfile()" style="width: 100%;">Update Information</button>
            </div>
        </div>
    </div>