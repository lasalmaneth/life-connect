<?php
  $pageTitle = 'Withdrawn Consents';
  $activePage = 'withdrawals';
  $pageIcon = 'fas fa-user-times';
  $pageHeading = 'Withdrawn Consents';
  $pageDesc = 'Donors who cancelled their donation willingness';
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
          <input type="text" class="d-search__input" placeholder="Search by Donor ID, Name...">
        </div>
        <button class="d-btn d-btn--primary d-btn--sm">Search</button>
      </div>

      <div class="d-filters">
        <button class="d-filter active">All (2)</button>
        <button class="d-filter">Pending Acknowledgment (1)</button>
        <button class="d-filter">Confirmed (1)</button>
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
                No withdrawn consents found.
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($donors as $donor): ?>
              <tr onclick="viewDonorDetails(<?= $donor->id ?>, 'withdrawn')">
                <td><strong><?= htmlspecialchars($donor->id) ?></strong></td>
                <td><?= htmlspecialchars($donor->first_name . ' ' . $donor->last_name) ?></td>
                <td><span class="d-status d-status--neutral"><span class="d-status__dot"></span>Withdrawn</span></td>
                <td>
                  <div class="d-actions">
                    <button class="d-btn d-btn--outline d-btn--sm" onclick="event.stopPropagation();viewLetter('withdrawal')">View Letter</button>
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
<div id="approveWithdrawalModal" class="d-modal">
  <div class="d-modal__body">
    <div class="d-modal__header">
      <h3 class="d-modal__title"><i class="fas fa-check-circle"></i> Approve Withdrawal Request</h3>
      <button onclick="closeModal('approveWithdrawalModal')" class="d-modal__close"><i class="fas fa-times"></i></button>
    </div>
    <p class="d-modal__text">You are approving this donor's withdrawal request. A withdrawal confirmation letter will be sent.</p>
    <div class="d-form-group">
      <label class="d-label">Approval Date</label>
      <input type="date" class="d-input" id="withdrawalApprovalDate" required>
    </div>
    <div class="d-form-group">
      <label class="d-label">Notes (Optional)</label>
      <textarea class="d-input" rows="3" id="withdrawalNotes" placeholder="Any additional notes about this withdrawal..."></textarea>
    </div>
    <div class="d-callout">
      <strong>What happens next:</strong>
      <ul style="margin-top:0.5rem; padding-left:1.2rem;">
        <li>Withdrawal confirmation letter will be generated</li>
        <li>Donor/Custodian will receive email notification</li>
        <li>Pre-death consent becomes invalid</li>
      </ul>
    </div>
    <div class="d-modal__actions">
      <button class="d-btn d-btn--outline" onclick="closeModal('approveWithdrawalModal')">Cancel</button>
      <button class="d-btn d-btn--success" onclick="confirmApproveWithdrawal()">Approve Withdrawal</button>
    </div>
  </div>
</div>

<div id="rejectWithdrawalModal" class="d-modal">
  <div class="d-modal__body">
    <div class="d-modal__header">
      <h3 class="d-modal__title"><i class="fas fa-times-circle"></i> Reject Withdrawal Request</h3>
      <button onclick="closeModal('rejectWithdrawalModal')" class="d-modal__close"><i class="fas fa-times"></i></button>
    </div>
    <p class="d-modal__text">You are rejecting this donor's withdrawal request. Please provide a clear reason.</p>
    <div class="d-form-group">
      <label class="d-label">Reason for Rejection</label>
      <textarea class="d-input" rows="4" id="withdrawalRejectionReason" placeholder="Please provide detailed reason why withdrawal cannot be approved..." required></textarea>
    </div>
    <div class="d-callout d-callout--danger">
      <strong>Note:</strong> Donor/Custodian will receive email with rejection reason.
    </div>
    <div class="d-modal__actions">
      <button class="d-btn d-btn--outline" onclick="closeModal('rejectWithdrawalModal')">Cancel</button>
      <button class="d-btn d-btn--danger" onclick="confirmRejectWithdrawal()">Reject Withdrawal Request</button>
    </div>
  </div>
</div>

<script src="<?= ROOT ?>/public/assets/js/medicalschools/withdrawals.js" defer></script>
<?php include 'inc/footer.view.php'; ?>
