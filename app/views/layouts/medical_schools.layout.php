<?php
/**
 * Medical School Portal — Layout Wrapper
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
 *   require dirname(__DIR__) . '/layouts/medical_schools.layout.php';
 *   ?>
 *
 * Expected variables (injected by controller via extract()):
 *   $page_title     (string)  — <title> tag
 *   $active_page    (string)  — sidebar active key
 *   $school         (object)  — school record from model
 *   $school_name    (string)  — pre-built school name
 *   $page_content   (string)  — output-buffered page HTML
 * ─────────────────────────────────────────────────────────────────────────────
 */

if (session_status() === PHP_SESSION_NONE) session_start();

// Auth failsafe
if (empty($_SESSION['user_id'])) {
    header('Location: ' . ROOT . '/login');
    exit;
}

// Fallback values
$page_title  = $page_title  ?? $pageTitle ?? 'Medical School Portal';
$active_page = $active_page ?? $activePage ?? 'dashboard';
// Derive school_name from the $school object passed by the controller
$school_name = $school_name ?? ($school->school_name ?? ($_SESSION['user_name'] ?? 'Medical School'));
$page_content = $page_content ?? '';

// Flash messages
$success_message = $_SESSION['flash_success'] ?? $_SESSION['success_message'] ?? null;
$error_message   = $_SESSION['flash_error'] ?? $_SESSION['error_message'] ?? null;
unset($_SESSION['flash_success'], $_SESSION['flash_error'], $_SESSION['success_message'], $_SESSION['error_message']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title><?= htmlspecialchars($page_title) ?> | LifeConnect Medical School Portal</title>

    <!-- Font Awesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
          crossorigin="anonymous" referrerpolicy="no-referrer">

    <!-- Custodian CSS (shared design system — cp- prefix) -->
    <link rel="stylesheet" href="<?= ROOT ?>/public/assets/css/custodian/main.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= ROOT ?>/public/assets/css/custodian/drawer.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= ROOT ?>/public/assets/css/medicalschools/medicalschools.css?v=<?= time() ?>">
</head>
<body>

<?php if ($success_message): ?>
    <div class="cp-popup show" id="ms-flash-success">
        <div class="cp-popup__icon">
            <i class="fas fa-check"></i>
        </div>
        <div>
            <div class="cp-popup__title">Success</div>
            <div class="cp-popup__msg"><?= htmlspecialchars($success_message) ?></div>
        </div>
        <button class="cp-popup__close" onclick="this.closest('.cp-popup').classList.remove('show')">
            <i class="fas fa-times"></i>
        </button>
    </div>
<?php endif; ?>

<?php if ($error_message): ?>
    <div class="cp-popup show" id="ms-flash-error" style="--cp-popup-icon-bg: var(--danger);">
        <div class="cp-popup__icon" style="background: var(--danger);">
            <i class="fas fa-exclamation"></i>
        </div>
        <div>
            <div class="cp-popup__title">Error</div>
            <div class="cp-popup__msg"><?= htmlspecialchars($error_message) ?></div>
        </div>
        <button class="cp-popup__close" onclick="this.closest('.cp-popup').classList.remove('show')">
            <i class="fas fa-times"></i>
        </button>
    </div>
<?php endif; ?>

<!-- ════════════════════════════════════════════════════════
     TOPBAR
════════════════════════════════════════════════════════ -->
<?php include __DIR__ . '/../medical_schools/partials/topbar.php'; ?>

<!-- ════════════════════════════════════════════════════════
     SIDEBAR OVERLAY (mobile)
════════════════════════════════════════════════════════ -->
<div class="cp-sidebar-overlay" id="cp-sidebar-overlay" onclick="closeSidebar()"></div>

<!-- ════════════════════════════════════════════════════════
     SHELL (sidebar + content)
════════════════════════════════════════════════════════ -->
<div class="cp-shell">

    <!-- Sidebar -->
    <?php include __DIR__ . '/../medical_schools/partials/sidebar.php'; ?>

    <!-- Main content -->
    <main class="cp-content-area" id="cp-main-content">
        <?= $page_content ?>
    </main>

</div><!-- /.cp-shell -->

<!-- ════════════════════════════════════════════════════════
     FOOTER / SCRIPTS
════════════════════════════════════════════════════════ -->
<?php include __DIR__ . '/../medical_schools/partials/footer.php'; ?>

</body>
</html>
