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
      <a class="d-menu-item <?= ($active_page ?? '') === 'financial-history' ? 'active' : '' ?>" href="<?= ROOT ?>/donor/financial-history">
        <span class="d-menu-item__icon"><i class="fas fa-history"></i></span> Donation History
      </a>
      <a class="d-menu-item <?= ($active_page ?? '') === 'financial-donate' ? 'active' : '' ?>" href="<?= ROOT ?>/donor/financial-donate">
        <span class="d-menu-item__icon"><i class="fas fa-hand-holding-dollar"></i></span> Make a Donation
      </a>
    </div>
    
    <div class="d-menu-section d-menu-section--bottom" style="margin-top:20px; border-top: 1px solid var(--g200);">
      <a class="d-menu-item" style="color: #ef4444;" href="javascript:void(0)" onclick="openLogoutModal()">
        <span class="d-menu-item__icon"><i class="fas fa-sign-out-alt"></i></span> Logout
      </a>
    </div>
  </nav>
</aside>
