<?php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo ROOT ?? '/life-connect'; ?>/public/assets/css/hospital/hospital.css">
    <title>Update Eligibility - Hospital Management - LifeConnect</title>
</head>
<body>
    <?php
        $current_page = 'eligibility';
    
        require_once __DIR__ . '/header.php';
    ?>

    <div class="container">
        <div class="main-content">
            <?php require_once __DIR__ . '/sidebar.php'; ?>

            <div class="content-area">
                <div class="content-section" style="display: block;">
                    <div class="content-header">
                        <h2>Update Donor Eligibility</h2>
                        <p>Update donor eligibility status after medical evaluations and screening.</p>
                    </div>
                    <div class="content-body">
                        <div class="search-bar">
                            <span class="search-icon">Search:</span>
                            <input type="text" class="search-input" placeholder="Search by donor NIC or name...">
                        </div>

                        <div class="data-table">
                            <div class="table-header">
                                <h4>Eligibility Reviews</h4>
                            </div>
                            <div class="table-content">
                                <div class="table-row" style="font-weight: 600; background: var(--gray-bg-color);">
                                    <div class="table-cell">Donor Details</div>
                                    <div class="table-cell">Organ Type</div>
                                    <div class="table-cell">Test Date</div>
                                    <div class="table-cell">Current Status</div>
                                    <div class="table-cell">Actions</div>
                                </div>

                                <?php if (!empty($eligibility_pledges ?? [])): ?>
                                    <?php foreach (($eligibility_pledges ?? []) as $p): ?>
                                        <?php $pledgeStatus = strtoupper(trim((string)($p->status ?? ''))); ?>
                                        <div class="table-row">
                                            <div class="table-cell name" data-label="Donor Details">
                                                NIC <?= htmlspecialchars($p->nic_number ?? 'N/A') ?> -
                                                <?= htmlspecialchars(trim(($p->first_name ?? '') . ' ' . ($p->last_name ?? '')) ?: 'N/A') ?>
                                            </div>
                                            <div class="table-cell" data-label="Organ Type"><?= htmlspecialchars($p->organ_name ?? 'N/A') ?></div>
                                            <div class="table-cell" data-label="Test Date"><?= htmlspecialchars(isset($p->pledge_date) ? date('Y-m-d', strtotime($p->pledge_date)) : 'N/A') ?></div>
                                            <div class="table-cell" data-label="Current Status">
                                                <?php if ($pledgeStatus === 'PENDING'): ?>
                                                    <span class="status-badge status-pending">Pending Upload</span>
                                                <?php elseif ($pledgeStatus === 'UPLOADED'): ?>
                                                    <span class="status-badge status-pending">Under Review</span>
                                                <?php elseif ($pledgeStatus === 'APPROVED'): ?>
                                                    <span class="status-badge status-success">Approved</span>
                                                <?php elseif ($pledgeStatus === 'IN_PROGRESS'): ?>
                                                    <span class="status-badge status-active">In Progress</span>
                                                <?php else: ?>
                                                    <span class="status-badge status-pending"><?= htmlspecialchars($pledgeStatus ?: 'UNKNOWN') ?></span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="table-cell" data-label="Actions">
                                                <?php if ($pledgeStatus === 'UPLOADED'): ?>
                                                    <div style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: nowrap;">
                                                        <button class="btn btn-success btn-small" onclick="approveEligibility('<?= (int)($p->pledge_id ?? 0) ?>')" style="white-space: nowrap;">Approve</button>
                                                        <button class="btn btn-danger btn-small" onclick="rejectEligibility('<?= (int)($p->pledge_id ?? 0) ?>')" style="white-space: nowrap;">Reject</button>
                                                    </div>
                                                <?php else: ?>
                                                    <span style="color:#64748b; font-weight:600;">No actions</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="table-row">
                                        <div class="table-cell" style="text-align: center; color: #999; grid-column: 1 / -1;">No eligibility pledges found for this hospital.</div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer style="background: linear-gradient(135deg, #005baa 0%, #003b6e 100%); color: white; text-align: center; padding: 20px; margin-top: 40px; box-shadow: 0 -4px 20px rgba(0, 91, 170, 0.2);">
        <p style="margin: 0; font-size: 14px;">Copyright © 2025 Ministry of Health - LifeConnect Sri Lanka</p>
    </footer>

    <form id="eligibilityActionForm" method="POST" action="<?php echo rtrim((ROOT ?? '/life-connect'), '/'); ?>/hospital/eligibility" style="display:none">
        <input type="hidden" name="action" id="eligibilityAction" value="">
        <input type="hidden" name="pledge_id" id="eligibilityPledgeId" value="">
    </form>

    <?php require_once __DIR__ . '/footer.php'; ?>

    <script>
        function postEligibilityAction(action, pledgeId) {
            const form = document.getElementById('eligibilityActionForm');
            const actionInput = document.getElementById('eligibilityAction');
            const pledgeInput = document.getElementById('eligibilityPledgeId');

            if (!form || !actionInput || !pledgeInput) {
                showServerMessage('Unable to submit action.', 'error');
                return;
            }

            actionInput.value = String(action || '');
            pledgeInput.value = String(pledgeId || '');
            showServerMessage('Updating eligibility...', 'info');
            form.submit();
        }

        function approveEligibility(pledgeId) {
            const id = parseInt(pledgeId, 10) || 0;
            if (!id) return showServerMessage('Invalid pledge.', 'error');

            hcConfirm('Approve this donor eligibility?').then((ok) => {
                if (!ok) return;
                postEligibilityAction('approve_eligibility', id);
            });
        }

        function rejectEligibility(pledgeId) {
            const id = parseInt(pledgeId, 10) || 0;
            if (!id) return showServerMessage('Invalid pledge.', 'error');

            hcConfirm('Reject this donor eligibility?', { danger: true }).then((ok) => {
                if (!ok) return;
                postEligibilityAction('reject_eligibility', id);
            });
        }
    </script>
</body>
</html>
