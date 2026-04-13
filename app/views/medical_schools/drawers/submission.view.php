<?php if (!$submission): ?>
    <div class="cp-empty">
        <i class="fas fa-search cp-empty__icon"></i>
        <h3 style="color: var(--slate);">Submission Not Found</h3>
        <p>The requested record could not be retrieved from the database.</p>
    </div>
<?php else: ?>
    <!-- Premium Drawer Content -->
    <div style="padding: 0 5px;">
        
        <!-- Slimmed Premium Header -->
        <div style="background: linear-gradient(135deg, var(--blue-700), var(--blue-900)); padding: 1.25rem; border-radius: 12px; color: white; margin-bottom: 1.25rem; box-shadow: 0 4px 15px rgba(0, 91, 170, 0.1); position: relative; overflow: hidden;">
            <div style="position: relative; z-index: 1;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.75rem;">
                    <span style="background: rgba(255,255,255,0.15); padding: 3px 10px; border-radius: 50px; font-size: 0.65rem; font-weight: 700; text-transform: uppercase;">
                        Case #<?= htmlspecialchars($submission->case_number) ?>
                    </span>
                    <span class="cp-status-badge" style="background: white; color: var(--blue-700); border: none; padding: 4px 12px; font-size: 0.7rem; font-weight: 800;">
                        <i class="fas fa-clock mr-1"></i> <?= htmlspecialchars(str_replace('_', ' ', $submission->document_status)) ?>
                    </span>
                </div>
                <h3 style="font-weight: 800; font-size: 1.25rem; margin-bottom: 0.25rem;">Document Bundle Review</h3>
                <p style="opacity: 0.8; font-size: 0.8rem; margin: 0;">Verify legal and medical paperwork for the deceased donor.</p>
            </div>
        </div>

        <!-- Compact Donor Info -->
        <div style="background: white; border: 1px solid var(--g200); border-radius: 12px; margin-bottom: 1.25rem; padding: 1rem 1.25rem;">
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 1rem; border-bottom: 1px solid var(--g100); padding-bottom: 0.5rem;">
                <i class="fas fa-id-card-alt" style="color: var(--blue-600); font-size: 0.8rem;"></i>
                <span style="font-weight: 800; font-size: 0.75rem; color: var(--g500); text-transform: uppercase; letter-spacing: 0.05em;">Donor Information</span>
            </div>
            <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 1rem;">
                <div>
                    <label style="display: block; font-size: 0.65rem; color: var(--g500); font-weight: 700; text-transform: uppercase; margin-bottom: 2px;">Name</label>
                    <div style="font-weight: 800; color: var(--slate); font-size: 1rem;"><?= htmlspecialchars($submission->first_name . ' ' . $submission->last_name) ?></div>
                </div>
                <div>
                    <label style="display: block; font-size: 0.65rem; color: var(--g500); font-weight: 700; text-transform: uppercase; margin-bottom: 2px;">NIC</label>
                    <div style="font-weight: 700; color: var(--slate); font-size: 0.9rem;"><?= htmlspecialchars($submission->nic_number) ?></div>
                </div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-top: 0.75rem;">
                <div style="font-size: 0.75rem;"><span style="color: var(--g500); font-weight: 700; text-transform: uppercase; font-size: 0.6rem; display: block;">Nationality</span> <?= htmlspecialchars($submission->nationality ?? 'Sri Lankan') ?></div>
                <div style="font-size: 0.75rem;"><span style="color: var(--g500); font-weight: 700; text-transform: uppercase; font-size: 0.6rem; display: block;">Age</span> <?= $submission->date_of_birth ? floor((time() - strtotime($submission->date_of_birth)) / 31556926) : 'N/A' ?> Years</div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-top: 0.75rem; border-top: 1px solid var(--g50); padding-top: 0.5rem;">
                <div style="font-size: 0.75rem;"><span style="color: var(--g500); font-weight: 700; text-transform: uppercase; font-size: 0.6rem; display: block;">Date of Death</span> <?= $submission->date_of_death ? date('d M, Y', strtotime($submission->date_of_death)) : 'N/A' ?></div>
                <div style="font-size: 0.75rem;"><span style="color: var(--g500); font-weight: 700; text-transform: uppercase; font-size: 0.6rem; display: block;">Submission Date</span> <?= date('d M, Y', strtotime($submission->submission_date ?? $submission->created_at)) ?></div>
            </div>
        </div>

        <!-- Family Custodians Section (Compact Style) -->
        <div style="background: white; border: 1px solid var(--blue-100); border-radius: 12px; margin-bottom: 1.25rem; padding: 1rem; box-shadow: 0 2px 8px rgba(0, 91, 170, 0.02);">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem; border-bottom: 1px solid var(--blue-50); padding-bottom: 0.5rem;">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-user-shield" style="color: var(--blue-600); font-size: 0.85rem;"></i>
                    <span style="font-weight: 800; font-size: 0.75rem; color: var(--blue-700); text-transform: uppercase; letter-spacing: 0.05em;">Family Custodians</span>
                </div>
                <span style="font-size: 0.65rem; color: var(--g500); font-weight: 700; background: var(--g50); padding: 2px 10px; border-radius: 50px;">
                    <?= count($custodians) ?>
                </span>
            </div>

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
        </div>

        <!-- Slim Document List -->
        <div style="margin-bottom: 1.5rem;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.75rem; padding: 0 2px;">
                <h4 style="font-weight: 800; color: var(--slate); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">
                    <i class="fas fa-layer-group" style="color: var(--blue-500); margin-right: 6px;"></i> Submission Bundle
                </h4>
            </div>
            
            <?php 
            $bundleDoc = null;
            $otherDocs = [];
            foreach ($documents as $doc) {
                $isBundle = (strpos(strtoupper($doc->document_type), 'BUNDLE') !== false);
                if ($isBundle) {
                    if (!$bundleDoc) {
                        $bundleDoc = $doc; // Take the latest one as primary
                    }
                    // Skip older bundles entirely to avoid confusion
                } elseif (!empty($doc->file_path)) {
                    $otherDocs[] = $doc;
                }
            }
            ?>

            <?php if (!$bundleDoc && empty($otherDocs)): ?>
                <div style="padding: 2.5rem 1rem; text-align: center; background: #fffcf0; border: 2px dashed #fef3c7; border-radius: 16px;">
                    <i class="fas fa-exclamation-triangle" style="color: #d97706; font-size: 1.5rem; margin-bottom: 10px;"></i>
                    <p style="color: #92400e; font-size: 0.9rem; font-weight: 700; margin: 0;">No Valid Submission Files Found</p>
                    <p style="color: #b45309; font-size: 0.75rem; margin-top: 4px;">Wait for the custodian to upload the consolidated bundle.</p>
                </div>
            <?php else: ?>
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    
                    <!-- HIGH-PRIORITY BUNDLE ITEM -->
                    <?php if ($bundleDoc): ?>
                        <div style="background: linear-gradient(to right, #f0f9ff, white); border: 2px solid var(--blue-200); padding: 1.25rem; border-radius: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.03); position: relative;">
                            <span style="position: absolute; top: -10px; right: 20px; background: var(--blue-600); color: white; padding: 2px 12px; border-radius: 50px; font-size: 0.6rem; font-weight: 800; text-transform: uppercase;">Primary Submission</span>
                            <div style="display:flex; align-items:center; gap: 1rem;">
                                <div style="width: 48px; height: 48px; background: #e0f2fe; color: var(--blue-600); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; flex-shrink: 0;">
                                    <i class="fas fa-file-archive"></i>
                                </div>
                                <div style="flex: 1;">
                                    <h5 style="font-weight: 800; font-size: 0.95rem; color: var(--blue-900); margin: 0 0 4px 0;">CONSOLIDATED BUNDLE</h5>
                                    <div style="font-size: 0.75rem; color: var(--blue-600); font-weight: 700;">
                                        <i class="fas fa-calendar-alt mr-1"></i> Uploaded: <?= date('M d, Y • h:i A', strtotime($bundleDoc->uploaded_at)) ?>
                                    </div>
                                </div>
                                <a href="<?= ROOT ?>/public/<?= $bundleDoc->file_path ?>" target="_blank" class="cp-btn cp-btn--primary" style="border-radius: 10px; padding: 10px 22px; font-size: 0.85rem; font-weight: 800; box-shadow: 0 4px 10px rgba(0, 91, 170, 0.2);">
                                    <i class="fas fa-download mr-2"></i> OPEN BUNDLE
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- OTHER ACCIDENTAL OR SUPPLEMENTAL FILES -->
                    <?php foreach ($otherDocs as $doc): ?>
                        <div class="cp-doc-card-slim" style="opacity: 0.7;">
                            <div style="width: 32px; height: 32px; background: var(--g100); color: var(--g500); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 0.9rem; flex-shrink: 0;">
                                <i class="fas fa-file-pdf"></i>
                            </div>
                            <div style="flex: 1;">
                                <div style="font-weight: 700; font-size: 0.75rem; color: var(--g600); text-transform: uppercase;"><?= htmlspecialchars(str_replace('_', ' ', $doc->document_type)) ?></div>
                                <div style="font-size: 0.65rem; color: var(--g400);"><?= date('M d • h:i A', strtotime($doc->uploaded_at)) ?></div>
                            </div>
                            <a href="<?= ROOT ?>/public/<?= $doc->file_path ?>" target="_blank" class="cp-btn cp-btn--outline" style="border-radius: 6px; padding: 4px 10px; font-size: 0.7rem; font-weight: 700;">
                                VIEW
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Workflow Actions Area -->
        <?php if ($submission->document_status === 'PENDING_REVIEW'): ?>
            <div style="background: #f8fafc; border: 1px solid var(--g200); border-radius: 16px; padding: 1.25rem;">
                <div style="display: flex; background: #e2e8f0; padding: 4px; border-radius: 10px; gap: 4px; margin-bottom: 1.25rem;">
                    <button type="button" 
                            onclick="document.getElementById('acceptArea').style.display='block'; document.getElementById('rejectArea').style.display='none'; this.style.background='white'; this.style.boxShadow='0 2px 5px rgba(0,0,0,0.05)'; document.getElementById('rejBtn').style.background='transparent'; document.getElementById('rejBtn').style.boxShadow='none';" 
                            id="accBtn"
                            style="flex: 1; border: none; padding: 10px; border-radius: 8px; font-weight: 800; font-size: 0.8rem; cursor: pointer; background: white; box-shadow: 0 2px 5px rgba(0,0,0,0.05); color: var(--green-600);">
                        APPROVE
                    </button>
                    <button type="button" 
                            onclick="document.getElementById('rejectArea').style.display='block'; document.getElementById('acceptArea').style.display='none'; this.style.background='white'; this.style.boxShadow='0 2px 5px rgba(0,0,0,0.05)'; document.getElementById('accBtn').style.background='transparent'; document.getElementById('accBtn').style.boxShadow='none';" 
                            id="rejBtn"
                            style="flex: 1; border: none; padding: 10px; border-radius: 8px; font-weight: 800; font-size: 0.8rem; cursor: pointer; background: transparent; color: var(--red-600);">
                        REJECT
                    </button>
                </div>

                <!-- Accept Form Section -->
                <div id="acceptArea" style="display: block; animation: slideDown 0.2s ease;">
                    <form action="<?= ROOT ?>/medical-school/submissions/accept" method="POST">
                        <input type="hidden" name="submission_id" value="<?= $submission->cis_id ?>">
                        
                        <div style="background: white; border: 1px solid var(--green-100); border-radius: 12px; padding: 1rem; margin-bottom: 0.75rem;">
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-bottom: 1rem;">
                                <div>
                                    <label class="slim-label">Handover Date</label>
                                    <input type="date" name="handover_date" value="<?= date('Y-m-d') ?>" min="<?= date('Y-m-d') ?>" class="slim-input" required>
                                </div>
                                <div>
                                    <label class="slim-label">Arrival Time</label>
                                    <input type="time" name="handover_time" class="slim-input" required>
                                </div>
                            </div>

                            <div>
                                <label class="slim-label">Special Instructions</label>
                                <textarea name="handover_message" class="slim-textarea" placeholder="E.g. Use East Gate..."></textarea>
                            </div>
                        </div>

                        <button type="submit" class="cp-btn cp-btn--success" style="width: 100%; height: 44px; font-weight: 800; font-size: 0.9rem; border-radius: 10px;">
                            FINALIZE APPROVAL
                        </button>
                    </form>
                </div>

                <!-- Reject Form Section -->
                <div id="rejectArea" style="display: none; animation: slideDown 0.2s ease;">
                    <form action="<?= ROOT ?>/medical-school/submissions/reject" method="POST">
                        <input type="hidden" name="submission_id" value="<?= $submission->cis_id ?>">
                        
                        <div style="background: white; border: 1px solid var(--red-100); border-radius: 12px; padding: 1rem; margin-bottom: 0.75rem;">
                            <div style="margin-bottom: 1rem;">
                                <label class="slim-label">Rejection Reason</label>
                                <select name="rejection_reason_code" class="slim-input" required onchange="document.getElementById('slimDocCheck').style.display = (this.value === 'DOCS_MISSING') ? 'block' : 'none';">
                                    <option value="">-- Choose --</option>
                                    <option value="DOCS_MISSING">Docs Missing</option>
                                    <option value="DOCS_INVALID">Invalid Docs</option>
                                    <option value="DOCS_UNREADABLE">Unreadable</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>

                            <div id="slimDocCheck" style="display: none; background: #fff5f5; border: 1px solid #fee2e2; border-radius: 8px; padding: 0.75rem; margin-bottom: 1rem;">
                                <div style="display: grid; grid-template-columns: 1fr; gap: 4px;">
                                    <?php foreach (["Death Cert", "Medical Cert", "Custodian NIC", "Affidavit", "Cadaver Sheet"] as $docName): ?>
                                        <label style="display: flex; align-items: center; gap: 8px; font-size: 0.75rem; color: #475569; cursor: pointer;">
                                            <input type="checkbox" name="missing_docs[]" value="<?= $docName ?>" style="width: 15px; height: 15px;"> <?= $docName ?>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <div>
                                <label class="slim-label">Notes</label>
                                <textarea name="reason_other" class="slim-textarea" style="height: 60px;" placeholder="Details..."></textarea>
                            </div>
                        </div>

                        <button type="submit" class="cp-btn cp-btn--danger" style="width: 100%; height: 44px; font-weight: 800; font-size: 0.9rem; border-radius: 10px;">
                            CONFIRM REJECTION
                        </button>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <style>
        .cp-doc-card-slim {
            background: white;
            padding: 0.75rem 1rem;
            border-radius: 12px;
            border: 1px solid var(--g200);
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: all 0.2s;
        }
        .cp-doc-card-slim:hover {
            border-color: var(--blue-300);
            background: var(--blue-50);
        }
        .slim-label {
            display: block;
            font-size: 0.65rem;
            font-weight: 800;
            color: var(--g500);
            margin-bottom: 4px;
            text-transform: uppercase;
        }
        .slim-input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid var(--g200);
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--slate);
        }
        .slim-textarea {
            width: 100%;
            height: 70px;
            padding: 10px;
            border: 1px solid var(--g200);
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 500;
            resize: none;
        }
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-5px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
<?php endif; ?>
