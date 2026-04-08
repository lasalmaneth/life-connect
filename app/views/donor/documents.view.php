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
                
                    <a href="<?= ROOT ?>/donor/downloadPdf?type=donor_card" class="d-btn d-btn--primary" style="justify-content: center; width: 100%;"><i class="fas fa-file-pdf"></i> Download PDF Card</a>
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
                            <p style="font-size: 0.8rem; color: var(--g500); margin-bottom: 1rem;">Need a fresh copy for a new pledge?</p>
                            <a href="<?= ROOT ?>/donor/downloadPdf?type=organ_consent" class="d-btn d-btn--outline" style="width: 100%; justify-content: center;"><i class="fas fa-file-download"></i> Download Blank Form</a>
                        </div>
                    <?php else: ?>
                        <div style="text-align: center; padding: 1.5rem 0;">
                            <div style="width: 60px; height: 60px; background: var(--g50); color: var(--g300); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; font-size: 1.5rem;">
                                <i class="fas fa-file-pdf"></i>
                            </div>
                            <h4 style="color: var(--slate); font-size: 0.95rem; margin-bottom: 0.5rem;">No Signed Forms Uploaded</h4>
                            <p style="font-size: 0.85rem; color: var(--g500); margin-bottom: 1.5rem;">Please download the consent form, sign it with witnesses, and upload it via the "My Donations" page.</p>
                            <a href="<?= ROOT ?>/donor/downloadPdf?type=organ_consent" class="d-btn d-btn--outline" style="width: 100%; justify-content: center;"><i class="fas fa-download"></i> Download Blank Form</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Donation Certificate -->
            <div class="d-widget">
                <div class="d-widget__header">
                    <div class="d-widget__title"><i class="fas fa-award text-accent"></i> Recognition</div>
                </div>
                <div class="d-widget__body" style="display: flex; flex-direction: column; justify-content: center; min-height: 200px;">
                    <div style="text-align: center; margin-bottom: 1.5rem;">
                        <div style="width: 64px; height: 64px; border-radius: 50%; background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center; font-size: 2rem; margin: 0 auto 1rem;">
                            <i class="fas fa-certificate"></i>
                        </div>
                        <h3 style="color: var(--slate); font-size: 1.1rem; margin-bottom: 0.5rem;">Donation Certificate</h3>
                        <p style="color: var(--g500); font-size: 0.9rem;">A certificate of appreciation for your noble commitment to saving lives.</p>
                    </div>
                    <a href="<?= ROOT ?>/donor/downloadPdf?type=certificate" class="d-btn d-btn--outline" style="justify-content: center; border-color: #fbbf24; color: #b45309;"><i class="fas fa-stamp"></i> Get Certificate</a>
                </div>
            </div>

        </div>
    </div>
</main>


<script>
    function openModal(id) {
        document.getElementById(id).classList.add('active');
    }
    function closeModal(id) {
        document.getElementById(id).classList.remove('active');
    }
</script>

<?php include __DIR__ . '/inc/footer.view.php'; ?>

