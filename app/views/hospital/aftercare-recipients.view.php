<?php
// Hospital Portal — View Aftercare Recipients (Standalone Page with small sidebar)
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="<?php echo ROOT; ?>/assets/css/hospital/hospital.css">
    <title>Aftercare Recipients - LifeConnect</title>
    <style>
        .table-container { background: #fff; border-radius: 12px; padding: 1.5rem; box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-top: 1.5rem; }
        .table-content { display: flex; flex-direction: column; }
        .table-header { display: grid; grid-template-columns: 1fr 1fr 1.5fr 1fr 1fr 1fr 1fr; gap: 1rem; padding: 1rem; background: #f8fafc; border-radius: 8px; font-weight: 700; color: #475569; font-size: 0.9rem; }
        .table-row { display: grid; grid-template-columns: 1fr 1fr 1.5fr 1fr 1fr 1fr 1fr; gap: 1rem; padding: 1rem; border-bottom: 1px solid #e2e8f0; align-items: center; transition: background 0.15s ease; }
        .table-row:hover { background: #f1f5f9; }
        .table-row:last-child { border-bottom: none; }
        .table-cell { font-size: 0.95rem; color: #1e293b; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .status-badge { padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; }
        .status-active { background: #ecfdf5; color: #10b981; }
        .status-pending { background: #fffbeb; color: #f59e0b; }
        .btn-small { padding: 6px 12px; font-size: 0.85rem; border-radius: 6px; }
        
        @media (max-width: 1024px) {
            .table-header { display: none; }
            .table-row { grid-template-columns: 1fr; gap: 0.5rem; padding: 1.5rem; }
            .table-cell::before { content: attr(data-label); font-weight: 800; display: block; font-size: 0.75rem; color: #64748b; margin-bottom: 2px; }
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="header-content">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <a href="<?php echo ROOT ?? '/life-connect'; ?>" style="text-decoration:none; display:flex; align-items:center; gap:10px;">
                    <img src="<?php echo ROOT ?? '/life-connect'; ?>/public/assets/images/logo.png" alt="LifeConnect" style="height:40px; width: auto;">
                    <div>
                        <strong style="display:block; font-size:1.1rem; color:#003b6e; line-height:1.2;">LifeConnect</strong>
                        <p style="margin:0; font-size:.68rem; color:#6b7280; padding-top:2px;">Hospital Portal</p>
                    </div>
                </a>
            </div>
            <div class="user-info">
                <div class="user-avatar"><?php echo strtoupper(substr($hospital_details['name'], 0, 1)); ?></div>
                <div class="user-details">
                    <div style="font-weight: 600; font-size: 0.9rem;"><?php echo htmlspecialchars($hospital_details['name']); ?></div>
                    <div style="font-size: 0.8rem; opacity: 0.8;"><?php echo htmlspecialchars($hospital_details['role']); ?></div>
                </div>
                <div class="user-actions">
                    <button class="btn-logout" onclick="window.location.href='<?php echo ROOT; ?>/logout'" title="Logout">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16,17 21,12 16,7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="main-content">
            <?php require_once 'sidebar.php'; ?>

            <div class="content-area">
                <div class="content-section" style="display: block;">
                    <div class="content-header">
                        <div style="display:flex; justify-content: space-between; align-items: center; width:100%;">
                            <div>
                                <h2>Aftercare Recipient Patients</h2>
                                <p>Manage patients who have undergone transplant surgery.</p>
                            </div>
                            <button class="btn btn-primary" onclick="window.location.href='<?php echo ROOT; ?>/hospital/addpatient/recipient'">
                                <i class="fas fa-plus"></i> Add Recipient
                            </button>
                        </div>
                    </div>

                    <div class="table-container">
                        <div class="table-content">
                            <div class="table-header">
                                <div class="table-cell">NIC</div>
                                <div class="table-cell">Reg. Number</div>
                                <div class="table-cell">Full Name</div>
                                <div class="table-cell">Surgery Type</div>
                                <div class="table-cell">Surgery Date</div>
                                <div class="table-cell">Status</div>
                                <div class="table-cell">Actions</div>
                            </div>

                            <?php if (!empty($aftercare_recipients)): ?>
                                <?php foreach ($aftercare_recipients as $recipient): ?>
                                    <div class="table-row">
                                        <div class="table-cell" data-label="NIC"><?php echo htmlspecialchars($recipient->nic); ?></div>
                                        <div class="table-cell" data-label="Reg. Number"><?php echo htmlspecialchars($recipient->registration_number); ?></div>
                                        <div class="table-cell" data-label="Full Name"><?php echo htmlspecialchars($recipient->full_name); ?></div>
                                        <div class="table-cell" data-label="Surgery Type"><?php echo htmlspecialchars($recipient->surgery_type ?? 'N/A'); ?></div>
                                        <div class="table-cell" data-label="Surgery Date"><?php echo !empty($recipient->surgery_date) ? date('Y-m-d', strtotime($recipient->surgery_date)) : 'N/A'; ?></div>
                                        <div class="table-cell" data-label="Status">
                                            <span class="status-badge <?php echo ($recipient->status === 'ACTIVE') ? 'status-active' : 'status-pending'; ?>">
                                                <?php echo htmlspecialchars($recipient->status); ?>
                                            </span>
                                        </div>
                                        <div class="table-cell" data-label="Actions">
                                            <button class="btn btn-secondary btn-small" onclick="viewRecipientDetails('<?php echo $recipient->nic; ?>')">View Details</button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div style="padding: 3rem; text-align: center; color: #64748b;">
                                    <i class="fas fa-user-friends" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.3;"></i>
                                    <p>No aftercare recipient patients found.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function viewRecipientDetails(nic) {
            const recipients = <?php echo json_encode($aftercare_recipients ?? []); ?>;
            const recipient = recipients.find(r => r.nic === nic);

            if (recipient) {
                let details = `Recipient: ${recipient.full_name}\n`;
                details += `NIC: ${recipient.nic}\n`;
                details += `Reg: ${recipient.registration_number}\n`;
                details += `Surgery Type: ${recipient.surgery_type || 'N/A'}\n`;
                details += `Surgery Date: ${recipient.surgery_date || 'N/A'}\n`;
                details += `Contact: ${recipient.contact_details || 'N/A'}\n`;
                details += `Medical: ${recipient.medical_details || 'N/A'}`;
                
                alert(details);
            }
        }
    </script>
    <?php require_once 'footer.php'; ?>
</body>

</html>
