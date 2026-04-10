<?php
/**
 * Medical School Portal — Usage Logs
 */

$page_title    = 'Usage Logs';
$active_page   = 'usage-logs';

ob_start();
?>

<div class="cp-content-header">
    <div class="cp-content-header__content">
        <h1 class="cp-content-header__title"><i class="fas fa-clipboard-list"></i> Usage Logs</h1>
        <p class="cp-content-header__subtitle">Internal anatomical usage tracking and institutional record keeping.</p>
    </div>
</div>

<div class="cp-content-body">
    <div class="cp-card" style="padding: 3rem; text-align: center;">
        <div class="cp-empty">
            <i class="fas fa-barcode cp-empty__icon"></i>
            <h3>Inventory Tracking</h3>
            <p>Track the anatomical usage of donors for educational and research purposes within your institution.</p>
            <div style="margin-top: 2rem;">
                <button class="cp-btn cp-btn--primary">
                    <i class="fas fa-plus"></i> Add Usage Entry
                </button>
            </div>
        </div>
    </div>
</div>

<?php
$page_content = ob_get_clean();
require dirname(__DIR__) . '/layouts/medical_schools.layout.php';
?>
