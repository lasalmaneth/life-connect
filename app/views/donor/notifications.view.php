<?php
include __DIR__ . '/inc/header.view.php';
include __DIR__ . '/inc/sidebar.view.php';
?>

<main class="d-content">
        <header class="d-content__header">
            <div class="header-with-actions">
                <div>
                    <h2><i class="fas fa-bell"></i> Notifications</h2>
                    <p>Stay updated with the latest information from hospitals and administration.</p>
                </div>
                <div class="header-actions-btns">
                    <a href="<?= ROOT ?>/donor/notifications?mark_all_read=1" class="d-btn d-btn--outline d-btn--sm">
                        <i class="fas fa-check-double"></i> Mark all as read
                    </a>
                </div>
            </div>
        </header>

        <div class="d-content__body">
            <div class="notification-panel">
                <?php if(!empty($notifications)): ?>
                    <div class="notification-list">
                        <?php foreach($notifications as $n): ?>
                            <div class="notification-card <?= !$n['is_read'] ? 'unread' : '' ?>" id="notif-<?= $n['id'] ?>">
                                <div class="notif-status-indicator"></div>
                                <div class="notif-icon">
                                    <?php if($n['sender_type'] == 'HOSPITAL'): ?>
                                        <i class="fas fa-hospital-user"></i>
                                    <?php else: ?>
                                        <i class="fas fa-user-shield"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="notif-details">
                                    <div class="notif-header">
                                        <div class="notif-title-group">
                                            <div class="notif-title-row">
                                              <h4 class="notif-title"><?= esc($n['title']) ?></h4>
                                              <?php if(!$n['is_read']): ?>
                                                  <span class="new-badge">NEW</span>
                                              <?php endif; ?>
                                            </div>
                                            <span class="sender-info">
                                                from <?= $n['sender_type'] == 'HOSPITAL' ? esc($n['hospital_name'] ?? 'Hospital') : 'Life Connect Admin' ?>
                                            </span>
                                        </div>
                                        <div class="notif-time-group">
                                          <span class="notif-time"><?= date('M d, Y', strtotime($n['created_at'])) ?></span>
                                          <span class="notif-exact-time"><?= date('H:i', strtotime($n['created_at'])) ?></span>
                                        </div>
                                    </div>
                                    <div class="notif-message">
                                        <?= esc($n['message']) ?>
                                    </div>
                                    <div class="notif-actions">
                                        <div class="main-actions">
                                            <?php if(!empty($n['action_url'])): ?>
                                                <a href="<?= ROOT ?>/<?= $n['action_url'] ?>" class="d-btn d-btn--primary d-btn--sm">View Details</a>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <div class="secondary-actions">
                                            <?php if(!$n['is_read']): ?>
                                                <button onclick="markAsRead(<?= $n['id'] ?>)" class="text-btn success-btn">
                                                    <i class="fas fa-check"></i> Mark as read
                                                </button>
                                            <?php endif; ?>
                                            <button onclick="deleteNotification(<?= $n['id'] ?>)" class="text-btn delete-btn">
                                                <i class="fas fa-trash-alt"></i> Delete
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <div class="empty-state__icon"><i class="fas fa-bell-slash"></i></div>
                        <h3>No notifications found</h3>
                        <p>When you receive updates about your donations or appointments, they will appear here.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

<script>
async function markAsRead(id) {
    const formData = new FormData();
    formData.append('id', id);

    try {
        const response = await fetch('<?= ROOT ?>/donor/markNotificationRead', {
            method: 'POST',
            body: formData
        });
        const data = await response.json();
        if(data.success) {
            document.getElementById('notif-' + id).classList.remove('unread');
            // Hide the mark as read button
            const btn = document.querySelector(`#notif-${id} .text-btn:not(.delete)`);
            if(btn) btn.style.display = 'none';
            
            // Optionally update the badge counts header/sidebar if they are in sync
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

async function deleteNotification(id) {
    if(!confirm('Are you sure you want to delete this notification?')) return;

    const formData = new FormData();
    formData.append('id', id);

    try {
        const response = await fetch('<?= ROOT ?>/donor/deleteNotification', {
            method: 'POST',
            body: formData
        });
        const data = await response.json();
        if(data.success) {
            const card = document.getElementById('notif-' + id);
            card.style.opacity = '0';
            setTimeout(() => card.remove(), 300);
        }
    } catch (error) {
        console.error('Error:', error);
    }
}
</script>

<?php include __DIR__ . '/inc/footer.view.php'; ?>
