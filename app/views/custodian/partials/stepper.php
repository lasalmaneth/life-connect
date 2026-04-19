<?php
/**
 * Custodian Portal — Unified Workflow Stepper
 * Dynamic steps based on track (BODY vs ORGAN)
 */
if (empty($stepperData['steps'])) return;

$current = $stepperData['current'];
$steps   = $stepperData['steps'];
$total   = count($steps);
$fillPct = ($total > 1) ? round(($current - 1) / ($total - 1) * 100) : 0;
?>

<div class="cp-stepper-container mb-4">
    <div class="cp-stepper">
        <div class="cp-stepper__track">
            <div class="cp-stepper__fill" style="width: <?= $fillPct ?>%"></div>
        </div>
        
        <?php foreach ($steps as $idx => $s): ?>
            <?php 
                $num = $idx + 1;
                $statusClass = '';
                if ($num < $current) $statusClass = 'is-done';
                elseif ($num === $current) $statusClass = 'is-active';
                else $statusClass = 'is-pending';
            ?>
            <div class="cp-stepper__step <?= $statusClass ?>">
                <div class="cp-stepper__circle">
                    <?php if ($statusClass === 'is-done'): ?>
                        <i class="fas fa-check"></i>
                    <?php else: ?>
                        <span><?= $num ?></span>
                    <?php endif; ?>
                </div>
                <div class="cp-stepper__label">
                    <i class="fas <?= $s['i'] ?> mr-1"></i>
                    <?= htmlspecialchars($s['l']) ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<style>
.cp-stepper-container {
    background: white;
    padding: 24px 30px;
    border-radius: 16px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    border: 1px solid #f1f5f9;
}

.cp-stepper {
    display: flex;
    justify-content: space-between;
    position: relative;
    max-width: 900px;
    margin: 0 auto;
}

.cp-stepper__track {
    position: absolute;
    top: 18px;
    left: 40px;
    right: 40px;
    height: 4px;
    background: #f1f5f9;
    z-index: 1;
    border-radius: 10px;
}

.cp-stepper__fill {
    height: 100%;
    background: linear-gradient(90deg, #3b82f6 0%, #2563eb 100%);
    transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    border-radius: 10px;
}

.cp-stepper__step {
    position: relative;
    z-index: 2;
    display: flex;
    flex-direction: column;
    align-items: center;
    flex: 1;
}

.cp-stepper__circle {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: #f8fafc;
    border: 3px solid #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    font-size: 0.9rem;
    color: #94a3b8;
    margin-bottom: 10px;
    transition: all 0.3s ease;
}

.cp-stepper__label {
    font-size: 0.7rem;
    font-weight: 800;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    transition: all 0.3s ease;
    white-space: nowrap;
}

/* Active State */
.cp-stepper__step.is-active .cp-stepper__circle {
    background: #eff6ff;
    border-color: #3b82f6;
    color: #2563eb;
    box-shadow: 0 0 0 5px rgba(59, 130, 246, 0.1);
    transform: scale(1.1);
}
.cp-stepper__step.is-active .cp-stepper__label {
    color: #1e40af;
}

/* Done State */
.cp-stepper__step.is-done .cp-stepper__circle {
    background: #2563eb;
    border-color: #2563eb;
    color: white;
}
.cp-stepper__step.is-done .cp-stepper__label {
    color: #2563eb;
}

/* Icons */
.cp-stepper__label i {
    font-size: 0.75rem;
    opacity: 0.7;
}

@media (max-width: 640px) {
    .cp-stepper__label {
        font-size: 0.6rem;
    }
    .cp-stepper__label i {
        display: none;
    }
    .cp-stepper-container {
        padding: 20px 10px;
    }
}
</style>
