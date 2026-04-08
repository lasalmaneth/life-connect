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
<body class="<?= $current_mode ?? 'mode-organ-donation' ?>">

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
<header class="header">
  <div class="header-content">
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
    <div class="header-right">
      <nav style="display: flex; align-items: center; gap: 1rem;">
          <a href="<?= ROOT ?>" class="nav-link"><i class="fas fa-home"></i> Home</a>
      </nav>
      
      <button class="notification-bell" onclick="toggleNotifications()">
        <i class="fas fa-bell"></i>
      </button>

      <div class="user-info" onclick="toggleSettingsModal()">
        <div class="user-avatar"><?= strtoupper(substr($donor_data['first_name'] ?? 'D', 0, 1)) ?></div>
        <div style="display: flex; flex-direction: column; gap: 2px;">
          <div style="font-size: 0.9rem; font-weight: 600; color: var(--blue-900);"><?= htmlspecialchars($donor_full_name ?? 'Donor') ?></div>
          <div class="user-id-pill"><i class="fas fa-id-card"></i> <?= htmlspecialchars($donor_id_display ?? 'Donor') ?></div>
        </div>
      </div>
    </div>
  </div>
</header>

<div class="donor-mode-container">
  <div class="mode-switcher-wrapper">
    <div class="mode-switcher">
      <div class="mode-indicator" id="modeIndicator"></div>
      
      <?php 
      $has_organ = in_array('organ', $active_roles ?? []);
      $has_financial = in_array('financial', $active_roles ?? []);
      $has_non = in_array('non', $active_roles ?? []);
      ?>

      <div class="mode-tab <?= $current_mode === 'mode-organ-donation' ? 'active' : '' ?> <?= !$has_organ ? 'inactive' : '' ?>" 
           data-mode="mode-organ-donation" 
           onclick="<?= $has_organ ? "setDonorMode('mode-organ-donation', this)" : "promptAddRole('organ', 'Organ Donor')" ?>"
           title="<?= !$has_organ ? 'Add this role to your profile' : '' ?>">
        <i class="fas fa-hand-holding-heart"></i>
        <span>Organ Donation</span>
      </div>

      <div class="mode-tab <?= $current_mode === 'mode-financial-donation' ? 'active' : '' ?> <?= !$has_financial ? 'inactive' : '' ?>" 
           data-mode="mode-financial-donation" 
           onclick="<?= $has_financial ? "setDonorMode('mode-financial-donation', this)" : "promptAddRole('financial', 'Financial Donor')" ?>"
           title="<?= !$has_financial ? 'Add this role to your profile' : '' ?>">
        <i class="fas fa-hand-holding-dollar"></i>
        <span>Financial Donation</span>
      </div>

      <div class="mode-tab <?= $current_mode === 'mode-non-donor' ? 'active' : '' ?> <?= !$has_non ? 'inactive' : '' ?>" 
           data-mode="mode-non-donor" 
           onclick="<?= $has_non ? "setDonorMode('mode-non-donor', this)" : "promptAddRole('non', 'Non-Donor')" ?>"
           title="<?= !$has_non ? 'Add this role to your profile' : '' ?>">
        <i class="fas fa-user-slash"></i>
        <span>Non-Donor</span>
      </div>
    </div>
    
    <button class="d-btn d-btn--sm d-btn--outline manage-roles-btn" onclick="openManageRolesModal()" style="border-radius: 8px;">
      <i class="fas fa-cog"></i> Manage Roles
    </button>
  </div>
</div>

<!-- Modal: Add New Role Confirmation -->
<div id="addRoleModal" class="d-modal">
  <div class="d-modal__body" style="max-width: 400px; text-align: center;">
    <h3 class="d-modal__title" id="addRoleTitle">Add New Role</h3>
    <p style="margin: 1.5rem 0; color: var(--g600);" id="addRoleMessage">
      This role is not currently active in your profile. Would you like to enable it?
    </p>
    <div style="display: flex; gap: 10px; justify-content: center;">
      <button class="d-btn d-btn--outline" onclick="closeModal('addRoleModal')">Cancel</button>
      <button class="d-btn d-btn--primary" id="confirmAddRoleBtn">Add Role</button>
    </div>
  </div>
</div>

<!-- Modal: Manage Roles -->
<div id="manageRolesModal" class="d-modal">
  <div class="d-modal__body" style="max-width: 450px;">
    <div class="d-modal__header">
      <h3 class="d-modal__title">Manage Your Roles</h3>
      <button class="d-modal__close" onclick="closeModal('manageRolesModal')">&times;</button>
    </div>
    <div style="padding: 1rem 0;">
      <p style="margin-bottom: 1.5rem; font-size: 0.9rem; color: var(--g600);">Enable or disable your participation roles below.</p>
      
      <div class="role-checkbox-item">
        <label style="display: flex; align-items: center; gap: 12px; cursor: pointer; padding: 10px; border-radius: 8px; transition: var(--tr);">
          <input type="checkbox" name="role_check" value="organ" <?= $has_organ ? 'checked' : '' ?> style="width: 18px; height: 18px;">
          <div>
            <div style="font-weight: 600; font-size: 0.95rem;">Organ Donor</div>
            <div style="font-size: 0.75rem; color: var(--g500);">Donate organs and save lives</div>
          </div>
        </label>
      </div>

      <div class="role-checkbox-item">
        <label style="display: flex; align-items: center; gap: 12px; cursor: pointer; padding: 10px; border-radius: 8px; transition: var(--tr);">
          <input type="checkbox" name="role_check" value="financial" <?= $has_financial ? 'checked' : '' ?> style="width: 18px; height: 18px;">
          <div>
            <div style="font-weight: 600; font-size: 0.95rem;">Financial Donor</div>
            <div style="font-size: 0.75rem; color: var(--g500);">Support transplant patients financially</div>
          </div>
        </label>
      </div>

      <div class="role-checkbox-item">
        <label style="display: flex; align-items: center; gap: 12px; cursor: pointer; padding: 10px; border-radius: 8px; transition: var(--tr);">
          <input type="checkbox" name="role_check" value="non" <?= $has_non ? 'checked' : '' ?> style="width: 18px; height: 18px;">
          <div>
            <div style="font-weight: 600; font-size: 0.95rem;">Non-Donor</div>
            <div style="font-size: 0.75rem; color: var(--g500);">Stay informed and support awareness</div>
          </div>
        </label>
      </div>
    </div>
    <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 1rem;">
      <button class="d-btn d-btn--outline" onclick="closeModal('manageRolesModal')">Cancel</button>
      <button class="d-btn d-btn--primary" onclick="saveRolesFromModal()">Update Roles</button>
    </div>
  </div>
</div>

<script>
const activeRoles = <?= json_encode($active_roles ?? []) ?>;

async function setDonorMode(mode, element) {
  if (element.classList.contains('inactive')) return;

  // Update Body Class
  document.body.classList.remove('mode-organ-donation', 'mode-financial-donation', 'mode-non-donor');
  document.body.classList.add(mode);

  // Update Active Tab
  document.querySelectorAll('.mode-tab').forEach(tab => tab.classList.remove('active'));
  element.classList.add('active');

  // Move Indicator
  updateModeIndicator(element);

  // Persist on Server
  const formData = new FormData();
  formData.append('mode', mode);
  fetch('<?= ROOT ?>/donor/set-portal-mode', {
    method: 'POST',
    body: formData
  });

  // Persist in LocalStorage (fallback/legacy)
  localStorage.setItem('donor_portal_mode', mode);
}

function updateModeIndicator(element) {
  const indicator = document.getElementById('modeIndicator');
  if (indicator && element) {
    indicator.style.width = element.offsetWidth + 'px';
    indicator.style.left = element.offsetLeft + 'px';
  }
}

function promptAddRole(role, roleName) {
  document.getElementById('addRoleTitle').innerText = `Add ${roleName} Role`;
  document.getElementById('confirmAddRoleBtn').onclick = () => activateRole(role);
  document.getElementById('addRoleModal').classList.add('active');
}

function openManageRolesModal() {
  document.getElementById('manageRolesModal').classList.add('active');
}

function closeModal(id) {
  document.getElementById(id).classList.remove('active');
}

async function activateRole(role) {
  const newRoles = [...activeRoles, role];
  await saveRolesToServer(newRoles);
}

async function saveRolesFromModal() {
  const checkboxes = document.querySelectorAll('input[name="role_check"]:checked');
  const newRoles = Array.from(checkboxes).map(cb => cb.value);
  
  if (newRoles.length === 0) {
      alert("Please select at least one role.");
      return;
  }
  
  await saveRolesToServer(newRoles);
}

async function saveRolesToServer(roles) {
  const formData = new FormData();
  roles.forEach(r => formData.append('roles[]', r));

  try {
    const response = await fetch('<?= ROOT ?>/donor/update-roles', {
      method: 'POST',
      body: formData
    });
    const data = await response.json();
    if (data.success) {
      window.location.reload();
    } else {
      alert(data.message || "Failed to update roles.");
    }
  } catch (e) {
    console.error(e);
    alert("An error occurred. Please try again.");
  }
}

// Initialize on Load
document.addEventListener('DOMContentLoaded', () => {
  const savedMode = localStorage.getItem('donor_portal_mode') || 'mode-organ-donation';
  let targetTab = document.querySelector(`[data-mode="${savedMode}"]`);
  
  // If saved mode is now inactive, pick the first active one
  if (!targetTab || targetTab.classList.contains('inactive')) {
    targetTab = document.querySelector('.mode-tab.active');
  }
  
  if (targetTab) {
    setTimeout(() => {
      setDonorMode(targetTab.dataset.mode, targetTab);
    }, 50);
  }
});
</script>

<div class="d-shell">
