<?php
/**
 * Medical School Portal — Inventory Detail Drawer
 */
?>
<div class="drawer-header" style="padding: 1.5rem; border-bottom: 1px solid var(--g100); background: #fff; display: flex; justify-content: space-between; align-items: center;">
    <div>
        <h3 style="margin: 0; font-size: 1.15rem; font-weight: 800; color: var(--blue-900);">Inventory Record</h3>
        <p style="margin: 4px 0 0; font-size: 0.75rem; color: var(--g400); font-weight: 600;">#<?= htmlspecialchars($case->case_number ?? 'N/A') ?> — Accepted & Pristine</p>
    </div>
    <button class="cp-drawer-close" onclick="closeGenericDrawer()" style="border: none; background: var(--g50); width: 32px; height: 32px; border-radius: 8px; cursor: pointer; color: var(--g400);">
        <i class="fas fa-times"></i>
    </button>
</div>

<div class="drawer-body" style="padding: 2rem; overflow-y: auto; flex: 1; background: #fafbfc;">
    
    <!-- Status Overview -->
    <div style="background: #fff; border: 1px solid #eef2f6; padding: 1.5rem; border-radius: 16px; margin-bottom: 1.5rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
            <span style="font-size: 0.65rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em;">Current Allocation</span>
            <span class="cp-badge cp-badge--active" style="background: #ecfdf4; color: #059669; border: 1px solid #dcfce7; font-size: 0.65rem;">GOOD / Pristine</span>
        </div>
        <div style="font-size: 1.5rem; font-weight: 900; color: var(--blue-900); margin-bottom: 4px;">Unused (Pristine)</div>
        <div style="font-size: 0.85rem; color: #64748b; font-weight: 600; margin-bottom: 1rem;">Usage yet to assign for academic purposes.</div>
        
        <div style="border-top: 1px solid #f1f5f9; pt-3; display: flex; align-items: center; justify-content: space-between; padding-top: 1rem;">
            <div style="font-size: 0.7rem; font-weight: 800; color: var(--g400); text-transform: uppercase;">Physical Exam Status</div>
            <div style="font-weight: 700; color: #059669; font-size: 0.85rem; display: flex; align-items: center; gap: 6px;">
                <i class="fas fa-check-circle"></i> Passed (GOOD)
            </div>
        </div>
    </div>

    <!-- Donor Information Section -->
    <div style="margin-bottom: 2rem;">
        <div style="display: flex; align-items: center; gap: 8px; font-weight: 800; color: var(--g400); font-size: 0.7rem; text-transform: uppercase; margin-bottom: 1rem; letter-spacing: 0.05em;">
            <i class="fas fa-id-card"></i> Donor Identification
        </div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
            <div>
                <label style="display: block; font-size: 0.6rem; font-weight: 800; color: var(--g400); text-transform: uppercase; margin-bottom: 2px;">Full Name</label>
                <div style="font-weight: 700; color: var(--blue-900);"><?= htmlspecialchars($case->first_name . ' ' . $case->last_name) ?></div>
            </div>
            <div>
                <label style="display: block; font-size: 0.6rem; font-weight: 800; color: var(--g400); text-transform: uppercase; margin-bottom: 2px;">Age / Gender</label>
                <div style="font-weight: 700; color: var(--blue-900);"><?= $case->age ?? 'N/A' ?> Years / <?= htmlspecialchars($case->gender ?? 'N/A') ?></div>
            </div>
            <div>
                <label style="display: block; font-size: 0.6rem; font-weight: 800; color: var(--g400); text-transform: uppercase; margin-bottom: 2px;">Identification</label>
                <div style="font-weight: 700; color: var(--blue-900);"><?= htmlspecialchars($case->nic_passport ?? 'N/A') ?></div>
            </div>
            <div>
                <label style="display: block; font-size: 0.6rem; font-weight: 800; color: var(--g400); text-transform: uppercase; margin-bottom: 2px;">Case Number</label>
                <div style="font-weight: 800; color: var(--blue-600);">#<?= htmlspecialchars($case->case_number) ?></div>
            </div>
        </div>
    </div>

    <!-- Institutional Record Section -->
    <div style="margin-bottom: 2rem;">
        <div style="display: flex; align-items: center; gap: 8px; font-weight: 800; color: var(--g400); font-size: 0.7rem; text-transform: uppercase; margin-bottom: 1rem; letter-spacing: 0.05em;">
            <i class="fas fa-university"></i> Institutional Record
        </div>
        <div class="cp-card" style="padding: 1.25rem; border: 1px dashed var(--g200); background: #fff;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
                <div>
                    <label style="display: block; font-size: 0.6rem; font-weight: 800; color: var(--g400); text-transform: uppercase; margin-bottom: 2px;">Accepted On</label>
                    <div style="font-weight: 700; color: var(--blue-900);"><?= date('d M Y', strtotime($case->institution_accepted_at ?? $case->final_exam_at)) ?></div>
                </div>
                <div>
                    <label style="display: block; font-size: 0.6rem; font-weight: 800; color: var(--g400); text-transform: uppercase; margin-bottom: 2px;">Assigned Department</label>
                    <div style="font-weight: 700; color: var(--blue-900);"><?= htmlspecialchars($case->assigned_department ?? 'Anatomy Department') ?></div>
                </div>
            </div>
            <div>
                <label style="display: block; font-size: 0.6rem; font-weight: 800; color: var(--g400); text-transform: uppercase; margin-bottom: 2px;">Final Assessment Notes</label>
                <div style="font-style: italic; color: var(--g600); font-size: 0.85rem; font-weight: 500;">
                    <?= !empty($case->final_exam_notes) ? htmlspecialchars($case->final_exam_notes) : 'Confirmed physically suitable for anatomical teaching and surgical research.' ?>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="drawer-footer" style="padding: 1.5rem; border-top: 1px solid var(--g100); background: #fff; display: flex; gap: 12px; justify-content: flex-end;">
    <button class="cp-btn cp-btn--secondary" style="height: 44px; border-radius: 10px; padding: 0 20px;" onclick="closeGenericDrawer()">Dismiss</button>
    <a href="<?= ROOT ?>/medical-school/usage-logs?cis_id=<?= $case->id ?>" class="cp-btn cp-btn--primary" style="height: 44px; border-radius: 10px; padding: 0 20px; text-decoration: none; display: flex; align-items: center;">
        <i class="fas fa-plus mr-2"></i> Record Usage Activity
    </a>
</div>
