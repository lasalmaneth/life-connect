<?php
if (session_status() === PHP_SESSION_NONE)
  session_start();

// Ensure donor is logged in
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
  header("Location: " . ROOT . "/login");
  exit();
}

// Try to build basic names if not injected
if (isset($donor_data) && is_object($donor_data)) {
  $donor_data = (array)$donor_data;
}

if (!isset($donor_full_name) && isset($donor_data)) {
  $donor_full_name = htmlspecialchars(($donor_data['first_name'] ?? '') . ' ' . ($donor_data['last_name'] ?? ''));
}
if (!isset($donor_id_display) && isset($donor_data)) {
  $did = $donor_data['id'] ?? $donor_data['donor_id'] ?? 0;
  $donor_id_display = 'D_' . str_pad($did, 5, '0', STR_PAD_LEFT);
}

$donor_avatar_initial = 'D';
if (isset($donor_data) && is_array($donor_data)) {
  $firstName = (string)($donor_data['first_name'] ?? '');
  if ($firstName !== '') {
    $donor_avatar_initial = strtoupper(substr($firstName, 0, 1));
  }
}

// Handle notification messages
$success_message = $_SESSION['success_message'] ?? null;
$error_message = $_SESSION['error_message'] ?? null;
unset($_SESSION['success_message'], $_SESSION['error_message']);
$donorModelForHeader = new \App\Models\DonorModel();
$targetDonorIdForPledges = $user_id;
if (isset($donor_data) && is_array($donor_data)) {
  $targetDonorIdForPledges = $donor_data['id'] ?? $donor_data['donor_id'] ?? $user_id;
}
$pledgeSummary = $donorModelForHeader->getPledgeSummary($targetDonorIdForPledges);
$activePledgeCount = $pledgeSummary['total'];
$finalizedPledgeCount = $pledgeSummary['finalized'];

// Active roles (for UI toggles)
$active_roles = $active_roles ?? [];
if (is_object($active_roles)) {
  $active_roles = (array) $active_roles;
}
if (!is_array($active_roles)) {
  $active_roles = [];
}
$has_organ = in_array('organ', $active_roles, true);
$has_financial = in_array('financial', $active_roles, true);
$has_non = in_array('non', $active_roles, true);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $page_title ?? 'Donor Portal' ?> | LifeConnect</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    crossorigin="anonymous" referrerpolicy="no-referrer">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="stylesheet" href="<?= ROOT ?>/public/assets/css/donor/donor.css?v=<?= time() ?>">
  <?php if (!empty($page_css)):
    foreach ($page_css as $css): ?>
      <link rel="stylesheet" href="<?= ROOT ?>/public/assets/css/donor/<?= $css ?>">
    <?php endforeach; endif; ?>
</head>

<?php
$user_status = $_SESSION['status'] ?? 'ACTIVE';
$is_withdrawing = (strtoupper($user_status) === 'WITHDRAW_REQUEST');
?>
<body class="<?= $current_mode ?? 'mode-organ-donation' ?> <?= $is_withdrawing ? 'status-withdrawal-pending' : '' ?>">

  <?php if ($is_withdrawing): ?>
    <div class="d-modal active" style="z-index: 9999; display: flex !important; background: rgba(10, 22, 40, 0.92); backdrop-filter: blur(4px);">
      <div class="d-modal__body" style="max-width: 500px; text-align: center; border: 2px solid var(--blue-400); box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);">
        <div style="width: 80px; height: 80px; background: var(--blue-50); color: var(--blue-600); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; margin: 0 auto 1.5rem;">
          <i class="fas fa-user-clock"></i>
        </div>
        <h2 style="color: var(--blue-900); font-weight: 800; margin-bottom: 1rem;">Account Withdrawal Pending</h2>
        <p style="color: var(--g600); line-height: 1.6; margin-bottom: 2rem; font-size: 0.95rem; text-align: left; padding: 0 10px;">
          Your request to withdraw your account is currently <strong>Pending Review</strong>. 
          <br><br>
          For security and data integrity, your profile access is restricted until the process is finalized by the administration or cancelled by you.
        </p>
        <div style="display: flex; flex-direction: column; gap: 12px;">
          <a href="<?= ROOT ?>/donor/cancel_withdraw_account" class="d-btn d-btn--primary" style="justify-content: center; padding: 1rem; width: 100%; text-decoration: none;">
            <i class="fas fa-undo"></i> Cancel Withdrawal Request
          </a>
          <a href="<?= ROOT ?>/logout" class="d-btn d-btn--outline" style="justify-content: center; border-color: var(--g300); color: var(--g600); width: 100%; text-decoration: none;">
            <i class="fas fa-sign-out-alt"></i> Logout
          </a>
        </div>
      </div>
    </div>
  <?php endif; ?>

  <?php if ($success_message): ?>
    <div class="d-status d-alert d-status--success"
      style="position:fixed; top:80px; right:20px; z-index:9999; box-shadow:0 10px 30px rgba(0,0,0,0.1); padding:15px; border-radius:10px;">
      <i class="fas fa-check-circle" style="margin-right:8px; font-size:1.2rem;"></i>
      <?= htmlspecialchars($success_message) ?>
    </div>
  <?php endif; ?>
  <?php if ($error_message): ?>
    <div class="d-status d-alert d-status--danger"
      style="position:fixed; top:80px; right:20px; z-index:9999; box-shadow:0 10px 30px rgba(0,0,0,0.1); padding:15px; border-radius:10px;">
      <i class="fas fa-exclamation-circle" style="margin-right:8px; font-size:1.2rem;"></i>
      <?= htmlspecialchars($error_message) ?>
    </div>
  <?php endif; ?>

  <!-- Header -->
  <header class="header">
    <div class="header-content">
      <div class="logo">
        <button class="d-mobile-nav-toggle" onclick="toggleSidebar()"
          style="display:none; background:none; border:none; font-size:1.2rem; cursor:pointer; color:var(--blue-600);"><i
            class="fas fa-bars"></i></button>
        <a href="<?= ROOT ?>" style="text-decoration:none; display:flex; align-items:center; gap:10px;">
          <img src="<?= ROOT ?>/public/assets/images/logo.png" alt="LifeConnect" style="height:40px;">
          <div>
            <strong
              style="display:block; font-size:1.1rem; color:var(--blue-700); line-height:1.2;">LifeConnect</strong>
            <p style="margin:0; font-size:.68rem; color:var(--g500);">Donor Portal</p>
          </div>
        </a>
      </div>
      <div class="header-right">
        <nav style="display: flex; align-items: center; gap: 1rem;">
          <a href="<?= ROOT ?>" class="nav-link"><i class="fas fa-home"></i> Home</a>
      </nav>
      
      <div class="notification-container">
        <a href="<?= ROOT ?>/donor/notifications" class="notification-bell" id="notificationBell" title="Recent Notifications">
            <i class="fas fa-bell"></i>
            <?php if(isset($unread_count) && $unread_count > 0): ?>
                <span class="notification-badge"><?= $unread_count ?></span>
            <?php endif; ?>
        </a>
        
        <div class="notification-dropdown" id="notificationDropdown">
          <div class="dropdown-header">
            <span>Recent Notifications</span>
            <a href="<?= ROOT ?>/donor/notifications?mark_all_read=1">Mark all read</a>
          </div>
          <div class="dropdown-body">
            <?php if (isset($notifications) && !empty($notifications)): ?>
              <?php foreach ($notifications as $n): ?>
                <?php
                  $nActionUrl = is_array($n) ? ($n['action_url'] ?? '') : (is_object($n) ? ($n->action_url ?? '') : '');
                  $nIsRead = is_array($n) ? (bool)($n['is_read'] ?? false) : (is_object($n) ? (bool)($n->is_read ?? false) : false);
                  $nTitle = is_array($n) ? ($n['title'] ?? '') : (is_object($n) ? ($n->title ?? '') : '');
                  $nCreatedAt = is_array($n) ? ($n['created_at'] ?? '') : (is_object($n) ? ($n->created_at ?? '') : '');
                ?>
                <a href="<?= !empty($nActionUrl) ? ROOT . '/' . ltrim($nActionUrl, '/') : ROOT . '/donor/notifications' ?>" class="notification-item <?= !$nIsRead ? 'unread' : '' ?>">
                  <div class="notification-icon">
                    <i class="fa-solid fa-circle-info"></i>
                  </div>
                  <div class="notification-content">
                    <p class="notification-title"><?= htmlspecialchars($nTitle) ?></p>
                    <p class="notification-time"><?= !empty($nCreatedAt) ? date('d/m/Y H:i', strtotime($nCreatedAt)) : '' ?></p>
                  </div>
                </a>
              <?php endforeach; ?>
            <?php else: ?>
              <div class="no-notifications">
                <i class="fas fa-bell-slash"></i>
                <p>No new notifications</p>
              </div>
            <?php endif; ?>
          </div>
          <div class="dropdown-footer">
            <a href="<?= ROOT ?>/donor/notifications">View All Notifications</a>
          </div>
        </div>
      </div>

      <div class="user-info" onclick="openEditProfileModal()" style="cursor: pointer;">
        <div class="user-avatar"><?= $donor_avatar_initial ?></div>
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

        <button type="button"
          class="mode-tab <?= (($current_mode ?? 'mode-organ-donation') === 'mode-organ-donation') ? 'active' : '' ?> <?= $has_organ ? '' : 'inactive' ?>"
          data-mode="mode-organ-donation"
          title="<?= $has_organ ? '' : 'Enable Organ Donor role to switch to this view' ?>"
          onclick="<?= $has_organ ? "setDonorMode('mode-organ-donation', this)" : "promptAddRole('organ','Organ Donor')" ?>">
          <i class="fas fa-hand-holding-heart"></i>
          <span>Organ Donor</span>
        </button>

        <button type="button"
          class="mode-tab <?= (($current_mode ?? '') === 'mode-financial-donation') ? 'active' : '' ?> <?= $has_financial ? '' : 'inactive' ?>"
          data-mode="mode-financial-donation"
          title="<?= $has_financial ? '' : 'Enable Financial Donor role to switch to this view' ?>"
          onclick="<?= $has_financial ? "setDonorMode('mode-financial-donation', this)" : "promptAddRole('financial','Financial Donor')" ?>">
          <i class="fas fa-hand-holding-dollar"></i>
          <span>Financial</span>
        </button>

        <button type="button"
          class="mode-tab <?= (($current_mode ?? '') === 'mode-non-donor') ? 'active' : '' ?> <?= $has_non ? '' : 'inactive' ?>"
          data-mode="mode-non-donor"
          title="<?= $has_non ? '' : 'Enable Non-Donor role to switch to this view' ?>"
          onclick="<?= $has_non ? "setDonorMode('mode-non-donor', this)" : "promptAddRole('non','Non-Donor')" ?>">
          <i class="fas fa-user-slash"></i>
          <span>Non-Donor</span>
        </button>
      </div>

      <button type="button" class="d-btn d-btn--sm d-btn--outline manage-roles-btn" onclick="openManageRolesModal()" style="border-radius: 8px;">
        <i class="fas fa-cog"></i> Manage Roles
      </button>
    </div>
  </div>

  <!-- Modal: Add New Role Confirmation -->
  <div id="addRoleModal" class="d-modal">
    <div class="d-modal__body" id="addRoleModalBody"
      style="max-width: 400px; text-align: center; transition: all 0.3s ease;">
      <div id="addRoleWarningIcon"
        style="display: none; width: 50px; height: 50px; background: #fee2e2; color: #ef4444; border-radius: 50%; align-items: center; justify-content: center; font-size: 1.25rem; margin: 0 auto 1rem;">
        <i class="fas fa-exclamation-triangle"></i>
      </div>
      <h3 class="d-modal__title" id="addRoleTitle">Add New Role</h3>
      <div style="margin: 1.5rem 0;">
        <p style="color: var(--g600); margin-bottom: 0.5rem;" id="addRoleMessage">
          This role is not currently active in your profile. Would you like to enable it?
        </p>
        <div id="addRoleRedWarning"
          style="display: none; color: #ef4444; font-weight: 600; font-size: 0.9rem; padding: 10px; background: #fee2e2; border-radius: 8px; margin-top: 10px;">
          <i class="fas fa-info-circle"></i> You must withdraw all active pledges before becoming a Non-Donor.
        </div>
      </div>
      <div style="display: flex; gap: 10px; justify-content: center;">
        <button class="d-btn d-btn--outline" onclick="closeModal('addRoleModal')">Cancel</button>
        <button class="d-btn d-btn--primary" id="confirmAddRoleBtn">Add Role</button>
        <a href="<?= ROOT ?>/donor/withdraw-consent" id="withdrawPledgesBtn" class="d-btn"
          style="display: none; background: #ef4444; color: white; text-decoration: none;">Withdraw Pledges First</a>
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
        <p style="margin-bottom: 1.5rem; font-size: 0.9rem; color: var(--g600);">Enable or disable your participation
          roles below.</p>

        <div class="role-checkbox-item">
          <label
            style="display: flex; align-items: center; gap: 12px; cursor: pointer; padding: 10px; border-radius: 8px; transition: var(--tr);">
            <input type="checkbox" name="role_check" value="organ" <?= $has_organ ? 'checked' : '' ?>
              style="width: 18px; height: 18px;" onchange="handleRoleExclusivity(this)">
            <div>
              <div style="font-weight: 600; font-size: 0.95rem;">Organ Donor</div>
              <div style="font-size: 0.75rem; color: var(--g500);">Donate organs and save lives</div>
            </div>
          </label>
        </div>

        <div class="role-checkbox-item">
          <label
            style="display: flex; align-items: center; gap: 12px; cursor: pointer; padding: 10px; border-radius: 8px; transition: var(--tr);">
            <input type="checkbox" name="role_check" value="financial" <?= $has_financial ? 'checked' : '' ?>
              style="width: 18px; height: 18px;" onchange="handleRoleExclusivity(this)">
            <div>
              <div style="font-weight: 600; font-size: 0.95rem;">Financial Donor</div>
              <div style="font-size: 0.75rem; color: var(--g500);">Support transplant patients financially</div>
            </div>
          </label>
        </div>

        <div class="role-checkbox-item">
          <label
            style="display: flex; align-items: center; gap: 12px; cursor: pointer; padding: 10px; border-radius: 8px; transition: var(--tr);">
            <input type="checkbox" name="role_check" value="non" <?= $has_non ? 'checked' : '' ?>
              style="width: 18px; height: 18px;" onchange="handleRoleExclusivity(this)">
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
    const activePledgeCount = <?= (int) ($activePledgeCount ?? 0) ?>;
    const finalizedPledgeCount = <?= (int) ($finalizedPledgeCount ?? 0) ?>;

    async function setDonorMode(mode, element) {
      if (element.classList.contains('inactive')) return;

      // Add confirmation for Non-Donor if switching via tab (even if role is active)
      if (mode === 'mode-non-donor' && activePledgeCount > 0) {
        showRoleWarning("You cannot switch to Non-Donor view while you have active donation consents. Please withdraw your pledges first.");
        return;
      }

      // Perform UI Updates
      updatePortalUI(mode, element);

      // Persist on Server & Navigate
      const formData = new FormData();
      formData.append('mode', mode);
      try {
        const response = await fetch('<?= ROOT ?>/donor/set-portal-mode', {
          method: 'POST',
          body: formData
        });
        const data = await response.json();
        if (data.success) {
          // Navigate to overview on role/mode click
          window.location.href = '<?= ROOT ?>/donor';
        }
      } catch (e) {
        console.error("Failed to set donor mode:", e);
        // Fallback if fetch fails: still redirect to update context
        window.location.href = '<?= ROOT ?>/donor';
      }
    }

    function updatePortalUI(mode, element) {
      // Update Active Tab
      document.querySelectorAll('.mode-tab').forEach(t => t.classList.remove('active'));
      element.classList.add('active');

      // Update Body Class (Primary mode indicator for CSS)
      document.body.className = mode;

      // Move Indicator UI
      updateModeIndicator(element);

      // Update Sidebar Visibility
      updateSidebarVisibility(mode);

      // Update LocalStorage
      localStorage.setItem('donor_portal_mode', mode);
    }

    function updateSidebarVisibility(mode) {
      const organSections = document.querySelectorAll('.section-organ');
      const financialSections = document.querySelectorAll('.section-financial');

      if (mode === 'mode-organ-donation') {
        organSections.forEach(s => s.style.display = 'block');
        financialSections.forEach(s => s.style.display = 'none');
      } else if (mode === 'mode-financial-donation') {
        organSections.forEach(s => s.style.display = 'none');
        financialSections.forEach(s => s.style.display = 'block');
      } else {
        // Non-Donor or Overview
        organSections.forEach(s => s.style.display = 'none');
        financialSections.forEach(s => s.style.display = 'none');
      }
    }

    // Ensure initial sidebar state matches active tab
    document.addEventListener('DOMContentLoaded', () => {
      const activeTab = document.querySelector('.mode-tab.active');
      if (activeTab) {
        const mode = activeTab.dataset.mode;
        updateSidebarVisibility(mode);
        updateModeIndicator(activeTab);
      }
    });

    function updateModeIndicator(element) {
      const indicator = document.getElementById('modeIndicator');
      if (indicator && element) {
        indicator.style.width = element.offsetWidth + 'px';
        indicator.style.left = element.offsetLeft + 'px';
      }
    }

    const hasActiveConsents = <?= ($activePledgeCount > 0) ? 'true' : 'false' ?>;

    function promptAddRole(role, roleName) {
      const modalBody = document.getElementById('addRoleModalBody');
      const warningSection = document.getElementById('addRoleRedWarning');
      const warningIcon = document.getElementById('addRoleWarningIcon');
      const addBtn = document.getElementById('confirmAddRoleBtn');
      const withdrawBtn = document.getElementById('withdrawPledgesBtn');
      const title = document.getElementById('addRoleTitle');

      modalBody.style.borderTop = "none";
      warningSection.style.display = "none";
      warningIcon.style.display = "none";
      addBtn.style.display = "inline-block";
      addBtn.innerText = "Add Role";
      addBtn.style.background = "var(--blue-600)";
      withdrawBtn.style.display = "none";
      title.innerText = `Add ${roleName} Role`;
      title.style.color = "var(--blue-800)";

      // Mandatory Red Warning for Non-Donor Case (Only if active finalized pledges exist)
      if (role === 'non' && hasActiveConsents) {
        const message = "Becoming a Non-Donor signifies your official choice to opt out of ALL organ, tissue, and body recovery efforts. This requires a formal legal withdrawal form because you have legally finalized pledges.";
        applyRedWarningStyle(message, role);
      }

      document.getElementById('confirmAddRoleBtn').onclick = () => activateRole(role);
      document.getElementById('addRoleModal').classList.add('active');
    }

    function applyRedWarningStyle(customMessage = null, role = 'non') {
      const modalBody = document.getElementById('addRoleModalBody');
      const warningSection = document.getElementById('addRoleRedWarning');
      const warningIcon = document.getElementById('addRoleWarningIcon');
      const addBtn = document.getElementById('confirmAddRoleBtn');
      const withdrawBtn = document.getElementById('withdrawPledgesBtn');
      const title = document.getElementById('addRoleTitle');
      const msg = document.getElementById('addRoleRedWarning');

      modalBody.style.borderTop = "5px solid #ef4444";
      warningIcon.style.background = "#fee2e2";
      warningIcon.style.color = "#ef4444";
      warningSection.style.background = "#fee2e2";
      warningSection.style.color = "#852626";
      warningSection.style.border = "1px solid #fecaca";

      if (role === 'non') {
        // MANDATORY WITHDRAWAL PORTAL FOR NON-DONOR
        title.innerText = "Formal Declaration Required";
        title.style.color = "#991b1b";
        addBtn.style.display = "none";
        withdrawBtn.style.display = "inline-block";
        withdrawBtn.innerText = "Proceed to Withdrawal Form";
        withdrawBtn.href = "javascript:void(0)";
        withdrawBtn.onclick = () => { window.location.href = '<?= ROOT ?>/donor/donations'; };
      } else if (activePledgeCount > 0) {
        // OTHER HARD BLOCK CASE (Emergency/Safety)
        title.innerText = "Action Required";
        title.style.color = "#991b1b";
        addBtn.style.display = "none";
        withdrawBtn.style.display = "inline-block";
        withdrawBtn.innerText = "Manage Pledges";
        withdrawBtn.href = "<?= ROOT ?>/donor/donations";
        withdrawBtn.onclick = null;
      }

      warningSection.style.display = "block";
      warningIcon.style.display = "flex";

      if (customMessage) {
        msg.innerHTML = `<i class="fas fa-info-circle"></i> ${customMessage}`;
      }
    }

    function showRoleWarning(message) {
      // Use the existing addRoleModal but set it to warning mode
      promptAddRole('non', 'Non-Donor');
      applyRedWarningStyle(message, 'non');
    }

    function openModal(id) {
      document.getElementById(id).classList.add('active');
    }

    function openManageRolesModal() {
      document.getElementById('manageRolesModal').classList.add('active');
    }

    function closeModal(id) {
      document.getElementById(id).classList.remove('active');
    }

    function handleRoleExclusivity(checkbox) {
      const allChecks = document.querySelectorAll('input[name="role_check"]');
      if (checkbox.checked) {
        if (checkbox.value === 'non') {
          // If Non-Donor is selected, uncheck Organ Donor only
          allChecks.forEach(cb => {
            if (cb.value === 'organ') cb.checked = false;
          });
        } else if (checkbox.value === 'organ') {
          // If Organ Donor is selected, uncheck Non-Donor
          allChecks.forEach(cb => {
            if (cb.value === 'non') cb.checked = false;
          });
        }
      }
    }

    async function activateRole(role) {
      let newRoles = [...activeRoles];

      if (role === 'non') {
        // Adding Non-Donor removes Organ Donor
        newRoles = newRoles.filter(r => r !== 'organ');
        newRoles.push('non');
      } else if (role === 'organ') {
        // Adding Organ Donor removes Non-Donor
        newRoles = newRoles.filter(r => r !== 'non');
        newRoles.push('organ');
      } else {
        newRoles.push(role);
      }

      // Deduplicate and filter any possible nulls
      newRoles = [...new Set(newRoles)].filter(r => r);

      await saveRolesToServer(newRoles);
    }

    async function saveRolesFromModal() {
      const checkboxes = document.querySelectorAll('input[name="role_check"]:checked');
      const newRoles = Array.from(checkboxes).map(cb => cb.value);

      if (newRoles.length === 0) {
        alert("Please select at least one role.");
        return;
      }

      // Intercept Organ Donor role removal if ANY pledges exist
      const wasOrganDonor = activeRoles.includes('organ');
      const isOrganDonorNow = newRoles.includes('organ');

      if (wasOrganDonor && !isOrganDonorNow && activePledgeCount > 0) {
        closeModal('manageRolesModal');
        showRoleWarning("You have active donation pledges. You must formally withdraw all pledges via the donations page before removing the Organ Donor role.");
        return;
      }

      // Also intercept Non-Donor selection (hard block if pledges exist)
      if (newRoles.includes('non') && activePledgeCount > 0) {
        closeModal('manageRolesModal');
        showRoleWarning("Becoming a Non-Donor signifies your official choice to opt out of ALL recovery efforts. Since you have active pledges, a formal withdrawal form is required.");
        return;
      }

      await saveRolesToServer(newRoles);
    }

    async function saveRolesToServer(roles, confirmWithdraw = false) {
      const formData = new FormData();
      roles.forEach(r => formData.append('roles[]', r));
      if (confirmWithdraw) formData.append('confirm_withdraw', '1');

      try {
        const response = await fetch('<?= ROOT ?>/donor/update-roles', {
          method: 'POST',
          body: formData
        });
        const data = await response.json();
        if (data.success) {
          // Direct redirect to overview on any role change
          window.location.href = '<?= ROOT ?>/donor';
        } else {
          // If the message indicates legally finalized pledges, show the red modal
          if (data.message && (data.message.toLowerCase().includes('finalized') || data.message.toLowerCase().includes('consent') || data.message.toLowerCase().includes('pledge'))) {
            showRoleWarning(data.message);
          } else {
            alert(data.message || "Failed to update roles.");
          }
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
          // Use UI-only update for initialization to avoid infinite redirect loop
          updatePortalUI(targetTab.dataset.mode, targetTab);
        }, 50);
      }

        // Notification Dropdown Toggle
      const bell = document.getElementById('notificationBell');
      const dropdown = document.getElementById('notificationDropdown');

      if (bell && dropdown) {
        // Bell icon now navigates directly via href.
        // We add persistent click listeners to manage the dropdown lifecycle.
        document.addEventListener('click', (e) => {
          if (!bell.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.remove('active');
          }
        });

        dropdown.addEventListener('click', (e) => {
          e.stopPropagation();
        });
      }
    });
  </script>

  <?php include 'withdraw_modal.view.php'; ?>
  <?php include 'profile_edit_modal.view.php'; ?>

  <div class="d-shell">