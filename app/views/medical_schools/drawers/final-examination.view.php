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
        <div style="padding: 1.5rem; background: var(--blue-50); border: 1px solid var(--blue-100); border-radius: 8px; margin-bottom: 2rem;">
            <div style="font-size: 0.75rem; color: var(--blue-600); margin-bottom: 8px; font-weight: 700; text-transform: uppercase;">Current Phase</div>
            <div style="font-weight: 600; color: var(--blue-900); font-size: 1.1rem;">Post-Arrival Anatomical Assessment</div>
            <p style="margin-top: 8px; color: var(--blue-700); font-size: 0.8125rem;">The body has reached institution grounds. Final medical clearance is required before formal intake.</p>
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
                    <button type="submit" class="cp-btn cp-btn--success" style="width: 100%;">
                        <i class="fas fa-check-circle"></i> Pass Examination
                    </button>
                    <p style="font-size: 0.6875rem; color: var(--g500); margin-top: 6px; text-align: center;">Formally admits body into the institution usage log.</p>
                </form>

                <button class="cp-btn cp-btn--danger" style="flex: 1;" onclick="document.getElementById('rejectExamArea').classList.toggle('cp-hidden')">
                    <i class="fas fa-file-medical-alt"></i> Reject Anatomically
                </button>
            </div>

            <!-- Reject Exam Logic -->
            <div id="rejectExamArea" class="cp-hidden" style="margin-top: 1.5rem; padding: 1.5rem; background: var(--red-50); border-radius: 8px; border: 1px solid var(--red-100);">
                <form action="<?= ROOT ?>/medical-school/final-examinations/reject" method="POST">
                    <input type="hidden" name="exam_id" value="<?= $exam->id ?>">
                    
                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; font-size: 0.8125rem; font-weight: 600; margin-bottom: 0.5rem; color: var(--red-900);">Categorical Reason:</label>
                        <select name="reason" class="cp-input" style="width: 100%;" required>
                            <option value="">-- Select Category --</option>
                            <option value="Advanced Decomposition">Advanced Decomposition</option>
                            <option value="Unauthorized Autopsy">Unauthorized Autopsy</option>
                            <option value="Communicable Disease">Communicable Disease</option>
                            <option value="Violent Trauma">Violent Trauma</option>
                            <option value="Missing Legal Docs">Missing Legal Docs at Arrival</option>
                        </select>
                    </div>

                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; font-size: 0.8125rem; font-weight: 600; margin-bottom: 0.5rem; color: var(--red-900);">Detailed Exam Notes:</label>
                        <textarea name="notes" class="cp-textarea" style="width: 100%; height: 100px; border-color: var(--red-200);" placeholder="Specific medical findings..." required></textarea>
                    </div>

                    <button type="submit" class="cp-btn cp-btn--danger" style="width: 100%;">Final Body Rejection & Archive</button>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <style>
        .cp-hidden { display: none; }
    </style>
<?php endif; ?>
