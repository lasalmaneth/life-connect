<?php
require_once __DIR__ . '/../../../core/config.php';
try {
    $pdo = new PDO("mysql:host=".DBHOST.";dbname=".DBNAME, DBUSER, DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT m.match_id, m.match_date, m.status as match_status, m.warning_details,
                   dp.id as pledge_id, d.first_name as donor_name, d.last_name as donor_last_name, d.blood_group as donor_blood_group,
                   orq.id as request_id, orq.blood_group as required_blood_group, orq.priority_level,
                   org.name as organ_name, h.name as hospital_name
            FROM donor_patient_match m
            JOIN donor_pledges dp ON m.donor_pledge_id = dp.id
            JOIN donors d ON dp.donor_id = d.id
            JOIN organ_requests orq ON m.request_id = orq.id
            JOIN organs org ON orq.organ_id = org.id
            JOIN hospitals h ON orq.hospital_id = h.id
            ORDER BY m.match_id ASC";
    $stmt = $pdo->query($sql);
    $matchingData = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $matchingData = [];
    echo "<div style='color:red; margin: 20px;'>Could not load matches: ".$e->getMessage()."</div>";
}
?>

<div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 15px; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
    <h3 style="margin-top: 0; color: #1e293b; font-size: 1.1rem; display: flex; align-items: center; gap: 8px;">
        <i class="fa-solid fa-scale-balanced" style="color: #3b82f6;"></i> Medical Matching Rules Applied
    </h3>
    <ul style="margin: 0; padding-left: 20px; color: #475569; font-size: 0.9rem; list-style-type: disc;">
        <li><b>Kidney (Living Donor)</b>: Blood group compatibility (O is universal, strict ABO mapping used). HLA tracking applied before final approval.</li>
        <li><b>Partial Liver</b>: Strict Blood group compatibility matching enforced. Validates safe organ extraction dynamically prior to final match.</li>
        <li><b>Bone Marrow</b>: Priority placed natively on rigorous 10-marker HLA sequence matching over standard generic blood-type matching restrictions.</li>
    </ul>
    
    <div style="margin-top: 15px;">
        <button onclick="runAlgorithm()" style="background: #3b82f6; color: white; border: none; padding: 8px 16px; border-radius: 6px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; font-size: 0.9rem;">
            <i class="fa-solid fa-arrows-rotate"></i> Run Background Algorithm Now
        </button>
    </div>
</div>

<div class="matches-table-container" style="background: white; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); border: 1px solid #e2e8f0; overflow: hidden;">
    <div class="table-header" style="display: grid; grid-template-columns: 1.5fr 1.5fr 1.5fr 1fr 150px; gap: 1rem; padding: 1rem 1.5rem; background: #f8fafc; border-bottom: 2px solid #e2e8f0; font-weight: 600; color: #475569; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">
        <div>Donor Details</div>
        <div>Request Info</div>
        <div>Requested By</div>
        <div>Match Date</div>
        <div style="text-align: center;">Status</div>
    </div>

    <?php if (empty($matchingData)): ?>
        <div style="padding: 2rem; text-align: center; color: #64748b;">No matches found or the matching algorithm hasn't produced any yet.</div>
    <?php else: ?>
        <?php foreach ($matchingData as $match): ?>
            <?php
                if ($match['match_status'] === 'MATCH' || $match['match_status'] === 'APPROVED') {
                    $statusDisplay = 'MATCH';
                    $statusStyle = 'background: #dcfce7; color: #166534; border: 1px solid #bbf7d0;';
                } else if (strpos($match['match_status'], 'WARNING') !== false) {
                    $statusDisplay = 'WARNING';
                    $statusStyle = 'background: #ffedd5; color: #9a3412; border: 1px solid #fed7aa;';
                } else {
                    $statusDisplay = $match['match_status'];
                    $statusStyle = 'background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0;';
                }
            ?>
            <div class='table-row' data-match-id='<?= $match['match_id'] ?>' style='display: grid; grid-template-columns: 1.5fr 1.5fr 1.5fr 1fr 150px; gap: 1rem; padding: 1.2rem 1.5rem; align-items: center; border-bottom: 1px solid #f1f5f9; transition: all 0.2s;'>
                <div class='table-cell'>
                    <div style='font-weight: 600; color: #1e293b;'><?= htmlspecialchars($match['donor_name'] . ' ' . $match['donor_last_name']) ?></div>
                    <div style='font-size: 0.8rem; color: #64748b;'>Pledge: #<?= $match['pledge_id'] ?> | BType: <?= htmlspecialchars($match['donor_blood_group'] ?? 'N/A') ?></div>
                </div>
                <div class='table-cell'>
                    <div style='font-weight: 600; color: #1e293b;'><?= htmlspecialchars($match['organ_name']) ?></div>
                    <div style='font-size: 0.8rem; color: #64748b;'>Req: #<?= $match['request_id'] ?> | Need: <?= htmlspecialchars($match['required_blood_group'] ?? 'N/A') ?></div>
                </div>
                <div class='table-cell' style='color: #475569;'>
                    <div style='font-weight: 500;'><?= htmlspecialchars($match['hospital_name']) ?></div>
                </div>
                <div class='table-cell' style='color: #64748b; font-size: 0.9rem;'>
                    <?= date('M d, Y', strtotime($match['match_date'])) ?>
                </div>
                <div class='table-cell' style='text-align: center; display: flex; flex-direction: column; gap: 5px; align-items: center;'>
                    <span style='padding: 6px 12px; border-radius: 50px; font-weight: 600; font-size: 0.75rem; text-transform: uppercase; box-shadow: 0 1px 2px rgba(0,0,0,0.05); <?= $statusStyle ?>'>
                        <?= $statusDisplay ?>
                    </span>
                </div>
            </div>
            
            <?php if (!empty($match['warning_details'])): ?>
                <div style='grid-column: 1 / -1; padding: 0.8rem 1.5rem; background: #fffbeb; border-bottom: 1px solid #fde68a; font-size: 0.85rem; color: #92400e; display: flex; gap: 10px; align-items: flex-start;'>
                    <i class='fa-solid fa-triangle-exclamation' style='color: #d97706; margin-top: 3px;'></i>
                    <div>
                        <strong style="display:block; margin-bottom: 2px;">Flagged Warning:</strong> 
                        <?= htmlspecialchars($match['warning_details']) ?>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script>
function runAlgorithm() {
    Swal.fire({
        title: 'Running Match Algorithm...',
        text: 'Executing medical compatibility matrix rules. Please wait.',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    fetch('<?= ROOT ?>/donation-admin/runAlgorithm', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Algorithm Complete',
                text: 'Generated ' + data.matches_created + ' new compatible matches!',
                confirmButtonColor: '#3b82f6'
            }).then(() => {
                location.reload();
            });
        } else {
            throw new Error(data.error || 'Unknown error occurred');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Algorithm Failed',
            text: error.message || 'There was an error parsing the database.',
            confirmButtonColor: '#3b82f6'
        });
    });
}
</script>