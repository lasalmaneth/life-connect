<div id="support-requests" class="content-section" style="display: none;">
    <div class="content-header">
        <h2>Patient Support Requests</h2>
        <p>Review and verify patient applications for medical assistance and financial support grants.</p>
    </div>

    <!-- Quick Stats for Support -->
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; margin-bottom: 2.5rem;">
        <div class="stat-card glass-card" style="padding: 1.5rem; border-radius: 20px; background: white; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
            <div style="font-size: 0.875rem; font-weight: 600; color: #64748b; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.025em;">Total Requests</div>
            <div id="support-total-count" style="font-size: 1.5rem; font-weight: 700; color: #1e293b;"><?= $support_stats['total'] ?? 0 ?></div>
        </div>
        <div class="stat-card glass-card" style="padding: 1.5rem; border-radius: 20px; background: white; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
            <div style="font-size: 0.875rem; font-weight: 600; color: #64748b; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.025em;">
                Approved Requests
            </div>
            <div id="support-approved-count-card" style="font-size: 1.5rem; font-weight: 700; color: #10b981;">
                <?= $support_stats['approved'] ?? 0 ?>
            </div>
        </div>
        <div class="stat-card glass-card" style="padding: 1.5rem; border-radius: 20px; background: white; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
            <div style="font-size: 0.875rem; font-weight: 600; color: #64748b; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.025em;">Total Approved Aid</div>
            <div id="support-approved-amount" style="font-size: 1.5rem; font-weight: 700; color: #1e293b;">
                <span style="font-size: 0.9rem; color: #64748b; font-weight: 600;">LKR</span> <?= number_format($support_stats['approved_amount'] ?? 0, 0) ?>
            </div>
        </div>
        <div class="stat-card glass-card" style="padding: 1.5rem; border-radius: 20px; background: white; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
            <div style="font-size: 0.875rem; font-weight: 600; color: #64748b; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.025em;">Rejected Requests</div>
            <div id="support-rejected-count" style="font-size: 1.5rem; font-weight: 700; color: #f43f5e;"><?= $support_stats['rejected'] ?? 0 ?></div>
        </div>
    </div>

    <!-- Filter and Search Bar -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; gap: 1rem;">
        <div style="position: relative; flex: 1; max-width: 400px;">
            <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #94a3b8;"></i>
            <input type="text" id="support-search-input" placeholder="Search patient name, NIC or reason..." 
                style="width: 100%; padding: 0.75rem 1rem 0.75rem 2.75rem; border-radius: 14px; border: 1px solid #e2e8f0; outline: none; font-size: 0.95rem; transition: border-color 0.2s;"
                onkeyup="filterSupportRequests()">
        </div>
        <div style="display: flex; gap: 0.75rem;">
            <select id="support-status-filter" onchange="filterSupportRequests()"
                style="padding: 0.75rem 1rem; border-radius: 14px; border: 1px solid #e2e8f0; background: white; color: #475569; outline: none; cursor: pointer; font-size: 0.95rem;">
                <option value="ALL">All Status</option>
                <option value="PENDING">Pending Review</option>
                <option value="APPROVED">Approved</option>
                <option value="REJECTED">Rejected</option>
            </select>
        </div>
    </div>

    <!-- Support Requests Table -->
    <div style="background: white; border-radius: 24px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05);">
        <table style="width: 100%; border-collapse: collapse; text-align: left;" id="support-requests-table">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                    <th style="padding: 1.25rem 1.5rem; font-weight: 600; color: #475569; font-size: 0.875rem;">Patient Details</th>
                    <th style="padding: 1.25rem 1.5rem; font-weight: 600; color: #475569; font-size: 0.875rem;">Support Reason</th>
                    <th style="padding: 1.25rem 1.5rem; font-weight: 600; color: #475569; font-size: 0.875rem;">Requested Amount</th>
                    <th style="padding: 1.25rem 1.5rem; font-weight: 600; color: #475569; font-size: 0.875rem;">Date</th>
                    <th style="padding: 1.25rem 1.5rem; font-weight: 600; color: #475569; font-size: 0.875rem;">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($support_requests)): ?>
                    <tr>
                        <td colspan="6" style="padding: 3rem; text-align: center; color: #94a3b8;">
                            <div style="font-size: 3rem; margin-bottom: 1rem;"><i class="fa-solid fa-folder-open"></i></div>
                            <div>No support requests found in the system.</div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($support_requests as $req): ?>
                        <tr class="support-row" 
                            data-status="<?= $req->status ?>" 
                            data-search="<?= strtolower($req->patient_name . ' ' . $req->patient_nic . ' ' . $req->reason) ?>" 
                            onclick="openSupportDetails(<?= htmlspecialchars(json_encode($req)) ?>)"
                            style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s; cursor: pointer;">
                            <td style="padding: 1.25rem 1.5rem;">
                                <div style="font-weight: 600; color: #1e293b;"><?= htmlspecialchars($req->patient_name) ?></div>
                                <div style="font-size: 0.8rem; color: #64748b;"><?= htmlspecialchars($req->patient_nic) ?></div>
                            </td>
                            <td style="padding: 1.25rem 1.5rem;">
                                <div style="font-weight: 500; color: #334155;"><?= htmlspecialchars($req->reason) ?></div>
                                <div style="font-size: 0.8rem; color: #94a3b8; max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"><?= htmlspecialchars($req->description) ?></div>
                            </td>
                            <td style="padding: 1.25rem 1.5rem;">
                                <div style="font-weight: 700; color: #0f172a;">LKR <?= number_format($req->amount ?? 0, 2) ?></div>
                            </td>
                            <td style="padding: 1.25rem 1.5rem; color: #64748b; font-size: 0.875rem;">
                                <?= date('M d, Y', strtotime($req->submitted_date)) ?>
                            </td>
                            <td style="padding: 1.25rem 1.5rem;">
                                <?php 
                                    $statusColor = '#94a3b8';
                                    $statusBg = '#f1f5f9';
                                    if ($req->status === 'APPROVED') { $statusColor = '#10b981'; $statusBg = '#ecfdf5'; }
                                    elseif ($req->status === 'REJECTED') { $statusColor = '#f43f5e'; $statusBg = '#fff1f2'; }
                                    elseif ($req->status === 'PENDING') { $statusColor = '#0ea5e9'; $statusBg = '#f0f9ff'; }
                                ?>
                                <span style="padding: 0.375rem 0.75rem; border-radius: 8px; font-size: 0.75rem; font-weight: 700; background: <?= $statusBg ?>; color: <?= $statusColor ?>; text-transform: uppercase; letter-spacing: 0.025em;">
                                    <?= $req->status ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Support Request Details Modal (Premium Overhaul) -->
<div id="supportRequestModal" class="modal">
    <div class="modal-content">
        <div class="modal-scroll-area">
            <div style="display: flex; flex-direction: column; gap: 1.25rem; position: relative;">
                <button class="modal-close" style="position: absolute; top: -15px; right: -15px; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 50%; background: #f1f5f9; border: none; cursor: pointer; color: #64748b; z-index: 10;" onclick="closeSupportModal()">&times;</button>
            
            <div style="display: flex; align-items: center; gap: 1.25rem;">
                <!-- Status Icon -->
                <div id="modal-req-status-icon-box" style="flex-shrink: 0; width: 48px; height: 48px; background: #f0f9ff; border-radius: 12px; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease;">
                    <i id="modal-req-status-icon" class="fa-solid fa-hand-holding-heart" style="font-size: 20px; color: #0ea5e9;"></i>
                </div>

                <!-- Title -->
                <div>
                    <h2 style="margin: 0; font-size: 1.5rem; font-weight: 800; color: #0f172a; line-height: 1.2;">Request Details</h2>
                    <p style="margin: 0; color: #64748b; font-size: 0.85rem; font-weight: 500;">Reference Case #<span id="modal-req-id">--</span></p>
                </div>
            </div>

            <p style="margin: 0; color: #64748b; font-size: 0.9rem; line-height: 1.5; font-weight: 500;">Reviewing patient application for medical assistance and financial support.</p>

            <!-- Details Card (2-Column Grid) -->
            <div style="background: #f0f7ff; border-radius: 16px; padding: 1.5rem; display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <span style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Patient Name</span>
                    <div id="modal-req-patient-name" style="font-size: 1.05rem; font-weight: 700; color: #1e293b;">-</div>
                </div>
                <div>
                    <span style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Status</span>
                    <div id="modal-req-status" style="font-size: 0.95rem; font-weight: 700; color: #0ea5e9;">-</div>
                </div>
                
                <div style="grid-column: span 2;">
                    <span style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Patient NIC Number</span>
                    <div id="modal-req-nic" style="font-size: 1rem; font-weight: 700; color: #1e293b;">-</div>
                </div>

                <div>
                    <span style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Requested Amount</span>
                    <div id="modal-req-amount" style="font-size: 1.1rem; font-weight: 800; color: #0f172a;">-</div>
                </div>
                <div>
                    <span style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Submission Date</span>
                    <div id="modal-req-date" style="font-size: 0.95rem; font-weight: 600; color: #0f172a;">-</div>
                </div>

                <div style="grid-column: span 2;">
                    <span style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Subject / Reason</span>
                    <div id="modal-req-reason" style="font-size: 0.95rem; font-weight: 700; color: #1e293b; background: white; padding: 10px; border-radius: 8px; border: 1px solid #e2e8f0; margin-top: 4px;">-</div>
                </div>

                <div style="grid-column: span 2;">
                    <span style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Patient's Description</span>
                    <div id="modal-req-description" style="font-size: 0.9rem; font-weight: 500; color: #475569; font-style: italic; background: #f8fafc; padding: 10px; border-radius: 8px; border: 1px solid #e2e8f0; margin-top: 5px;">-</div>
                </div>
            </div>

            <!-- Inline Confirmation Box (Hidden by default) -->
            <div id="voucher-confirm-box" style="display: none; background: #fff7ed; border: 1px solid #fed7aa; border-radius: 16px; padding: 1.25rem; margin-top: 0.5rem; animation: slideDown 0.3s ease;">
                <div style="display: flex; gap: 1rem; align-items: flex-start;">
                    <div style="width: 40px; height: 40px; background: #ffedd5; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i class="fa-solid fa-triangle-exclamation" style="color: #f97316;"></i>
                    </div>
                    <div>
                        <h4 style="margin: 0; font-size: 0.95rem; font-weight: 700; color: #9a3412;">Issue Financial Voucher?</h4>
                        <p style="margin: 4px 0 0; font-size: 0.8rem; color: #c2410c; line-height: 1.4;">This will approve the patient's request and automatically generate a unique financial voucher for the requested amount.</p>
                        
                        <div style="display: flex; gap: 0.75rem; margin-top: 1rem;">
                            <button type="button" onclick="confirmVoucherApproval()" style="background: #ea580c; color: white; border: none; padding: 0.5rem 1rem; border-radius: 8px; font-weight: 700; font-size: 0.85rem; cursor: pointer;">Yes, Issue Voucher</button>
                            <button type="button" onclick="cancelVoucherApproval()" style="background: white; color: #9a3412; border: 1px solid #fed7aa; padding: 0.5rem 1rem; border-radius: 8px; font-weight: 700; font-size: 0.85rem; cursor: pointer;">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Buttons -->
            <div id="modal-req-actions" style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 0.5rem;">
                <button type="button" onclick="approveFromModal()" id="btn-modal-approve" style="background: #10b981; color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: all 0.2s;">
                    <i class="fa-solid fa-<?= $_SESSION['role'] === 'AC_ADMIN' ? 'check-double' : 'ticket' ?>"></i>
                    <?= $_SESSION['role'] === 'AC_ADMIN' ? 'Verify' : 'Issue Voucher' ?>
                </button>
                <button type="button" onclick="rejectFromModal()" id="btn-modal-reject" style="background: #f43f5e; color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: all 0.2s;">
                    <i class="fa-solid fa-xmark"></i> Reject
                </button>
                <button type="button" onclick="closeSupportModal()" style="background: #f1f5f9; color: #475569; border: none; padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 700; cursor: pointer; transition: background 0.2s;">Close</button>
            </div>
            </div>
        </div>
    </div>
</div>

<style>
    .support-row:hover {
        background: #f8fafc;
    }
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
