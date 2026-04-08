<?php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo ROOT ?? '/life-connect'; ?>/public/assets/css/hospital/hospital.css">
    <title>Update Eligibility - Hospital Management - LifeConnect</title>
</head>
<body>
    <?php
        $current_page = 'eligibility';
    
        require_once __DIR__ . '/header.php';
    ?>

    <div class="container">
        <div class="main-content">
            <?php require_once __DIR__ . '/sidebar.php'; ?>

            <div class="content-area">
                <div class="content-section" style="display: block;">
                    <div class="content-header">
                        <h2>Update Donor Eligibility</h2>
                        <p>Update donor eligibility status after medical evaluations and screening.</p>
                    </div>
                    <div class="content-body">
                        <div class="search-bar">
                            <span class="search-icon">Search:</span>
                            <input type="text" class="search-input" placeholder="Search by donor NIC or name...">
                        </div>

                        <div class="data-table">
                            <div class="table-header">
                                <h4>Pending Eligibility Reviews</h4>
                            </div>
                            <div class="table-content">
                                <div class="table-row" style="font-weight: 600; background: var(--gray-bg-color);">
                                    <div class="table-cell">Donor Details</div>
                                    <div class="table-cell">Organ Type</div>
                                    <div class="table-cell">Test Date</div>
                                    <div class="table-cell">Current Status</div>
                                    <div class="table-cell">Actions</div>
                                </div>

                                <div class="table-row">
                                    <div class="table-cell name" data-label="Donor Details">NIC 2001XXXXXXX - S. Fernando</div>
                                    <div class="table-cell" data-label="Organ Type">Kidney</div>
                                    <div class="table-cell" data-label="Test Date">2025-10-10</div>
                                    <div class="table-cell" data-label="Current Status"><span class="status-badge status-pending">Under Review</span></div>
                                    <div class="table-cell" data-label="Actions">
                                        <div style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: nowrap;">
                                            <button class="btn btn-success btn-small" onclick="approveEligibility()" style="white-space: nowrap;">Approve</button>
                                            <button class="btn btn-danger btn-small" onclick="rejectEligibility()" style="white-space: nowrap;">Reject</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer style="background: linear-gradient(135deg, #005baa 0%, #003b6e 100%); color: white; text-align: center; padding: 20px; margin-top: 40px; box-shadow: 0 -4px 20px rgba(0, 91, 170, 0.2);">
        <p style="margin: 0; font-size: 14px;">Copyright © 2025 Ministry of Health - LifeConnect Sri Lanka</p>
    </footer>

    <script>
                function approveEligibility() { 
            showServerMessage('Donor eligibility approved and updated in database', 'success'); 
        }
        
        function rejectEligibility() { 
            showServerMessage('Donor eligibility rejected and status updated', 'error'); 
        }
    </script>

    <?php
        require_once __DIR__ . '/footer.php';
    ?>
</body>
</html>
