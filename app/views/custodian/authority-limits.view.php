<?php
/**
 * Custodian Portal — Authority Limits View
 * Route: GET /custodian/authority-limits
 * Active page key: authority-limits
 */

$page_icon     = 'fa-shield-halved';
$page_heading  = 'Authority Limits';
$page_subtitle = 'What custodians are authorized to do — and what requires administrative or legal intervention.';

ob_start();
?>

<!-- Page Header -->
<?php include __DIR__ . '/partials/page-header.php'; ?>

<div class="cp-content__body">

    <div class="cp-notice cp-notice--info">
        <i class="fas fa-circle-info"></i>
        <div>
            <strong>Role Clarification</strong>
            <p>Custodians are family members or legal representatives entrusted with coordinating the donation process. They do not have administrative access. All data changes are managed by LifeConnect staff.</p>
        </div>
    </div>

    <div class="cp-grid-2">

        <!-- ── What Custodians CAN Do ─────────────────────────────────── -->
        <div class="cp-section-card">
            <div class="cp-section-card__header">
                <div class="cp-section-card__title" style="color:var(--success);">
                    <i class="fas fa-circle-check" style="color:var(--success);"></i> Custodians Can
                </div>
            </div>
            <div class="cp-section-card__body">
                <?php
                $can_do = [
                    ['icon' => 'fa-eye',             'text' => 'View the donor\'s registered consent'],
                    ['icon' => 'fa-eye',             'text' => 'View the donor\'s profile information'],
                    ['icon' => 'fa-heart-pulse',     'text' => 'Declare the death of the donor'],
                    ['icon' => 'fa-gavel',           'text' => 'File a legal response (confirm or object)'],
                    ['icon' => 'fa-cloud-arrow-up',  'text' => 'Upload required case documents'],
                    ['icon' => 'fa-notes-medical',   'text' => 'Submit the cadaver data sheet'],
                    ['icon' => 'fa-network-wired',   'text' => 'Coordinate with institutions'],
                    ['icon' => 'fa-stream',          'text' => 'View the full case timeline'],
                    ['icon' => 'fa-certificate',     'text' => 'Download issued certificates'],
                    ['icon' => 'fa-phone',           'text' => 'Update their own contact information'],
                ];
                foreach ($can_do as $item): ?>
                    <div class="cp-info-row">
                        <span style="display:flex; align-items:center; gap:.6rem; font-size:.85rem; color:var(--slate);">
                            <i class="fas <?= $item['icon'] ?>" style="color:var(--success); width:16px; text-align:center;"></i>
                            <?= htmlspecialchars($item['text']) ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- ── What Custodians CANNOT Do ─────────────────────────────── -->
        <div class="cp-section-card">
            <div class="cp-section-card__header">
                <div class="cp-section-card__title" style="color:var(--danger);">
                    <i class="fas fa-ban" style="color:var(--danger);"></i> Custodians Cannot
                </div>
            </div>
            <div class="cp-section-card__body">
                <?php
                $cannot_do = [
                    ['icon' => 'fa-pen',            'text' => 'Edit the donor\'s registered consent'],
                    ['icon' => 'fa-pen',            'text' => 'Edit the donor\'s personal profile'],
                    ['icon' => 'fa-users-gear',     'text' => 'Add or remove other custodians'],
                    ['icon' => 'fa-hospital',       'text' => 'Approve or reject institutions directly'],
                    ['icon' => 'fa-key',            'text' => 'Access admin or medical school panels'],
                    ['icon' => 'fa-rotate-left',    'text' => 'Reverse a submitted death declaration'],
                    ['icon' => 'fa-rotate-left',    'text' => 'Reverse a submitted legal response'],
                    ['icon' => 'fa-file-shield',    'text' => 'Issue certificates (admin only)'],
                    ['icon' => 'fa-database',       'text' => 'Access raw database records'],
                    ['icon' => 'fa-user-gear',      'text' => 'Change another user\'s account settings'],
                ];
                foreach ($cannot_do as $item): ?>
                    <div class="cp-info-row">
                        <span style="display:flex; align-items:center; gap:.6rem; font-size:.85rem; color:var(--slate);">
                            <i class="fas <?= $item['icon'] ?>" style="color:var(--danger); width:16px; text-align:center;"></i>
                            <?= htmlspecialchars($item['text']) ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>

    <!-- ── Escalation Path ────────────────────────────────────────────── -->
    <?php
    $section_title = 'Need Help or Escalation?';
    $section_icon  = 'fa-life-ring';
    ob_start();
    ?>
    <div class="cp-notice cp-notice--info" style="margin-bottom:0;">
        <i class="fas fa-circle-info"></i>
        <div>
            <strong>Contact LifeConnect Administration</strong>
            <p>For any actions outside your authority, please contact the LifeConnect Administration team directly. They can assist with consent modifications, dispute resolution, and institutional coordination issues.</p>
        </div>
    </div>
    <?php
    $section_content = ob_get_clean();
    $section_action  = null;
    include __DIR__ . '/partials/section-card.php';
    ?>

</div><!-- /.cp-content__body -->

<?php
$page_content = ob_get_clean();
require dirname(__DIR__) . '/layouts/custodian.layout.php';
?>
