<?php
/**
 * Medical School Portal — Reports & Analytics
 */

$page_title    = 'Reports';
$active_page   = 'reports';

ob_start();
?>

<div class="cp-content-header">
    <div class="cp-content-header__content">
        <h1 class="cp-content-header__title"><i class="fas fa-chart-bar"></i> Multi-Stage Analytics</h1>
        <p class="cp-content-header__subtitle">Institution-level reports on consent trends, mortality responses, and anatomical usage.</p>
    </div>
</div>

<div class="cp-content-body">
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; margin-bottom: 2rem;">
        <div class="cp-report-card">
            <i class="fas fa-file-invoice cp-report-card__icon"></i>
            <div class="cp-report-card__title">Annual Consent Review</div>
            <p class="cp-report-card__desc">Detailed breakdown of pre-death registry metrics for this year.</p>
            <button class="cp-btn cp-btn--secondary cp-btn--sm">Generate PDF</button>
        </div>
        <div class="cp-report-card">
            <i class="fas fa-user-check cp-report-card__icon"></i>
            <div class="cp-report-card__title">Donation conversion Rate</div>
            <p class="cp-report-card__desc">Analytics comparing initial requests vs successful intakes.</p>
            <button class="cp-btn cp-btn--secondary cp-btn--sm">Generate PDF</button>
        </div>
        <div class="cp-report-card">
            <i class="fas fa-clipboard-check cp-report-card__icon"></i>
            <div class="cp-report-card__title">Intake Quota Audit</div>
            <p class="cp-report-card__desc">Current institutional capacity vs actual anatomical arrival.</p>
            <button class="cp-btn cp-btn--secondary cp-btn--sm">Generate PDF</button>
        </div>
    </div>
</div>



<?php
$page_content = ob_get_clean();
require dirname(__DIR__) . '/layouts/medical_schools.layout.php';
?>
