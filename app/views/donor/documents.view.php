<?php
/**
 * Donor Portal — Documents Page
 */

include __DIR__ . '/inc/header.view.php';
include __DIR__ . '/inc/sidebar.view.php';
?>

<main class="d-content">
    <div class="d-content__header">
        <h2><i class="fas fa-file-signature text-accent"></i> Consent & Documents</h2>
        <p>View and download your organ donation documents and track body usage status.</p>
    </div>
    
    <div class="d-content__body">
        
        <div class="d-dashboard-grid">
            
            <!-- Donor ID Card -->
            <div class="d-widget">
                <div class="d-widget__header">
                    <div class="d-widget__title"><i class="fas fa-id-badge text-accent"></i> Donor ID Card</div>
                </div>
                <div class="d-widget__body" style="display: flex; flex-direction: column;">
                    <?php if (($stats['total'] ?? 0) > 0): ?>
                        <div style="flex: 1; padding: 1.5rem; background: linear-gradient(135deg, var(--blue-900), var(--blue-700)); border-radius: 12px; color: white; box-shadow: 0 10px 25px rgba(10, 22, 40, 0.2); margin-bottom: 1.5rem; position: relative; overflow: hidden;">
                            <div style="position: absolute; right: -20px; top: -20px; font-size: 8rem; opacity: 0.1; transform: rotate(-15deg);"><i class="fas fa-heartbeat"></i></div>
                            
                            <div style="font-size: 0.75rem; letter-spacing: 2px; color: var(--blue-200); margin-bottom: 1rem; font-weight: 600;">LIFECONNECT ORGAN DONOR</div>
                            <div style="font-size: 1.25rem; font-weight: 700; margin-bottom: 0.25rem;"><?= $donor_full_name ?></div>
                            <div style="font-size: 0.85rem; color: var(--blue-100); margin-bottom: 1.5rem; font-family: monospace;">ID: <?= $donor_id_display ?></div>
                            
                            <div style="display: flex; gap: 2rem; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 1rem;">
                                <div>
                                    <div style="font-size: 0.65rem; color: var(--blue-200); margin-bottom: 0.2rem;">BLOOD TYPE</div>
                                    <div style="font-weight: 700; font-size: 1rem; color: #fca5a5;"><?= htmlspecialchars($donor_data['blood_group'] ?? 'N/A') ?></div>
                                </div>
                                <div>
                                    <div style="font-size: 0.65rem; color: var(--blue-200); margin-bottom: 0.2rem;">STATUS</div>
                                    <div style="font-weight: 700; font-size: 1rem; color: #86efac;">VERIFIED <i class="fas fa-check-circle" style="font-size: 0.8rem;"></i></div>
                                </div>
                            </div>
                        </div>
                    
                        <a href="<?= ROOT ?>/donor/download-pdf?type=donor_card" target="_blank" class="d-btn d-btn--primary" style="justify-content: center; width: 100%;"><i class="fas fa-id-badge"></i> Download Official Digital Card</a>
                    <?php else: ?>
                        <div style="text-align: center; padding: 2rem 1rem;">
                            <div style="width: 64px; height: 64px; background: var(--g50); color: var(--g300); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; font-size: 1.75rem;">
                                <i class="fas fa-id-card"></i>
                            </div>
                            <h4 style="color: var(--slate); font-size: 1rem; margin-bottom: 0.75rem;">ID Card Not Available</h4>
                            <p style="font-size: 0.85rem; color: var(--g500); line-height: 1.5; margin-bottom: 1.5rem;">The Digital Donor ID Card is exclusively for donors with active pledges. This card serves as your official identification during medical procedures.</p>
                            <a href="<?= ROOT ?>/donor/donations" class="d-btn d-btn--outline d-btn--sm" style="justify-content: center; width: 100%;">Pledge Now to Unlock Card</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Organ Donation Consent -->
            <div class="d-widget">
                <div class="d-widget__header">
                    <div class="d-widget__title"><i class="fas fa-file-download text-accent"></i> Organ Donation Consent</div>
                </div>
                <div class="d-widget__body">
                    <?php if (!empty($uploaded_pledges)): ?>
                        <div style="margin-bottom: 1.5rem;">
                            <p style="font-size: 0.85rem; color: var(--g500); margin-bottom: 1rem;">The following signed consent forms have been successfully uploaded and linked to your profile:</p>
                            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                                <?php foreach ($uploaded_pledges as $pledge): ?>
                                    <div style="display: flex; justify-content: space-between; align-items: center; background: var(--blue-50); padding: 0.75rem 1rem; border-radius: 8px; border: 1px solid var(--blue-100);">
                                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                                            <i class="fas fa-file-pdf" style="color: #ef4444; font-size: 1.2rem;"></i>
                                            <div>
                                                <div style="font-weight: 600; color: var(--blue-900); font-size: 0.9rem;"><?= htmlspecialchars($pledge->organ_name) ?> Consent</div>
                                                <div style="font-size: 0.75rem; color: var(--blue-600);">Uploaded: <?= date('M d, Y', strtotime($pledge->pledge_date)) ?></div>
                                            </div>
                                        </div>
                                        <a href="<?= ROOT ?>/<?= htmlspecialchars($pledge->signed_form_path) ?>" target="_blank" class="d-btn d-btn--sm d-btn--outline" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div style="border-top: 1px dashed var(--g200); padding-top: 1.5rem; text-align: center;">
                            <p style="font-size: 0.8rem; color: var(--g500); margin-bottom: 0;">Please follow the pledge process to generate new documents.</p>
                        </div>
                    <?php else: ?>
                        <div style="text-align: center; padding: 1.5rem 0;">
                            <div style="width: 60px; height: 60px; background: var(--g50); color: var(--g300); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; font-size: 1.5rem;">
                                <i class="fas fa-file-pdf"></i>
                            </div>
                            <h4 style="color: var(--slate); font-size: 0.95rem; margin-bottom: 0.5rem;">No Signed Forms Uploaded</h4>
                            <p style="font-size: 0.85rem; color: var(--g500); margin-bottom: 0.5rem;">Once you initiate a pledge, you will be able to download the custom consent form for signing.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Donation Certificate -->
            <div class="d-widget">
                <div class="d-widget__header">
                    <div class="d-widget__title"><i class="fas fa-award text-accent"></i> Recognition</div>
                </div>
                <div class="d-widget__body">
                    <?php if (!empty($completed_pledges)): ?>
                        <div style="margin-bottom: 1.5rem;">
                            <div style="text-align: center; margin-bottom: 1.5rem;">
                                <div style="width: 56px; height: 56px; border-radius: 50%; background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin: 0 auto 0.75rem;">
                                    <i class="fas fa-certificate"></i>
                                </div>
                                <h3 style="color: var(--slate); font-size: 1.05rem; margin-bottom: 0.25rem;">Donation Certificates</h3>
                                <p style="color: var(--g500); font-size: 0.85rem;">Download certificates of appreciation for your completed donations.</p>
                            </div>

                            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                                <?php foreach ($completed_pledges as $pledge): ?>
                                    <div style="display: flex; justify-content: space-between; align-items: center; background: var(--blue-50); padding: 0.75rem 1rem; border-radius: 8px; border: 1px solid var(--blue-100);">
                                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                                            <i class="fas fa-certificate" style="color: var(--blue-600); font-size: 1.1rem;"></i>
                                            <div>
                                                <div style="font-weight: 600; color: var(--blue-800); font-size: 0.9rem;"><?= htmlspecialchars($pledge->organ_name) ?> Certificate</div>
                                                <div style="font-size: 0.75rem; color: var(--g500);">Issued: <?= date('M d, Y', strtotime($pledge->pledge_date)) ?></div>
                                            </div>
                                        </div>
                                        <button type="button" onclick="viewCertificate('<?= ROOT ?>/donor/download-pdf?type=certificate&pledge_id=<?= $pledge->id ?>')" class="d-btn d-btn--sm d-btn--outline" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; border-color: var(--blue-600); color: var(--blue-600);">
                                            <i class="fas fa-eye"></i> View
                                        </button>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <div style="text-align: center; padding: 2rem 1rem;">
                            <div style="width: 64px; height: 64px; border-radius: 50%; background: #f3f4f6; color: #9ca3af; display: flex; align-items: center; justify-content: center; font-size: 2rem; margin: 0 auto 1.25rem;">
                                <i class="fas fa-award"></i>
                            </div>
                            <h3 style="color: var(--slate); font-size: 1.1rem; margin-bottom: 0.5rem;">No Certificates Yet</h3>
                            <p style="color: var(--g500); font-size: 0.9rem; line-height: 1.5;">Certificates of appreciation are awarded once a donation pledge is successfully completed.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</main>


<script>
    function openModal(id) {
        document.getElementById(id).classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    function closeModal(id) {
        document.getElementById(id).classList.remove('active');
        document.body.style.overflow = 'auto';
        if (id === 'certificateModal') {
            document.getElementById('certificateFrame').src = '';
        }
    }
    function viewCertificate(url) {
        document.getElementById('certificateFrame').src = url;
        openModal('certificateModal');
    }

    // Close on outside click
    window.onclick = function(event) {
        if (event.target.classList.contains('d-modal')) {
            closeModal(event.target.id);
        }
    }
</script>

<!-- Certificate Viewer Modal -->
<div id="certificateModal" class="d-modal">
    <div class="d-modal__body" style="max-width: 95%; width: 1150px; height: 95vh; padding: 0; display: flex; flex-direction: column; overflow: hidden; border: none;">
        <div class="d-modal__header" style="background: var(--blue-900); color: white; padding: 1.25rem 2rem; margin-bottom: 0; border-radius: 12px 12px 0 0; display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 32px; height: 32px; background: rgba(255,255,255,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #cbd5e0;">
                    <i class="fas fa-award"></i>
                </div>
                <div>
                    <h3 style="color: white; margin: 0; font-size: 1.1rem; font-weight: 700;">Certificate of Appreciation</h3>
                    <div style="font-size: 0.75rem; color: var(--blue-200);">Printable Life-Saving Recognition</div>
                </div>
            </div>
            <button class="d-modal__close" onclick="closeModal('certificateModal')" style="color: white; opacity: 0.8; font-size: 1.5rem; background: none; border: none; cursor: pointer;">&times;</button>
        </div>
        <div style="flex: 1; overflow: hidden; background: #525659; position: relative;">
            <iframe id="certificateFrame" src="" style="width: 100%; height: 100%; border: none;"></iframe>
        </div>
        <div style="padding: 1rem 2rem; background: var(--g50); border-top: 1px solid var(--g200); display: flex; justify-content: flex-end; gap: 12px;">
            <button class="d-btn d-btn--outline d-btn--sm" onclick="closeModal('certificateModal')">Close Preview</button>
            <button class="d-btn d-btn--primary d-btn--sm" onclick="document.getElementById('certificateFrame').contentWindow.print()">
                <i class="fas fa-print"></i> Print Certificate
            </button>
        </div>
    </div>
</div>

<?php include __DIR__ . '/inc/footer.view.php'; ?>

