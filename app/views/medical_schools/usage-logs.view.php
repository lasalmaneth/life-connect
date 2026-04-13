<?php
/**
 * Medical School Portal — Enhanced Anatomical Usage & Inventory
 */

$page_title    = 'Body Usage';
$active_page   = 'usage-logs';

ob_start();
?>

<div class="cp-content-header">
    <div class="cp-content-header__content">
        <h1 class="cp-content-header__title"><i class="fas fa-folder-open"></i> Body Usage</h1>
        <p class="cp-content-header__subtitle">Anatomical training and research log for institutional inventory.</p>
    </div>
    <div class="cp-content-header__actions">
        <button class="cp-btn cp-btn--primary" onclick="openUsageModal()">
            <i class="fas fa-plus cp-mr-2"></i> Record Activity
        </button>
    </div>
</div>

<div class="cp-content-body">
    
    <!-- Unified Stats Row (Standardized) -->
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; margin-bottom: 2rem;">
        <div class="cp-card" style="padding: 1.25rem; display: flex; align-items: center; gap: 1rem; border-bottom: 3px solid var(--blue-500);">
            <div style="width: 44px; height: 44px; border-radius: 10px; background: var(--blue-50); color: var(--blue-600); display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                <i class="fas fa-layer-group"></i>
            </div>
            <div>
                <div style="font-size: 0.7rem; font-weight: 800; color: var(--g400); text-transform: uppercase;">Total Inventory</div>
                <div style="font-size: 1.25rem; font-weight: 900; color: var(--blue-900);"><?= $inventoryStats['total'] ?? 0 ?></div>
            </div>
        </div>
        <div class="cp-card" style="padding: 1.25rem; display: flex; align-items: center; gap: 1rem; border-bottom: 3px solid #10b981;">
            <div style="width: 44px; height: 44px; border-radius: 10px; background: #f0fdf4; color: #15803d; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                <i class="fas fa-check-double"></i>
            </div>
            <div>
                <div style="font-size: 0.7rem; font-weight: 800; color: var(--g400); text-transform: uppercase;">Pristine (Unused)</div>
                <div style="font-size: 1.25rem; font-weight: 900; color: #15803d;"><?= $inventoryStats['pristine'] ?? 0 ?></div>
            </div>
        </div>
        <div class="cp-card" style="padding: 1.25rem; display: flex; align-items: center; gap: 1rem; border-bottom: 3px solid #f59e0b;">
            <div style="width: 44px; height: 44px; border-radius: 10px; background: #fffbeb; color: #b45309; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                <i class="fas fa-microscope"></i>
            </div>
            <div>
                <div style="font-size: 0.7rem; font-weight: 800; color: var(--g400); text-transform: uppercase;">In-Use Records</div>
                <div style="font-size: 1.25rem; font-weight: 900; color: #b45309;"><?= $inventoryStats['utilized'] ?? 0 ?></div>
            </div>
        </div>
        <div class="cp-card" style="padding: 1.25rem; display: flex; align-items: center; gap: 1rem; border-bottom: 3px solid #8b5cf6;">
            <div style="width: 44px; height: 44px; border-radius: 10px; background: #f5f3ff; color: #6d28d9; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                <i class="fas fa-paper-plane"></i>
            </div>
            <div>
                <div style="font-size: 0.7rem; font-weight: 800; color: var(--g400); text-transform: uppercase;">Letters Issued</div>
                <div style="font-size: 1.25rem; font-weight: 900; color: #6d28d9;"><?= count(array_filter($logs, fn($l) => !empty($l->letter_id))) ?></div>
            </div>
        </div>
    </div>

    <!-- Active Body Summary (If selected) -->
    <?php if ($caseInfo): ?>
    <div class="cp-card" style="margin-bottom: 2rem; border: 1px solid var(--blue-100); background: #f0f7ff;">
        <div style="display: grid; grid-template-columns: repeat(5, 1fr); padding: 1.25rem; gap: 1rem;">
            <div>
                <div style="font-size: 0.65rem; font-weight: 800; color: var(--blue-400); text-transform: uppercase; margin-bottom: 2px;">Case ID</div>
                <div style="font-weight: 800; color: var(--blue-900);">#<?= $caseInfo->case_number ?></div>
            </div>
            <div>
                <div style="font-size: 0.65rem; font-weight: 800; color: var(--blue-400); text-transform: uppercase; margin-bottom: 2px;">Donor Name</div>
                <div style="font-weight: 700; color: var(--blue-800);"><?= htmlspecialchars($caseInfo->first_name . ' ' . $caseInfo->last_name) ?></div>
            </div>
            <div>
                <div style="font-size: 0.65rem; font-weight: 800; color: var(--blue-400); text-transform: uppercase; margin-bottom: 2px;">Cadaver Reference</div>
                <div style="font-weight: 700; color: var(--blue-900);">CD-<?= str_pad($caseInfo->donor_id, 7, '0', STR_PAD_LEFT) ?></div>
            </div>
            <div>
                <div style="font-size: 0.65rem; font-weight: 800; color: var(--blue-400); text-transform: uppercase; margin-bottom: 2px;">Medical State</div>
                <span class="cp-badge cp-badge--info" style="font-size: 0.65rem;"><?= htmlspecialchars($caseInfo->current_condition ?? 'Accepted') ?></span>
            </div>
            <div style="text-align: right;">
                <a href="<?= ROOT ?>/medical-school/usage-logs" class="cp-btn cp-btn--secondary cp-btn--sm" style="background: #fff;">Clear Filter</a>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Navigation Filter Bar (Premium Segmented Control) -->
    <div class="ms-filter-wrapper">
        <div class="ms-filter-group">
            <button class="ms-filter-btn active" onclick="switchTab('usage-logs', this)">Used Bodies</button>
            <button class="ms-filter-btn" onclick="switchTab('inventory-list', this)">Unused (Pristine)</button>
        </div>
    </div>

    <!-- Used Bodies Tab Content (Logs) -->
    <div id="usage-logs" class="tab-content active transition-fade">
        <div class="cp-table-container">
            <table class="cp-table">
                <thead>
                    <tr>
                        <th style="width: 240px; padding: 1.25rem;">Donor Cadaver</th>
                        <th>Usage Date</th>
                        <th>Department</th>
                        <th>Subject / Purpose</th>
                        <th>Physician</th>
                        <th style="text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($logs)): ?>
                        <tr>
                            <td colspan="6">
                                <div class="cp-empty-state" style="padding: 4rem;">
                                    <i class="fas fa-microscope cp-empty-state__icon"></i>
                                    <div class="cp-empty-state__msg">No Usage Activities</div>
                                    <div class="cp-empty-state__sub">Records of used anatomical components will appear here.</div>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($logs as $log): ?>
                            <tr class="usage-row">
                                <td>
                                    <div class="cp-table__icon-cell">
                                        <div class="cp-table__file-icon cp-table__file-icon--primary">
                                            <i class="fas fa-user-tag"></i>
                                        </div>
                                        <div>
                                            <div class="cp-table__filename"><?= htmlspecialchars($log->first_name . ' ' . $log->last_name) ?></div>
                                            <div class="cp-table__subtext">#<?= $log->case_number ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="cp-table__filename"><?= date('d M Y', strtotime($log->usage_date)) ?></div>
                                    <div class="cp-table__subtext"><?= htmlspecialchars($log->duration ?? 'Unspecified') ?></div>
                                </td>
                                <td style="font-weight: 700; color: var(--blue-900);"><?= htmlspecialchars($log->usage_department) ?></td>
                                <td>
                                    <div class="cp-table__filename"><?= htmlspecialchars($log->subject_area) ?></div>
                                    <div class="cp-table__subtext"><?= htmlspecialchars($log->usage_type) ?></div>
                                </td>
                                <td style="font-weight: 600; color: var(--g700);"><?= htmlspecialchars($log->handled_by) ?></td>
                                <td style="text-align: right;">
                                    <div class="cp-table__actions">
                                        <button class="cp-btn cp-btn--secondary cp-btn--sm" onclick='openUsageDetail(<?= json_encode($log) ?>)'>
                                            <i class="fas fa-eye"></i> Usage Details
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Unused Bodies Tab Content (Inventory) -->
    <div id="inventory-list" class="tab-content transition-fade" style="display: none;">
        <div class="cp-table-container">
            <table class="cp-table">
                <thead>
                    <tr>
                        <th style="padding: 1.25rem;">Cadaver ID</th>
                        <th>Medical Status</th>
                        <th>Current Assignment</th>
                        <th style="text-align: center;">Utilization</th>
                        <th style="text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $pristine = array_filter($inventory, fn($i) => $i->usage_count == 0); ?>
                    <?php if (empty($pristine)): ?>
                        <tr>
                            <td colspan="5">
                                <div class="cp-empty-state" style="padding: 4rem;">
                                    <i class="fas fa-box-open cp-empty-state__icon"></i>
                                    <div class="cp-empty-state__msg">Zero Pristine Bodies</div>
                                    <div class="cp-empty-state__sub">All accepted inventory has been initiated for usage.</div>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($pristine as $item): ?>
                            <tr>
                                <td>
                                    <div class="cp-table__icon-cell">
                                        <div class="cp-table__file-icon cp-table__file-icon--success">
                                            <i class="fas fa-flask"></i>
                                        </div>
                                        <div>
                                            <div class="cp-table__filename">#<?= htmlspecialchars($item->case_number) ?></div>
                                            <div class="cp-table__subtext"><?= htmlspecialchars($item->first_name . ' ' . $item->last_name) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="cp-badge cp-badge--active">
                                        <?= htmlspecialchars($item->current_condition ?? 'Pristine') ?>
                                    </span>
                                </td>
                                <td style="font-weight: 700; color: var(--blue-700);"><?= htmlspecialchars($item->assigned_department ?? 'Anatomy Dept') ?></td>
                                <td style="text-align: center;">
                                    <div class="cp-table__filename"><?= $item->usage_count ?> Times</div>
                                    <div class="cp-table__subtext">Unused</div>
                                </td>
                                <td style="text-align: right;">
                                    <div class="cp-table__actions">
                                        <a href="<?= ROOT ?>/medical-school/usage-logs?cis_id=<?= $item->cis_id ?>" class="cp-btn cp-btn--secondary cp-btn--sm">
                                            <i class="fas fa-eye"></i> Usage Details
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Standard Side Drawer -->
<div class="drawer-overlay" id="usageDetailDrawerOverlay" onclick="closeUsageDetail()">
    <div class="drawer-content" id="usageDetailDrawer" onclick="event.stopPropagation()">
        <div class="drawer-header" style="padding: 1.5rem; border-bottom: 1px solid var(--g100); background: #fff; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h3 style="margin: 0; font-size: 1.15rem; font-weight: 800; color: var(--blue-900);">Utilization Details</h3>
                <p style="margin: 4px 0 0; font-size: 0.75rem; color: var(--g400); font-weight: 600;">Academic activity review and letter management.</p>
            </div>
            <button class="cp-drawer-close" onclick="closeUsageDetail()" style="border: none; background: var(--g50); width: 32px; height: 32px; border-radius: 8px; cursor: pointer; color: var(--g400);">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div id="usageDetailBody" style="padding: 1.5rem 2rem; overflow-y: auto; flex: 1;">
            <!-- Content filled by JS -->
        </div>

        <div style="padding: 1.5rem 2rem; border-top: 1px solid var(--g100); background: #f8fafc; display: flex; gap: 12px; justify-content: flex-end;">
            <button class="cp-btn cp-btn--secondary" style="width: 100%;" onclick="window.print()"><i class="fas fa-print cp-mr-2"></i> Print Usage Record</button>
        </div>
    </div>
</div>

<!-- Record Usage Modal (Premium Style) -->
<div class="ms-modal-overlay" id="usageModalOverlay" onclick="if(event.target == this) closeUsageModal()">
    <div class="ms-modal">
        <div class="ms-modal__header">
            <h2 class="ms-modal__title"><i class="fas fa-plus-circle"></i> Record Academic Usage</h2>
            <button class="ms-modal__close" onclick="closeUsageModal()">&times;</button>
        </div>
        <form action="<?= ROOT ?>/medical-school/usage-logs/submit" method="POST">
            <div class="ms-modal__body">
                <div class="cp-form-grid">
                    
                    <div style="grid-column: span 2;">
                        <label class="cp-label">Select Target Cadaver <span style="color: #ef4444;">*</span></label>
                        <select name="donor_id" class="cp-input" required>
                            <?php if ($caseInfo && $caseInfo->usage_count == 0): ?>
                                <option value="<?= $caseInfo->donor_id ?>"><?= htmlspecialchars($caseInfo->first_name . ' ' . $caseInfo->last_name) ?> (#<?= $caseInfo->case_number ?>)</option>
                            <?php else: ?>
                                <option value="">-- Select Unused Body --</option>
                                <?php foreach ($inventory as $inv): ?>
                                    <?php if ($inv->usage_count == 0): ?>
                                        <option value="<?= $inv->donor_id ?>"><?= htmlspecialchars($inv->first_name . ' ' . $inv->last_name) ?> (#<?= $inv->case_number ?>)</option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <div class="cp-help-text">
                            <i class="fas fa-shield-alt"></i> Only physically accepted, unused cadavers are listed above.
                        </div>
                    </div>

                    <div>
                        <label class="cp-label">Usage Date <span style="color: #ef4444;">*</span></label>
                        <input type="date" name="usage_date" value="<?= date('Y-m-d') ?>" class="cp-input" required>
                    </div>

                    <div>
                        <label class="cp-label">Department <span style="color: #ef4444;">*</span></label>
                        <select name="usage_department" id="usage_dept_select" class="cp-input" onchange="toggleOtherFields()" required>
                            <option value="Anatomy Dept">Anatomy Dept</option>
                            <option value="Surgery Dept">Surgery Dept</option>
                            <option value="Medical Education">Medical Education</option>
                            <option value="Research Unit">Research Unit</option>
                            <option value="Other">Other</option>
                        </select>
                        <div id="other_dept_container" style="display: none; margin-top: 10px;">
                            <input type="text" name="other_dept" placeholder="Enter Department Name" class="cp-input">
                        </div>
                    </div>

                    <div style="grid-column: span 2;">
                        <label class="cp-label">Purpose & Subject Area <span style="color: #ef4444;">*</span></label>
                        <div style="display: flex; gap: 12px; flex-direction: column;">
                            <div style="display: flex; gap: 12px;">
                                <select name="usage_type" class="cp-input" style="flex: 1;">
                                    <option value="Teaching">Teaching</option>
                                    <option value="Training">Training</option>
                                    <option value="Research">Research</option>
                                </select>
                                <select name="subject_area" id="subject_area_select" class="cp-input" style="flex: 2;" onchange="toggleOtherFields()" required>
                                    <option value="Gross Anatomy">Gross Anatomy</option>
                                    <option value="Neuroanatomy">Neuroanatomy</option>
                                    <option value="Surgical Skills">Surgical Skills</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div id="other_subject_container" style="display: none;">
                                <input type="text" name="other_subject" placeholder="Enter Subject Area" class="cp-input">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="cp-label">Handled By</label>
                        <input type="text" name="handled_by" placeholder="e.g. Dr. Smith" class="cp-input" required>
                    </div>

                    <div>
                        <label class="cp-label">Duration</label>
                        <input type="text" name="duration" placeholder="e.g. 2 hours" class="cp-input">
                    </div>

                    <div style="grid-column: span 2;">
                        <label class="cp-label">Activity Description</label>
                        <textarea name="description" class="cp-input cp-textarea" rows="3" placeholder="Provide a brief summary of the academic activities performed..." required></textarea>
                    </div>

                </div>
            </div>
            
            <div class="ms-modal__footer">
                <button type="button" class="cp-btn--discard" onclick="closeUsageModal()">Discard</button>
                <button type="submit" class="cp-btn--save">Save Record</button>
            </div>
        </form>
    </div>
</div>

<script>
let currentLog = null;

function switchTab(tabId, btn) {
    // Content toggle
    document.querySelectorAll('.tab-content').forEach(c => {
        c.style.display = 'none';
        c.classList.remove('active');
    });
    const target = document.getElementById(tabId);
    target.style.display = 'block';
    setTimeout(() => target.classList.add('active'), 10);

    // Button state toggle
    document.querySelectorAll('.ms-filter-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
}

function openUsageDetail(log) {
    currentLog = log;
    const body = document.getElementById('usageDetailBody');
    const overlay = document.getElementById('usageDetailDrawerOverlay');
    const drawer = document.getElementById('usageDetailDrawer');

    const formattedDate = new Date(log.usage_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
    const refNum = `#UR-${new Date(log.usage_date).getFullYear()}-${String(log.id).padStart(3, '0')}`;

    body.innerHTML = `
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2rem;">
            <span class="cp-badge cp-badge--neutral" style="font-size: 0.65rem; padding: 4px 12px; background: var(--blue-50); color: var(--blue-700); letter-spacing: 0.05em;">Usage Record - ${refNum}</span>
            <span style="font-size: 0.75rem; color: var(--g400); font-weight: 700;">Case #${log.case_number}</span>
        </div>

        <div style="margin-bottom: 2rem;">
            <div style="display: flex; align-items: center; gap: 8px; font-weight: 800; color: var(--g400); font-size: 0.7rem; text-transform: uppercase; margin-bottom: 1rem; letter-spacing: 0.05em;">
                <i class="fas fa-table-list"></i> Usage Information
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div>
                    <label style="display: block; font-size: 0.6rem; font-weight: 800; color: var(--g400); text-transform: uppercase; margin-bottom: 2px;">Date</label>
                    <div style="font-weight: 800; color: var(--blue-900); font-size: 1rem;">${formattedDate}</div>
                </div>
                <div>
                    <label style="display: block; font-size: 0.6rem; font-weight: 800; color: var(--g400); text-transform: uppercase; margin-bottom: 2px;">Department</label>
                    <div style="font-weight: 800; color: var(--blue-900); font-size: 1rem;">${log.usage_department}</div>
                </div>
                <div>
                    <label style="display: block; font-size: 0.6rem; font-weight: 800; color: var(--g400); text-transform: uppercase; margin-bottom: 2px;">Subject / Area</label>
                    <div style="font-weight: 800; color: var(--blue-900); font-size: 1rem;">${log.subject_area}</div>
                </div>
                <div>
                    <label style="display: block; font-size: 0.6rem; font-weight: 800; color: var(--g400); text-transform: uppercase; margin-bottom: 2px;">Purpose</label>
                    <div style="font-weight: 800; color: var(--blue-900); font-size: 1rem;">${log.usage_type}</div>
                </div>
            </div>
        </div>

        <div style="margin-bottom: 2rem;">
            <div style="display: flex; align-items: center; gap: 8px; font-weight: 800; color: var(--g400); font-size: 0.7rem; text-transform: uppercase; margin-bottom: 0.75rem; letter-spacing: 0.05em;">
                <i class="fas fa-align-left"></i> Description
            </div>
            <div style="background: var(--blue-50); padding: 1.25rem; border-radius: 12px; line-height: 1.6; color: var(--blue-900); font-weight: 600; font-size: 0.9rem;">
                ${log.description}
            </div>
        </div>

        <div style="margin-bottom: 2.5rem;">
            <div style="display: flex; align-items: center; gap: 8px; font-weight: 800; color: var(--g400); font-size: 0.7rem; text-transform: uppercase; margin-bottom: 1rem; letter-spacing: 0.05em;">
                <i class="fas fa-user-doctor"></i> Handling
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <label style="display: block; font-size: 0.6rem; font-weight: 800; color: var(--g400); text-transform: uppercase; margin-bottom: 2px;">Handled By</label>
                    <div style="font-weight: 800; color: var(--blue-900); font-size: 0.95rem;">${log.handled_by}</div>
                </div>
                <div>
                    <label style="display: block; font-size: 0.6rem; font-weight: 800; color: var(--g400); text-transform: uppercase; margin-bottom: 2px;">Duration</label>
                    <div style="font-weight: 800; color: var(--blue-900); font-size: 0.95rem;">${log.duration || 'N/A'}</div>
                </div>
            </div>
        </div>

        <div style="padding: 1.25rem; border-radius: 12px; border: 1px solid ${log.letter_id ? '#10b981' : '#e2e8f0'}; background: ${log.letter_id ? '#ecfdf5' : '#fff'}; display: flex; align-items: center; justify-content: space-between; gap: 12px;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <i class="fas ${log.letter_id ? 'fa-check-circle' : 'fa-clock'}" style="color: ${log.letter_id ? '#059669' : '#94a3b8'}; font-size: 1.25rem;"></i>
                <div>
                    <div style="font-weight: 800; color: ${log.letter_id ? '#064e3b' : '#475569'}; font-size: 0.9rem;">${log.letter_id ? 'Appreciation Letter Sent' : 'Appreciation Letter Pending'}</div>
                    <div style="font-size: 0.75rem; color: ${log.letter_id ? '#059669' : '#94a3b8'}; font-weight: 700;">
                        ${log.letter_id ? 'Sent On: '+ new Date(log.letter_issued_at).toLocaleString() : 'Formal letter awaiting institutional record.'}
                    </div>
                </div>
            </div>
            ${log.letter_id ? `
                <a href="<?= ROOT ?>/medical-school/appreciation/view?id=${log.letter_id}" class="ms-btn-details" style="background: var(--white); border-color: #10b981; color: #059669;">
                    <i class="fas fa-file-invoice"></i> View
                </a>
            ` : ''}
        </div>
    `;

    overlay.style.display = 'flex';
    setTimeout(() => drawer.style.transform = 'translateX(0)', 10);
}

function closeUsageDetail() {
    const overlay = document.getElementById('usageDetailDrawerOverlay');
    const drawer = document.getElementById('usageDetailDrawer');
    drawer.style.transform = 'translateX(100%)';
    setTimeout(() => overlay.style.display = 'none', 300);
}

function handleDrawerAction() {
    if (!currentLog) return;
    
    if (currentLog.letter_id) {
        // Redir to view letter
        window.location.href = '<?= ROOT ?>/medical-school/appreciation/view?id=' + currentLog.letter_id;
    } else {
        // Submit hidden form to send letter
        document.getElementById('formUsageId').value = currentLog.id;
        document.getElementById('issueLetterForm').submit();
    }
}

function openUsageModal() {
    document.getElementById('usageModalOverlay').classList.add('active');
}
function closeUsageModal() {
    document.getElementById('usageModalOverlay').classList.remove('active');
}
function toggleOtherFields() {
    const deptSelect = document.getElementById('usage_dept_select');
    const otherDept = document.getElementById('other_dept_container');
    otherDept.style.display = deptSelect.value === 'Other' ? 'block' : 'none';

    const subjectSelect = document.getElementById('subject_area_select');
    const otherSubject = document.getElementById('other_subject_container');
    otherSubject.style.display = subjectSelect.value === 'Other' ? 'block' : 'none';
}

window.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        closeUsageModal();
        closeUsageDetail();
    }
});
</script>

<style>
.tab-content { opacity: 0; transform: translateY(10px); }
.tab-content.active { opacity: 1; transform: translateY(0); }
.transition-fade { transition: all 0.3s ease-out; }

.usage-row:hover { background: #f8fafc !important; }

.drawer-overlay {
    position: fixed; inset: 0; background: rgba(15, 23, 42, 0.4); z-index: 1500;
    display: none; justify-content: flex-end; backdrop-filter: blur(4px);
}
.drawer-content {
    background: #fff; width: 480px; height: 100%; box-shadow: -10px 0 30px rgba(0,0,0,0.1);
    display: flex; flex-direction: column; transform: translateX(100%); transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
</style>

<?php
$page_content = ob_get_clean();
require dirname(__DIR__) . '/layouts/medical_schools.layout.php';
?>
>
