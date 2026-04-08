<?php
  $pageTitle = 'Certificates';
  $activePage = 'certificates';
  $pageIcon = 'fas fa-file-certificate';
  $pageHeading = 'Certificates and Letters';
  $pageDesc = 'All generated letters and certificates';
?>

<?php include 'inc/header.view.php'; ?>
<?php include 'inc/sidebar.view.php'; ?>

<main class="d-content">
  <div class="d-content__header">
    <h2><i class="<?= $pageIcon ?>"></i> <?= $pageHeading ?></h2>
    <p><?= $pageDesc ?></p>
  </div>
  <div class="d-content__body">
    <div class="d-card">
      <h4 class="d-section-title"><i class="fas fa-file-signature"></i> Consent Letters (Pre-Death)</h4>
      
      <div class="d-grid d-grid--1" style="margin-bottom: 2rem;">
        <div class="d-card" style="display:flex; align-items:center; gap:1.5rem; padding: 1rem; margin-bottom: 0;">
          <div class="d-icon-box d-icon-box--danger">
            <i class="fas fa-file-pdf"></i>
          </div>
          <div style="flex:1;">
            <div style="font-weight:600;" class="d-text-primary">Consent Acknowledgment - John Doe</div>
            <div style="font-size:0.875rem;" class="d-text-muted">Generated: 21 Jan 2026 - PRE-2025-001</div>
          </div>
          <div style="display:flex; gap:0.5rem;">
            <button class="d-btn d-btn--outline d-btn--sm">View</button>
            <button class="d-btn d-btn--primary d-btn--sm">Download</button>
          </div>
        </div>

        <div class="d-card" style="display:flex; align-items:center; gap:1.5rem; padding: 1rem; margin-bottom: 0;">
          <div class="d-icon-box d-icon-box--danger">
            <i class="fas fa-file-pdf"></i>
          </div>
          <div style="flex:1;">
            <div style="font-weight:600;" class="d-text-primary">Withdrawal Letter - Jane Smith</div>
            <div style="font-size:0.875rem;" class="d-text-muted">Generated: 22 Jan 2026 - PRE-2025-004</div>
          </div>
          <div style="display:flex; gap:0.5rem;">
            <button class="d-btn d-btn--outline d-btn--sm">View</button>
            <button class="d-btn d-btn--primary d-btn--sm">Download</button>
          </div>
        </div>
      </div>

      <h4 class="d-section-title"><i class="fas fa-hand-holding-medical"></i> Post-Death Letters</h4>
      
      <div class="d-grid d-grid--1">
        <div class="d-card" style="display:flex; align-items:center; gap:1.5rem; padding: 1rem; margin-bottom: 0;">
          <div class="d-icon-box d-icon-box--primary">
            <i class="fas fa-file-certificate"></i>
          </div>
          <div style="flex:1;">
            <div style="font-weight:600;" class="d-text-primary">Body Acceptance Certificate - Mr. Anil Rathnayake</div>
            <div style="font-size:0.875rem;" class="d-text-muted">Generated: 17 Jan 2026 - BODY-2025-001</div>
          </div>
          <div style="display:flex; gap:0.5rem;">
            <button class="d-btn d-btn--outline d-btn--sm">View</button>
            <button class="d-btn d-btn--primary d-btn--sm">Download</button>
          </div>
        </div>

        <div class="d-card" style="display:flex; align-items:center; gap:1.5rem; padding: 1rem; margin-bottom: 0;">
          <div class="d-icon-box d-icon-box--primary">
            <i class="fas fa-file-certificate"></i>
          </div>
          <div style="flex:1;">
            <div style="font-weight:600;" class="d-text-primary">Appreciation Certificate - Mr. Anil Rathnayake</div>
            <div style="font-size:0.875rem;" class="d-text-muted">Generated: 20 Jan 2026 - BODY-2025-001</div>
          </div>
          <div style="display:flex; gap:0.5rem;">
            <button class="d-btn d-btn--outline d-btn--sm">View</button>
            <button class="d-btn d-btn--primary d-btn--sm">Download</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>

<script src="<?= ROOT ?>/public/assets/js/medicalschools/certificates.js" defer></script>
<?php include 'inc/footer.view.php'; ?>
