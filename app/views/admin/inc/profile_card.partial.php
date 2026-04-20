<?php
/**
 * Shared Administrative Profile Component
 * Includes the Dropdown Card with Inline Editing
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

<style>
    /* Visibility Toggling - Use !important to override global styles */
    .profile-dropdown .edit-mode { display: none !important; }
    .profile-dropdown .view-mode { display: flex !important; }

    .profile-dropdown.is-editing .view-mode { display: none !important; }
    .profile-dropdown.is-editing .edit-mode { display: flex !important; }
    
    .profile-dropdown .card-avatar {
        flex-shrink: 0;
        width: 64px;
        height: 64px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .profile-dropdown .inline-input {
        width: 100%;
        padding: 4px 0;
        border: none;
        border-bottom: 2px solid #3b82f6;
        font-size: 1rem;
        font-weight: 700;
        color: #1e293b;
        background: transparent;
        transition: all 0.2s;
        text-align: right;
        font-family: inherit;
    }
    .profile-dropdown .inline-input:focus {
        outline: none;
        background: rgba(59, 130, 246, 0.05);
        border-bottom-color: #2563eb;
    }
    .profile-dropdown .card-name-input {
        font-size: 1.1rem;
        font-weight: 800;
        text-align: left;
        width: 100px;
        color: #1e293b;
    }
    
    .profile-dropdown .info-value {
        word-break: break-all;
        max-width: 180px;
        text-align: right;
        font-weight: 700;
        color: #1e293b;
    }
    
    /* Button Consistency Group */
    .profile-dropdown .card-btn {
        flex: 1;
        height: 48px;
        border-radius: 12px;
        font-size: 0.95rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.2s;
        cursor: pointer;
    }
    
    .profile-dropdown .card-btn-outline {
        background: #ffffff;
        color: #003b6e;
        border: 1.5px solid #e2e8f0;
    }
    .profile-dropdown .card-btn-outline:hover {
        background: #f8fafc;
        border-color: #003b6e;
    }
    
    .profile-dropdown .card-btn-primary {
        background: #005baa;
        color: white;
        border: none;
    }
    .profile-dropdown .card-btn-primary:hover {
        background: #004a8c;
    }
    
    .profile-dropdown .card-btn-danger {
        background: #ef4444;
        color: white;
        border: none;
    }
    .profile-dropdown .card-btn-danger:hover {
        background: #dc2626;
    }
</style>

<!-- Profile Dropdown -->
<div class="profile-dropdown" id="<?= $dropdownId ?>" onclick="event.stopPropagation()">
    <div class="profile-card-header">
        <div class="card-avatar"><?= $avatarLetter ?></div>
        <div class="card-title-group">
            <div class="view-mode">
                <div class="card-name"><?= htmlspecialchars($adminName) ?></div>
                <div class="card-role"><?= htmlspecialchars($adminRoleTitle ?? 'Administrator') ?></div>
            </div>
            <div class="edit-mode">
                <div style="display: flex; gap: 8px; align-items: center;">
                    <input type="text" id="inline_first_name" class="inline-input card-name-input" placeholder="First" value="<?= htmlspecialchars($admin->first_name ?? '') ?>">
                    <input type="text" id="inline_last_name" class="inline-input card-name-input" placeholder="Last" value="<?= htmlspecialchars($admin->last_name ?? '') ?>">
                </div>
                <div class="card-role"><?= htmlspecialchars($adminRoleTitle ?? 'Administrator') ?></div>
            </div>
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
            <span class="info-value view-mode"><?= htmlspecialchars($admin->contact_number ?? ($admin->phone ?? 'N/A')) ?></span>
            <div class="info-value edit-mode" style="flex: 1;">
                <input type="text" id="inline_phone" class="inline-input" value="<?= htmlspecialchars($admin->contact_number ?? ($admin->phone ?? '')) ?>">
            </div>
        </div>
        <div class="info-row">
            <span class="info-label">Designation:</span>
            <span class="info-value"><?= htmlspecialchars($admin->designation ?? 'N/A') ?></span>
        </div>
        <div class="info-row view-mode">
            <span class="info-label">Status:</span>
            <span class="info-value">
                <span class="status-badge active"><?= htmlspecialchars($admin->status ?? 'ACTIVE') ?></span>
            </span>
        </div>
    </div>
    <div class="profile-card-footer">
        <!-- View Mode Buttons -->
        <a href="javascript:void(0)" onclick="toggleAdminProfileEdit(true)" class="card-btn card-btn-outline view-mode">
            <i class="fa-solid fa-user-pen"></i>
            <span>Edit Profile</span>
        </a>
        <a href="<?= ROOT ?>/logout" class="card-btn card-btn-danger view-mode">
            <i class="fa-solid fa-right-from-bracket"></i>
            <span>Logout</span>
        </a>

        <!-- Edit Mode Buttons -->
        <a href="javascript:void(0)" onclick="saveAdminProfileInline()" class="card-btn card-btn-primary edit-mode" id="btnSaveInline">
            <i class="fa-solid fa-check"></i>
            <span>Save</span>
        </a>
        <a href="javascript:void(0)" onclick="toggleAdminProfileEdit(false)" class="card-btn card-btn-outline edit-mode">
            <i class="fa-solid fa-xmark"></i>
            <span>Cancel</span>
        </a>
    </div>
</div>

<script>
    function toggleAdminProfileEdit(show) {
        const dropdown = document.getElementById('<?= $dropdownId ?>');
        if (show) {
            dropdown.classList.add('is-editing');
            setTimeout(() => {
                const firstInput = document.getElementById('inline_first_name');
                if (firstInput) {
                    firstInput.focus();
                    firstInput.setSelectionRange(firstInput.value.length, firstInput.value.length);
                }
            }, 50);
        } else {
            dropdown.classList.remove('is-editing');
        }
    }

    function saveAdminProfileInline() {
        const btn = document.getElementById('btnSaveInline');
        
        const data = {
            first_name: document.getElementById('inline_first_name').value,
            last_name: document.getElementById('inline_last_name').value,
            contact_number: document.getElementById('inline_phone').value,
            email: '<?= $admin->email ?? "" ?>',
            designation: '<?= $admin->designation ?? "" ?>'
        };

        const originalBtnText = btn.innerHTML;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> <span>Saving...</span>';
        btn.style.pointerEvents = 'none';

        fetch('<?= ROOT ?>/user-admin/ajaxUpdateProfile', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(result => {
            btn.innerHTML = originalBtnText;
            btn.style.pointerEvents = 'auto';
            
            if (result.success) {
                if (typeof showToast === 'function') {
                    showToast('success', 'Profile updated successfully!');
                } else if (typeof showToastNotification === 'function') {
                    showToastNotification('success', 'Profile updated successfully!');
                } else {
                    alert('Profile updated successfully!');
                }
                
                setTimeout(() => {
                    window.location.reload();
                }, 800);
            } else {
                alert(result.message || 'Failed to update profile');
            }
        })
        .catch(err => {
            btn.innerHTML = originalBtnText;
            btn.style.pointerEvents = 'auto';
            console.error('Save error:', err);
            alert('An error occurred while saving.');
        });
    }

    document.addEventListener('click', function(e) {
        const dropdown = document.getElementById('<?= $dropdownId ?>');
        const toggle = document.querySelector('[data-profile-toggle]');
        
        if (dropdown && dropdown.classList.contains('active')) {
            if (dropdown.classList.contains('is-editing') && dropdown.contains(e.target)) {
                return;
            }
            if (!dropdown.contains(e.target) && (!toggle || !toggle.contains(e.target))) {
                dropdown.classList.remove('active');
                dropdown.classList.remove('is-editing');
            }
        }
    });

    document.querySelectorAll('.inline-input').forEach(input => {
        input.addEventListener('click', e => e.stopPropagation());
    });
</script>
