<?php if (!$submission): ?>
    <div class="cp-empty">
        <i class="fas fa-search cp-empty__icon"></i>
        <h3 class="dr-empty-title">Submission Not Found</h3>
        <p>The requested record could not be retrieved from the database.</p>
    </div>
<?php else: ?>
    <!-- Premium Drawer Content -->
    <div class="dr-content">
        
        <!-- Slimmed Premium Header -->
        <div class="dr-header">
            <div class="dr-header__inner">
                <div class="dr-header__top">
                    <span class="dr-tag">
                        Case #<?= htmlspecialchars($submission->case_number) ?>
                    </span>
                    <span class="cp-status-badge dr-status-badge">
                        <i class="fas fa-clock mr-1"></i> <?= htmlspecialchars(str_replace('_', ' ', $submission->document_status)) ?>
                    </span>
                </div>
                <h3>Document Bundle Review</h3>
                <p>Verify legal and medical paperwork for the deceased donor.</p>
            </div>
        </div>

        <!-- Compact Donor Info -->
        <div class="dr-card">
            <div class="dr-section-title">
                <i class="fas fa-id-card-alt"></i>
                <span>Donor Information</span>
            </div>
            <div class="dr-grid dr-grid--1-5">
                <div class="dr-label-group">
                    <label class="dr-label">Name</label>
                    <div class="dr-value"><?= htmlspecialchars($submission->first_name . ' ' . $submission->last_name) ?></div>
                </div>
                <div class="dr-label-group">
                    <label class="dr-label">NIC</label>
                    <div class="dr-value dr-value--sub"><?= htmlspecialchars($submission->nic_number) ?></div>
                </div>
            </div>
            <div class="dr-grid dr-grid--2 mt-1">
                <div class="dr-value dr-value--small"><span class="dr-label">Nationality</span> <?= htmlspecialchars($submission->nationality ?? 'Sri Lankan') ?></div>
                <div class="dr-value dr-value--small"><span class="dr-label">Age</span> <?= $submission->date_of_birth ? floor((time() - strtotime($submission->date_of_birth)) / 31556926) : 'N/A' ?> Years</div>
            </div>
            <div class="dr-grid dr-grid--2 dr-divider">
                <div class="dr-value dr-value--small"><span class="dr-label">Date of Death</span> <?= $submission->date_of_death ? date('d M, Y', strtotime($submission->date_of_death)) : 'N/A' ?></div>
                <div class="dr-value dr-value--small"><span class="dr-label">Submission Date</span> <?= date('d M, Y', strtotime($submission->submission_date ?? $submission->created_at)) ?></div>
            </div>
        </div>

        <!-- Family Custodians Section (Compact Style) -->
        <div class="dr-card dr-card--blue">
            <div class="dr-section-title">
                <div class="flex items-center gap-2">
                    <i class="fas fa-user-shield"></i>
                    <span>Family Custodians</span>
                </div>
                <span class="dr-count-badge">
                    <?= count($custodians) ?>
                </span>
            </div>

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
                                <label class="dr-label dr-label--xs">Phone</label>
                                <div class="dr-value dr-value--accent">
                                    <?= !empty($c->phone) ? htmlspecialchars($c->phone) : 'N/A' ?>
                                </div>
                            </div>
                            <div>
                                <label class="dr-label dr-label--xs">Email</label>
                                <div class="dr-value dr-value--small break-all">
                                    <?= !empty($c->email) ? htmlspecialchars($c->email) : 'N/A' ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Slim Document List -->
        <div class="mb-4">
            <div class="flex items-center justify-between mb-2 px-1">
                <h4 class="dr-heading-sm">
                    <i class="fas fa-layer-group text-blue-500 mr-1"></i> Submission Bundle
                </h4>
            </div>
            
            <?php 
            $bundleDoc = null;
            $otherDocs = [];
            foreach ($documents as $doc) {
                $isBundle = (strpos(strtoupper($doc->document_type), 'BUNDLE') !== false);
                if ($isBundle) {
                    if (!$bundleDoc) {
                        $bundleDoc = $doc; 
                    }
                } elseif (!empty($doc->file_path)) {
                    $otherDocs[] = $doc;
                }
            }
            ?>

            <?php if (!$bundleDoc && empty($otherDocs)): ?>
                <div class="dr-empty-state">
                    <i class="fas fa-exclamation-triangle"></i>
                    <p class="dr-empty-state__title">No Valid Submission Files Found</p>
                    <p class="dr-empty-state__sub">Wait for the custodian to upload the consolidated bundle.</p>
                </div>
            <?php else: ?>
                <div class="dr-item-list">
                    
                    <!-- HIGH-PRIORITY BUNDLE ITEM -->
                    <?php if ($bundleDoc): ?>
                        <div class="dr-bundle-card">
                            <span class="dr-tag dr-tag--primary">Primary Submission</span>
                            <div class="flex items-center gap-4">
                                <div class="dr-icon-box dr-icon-box--blue">
                                    <i class="fas fa-file-archive"></i>
                                </div>
                                <div class="flex-1">
                                    <h5 class="dr-bundle-title">CONSOLIDATED BUNDLE</h5>
                                    <div class="dr-bundle-meta">
                                        <i class="fas fa-calendar-alt mr-1"></i> Uploaded: <?= date('M d, Y • h:i A', strtotime($bundleDoc->uploaded_at)) ?>
                                    </div>
                                </div>
                                <a href="<?= ROOT ?>/public/<?= $bundleDoc->file_path ?>" target="_blank" class="cp-btn cp-btn--primary dr-btn-elevated">
                                    <i class="fas fa-download mr-2"></i> OPEN BUNDLE
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- OTHER SUPPLEMENTAL FILES -->
                    <?php foreach ($otherDocs as $doc): ?>
                        <div class="dr-doc-card-slim opacity-70">
                            <div class="dr-icon-box dr-icon-box--sm">
                                <i class="fas fa-file-pdf"></i>
                            </div>
                            <div class="flex-1">
                                <div class="dr-doc-type"><?= htmlspecialchars(str_replace('_', ' ', $doc->document_type)) ?></div>
                                <div class="dr-doc-meta"><?= date('M d • h:i A', strtotime($doc->uploaded_at)) ?></div>
                            </div>
                            <a href="<?= ROOT ?>/public/<?= $doc->file_path ?>" target="_blank" class="cp-btn cp-btn--outline dr-btn-xs">
                                VIEW
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Workflow Actions Area -->
        <?php if ($submission->document_status === 'PENDING_REVIEW'): ?>
            <div class="dr-workflow-box">
                <div class="dr-tabs">
                    <button type="button" 
                            onclick="toggleWorkflowAction('approve')" 
                            id="accBtn"
                            class="dr-tab-btn dr-tab-btn--success active">
                        APPROVE
                    </button>
                    <button type="button" 
                            onclick="toggleWorkflowAction('reject')" 
                            id="rejBtn"
                            class="dr-tab-btn dr-tab-btn--danger">
                        REJECT
                    </button>
                </div>

                <!-- Accept Form Section -->
                <div id="acceptArea" class="dr-workflow-action active">
                    <form action="<?= ROOT ?>/medical-school/submissions/accept" method="POST">
                        <input type="hidden" name="submission_id" value="<?= $submission->cis_id ?>">
                        
                        <div class="dr-form-area dr-form-area--success">
                            <div class="dr-grid dr-grid--2 mb-4">
                                <div>
                                    <label class="dr-slim-label">Handover Date</label>
                                    <input type="date" name="handover_date" value="<?= date('Y-m-d') ?>" min="<?= date('Y-m-d') ?>" class="dr-slim-input" required>
                                </div>
                                <div>
                                    <label class="dr-slim-label">Arrival Time</label>
                                    <input type="time" name="handover_time" class="dr-slim-input" required>
                                </div>
                            </div>

                            <div>
                                <label class="dr-slim-label">Special Instructions</label>
                                <textarea name="handover_message" class="dr-slim-textarea" placeholder="E.g. Use East Gate..."></textarea>
                            </div>
                        </div>

                        <button type="submit" class="cp-btn cp-btn--success dr-btn-full">
                            FINALIZE APPROVAL
                        </button>
                    </form>
                </div>

                <!-- Reject Form Section -->
                <div id="rejectArea" class="dr-workflow-action">
                    <form action="<?= ROOT ?>/medical-school/submissions/reject" method="POST">
                        <input type="hidden" name="submission_id" value="<?= $submission->cis_id ?>">
                        
                        <div class="dr-form-area dr-form-area--danger">
                            <div class="mb-4">
                                <label class="dr-slim-label">Rejection Reason</label>
                                <select name="rejection_reason_code" class="dr-slim-input" required onchange="document.getElementById('slimDocCheck').style.display = (this.value === 'DOCS_MISSING') ? 'block' : 'none';">
                                    <option value="">-- Choose --</option>
                                    <option value="DOCS_MISSING">Docs Missing</option>
                                    <option value="DOCS_INVALID">Invalid Docs</option>
                                    <option value="DOCS_UNREADABLE">Unreadable</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>

                            <div id="slimDocCheck" class="dr-doc-checklist">
                                <div class="dr-grid dr-grid--1 gap-1">
                                    <?php foreach (["Death Cert", "Medical Cert", "Custodian NIC", "Affidavit", "Cadaver Sheet"] as $docName): ?>
                                        <label class="dr-check-label">
                                            <input type="checkbox" name="missing_docs[]" value="<?= $docName ?>"> <?= $docName ?>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <div>
                                <label class="dr-slim-label">Notes</label>
                                <textarea name="reason_other" class="dr-slim-textarea h-60" placeholder="Details..."></textarea>
                            </div>
                        </div>

                        <button type="submit" class="cp-btn cp-btn--danger dr-btn-full">
                            CONFIRM REJECTION
                        </button>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script>
        function toggleWorkflowAction(action) {
            const accArea = document.getElementById('acceptArea');
            const rejArea = document.getElementById('rejectArea');
            const accBtn = document.getElementById('accBtn');
            const rejBtn = document.getElementById('rejBtn');

            if (action === 'approve') {
                accArea.classList.add('active');
                rejArea.classList.remove('active');
                accBtn.classList.add('active');
                rejBtn.classList.remove('active');
            } else {
                rejArea.classList.add('active');
                accArea.classList.remove('active');
                rejBtn.classList.add('active');
                accBtn.classList.remove('active');
            }
        }
    </script>
<?php endif; ?>
