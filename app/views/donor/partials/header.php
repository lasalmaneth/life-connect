<?php
/**
 * Donor Portal — Header Partial
 * Include this at the top of every donor page view.
 * 
 * Expected variables (from controller via extract):
 *   $donor_data, $donor_full_name, $donor_id_display, $donor_role
 *   $active_page (string: 'overview'|'donations'|'test-results'|'family'|'labs'|'documents')
 *   $page_title (string: page-specific title)
 *   $page_css (array: additional CSS files for this page)
 */

if (session_status() === PHP_SESSION_NONE) session_start();

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header("Location: " . ROOT . "/login");
    exit();
}

$success_message = $_SESSION['success_message'] ?? null;
$error_message = $_SESSION['error_message'] ?? null;
unset($_SESSION['success_message'], $_SESSION['error_message']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'Donor Portal' ?> - LifeConnect</title>
    
    <link rel="stylesheet" href="<?= ROOT ?>/public/assets/css/style.css">
    <link rel="stylesheet" href="<?= ROOT ?>/public/assets/css/donor/donor.css">
    <link rel="stylesheet" href="<?= ROOT ?>/public/assets/css/fontawesome.min.css?v=<?= time() ?>">
    <?php if (!empty($page_css)): foreach ($page_css as $css): ?>
        <link rel="stylesheet" href="<?= ROOT ?>/public/assets/css/donor/<?= $css ?>">
    <?php endforeach; endif; ?>
    
    <style>
        .donor-badge {
            display: inline-flex; align-items: center; padding: 0.25rem 0.75rem; border-radius: var(--radius-full);
            font-size: 0.75rem; font-weight: 600; text-transform: uppercase;
            background: rgba(16, 185, 129, 0.1); color: var(--success); border: 1px solid rgba(16, 185, 129, 0.2);
        }
        
        .donor-profile-image {
            width: 120px; height: 120px; border-radius: var(--radius-lg);
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            display: flex; align-items: center; justify-content: center; font-size: 3rem; color: white; box-shadow: var(--shadow-md);
        }

        .organ-tag {
            background: #f8fafc; border: 1px solid var(--glass-border); padding: 0.5rem 1rem;
            border-radius: var(--radius-md); font-size: 0.875rem; display: flex; align-items: center; gap: 0.5rem;
        }
    </style>
</head>
<body>

    <!-- Popup Notification -->
    <div id="popupNotification" class="popup-notification">
        <div class="popup-icon"><i class="fas fa-check"></i></div>
        <div class="popup-content">
            <div class="popup-title">Success!</div>
            <div class="popup-message">Operation completed successfully.</div>
        </div>
        <button class="popup-close" onclick="hidePopup()">&times;</button>
        <div class="popup-progress"></div>
    </div>

    <!-- Header -->
    <div class="header">
        <div class="header-content">
            <div class="header-left">
                <h1>Donor Portal</h1>
                <p>Welcome back, <?= $donor_full_name ?></p>
            </div>
            
            <div class="header-right">
                <nav class="header-nav">
                    <a href="<?= ROOT ?>" class="nav-link"><i class="fa-solid fa-house"></i> <span>Home</span></a>
                </nav>
                <div class="user-info" onclick="toggleUserDropdown()">
                    <div class="user-avatar"><?= strtoupper(substr($donor_data['first_name'] ?? '?', 0, 1)) ?></div>
                    <div class="user-details">
                        <span class="user-name"><?= $donor_full_name ?></span>
                        <span class="user-role"><?= $donor_id_display ?></span>
                    </div>
                    <i class="fa-solid fa-chevron-down ms-2 opacity-50"></i>
                    
                    <div class="user-dropdown" id="user-dropdown">
                        <div class="dropdown-header">
                            <div class="user-avatar-large"><?= strtoupper(substr($donor_data['first_name'] ?? '?', 0, 1)) ?></div>
                            <div class="user-details-large">
                                <div class="user-name"><?= $donor_full_name ?></div>
                                <div class="user-role"><?= $donor_role ?></div>
                            </div>
                        </div>
                        <div class="dropdown-content">
                            <div class="detail-item"><span class="detail-label">Donor ID</span><span class="detail-value"><?= $donor_id_display ?></span></div>
                            <div class="detail-item"><span class="detail-label">Blood Group</span><span class="detail-value"><?= htmlspecialchars($donor_data['blood_group'] ?? 'N/A') ?></span></div>
                        </div>
                        <div class="dropdown-footer">
                            <button class="btn-premium btn-small" style="width: 100%;" onclick="openSettingsModal()"><i class="fas fa-cog"></i> Settings</button>
                            <button class="btn-premium btn-small" style="width: 100%; background: var(--danger);" onclick="openLogoutModal()"><i class="fas fa-sign-out-alt"></i> Logout</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid p-0">
        <div class="main-content">
