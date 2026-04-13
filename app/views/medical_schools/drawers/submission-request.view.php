<?php if (!$request): ?>
    <div class="cp-alert cp-alert--danger">Request not found or unauthorized.</div>
<?php else: ?>
    <div class="cp-drawer-section">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem;">
            <span class="cp-badge cp-badge--<?= strtolower($request->request_status) === 'pending' ? 'pending' : 'info' ?>">
                <?= htmlspecialchars($request->request_status) ?>
            </span>
            <div class="cp-drawer-field__label">Case #<?= htmlspecialchars($request->case_number) ?></div>
        </div>

        <h4 class="cp-drawer-section__title">
            <i class="fas fa-id-card"></i> Personal Information
        </h4>
        <div class="cp-drawer-grid">
            <div style="grid-column: span 2;">
                <div class="cp-drawer-field__label">Full Name</div>
                <div class="cp-drawer-field__value"><?= htmlspecialchars($request->first_name . ' ' . $request->last_name) ?></div>
            </div>
            <div>
                <div class="cp-drawer-field__label">Date of Birth</div>
                <div class="cp-drawer-field__value"><?= $request->date_of_birth ? date('Y-m-d', strtotime($request->date_of_birth)) : 'N/A' ?></div>
            </div>
            <div>
                <div class="cp-drawer-field__label">Age</div>
                <div class="cp-drawer-field__value">
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
            <div>
                <div class="cp-drawer-field__label">Gender</div>
                <div class="cp-drawer-field__value"><?= strtoupper($request->gender ?? 'N/A') ?></div>
            </div>
            <div>
                <div class="cp-drawer-field__label">NIC Number</div>
                <div class="cp-drawer-field__value"><?= htmlspecialchars($request->nic_number ?? 'N/A') ?></div>
            </div>
            <div>
                <div class="cp-drawer-field__label">Nationality</div>
                <div class="cp-drawer-field__value"><?= htmlspecialchars($request->nationality ?? 'N/A') ?></div>
            </div>
            <div>
                <div class="cp-drawer-field__label">Date of Death</div>
                <div class="cp-drawer-field__value"><?= $request->date_of_death ? date('d M, Y', strtotime($request->date_of_death)) : 'N/A' ?></div>
            </div>
            <div>
                <div class="cp-drawer-field__label">Submission Date</div>
                <div class="cp-drawer-field__value"><?= date('d M, Y', strtotime($request->submission_date ?? $request->created_at)) ?></div>
            </div>
        </div>
    </div>

    <div class="cp-drawer-section">
        <div class="cp-drawer-section__title">
            <i class="fas fa-hand-holding-heart"></i> Decision Overview
        </div>
        <div class="cp-callout">
            <div class="cp-callout__title">Custodian Intention</div>
            <div class="cp-callout__text">Proceed with body submission to pathology institution for medical education and research.</div>
        </div>

        <?php if ($request->request_status === 'REJECTED'): ?>
            <div class="cp-alert-box--danger">
                <div class="cp-alert-box__title">
                    <i class="fas fa-times-circle"></i> Rejection Reason
                </div>
                <div class="cp-alert-box__msg">
                    <?= htmlspecialchars($request->request_action_reason) ?>
                    <div style="margin-top: 8px; font-size: 0.75rem; opacity: 0.8;">Action taken on <?= date('M d, Y', strtotime($request->request_action_at)) ?></div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Family Custodians Section (Compact Style) -->
    <div style="background: white; border: 1px solid var(--blue-100); border-radius: 12px; margin-top: 1rem; padding: 1rem; box-shadow: 0 2px 8px rgba(0, 91, 170, 0.02);">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem; border-bottom: 1px solid var(--blue-50); padding-bottom: 0.5rem;">
            <div style="display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-user-shield" style="color: var(--blue-600); font-size: 0.85rem;"></i>
                <span style="font-weight: 800; font-size: 0.75rem; color: var(--blue-700); text-transform: uppercase; letter-spacing: 0.05em;">Family Custodians</span>
            </div>
            <span style="font-size: 0.65rem; color: var(--g500); font-weight: 700; background: var(--g50); padding: 2px 10px; border-radius: 50px;">
                <?= count($custodians) ?>
            </span>
        </div>
        
        <?php if (empty($custodians)): ?>
            <div style="padding: 1rem; background: #fffcf0; border: 1px dashed #fef3c7; border-radius: 8px; font-size: 0.8125rem; color: #92400e; text-align: center;">
                <i class="fas fa-info-circle mr-1"></i> No family custodians are explicitly assigned yet.
            </div>
        <?php else: ?>
            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                <?php foreach ($custodians as $c): ?>
                    <div style="background: #f8fafc; padding: 0.875rem; border-radius: 10px; border: 1px solid var(--g100); position: relative;">
                        <span style="position: absolute; top: 10px; right: 12px; color: var(--blue-500); font-weight: 800; font-size: 0.8rem; opacity: 0.6;">#</span>
                        
                        <div style="font-weight: 800; color: var(--blue-900); font-size: 0.875rem; margin-bottom: 0.65rem; display: flex; align-items: center; gap: 6px;">
                            <?= htmlspecialchars($c->name ?? 'N/A') ?>
                            <span style="font-weight: 400; color: var(--g400); font-size: 0.75rem;">(<?= htmlspecialchars($c->relationship ?? 'N/A') ?>)</span>
                        </div>
                        
                        <div style="display: grid; grid-template-columns: 1fr 1.5fr; gap: 1rem; border-top: 1px solid var(--g50); padding-top: 0.65rem;">
                            <div>
                                <label style="display: block; font-size: 0.55rem; color: var(--g400); font-weight: 700; text-transform: uppercase; margin-bottom: 2px;">Phone</label>
                                <div style="font-weight: 700; color: var(--blue-600); font-size: 0.8rem;">
                                    <?= !empty($c->phone) ? htmlspecialchars($c->phone) : 'N/A' ?>
                                </div>
                            </div>
                            <div>
                                <label style="display: block; font-size: 0.55rem; color: var(--g400); font-weight: 700; text-transform: uppercase; margin-bottom: 2px;">Email</label>
                                <div style="font-weight: 600; color: var(--blue-900); font-size: 0.8rem; word-break: break-all;">
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
        <div class="cp-drawer-section" style="border-top: 1px solid var(--cp-gray-200); padding-top: 1.5rem;">
            <div style="display: flex; gap: 16px; align-items: flex-start;">
                <div style="flex: 1;">
                    <form action="<?= ROOT ?>/medical-school/submission-requests/accept" method="POST">
                        <input type="hidden" name="request_id" value="<?= $request->cis_id ?>">
                        <button type="submit" class="cp-btn" style="width: 100%; height: 48px; background: #10b981; color: white; border-radius: 12px; font-weight: 700; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: all 0.2s; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.15);">
                            <i class="fas fa-check"></i> Accept Request
                        </button>
                    </form>
                    <p style="font-size: 0.75rem; color: var(--cp-gray-500); margin-top: 10px; text-align: center; line-height: 1.4;">Approves outreach and unlocks document submission.</p>
                </div>

                <div style="flex: 1;">
                    <button type="button" class="cp-btn" style="width: 100%; height: 48px; background: #ef4444; color: white; border-radius: 12px; font-weight: 700; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: all 0.2s; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.15);" onclick="document.getElementById('rejectRequestArea').classList.toggle('cp-hidden')">
                        <i class="fas fa-times"></i> Reject Request
                    </button>
                    <p style="font-size: 0.75rem; color: var(--cp-gray-500); margin-top: 10px; text-align: center; line-height: 1.4;">Declines the request with a formal rejection reason.</p>
                </div>
            </div>

            <div id="rejectRequestArea" class="cp-hidden" style="margin-top: 1.5rem; padding: 1.5rem; background: #fef2f2; border-radius: 8px; border: 1px solid #fca5a5;">
                <form action="<?= ROOT ?>/medical-school/submission-requests/reject" method="POST">
                    <input type="hidden" name="request_id" value="<?= $request->cis_id ?>">
                    
                    <label style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 0.5rem; color: #991b1b;">Formal Rejection Reason:</label>
                    <select name="reason_type" class="cp-form-control" style="width: 100%; margin-bottom: 1rem; border-color: #f87171;" required onchange="const t = document.getElementById('other_reason_box'); if(this.value === 'Other'){ t.style.display='block'; t.querySelector('textarea').required=true; } else { t.style.display='none'; t.querySelector('textarea').required=false; }">
                        <option value="" disabled selected>-- Select a Reason --</option>
                        <option value="Body capacity full (No space available)">Body Capacity Full (No Space Available)</option>
                        <option value="Facility temporarily closed / Under maintenance">Facility Temporarily Closed / Under Maintenance</option>
                        <option value="University holidays / Staff unavailability">University Holidays / Staff Unavailability</option>
                        <option value="Administrative suspension of intake">Administrative Suspension of Intake</option>
                        <option value="Other">Other (Please specify)</option>
                    </select>

                    <div id="other_reason_box" style="display: none;">
                        <textarea name="reason" class="cp-textarea" style="width: 100%; height: 80px; margin-bottom: 1rem; border-color: #f87171;" placeholder="Provide specific details regarding the rejection..." aria-label="Other Rejection Reason"></textarea>
                    </div>
                    
                    <div style="display: flex; gap: 10px; align-items: center; justify-content: flex-end; margin-top: 10px;">
                        <span onclick="document.getElementById('rejectRequestArea').classList.add('cp-hidden')" style="font-size: 0.875rem; color: #6b7280; cursor: pointer; text-decoration: underline;">Cancel</span>
                        <button type="submit" class="cp-btn" style="background: #dc2626; color: white; border: none; padding: 10px 20px; border-radius: 10px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: all 0.2s; box-shadow: 0 4px 12px rgba(220, 38, 38, 0.15);">
                            <i class="fas fa-exclamation-triangle"></i> Confirm Rejection
                        </button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <style>
        .cp-hidden { display: none; }
        .cp-textarea {
            padding: 0.75rem;
            border: 1px solid var(--g200);
            border-radius: 6px;
            font-size: 0.875rem;
            font-family: inherit;
            resize: vertical;
        }
        .cp-textarea:focus {
            outline: none;
            border-color: var(--red-400);
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }
    </style>
<?php endif; ?>
