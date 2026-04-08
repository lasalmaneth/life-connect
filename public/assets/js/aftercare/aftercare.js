// Aftercare Management JavaScript Functions
// This file contains all the JavaScript functionality for the Aftercare portal

function showContent(id) {
    document.querySelectorAll('.content-section').forEach(s => s.style.display = 'none');
    const target = document.getElementById(id);
    if (target) target.style.display = '';
    document.querySelectorAll('.menu-item').forEach(i => i.classList.remove('active'));
    const item = Array.from(document.querySelectorAll('.menu-item')).find(mi => mi.getAttribute('onclick')?.includes(id));
    if (item) item.classList.add('active');
}

// Appointment Functions
function openAppointmentModal() { document.getElementById('appointment-modal').classList.add('show'); }
function closeAppointmentModal() { document.getElementById('appointment-modal').classList.remove('show'); }
function saveAppointment() {
    const nic = document.getElementById('patient-nic').value;
    const name = document.getElementById('patient-name').value;
    const type = document.getElementById('appointment-type').value;
    const datetime = document.getElementById('appointment-datetime').value;
    const apptId = document.getElementById('appointment-id').value;
    const desc = document.getElementById('appointment-desc').value;

    if (!apptId || !nic || !name || !type || !datetime) {
        notify('Please fill all required fields', 'error');
        return;
    }

    // Add appointment to the table
    const table = document.querySelector('#my-appointments .table-content');
    const newRow = document.createElement('div');
    newRow.className = 'table-row';
    newRow.innerHTML = `
        <div class="table-cell name" data-label="Patient Details">NIC ${nic} - ${name}</div>
        <div class="table-cell" data-label="Appointment Type">${type.charAt(0).toUpperCase() + type.slice(1).replace('-', ' ')}</div>
        <div class="table-cell" data-label="Date & Time">${datetime.replace('T', ' ')}</div>
        <div class="table-cell" data-label="Status"><span class="status-badge status-active">Upcoming</span></div>
        <div class="table-cell" data-label="Actions">
            <button class="btn btn-secondary btn-small" onclick="editAppointment()">Edit</button>
            <button class="btn btn-danger btn-small" onclick="cancelAppointment()">Cancel</button>
        </div>
    `;
    table.appendChild(newRow);

    // Send notification to recipient patient
    sendRecipientNotification(nic, name, type, datetime);

    closeAppointmentModal();
    notify('Appointment scheduled and notification sent to recipient patient', 'success');
}

function sendRecipientNotification(nic, name, type, datetime) {
    // This would typically send to the hospital's notification system
    // For now, we'll add it to the notifications list
    const notificationsTable = document.querySelector('#notifications .table-content');
    const newNotification = document.createElement('div');
    newNotification.className = 'table-row';
    newNotification.innerHTML = `
        <div class="table-cell name" data-label="Recipient & Subject">NIC ${nic} - Appointment Scheduled</div>
        <div class="table-cell" data-label="Type">Appointment</div>
        <div class="table-cell" data-label="Status"><span class="status-badge status-active">Sent</span></div>
        <div class="table-cell" data-label="Sent Date">${new Date().toISOString().slice(0, 10)}</div>
        <div class="table-cell" data-label="Actions">
            <button class="btn btn-secondary btn-small" onclick="viewNotification()">View</button>
        </div>
    `;
    notificationsTable.appendChild(newNotification);
}
function editAppointment() { notify('Edit appointment functionality', 'warning'); }
function cancelAppointment() { notify('Appointment cancelled', 'error'); }
function exportAppointments() { 
    // Show export format modal
    const modal = document.createElement('div');
    modal.className = 'modal show';
    modal.id = 'export-modal';
    modal.innerHTML = `
        <div class="modal-content">
            <div class="modal-header">
                <h3>Export Appointments</h3>
                <button class="modal-close" onclick="document.getElementById('export-modal').remove()">×</button>
            </div>
            <div>
                <div class="form-group">
                    <label class="form-label">Select Export Format</label>
                    <select class="form-select" id="export-format">
                        <option value="">Choose format...</option>
                        <option value="xlsx">Excel (.xlsx) - For data analysis</option>
                        <option value="csv">CSV (.csv) - For database imports</option>
                        <option value="pdf">PDF (.pdf) - For printing & documentation</option>
                    </select>
                </div>
                <div class="form-group">
                    <p style="color: #666; font-size: 0.9rem; margin: 0;">
                        📊 This will export all appointment records including patient details, type, date/time, and status.
                    </p>
                </div>
                <button class="btn btn-primary" onclick="downloadExport()">Download Export</button>
            </div>
        </div>
    `;
    document.body.appendChild(modal);
}

function downloadExport() {
    const format = document.getElementById('export-format').value;
    
    if (!format) {
        notify('Please select an export format', 'error');
        return;
    }
    
    // Close modal
    const modal = document.getElementById('export-modal');
    if (modal) modal.remove();
    
    // Show loading message
    notify('Preparing export file...', 'info');
    
    // Create download link with format parameter
    const url = window.location.pathname + '?action=export_appointments&format=' + format;
    window.location.href = url;
    
    // Show success message after a delay
    setTimeout(() => {
        notify('Export file downloaded successfully!', 'success');
    }, 1000);
}

function downloadAppointmentPDF(appointmentId) {
    // Download individual appointment as PDF
    notify('Preparing appointment PDF...', 'info');
    
    // Create form data to send to backend
    const formData = new FormData();
    formData.append('action', 'download_appointment_pdf');
    formData.append('appointment_id', appointmentId);
    
    // Send request to backend
    fetch(window.location.href, {
        method: 'POST',
        body: formData
    })
    .then(response => response.blob())
    .then(blob => {
        // Create download link
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'appointment_' + appointmentId + '_' + Date.now() + '.pdf';
        document.body.appendChild(a);
        a.click();
        
        // Clean up
        window.URL.revokeObjectURL(url);
        document.body.removeChild(a);
        
        notify('Appointment PDF downloaded successfully!', 'success');
    })
    .catch(error => {
        console.error('Error downloading PDF:', error);
        notify('Error downloading PDF. Please try again.', 'error');
    });
}

// Support Request Functions
function approveSupport() { notify('Support request approved', 'success'); }
function rejectSupport() { notify('Support request rejected', 'error'); }
function bulkApproveSupport() { notify('Selected requests approved', 'success'); }
function bulkRejectSupport() { notify('Selected requests rejected', 'error'); }

// Feedback Functions
function markResolved() { notify('Feedback marked as resolved', 'success'); }
function submitFeedback() {
    const msg = document.getElementById('feedback-message').value;
    const type = document.getElementById('feedback-type').value;
    if (!msg || !type) { notify('Please complete the feedback form', 'error'); return; }
    const list = document.getElementById('my-feedback-list');
    const row = document.createElement('div');
    row.className = 'table-row';
    const dateStr = new Date().toISOString().slice(0, 10);
    row.innerHTML = `<div class="table-cell" data-label="Message">${msg}</div>
        <div class="table-cell" data-label="Type">${type.charAt(0).toUpperCase() + type.slice(1)}</div>
        <div class="table-cell" data-label="Date">${dateStr}</div>`;
    list.appendChild(row);
    notify('Feedback submitted', 'success');
    document.getElementById('feedback-message').value = '';
    document.getElementById('feedback-type').value = '';
}

// Notification Functions
function openNotificationModal() { document.getElementById('notification-modal').classList.add('show'); }
function closeNotificationModal() { document.getElementById('notification-modal').classList.remove('show'); }
function sendNotification() {
    const recipient = document.getElementById('recipient-type').value;
    const subject = document.getElementById('notification-subject').value;
    const message = document.getElementById('notification-message').value;

    if (!recipient || !subject || !message) {
        notify('Please fill all required fields', 'error');
        return;
    }

    closeNotificationModal();
    notify('Notification sent successfully', 'success');
}
function sendApprovalNotifications() { notify('Approval notifications sent', 'success'); }
function sendRejectionNotifications() { notify('Rejection notifications sent', 'error'); }
function sendReminderNotifications() { notify('Reminder notifications sent', 'success'); }
function viewNotification() { notify('Viewing notification details', 'success'); }

// Emergency Modal
function openEmergencyModal() {
    const n = document.createElement('div');
    n.className = 'modal show';
    n.id = 'emergency-modal';
    n.innerHTML = `
        <div class="modal-content">
            <div class="modal-header">
                <h3>Emergency Notification</h3>
                <button class="modal-close" onclick="document.getElementById('emergency-modal').remove()">×</button>
            </div>
            <div>
                <div class="form-group">
                    <label class="form-label">Subject</label>
                    <input class="form-input" id="emg-subject" placeholder="e.g., Severe symptoms">
                </div>
                <div class="form-group">
                    <label class="form-label">Message</label>
                    <textarea class="form-textarea" id="emg-message" placeholder="Describe the emergency..." ></textarea>
                </div>
                <button class="btn btn-danger" onclick="sendEmergency()">Send to Hospital</button>
            </div>
        </div>`;
    document.body.appendChild(n);
}
function sendEmergency() {
    const s = document.getElementById('emg-subject').value;
    const m = document.getElementById('emg-message').value;
    if (!s || !m) { notify('Please fill subject and message', 'error'); return; }
    notify('Emergency notification sent to hospital', 'success');
    const modal = document.getElementById('emergency-modal');
    if (modal) modal.remove();
}

// Show specific patient field when needed
document.addEventListener('DOMContentLoaded', function () {
    const recipientTypeSelect = document.getElementById('recipient-type');
    if (recipientTypeSelect) {
        recipientTypeSelect.addEventListener('change', function () {
            const specificGroup = document.getElementById('specific-patient-group');
            if (this.value === 'specific') {
                specificGroup.style.display = 'block';
            } else {
                specificGroup.style.display = 'none';
            }
        });
    }
});

function notify(message, type) {
    const n = document.createElement('div');
    n.className = `notification ${type}`;
    n.textContent = message;
    document.body.appendChild(n);
    requestAnimationFrame(() => n.classList.add('show'));
    setTimeout(() => { n.classList.remove('show'); n.remove(); }, 2500);
}

// Single-patient view (no role categories). Current user appointments only.
let currentRole = 'patient';
function setRole() { renderCalendar(); }

// Simple Calendar State and Demo Data
const demoAppointments = [
    { nic: '2001XXXXXXX', name: 'You', datetime: '2025-11-05T09:00:00', type: 'Monthly Checkup' },
    { nic: '2001XXXXXXX', name: 'You', datetime: '2025-12-10T10:30:00', type: 'Annual Review' }
];
let currentDate = new Date();

function renderCalendar() {
    const monthLabel = document.getElementById('calendar-month');
    const grid = document.getElementById('calendar-grid');
    if (!monthLabel || !grid) return;

    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();
    const firstDay = new Date(year, month, 1);
    const lastDay = new Date(year, month + 1, 0);
    const startWeekday = firstDay.getDay();
    const daysInMonth = lastDay.getDate();

    monthLabel.textContent = firstDay.toLocaleString('en-US', { month: 'long', year: 'numeric' });
    grid.innerHTML = '';

    // Fill leading blanks
    for (let i = 0; i < startWeekday; i++) {
        const blank = document.createElement('div');
        blank.style.minHeight = '64px';
        grid.appendChild(blank);
    }

    // Build a map of dates with appointments
    const apptByDate = new Map();
    demoAppointments.forEach(a => {
        const d = new Date(a.datetime);
        if (d.getMonth() === month && d.getFullYear() === year) {
            const key = d.getDate();
            if (!apptByDate.has(key)) apptByDate.set(key, []);
            apptByDate.get(key).push(a);
        }
    });

    for (let day = 1; day <= daysInMonth; day++) {
        const cell = document.createElement('div');
        cell.style.border = '1px solid rgba(0, 91, 170, 0.1)';
        cell.style.borderRadius = '8px';
        cell.style.padding = '6px';
        cell.style.minHeight = '64px';
        cell.style.textAlign = 'left';
        cell.style.fontSize = '0.85rem';

        const head = document.createElement('div');
        head.textContent = day;
        head.style.fontWeight = '700';
        head.style.marginBottom = '4px';
        head.style.color = 'var(--primary-text-color)';
        cell.appendChild(head);

        if (apptByDate.has(day)) {
            apptByDate.get(day).forEach(ap => {
                const tag = document.createElement('div');
                tag.textContent = new Date(ap.datetime).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) + ' - ' + ap.name;
                tag.style.background = 'var(--gray-bg-color)';
                tag.style.borderRadius = '6px';
                tag.style.padding = '3px 6px';
                tag.style.marginTop = '2px';
                tag.style.color = 'var(--secondary-text-color)';
                tag.style.whiteSpace = 'nowrap';
                tag.style.overflow = 'hidden';
                tag.style.textOverflow = 'ellipsis';
                cell.appendChild(tag);
            });
        }

        grid.appendChild(cell);
    }

    // Upcoming list
    const list = document.getElementById('upcoming-list');
    if (list) {
        list.innerHTML = '';
        const now = new Date();
        const upcoming = demoAppointments
            .filter(a => new Date(a.datetime) >= now)
            .sort((a, b) => new Date(a.datetime) - new Date(b.datetime))
            .slice(0, 6);
        upcoming.forEach(a => {
            const li = document.createElement('li');
            li.style.display = 'flex';
            li.style.justifyContent = 'space-between';
            li.style.alignItems = 'center';
            li.style.border = '1px solid rgba(0, 91, 170, 0.1)';
            li.style.borderRadius = '8px';
            li.style.padding = '8px 10px';
            li.innerHTML = `<div style="font-weight:600;color:var(--primary-text-color)">${a.name}</div>
                <div style="color:var(--secondary-text-color);font-size:0.85rem;">${new Date(a.datetime).toLocaleString([], { month: 'short', day: '2-digit', hour: '2-digit', minute: '2-digit' })}</div>`;
            list.appendChild(li);
        });
    }
}

function prevMonth() {
    currentDate = new Date(currentDate.getFullYear(), currentDate.getMonth() - 1, 1);
    renderCalendar();
}
function nextMonth() {
    currentDate = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 1);
    renderCalendar();
}

// Initialize
function initializeAftercare() {
    showContent('overview');
    renderCalendar();
    setRole();
}

// Auto-initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', initializeAftercare);
