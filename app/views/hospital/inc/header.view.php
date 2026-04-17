<?php
// Ensure notifications and unread_count are set
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

// Ensure hospital_details are safe
if (!isset($hospital_details)) {
    $hospital_details = [
        'name' => $_SESSION['hospital_name'] ?? 'Hospital',
        'role' => $_SESSION['role'] ?? 'HOSPITAL',
        'registration' => $_SESSION['hospital_registration'] ?? 'REG-HSP-0',
        'email' => $_SESSION['email'] ?? 'info@lifeconnect.lk',
        'status' => 'Active',
        'last_login' => date('Y-m-d H:i:s')
    ];
}

// Further safety for specific keys
$h_name = $hospital_details['name'] ?? ($_SESSION['hospital_name'] ?? 'Hospital');
$h_role = $hospital_details['role'] ?? ($_SESSION['role'] ?? 'Medical Coordinator');
$h_reg = $hospital_details['registration'] ?? ($_SESSION['hospital_registration'] ?? 'N/A');
$h_avatar = strtoupper(substr((string)$h_name, 0, 1) ?: 'H');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="<?php echo ROOT ?? '/life-connect'; ?>/public/assets/css/hospital/hospital.css">
    <link rel="stylesheet" href="<?php echo ROOT ?? '/life-connect'; ?>/public/assets/css/hospital/stories.css">
    
    <title>Hospital Management - LifeConnect</title>
</head>

<body>
    <div class="header">
        <div class="header-content">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <a href="<?php echo rtrim((ROOT ?? '/life-connect'), '/'); ?>/"
                    style="text-decoration: none; display: flex; align-items: center; gap: 10px;">
                    <img src="<?php echo ROOT ?? '/life-connect'; ?>/public/assets/images/logo.png" alt="LifeConnect"
                        style="height: 40px; width: auto;">
                    <div>
                        <strong
                            style="display:block; font-size:1.1rem; color:#003b6e; line-height:1.2;">LifeConnect</strong>
                        <p style="margin:0; font-size:.68rem; color:#6b7280; padding-top:2px;">Hospital Portal</p>
                    </div>
                </a>
            </div>
            <div class="header-right">
                <a class="nav-link" href="<?php echo rtrim((ROOT ?? '/life-connect'), '/'); ?>/" title="Home">
                    <i class="fa-solid fa-house"></i>
                </a>

                <div class="notification-container">
                    <button class="notification-bell" onclick="toggleNotifications()" title="Notifications">
                        <i class="fa-solid fa-bell"></i>
                        <?php if ($unread_count > 0): ?>
                            <span class="notification-badge"><?php echo $unread_count; ?></span>
                        <?php endif; ?>
                    </button>

                    <div class="notification-dropdown" id="notification-dropdown">
                        <div class="dropdown-header">
                            <h3>Notifications</h3>
                            <?php if ($unread_count > 0): ?>
                                <button class="mark-all-btn" onclick="markAllAsRead()">Mark all as read</button>
                            <?php endif; ?>
                        </div>
                        <div class="notification-list" id="notification-list">
                            <?php if (empty($notifications)): ?>
                                <div class="empty-notifications">
                                    <i class="fa-solid fa-bell-slash"></i>
                                    <p>No new notifications</p>
                                </div>
                            <?php else: ?>
                                <?php foreach ($notifications as $notif): ?>
                                    <div class="notification-item <?php echo $notif['is_read'] ? '' : 'unread'; ?>"
                                        onclick="markAsRead(<?php echo $notif['id']; ?>)">
                                        <div class="notif-icon">
                                            <i class="fa-solid fa-circle-info"></i>
                                        </div>
                                        <div class="notif-content">
                                            <div class="notif-title"><?php echo htmlspecialchars($notif['title']); ?></div>
                                            <div class="notif-message"><?php echo htmlspecialchars($notif['message']); ?></div>
                                            <div class="notif-time"><?php echo date('M d, H:i', strtotime($notif['created_at'])); ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <div class="dropdown-footer">
                            <a href="<?php echo ROOT; ?>/hospital/notifications">View all notifications</a>
                        </div>
                    </div>
                </div>

                <div class="user-info">
                    <div class="user-avatar" onclick="toggleUserDropdown()">
                        <?php echo $h_avatar; ?>
                    </div>
                    <div class="user-details" onclick="toggleUserDropdown()">
                        <div style="font-weight: 700; font-size: 0.85rem; color: #1e293b;">
                            <?php echo htmlspecialchars($h_name); ?>
                        </div>
                        <div style="font-size: 0.7rem; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">
                            <?php echo htmlspecialchars($h_role); ?>
                        </div>
                        <div style="font-size: 0.7rem; color: #64748b;">ID:
                            <?php echo htmlspecialchars($h_reg); ?>
                        </div>
                    </div>
                    <div class="user-actions">
                        <button class="btn-logout" onclick="logout()" title="Logout">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                <polyline points="16,17 21,12 16,7"></polyline>
                                <line x1="21" y1="12" x2="9" y2="12"></line>
                            </svg>
                        </button>
                    </div>

                    <!-- User Details Dropdown -->
                    <div class="user-dropdown" id="user-dropdown">
                        <div class="dropdown-header">
                            <div class="user-avatar-large">
                                <?php echo $h_avatar; ?>
                            </div>
                            <div>
                                <div class="user-name"><?php echo htmlspecialchars($h_name); ?></div>
                                <div class="user-role"><?php echo htmlspecialchars($h_role); ?></div>
                            </div>
                        </div>
                        <div class="dropdown-content">
                            <div class="detail-item">
                                <span class="detail-label">Hospital ID:</span>
                                <span
                                    class="detail-value"><?php echo htmlspecialchars($h_reg); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Email:</span>
                                <span
                                    class="detail-value"><?php echo htmlspecialchars($hospital_details['email'] ?? 'Not specified'); ?></span>
                            </div>
                            <?php
                            $displayAddress = $hospital_details['address'] ?? 'Not specified';
                            $displayPhone = $hospital_details['phone'] ?? 'Not specified';

                            // If address contains our special [Phone] marker, parse it
                            if ($displayAddress && strpos($displayAddress, '[Phone]:') !== false) {
                                $parts = explode(' | [Address]: ', $displayAddress);
                                $displayPhone = str_replace('[Phone]: ', '', $parts[0]);
                                $displayAddress = $parts[1] ?? 'Not specified';
                            }
                            ?>
                            <div class="detail-item">
                                <span class="detail-label">Address:</span>
                                <span class="detail-value"><?php echo htmlspecialchars($displayAddress); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Phone:</span>
                                <span class="detail-value"><?php echo htmlspecialchars($displayPhone); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Status:</span>
                                <span
                                    class="detail-value status-active"><?php echo htmlspecialchars($hospital_details['status']); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Last Login:</span>
                                <span
                                    class="detail-value"><?php echo date('M d, Y H:i', strtotime($hospital_details['last_login'])); ?></span>
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
    </div>
