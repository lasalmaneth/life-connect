<?php
  $pageTitle = 'Post-Death Submissions';
  $activePage = 'submissions';
  $pageIcon = 'fas fa-folder-open';
  $pageHeading = 'Post-Death Submissions';
  $pageDesc = 'Custodian document submissions pending review';
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
          <input type="text" class="d-search__input" placeholder="Search by Donor ID, Name, or Custodian...">
        </div>
        <button class="d-btn d-btn--primary d-btn--sm">Search</button>
      </div>

      <div class="d-filters">
        <button class="d-filter active">All (3)</button>
        <button class="d-filter">Pending Admin (1)</button>
        <button class="d-filter">Pending School (2)</button>
        <button class="d-filter">Accepted (5)</button>
        <button class="d-filter">Rejected (2)</button>
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
          <?php if (empty($donors)): ?>
            <tr>
              <td colspan="4" style="text-align:center; padding: 2rem; color: var(--text-secondary);">
                No post-death submissions found.
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($donors as $donor): ?>
              <?php 
                $statusClass = 'd-status--info';
                if ($donor->consent_status === 'PENDING') $statusClass = 'd-status--warning';
                if ($donor->consent_status === 'GIVEN') $statusClass = 'd-status--success';
              ?>
              <tr onclick="viewDonorDetails(<?= $donor->id ?>, 'post-death')">
                <td><strong><?= htmlspecialchars($donor->id) ?></strong></td>
                <td><?= htmlspecialchars($donor->first_name . ' ' . $donor->last_name) ?></td>
                <td><span class="d-status <?= $statusClass ?>"><span class="d-status__dot"></span><?= htmlspecialchars(ucfirst(strtolower($donor->consent_status))) ?></span></td>
                <td>
                  <div class="d-actions">
                    <button class="d-btn d-btn--success d-btn--sm" onclick="event.stopPropagation();acceptBody(<?= $donor->id ?>)">Accept</button>
                    <button class="d-btn d-btn--danger d-btn--sm" onclick="event.stopPropagation();requestResubmission(<?= $donor->id ?>)">Request Resubmit</button>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </table>
      </div>
    </div>
  </div>
</main>

<!-- Modals -->
<div id="acceptBodyModal" class="d-modal">
  <div class="d-modal__body">
    <div class="d-modal__header">
      <h3 class="d-modal__title"><i class="fas fa-check-circle"></i> Accept Documents & Schedule Delivery</h3>
      <button onclick="closeModal('acceptBodyModal')" class="d-modal__close"><i class="fas fa-times"></i></button>
    </div>
    <p class="d-modal__text">You are accepting the submitted documents. The custodian will be notified to bring the body for physical verification.</p>
    <div class="d-form-group">
      <label class="d-label">Scheduled Delivery Date</label>
      <input type="date" class="d-input" id="deliveryDate" required>
    </div>
    <div class="d-form-group">
      <label class="d-label">Delivery Location</label>
      <select class="d-input" id="deliveryLocation">
        <option>Anatomy Department - Main Building</option>
        <option>Anatomy Lab - Ground Floor</option>
      </select>
    </div>
    <div class="d-form-group">
      <label class="d-label">Contact Person</label>
      <input type="text" class="d-input" id="contactPerson" placeholder="Dr. Silva - 077 123 4567">
    </div>
    <div class="d-form-group">
      <label class="d-label">Delivery Instructions</label>
      <textarea class="d-input" rows="3" id="deliveryInstructions" placeholder="Please bring the body between 8:00 AM - 4:00 PM..."></textarea>
    </div>
    <div class="d-callout">
      <strong>Next Steps:</strong> Custodian is notified, perform physical verification on arrival.
    </div>
    <div class="d-modal__actions">
      <button class="d-btn d-btn--outline" onclick="closeModal('acceptBodyModal')">Cancel</button>
      <button class="d-btn d-btn--success" onclick="confirmAcceptBody()">Accept & Notify</button>
    </div>
  </div>
</div>

<div id="resubmissionModal" class="d-modal">
  <div class="d-modal__body">
    <div class="d-modal__header">
      <h3 class="d-modal__title"><i class="fas fa-redo"></i> Request Resubmission</h3>
      <button onclick="closeModal('resubmissionModal')" class="d-modal__close"><i class="fas fa-times"></i></button>
    </div>
    <div class="d-form-group">
      <label class="d-label">Reason</label>
      <select class="d-input">
        <option>Missing Document - Death Certificate</option>
        <option>Document Quality Issue</option>
        <option>Other</option>
      </select>
    </div>
    <div class="d-form-group">
      <label class="d-label">Explanation</label>
      <textarea class="d-input" rows="3" placeholder="Explain what needs to be corrected..."></textarea>
    </div>
    <div class="d-modal__actions">
      <button class="d-btn d-btn--outline" onclick="closeModal('resubmissionModal')">Cancel</button>
      <button class="d-btn d-btn--warning" onclick="confirmResubmission()">Send Request</button>
    </div>
  </div>
</div>

<script src="<?= ROOT ?>/public/assets/js/medicalschools/submissions.js" defer></script>
<?php include 'inc/footer.view.php'; ?>
