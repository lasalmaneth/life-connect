<?php
/**
 * Medical School Portal — Enhanced Anatomical Usage & Inventory
 */

$page_title = 'Body Usage';
$active_page = 'usage-logs';

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
        <div class="cp-card"
            style="padding: 1.25rem; display: flex; align-items: center; gap: 1rem; border-bottom: 3px solid var(--blue-500);">
            <div
                style="width: 44px; height: 44px; border-radius: 10px; background: var(--blue-50); color: var(--blue-600); display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                <i class="fas fa-layer-group"></i>
            </div>
            <div>
                <div style="font-size: 0.7rem; font-weight: 800; color: var(--g400); text-transform: uppercase;">Total
                    Inventory</div>
                <div style="font-size: 1.25rem; font-weight: 900; color: var(--blue-900);">
                    <?= $inventoryStats['total'] ?? 0 ?></div>
            </div>
        </div>
        <div class="cp-card"
            style="padding: 1.25rem; display: flex; align-items: center; gap: 1rem; border-bottom: 3px solid #10b981;">
            <div
                style="width: 44px; height: 44px; border-radius: 10px; background: #f0fdf4; color: #15803d; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                <i class="fas fa-check-double"></i>
            </div>
            <div>
                <div style="font-size: 0.7rem; font-weight: 800; color: var(--g400); text-transform: uppercase;">
                    Pristine (Unused)</div>
                <div style="font-size: 1.25rem; font-weight: 900; color: #15803d;">
                    <?= $inventoryStats['pristine'] ?? 0 ?></div>
            </div>
        </div>
        <div class="cp-card"
            style="padding: 1.25rem; display: flex; align-items: center; gap: 1rem; border-bottom: 3px solid #f59e0b;">
            <div
                style="width: 44px; height: 44px; border-radius: 10px; background: #fffbeb; color: #b45309; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                <i class="fas fa-microscope"></i>
            </div>
            <div>
                <div style="font-size: 0.7rem; font-weight: 800; color: var(--g400); text-transform: uppercase;">In-Use
                    Records</div>
                <div style="font-size: 1.25rem; font-weight: 900; color: #b45309;">
                    <?= $inventoryStats['utilized'] ?? 0 ?></div>
            </div>
        </div>
        <div class="cp-card"
            style="padding: 1.25rem; display: flex; align-items: center; gap: 1rem; border-bottom: 3px solid #8b5cf6;">
            <div
                style="width: 44px; height: 44px; border-radius: 10px; background: #f5f3ff; color: #6d28d9; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                <i class="fas fa-paper-plane"></i>
            </div>
            <div>
                <div style="font-size: 0.7rem; font-weight: 800; color: var(--g400); text-transform: uppercase;">Letters
                    Issued</div>
                <div style="font-size: 1.25rem; font-weight: 900; color: #6d28d9;">
                    <?= count(array_filter($logs, fn($l) => !empty($l->letter_id))) ?></div>
            </div>
        </div>
    </div>

    <!-- Compact Active Body Summary (Minimimized to a small line) -->
    <?php if ($caseInfo): ?>
        <div
            style="background: var(--blue-50); border: 1px solid var(--blue-100); padding: 0.75rem 1.25rem; border-radius: 12px; margin-bottom: 2rem; display: flex; align-items: center; justify-content: space-between;">
            <div style="display: flex; align-items: center; gap: 1.5rem;">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <span
                        style="font-size: 0.65rem; font-weight: 800; color: var(--blue-400); text-transform: uppercase;">Case:</span>
                    <span
                        style="font-weight: 800; color: var(--blue-900); font-size: 0.85rem;">#<?= $caseInfo->case_number ?></span>
                </div>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <span
                        style="font-size: 0.65rem; font-weight: 800; color: var(--blue-400); text-transform: uppercase;">Donor:</span>
                    <span
                        style="font-weight: 700; color: var(--blue-800); font-size: 0.85rem;"><?= htmlspecialchars($caseInfo->first_name . ' ' . $caseInfo->last_name) ?></span>
                </div>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <span
                        style="font-size: 0.65rem; font-weight: 800; color: var(--blue-400); text-transform: uppercase;">Medical:</span>
                    <span class="cp-badge cp-badge--info"
                        style="font-size: 0.6rem; background: #fff; padding: 2px 8px;"><?= htmlspecialchars($caseInfo->current_condition ?? 'Accepted') ?></span>
                </div>
            </div>
            <a href="<?= ROOT ?>/medical-school/usage-logs"
                style="font-size: 0.75rem; color: var(--blue-600); font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 4px;">
                <i class="fas fa-times-circle"></i> Clear Selection
            </a>
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
                                    <div class="cp-empty-state__sub">Records of used anatomical components will appear here.
                                    </div>
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
                                            <div class="cp-table__filename">
                                                <?= htmlspecialchars($log->first_name . ' ' . $log->last_name) ?></div>
                                            <div class="cp-table__subtext">#<?= $log->case_number ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="cp-table__filename"><?= date('d M Y', strtotime($log->usage_date)) ?></div>
                                    <div class="cp-table__subtext"><?= htmlspecialchars($log->duration ?? 'Unspecified') ?>
                                    </div>
                                </td>
                                <td style="font-weight: 700; color: var(--blue-900);">
                                    <?= htmlspecialchars($log->usage_department) ?></td>
                                <td>
                                    <div class="cp-table__filename"><?= htmlspecialchars($log->subject_area) ?></div>
                                    <div class="cp-table__subtext"><?= htmlspecialchars($log->usage_type) ?></div>
                                </td>
                                <td style="font-weight: 600; color: var(--g700);"><?= htmlspecialchars($log->handled_by) ?></td>
                                <td style="text-align: right;">
                                    <div class="cp-table__actions">
                                        <button class="cp-btn cp-btn--secondary cp-btn--sm"
                                            onclick='openUsageDetail(<?= json_encode($log) ?>)'>
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
                                    <div class="cp-empty-state__sub">All accepted inventory has been initiated for usage.
                                    </div>
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
                                            <div class="cp-table__subtext">
                                                <?= htmlspecialchars($item->first_name . ' ' . $item->last_name) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="cp-badge cp-badge--active"
                                        style="background: #ecfdf5; color: #059669; border: 1px solid #d1fae5;">
                                        GOOD
                                    </span>
                                </td>
                                <td style="font-weight: 700; color: var(--blue-700);">
                                    <?= htmlspecialchars($item->assigned_department ?? 'Anatomy Dept') ?></td>
                                <td style="text-align: center;">
                                    <div class="cp-table__filename"><?= $item->usage_count ?> Times</div>
                                    <div class="cp-table__subtext">Usage yet to assign</div>
                                </td>
                                <td style="text-align: right;">
                                    <div class="cp-table__actions">
                                        <button type="button" onclick="openInventoryDrawer(<?= $item->cis_id ?>)" class="cp-btn"
                                            style="height: 32px; padding: 0 16px; border-radius: 8px; background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; font-weight: 600; font-size: 0.75rem; display: inline-flex; align-items: center; gap: 6px;">
                                            <i class="fas fa-info-circle" style="font-size: 0.85rem;"></i> Details
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

</div>

<!-- Standard Side Drawer -->
<div class="drawer-overlay" id="usageDetailDrawerOverlay" onclick="closeUsageDetail()">
    <div class="drawer-content" id="usageDetailDrawer" style="width: 500px; height: 100vh; position: fixed; right: 0; top: 0; display: flex; flex-direction: column; background: #fff; padding: 0; overflow: hidden; border-radius: 0;" onclick="event.stopPropagation()">
        <div id="usageDetailBody" style="display:flex; flex-direction:column; height:100%; overflow: hidden;">
            <!-- Content filled by JS -->
        </div>
    </div>
</div>

<!-- Generic Info Side Drawer (Matches standardized pattern) -->
<div class="drawer-overlay" id="genericDrawerOverlay" onclick="closeGenericDrawer()" style="z-index: 1600;">
    <div class="drawer-content" id="genericDrawer" style="width: 500px;" onclick="event.stopPropagation()">
        <div id="genericDrawerBody" style="display:flex; flex-direction:column; height:100%;">
            <div style="padding: 3rem; text-align: center; color: var(--g400);">
                <i class="fas fa-circle-notch fa-spin fa-2x mb-3"></i>
                <p>Loading details...</p>
            </div>
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
                                <option value="<?= $caseInfo->donor_id ?>">
                                    <?= htmlspecialchars($caseInfo->first_name . ' ' . $caseInfo->last_name) ?>
                                    (#<?= $caseInfo->case_number ?>)</option>
                            <?php else: ?>
                                <option value="">-- Select Unused Body --</option>
                                <?php foreach ($inventory as $inv): ?>
                                    <?php if ($inv->usage_count == 0): ?>
                                        <option value="<?= $inv->donor_id ?>">
                                            <?= htmlspecialchars($inv->first_name . ' ' . $inv->last_name) ?>
                                            (#<?= $inv->case_number ?>)</option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <div class="cp-help-text">
                            <i class="fas fa-shield-alt"></i> Only physically accepted, unused cadavers are listed
                            above.
                        </div>
                    </div>

                    <div>
                        <label class="cp-label">Usage Date <span style="color: #ef4444;">*</span></label>
                        <input type="date" name="usage_date" value="<?= date('Y-m-d') ?>" class="cp-input" required>
                    </div>

                    <div>
                        <label class="cp-label">Department <span style="color: #ef4444;">*</span></label>
                        <select name="usage_department" id="usage_dept_select" class="cp-input"
                            onchange="toggleOtherFields()" required>
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
                                <select name="subject_area" id="subject_area_select" class="cp-input" style="flex: 2;"
                                    onchange="toggleOtherFields()" required>
                                    <option value="Gross Anatomy">Gross Anatomy</option>
                                    <option value="Neuroanatomy">Neuroanatomy</option>
                                    <option value="Surgical Skills">Surgical Skills</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div id="other_subject_container" style="display: none;">
                                <input type="text" name="other_subject" placeholder="Enter Subject Area"
                                    class="cp-input">
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
                        <textarea name="description" class="cp-input cp-textarea" rows="3"
                            placeholder="Provide a brief summary of the academic activities performed..."
                            required></textarea>
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

        body.innerHTML = `
            <!-- Header -->
            <div class="drawer-header" style="padding: 1.5rem; border-bottom: 1px solid var(--g100); background: #fff; display: flex; justify-content: space-between; align-items: center; border-radius: 20px 20px 0 0;">
                <div>
                    <h3 style="margin: 0; font-size: 1.15rem; font-weight: 800; color: var(--blue-900);">Usage Record</h3>
                    <p style="margin: 4px 0 0; font-size: 0.75rem; color: var(--g400); font-weight: 600;">#UR-${String(log.id).padStart(3, '0')} — Verified Logged Activity</p>
                </div>
                <button class="cp-drawer-close" onclick="closeUsageDetail()" style="border: none; background: var(--g50); width: 32px; height: 32px; border-radius: 8px; cursor: pointer; color: var(--g400);">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="drawer-body" style="padding: 2rem; overflow-y: auto; flex: 1; background: #fafbfc;">
                
                <!-- Status Overview (Matches other pages) -->
                <div style="background: #fff; border: 1px solid #eef2f6; padding: 1.5rem; border-radius: 16px; margin-bottom: 2rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                        <span style="font-size: 0.6rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em;">Usage Status</span>
                        <span class="cp-badge cp-badge--neutral" style="background: #eff6ff; color: #1e4ed8; border: 1px solid #dbeafe; font-size: 0.65rem;">Active Inventory Use</span>
                    </div>
                    <div style="font-size: 1.4rem; font-weight: 900; color: var(--blue-900); margin-bottom: 4px;">Verified Usage</div>
                    <div style="font-size: 0.8rem; color: #64748b; font-weight: 600; margin-bottom: 1rem;">Log #${log.id} — Academic Year 2026</div>
                    
                    <div style="border-top: 1px solid #f1f5f9; pt-3; display: flex; align-items: center; justify-content: space-between; padding-top: 1rem;">
                        <div style="font-size: 0.65rem; font-weight: 800; color: var(--g400); text-transform: uppercase;">Reporting Case</div>
                        <div style="font-weight: 800; color: var(--blue-600); font-size: 0.85rem;">
                            #${log.case_number}
                        </div>
                    </div>
                </div>

                <!-- Usage Details Section -->
                <div style="margin-bottom: 2.5rem;">
                    <div style="display: flex; align-items: center; gap: 8px; font-weight: 800; color: var(--g400); font-size: 0.7rem; text-transform: uppercase; margin-bottom: 1.25rem; letter-spacing: 0.05em;">
                        <i class="fas fa-table-list"></i> Usage Details
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                        <div>
                            <label style="display: block; font-size: 0.6rem; font-weight: 800; color: var(--g400); text-transform: uppercase; margin-bottom: 4px;">Date of Usage</label>
                            <div style="font-weight: 700; color: var(--blue-900);">${formattedDate}</div>
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.6rem; font-weight: 800; color: var(--g400); text-transform: uppercase; margin-bottom: 4px;">Department</label>
                            <div style="font-weight: 700; color: var(--blue-900);">${log.usage_department}</div>
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.6rem; font-weight: 800; color: var(--g400); text-transform: uppercase; margin-bottom: 4px;">Subject / Area</label>
                            <div style="font-weight: 700; color: var(--blue-900);">${log.subject_area}</div>
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.6rem; font-weight: 800; color: var(--g400); text-transform: uppercase; margin-bottom: 4px;">Usage Purpose</label>
                            <div style="font-weight: 700; color: var(--blue-900);">${log.usage_type}</div>
                        </div>
                    </div>
                </div>

                <!-- Handled Section -->
                <div style="margin-bottom: 2.5rem;">
                    <div style="display: flex; align-items: center; gap: 8px; font-weight: 800; color: var(--g400); font-size: 0.7rem; text-transform: uppercase; margin-bottom: 1.25rem; letter-spacing: 0.05em;">
                        <i class="fas fa-user-doctor"></i> Handling Record
                    </div>
                    <div style="background: #fff; padding: 1.25rem; border-radius: 16px; border: 1px dashed var(--g200);">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                            <div>
                                <label style="display: block; font-size: 0.6rem; font-weight: 800; color: var(--g400); text-transform: uppercase; margin-bottom: 4px;">Handled By</label>
                                <div style="font-weight: 700; color: var(--blue-900);">${log.handled_by}</div>
                            </div>
                            <div>
                                <label style="display: block; font-size: 0.6rem; font-weight: 800; color: var(--g400); text-transform: uppercase; margin-bottom: 4px;">Duration</label>
                                <div style="font-weight: 700; color: var(--blue-900);">${log.duration || 'N/A'}</div>
                            </div>
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.6rem; font-weight: 800; color: var(--g400); text-transform: uppercase; margin-bottom: 6px;">Activity Description</label>
                            <div style="font-style: italic; color: #475569; font-size: 0.85rem; font-weight: 500; line-height: 1.5;">
                                "${log.description || 'Verified institutional anatomical usage record.'}"
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Appreciation Badge -->
                <div style="padding: 1rem; border-radius: 12px; border: 1px solid ${log.letter_id ? '#dcfce7' : '#f1f5f9'}; background: ${log.letter_id ? '#f0fdf4' : '#fff'}; display: flex; align-items: center; justify-content: space-between; gap: 12px; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <i class="fas ${log.letter_id ? 'fa-check-circle' : 'fa-clock'}" style="color: ${log.letter_id ? '#059669' : '#94a3b8'}; font-size: 1rem;"></i>
                        <div>
                            <div style="font-weight: 800; color: ${log.letter_id ? '#064e3b' : '#475569'}; font-size: 0.8rem;">${log.letter_id ? 'Appreciation Letter Sent' : 'Appreciation Pending'}</div>
                            <div style="font-size: 0.65rem; color: ${log.letter_id ? '#059669' : '#94a3b8'}; font-weight: 600;">
                                ${log.letter_id ? 'Formally issued on ' + new Date(log.letter_issued_at).toLocaleDateString() : 'Awaiting institutional issuance.'}
                            </div>
                        </div>
                    </div>
                    ${log.letter_id ? `
                        <a href="<?= ROOT ?>/medical-school/appreciation/view?id=${log.letter_id}&from=usage" class="cp-btn" style="height: 32px; padding: 0 16px; font-size: 0.75rem; background: var(--white); border: 1px solid #34d399; color: #059669; border-radius: 8px; font-weight: 700; text-decoration: none; display: flex; align-items: center;">
                            <i class="fas fa-eye mr-2"></i> View Letter
                        </a>
                    ` : ''}
                </div>

            </div>

            <div class="drawer-footer" style="padding: 1.5rem; border-top: 1px solid var(--g100); background: #fff; display: flex; justify-content: flex-end; border-radius: 0 0 20px 20px;">
                <button class="cp-btn cp-btn--secondary" style="height: 44px; border-radius: 10px; padding: 0 24px; font-weight: 700;" onclick="closeUsageDetail()">Dismiss Record</button>
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
            window.location.href = '<?= ROOT ?>/medical-school/appreciation/view?id=' + currentLog.letter_id;
        } else {
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

    function openInventoryDrawer(id) {
        const overlay = document.getElementById('genericDrawerOverlay');
        const drawer = document.getElementById('genericDrawer');
        const body = document.getElementById('genericDrawerBody');

        overlay.style.display = 'flex';
        setTimeout(() => drawer.style.transform = 'translateX(0)', 10);

        fetch(`<?= ROOT ?>/medical-school/view-inventory-detail?id=${id}`)
            .then(res => res.text())
            .then(html => {
                body.innerHTML = html;
            });
    }

    function closeGenericDrawer() {
        const overlay = document.getElementById('genericDrawerOverlay');
        const drawer = document.getElementById('genericDrawer');
        drawer.style.transform = 'translateX(100%)';
        setTimeout(() => overlay.style.display = 'none', 300);
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
    .tab-content {
        opacity: 0;
        transform: translateY(10px);
    }
    .tab-content.active {
        opacity: 1;
        transform: translateY(0);
    }
    .transition-fade {
        transition: all 0.3s ease-out;
    }
    .usage-row:hover {
        background: #f8fafc !important;
    }
    .drawer-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.4);
        z-index: 1500;
        display: none;
        justify-content: flex-end;
        backdrop-filter: blur(4px);
    }
    .drawer-content {
        background: #fff;
        width: 500px;
        height: 100vh;
        box-shadow: -10px 0 30px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
        transform: translateX(100%);
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        padding: 0;
        border-radius: 0;
        overflow: hidden;
    }
</style>

<?php
$page_content = ob_get_clean();
require dirname(__DIR__) . '/layouts/medical_schools.layout.php';
?>
>