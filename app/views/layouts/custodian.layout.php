<?php
/**
 * Custodian Portal — Layout Wrapper
 * ─────────────────────────────────────────────────────────────────────────────
 * HOW TO USE IN EACH VIEW FILE:
 *
 *   <?php
 *   $page_title    = 'Dashboard';
 *   $active_page   = 'dashboard';
 *   // any other page-specific vars...
 *
 *   ob_start();
 *   ?>
 *   <!-- page content HTML here -->
 *   <?php
 *   $page_content = ob_get_clean();
 *   require dirname(__DIR__) . '/layouts/custodian.layout.php';
 *   ?>
 *
 * Expected variables (injected by controller via extract()):
 *   $page_title          (string)  — <title> tag
 *   $active_page         (string)  — sidebar active key
 *   $custodian           (object)  — custodian record from model
 *   $custodian_name      (string)  — pre-built full name
 *   $custodian_id_display(string)  — e.g. "CID-00042"
 *   $page_content        (string)  — output-buffered page HTML
 * ─────────────────────────────────────────────────────────────────────────────
 */

if (session_status() === PHP_SESSION_NONE) session_start();

// Auth failsafe (controller should have already checked)
if (empty($_SESSION['user_id'])) {
    header('Location: ' . ROOT . '/login');
    exit;
}

// Fallback values if controller did not inject them
$page_title           = $page_title          ?? 'Custodian Portal';
$active_page          = $active_page         ?? 'dashboard';
$custodian_name       = $custodian_name      ?? ($_SESSION['user_name'] ?? 'Custodian');
$custodian_id_display = $custodian_id_display ?? 'CID-00000';
$page_content         = $page_content        ?? '';

// Flash messages
$success_message = $_SESSION['success_message'] ?? null;
$error_message   = $_SESSION['error_message']   ?? null;
unset($_SESSION['success_message'], $_SESSION['error_message']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title><?= htmlspecialchars($page_title) ?> | LifeConnect Custodian Portal</title>

    <!-- Font Awesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
          crossorigin="anonymous" referrerpolicy="no-referrer">

    <!-- Custodian CSS -->
    <link rel="stylesheet" href="<?= ROOT ?>/public/assets/css/custodian/main.css?v=<?= time() ?>">
    
    <!-- Extra Page Specific CSS -->
    <?php if (isset($extra_css) && is_array($extra_css)): ?>
        <?php foreach ($extra_css as $css): ?>
            <link rel="stylesheet" href="<?= ROOT ?>/public/assets/css/<?= $css ?>?v=<?= time() ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>

<!-- ════════════════════════════════════════════════════════
     FLASH MESSAGES (server-side)
════════════════════════════════════════════════════════ -->
<?php if ($success_message): ?>
    <div class="cp-flash-message" style="
        position:fixed; top:80px; right:20px; z-index:9999;
        display:flex; align-items:center; gap:.75rem;
        background:#fff; border:1px solid #e5e7eb; border-radius:12px;
        padding:.9rem 1.25rem; box-shadow:0 10px 40px rgba(0,0,0,.12);
        max-width:360px; transition:all .35s ease;">
        <i class="fas fa-check-circle" style="color:#10b981; font-size:1.25rem;"></i>
        <span style="font-size:.85rem; font-weight:600; color:#1e293b;">
            <?= htmlspecialchars($success_message) ?>
        </span>
    </div>
<?php endif; ?>

<?php if ($error_message): ?>
    <div class="cp-flash-message" style="
        position:fixed; top:80px; right:20px; z-index:9999;
        display:flex; align-items:center; gap:.75rem;
        background:#fff; border:1px solid #e5e7eb; border-radius:12px;
        padding:.9rem 1.25rem; box-shadow:0 10px 40px rgba(0,0,0,.12);
        max-width:360px; transition:all .35s ease;">
        <i class="fas fa-exclamation-circle" style="color:#ef4444; font-size:1.25rem;"></i>
        <span style="font-size:.85rem; font-weight:600; color:#1e293b;">
            <?= htmlspecialchars($error_message) ?>
        </span>
    </div>
<?php endif; ?>

<!-- ════════════════════════════════════════════════════════
     TOPBAR
════════════════════════════════════════════════════════ -->
<?php include __DIR__ . '/../custodian/partials/topbar.php'; ?>

<!-- ════════════════════════════════════════════════════════
     SIDEBAR OVERLAY (mobile)
════════════════════════════════════════════════════════ -->
<div class="cp-sidebar-overlay" id="cp-sidebar-overlay" onclick="closeSidebar()"></div>

<!-- ════════════════════════════════════════════════════════
     SHELL (sidebar + content)
════════════════════════════════════════════════════════ -->
<div class="cp-shell">

    <!-- Sidebar -->
    <?php include __DIR__ . '/../custodian/partials/sidebar.php'; ?>

    <!-- Main content -->
    <main class="cp-content-area" id="cp-main-content">
        <?= $page_content ?>
    </main>

</div><!-- /.cp-shell -->

<!-- ════════════════════════════════════════════════════════
     PREMIUM NOTIFICATION MODAL (cpNotify)
════════════════════════════════════════════════════════ -->
<div class="cp-notify-overlay" id="cp-notify-overlay">
    <div class="cp-notify-card">
        <div class="cp-notify-icon" id="cp-notify-icon">
            <i class="fas fa-info"></i>
        </div>
        <h3 class="cp-notify-title" id="cp-notify-title">Notification</h3>
        <p class="cp-notify-msg" id="cp-notify-msg">Message goes here.</p>
        <div class="cp-notify-btns">
            <button class="cp-notify-btn cp-notify-btn--cancel" id="cp-notify-cancel-btn" style="display:none;">Cancel</button>
            <button class="cp-notify-btn cp-notify-btn--confirm" id="cp-notify-confirm-btn">Confirm</button>
        </div>
    </div>
</div>

<script>
/**
 * cpNotify: Shared Notification Singleton
 */
const cpNotify = {
    overlay: document.getElementById('cp-notify-overlay'),
    icon: document.getElementById('cp-notify-icon'),
    iconI: document.querySelector('#cp-notify-icon i'),
    title: document.getElementById('cp-notify-title'),
    msg: document.getElementById('cp-notify-msg'),
    confirmBtn: document.getElementById('cp-notify-confirm-btn'),
    cancelBtn: document.getElementById('cp-notify-cancel-btn'),
    resolve: null,

    init() {
        this.confirmBtn.addEventListener('click', () => this.close(true));
        this.cancelBtn.addEventListener('click', () => this.close(false));
        this.overlay.addEventListener('click', (e) => {
            if (e.target === this.overlay) this.close(false);
        });
    },

    show({ title, message, type = 'confirm', confirmText = 'OK', cancelText = 'Cancel', icon = 'fa-info' }) {
        return new Promise((res) => {
            this.resolve = res;
            
            this.title.innerText = title;
            this.msg.innerText = message;
            this.confirmBtn.innerText = confirmText;
            this.cancelBtn.innerText = cancelText;
            
            // Set icon and style
            this.iconI.className = 'fas ' + icon;
            this.icon.className = 'cp-notify-icon cp-notify-icon--' + type;
            
            // Buttons visibility
            this.cancelBtn.style.display = (type === 'confirm') ? 'block' : 'none';
            this.confirmBtn.className = 'cp-notify-btn cp-notify-btn--' + (type === 'error' ? 'danger' : 'confirm');

            this.overlay.classList.add('show');
        });
    },

    alert(title, message, type = 'success') {
        const iconMap = { 'success': 'fa-check', 'error': 'fa-exclamation-triangle', 'warning': 'fa-exclamation-circle' };
        return this.show({ 
            title, 
            message, 
            type, 
            confirmText: 'Dismiss', 
            icon: iconMap[type] || 'fa-info' 
        });
    },

    confirm(title, message, isDanger = false) {
        return this.show({ 
            title, 
            message, 
            type: 'confirm', 
            confirmText: 'Yes, Proceed', 
            cancelText: 'Cancel',
            icon: isDanger ? 'fa-heart-crack' : 'fa-question-circle'
        });
    },

    close(val) {
        this.overlay.classList.remove('show');
        if (this.resolve) {
            this.resolve(val);
            this.resolve = null;
        }
    }
};

document.addEventListener('DOMContentLoaded', () => cpNotify.init());
</script>

<!-- ════════════════════════════════════════════════════════
     FOOTER / SCRIPTS
════════════════════════════════════════════════════════ -->
<?php include __DIR__ . '/../custodian/partials/footer.php'; ?>

</body>
</html>
