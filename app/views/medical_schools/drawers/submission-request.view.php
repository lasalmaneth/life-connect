<?php if (!$request): ?>
    <div class="cp-alert cp-alert--danger">Request not found or unauthorized.</div>
<?php else: ?>
    <div class="dr-section">
        <div class="flex items-center justify-between mb-4">
            <span class="dr-badge dr-badge--<?= strtolower($request->request_status) === 'pending' ? 'pending' : 'success' ?>">
                <?= htmlspecialchars($request->request_status) ?>
            </span>
            <div class="dr-doc-meta">Case #<?= htmlspecialchars($request->case_number) ?></div>
        </div>

        <h4 class="dr-section-title">
            <span><i class="fas fa-id-card"></i> Personal Information</span>
        </h4>
        <div class="dr-grid dr-grid--2">
            <div class="dr-label-group" style="grid-column: span 2;">
                <div class="dr-label">Full Name</div>
                <div class="dr-value"><?= htmlspecialchars($request->first_name . ' ' . $request->last_name) ?></div>
            </div>
            <div class="dr-label-group">
                <div class="dr-label">Date of Birth</div>
                <div class="dr-value dr-value--small"><?= $request->date_of_birth ? date('Y-m-d', strtotime($request->date_of_birth)) : 'N/A' ?></div>
            </div>
            <div class="dr-label-group">
                <div class="dr-label">Age</div>
                <div class="dr-value dr-value--small">
                    <?php 
                        if ($request->date_of_birth) {
                            $birthDate = new DateTime($request->date_of_birth);
                            $today = new DateTime();
                            $age = $today->diff($birthDate)->y;
                            echo $age;
                        } else {
                            echo 'N/A';
                        }
                    ?>
                </div>
            </div>
            <div class="dr-label-group">
                <div class="dr-label">Gender</div>
                <div class="dr-value dr-value--small"><?= strtoupper($request->gender ?? 'N/A') ?></div>
            </div>
            <div class="dr-label-group">
                <div class="dr-label">NIC Number</div>
                <div class="dr-value dr-value--small"><?= htmlspecialchars($request->nic_number ?? 'N/A') ?></div>
            </div>
            <div class="dr-label-group">
                <div class="dr-label">Nationality</div>
                <div class="dr-value dr-value--small"><?= htmlspecialchars($request->nationality ?? 'N/A') ?></div>
            </div>
            <div class="dr-label-group">
                <div class="dr-label">Date of Death</div>
                <div class="dr-value dr-value--small"><?= $request->date_of_death ? date('d M, Y', strtotime($request->date_of_death)) : 'N/A' ?></div>
            </div>
            <div class="dr-label-group">
                <div class="dr-label">Submission Date</div>
                <div class="dr-value dr-value--small"><?= date('d M, Y', strtotime($request->submission_date ?? $request->created_at)) ?></div>
            </div>
        </div>
    </div>

    <div class="dr-section">
        <h4 class="dr-section-title">
            <span><i class="fas fa-hand-holding-heart"></i> Decision Overview</span>
        </h4>
        <div class="dr-banner dr-banner--info">
            <div class="dr-banner__title-sm">Custodian Intention</div>
            <div class="dr-banner__msg">Proceed with body submission to pathology institution for medical education and research.</div>
        </div>

        <?php if ($request->request_status === 'REJECTED'): ?>
            <div class="dr-alert-box dr-alert-box--danger">
                <div class="dr-alert-box__title">
                    <i class="fas fa-times-circle"></i> Rejection Reason
                </div>
                <div class="dr-alert-box__main">
                    <?= htmlspecialchars($request->request_action_reason) ?>
                </div>
                <div class="dr-alert-box__meta">Action taken on <?= date('M d, Y', strtotime($request->request_action_at)) ?></div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Family Custodians Section (Compact Style) -->
    <div class="dr-card dr-card--blue shadow-sm mt-4">
        <div class="dr-section-title">
            <div class="flex items-center gap-2">
                <i class="fas fa-user-shield"></i>
                <span>Family Custodians</span>
            </div>
            <span class="dr-count-badge">
                <?= count($custodians) ?>
            </span>
        </div>
        
        <?php if (empty($custodians)): ?>
            <div class="dr-empty-state">
                <i class="fas fa-info-circle"></i>
                <p class="dr-empty-state__title">No family custodians assigned</p>
                <p class="dr-empty-state__sub">No specific records were found for this case.</p>
            </div>
        <?php else: ?>
            <div class="dr-item-list">
                <?php foreach ($custodians as $c): ?>
                    <div class="dr-item">
                        <span class="dr-item-marker">#</span>
                        
                        <div class="dr-item__header">
                            <?= htmlspecialchars($c->name ?? 'N/A') ?>
                            <span class="dr-item__sub">(<?= htmlspecialchars($c->relationship ?? 'N/A') ?>)</span>
                        </div>
                        
                        <div class="dr-grid dr-grid--1-5 dr-divider pt-2">
                            <div>
                                <label class="dr-label">Phone</label>
                                <div class="dr-value dr-value--accent">
                                    <?= !empty($c->phone) ? htmlspecialchars($c->phone) : 'N/A' ?>
                                </div>
                            </div>
                            <div>
                                <label class="dr-label">Email</label>
                                <div class="dr-value dr-value--small break-all">
                                    <?= !empty($c->email) ? htmlspecialchars($c->email) : 'N/A' ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <?php if ($request->request_status === 'PENDING' || $request->request_status === 'UNDER_REVIEW'): ?>
        <div class="dr-section pt-6 border-t border-gray-200 mt-6">
            <div class="dr-grid dr-grid--2 mb-4">
                <form action="<?= ROOT ?>/medical-school/submission-requests/accept" method="POST">
                    <input type="hidden" name="request_id" value="<?= $request->cis_id ?>">
                    <button type="submit" class="cp-btn cp-btn--success dr-btn-full">
                        <i class="fas fa-check mr-1"></i> Accept Request
                    </button>
                    <p class="dr-doc-meta text-center mt-2 px-1">Approves outreach and unlocks document submission.</p>
                </form>

                <div>
                    <button type="button" class="cp-btn cp-btn--danger dr-btn-full" onclick="document.getElementById('rejectRequestArea').classList.toggle('active')">
                        <i class="fas fa-times mr-1"></i> Reject Request
                    </button>
                    <p class="dr-doc-meta text-center mt-2 px-1">Declines the request with a formal rejection reason.</p>
                </div>
            </div>

            <div id="rejectRequestArea" class="dr-workflow-action">
                <div class="dr-alert-box dr-alert-box--danger bg-rose-50 border-rose-200 shadow-sm">
                    <form action="<?= ROOT ?>/medical-school/submission-requests/reject" method="POST">
                        <input type="hidden" name="request_id" value="<?= $request->cis_id ?>">
                        
                        <label class="dr-slim-label text-rose-800">Formal Rejection Reason:</label>
                        <select name="reason_type" class="dr-slim-input border-rose-200 mb-4" required onchange="const t = document.getElementById('other_reason_box'); if(this.value === 'Other'){ t.style.display='block'; t.querySelector('textarea').required=true; } else { t.style.display='none'; t.querySelector('textarea').required=false; }">
                            <option value="" disabled selected>-- Select a Reason --</option>
                            <option value="Body capacity full (No space available)">Body Capacity Full (No Space Available)</option>
                            <option value="Facility temporarily closed / Under maintenance">Facility Temporarily Closed / Under Maintenance</option>
                            <option value="University holidays / Staff unavailability">University Holidays / Staff Unavailability</option>
                            <option value="Administrative suspension of intake">Administrative Suspension of Intake</option>
                            <option value="Other">Other (Please specify)</option>
                        </select>

                        <div id="other_reason_box" style="display: none;">
                            <textarea name="reason" class="dr-slim-textarea border-rose-200 mb-4" placeholder="Provide specific details regarding the rejection..." aria-label="Other Rejection Reason"></textarea>
                        </div>
                        
                        <div class="flex items-center justify-between mt-2">
                            <span onclick="document.getElementById('rejectRequestArea').classList.remove('active')" class="dr-doc-meta cursor-pointer underline">Cancel</span>
                            <button type="submit" class="cp-btn dr-btn-elevated bg-rose-800 text-white">
                                <i class="fas fa-exclamation-triangle mr-1"></i> Confirm Rejection
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>
