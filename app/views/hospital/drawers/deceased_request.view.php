<div class="dr-content">
    <?php if (!$request): ?>
        <div class="dr-alert-box dr-alert-box--danger">
            <div class="dr-alert-box__title"><i class="fas fa-exclamation-circle"></i> Error</div>
            <div class="dr-alert-box__main">Request details not found or inaccessible.</div>
        </div>
    <?php else: ?>
        <!-- Premium Header -->
        <div class="dr-header">
            <div class="dr-header__inner">
                <div class="dr-header__top">
                    <span class="dr-tag">Recovery Request: <?= htmlspecialchars($request->requested_organs ?: 'Organs Pending') ?></span>
                    <span class="dr-badge <?= $request->request_status === 'REJECTED' ? 'dr-badge--danger' : ($request->request_status === 'ACCEPTED' ? 'dr-badge--success' : 'dr-badge--pending') ?>">
                        <?= htmlspecialchars($request->request_status) ?>
                    </span>
                </div>
                <h3>Case #<?= htmlspecialchars($request->case_number) ?></h3>
                <p><?= htmlspecialchars($request->first_name . ' ' . $request->last_name) ?></p>
            </div>
        </div>

        <!-- Section 1: Donor Profile -->
        <div class="dr-section">
            <div class="dr-section-title">
                <span><i class="fas fa-user-circle"></i> Donor Profile</span>
            </div>
            
            <div class="dr-card">
                <div class="dr-grid dr-grid--2">
                    <div class="dr-label-group">
                        <span class="dr-label">Full Name</span>
                        <div class="dr-value--sub"><?= htmlspecialchars($request->first_name . ' ' . $request->last_name) ?></div>
                    </div>
                    <div class="dr-label-group">
                        <span class="dr-label">NIC Number</span>
                        <div class="dr-value--sub"><?= htmlspecialchars($request->nic_number) ?></div>
                    </div>
                    <div class="dr-label-group">
                        <span class="dr-label">Age / Gender</span>
                        <div class="dr-value--sub">
                            <?php 
                                $dob = new DateTime($request->date_of_birth);
                                $now = new DateTime();
                                echo $dob->diff($now)->y . 'Y / ' . htmlspecialchars($request->gender);
                            ?>
                        </div>
                    </div>
                    <div class="dr-label-group">
                        <span class="dr-label">Blood Group</span>
                        <div class="dr-value--sub"><?= htmlspecialchars($request->blood_group ?: 'N/A') ?></div>
                    </div>
                    <div class="dr-label-group">
                        <span class="dr-label">Date of Death</span>
                        <div class="dr-value--sub"><?= date('M d, Y', strtotime($request->date_of_death)) ?></div>
                    </div>
                </div>
                <div class="cp-detail-item">
                    <div class="cp-detail-label">Case Number</div>
                    <div class="cp-detail-value"><?= htmlspecialchars($request->case_number) ?></div>
                </div>
                <div class="cp-detail-item">
                    <div class="cp-detail-label">Blood Group</div>
                    <div class="cp-detail-value"><?= htmlspecialchars($request->blood_group ?: 'N/A') ?></div>
                </div>
                <div class="cp-detail-item">
                    <div class="cp-detail-label">Date of Death</div>
                    <div class="cp-detail-value"><?= date('d/m/Y', strtotime($request->date_of_death)) ?></div>
                </div>
            </div>
        </div>

        <!-- Section 2: Legal Custodians -->
        <div class="dr-section">
            <div class="dr-section-title">
                <span><i class="fas fa-users"></i> Legal Custodian Team</span>
                <span class="dr-count-badge"><?= count($custodians) ?></span>
            </div>
            
            <div class="dr-item-list">
                <?php if (empty($custodians)): ?>
                    <div class="dr-empty-state">
                        <i class="fas fa-user-slash"></i>
                        <p class="dr-empty-state__title">No Custodians Assigned</p>
                        <p class="dr-empty-state__sub">Contact the central registry if this is unexpected.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($custodians as $index => $c): ?>
                        <div class="dr-item">
                            <span class="dr-item-marker">#<?= $index + 1 ?></span>
                            <div class="dr-item__header">
                                <i class="fas fa-id-card"></i>
                                <?= htmlspecialchars($c->name) ?>
                            </div>
                            <div class="dr-grid dr-grid--2 mt-2">
                                <div class="dr-label-group">
                                    <span class="dr-label">Relationship</span>
                                    <div class="dr-value--small"><?= htmlspecialchars($c->relationship) ?></div>
                                </div>
                                <div class="dr-label-group">
                                    <span class="dr-label">Contact</span>
                                    <div class="dr-value--small"><?= htmlspecialchars($c->phone) ?></div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Section 3: Workflow Actions -->
        <?php if ($request->request_status === 'PENDING' || $request->request_status === 'UNDER_REVIEW'): ?>
            <div class="dr-workflow-box">
                <div class="dr-tabs">
                    <button class="dr-tab-btn active" onclick="toggleHospitalAction('accept')">Accept Retrieval</button>
                    <button class="dr-tab-btn dr-tab-btn--danger" onclick="toggleHospitalAction('reject')">Reject Request</button>
                </div>

                <!-- Accept Action -->
                <div id="action-accept" class="dr-workflow-action active">
                    <div class="dr-form-area dr-form-area--success">
                        <p class="dr-label" style="color: var(--green-700); font-weight: 700;">
                            Confirming this request initiates the organ retrieval protocol.
                        </p>
                    </div>
                    <form method="POST" action="<?= ROOT ?>/hospital/deceased-requests/accept">
                        <input type="hidden" name="request_id" value="<?= $request->cis_id ?>">
                        <button type="submit" class="cp-btn cp-btn--primary dr-btn-full">
                            <i class="fas fa-check-circle mr-2"></i> Approve & Start Retrieval
                        </button>
                    </form>
                </div>

                <!-- Reject Action -->
                <div id="action-reject" class="dr-workflow-action">
                    <div class="dr-form-area dr-form-area--danger">
                        <form method="POST" action="<?= ROOT ?>/hospital/deceased-requests/reject">
                            <input type="hidden" name="request_id" value="<?= $request->cis_id ?>">
                            <div class="dr-label-group">
                                <label class="dr-slim-label">Formal Rejection Reason</label>
                                <select name="reason" class="dr-slim-input" required onchange="checkOtherReason(this)">
                                    <option value="">-- Select a Reason --</option>
                                    <option value="Medical Contraindications">Medical Contraindications</option>
                                    <option value="Logistical Constraints">Logistical Constraints</option>
                                    <option value="Equipment Failure">Equipment Failure</option>
                                    <option value="Staff Unavailable">Specialized Staff Unavailable</option>
                                    <option value="Other">Other (Specify below)</option>
                                </select>
                            </div>
                            <div id="otherReasonWrap" style="display:none;" class="mt-2">
                                <textarea name="other_reason" class="dr-slim-textarea" placeholder="Provide detailed explanation..."></textarea>
                            </div>
                            <button type="submit" class="cp-btn cp-btn--danger dr-btn-full mt-4">
                                <i class="fas fa-times-circle mr-2"></i> Confirm Rejection
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="dr-alert-box <?= $request->request_status === 'REJECTED' ? 'dr-alert-box--danger' : 'dr-alert-box--info' ?>">
                <div class="dr-alert-box__title">
                    <i class="fas <?= $request->request_status === 'REJECTED' ? 'fa-times-circle' : 'fa-check-circle' ?>"></i>
                    Request Logged
                </div>
                <div class="dr-alert-box__main">
                    This request was marked as <strong><?= htmlspecialchars($request->request_status) ?></strong>.
                </div>
                <div class="dr-alert-box__meta">
                    Action performed on <?= date('M d, Y', strtotime($request->request_action_at)) ?>
                    <?php if ($request->rejection_reason): ?>
                        <br>Reason: <?= htmlspecialchars($request->rejection_reason) ?>
                    <?php endif; ?>
                </div>
            <div class="cp-alert cp-alert--info">
                This request was <strong><?= htmlspecialchars($request->request_status) ?></strong> on <?= date('d/m/Y', strtotime($request->request_action_at)) ?>.
            </div>
        <?php endif; ?>

    <?php endif; ?>
</div>

<script>
function toggleHospitalAction(type) {
    document.querySelectorAll('.dr-workflow-action').forEach(el => el.classList.remove('active'));
    document.querySelectorAll('.dr-tab-btn').forEach(el => el.classList.remove('active'));
    
    document.getElementById('action-' + type).classList.add('active');
    event.currentTarget.classList.add('active');
}

function checkOtherReason(select) {
    const wrap = document.getElementById('otherReasonWrap');
    if (select.value === 'Other') {
        wrap.style.display = 'block';
        wrap.querySelector('textarea').required = true;
    } else {
        wrap.style.display = 'none';
        wrap.querySelector('textarea').required = false;
    }
}
</script>
