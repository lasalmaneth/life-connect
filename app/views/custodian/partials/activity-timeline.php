<?php
/**
 * Custodian Portal — Activity Timeline Partial
 * Injected: $timeline (array of events)
 */
// Ensure we have a valid array
$events = $timeline ?? [];

// Sort newest first
usort($events, fn($a, $b) => strtotime($b['time']) - strtotime($a['time']));

// Icon mapping
$iconMap = [
    'death'       => 'fa-heart-crack cp-text-red-500',
    'legal'       => 'fa-shield-check cp-text-blue-600',
    'institution' => 'fa-building-columns cp-text-g400',
    'submission'  => 'fa-paper-plane cp-text-blue-500',
    'response'    => 'fa-check-to-slot cp-text-success',
    'document'    => 'fa-file-arrow-up cp-text-g500'
];
?>

<div class="cp-section-card h-100">
    <div class="cp-section-card__header">
        <div class="cp-section-card__title">
            <i class="fas fa-history cp-text-blue-600"></i> 
            <?= ($active_page === 'dashboard') ? 'Recent Activity' : 'Full Activity History' ?>
        </div>
    </div>
    <div class="cp-section-card__body">
        <?php if (empty($events)): ?>
            <p class="cp-text-g500 text-center py-5">No recent activity detected.</p>
        <?php else: ?>
            <div class="cp-timeline">
                <?php foreach ($events as $t): ?>
                    <div class="cp-timeline-item">
                        <div class="cp-timeline-icon">
                            <i class="fas <?= $iconMap[$t['type']] ?? 'fa-circle' ?> fa-sm"></i>
                        </div>
                        <div class="cp-timeline-info">
                            <div class="cp-timeline-title">
                                <?= htmlspecialchars($t['event']) ?>
                                <?php if (!empty($t['actor'])): ?>
                                    <span class="cp-text-xs cp-text-g400 fw-normal ml-1">by <?= htmlspecialchars($t['actor']) ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="cp-timeline-date"><?= date('M j, Y — H:i', strtotime($t['time'])) ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <?php if ($active_page === 'dashboard'): ?>
                <div class="mt-4 pt-3 border-top text-center">
                    <a href="<?= ROOT ?>/custodian/activity-history" class="cp-text-sm cp-font-bold cp-text-blue-600">
                        View All History <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
