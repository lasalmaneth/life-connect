<?php if(!defined('ROOT')) die(); ?>
<!-- Sidebar -->
<aside class="d-sidebar">
  <div class="d-sidebar__header">
    <h3>Medical School Portal</h3>
    <p>Body Donation Management</p>
  </div>
  <nav>
    <div class="d-menu-section">
      <a class="d-menu-item" href="<?= ROOT ?>/home" style="margin-bottom: 10px;">
        <span class="d-menu-item__icon"><i class="fas fa-arrow-left"></i></span> Back to Home
      </a>
      <div class="d-menu-section__title">Overview</div>
      <a class="d-menu-item <?= ($activePage ?? '') === 'dashboard' ? 'active' : '' ?>" href="<?= ROOT ?>/medical-school">
        <span class="d-menu-item__icon"><i class="fas fa-home"></i></span> Dashboard
      </a>
    </div>
    <div class="d-menu-section">
      <div class="d-menu-section__title">Pre-Death Management</div>
      <a class="d-menu-item <?= ($activePage ?? '') === 'consents' ? 'active' : '' ?>" href="<?= ROOT ?>/medical-school/consents">
        <span class="d-menu-item__icon"><i class="fas fa-file-signature"></i></span> Consent Forms
        <span class="d-menu-item__badge">4</span>
      </a>
      <a class="d-menu-item <?= ($activePage ?? '') === 'withdrawals' ? 'active' : '' ?>" href="<?= ROOT ?>/medical-school/withdrawals">
        <span class="d-menu-item__icon"><i class="fas fa-user-times"></i></span> Withdrawn
      </a>
    </div>
    <div class="d-menu-section">
      <div class="d-menu-section__title">Post-Death Management</div>
      <a class="d-menu-item <?= ($activePage ?? '') === 'submissions' ? 'active' : '' ?>" href="<?= ROOT ?>/medical-school/submissions">
        <span class="d-menu-item__icon"><i class="fas fa-folder-open"></i></span> Submissions
        <span class="d-menu-item__badge">3</span>
      </a>
      <a class="d-menu-item <?= ($activePage ?? '') === 'body-acceptance' ? 'active' : '' ?>" href="<?= ROOT ?>/medical-school/body-acceptance">
        <span class="d-menu-item__icon"><i class="fas fa-hand-holding-medical"></i></span> Body Acceptance
      </a>
      <a class="d-menu-item <?= ($activePage ?? '') === 'usage-logs' ? 'active' : '' ?>" href="<?= ROOT ?>/medical-school/usage-logs">
        <span class="d-menu-item__icon"><i class="fas fa-clipboard-list"></i></span> Usage Logs
      </a>
    </div>
    <div class="d-menu-section">
      <div class="d-menu-section__title">Records</div>
      <a class="d-menu-item <?= ($activePage ?? '') === 'certificates' ? 'active' : '' ?>" href="<?= ROOT ?>/medical-school/certificates">
        <span class="d-menu-item__icon"><i class="fas fa-file-certificate"></i></span> Certificates
      </a>
      <a class="d-menu-item <?= ($activePage ?? '') === 'archived' ? 'active' : '' ?>" href="<?= ROOT ?>/medical-school/archived">
        <span class="d-menu-item__icon"><i class="fas fa-archive"></i></span> Archived
      </a>
      <a class="d-menu-item <?= ($activePage ?? '') === 'reports' ? 'active' : '' ?>" href="<?= ROOT ?>/medical-school/reports">
        <span class="d-menu-item__icon"><i class="fas fa-chart-bar"></i></span> Reports
      </a>
    </div>
  </nav>
</aside>
