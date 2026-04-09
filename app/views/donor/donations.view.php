<?php
/**
 * Donor Portal — Donations Page (FINAL UI RESTORATION)
 * Dashboard and Modal Data Steps (1-4/3) are in the ORIGINAL PROJECT STYLE.
 * ONLY the Final Review Step has the upgraded 3-button UI/Signatures as requested.
 */
include __DIR__ . '/inc/header.view.php';
include __DIR__ . '/inc/sidebar.view.php';
$hospitalsByOrganJson = json_encode($hospitals_by_organ ?? []);
?>
<style>
:root { --accent: #10b981; --accent-hover: #059669; }

/* Premium Document Modal Styles */
.d-modal__header { border-bottom: 2px solid var(--g200); padding: 1.5rem 2rem; display: flex; justify-content: space-between; align-items: center; background: #fafafa; border-radius: 12px 12px 0 0; }
.d-modal__title-group h3 { font-size: 1.3rem; font-weight: 800; color: var(--slate); margin: 0; display: flex; align-items: center; gap: 12px; }
.d-modal__subtitle { font-size: 0.85rem; color: var(--g500); margin-top: 0.2rem; }
.d-modal__close { background: #fee2e2; border: none; width: 32px; height: 32px; border-radius: 50%; color: #ef4444; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; transition: all 0.2s; }
.d-modal__close:hover { background: #fecaca; transform: rotate(90deg); }

/* Unified Input Styling */
.d-input-group { margin-bottom: 1.5rem; }
.d-input-group label { display: block; font-size: 0.85rem; font-weight: 700; color: var(--slate); margin-bottom: 0.6rem; }
.d-input { width: 100%; padding: 0.85rem 1rem; border: 1.5px solid var(--g200); border-radius: 10px; font-size: 0.95rem; color: var(--slate); transition: all 0.2s; background: #fff; }
.d-input:focus { border-color: var(--blue-500); box-shadow: 0 0 0 4px var(--blue-50); outline: none; }

/* Modal Content & Steps */
.d-modal__step { display: none; padding: 2rem; background: #fff; }
.d-modal__step.active { display: block; animation: fadeIn 0.3s ease; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

/* Formal Document Sectioning */
.d-section-header { font-size: 0.85rem; font-weight: 800; color: var(--blue-600); text-transform: uppercase; letter-spacing: 0.1em; border-bottom: 2px solid var(--blue-50); padding-bottom: 0.75rem; margin: 1.5rem 0 1.5rem; display: flex; align-items: center; gap: 10px; }

/* Instructional & Warning Boxes */
.d-instruction-box { background: var(--blue-50); border-left: 4px solid var(--blue-600); padding: 1.75rem; border-radius: 12px; margin-bottom: 2rem; border-top: 1px solid var(--blue-100); border-right: 1px solid var(--blue-100); border-bottom: 1px solid var(--blue-100); }
.d-instruction-box h4 { color: var(--blue-900); font-weight: 800; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 10px; }
.d-instruction-box p { font-size: 0.95rem; color: var(--blue-800); line-height: 1.6; }
.d-instruction-box ul { margin-top: 1rem; padding-left: 1.5rem; }
.d-instruction-box li { margin-bottom: 0.5rem; color: var(--blue-900); font-weight: 500; font-size: 0.9rem; }

.d-warning-box { background: #fffbeb; border-left: 4px solid #f59e0b; padding: 1.25rem; border-radius: 10px; margin: 2rem 0; font-size: 0.9rem; color: #92400e; display: flex; align-items: center; gap: 12px; line-height: 1.5; border: 1px solid #fef3c7; }

/* Review Page (Formal Document) */
.d-review-page { padding: 3rem; background: #fff; border: 2px solid var(--g100); border-radius: 8px; position: relative; margin-bottom: 1rem; }
.d-review-header { text-align: center; border-bottom: 3px double var(--g200); padding-bottom: 2rem; margin-bottom: 2.5rem; }
.d-review-header h2 { font-size: 1.4rem; font-weight: 900; letter-spacing: 0.15em; color: var(--slate); text-transform: uppercase; margin: 0; }

.d-info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2.5rem; }
.d-info-item label { display: block; font-size: 0.75rem; font-weight: 800; color: var(--g500); text-transform: uppercase; margin-bottom: 0.5rem; letter-spacing: 0.05em; }
.d-info-item span { font-size: 1.1rem; font-weight: 700; color: var(--slate); border-bottom: 1px solid var(--g100); display: block; padding-bottom: 5px; }
.no-print { display: block; }
@media print { .no-print { display: none !important; } }
.d-status--pending { background: #fefce8 !important; color: #854d0e !important; border: 1px solid #fef08a !important; }
.d-stat--pending { background: #fffbeb !important; border: 1.5px solid #facc15 !important; }

.signature-block { display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; margin-top: 4rem; }
.sig-line { border-top: 2.5px solid var(--slate); padding-top: 0.75rem; font-size: 0.75rem; font-weight: 900; color: var(--slate); text-transform: uppercase; letter-spacing: 0.1em; text-align: center; }

@media print {
    .d-btn, .fas, .d-modal__close, .d-modal__header, [class*="--interactive"] { display: none !important; }
    .d-modal__body, .d-modal__content { width: 100% !important; max-width: none !important; padding: 0 !important; margin: 0 !important; position: static !important; box-shadow: none !important; border:none !important; }
    .d-review-page { border: none !important; padding: 0 !important; box-shadow: none !important; }
    body { background: white !important; padding: 0 !important; margin: 0 !important; }
}
</style>

<main class="d-content">
    <div class="d-content__header">
        <h2><i class="fas fa-heart text-accent"></i> Organ & Tissue Donation Pledge</h2>
        <p>Your selfless pledge can help save multiple lives and heal many more.</p>
    </div>
    <div class="d-content__body">
        <?php if(isset($_SESSION['success_message'])): ?>
            <div class="d-instruction-box" style="background:#f0fdf4; border-color:var(--accent); color:#166534; margin-bottom:1.5rem;">
                <i class="fas fa-check-circle"></i> <?= $_SESSION['success_message'] ?>
                <?php unset($_SESSION['success_message']); ?>
            </div>
        <?php endif; ?>

        <?php if(isset($_SESSION['error_message'])): ?>
            <div class="d-instruction-box" style="background:#fff5f5; border-color:#feb2b2; color:#742a2a; margin-bottom:1.5rem;">
                <i class="fas fa-exclamation-circle"></i> <?= $_SESSION['error_message'] ?>
                <?php unset($_SESSION['error_message']); ?>
            </div>
        <?php endif; ?>

        <div style="display: grid; gap: 2rem;">
            
            <!-- Section: Your Pledged Donations (ORIGINAL UNITARY GRID) -->
            <div class="d-widget shadow-sm">
                <div class="d-widget__header">
                    <div class="d-widget__title"><i class="fas fa-check-circle text-accent"></i> Your Pledged Donations</div>
                </div>
                <div class="d-widget__body">
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 1.5rem;">
                        <?php if(!empty($selected_living) || !empty($selected_after_death) || !empty($selected_full_body)): ?>
                            <?php foreach($selected_living as $o): 
                                $isPending = ($o['status'] === 'PENDING' && empty($o['signed_form_path']));
                                $boxStyle = $isPending ? 'border: 1.5px solid #facc15; background: #fffbeb;' : 'border: 1.5px solid var(--accent); background: #f0fdf4;';
                                $statusClass = $isPending ? 'd-status--pending' : 'd-status--success';
                                $statusText = $isPending ? 'Pending Upload' : 'Active';
                                $clickHandler = $isPending ? "openPledgeActionModal(" . (int)$o['organ_id'] . ", '" . addslashes($o['organ_name']) . "')" : "openUnselectWarning(" . (int)$o['organ_id'] . ", '" . addslashes($o['organ_name']) . "')";
                            ?>
                                <div class="d-stat" style="<?= $boxStyle ?> cursor: pointer; text-align:center;" onclick="<?= $clickHandler ?>">
                                    <div style="color: <?= $isPending ? '#d97706' : 'var(--accent)' ?>; font-size: 1.5rem; margin-bottom: 0.5rem;"><?= $o['organ_icon'] ?></div>
                                    <div style="font-weight: 700; font-size: 0.9rem; color: <?= $isPending ? '#92400e' : '#166534' ?>;"><?= htmlspecialchars($o['organ_name']) ?></div>
                                    <span class="d-status <?= $statusClass ?>" style="font-size: 0.6rem; margin-top: 5px;"><?= $statusText ?></span>
                                </div>
                            <?php endforeach; ?>
                            <?php foreach($selected_after_death as $o): 
                                $isPending = ($o['status'] === 'PENDING' && empty($o['signed_form_path']));
                                $boxStyle = $isPending ? 'border: 1.5px solid #facc15; background: #fffbeb;' : 'border: 1.5px solid var(--blue-500); background: var(--blue-50);';
                                $statusClass = $isPending ? 'd-status--pending' : 'd-status--info';
                                $statusText = $isPending ? 'Pending Upload' : 'Pledged';
                                $clickHandler = $isPending ? "openPledgeActionModal(" . (int)$o['organ_id'] . ", '" . addslashes($o['organ_name']) . "')" : "openUnselectWarning(" . (int)$o['organ_id'] . ", '" . addslashes($o['organ_name']) . "')";
                            ?>
                                <div class="d-stat" style="<?= $boxStyle ?> cursor: pointer; text-align:center;" onclick="<?= $clickHandler ?>">
                                    <div style="color: <?= $isPending ? '#d97706' : 'var(--blue-600)' ?>; font-size: 1.5rem; margin-bottom: 0.5rem;"><?= $o['organ_icon'] ?></div>
                                    <div style="font-weight: 700; font-size: 0.9rem; color: <?= $isPending ? '#92400e' : 'var(--blue-800)' ?>;"><?= htmlspecialchars($o['organ_name']) ?></div>
                                    <span class="d-status <?= $statusClass ?>" style="font-size: 0.6rem; margin-top: 5px;"><?= $statusText ?></span>
                                </div>
                            <?php endforeach; ?>
                            <?php if(!empty($selected_full_body)): 
                                $o = $selected_full_body[0];
                                $isPending = ($o['status'] === 'PENDING' && empty($o['signed_form_path']));
                                $boxStyle = $isPending ? 'border: 1.5px solid #facc15; background: #fffbeb;' : 'border: 1.5px solid #8b5cf6; background: #f5f3ff;';
                                $statusClass = $isPending ? 'd-status--pending' : '';
                                $statusStyle = $isPending ? '' : 'background:#8b5cf6; color:white;';
                                $statusText = $isPending ? 'Pending Upload' : 'Pledged';
                                $clickHandler = $isPending ? "openPledgeActionModal(9, 'Full Body')" : "openUnselectWarning(9, 'Full Body')";
                            ?>
                                <div class="d-stat" style="<?= $boxStyle ?> cursor: pointer; text-align:center;" onclick="<?= $clickHandler ?>">
                                    <div style="color: <?= $isPending ? '#d97706' : '#8b5cf6' ?>; font-size: 1.5rem; margin-bottom: 0.5rem;"><i class="fas fa-university"></i></div>
                                    <div style="font-weight: 700; font-size: 0.9rem; color: <?= $isPending ? '#92400e' : '#5b21b6' ?>;">Full Body</div>
                                    <span class="d-status <?= $statusClass ?>" style="font-size: 0.6rem; margin-top: 5px; <?= $statusStyle ?>"><?= $statusText ?></span>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div style="grid-column: 1 / -1; padding: 2rem; text-align: center; border: 1px dashed var(--g300); border-radius: var(--r); background: var(--g50);">
                                <p style="color: var(--g500);">You haven't made any organ pledges yet.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Section: New Opportunities (ORIGINAL SECTIONAL LAYOUT) -->
            <div class="d-widget shadow-sm">
                <div class="d-widget__header">
                    <div class="d-widget__title"><i class="fas fa-plus-circle text-accent"></i> New Donation Opportunities</div>
                </div>
                <div class="d-widget__body">
                    <h3 style="font-size:0.9rem; color:var(--g500); text-transform:uppercase; margin-bottom:1rem; display:flex; align-items:center; gap:8px;">Donate While Living</h3>
                    <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(160px,1fr)); gap:1.25rem; margin-bottom:2.5rem;">
                        <?php if(!empty($available_living)): foreach($available_living as $o): ?>
                            <div class="d-stat d-stat--interactive" style="padding:1.25rem; border: 1px solid var(--g200); text-align:center;" onclick="openLivingModal(<?= $o['organ_id'] ?>, '<?= addslashes($o['organ_name']) ?>')">
                                <div style="color:var(--accent); font-size:1.5rem; margin-bottom:0.75rem;"><?= $o['organ_icon'] ?></div>
                                <div style="font-weight:700; font-size:0.85rem;"><?= htmlspecialchars($o['organ_name']) ?></div>
                            </div>
                        <?php endforeach; else: ?><div style="grid-column:1/-1; color:var(--g400); font-size:0.8rem;">No living pledges available</div><?php endif; ?>
                    </div>
                    <h3 style="font-size:0.9rem; color:var(--g500); text-transform:uppercase; margin-bottom:1rem; display:flex; align-items:center; gap:8px;">After Death Donations</h3>
                    <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(160px,1fr)); gap:1.25rem; margin-bottom:2.5rem;">
                        <?php if(!empty($available_after_death)): foreach($available_after_death as $o): ?>
                            <div class="d-stat d-stat--interactive" style="padding:1.25rem; border: 1px solid var(--g200); text-align:center;" onclick="openAfterDeathModal(<?= $o['organ_id'] ?>, '<?= addslashes($o['organ_name']) ?>')">
                                <div style="color:var(--blue-500); font-size:1.5rem; margin-bottom:0.75rem;"><?= $o['organ_icon'] ?></div>
                                <div style="font-weight:700; font-size:0.85rem;"><?= htmlspecialchars($o['organ_name']) ?></div>
                            </div>
                        <?php endforeach; else: ?><div style="grid-column:1/-1; color:var(--g400); font-size:0.8rem;">All death pledges active.</div><?php endif; ?>
                    </div>
                    <h3 style="font-size:0.9rem; color:var(--g500); text-transform:uppercase; margin-bottom:1rem; display:flex; align-items:center; gap:8px;">Academic Body Donation</h3>
                    <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(300px,1fr)); gap:1.25rem;">
                        <?php if(!empty($available_full_body)): ?>
                            <div class="d-stat d-stat--interactive" onclick="goToBodyStep(1); openModal('bodyConsentModal')">
                                <div style="display:flex; align-items:center; gap:1.25rem; width:100%;">
                                    <div style="font-size:1.8rem; color:#8b5cf6;"><i class="fas fa-graduation-cap"></i></div>
                                    <div><div style="font-weight:700; font-size:1rem;">Full Body Donation Authorization</div><div style="font-size:0.8rem; color:var(--g500);">Expression of intent for anatomical study and surgical training.</div></div>
                                </div>
                            </div>
                        <?php else: ?><div style="grid-column:1/-1; color:var(--g400);">Body donation authorization active.</div><?php endif; ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>

<!-- LIVING MODAL (DOCUMENT-STYLE 6-STEP PROCESS) -->
<div id="livingConsentModal" class="d-modal">
    <div class="d-modal__body" style="max-width:750px;">
        <div class="d-modal__header">
            <div class="d-modal__title-group">
                <h3><i class="fas fa-file-signature text-accent"></i> Living Donation Consent</h3>
                <p class="d-modal__subtitle">Official Legal Authorization for Organ Donation</p>
            </div>
            <button class="d-modal__close" onclick="closeModal('livingConsentModal')">&times;</button>
        </div>
        
        <div class="d-modal__content">
            <!-- Step 1: A. Donor Personal Information -->
            <div id="step1" class="d-modal__step active">
                <div class="d-instruction-box">
                    <h4><i class="fas fa-info-circle"></i> 1. Live Organ Donation Consent Form</h4>
                    <p>Please verify your personal information and provide additional details as required by the transplantation act.</p>
                </div>
                <h4 class="d-section-header"><i class="fas fa-user-circle text-accent"></i> A. Donor Personal Information</h4>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem; background:#f8fafc; padding:1.5rem; border-radius:12px; border:1.5px solid var(--g200);">
                    <div class="d-input-group">
                        <label>Full Name (as in NIC)</label>
                        <input type="text" class="d-input" value="<?= htmlspecialchars($donor_full_name) ?>" readonly style="background:#f1f5f9;">
                    </div>
                    <div class="d-input-group">
                        <label>NIC Number</label>
                        <input type="text" class="d-input" value="<?= htmlspecialchars($donor_data['nic_number'] ?? '') ?>" readonly style="background:#f1f5f9;">
                    </div>
                    <div class="d-input-group">
                        <label>Date of Birth</label>
                        <input type="text" class="d-input" value="<?= htmlspecialchars($donor_data['date_of_birth'] ?? '') ?>" readonly style="background:#f1f5f9;">
                    </div>
                    <div class="d-input-group">
                        <label>Gender</label>
                        <input type="text" class="d-input" value="<?= htmlspecialchars($donor_data['gender'] ?? '') ?>" readonly style="background:#f1f5f9;">
                    </div>
                    <div class="d-input-group" style="grid-column: span 2;">
                        <label>Address <span style="color:var(--danger);">*</span></label>
                        <textarea id="livingAddress" class="d-input" style="height:60px;"><?= htmlspecialchars($donor_data['address'] ?? '') ?></textarea>
                    </div>
                    <div class="d-input-group">
                        <label>Blood Group <span style="color:var(--danger);">*</span></label>
                        <select id="bloodGroup" class="d-input">
                            <option value="">-- Select Blood Group --</option>
                            <option value="A+" <?= ($donor_data['blood_group'] ?? '') == 'A+' ? 'selected' : '' ?>>A+</option>
                            <option value="A-" <?= ($donor_data['blood_group'] ?? '') == 'A-' ? 'selected' : '' ?>>A-</option>
                            <option value="B+" <?= ($donor_data['blood_group'] ?? '') == 'B+' ? 'selected' : '' ?>>B+</option>
                            <option value="B-" <?= ($donor_data['blood_group'] ?? '') == 'B-' ? 'selected' : '' ?>>B-</option>
                            <option value="AB+" <?= ($donor_data['blood_group'] ?? '') == 'AB+' ? 'selected' : '' ?>>AB+</option>
                            <option value="AB-" <?= ($donor_data['blood_group'] ?? '') == 'AB-' ? 'selected' : '' ?>>AB-</option>
                            <option value="O+" <?= ($donor_data['blood_group'] ?? '') == 'O+' ? 'selected' : '' ?>>O+</option>
                            <option value="O-" <?= ($donor_data['blood_group'] ?? '') == 'O-' ? 'selected' : '' ?>>O-</option>
                        </select>
                    </div>
                    <div class="d-input-group">
                        <label>Nationality <span style="color:var(--danger);">*</span></label>
                        <input type="text" id="nationality" class="d-input" value="<?= htmlspecialchars($donor_data['nationality'] ?? 'Sri Lankan') ?>" placeholder="e.g. Sri Lankan">
                    </div>
                </div>
                <div style="text-align:right; margin-top:2rem;"><button class="d-btn d-btn--primary" onclick="goToStep(2)">Continue to Medical Details <i class="fas fa-arrow-right"></i></button></div>
            </div>

            <!-- Step 2: B. Medical Information -->
            <div id="step2" class="d-modal__step">
                <h4 class="d-section-header"><i class="fas fa-briefcase-medical text-accent"></i> B. Medical Information</h4>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; background:#f8fafc; padding:1.5rem; border-radius:12px; border:1.5px solid var(--g200);">
                    <div class="d-input-group">
                        <label>Height (cm)</label>
                        <input type="number" id="height" class="d-input" placeholder="e.g. 175">
                    </div>
                    <div class="d-input-group">
                        <label>Weight (kg)</label>
                        <input type="number" id="weight" class="d-input" placeholder="e.g. 70">
                    </div>
                    <div class="d-input-group" style="grid-column: span 2;">
                        <label>Existing Medical Conditions</label>
                        <textarea id="conditions" class="d-input" placeholder="List any chronic illnesses..." style="height:60px;"></textarea>
                    </div>
                    <div class="d-input-group" style="grid-column: span 2;">
                        <label>Current Medications</label>
                        <textarea id="medications" class="d-input" placeholder="List medications you are currently taking..." style="height:60px;"></textarea>
                    </div>
                    <div class="d-input-group" style="grid-column: span 2;">
                        <label>Previous Surgeries</label>
                        <textarea id="surgeries" class="d-input" placeholder="List any major surgeries..." style="height:60px;"></textarea>
                    </div>
                    <div class="d-input-group" style="grid-column: span 2;">
                        <label>Allergies</label>
                        <input type="text" id="allergies" class="d-input" placeholder="Food, Drug or seasonal allergies...">
                    </div>
                    <div class="d-input-group" style="grid-column: span 2;">
                        <label>Smoking / Alcohol Status</label>
                        <select id="habits" class="d-input">
                            <option value="None">None</option>
                            <option value="Smoking Only">Smoking Only</option>
                            <option value="Alcohol Only">Alcohol Only</option>
                            <option value="Both">Both (Smoking & Alcohol)</option>
                            <option value="Occasionally">Occasionally</option>
                        </select>
                    </div>
                </div>
                <div style="display:flex; justify-content:space-between; margin-top:2rem;"><button class="d-btn d-btn--outline" onclick="goToStep(1)"><i class="fas fa-arrow-left"></i> Previous</button><button class="d-btn d-btn--primary" onclick="handleStep2Next()">Next: Donation Details <i class="fas fa-arrow-right"></i></button></div>
            </div>

            <!-- Step 3: C. Hospital Selection (Request Based) -->
            <div id="step3" class="d-modal__step">
                <h4 class="d-section-header"><i class="fas fa-hospital text-accent"></i> C. Hospital Selection</h4>
                
                <div class="d-instruction-box" style="margin-bottom:1.5rem;">
                    <h4><i class="fas fa-search-location"></i> Available Organ Requests</h4>
                    <p>Select a hospital that has submitted an official request for <strong id="req_organ_name">the organ</strong>. Matching your donation with a specific request ensures immediate clinical use.</p>
                </div>

                <div style="background:#f8fafc; padding:1.5rem; border-radius:12px; border:1.5px solid var(--g200); margin-bottom:1.5rem;">
                    <div class="d-input-group" style="margin-bottom:0;">
                        <label>Organ willing to donate</label>
                        <input type="text" id="living_organ_name" class="d-input" readonly style="background:#f1f5f9; font-weight:700;">
                    </div>
                </div>

                <div id="hospital_request_label" style="font-size:0.85rem; font-weight:700; color:var(--slate); margin-bottom:1rem; display:flex; justify-content:space-between; align-items:center;">
                    <span>Hospitals with active requests</span>
                    <span style="font-size:0.7rem; color:var(--g500);">(Ordered by Priority)</span>
                </div>
                
                <div class="d-input-group">
                    <label>Select Destination Hospital <span style="color:var(--danger);">*</span></label>
                    <select id="hospitalDropdown" class="d-input" onchange="onHospitalChange()" style="margin-top:8px;">
                        <option value="">-- No specific hospital preference --</option>
                    </select>
                </div>

                <div style="display:flex; justify-content:space-between; margin-top:2rem;"><button class="d-btn d-btn--outline" onclick="goToStep(2)"><i class="fas fa-arrow-left"></i> Previous</button><button class="d-btn d-btn--primary" onclick="goToStep(4)">Next: Legal & Emergency <i class="fas fa-arrow-right"></i></button></div>
            </div>

            <!-- Step 4: D. Compatibility & F. Emergency Contact -->
            <div id="step4" class="d-modal__step">
                <h4 class="d-section-header"><i class="fas fa-flask text-accent"></i> D. Compatibility Information (Staff Update)</h4>
                <p style="font-size:0.8rem; color:var(--g500); margin-bottom:1rem;">Optional at this stage. Medical staff will update this after investigations.</p>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; background:white; padding:1.25rem; border:1px solid var(--g200); border-radius:12px; margin-bottom:2rem;">
                    <div class="d-input-group">
                        <label>Blood Compatibility</label>
                        <input type="text" id="compat_blood" class="d-input" placeholder="Pending investigation...">
                    </div>
                    <div class="d-input-group">
                        <label>Tissue Typing (HLA Match)</label>
                        <input type="text" id="compat_tissue" class="d-input" placeholder="Pending investigation...">
                    </div>
                </div>

                <h4 class="d-section-header"><i class="fas fa-phone-alt text-accent"></i> F. Emergency Contact</h4>
                <div style="background:#fff7ed; padding:1.5rem; border-radius:12px; border:1.5px solid #fed7aa;">
                    <div class="d-input-group">
                        <label>Emergency Contact Name <span style="color:var(--danger);">*</span></label>
                        <input type="text" id="emergencyName" class="d-input">
                    </div>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                        <div class="d-input-group">
                            <label>Relationship <span style="color:var(--danger);">*</span></label>
                            <input type="text" id="emergencyRel" class="d-input">
                        </div>
                        <div class="d-input-group">
                            <label>Phone Number <span style="color:var(--danger);">*</span></label>
                            <input type="text" id="emergencyPhone" class="d-input">
                        </div>
                    </div>
                </div>

                <h4 class="d-section-header"><i class="fas fa-users text-accent"></i> Legal Representatives (Witnesses)</h4>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                    <div style="background:white; padding:1rem; border:1.5px solid var(--g200); border-radius:12px;">
                        <label style="font-size:0.7rem; font-weight:800; color:var(--g400);">WITNESS 1</label>
                        <input type="text" id="cust1_name" class="d-input" placeholder="Full Name" style="margin-top:8px;">
                        <input type="text" id="cust1_nic" class="d-input" placeholder="NIC Number" style="margin-top:8px;">
                    </div>
                    <div style="background:white; padding:1rem; border:1.5px solid var(--g200); border-radius:12px;">
                        <label style="font-size:0.7rem; font-weight:800; color:var(--g400);">WITNESS 2</label>
                        <input type="text" id="cust2_name" class="d-input" placeholder="Full Name" style="margin-top:8px;">
                        <input type="text" id="cust2_nic" class="d-input" placeholder="NIC Number" style="margin-top:8px;">
                    </div>
                </div>

                <div style="display:flex; justify-content:space-between; margin-top:2rem;"><button class="d-btn d-btn--outline" onclick="goToStep(3)"><i class="fas fa-arrow-left"></i> Previous</button><button class="d-btn d-btn--primary" onclick="goToStep5()">Review & Legal Consent <i class="fas fa-check-double"></i></button></div>
            </div>

            <!-- Step 5: E. Legal Consent & Review -->
            <div id="step5" class="d-modal__step">
                <div id="livingReviewContent">
                    <div class="d-review-page" style="padding: 2.5rem; color: var(--slate);">
                        <div class="d-review-header">
                            <h2>Live Organ Donation Consent Form</h2>
                            <p style="text-transform: uppercase; letter-spacing: 2px; font-weight: 700; font-size: 0.75rem; color: var(--blue-600); margin-top: 5px;">Formal Statutory Declaration</p>
                        </div>
                        
                        <div class="d-instruction-box" style="background:#f0fdf4; border-color:var(--accent); color:#166534; font-size:0.85rem; margin-bottom:2rem;">
                            <strong>Declaration:</strong> I, <span id="rev_donor_name" style="font-weight: 700; text-decoration: underline;"><?= htmlspecialchars($donor_full_name) ?></span>, holder of NIC <strong><?= htmlspecialchars($donor_data['nic_number'] ?? '') ?></strong>, hereby confirm that this pledge is <strong>strictly voluntary</strong> and I have received <strong>no financial compensation</strong> for this act.
                        </div>

                        <!-- Formal Info Grid -->
                        <div class="d-info-grid" style="margin-bottom: 1.5rem;">
                            <div class="d-info-item"><label>Organ for Donation</label><span id="review_as_organ" style="color:var(--blue-700); font-weight: 800;">-</span></div>
                            <div class="d-info-item"><label>Filing Date</label><span><?= date('F d, Y') ?></span></div>
                        </div>

                        <div class="d-info-grid" style="grid-template-columns: repeat(3, 1fr); gap: 1rem; border-top: 1px solid var(--g100); padding-top: 1rem; margin-bottom: 2rem;">
                            <div class="d-info-item"><label>Nationality</label><span id="rev_nationality">-</span></div>
                            <div class="d-info-item"><label>Blood Group</label><span><?= htmlspecialchars($donor_data['blood_group'] ?? 'Not Specified') ?></span></div>
                            <div class="d-info-item"><label>Gender</label><span><?= htmlspecialchars($donor_data['gender'] ?? '-') ?></span></div>
                        </div>

                        <div style="background: #f8fafc; padding: 1.5rem; border-radius: 8px; margin-bottom: 2rem;">
                            <h6 style="font-size:0.7rem; color:var(--g500); text-transform:uppercase; border-bottom:1px solid var(--g200); padding-bottom:5px; margin-bottom: 10px;">Medical Summary</h6>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                                <div style="font-size: 0.85rem;"><strong>Vitals:</strong> <span id="rev_vitals">-</span></div>
                                <div style="font-size: 0.85rem;"><strong>Habits:</strong> <span id="rev_habits">-</span></div>
                                <div style="font-size: 0.85rem; grid-column: span 2;"><strong>Surgeries/Conditions:</strong> <span id="rev_medical">-</span></div>
                            </div>
                        </div>

                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:2.5rem; margin-bottom:3rem;">
                            <div>
                                <h6 style="font-size:0.7rem; color:var(--g500); text-transform:uppercase; border-bottom:1px solid var(--g100); padding-bottom:5px;">Recipient Institution</h6>
                                <p id="rev_hospital_info" style="font-size:0.95rem; font-weight:800; color: var(--blue-700); margin-top:10px;">Registry Managed</p>
                                <div style="font-size:0.75rem; color:var(--g500); margin-top:4px;">Medical center authorized for recovery and surgical procedures.</div>
                            </div>
                            <div>
                                <h6 style="font-size:0.7rem; color:var(--g500); text-transform:uppercase; border-bottom:1px solid var(--g100); padding-bottom:5px;">Witnesses & Verification</h6>
                                <div style="margin-top: 10px;">
                                    <div style="font-size: 0.85rem; font-weight: 700;" id="rev_witness1">W1: -</div>
                                    <div style="font-size: 0.85rem; font-weight: 700; margin-top: 4px;" id="rev_witness2">W2: -</div>
                                </div>
                            </div>
                        </div>

                        <div style="background: #fffbeb; padding: 1rem; border-radius: 8px; margin-bottom: 2.5rem; font-size: 0.8rem; color: #92400e; display: flex; align-items: center; gap: 10px;">
                            <i class="fas fa-exclamation-triangle"></i>
                            <div><strong>Emergency Contact:</strong> <span id="rev_emergency_info" style="font-weight: 800;">-</span></div>
                        </div>

                        <div class="signature-block">
                            <div class="sig-line">WITNESS 01 SIGNATURE</div>
                            <div class="sig-line">WITNESS 02 SIGNATURE</div>
                            <div class="sig-line">DONOR'S SIGNATURE</div>
                        </div>
                    </div>
                </div>
                <div style="display:flex; justify-content:space-between; align-items:center; margin-top:2rem; padding-top:1.5rem; border-top:2px solid var(--g100);">
                    <button class="d-btn d-btn--outline" onclick="goToStep(4)"><i class="fas fa-arrow-left"></i> Previous</button>
                    <div style="display:flex; gap:12px;">
                        <button class="d-btn d-btn--secondary" onclick="downloadPledge('livingReviewContent')"><i class="fas fa-file-pdf"></i> Download Document</button>
                        <button class="d-btn d-btn--primary" onclick="submitPledge()"><i class="fas fa-check-circle"></i> Finalize Consent</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- AFTER DEATH MODAL (DOCUMENT-STYLE 4-STEP) -->
<div id="afterDeathConsentModal" class="d-modal">
    <div class="d-modal__body" style="max-width:700px;">
        <div class="d-modal__header">
            <div class="d-modal__title-group">
                <h3><i class="fas fa-dove text-accent"></i> After Death Donation Pledge</h3>
                <p class="d-modal__subtitle">Expression of Intent for Post-Mortem Recovery</p>
            </div>
            <button class="d-modal__close" onclick="closeModal('afterDeathConsentModal')">&times;</button>
        </div>
        <form id="afterDeathForm" method="POST" action="<?= ROOT ?>/donor/donations" style="padding: 0 1.5rem 1.5rem;">
            <input type="hidden" name="action" value="submit_after_death_pledge">
            
            <!-- Step 1: Personal Information -->
            <div id="deathStep1">
                <h4 class="d-section-header"><i class="fas fa-user-circle text-accent"></i> A. Donor Personal Information</h4>
                <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:1.25rem; background:white; padding:1.75rem; border-radius:12px; border:1px solid var(--g200);">
                    <div class="d-info-item"><label>Full Name</label><span><?= htmlspecialchars($donor_data['first_name'] . ' ' . $donor_data['last_name']) ?></span></div>
                    <div class="d-info-item"><label>NIC Number</label><span><?= htmlspecialchars($donor_data['nic_number']) ?></span></div>
                    <div class="d-info-item"><label>Date of Birth</label><span><?= htmlspecialchars($donor_data['date_of_birth']) ?></span></div>
                    <div class="d-info-item"><label>Gender</label><span><?= htmlspecialchars($donor_data['gender']) ?></span></div>
                    <div class="d-info-item">
                        <label>Blood Group <span style="color:var(--danger);">*</span></label>
                        <select name="blood_group" class="d-input" style="padding: 0.4rem; font-size: 0.8rem; height: auto;" required>
                            <option value="">-- Select --</option>
                            <option value="A+" <?= ($donor_data['blood_group'] ?? '') == 'A+' ? 'selected' : '' ?>>A+</option>
                            <option value="A-" <?= ($donor_data['blood_group'] ?? '') == 'A-' ? 'selected' : '' ?>>A-</option>
                            <option value="B+" <?= ($donor_data['blood_group'] ?? '') == 'B+' ? 'selected' : '' ?>>B+</option>
                            <option value="B-" <?= ($donor_data['blood_group'] ?? '') == 'B-' ? 'selected' : '' ?>>B-</option>
                            <option value="AB+" <?= ($donor_data['blood_group'] ?? '') == 'AB+' ? 'selected' : '' ?>>AB+</option>
                            <option value="AB-" <?= ($donor_data['blood_group'] ?? '') == 'AB-' ? 'selected' : '' ?>>AB-</option>
                            <option value="O+" <?= ($donor_data['blood_group'] ?? '') == 'O+' ? 'selected' : '' ?>>O+</option>
                            <option value="O-" <?= ($donor_data['blood_group'] ?? '') == 'O-' ? 'selected' : '' ?>>O-</option>
                        </select>
                    </div>
                    <div class="d-info-item"><label>Nationality</label><span><?= htmlspecialchars($donor_data['nationality'] ?? 'Sri Lankan') ?></span></div>
                    <div class="d-info-item" style="grid-column:span 4; border-top:1px solid var(--g100); padding-top:1rem; margin-top:0.5rem;">
                        <label>Official Address of Record <span style="color:var(--danger);">*</span></label>
                        <textarea name="address" class="d-input" style="height: 60px; font-size: 0.85rem;" required><?= htmlspecialchars($donor_data['address'] ?? '') ?></textarea>
                    </div>
                </div>
                <div class="d-instruction-box" style="margin-top:1.5rem; background:var(--blue-50); color:var(--blue-700); border-color:var(--blue-100);">
                    <p style="font-size:0.85rem;"><i class="fas fa-info-circle"></i> This statutory information is synced with your primary donor profile. Accuracy is mandatory for legal validity.</p>
                </div>
                <div style="text-align:right; margin-top:2rem;"><button type="button" class="d-btn d-btn--primary" style="padding: 0.8rem 2rem;" onclick="goToDeathStep(2)">Begin Statutory Selection <i class="fas fa-arrow-right"></i></button></div>
            </div>

            <!-- Step 2: Organ Selection -->
            <div id="deathStep2" style="display:none;">
                <h4 class="d-section-header"><i class="fas fa-check-square text-accent"></i> B. Donation Preferences</h4>
                <p style="font-size:0.9rem; color:var(--g600); margin-bottom:1.5rem;">Select the specific organs and tissues you authorize for clinical recovery:</p>
                <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(160px, 1fr)); gap:15px; margin-bottom:2rem;">
                    <?php foreach($available_after_death as $o): ?>
                    <label class="organ-sel-card" style="display:flex; align-items:center; gap:12px; cursor:pointer; padding:1.25rem; background:white; border-radius:12px; border:1px solid var(--g200); transition:all 0.2s ease;">
                        <input type="checkbox" name="organ_ids[]" id="death_org_<?=$o['organ_id']?>" value="<?=$o['organ_id']?>" class="death-org-check" onchange="updateDeathReview()" style="width:22px; height:22px; accent-color:var(--accent);"> 
                        <span style="font-size:0.95rem; font-weight:700; color:var(--slate);"><?=$o['organ_name']?></span>
                    </label>
                    <?php endforeach; ?>
                </div>
                <div style="display:flex; justify-content:space-between; margin-top:2rem;"><button type="button" class="d-btn d-btn--outline" onclick="goToDeathStep(1)"><i class="fas fa-arrow-left"></i> Previous</button><button type="button" class="d-btn d-btn--primary" onclick="goToDeathStep(3)">Confirm Selections <i class="fas fa-arrow-right"></i></button></div>
            </div>

            <!-- Step 3: Donation Type -->
            <div id="deathStep3" style="display:none;">
                <h4 class="d-section-header"><i class="fas fa-hand-holding-heart text-accent"></i> C. Donation Type</h4>
                <div class="d-input-group" style="background:var(--g50); padding:1.5rem; border-radius:12px; margin-bottom:1.5rem;">
                    <label style="font-weight:700; margin-bottom:10px; display:block;">Donate any suitable organs?</label>
                    <div style="display:flex; gap:2rem;">
                        <label style="display:flex; align-items:center; gap:8px; cursor:pointer;"><input type="radio" name="suitability_any" value="1" checked> Yes</label>
                        <label style="display:flex; align-items:center; gap:8px; cursor:pointer;"><input type="radio" name="suitability_any" value="0"> No</label>
                    </div>
                </div>
                <div class="d-input-group" style="background:var(--g50); padding:1.5rem; border-radius:12px;">
                    <label style="font-weight:700; margin-bottom:10px; display:block;">Do you want to restrict specific organs?</label>
                    <div style="display:flex; gap:2rem;">
                        <label style="display:flex; align-items:center; gap:8px; cursor:pointer;"><input type="radio" name="is_restricted" value="1"> Yes</label>
                        <label style="display:flex; align-items:center; gap:8px; cursor:pointer;"><input type="radio" name="is_restricted" value="0" checked> No</label>
                    </div>
                </div>
                <div style="display:flex; justify-content:space-between; margin-top:2rem;"><button type="button" class="d-btn d-btn--outline" onclick="goToDeathStep(2)"><i class="fas fa-arrow-left"></i> Back</button><button type="button" class="d-btn d-btn--primary" onclick="goToDeathStep(4)">Next Step <i class="fas fa-arrow-right"></i></button></div>
            </div>

            <!-- Step 4: Legal Custodians -->
            <div id="deathStep4" style="display:none;">
                <h4 class="d-section-header"><i class="fas fa-users text-accent"></i> D. Legal Custodian Information</h4>
                <p style="font-size:0.85rem; color:var(--g600); margin-bottom:1.5rem;">Provide details for two legal custodians/next of kin who will be consulted even after your consent.</p>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem;">
                    <div class="custodian-card" style="background:var(--blue-50); padding:1.25rem; border-radius:12px; border:1px solid var(--blue-100);">
                        <label style="font-size:0.7rem; font-weight:800; color:var(--blue-700); text-transform:uppercase;">Custodian 1</label>
                        <input type="text" name="c1_name" id="dc_c1_name" class="d-input" placeholder="Full Name" style="margin-top:8px;">
                        <input type="text" name="c1_nic" id="dc_c1_nic" class="d-input" placeholder="NIC Number" style="margin-top:8px;">
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px;">
                            <input type="text" name="c1_rel" id="dc_c1_rel" class="d-input" placeholder="Relation" style="margin-top:8px;">
                            <input type="text" name="c1_phone" id="dc_c1_phone" class="d-input" placeholder="Phone" style="margin-top:8px;">
                        </div>
                        <input type="email" name="c1_email" id="dc_c1_email" class="d-input" placeholder="Email" style="margin-top:8px;">
                        <input type="text" name="c1_address" id="dc_c1_address" class="d-input" placeholder="Address" style="margin-top:8px;">
                    </div>
                    <div class="custodian-card" style="background:var(--blue-50); padding:1.25rem; border-radius:12px; border:1px solid var(--blue-100);">
                        <label style="font-size:0.7rem; font-weight:800; color:var(--blue-700); text-transform:uppercase;">Custodian 2</label>
                        <input type="text" name="c2_name" id="dc_c2_name" class="d-input" placeholder="Full Name" style="margin-top:8px;">
                        <input type="text" name="c2_nic" id="dc_c2_nic" class="d-input" placeholder="NIC Number" style="margin-top:8px;">
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px;">
                            <input type="text" name="c2_rel" id="dc_c2_rel" class="d-input" placeholder="Relation" style="margin-top:8px;">
                            <input type="text" name="c2_phone" id="dc_c2_phone" class="d-input" placeholder="Phone" style="margin-top:8px;">
                        </div>
                        <input type="email" name="c2_email" id="dc_c2_email" class="d-input" placeholder="Email" style="margin-top:8px;">
                        <input type="text" name="c2_address" id="dc_c2_address" class="d-input" placeholder="Address" style="margin-top:8px;">
                    </div>
                </div>
                <div style="display:flex; justify-content:space-between; margin-top:2rem;"><button type="button" class="d-btn d-btn--outline" onclick="goToDeathStep(3)"><i class="fas fa-arrow-left"></i> Back</button><button type="button" class="d-btn d-btn--primary" onclick="goToDeathStep(5)">Next Step <i class="fas fa-arrow-right"></i></button></div>
            </div>

            <!-- Step 5: Death & Retrieval Preferences -->
            <div id="deathStep5" style="display:none;">
                <h4 class="d-section-header"><i class="fas fa-vial text-accent"></i> E. Death & Retrieval Preferences</h4>
                <div class="d-input-group" style="margin-top:1.5rem;">
                    <label style="font-weight:700;">Religion / Cultural Considerations</label>
                    <input type="text" name="religion" id="dc_religion" class="d-input" placeholder="e.g. Buddhist, Christian, Muslim">
                </div>
                <div class="d-input-group" style="margin-top:1.5rem;">
                    <label style="font-weight:700;">Special Instructions</label>
                    <textarea name="special_instructions" id="dc_instructions" class="d-input" placeholder="Any specific wishes regarding the retrieval or burial..." rows="3"></textarea>
                </div>
                <div style="display:flex; justify-content:space-between; margin-top:2rem;"><button type="button" class="d-btn d-btn--outline" onclick="goToDeathStep(4)"><i class="fas fa-arrow-left"></i> Back</button><button type="button" class="d-btn d-btn--primary" onclick="goToDeathStep(6)">Legal Declaration <i class="fas fa-arrow-right"></i></button></div>
            </div>

            <!-- Step 6: Legal Declaration & Witnesses -->
            <div id="deathStep6" style="display:none;">
                <h4 class="d-section-header"><i class="fas fa-scroll text-accent"></i> F. Legal Declaration</h4>
                <div class="d-instruction-box" style="margin-bottom:1.5rem;">
                    <p style="font-size:0.9rem; line-height:1.6; color:var(--slate);">
                        I hereby confirm my consent for organ retrieval after my death. I authorize clinical staff to determine brain death or circulatory death as per national statutory guidelines. I understand this consent can be revoked at any time.
                    </p>
                </div>
                <h4 class="d-section-header"><i class="fas fa-user-shield text-accent"></i> G. Witness Signatures</h4>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem;">
                    <div style="background:#f8fafc; padding:1.25rem; border-radius:12px; border:1px solid var(--g200);">
                        <label style="font-size:0.7rem; font-weight:800; color:var(--blue-600); text-transform:uppercase;">Witness 1</label>
                        <input type="text" name="w1_name" id="dc_w1_name" class="d-input" placeholder="Full Name" style="margin-top:8px;">
                        <input type="text" name="w1_nic" id="dc_w1_nic" class="d-input" placeholder="NIC Number" style="margin-top:8px;">
                    </div>
                    <div style="background:#f8fafc; padding:1.25rem; border-radius:12px; border:1px solid var(--g200);">
                        <label style="font-size:0.7rem; font-weight:800; color:var(--blue-600); text-transform:uppercase;">Witness 2</label>
                        <input type="text" name="w2_name" id="dc_w2_name" class="d-input" placeholder="Full Name" style="margin-top:8px;">
                        <input type="text" name="w2_nic" id="dc_w2_nic" class="d-input" placeholder="NIC Number" style="margin-top:8px;">
                    </div>
                </div>
                <div style="display:flex; justify-content:space-between; margin-top:2rem;"><button type="button" class="d-btn d-btn--outline" onclick="goToDeathStep(5)"><i class="fas fa-arrow-left"></i> Back</button><button type="button" class="d-btn d-btn--primary" onclick="goToDeathStep(7)">Review & Finalize <i class="fas fa-marker"></i></button></div>
            </div>

            <!-- Step 7: Final Statutory Review -->
            <div id="deathStep7" style="display:none;">
                <div id="afterDeathReviewContent">
                    <div class="d-review-page" style="padding:2.5rem; color:var(--slate);">
                        <div class="d-review-header">
                            <h2>Statutory Declaration of Intent</h2>
                            <p style="text-transform: uppercase; letter-spacing: 2px; font-weight: 700; font-size: 0.75rem; color: var(--blue-600); margin-top: 5px;">Official Post-Mortem Organ Donation Consent</p>
                        </div>

                        <div class="d-instruction-box" style="background:#f0fdf4; border-color:var(--accent); color:#166534; font-size:0.85rem; margin-bottom:2rem;">
                            <strong>Declaration of Intent:</strong> I, <span style="font-weight: 800; text-decoration: underline;"><?= htmlspecialchars($donor_data['first_name'] . ' ' . $donor_data['last_name']) ?></span>, holder of NIC <strong><?= htmlspecialchars($donor_data['nic_number']) ?></strong>, hereby declare my voluntary intent for organ retrieval following clinical verification of death.
                        </div>
                        
                        <div class="d-info-grid" style="grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom:1.5rem;">
                            <div class="d-info-item"><label>Date of Declaration</label><span><?= date('F d, Y') ?></span></div>
                            <div class="d-info-item"><label>Blood Group</label><span><?= htmlspecialchars($donor_data['blood_group'] ?? 'Not Set') ?></span></div>
                            <div class="d-info-item"><label>Nationality</label><span><?= htmlspecialchars($donor_data['nationality'] ?? 'Sri Lankan') ?></span></div>
                        </div>
                        
                        <div style="margin:2rem 0; padding:1.5rem; background:var(--blue-50); border-radius:12px; border:1px solid var(--blue-100);">
                            <label style="font-size:0.7rem; font-weight:800; color:var(--blue-600); text-transform:uppercase; margin-bottom:10px; display:block;">Authorized Recovery Portfolio</label>
                            <div id="revDeathOrgans" style="font-size:1.15rem; font-weight:800; color:var(--blue-700); line-height:1.4;">-</div>
                        </div>
                        
                        <div style="margin-bottom:2.5rem;">
                            <h6 style="font-size:0.7rem; color:var(--g500); text-transform:uppercase; border-bottom:1px solid var(--g100); padding-bottom:8px; margin-bottom:15px;">Legal Custodians / Next of Kin</h6>
                            <div style="display:grid; grid-template-columns:1fr 1fr; gap:2rem;">
                                <div style="background:#f8fafc; padding:1.25rem; border-radius:10px; border:1px solid var(--g200);">
                                    <strong id="revDeathC1Name" style="color:var(--blue-900); font-size:1rem; display:block; margin-bottom:4px;">-</strong>
                                    <div style="font-size:0.8rem; color:var(--g600);"><i class="fas fa-link"></i> <span id="revDeathC1Rel">-</span></div>
                                    <div style="font-size:0.8rem; color:var(--g600); margin-top:4px;"><i class="fas fa-phone"></i> <span id="revDeathC1Phone">-</span></div>
                                </div>
                                <div style="background:#f8fafc; padding:1.25rem; border-radius:10px; border:1px solid var(--g200);">
                                    <strong id="revDeathC2Name" style="color:var(--blue-900); font-size:1rem; display:block; margin-bottom:4px;">-</strong>
                                    <div style="font-size:0.8rem; color:var(--g600);"><i class="fas fa-link"></i> <span id="revDeathC2Rel">-</span></div>
                                    <div style="font-size:0.8rem; color:var(--g600); margin-top:4px;"><i class="fas fa-phone"></i> <span id="revDeathC2Phone">-</span></div>
                                </div>
                            </div>
                        </div>

                        <div style="margin-bottom:3rem;">
                            <h6 style="font-size:0.7rem; color:var(--g500); text-transform:uppercase; border-bottom:1px solid var(--g100); padding-bottom:8px; margin-bottom:15px;">Legal Witnesses</h6>
                            <div style="display:grid; grid-template-columns:1fr 1fr; gap:2rem;">
                                <div style="font-size:0.85rem;"><span style="color:var(--g500);">Witness 1:</span> <strong id="revDeathW1Name">-</strong> (NIC: <span id="revDeathW1Nic">-</span>)</div>
                                <div style="font-size:0.85rem;"><span style="color:var(--g500);">Witness 2:</span> <strong id="revDeathW2Name">-</strong> (NIC: <span id="revDeathW2Nic">-</span>)</div>
                            </div>
                        </div>

                        <div class="signature-block" style="border-top:1px solid var(--g100); padding-top:2.5rem; display:grid; grid-template-columns:1fr 1fr; gap:2rem 3rem;">
                            <div class="sig-line">Donor Signature</div>
                            <div class="sig-line">Witness 1 Signature</div>
                            <div class="sig-line">Witness 2 Signature</div>
                            <div class="sig-line">Custodian 1 Authorization</div>
                        </div>
                    </div>
                </div>
                <div style="display:flex; justify-content:space-between; align-items:center; margin-top:2rem;">
                    <button type="button" class="d-btn d-btn--outline" onclick="goToDeathStep(6)"><i class="fas fa-arrow-left"></i> Back</button>
                    <div style="display:flex; gap:10px;">
                        <button type="button" class="d-btn d-btn--secondary" onclick="downloadPledge('afterDeathReviewContent')"><i class="fas fa-file-pdf"></i> Download Document</button>
                        <button type="button" class="d-btn d-btn--primary" onclick="submitAfterDeath()"><i class="fas fa-check-circle"></i> Submit Consent</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- ACADEMIC MODAL (DOCUMENT-STYLE 4-STEP) -->
<div id="bodyConsentModal" class="d-modal">
    <div class="d-modal__body" style="max-width:700px;">
        <div class="d-modal__header">
            <div class="d-modal__title-group">
                <h3><i class="fas fa-university text-accent"></i> Full Body Donation Consent</h3>
                <p class="d-modal__subtitle">Anatomical Authorization for Medical Education</p>
            </div>
            <button class="d-modal__close" onclick="closeModal('bodyConsentModal')">&times;</button>
        </div>
        <form id="bodyConsentForm" method="POST" action="<?= ROOT ?>/donor/donations" style="padding: 0 1.5rem 1.5rem;">
            <input type="hidden" name="action" value="submit_body_pledge">
            
            <!-- Step 1: Personal Info -->
            <div id="bodyStep1">
                <h4 class="d-section-header"><i class="fas fa-user-circle text-accent"></i> A. Donor Personal Information</h4>
                <div style="display:grid; grid-template-columns: repeat(3, 1fr); gap:1.25rem; background:white; padding:1.75rem; border-radius:12px; border:1px solid var(--g200);">
                    <div class="d-info-item"><label>Full Name</label><span><?= htmlspecialchars($donor_full_name) ?></span></div>
                    <div class="d-info-item"><label>NIC / ID</label><span><?= htmlspecialchars($donor_data['nic_number'] ?? '') ?></span></div>
                    <div class="d-info-item"><label>Date of Birth</label><span><?= htmlspecialchars($donor_data['date_of_birth'] ?? '') ?></span></div>
                    <div class="d-info-item"><label>Gender</label><span><?= htmlspecialchars($donor_data['gender'] ?? '-') ?></span></div>
                    <div class="d-info-item">
                        <label>Blood Group <span style="color:var(--danger);">*</span></label>
                        <select name="blood_group" class="d-input" style="padding: 0.4rem; font-size: 0.8rem; height: auto;" required>
                            <option value="">-- Select --</option>
                            <option value="A+" <?= ($donor_data['blood_group'] ?? '') == 'A+' ? 'selected' : '' ?>>A+</option>
                            <option value="A-" <?= ($donor_data['blood_group'] ?? '') == 'A-' ? 'selected' : '' ?>>A-</option>
                            <option value="B+" <?= ($donor_data['blood_group'] ?? '') == 'B+' ? 'selected' : '' ?>>B+</option>
                            <option value="B-" <?= ($donor_data['blood_group'] ?? '') == 'B-' ? 'selected' : '' ?>>B-</option>
                            <option value="AB+" <?= ($donor_data['blood_group'] ?? '') == 'AB+' ? 'selected' : '' ?>>AB+</option>
                            <option value="AB-" <?= ($donor_data['blood_group'] ?? '') == 'AB-' ? 'selected' : '' ?>>AB-</option>
                            <option value="O+" <?= ($donor_data['blood_group'] ?? '') == 'O+' ? 'selected' : '' ?>>O+</option>
                            <option value="O-" <?= ($donor_data['blood_group'] ?? '') == 'O-' ? 'selected' : '' ?>>O-</option>
                        </select>
                    </div>
                    <div class="d-info-item" style="grid-column: span 2;">
                        <label>Address <span style="color:var(--danger);">*</span></label>
                        <textarea name="address" class="d-input" style="height: 60px; font-size: 0.85rem;" required><?= htmlspecialchars($donor_data['address'] ?? '') ?></textarea>
                    </div>
                </div>
                <div style="text-align:right; margin-top:2rem;"><button type="button" class="d-btn d-btn--primary" onclick="goToBodyStep(2)">Proceed to Academic Details <i class="fas fa-arrow-right"></i></button></div>
            </div>

            <!-- Step 2: Academic Details -->
            <div id="bodyStep2" style="display:none;">
                <h4 class="d-section-header"><i class="fas fa-university text-accent"></i> B. Body Donation Details</h4>
                <div class="d-input-group">
                    <label>Preferred Medical Faculty / University <span style="color:var(--danger);">*</span></label>
                    <select name="medical_school_id" id="schoolSelect" class="d-input" required>
                        <option value="">-- Select Medical Faculty --</option>
                        <?php if(!empty($medical_schools)): foreach($medical_schools as $s): ?>
                            <option value="<?=$s->id?>"><?=htmlspecialchars($s->school_name)?></option>
                        <?php endforeach; endif; ?>
                    </select>
                </div>
                <div class="d-input-group" style="margin-top:1.25rem;">
                    <label>Religion</label>
                    <input type="text" name="religion" id="body_religion" class="d-input" placeholder="e.g. Buddhist, Christian">
                </div>
                <div class="d-input-group" style="margin-top:1.25rem;">
                    <label>Special Requests regarding usage (Optional)</label>
                    <textarea name="special_requests" id="body_requests" class="d-input" placeholder="Any specific limitations or wishes..." rows="2"></textarea>
                </div>
                <div style="display:flex; justify-content:space-between; margin-top:2rem;"><button type="button" class="d-btn d-btn--outline" onclick="goToBodyStep(1)"><i class="fas fa-arrow-left"></i> Previous</button><button type="button" class="d-btn d-btn--primary" onclick="goToBodyStep(3)">Accept Conditions <i class="fas fa-arrow-right"></i></button></div>
            </div>

            <!-- Step 3: Acceptance Conditions -->
            <div id="bodyStep3" style="display:none;">
                <h4 class="d-section-header"><i class="fas fa-exclamation-triangle text-accent"></i> C. Acceptance Conditions</h4>
                <div class="d-instruction-box" style="background: #fff5f5; border-color: #feb2b2; color: #742a2a; margin-bottom:1.5rem;">
                    <p style="font-size:0.85rem; font-weight:700;"><i class="fas fa-info-circle"></i> Medical Schools may decline a body under specific statutory conditions. Please acknowledge you understand these terms:</p>
                </div>
                <div style="display:grid; gap:12px;">
                    <label style="display:flex; gap:12px; font-size:0.9rem; cursor:pointer; padding:12px; border:1px solid var(--g200); border-radius:8px; background:white;">
                        <input type="checkbox" required style="width:20px; height:20px;">
                        <span>Body may be refused if infected with communicable diseases (e.g. HIV, Hepatitis).</span>
                    </label>
                    <label style="display:flex; gap:12px; font-size:0.9rem; cursor:pointer; padding:12px; border:1px solid var(--g200); border-radius:8px; background:white;">
                        <input type="checkbox" required style="width:20px; height:20px;">
                        <span>Body may be refused in cases of severe physical trauma or post-mortem already conducted.</span>
                    </label>
                    <label style="display:flex; gap:12px; font-size:0.9rem; cursor:pointer; padding:12px; border:1px solid var(--g200); border-radius:8px; background:white;">
                        <input type="checkbox" required style="width:20px; height:20px;">
                        <span>Authorizing Institution reserves the right of final acceptance based on educational needs.</span>
                    </label>
                </div>
                <div style="display:flex; justify-content:space-between; margin-top:2rem;"><button type="button" class="d-btn d-btn--outline" onclick="goToBodyStep(2)"><i class="fas fa-arrow-left"></i> Previous</button><button type="button" class="d-btn d-btn--primary" onclick="goToBodyStep(4)">Next: Custodians <i class="fas fa-arrow-right"></i></button></div>
            </div>

            <!-- Step 4: Legal Custodians (Next of Kin) -->
            <div id="bodyStep4" style="display:none;">
                <h4 class="d-section-header"><i class="fas fa-users text-accent"></i> D. Next of Kin (Custodians)</h4>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.25rem;">
                    <div class="custodian-card" style="background:var(--blue-50); padding:1rem; border-radius:12px; border:1px solid var(--blue-100);">
                        <label style="font-size:0.7rem; font-weight:800; color:var(--blue-700);">CUSTODIAN 1 (NOK)</label>
                        <input type="text" name="cust1_name" id="bc_c1_name" class="d-input" placeholder="Full Name" style="margin-top:8px;">
                        <input type="text" name="cust1_nic" id="bc_c1_nic" class="d-input" placeholder="NIC Number" style="margin-top:8px;">
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px;">
                            <input type="text" name="cust1_rel" id="bc_c1_rel" class="d-input" placeholder="Relation" style="margin-top:8px;">
                            <input type="text" name="cust1_phone" id="bc_c1_phone" class="d-input" placeholder="Phone" style="margin-top:8px;">
                        </div>
                    </div>
                    <div class="custodian-card" style="background:var(--blue-50); padding:1rem; border-radius:12px; border:1px solid var(--blue-100);">
                        <label style="font-size:0.7rem; font-weight:800; color:var(--blue-700);">CUSTODIAN 2 (NOK)</label>
                        <input type="text" name="cust2_name" id="bc_c2_name" class="d-input" placeholder="Full Name" style="margin-top:8px;">
                        <input type="text" name="cust2_nic" id="bc_c2_nic" class="d-input" placeholder="NIC Number" style="margin-top:8px;">
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px;">
                            <input type="text" name="cust2_rel" id="bc_c2_rel" class="d-input" placeholder="Relation" style="margin-top:8px;">
                            <input type="text" name="cust2_phone" id="bc_c2_phone" class="d-input" placeholder="Phone" style="margin-top:8px;">
                        </div>
                    </div>
                </div>
                <div style="display:flex; justify-content:space-between; margin-top:2rem;"><button type="button" class="d-btn d-btn--outline" onclick="goToBodyStep(3)"><i class="fas fa-arrow-left"></i> Previous</button><button type="button" class="d-btn d-btn--primary" onclick="goToBodyStep(5)">Notification & Transport <i class="fas fa-arrow-right"></i></button></div>
            </div>

            <!-- Step 5: Notification & Transport -->
            <div id="bodyStep5" style="display:none;">
                <h4 class="d-section-header"><i class="fas fa-phone-volume text-accent"></i> E. Death Notification & F. Transport</h4>
                <div style="background:#f8fafc; padding:1.5rem; border-radius:12px; border:1px solid var(--g200);">
                    <div class="d-input-group">
                        <label>Person Responsible to Inform Medical Faculty <span style="color:var(--danger);">*</span></label>
                        <input type="text" name="responsible_person" id="bc_resp_p" class="d-input" placeholder="Full Name of Primary Contact">
                    </div>
                    <div class="d-input-group" style="margin-top:1.25rem;">
                        <label>Contact Number <span style="color:var(--danger);">*</span></label>
                        <input type="text" name="responsible_contact" id="bc_resp_c" class="d-input" placeholder="07x-xxxxxxx">
                    </div>
                    <hr style="margin:1.5rem 0; border:0; border-top:1px solid var(--g100);">
                    <div class="d-input-group">
                        <label>Transport Arrangement Scheme <span style="color:var(--danger);">*</span></label>
                        <textarea name="transport_arrangement" id="bc_transport" class="d-input" placeholder="Details of how transport will be managed (e.g. Family arranged, specific funeral service)..." rows="2"></textarea>
                    </div>
                </div>
                <div style="display:flex; justify-content:space-between; margin-top:2rem;"><button type="button" class="d-btn d-btn--outline" onclick="goToBodyStep(4)"><i class="fas fa-arrow-left"></i> Previous</button><button type="button" class="d-btn d-btn--primary" onclick="goToBodyStep(6)">Review & Sign <i class="fas fa-check-double"></i></button></div>
            </div>

            <!-- Step 6: Review & Declaration -->
            <div id="bodyStep6" style="display:none;">
                <div id="bodyReviewContent">
                    <div class="d-review-page" style="padding:2.5rem; color:var(--slate);">
                        <div class="d-review-header">
                            <h2>Statutory Anatomical Authorization</h2>
                            <p style="text-transform: uppercase; letter-spacing: 1px; font-weight: 700; font-size: 0.75rem; color: var(--blue-600); margin-top: 5px;">Whole Body Donation for Medical Science</p>
                        </div>
                        
                        <div class="d-instruction-box" style="background:#f0fdf4; border-color:var(--accent); color:#166534; font-size:0.85rem; margin-bottom:2rem;">
                            <strong>Anatomical Declaration:</strong> I, <span style="font-weight: 800; text-decoration: underline;"><?= htmlspecialchars($donor_full_name) ?></span>, NIC <strong><?= htmlspecialchars($donor_data['nic_number'] ?? '') ?></strong>, hereby authorize the delivery of my body to the <span id="revBodySchool" style="font-weight:800; color:var(--blue-700);">-</span> for purposes of anatomical study and clinical research.
                        </div>

                        <div class="d-info-grid" style="grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom:2rem;">
                            <div class="d-info-item"><label>Religion</label><span id="revBodyReligion">-</span></div>
                            <div class="d-info-item"><label>Notification Contact</label><span id="revBodyResp">-</span></div>
                            <div class="d-info-item"><label>Filing Date</label><span><?= date('F d, Y') ?></span></div>
                        </div>

                        <div style="margin-bottom:2rem;">
                            <h6 style="font-size:0.7rem; color:var(--g500); text-transform:uppercase; border-bottom:1px solid var(--g100); padding-bottom:5px; margin-bottom:12px;">Witnesses & Verification</h6>
                            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem;">
                                <div style="background:#f8fafc; padding:1rem; border-radius:8px; border:1px solid var(--g200);">
                                    <label style="font-size:0.65rem; font-weight:800; color:var(--blue-600);">WITNESS 1</label>
                                    <input type="text" name="witness1_name" id="bc_w1_name" class="d-input" placeholder="Full Name (Required)" required style="margin-top:4px;">
                                    <input type="text" name="witness1_nic" id="bc_w1_nic" class="d-input" placeholder="NIC Number (Required)" required style="margin-top:8px;">
                                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:5px;">
                                        <input type="text" name="witness1_phone" id="bc_w1_phone" class="d-input" placeholder="Phone" style="margin-top:8px;">
                                        <input type="text" name="witness1_address" id="bc_w1_address" class="d-input" placeholder="Address" style="margin-top:8px;">
                                    </div>
                                </div>
                                <div style="background:#f8fafc; padding:1rem; border-radius:8px; border:1px solid var(--g200);">
                                    <label style="font-size:0.65rem; font-weight:800; color:var(--blue-600);">WITNESS 2</label>
                                    <input type="text" name="witness2_name" id="bc_w2_name" class="d-input" placeholder="Full Name (Required)" required style="margin-top:4px;">
                                    <input type="text" name="witness2_nic" id="bc_w2_nic" class="d-input" placeholder="NIC Number (Required)" required style="margin-top:8px;">
                                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:5px;">
                                        <input type="text" name="witness2_phone" id="bc_w2_phone" class="d-input" placeholder="Phone" style="margin-top:8px;">
                                        <input type="text" name="witness2_address" id="bc_w2_address" class="d-input" placeholder="Address" style="margin-top:8px;">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="signature-block" style="margin-top:3rem; grid-template-columns: 1fr 1fr; gap: 2rem 4rem;">
                            <div class="sig-line">Donor Signature</div>
                            <div class="sig-line">Custodian (NOK) 1</div>
                            <div class="sig-line">Witness 1 Signature</div>
                            <div class="sig-line">Witness 2 Signature</div>
                        </div>
                    </div>
                </div>
                <div style="display:flex; justify-content:space-between; margin-top:2rem; border-top:1px solid var(--g200); padding-top:1.5rem;">
                    <button type="button" class="d-btn d-btn--outline" onclick="goToBodyStep(5)"><i class="fas fa-arrow-left"></i> Previous</button>
                    <div style="display:flex; gap:12px;"><button type="button" class="d-btn d-btn--secondary" onclick="downloadPledge('bodyReviewContent')"><i class="fas fa-file-pdf"></i> Download Document</button><button type="submit" class="d-btn d-btn--primary"><i class="fas fa-check-circle"></i> Authorize Donation</button></div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Warning / Scripts -->
<div id="unselectWarningModal" class="d-modal"><div class="d-modal__body" style="max-width:400px; text-align:center; p:2rem;"><h3>Withdraw Pledge?</h3><p id="unselectText" mb:2rem></p><div style="display:flex; gap:1rem; justify:center;"><button class="d-btn d-btn--outline" onclick="closeModal('unselectWarningModal')">Cancel</button><button class="d-btn d-btn--danger" onclick="submitAction('unselect_organ', pendingOrganId)">Withdraw</button></div></div></div>

<form id="pledgeForm" method="POST" action="<?= ROOT ?>/donor/donations">
    <input type="hidden" name="action" value="submit_living_pledge">
    <input type="hidden" name="organ_id" id="pledgeOrganId">
    <input type="hidden" name="hospital_id" id="pledgeHospitalId">
    
    <!-- Detailed Sections -->
    <input type="hidden" name="nationality" id="p_nationality">
    <input type="hidden" name="height" id="p_height">
    <input type="hidden" name="weight" id="p_weight">
    <input type="hidden" name="surgeries" id="p_surgeries">
    <input type="hidden" name="allergies" id="p_allergies">
    <input type="hidden" name="habits" id="p_habits">
    
    <!-- Legacy fields -->
    <input type="hidden" name="conditions" id="pledgeConditions">
    <input type="hidden" name="blood_group" id="pledgeBloodGroup">
    <input type="hidden" name="address" id="pledgeAddress">
    
    <!-- Recipient Info (REMOVED) -->
    
    <!-- Compatibility -->
    <input type="hidden" name="compat_blood" id="p_compat_blood">
    <input type="hidden" name="compat_tissue" id="p_compat_tissue">
    
    <!-- Emergency Contact -->
    <input type="hidden" name="emergency_name" id="p_emergency_name">
    <input type="hidden" name="emergency_rel" id="p_emergency_rel">
    <input type="hidden" name="emergency_phone" id="p_emergency_phone">

    <!-- Witnesses (Original Custodians Map) -->
    <input type="hidden" name="cust1_name" id="p_cust1_name">
    <input type="hidden" name="cust1_nic" id="p_cust1_nic">
    <input type="hidden" name="cust2_name" id="p_cust2_name">
    <input type="hidden" name="cust2_nic" id="p_cust2_nic">
</form>

<script>
const hospitalsByOrgan = <?= $hospitalsByOrganJson ?>;
let pendingOrganId=null, pendingOrganName=null, selectedHospitalId=null, selectedHospitalName='No Preference';
function openModal(id){ document.getElementById(id).style.display='flex'; }
function closeModal(id){ document.getElementById(id).style.display='none'; }
function openLivingModal(id,name){ 
    pendingOrganId=id; 
    pendingOrganName=name; 
    document.getElementById('living_organ_name').value = name; 
    document.getElementById('req_organ_name').textContent = name;
    goToStep(1); 
    openModal('livingConsentModal'); 
}
function goToStep(n){ 
    const currentStepNum = parseInt(document.querySelector('.d-modal__step.active')?.id.replace('step','') || '1');
    
    // Validate Current Step before moving forward
    if(n > currentStepNum) {
        const currentStep = document.getElementById('step' + currentStepNum);
        const inputs = currentStep.querySelectorAll('input[required], select[required], textarea[required]');
        let valid = true;
        inputs.forEach(input => {
            if(!input.value || (input.type === 'checkbox' && !input.checked)) {
                input.style.borderColor = 'var(--danger)';
                valid = false;
            } else {
                input.style.borderColor = 'var(--g200)';
            }
        });
        if(!valid) {
            alert('Please fill all required fields in Step ' + currentStepNum + ' before proceeding.');
            return;
        }
    }

    document.querySelectorAll('.d-modal__step').forEach(s => s.classList.remove('active'));
    document.querySelectorAll('#livingConsentModal [id^="step"]').forEach(s => s.style.display = 'none');
    const el = document.getElementById('step' + n);
    if(el) {
        el.classList.add('active');
        el.style.display = 'block';
    }
}
function handleStep2Next(){ updateHospitalList(); goToStep(3); }
function updateHospitalList() {
    const dropdown = document.getElementById('hospitalDropdown');
    dropdown.innerHTML = '';
    
    // Add default option
    const defaultOpt = document.createElement('option');
    defaultOpt.value = '';
    defaultOpt.textContent = '-- No specific hospital preference --';
    dropdown.appendChild(defaultOpt);

    const reqs = hospitalsByOrgan[pendingOrganId] || [];
    if(reqs.length > 0) {
        reqs.forEach(h => {
            const opt = document.createElement('option');
            opt.value = h.hospital_id;
            opt.textContent = `${h.hospital_name} (${h.district}) - ${h.priority} PRIORITY`;
            dropdown.appendChild(opt);
        });
    }

    // Reset selection state
    selectedHospitalId = null;
    selectedHospitalName = 'No specific hospital preference';
}

function onHospitalChange() {
    const dropdown = document.getElementById('hospitalDropdown');
    selectedHospitalId = dropdown.value;
    selectedHospitalName = dropdown.options[dropdown.selectedIndex].text;
}
function goToStep5(){ 
    document.getElementById('review_as_organ').textContent=pendingOrganName; 
    
    // Personal & Medical
    document.getElementById('rev_nationality').textContent = document.getElementById('nationality').value || 'Not Specified';
    document.getElementById('rev_vitals').textContent = (document.getElementById('height').value || '-') + ' cm | ' + (document.getElementById('weight').value || '-') + ' kg';
    document.getElementById('rev_habits').textContent = document.getElementById('habits').value;
    document.getElementById('rev_medical').textContent = document.getElementById('conditions').value + ' | Surgeries: ' + document.getElementById('surgeries').value;

    // Hospital Selection Summary
    document.getElementById('rev_hospital_info').textContent = selectedHospitalName;

    // Witnesses
    document.getElementById('rev_witness1').textContent = 'W1: ' + (document.getElementById('cust1_name').value || 'Not Provided') + ' (' + (document.getElementById('cust1_nic').value || '-') + ')';
    document.getElementById('rev_witness2').textContent = 'W2: ' + (document.getElementById('cust2_name').value || 'Not Provided') + ' (' + (document.getElementById('cust2_nic').value || '-') + ')';

    // Emergency Contact Summary
    document.getElementById('rev_emergency_info').textContent = document.getElementById('emergencyName').value + ' (' + document.getElementById('emergencyRel').value + ') - ' + document.getElementById('emergencyPhone').value;

    goToStep(5); 
}
function submitPledge(){ 
    document.getElementById('pledgeOrganId').value=pendingOrganId; 
    document.getElementById('pledgeHospitalId').value=selectedHospitalId||''; 
    
    // Detailed Sections
    document.getElementById('p_nationality').value = document.getElementById('nationality').value;
    document.getElementById('p_height').value = document.getElementById('height').value;
    document.getElementById('p_weight').value = document.getElementById('weight').value;
    document.getElementById('p_surgeries').value = document.getElementById('surgeries').value;
    document.getElementById('p_allergies').value = document.getElementById('allergies').value;
    document.getElementById('p_habits').value = document.getElementById('habits').value;
    
    // Recipient Info (REMOVED)
    
    document.getElementById('p_compat_blood').value = document.getElementById('compat_blood').value;
    document.getElementById('p_compat_tissue').value = document.getElementById('compat_tissue').value;
    
    document.getElementById('p_emergency_name').value = document.getElementById('emergencyName').value;
    document.getElementById('p_emergency_rel').value = document.getElementById('emergencyRel').value;
    document.getElementById('p_emergency_phone').value = document.getElementById('emergencyPhone').value;

    document.getElementById('p_cust1_name').value = document.getElementById('cust1_name').value;
    document.getElementById('p_cust1_nic').value = document.getElementById('cust1_nic').value;
    document.getElementById('p_cust2_name').value = document.getElementById('cust2_name').value;
    document.getElementById('p_cust2_nic').value = document.getElementById('cust2_nic').value;

    // Legacy fields still needed by simple model logic
    document.getElementById('pledgeConditions').value = document.getElementById('conditions').value;
    document.getElementById('pledgeBloodGroup').value = document.getElementById('bloodGroup').value;
    document.getElementById('pledgeAddress').value = document.getElementById('livingAddress').value;

    document.getElementById('pledgeForm').submit(); 
}
function openAfterDeathModal(id,name){ document.querySelectorAll('.death-org-check').forEach(c=>c.checked=false); const target=document.getElementById('death_org_'+id); if(target) target.checked=true; goToDeathStep(1); openModal('afterDeathConsentModal'); }
function goToDeathStep(step) {
    const currentStepNum = parseInt(document.querySelector('#afterDeathForm div[id^="deathStep"]:not([style*="display: none"])')?.id.replace('deathStep','') || '1');
    
    // Validate Current Step before moving forward
    if(step > currentStepNum) {
        const currentStep = document.getElementById('deathStep' + currentStepNum);
        const inputs = currentStep.querySelectorAll('input[required], select[required], textarea[required]');
        let valid = true;
        inputs.forEach(input => {
            if(!input.value || (input.type === 'checkbox' && !input.checked)) {
                input.style.borderColor = 'var(--danger)';
                valid = false;
            } else {
                input.style.borderColor = 'var(--g200)';
            }
        });
        if(!valid) {
            alert('Please fill all required fields in Step ' + currentStepNum + ' before proceeding.');
            return;
        }
    }

    for(let i=1; i<=7; i++) {
        let el = document.getElementById('deathStep' + i);
        if(el) el.style.display = 'none';
    }
    document.getElementById('deathStep' + step).style.display = 'block';
    if(step === 7) updateDeathReview();
}

function updateDeathReview() {
    let organs = [];
    document.querySelectorAll('.death-org-check:checked').forEach(cb => {
        organs.push(cb.nextElementSibling.textContent);
    });
    document.getElementById('revDeathOrgans').textContent = organs.join(', ') || 'No Organs Selected';
    
    // Custodians
    document.getElementById('revDeathC1Name').textContent = document.getElementById('dc_c1_name').value || '-';
    document.getElementById('revDeathC1Rel').textContent = document.getElementById('dc_c1_rel').value || '-';
    document.getElementById('revDeathC1Phone').textContent = document.getElementById('dc_c1_phone').value || '-';
    
    document.getElementById('revDeathC2Name').textContent = document.getElementById('dc_c2_name').value || '-';
    document.getElementById('revDeathC2Rel').textContent = document.getElementById('dc_c2_rel').value || '-';
    document.getElementById('revDeathC2Phone').textContent = document.getElementById('dc_c2_phone').value || '-';
    
    // Witnesses
    document.getElementById('revDeathW1Name').textContent = document.getElementById('dc_w1_name').value || '-';
    document.getElementById('revDeathW1Nic').textContent = document.getElementById('dc_w1_nic').value || '-';
    document.getElementById('revDeathW2Name').textContent = document.getElementById('dc_w2_name').value || '-';
    document.getElementById('revDeathW2Nic').textContent = document.getElementById('dc_w2_nic').value || '-';
}

function submitAfterDeath() {
    document.getElementById('afterDeathForm').submit();
}
function goToBodyStep(n){ 
    const currentStepNum = parseInt(document.querySelector('#bodyConsentForm div[id^="bodyStep"]:not([style*="display: none"])')?.id.replace('bodyStep','') || '1');
    
    // Validate Current Step before moving forward
    if(n > currentStepNum) {
        const currentStep = document.getElementById('bodyStep' + currentStepNum);
        const inputs = currentStep.querySelectorAll('input[required], select[required], textarea[required]');
        let valid = true;
        inputs.forEach(input => {
            if(!input.value || (input.type === 'checkbox' && !input.checked)) {
                input.style.borderColor = 'var(--danger)';
                valid = false;
            } else {
                input.style.borderColor = 'var(--g200)';
            }
        });
        if(!valid) {
            alert('Please fill all required fields and acknowledge conditions before proceeding.');
            return;
        }
    }

    for(let i=1;i<=6;i++){ const el=document.getElementById('bodyStep'+i); if(el) el.style.display=(i===n)?'block':'none'; } 
    if(n===6) { 
        const s=document.getElementById('schoolSelect'); 
        document.getElementById('revBodySchool').textContent=s.options[s.selectedIndex].text; 
        document.getElementById('revBodyReligion').textContent = document.getElementById('body_religion').value || 'Not Specified';
        document.getElementById('revBodyResp').textContent = document.getElementById('bc_resp_p').value + ' (' + document.getElementById('bc_resp_c').value + ')';
    } 
}
function submitAction(action,id){ const f=document.createElement('form'); f.method='POST'; f.action='<?= ROOT ?>/donor/donations'; f.innerHTML=`<input type="hidden" name="action" value="${action}"><input type="hidden" name="id" value="${id}">`; document.body.appendChild(f); f.submit(); }
function downloadPledge(id) {
    const content = document.getElementById(id).innerHTML;
    const printWindow = window.open('', '_blank', 'height=800,width=1000');
    
    // Extract all styles from current document
    const styles = Array.from(document.querySelectorAll('style, link[rel="stylesheet"]'))
        .map(el => el.outerHTML)
        .join('\n');

    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Pledge Certification</title>
            <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
            ${styles}
            <style>
                body { background: white !important; margin: 0; padding: 40px; }
                .d-review-page { border: 2px solid #e2e8f0 !important; box-shadow: none !important; margin: 0 !important; width: 100% !important; max-width: none !important; }
                @page { size: auto; margin: 15mm; }
                .no-print { display: none !important; }
            </style>
        </head>
        <body>
            <div style="text-align:center; margin-bottom:40px;">
                <img src="<?= ROOT ?>/assets/images/logo.png" style="height:60px; margin-bottom:15px; display:block; margin: 0 auto;" onerror="this.src='https://via.placeholder.com/60?text=LC'">
                <h1 style="font-family:sans-serif; letter-spacing:2px; color:#1e293b; margin:0; font-size:1.8rem;">LIFE-CONNECT</h1>
                <p style="color:#64748b; font-size:0.9rem; margin-top:5px; text-transform:uppercase; letter-spacing:1px;">Official Registry Authorization Document</p>
            </div>
            ${content}
            <div style="margin-top:40px; padding-top:20px; border-top:1px solid #eee; text-align:center; color:#94a3b8; font-size:0.8rem;">
                This document is a formal record of intent logged in the Life-Connect National Organ Registry.<br>
                Verification: LC-${Math.random().toString(36).substr(2, 9).toUpperCase()}
            </div>
            <script>
                window.onload = function() {
                    setTimeout(() => {
                        window.print();
                        window.close();
                    }, 500);
                };
            <\/script>
        </body>
        </html>
    `);
    printWindow.document.close();
}
function openUnselectWarning(id,name){ pendingOrganId=id; document.getElementById('unselectText').textContent=`Withdraw pledge for ${name}?`; openModal('unselectWarningModal'); }
function openPledgeActionModal(id, name) {
    pendingOrganId = id;
    document.getElementById('actionPledgeTitle').textContent = name;
    document.getElementById('actionPledgeId').value = id;
    openModal('pledgeActionModal');
}
function uploadPledgeFile() {
    const fileInput = document.getElementById('pledgeFile');
    if(!fileInput.files.length) {
        alert("Please select a signed PDF document.");
        return;
    }
    document.getElementById('pledgeUploadForm').submit();
}
</script>

<!-- MODAL: PLEDGE ACTION (UPLOAD/WITHDRAW) -->
<div id="pledgeActionModal" class="d-modal">
    <div class="d-modal__body" style="max-width:500px;">
        <div class="d-modal__header">
            <h3 id="actionPledgeTitle">Organ Pledge</h3>
            <button class="d-modal__close" onclick="closeModal('pledgeActionModal')">&times;</button>
        </div>
        <div class="d-modal__content">
            <div style="padding: 1.5rem; background: #fffbe6; border: 1px solid #ffe58f; border-radius: 8px; margin-bottom: 1.5rem; font-size: 0.9rem; color: #854d0e;">
                <i class="fas fa-exclamation-triangle"></i> This pledge is awaiting a signed document. Please upload the scanned copy to complete the formal registry process.
            </div>
            
            <form id="pledgeUploadForm" method="POST" action="<?= ROOT ?>/donor/donations" enctype="multipart/form-data">
                <input type="hidden" name="action" value="upload_signed_pledge">
                <input type="hidden" name="id" id="actionPledgeId">
                <div class="d-input-group">
                    <label>Signed PDF Document <span style="color:var(--danger);">*</span></label>
                    <input type="file" name="pledge_pdf" id="pledgeFile" class="d-input" accept=".pdf" style="padding: 10px;">
                    <p style="font-size:0.7rem; color:var(--g500); margin-top:5px;">Max size: 5MB (PDF only)</p>
                </div>
            </form>

            <div style="display:grid; grid-template-columns: 1fr; gap: 10px; margin-top: 1.5rem;">
                <button class="d-btn d-btn--primary" onclick="uploadPledgeFile()">
                    <i class="fas fa-upload"></i> Upload & Complete
                </button>
                <div style="text-align:center; margin: 5px 0; font-size: 0.8rem; color: var(--g400);">— OR —</div>
                <button class="d-btn d-btn--outline" onclick="closeModal('pledgeActionModal'); openUnselectWarning(pendingOrganId, document.getElementById('actionPledgeTitle').textContent)" style="color: var(--danger); border-color: var(--danger);">
                    <i class="fas fa-trash"></i> Withdraw Pledge
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL: UNSELECT WARNING -->
<div id="unselectWarningModal" class="d-modal">
    <div class="d-modal__body" style="max-width:400px; text-align:center;">
        <div style="font-size:3rem; color:var(--danger); margin-bottom:1rem;"><i class="fas fa-exclamation-circle"></i></div>
        <h3 id="unselectText">Withdraw this pledge?</h3>
        <p style="color:var(--g500); font-size:0.9rem; margin-top:0.5rem;">This will remove the intent from the official registry and notify relevant departments.</p>
        <div style="display:flex; justify-content:center; gap:12px; margin-top:2rem;">
            <button class="d-btn d-btn--outline" onclick="closeModal('unselectWarningModal')">Keep Pledge</button>
            <button class="d-btn d-btn--primary" style="background:var(--danger);" onclick="submitAction('unselect_organ', pendingOrganId)">Yes, Withdraw</button>
        </div>
    </div>
</div>

<?php include __DIR__ . '/inc/footer.view.php'; ?>
