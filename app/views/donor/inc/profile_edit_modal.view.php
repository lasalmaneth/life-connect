<?php if(!defined('ROOT')) die(); ?>

<!-- Modal: Edit Profile -->
<div id="editProfileModal" class="d-modal">
    <div class="d-modal__body" style="max-width: 500px;">
        <div class="d-modal__header">
            <h3 class="d-modal__title">Update Contact Information</h3>
            <button class="d-modal__close" onclick="closeEditProfileModal()">&times;</button>
        </div>
        <div class="d-modal__content" style="padding: 1.5rem;">
            <div class="d-detail-item" style="margin-bottom: 1.5rem;">
                <label style="display:block; margin-bottom:0.5rem; font-weight:700; color:var(--g600);">Telephone Number</label>
                <input type="text" id="edit_phone" class="d-input" value="<?= htmlspecialchars($donor_data['phone'] ?? $donor_data['phone_number'] ?? '') ?>" style="width:100%; padding:0.75rem; border:1px solid var(--g200); border-radius:10px;">
            </div>
            <div class="d-detail-item">
                <label style="display:block; margin-bottom:0.5rem; font-weight:700; color:var(--g600);">Residential Address</label>
                <textarea id="edit_address" class="d-input" style="width:100%; padding:0.75rem; border:1px solid var(--g200); border-radius:10px; min-height:100px;"><?= htmlspecialchars($donor_data['address'] ?? '') ?></textarea>
            </div>
            
            <div style="margin-top: 2rem; display: flex; gap: 10px; justify-content: flex-end;">
                <button class="d-btn d-btn--outline" onclick="closeEditProfileModal()">Cancel</button>
                <button id="saveProfileBtn" class="d-btn d-btn--primary" onclick="saveProfile()">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<script>
    // --- Profile Edit Logic ---
    function openEditProfileModal() {
        document.getElementById('editProfileModal').classList.add('active');
    }

    function closeEditProfileModal() {
        document.getElementById('editProfileModal').classList.remove('active');
    }

    async function saveProfile() {
        const phone = document.getElementById('edit_phone').value;
        const address = document.getElementById('edit_address').value;
        const btn = document.getElementById('saveProfileBtn');

        if (!phone) {
            alert("Phone number is required.");
            return;
        }

        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';

        const formData = new FormData();
        formData.append('phone', phone);
        formData.append('address', address);

        try {
            const response = await fetch('<?= ROOT ?>/donor/update_profile', {
                method: 'POST',
                body: formData
            });
            const data = await response.json();
            if (data.success) {
                // Update display values immediately if on the overview page
                const phoneLabel = document.getElementById('display_phone');
                const addressLabel = document.getElementById('display_address');
                
                if (phoneLabel) phoneLabel.innerHTML = '<i class="fas fa-phone text-blue-500" style="font-size:0.8rem;"></i> ' + phone;
                if (addressLabel) addressLabel.innerHTML = '<i class="fas fa-map-marker-alt text-blue-500" style="font-size:0.8rem;"></i> ' + address;
                
                closeEditProfileModal();
                
                // Use Swal if available, otherwise fallback to alert
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Profile updated successfully.',
                        icon: 'success',
                        confirmButtonColor: '#3b82f6'
                    });
                } else {
                    alert('Profile updated successfully.');
                }
            } else {
                alert(data.message || "Failed to update profile.");
            }
        } catch (e) {
            console.error(e);
            alert("An error occurred. Please try again.");
        } finally {
            btn.disabled = false;
            btn.innerHTML = 'Save Changes';
        }
    }
</script>
