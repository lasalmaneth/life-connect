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
    <link rel="stylesheet" href="<?= ROOT ?>/public/assets/css/donor/donor.css">
    <link rel="stylesheet" href="<?= ROOT ?>/public/assets/css/aftercare/aftercare.css">
</head>
<body>

<header class="d-header">
  <div class="d-header__inner">
    <div class="logo">
      <a href="<?= ROOT ?>" style="text-decoration:none; display:flex; align-items:center; gap:10px;">
          <img src="<?= ROOT ?>/public/assets/images/logo.png" alt="LifeConnect" style="height:40px;">
          <div>
            <strong style="display:block; font-size:1.1rem; color:var(--blue-700); line-height:1.2;">LifeConnect</strong>
            <p style="margin:0; font-size:.68rem; color:var(--g500);">Aftercare Portal</p>
          </div>
      </a>
    </div>
    <div class="d-header__right">
      <div class="d-user-chip">
        <div class="d-user-avatar"><?= strtoupper(substr($patientName, 0, 1)) ?></div>
        <div>
          <div class="d-user-chip__name"><?= htmlspecialchars($patientName) ?></div>
          <div class="d-user-chip__badge" style="background:var(--blue-100); color:var(--blue-700);">RECIPIENT</div>
        </div>
      </div>
      <a class="d-btn d-btn--sm d-btn--secondary" href="<?= ROOT ?>/aftercare/logout" style="margin-left: 12px; text-decoration:none;">Logout</a>
    </div>
  </div>
</header>

<main class="d-content" style="margin-left: 0;">
    <div class="d-content__header">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <h2>Aftercare Support</h2>
                <p>Track your follow-up appointments and review support requests.</p>
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

            <div class="d-widget">
                <div class="d-widget__header" style="display: flex; justify-content: space-between; align-items: center;">
                    <div class="d-widget__title">My Appointments</div>
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
                            <tbody>
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

            <div class="d-widget">
                <div class="d-widget__header" style="display: flex; justify-content: space-between; align-items: center;">
                    <div class="d-widget__title">Support Requests</div>
                    <button class="d-btn d-btn--sm d-btn--primary" onclick="openSupportRequestModal()">New Request</button>
                </div>

                <div class="d-widget__body" style="padding: 0;">
                    <div class="d-table-wrap" style="border: none; border-radius: 0 0 var(--r) var(--r);">
                        <table class="d-table">
                            <thead>
                                <tr>
                                    <th>Date Submitted</th>
                                    <th>Reason</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($support_requests)): foreach ($support_requests as $req): ?>
                                    <tr>
                                        <td><?= date('M d, Y', strtotime($req->created_at ?? $req->submitted_date ?? 'now')) ?></td>
                                        <td style="font-weight: 500; color: var(--blue-800);"><?= htmlspecialchars($req->reason) ?></td>
                                        <td style="color: var(--g500);"><?= htmlspecialchars($req->description ?: 'N/A') ?></td>
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
                                    </tr>
                                <?php endforeach; else: ?>
                                    <tr>
                                        <td colspan="4" style="text-align: center; padding: 2rem; color: var(--g500); font-style: italic;">
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
</main>

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
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Unable to book appointment'));
        }
    })
    .catch(() => alert('An error occurred while booking the appointment'));
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

    if (!reason || !hospitalRegistrationNo) {
        alert('Please fill in all required fields');
        return;
    }

    const formData = new FormData();
    formData.append('reason', reason);
    formData.append('description', description);
    formData.append('hospital_registration_no', hospitalRegistrationNo);

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


