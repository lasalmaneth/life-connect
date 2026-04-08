<?php
/**
 * Donor Portal — Test Results Page
 */

include __DIR__ . '/inc/header.view.php';
include __DIR__ . '/inc/sidebar.view.php';
?>

<main class="d-content">
    <div class="d-content__header" style="display: flex; justify-content: space-between; align-items: flex-end;">
        <div>
            <h2><i class="fas fa-vial text-accent"></i> My Medical Test Results</h2>
            <p>View and manage your recent lab reports from verified LifeConnect hospital partners.</p>
        </div>
        <div>
            <span class="d-status d-status--success"><i class="fas fa-shield-check"></i> EHR Synced</span>
        </div>
    </div>
    
    <div class="d-content__body">
        
        <div class="d-dashboard-grid" style="grid-template-columns: 1fr; gap: 2rem;">
            
            <!-- Widget 1: Test Results List -->
            <div class="d-widget">
                <div class="d-widget__header">
                    <div class="d-widget__title"><i class="fas fa-microscope text-accent"></i> Laboratory Reports</div>
                </div>
                <div class="d-widget__body">
                    <?php if (!empty($test_results)): foreach ($test_results as $test): ?>
                        <div style="border: 1px solid var(--g200); border-radius: var(--r); padding: 1.25rem; margin-bottom: 1rem; background: var(--white); display: flex; justify-content: space-between; align-items: flex-start; transition: all 0.2s ease;" onmouseover="this.style.borderColor='var(--blue-300)'; this.style.boxShadow='0 4px 12px rgba(0,91,170,0.05)';" onmouseout="this.style.borderColor='var(--g200)'; this.style.boxShadow='none';">
                            <div>
                                <div style="font-weight: 700; color: var(--blue-800); font-size: 1rem; margin-bottom: 0.4rem; display: flex; align-items: center; gap: 0.5rem;">
                                    <i class="fas fa-file-medical text-accent"></i> <?= htmlspecialchars($test->test_name) ?>
                                </div>
                                <div style="font-size: 0.8rem; color: var(--g500); display: flex; align-items: center; gap: 1rem; margin-bottom: 0.75rem;">
                                    <span><i class="far fa-calendar-alt"></i> <?= date('M d, Y', strtotime($test->test_date)) ?></span>
                                    <span><i class="far fa-hospital"></i> <?= htmlspecialchars($test->hospital_name ?? 'LifeConnect Lab') ?></span>
                                </div>
                                
                                <div style="font-size: 0.85rem; padding: 0.5rem 0.75rem; background: var(--g50); border-radius: 6px; border-left: 3px solid var(--success); display: inline-block;">
                                    <span style="color: var(--g600);">Result:</span> <span style="font-weight: 700; color: var(--success); margin-left: 0.25rem;"><?= htmlspecialchars($test->result_value) ?></span>
                                </div>
                            </div>
                            <div>
                                <?php if (!empty($test->document_path)): ?>
                                    <a href="<?= ROOT ?>/donor/downloadPdf?type=lab_report&id=<?= $test->id ?>" class="d-btn d-btn--sm d-btn--outline"><i class="fas fa-download"></i> PDF Report</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; else: ?>
                        <div style="text-align: center; padding: 3rem 1rem; border: 1px dashed var(--g300); border-radius: var(--r); background: var(--g50);">
                            <i class="fas fa-microscope" style="font-size: 3rem; color: var(--g300); margin-bottom: 1rem; display: block;"></i>
                            <h4 style="color: var(--slate); margin-bottom: 0.5rem;">No Test Results</h4>
                            <p style="color: var(--g500); font-size: 0.9rem;">We couldn't find any recent medical reports in your electronic health record.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</main>

<?php include __DIR__ . '/inc/footer.view.php'; ?>
