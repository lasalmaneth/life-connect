<?php
  $pageTitle = 'Body Acceptance';
  $activePage = 'bodyacceptance';
  $pageIcon = 'fas fa-hand-holding-medical';
  $pageHeading = 'Body Acceptance & Handover';
  $pageDesc = 'Physical verification and body acceptance tracking';
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
      <div class="d-search-bar">
        <div class="d-search">
          <i class="fas fa-search d-search__icon"></i>
          <input type="text" class="d-search__input" placeholder="Search by Body ID, Donor Name...">
        </div>
        <button class="d-btn d-btn--primary d-btn--sm">Search</button>
      </div>

      <div class="d-filters">
        <button class="d-filter active">All (2)</button>
        <button class="d-filter">Pending Verification (1)</button>
        <button class="d-filter">Verified (1)</button>
      </div>

      <div class="d-table-wrapper">
        <table class="d-table">
          <thead>
            <tr>
              <th>Donor ID</th>
              <th>Donor Name</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          <?php if (empty($donors)): ?>
            <tr>
              <td colspan="4" style="text-align:center; padding: 2rem; color: var(--text-secondary);">
                No accepted bodies found.
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($donors as $donor): ?>
              <?php 
                $statusClass = 'd-status--info';
                if ($donor->status === 'IN_USE') $statusClass = 'd-status--success';
              ?>
              <tr onclick="viewDonorDetails(<?= $donor->id ?>, 'body-accepted')">
                <td><strong><?= htmlspecialchars($donor->id) ?></strong></td>
                <td><?= htmlspecialchars($donor->first_name . ' ' . $donor->last_name) ?></td>
                <td><span class="d-status <?= $statusClass ?>"><span class="d-status__dot"></span><?= htmlspecialchars(ucfirst(strtolower(str_replace('_', ' ', $donor->status)))) ?></span></td>
                <td>
                  <div class="d-actions">
                    <button class="d-btn d-btn--outline d-btn--sm" onclick="event.stopPropagation();viewLetter('acceptance')">Certificate</button>
                    <button class="d-btn d-btn--primary d-btn--sm" onclick="event.stopPropagation();logUsage(<?= $donor->id ?>)">Usage</button>
                    <button class="d-btn d-btn--success d-btn--sm" onclick="event.stopPropagation();generateAppreciation(<?= $donor->id ?>)">Appreciation</button>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</main>

<!-- Modals -->
<div id="physicalVerificationModal" class="d-modal">
  <div class="d-modal__body">
    <div class="d-modal__header">
      <h3 class="d-modal__title"><i class="fas fa-check-double"></i> Complete Physical Verification</h3>
      <button onclick="closeModal('physicalVerificationModal')" class="d-modal__close"><i class="fas fa-times"></i></button>
    </div>
    <div class="d-form-group">
      <label class="d-label">Verification Date</label>
      <input type="date" class="d-input" id="verificationDate" required>
    </div>
    <div class="d-form-group">
      <label class="d-label">Body Condition</label>
      <select class="d-input" id="bodyCondition">
        <option>Excellent - Properly Embalmed</option>
        <option>Good - Acceptable Condition</option>
      </select>
    </div>
    <div class="d-form-group">
      <label class="d-label">Verified By</label>
      <input type="text" class="d-input" id="verifiedBy" placeholder="Dr. Perera">
    </div>
    <div class="d-callout d-callout--success">
      <strong>Next:</strong> Acceptance Certificate will be generated.
    </div>
    <div class="d-modal__actions">
      <button class="d-btn d-btn--outline" onclick="closeModal('physicalVerificationModal')">Cancel</button>
      <button class="d-btn d-btn--success" onclick="confirmPhysicalVerification()">Complete Verification</button>
    </div>
  </div>
</div>

<div id="appreciationModal" class="d-modal">
  <div class="d-modal__body">
    <div class="d-modal__header">
      <h3 class="d-modal__title"><i class="fas fa-heart"></i> Generate Appreciation Certificate</h3>
      <button onclick="closeModal('appreciationModal')" class="d-modal__close"><i class="fas fa-times"></i></button>
    </div>
    <div class="d-form-group">
      <label class="d-label">Usage Summary</label>
      <textarea class="d-input" rows="3" placeholder="This body was used for Anatomy education..."></textarea>
    </div>
    <div class="d-modal__actions">
      <button class="d-btn d-btn--outline" onclick="closeModal('appreciationModal')">Cancel</button>
      <button class="d-btn d-btn--success" onclick="confirmGenerateAppreciation()">Generate</button>
    </div>
  </div>
</div>

<script src="<?= ROOT ?>/public/assets/js/medicalschools/bodyacceptance.js" defer></script>
<?php include 'inc/footer.view.php'; ?>
