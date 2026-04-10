<?php
/**
 * Medical School Portal — Archived Records
 */

$page_title    = 'Archived Records';
$active_page   = 'archived';

ob_start();
?>

<div class="cp-content-header">
    <div class="cp-content-header__content">
        <h1 class="cp-content-header__title"><i class="fas fa-archive"></i> Archived Records</h1>
        <p class="cp-content-header__subtitle">Access historical data of completed cases and anatomical disposal records.</p>
    </div>
</div>

<div class="cp-content-body">
    <div class="cp-table-container">
        <table class="cp-table">
            <thead>
                <tr>
                    <th>Donor Name</th>
                    <th>NIC Number</th>
                    <th>Completed Date</th>
                    <th>Final Disposal Method</th>
                    <th style="text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($records)): ?>
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 3rem;">
                            <div class="cp-empty">
                                <i class="fas fa-file-archive cp-empty__icon"></i>
                                <p>No records found in the archive.</p>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($records as $record): ?>
                        <tr>
                            <td><?= htmlspecialchars($record->first_name . ' ' . $record->last_name) ?></td>
                            <td><?= htmlspecialchars($record->nic_number) ?></td>
                            <td><?= date('M d, Y', strtotime($record->archive_date)) ?></td>
                            <td><span class="cp-status-badge cp-status-badge--secondary"><?= htmlspecialchars($record->reason ?? 'Cremation') ?></span></td>
                            <td style="text-align: right;">
                                <button class="cp-btn cp-btn--secondary cp-btn--sm">
                                    <i class="fas fa-file-alt"></i> View Case History
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
$page_content = ob_get_clean();
require dirname(__DIR__) . '/layouts/medical_schools.layout.php';
?>
