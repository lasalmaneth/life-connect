<?php if (!$exam): ?>
    <div class="cp-alert cp-alert--danger">Examination record not found.</div>
<?php else: ?>
    <style>
        .dr-action-card {
            padding: 14px 18px;
            border-radius: 12px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            margin-bottom: 16px;
            position: relative;
            overflow: hidden;
        }
        .dr-action-card--success {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border: 1px solid #86efac;
            box-shadow: 0 4px 12px -2px rgba(22, 163, 74, 0.08);
        }
        .dr-action-card--danger {
            background: linear-gradient(135deg, #fff1f2 0%, #ffe4e6 100%);
            border: 1px solid #fda4af;
            box-shadow: 0 4px 12px -2px rgba(225, 29, 72, 0.08);
        }
        .dr-btn-premium {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 10px 14px;
            border-radius: 10px;
            font-weight: 800;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
        }
        .dr-btn-premium--success {
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            color: white;
            box-shadow: 0 4px 12px -4px rgba(16, 185, 129, 0.3);
        }
        .dr-btn-premium--danger {
            background: linear-gradient(135deg, #be123c 0%, #e11d48 100%);
            color: white;
            box-shadow: 0 4px 12px -4px rgba(225, 29, 72, 0.3);
        }
        .dr-btn-premium:hover {
            transform: translateY(-1px);
            filter: brightness(1.05);
            box-shadow: 0 6px 15px -4px rgba(16, 185, 129, 0.4);
        }
        .dr-btn-premium--danger:hover {
            box-shadow: 0 6px 15px -4px rgba(225, 29, 72, 0.4);
        }
        .dr-workflow-action {
            display: none;
        }
        .dr-workflow-action.active {
            display: block;
            animation: cpFadeIn 0.3s ease;
        }

        /* Unified Bubble Badges */
        .cp-status-bubble {
            display: inline-flex;
            align-items: center;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.65rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            border: 1px solid transparent;
        }
        .cp-status-bubble--accepted { background: #dcfce7; color: #15803d; border-color: #bbf7d0; }
        .cp-status-bubble--rejected { background: #fee2e2; color: #b91c1c; border-color: #fecaca; }
        .cp-status-bubble--awaiting { background: #f1f5f9; color: #475569; border-color: #e2e8f0; }
        .cp-status-bubble--pending { background: #fef9c3; color: #854d0e; border-color: #fef08a; }

        .dr-case-tag {
            background: #f1f5f9;
            color: #64748b;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 0.65rem;
            font-weight: 800;
            border: 1px solid #e2e8f0;
        }
    </style>
    <div class="dr-section" style="padding-bottom: 12px;">
        <div class="flex items-center justify-between mb-4">
            <span class="cp-status-bubble cp-status-bubble--<?= strtolower($exam->final_exam_status) ?>">
                <i class="fas fa-circle mr-2" style="font-size: 0.4rem; opacity: 0.8;"></i>
                <?= htmlspecialchars($exam->final_exam_status) ?>
            </span>
            <div class="dr-case-tag">Case #<?= htmlspecialchars($exam->case_number) ?></div>
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
        <div class="dr-section pt-6 border-t border-gray-100">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <!-- Pass Card -->
                <div class="dr-action-card dr-action-card--success">
                    <h5 class="text-emerald-800 font-extrabold text-[0.65rem] mb-2 uppercase tracking-wider flex items-center gap-2">
                        <i class="fas fa-check-circle"></i> APPROVAL TRACK
                    </h5>
                    <form action="<?= ROOT ?>/medical-school/final-examinations/accept" method="POST">
                        <input type="hidden" name="exam_id" value="<?= $exam->id ?>">
                        <button type="submit" class="dr-btn-premium dr-btn-premium--success">
                            <i class="fas fa-check-circle mr-2"></i> Pass Examination
                        </button>
                    </form>
                    <p class="text-emerald-700 text-[0.6rem] font-bold text-center mt-3">Formally admits body into usage log.</p>
                </div>

                <!-- Reject Trigger Card -->
                <div class="dr-action-card dr-action-card--danger">
                    <h5 class="text-rose-800 font-extrabold text-[0.65rem] mb-2 uppercase tracking-wider flex items-center gap-2">
                        <i class="fas fa-times-circle"></i> REJECTION TRACK
                    </h5>
                    <button class="dr-btn-premium dr-btn-premium--danger" onclick="document.getElementById('rejectExamArea').classList.toggle('active')">
                        <i class="fas fa-file-medical-alt mr-2"></i> Reject Anatomically
                    </button>
                    <p class="text-rose-700 text-[0.6rem] font-bold text-center mt-3">Decline body suitability for research.</p>
                </div>
            </div>

            <!-- Reject Exam Area Details -->
            <div id="rejectExamArea" class="dr-workflow-action">
                <div class="dr-action-card dr-action-card--danger" style="background: white; border: 2px solid #fda4af;">
                    <form action="<?= ROOT ?>/medical-school/final-examinations/reject" method="POST">
                        <input type="hidden" name="exam_id" value="<?= $exam->id ?>">
                        
                        <h5 class="dr-heading-sm text-rose-900 mb-6 flex items-center gap-2 font-extrabold">
                            <i class="fas fa-microscope text-lg"></i> MEDICAL REJECTION DETAILS
                        </h5>

                        <div class="mb-4">
                            <label class="dr-slim-label text-rose-900 font-bold">Anatomical Rejection Category</label>
                            <select name="reason" class="dr-slim-input border-rose-200 focus:border-rose-500 focus:ring-rose-200" required>
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
                            <label class="dr-slim-label text-rose-900 font-bold">Medical Findings / Mandatory Notes</label>
                            <textarea name="notes" class="dr-slim-textarea border-rose-200 h-100 placeholder-rose-300" placeholder="Please provide professional medical justification for rejection..." required></textarea>
                        </div>

                        <button type="submit" class="cp-btn dr-btn-full bg-rose-900 text-white font-extrabold py-4">
                            CONFIRM REJECTION & ARCHIVE
                        </button>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>
