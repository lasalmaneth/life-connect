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
            <!-- Step 1: Policies -->
            <div id="step1" class="d-modal__step active">
                <div class="d-instruction-box">
                    <h4><i class="fas fa-info-circle"></i> Medical Policies & Consent Guidelines</h4>
                    <p>By proceeding with this donation pledge, you acknowledge your adherence to clinical standards:</p>
                    <ul>
                        <li>Pledge is 100% voluntary and revocable anytime prior to surgery.</li>
                        <li>Absolutely no commercial trade is permitted under national law.</li>
                        <li>Donor must be medically verified for compatibility by an approved hospital.</li>
                    </ul>
                </div>
                <div class="d-warning-box">
                    <i class="fas fa-balance-scale"></i> Certification: I have read and legally accept all medical policies associated with organ donation for educational and healing purposes.
                </div>
                <div style="padding: 0 1rem;">
                    <label style="display:flex; align-items:center; gap:12px; cursor:pointer; font-weight:700; color:var(--slate);"><input type="checkbox" id="medicalConsent" style="width:20px; height:20px; accent-color:var(--accent);"> I formally agree to the terms above</label>
                </div>
                <div style="text-align:right; margin-top:2.5rem;"><button class="d-btn d-btn--primary" onclick="handleStep1Next()">Begin Formal Process <i class="fas fa-arrow-right"></i></button></div>
            </div>

            <!-- Step 2: Medical Details -->
            <div id="step2" class="d-modal__step">
                <h4 class="d-section-header"><i class="fas fa-notes-medical text-accent"></i> Clinical Health Profile</h4>
                <div style="background:#f8fafc; padding:2rem; border-radius:12px; border:1.5px solid var(--g200);">
                    <div class="d-input-group">
                        <label>Verified Blood Group <span style="color:var(--danger);">*</span></label>
                        <select id="bloodGroup" class="d-input" style="background:white;"><option value="">Select Blood Group</option><?php foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg): ?><option value="<?=$bg?>"><?=$bg?></option><?php endforeach; ?></select>
                    </div>
                    <div class="d-input-group" style="margin-top:1.5rem; margin-bottom:0;">
                        <label>Known Medical Conditions or Chronic Medications</label>
                        <textarea id="medications" class="d-input" placeholder="Type here..." style="height:120px; background:white;"></textarea>
                    </div>
                </div>
                <div style="display:flex; justify-content:space-between; margin-top:2.5rem;"><button class="d-btn d-btn--outline" onclick="goToStep(1)"><i class="fas fa-arrow-left"></i> Previous</button><button class="d-btn d-btn--primary" onclick="handleStep2Next()">Next Stage <i class="fas fa-arrow-right"></i></button></div>
            </div>

            <!-- Step 3: Receiving Hospital -->
            <div id="step3" class="d-modal__step">
                <h4 class="d-section-header"><i class="fas fa-hospital text-accent"></i> Selected Receiving Institution</h4>
                <p style="font-size:0.9rem; color:var(--g500); margin-bottom:1.5rem; padding: 0 0.5rem;">Choose your preferred hospital where the procedure and laboratory verification takes place.</p>
                <div id="hospitalList" style="display:grid; grid-template-columns:1fr 1fr; gap:1.25rem; margin-bottom:1rem;"></div>
                <div style="display:flex; justify-content:space-between; margin-top:2.5rem;"><button class="d-btn d-btn--outline" onclick="goToStep(2)"><i class="fas fa-arrow-left"></i> Previous</button><button class="d-btn d-btn--primary" onclick="goToStep(4)">Continue to Witnesses <i class="fas fa-arrow-right"></i></button></div>
            </div>

            <!-- Step 4: Witnesses -->
            <div id="step4" class="d-modal__step">
                <h4 class="d-section-header"><i class="fas fa-user-edit text-accent"></i> Identity Verification Witnesses</h4>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem;">
                    <div style="background:white; padding:1.5rem; border:1.5px solid var(--g200); border-radius:12px;">
                        <label style="font-size:0.75rem; font-weight:800; color:var(--g400);">WITNESS 1</label>
                        <input type="text" id="cust1_name" class="d-input" placeholder="Full Name" style="margin-top:10px;">
                        <input type="text" id="cust1_nic" class="d-input" placeholder="NIC Number" style="margin-top:10px;">
                    </div>
                    <div style="background:white; padding:1.5rem; border:1.5px solid var(--g200); border-radius:12px;">
                        <label style="font-size:0.75rem; font-weight:800; color:var(--g400);">WITNESS 2</label>
                        <input type="text" id="cust2_name" class="d-input" placeholder="Full Name" style="margin-top:10px;">
                        <input type="text" id="cust2_nic" class="d-input" placeholder="NIC Number" style="margin-top:10px;">
                    </div>
                </div>
                <div style="display:flex; justify-content:space-between; margin-top:2.5rem;"><button class="d-btn d-btn--outline" onclick="goToStep(3)"><i class="fas fa-arrow-left"></i> Previous</button><button class="d-btn d-btn--primary" onclick="goToStep6()">Review Application <i class="fas fa-marker"></i></button></div>
            </div>


            <!-- Step 6 (ENHANCED REVIEW) -->
            <div id="step6" class="d-modal__step">
                <div id="livingReviewContent">
                    <div class="d-review-page" style="padding: 2.5rem;">
                        <div class="d-review-header">
                            <h2>Pledge Authorization Record</h2>
                            <p>Certificate of Formal Intent for Living Organ Donation</p>
                        </div>
                        <div class="d-info-grid">
                            <div class="d-info-item"><label>Donor</label><span><?= htmlspecialchars($donor_full_name) ?></span></div>
                            <div class="d-info-item"><label>Pledge Type</label><span id="review_as_organ" style="color:var(--accent);">-</span></div>
                            <div class="d-info-item"><label>Certificate Date</label><span><?= date('M d, Y') ?></span></div>
                        </div>
                        <div class="no-print" style="margin: 1.5rem 0; padding: 1.25rem; background: #fffbeb; border: 1px solid #fef08a; border-radius: 8px; color: #854d0e; font-size: 0.85rem; line-height: 1.5;">
                            <i class="fas fa-info-circle"></i> <strong>Next Step:</strong> Please download this form, obtain a hard copy, add all required signatures (Donor and Witnesses), and upload the signed document back to the system to complete the process.
                        </div>
                        <div style="border-top:1px solid var(--g100); padding-top:1.5rem; margin-top:1rem; display:grid; grid-template-columns:1fr 1fr; gap:2rem;">
                            <div>
                                <label style="font-size:0.75rem; font-weight:800; color:var(--g400); text-transform:uppercase;">Witness 1</label>
                                <div id="revLivingW1" style="font-weight:700; color:var(--slate); margin-top:4px;">-</div>
                            </div>
                            <div>
                                <label style="font-size:0.75rem; font-weight:800; color:var(--g400); text-transform:uppercase;">Witness 2</label>
                                <div id="revLivingW2" style="font-weight:700; color:var(--slate); margin-top:4px;">-</div>
                            </div>
                        </div>
                        <div style="border-top:1px solid var(--g100); padding-top:1.5rem; margin-top:1rem;">
                            <label style="font-size:0.75rem; font-weight:800; color:var(--g400); text-transform:uppercase;">Declaration of Intent</label>
                            <p style="font-size:0.95rem; color:var(--slate); line-height:1.7; margin-top:0.75rem;">I, the undersigned, hereby certify that my decision to pledge this anatomical gift is made freely and without coercion. I authorize the medical personnel to record this intent in the registry.</p>
                        </div>
                        <div class="signature-block">
                            <div class="sig-line">WITNESS 1 SIGNATURE</div>
                            <div class="sig-line">WITNESS 2 SIGNATURE</div>
                            <div class="sig-line">DONOR SIGNATURE</div>
                        </div>
                    </div>
                </div>
                <div style="display:flex; justify-content:space-between; align-items:center; margin-top:2rem; padding-top:1.5rem; border-top:2px solid var(--g100);">
                    <button class="d-btn d-btn--outline" onclick="goToStep(4)"><i class="fas fa-arrow-left"></i> Previous</button>
                    <div style="display:flex; gap:12px;">
                        <button class="d-btn d-btn--secondary" onclick="downloadPledge('livingReviewContent')"><i class="fas fa-file-pdf"></i> Download Document</button>
                        <button class="d-btn d-btn--primary" onclick="submitPledge()"><i class="fas fa-check-circle"></i> Submit Official Application</button>
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
            
            <div id="deathStep1">
                <div class="d-instruction-box">
                    <h4><i class="fas fa-shield-alt"></i> Post-Mortem Guidelines</h4>
                    <p>This pledge constitutes a legal intent for organ recovery following clinical verification of brain death.</p>
                    <ul>
                        <li>Recovery only occurs in a certified clinical setting.</li>
                        <li>Family/Custodians will be consulted for final authorization.</li>
                        <li>This record serves as primary evidence of your noble intent.</li>
                    </ul>
                </div>
                <div style="text-align:right; margin-top:2rem;"><button type="button" class="d-btn d-btn--primary" onclick="goToDeathStep(2)">Begin Selection <i class="fas fa-arrow-right"></i></button></div>
            </div>

            <div id="deathStep2" style="display:none;">
                <h4 class="d-section-header"><i class="fas fa-check-square text-accent"></i> Authorized Organ Selection</h4>
                <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(140px, 1fr)); gap:12px; margin-bottom:2rem; background:var(--g50); padding:1.5rem; border-radius:12px;">
                    <?php foreach($available_after_death as $o): ?><label style="display:flex; align-items:center; gap:10px; cursor:pointer;"><input type="checkbox" name="organ_ids[]" id="death_org_<?=$o['organ_id']?>" value="<?=$o['organ_id']?>" class="death-org-check" style="width:18px; height:18px;"> <span style="font-size:0.9rem; font-weight:600; color:var(--slate);"><?=$o['organ_name']?></span></label><?php endforeach; ?>
                </div>
                <div style="display:flex; justify-content:space-between; margin-top:2rem;"><button type="button" class="d-btn d-btn--outline" onclick="goToDeathStep(1)"><i class="fas fa-arrow-left"></i> Back</button><button type="button" class="d-btn d-btn--primary" onclick="goToDeathStep(3)">Next Step <i class="fas fa-arrow-right"></i></button></div>
            </div>

            <div id="deathStep3" style="display:none;">
                <h4 class="d-section-header"><i class="fas fa-users text-accent"></i> Verification & Custodians</h4>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem;">
                    <div style="background:#f8fafc; padding:1.25rem; border-radius:12px; border:1px solid var(--g200);">
                        <label style="font-size:0.7rem; font-weight:800; color:var(--blue-600); text-transform:uppercase;">Witness 1</label>
                        <input type="text" name="w1_name" class="d-input" placeholder="Full Name" style="margin-top:8px;">
                        <input type="text" name="w1_nic" class="d-input" placeholder="NIC Number" style="margin-top:8px;">
                    </div>
                    <div style="background:#f8fafc; padding:1.25rem; border-radius:12px; border:1px solid var(--g200);">
                        <label style="font-size:0.7rem; font-weight:800; color:var(--blue-600); text-transform:uppercase;">Witness 2</label>
                        <input type="text" name="w2_name" class="d-input" placeholder="Full Name" style="margin-top:8px;">
                        <input type="text" name="w2_nic" class="d-input" placeholder="NIC Number" style="margin-top:8px;">
                    </div>
                    <div style="background:var(--blue-50); padding:1.25rem; border-radius:12px; border:1px solid var(--blue-100);">
                        <label style="font-size:0.7rem; font-weight:800; color:var(--blue-700); text-transform:uppercase;">Custodian 1</label>
                        <input type="text" name="c1_name" class="d-input" placeholder="Full Name" style="margin-top:8px;">
                        <input type="text" name="c1_nic" class="d-input" placeholder="NIC Number" style="margin-top:8px;">
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px;">
                            <input type="text" name="c1_rel" class="d-input" placeholder="Relation" style="margin-top:8px;">
                            <input type="text" name="c1_phone" class="d-input" placeholder="Phone" style="margin-top:8px;">
                        </div>
                    </div>
                    <div style="background:var(--blue-50); padding:1.25rem; border-radius:12px; border:1px solid var(--blue-100);">
                        <label style="font-size:0.7rem; font-weight:800; color:var(--blue-700); text-transform:uppercase;">Custodian 2</label>
                        <input type="text" name="c2_name" class="d-input" placeholder="Full Name" style="margin-top:8px;">
                        <input type="text" name="c2_nic" class="d-input" placeholder="NIC Number" style="margin-top:8px;">
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px;">
                            <input type="text" name="c2_rel" class="d-input" placeholder="Relation" style="margin-top:8px;">
                            <input type="text" name="c2_phone" class="d-input" placeholder="Phone" style="margin-top:8px;">
                        </div>
                    </div>
                </div>
                <div style="display:flex; justify-content:space-between; margin-top:2rem;"><button type="button" class="d-btn d-btn--outline" onclick="goToDeathStep(2)"><i class="fas fa-arrow-left"></i> Back</button><button type="button" class="d-btn d-btn--primary" onclick="goToDeathStep(4)">Review Pledge <i class="fas fa-marker"></i></button></div>
            </div>

            <div id="deathStep4" style="display:none;">
                <div id="afterDeathReviewContent">
                    <div class="d-review-page">
                        <div class="d-review-header">
                            <h2>Intent of Anatomical Gift</h2>
                            <p>Official Record of Post-Mortem Donation Pledge</p>
                        </div>
                        <div class="d-info-grid">
                            <div class="d-info-item"><label>Pledgor</label><span><?= htmlspecialchars($donor_full_name) ?></span></div>
                            <div class="d-info-item"><label>Donor ID</label><span>LC-<?= strtoupper(substr(md5($donor_full_name), 0, 8)) ?></span></div>
                        </div>
                        <div class="no-print" style="margin: 1.5rem 0; padding: 1.25rem; background: #fffbeb; border: 1px solid #fef08a; border-radius: 8px; color: #854d0e; font-size: 0.85rem; line-height: 1.5;">
                            <i class="fas fa-info-circle"></i> <strong>Next Step:</strong> Please download this form, obtain a hard copy, add all required signatures (Donor, Witnesses, and Custodians), and upload the signed document back to the system to complete the process.
                        </div>
                        <div style="margin-bottom:2rem;"><label style="font-size:0.7rem; font-weight:700; color:var(--g400); text-transform:uppercase;">Selected Organs for Recovery</label><div id="revDeathOrgans" style="font-size:1.1rem; font-weight:800; color:var(--blue-700); margin-top:0.5rem; padding:1rem; background:var(--blue-50); border-radius:8px;">-</div></div>
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:2rem; margin-bottom:2rem; padding:1.5rem; background:#f8fafc; border-radius:12px;">
                            <div>
                                <label style="font-size:0.7rem; font-weight:800; color:var(--blue-600); text-transform:uppercase; display:block; margin-bottom:8px;">Witness Verification</label>
                                <div id="revDeathW1" style="font-size:0.9rem; font-weight:700; color:var(--slate); margin-bottom:4px;">-</div>
                                <div id="revDeathW2" style="font-size:0.9rem; font-weight:700; color:var(--slate);">-</div>
                            </div>
                            <div>
                                <label style="font-size:0.7rem; font-weight:800; color:var(--blue-600); text-transform:uppercase; display:block; margin-bottom:8px;">Legal Custodians</label>
                                <div id="revDeathC1" style="font-size:0.9rem; font-weight:700; color:var(--slate); margin-bottom:4px;">-</div>
                                <div id="revDeathC2" style="font-size:0.9rem; font-weight:700; color:var(--slate);">-</div>
                            </div>
                        </div>
                        <div class="signature-block" style="grid-template-columns: 1fr 1fr; gap: 2rem 4rem;">
                            <div class="sig-line">Witness 1 Verification</div>
                            <div class="sig-line">Witness 2 Verification</div>
                            <div class="sig-line">Custodian 1 Authorization</div>
                            <div class="sig-line">Custodian 2 Authorization</div>
                            <div class="sig-line">DONOR SIGNATURE</div>
                        </div>
                    </div>
                </div>
                <div style="display:flex; justify-content:space-between; align-items:center; margin-top:2rem;">
                    <button type="button" class="d-btn d-btn--outline" onclick="goToDeathStep(3)"><i class="fas fa-arrow-left"></i> Back</button>
                    <div style="display:flex; gap:10px;"><button type="button" class="d-btn d-btn--secondary" onclick="downloadPledge('afterDeathReviewContent')"><i class="fas fa-file-pdf"></i> PDF</button><button type="button" class="d-btn d-btn--primary" onclick="submitAfterDeath()"><i class="fas fa-check-circle"></i> Confirm Intent</button></div>
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
            <input type="hidden" name="action" value="submit_body_consent">
            
            <div id="bodyStep1">
                <div class="d-instruction-box">
                    <h4><i class="fas fa-balance-scale"></i> Academic Terms & Conditions</h4>
                    <p>The remains will be utilized for medical research and surgical training at a recognized medical institution.</p>
                    <ul>
                        <li>Immediate notification of the institution upon death is mandatory.</li>
                        <li>Bodies with high communicable diseases may be declined.</li>
                        <li>Return or cremation of remains follows institutional policy.</li>
                    </ul>
                </div>
                <h4 class="d-section-header"><i class="fas fa-hospital text-accent"></i> Receiving Institution</h4>
                <select name="medical_school_id" id="schoolSelect" class="d-input" required><option value="">-- Select Medical Faculty --</option><?php if(!empty($medical_schools)): foreach($medical_schools as $s): ?><option value="<?=$s->id?>"><?=htmlspecialchars($s->school_name)?></option><?php endforeach; endif; ?></select>
                <div style="text-align:right; margin-top:2rem;"><button type="button" class="d-btn d-btn--primary" onclick="goToBodyStep(2)">Next Step <i class="fas fa-arrow-right"></i></button></div>
            </div>

            <div id="bodyStep2" style="display:none;">
                <h4 class="d-section-header"><i class="fas fa-user-edit text-accent"></i> Primary Witnesses</h4>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem;">
                    <div><label style="font-size:0.75rem; font-weight:700; color:var(--g600);">WITNESS 1</label><input type="text" name="witness1_name" class="d-input" placeholder="Full Name" style="margin-top:5px;"><input type="text" name="witness1_nic" class="d-input" placeholder="NIC / Passport" style="margin-top:10px;"></div>
                    <div><label style="font-size:0.75rem; font-weight:700; color:var(--g600);">WITNESS 2</label><input type="text" name="witness2_name" class="d-input" placeholder="Full Name" style="margin-top:5px;"><input type="text" name="witness2_nic" class="d-input" placeholder="NIC / Passport" style="margin-top:10px;"></div>
                </div>
                <div style="display:flex; justify-content:space-between; margin-top:2rem;"><button type="button" class="d-btn d-btn--outline" onclick="goToBodyStep(1)"><i class="fas fa-arrow-left"></i> Back</button><button type="button" class="d-btn d-btn--primary" onclick="goToBodyStep(3)">Next Step <i class="fas fa-arrow-right"></i></button></div>
            </div>

            <div id="bodyStep3" style="display:none;">
                <h4 class="d-section-header"><i class="fas fa-users text-accent"></i> Legal Custodians</h4>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem;">
                    <div><label style="font-size:0.75rem; font-weight:700; color:var(--g600);">CUSTODIAN 1</label><input type="text" name="cust1_name" class="d-input" placeholder="Full Name" style="margin-top:5px;"><input type="text" name="cust1_nic" class="d-input" placeholder="NIC / Passport" style="margin-top:10px;"></div>
                    <div><label style="font-size:0.75rem; font-weight:700; color:var(--g600);">CUSTODIAN 2</label><input type="text" name="cust2_name" class="d-input" placeholder="Full Name" style="margin-top:5px;"><input type="text" name="cust2_nic" class="d-input" placeholder="NIC / Passport" style="margin-top:10px;"></div>
                </div>
                <div style="display:flex; justify-content:space-between; margin-top:2rem;"><button type="button" class="d-btn d-btn--outline" onclick="goToBodyStep(2)"><i class="fas fa-arrow-left"></i> Back</button><button type="button" class="d-btn d-btn--primary" onclick="goToBodyStep(4)">Review Document <i class="fas fa-marker"></i></button></div>
            </div>

            <div id="bodyStep4" style="display:none;">
                <div id="bodyReviewContent">
                    <div class="d-review-page">
                        <div class="d-review-header">
                            <h2>Form of Anatomical Consent</h2>
                            <p>Official Full Body Donation Authorization for Academic Use</p>
                        </div>
                        <div class="d-info-grid">
                            <div class="d-info-item"><label>Authorized Donor</label><span><?= htmlspecialchars($donor_full_name) ?></span></div>
                            <div class="d-info-item"><label>Receiving Faculty</label><span id="revBodySchool" style="color:var(--accent); font-weight:800;">-</span></div>
                        </div>
                        <div class="no-print" style="margin: 1.5rem 0; padding: 1.25rem; background: #fffbeb; border: 1px solid #fef08a; border-radius: 8px; color: #854d0e; font-size: 0.85rem; line-height: 1.5;">
                            <i class="fas fa-info-circle"></i> <strong>Next Step:</strong> Please download this form, obtain a hard copy, add all required signatures (Donor, Witnesses, and Custodians), and upload the signed document back to the system to complete the process.
                        </div>
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:2rem; margin:1.5rem 0; padding:1.25rem; border:1px solid #e2e8f0; border-radius:8px;">
                            <div>
                                <label style="font-size:0.7rem; font-weight:800; color:var(--g500); text-transform:uppercase;">Witnesses</label>
                                <div id="revBodyW1" style="font-size:0.85rem; font-weight:600; color:var(--slate); margin-top:4px;">-</div>
                                <div id="revBodyW2" style="font-size:0.85rem; font-weight:600; color:var(--slate); margin-top:2px;">-</div>
                            </div>
                            <div>
                                <label style="font-size:0.7rem; font-weight:800; color:var(--g500); text-transform:uppercase;">Custodians</label>
                                <div id="revBodyC1" style="font-size:0.85rem; font-weight:600; color:var(--slate); margin-top:4px;">-</div>
                                <div id="revBodyC2" style="font-size:0.85rem; font-weight:600; color:var(--slate); margin-top:2px;">-</div>
                            </div>
                        </div>
                        <div class="signature-block" style="grid-template-columns: 1fr 1fr; gap: 2rem 4rem;">
                            <div class="sig-line">Witness 1 Verification</div>
                            <div class="sig-line">Witness 2 Verification</div>
                            <div class="sig-line">Custodian 1 Authorization</div>
                            <div class="sig-line">Custodian 2 Authorization</div>
                            <div class="sig-line">DONOR SIGNATURE</div>
                        </div>
                    </div>
                </div>
            </div>
                <div style="display:flex; justify-content:space-between; margin-top:2rem; border-top:1px solid var(--g200); padding-top:1rem;">
                    <button type="button" class="d-btn d-btn--outline" onclick="goToBodyStep(3)"><i class="fas fa-arrow-left"></i> Back</button>
                    <div style="display:flex; gap:10px;"><button type="button" class="d-btn d-btn--secondary" onclick="downloadPledge('bodyReviewContent')"><i class="fas fa-file-pdf"></i> PDF</button><button type="submit" class="d-btn d-btn--primary"><i class="fas fa-check-circle"></i> Confirm Authorization</button></div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Warning / Scripts -->
<div id="unselectWarningModal" class="d-modal"><div class="d-modal__body" style="max-width:400px; text-align:center; p:2rem;"><h3>Withdraw Pledge?</h3><p id="unselectText" mb:2rem></p><div style="display:flex; gap:1rem; justify:center;"><button class="d-btn d-btn--outline" onclick="closeModal('unselectWarningModal')">Cancel</button><button class="d-btn d-btn--danger" onclick="submitAction('unselect_organ', pendingOrganId)">Withdraw</button></div></div></div>
<form id="pledgeForm" method="POST" action="<?= ROOT ?>/donor/donations" style="display:none;"><input type="hidden" name="action" value="select_organ"><input type="hidden" name="id" id="pledgeOrganId"><input type="hidden" name="hospital_id" id="pledgeHospitalId"><input type="hidden" name="blood_group" id="pledgeBloodGroup"><input type="hidden" name="conditions" id="pledgeConditions"><input type="hidden" name="cust1_name" id="p_cust1_name"><input type="hidden" name="cust1_nic" id="p_cust1_nic"><input type="hidden" name="rep1_rel" id="p_cust1_rel"><input type="hidden" name="rep1_phone" id="p_cust1_phone"><input type="hidden" name="cust2_name" id="p_cust2_name"><input type="hidden" name="cust2_nic" id="p_cust2_nic"><input type="hidden" name="rep2_rel" id="p_cust2_rel"><input type="hidden" name="rep2_phone" id="p_cust2_phone"></form>

<script>
const hospitalsByOrgan = <?= $hospitalsByOrganJson ?>;
let pendingOrganId=null, pendingOrganName=null, selectedHospitalId=null, selectedHospitalName='No Preference';
function openModal(id){ document.getElementById(id).style.display='flex'; }
function closeModal(id){ document.getElementById(id).style.display='none'; }
function openLivingModal(id,name){ pendingOrganId=id; pendingOrganName=name; goToStep(1); openModal('livingConsentModal'); }
function goToStep(n){ 
    document.querySelectorAll('.d-modal__step').forEach(s => s.classList.remove('active'));
    document.querySelectorAll('#livingConsentModal [id^="step"]').forEach(s => s.style.display = 'none'); // Reset legacy
    const el = document.getElementById('step' + n);
    if(el) {
        el.classList.add('active');
        el.style.display = 'block'; // Legacy fallback
    }
}
function handleStep1Next(){ if(!document.getElementById('medicalConsent').checked){ alert("Formal acceptance is required to proceed."); return; } goToStep(2); }
function handleStep2Next(){ if(!document.getElementById('bloodGroup').value){ alert("Clinical blood group verification required."); return; } updateHospitalList(); goToStep(3); }
function updateHospitalList(){ const list=document.getElementById('hospitalList'); list.innerHTML=''; (hospitalsByOrgan[pendingOrganId]||[]).forEach(h=>{ const card=document.createElement('div'); card.className='d-stat d-stat--interactive'; card.style.padding='1rem'; card.style.textAlign='center'; card.innerHTML=`<div style="font-size:1.1rem; font-weight:700; color:var(--blue-700);">${h.hospital_name}</div><div style="font-size:0.7rem; color:var(--g500); margin-top:4px;">Licensed Recovery Center</div>`; card.onclick=()=>selectHospital(h.hospital_id, h.hospital_name, card); list.appendChild(card); }); const noPref=document.createElement('div'); noPref.className='d-stat d-stat--interactive'; noPref.style.padding='1rem'; noPref.style.textAlign='center'; noPref.innerHTML=`<div style="font-size:1.1rem; font-weight:700; color:var(--g500);">No Preference</div><div style="font-size:0.7rem; color:var(--g400); margin-top:4px;">Allocation by Registry</div>`; noPref.onclick=()=>selectHospital(null, 'No Preference', noPref); list.appendChild(noPref); }
function selectHospital(id,name,el){ selectedHospitalId=id; selectedHospitalName=name; document.querySelectorAll('#hospitalList .d-stat').forEach(c=>c.style.borderColor='var(--g200)'); el.style.borderColor='var(--blue-500)'; el.style.background='var(--blue-50)'; }
function goToStep6(){ 
    document.getElementById('review_as_organ').textContent=pendingOrganName; 
    document.getElementById('revLivingW1').textContent = document.getElementById('cust1_name').value + ' (' + document.getElementById('cust1_nic').value + ')';
    document.getElementById('revLivingW2').textContent = document.getElementById('cust2_name').value + ' (' + document.getElementById('cust2_nic').value + ')';
    goToStep(6); 
}
function submitPledge(){ 
    document.getElementById('pledgeOrganId').value=pendingOrganId; 
    document.getElementById('pledgeHospitalId').value=selectedHospitalId||''; 
    document.getElementById('pledgeBloodGroup').value=document.getElementById('bloodGroup').value; 
    document.getElementById('pledgeConditions').value=document.getElementById('medications').value; 
    document.getElementById('p_cust1_name').value=document.getElementById('cust1_name').value; 
    document.getElementById('p_cust1_nic').value=document.getElementById('cust1_nic').value; 
    document.getElementById('p_cust2_name').value=document.getElementById('cust2_name').value; 
    document.getElementById('p_cust2_nic').value=document.getElementById('cust2_nic').value; 
    // Custodian fields are no longer collected for living donations
    document.getElementById('p_cust1_rel').value=''; 
    document.getElementById('p_cust1_phone').value='';
    document.getElementById('p_cust2_rel').value=''; 
    document.getElementById('p_cust2_phone').value='';
    document.getElementById('pledgeForm').submit(); 
}
function openAfterDeathModal(id,name){ document.querySelectorAll('.death-org-check').forEach(c=>c.checked=false); const target=document.getElementById('death_org_'+id); if(target) target.checked=true; goToDeathStep(1); openModal('afterDeathConsentModal'); }
function goToDeathStep(n){ for(let i=1;i<=4;i++){ const el=document.getElementById('deathStep'+i); if(el) el.style.display=(i===n)?'block':'none'; } if(n===4) updateDeathReview(); }
function updateDeathReview(){ 
    const sel=[...document.querySelectorAll('.death-org-check:checked')].map(c=>c.nextElementSibling.textContent.trim()); 
    document.getElementById('revDeathOrgans').textContent=sel.join(', ') || 'None'; 
    
    // Summary data
    const f = document.getElementById('afterDeathForm');
    document.getElementById('revDeathW1').textContent = f.w1_name.value + ' (' + f.w1_nic.value + ')';
    document.getElementById('revDeathW2').textContent = f.w2_name.value + ' (' + f.w2_nic.value + ')';
    document.getElementById('revDeathC1').textContent = f.c1_name.value + ' (' + f.c1_nic.value + ') - ' + f.c1_rel.value;
    document.getElementById('revDeathC2').textContent = f.c2_name.value + ' (' + f.c2_nic.value + ') - ' + f.c2_rel.value;
}
function submitAfterDeath(){ document.getElementById('afterDeathForm').submit(); }
function goToBodyStep(n){ 
    for(let i=1;i<=4;i++){ const el=document.getElementById('bodyStep'+i); if(el) el.style.display=(i===n)?'block':'none'; } 
    if(n===4) { 
        const s=document.getElementById('schoolSelect'); 
        document.getElementById('revBodySchool').textContent=s.options[s.selectedIndex].text; 
        
        const f = document.getElementById('bodyConsentForm');
        document.getElementById('revBodyW1').textContent = f.witness1_name.value + ' (' + f.witness1_nic.value + ')';
        document.getElementById('revBodyW2').textContent = f.witness2_name.value + ' (' + f.witness2_nic.value + ')';
        document.getElementById('revBodyC1').textContent = f.cust1_name.value + ' (' + f.cust1_nic.value + ')';
        document.getElementById('revBodyC2').textContent = f.cust2_name.value + ' (' + f.cust2_nic.value + ')';
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
