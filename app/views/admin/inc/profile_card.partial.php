<?php
/**
 * Shared Administrative Profile Component
 * Includes the Dropdown Card and Edit Modal
 * 
 * Expected variables:
 * @var object $admin - The admin record (contains first_name, last_name, staff_id, designation, etc.)
 * @var string $adminRoleTitle - Human-readable role (e.g. "Donation Administrator")
 * @var string $dropdownId - (Optional) DOM ID for the dropdown container
 */

$dropdownId = $dropdownId ?? 'userProfileDropdown';
$adminName = ($admin->first_name ?? '') . ' ' . ($admin->last_name ?? '');
$avatarLetter = strtoupper(substr($admin->first_name ?? 'A', 0, 1));
?>

<!-- Profile Dropdown -->
<div class="profile-dropdown" id="<?= $dropdownId ?>">
    <div class="profile-card-header">
        <div class="card-avatar"><?= $avatarLetter ?></div>
        <div class="card-title-group">
            <div class="card-name"><?= htmlspecialchars($adminName) ?></div>
            <div class="card-role"><?= htmlspecialchars($adminRoleTitle ?? 'Administrator') ?></div>
        </div>
    </div>
    <div class="profile-card-body">
        <div class="info-row">
            <span class="info-label">Admin ID:</span>
            <span class="info-value"><?= htmlspecialchars($admin->staff_id ?? 'N/A') ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Email:</span>
            <span class="info-value"><?= htmlspecialchars($admin->email ?? 'N/A') ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Phone:</span>
            <span class="info-value"><?= htmlspecialchars($admin->contact_number ?? ($admin->phone ?? 'N/A')) ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Designation:</span>
            <span class="info-value"><?= htmlspecialchars($admin->designation ?? 'N/A') ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Status:</span>
            <span class="info-value">
                <span class="status-badge active"><?= htmlspecialchars($admin->status ?? 'ACTIVE') ?></span>
            </span>
        </div>
    </div>
    <div class="profile-card-footer">
        <a href="javascript:void(0)" onclick="toggleAdminProfileModal(true)" class="card-btn card-btn-outline">
            <i class="fa-solid fa-user-pen"></i>
            <span>Edit Profile</span>
        </a>
        <a href="<?= ROOT ?>/logout" class="card-btn card-btn-danger">
            <i class="fa-solid fa-right-from-bracket"></i>
            <span>Logout</span>
        </a>
    </div>
</div>

<!-- Edit Profile Modal -->
<div class="modal" id="adminProfileModal">
    <div class="modal-content">
        <div class="modal-header" style="padding: 24px 32px; border-bottom: 1px solid #edf2f7; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0;">Edit Admin Profile</h3>
            <button onclick="toggleAdminProfileModal(false)" style="background: none; border: none; font-size: 1.8rem; color: #64748b; cursor: pointer; line-height: 1;">&times;</button>
        </div>
        <div style="padding: 32px;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div class="form-group">
                    <label class="form-label">First Name</label>
                    <input type="text" id="modal_first_name" class="form-input" value="<?= htmlspecialchars($admin->first_name ?? '') ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Last Name</label>
                    <input type="text" id="modal_last_name" class="form-input" value="<?= htmlspecialchars($admin->last_name ?? '') ?>">
                </div>
            </div>
            <div style="margin-bottom: 20px;">
                <label class="form-label">Email Address</label>
                <input type="email" id="modal_email" class="form-input" value="<?= htmlspecialchars($admin->email ?? '') ?>">
            </div>
            <div style="margin-bottom: 20px;">
                <label class="form-label">Contact Number</label>
                <input type="text" id="modal_phone" class="form-input" value="<?= htmlspecialchars($admin->contact_number ?? ($admin->phone ?? '')) ?>">
            </div>
            <div style="margin-bottom: 32px;">
                <label class="form-label">Designation</label>
                <input type="text" id="modal_designation" class="form-input" value="<?= htmlspecialchars($admin->designation ?? '') ?>">
            </div>
            <button onclick="saveAdminProfileAjax()" class="btn btn-primary" id="saveAdminProfileBtn" style="width: 100%; height: 52px; border-radius: 12px; font-size: 1rem; justify-content: center;">
                Update Information
            </button>
        </div>
    </div>
</div>

<script>
    function toggleAdminProfileModal(show) {
        const modal = document.getElementById('adminProfileModal');
        const dropdown = document.getElementById('<?= $dropdownId ?>');
        if (show) {
            modal.classList.add('show');
            if (dropdown) dropdown.classList.remove('active');
        } else {
            modal.classList.remove('show');
        }
    }

    function saveAdminProfileAjax() {
        const btn = document.getElementById('saveAdminProfileBtn');
        const data = {
            first_name: document.getElementById('modal_first_name').value,
            last_name: document.getElementById('modal_last_name').value,
            email: document.getElementById('modal_email').value,
            contact_number: document.getElementById('modal_phone').value,
            designation: document.getElementById('modal_designation').value
        };

        btn.innerText = 'Updating...';
        btn.disabled = true;

        fetch('<?= ROOT ?>/user-admin/ajaxUpdateProfile', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(result => {
            btn.innerText = 'Update Information';
            btn.disabled = false;
            if (result.success) {
                toggleAdminProfileModal(false);
                window.location.reload(); 
            } else {
                alert(result.message);
            }
        })
        .catch(err => {
            btn.innerText = 'Update Information';
            btn.disabled = false;
            console.error('Save error:', err);
            alert('An error occurred while saving.');
        });
    }

    // Shared outside-click logic
    document.addEventListener('click', function(e) {
        const dropdown = document.getElementById('<?= $dropdownId ?>');
        const toggle = document.querySelector('[data-profile-toggle]'); // Centralized toggle selector
        
        if (dropdown && dropdown.classList.contains('active')) {
            if (!dropdown.contains(e.target) && (!toggle || !toggle.contains(e.target))) {
                dropdown.classList.remove('active');
            }
        }
    });
</script>
