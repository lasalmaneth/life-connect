<?php
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo ROOT ?? '/life-connect'; ?>/public/assets/css/hospital/hospital.css">
    <title>Schedule Appointment - Hospital Management - LifeConnect</title>
    <style>
        /* Only keep modal styles as they are specific to this page for now */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background-color: white;
            padding: 30px;
            border: 1px solid #888;
            border-radius: 8px;
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
        }

        .modal-header h3 {
            margin: 0;
            font-size: 22px;
            color: #333;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 28px;
            cursor: pointer;
            color: #999;
        }
    </style>
</head>

<body>
    <?php
    $current_page = 'upcoming-appointments';
    require_once __DIR__ . '/header.php';
    ?>

    <div class="container">
        <div class="main-content">
            <?php
            require_once __DIR__ . '/sidebar.php';
            ?>

            <div class="content-area" id="content-area">
                <div class="content-section" style="display: block;">
                    <div class="content-header">
                        <h2>Schedule Appointment</h2>
                        <p>View and manage upcoming donor appointments scheduled for your hospital.</p>
                    </div>
                    <div class="content-body">
                        <div class="action-section">
                            <h3>Appointment Actions</h3>
                            <div class="action-buttons">
                                <button class="btn btn-primary" onclick="openScheduleAppointmentModal()">Schedule
                                    Appointment</button>
                            </div>
                        </div>

                        <div class="search-bar">
                            <span class="search-icon">Search:</span>
                            <input type="text" class="search-input"
                                placeholder="Search by donor NIC, name, test type, status, or notes...">
                        </div>

                        <div class="data-table">
                            <div class="table-header">
                                <h4>Schedule Appointment</h4>
                            </div>
                            <div class="table-content" id="scheduled-appointments-table">
                                <div class="table-row" style="font-weight: 600; background: var(--gray-bg-color);">
                                    <div class="table-cell">Appointment ID</div>
                                    <div class="table-cell">Donor NIC</div>
                                    <div class="table-cell">Donor Name</div>
                                    <div class="table-cell">Test Type</div>
                                    <div class="table-cell">Scheduled Date</div>
                                    <div class="table-cell">Status</div>
                                    <div class="table-cell">Notes</div>
                                    <div class="table-cell">Actions</div>
                                </div>
                            </div>
                        </div>

                        <div class="data-table" style="margin-top: 24px;">
                            <div class="table-header">
                                <h4>Aftercare Appointments</h4>
                            </div>
                            <div class="table-content" id="aftercare-requests-table">
                                <div class="table-row" style="font-weight: 600; background: var(--gray-bg-color);">
                                    <div class="table-cell">Appointment ID</div>
                                    <div class="table-cell">Patient NIC</div>
                                    <div class="table-cell">Patient Name</div>
                                    <div class="table-cell">Requested Date</div>
                                    <div class="table-cell">Type</div>
                                    <div class="table-cell">Reason</div>
                                    <div class="table-cell">Status</div>
                                    <div class="table-cell">Actions</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Schedule Appointment Modal -->
            <div class="modal" id="schedule-appointment-modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3>Schedule Appointment</h3>
                        <button class="modal-close" onclick="closeScheduleAppointmentModal()">×</button>
                    </div>
                    <div>
                        <div class="form-group">
                            <label class="form-label">Search Donor *</label>
                            <input type="text" class="form-input" id="donor-search"
                                placeholder="Enter donor NIC or name...">
                            <div id="donor-search-results"
                                style="max-height: 200px; overflow-y: auto; border: 1px solid #ddd; border-radius: 4px; margin-top: 5px; display: none;">
                            </div>
                            <input type="hidden" id="selected-donor-id" value="">
                            <div id="selected-donor-info"
                                style="margin-top: 10px; padding: 10px; background: #f0f7ff; border-radius: 4px; display: none;">
                                <strong>Selected Donor:</strong> <span id="selected-donor-name"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Test Type *</label>
                            <input type="text" class="form-input" id="test-type"
                                placeholder="e.g., Blood Test, CT Scan">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Scheduled Date *</label>
                            <input type="date" class="form-input" id="test-date">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Status *</label>
                            <select class="form-select" id="status">
                                <option value="">Select Status</option>
                                <option value="Pending">Pending</option>
                                <option value="Approved">Approved</option>
                                <option value="Rejected">Rejected</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Notes</label>
                            <textarea class="form-textarea" id="notes"
                                placeholder="Optional notes about the appointment..."></textarea>
                        </div>

                        <button class="btn btn-primary" onclick="scheduleAppointment()">Schedule Appointment</button>
                    </div>
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    loadScheduledAppointments();

                    const searchInput = document.querySelector('.search-input');
                    if (searchInput) {
                        searchInput.addEventListener('input', function () {
                            searchScheduledAppointments(this.value.trim());
                        });
                    }

                    const donorSearchInput = document.getElementById('donor-search');
                    if (donorSearchInput) {
                        donorSearchInput.addEventListener('input', function () {
                            searchDonors(this.value.trim());
                        });
                    }
                });

                function openScheduleAppointmentModal() {
                    const modal = document.getElementById('schedule-appointment-modal');
                    if (modal) {
                        modal.classList.add('show');
                        resetScheduleAppointmentForm();
                    }
                }

                function closeScheduleAppointmentModal() {
                    const modal = document.getElementById('schedule-appointment-modal');
                    if (modal) {
                        modal.classList.remove('show');
                        resetScheduleAppointmentForm();
                    }
                }

                function resetScheduleAppointmentForm() {
                    document.getElementById('donor-search').value = '';
                    document.getElementById('selected-donor-id').value = '';
                    document.getElementById('test-type').value = '';
                    document.getElementById('test-date').value = '';
                    document.getElementById('status').value = '';
                    document.getElementById('notes').value = '';
                    document.getElementById('selected-donor-info').style.display = 'none';
                    document.getElementById('donor-search-results').style.display = 'none';
                }

                function searchDonors(query) {
                    const results = document.getElementById('donor-search-results');
                    if (!results) return;

                    if (query.length < 2) {
                        results.style.display = 'none';
                        return;
                    }

                    fetch('<?php echo ROOT; ?>/hospital/search-donors?q=' + encodeURIComponent(query), {
                        method: 'GET',
                        credentials: 'include'
                    })
                        .then(response => response.json())
                        .then(data => {
                            displayDonorSearchResults(Array.isArray(data) ? data : []);
                        })
                        .catch(error => {
                            console.error('Error searching donors:', error);
                            displayDonorSearchResults([]);
                        });
                }

                function displayDonorSearchResults(donors) {
                    const resultsContainer = document.getElementById('donor-search-results');
                    if (!resultsContainer) return;

                    if (!donors || donors.length === 0) {
                        resultsContainer.innerHTML = '<div style="padding: 10px; color: #999;">No donors found</div>';
                        resultsContainer.style.display = 'block';
                        return;
                    }

                    let html = '';
                    donors.forEach(donor => {
                        const donorId = Number(donor.id || 0);
                        const donorCode = donorId > 0 ? ('D_' + String(donorId).padStart(5, '0')) : '';
                        const nic = String(donor.nic_number || '');
                        const name = `${donor.first_name || ''} ${donor.last_name || ''}`.trim();

                        html += `
                <div style="padding: 10px; border-bottom: 1px solid #eee; cursor: pointer;"
                     onclick="selectDonor('${nic}', '${name.replace(/'/g, "\\'")}', ${donorId})">
                    <strong>${name || '—'}</strong><br>
                    <small style="color: #666;">${donorCode ? ('ID: ' + donorCode + ' · ') : ''}NIC: ${nic || '—'}</small>
                </div>
            `;
                    });

                    resultsContainer.innerHTML = html;
                    resultsContainer.style.display = 'block';
                }

                function selectDonor(nic, name, donorId) {
                    const code = donorId ? ('D_' + String(donorId).padStart(5, '0')) : '';
                    document.getElementById('donor-search').value = name + (nic ? (' (' + nic + ')') : '');
                    document.getElementById('selected-donor-id').value = donorId;
                    document.getElementById('selected-donor-name').textContent = (code ? (code + ' · ') : '') + name + (nic ? (' · ' + nic) : '');
                    document.getElementById('selected-donor-info').style.display = 'block';
                    document.getElementById('donor-search-results').style.display = 'none';
                }

                function scheduleAppointment() {
                    const donorId = document.getElementById('selected-donor-id').value;
                    const testType = document.getElementById('test-type').value.trim();
                    const testDate = document.getElementById('test-date').value;
                    const status = document.getElementById('status').value;
                    const notes = document.getElementById('notes').value;

                    if (!donorId || !testType || !testDate || !status) {
                        hcAlert('Please fill in all required fields', 'error');
                        return;
                    }

                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.style.display = 'none';

                    const fields = {
                        action: 'schedule_appointment',
                        donor_id: donorId,
                        test_type: testType,
                        test_date: testDate,
                        status: status,
                        notes: notes
                    };

                    for (const [key, value] of Object.entries(fields)) {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = key;
                        input.value = value;
                        form.appendChild(input);
                    }

                    document.body.appendChild(form);
                    form.submit();
                }

                function loadScheduledAppointments() {
                    const appointments = <?php echo json_encode($scheduled_appointments ?? []); ?>;
                    window.allScheduledAppointments = appointments || [];
                    console.log('Loaded scheduled appointments:', window.allScheduledAppointments);
                    updateScheduledAppointmentsTable(window.allScheduledAppointments);

                    const aftercare = <?php echo json_encode($aftercare_appointments ?? []); ?>;
                    window.aftercareRequests = aftercare || [];
                    updateAftercareRequestsTable(window.aftercareRequests);
                }

                function updateAftercareRequestsTable(items) {
                    const tableContent = document.querySelector('#aftercare-requests-table');
                    if (!tableContent) return;

                    const existingRows = tableContent.querySelectorAll('.table-row:not(:first-child)');
                    existingRows.forEach(row => row.remove());

                    if (!items || items.length === 0) {
                        const emptyRow = document.createElement('div');
                        emptyRow.className = 'table-row';
                        emptyRow.innerHTML = '<div style="text-align: center; padding: 20px; color: #999; grid-column: 1/-1;">No aftercare appointment requests.</div>';
                        tableContent.appendChild(emptyRow);
                        return;
                    }

                    items.forEach(apt => {
                        const row = document.createElement('div');
                        row.className = 'table-row';
                        const dateText = apt.appointment_date ? new Date(apt.appointment_date).toLocaleString('en-GB') : '';
                        const status = apt.status || '';
                        const isRequested = String(status).toLowerCase() === 'requested';
                        const statusClass = isRequested ? 'status-pending' : (String(status).toLowerCase() === 'scheduled' ? 'status-success' : 'status-danger');
                        const reason = apt.rejection_reason ? String(apt.rejection_reason) : '';
                        const desc = apt.description ? String(apt.description) : '';
                        const reasonHtml = (String(status).toLowerCase() === 'cancelled' && reason) ? (desc ? (desc + ' — ') : '') + ('Rejected: ' + reason) : desc;
                        row.innerHTML = `
                            <div class="table-cell" data-label="Appointment ID">${apt.appointment_id ?? ''}</div>
                            <div class="table-cell name" data-label="Patient NIC">${apt.patient_id ?? ''}</div>
                            <div class="table-cell" data-label="Patient Name">${apt.patient_name ?? ''}</div>
                            <div class="table-cell" data-label="Requested Date">${dateText}</div>
                            <div class="table-cell" data-label="Type">${apt.appointment_type ?? ''}</div>
                            <div class="table-cell" data-label="Reason">${reasonHtml || ''}</div>
                            <div class="table-cell" data-label="Status"><span class="status-badge ${statusClass}">${status}</span></div>
                            <div class="table-cell" data-label="Actions">
                                ${isRequested ? `
                                    <div style="display:flex; flex-direction:column; gap:8px;">
                                        <button class="btn btn-success btn-small" onclick="acceptAftercareRequest(${apt.appointment_id})">Accept</button>
                                        <button class="btn btn-danger btn-small" onclick="rejectAftercareRequest(${apt.appointment_id})">Reject</button>
                                    </div>
                                ` : '<span style="align-self:center; color:#64748b; font-weight:600;">No actions</span>'}
                            </div>
                        `;
                        tableContent.appendChild(row);
                    });
                }

                function postAftercareAction(action, appointmentId, extra = {}) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.style.display = 'none';

                    const fields = Object.assign({
                        action,
                        appointment_id: String(appointmentId || '')
                    }, extra);

                    Object.entries(fields).forEach(([k, v]) => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = k;
                        input.value = String(v ?? '');
                        form.appendChild(input);
                    });

                    document.body.appendChild(form);
                    form.submit();
                }

                async function acceptAftercareRequest(appointmentId) {
                    const ok = await hcConfirm('Accept this aftercare appointment request?');
                    if (!ok) return;
                    postAftercareAction('accept_aftercare_appointment', appointmentId);
                }

                async function rejectAftercareRequest(appointmentId) {
                    const ok = await hcConfirm('Reject this aftercare appointment request?', { danger: true });
                    if (!ok) return;
                    const reason = await hcPrompt('Enter rejection reason (required):', { required: true });
                    if (!reason) return;
                    postAftercareAction('reject_aftercare_appointment', appointmentId, { reason });
                }

                function statusBadgeClass(status) {
                    const s = String(status || '').toLowerCase();
                    if (s.includes('approved')) return 'status-success';
                    if (s.includes('rejected')) return 'status-danger';
                    return 'status-pending';
                }

                function parseRescheduleInfo(notes) {
                    const text = String(notes || '');
                    if (!text) return null;

                    // Example line:
                    // [Reschedule Request] Proposed date: 2026-04-30 | Reason: ... | Requested at: 2026-04-12 20:13
                    const re = /\[Reschedule Request\]\s*Proposed date:\s*(\d{4}-\d{2}-\d{2})\s*\|\s*Reason:\s*([^|\n]+?)\s*\|\s*Requested at:\s*([^\n]+)/gi;
                    let match;
                    let last = null;
                    while ((match = re.exec(text)) !== null) {
                        last = {
                            proposedDate: match[1],
                            reason: (match[2] || '').trim(),
                            requestedAt: (match[3] || '').trim(),
                        };
                    }
                    return last;
                }

                function updateScheduledAppointmentsTable(appointments) {
                    const tableContent = document.querySelector('#scheduled-appointments-table');
                    if (!tableContent) return;

                    const existingRows = tableContent.querySelectorAll('.table-row:not(:first-child)');
                    existingRows.forEach(row => row.remove());

                    if (!appointments || appointments.length === 0) {
                        const emptyRow = document.createElement('div');
                        emptyRow.className = 'table-row';
                        emptyRow.innerHTML = '<div style="text-align: center; padding: 20px; color: #999; grid-column: 1/-1;">No scheduled appointments found</div>';
                        tableContent.appendChild(emptyRow);
                        return;
                    }

                    appointments.forEach(apt => {
                        const row = document.createElement('div');
                        row.className = 'table-row';
                        const dateText = apt.test_date ? new Date(apt.test_date).toLocaleDateString('en-GB') : '';
                        const res = parseRescheduleInfo(apt.notes);
                        const resHtml = res ? `<div style="margin-top:4px; font-size:12px; color:#0b4a86; font-weight:600;">Requested: ${res.proposedDate}</div>` : '';
                        const notesHtml = res
                            ? `<div style="white-space:pre-wrap;">${apt.notes ?? ''}</div>`
                            : `${apt.notes ?? ''}`;
                        row.innerHTML = `
                <div class="table-cell" data-label="Appointment ID">${apt.appointment_id ?? ''}</div>
                <div class="table-cell name" data-label="Donor NIC">${apt.donor_nic ?? ''}</div>
                <div class="table-cell" data-label="Donor Name">${apt.donor_name ?? ''}</div>
                <div class="table-cell" data-label="Test Type">${apt.test_type ?? ''}</div>
                <div class="table-cell" data-label="Scheduled Date">${dateText}${resHtml}</div>
                <div class="table-cell" data-label="Status">
                    <span class="status-badge ${statusBadgeClass(apt.status)}">${apt.status ?? ''}</span>
                </div>
                <div class="table-cell" data-label="Notes">${notesHtml}</div>
                <div class="table-cell" data-label="Actions">
                    <button class="btn btn-danger btn-small" onclick="deleteScheduledAppointment(${apt.appointment_id})" style="white-space: nowrap;">Delete</button>
                </div>
            `;
                        tableContent.appendChild(row);
                    });
                }

                function searchScheduledAppointments(query) {
                    if (!window.allScheduledAppointments) return;

                    if (query === '') {
                        updateScheduledAppointmentsTable(window.allScheduledAppointments);
                        return;
                    }

                    const searchQuery = query.toLowerCase();
                    const filtered = window.allScheduledAppointments.filter(apt => {
                        const nic = String(apt.donor_nic || '').toLowerCase();
                        const name = String(apt.donor_name || '').toLowerCase();
                        const testType = String(apt.test_type || '').toLowerCase();
                        const status = String(apt.status || '').toLowerCase();
                        const notes = String(apt.notes || '').toLowerCase();
                        return nic.includes(searchQuery) || name.includes(searchQuery) || testType.includes(searchQuery) || status.includes(searchQuery) || notes.includes(searchQuery);
                    });

                    updateScheduledAppointmentsTable(filtered);
                }

                async function deleteScheduledAppointment(appointmentId) {
                    const ok = await hcConfirm('Are you sure you want to delete this appointment?', { danger: true });
                    if (!ok) return;

                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.style.display = 'none';

                    const actionInput = document.createElement('input');
                    actionInput.type = 'hidden';
                    actionInput.name = 'action';
                    actionInput.value = 'delete_scheduled_appointment';
                    form.appendChild(actionInput);

                    const idInput = document.createElement('input');
                    idInput.type = 'hidden';
                    idInput.name = 'appointment_id';
                    idInput.value = appointmentId;
                    form.appendChild(idInput);

                    document.body.appendChild(form);
                    form.submit();
                }
            </script>
        </div>
</body>

</html>