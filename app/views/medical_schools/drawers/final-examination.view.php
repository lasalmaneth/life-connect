<?php if (!$exam): ?>
    <div class="cp-alert cp-alert--danger">Examination record not found.</div>
<?php else: ?>
    <div class="case-detail-section">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem;">
            <span class="cp-status-badge cp-status-badge--<?= strtolower($exam->final_exam_status) ?>" style="font-weight: 700; text-transform: uppercase;"><?= htmlspecialchars($exam->final_exam_status) ?></span>
            <div style="font-size: 0.8125rem; color: var(--g500);">Case #<?= htmlspecialchars($exam->case_number) ?></div>
        </div>

        <h4 style="margin-bottom: 1rem; color: var(--g800); border-bottom: 1px solid var(--g200); padding-bottom: 0.5rem;">
            Donor Identification
        </h4>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 2rem;">
            <div>
                <div style="font-size: 0.75rem; color: var(--g500);">Full Name</div>
                <div style="font-weight: 500; font-size: 1.1rem;"><?= htmlspecialchars($exam->first_name . ' ' . $exam->last_name) ?></div>
            </div>
            <div>
                <div style="font-size: 0.75rem; color: var(--g500);">NIC</div>
                <div style="font-weight: 500;"><?= htmlspecialchars($exam->nic_number ?? 'N/A') ?></div>
            </div>
        </div>
    </div>

    <div class="case-detail-section" style="margin-top: 2rem;">
        <h4 style="margin-bottom: 1rem; color: var(--g800); border-bottom: 1px solid var(--g200); padding-bottom: 0.5rem;">
            Physical Verification Status
        </h4>
        <div style="padding: 1.5rem; background: var(--blue-50); border: 1px solid var(--blue-100); border-radius: 12px; margin-bottom: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <div style="font-size: 0.75rem; color: var(--blue-600); margin-bottom: 8px; font-weight: 700; text-transform: uppercase;">Current Phase</div>
                    <div style="font-weight: 600; color: var(--blue-900); font-size: 1.1rem;">Post-Arrival Anatomical Assessment</div>
                    <p style="margin-top: 8px; color: var(--blue-700); font-size: 0.8125rem;">The body has reached institution grounds. Final medical clearance is required before formal intake.</p>
                </div>
            </div>

        <!-- Premium Certificate Banner (Matches Usage Logs Style) -->
        <?php if (isset($certificate) && $certificate && strtolower($exam->final_exam_status) === 'accepted'): ?>
            <div style="padding: 1.25rem; border-radius: 12px; border: 1px solid #dbeafe; background: #eff6ff; display: flex; align-items: center; justify-content: space-between; gap: 12px; box-shadow: 0 1px 2px rgba(0,0,0,0.05); margin-bottom: 2rem;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <i class="fas fa-certificate" style="color: #2563eb; font-size: 1.1rem;"></i>
                    <div>
                        <div style="font-weight: 800; color: #1e40af; font-size: 0.85rem;">Donation Certificate Issued</div>
                        <div style="font-size: 0.7rem; color: #3b82f6; font-weight: 700;">Ref: <?= htmlspecialchars($certificate->certificate_number) ?></div>
                    </div>
                </div>
                <a href="<?= ROOT ?>/medical-school/certificates/view?id=<?= $certificate->id ?>&from=examinations" class="cp-btn" style="height: 36px; padding: 0 16px; font-size: 0.75rem; background: var(--white); border: 1px solid #3b82f6; color: #2563eb; border-radius: 8px; font-weight: 700; text-decoration: none; display: flex; align-items: center;">
                    <i class="fas fa-eye mr-2"></i> View Certificate
                </a>
            </div>
        <?php endif; ?>
        </div>

        <?php if ($exam->final_exam_status === 'REJECTED'): ?>
            <div class="cp-alert cp-alert--danger" style="margin-bottom: 2rem;">
                <div style="font-weight: 700; margin-bottom: 8px;"><i class="fas fa-times-circle"></i> Anatomical Rejection:</div>
                <div style="font-weight: 600; margin-bottom: 4px;">Primary Reason: <?= htmlspecialchars($exam->final_exam_reason) ?></div>
                <div style="font-style: italic; font-size: 0.8125rem;">"<?= htmlspecialchars($exam->final_exam_notes) ?>"</div>
                <div style="margin-top: 12px; font-size: 0.75rem; color: var(--red-700);">Examined on <?= date('M d, Y', strtotime($exam->final_exam_at)) ?></div>
            </div>
        <?php endif; ?>
    </div>

    <?php if ($exam->final_exam_status === 'AWAITING'): ?>
        <div class="case-detail-section" style="padding-top: 1.5rem; border-top: 1px solid var(--g200);">
            <div style="display: flex; gap: 1rem; margin-bottom: 1rem;">
                <form action="<?= ROOT ?>/medical-school/final-examinations/accept" method="POST" style="flex: 1;">
                    <input type="hidden" name="exam_id" value="<?= $exam->id ?>">
                    <button type="submit" class="cp-btn" style="width: 100%; background: #059669 !important; color: white !important; border: none; height: 48px; font-weight: 700; border-radius: 12px; box-shadow: 0 4px 10px rgba(5, 150, 105, 0.2);">
                        <i class="fas fa-check-circle mr-2"></i> Pass Examination
                    </button>
                    <p style="font-size: 0.6875rem; color: var(--g500); margin-top: 8px; text-align: center;">Formally admits body into usage log.</p>
                </form>

                <div style="flex: 1;">
                    <button class="cp-btn" style="width: 100%; background: #dc2626 !important; color: white !important; border: none; height: 48px; font-weight: 700; border-radius: 12px; box-shadow: 0 4px 10px rgba(220, 38, 38, 0.2);" onclick="document.getElementById('rejectExamArea').classList.toggle('cp-hidden')">
                        <i class="fas fa-file-medical-alt mr-2"></i> Reject Anatomically
                    </button>
                </div>
            </div>

            <!-- Reject Exam Area -->
            <div id="rejectExamArea" class="cp-hidden" style="margin-top: 1.5rem; padding: 1.5rem; background: #fff1f2; border-radius: 14px; border: 1px solid #fecaca; box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);">
                <form action="<?= ROOT ?>/medical-school/final-examinations/reject" method="POST">
                    <input type="hidden" name="exam_id" value="<?= $exam->id ?>">
                    
                    <h5 style="color: #991b1b; margin-bottom: 1.25rem; font-size: 1rem; font-weight: 800; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-microscope" style="font-size: 1.1rem;"></i> Medical Rejection Details
                    </h5>

                    <div style="margin-bottom: 1.25rem;">
                        <label style="display: block; font-size: 0.75rem; font-weight: 800; margin-bottom: 6px; color: #991b1b; text-transform: uppercase; letter-spacing: 0.025em;">Anatomical Rejection Category</label>
                        <select name="reason" class="cp-form-control" style="width: 100%; height: 44px; border-radius: 10px; border-color: #fecaca; background: white; font-weight: 600;" required>
                            <option value="">-- Select Specific Reason --</option>
                            <option value="Body decomposition (putrefaction)">Body decomposition (putrefaction)</option>
                            <option value="Infectious disease (HIV, Hepatitis, etc.)">Infectious disease (HIV, Hepatitis, etc.)</option>
                            <option value="Presence of open wounds or bed sores">Presence of open wounds or bed sores</option>
                            <option value="Recent major surgical procedures">Recent major surgical procedures</option>
                            <option value="Extensive surgical scars">Extensive surgical scars</option>
                            <option value="Severe physical deformities">Severe physical deformities</option>
                            <option value="Extreme obesity">Extreme obesity</option>
                            <option value="Severe emaciation">Severe emaciation</option>
                            <option value="Certain types of cancer affecting suitability">Certain types of cancer affecting suitability</option>
                            <option value="General poor physical conditioned">General poor physical condition of the body</option>
                            <option value="Other">Other (Specify in notes)</option>
                        </select>
                    </div>

                    <div id="examNotesArea" style="margin-bottom: 1.5rem;">
                        <label style="display: block; font-size: 0.75rem; font-weight: 800; margin-bottom: 6px; color: #991b1b; text-transform: uppercase;">Medical Findings / Mandatory Notes</label>
                        <textarea name="notes" class="cp-textarea" style="width: 100%; height: 100px; border-radius: 10px; border-color: #fecaca; padding: 12px; font-size: 0.875rem;" placeholder="Please provide professional medical justification for rejection..." required></textarea>
                    </div>

                    <button type="submit" class="cp-btn" style="width: 100%; background: #991b1b; color: white; height: 44px; border: none; font-weight: 700; border-radius: 10px;">
                        Confirm Anatomical Rejection & Archive
                    </button>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <style>
        .cp-hidden { display: none !important; }
    </style>
<?php endif; ?>
