<?php
/**
 * Medical School Portal — Appreciation Letters
 */

$page_title    = 'Appreciation Letters';
$active_page   = 'appreciation';

ob_start();
?>

<div class="cp-content-header">
    <div class="cp-content-header__content">
        <h1 class="cp-content-header__title"><i class="fas fa-envelope-open-text"></i> Appreciation Letters</h1>
        <p class="cp-content-header__subtitle">Manage and issue formal gratitude letters to donor families.</p>
    </div>
</div>

<div class="cp-content-body">
    <div class="cp-card" style="padding: 3rem; text-align: center;">
        <div class="cp-empty">
            <i class="fas fa-stamp cp-empty__icon"></i>
            <h3>Recognition Module</h3>
            <p>This section allows you to generate and send formal appreciation letters to the next-of-kin for completed donations.</p>
            <div style="margin-top: 2rem;">
                <button class="cp-btn cp-btn--primary">
                    <i class="fas fa-plus"></i> Generate New Letter
                </button>
            </div>
        </div>
    </div>
</div>

<?php
$page_content = ob_get_clean();
require dirname(__DIR__) . '/layouts/medical_schools.layout.php';
?>
