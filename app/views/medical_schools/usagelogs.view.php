<?php
  $pageTitle = 'Usage Logs';
  $activePage = 'usagelogs';
  $pageIcon = 'fas fa-clipboard-list';
  $pageHeading = 'Usage Logs and Tracking';
  $pageDesc = 'Record and track body donation usage';
?>

<?php include 'inc/header.view.php'; ?>
<?php include 'inc/sidebar.view.php'; ?>

<main class="d-content">
  <div class="d-content__header">
    <h2><i class="<?= $pageIcon ?>"></i> <?= $pageHeading ?></h2>
    <p><?= $pageDesc ?></p>
  </div>
  <div class="d-content__body">
    <div class="d-card" style="margin-bottom: 2rem;">
      <h4 class="d-card__title"><i class="fas fa-plus-circle"></i> Log New Usage</h4>
      <div class="d-grid d-grid--2" style="margin-top: 1rem;">
        <div class="d-form-group">
          <label class="d-label">Select Donor</label>
          <select class="d-input">
            <?php if (empty($donors)): ?>
              <option disabled selected>No accepted bodies available</option>
            <?php else: ?>
              <?php foreach ($donors as $donor): ?>
                <option value="<?= $donor->id ?>"><?= htmlspecialchars($donor->first_name . ' ' . $donor->last_name . ' (' . $donor->id . ')') ?></option>
              <?php endforeach; ?>
            <?php endif; ?>
          </select>
        </div>
        <div class="d-form-group">
          <label class="d-label">Department</label>
          <select class="d-input">
            <option>Anatomy Department</option>
            <option>Histology Department</option>
          </select>
        </div>
        <div class="d-form-group">
          <label class="d-label">Usage Type</label>
          <select class="d-input">
            <option>Education - Anatomy Lab</option>
            <option>Research - Medical Study</option>
          </select>
        </div>
        <div class="d-form-group">
          <label class="d-label">Students</label>
          <input type="number" class="d-input" placeholder="35">
        </div>
      </div>
      <div style="margin-top: 1.5rem;">
        <button class="d-btn d-btn--success">Add Usage Log</button>
      </div>
    </div>

    <h4 class="d-section-title">Recent Usage Records</h4>
    
    <div class="d-grid d-grid--1">
      <?php if (empty($donors)): ?>
        <div class="d-card" style="text-align:center; padding: 2rem; color: var(--text-secondary);">
          No active usage logs found.
        </div>
      <?php else: ?>
        <?php foreach ($donors as $donor): ?>
          <div class="d-card">
            <div class="d-flex-between" style="margin-bottom: 1.5rem;">
              <div>
                <h3 style="margin:0; font-size:1.25rem;" class="d-text-primary"><?= htmlspecialchars($donor->first_name . ' ' . $donor->last_name) ?></h3>
                <span class="d-text-muted" style="font-size:0.875rem;"><?= htmlspecialchars($donor->id) ?></span>
              </div>
              <span class="d-status d-status--success"><span class="d-status__dot"></span>In Use</span>
            </div>
            <div class="d-grid d-grid--3">
              <div>
                <div class="d-text-xs d-text-muted">Usage Date</div>
                <div style="font-weight:600;"><?= htmlspecialchars(date('d M, Y', strtotime($donor->usage_date))) ?></div>
              </div>
              <div>
                <div class="d-text-xs d-text-muted">Status</div>
                <div style="font-weight:600;"><?= htmlspecialchars($donor->status) ?></div>
              </div>
            </div>
            <div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid var(--g200);">
              <button class="d-btn d-btn--outline d-btn--sm" onclick="viewDonorDetails(<?= $donor->id ?>, 'body-accepted')">View Full Details</button>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</main>

<script src="<?= ROOT ?>/public/assets/js/medicalschools/usagelogs.js" defer></script>
<?php include 'inc/footer.view.php'; ?>
