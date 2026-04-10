<?php
/**
 * Custodian Portal — Top Status Cards Partial
 * Reused on Dashboard to show core states
 */
$stat_donor_status = $death_declaration ? 'Deceased' : 'Alive';
$stat_case_status  = $activeCase ? 'Protocol Active' : 'Not Active';
$stat_inst_status  = $currentRequest ? str_replace('_', ' ', $currentRequest->status) : 'Pending Selection';
$donationTypeStr   = str_replace('_', ' ', $consent['donation_type'] ?? 'NONE');
?>
<div class="cp-stats-grid mb-4">
    <div class="cp-stat <?= $death_declaration ? 'cp-stat--danger' : 'cp-stat--success' ?>">
        <div class="cp-stat__icon"><i class="fas <?= $death_declaration ? 'fa-bed-pulse' : 'fa-heart-pulse' ?>"></i></div>
        <div class="cp-stat__label">Donor Status</div>
        <div class="cp-stat__value"><?= $stat_donor_status ?></div>
    </div>

    <div class="cp-stat <?= ($consent['donation_type'] ?? 'NONE') !== 'NONE' ? 'cp-stat--info' : '' ?>">
        <div class="cp-stat__icon"><i class="fas fa-file-signature"></i></div>
        <div class="cp-stat__label">Active Consent</div>
        <div class="cp-stat__value"><?= htmlspecialchars($donationTypeStr) ?></div>
    </div>

    <div class="cp-stat">
        <div class="cp-stat__icon"><i class="fas fa-briefcase-medical"></i></div>
        <div class="cp-stat__label">Case Status</div>
        <div class="cp-stat__value"><?= $stat_case_status ?></div>
    </div>
    
    <div class="cp-stat">
        <div class="cp-stat__icon"><i class="fas fa-hospital"></i></div>
        <div class="cp-stat__label">Institution Status</div>
        <div class="cp-stat__value"><?= $stat_inst_status ?></div>
    </div>
</div>
