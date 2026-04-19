<!-- Deceased Request Detail View (AJAX Body) -->
<div class="cp-drawer-content">
    <?php if (!$request): ?>
        <div class="cp-alert cp-alert--danger">Request details not found.</div>
    <?php else: ?>
        <div class="cp-detail-group">
            <h3 class="cp-detail-group__title">Organs & Case Profile</h3>
            <div class="cp-detail-grid">
                <div class="cp-detail-item">
                    <div class="cp-detail-label">Donor Name</div>
                    <div class="cp-detail-value"><?= htmlspecialchars($request->first_name . ' ' . $request->last_name) ?></div>
                </div>
                <div class="cp-detail-item">
                    <div class="cp-detail-label">NIC Number</div>
                    <div class="cp-detail-value"><?= htmlspecialchars($request->nic_number) ?></div>
                </div>
                <div class="cp-detail-item">
                    <div class="cp-detail-label">Age / Gender</div>
                    <div class="cp-detail-value">
                        <?php 
                            $dob = new DateTime($request->date_of_birth);
                            $now = new DateTime();
                            echo $dob->diff($now)->y . 'Y / ' . htmlspecialchars($request->gender);
                        ?>
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

        <div class="cp-detail-group">
            <h3 class="cp-detail-group__title">Legal Custodian Information</h3>
            <?php if (empty($custodians)): ?>
                <div class="cp-alert cp-alert--warning">No custodian details available.</div>
            <?php else: ?>
                <?php foreach ($custodians as $c): ?>
                    <div class="cp-detail-grid" style="margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid #f1f5f9;">
                        <div class="cp-detail-item">
                            <div class="cp-detail-label">Name</div>
                            <div class="cp-detail-value"><?= htmlspecialchars($c->name) ?></div>
                        </div>
                        <div class="cp-detail-item">
                            <div class="cp-detail-label">Relationship</div>
                            <div class="cp-detail-value"><?= htmlspecialchars($c->relationship) ?></div>
                        </div>
                        <div class="cp-detail-item">
                            <div class="cp-detail-label">Phone</div>
                            <div class="cp-detail-value"><?= htmlspecialchars($c->phone) ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <?php if ($request->request_status === 'PENDING' || $request->request_status === 'UNDER_REVIEW'): ?>
            <div class="cp-drawer-footer" style="display: flex; gap: 1rem; margin-top: 2rem;">
                <form method="POST" action="<?= ROOT ?>/hospital/deceased-requests/accept" style="flex: 1;">
                    <input type="hidden" name="request_id" value="<?= $request->cis_id ?>">
                    <button type="submit" class="cp-btn cp-btn--primary" style="width: 100%;">
                        <i class="fas fa-check cp-mr-2"></i> Accept Retrieval
                    </button>
                </form>
                <button type="button" class="cp-btn cp-btn--danger" style="flex: 1;" onclick="document.getElementById('rejectionForm').style.display='block'; this.style.display='none';">
                    <i class="fas fa-times cp-mr-2"></i> Reject Request
                </button>
            </div>

            <div id="rejectionForm" style="display: none; margin-top: 2rem; padding: 1.5rem; background: #fff1f2; border-radius: 12px; border: 1px solid #fecdd3;">
                <h4 style="color: #9f1239; margin-bottom: 1rem;">Reason for Rejection</h4>
                <form method="POST" action="<?= ROOT ?>/hospital/deceased-requests/reject">
                    <input type="hidden" name="request_id" value="<?= $request->cis_id ?>">
                    <textarea name="reason" class="cp-input" rows="3" placeholder="Explain why this organ cannot be retrieved (e.g., Medical contraindications, Logistics...)" required></textarea>
                    <div style="display: flex; gap: 0.5rem; margin-top: 1rem;">
                        <button type="button" class="cp-btn cp-btn--secondary" onclick="document.getElementById('rejectionForm').style.display='none';">Cancel</button>
                        <button type="submit" class="cp-btn cp-btn--danger">Confirm Reject</button>
                    </div>
                </form>
            </div>
        <?php else: ?>
            <div class="cp-alert cp-alert--info">
                This request was <strong><?= htmlspecialchars($request->request_status) ?></strong> on <?= date('d/m/Y', strtotime($request->request_action_at)) ?>.
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
