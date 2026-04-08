<?php
  $pageTitle = 'Consent Forms';
  $activePage = 'consents';
  $pageIcon = 'fas fa-file-signature';
  $pageHeading = 'Pre-Death Consent Forms';
  $pageDesc = 'Review and manage donor willingness forms';
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
          <input type="text" class="d-search__input" placeholder="Search by Donor ID, Name, or NIC...">
        </div>
        <button class="d-btn d-btn--primary d-btn--sm">Search</button>
      </div>

      <div class="d-filters">
        <button class="d-filter active">All (4)</button>
        <button class="d-filter">Pending Admin (2)</button>
        <button class="d-filter">Pending School (2)</button>
        <button class="d-filter">Accepted (12)</button>
        <button class="d-filter">Rejected (3)</button>
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
                No pre-death consent forms available at this time.
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($donors as $donor): ?>
              <?php 
                $statusClass = 'd-status--info';
                if ($donor->consent_status === 'PENDING') $statusClass = 'd-status--warning';
                if ($donor->consent_status === 'GIVEN') $statusClass = 'd-status--success';
              ?>
              <tr onclick="viewDonorDetails(<?= $donor->id ?>, 'pre-death')">
                <td><strong><?= htmlspecialchars($donor->id) ?></strong></td>
                <td><?= htmlspecialchars($donor->first_name . ' ' . $donor->last_name) ?></td>
                <td><span class="d-status <?= $statusClass ?>"><span class="d-status__dot"></span><?= htmlspecialchars(ucfirst(strtolower($donor->consent_status))) ?></span></td>
                <td>
                  <?php if ($donor->consent_status === 'PENDING'): ?>
                  <div class="d-actions">
                    <button class="d-btn d-btn--success d-btn--sm" onclick="event.stopPropagation();approveConsent(<?= $donor->id ?>)">Accept</button>
                    <button class="d-btn d-btn--danger d-btn--sm" onclick="event.stopPropagation();rejectConsent(<?= $donor->id ?>)">Reject</button>
                  </div>
                  <?php else: ?>
                    <button class="d-btn d-btn--outline d-btn--sm" disabled><i class="fas fa-check"></i> Accepted</button>
                  <?php endif; ?>
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
<div id="consentActionModal" class="d-modal">
  <div class="d-modal__body">
    <div class="d-modal__header">
      <h3 class="d-modal__title" id="consentModalTitle"><i class="fas fa-check-circle"></i> Accept Consent</h3>
      <button onclick="closeModal('consentActionModal')" class="d-modal__close"><i class="fas fa-times"></i></button>
    </div>
    <p class="d-modal__text" id="consentModalText">You are about to accept this pre-death consent form.</p>
    
    <div class="d-form-group">
      <label class="d-label">Comments / Notes</label>
      <textarea class="d-input" rows="3" placeholder="Any additional notes..."></textarea>
    </div>

    <div class="d-modal__actions">
      <button class="d-btn d-btn--outline" onclick="closeModal('consentActionModal')">Cancel</button>
      <button class="d-btn d-btn--success" id="consentConfirmBtn" onclick="confirmConsentAction()">Confirm</button>
    </div>
  </div>
</div>

<script src="<?= ROOT ?>/public/assets/js/medicalschools/consentforms.js" defer></script>
<?php include 'inc/footer.view.php'; ?>
