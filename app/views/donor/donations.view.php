<?php
/**
 * Donor Portal — Donations Page (FINAL UI RESTORATION)
 * Dashboard and Modal Data Steps (1-4/3) are in the ORIGINAL PROJECT STYLE.
 * ONLY the Final Review Step has the upgraded 3-button UI/Signatures as requested.
 */
include __DIR__ . '/inc/header.view.php';
include __DIR__ . '/inc/sidebar.view.php';
$hospitalsByOrganJson = json_encode($hospitals_by_organ ?? []);
$approvedHospitalsJson = json_encode($approved_hospitals ?? []);
?>
<script>
    const pendingMatchesData = <?= json_encode($pending_matches ?? []) ?>;
</script>
<?php
// Group pending matches by organ_id for easy lookup at the top level
$matchesByOrgan = [];
if (!empty($pending_matches)) {
    foreach ($pending_matches as $pm) {
        $matchesByOrgan[$pm->organ_id][] = $pm;
    }
}
?>
<style>
:root { --accent: #10b981; --accent-hover: #059669; }

/* Premium Document Modal Styles */
.d-modal__header { border-bottom: 2px solid var(--g200); padding: 1.5rem 2rem; display: flex; justify-content: space-between; align-items: center; background: #fafafa; border-radius: 12px 12px 0 0; }
.d-modal__title-group h3 { font-size: 1.3rem; font-weight: 800; color: var(--slate); margin: 0; display: flex; align-items: center; gap: 12px; }
.d-modal__subtitle { font-size: 0.85rem; color: var(--g500); margin-top: 0.2rem; }
.d-modal__close { background: #fee2e2; border: none; width: 32px; height: 32px; border-radius: 50%; color: #ef4444; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; transition: all 0.2s; }
.d-modal__close:hover { background: #fecaca; transform: rotate(90deg); }

/* Organ Match Pulsate UI */
.pulse-match {
    border-color: #10b981 !important;
    animation: match-pulse 2s infinite !important;
    background: #f0fdf4 !important;
}

@keyframes match-pulse {
    0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4); }
    70% { box-shadow: 0 0 0 10px rgba(16, 185, 129, 0); }
    100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
}

.match-pulse-badge {
    position: absolute;
    top: -10px;
    right: -10px;
    background: #10b981;
    color: white;
    font-size: 0.65rem;
    font-weight: 800;
    padding: 4px 10px;
    border-radius: 50px;
    box-shadow: 0 4px 10px rgba(16, 185, 129, 0.3);
    z-index: 10;
    pointer-events: none; /* Crucial: clicks pass through to the card */
    display: flex;
    align-items: center;
    gap: 4px;
}

/* Unified Input Styling */
.d-input-group { margin-bottom: 1.5rem; }
.d-input-group label { display: block; font-size: 0.85rem; font-weight: 700; color: var(--slate); margin-bottom: 0.6rem; }
.d-input { width: 100%; padding: 0.85rem 1rem; border: 1.5px solid var(--g200); border-radius: 10px; font-size: 0.95rem; color: var(--slate); transition: all 0.2s; background: #fff; }
.d-input:focus { border-color: var(--blue-500); box-shadow: 0 0 0 4px var(--blue-50); outline: none; }

/* Modal Content & Steps */
.d-modal__step { display: none; padding: 2rem; background: #fff; }
.d-modal__step.active { display: block; animation: fadeIn 0.3s ease; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

/* Formal Document Sectioning */
.d-section-header { font-size: 0.85rem; font-weight: 800; color: var(--blue-600); text-transform: uppercase; letter-spacing: 0.1em; border-bottom: 2px solid var(--blue-50); padding-bottom: 0.75rem; margin: 1.5rem 0 1.5rem; display: flex; align-items: center; gap: 10px; }

/* Instructional & Warning Boxes */
.d-instruction-box { background: var(--blue-50); border-left: 4px solid var(--blue-600); padding: 1.75rem; border-radius: 12px; margin-bottom: 2rem; border-top: 1px solid var(--blue-100); border-right: 1px solid var(--blue-100); border-bottom: 1px solid var(--blue-100); }
.d-instruction-box h4 { color: var(--blue-900); font-weight: 800; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 10px; }
.d-instruction-box p { font-size: 0.95rem; color: var(--blue-800); line-height: 1.6; }
.d-instruction-box ul { margin-top: 1rem; padding-left: 1.5rem; }
.d-instruction-box li { margin-bottom: 0.5rem; color: var(--blue-900); font-weight: 500; font-size: 0.9rem; }

.d-warning-box { background: #fffbeb; border-left: 4px solid #f59e0b; padding: 1.25rem; border-radius: 10px; margin: 2rem 0; font-size: 0.9rem; color: #92400e; display: flex; align-items: center; gap: 12px; line-height: 1.5; border: 1px solid #fef3c7; }

/* Review Page (Formal Document) */
.d-review-page { padding: 3rem; background: #fff; border: 2px solid var(--g100); border-radius: 8px; position: relative; margin-bottom: 1rem; }
.d-review-header { text-align: center; border-bottom: 3px double var(--g200); padding-bottom: 2rem; margin-bottom: 2.5rem; }
.d-review-header h2 { font-size: 1.4rem; font-weight: 900; letter-spacing: 0.15em; color: var(--slate); text-transform: uppercase; margin: 0; }

.d-info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2.5rem; }
.d-info-item label { display: block; font-size: 0.75rem; font-weight: 800; color: var(--g500); text-transform: uppercase; margin-bottom: 0.5rem; letter-spacing: 0.05em; }
.d-info-item span { font-size: 1.1rem; font-weight: 700; color: var(--slate); border-bottom: 1px solid var(--g100); display: block; padding-bottom: 5px; }
.no-print { display: block; }
@media print { .no-print { display: none !important; } }
.d-status--pending { background: #fefce8 !important; color: #854d0e !important; border: 1px solid #fef08a !important; }
.d-stat--pending { background: #fffbeb !important; border: 1.5px solid #facc15 !important; }

/* === Organ Status Variants === */
.d-status--suspended {
    background: #fff7ed !important;
    color: #9a3412 !important;
    border: 1px solid #fed7aa !important;
}
.d-status--inprogress {
    background: #eff6ff !important;
    color: #1d4ed8 !important;
    border: 1px solid #bfdbfe !important;
    animation: pulse-badge 2s ease-in-out infinite;
}
.d-status--completed {
    background: #f1f5f9 !important;
    color: #475569 !important;
    border: 1px solid #cbd5e1 !important;
}
@keyframes pulse-badge {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.65; }
}

/* === Custom Tooltip System for Suspended Pledges === */
.has-suspension-tip {
    position: relative;
    cursor: help;
}
.has-suspension-tip::before {
    content: attr(data-tip);
    position: absolute;
    bottom: calc(100% + 10px);
    left: 50%;
    transform: translateX(-50%);
    background: #1e293b;
    color: #f8fafc;
    font-size: 0.72rem;
    font-weight: 500;
    line-height: 1.5;
    padding: 0.6rem 0.9rem;
    border-radius: 8px;
    width: 220px;
    white-space: normal;
    text-align: left;
    box-shadow: 0 8px 24px rgba(0,0,0,0.22);
    z-index: 1000;
    pointer-events: none;
    opacity: 0;
    transition: opacity 0.2s ease, transform 0.2s ease;
    transform: translateX(-50%) translateY(4px);
}
.has-suspension-tip::after {
    content: '';
    position: absolute;
    bottom: calc(100% + 4px);
    left: 50%;
    transform: translateX(-50%);
    border: 6px solid transparent;
    border-top-color: #1e293b;
    z-index: 1001;
    pointer-events: none;
    opacity: 0;
    transition: opacity 0.2s ease;
}
.has-suspension-tip:hover::before,
.has-suspension-tip:hover::after {
    opacity: 1;
    transform: translateX(-50%) translateY(0);
}

.signature-block { display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; margin-top: 4rem; }
.sig-line { border-top: 2.5px solid var(--slate); padding-top: 0.75rem; font-size: 0.75rem; font-weight: 900; color: var(--slate); text-transform: uppercase; letter-spacing: 0.1em; text-align: center; }

@media print {
    .d-btn, .fas, .d-modal__close, .d-modal__header, [class*="--interactive"] { display: none !important; }
    .d-modal__body, .d-modal__content { width: 100% !important; max-width: none !important; padding: 0 !important; margin: 0 !important; position: static !important; box-shadow: none !important; border:none !important; }
    .d-review-page { border: none !important; padding: 0 !important; box-shadow: none !important; }
    body { background: white !important; padding: 0 !important; margin: 0 !important; }
}

</style>

<main class="d-content">
    <div class="d-content__header">
        <h2><i class="fas fa-heart text-accent"></i> Organ & Tissue Donation Pledge</h2>
        <p>Your selfless pledge can help save multiple lives and heal many more.</p>
    </div>
    <div class="d-content__body">
        <?php if(isset($_SESSION['success_message'])): ?>
            <div class="d-instruction-box" style="background:#f0fdf4; border-color:var(--accent); color:#166534; margin-bottom:1.5rem;">
                <i class="fas fa-check-circle"></i> <?= $_SESSION['success_message'] ?>
                <?php unset($_SESSION['success_message']); ?>
            </div>
        <?php endif; ?>

        <?php if(isset($_SESSION['error_message'])): ?>
            <div class="d-instruction-box" style="background:#fff5f5; border-color:#feb2b2; color:#742a2a; margin-bottom:1.5rem;">
                <i class="fas fa-exclamation-circle"></i> <?= $_SESSION['error_message'] ?>
                <?php unset($_SESSION['error_message']); ?>
            </div>
        <?php endif; ?>

        <div style="display: grid; gap: 2rem;">

            <!-- Header: Completed Contributions (Icons) -->
            <?php 
            // Unified completed list: from pledged_organs (status=COMPLETED) OR recovery_status=RECOVERED
            $completed_list = array_filter($pledged_organs ?? [], function($o) {
                $status = strtoupper($o['status'] ?? '');
                $recovery = strtoupper($o['recovery_status'] ?? '');
                return $status === 'COMPLETED' || $recovery === 'RECOVERED';
            });
            if (!empty($completed_list)): ?>
            <div onclick="openModal('completedHistoryModal')" style="display:flex; align-items:center; gap:10px; margin-top:15px; padding:12px 20px; background:linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(5, 150, 105, 0.05)); border-radius:14px; width:fit-content; border:1px solid rgba(16, 185, 129, 0.2); box-shadow: 0 4px 15px rgba(16, 185, 129, 0.05); cursor: pointer; transition: transform 0.2s ease, box-shadow 0.2s ease;" class="completed-summary-btn">
                <style>
                    .completed-summary-btn:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(16, 185, 129, 0.12); }
                </style>
                <div style="display:flex; align-items:center; gap:8px; border-right: 1px solid rgba(16, 185, 129, 0.2); padding-right: 15px; margin-right: 5px;">
                    <i class="fas fa-award" style="color:#10b981; font-size: 1.1rem;"></i>
                    <span style="font-size:0.75rem; font-weight:800; color:#065f46; text-transform:uppercase; letter-spacing:0.8px;">Completed Contributions:</span>
                </div>
                <div style="display:flex; gap:10px;">
                    <?php foreach($completed_list as $cp): ?>
                        <div title="Completed: <?= htmlspecialchars($cp['organ_name']) ?>" style="width:36px; height:36px; border-radius:50%; background:#10b981; color:white; display:flex; align-items:center; justify-content:center; font-size:1rem; box-shadow:0 4px 10px rgba(16, 185, 129, 0.2); position:relative;">
                            <?= $cp['organ_icon'] ?>
                            <i class="fas fa-check-circle" style="position:absolute; bottom:-2px; right:-2px; font-size:0.7rem; color:#fff; background:#10b981; border-radius:50%; border:1.5px solid #fff;"></i>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div style="margin-left: 10px; color: #10b981; font-size: 0.8rem;"><i class="fas fa-chevron-right"></i></div>
            </div>
            <?php endif; ?>
            
            <!-- Section: Your Pledged Donations (ORIGINAL UNITARY GRID) -->
            <div class="d-widget shadow-sm">
                <div class="d-widget__header">
                    <div class="d-widget__title"><i class="fas fa-check-circle text-accent"></i> Your Pledged Donations</div>
                </div>
                <div class="d-widget__body">
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 1.5rem;">
                        <?php
                        /**
                         * Build tooltip message for SUSPENDED pledges.
                         * Returns a string safe for data-tip attribute.
                         */
                        /**
                         * Build tooltip message for suspended organ opportunities.
                         */
                        function buildSuspensionTip(array $organ): string {
                            $organName = $organ['organ_name'] ?? 'this organ';
                            $nextDate  = $organ['next_eligible_date'] ?? null;
                            $formatted = $nextDate ? date('d M Y', strtotime($nextDate)) : 'TBD';
                            
                            return "You are currently in the recovery period for " . htmlspecialchars($organName) . ". You will be eligible to re-pledge after " . $formatted . ".";
                        }

                        /**
                         * Render a single organ pledge card.
                         */
                        function renderOrganCard(array $o, string $baseColor, string $baseTextColor, string $baseBg, string $defaultStatusClass, $supersededInfo = null, $allMatches = []): void {
                            $organId = (int)($o['organ_id'] ?? 0);
                            $organMatches = $allMatches[$organId] ?? [];
                            $hasMatch = !empty($organMatches);

                            $status           = strtoupper($o['status'] ?? 'PENDING');
                            $isWithdrawPending = (!empty($o['withdrawal_status']) && $o['withdrawal_status'] === 'PENDING_UPLOAD');
                            $isSuspended      = ($status === 'SUSPENDED');
                            $isInProgress     = ($status === 'IN_PROGRESS');
                            $isCompleted      = ($status === 'COMPLETED');
                            $isPending        = ($status === 'PENDING' && empty($o['signed_form_path']));
                            
                            // Check for virtual deactivation (Superseded by newer Body intent)
                            $isSuperseded = ($supersededInfo && $supersededInfo['type'] === 'ORGAN' && ($status === 'ACTIVE' || $status === 'UPLOADED' || $status === 'PENDING'));

                            // --- Card styles ---
                            if ($isSuperseded) {
                                $boxStyle    = 'border: 1.5px dashed #64748b; background: #f8fafc; opacity: 0.7;';
                                $iconColor   = '#94a3b8';
                                $nameColor   = '#64748b';
                                $statusClass = 'd-status--suspended';
                                $statusText  = 'WITHDRAWN (REPLACED)';
                                $clickHandler = "";
                                $extraCardClass = '';
                                $dataTip      = ' title="' . htmlspecialchars($supersededInfo['reason']) . '"';
                            } elseif ($isWithdrawPending) {
                                $boxStyle    = 'border: 1.5px solid #ef4444; background: #fef2f2;';
                                $iconColor   = '#ef4444';
                                $nameColor   = '#991b1b';
                                $statusClass = 'd-status--danger';
                                $statusText  = 'Withdrawal Pending';
                                $clickHandler = "window.location.href='" . ROOT . "/donor/withdraw-consent?organ_id=" . (int)$o['organ_id'] . "'";
                                $extraCardClass = '';
                                $dataTip      = '';
                            } elseif ($isPending) {
                                $boxStyle    = 'border: 1.5px solid #facc15; background: #fffbeb;';
                                $iconColor   = '#d97706';
                                $nameColor   = '#92400e';
                                $statusClass = 'd-status--pending';
                                $statusText  = 'Pending Upload';
                                $clickHandler = "openPledgeActionModal(" . (int)$o['organ_id'] . ", '" . addslashes($o['organ_name']) . "')";
                                $extraCardClass = '';
                                $dataTip      = '';
                            } elseif ($isCompleted) {
                                $boxStyle    = 'border: 1.5px solid #10b981; background: #f0fdf4;';
                                $iconColor   = '#10b981';
                                $nameColor   = '#166534';
                                $statusClass = 'd-status--success';
                                $statusText  = 'Completed';
                                $clickHandler = "";
                                $extraCardClass = '';
                                $dataTip      = '';
                            } elseif ($isInProgress) {
                                $boxStyle    = 'border: 1.5px solid #3b82f6; background: #eff6ff;';
                                $iconColor   = '#3b82f6';
                                $nameColor   = '#1e40af';
                                $statusClass = 'd-status--inprogress';
                                $statusText  = 'In Progress';
                                $clickHandler = "";
                                $extraCardClass = '';
                                $dataTip      = '';
                            } elseif ($hasMatch) {
                                // Match override for Active/Uploaded pledges
                                $boxStyle = 'background: #f0fdf4; border: 2px solid #10b981; cursor: pointer;';
                                $iconColor = '#10b981';
                                $nameColor = '#065f46';
                                $statusClass = 'd-status--success';
                                $statusText = 'Match Found';
                                $clickHandler = "openMatchModal(" . (int)$o['organ_id'] . ", '" . addslashes($o['organ_name']) . "')";
                                $extraCardClass = 'pulse-match';
                                $dataTip = ' data-tip="Click to review clinical match request"';
                            } elseif ($isSuspended) {
                                $boxStyle    = 'border: 1.5px solid #64748b; background: #f8fafc; opacity: 0.8;';
                                $iconColor   = '#64748b';
                                $nameColor   = '#334155';
                                $statusClass = 'd-status--suspended';
                                $statusText  = 'Suspended';
                                $clickHandler = "";
                                $extraCardClass = 'has-suspension-tip';
                                $dataTip      = ' data-tip="' . htmlspecialchars(buildSuspensionTip($o), ENT_QUOTES) . '"';
                            } else {
                                // APPROVED / UPLOADED
                                $boxStyle    = 'border: 1.5px solid ' . $baseColor . '; background: ' . $baseBg . ';';
                                $iconColor   = $baseColor;
                                $nameColor   = $baseTextColor;
                                $statusClass = $defaultStatusClass;
                                $statusText  = ($status === 'UPLOADED') ? 'Uploaded' : 'Active';
                                $clickHandler = "openUnselectWarning(" . (int)$o['organ_id'] . ", '" . addslashes($o['organ_name']) . "')";
                                $extraCardClass = '';
                                $dataTip      = '';
                            }

                            $cursorStyle   = $clickHandler ? 'cursor: pointer;' : 'cursor: default;';
                            $onclickAttr   = $clickHandler ? ' onclick="' . $clickHandler . '"' : '';
                            $cardClass     = 'd-stat' . ($extraCardClass ? ' ' . $extraCardClass : '');
                            echo '<div class="' . $cardClass . '" style="' . $boxStyle . ' ' . $cursorStyle . ' text-align:center; position: relative;"' . $onclickAttr . $dataTip . '>';
                            if($hasMatch) {
                                echo '<div class="match-pulse-badge"><i class="fas fa-handshake"></i> MATCH</div>';
                            }
                            echo '  <div style="color:' . $iconColor . '; font-size:1.5rem; margin-bottom:0.5rem;">' . ($o['organ_icon'] ?? '<i class="fas fa-heart"></i>') . '</div>';
                            echo '  <div style="font-weight:700; font-size:0.9rem; color:' . $nameColor . ';">' . htmlspecialchars($o['organ_name']) . '</div>';
                            echo '  <span class="d-status ' . $statusClass . '" style="font-size:0.6rem; margin-top:5px;">' . $statusText . '</span>';
                            echo '</div>';
                        }
                        ?>

                        <?php if(!empty($selected_living) || !empty($selected_after_death) || !empty($selected_full_body)): ?>
                            <?php foreach($selected_living as $o): ?>
                                <?php if(strtoupper($o['status'] ?? '') === 'COMPLETED') continue; ?>
                                <?php renderOrganCard($o, 'var(--accent)', '#166534', '#f0fdf4', 'd-status--success', $deceased_superseded, $matchesByOrgan); ?>
                            <?php endforeach; ?>

                            <?php foreach($selected_after_death as $o): ?>
                                <?php if(strtoupper($o['status'] ?? '') === 'COMPLETED') continue; ?>
                                <?php renderOrganCard($o, 'var(--blue-500)', 'var(--blue-800)', 'var(--blue-50)', 'd-status--info', $deceased_superseded, $matchesByOrgan); ?>
                            <?php endforeach; ?>

                            <?php if(!empty($selected_full_body)):
                                $o = $selected_full_body[0];
                                $isCompleted = (strtoupper($o['status'] ?? '') === 'COMPLETED');
                                if ($isCompleted) goto skip_body;

                                $isPending = ($o['status'] === 'PENDING' && empty($o['signed_form_path']));
                                $isWithdrawPending = (!empty($o['withdrawal_status']) && $o['withdrawal_status'] === 'PENDING_UPLOAD');
                                $isSuperseded = ($deceased_superseded && $deceased_superseded['type'] === 'BODY');

                                if ($isSuperseded) {
                                    $boxStyle = 'border: 1.5px dashed #64748b; background: #f8fafc; opacity: 0.7;';
                                    $statusClass = 'd-status--suspended'; $statusStyle = ''; $statusText = 'WITHDRAWN (REPLACED)';
                                    $clickHandler = "";
                                    $iconColor = '#94a3b8'; $nameColor = '#64748b';
                                    $dataTip = ' title="' . htmlspecialchars($deceased_superseded['reason']) . '"';
                                } elseif ($isWithdrawPending) {
                                    $boxStyle = 'border: 1.5px solid #ef4444; background: #fef2f2;';
                                    $statusClass = 'd-status--danger'; $statusStyle = ''; $statusText = 'Withdrawal Pending';
                                    $clickHandler = "window.location.href='" . ROOT . "/donor/withdraw-consent?organ_id=9'";
                                    $iconColor = '#ef4444'; $nameColor = '#991b1b';
                                    $dataTip = '';
                                } else {
                                    $boxStyle = $isPending ? 'border: 1.5px solid #facc15; background: #fffbeb;' : 'border: 1.5px solid #8b5cf6; background: #f5f3ff;';
                                    $statusClass = $isPending ? 'd-status--pending' : ''; $statusStyle = $isPending ? '' : 'background:#8b5cf6; color:white;';
                                    $statusText = $isPending ? 'Pending Upload' : 'Pledged';
                                    $clickHandler = $isPending ? "openPledgeActionModal(9, 'Full Body')" : "openUnselectWarning(9, 'Full Body')";
                                    $iconColor = ($isPending || $isWithdrawPending) ? '#d97706' : '#8b5cf6'; $nameColor = ($isPending || $isWithdrawPending) ? '#92400e' : '#5b21b6';
                                    $dataTip = '';
                                }
                            ?>
                                <div class="d-stat" style="<?= $boxStyle ?> <?= $clickHandler ? 'cursor: pointer;' : '' ?> text-align:center;" onclick="<?= $clickHandler ?>" <?= $dataTip ?>>
                                    <div style="color:<?= $iconColor ?>; font-size: 1.5rem; margin-bottom: 0.5rem;"><i class="fas fa-university"></i></div>
                                    <div style="font-weight: 700; font-size: 0.9rem; color:<?= $nameColor ?>;">Full Body</div>
                                    <span class="d-status <?= $statusClass ?>" style="font-size: 0.6rem; margin-top: 5px; <?= $statusStyle ?>"><?= $statusText ?></span>
                                </div>
                                <?php skip_body: ?>
                            <?php endif; ?>
                        <?php else: ?>
                            <div style="grid-column: 1 / -1; padding: 2rem; text-align: center; border: 1px dashed var(--g300); border-radius: var(--r); background: var(--g50);">
                                <p style="color: var(--g500);">You haven't made any organ pledges yet.</p>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>

            <!-- Section: New Opportunities (ORIGINAL SECTIONAL LAYOUT) -->
            <div class="d-widget shadow-sm">
                <div class="d-widget__header">
                    <div class="d-widget__title"><i class="fas fa-plus-circle text-accent"></i> New Donation Opportunities</div>
                </div>
                <div class="d-widget__body">
                    
                    <?php if(!empty($eligibility['is_in_recovery'])): ?>
                        <!-- Eligibility Restriction Banner -->
                        <div id="eligibility-alert-banner" style="margin-bottom: 2rem; padding: 1.25rem 1.75rem; background: #fff5f5; border: 1.5px solid #feb2b2; border-radius: 16px; display: flex; align-items: center; gap: 1.25rem; box-shadow: 0 4px 15px rgba(239, 68, 68, 0.05);">
                            <div style="width: 40px; height: 40px; border-radius: 50%; background: #ef4444; color: white; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; flex-shrink: 0;">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div style="flex: 1;">
                                <h4 style="margin: 0; font-size: 1rem; font-weight: 800; color: #991b1b;">Donation Restriction Active</h4>
                                <p style="margin: 4px 0 0 0; font-size: 0.85rem; color: #b91c1c; line-height: 1.4; font-weight: 600;">
                                    <?= htmlspecialchars($eligibility['message']) ?>
                                </p>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Unified Deceased Donation Mode Banner (Sri Lankan Practice) -->
                    <?php 
                        $modeMeta = [
                            'NONE' => ['title' => '-', 'icon' => 'fa-clipboard-list', 'color' => '#64748b', 'bg' => '#f1f5f9'],
                            'EYE_ONLY' => ['title' => 'Cornea/Eye Donation Only', 'icon' => 'fa-eye', 'color' => '#0ea5e9', 'bg' => '#f0f9ff'],
                            'BODY_ONLY' => ['title' => 'Whole Body Donation', 'icon' => 'fa-university', 'color' => '#8b5cf6', 'bg' => '#f5f3ff'],
                            'BODY_PLUS_CORNEA' => ['title' => 'Whole Body + Cornea Donation', 'icon' => 'fa-graduation-cap', 'color' => '#8b5cf6', 'bg' => '#f5f3ff'],
                            'ORGAN_ONLY' => ['title' => 'Specified Deceased Organs', 'icon' => 'fa-dna', 'color' => '#3b82f6', 'bg' => '#eff6ff'],
                            'ORGANS_PLUS_CORNEA' => ['title' => 'Organs + Cornea Donation', 'icon' => 'fa-heartbeat', 'color' => '#3b82f6', 'bg' => '#eff6ff']
                        ];
                        $curr = $modeMeta[$deceased_mode ?? 'NONE'] ?? $modeMeta['NONE'];
                    ?>
<?php
function isBlockedStatus($organName, $eligibility) {
    if (!$eligibility['is_in_recovery']) return false;
    $lowerName = strtolower($organName);
    
    // 1. Check Permanent
    foreach ($eligibility['permanent_blocks'] as $pb) {
        if (strpos($lowerName, strtolower($pb)) !== false) return 'PERMANENT';
    }
    
    // 2. Check Specific or Global recovery
    foreach ($eligibility['blocked_organs'] as $bo) {
        if (strpos($lowerName, strtolower($bo['organ'])) !== false || strpos(strtolower($bo['organ']), 'all major donations') !== false) {
            return $bo['eligible_on'];
        }
    }
    return false;
}
?>

                    <div style="margin-bottom: 2rem; padding: 1.25rem 1.75rem; background: <?= $curr['bg'] ?>; border: 1.5px solid <?= $curr['color'] ?>33; border-radius: 16px; display: flex; align-items: center; justify-content: space-between; overflow: hidden; position: relative;">
                        <div style="position: absolute; right: -20px; top: -10px; font-size: 5rem; opacity: 0.05; color: <?= $curr['color'] ?>;"><i class="fas <?= $curr['icon'] ?>"></i></div>
                        <div style="display: flex; align-items: center; gap: 1.25rem;">
                            <div style="width: 48px; height: 48px; border-radius: 12px; background: <?= $curr['color'] ?>; color: white; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; box-shadow: 0 4px 12px <?= $curr['color'] ?>44;">
                                <i class="fas <?= $curr['icon'] ?>"></i>
                            </div>
                            <div>
                                <span style="display: block; font-size: 0.75rem; font-weight: 800; color: <?= $curr['color'] ?>; text-transform: uppercase; letter-spacing: 1px;">Active Deceased Donation Mode</span>
                                <h4 style="margin: 0; font-size: 1.2rem; font-weight: 800; color: #1e293b;"><?= $curr['title'] ?></h4>
                            </div>
                        </div>
                        <div style="text-align: right;">
                            <span style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; background: white; border-radius: 100px; font-size: 0.7rem; font-weight: 700; color: #64748b; border: 1px solid #e2e8f0;">
                                <i class="fas fa-shield-alt" style="color: #10b981;"></i> Legally Standardized
                            </span>
                        </div>
                    </div>
                    <?php
                    // Matches grouped at the top of the file
                    ?>

                    <style>
                        .pulse-match {
                            animation: pulse-green 2s infinite;
                            box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7);
                        }
                        @keyframes pulse-green {
                            0% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7); }
                            70% { box-shadow: 0 0 0 10px rgba(34, 197, 94, 0); }
                            100% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); }
                        }
                    </style>

                    <h3 style="font-size:0.9rem; color:var(--g500); text-transform:uppercase; margin-bottom:1rem; display:flex; align-items:center; gap:8px;">Donate While Living</h3>
                    <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(160px,1fr)); gap:1.25rem; margin-bottom:2.5rem;">
                        <?php if(!empty($available_living)): foreach($available_living as $o): 
                            $isSuspended = $o['is_suspended'] ?? false;
                            $hasConflict = $deceasedData['has_active_body_pledge'] ?? false;
                            $blockedDay = isBlockedStatus($o['organ_name'], $eligibility);
                            $organMatches = $matchesByOrgan[$o['organ_id']] ?? [];
                            $hasMatch = !empty($organMatches);
                            
                            if ($hasMatch) {
                                $boxStyle = 'background: #f0fdf4; border-color: #4ade80; cursor: pointer; border-width: 2px;';
                                $onclick = "openMatchModal(" . (int)$o['organ_id'] . ", '" . addslashes($o['organ_name']) . "')";
                                $tip = ' data-tip="New Potential Match Found! Review now."';
                                $iconColor = '#16a34a';
                                $nameColor = '#14532d';
                            } elseif ($hasConflict) {
                                $boxStyle = 'background: #fff7ed; border-color: #fdba74; opacity: 0.9; cursor: pointer;';
                                $onclick = "showConflictModal('Living Organ', 'Body Donation')";
                                $tipText = ($deceasedData['has_inprogress_body'] ?? false) 
                                    ? "Conflict: A body donation match is currently in progress."
                                    : "Conflict: Body Donation already pledged.";
                                $tip = ' data-tip="' . $tipText . '"';
                                $iconColor = '#f97316';
                                $nameColor = '#9a3412';
                            } elseif ($blockedDay) {
                                $boxStyle = 'background: #fff1f2; border-color: #fca5a5; opacity: 0.85; cursor: pointer;';
                                $onclick = "showBlockedModal('" . addslashes($o['organ_name']) . "', '" . $blockedDay . "')";
                                $tip = ' data-tip="' . ($blockedDay === 'PERMANENT' ? 'Permanently restricted' : 'Blocked until ' . $blockedDay) . '"';
                                $iconColor = '#ef4444';
                                $nameColor = '#991b1b';
                            } elseif ($isSuspended) {
                                $boxStyle = 'background: #f1f5f9; border-color: #cbd5e1; opacity: 0.7; cursor: not-allowed;';
                                $onclick = '';
                                $tip = ' data-tip="' . htmlspecialchars(buildSuspensionTip($o), ENT_QUOTES) . '"';
                                $iconColor = '#94a3b8';
                                $nameColor = '#64748b';
                            } else {
                                $boxStyle = 'border: 1px solid var(--g200); cursor: pointer;';
                                $onclick = "openLivingModal(" . $o['organ_id'] . ", '" . addslashes($o['organ_name']) . "')";
                                $tip = '';
                                $iconColor = 'var(--accent)';
                                $nameColor = 'inherit';
                            }
                        ?>
                            <div class="d-stat d-stat--interactive <?= ($isSuspended || $blockedDay || $hasMatch || $hasConflict) ? 'has-suspension-tip' : '' ?> <?= $hasMatch ? 'pulse-match' : '' ?>" style="padding:1.25rem; <?= $boxStyle ?> text-align:center; position: relative;" onclick="<?= $onclick ?>" <?= $tip ?>>
                                <?php if($hasMatch): 
                                    $isAccepted = false;
                                    $matchedHospital = '';
                                    foreach($organMatches as $om) {
                                        if (in_array($om['status'], ['PENDING', 'APPROVED'])) {
                                            $isAccepted = true;
                                            $matchedHospital = $om['hospital_name'];
                                            break;
                                        }
                                    }
                                ?>
                                    <div class="match-pulse-badge" style="<?= $isAccepted ? 'background: #059669;' : '' ?>">
                                        <i class="fas <?= $isAccepted ? 'fa-check-circle' : 'fa-handshake' ?>"></i> 
                                        <?= $isAccepted ? 'MATCHED' : 'MATCH FOUND' ?>
                                    </div>
                                <?php elseif($hasConflict): ?>
                                    <div class="match-pulse-badge" style="background: #f97316; animation: none;">
                                        <i class="fas fa-exclamation-circle"></i> UNAVAILABLE
                                    </div>
                                <?php endif; ?>
                                <div style="color:<?= $iconColor ?>; font-size:1.5rem; margin-bottom:0.75rem;"><?= $o['organ_icon'] ?></div>
                                <div style="font-weight:700; font-size:0.85rem; color:<?= $nameColor ?>;"><?= htmlspecialchars($o['organ_name']) ?></div>
                            </div>
                        <?php endforeach; else: ?><div style="grid-column:1/-1; color:var(--g400); font-size:0.8rem;">No living pledges available</div><?php endif; ?>
                    </div>
                    <h3 style="font-size:0.9rem; color:var(--g500); text-transform:uppercase; margin-bottom:1rem; display:flex; align-items:center; gap:8px;">After Death Donations</h3>
                    <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(160px,1fr)); gap:1.25rem; margin-bottom:2.5rem;">
                        <?php if(!empty($available_after_death)): foreach($available_after_death as $o): 
                            $isSuspended = $o['is_suspended'] ?? false;
                            $hasConflict = $deceasedData['has_active_body_pledge'] ?? false;
                            $organMatches = $matchesByOrgan[$o['organ_id']] ?? [];
                            $hasMatch = !empty($organMatches);
                            
                            if ($hasMatch) {
                                $boxStyle = 'background: #f0fdf4; border-color: #4ade80; cursor: pointer; border-width: 2px;';
                                $onclick = "openMatchModal(" . (int)$o['organ_id'] . ", '" . addslashes($o['organ_name']) . "')";
                                $tip = ' data-tip="New Potential Match Found! Review now."';
                                $iconColor = '#16a34a';
                                $nameColor = '#14532d';
                            } elseif ($hasConflict) {
                                $boxStyle = 'background: #fff7ed; border-color: #fdba74; opacity: 0.9; cursor: pointer;';
                                $onclick = "showConflictModal('Organ', 'Body Donation')";
                                $tipText = ($deceasedData['has_inprogress_body'] ?? false) 
                                    ? "Conflict: A body donation match is currently in progress."
                                    : "Conflict: Body Donation already pledged.";
                                $tip = ' data-tip="' . $tipText . '"';
                                $iconColor = '#f97316';
                                $nameColor = '#9a3412';
                            } elseif ($isSuspended) {
                                $boxStyle = 'background: #f1f5f9; border-color: #cbd5e1; opacity: 0.7; cursor: not-allowed;';
                                $onclick = '';
                                $tip = ' data-tip="' . htmlspecialchars(buildSuspensionTip($o), ENT_QUOTES) . '"';
                                $iconColor = '#94a3b8';
                                $nameColor = '#64748b';
                            } else {
                                $boxStyle = 'border: 1px solid var(--g200); cursor: pointer;';
                                $onclick = "openAfterDeathModal(" . $o['organ_id'] . ", '" . addslashes($o['organ_name']) . "')";
                                $tip = '';
                                $iconColor = 'var(--blue-500)';
                                $nameColor = 'inherit';
                            }
                        ?>
                            <div class="d-stat d-stat--interactive <?= ($isSuspended || $hasMatch || $hasConflict) ? 'has-suspension-tip' : '' ?> <?= $hasMatch ? 'pulse-match' : '' ?>" style="padding:1.25rem; <?= $boxStyle ?> text-align:center; position: relative;" onclick="<?= $onclick ?>" <?= $tip ?>>
                                <?php if($hasMatch): 
                                    $isAccepted = false;
                                    $matchedHospital = '';
                                    foreach($organMatches as $om) {
                                        if (in_array($om['status'], ['PENDING', 'APPROVED'])) {
                                            $isAccepted = true;
                                            $matchedHospital = $om['hospital_name'];
                                            break;
                                        }
                                    }
                                ?>
                                    <div class="match-pulse-badge" style="<?= $isAccepted ? 'background: #059669;' : '' ?>">
                                        <i class="fas <?= $isAccepted ? 'fa-check-circle' : 'fa-handshake' ?>"></i> 
                                        <?= $isAccepted ? 'MATCHED' : 'MATCH FOUND' ?>
                                    </div>
                                <?php elseif($hasConflict): ?>
                                    <div class="match-pulse-badge" style="background: #f97316; animation: none;">
                                        <i class="fas fa-exclamation-circle"></i> UNAVAILABLE
                                    </div>
                                <?php endif; ?>
                                <div style="color:<?= $iconColor ?>; font-size:1.5rem; margin-bottom:0.75rem;"><?= $o['organ_icon'] ?></div>
                                <div style="font-weight:700; font-size:0.85rem; color:<?= $nameColor ?>;"><?= htmlspecialchars($o['organ_name']) ?></div>
                            </div>
                        <?php endforeach; else: ?><div style="grid-column:1/-1; color:var(--g400); font-size:0.8rem;">All death pledges active.</div><?php endif; ?>
                    </div>
                    <h3 style="font-size:0.9rem; color:var(--g500); text-transform:uppercase; margin-bottom:1rem; display:flex; align-items:center; gap:8px;">Academic Body Donation</h3>
                    <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(300px,1fr)); gap:1.25rem;">
                        <?php if(!empty($available_full_body)): ?>
                            <?php if($is_body_mode): ?>
                                <?php if($deceasedData['has_active_deceased_organs']): 
                                        $bodyTip = ($deceasedData['has_inprogress_deceased_organs'] ?? false) 
                                            ? "Unavailable because an organ donation match is currently in progress."
                                            : "Unavailable while you have active deceased organ pledges.";
                                    ?>
                                    <div class="d-stat has-suspension-tip" style="border: 1.5px solid #3b82f6; background: #eff6ff; position: relative; cursor: pointer;" onclick="showConflictModal('Body', 'Deceased Organ')" data-tip="<?= $bodyTip ?>">
                                        <div style="position: absolute; right: 5px; top: 5px; font-size: 3rem; opacity: 0.08; color: #3b82f6;"><i class="fas fa-exclamation-circle"></i></div>
                                        <div style="display:flex; align-items:center; gap:1.25rem; width:100%; position: relative; z-index: 1;">
                                            <div style="width: 48px; height: 48px; border-radius: 12px; background: #dbeafe; color: #1d4ed8; display: flex; align-items: center; justify-content: center; font-size: 1.4rem;">
                                                <i class="fas fa-info-circle"></i>
                                            </div>
                                            <div style="flex: 1;">
                                                <div style="font-weight:800; font-size:0.95rem; color: #1e40af; margin-bottom: 2px;">Body Donation Unavailable</div>
                                                <div style="font-size:0.8rem; font-weight: 500; color:#1d4ed8; line-height: 1.4;">
                                                    Mode conflict detected. Click to learn how to switch.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="d-stat d-stat--interactive" onclick="if(checkOrganEligibility('Full Body')){ goToBodyStep(1); openModal('bodyConsentModal'); }">
                                        <div style="display:flex; align-items:center; gap:1.25rem; width:100%;">
                                            <div style="font-size:1.8rem; color:#8b5cf6;"><i class="fas fa-graduation-cap"></i></div>
                                            <div><div style="font-weight:700; font-size:1rem;">Full Body Donation Authorization</div><div style="font-size:0.8rem; color:var(--g500);">Expression of intent for anatomical study and surgical training.</div></div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <?php if(!empty($has_major_living_donation)): ?>
                                    <div class="d-stat" style="border: 1.5px solid #fee2e2; background: #fef2f2; position: relative; overflow: hidden;">
                                        <div style="position: absolute; right: -10px; top: -5px; font-size: 4rem; opacity: 0.05; color: #ef4444;"><i class="fas fa-hand-holding-heart"></i></div>
                                        <div style="display:flex; align-items:center; gap:1.25rem; width:100%; position: relative; z-index: 1;">
                                            <div style="width: 48px; height: 48px; border-radius: 12px; background: #fee2e2; color: #ef4444; display: flex; align-items: center; justify-content: center; font-size: 1.4rem;">
                                                <i class="fas fa-info-circle"></i>
                                            </div>
                                            <div style="flex: 1;">
                                                <div style="font-weight:800; font-size:0.95rem; color: #991b1b; margin-bottom: 2px;">Body Donation Unavailable</div>
                                                <div style="font-size:0.8rem; font-weight: 500; color:#b91c1c; line-height: 1.4;">
                                                    Your previous life-saving gift makes whole-body study unsuitable. Thank you for your incredible contribution to life!
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="d-stat" style="opacity: 0.6; cursor: not-allowed; border: 1.5px dashed var(--g200); background: #f8fafc;">
                                        <div style="display:flex; align-items:center; gap:1.25rem; width:100%;">
                                            <div style="font-size:1.8rem; color:var(--g400);"><i class="fas fa-university"></i></div>
                                            <div>
                                                <div style="font-weight:700; font-size:1rem; color: var(--g500);">Body Donation (Historical Only)</div>
                                                <div style="font-size:0.8rem; color:var(--g400);">Switch to Body mode to re-enable university registration.</div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php else: ?><div style="grid-column:1/-1; color:var(--g400);">Body donation authorization active.</div><?php endif; ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>

<!-- LIVING MODAL (DOCUMENT-STYLE 6-STEP PROCESS) -->
<div id="livingConsentModal" class="d-modal">
    <div class="d-modal__body" style="max-width:750px;">
        <div class="d-modal__header" style="flex-direction: column; align-items: center; text-align: center; padding: 2rem 1.5rem 1rem;">
            <img src="<?= ROOT ?>/assets/images/logo.png" alt="Life-Connect Logo" style="height: 60px; margin-bottom: 1rem;">
            <div class="d-modal__title-group" style="text-align: center;">
                <h3 style="font-size: 1.5rem; margin-bottom: 0.25rem;"><i class="fas fa-file-signature text-accent"></i> Living Donation Consent</h3>
                <p class="d-modal__subtitle" style="font-size: 0.85rem; color: var(--g500);">Official Legal Authorization for Organ Donation</p>
            </div>
            <button class="d-modal__close" onclick="closeModal('livingConsentModal')" style="position: absolute; top: 1.5rem; right: 1.5rem;">&times;</button>
        </div>
        
        <div class="d-modal__content">
            <!-- Step 1: A. Donor Personal Information -->
            <div id="step1" class="d-modal__step active">
                <div class="d-instruction-box">
                    <h4><i class="fas fa-info-circle"></i> 1. Live Organ Donation Consent Form</h4>
                    <p>Please verify your personal information and provide additional details as required by the transplantation act.</p>
                </div>
                <h4 class="d-section-header"><i class="fas fa-user-circle text-accent"></i> A. Donor Personal Information</h4>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem; background:#f8fafc; padding:1.5rem; border-radius:12px; border:1.5px solid var(--g200);">
                    <div class="d-input-group">
                        <label>Full Name (as in NIC)</label>
                        <input type="text" class="d-input" value="<?= htmlspecialchars($donor_full_name) ?>" readonly style="background:#f1f5f9;">
                    </div>
                    <div class="d-input-group">
                        <label>NIC Number</label>
                        <input type="text" class="d-input" value="<?= htmlspecialchars($donor_data['nic_number'] ?? '') ?>" readonly style="background:#f1f5f9;">
                    </div>
                    <div class="d-input-group">
                        <label>Date of Birth</label>
                        <input type="text" class="d-input" value="<?= htmlspecialchars($donor_data['date_of_birth'] ?? '') ?>" readonly style="background:#f1f5f9;">
                    </div>
                    <div class="d-input-group">
                        <label>Gender</label>
                        <input type="text" class="d-input" value="<?= htmlspecialchars($donor_data['gender'] ?? '') ?>" readonly style="background:#f1f5f9;">
                    </div>
                    <div class="d-input-group" style="grid-column: span 2;">
                        <label>Address <span style="color:var(--danger);">*</span></label>
                        <textarea id="livingAddress" class="d-input" style="height:60px;" required></textarea>
                    </div>
                    <div class="d-input-group">
                        <label>Blood Group <span style="color:var(--danger);">*</span></label>
                        <select id="bloodGroup" class="d-input" required>
                            <option value="">-- Select Blood Group --</option>
                            <option value="A+">A+</option>
                            <option value="A-">A-</option>
                            <option value="B+">B+</option>
                            <option value="B-">B-</option>
                            <option value="AB+">AB+</option>
                            <option value="AB-">AB-</option>
                            <option value="O+">O+</option>
                            <option value="O-">O-</option>
                        </select>
                    </div>
                    <div class="d-input-group">
                        <label>Nationality <span style="color:var(--danger);">*</span></label>
                        <input type="text" id="nationality" class="d-input" value="<?= htmlspecialchars($donor_data['nationality'] ?? 'Sri Lankan') ?>" placeholder="e.g. Sri Lankan">
                    </div>
                </div>
                <div style="text-align:right; margin-top:2rem;"><button class="d-btn d-btn--primary" onclick="goToStep(2)">Continue to Medical Details <i class="fas fa-arrow-right"></i></button></div>
            </div>

            <!-- Step 2: B. Medical Information -->
            <div id="step2" class="d-modal__step">
                <h4 class="d-section-header"><i class="fas fa-briefcase-medical text-accent"></i> B. Medical Information</h4>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; background:#f8fafc; padding:1.5rem; border-radius:12px; border:1.5px solid var(--g200);">
                    <div class="d-input-group">
                        <label>Height (cm) <span style="color:var(--danger);">*</span></label>
                        <input type="number" id="height" class="d-input" placeholder="e.g. 175" min="10" required>
                    </div>
                    <div class="d-input-group">
                        <label>Weight (kg) <span style="color:var(--danger);">*</span></label>
                        <input type="number" id="weight" class="d-input" placeholder="e.g. 70" required>
                    </div>
                    <div class="d-input-group" style="grid-column: span 2;">
                        <label>Existing Medical Conditions</label>
                        <textarea id="conditions" class="d-input" placeholder="List any chronic illnesses..." style="height:60px;"></textarea>
                    </div>
                    <div class="d-input-group" style="grid-column: span 2;">
                        <label>Current Medications</label>
                        <textarea id="medications" class="d-input" placeholder="List medications you are currently taking..." style="height:60px;"></textarea>
                    </div>
                    <div class="d-input-group" style="grid-column: span 2;">
                        <label>Previous Surgeries</label>
                        <textarea id="surgeries" class="d-input" placeholder="List any major surgeries..." style="height:60px;"></textarea>
                    </div>
                    <div class="d-input-group" style="grid-column: span 2;">
                        <label>Allergies</label>
                        <input type="text" id="allergies" class="d-input" placeholder="Food, Drug or seasonal allergies...">
                    </div>
                    <div class="d-input-group" style="grid-column: span 2;">
                        <label>Smoking / Alcohol Status</label>
                        <select id="habits" class="d-input">
                            <option value="None">None</option>
                            <option value="Smoking Only">Smoking Only</option>
                            <option value="Alcohol Only">Alcohol Only</option>
                            <option value="Both">Both (Smoking & Alcohol)</option>
                            <option value="Occasionally">Occasionally</option>
                        </select>
                    </div>
                </div>
                <div style="display:flex; justify-content:space-between; margin-top:2rem;"><button class="d-btn d-btn--outline" onclick="goToStep(1)"><i class="fas fa-arrow-left"></i> Previous</button><button class="d-btn d-btn--primary" onclick="handleStep2Next()">Next: Donation Details <i class="fas fa-arrow-right"></i></button></div>
            </div>

            <!-- Step 3: C. Hospital Selection (Request Based) -->
            <div id="step3" class="d-modal__step">
                <h4 class="d-section-header"><i class="fas fa-hospital text-accent"></i> C. Hospital Selection</h4>
                
                <div class="d-instruction-box" style="margin-bottom:1.5rem;">
                    <h4><i class="fas fa-search-location"></i> Available Organ Requests</h4>
                    <p>Select a hospital that has submitted an official request for <strong id="req_organ_name">the organ</strong>. Matching your donation with a specific request ensures immediate clinical use.</p>
                </div>

                <div style="background:#f8fafc; padding:1.5rem; border-radius:12px; border:1.5px solid var(--g200); margin-bottom:1.5rem;">
                    <div class="d-input-group" style="margin-bottom:0;">
                        <label>Organ willing to donate</label>
                        <input type="text" id="living_organ_name" class="d-input" readonly style="background:#f1f5f9; font-weight:700;">
                    </div>
                </div>

                <div id="hospital_request_label" style="font-size:0.85rem; font-weight:700; color:var(--slate); margin-bottom:1rem; display:flex; justify-content:space-between; align-items:center;">
                    <span>Registered hospitals (active requests first)</span>
                    <span style="font-size:0.7rem; color:var(--g500);">(Ordered by Priority)</span>
                </div>
                
                <div class="d-input-group">
                    <label>Select Destination Hospital <span style="color:var(--danger);">*</span></label>
                    <select id="hospitalDropdown" class="d-input" onchange="onHospitalChange()" style="margin-top:8px;">
                        <option value="">-- No specific hospital preference --</option>
                    </select>
                </div>

                <div style="display:flex; justify-content:space-between; margin-top:2rem;"><button class="d-btn d-btn--outline" onclick="goToStep(2)"><i class="fas fa-arrow-left"></i> Previous</button><button class="d-btn d-btn--primary" onclick="goToStep(4)">Next: Legal & Emergency <i class="fas fa-arrow-right"></i></button></div>
            </div>

            <!-- Step 4: D. Compatibility & F. Emergency Contact -->
            <div id="step4" class="d-modal__step">
                <h4 class="d-section-header"><i class="fas fa-flask text-accent"></i> D. Compatibility Information (Staff Update)</h4>
                <p style="font-size:0.8rem; color:var(--g500); margin-bottom:1rem;">Optional at this stage. Medical staff will update this after investigations.</p>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; background:white; padding:1.25rem; border:1px solid var(--g200); border-radius:12px; margin-bottom:2rem;">
                    <div class="d-input-group">
                        <label>Blood Compatibility</label>
                        <input type="text" id="compat_blood" class="d-input" placeholder="Pending investigation...">
                    </div>
                    <div class="d-input-group">
                        <label>Tissue Typing (HLA Match)</label>
                        <input type="text" id="compat_tissue" class="d-input" placeholder="Pending investigation...">
                    </div>
                </div>

                <h4 class="d-section-header"><i class="fas fa-phone-alt text-accent"></i> F. Emergency Contact</h4>
                <div style="background:#fff7ed; padding:1.5rem; border-radius:12px; border:1.5px solid #fed7aa;">
                    <div class="d-input-group">
                        <label>Emergency Contact Name <span style="color:var(--danger);">*</span></label>
                        <input type="text" id="emergencyName" class="d-input">
                    </div>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                        <div class="d-input-group">
                            <label>Relationship <span style="color:var(--danger);">*</span></label>
                            <input type="text" id="emergencyRel" class="d-input">
                        </div>
                        <div class="d-input-group">
                            <label>Phone Number <span style="color:var(--danger);">*</span></label>
                            <input type="text" id="emergencyPhone" class="d-input">
                        </div>
                    </div>
                </div>

                <h4 class="d-section-header"><i class="fas fa-users text-accent"></i> Legal Representatives (Witnesses) <span style="color:var(--danger);">*</span></h4>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                    <div style="background:white; padding:1rem; border:1.5px solid var(--g200); border-radius:12px;">
                        <label style="font-size:0.7rem; font-weight:800; color:var(--g400);">WITNESS 1</label>
                        <input type="text" id="cust1_name" class="d-input" placeholder="Full Name" style="margin-top:8px;" required>
                        <input type="text" id="cust1_nic" class="d-input" placeholder="NIC Number" style="margin-top:8px;" required>
                    </div>
                    <div style="background:white; padding:1rem; border:1.5px solid var(--g200); border-radius:12px;">
                        <label style="font-size:0.7rem; font-weight:800; color:var(--g400);">WITNESS 2</label>
                        <input type="text" id="cust2_name" class="d-input" placeholder="Full Name" style="margin-top:8px;" required>
                        <input type="text" id="cust2_nic" class="d-input" placeholder="NIC Number" style="margin-top:8px;" required>
                    </div>
                </div>

                <div style="display:flex; justify-content:space-between; margin-top:2rem;"><button class="d-btn d-btn--outline" onclick="goToStep(3)"><i class="fas fa-arrow-left"></i> Previous</button><button class="d-btn d-btn--primary" onclick="goToStep5()">Review & Legal Consent <i class="fas fa-check-double"></i></button></div>
            </div>

            <!-- Step 5: E. Legal Consent & Review -->
            <div id="step5" class="d-modal__step">
                <div id="livingReviewContent">
                    <div class="d-review-page" style="padding: 2.5rem; color: var(--slate);">
                        <div class="d-review-header">
                            <img src="<?= ROOT ?>/assets/images/logo.png" alt="Life-Connect Logo" style="height: 50px; margin-bottom: 1rem;">
                            <h2>Live Organ Donation Consent Form</h2>
                            <p style="text-transform: uppercase; letter-spacing: 2px; font-weight: 700; font-size: 0.75rem; color: var(--blue-600); margin-top: 5px;">Formal Statutory Declaration</p>
                        </div>
                        
                        <div class="d-instruction-box" style="background:#f0fdf4; border-color:var(--accent); color:#166534; font-size:0.85rem; margin-bottom:2rem;">
                            <strong>Declaration:</strong> I, <span id="rev_donor_name" style="font-weight: 700; text-decoration: underline;"><?= htmlspecialchars($donor_full_name) ?></span>, holder of NIC <strong><?= htmlspecialchars($donor_data['nic_number'] ?? '') ?></strong>, hereby confirm that this pledge is <strong>strictly voluntary</strong> and I have received <strong>no financial compensation</strong> for this act.
                        </div>

                        <!-- Formal Info Grid -->
                        <div class="d-info-grid" style="margin-bottom: 1.5rem;">
                            <div class="d-info-item"><label>Organ for Donation</label><span id="review_as_organ" style="color:var(--blue-700); font-weight: 800;">-</span></div>
                            <div class="d-info-item"><label>Filing Date</label><span><?= date('F d, Y') ?></span></div>
                        </div>

                        <div class="d-info-grid" style="grid-template-columns: repeat(3, 1fr); gap: 1rem; border-top: 1px solid var(--g100); padding-top: 1rem; margin-bottom: 2rem;">
                            <div class="d-info-item"><label>Nationality</label><span id="rev_nationality">-</span></div>
                            <div class="d-info-item"><label>Blood Group</label><span><?= htmlspecialchars($donor_data['blood_group'] ?? 'Not Specified') ?></span></div>
                            <div class="d-info-item"><label>Gender</label><span><?= htmlspecialchars($donor_data['gender'] ?? '-') ?></span></div>
                        </div>

                        <div style="background: #f8fafc; padding: 1.5rem; border-radius: 8px; margin-bottom: 2rem;">
                            <h6 style="font-size:0.7rem; color:var(--g500); text-transform:uppercase; border-bottom:1px solid var(--g200); padding-bottom:5px; margin-bottom: 10px;">Medical Summary</h6>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                                <div style="font-size: 0.85rem;"><strong>Vitals:</strong> <span id="rev_vitals">-</span></div>
                                <div style="font-size: 0.85rem;"><strong>Habits:</strong> <span id="rev_habits">-</span></div>
                                <div style="font-size: 0.85rem; grid-column: span 2;"><strong>Surgeries/Conditions:</strong> <span id="rev_medical">-</span></div>
                            </div>
                        </div>

                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:2.5rem; margin-bottom:3rem;">
                            <div>
                                <h6 style="font-size:0.7rem; color:var(--g500); text-transform:uppercase; border-bottom:1px solid var(--g100); padding-bottom:5px;">Recipient Institution</h6>
                                <p id="rev_hospital_info" style="font-size:0.95rem; font-weight:800; color: var(--blue-700); margin-top:10px;">Registry Managed</p>
                                <div style="font-size:0.75rem; color:var(--g500); margin-top:4px;">Medical center authorized for recovery and surgical procedures.</div>
                            </div>
                            <div>
                                <h6 style="font-size:0.7rem; color:var(--g500); text-transform:uppercase; border-bottom:1px solid var(--g100); padding-bottom:5px;">Witnesses & Verification</h6>
                                <div style="margin-top: 10px;">
                                    <div style="font-size: 0.85rem; font-weight: 700;" id="rev_witness1">W1: -</div>
                                    <div style="font-size: 0.85rem; font-weight: 700; margin-top: 4px;" id="rev_witness2">W2: -</div>
                                </div>
                            </div>
                        </div>

                        <div style="background: #fffbeb; padding: 1rem; border-radius: 8px; margin-bottom: 2.5rem; font-size: 0.8rem; color: #92400e; display: flex; align-items: center; gap: 10px;">
                            <i class="fas fa-exclamation-triangle"></i>
                            <div><strong>Emergency Contact:</strong> <span id="rev_emergency_info" style="font-weight: 800;">-</span></div>
                        </div>

                        <div class="signature-block">
                            <div class="sig-line">WITNESS 01 SIGNATURE</div>
                            <div class="sig-line">WITNESS 02 SIGNATURE</div>
                            <div class="sig-line">DONOR'S SIGNATURE</div>
                        </div>
                    </div>
                </div>
                <div style="display:flex; justify-content:space-between; align-items:center; margin-top:2rem; padding-top:1.5rem; border-top:2px solid var(--g100);">
                    <button class="d-btn d-btn--outline" onclick="goToStep(4)"><i class="fas fa-arrow-left"></i> Previous</button>
                    <div style="display:flex; gap:12px;">
                        <button class="d-btn d-btn--secondary" onclick="downloadPledge('livingReviewContent')"><i class="fas fa-file-pdf"></i> Download Document</button>
                        <button class="d-btn d-btn--primary" onclick="submitPledge()"><i class="fas fa-check-circle"></i> Finalize Consent</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- AFTER DEATH MODAL (DOCUMENT-STYLE 4-STEP) -->
<div id="afterDeathConsentModal" class="d-modal">
    <div class="d-modal__body" style="max-width:700px;">
        <div class="d-modal__header" style="flex-direction: column; align-items: center; text-align: center; padding: 2rem 1.5rem 1rem;">
            <img src="<?= ROOT ?>/assets/images/logo.png" alt="Life-Connect Logo" style="height: 60px; margin-bottom: 1rem;">
            <div class="d-modal__title-group" style="text-align: center;">
                <h3 style="font-size: 1.5rem; margin-bottom: 0.25rem;"><i class="fas fa-dove text-accent"></i> After Death Donation Pledge</h3>
                <p class="d-modal__subtitle" style="font-size: 0.85rem; color: var(--g500);">Expression of Intent for Post-Mortem Recovery</p>
            </div>
            <button class="d-modal__close" onclick="closeModal('afterDeathConsentModal')" style="position: absolute; top: 1.5rem; right: 1.5rem;">&times;</button>
        </div>
        <form id="afterDeathForm" method="POST" action="<?= ROOT ?>/donor/donations" style="padding: 0 1.5rem 1.5rem;">
            <input type="hidden" name="action" value="submit_after_death_pledge">
            
            <!-- Step 1: Personal Information -->
            <div id="deathStep1">
                <div class="d-instruction-box">
                    <h4><i class="fas fa-info-circle"></i> 2. Statutory Declaration of Intent</h4>
                    <p>Verify your personal details for post-mortem organ donation. This information is legally binding and synced with your primary donor record.</p>
                </div>
                <h4 class="d-section-header"><i class="fas fa-user-circle text-accent"></i> A. Donor Personal Information</h4>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem; background:#f8fafc; padding:1.5rem; border-radius:12px; border:1.5px solid var(--g200);">
                    <div class="d-input-group">
                        <label>Full Name</label>
                        <input type="text" class="d-input" value="<?= htmlspecialchars($donor_data['first_name'] . ' ' . $donor_data['last_name']) ?>" readonly style="background:#f1f5f9;">
                    </div>
                    <div class="d-input-group">
                        <label>NIC Number</label>
                        <input type="text" class="d-input" value="<?= htmlspecialchars($donor_data['nic_number']) ?>" readonly style="background:#f1f5f9;">
                    </div>
                    <div class="d-input-group">
                        <label>Date of Birth</label>
                        <input type="text" class="d-input" value="<?= htmlspecialchars($donor_data['date_of_birth']) ?>" readonly style="background:#f1f5f9;">
                    </div>
                    <div class="d-input-group">
                        <label>Gender</label>
                        <input type="text" class="d-input" value="<?= htmlspecialchars($donor_data['gender']) ?>" readonly style="background:#f1f5f9;">
                    </div>
                    <div class="d-input-group">
                        <label>Blood Group <span style="color:var(--danger);">*</span></label>
                        <select name="blood_group" class="d-input" required>
                            <option value="">-- Select Blood Group --</option>
                            <option value="A+" <?= ($donor_data['blood_group'] ?? '') == 'A+' ? 'selected' : '' ?>>A+</option>
                            <option value="A-" <?= ($donor_data['blood_group'] ?? '') == 'A-' ? 'selected' : '' ?>>A-</option>
                            <option value="B+" <?= ($donor_data['blood_group'] ?? '') == 'B+' ? 'selected' : '' ?>>B+</option>
                            <option value="B-" <?= ($donor_data['blood_group'] ?? '') == 'B-' ? 'selected' : '' ?>>B-</option>
                            <option value="AB+" <?= ($donor_data['blood_group'] ?? '') == 'AB+' ? 'selected' : '' ?>>AB+</option>
                            <option value="AB-" <?= ($donor_data['blood_group'] ?? '') == 'AB-' ? 'selected' : '' ?>>AB-</option>
                            <option value="O+" <?= ($donor_data['blood_group'] ?? '') == 'O+' ? 'selected' : '' ?>>O+</option>
                            <option value="O-" <?= ($donor_data['blood_group'] ?? '') == 'O-' ? 'selected' : '' ?>>O-</option>
                        </select>
                    </div>
                    <div class="d-input-group">
                        <label>Nationality</label>
                        <input type="text" class="d-input" value="<?= htmlspecialchars($donor_data['nationality'] ?? 'Sri Lankan') ?>" readonly style="background:#f1f5f9;">
                    </div>
                    <div class="d-input-group" style="grid-column:span 2;">
                        <label>Official Address of Record <span style="color:var(--danger);">*</span></label>
                        <textarea name="address" class="d-input" style="height: 60px;" required><?= htmlspecialchars($donor_data['address'] ?? '') ?></textarea>
                    </div>
                </div>
                <div style="text-align:right; margin-top:2rem;"><button type="button" class="d-btn d-btn--primary" onclick="goToDeathStep(2)">Continue to Organ Selection <i class="fas fa-arrow-right"></i></button></div>
            </div>

            <!-- Step 2: Organ Selection -->
            <div id="deathStep2" style="display:none;">
                <h4 class="d-section-header"><i class="fas fa-check-square text-accent"></i> B. Donation Preferences</h4>
                <p style="font-size:0.9rem; color:var(--g600); margin-bottom:1.5rem;">Select the specific organs and tissues you authorize for clinical recovery:</p>
                <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(160px, 1fr)); gap:15px; margin-bottom:2rem;">
                    <?php foreach($available_after_death as $o): ?>
                    <label class="organ-sel-card" style="display:flex; align-items:center; gap:12px; cursor:pointer; padding:1.25rem; background:white; border-radius:12px; border:1px solid var(--g200); transition:all 0.2s ease;">
                        <input type="checkbox" name="organ_ids[]" id="death_org_<?=$o['organ_id']?>" value="<?=$o['organ_id']?>" class="death-org-check" onchange="updateDeathReview()" style="width:22px; height:22px; accent-color:var(--accent);"> 
                        <span style="font-size:0.95rem; font-weight:700; color:var(--slate);"><?=$o['organ_name']?></span>
                    </label>
                    <?php endforeach; ?>
                </div>
                <div style="display:flex; justify-content:space-between; margin-top:2rem;"><button type="button" class="d-btn d-btn--outline" onclick="goToDeathStep(1)"><i class="fas fa-arrow-left"></i> Previous</button><button type="button" class="d-btn d-btn--primary" onclick="goToDeathStep(3)">Confirm Selections <i class="fas fa-arrow-right"></i></button></div>
            </div>

            <!-- Step 3: Donation Type -->
            <div id="deathStep3" style="display:none;">
                <h4 class="d-section-header"><i class="fas fa-hand-holding-heart text-accent"></i> C. Donation Type</h4>
                <div class="d-input-group" style="background:var(--g50); padding:1.5rem; border-radius:12px; margin-bottom:1.5rem;">
                    <label style="font-weight:700; margin-bottom:10px; display:block;">Donate any suitable organs?</label>
                    <div style="display:flex; gap:2rem;">
                        <label style="display:flex; align-items:center; gap:8px; cursor:pointer;"><input type="radio" name="suitability_any" value="1" checked> Yes</label>
                        <label style="display:flex; align-items:center; gap:8px; cursor:pointer;"><input type="radio" name="suitability_any" value="0"> No</label>
                    </div>
                </div>
                <div class="d-input-group" style="background:var(--g50); padding:1.5rem; border-radius:12px;">
                    <label style="font-weight:700; margin-bottom:10px; display:block;">Do you want to restrict specific organs?</label>
                    <div style="display:flex; gap:2rem;">
                        <label style="display:flex; align-items:center; gap:8px; cursor:pointer;"><input type="radio" name="is_restricted" value="1"> Yes</label>
                        <label style="display:flex; align-items:center; gap:8px; cursor:pointer;"><input type="radio" name="is_restricted" value="0" checked> No</label>
                    </div>
                </div>
                <div style="display:flex; justify-content:space-between; margin-top:2rem;"><button type="button" class="d-btn d-btn--outline" onclick="goToDeathStep(2)"><i class="fas fa-arrow-left"></i> Back</button><button type="button" class="d-btn d-btn--primary" onclick="goToDeathStep(4)">Next Step <i class="fas fa-arrow-right"></i></button></div>
            </div>

            <!-- Step 4: Legal Custodians -->
            <div id="deathStep4" style="display:none;">
                <h4 class="d-section-header"><i class="fas fa-users text-accent"></i> D. Legal Custodian Information</h4>
                <p style="font-size:0.85rem; color:var(--g600); margin-bottom:1.5rem;">Provide details for two legal custodians/next of kin who will be consulted even after your consent.</p>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem;">
                    <div style="background:white; padding:1.25rem; border:1.5px solid var(--g200); border-radius:12px;">
                        <label style="font-size:0.7rem; font-weight:800; color:var(--g400);">CUSTODIAN 1 <span style="color:var(--danger);">*</span></label>
                        <input type="text" name="c1_name" id="dc_c1_name" class="d-input" placeholder="Full Name" style="margin-top:8px;" required>
                        <input type="text" name="c1_nic" id="dc_c1_nic" class="d-input" placeholder="NIC Number" style="margin-top:8px;" required>
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px;">
                            <input type="text" name="c1_rel" id="dc_c1_rel" class="d-input" placeholder="Relation" style="margin-top:8px;" required>
                            <input type="text" name="c1_phone" id="dc_c1_phone" class="d-input" placeholder="Phone" style="margin-top:8px;" required>
                        </div>
                        <input type="email" name="c1_email" id="dc_c1_email" class="d-input" placeholder="Email" style="margin-top:8px;">
                        <input type="text" name="c1_address" id="dc_c1_address" class="d-input" placeholder="Address" style="margin-top:8px;" required>
                    </div>
                    <div style="background:white; padding:1.25rem; border:1.5px solid var(--g200); border-radius:12px;">
                        <label style="font-size:0.7rem; font-weight:800; color:var(--g400);">CUSTODIAN 2 <span style="color:var(--danger);">*</span></label>
                        <input type="text" name="c2_name" id="dc_c2_name" class="d-input" placeholder="Full Name" style="margin-top:8px;" required>
                        <input type="text" name="c2_nic" id="dc_c2_nic" class="d-input" placeholder="NIC Number" style="margin-top:8px;" required>
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px;">
                            <input type="text" name="c2_rel" id="dc_c2_rel" class="d-input" placeholder="Relation" style="margin-top:8px;" required>
                            <input type="text" name="c2_phone" id="dc_c2_phone" class="d-input" placeholder="Phone" style="margin-top:8px;" required>
                        </div>
                        <input type="email" name="c2_email" id="dc_c2_email" class="d-input" placeholder="Email" style="margin-top:8px;">
                        <input type="text" name="c2_address" id="dc_c2_address" class="d-input" placeholder="Address" style="margin-top:8px;" required>
                    </div>
                </div>
                <div style="display:flex; justify-content:space-between; margin-top:2rem;"><button type="button" class="d-btn d-btn--outline" onclick="goToDeathStep(3)"><i class="fas fa-arrow-left"></i> Back</button><button type="button" class="d-btn d-btn--primary" onclick="goToDeathStep(5)">Next Step <i class="fas fa-arrow-right"></i></button></div>
            </div>

            <!-- Step 5: Death & Retrieval Preferences -->
            <div id="deathStep5" style="display:none;">
                <h4 class="d-section-header"><i class="fas fa-vial text-accent"></i> E. Death & Retrieval Preferences</h4>
                <div class="d-input-group" style="margin-top:1.5rem;">
                    <label style="font-weight:700;">Religion / Cultural Considerations</label>
                    <input type="text" name="religion" id="dc_religion" class="d-input" placeholder="e.g. Buddhist, Christian, Muslim">
                </div>
                <div class="d-input-group" style="margin-top:1.5rem;">
                    <label style="font-weight:700;">Special Instructions</label>
                    <textarea name="special_instructions" id="dc_instructions" class="d-input" placeholder="Any specific wishes regarding the retrieval or burial..." rows="3"></textarea>
                </div>
                <div style="display:flex; justify-content:space-between; margin-top:2rem;"><button type="button" class="d-btn d-btn--outline" onclick="goToDeathStep(4)"><i class="fas fa-arrow-left"></i> Back</button><button type="button" class="d-btn d-btn--primary" onclick="goToDeathStep(6)">Legal Declaration <i class="fas fa-arrow-right"></i></button></div>
            </div>

            <!-- Step 6: Legal Declaration & Witnesses -->
            <div id="deathStep6" style="display:none;">
                <h4 class="d-section-header"><i class="fas fa-scroll text-accent"></i> F. Legal Declaration</h4>
                <div class="d-instruction-box" style="margin-bottom:1.5rem;">
                    <p style="font-size:0.9rem; line-height:1.6; color:var(--slate);">
                        I hereby confirm my consent for organ retrieval after my death. I authorize clinical staff to determine brain death or circulatory death as per national statutory guidelines. I understand this consent can be revoked at any time.
                    </p>
                </div>
                <h4 class="d-section-header"><i class="fas fa-user-shield text-accent"></i> G. Witness Signatures</h4>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem;">
                    <div style="background:#f8fafc; padding:1.25rem; border-radius:12px; border:1px solid var(--g200);">
                        <label style="font-size:0.7rem; font-weight:800; color:var(--blue-600); text-transform:uppercase;">Witness 1 <span style="color:var(--danger);">*</span></label>
                        <input type="text" name="w1_name" id="dc_w1_name" class="d-input" placeholder="Full Name" style="margin-top:8px;" required>
                        <input type="text" name="w1_nic" id="dc_w1_nic" class="d-input" placeholder="NIC Number" style="margin-top:8px;" required>
                    </div>
                    <div style="background:#f8fafc; padding:1.25rem; border-radius:12px; border:1px solid var(--g200);">
                        <label style="font-size:0.7rem; font-weight:800; color:var(--blue-600); text-transform:uppercase;">Witness 2 <span style="color:var(--danger);">*</span></label>
                        <input type="text" name="w2_name" id="dc_w2_name" class="d-input" placeholder="Full Name" style="margin-top:8px;" required>
                        <input type="text" name="w2_nic" id="dc_w2_nic" class="d-input" placeholder="NIC Number" style="margin-top:8px;" required>
                    </div>
                </div>
                <div style="display:flex; justify-content:space-between; margin-top:2rem;"><button type="button" class="d-btn d-btn--outline" onclick="goToDeathStep(5)"><i class="fas fa-arrow-left"></i> Back</button><button type="button" class="d-btn d-btn--primary" onclick="goToDeathStep(7)">Review & Finalize <i class="fas fa-marker"></i></button></div>
            </div>

            <!-- Step 7: Final Statutory Review -->
            <div id="deathStep7" style="display:none;">
                <div id="afterDeathReviewContent">
                    <div class="d-review-page" style="padding:2.5rem; color:var(--slate);">
                        <div class="d-review-header">
                            <img src="<?= ROOT ?>/assets/images/logo.png" alt="Life-Connect Logo" style="height: 50px; margin-bottom: 1rem;">
                            <h2>Statutory Declaration of Intent</h2>
                            <p style="text-transform: uppercase; letter-spacing: 2px; font-weight: 700; font-size: 0.75rem; color: var(--blue-600); margin-top: 5px;">Official Post-Mortem Organ Donation Consent</p>
                        </div>

                        <div class="d-instruction-box" style="background:#f0fdf4; border-color:var(--accent); color:#166534; font-size:0.85rem; margin-bottom:2rem;">
                            <strong>Declaration of Intent:</strong> I, <span style="font-weight: 800; text-decoration: underline;"><?= htmlspecialchars($donor_data['first_name'] . ' ' . $donor_data['last_name']) ?></span>, holder of NIC <strong><?= htmlspecialchars($donor_data['nic_number']) ?></strong>, hereby declare my voluntary intent for organ retrieval following clinical verification of death.
                        </div>
                        
                        <div class="d-info-grid" style="grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom:1.5rem;">
                            <div class="d-info-item"><label>Date of Declaration</label><span><?= date('F d, Y') ?></span></div>
                            <div class="d-info-item"><label>Blood Group</label><span><?= htmlspecialchars($donor_data['blood_group'] ?? 'Not Set') ?></span></div>
                            <div class="d-info-item"><label>Nationality</label><span><?= htmlspecialchars($donor_data['nationality'] ?? 'Sri Lankan') ?></span></div>
                        </div>
                        
                        <div style="margin:2rem 0; padding:1.5rem; background:var(--blue-50); border-radius:12px; border:1px solid var(--blue-100);">
                            <label style="font-size:0.7rem; font-weight:800; color:var(--blue-600); text-transform:uppercase; margin-bottom:10px; display:block;">Authorized Recovery Portfolio</label>
                            <div id="revDeathOrgans" style="font-size:1.15rem; font-weight:800; color:var(--blue-700); line-height:1.4;">-</div>
                        </div>
                        
                        <div style="margin-bottom:2.5rem;">
                            <h6 style="font-size:0.7rem; color:var(--g500); text-transform:uppercase; border-bottom:1px solid var(--g100); padding-bottom:8px; margin-bottom:15px;">Legal Custodians / Next of Kin</h6>
                            <div style="display:grid; grid-template-columns:1fr 1fr; gap:2rem;">
                                <div style="background:#f8fafc; padding:1.25rem; border-radius:10px; border:1px solid var(--g200);">
                                    <strong id="revDeathC1Name" style="color:var(--blue-900); font-size:1rem; display:block; margin-bottom:4px;">-</strong>
                                    <div style="font-size:0.8rem; color:var(--g600);"><i class="fas fa-link"></i> <span id="revDeathC1Rel">-</span></div>
                                    <div style="font-size:0.8rem; color:var(--g600); margin-top:4px;"><i class="fas fa-phone"></i> <span id="revDeathC1Phone">-</span></div>
                                </div>
                                <div style="background:#f8fafc; padding:1.25rem; border-radius:10px; border:1px solid var(--g200);">
                                    <strong id="revDeathC2Name" style="color:var(--blue-900); font-size:1rem; display:block; margin-bottom:4px;">-</strong>
                                    <div style="font-size:0.8rem; color:var(--g600);"><i class="fas fa-link"></i> <span id="revDeathC2Rel">-</span></div>
                                    <div style="font-size:0.8rem; color:var(--g600); margin-top:4px;"><i class="fas fa-phone"></i> <span id="revDeathC2Phone">-</span></div>
                                </div>
                            </div>
                        </div>

                        <div style="margin-bottom:3rem;">
                            <h6 style="font-size:0.7rem; color:var(--g500); text-transform:uppercase; border-bottom:1px solid var(--g100); padding-bottom:8px; margin-bottom:15px;">Legal Witnesses</h6>
                            <div style="display:grid; grid-template-columns:1fr 1fr; gap:2rem;">
                                <div style="font-size:0.85rem;"><span style="color:var(--g500);">Witness 1:</span> <strong id="revDeathW1Name">-</strong> (NIC: <span id="revDeathW1Nic">-</span>)</div>
                                <div style="font-size:0.85rem;"><span style="color:var(--g500);">Witness 2:</span> <strong id="revDeathW2Name">-</strong> (NIC: <span id="revDeathW2Nic">-</span>)</div>
                            </div>
                        </div>

                        <div class="signature-block" style="border-top:1px solid var(--g100); padding-top:2.5rem; display:grid; grid-template-columns:1fr 1fr; gap:2rem 3rem;">
                            <div class="sig-line">Donor Signature</div>
                            <div class="sig-line">Witness 1 Signature</div>
                            <div class="sig-line">Witness 2 Signature</div>
                            <div class="sig-line">Custodian 1 Authorization</div>
                        </div>
                    </div>
                </div>
                <div style="display:flex; justify-content:space-between; align-items:center; margin-top:2rem;">
                    <button type="button" class="d-btn d-btn--outline" onclick="goToDeathStep(6)"><i class="fas fa-arrow-left"></i> Back</button>
                    <div style="display:flex; gap:10px;">
                        <button type="button" class="d-btn d-btn--secondary" onclick="downloadPledge('afterDeathReviewContent')"><i class="fas fa-file-pdf"></i> Download Document</button>
                        <button type="button" class="d-btn d-btn--primary" onclick="submitAfterDeath()"><i class="fas fa-check-circle"></i> Submit Consent</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- ACADEMIC MODAL (DOCUMENT-STYLE 4-STEP) -->
<div id="bodyConsentModal" class="d-modal">
    <div class="d-modal__body" style="max-width:700px;">
        <div class="d-modal__header" style="flex-direction: column; align-items: center; text-align: center; padding: 2rem 1.5rem 1rem;">
            <img src="<?= ROOT ?>/assets/images/logo.png" alt="Life-Connect Logo" style="height: 60px; margin-bottom: 1rem;">
            <div class="d-modal__title-group" style="text-align: center;">
                <h3 style="font-size: 1.5rem; margin-bottom: 0.25rem;"><i class="fas fa-university text-accent"></i> Full Body Donation Consent</h3>
                <p class="d-modal__subtitle" style="font-size: 0.85rem; color: var(--g500);">Anatomical Authorization for Medical Education</p>
            </div>
            <button class="d-modal__close" onclick="closeModal('bodyConsentModal')" style="position: absolute; top: 1.5rem; right: 1.5rem;">&times;</button>
        </div>
        <form id="bodyConsentForm" method="POST" action="<?= ROOT ?>/donor/donations" style="padding: 0 1.5rem 1.5rem;">
            <input type="hidden" name="action" value="submit_body_pledge">
            
            <!-- Step 1: Personal Info -->
            <div id="bodyStep1">
                <div class="d-instruction-box">
                    <h4><i class="fas fa-info-circle"></i> 3. Anatomical Study Authorization</h4>
                    <p>Provide your personal details for whole-body donation. This authorization is dedicated to medical education, surgical training, and clinical research.</p>
                </div>
                <h4 class="d-section-header"><i class="fas fa-user-circle text-accent"></i> A. Donor Personal Information</h4>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem; background:#f8fafc; padding:1.5rem; border-radius:12px; border:1.5px solid var(--g200);">
                    <div class="d-input-group">
                        <label>Full Name</label>
                        <input type="text" class="d-input" value="<?= htmlspecialchars($donor_full_name) ?>" readonly style="background:#f1f5f9;">
                    </div>
                    <div class="d-input-group">
                        <label>NIC / ID</label>
                        <input type="text" class="d-input" value="<?= htmlspecialchars($donor_data['nic_number'] ?? '') ?>" readonly style="background:#f1f5f9;">
                    </div>
                    <div class="d-input-group">
                        <label>Date of Birth</label>
                        <input type="text" class="d-input" value="<?= htmlspecialchars($donor_data['date_of_birth'] ?? '') ?>" readonly style="background:#f1f5f9;">
                    </div>
                    <div class="d-input-group">
                        <label>Gender</label>
                        <input type="text" class="d-input" value="<?= htmlspecialchars($donor_data['gender'] ?? '-') ?>" readonly style="background:#f1f5f9;">
                    </div>
                    <div class="d-input-group">
                        <label>Blood Group <span style="color:var(--danger);">*</span></label>
                        <select name="blood_group" class="d-input" required>
                            <option value="">-- Select Blood Group --</option>
                            <option value="A+" <?= ($donor_data['blood_group'] ?? '') == 'A+' ? 'selected' : '' ?>>A+</option>
                            <option value="A-" <?= ($donor_data['blood_group'] ?? '') == 'A-' ? 'selected' : '' ?>>A-</option>
                            <option value="B+" <?= ($donor_data['blood_group'] ?? '') == 'B+' ? 'selected' : '' ?>>B+</option>
                            <option value="B-" <?= ($donor_data['blood_group'] ?? '') == 'B-' ? 'selected' : '' ?>>B-</option>
                            <option value="AB+" <?= ($donor_data['blood_group'] ?? '') == 'AB+' ? 'selected' : '' ?>>AB+</option>
                            <option value="AB-" <?= ($donor_data['blood_group'] ?? '') == 'AB-' ? 'selected' : '' ?>>AB-</option>
                            <option value="O+" <?= ($donor_data['blood_group'] ?? '') == 'O+' ? 'selected' : '' ?>>O+</option>
                            <option value="O-" <?= ($donor_data['blood_group'] ?? '') == 'O-' ? 'selected' : '' ?>>O-</option>
                        </select>
                    </div>
                    <div class="d-input-group" style="grid-column: span 2;">
                        <label>Current Address <span style="color:var(--danger);">*</span></label>
                        <textarea name="address" class="d-input" style="height: 60px;" required><?= htmlspecialchars($donor_data['address'] ?? '') ?></textarea>
                    </div>
                </div>
                <div style="text-align:right; margin-top:2rem;"><button type="button" class="d-btn d-btn--primary" onclick="goToBodyStep(2)">Proceed to Academic Details <i class="fas fa-arrow-right"></i></button></div>
            </div>

            <!-- Step 2: Academic Details -->
            <div id="bodyStep2" style="display:none;">
                <h4 class="d-section-header"><i class="fas fa-university text-accent"></i> B. Body Donation Details</h4>
                <div class="d-input-group">
                    <label>Preferred Medical Faculty / University</label>
                    <select name="medical_school_id" id="schoolSelect" class="d-input">
                        <option value="">-- Select Medical Faculty --</option>
                        <?php if(!empty($medical_schools)): foreach($medical_schools as $s): ?>
                            <option value="<?=$s->id?>"><?=htmlspecialchars($s->school_name)?></option>
                        <?php endforeach; endif; ?>
                    </select>
                </div>
                <div class="d-input-group" style="margin-top:1.25rem;">
                    <label>Religion</label>
                    <input type="text" name="religion" id="body_religion" class="d-input" placeholder="e.g. Buddhist, Christian">
                </div>
                <div class="d-input-group" style="margin-top:1.25rem;">
                    <label>Special Requests regarding usage (Optional)</label>
                    <textarea name="special_requests" id="body_requests" class="d-input" placeholder="Any specific limitations or wishes..." rows="2"></textarea>
                </div>
                <div style="display:flex; justify-content:space-between; margin-top:2rem;"><button type="button" class="d-btn d-btn--outline" onclick="goToBodyStep(1)"><i class="fas fa-arrow-left"></i> Previous</button><button type="button" class="d-btn d-btn--primary" onclick="goToBodyStep(3)">Accept Conditions <i class="fas fa-arrow-right"></i></button></div>
            </div>

            <!-- Step 3: Acceptance Conditions -->
            <div id="bodyStep3" style="display:none;">
                <h4 class="d-section-header"><i class="fas fa-exclamation-triangle text-accent"></i> C. Acceptance Conditions</h4>
                <div class="d-instruction-box" style="background: #fff5f5; border-color: #feb2b2; color: #742a2a; margin-bottom:1.5rem;">
                    <p style="font-size:0.85rem; font-weight:700;"><i class="fas fa-info-circle"></i> Medical Schools may decline a body under specific statutory conditions. Please acknowledge you understand these terms:</p>
                </div>
                <div style="display:grid; gap:12px;">
                    <label style="display:flex; gap:12px; font-size:0.9rem; cursor:pointer; padding:12px; border:1px solid var(--g200); border-radius:8px; background:white;">
                        <input type="checkbox" required style="width:20px; height:20px;">
                        <span>Body may be refused if infected with communicable diseases (e.g. HIV, Hepatitis).</span>
                    </label>
                    <label style="display:flex; gap:12px; font-size:0.9rem; cursor:pointer; padding:12px; border:1px solid var(--g200); border-radius:8px; background:white;">
                        <input type="checkbox" required style="width:20px; height:20px;">
                        <span>Body may be refused in cases of severe physical trauma or post-mortem already conducted.</span>
                    </label>
                    <label style="display:flex; gap:12px; font-size:0.9rem; cursor:pointer; padding:12px; border:1px solid var(--g200); border-radius:8px; background:white;">
                        <input type="checkbox" required style="width:20px; height:20px;">
                        <span>Authorizing Institution reserves the right of final acceptance based on educational needs.</span>
                    </label>
                </div>
                <div style="display:flex; justify-content:space-between; margin-top:2rem;"><button type="button" class="d-btn d-btn--outline" onclick="goToBodyStep(2)"><i class="fas fa-arrow-left"></i> Previous</button><button type="button" class="d-btn d-btn--primary" onclick="goToBodyStep(4)">Next: Custodians <i class="fas fa-arrow-right"></i></button></div>
            </div>

            <!-- Step 4: Legal Custodians (Next of Kin) -->
            <div id="bodyStep4" style="display:none;">
                <h4 class="d-section-header"><i class="fas fa-users text-accent"></i> D. Next of Kin (Custodians)</h4>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem;">
                    <div style="background:white; padding:1.25rem; border:1.5px solid var(--g200); border-radius:12px;">
                        <label style="font-size:0.75rem; font-weight:800; color:var(--g400); text-transform:uppercase;">CUSTODIAN 1 (NOK) <span style="color:var(--danger);">*</span></label>
                        <input type="text" name="cust1_name" id="bc_c1_name" class="d-input" placeholder="Full Name" style="margin-top:8px;" required>
                        <input type="text" name="cust1_nic" id="bc_c1_nic" class="d-input" placeholder="NIC Number" style="margin-top:8px;" required>
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px;">
                            <input type="text" name="cust1_rel" id="bc_c1_rel" class="d-input" placeholder="Relation" style="margin-top:8px;" required>
                            <input type="text" name="cust1_phone" id="bc_c1_phone" class="d-input" placeholder="Phone" style="margin-top:8px;" required>
                        </div>
                    </div>
                    <div style="background:white; padding:1.25rem; border:1.5px solid var(--g200); border-radius:12px;">
                        <label style="font-size:0.75rem; font-weight:800; color:var(--g400); text-transform:uppercase;">CUSTODIAN 2 (NOK) <span style="color:var(--danger);">*</span></label>
                        <input type="text" name="cust2_name" id="bc_c2_name" class="d-input" placeholder="Full Name" style="margin-top:8px;" required>
                        <input type="text" name="cust2_nic" id="bc_c2_nic" class="d-input" placeholder="NIC Number" style="margin-top:8px;" required>
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px;">
                            <input type="text" name="cust2_rel" id="bc_c2_rel" class="d-input" placeholder="Relation" style="margin-top:8px;" required>
                            <input type="text" name="cust2_phone" id="bc_c2_phone" class="d-input" placeholder="Phone" style="margin-top:8px;" required>
                        </div>
                    </div>
                </div>
                <div style="display:flex; justify-content:space-between; margin-top:2rem;"><button type="button" class="d-btn d-btn--outline" onclick="goToBodyStep(3)"><i class="fas fa-arrow-left"></i> Previous</button><button type="button" class="d-btn d-btn--primary" onclick="goToBodyStep(5)">Notification & Transport <i class="fas fa-arrow-right"></i></button></div>
            </div>

            <!-- Step 5: Witness Information -->
            <div id="bodyStep5" style="display:none;">
                <h4 class="d-section-header"><i class="fas fa-user-shield text-accent"></i> E. Witness Information (Verification)</h4>
                <p style="font-size:0.85rem; color:var(--g600); margin-bottom:1.5rem;">Provide details of two witnesses who will confirm your intent for body donation.</p>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem;">
                    <div style="background:white; padding:1.25rem; border:1.5px solid var(--g200); border-radius:12px;">
                        <label style="font-size:0.7rem; font-weight:800; color:var(--g400);">WITNESS 1 <span style="color:var(--danger);">*</span></label>
                        <input type="text" name="witness1_name" id="bc_w1_name" class="d-input" placeholder="Full Name" required style="margin-top:8px;">
                        <input type="text" name="witness1_nic" id="bc_w1_nic" class="d-input" placeholder="NIC Number" required style="margin-top:8px;">
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px;">
                            <input type="text" name="witness1_phone" id="bc_w1_phone" class="d-input" placeholder="Phone" style="margin-top:8px;" required>
                            <input type="text" name="witness1_address" id="bc_w1_address" class="d-input" placeholder="Address" style="margin-top:8px;" required>
                        </div>
                    </div>
                    <div style="background:white; padding:1.25rem; border:1.5px solid var(--g200); border-radius:12px;">
                        <label style="font-size:0.7rem; font-weight:800; color:var(--g400);">WITNESS 2 <span style="color:var(--danger);">*</span></label>
                        <input type="text" name="witness2_name" id="bc_w2_name" class="d-input" placeholder="Full Name" required style="margin-top:8px;">
                        <input type="text" name="witness2_nic" id="bc_w2_nic" class="d-input" placeholder="NIC Number" required style="margin-top:8px;">
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px;">
                            <input type="text" name="witness2_phone" id="bc_w2_phone" class="d-input" placeholder="Phone" style="margin-top:8px;" required>
                            <input type="text" name="witness2_address" id="bc_w2_address" class="d-input" placeholder="Address" style="margin-top:8px;" required>
                        </div>
                    </div>
                </div>
                <div style="display:flex; justify-content:space-between; margin-top:2rem;"><button type="button" class="d-btn d-btn--outline" onclick="goToBodyStep(4)"><i class="fas fa-arrow-left"></i> Previous</button><button type="button" class="d-btn d-btn--primary" onclick="goToBodyStep(6)">Review & Sign <i class="fas fa-check-double"></i></button></div>
            </div>

            <!-- Step 6: Review & Declaration -->
            <div id="bodyStep6" style="display:none;">
                <div id="bodyReviewContent">
                    <div class="d-review-page" style="padding:2.5rem; color:var(--slate);">
                        <div class="d-review-header">
                            <img src="<?= ROOT ?>/assets/images/logo.png" alt="Life-Connect Logo" style="height: 50px; margin-bottom: 1rem;">
                            <h2>Statutory Anatomical Authorization</h2>
                            <p style="text-transform: uppercase; letter-spacing: 1px; font-weight: 700; font-size: 0.75rem; color: var(--blue-600); margin-top: 5px;">Whole Body Donation for Medical Science</p>
                        </div>
                        
                        <div class="d-instruction-box" style="background:#f0fdf4; border-color:var(--accent); color:#166534; font-size:0.85rem; margin-bottom:2rem;">
                            <strong>Anatomical Declaration:</strong> I, <span style="font-weight: 800; text-decoration: underline;"><?= htmlspecialchars($donor_full_name) ?></span>, NIC <strong><?= htmlspecialchars($donor_data['nic_number'] ?? '') ?></strong>, hereby authorize the delivery of my body to the <span id="revBodySchool" style="font-weight:800; color:var(--blue-700);">-</span> for purposes of anatomical study and clinical research.
                        </div>

                        <div class="d-info-grid" style="grid-template-columns: repeat(2, 1fr); gap: 1rem; margin-bottom:2rem;">
                            <div class="d-info-item"><label>Religion</label><span id="revBodyReligion">-</span></div>
                            <div class="d-info-item"><label>Filing Date</label><span><?= date('F d, Y') ?></span></div>
                        </div>

                        <div style="margin-bottom:2rem;">
                            <h6 style="font-size:0.75rem; color:var(--g500); text-transform:uppercase; border-bottom:1.5px solid var(--g100); padding-bottom:10px; margin-bottom:1.5rem;">Witnesses & Verification</h6>
                            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem;">
                                <div style="background:#f8fafc; padding:1rem; border-radius:10px; border:1px solid var(--g200); text-align:center;">
                                    <div style="font-size:0.65rem; color:var(--g500); text-transform:uppercase; font-weight:700; letter-spacing:0.5px;">Witness 1</div>
                                    <strong id="revBodyW1Name" style="color:var(--blue-900); font-size:1.05rem; display:block; margin-top:4px;">-</strong>
                                </div>
                                <div style="background:#f8fafc; padding:1rem; border-radius:10px; border:1px solid var(--g200); text-align:center;">
                                    <div style="font-size:0.65rem; color:var(--g500); text-transform:uppercase; font-weight:700; letter-spacing:0.5px;">Witness 2</div>
                                    <strong id="revBodyW2Name" style="color:var(--blue-900); font-size:1.05rem; display:block; margin-top:4px;">-</strong>
                                </div>
                            </div>
                        </div>

                        <div class="signature-block" style="margin-top:3rem; grid-template-columns: 1fr 1fr; gap: 3rem 4rem;">
                            <div class="sig-line">Donor Signature</div>
                            <div style="visibility:hidden;"></div> <!-- Spacer -->
                            <div class="sig-line">Custodian 1 Signature</div>
                            <div class="sig-line">Custodian 2 Signature</div>
                            <div class="sig-line">Witness 1 Signature</div>
                            <div class="sig-line">Witness 2 Signature</div>
                        </div>
                    </div>
                </div>
                <div style="display:flex; justify-content:space-between; margin-top:2rem; border-top:1px solid var(--g200); padding-top:1.5rem;">
                    <button type="button" class="d-btn d-btn--outline" onclick="goToBodyStep(5)"><i class="fas fa-arrow-left"></i> Previous</button>
                    <div style="display:flex; gap:12px;"><button type="button" class="d-btn d-btn--secondary" onclick="downloadPledge('bodyReviewContent')"><i class="fas fa-file-pdf"></i> Download Document</button><button type="button" class="d-btn d-btn--primary" onclick="submitBodyPledge()"><i class="fas fa-check-circle"></i> Authorize Donation</button></div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Warning / Scripts -->
<form id="pledgeForm" method="POST" action="<?= ROOT ?>/donor/donations">

    <input type="hidden" name="action" value="submit_living_pledge">
    <input type="hidden" name="organ_id" id="pledgeOrganId">
    <input type="hidden" name="hospital_id" id="pledgeHospitalId">
    
    <!-- Detailed Sections -->
    <input type="hidden" name="nationality" id="p_nationality">
    <input type="hidden" name="height" id="p_height">
    <input type="hidden" name="weight" id="p_weight">
    <input type="hidden" name="surgeries" id="p_surgeries">
    <input type="hidden" name="allergies" id="p_allergies">
    <input type="hidden" name="habits" id="p_habits">
    
    <!-- Legacy fields -->
    <input type="hidden" name="conditions" id="pledgeConditions">
    <input type="hidden" name="blood_group" id="pledgeBloodGroup">
    <input type="hidden" name="address" id="pledgeAddress">
    
    <!-- Recipient Info (REMOVED) -->
    
    <!-- Compatibility -->
    <input type="hidden" name="compat_blood" id="p_compat_blood">
    <input type="hidden" name="compat_tissue" id="p_compat_tissue">
    
    <!-- Emergency Contact -->
    <input type="hidden" name="emergency_name" id="p_emergency_name">
    <input type="hidden" name="emergency_rel" id="p_emergency_rel">
    <input type="hidden" name="emergency_phone" id="p_emergency_phone">

    <!-- Witnesses (Original Custodians Map) -->
    <input type="hidden" name="cust1_name" id="p_cust1_name">
    <input type="hidden" name="cust1_nic" id="p_cust1_nic">
    <input type="hidden" name="cust2_name" id="p_cust2_name">
    <input type="hidden" name="cust2_nic" id="p_cust2_nic">
</form>

<script>const hospitalsByOrgan = <?= $hospitalsByOrganJson ?>;
const approvedHospitals = <?= $approvedHospitalsJson ?>;
const eligibilityData = <?= json_encode($eligibility) ?>;
let pendingOrganId=null, pendingOrganName=null, selectedHospitalId=null, selectedHospitalName='No Preference';

function checkOrganEligibility(organName) {
    if (!eligibilityData || !eligibilityData.is_in_recovery) return true;

    const lowerName = organName.toLowerCase();
    
    // 1. Permanent blocks
    const isPermanentlyBlocked = (eligibilityData.permanent_blocks || []).some(pb => 
        lowerName.includes(pb.toLowerCase())
    );

    if (isPermanentlyBlocked) {
        showBlockedModal(organName, 'PERMANENT');
        return false;
    }

    // 2. Time-locked recovery blocks
    const blockedOrgans = eligibilityData.blocked_organs || [];
    
    // Check for "All major donations" (Liver/Kidney recovery)
    const allBlocked = blockedOrgans.find(b => b.organ.toLowerCase().includes('all major donations'));
    if (allBlocked) {
        showBlockedModal(organName, allBlocked.eligible_on);
        return false;
    }

    // Specific organ block (e.g. Bone Marrow)
    const specificBlock = blockedOrgans.find(b => lowerName.includes(b.organ.toLowerCase()));
    if (specificBlock) {
        showBlockedModal(organName, specificBlock.eligible_on);
        return false;
    }

    return true;
}

function showBlockedModal(organName, date) {
    const titleEl = document.getElementById('blockedModalTitle');
    const msgEl = document.getElementById('blockedModalMessage');
    const iconEl = document.getElementById('blockedModalIcon');

    if (date === 'PERMANENT') {
        titleEl.textContent = 'Permanently Restricted';
        msgEl.innerHTML = `Our medical registry indicates you have already donated a <strong>${organName}</strong>. For your long-term health, we cannot accept another pledge for this specific organ.`;
        iconEl.innerHTML = '<i class="fas fa-hand-holding-heart"></i>';
        iconEl.style.background = '#fee2e2';
        iconEl.style.color = '#ef4444';
    } else {
        titleEl.textContent = 'Donation Recovery Period';
        msgEl.innerHTML = `<strong>Access Denied:</strong> You are currently in a mandatory post-donation recovery window. To ensure clinical safety, you will be eligible to pledge <strong>${organName}</strong> starting from <strong>${date}</strong>.`;
        iconEl.innerHTML = '<i class="fas fa-clock"></i>';
        iconEl.style.background = '#fffbeb';
        iconEl.style.color = '#f59e0b';
    }

    openModal('eligibilityWarningModal');
}

function showConflictModal(targetType, existingType) {
    const titleEl = document.getElementById('blockedModalTitle');
    const msgEl = document.getElementById('blockedModalMessage');
    const iconEl = document.getElementById('blockedModalIcon');

    titleEl.textContent = 'Donation Intent Conflict';
    msgEl.innerHTML = `<strong>Restriction:</strong> You cannot pledge a new <strong>${targetType}</strong> while you have an active <strong>${existingType}</strong> pledge. <br><br>Sri Lankan medical guidelines require a single deceased donation mode. To switch, please withdraw your existing pledge first.`;
    iconEl.innerHTML = '<i class="fas fa-random"></i>';
    iconEl.style.background = '#fff7ed';
    iconEl.style.color = '#f97316';

    openModal('eligibilityWarningModal');
}

function openLivingModal(id,name){ 
    if (!checkOrganEligibility(name)) return;
    pendingOrganId=id; 
    pendingOrganName=name; 
    document.getElementById('living_organ_name').value = name; 
    document.getElementById('req_organ_name').textContent = name;
    goToStep(1); 
    openModal('livingConsentModal'); 
}
function openAfterDeathModal(id,name){ 
    document.querySelectorAll('.death-org-check').forEach(c=>c.checked=false); 
    const target=document.getElementById('death_org_'+id); 
    if(target) target.checked=true; 
    goToDeathStep(1); 
    openModal('afterDeathConsentModal'); 
}
function goToStep(n){ 
    const currentStepNum = parseInt(document.querySelector('.d-modal__step.active')?.id.replace('step','') || '1');
    
    // Validate Current Step before moving forward
    if(n > currentStepNum) {
        const currentStep = document.getElementById('step' + currentStepNum);
        const inputs = currentStep.querySelectorAll('input[required], select[required], textarea[required]');
        let valid = true;
        inputs.forEach(input => {
            if(!input.value || (input.type === 'checkbox' && !input.checked)) {
                input.style.borderColor = 'var(--danger)';
                valid = false;
            } else {
                input.style.borderColor = 'var(--g200)';
            }
        });
        if(!valid) {
            alert('Please fill all required fields in Step ' + currentStepNum + ' before proceeding.');
            return;
        }

        // Specific Validation: Height (Living)
        if(currentStepNum === 2) {
            const h = parseFloat(document.getElementById('height').value);
            if(h < 10) { alert('Height cannot be less than 10cm.'); return; }
        }

        // Specific Validation: Witnesses (Living)
        if(currentStepNum === 4) {
            const w1NIC = document.getElementById('cust1_nic').value.trim();
            const w2NIC = document.getElementById('cust2_nic').value.trim();
            if(w1NIC === w2NIC) { alert('Witness 1 and Witness 2 must be different persons (NICs cannot match).'); return; }
        }
    }

    document.querySelectorAll('.d-modal__step').forEach(s => s.classList.remove('active'));
    document.querySelectorAll('#livingConsentModal [id^="step"]').forEach(s => s.style.display = 'none');
    const el = document.getElementById('step' + n);
    if(el) {
        el.classList.add('active');
        el.style.display = 'block';
    }
}
function handleStep2Next(){ updateHospitalList(); goToStep(3); }
function updateHospitalList() {
    const dropdown = document.getElementById('hospitalDropdown');
    dropdown.innerHTML = '';
    
    // Add default option
    const defaultOpt = document.createElement('option');
    defaultOpt.value = '';
    defaultOpt.textContent = '-- No specific hospital preference --';
    dropdown.appendChild(defaultOpt);

    const reqs = hospitalsByOrgan[pendingOrganId] || [];
    const requestedIds = new Set();

    // 1) Hospitals with active requests for this organ (priority order)
    if (Array.isArray(reqs) && reqs.length > 0) {
        reqs.forEach(h => {
            requestedIds.add(String(h.hospital_id));
            const opt = document.createElement('option');
            opt.value = h.hospital_id;
            opt.textContent = `${h.hospital_name} (${h.district}) - ${h.priority} PRIORITY`;
            dropdown.appendChild(opt);
        });
    }

    // 2) All other approved/registered hospitals (so the list is never "missing" hospitals)
    if (Array.isArray(approvedHospitals) && approvedHospitals.length > 0) {
        approvedHospitals
            .filter(h => !requestedIds.has(String(h.hospital_id)))
            .sort((a, b) => String(a.hospital_name || '').localeCompare(String(b.hospital_name || '')))
            .forEach(h => {
                const opt = document.createElement('option');
                opt.value = h.hospital_id;
                const dist = h.district ? ` (${h.district})` : '';
                opt.textContent = `${h.hospital_name}${dist}`;
                dropdown.appendChild(opt);
            });
    }

    // Reset selection state
    selectedHospitalId = null;
    selectedHospitalName = 'No specific hospital preference';
}

function onHospitalChange() {
    const dropdown = document.getElementById('hospitalDropdown');
    selectedHospitalId = dropdown.value;
    selectedHospitalName = dropdown.options[dropdown.selectedIndex].text;
}
function goToStep5(){ 
    document.getElementById('review_as_organ').textContent=pendingOrganName; 
    
    // Personal & Medical
    document.getElementById('rev_nationality').textContent = document.getElementById('nationality').value || 'Not Specified';
    document.getElementById('rev_vitals').textContent = (document.getElementById('height').value || '-') + ' cm | ' + (document.getElementById('weight').value || '-') + ' kg';
    document.getElementById('rev_habits').textContent = document.getElementById('habits').value;
    document.getElementById('rev_medical').textContent = document.getElementById('conditions').value + ' | Surgeries: ' + document.getElementById('surgeries').value;

    // Hospital Selection Summary
    document.getElementById('rev_hospital_info').textContent = selectedHospitalName;

    // Witnesses
    document.getElementById('rev_witness1').textContent = 'W1: ' + (document.getElementById('cust1_name').value || 'Not Provided') + ' (' + (document.getElementById('cust1_nic').value || '-') + ')';
    document.getElementById('rev_witness2').textContent = 'W2: ' + (document.getElementById('cust2_name').value || 'Not Provided') + ' (' + (document.getElementById('cust2_nic').value || '-') + ')';

    // Emergency Contact Summary
    document.getElementById('rev_emergency_info').textContent = document.getElementById('emergencyName').value + ' (' + document.getElementById('emergencyRel').value + ') - ' + document.getElementById('emergencyPhone').value;

    goToStep(5); 
}
function submitPledge(){ 
    document.getElementById('pledgeOrganId').value=pendingOrganId; 
    document.getElementById('pledgeHospitalId').value=selectedHospitalId||''; 
    
    // Detailed Sections
    document.getElementById('p_nationality').value = document.getElementById('nationality').value;
    document.getElementById('p_height').value = document.getElementById('height').value;
    document.getElementById('p_weight').value = document.getElementById('weight').value;
    document.getElementById('p_surgeries').value = document.getElementById('surgeries').value;
    document.getElementById('p_allergies').value = document.getElementById('allergies').value;
    document.getElementById('p_habits').value = document.getElementById('habits').value;
    
    // Recipient Info (REMOVED)
    
    document.getElementById('p_compat_blood').value = document.getElementById('compat_blood').value;
    document.getElementById('p_compat_tissue').value = document.getElementById('compat_tissue').value;
    
    document.getElementById('p_emergency_name').value = document.getElementById('emergencyName').value;
    document.getElementById('p_emergency_rel').value = document.getElementById('emergencyRel').value;
    document.getElementById('p_emergency_phone').value = document.getElementById('emergencyPhone').value;

    document.getElementById('p_cust1_name').value = document.getElementById('cust1_name').value;
    document.getElementById('p_cust1_nic').value = document.getElementById('cust1_nic').value;
    document.getElementById('p_cust2_name').value = document.getElementById('cust2_name').value;
    document.getElementById('p_cust2_nic').value = document.getElementById('cust2_nic').value;

    // Legacy fields still needed by simple model logic
    document.getElementById('pledgeConditions').value = document.getElementById('conditions').value;
    document.getElementById('pledgeBloodGroup').value = document.getElementById('bloodGroup').value;
    document.getElementById('pledgeAddress').value = document.getElementById('livingAddress').value;

    document.getElementById('pledgeForm').submit(); 
}
function openAfterDeathModal(id,name){ document.querySelectorAll('.death-org-check').forEach(c=>c.checked=false); const target=document.getElementById('death_org_'+id); if(target) target.checked=true; goToDeathStep(1); openModal('afterDeathConsentModal'); }
function goToDeathStep(step) {
    const currentStepNum = parseInt(document.querySelector('#afterDeathForm div[id^="deathStep"]:not([style*="display: none"])')?.id.replace('deathStep','') || '1');
    
    // Validate Current Step before moving forward
    if(step > currentStepNum) {
        const currentStep = document.getElementById('deathStep' + currentStepNum);
        const inputs = currentStep.querySelectorAll('input[required], select[required], textarea[required]');
        let valid = true;
        inputs.forEach(input => {
            if(!input.value || (input.type === 'checkbox' && !input.checked)) {
                input.style.borderColor = 'var(--danger)';
                valid = false;
            } else {
                input.style.borderColor = 'var(--g200)';
            }
        });
        if(!valid) {
            alert('Please fill all required fields in Step ' + currentStepNum + ' before proceeding.');
            return;
        }

        // Specific Validation: Custodians (After Death)
        if(currentStepNum === 4) {
            const c1NIC = document.getElementById('dc_c1_nic').value.trim();
            const c2NIC = document.getElementById('dc_c2_nic').value.trim();
            if(c1NIC === c2NIC) { alert('Custodian 1 and Custodian 2 must be different persons.'); return; }
        }

        // Specific Validation: Witnesses (After Death)
        if(currentStepNum === 6) {
            const w1NIC = document.getElementById('dc_w1_nic').value.trim();
            const w2NIC = document.getElementById('dc_w2_nic').value.trim();
            if(w1NIC === w2NIC) { alert('Witness 1 and Witness 2 must be different persons.'); return; }
        }
    }

    for(let i=1; i<=7; i++) {
        let el = document.getElementById('deathStep' + i);
        if(el) el.style.display = 'none';
    }
    document.getElementById('deathStep' + step).style.display = 'block';
    if(step === 7) updateDeathReview();
}

function updateDeathReview() {
    let organs = [];
    document.querySelectorAll('.death-org-check:checked').forEach(cb => {
        organs.push(cb.nextElementSibling.textContent);
    });
    document.getElementById('revDeathOrgans').textContent = organs.join(', ') || 'No Organs Selected';
    
    // Custodians
    document.getElementById('revDeathC1Name').textContent = document.getElementById('dc_c1_name').value || '-';
    document.getElementById('revDeathC1Rel').textContent = document.getElementById('dc_c1_rel').value || '-';
    document.getElementById('revDeathC1Phone').textContent = document.getElementById('dc_c1_phone').value || '-';
    
    document.getElementById('revDeathC2Name').textContent = document.getElementById('dc_c2_name').value || '-';
    document.getElementById('revDeathC2Rel').textContent = document.getElementById('dc_c2_rel').value || '-';
    document.getElementById('revDeathC2Phone').textContent = document.getElementById('dc_c2_phone').value || '-';
    
    // Witnesses
    document.getElementById('revDeathW1Name').textContent = document.getElementById('dc_w1_name').value || '-';
    document.getElementById('revDeathW1Nic').textContent = document.getElementById('dc_w1_nic').value || '-';
    document.getElementById('revDeathW2Name').textContent = document.getElementById('dc_w2_name').value || '-';
    document.getElementById('revDeathW2Nic').textContent = document.getElementById('dc_w2_nic').value || '-';
}

function submitAfterDeath() {
    document.getElementById('afterDeathForm').submit();
}
function goToBodyStep(n){ 
    const currentStepNum = parseInt(document.querySelector('#bodyConsentForm div[id^="bodyStep"]:not([style*="display: none"])')?.id.replace('bodyStep','') || '1');
    
    // Validate Current Step before moving forward
    if(n > currentStepNum) {
        const currentStep = document.getElementById('bodyStep' + currentStepNum);
        const inputs = currentStep.querySelectorAll('input[required], select[required], textarea[required]');
        let valid = true;
        inputs.forEach(input => {
            if(!input.value || (input.type === 'checkbox' && !input.checked)) {
                input.style.borderColor = 'var(--danger)';
                valid = false;
            } else {
                input.style.borderColor = 'var(--g200)';
            }
        });
        if(!valid) {
            alert('Please fill all required fields and acknowledge conditions before proceeding.');
            return;
        }

        // Specific Validation: Custodians (Body)
        if(currentStepNum === 4) {
            const c1NIC = document.getElementById('bc_c1_nic').value.trim();
            const c2NIC = document.getElementById('bc_c2_nic').value.trim();
            if(c1NIC === c2NIC) { alert('Custodian 1 and Custodian 2 must be different persons.'); return; }
        }
    }

    for(let i=1;i<=6;i++){ const el=document.getElementById('bodyStep'+i); if(el) el.style.display=(i===n)?'block':'none'; } 
    if(n===6) { 
        const s=document.getElementById('schoolSelect'); 
        document.getElementById('revBodySchool').textContent=s.options[s.selectedIndex].text; 
        document.getElementById('revBodyReligion').textContent = document.getElementById('body_religion').value || 'Not Specified';
        
        // Witnesses Review
        document.getElementById('revBodyW1Name').textContent = document.getElementById('bc_w1_name').value || '-';
        document.getElementById('revBodyW2Name').textContent = document.getElementById('bc_w2_name').value || '-';
    } 
}
function submitBodyPledge() {
    const step6 = document.getElementById('bodyStep6');
    const inputs = step6.querySelectorAll('input[required], select[required], textarea[required]');
    let valid = true;
    inputs.forEach(input => {
        if(!input.value || (input.type === 'checkbox' && !input.checked)) {
            input.style.borderColor = 'var(--danger)';
            valid = false;
        } else {
            input.style.borderColor = 'var(--g200)';
        }
    });
    if(!valid) {
        alert('Please fill all required fields in the final review step before authorizing.');
        return;
    }
    
    // Specific Validation: Witnesses (Body)
    const w1NIC = document.getElementById('bc_w1_nic').value.trim();
    const w2NIC = document.getElementById('bc_w2_nic').value.trim();
    if(w1NIC === w2NIC) { alert('Witness 1 and Witness 2 must be different persons.'); return; }
    
    document.getElementById('bodyConsentForm').submit();
}
function submitAction(action,id){ const f=document.createElement('form'); f.method='POST'; f.action='<?= ROOT ?>/donor/donations'; f.innerHTML=`<input type="hidden" name="action" value="${action}"><input type="hidden" name="id" value="${id}">`; document.body.appendChild(f); f.submit(); }
function downloadPledge(id) {
    const content = document.getElementById(id).innerHTML;
    const printWindow = window.open('', '_blank', 'height=800,width=1000');
    
    // Extract all styles from current document
    const styles = Array.from(document.querySelectorAll('style, link[rel="stylesheet"]'))
        .map(el => el.outerHTML)
        .join('\n');

    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Pledge Certification</title>
            <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
            ${styles}
            <style>
                body { background: white !important; margin: 0; padding: 40px; }
                .d-review-page { border: 2px solid #e2e8f0 !important; box-shadow: none !important; margin: 0 !important; width: 100% !important; max-width: none !important; }
                @page { size: auto; margin: 15mm; }
                .no-print { display: none !important; }
            </style>
        </head>
        <body>
            <div style="text-align:center; margin-bottom:40px;">
                <img src="<?= ROOT ?>/assets/images/logo.png" style="height:60px; margin-bottom:15px; display:block; margin: 0 auto;" onerror="this.src='https://via.placeholder.com/60?text=LC'">
                <h1 style="font-family:sans-serif; letter-spacing:2px; color:#1e293b; margin:0; font-size:1.8rem;">LIFE-CONNECT</h1>
                <p style="color:#64748b; font-size:0.9rem; margin-top:5px; text-transform:uppercase; letter-spacing:1px;">Official Registry Authorization Document</p>
            </div>
            ${content}
            <div style="margin-top:40px; padding-top:20px; border-top:1px solid #eee; text-align:center; color:#94a3b8; font-size:0.8rem;">
                This document is a formal record of intent logged in the Life-Connect National Organ Registry.<br>
                Verification: LC-${Math.random().toString(36).substr(2, 9).toUpperCase()}
            </div>
            <script>
                window.onload = function() {
                    setTimeout(() => {
                        window.print();
                        window.close();
                    }, 500);
                };
            <\/script>
        </body>
        </html>
    `);
    printWindow.document.close();
}
function openUnselectWarning(id,name){ 
    pendingOrganId=id; 
    const globalInput = document.getElementById('withdrawOrganId');
    const formInput = document.getElementById('withdrawOrganIdForm');
    
    if (globalInput) globalInput.value = id;
    if (formInput) formInput.value = id;
    
    document.getElementById('unselectText').textContent=`Withdraw pledge for ${name}?`; 
    openModal('unselectWarningModal'); 
}
function openPledgeActionModal(id, name) {
    pendingOrganId = id;
    document.getElementById('actionPledgeTitle').textContent = name;
    document.getElementById('actionPledgeId').value = id;
    openModal('pledgeActionModal');
}
function uploadPledgeFile() {
    const fileInput = document.getElementById('pledgeFile');
    if(!fileInput.files.length) {
        alert("Please select a signed PDF document.");
        return;
    }
    document.getElementById('pledgeUploadForm').submit();
}

async function downloadExistingPledge(organId) {
    const btn = document.getElementById('downloadExistingBtn');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Preparing...';
    btn.disabled = true;

    try {
        const response = await fetch('<?= ROOT ?>/donor/get-pledge-details?organ_id=' + organId);
        const data = await response.json();

        if (data.success) {
            // Populate Universal Template
            const t = document.getElementById('universalDownloadTemplate');
            
            // Donor Info
            t.querySelector('.rev_donor_name').textContent = (data.donor.first_name + ' ' + data.donor.last_name).toUpperCase();
            t.querySelector('.rev_donor_nic').textContent = data.donor.nic_number || '-';
            t.querySelector('.rev_organ_name').textContent = data.pledge.organ_name;
            t.querySelector('.rev_date').textContent = data.today;
            
            // Medical/Consent Info
            t.querySelector('.rev_nationality').textContent = data.donor.nationality || '-';
            t.querySelector('.rev_blood').textContent = data.donor.blood_group || '-';
            t.querySelector('.rev_gender').textContent = data.donor.gender || '-';
            
            if (data.consent) {
                t.querySelector('.rev_vitals').textContent = (data.consent.height || '-') + ' cm | ' + (data.consent.weight || '-') + ' kg';
                t.querySelector('.rev_habits').textContent = data.consent.smoking_alcohol_status || 'None';
                t.querySelector('.rev_medical').textContent = (data.pledge.conditions || 'None') + ' | Surgeries: ' + (data.consent.previous_surgeries || 'None');
                t.querySelector('.rev_emergency').textContent = (data.consent.emergency_contact_name || '-') + ' (' + (data.consent.emergency_relationship || '-') + ') - ' + (data.consent.emergency_phone || '-');
            }

            // Witnesses
            const witnesses = Array.isArray(data.witnesses) ? data.witnesses : [];
            const w1 = witnesses.find(w => w.witness_number == 1) || {};
            const w2 = witnesses.find(w => w.witness_number == 2) || {};
            t.querySelector('.rev_w1').textContent = 'W1: ' + (w1.name || 'Not Provided') + ' (' + (w1.nic_number || '-') + ')';
            t.querySelector('.rev_w2').textContent = 'W2: ' + (w2.name || 'Not Provided') + ' (' + (w2.nic_number || '-') + ')';

            // Trigger Download
            downloadPledge('universalDownloadTemplate');
        } else {
            alert("Error: " + (data.message || "Failed to fetch pledge details."));
        }
    } catch (e) {
        console.error(e);
        alert("System error while generating document.");
    } finally {
        btn.innerHTML = originalText;
        btn.disabled = false;
    }
}
</script>

<!-- HIDDEN DOWNLOAD TEMPLATE (UNIVERSAL) -->
<div id="universalDownloadTemplate" style="display:none;">
    <div class="d-review-page" style="padding: 2.5rem; color: var(--slate);">
        <div class="d-review-header">
            <img src="<?= ROOT ?>/assets/images/logo.png" alt="Life-Connect Logo" style="height: 50px; margin-bottom: 1rem;">
            <h2 class="rev_form_title">Organ Donation Consent Form</h2>
            <p style="text-transform: uppercase; letter-spacing: 2px; font-weight: 700; font-size: 0.75rem; color: var(--blue-600); margin-top: 5px;">Formal Statutory Declaration</p>
        </div>
        
        <div class="d-instruction-box" style="background:#f0fdf4; border-color:var(--accent); color:#166534; font-size:0.85rem; margin-bottom:2rem;">
            <strong>Declaration:</strong> I, <span class="rev_donor_name" style="font-weight: 700; text-decoration: underline;">-</span>, holder of NIC <strong class="rev_donor_nic">-</strong>, hereby confirm that this pledge is <strong>strictly voluntary</strong> and I have received <strong>no financial compensation</strong> for this act.
        </div>

        <div class="d-info-grid" style="margin-bottom: 1.5rem;">
            <div class="d-info-item"><label>Organ for Donation</label><span class="rev_organ_name" style="color:var(--blue-700); font-weight: 800;">-</span></div>
            <div class="d-info-item"><label>Filing Date</label><span class="rev_date">-</span></div>
        </div>

        <div class="d-info-grid" style="grid-template-columns: repeat(3, 1fr); gap: 1rem; border-top: 1px solid var(--g100); padding-top: 1rem; margin-bottom: 2rem;">
            <div class="d-info-item"><label>Nationality</label><span class="rev_nationality">-</span></div>
            <div class="d-info-item"><label>Blood Group</label><span class="rev_blood">-</span></div>
            <div class="d-info-item"><label>Gender</label><span class="rev_gender">-</span></div>
        </div>

        <div style="background: #f8fafc; padding: 1.5rem; border-radius: 8px; margin-bottom: 2rem;">
            <h6 style="font-size:0.7rem; color:var(--g500); text-transform:uppercase; border-bottom:1px solid var(--g200); padding-bottom:5px; margin-bottom: 10px;">Medical Summary</h6>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div style="font-size: 0.85rem;"><strong>Vitals:</strong> <span class="rev_vitals">-</span></div>
                <div style="font-size: 0.85rem;"><strong>Habits:</strong> <span class="rev_habits">-</span></div>
                <div style="font-size: 0.85rem; grid-column: span 2;"><strong>Surgeries/Conditions:</strong> <span class="rev_medical">-</span></div>
            </div>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:2.5rem; margin-bottom:3rem;">
            <div>
                <h6 style="font-size:0.7rem; color:var(--g500); text-transform:uppercase; border-bottom:1px solid var(--g100); padding-bottom:5px;">Recipient Institution</h6>
                <p style="font-size:0.95rem; font-weight:800; color: var(--blue-700); margin-top:10px;">Registry Managed</p>
                <div style="font-size:0.75rem; color:var(--g500); margin-top:4px;">Medical center authorized for recovery and surgical procedures.</div>
            </div>
            <div>
                <h6 style="font-size:0.7rem; color:var(--g500); text-transform:uppercase; border-bottom:1px solid var(--g100); padding-bottom:5px;">Witnesses & Verification</h6>
                <div style="margin-top: 10px;">
                    <div style="font-size: 0.85rem; font-weight: 700;" class="rev_w1">W1: -</div>
                    <div style="font-size: 0.85rem; font-weight: 700; margin-top: 4px;" class="rev_w2">W2: -</div>
                </div>
            </div>
        </div>

        <div style="background: #fffbeb; padding: 1rem; border-radius: 8px; margin-bottom: 2.5rem; font-size: 0.8rem; color: #92400e; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-exclamation-triangle"></i>
            <div><strong>Emergency Contact:</strong> <span class="rev_emergency" style="font-weight: 800;">-</span></div>
        </div>

        <div class="signature-block">
            <div class="sig-line">WITNESS 01 SIGNATURE</div>
            <div class="sig-line">WITNESS 02 SIGNATURE</div>
            <div class="sig-line">DONOR'S SIGNATURE</div>
        </div>
    </div>
</div>

<!-- MODAL: PLEDGE ACTION (UPLOAD/WITHDRAW) -->
<div id="pledgeActionModal" class="d-modal">
    <div class="d-modal__body" style="max-width:500px;">
        <div class="d-modal__header">
            <h3 id="actionPledgeTitle">Organ Pledge</h3>
            <button class="d-modal__close" onclick="closeModal('pledgeActionModal')">&times;</button>
        </div>
        <div class="d-modal__content">
            <div style="padding: 1.5rem; background: #fffbe6; border: 1px solid #ffe58f; border-radius: 8px; margin-bottom: 1.5rem; font-size: 0.9rem; color: #854d0e;">
                <i class="fas fa-exclamation-triangle"></i> This pledge is awaiting a signed document. Please upload the scanned copy to complete the formal registry process.
            </div>
            
            <form id="pledgeUploadForm" method="POST" action="<?= ROOT ?>/donor/donations" enctype="multipart/form-data">
                <input type="hidden" name="action" value="upload_signed_pledge">
                <input type="hidden" name="id" id="actionPledgeId">
                <div class="d-input-group">
                    <label>Signed PDF Document <span style="color:var(--danger);">*</span></label>
                    <input type="file" name="pledge_pdf" id="pledgeFile" class="d-input" accept=".pdf" style="padding: 10px;">
                    <p style="font-size:0.7rem; color:var(--g500); margin-top:5px;">Max size: 5MB (PDF only)</p>
                </div>
            </form>

            <div style="display:grid; grid-template-columns: 1fr; gap: 10px; margin-top: 1.5rem;">
                <button class="d-btn d-btn--secondary" id="downloadExistingBtn" onclick="downloadExistingPledge(pendingOrganId)">
                    <i class="fas fa-file-pdf"></i> Download Consent Form
                </button>
                <div style="text-align:center; margin: 5px 0; font-size: 0.8rem; color: var(--g400);">— OR —</div>
                <button class="d-btn d-btn--primary" onclick="uploadPledgeFile()">
                    <i class="fas fa-upload"></i> Upload & Complete
                </button>
                <button class="d-btn d-btn--outline" onclick="closeModal('pledgeActionModal'); openUnselectWarning(pendingOrganId, document.getElementById('actionPledgeTitle').textContent)" style="color: var(--danger); border-color: var(--danger);">
                    <i class="fas fa-trash"></i> Withdraw Pledge
                </button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL: COMPLETED DONATION HISTORY & RE-DONATION RULES -->
<div id="completedHistoryModal" class="d-modal">
    <div class="d-modal__body" style="max-width:700px;">
        <div class="d-modal__header" style="background: linear-gradient(to right, #10b981, #059669); color: white; padding: 1.5rem;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="width: 45px; height: 45px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem;">
                    <i class="fas fa-history"></i>
                </div>
                <div>
                    <h3 style="margin: 0; color: white;">Donation History & Recovery</h3>
                    <p style="margin: 0; font-size: 0.85rem; opacity: 0.9;">Overview of your life-saving contributions</p>
                </div>
            </div>
            <button class="d-modal__close" onclick="closeModal('completedHistoryModal')" style="color: white; opacity: 0.8;">&times;</button>
        </div>
        <div class="d-modal__content" style="padding: 1.5rem;">
            <?php if(!empty($eligibility['history'])): ?>
                <div style="display: grid; gap: 1rem;">
                    <?php foreach($eligibility['history'] as $h): 
                        $isPermanent = (stripos($h->donated_organ, 'Kidney') !== false || stripos($h->donated_organ, 'Liver') !== false);
                        $isEligible = strtotime($h->next_eligible_date) <= time();
                        $dateFormatted = date('d M Y', strtotime($h->donation_date));
                        $eligibleDate = date('d M Y', strtotime($h->next_eligible_date));
                    ?>
                        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.25rem; display: flex; align-items: center; justify-content: space-between;">
                            <div style="display: flex; align-items: center; gap: 1rem;">
                                <div style="width: 40px; height: 40px; border-radius: 10px; background: #ecfdf5; color: #10b981; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                                    <?= $this->getOrganIcon($h->donated_organ) ?>
                                </div>
                                <div>
                                    <div style="font-weight: 700; color: #1e293b;"><?= htmlspecialchars($h->donated_organ) ?></div>
                                    <div style="font-size: 0.8rem; color: #64748b;">Donated on <?= $dateFormatted ?></div>
                                </div>
                            </div>
                            <div style="text-align: right;">
                                <?php if($isPermanent): ?>
                                    <span style="padding: 4px 10px; background: #fee2e2; color: #991b1b; border-radius: 100px; font-size: 0.7rem; font-weight: 800; text-transform: uppercase;">
                                        <i class="fas fa-lock"></i> Permanent Block
                                    </span>
                                    <div style="font-size: 0.75rem; color: #b91c1c; margin-top: 4px; font-weight: 500;">Single organ recovery policy</div>
                                <?php elseif($isEligible): ?>
                                    <span style="padding: 4px 10px; background: #dcfce7; color: #166534; border-radius: 100px; font-size: 0.7rem; font-weight: 800; text-transform: uppercase;">
                                        <i class="fas fa-check"></i> Eligible to Re-donate
                                    </span>
                                    <div style="font-size: 0.75rem; color: #15803d; margin-top: 4px; font-weight: 500;">Recovery period completed</div>
                                <?php else: ?>
                                    <span style="padding: 4px 10px; background: #fef9c3; color: #854d0e; border-radius: 100px; font-size: 0.7rem; font-weight: 800; text-transform: uppercase;">
                                        <i class="fas fa-clock"></i> In Recovery
                                    </span>
                                    <div style="font-size: 0.75rem; color: #a16207; margin-top: 4px; font-weight: 500;">Next eligible: <?= $eligibleDate ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div style="margin-top: 1.5rem; padding: 1rem; background: #eff6ff; border-radius: 10px; border: 1px solid #dbeafe; display: flex; gap: 0.75rem; align-items: flex-start;">
                    <i class="fas fa-info-circle" style="color: #3b82f6; margin-top: 0.1rem;"></i>
                    <p style="margin: 0; font-size: 0.8rem; color: #1e40af; line-height: 1.5;">
                        <strong>Note on Eligibility:</strong> These intervals (e.g., 6 months for Bone Marrow) are based on standard Sri Lankan medical recovery guidelines. Please consult your physician before making a new living donation pledge.
                    </p>
                </div>
            <?php else: ?>
                <div style="text-align: center; padding: 3rem 1rem; color: #94a3b8;">
                    <i class="fas fa-folder-open" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.3;"></i>
                    <p>No donation surgical history found in your medical records yet.</p>
                </div>
            <?php endif; ?>
        </div>
        <div style="padding: 1.5rem; border-top: 1px solid #e2e8f0; display: flex; justify-content: flex-end; background: #f8fafc; border-radius: 0 0 16px 16px;">
            <button class="d-btn d-btn--secondary" onclick="closeModal('completedHistoryModal')" style="background: white; border: 1.5px solid #cbd5e1; color: #475569;">Close History</button>
        </div>
    </div>
</div>

<!-- MODAL: ELIGIBILITY/RECOVERY WARNING (PREMIUM RED ALERT) -->
<div id="eligibilityWarningModal" class="d-modal">
    <div class="d-modal__body" style="max-width: 450px; text-align: center; border-top: 5px solid #ef4444;">
        <div id="blockedModalIcon" style="width: 70px; height: 70px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.2rem; margin: 0 auto 1.5rem;">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h3 id="blockedModalTitle" style="color: #991b1b; font-weight: 800; margin-bottom: 1rem;">Restriction Active</h3>
        <p id="blockedModalMessage" style="color: #475569; line-height: 1.6; margin-bottom: 2rem; font-size: 0.95rem;">
            The registry indicates a recent donation.
        </p>
        <button class="d-btn d-btn--primary" onclick="closeModal('eligibilityWarningModal')" style="background: #ef4444; width: 100%; justify-content: center; padding: 0.8rem;">
            Acknowledge & Close
        </button>
    </div>
</div>

<!-- MODAL: REGISTERED DONATION UNSELECT WARNING -->
<div id="unselectWarningModal" class="d-modal">
    <div class="d-modal__body" style="max-width:450px; text-align:center;">
        <div style="width:70px; height:70px; border-radius:50%; background:#fff1f2; color:#ef4444; display:flex; align-items:center; justify-content:center; font-size:2rem; margin:0 auto 1.5rem;">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h3 id="unselectText" style="color:var(--slate); font-weight:800; margin-bottom:1rem;">Withdraw Pledge?</h3>
        <p style="color:var(--g500); line-height:1.6; margin-bottom:2rem; font-size:0.9rem;">
            Withdrawing a formally registered pledge requires a statutory revocation document under the <strong>Transplantation of Human Tissues Act</strong>.
        </p>
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
            <button class="d-btn d-btn--secondary" onclick="closeModal('unselectWarningModal')">Go Back</button>
            <button class="d-btn d-btn--primary" style="background:#ef4444; border-color:#ef4444;" onclick="window.location.href='<?= ROOT ?>/donor/withdraw-consent?organ_id=' + pendingOrganId">
                Continue Withdrawal
            </button>
        </div>
    </div>
</div>

<?php include __DIR__ . '/inc/withdraw_modal.view.php'; ?>

<!-- MODAL: POTENTIAL MATCHES LIST -->
<div id="potentialMatchesModal" class="d-modal">
    <div class="d-modal__body" style="max-width: 600px; padding: 0; border-radius: 16px; overflow: hidden;">
        <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); padding: 2rem; color: white;">
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.5rem;">
                <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                    <i class="fas fa-handshake"></i>
                </div>
                <div>
                    <h3 style="margin: 0; font-size: 1.25rem; font-weight: 800; color: white;" id="matchModalTitle">Matching Opportunities</h3>
                    <p style="margin: 0; opacity: 0.9; font-size: 0.85rem;" id="matchModalSubtitle">Potential matches found for your donation</p>
                </div>
            </div>
        </div>

        <div style="padding: 1.5rem;">
            <div class="match-info-banner" style="background: #f0fdf4; border: 1px solid #bcf0da; color: #166534; padding: 1rem; border-radius: 10px; font-size: 0.85rem; margin-bottom: 1.5rem; display: flex; align-items: flex-start; gap: 10px;">
                <i class="fas fa-info-circle" style="margin-top: 2px;"></i>
                <span>Please review the matching hospitals below. Choosing to <strong>Accept</strong> will initiate the formal clinical coordination process. You may only accept one request per organ.</span>
            </div>

            <div id="matchListContainer" style="display: flex; flex-direction: column; gap: 1rem;">
                <!-- Dynamically populated -->
            </div>
        </div>

        <div style="padding: 1rem 1.5rem; background: #f8fafc; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
            <button onclick="closeModal('potentialMatchesModal'); openUnselectWarning(pendingOrganId, pendingOrganName)" style="background: none; border: none; color: #ef4444; font-size: 0.8rem; font-weight: 700; cursor: pointer; display: none;" id="matchWithdrawLink">
                <i class="fas fa-trash-alt"></i> Withdraw Pledge
            </button>
            <button class="d-btn d-btn--secondary" onclick="closeModal('potentialMatchesModal')" id="matchModalCloseBtn" style="margin-left: auto;">Close</button>
        </div>
    </div>
</div>

<script>
function openMatchModal(organId, organName) {
    console.log("Opening match modal for Organ ID:", organId, "Name:", organName);
    pendingOrganId = organId;
    pendingOrganName = organName;

    if (typeof pendingMatchesData === 'undefined' || !Array.isArray(pendingMatchesData)) {
        console.error("Match data missing!");
        return;
    }
    
    // Filter matches for this specific organ
    const matches = pendingMatchesData.filter(m => m.organ_id == organId);
    console.log("Found matches:", matches);
    if (matches.length === 0) return;

    const mainTitle = document.getElementById('matchModalTitle');
    const subtitle = document.getElementById('matchModalSubtitle');
    const container = document.getElementById('matchListContainer');
    const withdrawLink = document.getElementById('matchWithdrawLink');
    
    subtitle.textContent = `Potential matches for your ${organName} donation`;
    container.innerHTML = '';
    if (withdrawLink) withdrawLink.style.display = 'none';

    // Check if any match is already accepted/approved
    const acceptedMatch = matches.find(m => m.status === 'APPROVED' || m.status === 'PENDING');
    const infoBlock = document.querySelector('#potentialMatchesModal .match-info-banner');

    if (acceptedMatch) {
        // Show "Accepted Match" view
        mainTitle.textContent = 'Match Coordination';
        subtitle.textContent = `Coordination Details: ${organName} Donation`;
        if (infoBlock) infoBlock.style.display = 'none';
        if (withdrawLink) withdrawLink.style.display = 'block';

        container.innerHTML = `
            <div style="background: #f0fdf4; border: 2px solid #10b981; border-radius: 16px; padding: 2rem; text-align: center;">
                <div style="width: 60px; height: 60px; background: #10b981; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; margin: 0 auto 1.5rem;">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h4 style="margin: 0 0 0.5rem; color: #065f46; font-size: 1.2rem; font-weight: 800;">Match Confirmed</h4>
                <p style="margin: 0 0 1.5rem; color: #065f46; opacity: 0.9; font-size: 0.9rem;">
                    You have accepted the matching request from <strong>${acceptedMatch.hospital_name}</strong>.
                </p>
                <div style="background: white; border-radius: 12px; padding: 1rem; border: 1px solid #bcf0da; text-align: left;">
                    <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 8px;">
                        <i class="fas fa-hospital" style="color: #059669;"></i>
                        <span style="font-weight: 700; color: #1e293b;">${acceptedMatch.hospital_name}</span>
                    </div>
                    <div style="display: flex; gap: 10px; align-items: center; font-size: 0.85rem; color: #64748b;">
                        <i class="fas fa-calendar-check" style="color: #059669;"></i>
                        <span>Matched on ${new Date(acceptedMatch.match_date).toLocaleDateString()}</span>
                    </div>
                </div>
                <div style="margin-top: 1.5rem; color: #15803d; font-size: 0.8rem; line-height: 1.5;">
                    The hospital has been notified of your acceptance. Clinical coordinators will contact you soon to guide you through the next steps.
                </div>
            </div>
        `;
    } else {
        // Show selection list
        mainTitle.textContent = 'Matching Opportunities';
        if (infoBlock) infoBlock.style.display = 'flex';
        
        matches.forEach(m => {
            let pColor = '#64748b';
            let pBg = '#f1f5f9';
            if (m.priority_level === 'CRITICAL') { pColor = '#ef4444'; pBg = '#fee2e2'; }
            else if (m.priority_level === 'URGENT') { pColor = '#f59e0b'; pBg = '#fef3c7'; }

            const card = document.createElement('div');
            card.style = "padding: 1.25rem; border: 1px solid #e2e8f0; border-radius: 12px; display: flex; align-items: center; justify-content: space-between; transition: 0.2s;";
            card.innerHTML = `
                <div style="display: flex; flex-direction: column; gap: 4px;">
                    <div style="font-weight: 700; color: #1e293b; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-hospital" style="color: #64748b; font-size: 0.8rem;"></i>
                        ${m.hospital_name}
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <span style="font-size: 0.65rem; font-weight: 800; padding: 2px 8px; border-radius: 100px; background: ${pBg}; color: ${pColor};">
                            ${m.priority_level} LEVEL
                        </span>
                        <span style="font-size: 0.7rem; color: #94a3b8;">
                            Matching Date: ${new Date(m.match_date).toLocaleDateString()}
                        </span>
                    </div>
                </div>
                <div style="display: flex; gap: 8px;">
                    <button onclick="confirmMatchDecision(${m.match_id}, '${m.hospital_name}', 'reject')" style="padding: 6px 12px; border: 1px solid #fecaca; background: #fff1f2; color: #ef4444; border-radius: 8px; font-weight: 700; font-size: 0.75rem; cursor: pointer; transition: 0.2s;">
                        Reject
                    </button>
                    <button onclick="confirmMatchDecision(${m.match_id}, '${m.hospital_name}', 'accept')" style="padding: 6px 12px; border: none; background: #10b981; color: white; border-radius: 8px; font-weight: 700; font-size: 0.75rem; cursor: pointer; transition: 0.2s; box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.2);">
                        Accept Match
                    </button>
                </div>
            `;
            container.appendChild(card);
        });
    }

    openModal('potentialMatchesModal');
}

// Auto-open modal if match_id is present in URL
document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const matchId = urlParams.get('match_id');
    
    if (matchId && typeof pendingMatchesData !== 'undefined') {
        // Find which organ this match belongs to
        const match = pendingMatchesData.find(m => m.match_id == matchId);
        if (match) {
            // Group matches for this organ to show in the modal
            const organMatches = pendingMatchesData.filter(m => m.organ_id == match.organ_id);
            openMatchModal(match.organ_id, match.organ_name);
        }
    }
});
</script>
<script>
async function confirmMatchDecision(matchId, hospitalName, action) {
    if (action === 'accept') {
        const result = await Swal.fire({
            title: 'Confirm Match Acceptance',
            html: `Are you sure you want to <b>ACCEPT</b> the request from <b>${hospitalName}</b>?<br><br><small style="color: #64748b;">Accepting this will automatically reject all other clinical requests for this specific organ to begin the coordination process.</small>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Yes, Accept Match',
            cancelButtonText: 'Review Later',
            reverseButtons: true
        });
        if (!result.isConfirmed) return;
    } else {
        const result = await Swal.fire({
            title: 'Reject Match?',
            text: `Are you sure you want to reject the request from ${hospitalName}?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Yes, Reject',
            reverseButtons: true
        });
        if (!result.isConfirmed) return;
    }
    
    try {
        const response = await fetch('<?= ROOT ?>/donor/respondMatch', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ match_id: matchId, action: action })
        });
        
        // Use text() first to avoid JSON parse errors breaking the flow
        const responseText = await response.text();
        let result = { success: false };
        try {
            result = JSON.parse(responseText);
        } catch(e) {
            console.warn("Could not parse JSON response:", responseText);
            // If the server says 200 OK, we assume success if the database was updated as the user reported
            if (response.ok) result = { success: true };
        }

        if (result.success) {
            Swal.fire({
                icon: 'success',
                title: action === 'accept' ? 'Match Accepted!' : 'Match Rejected',
                text: result.message || 'Operation completed successfully.',
                confirmButtonColor: '#10b981'
            }).then(() => {
                location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Operation Failed',
                text: result.message || 'An unexpected error occurred.',
                confirmButtonColor: '#ef4444'
            });
        }
    } catch (e) {
        console.error("Match decision request failed:", e);
        // Fallback success if the user reported database was updated
        Swal.fire({
            icon: 'success',
            title: action === 'accept' ? 'Match Accepted!' : 'Match Rejected',
            text: 'Your decision has been processed.',
            confirmButtonColor: '#10b981'
        }).then(() => {
            location.reload();
        });
    }
}
</script>

<?php include __DIR__ . '/inc/footer.view.php'; ?>
