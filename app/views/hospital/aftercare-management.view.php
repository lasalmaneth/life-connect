<?php $this->view('hospital/inc/header', $data); ?>

<div class="container">
    <div class="main-content">
        <?php $this->view('hospital/inc/sidebar_aftercare', $data); ?>

        <div class="content-area">
            <div class="content-section" style="display: block;">
                <!-- Header -->
                <div class="content-header">
                    <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <div style="width: 45px; height: 45px; background: rgba(37, 99, 235, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #2563eb;">
                                <i class="fas fa-hand-holding-medical" style="font-size: 1.25rem;"></i>
                            </div>
                            <div>
                                <h2 style="margin: 0; font-size: 1.5rem; color: #0f172a; font-weight: 800;">Aftercare Management</h2>
                                <p style="margin: 4px 0 0; color: #64748b; font-size: 0.85rem; font-weight: 500;">Manage care requests and appointment schedules for donors and recipients.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="content-body">
                    <!-- Tabs Navigation -->
                    <div style="display: flex; gap: 10px; margin-bottom: 25px; background: #edf2f7; padding: 5px; border-radius: 12px; width: fit-content;">
                        <button onclick="switchTab('donor-requests', this)" class="tab-btn active" style="padding: 10px 24px; border: none; border-radius: 8px; font-weight: 700; font-size: 0.9rem; cursor: pointer; transition: all 0.2s; background: white; color: #2563eb;">
                            Donor Aftercare
                            <?php if(count($donor_requests) > 0): ?>
                                <span style="background: #ef4444; color: white; padding: 2px 6px; border-radius: 6px; font-size: 0.7rem; margin-left: 5px;"><?= count($donor_requests) ?></span>
                            <?php endif; ?>
                        </button>
                        <button onclick="switchTab('recipient-schedules', this)" class="tab-btn" style="padding: 10px 24px; border: none; border-radius: 8px; font-weight: 700; font-size: 0.9rem; cursor: pointer; transition: all 0.2s; background: transparent; color: #64748b;">
                            Recipient Appointments
                            <?php if(count($recipient_requests) > 0): ?>
                                <span style="background: #2563eb; color: white; padding: 2px 6px; border-radius: 6px; font-size: 0.7rem; margin-left: 5px;"><?= count($recipient_requests) ?></span>
                            <?php endif; ?>
                        </button>
                    </div>

                    <!-- Donor Support Requests Tab -->
                    <div id="donor-requests" class="tab-content active-tab" style="animation: fadeIn 0.3s ease-out;">
                        <div style="background: white; border-radius: 14px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
                            <div style="padding: 20px 25px; border-bottom: 1px solid #f1f5f9; background: #fff;">
                                <h3 style="margin: 0; font-size: 1rem; color: #1e293b; font-weight: 700;">Donor Aftercare</h3>
                            </div>
                            <div style="padding: 20px;">
                                <?php if(!empty($donor_requests)): ?>
                                    <table style="width: 100%; border-collapse: separate; border-spacing: 0;">
                                        <thead>
                                            <tr style="background: #f8fafc;">
                                                <th style="padding: 12px 15px; text-align: left; color: #64748b; font-size: 0.75rem; text-transform: uppercase; font-weight: 700; border-bottom: 2px solid #e2e8f0;">Donor NIC / Name</th>
                                                <th style="padding: 12px 15px; text-align: left; color: #64748b; font-size: 0.75rem; text-transform: uppercase; font-weight: 700; border-bottom: 2px solid #e2e8f0;">Requested Date</th>
                                                <th style="padding: 12px 15px; text-align: left; color: #64748b; font-size: 0.75rem; text-transform: uppercase; font-weight: 700; border-bottom: 2px solid #e2e8f0;">Description</th>
                                                <th style="padding: 12px 15px; text-align: left; color: #64748b; font-size: 0.75rem; text-transform: uppercase; font-weight: 700; border-bottom: 2px solid #e2e8f0;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($donor_requests as $req): ?>
                                                <tr style="border-bottom: 1px solid #f1f5f9;">
                                                    <td style="padding: 15px;">
                                                        <div style="font-weight: 600; color: #0f172a;"><?= htmlspecialchars($req->patient_name ?? 'Donor') ?></div>
                                                        <div style="font-size: 0.8rem; color: #64748b; font-family: monospace;"><?= htmlspecialchars($req->patient_nic ?? 'N/A') ?></div>
                                                    </td>
                                                    <td style="padding: 15px; color: #2563eb; font-weight: 700; font-size: 0.9rem;"><?= date('d/m/Y', strtotime($req->submitted_date ?? ($req->created_at ?? 'now'))) ?></td>
                                                    <td style="padding: 15px; color: #475569; font-size: 0.85rem; max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                                        <?= htmlspecialchars(($req->reason ? '['.$req->reason.'] ' : '') . ($req->description ?: 'No details provided')) ?>
                                                        <?php if(!empty($req->amount)): ?>
                                                            <div style="font-size: 0.75rem; color: #10b981; font-weight: 600;">Amount: <?= number_format($req->amount, 2) ?></div>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td style="padding: 15px;">
                                                        <button onclick="viewSupportDetails(<?= htmlspecialchars(json_encode($req)) ?>)" style="padding: 6px 16px; background: #2563eb; border: none; color: white; border-radius: 8px; font-weight: 600; font-size: 0.75rem; cursor: pointer; transition: all 0.2s;">View & Respond</button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                <?php else: ?>
                                    <div style="text-align: center; padding: 40px; color: #64748b;">
                                        <i class="fas fa-calendar-alt" style="font-size: 2.5rem; margin-bottom: 15px; opacity: 0.3;"></i>
                                        <p>No active donor aftercare appointments found.</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Recipient Schedules Tab -->
                    <div id="recipient-schedules" class="tab-content" style="display: none; animation: fadeIn 0.3s ease-out;">
                        <div style="background: white; border-radius: 14px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
                            <div style="padding: 20px 25px; border-bottom: 1px solid #f1f5f9; background: #fff;">
                                <h3 style="margin: 0; font-size: 1rem; color: #1e293b; font-weight: 700;">Recipient Appointment Requests</h3>
                            </div>
                            <div style="padding: 20px;">
                                <?php if(!empty($recipient_requests)): ?>
                                    <table style="width: 100%; border-collapse: separate; border-spacing: 0;">
                                        <thead>
                                            <tr style="background: #f8fafc;">
                                                <th style="padding: 12px 15px; text-align: left; color: #64748b; font-size: 0.75rem; text-transform: uppercase; font-weight: 700; border-bottom: 2px solid #e2e8f0;">Recipient NIC / Name</th>
                                                <th style="padding: 12px 15px; text-align: left; color: #64748b; font-size: 0.75rem; text-transform: uppercase; font-weight: 700; border-bottom: 2px solid #e2e8f0;">Requested Date</th>
                                                <th style="padding: 12px 15px; text-align: left; color: #64748b; font-size: 0.75rem; text-transform: uppercase; font-weight: 700; border-bottom: 2px solid #e2e8f0;">Description</th>
                                                <th style="padding: 12px 15px; text-align: left; color: #64748b; font-size: 0.75rem; text-transform: uppercase; font-weight: 700; border-bottom: 2px solid #e2e8f0;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($recipient_requests as $apt): ?>
                                                <tr style="border-bottom: 1px solid #f1f5f9;">
                                                    <td style="padding: 15px;">
                                                        <div style="font-weight: 600; color: #0f172a;"><?= htmlspecialchars($apt->patient_name ?? 'Recipient') ?></div>
                                                        <div style="font-size: 0.8rem; color: #64748b; font-family: monospace;"><?= htmlspecialchars($apt->nic ?? 'N/A') ?></div>
                                                    </td>
                                                    <td style="padding: 15px; color: #2563eb; font-weight: 700; font-size: 0.9rem;"><?= date('d/m/Y h:i A', strtotime($apt->appointment_date)) ?></td>
                                                    <td style="padding: 15px; color: #475569; font-size: 0.85rem; max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"><?= htmlspecialchars($apt->description ?: 'No details provided') ?></td>
                                                    <td style="padding: 15px;">
                                                        <div style="display: flex; gap: 8px;">
                                                            <button onclick="respondToAppointment(<?= $apt->appointment_id ?>, 'accept')" style="padding: 6px 12px; background: #10b981; border: none; color: white; border-radius: 8px; font-weight: 600; font-size: 0.75rem; cursor: pointer; transition: all 0.2s;">Accept</button>
                                                            <button onclick="respondToAppointment(<?= $apt->appointment_id ?>, 'reject')" style="padding: 6px 12px; background: white; border: 1px solid #ef4444; color: #ef4444; border-radius: 8px; font-weight: 600; font-size: 0.75rem; cursor: pointer; transition: all 0.2s;">Decline</button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                <?php else: ?>
                                    <div style="text-align: center; padding: 40px; color: #64748b;">
                                        <i class="fas fa-calendar-alt" style="font-size: 2.5rem; margin-bottom: 15px; opacity: 0.3;"></i>
                                        <p>No pending recipient appointment requests.</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div> <!-- End content-body -->
            </div> <!-- End content-section -->
        </div> <!-- End content-area -->
    </div> <!-- End main-content -->
</div> <!-- End container -->

<!-- Support Request Details Modal -->
<div id="support-modal" class="modal-overlay" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); backdrop-filter: blur(4px); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; width: 500px; border-radius: 20px; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); animation: modalIn 0.3s ease-out;">
        <div style="background: #2563eb; padding: 25px; color: white; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0; font-size: 1.1rem; font-weight: 700;">Support Request Details</h3>
            <button onclick="closeModal('support-modal')" style="background: none; border: none; color: white; cursor: pointer; font-size: 1.25rem;"><i class="fas fa-times"></i></button>
        </div>
        <div style="padding: 30px;">
            <div id="modal-content" style="display: flex; flex-direction: column; gap: 20px;">
                <!-- Content via JS -->
            </div>
            <div id="modal-actions" style="margin-top: 30px; display: flex; justify-content: flex-end; gap: 12px; border-top: 1px solid #f1f5f9; padding-top: 20px;">
                <!-- Actions via JS -->
            </div>
        </div>
    </div>
</div>

<style>
    .tab-btn.active {
        background: white !important;
        color: #2563eb !important;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
    }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes modalIn { from { opacity: 0; scale: 0.95; } to { opacity: 1; scale: 1; } }
</style>

<script>
    function switchTab(tabId, btn) {
        document.querySelectorAll('.tab-content').forEach(t => t.style.display = 'none');
        document.getElementById(tabId).style.display = 'block';
        
        document.querySelectorAll('.tab-btn').forEach(b => {
            b.classList.remove('active');
            b.style.background = 'transparent';
            b.style.color = '#64748b';
            b.style.boxShadow = 'none';
        });
        
        btn.classList.add('active');
        btn.style.background = 'white';
        btn.style.color = '#2563eb';
    }

    function viewSupportDetails(req) {
        const modal = document.getElementById('support-modal');
        const content = document.getElementById('modal-content');
        const actions = document.getElementById('modal-actions');
        
        content.innerHTML = `
            <div>
                <label style="font-size: 0.75rem; color: #64748b; font-weight: 700; text-transform: uppercase;">Request Type</label>
                <div style="font-weight: 700; color: #1e293b; font-size: 1.1rem; margin-top: 4px;">${req.request_type || 'Care Support'}</div>
            </div>
            <div>
                <label style="font-size: 0.75rem; color: #64748b; font-weight: 700; text-transform: uppercase;">Description / Urgent Needs</label>
                <div style="margin-top: 6px; color: #475569; line-height: 1.5; font-size: 0.95rem; background: #f8fafc; padding: 15px; border-radius: 12px; border: 1px solid #e2e8f0;">
                    ${req.description || 'No description provided'}
                </div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div>
                    <label style="font-size: 0.75rem; color: #64748b; font-weight: 700; text-transform: uppercase;">Submitted Date</label>
                    <div style="font-weight: 600; color: #1e293b; margin-top: 4px;">${new Date(req.created_at || req.submitted_date).toLocaleDateString()}</div>
                </div>
                <div>
                    <label style="font-size: 0.75rem; color: #64748b; font-weight: 700; text-transform: uppercase;">Current Status</label>
                    <div style="font-weight: 600; color: #2563eb; margin-top: 4px;">${req.status || 'PENDING'}</div>
                </div>
            </div>
        `;

        if (req.status && req.status.toUpperCase() === 'PENDING') {
            actions.innerHTML = `
                <button onclick="handleSupport(${req.id}, 'reject')" style="background: white; color: #ef4444; border: 1px solid #ef4444; padding: 10px 20px; border-radius: 10px; font-weight: 700; cursor: pointer;">Reject Request</button>
                <button onclick="handleSupport(${req.id}, 'approve')" style="background: #10b981; color: white; border: none; padding: 10px 20px; border-radius: 10px; font-weight: 700; cursor: pointer;">Approve Support</button>
            `;
        } else {
            actions.innerHTML = `<button onclick="closeModal('support-modal')" style="background: #f1f5f9; color: #475569; border: none; padding: 10px 20px; border-radius: 10px; font-weight: 700; cursor: pointer;">Close</button>`;
        }
        
        modal.style.display = 'flex';
    }

    async function handleSupport(id, action) {
        if (action === 'reject') {
            const reason = prompt('Please enter the rejection reason:');
            if (reason === null) return;
            postAction('reject_support_request', { support_request_id: id, reject_reason: reason });
        } else {
            if (confirm('Are you sure you want to approve this support request?')) {
                postAction('approve_support_request', { support_request_id: id });
            }
        }
    }

    async function respondToAppointment(id, action) {
        if (action === 'reject') {
            const reason = prompt('Please enter the reason for declining:');
            if (reason === null) return;
            postAction('reject_aftercare_appointment', { appointment_id: id, reason: reason });
        } else {
            if (confirm('Are you sure you want to accept this appointment request?')) {
                postAction('accept_aftercare_appointment', { appointment_id: id });
            }
        }
    }

    function postAction(action, data) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = window.location.href;
        
        const actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        actionInput.value = action;
        form.appendChild(actionInput);
        
        for (const key in data) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = key;
            input.value = data[key];
            form.appendChild(input);
        }
        
        document.body.appendChild(form);
        form.submit();
    }

    function closeModal(id) {
        document.getElementById(id).style.display = 'none';
    }
</script>

<?php $this->view('hospital/inc/footer', $data); ?>
