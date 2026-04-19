<?php
require_once __DIR__ . '/../../../core/config.php';
try {
    $pdo = new PDO("mysql:host=".DBHOST.";dbname=".DBNAME, DBUSER, DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 1. Auto-sync schema if column is missing
    try {
        $cols = $pdo->query("SHOW COLUMNS FROM donor_patient_match")->fetchAll(PDO::FETCH_COLUMN);
        if (!in_array('clinical_match_quality', $cols)) {
            $pdo->exec("ALTER TABLE donor_patient_match ADD COLUMN clinical_match_quality ENUM('MATCH','MATCH WITH WARNING') DEFAULT 'MATCH' AFTER request_id");
            $pdo->exec("ALTER TABLE donor_patient_match ADD COLUMN warning_details TEXT DEFAULT NULL AFTER clinical_match_quality");
        }
    } catch (Exception $e) { /* Already exists */ }

    // 2. TRIGGER MATCHING ENGINE AUTOMATICALLY ON LOAD
    require_once __DIR__ . '/../../../controllers/admin/DonationAdminController.php';
    $matchingController = new \App\Controllers\admin\DonationAdminController();
    $matchingController->executeMatchingEngine($pdo);

    // 3. Fetch Data using the new column name
    $sql = "SELECT m.match_id, m.match_date, m.clinical_match_quality as match_status, m.warning_details, m.donor_status, m.hospital_match_status,
                   dp.id as pledge_id, dp.status as pledge_status, d.first_name as donor_name, d.last_name as donor_last_name, d.blood_group as donor_blood_group,
                   orq.id as request_id, orq.blood_group as required_blood_group, orq.priority_level,
                   org.name as organ_name, h.name as hospital_name
            FROM donor_patient_match m
            JOIN donor_pledges dp ON m.donor_pledge_id = dp.id
            JOIN donors d ON dp.donor_id = d.id
            JOIN organ_requests orq ON m.request_id = orq.id
            JOIN organs org ON orq.organ_id = org.id
            JOIN hospitals h ON orq.hospital_id = h.id
            ORDER BY m.match_id DESC";
    $stmt = $pdo->query($sql);
    $matchingData = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(Exception $e) {
    $matchingData = [];
    echo "<div style='color:red; margin: 20px;'>Could not load matches: ".$e->getMessage()."</div>";
}
?>

<div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; margin-bottom: 25px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
    <h3 style="margin-top: 0; color: #1e293b; font-size: 1.15rem; display: flex; align-items: center; gap: 10px; font-weight: 700;">
        <i class="fa-solid fa-microscope" style="color: #3b82f6;"></i> Advanced Clinical Matching Engine
    </h3>
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-top: 15px;">
        <div style="background: white; padding: 12px; border-radius: 8px; border: 1px solid #e2e8f0;">
            <div style="font-weight: 700; color: #1e293b; font-size: 0.85rem; margin-bottom: 5px; text-transform: uppercase;">Kidney Matching</div>
            <div style="font-size: 0.85rem; color: #64748b; line-height: 1.4;">Strict ABO checking + 6-Marker HLA Sequence Analysis. Matches rejected if ABO incompatible or HLA Score < 50%.</div>
        </div>
        <div style="background: white; padding: 12px; border-radius: 8px; border: 1px solid #e2e8f0;">
            <div style="font-weight: 700; color: #1e293b; font-size: 0.85rem; margin-bottom: 5px; text-transform: uppercase;">Bone Marrow</div>
            <div style="font-size: 0.85rem; color: #64748b; line-height: 1.4;">High-stringency HLA tracking. Requires 4/6 matches. Flagged as Partial Verification (6/10 marker scope).</div>
        </div>
        <div style="background: white; padding: 12px; border-radius: 8px; border: 1px solid #e2e8f0;">
            <div style="font-weight: 700; color: #1e293b; font-size: 0.85rem; margin-bottom: 5px; text-transform: uppercase;">Partial Liver</div>
            <div style="font-size: 0.85rem; color: #64748b; line-height: 1.4;">ABO Compatibility + Donor BMI Assessment. BMI > 25.0 triggers medical review warning.</div>
        </div>
    </div>
    
    
    <div style="margin-top: 20px; display: flex; align-items: center; gap: 15px;">
        <span style="background: #dcfce7; color: #166534; padding: 6px 12px; border-radius: 6px; font-size: 0.85rem; font-weight: 700;">
            <i class="fa-solid fa-sync fa-spin"></i> Live Clinical Sync Active
        </span>
        <span style="font-size: 0.85rem; color: #94a3b8; font-weight: 500;">Matches are refreshed automatically based on the latest medical sequence data.</span>
    </div>
</div>

<!-- Search and Filters Section -->
<div style="margin-bottom: 25px; display: grid; grid-template-columns: 1.5fr 1fr 1fr 1fr; gap: 15px; background: white; padding: 15px; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
    <div style="position: relative;">
        <i class="fa-solid fa-search" style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #94a3b8;"></i>
        <input type="text" id="matching-search" placeholder="Search donors, hospitals or request IDs..." style="width: 100%; padding: 10px 15px 10px 40px; border-radius: 8px; border: 1px solid #e2e8f0; font-size: 0.9rem; outline: none; transition: border-color 0.2s;" onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#e2e8f0'">
    </div>
    
    <div style="display: flex; align-items: center; gap: 10px;">
        <span style="font-size: 0.75rem; font-weight: 800; color: #64748b; white-space: nowrap; text-transform: uppercase;">Donor:</span>
        <select id="matching-donor-status-filter" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #e2e8f0; font-size: 0.85rem; color: #475569; outline: none; cursor: pointer;">
            <option value="">All Decisions</option>
            <option value="PENDING">🕒 Pending</option>
            <option value="ACCEPTED">✅ Accepted</option>
            <option value="REJECTED">❌ Rejected</option>
        </select>
    </div>

    <div style="display: flex; align-items: center; gap: 10px;">
        <span style="font-size: 0.75rem; font-weight: 800; color: #64748b; white-space: nowrap; text-transform: uppercase;">Hospital:</span>
        <select id="matching-hospital-status-filter" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #e2e8f0; font-size: 0.85rem; color: #475569; outline: none; cursor: pointer;">
            <option value="">All Responses</option>
            <option value="PENDING">🕒 Pending</option>
            <option value="ACCEPTED">✅ Accepted</option>
            <option value="REJECTED">❌ Rejected</option>
        </select>
    </div>

    <div style="display: flex; align-items: center; gap: 10px;">
        <span style="font-size: 0.75rem; font-weight: 800; color: #64748b; white-space: nowrap; text-transform: uppercase;">Pledge:</span>
        <select id="matching-pledge-status-filter" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #e2e8f0; font-size: 0.85rem; color: #475569; outline: none; cursor: pointer;">
            <option value="">All Statuses</option>
            <option value="PENDING">🕒 Pending</option>
            <option value="APPROVED">🛡️ Approved</option>
            <option value="IN_PROGRESS">🔄 In Progress</option>
            <option value="SUSPENDED">⏸️ Suspended</option>
            <option value="UPLOADED">📤 Uploaded</option>
            <option value="COMPLETED">🏁 Completed</option>
            <option value="REJECTED">❌ Rejected</option>
        </select>
    </div>
</div>

<div id="matching-table" class="matches-table-container" style="background: white; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); border: 1px solid #e2e8f0; overflow: hidden;">
    <div class="table-header" style="display: grid; grid-template-columns: 1.5fr 1.5fr 1.5fr 1fr 180px; gap: 1rem; padding: 1.25rem 1.5rem; background: #f8fafc; border-bottom: 2px solid #e2e8f0; font-weight: 700; color: #475569; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.75px;">
        <div>Donor Details</div>
        <div>Request Info</div>
        <div>Requested By</div>
        <div>Match Date</div>
        <div style="text-align: center;">Compatibility</div>
    </div>

    <?php if (empty($matchingData)): ?>
        <div style="padding: 4rem; text-align: center; color: #94a3b8;">
            <i class="fa-solid fa-folder-open" style="font-size: 3rem; margin-bottom: 1rem; display: block; opacity: 0.3;"></i>
            No clinical matches found yet. The engine will populate this list as soon as compatible donors and requests are registered.
        </div>
    <?php else: ?>
        <?php foreach ($matchingData as $match): ?>
            <?php
                $matchScore = null;
                if (preg_match('/Score: (\d+)%/', $match['warning_details'], $matches)) {
                    $matchScore = $matches[1];
                }

                if ($match['match_status'] === 'MATCH' || $match['match_status'] === 'APPROVED') {
                    $statusDisplay = 'EXCELLENT';
                    $statusStyle = 'background: #dcfce7; color: #166534; border: 1px solid #bbf7d0;';
                } else if (strpos($match['match_status'], 'WARNING') !== false) {
                    $statusDisplay = 'COMPATIBLE';
                    $statusStyle = 'background: #fffbeb; color: #92400e; border: 1px solid #fef3c7;';
                } else {
                    $statusDisplay = $match['match_status'];
                    $statusStyle = 'background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0;';
                }
            ?>
            <div class='table-row' data-match-id='<?= $match['match_id'] ?>' data-donor-status='<?= $match['donor_status'] ?>' data-hospital-match-status='<?= $match['hospital_match_status'] ?>' data-pledge-status='<?= $match['pledge_status'] ?>' onclick="viewMatchDetails(<?= $match['match_id'] ?>)" style='display: grid; grid-template-columns: 1.5fr 1.5fr 1.5fr 1fr 180px; gap: 1rem; padding: 1.5rem; align-items: center; border-bottom: 1px solid #f1f5f9; transition: all 0.2s;'>
                <div class='table-cell'>
                    <div style='font-weight: 700; color: #0f172a; margin-bottom: 4px;'><?= htmlspecialchars($match['donor_name'] . ' ' . $match['donor_last_name']) ?></div>
                    <div style='font-size: 0.8rem; color: #64748b; font-weight: 500; display: flex; align-items: center; gap: 6px;'>
                        <span style="background: #eef2ff; color: #4338ca; padding: 2px 6px; border-radius: 4px; font-weight: 700;"><?= htmlspecialchars($match['donor_blood_group'] ?? 'N/A') ?></span>
                        Pledge #<?= $match['pledge_id'] ?>
                    </div>
                </div>
                <div class='table-cell'>
                    <div style='font-weight: 700; color: #0f172a; margin-bottom: 4px;'><?= htmlspecialchars($match['organ_name']) ?></div>
                    <div style='font-size: 0.8rem; color: #64748b; font-weight: 500; display: flex; align-items: center; gap: 6px;'>
                        <span style="background: #fff1f2; color: #be123c; padding: 2px 6px; border-radius: 4px; font-weight: 700;"><?= htmlspecialchars($match['required_blood_group'] ?? 'N/A') ?></span>
                        Req #<?= $match['request_id'] ?>
                    </div>
                </div>
                <div class='table-cell'>
                    <div style='font-weight: 600; color: #334155;'><?= htmlspecialchars($match['hospital_name']) ?></div>
                    <div style='font-size: 0.75rem; color: #94a3b8; margin-top: 2px;'>Institution Verified</div>
                </div>
                <div class='table-cell' style='color: #64748b; font-size: 0.9rem; font-weight: 500;'>
                    <?= date('M d, Y', strtotime($match['match_date'])) ?>
                </div>
                <div class='table-cell' style='text-align: center; display: flex; flex-direction: column; gap: 8px; align-items: center;'>
                    <span style='padding: 6px 14px; border-radius: 50px; font-weight: 800; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px; <?= $statusStyle ?>'>
                        <?= $statusDisplay ?>
                    </span>
                    <?php if ($matchScore): ?>
                        <div style="font-size: 0.75rem; font-weight: 700; color: #3b82f6;">Score: <?= $matchScore ?>%</div>
                    <?php endif; ?>
                </div>
            </div>
            
            <?php 
                $actualWarnings = trim(preg_replace('/Score: \d+% Match\. /', '', $match['warning_details']));
                $hasWarnings = !empty($actualWarnings);
            ?>
            <div class='insights-row' data-match-id='<?= $match['match_id'] ?>' style='grid-column: 1 / -1; padding: 0.85rem 1.5rem; background: <?= $hasWarnings ? "#fffbeb" : "#f0fdf4" ?>; border-bottom: 1px solid <?= $hasWarnings ? "#fef3c7" : "#dcfce7" ?>; font-size: 0.85rem; color: <?= $hasWarnings ? "#92400e" : "#166534" ?>; display: flex; gap: 12px; align-items: center;'>
                <i class='fa-solid <?= $hasWarnings ? "fa-circle-exclamation" : "fa-shield-check" ?>' style='color: <?= $hasWarnings ? "#d97706" : "#22c55e" ?>; font-size: 1rem;'></i>
                <div style="font-weight: 500;">
                    <strong style="color: <?= $hasWarnings ? "#b45309" : "#15803d" ?>;">Clinical Insights:</strong> 
                    <?= $hasWarnings ? htmlspecialchars($actualWarnings) : "Biological compatibility verified. No clinical risks identified for this donor-recipient pair." ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Match Detail Modal -->
<div id="matchDetailModal" class="modal" style="display: none;">
    <div class="modal-content" style="max-width: 850px !important;">
        <div class="modal-scroll-area" style="padding: 2rem !important; gap: 1.5rem !important;">
            <!-- Modal Header -->
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem; position: relative;">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div style="width: 50px; height: 50px; background: #eff6ff; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #3b82f6; font-size: 1.5rem;">
                        <i class="fa-solid fa-handshake-angle"></i>
                    </div>
                    <div>
                        <h2 style="margin: 0; font-size: 1.4rem; font-weight: 800; color: #0f172a;">Match Verification Details</h2>
                        <div id="modal-match-id-badge" style="font-size: 0.75rem; color: #64748b; font-weight: 600; margin-top: 2px;">MATCH ID: #000</div>
                    </div>
                </div>
                <button onclick="closeMatchModal()" style="background: #f1f5f9; border: none; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #64748b; transition: all 0.2s;">&times;</button>
            </div>

            <!-- Decision Tracking & Compatibility Card -->
            <div id="modal-compatibility-card" style="padding: 1.25rem; border-radius: 12px; border: 1px solid #e2e8f0; display: grid; grid-template-columns: 1fr 1fr 1fr 120px; gap: 1rem; background: #f8fafc; align-items: center;">
                <div style="display: flex; flex-direction: column; gap: 6px;">
                    <span id="modal-quality-badge" style="width: fit-content; padding: 4px 12px; border-radius: 50px; font-weight: 800; font-size: 0.65rem; text-transform: uppercase;">-</span>
                    <div style="font-size: 0.8rem; color: #64748b; font-weight: 500;">Matched: <span id="modal-match-date" style="font-weight: 700; color: #1e293b;">-</span></div>
                </div>
                
                <!-- Donor Confirmation -->
                <div style="display: flex; flex-direction: column; gap: 6px; padding-left: 15px; border-left: 2px solid #e2e8f0;">
                    <span style="font-size: 0.65rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Donor Decision:</span>
                    <div id="modal-donor-status-badge" style="display: flex; align-items: center; gap: 6px; padding: 5px 10px; border-radius: 8px; font-size: 0.75rem; font-weight: 700; width: fit-content;">
                        <i id="modal-donor-status-icon" class="fa-solid fa-circle-question"></i>
                        <span id="modal-donor-status-text">Pending</span>
                    </div>
                </div>

                <!-- Hospital Response -->
                <div style="display: flex; flex-direction: column; gap: 6px; padding-left: 15px; border-left: 2px solid #e2e8f0;">
                    <span style="font-size: 0.65rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Hospital Response:</span>
                    <div id="modal-hospital-status-badge" style="display: flex; align-items: center; gap: 6px; padding: 5px 10px; border-radius: 8px; font-size: 0.75rem; font-weight: 700; width: fit-content;">
                        <i id="modal-hospital-status-icon" class="fa-solid fa-hotel"></i>
                        <span id="modal-hospital-status-text">Requested</span>
                    </div>
                </div>

                <div id="modal-score-display" style="font-weight: 900; font-size: 1.25rem; color: #3b82f6; text-align: right;">0%</div>
            </div>

            <!-- Hospital Rejection Reason (Hidden by Default) -->
            <div id="modal-hospital-rejection-box" style="display: none; padding: 1rem; background: #fef2f2; border: 1px solid #fecaca; border-radius: 10px; margin-top: -0.5rem;">
                <div style="font-size: 0.7rem; font-weight: 800; color: #b91c1c; text-transform: uppercase; margin-bottom: 4px; display: flex; align-items: center; gap: 6px;">
                    <i class="fa-solid fa-circle-xmark"></i> Hospital Rejection Reason
                </div>
                <div id="modal-hospital-rejection-text" style="font-size: 0.85rem; color: #991b1b; font-weight: 500; line-height: 1.4;">-</div>
            </div>

            <div class="grid-2" style="grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
                <!-- Left Column: Donor Profile -->
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <h3 style="font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 1px; display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-user-donor"></i> Donor Profile
                    </h3>
                    <div class="summary-card" style="grid-template-columns: 1fr; gap: 1rem; background: #fffcfc; border-color: #fee2e2;">
                        <div>
                            <span class="data-label" style="color: #ef4444;">Name & Identity</span>
                            <div id="modal-donor-name" class="data-value">-</div>
                            <div id="modal-donor-nic" class="data-value-sub">NIC: -</div>
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                            <div>
                                <span class="data-label" style="color: #ef4444;">Blood Group</span>
                                <div id="modal-donor-blood" class="data-value">-</div>
                            </div>
                            <div>
                                <span class="data-label" style="color: #ef4444;">Donor Age</span>
                                <div id="modal-donor-age" class="data-value">-</div>
                            </div>
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                            <div>
                                <span class="data-label" style="color: #ef4444;">Gender</span>
                                <div id="modal-donor-gender" class="data-value">-</div>
                            </div>
                            <div>
                                <span class="data-label" style="color: #ef4444;">Weight/BMI</span>
                                <div id="modal-donor-bmi" class="data-value">-</div>
                            </div>
                        </div>
                        <div style="border-top: 1px solid #fee2e2; padding-top: 10px; margin-top: 5px; display: flex; justify-content: space-between; align-items: center;">
                            <span class="data-label" style="color: #ef4444; margin: 0;">Pledge Status</span>
                            <div id="modal-donor-pledge-status-badge" style="padding: 4px 10px; border-radius: 6px; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; background: #f1f5f9; color: #475569;">
                                <span id="modal-donor-pledge-status-text">UNKNOWN</span>
                            </div>
                        </div>
                        <div style="border-top: 1px solid #fee2e2; padding-top: 10px; display: grid; grid-template-columns: 1fr; gap: 8px;">
                            <div id="modal-donor-phone" style="font-size: 0.85rem; font-weight: 700; color: #1e293b; display: flex; align-items: center; gap: 8px;">
                                <i class="fa-solid fa-phone" style="color: #ef4444; font-size: 0.75rem;"></i> -
                            </div>
                            <div id="modal-donor-email" style="font-size: 0.85rem; color: #475569; font-weight: 500; display: flex; align-items: center; gap: 8px;">
                                <i class="fa-solid fa-envelope" style="color: #ef4444; font-size: 0.75rem;"></i> -
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Medical Request -->
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <h3 style="font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 1px; display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-hospital-user"></i> Hospital Organ Request
                    </h3>
                    <div class="summary-card" style="grid-template-columns: 1fr; gap: 1rem; background: #fcfdfe; border-color: #e0f0ff;">
                        <div>
                            <span class="data-label" style="color: #3b82f6;">Healthcare Institution</span>
                            <div id="modal-hospital-name" class="data-value">-</div>
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                            <div>
                                <span class="data-label" style="color: #3b82f6;">Organ & Priority</span>
                                <div id="modal-request-organ" class="data-value">-</div>
                                <div id="modal-request-priority" class="data-value-sub">-</div>
                            </div>
                            <div>
                                <span class="data-label" style="color: #3b82f6;">Recipient Profile</span>
                                <div id="modal-recipient-age-gender" class="data-value">-</div>
                                <div id="modal-request-reason" class="data-value-sub" style="font-style: italic;">Reason: -</div>
                            </div>
                        </div>
                        <div style="border-top: 1px solid #e2e8f0; padding-top: 10px; display: grid; grid-template-columns: 1fr; gap: 8px;">
                            <div id="modal-hospital-phone" style="font-size: 0.85rem; font-weight: 700; color: #1e293b; display: flex; align-items: center; gap: 8px;">
                                <i class="fa-solid fa-phone-volume" style="color: #3b82f6; font-size: 0.75rem;"></i> -
                            </div>
                            <div id="modal-hospital-email" style="font-size: 0.85rem; color: #475569; font-weight: 500; display: flex; align-items: center; gap: 8px;">
                                <i class="fa-solid fa-envelope" style="color: #3b82f6; font-size: 0.75rem;"></i> -
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- End of grid-2 -->

            <!-- Full-width: Insights Section -->
            <div id="modal-insights-section" style="padding: 1.25rem; border-radius: 12px; border: 1px solid #fef3c7; background: #fffbeb; margin-bottom: 1.5rem;">
                <div style="font-size: 0.7rem; font-weight: 800; color: #92400e; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px; display: flex; align-items: center; gap: 6px;">
                    <i class="fa-solid fa-lightbulb"></i> Clinical Insights & Persistent Warnings
                </div>
                <div id="modal-insights-text" style="font-size: 0.9rem; color: #b45309; line-height: 1.5; font-weight: 500;">-</div>
            </div>

            <!-- Full-width: HLA Breakdown Section -->
            <div id="modal-hla-breakdown-section" style="padding: 1.25rem; border-radius: 12px; border: 1px solid #e2e8f0; background: #ffffff; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                <div style="font-size: 0.75rem; font-weight: 800; color: #475569; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-list-check" style="color: #3b82f6;"></i> Tissue Typing Comparison Analysis
                </div>
                <div id="modal-hla-comparison-table-container">
                    <!-- Table will be injected here by JS -->
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.table-row { pointer-events: auto; }
.table-row:hover { 
    background: #f8fafc !important; 
    cursor: pointer;
    box-shadow: inset 4px 0 0 #3b82f6;
    transform: translateX(4px);
}
.matches-table-container .table-row:active {
    background: #f1f5f9 !important;
}
</style>