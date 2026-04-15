<?php
/**
 * Medical School Portal — Inventory Detail Drawer
 */
?>
<div class="dr-drawer-header">
    <div>
        <h3>Inventory Record</h3>
        <p>#<?= htmlspecialchars($case->case_number ?? 'N/A') ?> — Accepted & Pristine</p>
    </div>
    <button class="dr-close-btn" onclick="closeGenericDrawer()">
        <i class="fas fa-times"></i>
    </button>
</div>

<div class="dr-drawer-body">
    
    <!-- Status Overview -->
    <div class="dr-card dr-card--blue shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <span class="dr-label dr-label--xs">Current Allocation</span>
            <span class="dr-badge dr-badge--success">GOOD / Pristine</span>
        </div>
        <div class="dr-value text-2xl mb-1">Unused (Pristine)</div>
        <div class="text-gray-500 font-semibold mb-4 text-sm">Usage yet to assign for academic purposes.</div>
        
        <div class="dr-divider pt-4 flex items-center justify-between">
            <div class="dr-label dr-label--xs">Physical Exam Status</div>
            <div class="text-emerald-600 font-bold text-sm flex items-center gap-2">
                <i class="fas fa-check-circle"></i> Passed (GOOD)
            </div>
        </div>
    </div>

    <!-- Donor Information Section -->
    <div class="dr-section">
        <div class="dr-section-title">
            <div class="flex items-center gap-2">
                <i class="fas fa-id-card"></i>
                <span>Donor Identification</span>
            </div>
        </div>
        <div class="dr-grid dr-grid--2">
            <div class="dr-label-group">
                <label class="dr-label">Full Name</label>
                <div class="dr-value"><?= htmlspecialchars($case->first_name . ' ' . $case->last_name) ?></div>
            </div>
            <div class="dr-label-group">
                <label class="dr-label">Age / Gender</label>
                <div class="dr-value"><?= $case->age ?? 'N/A' ?> Years / <?= htmlspecialchars($case->gender ?? 'N/A') ?></div>
            </div>
            <div class="dr-label-group">
                <label class="dr-label">Identification</label>
                <div class="dr-value"><?= htmlspecialchars($case->nic_passport ?? 'N/A') ?></div>
            </div>
            <div class="dr-label-group">
                <label class="dr-label">Case Number</label>
                <div class="dr-value text-blue-600 font-extrabold">#<?= htmlspecialchars($case->case_number) ?></div>
            </div>
        </div>
    </div>

    <!-- Institutional Record Section -->
    <div class="dr-section">
        <div class="dr-section-title">
            <div class="flex items-center gap-2">
                <i class="fas fa-university"></i>
                <span>Institutional Record</span>
            </div>
        </div>
        <div class="dr-card bg-white border-dashed">
            <div class="dr-grid dr-grid--2 mb-4">
                <div class="dr-label-group">
                    <label class="dr-label">Accepted On</label>
                    <div class="dr-value"><?= date('d M Y', strtotime($case->institution_accepted_at ?? $case->final_exam_at)) ?></div>
                </div>
                <div class="dr-label-group">
                    <label class="dr-label">Assigned Department</label>
                    <div class="dr-value"><?= htmlspecialchars($case->assigned_department ?? 'Anatomy Department') ?></div>
                </div>
            </div>
            <div class="dr-label-group">
                <label class="dr-label">Final Assessment Notes</label>
                <div class="italic text-gray-500 text-sm font-medium">
                    <?= !empty($case->final_exam_notes) ? htmlspecialchars($case->final_exam_notes) : 'Confirmed physically suitable for anatomical teaching and surgical research.' ?>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="dr-drawer-footer">
    <button class="cp-btn cp-btn--secondary dr-btn-elevated bg-gray-100" onclick="closeGenericDrawer()">Dismiss</button>
    <a href="<?= ROOT ?>/medical-school/usage-logs?cis_id=<?= $case->id ?>" class="cp-btn cp-btn--primary dr-btn-elevated flex items-center">
        <i class="fas fa-plus mr-2"></i> Record Usage Activity
    </a>
</div>
