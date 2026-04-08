<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// Ensure donor is logged in
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header("Location: " . ROOT . "/login");
    exit();
}

// Try to build basic names if not injected
if (!isset($donor_full_name) && isset($donor_data)) {
    $donor_full_name = htmlspecialchars(($donor_data['first_name'] ?? '') . ' ' . ($donor_data['last_name'] ?? ''));
}
if (!isset($donor_id_display) && isset($donor_data)) {
    $did = $donor_data['id'] ?? $donor_data['donor_id'] ?? 0;
    $donor_id_display = 'D_' . str_pad($did, 5, '0', STR_PAD_LEFT);
}

// Handle notification messages
$success_message = $_SESSION['success_message'] ?? null;
$error_message = $_SESSION['error_message'] ?? null;
unset($_SESSION['success_message'], $_SESSION['error_message']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'Donor Portal' ?> | LifeConnect</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="<?= ROOT ?>/public/assets/css/donor/donor.css?v=<?= time() ?>">
    <?php if (!empty($page_css)): foreach ($page_css as $css): ?>
        <link rel="stylesheet" href="<?= ROOT ?>/public/assets/css/donor/<?= $css ?>">
    <?php endforeach; endif; ?>
</head>
<body>

<?php if ($success_message): ?>
    <div class="d-status d-alert d-status--success" style="position:fixed; top:80px; right:20px; z-index:9999; box-shadow:0 10px 30px rgba(0,0,0,0.1); padding:15px; border-radius:10px;">
        <i class="fas fa-check-circle" style="margin-right:8px; font-size:1.2rem;"></i> <?= htmlspecialchars($success_message) ?>
    </div>
<?php endif; ?>
<?php if ($error_message): ?>
    <div class="d-status d-alert d-status--danger" style="position:fixed; top:80px; right:20px; z-index:9999; box-shadow:0 10px 30px rgba(0,0,0,0.1); padding:15px; border-radius:10px;">
        <i class="fas fa-exclamation-circle" style="margin-right:8px; font-size:1.2rem;"></i> <?= htmlspecialchars($error_message) ?>
    </div>
<?php endif; ?>

<!-- Header -->
<header class="d-header">
  <div class="d-header__inner">
    <div class="logo">
      <button class="d-mobile-nav-toggle" onclick="toggleSidebar()" style="display:none; background:none; border:none; font-size:1.2rem; cursor:pointer; color:var(--blue-600);"><i class="fas fa-bars"></i></button>
      <a href="<?= ROOT ?>" style="text-decoration:none; display:flex; align-items:center; gap:10px;">
          <img src="<?= ROOT ?>/public/assets/images/logo.png" alt="LifeConnect" style="height:40px;">
          <div>
            <strong style="display:block; font-size:1.1rem; color:var(--blue-700); line-height:1.2;">LifeConnect</strong>
            <p style="margin:0; font-size:.68rem; color:var(--g500);">Donor Portal</p>
          </div>
      </a>
    </div>
    <div class="d-header__right">
      <nav style="display: flex; align-items: center; margin-right: 1rem;">
          <a href="<?= ROOT ?>" class="d-menu-item" style="padding: 0.5rem; text-decoration: none;"><i class="fas fa-home" style="margin-right: 5px;"></i> Home</a>
      </nav>
      <div style="position:relative;">
        <div class="d-bell" onclick="toggleNotifications()">
          <i class="fas fa-bell"></i>
        </div>
      </div>
      <div class="d-user-chip" onclick="toggleSettingsModal()">
        <div class="d-user-avatar"><?= strtoupper(substr($donor_data['first_name'] ?? 'D', 0, 1)) ?></div>
        <div>
          <div class="d-user-chip__name"><?= htmlspecialchars($donor_full_name ?? 'Donor') ?></div>
          <div class="d-user-chip__badge" style="background:var(--blue-100); color:var(--blue-700);"><i class="fas fa-id-card"></i> <?= htmlspecialchars($donor_id_display ?? 'Donor') ?></div>
        </div>
      </div>
    </div>
  </div>
</header>

<div class="donor-mode-container">
  <div class="mode-switcher">
    <div class="mode-indicator" id="modeIndicator"></div>
    <div class="mode-tab active" data-mode="mode-organ-donation" onclick="setDonorMode('mode-organ-donation', this)">
      <i class="fas fa-hand-holding-heart"></i>
      <span>Organ Donation</span>
    </div>
    <div class="mode-tab" data-mode="mode-financial-donation" onclick="setDonorMode('mode-financial-donation', this)">
      <i class="fas fa-hand-holding-dollar"></i>
      <span>Financial Donation</span>
    </div>
    <div class="mode-tab" data-mode="mode-non-donor" onclick="setDonorMode('mode-non-donor', this)">
      <i class="fas fa-user-slash"></i>
      <span>Become a Non-Donor</span>
    </div>
  </div>
</div>

<script>
function setDonorMode(mode, element) {
  // Update Body Class
  document.body.classList.remove('mode-organ-donation', 'mode-financial-donation', 'mode-non-donor');
  document.body.classList.add(mode);

  // Update Active Tab
  document.querySelectorAll('.mode-tab').forEach(tab => tab.classList.remove('active'));
  element.classList.add('active');

  // Move Indicator
  const indicator = document.getElementById('modeIndicator');
  indicator.style.width = element.offsetWidth + 'px';
  indicator.style.left = element.offsetLeft + 'px';

  // Persist
  localStorage.setItem('donor_portal_mode', mode);
}

// Initialize on Load
document.addEventListener('DOMContentLoaded', () => {
  const savedMode = localStorage.getItem('donor_portal_mode') || 'mode-organ-donation';
  const targetTab = document.querySelector(`[data-mode="${savedMode}"]`) || document.querySelector('.mode-tab');
  
  // Need a small timeout to ensure offsetWidth is calculated correctly
  setTimeout(() => {
    setDonorMode(savedMode, targetTab);
  }, 50);
});
</script>

<div class="d-shell">
