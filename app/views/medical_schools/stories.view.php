<?php
/**
 * Medical School Portal — Success / Tribute Stories
 */

$page_title    = 'Success Stories';
$active_page   = 'stories';

ob_start();
?>

<div class="cp-content-header">
    <div class="cp-content-header__content">
        <h1 class="cp-content-header__title"><i class="fas fa-star"></i> Success / Tribute Stories</h1>
        <p class="cp-content-header__subtitle">Manage entries for the institutional Wall of Remembrance and gratitude portal.</p>
    </div>
</div>

<div class="cp-content-body">
    <div class="cp-card" style="padding: 3rem; text-align: center;">
        <div class="cp-empty">
            <i class="fas fa-quote-left cp-empty__icon"></i>
            <h3>Digital Memorials</h3>
            <p>Collaborative stories submitted by medical students and families to honor the donors.</p>
            <div style="margin-top: 2rem;">
                <button class="cp-btn cp-btn--primary">
                    <i class="fas fa-edit"></i> Create Tribute
                </button>
            </div>
        </div>
    </div>
</div>

<?php
$page_content = ob_get_clean();
require dirname(__DIR__) . '/layouts/medical_schools.layout.php';
?>
