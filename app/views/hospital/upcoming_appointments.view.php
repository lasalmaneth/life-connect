<?php
/**
 * Hospital Portal — Upcoming Appointments Page
 * View and manage all donor and patient appointments efficiently
 */

// Process appointment data for calendar highlighting
$appointmentsByDate = [];
$scheduledCount = 0;
$requestedCount = 0;

// Process scheduled appointments
if (!empty($scheduled_appointments)) {
    foreach ($scheduled_appointments as $apt) {
        $date = $apt->test_date ?? $apt->appointment_date ?? null;
        if ($date) {
            $dateOnly = date('Y-m-d', strtotime($date));
            if (!isset($appointmentsByDate[$dateOnly])) {
                $appointmentsByDate[$dateOnly] = [];
            }
            $appointmentsByDate[$dateOnly][] = ['type' => 'scheduled', 'data' => $apt];
            $scheduledCount++;
        }
    }
}

// Process aftercare appointments
if (!empty($aftercare_appointments)) {
    foreach ($aftercare_appointments as $apt) {
        $date = $apt->requested_date ?? $apt->appointment_date ?? null;
        if ($date) {
            $dateOnly = date('Y-m-d', strtotime($date));
            if (!isset($appointmentsByDate[$dateOnly])) {
                $appointmentsByDate[$dateOnly] = [];
            }
            $appointmentsByDate[$dateOnly][] = ['type' => 'requested', 'data' => $apt];
            $requestedCount++;
        }
    }
}

// Prepare JSON for JavaScript
$appointmentDatesJson = json_encode(array_keys($appointmentsByDate));
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="<?php echo ROOT ?? '/life-connect'; ?>/public/assets/css/hospital/hospital.css">
    <title>Upcoming Appointments - Hospital Portal | LifeConnect</title>
    <style>
        :root {
            --primary-color: #0284c7;
            --secondary-color: #f59e0b;
            --danger-color: #dc2626;
            --success-color: #16a34a;
            --light-bg: #f9fafb;
        }

        body {
            background-color: var(--light-bg);
        }

        .appointments-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        /* Header Section */
        .appointments-header {
            margin-bottom: 2rem;
        }

        .appointments-header h1 {
            font-size: 2rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }

        .appointments-header p {
            font-size: 1rem;
            color: #6b7280;
        }

        /* Calendar Section */
        .calendar-section {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .calendar-header {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-direction: column;
            gap: 1rem;
        }

        .calendar-header h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1f2937;
            margin: 0;
        }

        .calendar-nav {
            display: flex;
            gap: 0.5rem;
            align-items: center;
            justify-content: center;
        }

        .calendar-nav button {
            background: white;
            border: 1px solid #e5e7eb;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.2s;
        }

        .calendar-nav button:hover {
            background: var(--light-bg);
            border-color: var(--primary-color);
            color: var(--primary-color);
        }

        .calendar-month {
            font-weight: 600;
            color: #1f2937;
            min-width: 200px;
            text-align: center;
        }

        /* Calendar Grid */
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 0.5rem;
            margin-bottom: 1.5rem;
            max-width: 100%;
            margin-left: auto;
            margin-right: auto;
        }

        .calendar-day-header {
            text-align: center;
            font-weight: 600;
            color: #6b7280;
            padding: 0.75rem 0;
            font-size: 0.875rem;
        }

        .calendar-day {
            aspect-ratio: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            color: #6b7280;
            transition: all 0.2s;
            position: relative;
            background: white;
        }

        .calendar-day:hover {
            border-color: var(--primary-color);
            background: #f0f9ff;
        }

        .calendar-day.other-month {
            color: #d1d5db;
            background: #f9fafb;
        }

        .calendar-day.today {
            background: #e0f2fe;
            border-color: var(--primary-color);
            color: var(--primary-color);
            font-weight: 700;
        }

        .calendar-day.selected {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
            font-weight: 700;
        }

        /* Appointment indicators on calendar */
        .calendar-day.has-scheduled::after,
        .calendar-day.has-request::after,
        .calendar-day.has-reschedule::after {
            content: '';
            position: absolute;
            bottom: 2px;
            width: 6px;
            height: 6px;
            border-radius: 50%;
        }

        .calendar-day.has-scheduled::after {
            background: var(--primary-color);
        }

        .calendar-day.has-request::after {
            background: var(--secondary-color);
            left: 2px;
        }

        .calendar-day.has-reschedule::after {
            background: var(--danger-color);
            right: 2px;
        }

        /* Donor Selection */
        .donor-selection {
            margin-bottom: 1.5rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .donor-selection label {
            display: block;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }

        .donor-selection input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 0.95rem;
        }

        .donor-selection input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.1);
        }

        /* Action Cards */
        .action-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
            max-width: 1400px;
            margin-left: auto;
            margin-right: auto;
            padding: 0 1rem;
        }

        .action-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 2px solid transparent;
        }

        .action-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            border-color: var(--primary-color);
        }

        .action-card.active {
            background: #f0f9ff;
            border-color: var(--primary-color);
        }

        .action-card-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: var(--primary-color);
        }

        .action-card.aftercare .action-card-icon {
            color: var(--secondary-color);
        }

        .action-card.reschedule .action-card-icon {
            color: var(--danger-color);
        }

        .action-card-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: #1f2937;
            margin: 0;
        }

        /* Content Section */
        .content-section {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            max-width: 1400px;
            margin-left: auto;
            margin-right: auto;
        }

        .selected-date {
            font-size: 1.1rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #e5e7eb;
        }

        /* Tabs */
        .tabs-container {
            display: flex;
            gap: 0;
            border-bottom: 2px solid #e5e7eb;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }

        .tab {
            padding: 1rem 1.5rem;
            border: none;
            background: none;
            cursor: pointer;
            font-size: 0.95rem;
            font-weight: 500;
            color: #6b7280;
            border-bottom: 3px solid transparent;
            transition: all 0.2s;
            position: relative;
        }

        .tab:hover {
            color: var(--primary-color);
        }

        .tab.active {
            color: var(--primary-color);
            border-bottom-color: var(--primary-color);
        }

        /* Tab Content */
        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
            animation: fadeIn 0.2s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        /* Table */
        .appointments-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        .appointments-table thead {
            background: var(--light-bg);
            border-bottom: 2px solid #e5e7eb;
        }

        .appointments-table th {
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: #1f2937;
            font-size: 0.875rem;
        }

        .appointments-table td {
            padding: 1rem;
            border-bottom: 1px solid #e5e7eb;
            color: #6b7280;
        }

        .appointments-table tbody tr:hover {
            background: #f9fafb;
        }

        /* Status Badges */
        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .status-scheduled {
            background: #dbeafe;
            color: #0284c7;
        }

        .status-requested {
            background: #fef3c7;
            color: #f59e0b;
        }

        .status-reschedule {
            background: #fee2e2;
            color: #dc2626;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .btn-small {
            padding: 0.4rem 0.8rem;
            border: 1px solid #e5e7eb;
            background: white;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.75rem;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-small:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
        }

        .btn-small.danger {
            border-color: var(--danger-color);
            color: var(--danger-color);
        }

        .btn-small.danger:hover {
            background: #fee2e2;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: #9ca3af;
        }

        .empty-state-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .empty-state-text {
            font-size: 1rem;
            color: #6b7280;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .appointments-container {
                padding: 1rem;
            }

            .appointments-header h1 {
                font-size: 1.5rem;
            }

            .calendar-grid {
                gap: 0.25rem;
            }

            .action-cards {
                grid-template-columns: 1fr;
            }

            .tabs-container {
                overflow-x: auto;
            }

            .tab {
                padding: 0.75rem 1rem;
                font-size: 0.85rem;
            }

            .appointments-table {
                font-size: 0.875rem;
            }

            .appointments-table th,
            .appointments-table td {
                padding: 0.75rem 0.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="appointments-container">
        <!-- Header -->
        <div class="appointments-header">
            <h1><i class="fas fa-calendar-check" style="color: var(--primary-color); margin-right: 0.5rem;"></i>Upcoming Appointments</h1>
            <p>View and manage all scheduled donor and patient appointments with our hospital calendar.</p>
        </div>

        <!-- Calendar Section -->
        <div class="calendar-section">
            <div class="donor-selection">
                <label><i class="fas fa-search" style="margin-right: 0.5rem;"></i>Select Donor</label>
                <input type="text" id="donorSearch" placeholder="Search tests or donors...">
            </div>

            <div class="calendar-header">
                <h3>Hospital Calendar</h3>
                <div class="calendar-nav">
                    <button onclick="previousMonth()"><i class="fas fa-chevron-left"></i></button>
                    <span class="calendar-month" id="calendarMonth">April 2026</span>
                    <button onclick="nextMonth()"><i class="fas fa-chevron-right"></i></button>
                </div>
            </div>

            <!-- Calendar Grid -->
            <div class="calendar-grid" id="calendarGrid">
                <!-- Generated by JavaScript -->
            </div>

            <div class="empty-state">
                <div class="empty-state-icon"><i class="fas fa-calendar-alt"></i></div>
                <div class="empty-state-text">Click on any highlighted date to view appointments. Scroll down to manage your appointments.</div>
            </div>
        </div>

        <!-- Action Cards -->
        <div class="action-cards">
            <div class="action-card" onclick="switchTab('scheduled')">
                <div class="action-card-icon"><i class="fas fa-plus-circle"></i></div>
                <h4 class="action-card-title">Create Appointment</h4>
            </div>
            <div class="action-card aftercare" onclick="switchTab('requested')">
                <div class="action-card-icon"><i class="fas fa-hourglass-half"></i></div>
                <h4 class="action-card-title">Aftercare Requested Appointments</h4>
            </div>
            <div class="action-card reschedule" onclick="switchTab('reschedule')">
                <div class="action-card-icon"><i class="fas fa-calendar-times"></i></div>
                <h4 class="action-card-title">Reschedule Requests</h4>
            </div>
        </div>

        <!-- Content Section -->
        <div class="content-section">
            <div class="selected-date" id="selectedDate">Select a date to view appointments</div>

            <!-- Tabs -->
            <div class="tabs-container">
                <button class="tab active" onclick="switchTab('scheduled')">
                    <i class="fas fa-check-circle" style="margin-right: 0.5rem;"></i>Scheduled
                </button>
                <button class="tab" onclick="switchTab('requested')">
                    <i class="fas fa-clock" style="margin-right: 0.5rem;"></i>Requests
                </button>
                <button class="tab" onclick="switchTab('reschedule')">
                    <i class="fas fa-sync-alt" style="margin-right: 0.5rem;"></i>Reschedule
                </button>
            </div>

            <!-- Scheduled Tab Content -->
            <div class="tab-content active" id="scheduled-content">
                <table class="appointments-table">
                    <thead>
                        <tr>
                            <th>Patient ID</th>
                            <th>Donor NIC</th>
                            <th>Donor Name</th>
                            <th>Test Type</th>
                            <th>Test Date</th>
                            <th>Result Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($scheduled_appointments)): ?>
                            <?php foreach ($scheduled_appointments as $apt): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($apt->patient_id ?? $apt->id ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($apt->donor_nic ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($apt->donor_name ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($apt->test_type ?? $apt->appointment_type ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($apt->test_date ?? $apt->appointment_date ?? 'N/A'); ?></td>
                                    <td><span class="status-badge status-scheduled">Scheduled</span></td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn-small">Edit</button>
                                            <button class="btn-small danger">Delete</button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 2rem;">
                                    <div class="empty-state">
                                        <div class="empty-state-icon"><i class="fas fa-inbox"></i></div>
                                        <div class="empty-state-text">No scheduled appointments yet</div>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Requests Tab Content -->
            <div class="tab-content" id="requested-content">
                <table class="appointments-table">
                    <thead>
                        <tr>
                            <th>Patient ID</th>
                            <th>Donor NIC</th>
                            <th>Donor Name</th>
                            <th>Test Type</th>
                            <th>Requested Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($aftercare_appointments)): ?>
                            <?php foreach ($aftercare_appointments as $apt): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($apt->patient_id ?? $apt->id ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($apt->donor_nic ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($apt->donor_name ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($apt->test_type ?? $apt->appointment_type ?? 'Aftercare'); ?></td>
                                    <td><?php echo htmlspecialchars($apt->requested_date ?? $apt->appointment_date ?? 'N/A'); ?></td>
                                    <td><span class="status-badge status-requested">Requested</span></td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn-small" style="border-color: var(--success-color); color: var(--success-color);">Approve</button>
                                            <button class="btn-small danger">Decline</button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 2rem;">
                                    <div class="empty-state">
                                        <div class="empty-state-icon"><i class="fas fa-inbox"></i></div>
                                        <div class="empty-state-text">No aftercare requested appointments</div>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Reschedule Tab Content -->
            <div class="tab-content" id="reschedule-content">
                <table class="appointments-table">
                    <thead>
                        <tr>
                            <th>Patient ID</th>
                            <th>Donor NIC</th>
                            <th>Donor Name</th>
                            <th>Test Type</th>
                            <th>Original Date</th>
                            <th>Requested Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 2rem;">
                                <div class="empty-state">
                                    <div class="empty-state-icon"><i class="fas fa-inbox"></i></div>
                                    <div class="empty-state-text">No reschedule requests</div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        let currentDate = new Date();
        const appointmentDates = <?php echo $appointmentDatesJson; ?>;

        // Initialize calendar on load
        document.addEventListener('DOMContentLoaded', function() {
            generateCalendar();
        });

        function generateCalendar() {
            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();
            const firstDay = new Date(year, month, 1);
            const lastDay = new Date(year, month + 1, 0);
            const daysInMonth = lastDay.getDate();
            const startingDayOfWeek = firstDay.getDay();

            const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                'July', 'August', 'September', 'October', 'November', 'December'
            ];

            document.getElementById('calendarMonth').textContent = monthNames[month] + ' ' + year;

            const calendarGrid = document.getElementById('calendarGrid');
            calendarGrid.innerHTML = '';

            // Day headers
            const dayHeaders = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
            dayHeaders.forEach(day => {
                const header = document.createElement('div');
                header.className = 'calendar-day-header';
                header.textContent = day;
                calendarGrid.appendChild(header);
            });

            // Previous month days
            for (let i = startingDayOfWeek - 1; i >= 0; i--) {
                const day = new Date(year, month, -i).getDate();
                const dayEl = document.createElement('div');
                dayEl.className = 'calendar-day other-month';
                dayEl.textContent = day;
                calendarGrid.appendChild(dayEl);
            }

            // Current month days
            for (let day = 1; day <= daysInMonth; day++) {
                const dayEl = document.createElement('div');
                dayEl.className = 'calendar-day';
                dayEl.textContent = day;

                const today = new Date();
                const checkDate = new Date(year, month, day);
                const dateString = checkDate.toISOString().split('T')[0];

                if (day === today.getDate() && month === today.getMonth() && year === today.getFullYear()) {
                    dayEl.classList.add('today');
                }

                // Check if this date has appointments
                if (appointmentDates.includes(dateString)) {
                    dayEl.classList.add('has-scheduled');
                }

                dayEl.onclick = function() {
                    document.querySelectorAll('.calendar-day').forEach(d => d.classList.remove('selected'));
                    dayEl.classList.add('selected');
                    updateSelectedDate(day, month, year);
                };

                calendarGrid.appendChild(dayEl);
            }

            // Next month days
            const remainingDays = 42 - (startingDayOfWeek + daysInMonth);
            for (let day = 1; day <= remainingDays; day++) {
                const dayEl = document.createElement('div');
                dayEl.className = 'calendar-day other-month';
                dayEl.textContent = day;
                calendarGrid.appendChild(dayEl);
            }
        }

        function previousMonth() {
            currentDate.setMonth(currentDate.getMonth() - 1);
            generateCalendar();
        }

        function nextMonth() {
            currentDate.setMonth(currentDate.getMonth() + 1);
            generateCalendar();
        }

        function updateSelectedDate(day, month, year) {
            const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                'July', 'August', 'September', 'October', 'November', 'December'
            ];
            const dateString = `Appointments on ${monthNames[month]} ${day}, ${year}`;
            document.getElementById('selectedDate').textContent = dateString;
        }

        function switchTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });

            // Remove active class from all tabs
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
            });

            // Remove active class from all action cards
            document.querySelectorAll('.action-card').forEach(card => {
                card.classList.remove('active');
            });

            // Show selected tab content
            const contentId = (tabName === 'requested' ? 'requested' : tabName === 'reschedule' ? 'reschedule' : 'scheduled') + '-content';
            const content = document.getElementById(contentId);
            if (content) {
                content.classList.add('active');
            }

            // Add active class to corresponding tab button
            document.querySelectorAll('.tab').forEach(tab => {
                if (tab.textContent.toLowerCase().includes(tabName.replace('requested', 'requests').replace('scheduled', 'scheduled').replace('reschedule', 'reschedule'))) {
                    tab.classList.add('active');
                }
            });

            document.querySelectorAll('.action-card').forEach(card => {
                if ((tabName === 'requested' && card.classList.contains('aftercare')) ||
                    (tabName === 'reschedule' && card.classList.contains('reschedule')) ||
                    (tabName === 'scheduled' && !card.classList.contains('aftercare') && !card.classList.contains('reschedule'))) {
                    card.classList.add('active');
                }
            });
        }
    </script>
</body>

</html>
