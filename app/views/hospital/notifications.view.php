<?php
/**
 * Hospital Portal — Notifications Page
 * Clean content-only view (no donor portal wrapper)
 */

// Session check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'HOSPITAL') {
    redirect('login');
}

$userId = (int)$_SESSION['user_id'];
$notificationModel = new \App\Models\NotificationModel();

// Get notifications
$notifications = $notificationModel->getNotificationsForUser($userId) ?: [];
$unreadCount = $notificationModel->getUnreadCount($userId) ?: 0;

// Convert stdClass to array
if (is_array($notifications)) {
    foreach ($notifications as $i => $row) {
        if (is_object($row)) {
            $notifications[$i] = (array)$row;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="<?php echo ROOT ?? '/life-connect'; ?>/public/assets/css/hospital/hospital.css">
    <title>Notifications - Hospital Portal | LifeConnect</title>
    <style>
        body {
            background: #f9fafb;
        }
        
        .notifications-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        .notification-header-section {
            background: linear-gradient(135deg, #e0f2fe 0%, #cce5ff 100%);
            border-radius: 10px;
            padding: 2rem;
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 2rem;
        }

        .notification-header-section h2 {
            margin: 0;
            color: var(--primary-text-color);
            font-size: 1.8rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .back-btn {
            background: white;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-size: 1.1rem;
            transition: all 0.2s;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .back-btn:hover {
            background: var(--gray-bg-color);
            transform: translateX(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .notification-header-subtitle {
            color: var(--secondary-text-color);
            font-size: 0.95rem;
            margin: 0;
            line-height: 1.4;
        }

        .notification-header-left {
            flex: 1;
        }

        .notification-header-actions {
            display: flex;
            gap: 0.8rem;
        }

        .mark-all-btn {
            background: white;
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            padding: 0.7rem 1.5rem;
            border-radius: 50px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            white-space: nowrap;
        }

        .mark-all-btn:hover {
            background: var(--gray-bg-color);
        }

        .notification-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .notification-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 1.2rem;
            display: flex;
            gap: 1rem;
            transition: all 0.3s ease;
        }

        .notification-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transform: translateY(-1px);
        }

        .notification-card.unread {
            background: linear-gradient(135deg, #f0f7fd 0%, #ffffff 100%);
            border-left: 4px solid var(--primary-color);
        }

        .notification-icon {
            width: 40px;
            height: 40px;
            min-width: 40px;
            border-radius: 10px;
            background: #f0f7fd;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .notification-card.unread .notification-icon {
            background: #e0f2fe;
            color: var(--primary-color);
        }

        .notification-content {
            flex: 1;
        }

        .notification-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 0.4rem;
        }

        .notification-title {
            margin: 0;
            font-size: 1rem;
            font-weight: 700;
            color: var(--primary-text-color);
        }

        .notification-badges {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .badge {
            background: #ef4444;
            color: white;
            padding: 0.3rem 0.6rem;
            border-radius: 4px;
            font-size: 0.7rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .notification-message {
            margin: 0.4rem 0;
            font-size: 0.95rem;
            color: var(--secondary-text-color);
            line-height: 1.4;
        }

        .notification-meta {
            display: flex;
            gap: 1rem;
            font-size: 0.8rem;
            color: #9ca3af;
            margin-top: 0.6rem;
            margin-bottom: 0.8rem;
        }

        .notification-actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .btn {
            background: white;
            border: 1px solid #d1d5db;
            color: var(--primary-color);
            padding: 0.4rem 0.8rem;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        .btn:hover {
            background: var(--gray-bg-color);
            border-color: var(--primary-color);
        }

        .btn--danger {
            color: #ef4444;
            border-color: #fee2e2;
        }

        .btn--danger:hover {
            background: #fee2e2;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 1rem;
            background: white;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
        }

        .empty-icon {
            font-size: 3.5rem;
            color: #d1d5db;
            margin-bottom: 1rem;
        }

        .empty-text {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--primary-text-color);
            margin-bottom: 0.5rem;
        }

        .empty-subtext {
            font-size: 0.95rem;
            color: var(--secondary-text-color);
        }
    </style>
</head>

<body>
    <div class="notifications-container">
        <!-- Header Section -->
        <div class="notification-header-section">
            <div class="notification-header-left">
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.5rem;">
                    <button onclick="history.back()" class="back-btn" title="Go back">
                        <i class="fas fa-arrow-left"></i>
                    </button>
                    <h2>
                        <i class="fas fa-bell"></i> Notifications
                    </h2>
                </div>
                <p class="notification-header-subtitle">
                    Stay updated with the latest information from hospitals and administration.
                </p>
            </div>
            <div class="notification-header-actions">
                <?php if (!empty($notifications) && $unreadCount > 0): ?>
                    <button class="mark-all-btn" onclick="markAllAsRead()">
                        <i class="fas fa-check-double"></i> Mark all as read
                    </button>
                <?php endif; ?>
            </div>
        </div>

        <!-- Notifications Content -->
        <?php if (!empty($notifications) && is_array($notifications)): ?>

            <div class="notification-list">
                <?php foreach ($notifications as $notification): 

                    $id = $notification['id'] ?? null;
                    $title = htmlspecialchars($notification['title'] ?? 'Notification');
                    $message = htmlspecialchars($notification['message'] ?? '');
                    $type = htmlspecialchars($notification['type'] ?? 'GENERAL');
                    $is_read = isset($notification['is_read']) ? (int)$notification['is_read'] : 0;
                    $created_at = $notification['created_at'] ?? date('Y-m-d H:i:s');
                    $sender = htmlspecialchars($notification['hospital_name'] ?? $notification['sender_id'] ?? 'System');
                    $action_url = $notification['action_url'] ?? null;

                    // Format timestamp
                    try {
                        $createdTime = new DateTime($created_at);
                        $now = new DateTime();
                        $diff = $now->diff($createdTime);

                        if ($diff->days == 0) {
                            if ($diff->h == 0) {
                                $timeAgo = $diff->i . ' minutes ago';
                            } else {
                                $timeAgo = $diff->h . ' hours ago';
                            }
                        } else if ($diff->days == 1) {
                            $timeAgo = 'Yesterday';
                        } else if ($diff->days < 7) {
                            $timeAgo = $diff->days . ' days ago';
                        } else {
                            $timeAgo = $createdTime->format('M d, Y');
                        }
                    } catch (Exception $e) {
                        $timeAgo = 'Recently';
                    }
                ?>

                    <div class="notification-card <?= !$is_read ? 'unread' : '' ?>" id="notif-<?= $id ?>">
                        <div class="notification-icon">
                            <i class="fas fa-bell"></i>
                        </div>
                        <div class="notification-content">
                            <div class="notification-header">
                                <h3 class="notification-title"><?= $title ?></h3>
                                <div class="notification-badges">
                                    <?php if (!$is_read): ?>
                                        <span class="badge">NEW</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <p class="notification-message"><?= $message ?></p>
                            <div class="notification-meta">
                                <span><i class="fas fa-clock"></i> <?= $timeAgo ?></span>
                                <span><i class="fas fa-hospital"></i> <?= $sender ?></span>
                            </div>
                            <div class="notification-actions">
                                <?php if ($action_url): ?>
                                    <a href="<?= htmlspecialchars($action_url) ?>" class="btn">
                                        <i class="fas fa-arrow-right"></i> View Details
                                    </a>
                                <?php endif; ?>
                                <?php if (!$is_read): ?>
                                    <button type="button" class="btn" onclick="markAsRead(<?= $id ?>)">
                                        <i class="fas fa-envelope-open"></i> Mark as Read
                                    </button>
                                <?php endif; ?>
                                <button type="button" class="btn btn--danger" onclick="deleteNotification(<?= $id ?>)">
                                    <i class="fas fa-trash-alt"></i> Delete
                                </button>
                            </div>
                        </div>
                    </div>

                <?php endforeach; ?>
            </div>

        <?php else: ?>

            <!-- Empty State -->
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-bell-slash"></i>
                </div>
                <div class="empty-text">No notifications found</div>
                <div class="empty-subtext">
                    When you receive updates about your donations or appointments, they will appear here.
                </div>
            </div>

        <?php endif; ?>
    </div>

    <script>
        async function markAsRead(id) {
            try {
                const response = await fetch('<?= ROOT ?>/hospital/markNotificationRead', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: id })
                });

                if (response.ok) {
                    const card = document.getElementById('notif-' + id);
                    if (card) {
                        card.classList.remove('unread');
                        const btn = event.target.closest('button');
                        if (btn) btn.style.display = 'none';
                    }
                }
            } catch (error) {
                console.error('Error:', error);
            }
        }

        async function markAllAsRead() {
            try {
                const response = await fetch('<?= ROOT ?>/hospital/markAllNotificationsRead', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' }
                });

                if (response.ok) {
                    document.querySelectorAll('.notification-card.unread').forEach(card => {
                        card.classList.remove('unread');
                    });
                    location.reload();
                }
            } catch (error) {
                console.error('Error:', error);
            }
        }

        async function deleteNotification(id) {
            if (!confirm('Delete this notification?')) return;

            try {
                const response = await fetch('<?= ROOT ?>/hospital/deleteNotification', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: id })
                });

                if (response.ok) {
                    const card = document.getElementById('notif-' + id);
                    if (card) {
                        card.style.opacity = '0';
                        card.style.transform = 'translateX(-10px)';
                        setTimeout(() => card.remove(), 300);
                    }
                }
            } catch (error) {
                console.error('Error:', error);
            }
        }
    </script>
</body>

</html>
