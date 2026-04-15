<div id="eligibility" class="content-section" style="display: none;">
                    <div class="content-header">
                        <h2>Update Donor Eligibility</h2>
                        <p>Update donor eligibility status after medical evaluations and screening.</p>
                    </div>
                    <div class="content-body">
                        <div class="search-bar">
                            <span class="search-icon">🔍</span>
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
                                            <div class="table-cell" data-label="Test Date"><?= htmlspecialchars(isset($p->pledge_date) ? date('d/m/Y', strtotime($p->pledge_date)) : 'N/A') ?></div>
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
                                            <div class="table-cell" data-label="Actions" style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                                <button class="btn btn-secondary btn-small" onclick="viewDonorLabData('<?= htmlspecialchars($p->nic_number ?? '') ?>')">View Labs</button>
                                                <?php if ($pledgeStatus === 'UPLOADED'): ?>
                                                    <button class="btn btn-success btn-small" onclick="approveEligibility('<?= (int)($p->pledge_id ?? 0) ?>')">Approve</button>
                                                    <button class="btn btn-danger btn-small" onclick="rejectEligibility('<?= (int)($p->pledge_id ?? 0) ?>')">Reject</button>
                                                <?php else: ?>
                                                    <span style="align-self:center; color:#64748b; font-weight:600;">No actions</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="table-row">
                                        <div class="table-cell" style="text-align:center; color:#999; grid-column: 1 / -1;">
                                            No eligibility pledges found for this hospital.
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>