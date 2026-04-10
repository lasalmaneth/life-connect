<?php
/**
 * Custodian Portal — Activity Timeline Partial
 */
$timeline = [];

if (!empty($death_declaration)) {
    $timeline[] = ['date' => $death_declaration->created_at ?? null, 'label' => 'Death Declared', 'icon' => 'fa-heart-crack'];
}

if (!empty($currentInstRequest)) {
    $timeline[] = ['date' => $currentInstRequest->submission_date ?? $currentInstRequest->created_at ?? null, 'label' => 'Institution Request Sent', 'icon' => 'fa-paper-plane'];
    $timeline[] = ['date' => $currentInstRequest->response_date ?? null, 'label' => 'Institution Request Resolved', 'icon' => 'fa-check-to-slot'];
}

$timeline = array_filter($timeline, fn($t) => !empty($t['date']));
usort($timeline, fn($a, $b) => strtotime($b['date']) - strtotime($a['date']));
?>

<div class="cp-section-card h-100">
    <div class="cp-section-card__header">
        <div class="cp-section-card__title"><i class="fas fa-history cp-text-blue-600"></i> Recent Activity</div>
    </div>
    <div class="cp-section-card__body">
        <?php if (empty($timeline)): ?>
            <p class="cp-text-g500 text-center py-5">No recent activity detected.</p>
        <?php else: ?>
            <div class="cp-timeline">
                <?php foreach ($timeline as $t): ?>
                    <div class="cp-timeline-item">
                        <div class="cp-timeline-icon">
                            <i class="fas <?= $t['icon'] ?> fa-sm"></i>
                        </div>
                        <div class="cp-timeline-info">
                            <div class="cp-timeline-title"><?= htmlspecialchars($t['label']) ?></div>
                            <div class="cp-timeline-date"><?= date('M j, Y — H:i', strtotime($t['date'])) ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
