<?php if(!defined('ROOT')) die(); ?>
<!-- Sidebar -->
<aside class="d-sidebar">
  <div class="d-sidebar__header">
    <h3>LifeConnect Donor</h3>
    <p>Personal Dashboard</p>
  </div>
  <nav>
    <div class="d-menu-section">
      <a class="d-menu-item <?= ($active_page ?? '') === 'overview' ? 'active' : '' ?>" href="<?= ROOT ?>/donor">
        <span class="d-menu-item__icon"><i class="fas fa-chart-line"></i></span> Overview
      </a>
      <a class="d-menu-item <?= ($active_page ?? '') === 'notifications' ? 'active' : '' ?>" href="<?= ROOT ?>/donor/notifications">
        <span class="d-menu-item__icon"><i class="fas fa-bell"></i></span> Notifications
      </a>
    </div>

    <!-- Organ Donation Specific -->
    <div class="d-menu-section section-organ">
      <a class="d-menu-item <?= ($active_page ?? '') === 'donations' ? 'active' : '' ?>" href="<?= ROOT ?>/donor/donations">
        <span class="d-menu-item__icon"><i class="fas fa-heart"></i></span> My Donations
      </a>
      <a class="d-menu-item <?= ($active_page ?? '') === 'consent-history' ? 'active' : '' ?>" href="<?= ROOT ?>/donor/consent-history">
        <span class="d-menu-item__icon"><i class="fas fa-history"></i></span> Donation Consent History
      </a>
    </div>


    <div class="d-menu-section section-organ">
      <div class="d-menu-section__title">Medical & Personal</div>
      <a class="d-menu-item <?= ($active_page ?? '') === 'appointments' ? 'active' : '' ?>" href="<?= ROOT ?>/donor/appointments">
        <span class="d-menu-item__icon"><i class="fas fa-calendar-alt"></i></span> Upcoming Appointments
      </a>
      <a class="d-menu-item <?= ($active_page ?? '') === 'test-results' ? 'active' : '' ?>" href="<?= ROOT ?>/donor/test-results">
        <span class="d-menu-item__icon"><i class="fas fa-vial"></i></span> Test Results
      </a>
      <a class="d-menu-item <?= ($active_page ?? '') === 'family' ? 'active' : '' ?>" href="<?= ROOT ?>/donor/family-custodians">
        <span class="d-menu-item__icon"><i class="fas fa-users"></i></span> Family & Custodians
      </a>
      <a class="d-menu-item <?= ($active_page ?? '') === 'labs' ? 'active' : '' ?>" href="<?= ROOT ?>/donor/approved-labs">
        <span class="d-menu-item__icon"><i class="fas fa-microscope"></i></span> Approved Labs
      </a>
      <?php if (!empty($donor_data['aftercare_access'])): ?>
      <a class="d-menu-item <?= ($active_page ?? '') === 'aftercare' ? 'active' : '' ?>" href="<?= ROOT ?>/donor/aftercare">
        <span class="d-menu-item__icon"><i class="fas fa-hand-holding-medical"></i></span> Aftercare Support
      </a>
      <?php else: ?>
      <a class="d-menu-item" href="javascript:void(0)" onclick="alert('Aftercare access will be available once approved by the hospital.')" style="opacity: 0.6; cursor: not-allowed;">
        <span class="d-menu-item__icon"><i class="fas fa-lock"></i></span> Aftercare Support
      </a>
      <?php endif; ?>
    </div>
    <div class="d-menu-section section-organ">
      <div class="d-menu-section__title">Documentation</div>
      <a class="d-menu-item <?= ($active_page ?? '') === 'documents' ? 'active' : '' ?>" href="<?= ROOT ?>/donor/documents">
        <span class="d-menu-item__icon"><i class="fas fa-file-signature"></i></span> Consent Forms
      </a>
    </div>

    <!-- Financial Donation Specific -->
    <div class="d-menu-section section-financial">
      <div class="d-menu-section__title">Financial Donations</div>
      <a class="d-menu-item <?= ($active_page ?? '') === 'financial-donate' ? 'active' : '' ?>" href="<?= ROOT ?>/donor/financial-donate">
        <span class="d-menu-item__icon"><i class="fas fa-hand-holding-dollar"></i></span> Make a Donation
      </a>
      <a class="d-menu-item <?= ($active_page ?? '') === 'financial-history' ? 'active' : '' ?>" href="<?= ROOT ?>/donor/financial-history">
        <span class="d-menu-item__icon"><i class="fas fa-history"></i></span> Donation History
      </a>
    </div>
    
    <div class="d-menu-section d-menu-section--bottom" style="margin-top:20px; border-top: 1px solid var(--g200); padding-top: 10px;">
      <a class="d-menu-item" style="color: #ef4444;" href="javascript:void(0)" onclick="openLogoutModal()">
        <span class="d-menu-item__icon"><i class="fas fa-sign-out-alt"></i></span> Logout
      </a>
      <a class="d-menu-item" style="color: var(--g500); margin-top: 5px;" href="javascript:void(0)" onclick="openWithdrawAccountModal()">
        <span class="d-menu-item__icon"><i class="fas fa-user-minus"></i></span> Account Withdrawal
      </a>
    </div>
  </nav>
</aside>

<!-- Modal: Account Withdrawal Confirmation -->
<div id="withdrawAccountModal" class="d-modal">
  <div class="d-modal__body" id="withdrawModalBody" style="max-width: 450px; text-align: center;">
    <!-- Loading State -->
    <div id="withdrawModalLoading" style="padding: 2.5rem 1rem;">
      <div class="d-spinner" style="margin: 0 auto 1.5rem;"></div>
      <p style="color: var(--g600); font-weight: 500;">Checking account eligibility...</p>
    </div>

    <!-- Ready State (Success) -->
    <div id="withdrawModalReady" style="display: none;">
      <div style="width: 60px; height: 60px; background: #fee2e2; color: #ef4444; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin: 0 auto 1.5rem;">
        <i class="fas fa-exclamation-triangle"></i>
      </div>
      <h3 class="d-modal__title">Request Account Withdrawal</h3>
      <div style="margin: 1.5rem 0; text-align: left;">
        <p style="color: var(--g600); font-size: 0.9rem; margin-bottom: 1rem;">
          You are about to request a formal withdrawal of your LifeConnect account. 
        </p>
        <ul style="color: var(--g600); font-size: 0.85rem; padding-left: 1.25rem;">
          <li>All current donation commitments are cleared.</li>
          <li>Once processed, you will no longer be able to log in.</li>
          <li>Your data will be preserved for legal/medical records.</li>
        </ul>
      </div>
      <div style="display: flex; gap: 10px; justify-content: center; margin-top: 2rem;">
        <button class="d-btn d-btn--outline" onclick="closeModal('withdrawAccountModal')">Stay Active</button>
        <a href="<?= ROOT ?>/donor/withdraw_account" class="d-btn" style="background: #ef4444; color: white; text-decoration: none;">Request Withdrawal</a>
      </div>
    </div>

    <!-- Error State (Pledges Exist) -->
    <div id="withdrawModalError" style="display: none;">
      <div style="width: 60px; height: 60px; background: #fff7ed; color: #f97316; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin: 0 auto 1.5rem;">
        <i class="fas fa-hand"></i>
      </div>
      <h3 class="d-modal__title" style="color: #c2410c;">Withdrawal Blocked</h3>
      <div style="margin: 1.5rem 0;">
        <p id="withdrawErrorMessage" style="color: var(--g700); font-size: 0.95rem; font-weight: 600; line-height: 1.5; padding: 0 10px;">
          You have ongoing donation commitments. You must withdraw all pledges first.
        </p>
      </div>
      <div style="display: flex; gap: 10px; justify-content: center; margin-top: 2rem;">
        <button class="d-btn d-btn--outline" onclick="closeModal('withdrawAccountModal')">Dismiss</button>
        <a href="<?= ROOT ?>/donor/donations" class="d-btn" style="background: var(--blue-600); color: white; text-decoration: none;">Withdraw Pledges First</a>
      </div>
    </div>
  </div>
</div>

<script>
function openWithdrawAccountModal() {
  const modal = document.getElementById('withdrawAccountModal');
  const loading = document.getElementById('withdrawModalLoading');
  const ready = document.getElementById('withdrawModalReady');
  const error = document.getElementById('withdrawModalError');
  const errMsg = document.getElementById('withdrawErrorMessage');

  // Activate modal and show loading state
  modal.classList.add('active');
  loading.style.display = 'block';
  ready.style.display = 'none';
  error.style.display = 'none';

  // Perform AJAX pre-check
  fetch('<?= ROOT ?>/donor/check_withdrawal_eligibility')
    .then(response => response.json())
    .then(data => {
      loading.style.display = 'none';
      if (data.success) {
        ready.style.display = 'block';
      } else {
        error.style.display = 'block';
        errMsg.innerText = data.message;
      }
    })
    .catch(err => {
      loading.style.display = 'none';
      error.style.display = 'block';
      errMsg.innerText = "Error connecting to server. Please try again.";
    });
}
</script>
