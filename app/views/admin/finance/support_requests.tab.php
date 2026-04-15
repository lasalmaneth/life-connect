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
                <?= $_SESSION['role'] === 'AC_ADMIN' ? 'Requests Pending Verification' : 'Verified Requests' ?>
            </div>
            <div id="support-pending-count" style="font-size: 1.5rem; font-weight: 700; color: #0ea5e9;">
                <?= $_SESSION['role'] === 'AC_ADMIN' ? ($support_stats['pending'] ?? 0) : ($support_stats['verified'] ?? 0) ?>
            </div>
        </div>
        <div class="stat-card glass-card" style="padding: 1.5rem; border-radius: 20px; background: white; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
            <div style="font-size: 0.875rem; font-weight: 600; color: #64748b; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.025em;">Disbursed Aid</div>
            <div id="support-approved-count" style="font-size: 1.5rem; font-weight: 700; color: #10b981;"><?= $support_stats['approved'] ?? 0 ?></div>
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
            <button onclick="refreshSupportRequests()" style="padding: 0.75rem 1.25rem; border-radius: 14px; border: none; background: #f1f5f9; color: #475569; cursor: pointer; font-weight: 600; transition: background 0.2s;">
                <i class="fa-solid fa-rotate"></i>
            </button>
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
                    <th style="padding: 1.25rem 1.5rem; font-weight: 600; color: #475569; font-size: 0.875rem; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($support_requests)): ?>
                    <tr>
                        <td colspan="6" style="padding: 3rem; text-align: center; color: #94a3b8;">
                            <div style="font-size: 3rem; margin-bottom: 1rem;"><i class="fa-solid fa-folder-open"></i></div>
                            <div>No <?= $_SESSION['role'] === 'AC_ADMIN' ? 'pending' : 'verified' ?> support requests to review.</div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($support_requests as $req): ?>
                        <tr class="support-row" data-status="<?= $req->status ?>" data-search="<?= strtolower($req->patient_name . ' ' . $req->patient_nic . ' ' . $req->reason) ?>" style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;">
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
                                    elseif ($req->status === 'VERIFIED') { $statusColor = '#8b5cf6'; $statusBg = '#f5f3ff'; }
                                    elseif ($req->status === 'PENDING') { $statusColor = '#0ea5e9'; $statusBg = '#f0f9ff'; }
                                ?>
                                <span style="padding: 0.375rem 0.75rem; border-radius: 8px; font-size: 0.75rem; font-weight: 700; background: <?= $statusBg ?>; color: <?= $statusColor ?>; text-transform: uppercase; letter-spacing: 0.025em;">
                                    <?= $req->status ?>
                                </span>
                            </td>
                            <td style="padding: 1.25rem 1.5rem; text-align: right;">
                                <div style="display: flex; justify-content: flex-end; gap: 0.5rem;">
                                    <button onclick="openSupportDetails(<?= htmlspecialchars(json_encode($req)) ?>)" title="View Details" style="width: 32px; height: 32px; border-radius: 8px; border: 1px solid #e2e8f0; background: white; color: #64748b; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s;">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                    <?php if ($req->status === 'PENDING' || $req->status === 'VERIFIED'): ?>
                                        <button onclick="updateSupportStatus(<?= $req->id ?>, 'approved')" title="<?= $_SESSION['role'] === 'AC_ADMIN' ? 'Verify & Forward' : 'Approve & Issue Voucher' ?>" style="width: 32px; height: 32px; border-radius: 8px; border: none; background: #1b4f72; color: white; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s;">
                                            <i class="fa-solid fa-<?= $_SESSION['role'] === 'AC_ADMIN' ? 'check-double' : 'ticket' ?>"></i>
                                        </button>
                                        <button onclick="updateSupportStatus(<?= $req->id ?>, 'REJECTED')" title="Reject Request" style="width: 32px; height: 32px; border-radius: 8px; border: none; background: #f43f5e; color: white; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s;">
                                            <i class="fa-solid fa-xmark"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Support Request Details Modal -->
<div id="supportRequestModal" class="modal">
    <div class="modal-content">
        <div class="modal-scroll-area">
            <div style="display: flex; flex-direction: column; gap: 1.5rem; position: relative;">
                <button class="modal-close" style="position: absolute; top: -15px; right: -15px; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 50%; background: #f1f5f9; border: none; cursor: pointer; color: #64748b; z-index: 10;" onclick="closeSupportModal()">&times;</button>
            
            <div style="text-align: center; margin-bottom: 1rem;">
                <div id="modal-req-icon-box" style="width: 64px; height: 64px; background: #f0f9ff; border-radius: 20px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                    <i class="fa-solid fa-hand-holding-heart" style="font-size: 28px; color: #0ea5e9;"></i>
                </div>
                <h3 style="font-size: 1.25rem; font-weight: 700; color: #1e293b; margin: 0;">Support Application Details</h3>
                <p style="color: #64748b; font-size: 0.875rem; margin-top: 0.25rem;">Reference Case #<span id="modal-req-id">--</span></p>
            </div>

            <div style="background: #f8fafc; border-radius: 16px; padding: 1.5rem; display: flex; flex-direction: column; gap: 1rem;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                    <div>
                        <div style="font-size: 0.75rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin-bottom: 0.25rem;">Patient Applicant</div>
                        <div id="modal-req-name" style="font-weight: 600; color: #1e293b;">--</div>
                        <div id="modal-req-nic" style="font-size: 0.85rem; color: #64748b;">--</div>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-size: 0.75rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin-bottom: 0.25rem;">Date Submitted</div>
                        <div id="modal-req-date" style="font-weight: 600; color: #1e293b;">--</div>
                    </div>
                </div>

                <div style="height: 1px; background: #e2e8f0;"></div>

                <div>
                    <div style="font-size: 0.75rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin-bottom: 0.25rem;">Reason for Support</div>
                    <div id="modal-req-reason" style="font-weight: 600; color: #334155;">--</div>
                    <div id="modal-req-desc" style="font-size: 0.875rem; color: #64748b; margin-top: 0.5rem; line-height: 1.5; font-style: italic;">"--"</div>
                </div>

                <div style="height: 1px; background: #e2e8f0;"></div>

                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-size: 0.75rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin-bottom: 0.25rem;">Requested Support Amt</div>
                        <div id="modal-req-amount" style="font-size: 1.25rem; font-weight: 800; color: #0f172a;">LKR 0.00</div>
                    </div>
                    <div id="modal-req-status-tag">--</div>
                </div>
            </div>

            <div id="modal-req-actions" style="display: flex; gap: 0.75rem; margin-top: 0.5rem;">
                <button onclick="approveFromModal()" style="flex: 1; padding: 1rem; border-radius: 12px; border: none; background: #1b4f72; color: white; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.5rem; transition: transform 0.2s;">
                    <i class="fa-solid fa-<?= $_SESSION['role'] === 'AC_ADMIN' ? 'check-double' : 'ticket' ?>"></i> 
                    <?= $_SESSION['role'] === 'AC_ADMIN' ? 'Verify Request' : 'Issue Voucher' ?>
                </button>
                <button onclick="rejectFromModal()" style="flex: 1; padding: 1rem; border-radius: 12px; border: none; background: #f43f5e; color: white; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.5rem; transition: transform 0.2s;">
                    <i class="fa-solid fa-times-circle"></i> Reject Request
                </button>
            </div>
            
            <button onclick="closeSupportModal()" style="width: 100%; padding: 0.75rem; border-radius: 12px; border: 1px solid #e2e8f0; background: white; color: #64748b; font-weight: 600; cursor: pointer; transition: background 0.2s;">
                Dismiss
            </button>
            </div>
        </div>
    </div>
</div>

<style>
    .support-row:hover {
        background: #f8fafc;
    }
</style>
