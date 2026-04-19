<?php
// Recipient Patient Portal — Aftercare Support (donor-style UI)
$patientName = !empty($patient->full_name) ? (string)$patient->full_name : 'Recipient';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aftercare Support | LifeConnect</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="<?= ROOT ?>/public/assets/css/donor/donor.css">
    <link rel="stylesheet" href="<?= ROOT ?>/public/assets/css/aftercare/aftercare.css">
    <link rel="stylesheet" href="<?= ROOT ?>/public/assets/css/hospital/hospital.css">
    <style>
        /* Aftercare page uses Hospital header styles, but must remain scrollable.
           hospital.css sets body as a fixed-height flex container; override locally. */
        html { height: auto; }
        body.aftercare-portal {
            height: 100vh;
            display: flex;
            overflow: hidden;
            background: #f8fafc;
        }
        .main-container {
            display: flex;
            width: 100%;
            height: 100vh;
        }
        .sidebar {
            width: 280px;
            background: white;
            border-right: 1px solid #e2e8f0;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            z-index: 100;
        }
        .sidebar-nav {
            padding: 20px;
            flex: 1;
        }
        .sidebar-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 12px;
            color: #64748b;
            text-decoration: none;
            font-weight: 700;
            font-size: 0.9rem;
            margin-bottom: 4px;
            transition: all 0.2s;
            cursor: pointer;
        }
        .sidebar-item:hover {
            background: #f1f5f9;
            color: #1e293b;
        }
        .sidebar-item.active {
            background: #eff6ff;
            color: #2563eb;
        }
        .sidebar-item i {
            font-size: 1.1rem;
            width: 24px;
            text-align: center;
        }
        .content-area {
            flex: 1;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }
        .content-section {
            display: none;
            padding: 30px;
        }
        .content-section.active {
            display: block;
        }
        
        /* Overrides for mobile/standardizing */
        main.d-content { margin-left: 0; width: 100%; padding: 0; }
        .header { background: white; border-bottom: 1px solid #e2e8f0; position: sticky; top: 0; z-index: 50; }
        
        /* Re-center header items for horizontal alignment while staying sleek */
        .header-content { align-items: center !important; }
        .header-right { align-items: center !important; }
    </style>
</head>
<body class="aftercare-portal">

<div class="header">
    <div class="header-content">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <a href="<?php echo rtrim((ROOT ?? '/life-connect'), '/'); ?>/" style="text-decoration:none; display:flex; align-items:center; gap:10px;">
                <img src="<?php echo ROOT ?? '/life-connect'; ?>/public/assets/images/logo.png" alt="LifeConnect" style="height:40px; width: auto;">
                <div>
                    <strong style="display:block; font-size:1.1rem; color:#003b6e; line-height:1.2;">LifeConnect</strong>
                    <p style="margin:0; font-size:.68rem; color:#6b7280; padding-top:2px;">Aftercare Portal</p>
                </div>
            </a>
        </div>

        <div class="header-right" style="display: flex; align-items: center; gap: 1.5rem;">
            <a class="nav-link" href="<?php echo rtrim((ROOT ?? '/life-connect'), '/'); ?>/" title="Home" style="display: flex; align-items: center; gap: 8px; color: #64748b; text-decoration: none; font-weight: 600; font-size: 0.9rem;">
                <i class="fa-solid fa-house"></i>
                <span>Home</span>
            </a>

            <button class="notification-bell" type="button" title="Notifications" aria-label="Notifications" style="background: none; border: none; color: #64748b; cursor: pointer; font-size: 1.1rem; padding: 0; display: flex; align-items: center; justify-content: center;">
                <i class="fa-solid fa-bell"></i>
            </button>

            <div class="user-info" style="display: flex; align-items: center; gap: 12px; background: #f8fafc; padding: 6px 12px; border-radius: 12px; border: 1px solid #e2e8f0; cursor: default;">
                <div class="user-avatar" style="width: 32px; height: 32px; background: linear-gradient(135deg, #3b82f6, #1d4ed8); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.8rem;"><?php echo strtoupper(substr($patientName, 0, 1)); ?></div>
                <div style="display: flex; flex-direction: column; gap: 0;">
                    <div style="font-size: 0.85rem; font-weight: 700; color: #1e293b; line-height: 1.2;"><?php echo htmlspecialchars($patientName); ?></div>
                    <div style="font-size: 0.7rem; color: #64748b; font-weight: 600;">ID: <?php echo htmlspecialchars((string)($patient->registration_number ?? 'RECIPIENT')); ?></div>
                </div>
                <div style="width: 1px; height: 24px; background: #e2e8f0; margin: 0 4px;"></div>
                <a class="btn-logout" href="<?= ROOT ?>/aftercare/logout" title="Logout" aria-label="Logout" style="color: #ef4444; text-decoration: none; font-size: 1.1rem; display: flex; align-items: center; justify-content: center; transition: transform 0.2s;">
                    <i class="fa-solid fa-right-from-bracket"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="main-container">
    <div class="sidebar">
        <div class="sidebar-nav">
            <div style="font-size: 0.65rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 15px; padding-left: 10px;">User Menu</div>
            <a onclick="showSection('dashboard', this)" class="sidebar-item active">
                <i class="fa-solid fa-gauge-high"></i>
                Dashboard
            </a>
            <a onclick="showSection('appointments', this)" class="sidebar-item">
                <i class="fa-solid fa-calendar-check"></i>
                My Appointments
            </a>
            <a onclick="showSection('support', this)" class="sidebar-item">
                <i class="fa-solid fa-hand-holding-medical"></i>
                Support Request
            </a>
            <a onclick="showSection('medical-history', this)" class="sidebar-item">
                <i class="fa-solid fa-file-medical"></i>
                Medical History
            </a>
        </div>
        
        <div style="padding: 20px; border-top: 1px solid #f1f5f9;">
            <div style="background: #f8fafc; padding: 15px; border-radius: 12px; border: 1px solid #e2e8f0;">
                <div style="font-size: 0.75rem; font-weight: 800; color: #64748b; margin-bottom: 5px;">Linked Hospital</div>
                <div style="font-weight: 700; color: #1e293b; font-size: 0.85rem;"><?php echo htmlspecialchars((string)($patient->hospital_registration_no ?? 'HOSP-GEN-01')); ?></div>
            </div>
        </div>
    </div>

    <div class="content-area">
        <main class="d-content">
            <!-- Global Page Header (Optional, or per section) -->
            <div id="dashboard" class="content-section active">
                <div class="d-content__header">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                        <div>
                            <h2>Patient Dashboard</h2>
                            <p>Overview of your aftercare progress and upcoming activities.</p>
                        </div>
                        <div class="d-status d-status--success">
                            <div class="d-status__dot"></div>
                            Active Support
                        </div>
                    </div>
                </div>
                
                <div class="d-content__body">
                    <div class="d-dashboard-grid" style="grid-template-columns: 1fr; gap: 2rem;">
                         <div class="d-widget">
                            <div class="d-widget__header">
                                <div class="d-widget__title">Appointments Calendar</div>
                            </div>
                            <div class="d-widget__body">
                                <div id="calendar-container" style="padding: 20px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="appointments" class="content-section">
                <div class="d-content__header">
                    <h2>My Appointments</h2>
                    <p>Manage and track your scheduled hospital consultations.</p>
                </div>
                <div class="d-content__body">
                    <div class="d-widget" style="margin-top: 0;">
                        <div class="d-widget__header" style="display: flex; justify-content: space-between; align-items: center;">
                            <div class="d-widget__title">Appointment History</div>
                            <button class="d-btn d-btn--sm d-btn--primary" onclick="openAppointmentModal()">Book Appointment</button>
                        </div>

                <div class="d-widget__body" style="padding: 0;">
                    <div class="d-table-wrap" style="border: none; border-radius: 0 0 var(--r) var(--r);">
                        <table class="d-table">
                            <thead>
                                <tr>
                                    <th>Date & Time</th>
                                    <th>Type</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="appointmentsTableBody">
                                <?php if (!empty($appointments)): foreach ($appointments as $apt): ?>
                                    <tr>
                                        <td><?= date('M d, Y - h:i A', strtotime($apt->appointment_date)) ?></td>
                                        <td style="font-weight: 600; color: var(--blue-800);"><?= htmlspecialchars($apt->appointment_type) ?></td>
                                        <td style="color: var(--g500);"><?= htmlspecialchars($apt->description ?: 'N/A') ?></td>
                                        <td>
                                            <?php
                                                $statusClass = 'd-status--neutral';
                                                if ($apt->status === 'Scheduled') $statusClass = 'd-status--info';
                                                if ($apt->status === 'Completed') $statusClass = 'd-status--success';
                                                if ($apt->status === 'Cancelled' || $apt->status === 'Missed') $statusClass = 'd-status--danger';
                                            ?>
                                            <div class="d-status <?= $statusClass ?>" style="display: inline-flex; padding: 0.25rem 0.5rem; font-size: 0.75rem;">
                                                <div class="d-status__dot"></div>
                                                <?= htmlspecialchars($apt->status) ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; else: ?>
                                    <tr>
                                        <td colspan="4" style="text-align: center; padding: 2rem; color: var(--g500); font-style: italic;">
                                            No aftercare appointments scheduled yet.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    </div>
                </div>
            </div>
        </div>

            <div id="support" class="content-section">
                <div class="d-content__header">
                    <h2>Support Requests</h2>
                    <p>Request financial or medical assistance from your hospital.</p>
                </div>
                <div class="d-content__body">
                    <div class="d-widget" style="margin-top: 0;">
                        <div class="d-widget__header" style="display: flex; justify-content: space-between; align-items: center;">
                            <div class="d-widget__title">Request Registry</div>
                            <button class="d-btn d-btn--sm d-btn--primary" onclick="openSupportRequestModal()">New Request</button>
                        </div>

                <div class="d-widget__body" style="padding: 0;">
                    <div class="d-table-wrap" style="border: none; border-radius: 0 0 var(--r) var(--r);">
                        <table class="d-table">
                            <thead>
                                <tr>
                                    <th>Date Submitted</th>
                                    <th>Reason</th>
                                    <th>Amount</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Assigned Voucher</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($support_requests)): foreach ($support_requests as $req): ?>
                                    <tr>
                                        <td><?= date('d/m/Y', strtotime($req->created_at ?? $req->submitted_date ?? 'now')) ?></td>
                                        <td style="font-weight: 500; color: var(--blue-800);"><?= htmlspecialchars($req->reason) ?></td>
                                        <td style="font-weight: 700; color: var(--blue-800);">
                                            <?php if (isset($req->amount) && $req->amount !== '' && is_numeric($req->amount)): ?>
                                                LKR <?= number_format((float)$req->amount, 2) ?>
                                            <?php else: ?>
                                                —
                                            <?php endif; ?>
                                        </td>
                                        <td style="color: var(--g500);">
                                            <?php
                                                $fullDesc = (string)($req->description ?? '');
                                                $markerPos = strpos($fullDesc, '[Hospital Review]');
                                                $userDesc = $markerPos !== false ? trim(substr($fullDesc, 0, $markerPos)) : trim($fullDesc);
                                                $reviewReason = '';
                                                if (preg_match('/\[Hospital Review\][\s\S]*?\nReason:\s*([^\n]+)/i', $fullDesc, $m)) {
                                                    $reviewReason = trim((string)$m[1]);
                                                }
                                            ?>
                                            <div><?= htmlspecialchars($userDesc !== '' ? $userDesc : 'N/A') ?></div>
                                            <?php if (strtoupper((string)($req->status ?? '')) === 'REJECTED' && $reviewReason !== ''): ?>
                                                <div style="margin-top: 6px; color: var(--blue-800); font-weight: 600;">
                                                    Rejection reason: <?= htmlspecialchars($reviewReason) ?>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php
                                                $status = (string)($req->status ?: 'PENDING');
                                                $statusClass = 'd-status--warning';
                                                if ($status === 'APPROVED') $statusClass = 'd-status--success';
                                                if ($status === 'REJECTED') $statusClass = 'd-status--danger';
                                            ?>
                                            <div class="d-status <?= $statusClass ?>" style="display: inline-flex; padding: 0.25rem 0.5rem; font-size: 0.75rem;">
                                                <div class="d-status__dot"></div>
                                                <?= htmlspecialchars($status) ?>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if (!empty($req->voucher_code)): ?>
                                                <div style="font-size: 0.8rem;">
                                                    <strong style="color: #2563eb;">Code:</strong> <?= htmlspecialchars($req->voucher_code) ?><br>
                                                    <strong style="color: #64748b;">Expires:</strong> <?= htmlspecialchars(date('d/m/Y', strtotime($req->expiry_date))) ?><br>
                                                    <?php $vStatus = $req->voucher_status ?? 'ACTIVE'; ?>
                                                    <span style="display: inline-block; margin-top: 3px; font-size: 0.7rem; padding: 2px 5px; border-radius: 4px; background: <?= $vStatus === 'ACTIVE' ? '#dcfce7' : ($vStatus === 'USED' ? '#e2e8f0' : '#fee2e2') ?>; color: <?= $vStatus === 'ACTIVE' ? '#166534' : ($vStatus === 'USED' ? '#475569' : '#991b1b') ?>; font-weight: 600;">
                                                        <?= htmlspecialchars($vStatus) ?>
                                                    </span>
                                                </div>
                                            <?php else: ?>
                                                <span style="color: #94a3b8; font-size: 0.8rem; font-style: italic;">No voucher assigned</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; else: ?>
                                    <tr>
                                        <td colspan="6" style="text-align: center; padding: 2rem; color: var(--g500); font-style: italic;">
                                            No support requests submitted yet.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    </div>
                </div>
            </div>
        </div>

            <!-- Medical History Section -->
            <div id="medical-history" class="content-section">
                <div class="d-content__header">
                    <h2>Medical History & Test Results</h2>
                    <p>Access your clinical records and laboratory reports.</p>
                </div>
                <div class="d-content__body">
                    <div class="d-widget" style="margin-top: 0;">
                        <div class="d-widget__body">
                    <?php if (!empty($medical_history)): ?>
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem; padding: 1rem 0;">
                            <?php foreach ($medical_history as $record): ?>
                                <div style="background: linear-gradient(135deg, #f8fafc 0%, #eef2f7 100%); border-left: 4px solid var(--secondary-color); padding: 1.25rem; border-radius: 10px; box-shadow: 0 2px 8px rgba(0, 91, 170, 0.1); transition: all 0.3s ease;">
                                    <!-- Test Header -->
                                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                                        <div>
                                            <div style="font-weight: 800; color: #0f172a; font-size: 0.95rem; line-height: 1.4;">
                                                <?= htmlspecialchars($record->test_name ?? 'Test') ?>
                                            </div>
                                            <div style="font-size: 0.75rem; color: #64748b; margin-top: 0.25rem;">
                                                <?= htmlspecialchars((string)date('d/m/Y', strtotime($record->test_date ?? 'now'))) ?>
                                            </div>
                                        </div>
                                        <div style="background: var(--blue-50); color: var(--secondary-color); padding: 0.35rem 0.7rem; border-radius: 6px; font-size: 0.7rem; font-weight: 700; white-space: nowrap;">
                                            Completed
                                        </div>
                                    </div>

                                    <!-- Test Result -->
                                    <div style="background: white; padding: 0.75rem; border-radius: 8px; margin-bottom: 0.75rem; border-left: 3px solid var(--secondary-color);">
                                        <div style="font-size: 0.75rem; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.35rem;">Result</div>
                                        <div style="font-weight: 700; color: #0f172a; word-break: break-word;">
                                            <?= htmlspecialchars($record->result_value ?? 'N/A') ?>
                                        </div>
                                    </div>

                                    <!-- Hospital Information -->
                                    <?php if (!empty($record->hospital_name)): ?>
                                        <div style="background: rgba(255, 255, 255, 0.5); padding: 0.75rem; border-radius: 8px; margin-bottom: 0.75rem;">
                                            <div style="font-size: 0.7rem; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.35rem;">
                                                <i class="fa-solid fa-hospital" style="margin-right: 0.4rem; color: var(--secondary-color);"></i>Provided by
                                            </div>
                                            <div style="font-weight: 700; color: #0f172a; font-size: 0.9rem;">
                                                <?= htmlspecialchars($record->hospital_name) ?>
                                            </div>
                                            <?php if (!empty($record->hospital_address)): ?>
                                                <div style="font-size: 0.75rem; color: #64748b; margin-top: 0.3rem;">
                                                    <?= htmlspecialchars($record->hospital_address) ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>

                                    <!-- View Document Link -->
                                    <?php if (!empty($record->document_path)): ?>
                                        <a href="<?= htmlspecialchars((string)($record->document_path)) ?>" target="_blank" style="display: inline-flex; align-items: center; gap: 0.4rem; color: var(--secondary-color); text-decoration: none; font-size: 0.85rem; font-weight: 600; padding: 0.5rem 0.75rem; background: rgba(0, 91, 170, 0.08); border-radius: 6px; transition: all 0.3s ease; cursor: pointer;">
                                            <i class="fa-solid fa-file-pdf"></i>
                                            View Report
                                        </a>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div style="text-align: center; padding: 3rem 1rem; color: var(--g500);">
                            <i class="fa-solid fa-file-medical" style="font-size: 2.5rem; opacity: 0.3; margin-bottom: 1rem; display: block;"></i>
                            <p style="margin: 0; font-style: italic;">No medical history records available yet.</p>
                            <p style="margin: 0.5rem 0 0; font-size: 0.9rem; color: #94a3b8;">Your test results and medical records will appear here automatically from linked hospitals.</p>
                        </div>
                    <?php endif; ?>
                    </div>
                </div>
            </div></div>
        </main>
    </div>
</div>

<!-- Appointment Modal -->
<div id="appointmentModal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5);">
    <div style="background: white; margin: 10% auto; padding: 2rem; border-radius: 8px; width: 90%; max-width: 500px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h3 style="margin: 0; color: #1f2937; font-size: 1.25rem;">Book an Appointment</h3>
            <button onclick="closeAppointmentModal()" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #6b7280;">&times;</button>
        </div>

        <form id="appointmentForm" onsubmit="submitAppointment(event)">
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #374151;">Appointment Date & Time <span style="color: #ef4444;">*</span></label>
                <input type="datetime-local" id="appointmentDateInput" required style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 1rem;">
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #374151;">Hospital <span style="color: #ef4444;">*</span></label>
                <select id="appointmentHospitalInput" required style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 1rem;">
                    <option value="">Select Hospital</option>
                    <?php if (!empty($hospitals)): foreach ($hospitals as $hosp): ?>
                        <option value="<?= htmlspecialchars($hosp->registration_number ?? $hosp->id) ?>"><?= htmlspecialchars($hosp->name) ?></option>
                    <?php endforeach; endif; ?>
                </select>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #374151;">Appointment Type <span style="color: #ef4444;">*</span></label>
                <select id="appointmentTypeInput" required style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 1rem;">
                    <option value="">Select Type</option>
                    <option value="Follow-up">Follow-up Checkup</option>
                    <option value="Health Review">Health Review</option>
                    <option value="Medical Consultation">Medical Consultation</option>
                    <option value="Laboratory Tests">Laboratory Tests</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #374151;">Reason for Appointment</label>
                <textarea id="appointmentReasonInput" placeholder="Please describe the reason or any specific concerns..." style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 1rem; font-family: inherit; resize: vertical; min-height: 100px;"></textarea>
            </div>

            <div style="display: flex; gap: 1rem;">
                <button type="submit" class="d-btn d-btn--primary" style="flex: 1; padding: 0.75rem; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; background: #2563eb; color: white;">Book Appointment</button>
                <button type="button" onclick="closeAppointmentModal()" class="d-btn d-btn--secondary" style="flex: 1; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 6px; font-weight: 600; cursor: pointer; background: white; color: #374151;">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Support Request Modal -->
<div id="supportRequestModal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5);">
    <div style="background: white; margin: 10% auto; padding: 2rem; border-radius: 8px; width: 90%; max-width: 500px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h3 style="margin: 0; color: #1f2937; font-size: 1.25rem;">Submit Support Request</h3>
            <button onclick="closeSupportRequestModal()" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #6b7280;">&times;</button>
        </div>

        <form id="supportRequestForm" onsubmit="submitSupportRequest(event)">
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #374151;">Hospital <span style="color: #ef4444;">*</span></label>
                <select id="supportHospitalInput" required style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 1rem;">
                    <option value="">Select Hospital</option>
                    <?php if (!empty($hospitals)): foreach ($hospitals as $hosp): ?>
                        <option value="<?= htmlspecialchars($hosp->registration_number ?? $hosp->id) ?>"><?= htmlspecialchars($hosp->name) ?></option>
                    <?php endforeach; endif; ?>
                </select>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #374151;">Type of Support Required <span style="color: #ef4444;">*</span></label>
                <select id="supportReasonInput" required style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 1rem;">
                    <option value="">Select Support Type</option>
                    <option value="Medical Support">Medical Support</option>
                    <option value="Financial Support">Financial Support</option>
                    <option value="Transportation Assistance">Transportation Assistance</option>
                    <option value="Travel Cost Support">Travel Cost Support</option>
                    <option value="Test Cost Support">Test Cost Support</option>
                    <option value="Medication Support">Medication Support</option>
                    <option value="Counselling Support">Counselling Support</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #374151;">Amount (LKR) <span style="color: #6b7280; font-weight: 500;">(optional)</span></label>
                <input id="supportAmountInput" type="number" min="0" step="0.01" placeholder="e.g., 2500" style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 1rem;" />
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #374151;">Detailed Description</label>
                <textarea id="supportDescriptionInput" placeholder="Please provide details about your support request..." style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 1rem; font-family: inherit; resize: vertical; min-height: 120px;"></textarea>
            </div>

            <div style="display: flex; gap: 1rem;">
                <button type="submit" class="d-btn d-btn--primary" style="flex: 1; padding: 0.75rem; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; background: #2563eb; color: white;">Submit Request</button>
                <button type="button" onclick="closeSupportRequestModal()" class="d-btn d-btn--secondary" style="flex: 1; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 6px; font-weight: 600; cursor: pointer; background: white; color: #374151;">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
function showSection(sectionId, btn) {
    // Hide all sections
    document.querySelectorAll('.content-section').forEach(section => {
        section.classList.remove('active');
    });
    
    // Show target section
    const target = document.getElementById(sectionId);
    if (target) {
        target.classList.add('active');
    }
    
    // Update sidebar active state
    document.querySelectorAll('.sidebar-item').forEach(item => {
        item.classList.remove('active');
    });
    if (btn) btn.classList.add('active');
    
    // Smooth scroll to top of content
    document.querySelector('.content-area').scrollTop = 0;
}

const appointmentsData = <?php echo json_encode(array_map(function($apt) {
    return [
        'date' => date('Y-m-d', strtotime($apt->appointment_date)),
        'time' => date('h:i A', strtotime($apt->appointment_date)),
        'type' => $apt->appointment_type,
        'description' => $apt->description ?? '',
        'status' => $apt->status
    ];
}, $appointments ?? [])); ?>;

class AppointmentCalendar {
    constructor(containerId, appointmentsArray) {
        this.container = document.getElementById(containerId);
        this.appointments = appointmentsArray;
        this.currentDate = new Date();
        this.render();
    }

    getDaysInMonth(date) {
        return new Date(date.getFullYear(), date.getMonth() + 1, 0).getDate();
    }

    getFirstDayOfMonth(date) {
        return new Date(date.getFullYear(), date.getMonth(), 1).getDay();
    }

    hasAppointment(day) {
        const dateStr = `${this.currentDate.getFullYear()}-${String(this.currentDate.getMonth() + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        return this.appointments.some(apt => apt.date === dateStr);
    }

    getAppointmentsForDay(day) {
        const dateStr = `${this.currentDate.getFullYear()}-${String(this.currentDate.getMonth() + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        return this.appointments.filter(apt => apt.date === dateStr);
    }

    previousMonth() {
        this.currentDate.setMonth(this.currentDate.getMonth() - 1);
        this.render();
    }

    nextMonth() {
        this.currentDate.setMonth(this.currentDate.getMonth() + 1);
        this.render();
    }

    render() {
        const monthYear = this.currentDate.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
        const daysInMonth = this.getDaysInMonth(this.currentDate);
        const firstDay = this.getFirstDayOfMonth(this.currentDate);

        let html = `
            <div style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <button onclick="calendar.previousMonth()" style="background: #f3f4f6; border: 1px solid #e5e7eb; padding: 8px 12px; border-radius: 6px; cursor: pointer; color: #374151; font-weight: 500;">
                        Previous
                    </button>
                    <h3 style="margin: 0; color: #1f2937; font-size: 1.25rem; font-weight: 600;">${monthYear}</h3>
                    <button onclick="calendar.nextMonth()" style="background: #f3f4f6; border: 1px solid #e5e7eb; padding: 8px 12px; border-radius: 6px; cursor: pointer; color: #374151; font-weight: 500;">
                        Next
                    </button>
                </div>

                <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 8px;">
        `;

        const dayLabels = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        dayLabels.forEach(label => {
            html += `<div style="text-align: center; font-weight: 700; color: #6b7280; padding: 8px; font-size: 0.9rem;">${label}</div>`;
        });

        for (let i = 0; i < firstDay; i++) {
            html += `<div style="padding: 8px;"></div>`;
        }

        for (let day = 1; day <= daysInMonth; day++) {
            const hasApt = this.hasAppointment(day);
            const apts = this.getAppointmentsForDay(day);
            const bgColor = hasApt ? '#dbeafe' : '#f9fafb';
            const borderColor = hasApt ? '#3b82f6' : '#e5e7eb';
            const hoverStyle = 'cursor: pointer; transition: all 0.2s ease;';

            html += `
                <div style="background: ${bgColor}; border: 2px solid ${borderColor}; border-radius: 8px; padding: 8px; text-align: center; min-height: 60px; display: flex; flex-direction: column; align-items: center; justify-content: center; ${hoverStyle}"
                     onmouseover="this.style.background='#eff6ff'; this.style.borderColor='#0ea5e9';"
                     onmouseout="this.style.background='${bgColor}'; this.style.borderColor='${borderColor}';"
                     onclick="${hasApt ? `showAppointmentDetails(${day})` : `openAppointmentForDate(${day})`}">
                    <div style="font-weight: ${hasApt ? '700' : '500'}; color: ${hasApt ? '#1e40af' : '#6b7280'}; font-size: 1rem;">${day}</div>
                    ${hasApt ? `<div style="font-size: 0.7rem; color: #3b82f6; margin-top: 4px;">${apts.length} appt</div>` : `<div style="font-size: 0.7rem; color: #9ca3af; margin-top: 4px;">Book</div>`}
                </div>
            `;
        }

        html += `</div>`;
        this.container.innerHTML = html;
    }
}

function showAppointmentDetails(day) {
    const apts = calendar.getAppointmentsForDay(day);
    const monthYear = calendar.currentDate.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
    if (apts.length === 0) return;

    let details = `Appointments on ${monthYear} ${day}\n\n`;
    apts.forEach(apt => {
        details += `${apt.time} — ${apt.type} (${apt.status})\n${apt.description || ''}\n\n`;
    });
    alert(details);
}

function openAppointmentForDate(day) {
    const date = new Date(calendar.currentDate.getFullYear(), calendar.currentDate.getMonth(), day);
    const dateStr = date.toISOString().split('T')[0];
    document.getElementById('appointmentDateInput').value = dateStr + 'T09:00';
    openAppointmentModal();
}

function openAppointmentModal() {
    const modal = document.getElementById('appointmentModal');
    if (modal) {
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }
}

function closeAppointmentModal() {
    const modal = document.getElementById('appointmentModal');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
        document.getElementById('appointmentForm').reset();
    }
}

function submitAppointment(e) {
    e.preventDefault();

    const appointmentDate = document.getElementById('appointmentDateInput').value;
    const appointmentType = document.getElementById('appointmentTypeInput').value;
    const description = document.getElementById('appointmentReasonInput').value;
    const hospitalRegistrationNo = document.getElementById('appointmentHospitalInput').value;

    if (!appointmentDate || !appointmentType || !hospitalRegistrationNo) {
        alert('Please fill in all required fields');
        return;
    }

    const formData = new FormData();
    formData.append('appointment_date', appointmentDate);
    formData.append('appointment_type', appointmentType);
    formData.append('description', description);
    formData.append('hospital_registration_no', hospitalRegistrationNo);

    fetch('<?php echo ROOT; ?>/aftercare/create-appointment', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Appointment booked successfully!');
            closeAppointmentModal();

            if (data.appointment) {
                // Update calendar dataset in-place
                appointmentsData.push({
                    date: data.appointment.date,
                    time: data.appointment.time,
                    type: data.appointment.type,
                    description: data.appointment.description || '',
                    status: data.appointment.status
                });
                calendar.render();

                // Update appointments table
                const tbody = document.getElementById('appointmentsTableBody');
                if (tbody) {
                    // Remove the empty-state row if present
                    const emptyRow = tbody.querySelector('tr td[colspan="4"]');
                    if (emptyRow) {
                        tbody.innerHTML = '';
                    }

                    const status = (data.appointment.status || 'Scheduled');
                    let statusClass = 'd-status--neutral';
                    if (status === 'Scheduled') statusClass = 'd-status--info';
                    if (status === 'Completed') statusClass = 'd-status--success';
                    if (status === 'Cancelled' || status === 'Missed') statusClass = 'd-status--danger';

                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${data.appointment.datetime_display || ''}</td>
                        <td style="font-weight: 600; color: var(--blue-800);">${escapeHtml(data.appointment.type || '')}</td>
                        <td style="color: var(--g500);">${escapeHtml((data.appointment.description || '') || 'N/A')}</td>
                        <td>
                            <div class="d-status ${statusClass}" style="display: inline-flex; padding: 0.25rem 0.5rem; font-size: 0.75rem;">
                                <div class="d-status__dot"></div>
                                ${escapeHtml(status)}
                            </div>
                        </td>
                    `;
                    tbody.appendChild(tr);
                }
            } else {
                // Fallback for older API responses
                location.reload();
            }
        } else {
            alert('Error: ' + (data.message || 'Unable to book appointment'));
        }
    })
    .catch(() => alert('An error occurred while booking the appointment'));
}

function escapeHtml(str) {
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

function openSupportRequestModal() {
    const modal = document.getElementById('supportRequestModal');
    if (modal) {
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }
}

function closeSupportRequestModal() {
    const modal = document.getElementById('supportRequestModal');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
        document.getElementById('supportRequestForm').reset();
    }
}

function submitSupportRequest(e) {
    e.preventDefault();

    const reason = document.getElementById('supportReasonInput').value;
    const description = document.getElementById('supportDescriptionInput').value;
    const hospitalRegistrationNo = document.getElementById('supportHospitalInput').value;
    const amount = (document.getElementById('supportAmountInput')?.value || '').trim();

    if (!reason || !hospitalRegistrationNo) {
        alert('Please fill in all required fields');
        return;
    }

    const formData = new FormData();
    formData.append('reason', reason);
    formData.append('description', description);
    formData.append('hospital_registration_no', hospitalRegistrationNo);
    if (amount !== '') formData.append('amount', amount);

    fetch('<?php echo ROOT; ?>/aftercare/submit-support-request', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Support request submitted successfully!');
            closeSupportRequestModal();
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Unable to submit request'));
        }
    })
    .catch(() => alert('An error occurred while submitting the request'));
}

const calendar = new AppointmentCalendar('calendar-container', appointmentsData);

window.onclick = function(event) {
    const appointmentModal = document.getElementById('appointmentModal');
    const supportRequestModal = document.getElementById('supportRequestModal');
    if (event.target === appointmentModal) closeAppointmentModal();
    if (event.target === supportRequestModal) closeSupportRequestModal();
};
</script>

</body>
</html>


