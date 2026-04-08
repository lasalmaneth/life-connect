<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/life-connect/public/assets/css/aftercare/aftercare.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Aftercare Management - LifeConnect Admin</title>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <div>
                <h1>Aftercare Management</h1>
                <p>Manage aftercare appointments and patient support requests</p>
            </div>
            <div class="user-info">
                <div class="user-avatar">A</div>
                <div>
                    <div style="font-weight: 600; font-size: 0.9rem;">Admin User</div>
                    <div style="font-size: 0.8rem; opacity: 0.8;">Aftercare Admin</div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="main-content">
            <div class="sidebar">
                <div class="sidebar-header">
                    <h3>Aftercare Panel</h3>
                    <p>Post-donation support</p>
                </div>

                <div class="menu-section">
                    <div class="menu-section-title">Sections</div>
                    <div class="menu-item active" onclick="showContent('overview')">
                        <span class="icon"><i class="fa-solid fa-heart-pulse"></i></span>
                        <span>Overview</span>
                    </div>
                    <div class="menu-item" onclick="showContent('appointments')">
                        <span class="icon"><i class="fa-solid fa-calendar-check"></i></span>
                        <span>Appointments</span>
                    </div>
                    <div class="menu-item" onclick="showContent('support-requests')">
                        <span class="icon"><i class="fa-solid fa-hand-holding-medical"></i></span>
                        <span>Support Requests</span>
                    </div>
                    <div class="menu-item" onclick="showContent('feedback')">
                        <span class="icon"><i class="fa-solid fa-comment-dots"></i></span>
                        <span>User Feedback</span>
                    </div>
                </div>
            </div>

            <div class="content-area" id="content-area">
                <div id="overview" class="content-section">
                    <div class="content-header">
                        <h2>Aftercare Overview</h2>
                        <p>Track appointments, review support requests, and manage patient notifications.</p>
                    </div>
                    <div class="content-body">
                        <div class="stats-grid">
                            <div class="stat-card">
                                <div class="stat-number">48</div>
                                <div class="stat-label">Upcoming Appointments</div>
                                <div class="stat-change neutral">This month</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number">19</div>
                                <div class="stat-label">Pending Support</div>
                                <div class="stat-change negative">Awaiting review</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number">86%</div>
                                <div class="stat-label">Attendance Rate</div>
                                <div class="stat-change positive">↑ Improving</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number">12</div>
                                <div class="stat-label">New Feedback</div>
                                <div class="stat-change neutral">7 unresolved</div>
                            </div>
                        </div>

                        <div class="feature-grid">
                            <div class="feature-card">
                                <div class="feature-icon">🗓️</div>
                                <h3>Schedule Checkups</h3>
                                <p>Create monthly or annual aftercare appointments for post-donation and recipient patients.</p>
                            </div>
                            <div class="feature-card">
                                <div class="feature-icon">📨</div>
                                <h3>Review Support</h3>
                                <p>Approve or reject requests for test support and notify patients instantly.</p>
                            </div>
                            <div class="feature-card">
                                <div class="feature-icon">🔔</div>
                                <h3>Send Notifications</h3>
                                <p>Notify patients about approvals, reminders, or reschedules with one click.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="appointments" class="content-section" style="display: none;">
                    <div class="content-header">
                        <h2>Manage Aftercare Appointments</h2>
                        <p>Create and manage follow-up appointments for registered patients.</p>
                    </div>
                    <div class="content-body">
                        <div class="action-section">
                            <h3>Appointment Actions</h3>
                            <div class="action-buttons">
                                <button class="btn btn-primary" onclick="openAppointmentModal()">Add Appointment</button>
                                <button class="btn btn-secondary" onclick="exportAppointments()">Export</button>
                            </div>
                        </div>

                        <div class="search-bar">
                            <span class="search-icon">🔍</span>
                            <input type="text" class="search-input" placeholder="Search by patient name or NIC...">
                        </div>

                        <div class="filter-section">
                            <select class="filter-select">
                                <option value="">All Types</option>
                                <option value="monthly">Monthly Checkup</option>
                                <option value="annual">Annual Review</option>
                            </select>
                            <select class="filter-select">
                                <option value="">All Status</option>
                                <option value="upcoming">Upcoming</option>
                                <option value="completed">Completed</option>
                                <option value="missed">Missed</option>
                            </select>
                        </div>

                        <div class="data-table">
                            <div class="table-header">
                                <h4>Scheduled Appointments</h4>
                            </div>
                            <div class="table-content">
                                <div class="table-row" style="font-weight: 600; background: var(--gray-bg-color);">
                                    <div class="table-cell">Patient</div>
                                    <div class="table-cell">Type</div>
                                    <div class="table-cell">Date</div>
                                    <div class="table-cell">Status</div>
                                    <div class="table-cell">Actions</div>
                                </div>

                                <div class="table-row">
                                    <div class="table-cell name" data-label="Patient">NIC 2001XXXXXXX - D. Perera</div>
                                    <div class="table-cell" data-label="Type">Monthly</div>
                                    <div class="table-cell" data-label="Date">2025-11-05 09:00</div>
                                    <div class="table-cell" data-label="Status"><span class="status-badge status-active">Upcoming</span></div>
                                    <div class="table-cell" data-label="Actions">
                                        <button class="btn btn-secondary btn-small" onclick="editAppointment()">Edit</button>
                                        <button class="btn btn-danger btn-small" onclick="cancelAppointment()">Cancel</button>
                                    </div>
                                </div>

                                <div class="table-row">
                                    <div class="table-cell name" data-label="Patient">NIC 1999XXXXXXX - S. Lakshika</div>
                                    <div class="table-cell" data-label="Type">Annual</div>
                                    <div class="table-cell" data-label="Date">2025-12-10 10:30</div>
                                    <div class="table-cell" data-label="Status"><span class="status-badge status-pending">Pending</span></div>
                                    <div class="table-cell" data-label="Actions">
                                        <button class="btn btn-secondary btn-small" onclick="editAppointment()">Edit</button>
                                        <button class="btn btn-danger btn-small" onclick="cancelAppointment()">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="support-requests" class="content-section" style="display: none;">
                    <div class="content-header">
                        <h2>Patient Support Requests</h2>
                        <p>Approve or reject support requests for medical tests and notify patients.</p>
                    </div>
                    <div class="content-body">
                        <div class="action-section">
                            <h3>Bulk Actions</h3>
                            <div class="action-buttons">
                                <button class="btn btn-success" onclick="bulkApproveSupport()">Approve Selected</button>
                                <button class="btn btn-danger" onclick="bulkRejectSupport()">Reject Selected</button>
                            </div>
                        </div>

                        <div class="search-bar">
                            <span class="search-icon">🔍</span>
                            <input type="text" class="search-input" placeholder="Search by patient or reason...">
                        </div>

                        <div class="data-table">
                            <div class="table-header">
                                <h4>Requests Queue</h4>
                            </div>
                            <div class="table-content">
                                <div class="table-row" style="font-weight: 600; background: var(--gray-bg-color);">
                                    <div class="table-cell"><input type="checkbox"> Patient & Reason</div>
                                    <div class="table-cell">Type</div>
                                    <div class="table-cell">Status</div>
                                    <div class="table-cell">Submitted</div>
                                    <div class="table-cell">Actions</div>
                                </div>

                                <div class="table-row">
                                    <div class="table-cell name" data-label="Patient & Reason"><input type="checkbox"> 2001XXXXXXX - M. Jayasinghe — Travel cost support</div>
                                    <div class="table-cell" data-label="Type">Post Donation Patient</div>
                                    <div class="table-cell" data-label="Status"><span class="status-badge status-pending">Pending</span></div>
                                    <div class="table-cell" data-label="Submitted">2025-10-14</div>
                                    <div class="table-cell" data-label="Actions">
                                        <button class="btn btn-success btn-small" onclick="approveSupport()">Approve</button>
                                        <button class="btn btn-danger btn-small" onclick="rejectSupport()">Reject</button>
                                    </div>
                                </div>

                                <div class="table-row">
                                    <div class="table-cell name" data-label="Patient & Reason"><input type="checkbox"> 1998XXXXXXX - R. Fernando — Test fee support</div>
                                    <div class="table-cell" data-label="Type">Recipient Patient</div>
                                    <div class="table-cell" data-label="Status"><span class="status-badge status-eligible">In Review</span></div>
                                    <div class="table-cell" data-label="Submitted">2025-10-13</div>
                                    <div class="table-cell" data-label="Actions">
                                        <button class="btn btn-success btn-small" onclick="approveSupport()">Approve</button>
                                        <button class="btn btn-danger btn-small" onclick="rejectSupport()">Reject</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="feedback" class="content-section" style="display: none;">
                    <div class="content-header">
                        <h2>User Feedback</h2>
                        <p>Review and mark feedback as resolved to improve aftercare services.</p>
                    </div>
                    <div class="content-body">
                        <div class="data-table">
                            <div class="table-header">
                                <h4>Feedback</h4>
                            </div>
                            <div class="table-content">
                                <div class="table-row" style="font-weight: 600; background: var(--gray-bg-color);">
                                    <div class="table-cell">User</div>
                                    <div class="table-cell">Message</div>
                                    <div class="table-cell">Status</div>
                                    <div class="table-cell">Date</div>
                                    <div class="table-cell">Actions</div>
                                </div>

                                <div class="table-row">
                                    <div class="table-cell name" data-label="User">Anonymous</div>
                                    <div class="table-cell" data-label="Message">Waiting area could be more comfortable.</div>
                                    <div class="table-cell" data-label="Status"><span class="status-badge status-pending">Unresolved</span></div>
                                    <div class="table-cell" data-label="Date">2025-10-12</div>
                                    <div class="table-cell" data-label="Actions">
                                        <button class="btn btn-secondary btn-small" onclick="markResolved()">Mark Resolved</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="appointment-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add Appointment</h3>
                <button class="modal-close" onclick="closeAppointmentModal()">×</button>
            </div>
            <div>
                <div class="form-group">
                    <label class="form-label">Patient NIC</label>
                    <input type="text" class="form-input" placeholder="2001XXXXXXX">
                </div>
                <div class="form-group">
                    <label class="form-label">Appointment Type</label>
                    <select class="form-select">
                        <option value="monthly">Monthly Checkup</option>
                        <option value="annual">Annual Review</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Date & Time</label>
                    <input type="datetime-local" class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label">Notes</label>
                    <textarea class="form-textarea" placeholder="Optional notes..."></textarea>
                </div>
                <button class="btn btn-primary" onclick="saveAppointment()">Save</button>
            </div>
        </div>
    </div>

    <!-- External JavaScript -->
    <script src="/life-connect/public/assets/js/aftercare/aftercare.js"></script>
</body>
</html>


