<?php
  $pageTitle = 'Archived Records';
  $activePage = 'archived';
  $pageIcon = 'fas fa-archive';
  $pageHeading = 'Archived Records';
  $pageDesc = 'Historical and inactive donation records';
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
      <div class="d-table-header">
        <h4 class="d-section-title">Historical Records</h4>
        <div class="d-table-actions">
          <div class="d-search">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Search archives...">
          </div>
        </div>
      </div>

      <div class="d-table-wrapper">
        <table class="d-table">
          <thead>
            <tr>
              <th>Donor ID</th>
              <th>Donor Name</th>
              <th>Status</th>
              <th class="d-table-actions">Action</th>
            </tr>
          </thead>
          <tbody>
          <?php if (empty($donors)): ?>
            <tr>
              <td colspan="4" style="text-align:center; padding: 2rem; color: var(--text-secondary);">
                No archived records found.
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($donors as $donor): ?>
              <tr onclick="viewDonorDetails(<?= $donor->id ?>, 'archived')">
                <td><strong><?= htmlspecialchars($donor->id) ?></strong></td>
                <td><?= htmlspecialchars($donor->first_name . ' ' . $donor->last_name) ?></td>
                <td><span class="d-status d-status--neutral">Archived</span></td>
                <td class="d-table-actions"></td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</main>

<?php include 'inc/footer.view.php'; ?>
