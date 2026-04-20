<?php if (!$submission): ?>
    <div class="cp-empty">
        <i class="fas fa-search cp-empty__icon"></i>
        <h3 class="dr-empty-title">Submission Not Found</h3>
        <p>The requested record could not be retrieved from the database.</p>
    </div>
<?php else: ?>
    <!-- Premium Drawer Content -->
    <div class="dr-content">
        <style>
            .dr-time-presets {
                display: flex;
                gap: 6px;
                margin-top: 10px;
                flex-wrap: wrap;
            }
            .dr-time-chip {
                padding: 6px 14px;
                background: rgba(22, 163, 74, 0.06);
                border: 1px solid rgba(22, 163, 74, 0.15);
                border-radius: 30px;
                font-size: 0.7rem;
                font-weight: 700;
                color: #16a34a;
                cursor: pointer;
                transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
                user-select: none;
            }
            .dr-time-chip:hover {
                background: #16a34a;
                color: white;
                border-color: #16a34a;
                transform: translateY(-1px);
                box-shadow: 0 4px 12px rgba(22, 163, 74, 0.2);
            }
            .dr-time-chip:active {
                transform: translateY(0);
            }
            .dr-time-row {
                display: flex;
                gap: 2px;
                align-items: center;
                background: #f8fafc;
                padding: 2px;
                border: 1px solid #cbd5e1;
                border-radius: 10px;
                margin-top: 5px;
                transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
                box-shadow: inset 0 2px 4px rgba(0,0,0,0.03);
                height: 42px; /* Matches standard slim input */
            }
            .dr-time-row:focus-within {
                border-color: #16a34a;
                background: white;
                box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.1), inset 0 1px 2px rgba(0,0,0,0.05);
            }
            .dr-time-select {
                flex: 1;
                border: none;
                background: transparent;
                font-size: 0.9rem;
                font-weight: 800;
                color: #334155;
                padding: 0 4px;
                height: 100%;
                cursor: pointer;
                outline: none;
                text-align: center;
                -webkit-appearance: none;
                appearance: none;
                border-radius: 6px;
                transition: all 0.2s;
            }
            .dr-time-select:hover {
                background: rgba(22, 163, 74, 0.08);
                color: #16a34a;
            }
            .dr-time-sep {
                font-weight: 900;
                color: #94a3b8;
                font-size: 1.1rem;
                opacity: 0.4;
                user-select: none;
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
            
            .dr-form-area {
                padding: 16px;
                border-radius: 12px;
                margin-bottom: 16px;
            }
            .dr-grid--2 {
                gap: 12px;
            }
        </style>
        
        <!-- Slimmed Premium Header -->
        <div class="dr-header">
            <div class="dr-header__inner">
                <div class="flex items-center justify-between mb-4">
                    <span class="cp-status-bubble cp-status-bubble--<?= strtolower($submission->document_status) ?>">
                        <i class="fas fa-circle mr-2" style="font-size: 0.4rem; opacity: 0.8;"></i>
                        <?= htmlspecialchars(str_replace('_', ' ', $submission->document_status)) ?>
                    </span>
                    <div class="dr-case-tag">Case #<?= htmlspecialchars($submission->case_number) ?></div>
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
                            onclick="toggleDocWorkflow('approve')" 
                            id="doc_accBtn"
                            class="dr-tab-btn dr-tab-btn--success active">
                        APPROVE
                    </button>
                    <button type="button" 
                            onclick="toggleDocWorkflow('reject')" 
                            id="doc_rejBtn"
                            class="dr-tab-btn dr-tab-btn--danger">
                        REJECT
                    </button>
                </div>

                <!-- Accept Form Section -->
                <div id="doc_acceptArea" class="dr-workflow-action active">
                    <form id="approveForm" action="<?= ROOT ?>/medical-school/submissions/accept" method="POST" onsubmit="syncCustomTime()">
                        <input type="hidden" name="submission_id" value="<?= $submission->cis_id ?>">
                        
                        <div class="dr-form-area dr-form-area--success">
                            <div class="dr-grid dr-grid--2 mb-4">
                                <div>
                                    <label class="dr-slim-label">Handover Date</label>
                                    <input type="date" name="handover_date" value="<?= date('Y-m-d') ?>" min="<?= date('Y-m-d') ?>" class="dr-slim-input" required>
                                </div>
                                <div>
                                    <label class="dr-slim-label">Arrival Time</label>
                                    <input type="hidden" name="handover_time" id="handover_time_input" value="09:00" required>
                                    
                                    <div class="dr-time-row">
                                        <select id="cust_hr" class="dr-time-select" onchange="syncCustomTime()">
                                            <?php for($i=1; $i<=12; $i++): ?>
                                                <option value="<?= str_pad($i, 2, '0', STR_PAD_LEFT) ?>" <?= ($i==9 ? 'selected' : '') ?>><?= str_pad($i, 2, '0', STR_PAD_LEFT) ?></option>
                                            <?php endfor; ?>
                                        </select>
                                        <span class="dr-time-sep">:</span>
                                        <select id="cust_min" class="dr-time-select" onchange="syncCustomTime()">
                                            <?php for($i=0; $i<60; $i+=5): ?>
                                                <option value="<?= str_pad($i, 2, '0', STR_PAD_LEFT) ?>"><?= str_pad($i, 2, '0', STR_PAD_LEFT) ?></option>
                                            <?php endfor; ?>
                                        </select>
                                        <select id="cust_ampm" class="dr-time-select" onchange="syncCustomTime()">
                                            <option value="AM" selected>AM</option>
                                            <option value="PM">PM</option>
                                        </select>
                                    </div>

                                    <div class="dr-time-presets" style="display: flex; gap: 6px; margin-top: 10px; flex-wrap: wrap;">
                                        <div class="dr-time-chip" onclick="setTimeVal('09:00')">09:00 AM</div>
                                        <div class="dr-time-chip" onclick="setTimeVal('11:00')">11:00 AM</div>
                                        <div class="dr-time-chip" onclick="setTimeVal('14:00')">02:00 PM</div>
                                        <div class="dr-time-chip" onclick="setTimeVal('16:00')">04:00 PM</div>
                                    </div>
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
                <div id="doc_rejectArea" class="dr-workflow-action">
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

    <!-- Toggle logic moved to main page script -->
    <script>
        /**
         * Robust sync function to ensure hidden input matches dropdowns
         */
        function syncCustomTime() {
            const hrField = document.getElementById('cust_hr');
            const minField = document.getElementById('cust_min');
            const ampmField = document.getElementById('cust_ampm');
            const targetInput = document.getElementById('handover_time_input');

            if (!hrField || !minField || !ampmField || !targetInput) return;

            const hr = hrField.value;
            const min = minField.value;
            const ampm = ampmField.value;
            
            let hrInt = parseInt(hr);
            if (ampm === 'PM' && hrInt < 12) hrInt += 12;
            if (ampm === 'AM' && hrInt === 12) hrInt = 0;
            
            const finalTime = hrInt.toString().padStart(2, '0') + ':' + min;
            targetInput.value = finalTime;
            return true;
        }

        function setTimeVal(val) {
            const input = document.getElementById('handover_time_input');
            if (input) {
                input.value = val;
                
                // Update visuals
                const [h, m] = val.split(':');
                const hInt = parseInt(h);
                const ampm = hInt >= 12 ? 'PM' : 'AM';
                const displayHr = hInt % 12 || 12;
                
                const hrSelect = document.getElementById('cust_hr');
                const minSelect = document.getElementById('cust_min');
                const ampmSelect = document.getElementById('cust_ampm');

                if (hrSelect) hrSelect.value = displayHr.toString().padStart(2, '0');
                if (minSelect) minSelect.value = m;
                if (ampmSelect) ampmSelect.value = ampm;
                
                input.dispatchEvent(new Event('change'));
            }
        }

        // Immediate initialization + brief delay for AJAX race conditions
        syncCustomTime();
        setTimeout(syncCustomTime, 100);
        setTimeout(syncCustomTime, 500); 
    </script>
<?php endif; ?>
