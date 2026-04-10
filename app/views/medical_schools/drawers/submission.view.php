<?php if (!$submission): ?>
    <div class="cp-alert cp-alert--danger">Submission record not found.</div>
<?php else: ?>
    <div class="case-detail-section">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem;">
            <div>
                <span class="cp-status-badge cp-status-badge--<?= strtolower(str_replace('_', '-', $submission->document_status)) ?>">
                    <?= htmlspecialchars(str_replace('_', ' ', $submission->document_status)) ?>
                </span>
            </div>
            <div style="font-size: 0.8125rem; color: var(--g500);">Case #<?= htmlspecialchars($submission->case_number) ?></div>
        </div>

        <h4 style="margin-bottom: 1rem; color: var(--g800); border-bottom: 1px solid var(--g200); padding-bottom: 0.5rem;">
            Donor Identification
        </h4>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 2rem;">
            <div>
                <div style="font-size: 0.75rem; color: var(--g500);">Full Name</div>
                <div style="font-weight: 500; font-size: 1.1rem;"><?= htmlspecialchars($submission->first_name . ' ' . $submission->last_name) ?></div>
            </div>
            <div>
                <div style="font-size: 0.75rem; color: var(--g500);">Date of Birth</div>
                <div style="font-weight: 500;"><?= $submission->date_of_birth ? date('M d, Y', strtotime($submission->date_of_birth)) : 'N/A' ?></div>
            </div>
        </div>
    </div>

    <div class="case-detail-section" style="margin-top: 2rem;">
        <h4 style="margin-bottom: 1rem; color: var(--g800); border-bottom: 1px solid var(--g200); padding-bottom: 0.5rem;">
            Document Bundle (Pathology Requirements)
        </h4>
        <div style="display: flex; flex-direction: column; gap: 0.75rem; margin-bottom: 2.5rem;">
            <?php if (empty($documents)): ?>
                <div class="cp-alert cp-alert--info">No documents have been uploaded yet.</div>
            <?php else: ?>
                <?php foreach ($documents as $doc): ?>
                    <div style="display: flex; align-items: center; justify-content: space-between; padding: 1rem; background: #fff; border: 1px solid var(--g200); border-radius: 8px;">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div style="width: 40px; hieght: 40px; display: flex; align-items: center; justify-content: center; background: var(--blue-50); color: var(--blue-600); border-radius: 6px; font-size: 1.25rem;">
                                <i class="fas fa-file-pdf"></i>
                            </div>
                            <div>
                                <div style="font-weight: 600; font-size: 0.875rem;"><?= htmlspecialchars(str_replace('_', ' ', $doc->document_type)) ?></div>
                                <div style="font-size: 0.75rem; color: var(--g500);">Uploaded on <?= date('M d, Y H:i', strtotime($doc->uploaded_at)) ?></div>
                            </div>
                        </div>
                        <a href="<?= ROOT ?>/<?= $doc->file_path ?>" target="_blank" class="cp-btn cp-btn--secondary cp-btn--sm">
                            <i class="fas fa-eye"></i> View
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($submission->document_status === 'PENDING_REVIEW'): ?>
        <div class="case-detail-section" style="padding-top: 1.5rem; border-top: 1px solid var(--g200);">
            <div style="display: flex; gap: 1rem; margin-bottom: 1rem;">
                <form action="<?= ROOT ?>/medical-school/submissions/accept" method="POST" style="flex: 1;">
                    <input type="hidden" name="submission_id" value="<?= $submission->id ?>">
                    <button type="submit" class="cp-btn cp-btn--success" style="width: 100%;">
                        <i class="fas fa-check-circle"></i> Accept Document Bundle
                    </button>
                    <p style="font-size: 0.6875rem; color: var(--g500); margin-top: 6px; text-align: center;">Moves case to final physical exam stage.</p>
                </form>

                <button class="cp-btn cp-btn--warning" style="flex: 1;" onclick="document.getElementById('requestMoreArea').classList.toggle('cp-hidden')">
                    <i class="fas fa-reply-all"></i> Need More Docs
                </button>
            </div>

            <button class="cp-btn cp-btn--danger cp-btn--sm" style="width: 100%;" onclick="document.getElementById('rejectSubmissionArea').classList.toggle('cp-hidden')">
                <i class="fas fa-ban"></i> Final Bundle Rejection
            </button>

            <!-- Need More Docs Logic -->
            <div id="requestMoreArea" class="cp-hidden" style="margin-top: 1.5rem; padding: 1.5rem; background: var(--warning-50); border-radius: 8px; border: 1px solid var(--warning-200);">
                <form action="<?= ROOT ?>/medical-school/submissions/request-documents" method="POST">
                    <input type="hidden" name="submission_id" value="<?= $submission->id ?>">
                    
                    <label style="display: block; font-size: 0.8125rem; font-weight: 600; margin-bottom: 0.5rem; color: var(--warning-900);">Missing / Incorrect Document:</label>
                    <select name="reason_type" class="cp-form-control" style="width: 100%; margin-bottom: 1rem; border-color: var(--warning-300);" required onchange="if(this.value==='Other'){document.getElementById('otherDocReason').style.display='block';}else{document.getElementById('otherDocReason').style.display='none';}">
                        <option value="">-- Select Document Issue --</option>
                        <option value="Missing Death Certificate">Missing Death Certificate</option>
                        <option value="Incorrect/Unclear ID Copy">Incorrect/Unclear ID Copy</option>
                        <option value="Missing Sworn Statement">Missing Sworn Statement</option>
                        <option value="Missing Cause of Death Report">Missing Cause of Death Report</option>
                        <option value="Missing Cadaver Data Sheet">Missing Cadaver Data Sheet</option>
                        <option value="Missing Embalming Certificate">Missing Embalming Certificate</option>
                        <option value="Missing Police/Inquest Report">Missing Police/Inquest Report</option>
                        <option value="Missing PCR Report">Missing PCR Report</option>
                        <option value="Corrupted File / Cannot Open">Corrupted File / Cannot Open</option>
                        <option value="Other">Other (Specify Below)</option>
                    </select>

                    <div id="otherDocReason" style="display: none;">
                        <label style="display: block; font-size: 0.8125rem; font-weight: 600; margin-bottom: 0.5rem; color: var(--warning-900);">Additional Instructions:</label>
                        <textarea name="reason" class="cp-textarea" style="width: 100%; height: 80px; margin-bottom: 1rem; border-color: var(--warning-300);" placeholder="Specify what is missing or needs correction..."></textarea>
                    </div>

                    <button type="submit" class="cp-btn cp-btn--warning" style="width: 100%;">Send Correction Request</button>
                </form>
            </div>

            <!-- Full Reject Logic -->
            <div id="rejectSubmissionArea" class="cp-hidden" style="margin-top: 1.5rem; padding: 1.5rem; background: var(--red-50); border-radius: 8px; border: 1px solid var(--red-100);">
                <form action="<?= ROOT ?>/medical-school/submissions/reject" method="POST">
                    <input type="hidden" name="submission_id" value="<?= $submission->cis_id ?>">
                    
                    <label style="display: block; font-size: 0.8125rem; font-weight: 600; margin-bottom: 0.5rem; color: var(--red-900);">Final Rejection Reason:</label>
                    <select name="reason_type" class="cp-form-control" style="width: 100%; margin-bottom: 1rem; border-color: var(--red-200);" required onchange="if(this.value==='Other'){document.getElementById('rejectOtherArea').style.display='block';}else{document.getElementById('rejectOtherArea').style.display='none';}">
                        <option value="">-- Select Rejection Reason --</option>
                        <option value="Medical History Incompatibility">Medical History Incompatibility</option>
                        <option value="Severe Physical Damage">Severe Physical Damage</option>
                        <option value="Unmet Legal Requirements">Unmet Legal Requirements</option>
                        <option value="Institution Capacity Reached">Institution Capacity Reached</option>
                        <option value="Other">Other (Specify Below)</option>
                    </select>

                    <div id="rejectOtherArea" style="display: none;">
                        <textarea name="reason" class="cp-textarea" style="width: 100%; height: 80px; margin-bottom: 1rem; border-color: var(--red-200);" placeholder="Provide detailed reason..."></textarea>
                    </div>

                    <button type="submit" class="cp-btn cp-btn--danger" style="width: 100%;">Confirm Permanent Rejection</button>
                </form>
            </div>

        </div>
    <?php endif; ?>

    <style>
        .cp-hidden { display: none; }
    </style>
<?php endif; ?>
