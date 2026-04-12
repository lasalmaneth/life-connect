<?php
?>
<style>
    
    .modal {
        position: fixed !important;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: none !important;
        justify-content: center;
        align-items: center;
        z-index: 10000 !important;
        padding: 20px;
        box-sizing: border-box;
    }
    
    .modal.show {
        display: flex !important;
    }
    
    .modal-content {
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        max-width: 500px;
        width: 100%;
        max-height: 90vh;
        overflow-y: auto;
    }
    
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px;
        border-bottom: 1px solid #e0e0e0;
    }
    
    .modal-header h3 {
        margin: 0;
        font-size: 1.3rem;
        color: #333;
    }
    
    .modal-close {
        background: none;
        border: none;
        font-size: 28px;
        cursor: pointer;
        color: #999;
        padding: 0;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .modal-close:hover {
        color: #333;
    }
    
    .modal-footer {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        padding: 15px 20px;
        border-top: 1px solid #e0e0e0;
    }
    
    .form-group {
        margin-bottom: 15px;
    }
    
    .form-label {
        display: block;
        font-weight: 600;
        margin-bottom: 5px;
        color: #333;
        font-size: 0.9rem;
    }
    
    .form-input,
    .form-textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 0.95rem;
        font-family: inherit;
        box-sizing: border-box;
    }
    
    .form-input:focus,
    .form-textarea:focus {
        outline: none;
        border-color: #4a7c9e;
        box-shadow: 0 0 0 3px rgba(74, 124, 158, 0.1);
    }
    
    .btn {
        padding: 8px 16px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.9rem;
        font-weight: 500;
        transition: all 0.2s;
    }
    
    .btn-primary {
        background-color: #4a7c9e;
        color: white;
    }
    
    .btn-primary:hover {
        background-color: #366080;
    }
    
    .btn-secondary {
        background-color: #f0f0f0;
        color: #333;
        border: 1px solid #ddd;
    }
    
    .btn-secondary:hover {
        background-color: #e0e0e0;
    }
</style>
<!-- Add Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<?php
if (!isset($hospital_details)) {
    $emailFromDb = null;
    if (!empty($_SESSION['user_id'])) {
        try {
            $hospitalModel = new \App\Models\HospitalModel();
            $h = $hospitalModel->getHospitalByUserId((int)$_SESSION['user_id']);
            if ($h && !empty($h->user_email)) {
                $emailFromDb = (string)$h->user_email;
            }
        } catch (\Throwable $e) {
            // ignore
        }
    }

    $hospital_details = [
        'name' => $hospital_name ?? 'Hospital',
        'registration' => $_SESSION['hospital_registration'] ?? 'HOSP001',
        'role' => $_SESSION['role'] ?? 'Hospital Admin',
        'email' => $emailFromDb ?? ($_SESSION['email'] ?? 'Not specified'),
        'status' => 'Active',
        'last_login' => date('Y-m-d H:i:s')
    ];
}

// Notifications (donor-style dropdown)
if (!isset($notifications) || !isset($unread_count)) {
    $notifications = [];
    $unread_count = 0;

    if (!empty($_SESSION['user_id'])) {
        try {
            $notificationModel = new \App\Models\NotificationModel();
            $uid = (int)$_SESSION['user_id'];
            $unread_count = (int)$notificationModel->getUnreadCount($uid);
            $recent = $notificationModel->getNotificationsForUser($uid, 5);
            $notifications = json_decode(json_encode($recent), true) ?: [];
        } catch (\Throwable $e) {
            $notifications = [];
            $unread_count = 0;
        }
    }
}
?>

<div class="header">
    <div class="header-content">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <a href="<?php echo rtrim((ROOT ?? '/life-connect'), '/'); ?>/" style="text-decoration:none; display:flex; align-items:center; gap:10px;">
                <img src="<?php echo ROOT ?? '/life-connect'; ?>/public/assets/images/logo.png" alt="LifeConnect" style="height:40px; width: auto;">
                <div>
                    <strong style="display:block; font-size:1.1rem; color:#003b6e; line-height:1.2;">LifeConnect</strong>
                    <p style="margin:0; font-size:.68rem; color:#6b7280; padding-top:2px;">Hospital Portal</p>
                </div>
            </a>
        </div>
        <div class="header-right">
            <a class="nav-link" href="<?php echo rtrim((ROOT ?? '/life-connect'), '/'); ?>/" title="Home">
                <i class="fa-solid fa-house"></i>
                <span>Home</span>
            </a>

            <div class="notification-container">
                <button class="notification-bell" id="notificationBell" type="button" title="Notifications">
                    <i class="fa-solid fa-bell"></i>
                    <?php if (!empty($unread_count)): ?>
                        <span class="notification-badge"><?php echo (int)$unread_count; ?></span>
                    <?php endif; ?>
                </button>

                <div class="notification-dropdown" id="notificationDropdown">
                    <div class="dropdown-header">
                        <span>Recent Notifications</span>
                        <a href="<?php echo ROOT; ?>/hospital/notifications?mark_all_read=1">Mark all read</a>
                    </div>
                    <div class="dropdown-body">
                        <?php if (!empty($notifications)): ?>
                            <?php foreach ($notifications as $n): ?>
                                <a href="<?php echo !empty($n['action_url']) ? (ROOT . '/' . ltrim((string)$n['action_url'], '/')) : (ROOT . '/hospital/notifications'); ?>" class="notification-item <?php echo empty($n['is_read']) ? 'unread' : ''; ?>">
                                    <div class="notification-icon">
                                        <i class="fa-solid fa-circle-info"></i>
                                    </div>
                                    <div class="notification-content">
                                        <p class="notification-title"><?php echo htmlspecialchars((string)($n['title'] ?? 'Notification')); ?></p>
                                        <p class="notification-time"><?php echo !empty($n['created_at']) ? date('M d, H:i', strtotime((string)$n['created_at'])) : ''; ?></p>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="no-notifications">
                                <i class="fa-solid fa-bell-slash"></i>
                                <p>No new notifications</p>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="dropdown-footer">
                        <a href="<?php echo ROOT; ?>/hospital/notifications">View All Notifications</a>
                    </div>
                </div>
            </div>

            <div class="user-info" onclick="toggleUserDropdown()">
            <div class="user-avatar"><?php echo strtoupper(substr($hospital_details['name'], 0, 1)); ?></div>
            <div class="user-details" style="display: flex; flex-direction: column; gap: 2px;">
                <div style="font-weight: 600; font-size: 0.9rem; color: #1e293b;"><?php echo htmlspecialchars($hospital_details['name']); ?></div>
                <div class="user-id-pill">
                    <i class="fa-solid fa-id-card" style="font-size: 0.65rem;"></i>
                    <span><?php echo htmlspecialchars($hospital_details['registration']); ?></span>
                </div>
            </div>
            <!-- Redundant logout button removed to match donor portal -->
            
            <div class="user-dropdown" id="user-dropdown">
                <div class="dropdown-header">
                    <div class="user-avatar-large"><?php echo strtoupper(substr($hospital_details['name'], 0, 1)); ?></div>
                    <div>
                        <div class="user-name"><?php echo htmlspecialchars($hospital_details['name']); ?></div>
                        <div class="user-role"><?php echo htmlspecialchars($hospital_details['role']); ?></div>
                    </div>
                </div>
                <div class="dropdown-content">
                    <div class="detail-item">
                        <span class="detail-label">Hospital ID:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($hospital_details['registration']); ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Email:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($hospital_details['email']); ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Address:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($hospital_details['address'] ?? 'Not specified'); ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Status:</span>
                        <span class="detail-value status-active"><?php echo htmlspecialchars($hospital_details['status'] ?? 'Active'); ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Last Login:</span>
                        <span class="detail-value"><?php echo isset($hospital_details['last_login']) ? date('M d, Y H:i', strtotime($hospital_details['last_login'])) : 'First login'; ?></span>
                    </div>
                </div>
                <div class="dropdown-footer">
                    <button class="btn btn-secondary btn-small" onclick="editProfile()">Edit Profile</button>
                    <button class="btn btn-danger btn-small" onclick="logout()">Logout</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const bell = document.getElementById('notificationBell');
    const dropdown = document.getElementById('notificationDropdown');
    if (!bell || !dropdown) return;

    bell.addEventListener('click', function (e) {
        e.stopPropagation();
        dropdown.classList.toggle('show');
    });

    dropdown.addEventListener('click', function (e) {
        e.stopPropagation();
    });

    document.addEventListener('click', function () {
        dropdown.classList.remove('show');
    });
});
</script>


<div class="modal" id="edit-profile-modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Edit Hospital Profile</h3>
            <button class="modal-close" onclick="closeEditProfileModal()">×</button>
        </div>
        <form method="POST" action="<?php echo ROOT; ?>/hospital" id="edit-profile-form" style="padding: 20px;">
            <input type="hidden" name="action" value="update_profile">
            
            <div class="form-group">
                <label class="form-label">Hospital Name</label>
                <input type="text" class="form-input" id="profile-name" name="name" value="<?php echo htmlspecialchars($hospital_details['name']); ?>" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" class="form-input" id="profile-email" name="email" value="<?php echo htmlspecialchars($hospital_details['email']); ?>" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Phone Number</label>
                <input type="tel" class="form-input" id="profile-phone" name="phone" value="<?php echo htmlspecialchars($hospital_details['phone'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label class="form-label">Address</label>
                <textarea class="form-textarea" id="profile-address" name="address" style="resize: vertical; min-height: 80px;"><?php echo htmlspecialchars($hospital_details['address'] ?? ''); ?></textarea>
            </div>
            
            <div class="form-group">
                <label class="form-label">District</label>
                <input type="text" class="form-input" id="profile-district" name="district" value="<?php echo htmlspecialchars($hospital_details['district'] ?? ''); ?>">
            </div>
            
            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                <button type="button" class="btn btn-secondary" onclick="closeEditProfileModal()">Cancel</button>
                <button type="submit" class="btn btn-primary" onclick="submitEditProfile()">Save Changes</button>
            </div>
        </form>
    </div>
</div>
<script>
    function closeEditProfileModal() {
        const modal = document.getElementById('edit-profile-modal');
        if (modal) {
            modal.classList.remove('show');
        }
    }

    function submitEditProfile() {
        const form = document.getElementById('edit-profile-form');
        if (form) {
            form.submit();
        }
    }

        document.addEventListener('click', function(event) {
        const modal = document.getElementById('edit-profile-modal');
        if (modal && event.target === modal) {
            closeEditProfileModal();
        }
    });
</script>

