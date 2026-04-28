js antribution...AbortController.apply.apply.apply


addpatient - recipient.view.php
hospital.php-- - views > hospital line 160 wage

    < !-- < div class="table-cell" data - label="WEIGHT" > 
                                            <? php echo htmlspecialchars($recipient -> weight ?? 'N/A'); ?> kg
                                        </div > -->
                                        < !-- < div class="table-cell" data - label="TYPE" > 
                                            <? php echo htmlspecialchars($recipient -> type ?? 'N/A'); ?>
                                        </div > -->


    aftercarepatientmodel(370, 263, 246, 229) === model - hospital - sure

Hospital - aftercare recipient view----166 line


to fetch data from DB we type; app / models / hospitals / aftercarepatientmodel.php


call the getRecipientByHospital / ()


===========================

app>models>hospital>aftercarepatientmodel.php> ====NIC

        // NIC Validation: 9 digits + V/X (Old) or 12 digits (New)
        if (!preg_match('/^\d{9}[vVxX]$/', $nic) && !preg_match('/^\d{12}$/', $nic)) {
            throw new \InvalidArgumentException('Invalid NIC format. Must be 9 digits + "V/X" or exactly 12 digits.');
        }


        same 89-112 should put

            public function createRecipientAccount(array $data): string
    {
        $fullName  = trim((string)($data['full_name'] ?? ''));
        $nic       = trim((string)($data['nic'] ?? ''));
        $hospitalReg  = trim((string)($data['hospital_registration_no'] ?? ''));
        $requestedReg = trim((string)($data['registration_number'] ?? ''));

        if ($fullName === '' || $nic === '' || $hospitalReg === '') {
            throw new \InvalidArgumentException('Full name, NIC, and Hospital Registration are required.');
        }

        // NIC Validation: 9 digits + V/X (Old) or 12 digits (New)
        if (!preg_match('/^\d{9}[vVxX]$/', $nic) && !preg_match('/^\d{12}$/', $nic)) {
            throw new \InvalidArgumentException('Invalid NIC format. Must be 9 digits + "V/X" or exactly 12 digits.');
        }

        // Check NIC only in aftercare_patients (source of truth)
        $existing = $this->query(
            "SELECT id FROM {$this->table} WHERE nic = :nic LIMIT 1",
            [':nic' => $nic]
        );
        if ($existing) {
            throw new \RuntimeException("A patient with NIC '$nic' is already registered.");
        }



        =====================
        =====================

        $func app=>views=>hospital=>aftercare-recipient.view.php cp pst

        <?php
//recipient
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="<?php echo ROOT; ?>/assets/css/hospital/hospital.css">
    <title>Aftercare Recipients - LifeConnect</title>
    <style>
        .table-container {
            background: #fff;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            margin-top: 1.5rem;
        }

        .table-content {
            display: flex;
            flex-direction: column;
        }

        .table-header {
            display: grid;
            grid-template-columns: 1fr 1fr 0.8fr 1fr 1.5fr 1fr 1fr;
            gap: 1rem;
            padding: 1rem;
            background: #f8fafc;
            border-radius: 8px;
            font-weight: 700;
            color: #475569;
            font-size: 0.9rem;
        }

        .table-row {
            display: grid;
            grid-template-columns: 1fr 1fr 0.8fr 1fr 1.5fr 1fr 1fr;
            gap: 1rem;
            padding: 1rem;
            border-bottom: 1px solid #e2e8f0;
            align-items: center;
            transition: background 0.15s ease;
        }

        .table-row:hover {
            background: #f1f5f9;
        }

        .table-row:last-child {
            border-bottom: none;
        }

        .table-cell {
            font-size: 0.95rem;
            color: #1e293b;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .status-badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .status-active {
            background: #ecfdf5;
            color: #10b981;
        }

        .status-pending {
            background: #fffbeb;
            color: #f59e0b;
        }

        .btn-small {
            padding: 6px 12px;
            font-size: 0.85rem;
            border-radius: 6px;
        }

        @media (max-width: 1024px) {
            .table-header {
                display: none;
            }

            .table-row {
                grid-template-columns: 1fr;
                gap: 0.5rem;
                padding: 1.5rem;
            }

            .table-cell::before {
                content: attr(data-label);
                font-weight: 800;
                display: block;
                font-size: 0.75rem;
                color: #64748b;
                margin-bottom: 2px;
            }
        }
    </style>
</head>

<body>
    <?php include __DIR__ . '/inc/header.view.php'; ?>

    <div class="container">
        <div class="main-content">
            <?php include __DIR__ . '/inc/sidebar_aftercare.view.php'; ?>

            <div class="content-area">
                <div class="content-section" style="display: block;">
                    <div class="content-header">
                        <div style="display:flex; justify-content: space-between; align-items: center; width:100%;">
                            <div>
                                <h2>Aftercare Recipient Patients</h2>
                                <p>Manage patients who have undergone transplant surgery.</p>
                            </div>
                            <div style="display:flex; gap: 10px;">
                                <button class="btn btn-secondary"
                                    style="background: #fff; color: #1e293b; border: 1px solid #e2e8f0; display: flex; align-items: center; gap: 8px;"
                                    onclick="openExportModal()">
                                    <i class="fas fa-file-export"></i> Export Records
                                </button>
                                <button class="btn btn-primary"
                                    onclick="window.location.href='<?php echo ROOT; ?>/hospital/addpatient/recipient'">
                                    <i class="fas fa-plus"></i> Add Recipient
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="filters-container" style="display: flex; gap: 1rem; margin-bottom: 1.5rem; background: white; padding: 1.25rem; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                        <div style="flex: 1;">
                            <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #64748b; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.025em;">Filter by Organ</label>
                            <select id="organ-filter" onchange="filterRecipients()" style="width: 100%; padding: 0.625rem; border: 1px solid #e2e8f0; border-radius: 8px; color: #1e293b; font-size: 0.95rem; outline: none; transition: border-color 0.2s;">
                                <option value="all">All Organs</option>
                                <?php
                                    $organs = array_unique(array_column($aftercare_recipients, 'surgery_type'));
                                    foreach ($organs as $organ): if($organ):
                                ?>
                                    <option value="<?php echo htmlspecialchars($organ); ?>"><?php echo htmlspecialchars($organ); ?></option>
                                <?php endif; endforeach; ?>
                            </select>
                        </div>
                        <div style="flex: 1;">
                            <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #64748b; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.025em;">Filter by Urgency / Type</label>
                            <select id="urgency-filter" onchange="filterRecipients()" style="width: 100%; padding: 0.625rem; border: 1px solid #e2e8f0; border-radius: 8px; color: #1e293b; font-size: 0.95rem; outline: none; transition: border-color 0.2s;">
                                <option value="all">All Urgency</option>
                                <?php
                                    $types = array_unique(array_column($aftercare_recipients, 'type'));
                                    foreach ($types as $type): if($type):
                                ?>
                                    <option value="<?php echo htmlspecialchars($type); ?>"><?php echo htmlspecialchars($type); ?></option>
                                <?php endif; endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="table-container">
                        <div class="table-content">
                            <div class="table-header table-row aftercare-recipients-grid">
                                <div class="table-cell">NIC</div>
                                <div class="table-cell">NAME</div>
                                <!-- <div class="table-cell">WEIGHT</div> -->
                                <!-- <div class="table-cell">TYPE</div> -->
                                <div class="table-cell">ORGAN RECEIVED</div>
                                <div class="table-cell" onclick="sortRecipientsByDate(event)" style="cursor: pointer; display: flex; align-items: center; gap: 5px; user-select: none;">
                                    SURGERY DATE <i class="fas fa-sort" id="date-sort-icon"></i>
                                </div>
                                <div class="table-cell">STATUS</div>
                            </div>

                            <?php if (!empty($aftercare_recipients)): ?>
                                <?php foreach ($aftercare_recipients as $recipient): ?>
                                    <div class="table-row aftercare-recipients-grid recipient-row"
                                         data-date="<?php echo htmlspecialchars($recipient->surgery_date ?? '0000-00-00'); ?>"
                                         data-organ="<?php echo htmlspecialchars($recipient->surgery_type ?? ''); ?>"
                                         data-urgency="<?php echo htmlspecialchars($recipient->type ?? ''); ?>">
                                        <div class="table-cell" data-label="NIC">
                                            <?php echo htmlspecialchars($recipient->nic ?? 'N/A'); ?>
                                        </div>
                                        <div class="table-cell" data-label="NAME">
                                            <?php echo htmlspecialchars($recipient->full_name ?? 'N/A'); ?>
                                        </div>
                                        <!-- <div class="table-cell" data-label="WEIGHT">
                                            <?php echo htmlspecialchars($recipient->weight ?? 'N/A'); ?> kg
                                        </div>-->
                                        <!-- <div class="table-cell" data-label="TYPE">
                                            <?php echo htmlspecialchars($recipient->type ?? 'N/A'); ?>
                                        </div>-->
                                        <div class="table-cell" data-label="ORGAN RECEIVED">
                                            <?php echo htmlspecialchars($recipient->surgery_type ?? 'N/A'); ?>
                                        </div>
                                        <div class="table-cell" data-label="SURGERY DATE">
                                            <?php echo !empty($recipient->surgery_date) ? date('d/m/Y', strtotime($recipient->surgery_date)) : 'N/A'; ?>
                                        </div>
                                        <div class="table-cell" data-label="STATUS">
                                            <span
                                                class="status-badge <?php echo ($recipient->status === 'ACTIVE') ? 'status-active' : 'status-pending'; ?>"
                                                style="font-size: 0.75rem;">
                                                <?php echo htmlspecialchars($recipient->status ?? 'PENDING'); ?>
                                            </span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div style="padding: 3rem; text-align: center; color: #64748b;">
                                    <i class="fas fa-user-friends"
                                        style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.3;"></i>
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
        let sortDirection = 'desc';

        function sortRecipientsByDate(event) {
            const container = document.querySelector('.table-content');
            const rows = Array.from(container.querySelectorAll('.recipient-row'));
            const icon = document.getElementById('date-sort-icon');

            sortDirection = sortDirection === 'desc' ? 'asc' : 'desc';

            rows.sort((a, b) => {
                const dateA = new Date(a.dataset.date);
                const dateB = new Date(b.dataset.date);
                if (dateA.toString() === 'Invalid Date') return 1;
                if (dateB.toString() === 'Invalid Date') return -1;

                return sortDirection === 'asc' ? dateA - dateB : dateB - dateA;
            });

            rows.forEach(row => container.appendChild(row));

            // Update icon
            if (icon) {
                icon.className = `fas fa-sort-amount-${sortDirection === 'asc' ? 'up' : 'down'}`;
                icon.style.color = '#3b82f6'; // Brighten color when active
            }
        }

        function filterRecipients() {
            const organValue = document.getElementById('organ-filter').value;
            const urgencyValue = document.getElementById('urgency-filter').value;
            const rows = document.querySelectorAll('.recipient-row');
            let visibleCount = 0;

            rows.forEach(row => {
                const organMatch = organValue === 'all' || row.dataset.organ === organValue;
                const urgencyMatch = urgencyValue === 'all' || row.dataset.urgency === urgencyValue;

                if (organMatch && urgencyMatch) {
                    row.style.display = 'grid';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Handle "No results found" logic
            const noResults = document.getElementById('no-filter-results');
            if (visibleCount === 0) {
                if (!noResults) {
                    const msg = document.createElement('div');
                    msg.id = 'no-filter-results';
                    msg.style.padding = '3rem';
                    msg.style.textAlign = 'center';
                    msg.style.color = '#64748b';
                    msg.innerHTML = '<p>No patients match the selected filters.</p>';
                    document.querySelector('.table-content').appendChild(msg);
                }
            } else if (noResults) {
                noResults.remove();
            }
        }

        function openExportModal() {
            const modal = document.getElementById('export-modal');
            if (modal) modal.classList.add('show');
        }

        function closeExportModal() {
            const modal = document.getElementById('export-modal');
            if (modal) modal.classList.remove('show');
        }

        function downloadExport() {
            const format = document.getElementById('export-format').value;
            if (!format) {
                showServerMessage('Please select an export format', 'error');
                return;
            }

            closeExportModal();
            showServerMessage('Generating professional PDF report...', 'info');

            const recipients = <?php echo json_encode($aftercare_recipients ?? []); ?>;

            setTimeout(() => {
                if (format === 'csv') {
                    let csvContent = "NIC,Name,Registration,Surgery Type,Surgery Date,Status\n";
                    recipients.forEach(r => {
                        csvContent += `"${r.nic}","${r.full_name}","${r.registration_number}","${r.surgery_type || ''}","${r.surgery_date || ''}","${r.status}"\n`;
                    });

                    const blob = new Blob([csvContent], { type: 'text/csv' });
                    const link = document.createElement("a");
                    link.href = URL.createObjectURL(blob);
                    link.download = "Aftercare_Recipients_Report.csv";
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    showServerMessage('CSV downloaded successfully!', 'success');

                } else if (format === 'xlsx') {
                    let html = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
                    html += '<head><meta charset="utf-8"></head><body><table>';
                    html += '<tr><th>NIC</th><th>Full Name</th><th>Reg Number</th><th>Surgery Type</th><th>Surgery Date</th><th>Status</th></tr>';
                    recipients.forEach(r => {
                        html += `<tr><td>${r.nic}</td><td>${r.full_name}</td><td>${r.registration_number}</td><td>${r.surgery_type || 'N/A'}</td><td>${r.surgery_date || 'N/A'}</td><td>${r.status}</td></tr>`;
                    });
                    html += '</table></body></html>';

                    const blob = new Blob([html], { type: 'application/vnd.ms-excel' });
                    const link = document.createElement("a");
                    link.href = URL.createObjectURL(blob);
                    link.download = "Aftercare_Recipients_Report.xls";
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    showServerMessage('Excel file downloaded successfully!', 'success');

                } else if (format === 'pdf') {
                    let printWin = window.open('', '_blank');
                    const logoUrl = '<?php echo ROOT; ?>/public/assets/images/logo.png';
                    let html = '<html><head><meta charset="utf-8"><title>Aftercare Recipients Report</title>';
                    html += '<style>';
                    html += 'body{margin:0;padding:40px;font-family:Arial,sans-serif;color:#111827;}';
                    html += '.watermark{position:fixed;inset:0;z-index:0;display:flex;align-items:center;justify-content:center;pointer-events:none;}';
                    html += '.watermark img{width:520px;max-width:85%;opacity:.04;}';
                    html += '.watermark .wm-text{position:absolute;font-size:64px;font-weight:800;color:#9ca3af;opacity:.08;transform:rotate(-25deg);text-align:center;letter-spacing:2px;}';
                    html += '.content{position:relative;z-index:1;}';
                    html += '.report-header{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:30px;border-bottom:2px solid #f3f4f6;padding-bottom:15px;}';
                    html += '.header-left{display:flex;align-items:center;gap:15px;}';
                    html += '.header-left img{height:60px;width:auto;}';
                    html += '.report-title{margin:0;font-size:24px;font-weight:800;color:#1e3a8a;line-height:1.2;}';
                    html += '.report-sub{margin:4px 0 0;font-size:14px;color:#64748b;font-weight:500;}';
                    html += '.report-meta{text-align:right;font-size:12px;color:#64748b;}';
                    html += 'table{width:100%;border-collapse:collapse;margin-top:20px;box-shadow: 0 1px 2px rgba(0,0,0,0.05);}';
                    html += 'th,td{border:1px solid #e5e7eb;padding:12px 10px;text-align:left;font-size:12px;}';
                    html += 'th{background-color:#f8fafc;color:#475569;font-weight:700;text-transform:uppercase;letter-spacing:0.025em;}';
                    html += 'tr:nth-child(even){background-color:#f9fafb;}';
                    html += '.status-label{padding:4px 8px;border-radius:4px;font-weight:700;font-size:10px;}';
                    html += '.status-active{background:#dcfce7;color:#15803d;}';
                    html += '@media print{body{padding:0} .watermark{display:flex}}';
                    html += '</style>';
                    html += '</head><body>';
                    html += '<div class="watermark"><img src="' + logoUrl + '" alt="LifeConnect"><div class="wm-text">LifeConnect Sri Lanka</div></div>';
                    html += '<div class="content">';
                    html += '<div class="report-header">';
                    html += '<div class="header-left">';
                    html += '<img src="' + logoUrl + '" alt="LifeConnect">';
                    html += '<div><div class="report-title">Recipient Patients Report</div><div class="report-sub">LifeConnect Sri Lanka — Hospital Records</div></div>';
                    html += '</div>';
                    html += '<div class="report-meta">Generated on: ' + new Date().toLocaleString() + '</div>';
                    html += '</div>';
                    html += '<table>';
                    html += '<tr><th>NIC</th><th>Name</th><th>Organ Received</th><th>Surgery Date</th><th>Status</th></tr>';
                    recipients.forEach(r => {
                        html += `<tr><td>${r.nic}</td><td>${r.full_name}</td><td>${r.surgery_type || 'N/A'}</td><td>${r.surgery_date || 'N/A'}</td><td><span class="status-label ${r.status === 'ACTIVE' ? 'status-active' : ''}">${r.status}</span></td></tr>`;
                    });
                    html += '</table>';
                    html += '</div></body></html>';
                    printWin.document.write(html);
                    printWin.document.close();
                    printWin.focus();
                    setTimeout(() => {
                        printWin.print();
                    }, 800);
                }
            }, 800);
        }

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
    <?php include __DIR__ . '/partials/export_modal.view.php'; ?>
    <?php include __DIR__ . '/inc/footer.view.php'; ?>

    ALTER TABLE organ_requests ADD COLUMN [new_field_name] VARCHAR(255) NULL;
    ALTER TABLE recipient_patient ADD COLUMN witnesss VARCHAR(255) DEFAULT NULL;

    UPDATE upcoming_appoinments
SET test_completion_status = 'Completed'
WHERE appointment_id = 101;