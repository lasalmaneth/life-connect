<?php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo ROOT ?? '/life-connect'; ?>/public/assets/css/hospital/hospital.css">
    <title>Notifications - Hospital Portal - LifeConnect</title>
    <style>
        .h-notif-panel { display: flex; flex-direction: column; gap: 12px; }
        .h-notif-card {
            display: flex;
            gap: 12px;
            padding: 14px;
            border: 1px solid var(--border-color);
            background: var(--white-color);
            border-radius: 14px;
        }
        .h-notif-card.unread { background: #eff6ff; }
        .h-notif-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: #dbeafe;
            color: #1e40af;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .h-notif-title { font-weight: 900; color: var(--primary-text-color); margin: 0; }
        .h-notif-meta { margin-top: 3px; font-size: .82rem; font-weight: 700; color: var(--secondary-text-color); }
        .h-notif-msg { margin-top: 10px; color: var(--primary-text-color); font-weight: 600; white-space: pre-line; }
        .h-notif-top { display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; }
        .h-notif-new {
            font-size: 0.72rem;
            font-weight: 900;
            padding: 3px 8px;
            border-radius: 999px;
            background: var(--primary-color);
            color: var(--white-color);
            flex-shrink: 0;
        }
        .h-notif-actions { margin-top: 12px; display: flex; justify-content: flex-end; }
        .h-notif-actions a { text-decoration: none; }
    </style>
</head>
<body>

<?php
    $current_page = 'notifications';
    require_once __DIR__ . '/header.php';
?>

<div class="container">
    <div class="main-content">
        <?php require_once __DIR__ . '/sidebar.php'; ?>

        <div class="content-area">
            <div class="content-section" style="display:block;">
                <div class="content-header" style="display:flex; justify-content:space-between; align-items:flex-start; gap: 12px;">
                    <div>
                        <h2 style="margin:0;"><i class="fa-solid fa-bell"></i> Notifications</h2>
                        <p style="margin:6px 0 0 0;">Recent updates and messages for your hospital account.</p>
                    </div>
                    <div>
                        <a class="btn btn-secondary" href="<?php echo ROOT; ?>/hospital/notifications?mark_all_read=1">
                            <i class="fa-solid fa-check-double"></i> Mark all read
                        </a>
                    </div>
                </div>

                <div class="content-body">
                    <?php if (!empty($notifications)): ?>
                        <div class="h-notif-panel">
                            <?php foreach ($notifications as $n): ?>
                                <div class="h-notif-card <?php echo empty($n['is_read']) ? 'unread' : ''; ?>">
                                    <div class="h-notif-icon"><i class="fa-solid fa-circle-info"></i></div>
                                    <div style="flex:1; min-width:0;">
                                        <div class="h-notif-top">
                                            <div style="min-width:0;">
                                                <p class="h-notif-title"><?php echo htmlspecialchars((string)($n['title'] ?? 'Notification')); ?></p>
                                                <div class="h-notif-meta">
                                                    <?php echo !empty($n['created_at']) ? date('M d, Y H:i', strtotime((string)$n['created_at'])) : ''; ?>
                                                </div>
                                            </div>
                                            <?php if (empty($n['is_read'])): ?>
                                                <span class="h-notif-new">NEW</span>
                                            <?php endif; ?>
                                        </div>

                                        <?php if (!empty($n['message'])): ?>
                                            <div class="h-notif-msg"><?php echo htmlspecialchars((string)$n['message']); ?></div>
                                        <?php endif; ?>

                                        <?php if (!empty($n['action_url'])): ?>
                                            <div class="h-notif-actions">
                                                <a class="btn btn-primary" href="<?php echo ROOT . '/' . ltrim((string)$n['action_url'], '/'); ?>">View Details</a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="no-notifications">
                            <i class="fa-solid fa-bell-slash"></i>
                            <p>No notifications found</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
    require_once __DIR__ . '/footer.php';
?>
</body>
</html>
