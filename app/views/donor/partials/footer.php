<?php
/**
 * Donor Portal — Footer Partial
 * Include this at the bottom of every donor page view.
 * 
 * Expected variables:
 *   $donor_data, $donor_full_name, $donor_id_display
 *   $districts (array, for settings modal)
 *   $success_message, $error_message (from session, set in header.php)
 */

// Get districts for settings modal if not already set
if (!isset($districts)) {
    $districts = ["Ampara", "Anuradhapura", "Badulla", "Batticaloa", "Colombo", "Galle", "Gampaha", "Hambantota", "Jaffna", "Kalutara", "Kandy", "Kegalle", "Kilinochchi", "Kurunegala", "Mannar", "Matale", "Matara", "Moneragala", "Mullaitivu", "Nuwara Eliya", "Polonnaruwa", "Puttalam", "Ratnapura", "Trincomalee", "Vavuniya"];
}
?>
            </div> <!-- Close content-area -->
        </div> <!-- Close main-content -->
    </div> <!-- Close container-fluid -->

    <!-- Settings Modal -->
    <div id="settingsModal" class="modal">
        <div class="modal-content">
            <div class="modal-header"><h2>Donor Profile Settings</h2><button class="close-btn" onclick="closeSettingsModal()">&times;</button></div>
            <div class="modal-body">
                <form id="settingsForm" method="POST" action="<?= ROOT ?>/donor">
                    <input type="hidden" name="action" value="update_profile">
                    <div class="section-divider"><h3>Contact Information</h3><span style="font-size: 0.8rem; color: #059669;">(Editable)</span></div>
                    <div class="form-row">
                        <div class="form-group"><label>Contact Number *</label><input type="tel" name="contact_number" value="<?= htmlspecialchars($donor_data['contact_number'] ?? '') ?>" required maxlength="10"></div>
                        <div class="form-group"><label>Email Address *</label><input type="email" name="email" value="<?= htmlspecialchars($donor_data['email'] ?? '') ?>" required></div>
                    </div>
                    <div class="form-group"><label>Residential Address *</label><textarea name="address" rows="3" required><?= htmlspecialchars($donor_data['address'] ?? '') ?></textarea></div>
                    <div class="form-row">
                        <div class="form-group"><label>District *</label>
                            <select name="district" required><option value="">Select</option><?php foreach ($districts as $d): ?><option value="<?= $d ?>" <?= ($donor_data['district'] ?? '') == $d ? 'selected' : '' ?>><?= $d ?></option><?php endforeach; ?></select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer"><button class="btn btn-secondary" onclick="closeSettingsModal()">Cancel</button><button class="btn btn-primary" onclick="saveSettings()">Save Changes</button></div>
        </div>
    </div>

    <!-- Logout Modal -->
    <div id="logoutModal" class="modal">
        <div class="modal-content"><div class="modal-header"><h2>Confirm Logout</h2><button class="close-btn" onclick="closeLogoutModal()">&times;</button></div><div class="modal-body"><h3>Are you sure you want to logout?</h3><p>You will need to login again to access your dashboard.</p></div><div class="modal-footer"><button class="btn btn-secondary" onclick="closeLogoutModal()">Cancel</button><button class="btn btn-danger" onclick="confirmLogout()">Logout</button></div></div>
    </div>

    <!-- Shared Scripts -->
    <script>
        // Dropdowns & Modals
        function toggleUserDropdown() { document.getElementById('user-dropdown').classList.toggle('show'); }
        function openSettingsModal() { document.getElementById('settingsModal').classList.add('active'); }
        function closeSettingsModal() { document.getElementById('settingsModal').classList.remove('active'); }
        function openLogoutModal() { document.getElementById('logoutModal').classList.add('active'); }
        function closeLogoutModal() { document.getElementById('logoutModal').classList.remove('active'); }
        
        function openModal(id) { document.getElementById(id).classList.add('active'); }
        function closeModal(id) { document.getElementById(id).classList.remove('active'); }

        function confirmLogout() { window.location.href = '<?= ROOT ?>/logout'; }
        function saveSettings() { document.getElementById('settingsForm').submit(); }

        // Notifications
        function showPopup(type, title, message) {
            const popup = document.getElementById('popupNotification');
            popup.className = 'popup-notification ' + type;
            popup.querySelector('.popup-title').textContent = title;
            popup.querySelector('.popup-message').textContent = message;
            popup.querySelector('.popup-icon').innerHTML = type === 'success' ? '<i class="fas fa-check"></i>' : '<i class="fas fa-times"></i>';
            popup.classList.add('show');
            setTimeout(hidePopup, 4000);
        }
        function hidePopup() { document.getElementById('popupNotification').classList.remove('show'); }

        function downloadPDF(type) {
            window.open(`<?= ROOT ?>/donor/download-pdf?type=${type}`, '_blank');
        }

        // Show flash messages
        <?php if ($success_message): ?>showPopup('success', 'Success', '<?= addslashes($success_message) ?>');<?php endif; ?>
        <?php if ($error_message): ?>showPopup('error', 'Error', '<?= addslashes($error_message) ?>');<?php endif; ?>
    </script>
</body>
</html>
