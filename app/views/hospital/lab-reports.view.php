<?php
$current_page = 'lab-reports';
require_once __DIR__ . '/header.php';
?>

<style>
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

    .modal-close:hover {
        color: #000;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #333;
        font-size: 14px;
    }

    .form-input,
    .form-select,
    .form-textarea {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
        font-family: inherit;
    }

    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
        outline: none;
        border-color: #005baa;
        box-shadow: 0 0 0 3px rgba(0, 91, 170, 0.1);
    }

    .form-textarea {
        resize: vertical;
        min-height: 80px;
    }

    .status-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-success {
        background-color: #d4edda;
        color: #155724;
    }

    .status-danger {
        background-color: #f8d7da;
        color: #721c24;
    }

    .status-pending {
        background-color: #fff3cd;
        color: #856404;
    }
</style>

<div class="container">
    <div class="main-content">
        <?php require_once __DIR__ . '/sidebar.php'; ?>

        <div class="content-area" id="content-area">
    <div class="content-section" style="display: block;">
        <div class="content-header">
            <h2>Scheduled Appointments</h2>
            <p>View and manage upcoming donor appointments scheduled for your hospital.</p>
        </div>
        <div class="content-body">
            <div class="action-section">
                <h3>Appointment Actions</h3>
                <div class="action-buttons">
                    <button class="btn btn-primary" onclick="openScheduleAppointmentModal()">Schedule Appointment</button>
                </div>
            </div>

            <div class="search-bar">
                <span class="search-icon">🔍</span>
                <input type="text" class="search-input" placeholder="Search by donor NIC, name, test type, status, or notes...">
            </div>

            <div class="data-table">
                <div class="table-header">
                    <h4>Scheduled Appointments</h4>
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
                <input type="text" class="form-input" id="donor-search" placeholder="Enter donor NIC or name...">
                <div id="donor-search-results" style="max-height: 200px; overflow-y: auto; border: 1px solid #ddd; border-radius: 4px; margin-top: 5px; display: none;"></div>
                <input type="hidden" id="selected-donor-id" value="">
                <div id="selected-donor-info" style="margin-top: 10px; padding: 10px; background: #f0f7ff; border-radius: 4px; display: none;">
                    <strong>Selected Donor:</strong> <span id="selected-donor-name"></span>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Test Type *</label>
                <input type="text" class="form-input" id="test-type" placeholder="e.g., Blood Test, CT Scan">
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
                <textarea class="form-textarea" id="notes" placeholder="Optional notes about the appointment..."></textarea>
            </div>

            <button class="btn btn-primary" onclick="scheduleAppointment()">Schedule Appointment</button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        loadScheduledAppointments();

        const searchInput = document.querySelector('.search-input');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                searchScheduledAppointments(this.value.trim());
            });
        }

        const donorSearchInput = document.getElementById('donor-search');
        if (donorSearchInput) {
            donorSearchInput.addEventListener('input', function() {
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
            html += `
                <div style="padding: 10px; border-bottom: 1px solid #eee; cursor: pointer;"
                     onclick="selectDonor('${donor.nic_number}', '${donor.first_name} ${donor.last_name}', ${donor.id})">
                    <strong>${donor.first_name} ${donor.last_name}</strong><br>
                    <small style="color: #666;">NIC: ${donor.nic_number}</small>
                </div>
            `;
        });

        resultsContainer.innerHTML = html;
        resultsContainer.style.display = 'block';
    }

    function selectDonor(nic, name, donorId) {
        document.getElementById('donor-search').value = name + ' (' + nic + ')';
        document.getElementById('selected-donor-id').value = donorId;
        document.getElementById('selected-donor-name').textContent = name + ' - ' + nic;
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
            alert('Please fill in all required fields');
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
    }

    function statusBadgeClass(status) {
        const s = String(status || '').toLowerCase();
        if (s.includes('approved')) return 'status-success';
        if (s.includes('rejected')) return 'status-danger';
        return 'status-pending';
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
            const dateText = apt.test_date ? new Date(apt.test_date).toLocaleDateString() : '';
            row.innerHTML = `
                <div class="table-cell" data-label="Appointment ID">${apt.appointment_id ?? ''}</div>
                <div class="table-cell name" data-label="Donor NIC">${apt.donor_nic ?? ''}</div>
                <div class="table-cell" data-label="Donor Name">${apt.donor_name ?? ''}</div>
                <div class="table-cell" data-label="Test Type">${apt.test_type ?? ''}</div>
                <div class="table-cell" data-label="Scheduled Date">${dateText}</div>
                <div class="table-cell" data-label="Status">
                    <span class="status-badge ${statusBadgeClass(apt.status)}">${apt.status ?? ''}</span>
                </div>
                <div class="table-cell" data-label="Notes">${apt.notes ?? ''}</div>
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

    function deleteScheduledAppointment(appointmentId) {
        if (!confirm('Are you sure you want to delete this appointment?')) return;

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
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        loadScheduledAppointments();

        const searchInput = document.querySelector('.search-input');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                searchScheduledAppointments(this.value.trim());
            });
        }

        const donorSearchInput = document.getElementById('donor-search');
        if (donorSearchInput) {
            donorSearchInput.addEventListener('input', function() {
                searchDonors(this.value.trim());
            });
        }
    });

