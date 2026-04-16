<?php if (!$exam): ?>
    <div class="cp-alert cp-alert--danger">Examination record not found.</div>
<?php else: ?>
    <div class="dr-section">
        <div class="flex items-center justify-between mb-4">
            <span class="cp-status-badge dr-status-badge dr-status-badge--<?= strtolower($exam->final_exam_status) ?>"><?= htmlspecialchars($exam->final_exam_status) ?></span>
            <div class="dr-doc-meta">Case #<?= htmlspecialchars($exam->case_number) ?></div>
        </div>

        <h4 class="dr-section-title">
            <span>Donor Identification</span>
        </h4>
        <div class="dr-grid dr-grid--2 mb-6">
            <div class="dr-label-group">
                <div class="dr-label">Full Name</div>
                <div class="dr-value dr-value--sub"><?= htmlspecialchars($exam->first_name . ' ' . $exam->last_name) ?></div>
            </div>
            <div class="dr-label-group">
                <div class="dr-label">NIC</div>
                <div class="dr-value dr-value--small"><?= htmlspecialchars($exam->nic_number ?? 'N/A') ?></div>
            </div>
        </div>
    </div>

    <div class="dr-section">
        <h4 class="dr-section-title">
            <span>Physical Verification Status</span>
        </h4>
        
        <div class="dr-banner">
            <div class="dr-banner__content">
                <div class="dr-banner__main">
                    <div class="dr-banner__title-sm">Current Phase</div>
                    <div class="dr-banner__title">Post-Arrival Anatomical Assessment</div>
                    <p class="dr-banner__msg">The body has reached institution grounds. Final medical clearance is required before formal intake.</p>
                </div>
            </div>

            <!-- Premium Certificate Banner (Matches Usage Logs Style) -->
            <?php if (isset($certificate) && $certificate && strtolower($exam->final_exam_status) === 'accepted'): ?>
                <div class="dr-banner dr-banner--info mt-6 mb-0">
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <i class="fas fa-certificate text-blue-600 text-lg"></i>
                            <div>
                                <div class="dr-heading-sm text-blue-800">Donation Certificate Issued</div>
                                <div class="dr-doc-meta font-bold text-blue-500">Ref: <?= htmlspecialchars($certificate->certificate_number) ?></div>
                            </div>
                        </div>
                        <a href="<?= ROOT ?>/medical-school/certificates/view?id=<?= $certificate->id ?>&from=examinations" class="cp-btn dr-btn-xs bg-white border-blue-500 text-blue-600">
                            <i class="fas fa-eye mr-2"></i> View Certificate
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($exam->final_exam_status === 'REJECTED'): ?>
            <div class="dr-alert-box dr-alert-box--danger">
                <div class="dr-alert-box__title"><i class="fas fa-times-circle"></i> Anatomical Rejection:</div>
                <div class="dr-alert-box__main">Primary Reason: <?= htmlspecialchars($exam->final_exam_reason) ?></div>
                <div class="dr-alert-box__notes">"<?= htmlspecialchars($exam->final_exam_notes) ?>"</div>
                <div class="dr-alert-box__meta">Examined on <?= date('M d, Y', strtotime($exam->final_exam_at)) ?></div>
            </div>
        <?php endif; ?>
    </div>

    <?php if ($exam->final_exam_status === 'AWAITING'): ?>
        <div class="dr-section pt-6 border-t border-gray-200">
            <div class="dr-grid dr-grid--2 mb-4">
                <form action="<?= ROOT ?>/medical-school/final-examinations/accept" method="POST">
                    <input type="hidden" name="exam_id" value="<?= $exam->id ?>">
                    <button type="submit" class="cp-btn dr-btn-full bg-emerald-600 text-white shadow-emerald-200">
                        <i class="fas fa-check-circle mr-2"></i> Pass Examination
                    </button>
                    <p class="dr-doc-meta text-center mt-2">Formally admits body into usage log.</p>
                </form>

                <div>
                    <button class="cp-btn dr-btn-full bg-red-600 text-white shadow-red-200" onclick="document.getElementById('rejectExamArea').classList.toggle('active')">
                        <i class="fas fa-file-medical-alt mr-2"></i> Reject Anatomically
                    </button>
                </div>
            </div>

            <!-- Reject Exam Area -->
            <div id="rejectExamArea" class="dr-workflow-action">
                <div class="dr-alert-box dr-alert-box--danger bg-rose-50 border-rose-200 shadow-sm mt-4">
                    <form action="<?= ROOT ?>/medical-school/final-examinations/reject" method="POST">
                        <input type="hidden" name="exam_id" value="<?= $exam->id ?>">
                        
                        <h5 class="dr-heading-sm text-rose-800 mb-6 flex items-center gap-2">
                            <i class="fas fa-microscope text-lg"></i> Medical Rejection Details
                        </h5>

                        <div class="mb-4">
                            <label class="dr-slim-label text-rose-800">Anatomical Rejection Category</label>
                            <select name="reason" class="dr-slim-input border-rose-200" required>
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

                        <div class="mb-6">
                            <label class="dr-slim-label text-rose-800">Medical Findings / Mandatory Notes</label>
                            <textarea name="notes" class="dr-slim-textarea border-rose-200 h-100" placeholder="Please provide professional medical justification for rejection..." required></textarea>
                        </div>

                        <button type="submit" class="cp-btn dr-btn-full bg-rose-800 text-white">
                            Confirm Anatomical Rejection & Archive
                        </button>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>
