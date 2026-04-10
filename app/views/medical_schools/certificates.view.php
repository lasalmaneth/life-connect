<?php
/**
 * Medical School Portal — Donation Certificates
 */

$page_title    = 'Donation Certificates';
$active_page   = 'certificates';

ob_start();
?>

<div class="cp-content-header">
    <div class="cp-content-header__content">
        <h1 class="cp-content-header__title"><i class="fas fa-certificate"></i> Donation Certificates</h1>
        <p class="cp-content-header__subtitle">Official institutional certificates honoring the anatomical gift.</p>
    </div>
</div>

<div class="cp-content-body">
    <div class="cp-card" style="padding: 3rem; text-align: center;">
        <div class="cp-empty">
            <i class="fas fa-file-contract cp-empty__icon"></i>
            <h3>Certificate Registry</h3>
            <p>Generate and track official certificates issued to the families of deceased donors.</p>
            <div style="margin-top: 2rem;">
                <button class="cp-btn cp-btn--secondary">
                    <i class="fas fa-print"></i> Batch Print Pending
                </button>
            </div>
        </div>
    </div>
</div>

<?php
$page_content = ob_get_clean();
require dirname(__DIR__) . '/layouts/medical_schools.layout.php';
?>
