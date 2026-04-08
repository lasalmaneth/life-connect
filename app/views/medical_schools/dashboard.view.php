<?php
  $pageTitle = 'Dashboard';
  $activePage = 'dashboard';
  $pageIcon = 'fas fa-home';
  $pageHeading = 'Dashboard Overview';
  $pageDesc = 'Complete body donation workflow management';
?>

<?php include 'inc/header.view.php'; ?>
<?php include 'inc/sidebar.view.php'; ?>

<main class="d-content">
  <div class="d-content__header">
    <h2><i class="<?= $pageIcon ?>"></i> <?= $pageHeading ?></h2>
    <p><?= $pageDesc ?></p>
  </div>
  <div class="d-content__body">
    
    <!-- Compact Summary Bar -->
    <div class="d-summary-bar">
      <div class="d-summary-item">
        <div class="d-summary-item__icon"><i class="fas fa-file-signature"></i></div>
        <div class="d-summary-item__stats">
          <h4>Pre-Death Consents</h4>
          <span><?= htmlspecialchars($stats['pre_accepted'] ?? 0) ?></span>
        </div>
      </div>
      <div class="d-summary-item">
        <div class="d-summary-item__icon"><i class="fas fa-procedures"></i></div>
        <div class="d-summary-item__stats">
          <h4>Received Bodies</h4>
          <span><?= htmlspecialchars($stats['bodies_accepted'] ?? 0) ?></span>
        </div>
      </div>
      <div class="d-summary-item">
        <div class="d-summary-item__icon"><i class="fas fa-microscope"></i></div>
        <div class="d-summary-item__stats">
          <h4>Currently In Use</h4>
          <span><?= htmlspecialchars($stats['in_use'] ?? 0) ?></span>
        </div>
      </div>
      <div class="d-summary-item">
        <div class="d-summary-item__icon" style="background: #fee2e2; color: #ef4444;"><i class="fas fa-exclamation-circle"></i></div>
        <div class="d-summary-item__stats">
          <h4 style="color: #ef4444;">Pending Actions</h4>
          <span><?= htmlspecialchars(($stats['pre_pending'] ?? 0) + ($stats['post_pending'] ?? 0)) ?></span>
        </div>
      </div>
    </div>

    <!-- Dashboard Main Grid -->
    <div class="d-dashboard-grid">
      
      <!-- Widget 1: Active Transfers Tracker -->
      <div class="d-widget">
        <div class="d-widget__header">
          <div class="d-widget__title"><i class="fas fa-truck-moving text-accent"></i> Active Transfers</div>
        </div>
        <div class="d-widget__body" style="background: var(--white);">
          <ul class="d-timeline">
            <?php if(empty($activeTransfers)): ?>
              <li class="completed">
                <div class="d-timeline__date">No Active Transfers</div>
                <div class="d-timeline__content" style="color: var(--g500);">All bodies safely received.</div>
              </li>
            <?php else: ?>
              <?php foreach(array_slice($activeTransfers, 0, 4) as $transfer): ?>
              <li>
                <div class="d-timeline__date">Awaiting Transfer from <?= htmlspecialchars($transfer->custodian_name ?? 'Hospital') ?></div>
                <div class="d-timeline__content">Donor #<?= htmlspecialchars($transfer->id) ?> - <?= htmlspecialchars($transfer->first_name) ?></div>
              </li>
              <?php endforeach; ?>
            <?php endif; ?>
          </ul>
        </div>
      </div>

      <!-- Widget 2: Active Lab Usage Matrix -->
      <div class="d-widget">
        <div class="d-widget__header">
          <div class="d-widget__title"><i class="fas fa-vial text-accent"></i> Active Lab Usage Matrix</div>
        </div>
        <div class="d-widget__body">
          <?php if(empty($usageMatrix)): ?>
            <div class="d-empty" style="padding: 1rem 0;">
              <p>No bodies currently in use.</p>
            </div>
          <?php else: ?>
            <?php foreach($usageMatrix as $row): ?>
            <div class="d-matrix-item">
              <span class="d-matrix-item__label"><?= htmlspecialchars($row->usage_type ?: 'General Anatomy') ?></span>
              <span class="d-matrix-item__value"><?= htmlspecialchars($row->count) ?> Bodies</span>
            </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>

      <!-- Widget 3: Intake vs Requirement Chart -->
      <div class="d-widget">
        <div class="d-widget__header">
          <div class="d-widget__title"><i class="fas fa-chart-pie text-accent"></i> Semester Intake Quota</div>
        </div>
        <div class="d-widget__body" style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
          <div style="position: relative; width: 140px; height: 140px;">
            <canvas id="quotaChart" data-intake="<?= htmlspecialchars($quotaMetrics['intake']) ?>" data-remaining="<?= htmlspecialchars($quotaMetrics['remaining']) ?>"></canvas>
            <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; display: flex; flex-direction: column; align-items: center; justify-content: center;">
              <span style="font-size: 1.5rem; font-weight: 800; color: var(--slate); line-height: 1;"><?= htmlspecialchars($quotaMetrics['intake']) ?></span>
              <span style="font-size: 0.7rem; color: var(--g500); font-weight: 600;">/ <?= htmlspecialchars($quotaMetrics['quota']) ?></span>
            </div>
          </div>
          <p style="text-align: center; margin-top: 1.25rem; font-size: 0.85rem; color: var(--g500);">Current intake over the last 6 months against the target educational requirement.</p>
        </div>
      </div>

    </div>

  </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="<?= ROOT ?>/public/assets/js/medicalschools/dashboard.js" defer></script>
<?php include 'inc/footer.view.php'; ?>
