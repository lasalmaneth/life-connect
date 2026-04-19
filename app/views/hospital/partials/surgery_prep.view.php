<div id="surgery-prep" class="content-section" style="<?php echo (isset($initialSection) && $initialSection === 'surgery-prep') ? 'display:block' : 'display:none'; ?>">
    <div class="content-header" style="background: white; border-bottom: 1px solid #e2e8f0; padding: 25px 30px; border-radius: 16px 16px 0 0;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 15px;">
                <div style="width: 45px; height: 45px; background: rgba(37, 99, 235, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #2563eb;">
                    <i class="fas fa-calendar-check" style="font-size: 1.25rem;"></i>
                </div>
                <div>
                    <h2 style="margin: 0; font-size: 1.5rem; color: #0f172a; font-weight: 800;">Donor Approval Registry</h2>
                    <p style="margin: 4px 0 0; color: #64748b; font-size: 0.85rem; font-weight: 500;">Manage clinical approvals and generate donation certificates for matched donors.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body" style="padding: 30px; background: #f8fafc; border-radius: 0 0 16px 16px; border: 1px solid #e2e8f0; border-top: none;">
        <div style="background: white; border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                        <th style="padding: 18px 24px; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Match ID</th>
                        <th style="padding: 18px 24px; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Donor Name</th>
                        <th style="padding: 18px 24px; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Organ</th>
                        <th style="padding: 18px 24px; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Surgery Date</th>
                        <th style="padding: 18px 24px; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Status</th>
                        <th style="padding: 18px 24px; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($surgery_matches)): ?>
                        <?php foreach ($surgery_matches as $match): ?>
                            <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;">
                                <td style="padding: 18px 24px;">
                                    <span style="font-weight: 700; color: #1e293b; font-size: 0.9rem;">#MAT-<?= str_pad($match->match_id, 4, '0', STR_PAD_LEFT) ?></span>
                                </td>
                                <td style="padding: 18px 24px;">
                                    <div style="font-weight: 700; color: #0f172a; font-size: 0.95rem;"><?= htmlspecialchars($match->donor_first_name . ' ' . $match->donor_last_name) ?></div>
                                    <div style="font-size: 0.75rem; color: #64748b; margin-top: 2px;">Verified Donor</div>
                                </td>
                                <td style="padding: 18px 24px;">
                                    <span style="padding: 6px 12px; background: #eff6ff; color: #1e40af; border-radius: 8px; font-size: 0.8rem; font-weight: 700;">
                                        <?= htmlspecialchars($match->organ_name) ?>
                                    </span>
                                </td>
                                <td style="padding: 18px 24px;">
                                    <div style="font-weight: 600; color: #1e293b; font-size: 0.9rem;">
                                        <?= date('d/m/Y', strtotime($match->surgery_date)) ?>
                                    </div>
                                    <div style="font-size: 0.75rem; color: #94a3b8;"><?= date('h:i A', strtotime($match->surgery_date)) ?></div>
                                </td>
                                <td style="padding: 18px 24px;">
                                    <?php 
                                        $hStatus = strtoupper(trim((string)($match->hospital_match_status ?? 'PENDING')));
                                        $bg = '#f1f5f9'; $fg = '#64748b';
                                        if ($hStatus === 'ACCEPTED') { $bg = '#dcfce7'; $fg = '#166534'; }
                                        elseif ($hStatus === 'REJECTED') { $bg = '#fee2e2'; $fg = '#991b1b'; }
                                        elseif ($hStatus === 'PENDING') { $bg = '#fef3c7'; $fg = '#92400e'; }
                                    ?>
                                    <span style="padding: 6px 14px; background: <?= $bg ?>; color: <?= $fg ?>; border-radius: 20px; font-size: 0.75rem; font-weight: 800; letter-spacing: 0.02em;">
                                        <?= $hStatus ?>
                                    </span>
                                </td>
                                <td style="padding: 18px 24px; text-align: right;">
                                    <button onclick="viewSurgeryMatchDetails(<?= $match->match_id ?>)" style="background: #2563eb; color: white; border: none; padding: 8px 16px; border-radius: 8px; font-weight: 700; font-size: 0.85rem; cursor: pointer; transition: all 0.2s; white-space: nowrap;">
                                        <i class="fas fa-info-circle" style="margin-right: 6px;"></i> Details
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="padding: 60px 0; text-align: center;">
                                <div style="display: flex; flex-direction: column; align-items: center; gap: 15px;">
                                    <div style="width: 60px; height: 60px; background: #f8fafc; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #cbd5e1;">
                                        <i class="fas fa-calendar-times" style="font-size: 2rem;"></i>
                                    </div>
                                    <div style="color: #64748b; font-weight: 600;">No surgery matches found for clinical approval.</div>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
let currentMatchId = null;

function viewSurgeryMatchDetails(matchId) {
    currentMatchId = matchId;
    
    fetch(`${ROOT}/hospital/get-surgery-match-details?id=${matchId}`)
        .then(response => response.json())
        .then(res => {
            if (res.success) {
                const data = res.data;
                const modal = document.getElementById('surgeryMatchModal');
                
                // Set Header
                document.getElementById('matchSubtitle').innerText = `Pairing Reference: ${data.match_id} | Scheduled: ${new Date(data.surgery_date).toLocaleDateString()}`;
                
                // Donor Details
                document.getElementById('donorDetails').innerHTML = `
                    <div class="match-detail-row">
                        <span class="match-detail-label">Name</span>
                        <span class="match-detail-value">${data.donor_first_name} ${data.donor_last_name}</span>
                    </div>
                    <div class="match-detail-row">
                        <span class="match-detail-label">NIC No</span>
                        <span class="match-detail-value">${data.donor_nic}</span>
                    </div>
                    <div class="match-detail-row">
                        <span class="match-detail-label">Blood Group</span>
                        <span class="match-detail-value" style="color: #ef4444;">${data.donor_blood_group}</span>
                    </div>
                    <div class="match-detail-row">
                        <span class="match-detail-label">Gender</span>
                        <span class="match-detail-value">${data.donor_gender}</span>
                    </div>
                `;
                
                // Recipient Details
                document.getElementById('recipientDetails').innerHTML = `
                    <div class="match-detail-row">
                        <span class="match-detail-label">Requested Organ</span>
                        <span class="match-detail-value" style="color: #2563eb;">${data.organ_name}</span>
                    </div>
                    <div class="match-detail-row">
                        <span class="match-detail-label">Required Group</span>
                        <span class="match-detail-value" style="color: #ef4444;">${data.recipient_blood_group}</span>
                    </div>
                     <div class="match-detail-row">
                        <span class="match-detail-label">Patient Gender</span>
                        <span class="match-detail-value">${data.recipient_gender}</span>
                    </div>
                    <div class="match-detail-row">
                        <span class="match-detail-label">Patient Age</span>
                        <span class="match-detail-value">${data.recipient_age} Years</span>
                    </div>
                `;

                // Warning
                const warningBox = document.getElementById('medicalWarningBox');
                if (data.warning_details) {
                    warningBox.style.display = 'block';
                    document.getElementById('matchWarningText').innerText = data.warning_details;
                } else {
                    warningBox.style.display = 'none';
                }

                // Date & Status Badge
                document.getElementById('matchSurgeryDate').innerText = new Date(data.surgery_date).toLocaleString();

                // Hospital Specific Decision
                const hBox = document.getElementById('hospitalDecisionBox');
                const hStatus = data.hospital_match_status || 'PENDING';
                const hReason = data.hospital_reject_reason;
                
                if (hStatus !== 'PENDING') {
                    hBox.style.display = 'block';
                    const pill = document.getElementById('hospitalStatusPill');
                    pill.innerText = hStatus;
                    pill.style.background = (hStatus === 'ACCEPTED') ? '#dcfce7' : '#fee2e2';
                    pill.style.color = (hStatus === 'ACCEPTED') ? '#166534' : '#991b1b';
                    document.getElementById('hospitalRemarksText').innerText = hReason || 'No reason provided.';
                    
                    if (document.getElementById('matchReason')) {
                        document.getElementById('matchReason').value = hReason || '';
                    }
                } else {
                    hBox.style.display = 'none';
                    if (document.getElementById('matchReason')) {
                        document.getElementById('matchReason').value = '';
                    }
                }

                // LOCKING: Only allow decision if institution has not finalized one yet
                const actionForm = document.getElementById('matchActionForm');
                if (hStatus === 'PENDING') {
                    actionForm.style.display = 'block';
                } else {
                    actionForm.style.display = 'none';
                }
                
                let statusHtml = '';
                if (hStatus === 'ACCEPTED') {
                    statusHtml = '<span style="padding: 8px 16px; background: #dcfce7; color: #166534; border-radius: 8px; font-weight: 800; font-size: 0.85rem;">MATCH ACCEPTED</span>';
                    document.getElementById('matchDocsSection').style.display = 'block';
                    
                    // Set Doc Links
                    document.getElementById('certLink').href = `${ROOT}/hospital/view-donation-certificate?id=${data.match_id}`;
                    document.getElementById('letterLink').href = `${ROOT}/hospital/view-appreciation-letter?id=${data.match_id}`;
                } else if (hStatus === 'REJECTED') {
                    statusHtml = '<span style="padding: 8px 16px; background: #fee2e2; color: #991b1b; border-radius: 8px; font-weight: 800; font-size: 0.85rem;">MATCH REJECTED</span>';
                    document.getElementById('matchDocsSection').style.display = 'none';
                } else {
                    statusHtml = '<span style="padding: 8px 16px; background: #fef3c7; color: #92400e; border-radius: 8px; font-weight: 800; font-size: 0.85rem;">PENDING YOUR DECISION</span>';
                    document.getElementById('matchDocsSection').style.display = 'none';
                }
                document.getElementById('matchStatusBadgeContainer').innerHTML = statusHtml;

                modal.style.display = 'block';
            } else {
                notify(res.message, 'error');
            }
        })
        .catch(err => {
            console.error('Error fetching match details:', err);
            notify('Failed to load match details.', 'error');
        });
}

function closeSurgeryMatchModal() {
    document.getElementById('surgeryMatchModal').style.display = 'none';
}

function submitMatchAction(action) {
    const reason = document.getElementById('matchReason').value;
    
    if (action === 'reject' && !reason.trim()) {
        notify('Please provide a reason for rejection.', 'warning');
        return;
    }

    const confirmMsg = action === 'approve' 
        ? 'Are you sure you want to ACCEPT this match results? Once accepted, this decision is final and irreversible.' 
        : 'Are you sure you want to REJECT this pairing? Once rejected, you cannot reconsider this donor for this specific request.';

    hcConfirm(confirmMsg, { danger: action === 'reject' }).then(ok => {
        if (!ok) return;

        const formData = new FormData();
        formData.append('match_id', currentMatchId);
        formData.append('action', action);
        formData.append('reason', reason);

        fetch(`${ROOT}/hospital/handle-match-action`, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(res => {
            if (res.success) {
                notify(res.message, 'success');
                setTimeout(() => {
                    closeSurgeryMatchModal();
                    location.reload();
                }, 1500);
            } else {
                notify(res.message, 'error');
            }
        })
        .catch(err => {
            console.error('Error submitting match action:', err);
            notify('An error occurred. Please try again.', 'error');
        });
    });
}

window.onclick = function(event) {
    const modal = document.getElementById('surgeryMatchModal');
    if (event.target == modal) {
        closeSurgeryMatchModal();
    }
}
</script>
