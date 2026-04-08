<?php
  $pageTitle = 'Reports';
  $activePage = 'reports';
  $pageIcon = 'fas fa-chart-bar';
  $pageHeading = 'Reports & Analytics';
  $pageDesc = 'Performance metrics and usage statistics';
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
      <h4 class="d-section-title">Overall Statistics</h4>
      <div class="d-grid d-grid--2" style="margin-bottom: 2rem;">
        <div class="d-card d-text-center">
          <div class="d-text-xl d-text-primary" style="margin-bottom: 0.5rem;"><i class="fas fa-file-signature"></i></div>
          <div class="d-text-lg d-text-primary">19</div>
          <div class="d-text-sm d-text-muted">Total Pre-Death Consents</div>
        </div>
        <div class="d-card d-text-center">
          <div class="d-text-xl d-text-success" style="margin-bottom: 0.5rem;"><i class="fas fa-hand-holding-medical"></i></div>
          <div class="d-text-lg d-text-success">127</div>
          <div class="d-text-sm d-text-muted">Bodies Accepted</div>
        </div>
        <div class="d-card d-text-center">
          <div class="d-text-xl" style="color: #6366f1; margin-bottom: 0.5rem;"><i class="fas fa-user-graduate"></i></div>
          <div class="d-text-lg" style="color: #6366f1;">2,450</div>
          <div class="d-text-sm d-text-muted">Students Educated</div>
        </div>
        <div class="d-card d-text-center">
          <div class="d-text-xl d-text-muted" style="margin-bottom: 0.5rem;"><i class="fas fa-archive"></i></div>
          <div class="d-text-lg d-text-muted">45</div>
          <div class="d-text-sm d-text-muted">Archived Records</div>
        </div>
      </div>

      <h4 class="d-section-title">Monthly Breakdown</h4>
      <div class="d-card d-text-center" style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 4rem; background: var(--g50); border: 2px dashed var(--g300);">
        <i class="fas fa-chart-line" style="font-size: 3rem; color: var(--g400); margin-bottom: 1.5rem;"></i>
        <p style="color: var(--g500); font-size: 1.125rem; max-width: 400px; margin: 0;">Analytics charts and detailed reports can be generated here. Integration with charting libraries is planned for the next phase.</p>
        <button class="d-btn d-btn--primary" style="margin-top: 2rem;"><i class="fas fa-file-export"></i> Export All Data</button>
      </div>
    </div>
  </div>
</main>

<script src="<?= ROOT ?>/public/assets/js/medicalschools/reports.js" defer></script>
<?php include 'inc/footer.view.php'; ?>
