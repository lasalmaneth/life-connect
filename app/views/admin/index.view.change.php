<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/life-connect/public/assets/css/style.css">
    <link rel="stylesheet" href="/life-connect/public/assets/css/admin/style.css">
    <link rel="stylesheet" href="/life-connect/public/assets/css/admin/dashboard.css">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <title>User Admin | LifeConnect</title>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <div>
                <h1>LifeConnect Admin Dashboard</h1>
                <p>Healthcare Management System - User Administration</p>
            </div>
            <div class="user-info">
                <div class="user-avatar">A</div>
                <div>
                    <div style="font-weight: 600; font-size: 0.9rem;">Admin User</div>
                    <div style="font-size: 0.8rem; opacity: 0.8;">System Administrator</div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="main-content">
            <div class="sidebar">
                <div class="sidebar-header">
                    <h3>System Management</h3>
                    <p>Administrative Dashboard</p>
                </div>
                
                <div class="menu-section">
                    <div class="menu-section-title">Overview</div>
                    <div class="menu-item active" onclick="showContent('dashboard')">
                        <span class="icon"><i class="fa-solid fa-house"></i></span>
                        <span>Dashboard Overview</span>
                    </div>
                </div>

                <div class="menu-section">
                    <div class="menu-section-title">User Management</div>
                    
                    <div class="menu-item" onclick="showContent('accounts')">
                        <span class="icon"><i class="fa-solid fa-circle-user"></i></span>
                        <span>User Accounts</span>
                    </div>
                    
                    <div class="menu-item" onclick="showContent('documents')">
                        <span class="icon"><i class="fa-solid fa-file"></i></span>
                        <span>Document Verification</span>
                    </div>
                    
                    <div class="menu-item" onclick="showContent('notifications')">
                        <span class="icon"><i class="fa-solid fa-at"></i></span>
                        <span>User Notifications</span>
                    </div>
                    
                    <div class="menu-item" onclick="showContent('eligibility')">
                        <span class="icon"><i class="fa-solid fa-heart-circle-check"></i></span>
                        <span>Donor Eligibility</span>
                    </div>
                    
                    <div class="menu-item" onclick="showContent('roles')">
                        <span class="icon"><i class="fa-solid fa-people-group"></i></span>
                        <span>Role Management</span>
                    </div>
                    
                    <div class="menu-item" onclick="showContent('nic-validation')">
                        <span class="icon"><i class="fa-solid fa-address-card"></i></span>
                        <span>NIC Validation</span>
                    </div>
                </div>
            </div>

            <div class="content-area" id="content-area">
                <!-- Dashboard Overview -->
                <div id="dashboard" class="content-section">
                    <div class="content-header">
                        <h2>📊 LifeConnect Dashboard</h2>
                        <p>Comprehensive monitoring and management dashboard. Track user activities, document processing, and system integrations in real-time.</p>
                    </div>
                    <div class="content-body">
                        <div class="quick-stats">
                            <div class="quick-stat-card">
                                <div class="quick-stat-number">1,247</div>
                                <div class="quick-stat-label">Total Users</div>
                            </div>
                            <div class="quick-stat-card">
                                <div class="quick-stat-number">89</div>
                                <div class="quick-stat-label">Pending Documents</div>
                            </div>
                            <div class="quick-stat-card">
                                <div class="quick-stat-number">342</div>
                                <div class="quick-stat-label">Eligible Donors</div>
                            </div>
                            <div class="quick-stat-card">
                                <div class="quick-stat-number">156</div>
                                <div class="quick-stat-label">Active Patients</div>
                            </div>
                        </div>

                        <div class="stats-grid">
                            <div class="stat-card">
                                <div class="stat-number">287</div>
                                <div class="stat-label">Live Organ Donors</div>
                                <div class="stat-change positive">↑ 15% this month</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number">156</div>
                                <div class="stat-label">Deceased Organ Donors</div>
                                <div class="stat-change positive">↑ 8% this week</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number">94</div>
                                <div class="stat-label">Aftercare Donors</div>
                                <div class="stat-change positive">↑ 12% this month</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number">178</div>
                                <div class="stat-label">Financial Donors</div>
                                <div class="stat-change positive">↑ 22% this month</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number">23</div>
                                <div class="stat-label">Hospitals</div>
                                <div class="stat-change neutral">No change</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number">47</div>
                                <div class="stat-label">Medical Schools</div>
                                <div class="stat-change positive">↑ 2 new this week</div>
                            </div>
                        </div>

                        <div class="charts-section">
                            <div class="chart-card">
                                <h3 class="chart-title">🍩 User Distribution</h3>
                                <div class="doughnut-container"><canvas id="userChart"></canvas></div>
                                <div class="chart-legend">
                                    <div class="legend-item">
                                        <div class="legend-left">
                                            <div class="legend-color" style="background:#005baa"></div>Live Organ Donors
                                        </div>
                                        <div class="legend-count">287</div>
                                    </div>
                                    <div class="legend-item">
                                        <div class="legend-left">
                                            <div class="legend-color" style="background:#0076d1"></div>Deceased Organ Donors
                                        </div>
                                        <div class="legend-count">156</div>
                                    </div>
                                    <div class="legend-item">
                                        <div class="legend-left">
                                            <div class="legend-color" style="background:#3498db"></div>Aftercare Donors
                                        </div>
                                        <div class="legend-count">94</div>
                                    </div>
                                    <div class="legend-item">
                                        <div class="legend-left">
                                            <div class="legend-color" style="background:#74b9ff"></div>Financial Donors
                                        </div>
                                        <div class="legend-count">178</div>
                                    </div>
                                    <div class="legend-item">
                                        <div class="legend-left">
                                            <div class="legend-color" style="background:#a4c8e1"></div>Patients
                                        </div>
                                        <div class="legend-count">421</div>
                                    </div>
                                    <div class="legend-item">
                                        <div class="legend-left">
                                            <div class="legend-color" style="background:#dbeafe"></div>Medical Staff
                                        </div>
                                        <div class="legend-count">111</div>
                                    </div>
                                </div>
                            </div>

                            <div class="chart-card">
                                <h3 class="chart-title">📈 Weekly Registration Activity</h3>
                                <div class="bar-chart-container">
                                    <div class="chart-grid">
                                        <div class="chart-grid-line" style="bottom:80%;"></div>
                                        <div class="chart-grid-line" style="bottom:60%;"></div>
                                        <div class="chart-grid-line" style="bottom:40%;"></div>
                                        <div class="chart-grid-line" style="bottom:20%;"></div>
                                    </div>
                                    <div class="bar-chart">
                                        <div class="bar" style="height:60%">
                                            <div class="bar-value">24</div>
                                            <div class="bar-label">Mon</div>
                                        </div>
                                        <div class="bar" style="height:80%">
                                            <div class="bar-value">32</div>
                                            <div class="bar-label">Tue</div>
                                        </div>
                                        <div class="bar" style="height:45%">
                                            <div class="bar-value">18</div>
                                            <div class="bar-label">Wed</div>
                                        </div>
                                        <div class="bar" style="height:70%">
                                            <div class="bar-value">28</div>
                                            <div class="bar-label">Thu</div>
                                        </div>
                                        <div class="bar" style="height:90%">
                                            <div class="bar-value">36</div>
                                            <div class="bar-label">Fri</div>
                                        </div>
                                        <div class="bar" style="height:35%">
                                            <div class="bar-value">14</div>
                                            <div class="bar-label">Sat</div>
                                        </div>
                                        <div class="bar" style="height:25%">
                                            <div class="bar-value">10</div>
                                            <div class="bar-label">Sun</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="chart-stats">
                                    <div class="chart-stat">
                                        <div class="chart-stat-value">162</div>
                                        <div class="chart-stat-label">Weekly Total</div>
                                    </div>
                                    <div class="chart-stat">
                                        <div class="chart-stat-value">23</div>
                                        <div class="chart-stat-label">Daily Average</div>
                                    </div>
                                    <div class="chart-stat">
                                        <div class="chart-stat-value">36</div>
                                        <div class="chart-stat-label">Peak Day</div>
                                    </div>
                                    <div class="chart-stat">
                                        <div class="chart-stat-value">+18%</div>
                                        <div class="chart-stat-label">vs Last Week</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="summary-section">
                            <h3 class="summary-title">📊 System Summary</h3>
                            <div class="summary-grid">
                                <div class="summary-item"><div class="summary-number">142</div><div class="summary-text">Active Accounts</div></div>
                                <div class="summary-item"><div class="summary-number">28</div><div class="summary-text">Suspended Accounts</div></div>
                                <div class="summary-item"><div class="summary-number">15</div><div class="summary-text">Deactivated Accounts</div></div>
                                <div class="summary-item"><div class="summary-number">73</div><div class="summary-text">Approved Documents</div></div>
                                <div class="summary-item"><div class="summary-number">16</div><div class="summary-text">Rejected Documents</div></div>
                                <div class="summary-item"><div class="summary-number">89</div><div class="summary-text">Pending Reviews</div></div>
                                <div class="summary-item"><div class="summary-number">234</div><div class="summary-text">NIC Validated</div></div>
                                <div class="summary-item"><div class="summary-number">12</div><div class="summary-text">Age Flagged (Under 18)</div></div>
                                <div class="summary-item"><div class="summary-number">4</div><div class="summary-text">Guardian Required</div></div>
                                <div class="summary-item"><div class="summary-number">67</div><div class="summary-text">Eligibility Updates</div></div>
                            </div>
                        </div>

                        <div class="activity-feed">
                            <h3 class="activity-title">🔄 Recent System Activity</h3>
                            <div class="activity-item">
                                <div class="activity-icon success">✓</div>
                                <div class="activity-content">
                                    <div class="activity-text">5 new donor registrations approved</div>
                                    <div class="activity-time">2 minutes ago</div>
                                </div>
                            </div>
                            <div class="activity-item">
                                <div class="activity-icon info">📋</div>
                                <div class="activity-content">
                                    <div class="activity-text">12 documents submitted for verification</div>
                                    <div class="activity-time">15 minutes ago</div>
                                </div>
                            </div>
                            <div class="activity-item">
                                <div class="activity-icon warning">⚠️</div>
                                <div class="activity-content">
                                    <div class="activity-text">3 accounts suspended for incomplete documentation</div>
                                    <div class="activity-time">1 hour ago</div>
                                </div>
                            </div>
                            <div class="activity-item">
                                <div class="activity-icon success">🔗</div>
                                <div class="activity-content">
                                    <div class="activity-text">Election Commission API sync completed</div>
                                    <div class="activity-time">2 hours ago</div>
                                </div>
                            </div>
                            <div class="activity-item">
                                <div class="activity-icon info">📧</div>
                                <div class="activity-content">
                                    <div class="activity-text">89 approval notifications sent to users</div>
                                    <div class="activity-time">3 hours ago</div>
                                </div>
                            </div>
                            <div class="activity-item">
                                <div class="activity-icon error">❌</div>
                                <div class="activity-content">
                                    <div class="activity-text">2 registrations rejected - invalid NIC details</div>
                                    <div class="activity-time">4 hours ago</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Accounts Management -->
                <div id="accounts" class="content-section" style="display: none;">
                    <div class="content-header">
                        <h2>User Account Management</h2>
                        <p>Manage user accounts with comprehensive administrative controls. Activate, deactivate, and suspend accounts while maintaining detailed audit logs and user communication.</p>
                    </div>
                    <div class="content-body">
                        <div class="search-bar">
                            <span class="search-icon">🔍</span>
                            <input type="text" class="search-input" placeholder="Search users by name, email, or ID..." id="user-search">
                        </div>

                        <div class="filter-section">
                            <select class="filter-select" id="status-filter">
                                <option value="">All Statuses</option>
                                <option value="active">Active</option>
                                <option value="suspended">Suspended</option>
                                <option value="pending">Pending</option>
                            </select>
                            <select class="filter-select" id="role-filter">
                                <option value="">All Roles</option>
                                <option value="donor">Donor</option>
                                <option value="patient">Patient</option>
                                <option value="hospital">Hospital</option>
                                <option value="financial">Financial Donor</option>
                            </select>
                        </div>

                        <div class="action-section">
                            <h3>Account Actions</h3>
                            <div class="action-buttons">
                                <button class="btn btn-primary" onclick="showUserModal('add')">Add New User</button>
                                <button class="btn btn-success" id="bulk-activate" onclick="bulkActivate()" disabled>Bulk Activate</button>
                                <button class="btn btn-secondary" id="bulk-deactivate" onclick="bulkDeactivate()" disabled>Bulk Deactivate</button>
                                <button class="btn btn-danger" id="bulk-suspend" onclick="bulkSuspend()" disabled>Bulk Suspend</button>
                            </div>
                        </div>

                        <div class="data-table">
                            <div class="table-header">
                                <h4>User Accounts</h4>
                            </div>
                            <div class="table-content" id="users-table">
                                <div class="table-row" style="font-weight: 600; background: var(--gray-bg-color);">
                                    <div class="table-cell">
                                        <input type="checkbox" id="select-all" onchange="toggleSelectAll()"> User Details
                                    </div>
                                    <div class="table-cell">Role</div>
                                    <div class="table-cell">Status</div>
                                    <div class="table-cell">Registration Date</div>
                                    <div class="table-cell">Actions</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Document Verification -->
                <div id="documents" class="content-section" style="display: none;">
                    <div class="content-header">
                        <h2>Document Verification</h2>
                        <p>Verify user document uploads and approve or reject registrations through comprehensive document review system with automated workflows and quality control.</p>
                    </div>
                    <div class="content-body">
                        <div class="search-bar">
                            <span class="search-icon">🔍</span>
                            <input type="text" class="search-input" placeholder="Search by document ID, user name, or type...">
                        </div>

                        <div class="filter-section">
                            <select class="filter-select" id="doc-type-filter">
                                <option value="">All Document Types</option>
                                <option value="nic">NIC Documents</option>
                                <option value="medical">Medical Certificates</option>
                                <option value="address">Address Proof</option>
                                <option value="guardian">Guardian Documents</option>
                            </select>
                            <select class="filter-select" id="doc-status-filter">
                                <option value="">All Statuses</option>
                                <option value="pending">Pending Review</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>

                        <div class="action-section">
                            <h3>Document Actions</h3>
                            <div class="action-buttons">
                                <button class="btn btn-success" id="bulk-approve-docs" onclick="bulkApproveDocuments()" disabled>Bulk Approve</button>
                                <button class="btn btn-danger" id="bulk-reject-docs" onclick="bulkRejectDocuments()" disabled>Bulk Reject</button>
                                <button class="btn btn-secondary" onclick="exportDocumentReport()">Export Report</button>
                            </div>
                        </div>

                        <div class="data-table">
                            <div class="table-header">
                                <h4>Document Verification Queue</h4>
                            </div>
                            <div class="table-content" id="documents-table">
                                <div class="table-row" style="font-weight: 600; background: var(--gray-bg-color);">
                                    <div class="table-cell">
                                        <input type="checkbox" id="select-all-docs" onchange="toggleSelectAllDocs()"> Document Details
                                    </div>
                                    <div class="table-cell">Type</div>
                                    <div class="table-cell">Status</div>
                                    <div class="table-cell">Submission Date</div>
                                    <div class="table-cell">Actions</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Notifications -->
                <div id="notifications" class="content-section" style="display: none;">
                    <div class="content-header">
                        <h2>User Notifications</h2>
                        <p>Notify users upon approval or rejection with detailed reasons for rejection and comprehensive communication management. Automated and manual notification systems.</p>
                    </div>
                    <div class="content-body">
                        <div class="action-section">
                            <h3>Send Notifications</h3>
                            <div class="action-buttons">
                                <button class="btn btn-primary" onclick="showNotificationModal('compose')">Compose Notification</button>
                                <button class="btn btn-success" onclick="sendApprovalNotifications()">Send Approval Notices</button>
                                <button class="btn btn-danger" onclick="sendRejectionNotifications()">Send Rejection Notices</button>
                                <button class="btn btn-secondary" onclick="sendReminderNotifications()">Send Reminders</button>
                            </div>
                        </div>

                        <div class="data-table">
                            <div class="table-header">
                                <h4>Recent Notifications</h4>
                            </div>
                            <div class="table-content" id="notifications-table">
                                <div class="table-row" style="font-weight: 600; background: var(--gray-bg-color);">
                                    <div class="table-cell">Recipient & Subject</div>
                                    <div class="table-cell">Type</div>
                                    <div class="table-cell">Status</div>
                                    <div class="table-cell">Sent Date</div>
                                    <div class="table-cell">Actions</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Donor Eligibility -->
                <div id="eligibility" class="content-section" style="display: none;">
                    <div class="content-header">
                        <h2>Donor Eligibility Management</h2>
                        <p>Update and notify donor eligibility status based on medical assessments, compliance requirements, and periodic health evaluations. Comprehensive donor management system.</p>
                    </div>
                    <div class="content-body">
                        <div class="search-bar">
                            <span class="search-icon">🔍</span>
                            <input type="text" class="search-input" placeholder="Search donors by name, ID, or blood type...">
                        </div>

                        <div class="filter-section">
                            <select class="filter-select" id="eligibility-filter">
                                <option value="">All Eligibility Status</option>
                                <option value="eligible">Eligible</option>
                                <option value="temp-ineligible">Temporarily Ineligible</option>
                                <option value="perm-ineligible">Permanently Ineligible</option>
                                <option value="under-review">Under Review</option>
                            </select>
                            <select class="filter-select" id="blood-type-filter">
                                <option value="">All Blood Types</option>
                                <option value="O+">O+</option>
                                <option value="O-">O-</option>
                                <option value="A+">A+</option>
                                <option value="A-">A-</option>
                                <option value="B+">B+</option>
                                <option value="B-">B-</option>
                                <option value="AB+">AB+</option>
                                <option value="AB-">AB-</option>
                            </select>
                        </div>

                        <div class="action-section">
                            <h3>Eligibility Actions</h3>
                            <div class="action-buttons">
                                <button class="btn btn-primary" onclick="showEligibilityModal()">Update Eligibility Status</button>
                                <button class="btn btn-success" id="bulk-approve-eligibility" onclick="bulkApproveEligibility()" disabled>Mark as Eligible</button>
                                <button class="btn btn-secondary" id="bulk-temp-ineligible" onclick="bulkTempIneligible()" disabled>Temporarily Ineligible</button>
                                <button class="btn btn-danger" id="bulk-perm-ineligible" onclick="bulkPermIneligible()" disabled>Permanently Ineligible</button>
                            </div>
                        </div>

                        <div class="data-table">
                            <div class="table-header">
                                <h4>Donor Eligibility Status</h4>
                            </div>
                            <div class="table-content" id="eligibility-table">
                                <div class="table-row" style="font-weight: 600; background: var(--gray-bg-color);">
                                    <div class="table-cell">
                                        <input type="checkbox" id="select-all-eligibility" onchange="toggleSelectAllEligibility()"> Donor Information
                                    </div>
                                    <div class="table-cell">Blood Type</div>
                                    <div class="table-cell">Eligibility Status</div>
                                    <div class="table-cell">Last Assessment</div>
                                    <div class="table-cell">Actions</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Role Management -->
                <div id="roles" class="content-section" style="display: none;">
                    <div class="content-header">
                        <h2>Role Management</h2>
                        <p>Manage system user roles including donor, patient, hospital, and financial donor with comprehensive permission controls and security management.</p>
                    </div>
                    <div class="content-body">
                        <div class="feature-grid" style="margin-bottom: 2rem;">
                            <div class="feature-card">
                                <div class="feature-icon">💝</div>
                                <h3>Donor Role</h3>
                                <p>Blood and organ donors with access to donation scheduling, health assessments, and donation history tracking.</p>
                                <div style="margin-top: 1rem;">
                                    <span class="status-badge status-active" id="donor-count">342 Active</span>
                                </div>
                            </div>
                            <div class="feature-card">
                                <div class="feature-icon">🏥</div>
                                <h3>Patient Role</h3>
                                <p>Individuals seeking medical assistance with access to treatment requests, medical records, and communication tools.</p>
                                <div style="margin-top: 1rem;">
                                    <span class="status-badge status-active" id="patient-count">156 Active</span>
                                </div>
                            </div>
                            <div class="feature-card">
                                <div class="feature-icon">🏥</div>
                                <h3>Hospital Role</h3>
                                <p>Healthcare institutions with administrative access to patient management, resource allocation, and reporting systems.</p>
                                <div style="margin-top: 1rem;">
                                    <span class="status-badge status-active" id="hospital-count">28 Active</span>
                                </div>
                            </div>
                            <div class="feature-card">
                                <div class="feature-icon">💰</div>
                                <h3>Financial Donor Role</h3>
                                <p>Individuals or organizations providing financial support with access to donation tracking and impact reports.</p>
                                <div style="margin-top: 1rem;">
                                    <span class="status-badge status-active" id="financial-count">89 Active</span>
                                </div>
                            </div>
                        </div>

                        <div class="action-section">
                            <h3>Role Actions</h3>
                            <div class="action-buttons">
                                <button class="btn btn-primary" onclick="showRoleModal()">Assign Role</button>
                                <button class="btn btn-secondary" onclick="bulkRoleUpdate()">Bulk Role Update</button>
                                <button class="btn btn-secondary" onclick="managePermissions()">Manage Permissions</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- NIC Validation -->
                <div id="nic-validation" class="content-section" style="display: none;">
                    <div class="content-header">
                        <h2>NIC Validation & Integration</h2>
                        <p>System integrates with Election Commission eServices API to validate NIC details and age during registration. Ensures donors are 21+ and flags recipients under 18 for guardian registration.</p>
                    </div>
                    <div class="content-body">
                        <div class="stats-grid">
                            <div class="stat-card">
                                <div class="stat-number" id="total-validations">1,156</div>
                                <div class="stat-label">Total Validations</div>
                                <div class="stat-change positive">↑ 45 today</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number" id="under-18-flagged">23</div>
                                <div class="stat-label">Under 18 Flagged</div>
                                <div class="stat-change warning">Guardian required</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number" id="api-failures">8</div>
                                <div class="stat-label">API Failures</div>
                                <div class="stat-change negative">↑ 3 today</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number">99.3%</div>
                                <div class="stat-label">API Success Rate</div>
                                <div class="stat-change positive">↑ 0.2% improvement</div>
                            </div>
                        </div>

                        <div class="action-section">
                            <h3>Validation Actions</h3>
                            <div class="action-buttons">
                                <button class="btn btn-primary" onclick="showNICValidationModal()">Manual NIC Validation</button>
                                <button class="btn btn-secondary" onclick="retryFailedValidations()">Retry Failed Validations</button>
                                <button class="btn btn-secondary" onclick="manageGuardianRequests()">Manage Guardian Requests</button>
                                <button class="btn btn-secondary" onclick="exportValidationReport()">Export Report</button>
                            </div>
                        </div>

                        <div class="data-table">
                            <div class="table-header">
                                <h4>Recent NIC Validations</h4>
                            </div>
                            <div class="table-content" id="nic-validations-table">
                                <div class="table-row" style="font-weight: 600; background: var(--gray-bg-color);">
                                    <div class="table-cell">User & NIC Details</div>
                                    <div class="table-cell">Age</div>
                                    <div class="table-cell">Validation Status</div>
                                    <div class="table-cell">Validation Date</div>
                                    <div class="table-cell">Actions</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- User Management Modal -->
    <div id="user-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="user-modal-title">Add New User</h3>
                <button class="modal-close" onclick="closeModal('user-modal')">&times;</button>
            </div>
            <form id="user-form">
                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <input type="text" class="form-input" id="user-name" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" class="form-input" id="user-email" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Phone Number</label>
                    <input type="tel" class="form-input" id="user-phone" required>
                </div>
                <div class="form-group">
                    <label class="form-label">NIC Number</label>
                    <input type="text" class="form-input" id="user-nic" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Role</label>
                    <select class="form-select" id="user-role" required>
                        <option value="">Select Role</option>
                        <option value="donor">Donor</option>
                        <option value="patient">Patient</option>
                        <option value="hospital">Hospital</option>
                        <option value="financial">Financial Donor</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select class="form-select" id="user-status" required>
                        <option value="pending">Pending</option>
                        <option value="active">Active</option>
                        <option value="suspended">Suspended</option>
                    </select>
                </div>
                <div class="action-buttons" style="margin-top: 2rem;">
                    <button type="submit" class="btn btn-primary">
                        <span class="loading" id="user-form-loading" style="display: none;"></span>
                        <span id="user-form-text">Save User</span>
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="closeModal('user-modal')">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Notification Compose Modal -->
    <div id="notification-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Compose Notification</h3>
                <button class="modal-close" onclick="closeModal('notification-modal')">&times;</button>
            </div>
            <form id="notification-form">
                <div class="form-group">
                    <label class="form-label">Recipient</label>
                    <select class="form-select" id="notification-recipient" required>
                        <option value="">Select Recipient</option>
                        <option value="all-users">All Users</option>
                        <option value="donors">All Donors</option>
                        <option value="patients">All Patients</option>
                        <option value="hospitals">All Hospitals</option>
                        <option value="specific">Specific User</option>
                    </select>
                </div>
                <div class="form-group" id="specific-user-group" style="display: none;">
                    <label class="form-label">Specific User Email</label>
                    <input type="email" class="form-input" id="specific-user-email">
                </div>
                <div class="form-group">
                    <label class="form-label">Notification Type</label>
                    <select class="form-select" id="notification-type" required>
                        <option value="">Select Type</option>
                        <option value="approval">Approval Notice</option>
                        <option value="rejection">Rejection Notice</option>
                        <option value="reminder">Reminder</option>
                        <option value="update">Status Update</option>
                        <option value="welcome">Welcome Message</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Subject</label>
                    <input type="text" class="form-input" id="notification-subject" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Message</label>
                    <textarea class="form-textarea" id="notification-message" required placeholder="Enter your notification message..."></textarea>
                </div>
                <div class="form-group" id="rejection-reason-group" style="display: none;">
                    <label class="form-label">Rejection Reason</label>
                    <textarea class="form-textarea" id="rejection-reason" placeholder="Provide detailed reason for rejection..."></textarea>
                </div>
                <div class="action-buttons" style="margin-top: 2rem;">
                    <button type="submit" class="btn btn-primary">
                        <span class="loading" id="notification-form-loading" style="display: none;"></span>
                        <span id="notification-form-text">Send Notification</span>
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="closeModal('notification-modal')">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- NIC Validation Modal -->
    <div id="nic-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Manual NIC Validation</h3>
                <button class="modal-close" onclick="closeModal('nic-modal')">&times;</button>
            </div>
            <form id="nic-form">
                <div class="form-group">
                    <label class="form-label">NIC Number</label>
                    <input type="text" class="form-input" id="nic-number" required placeholder="Enter NIC number (e.g., 199512345678)">
                </div>
                <div class="form-group">
                    <label class="form-label">User Name</label>
                    <input type="text" class="form-input" id="nic-user-name" required>
                </div>
                <div id="validation-results" style="display: none; margin-top: 1rem; padding: 1rem; border-radius: 8px;">
                    <h4 style="margin-bottom: 0.5rem;">Validation Results:</h4>
                    <div id="validation-details"></div>
                </div>
                <div class="action-buttons" style="margin-top: 2rem;">
                    <button type="submit" class="btn btn-primary">
                        <span class="loading" id="nic-form-loading" style="display: none;"></span>
                        <span id="nic-form-text">Validate NIC</span>
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="closeModal('nic-modal')">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Eligibility Update Modal -->
    <div id="eligibility-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Update Donor Eligibility</h3>
                <button class="modal-close" onclick="closeModal('eligibility-modal')">&times;</button>
            </div>
            <form id="eligibility-form">
                <div class="form-group">
                    <label class="form-label">Donor</label>
                    <select class="form-select" id="eligibility-donor" required>
                        <option value="">Select Donor</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Eligibility Status</label>
                    <select class="form-select" id="new-eligibility-status" required>
                        <option value="">Select Status</option>
                        <option value="eligible">Eligible</option>
                        <option value="temp-ineligible">Temporarily Ineligible</option>
                        <option value="perm-ineligible">Permanently Ineligible</option>
                        <option value="under-review">Under Review</option>
                    </select>
                </div>
                <div class="form-group" id="eligibility-reason-group" style="display: none;">
                    <label class="form-label">Reason for Ineligibility</label>
                    <textarea class="form-textarea" id="eligibility-reason" placeholder="Provide detailed reason for ineligibility status..."></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Assessment Notes</label>
                    <textarea class="form-textarea" id="assessment-notes" placeholder="Add any additional notes about the assessment..."></textarea>
                </div>
                <div class="action-buttons" style="margin-top: 2rem;">
                    <button type="submit" class="btn btn-primary">
                        <span class="loading" id="eligibility-form-loading" style="display: none;"></span>
                        <span id="eligibility-form-text">Update Status</span>
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="closeModal('eligibility-modal')">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="notification">
        <div id="toast-message">Operation completed successfully.</div>
    </div>

    <script>
        // Application State
        const appState = {
            currentSection: 'dashboard',
            users: [
                { id: 1, name: 'John Doe', email: 'john.doe@email.com', role: 'donor', status: 'active', registrationDate: '2024-01-15', bloodType: 'O+', eligibility: 'eligible' },
                { id: 2, name: 'Jane Smith', email: 'jane.smith@email.com', role: 'patient', status: 'pending', registrationDate: '2024-02-20', bloodType: 'A-', eligibility: 'under-review' },
                { id: 3, name: 'City Hospital', email: 'admin@cityhospital.com', role: 'hospital', status: 'suspended', registrationDate: '2024-01-10', bloodType: null, eligibility: null },
                { id: 4, name: 'Sarah Wilson', email: 'sarah.w@email.com', role: 'donor', status: 'active', registrationDate: '2024-02-15', bloodType: 'B+', eligibility: 'eligible' },
                { id: 5, name: 'Mike Johnson', email: 'mike.j@email.com', role: 'patient', status: 'active', registrationDate: '2024-02-10', bloodType: 'AB-', eligibility: null },
                { id: 6, name: 'LifeConnect Foundation', email: 'contact@lifeconnect.org', role: 'financial', status: 'active', registrationDate: '2024-01-05', bloodType: null, eligibility: null }
            ],
            documents: [
                { id: 'DOC-001-2024', userId: 1, userName: 'John Doe', type: 'nic', status: 'pending', submissionDate: '2024-02-25 14:30' },
                { id: 'DOC-002-2024', userId: 2, userName: 'Jane Smith', type: 'medical', status: 'pending', submissionDate: '2024-02-25 16:45' },
                { id: 'DOC-003-2024', userId: 4, userName: 'Sarah Wilson', type: 'guardian', status: 'approved', submissionDate: '2024-02-24 11:20' },
                { id: 'DOC-004-2024', userId: 5, userName: 'Mike Johnson', type: 'address', status: 'rejected', submissionDate: '2024-02-23 09:15' }
            ],
            notifications: [
                { id: 1, recipient: 'John Doe', subject: 'Document Approval Confirmation', type: 'approval', status: 'delivered', sentDate: '2024-02-26 09:15' },
                { id: 2, recipient: 'Jane Smith', subject: 'Document Rejection - Missing Information', type: 'rejection', status: 'delivered', sentDate: '2024-02-26 08:30' },
                { id: 3, recipient: 'City Hospital', subject: 'Account Status Update', type: 'update', status: 'pending', sentDate: '2024-02-26 07:45' }
            ],
            nicValidations: [
                { id: 'VAL-001', name: 'Thilini Perera', nic: '199512345678', age: 28, status: 'validated', date: '2024-02-26 09:15' },
                { id: 'VAL-002', name: 'Kasun Silva', nic: '200512345679', age: 19, status: 'age-restricted', date: '2024-02-26 08:45' },
                { id: 'VAL-003', name: 'Anu Wickramasinghe', nic: '201012345680', age: 14, status: 'guardian-required', date: '2024-02-26 07:30' },
                { id: 'VAL-004', name: 'Invalid Entry', nic: '123456789ABC', age: null, status: 'failed', date: '2024-02-26 06:15' }
            ],
            selectedUsers: [],
            selectedDocuments: [],
            selectedEligibility: []
        };

        // Navigation Functions
        function showContent(sectionId) {
            // Hide all sections
            document.querySelectorAll('.content-section').forEach(section => {
                section.style.display = 'none';
            });

            // Show selected section
            document.getElementById(sectionId).style.display = 'block';
            appState.currentSection = sectionId;

            // Update active menu item
            document.querySelectorAll('.menu-item').forEach(item => {
                item.classList.remove('active');
            });
            event.target.classList.add('active');

            // Load section-specific data
            loadSectionData(sectionId);
        }

        // Data Loading Functions
        function loadSectionData(sectionId) {
            switch(sectionId) {
                case 'dashboard':
                    break;
                case 'accounts':
                    renderUsersTable();
                    break;
                case 'documents':
                    renderDocumentsTable();
                    break;
                case 'notifications':
                    renderNotificationsTable();
                    break;
                case 'eligibility':
                    renderEligibilityTable();
                    break;
                case 'roles':
                    updateRoleCounts();
                    break;
                case 'nic-validation':
                    renderNICValidationsTable();
                    break;
            }
        }

        // User Account Management Functions
        function renderUsersTable() {
            const tableContent = document.getElementById('users-table');
            const headerRow = tableContent.querySelector('.table-row');
            
            // Clear existing rows except header
            tableContent.innerHTML = '';
            tableContent.appendChild(headerRow);

            appState.users.forEach(user => {
                const row = document.createElement('div');
                row.className = 'table-row';
                row.innerHTML = `
                    <div class="table-cell name" data-label="User">
                        <input type="checkbox" class="user-checkbox" data-user-id="${user.id}">
                        <span style="margin-left: 0.5rem;">
                            <strong>${user.name}</strong><br>
                            <small>${user.email}</small>
                        </span>
                    </div>
                    <div class="table-cell" data-label="Role">${formatRole(user.role)}</div>
                    <div class="table-cell status" data-label="Status">
                        <span class="status-badge status-${user.status}">${formatStatus(user.status)}</span>
                    </div>
                    <div class="table-cell" data-label="Registration">${user.registrationDate}</div>
                    <div class="table-cell" data-label="Actions">
                        <button class="btn btn-secondary btn-small" onclick="editUser(${user.id})">Edit</button>
                        ${user.status === 'active' ? 
                            `<button class="btn btn-danger btn-small" onclick="suspendUser(${user.id})">Suspend</button>` :
                            user.status === 'suspended' ?
                            `<button class="btn btn-success btn-small" onclick="activateUser(${user.id})">Activate</button>` :
                            `<button class="btn btn-success btn-small" onclick="activateUser(${user.id})">Activate</button>`
                        }
                    </div>
                `;
                tableContent.appendChild(row);
            });

            // Add event listeners to checkboxes
            document.querySelectorAll('.user-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', updateSelectedUsers);
            });
        }

        function showUserModal(action, userId = null) {
            const modal = document.getElementById('user-modal');
            const title = document.getElementById('user-modal-title');
            
            if (action === 'add') {
                title.textContent = 'Add New User';
                document.getElementById('user-form').reset();
            } else if (action === 'edit' && userId) {
                title.textContent = 'Edit User';
                const user = appState.users.find(u => u.id === userId);
                if (user) {
                    document.getElementById('user-name').value = user.name;
                    document.getElementById('user-email').value = user.email;
                    document.getElementById('user-phone').value = '+94771234567'; // Default phone
                    document.getElementById('user-nic').value = '199512345678'; // Default NIC
                    document.getElementById('user-role').value = user.role;
                    document.getElementById('user-status').value = user.status;
                }
            }
            
            modal.classList.add('show');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('show');
        }

        function toggleSelectAll() {
            const selectAll = document.getElementById('select-all');
            const checkboxes = document.querySelectorAll('.user-checkbox');
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAll.checked;
            });
            
            updateSelectedUsers();
        }

        function updateSelectedUsers() {
            const checkboxes = document.querySelectorAll('.user-checkbox:checked');
            appState.selectedUsers = Array.from(checkboxes).map(cb => parseInt(cb.dataset.userId));
            updateBulkButtons(['bulk-activate', 'bulk-deactivate', 'bulk-suspend']);
        }

        function updateBulkButtons(buttonIds) {
            buttonIds.forEach(id => {
                const button = document.getElementById(id);
                if (button) {
                     // Disable bulk buttons unless more than one user is selected
                    button.disabled = appState.selectedUsers.length <= 1;
                }
            });
        }

        function editUser(userId) {
            showUserModal('edit', userId);
        }


        function activateUser(userId) {
            const user = appState.users.find(u => u.id === userId);
            if (user) {
                user.status = 'active';
                renderUsersTable();
                showToast('success', `${user.name} has been activated successfully.`);
                
                // Send notification
                sendUserNotification(user, 'Account Activated', 'Your account has been activated and you can now access all features.');
            }
        }

        function suspendUser(userId) {
            if (confirm('Are you sure you want to suspend this user?')) {
                const user = appState.users.find(u => u.id === userId);
                if (user) {
                    user.status = 'suspended';
                    renderUsersTable();
                    showToast('warning', `${user.name} has been suspended.`);
                    
                    // Send notification
                    sendUserNotification(user, 'Account Suspended', 'Your account has been suspended. Please contact support for assistance.');
                }
            }
        }

    function bulkActivate() {
    if (appState.selectedUsers.length === 0) return;
    
    // Count how many users are actually not active
    const usersToActivate = appState.selectedUsers.filter(userId => {
        const user = appState.users.find(u => u.id === userId);
        return user && user.status !== 'active';
    });
    
    if (usersToActivate.length === 0) {
        showToast('info', 'All selected users are already active.');
        return;
    }
    
    if (confirm(`Activate ${usersToActivate.length} user(s)? ${appState.selectedUsers.length - usersToActivate.length} users are already active.`)) {
        usersToActivate.forEach(userId => {
            const user = appState.users.find(u => u.id === userId);
            if (user) {
                user.status = 'active';
                sendUserNotification(user, 'Account Activated', 'Your account has been activated and you can now access all features.');
            }
        });
        renderUsersTable();
        showToast('success', `${usersToActivate.length} users activated successfully.`);
        appState.selectedUsers = [];
        document.getElementById('select-all').checked = false;
        updateBulkButtons(['bulk-activate', 'bulk-deactivate', 'bulk-suspend']);
    }
}

function bulkDeactivate() {
    if (appState.selectedUsers.length === 0) return;
    
    const usersToDeactivate = appState.selectedUsers.filter(userId => {
        const user = appState.users.find(u => u.id === userId);
        return user && user.status !== 'pending';
    });
    
    if (usersToDeactivate.length === 0) {
        showToast('info', 'All selected users are already deactivated.');
        return;
    }
    
    if (confirm(`Deactivate ${usersToDeactivate.length} user(s)? ${appState.selectedUsers.length - usersToDeactivate.length} users are already deactivated.`)) {
        usersToDeactivate.forEach(userId => {
            const user = appState.users.find(u => u.id === userId);
            if (user) user.status = 'pending';
        });
        renderUsersTable();
        showToast('warning', `${usersToDeactivate.length} users deactivated.`);
        appState.selectedUsers = [];
        document.getElementById('select-all').checked = false;
        updateBulkButtons(['bulk-activate', 'bulk-deactivate', 'bulk-suspend']);
    }
}

function bulkSuspend() {
    if (appState.selectedUsers.length === 0) return;
    
    const usersToSuspend = appState.selectedUsers.filter(userId => {
        const user = appState.users.find(u => u.id === userId);
        return user && user.status !== 'suspended';
    });
    
    if (usersToSuspend.length === 0) {
        showToast('info', 'All selected users are already suspended.');
        return;
    }
    
    if (confirm(`Suspend ${usersToSuspend.length} user(s)? ${appState.selectedUsers.length - usersToSuspend.length} users are already suspended.`)) {
        usersToSuspend.forEach(userId => {
            const user = appState.users.find(u => u.id === userId);
            if (user) user.status = 'suspended';
        });
        renderUsersTable();
        showToast('warning', `${usersToSuspend.length} users suspended.`);
        appState.selectedUsers = [];
        document.getElementById('select-all').checked = false;
        updateBulkButtons(['bulk-activate', 'bulk-deactivate', 'bulk-suspend']);
    }
}

        // Document Verification Functions
        function renderDocumentsTable() {
            const tableContent = document.getElementById('documents-table');
            const headerRow = tableContent.querySelector('.table-row');
            
            tableContent.innerHTML = '';
            tableContent.appendChild(headerRow);

            appState.documents.forEach(doc => {
                const row = document.createElement('div');
                row.className = 'table-row';
                row.innerHTML = `
                    <div class="table-cell name" data-label="Document">
                        <input type="checkbox" class="doc-checkbox" data-doc-id="${doc.id}">
                        <span style="margin-left: 0.5rem;">
                            <strong>${doc.id}</strong><br>
                            <small>${doc.userName} - ${formatDocType(doc.type)}</small>
                        </span>
                    </div>
                    <div class="table-cell" data-label="Type">${formatDocType(doc.type)}</div>
                    <div class="table-cell status" data-label="Status">
                        <span class="status-badge status-${doc.status}">${formatStatus(doc.status)}</span>
                    </div>
                    <div class="table-cell" data-label="Submitted">${doc.submissionDate}</div>
                    <div class="table-cell" data-label="Actions">
                        ${doc.status === 'pending' ? `
                            <button class="btn btn-success btn-small" onclick="approveDocument('${doc.id}')">Approve</button>
                            <button class="btn btn-danger btn-small" onclick="rejectDocument('${doc.id}')">Reject</button>
                        ` : `
                            <button class="btn btn-secondary btn-small" onclick="viewDocument('${doc.id}')">View</button>
                        `}
                    </div>
                `;
                tableContent.appendChild(row);
            });

            // Add event listeners
            document.querySelectorAll('.doc-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', updateSelectedDocuments);
            });
        }

        function toggleSelectAllDocs() {
            const selectAll = document.getElementById('select-all-docs');
            const checkboxes = document.querySelectorAll('.doc-checkbox');
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAll.checked;
            });
            
            updateSelectedDocuments();
        }

        function updateSelectedDocuments() {
            const checkboxes = document.querySelectorAll('.doc-checkbox:checked');
            appState.selectedDocuments = Array.from(checkboxes).map(cb => cb.dataset.docId);
            updateBulkDocButtons();
        }

        function updateBulkDocButtons() {
            const approveBtn = document.getElementById('bulk-approve-docs');
            const rejectBtn = document.getElementById('bulk-reject-docs');
            
            if (approveBtn) approveBtn.disabled = appState.selectedDocuments.length === 0;
            if (rejectBtn) rejectBtn.disabled = appState.selectedDocuments.length === 0;
        }

        function approveDocument(docId) {
            const doc = appState.documents.find(d => d.id === docId);
            if (doc) {
                doc.status = 'approved';
                renderDocumentsTable();
                
                const user = appState.users.find(u => u.id === doc.userId);
                if (user) {
                    sendUserNotification(user, 'Document Approved', `Your ${formatDocType(doc.type)} has been approved.`);
                }
                
                showToast('success', `Document ${docId} approved successfully.`);
            }
        }

        function rejectDocument(docId) {
            const reason = prompt('Please provide reason for rejection:');
            if (reason) {
                const doc = appState.documents.find(d => d.id === docId);
                if (doc) {
                    doc.status = 'rejected';
                    renderDocumentsTable();
                    
                    const user = appState.users.find(u => u.id === doc.userId);
                    if (user) {
                        sendUserNotification(user, 'Document Rejected', `Your ${formatDocType(doc.type)} has been rejected. Reason: ${reason}`);
                    }
                    
                    showToast('warning', `Document ${docId} rejected.`);
                }
            }
        }

        function bulkApproveDocuments() {
            if (appState.selectedDocuments.length === 0) return;
            
            if (confirm(`Approve ${appState.selectedDocuments.length} document(s)?`)) {
                appState.selectedDocuments.forEach(docId => {
                    const doc = appState.documents.find(d => d.id === docId);
                    if (doc) {
                        doc.status = 'approved';
                        const user = appState.users.find(u => u.id === doc.userId);
                        if (user) {
                            sendUserNotification(user, 'Document Approved', `Your ${formatDocType(doc.type)} has been approved.`);
                        }
                    }
                });
                renderDocumentsTable();
                showToast('success', `${appState.selectedDocuments.length} documents approved successfully.`);
                appState.selectedDocuments = [];
                document.getElementById('select-all-docs').checked = false;
            }
        }

        function bulkRejectDocuments() {
            if (appState.selectedDocuments.length === 0) return;
            
            const reason = prompt('Please provide reason for rejection:');
            if (reason) {
                appState.selectedDocuments.forEach(docId => {
                    const doc = appState.documents.find(d => d.id === docId);
                    if (doc) {
                        doc.status = 'rejected';
                        const user = appState.users.find(u => u.id === doc.userId);
                        if (user) {
                            sendUserNotification(user, 'Document Rejected', `Your ${formatDocType(doc.type)} has been rejected. Reason: ${reason}`);
                        }
                    }
                });
                renderDocumentsTable();
                showToast('warning', `${appState.selectedDocuments.length} documents rejected.`);
                appState.selectedDocuments = [];
                document.getElementById('select-all-docs').checked = false;
            }
        }

        function viewDocument(docId) {
            showToast('info', `Opening document ${docId} for review...`);
        }

        function exportDocumentReport() {
            showToast('info', 'Document verification report is being prepared.');
        }

        // Notification Functions
        function showNotificationModal(action) {
            const modal = document.getElementById('notification-modal');
            modal.classList.add('show');
        }

        function renderNotificationsTable() {
            const tableContent = document.getElementById('notifications-table');
            const headerRow = tableContent.querySelector('.table-row');
            
            tableContent.innerHTML = '';
            tableContent.appendChild(headerRow);

            appState.notifications.forEach(notification => {
                const row = document.createElement('div');
                row.className = 'table-row';
                row.innerHTML = `
                    <div class="table-cell name" data-label="Notification">
                        <strong>${notification.recipient}</strong><br>
                        <small>${notification.subject}</small>
                    </div>
                    <div class="table-cell" data-label="Type">${formatNotificationType(notification.type)}</div>
                    <div class="table-cell status" data-label="Status">
                        <span class="status-badge status-${notification.status}">${formatStatus(notification.status)}</span>
                    </div>
                    <div class="table-cell" data-label="Sent">${notification.sentDate}</div>
                    <div class="table-cell" data-label="Actions">
                        <button class="btn btn-secondary btn-small" onclick="viewNotification(${notification.id})">View</button>
                        ${notification.status === 'pending' ? 
                            `<button class="btn btn-primary btn-small" onclick="resendNotification(${notification.id})">Resend</button>` :
                            `<button class="btn btn-secondary btn-small" onclick="resendNotification(${notification.id})">Resend</button>`
                        }
                    </div>
                `;
                tableContent.appendChild(row);
            });
        }

        function sendUserNotification(user, subject, message) {
            const newNotification = {
                id: appState.notifications.length + 1,
                recipient: user.name,
                subject: subject,
                type: subject.toLowerCase().includes('reject') ? 'rejection' : 'approval',
                status: 'delivered',
                sentDate: new Date().toISOString().slice(0, 16).replace('T', ' ')
            };
            
            appState.notifications.unshift(newNotification);
            
            if (appState.currentSection === 'notifications') {
                renderNotificationsTable();
            }
        }

        function sendApprovalNotifications() {
            const approvedDocs = appState.documents.filter(d => d.status === 'approved');
            let count = 0;
            
            approvedDocs.forEach(doc => {
                const user = appState.users.find(u => u.id === doc.userId);
                if (user) {
                    sendUserNotification(user, 'Document Approval Confirmation', `Your ${formatDocType(doc.type)} has been approved.`);
                    count++;
                }
            });
            
            showToast('success', `${count} approval notifications sent.`);
        }

        function sendRejectionNotifications() {
            const rejectedDocs = appState.documents.filter(d => d.status === 'rejected');
            let count = 0;
            
            rejectedDocs.forEach(doc => {
                const user = appState.users.find(u => u.id === doc.userId);
                if (user) {
                    sendUserNotification(user, 'Document Rejection Notice', `Your ${formatDocType(doc.type)} has been rejected. Please resubmit with corrections.`);
                    count++;
                }
            });
            
            showToast('warning', `${count} rejection notifications sent.`);
        }

        function sendReminderNotifications() {
            const pendingUsers = appState.users.filter(u => u.status === 'pending');
            let count = 0;
            
            pendingUsers.forEach(user => {
                sendUserNotification(user, 'Account Activation Reminder', 'Please complete your registration to activate your account.');
                count++;
            });
            
            showToast('info', `${count} reminder notifications sent.`);
        }

        function viewNotification(notificationId) {
            const notification = appState.notifications.find(n => n.id === notificationId);
            if (notification) {
                alert(`Notification Details:\nTo: ${notification.recipient}\nSubject: ${notification.subject}\nType: ${notification.type}\nStatus: ${notification.status}`);
            }
        }

        function resendNotification(notificationId) {
            const notification = appState.notifications.find(n => n.id === notificationId);
            if (notification) {
                notification.status = 'delivered';
                notification.sentDate = new Date().toISOString().slice(0, 16).replace('T', ' ');
                renderNotificationsTable();
                showToast('success', `Notification resent to ${notification.recipient}.`);
            }
        }

        // Donor Eligibility Functions
        function renderEligibilityTable() {
            const tableContent = document.getElementById('eligibility-table');
            const headerRow = tableContent.querySelector('.table-row');
            
            tableContent.innerHTML = '';
            tableContent.appendChild(headerRow);

            const donors = appState.users.filter(u => u.role === 'donor');
            donors.forEach(donor => {
                const row = document.createElement('div');
                row.className = 'table-row';
                row.innerHTML = `
                    <div class="table-cell name" data-label="Donor">
                        <input type="checkbox" class="eligibility-checkbox" data-donor-id="${donor.id}">
                        <span style="margin-left: 0.5rem;">
                            <strong>${donor.name}</strong><br>
                            <small>ID: DNR-${String(donor.id).padStart(3, '0')} • Blood Type: ${donor.bloodType}</small>
                        </span>
                    </div>
                    <div class="table-cell" data-label="Blood Type">${donor.bloodType}</div>
                    <div class="table-cell status" data-label="Status">
                        <span class="status-badge status-${donor.eligibility || 'pending'}">${formatEligibility(donor.eligibility)}</span>
                    </div>
                    <div class="table-cell" data-label="Assessment">${donor.registrationDate}</div>
                    <div class="table-cell" data-label="Actions">
                        <button class="btn btn-primary btn-small" onclick="updateDonorEligibility(${donor.id})">Update Status</button>
                        <button class="btn btn-secondary btn-small" onclick="viewDonorDetails(${donor.id})">View Details</button>
                    </div>
                `;
                tableContent.appendChild(row);
            });

            // Add event listeners
            document.querySelectorAll('.eligibility-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', updateSelectedEligibility);
            });
        }

        function showEligibilityModal() {
            const modal = document.getElementById('eligibility-modal');
            const donorSelect = document.getElementById('eligibility-donor');
            
            // Populate donor dropdown
            donorSelect.innerHTML = '<option value="">Select Donor</option>';
            const donors = appState.users.filter(u => u.role === 'donor');
            donors.forEach(donor => {
                const option = document.createElement('option');
                option.value = donor.id;
                option.textContent = `${donor.name} (${donor.bloodType})`;
                donorSelect.appendChild(option);
            });
            
            modal.classList.add('show');
        }

        function toggleSelectAllEligibility() {
            const selectAll = document.getElementById('select-all-eligibility');
            const checkboxes = document.querySelectorAll('.eligibility-checkbox');
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAll.checked;
            });
            
            updateSelectedEligibility();
        }

        function updateSelectedEligibility() {
            const checkboxes = document.querySelectorAll('.eligibility-checkbox:checked');
            appState.selectedEligibility = Array.from(checkboxes).map(cb => parseInt(cb.dataset.donorId));
            updateEligibilityBulkButtons();
        }

        function updateEligibilityBulkButtons() {
            const buttons = ['bulk-approve-eligibility', 'bulk-temp-ineligible', 'bulk-perm-ineligible'];
            buttons.forEach(id => {
                const button = document.getElementById(id);
                if (button) {
                    button.disabled = appState.selectedEligibility.length === 0;
                }
            });
        }

        function updateDonorEligibility(donorId) {
            const donor = appState.users.find(u => u.id === donorId);
            if (donor) {
                document.getElementById('eligibility-donor').value = donorId;
                showEligibilityModal();
            }
        }

        function bulkApproveEligibility() {
            if (appState.selectedEligibility.length === 0) return;
            
            if (confirm(`Mark ${appState.selectedEligibility.length} donor(s) as eligible?`)) {
                appState.selectedEligibility.forEach(donorId => {
                    const donor = appState.users.find(u => u.id === donorId);
                    if (donor) {
                        donor.eligibility = 'eligible';
                        sendUserNotification(donor, 'Eligibility Status Update', 'You have been approved as an eligible donor.');
                    }
                });
                renderEligibilityTable();
                showToast('success', `${appState.selectedEligibility.length} donors marked as eligible.`);
                appState.selectedEligibility = [];
                document.getElementById('select-all-eligibility').checked = false;
            }
        }

        function bulkTempIneligible() {
            if (appState.selectedEligibility.length === 0) return;
            
            const reason = prompt('Please provide reason for temporary ineligibility:');
            if (reason) {
                appState.selectedEligibility.forEach(donorId => {
                    const donor = appState.users.find(u => u.id === donorId);
                    if (donor) {
                        donor.eligibility = 'temp-ineligible';
                        sendUserNotification(donor, 'Temporary Ineligibility Notice', `You are temporarily ineligible for donation. Reason: ${reason}`);
                    }
                });
                renderEligibilityTable();
                showToast('warning', `${appState.selectedEligibility.length} donors marked as temporarily ineligible.`);
                appState.selectedEligibility = [];
                document.getElementById('select-all-eligibility').checked = false;
            }
        }

        function bulkPermIneligible() {
            if (appState.selectedEligibility.length === 0) return;
            
            const reason = prompt('Please provide reason for permanent ineligibility:');
            if (reason && confirm('This action cannot be undone. Proceed?')) {
                appState.selectedEligibility.forEach(donorId => {
                    const donor = appState.users.find(u => u.id === donorId);
                    if (donor) {
                        donor.eligibility = 'perm-ineligible';
                        sendUserNotification(donor, 'Permanent Ineligibility Notice', `You are permanently ineligible for donation. Reason: ${reason}`);
                    }
                });
                renderEligibilityTable();
                showToast('error', `${appState.selectedEligibility.length} donors marked as permanently ineligible.`);
                appState.selectedEligibility = [];
                document.getElementById('select-all-eligibility').checked = false;
            }
        }

        function viewDonorDetails(donorId) {
            const donor = appState.users.find(u => u.id === donorId);
            if (donor) {
                alert(`Donor Details:\nName: ${donor.name}\nEmail: ${donor.email}\nBlood Type: ${donor.bloodType}\nEligibility: ${formatEligibility(donor.eligibility)}\nRegistered: ${donor.registrationDate}`);
            }
        }

        // Role Management Functions
        function updateRoleCounts() {
            const roleCounts = {
                donor: appState.users.filter(u => u.role === 'donor').length,
                patient: appState.users.filter(u => u.role === 'patient').length,
                hospital: appState.users.filter(u => u.role === 'hospital').length,
                financial: appState.users.filter(u => u.role === 'financial').length
            };

            document.getElementById('donor-count').textContent = `${roleCounts.donor} Active`;
            document.getElementById('patient-count').textContent = `${roleCounts.patient} Active`;
            document.getElementById('hospital-count').textContent = `${roleCounts.hospital} Active`;
            document.getElementById('financial-count').textContent = `${roleCounts.financial} Active`;
        }

        function showRoleModal() {
            showToast('info', 'Role assignment feature coming soon...');
        }

        function bulkRoleUpdate() {
            showToast('info', 'Bulk role update feature coming soon...');
        }

        function managePermissions() {
            showToast('info', 'Permission management feature coming soon...');
        }

        // NIC Validation Functions
        function renderNICValidationsTable() {
            const tableContent = document.getElementById('nic-validations-table');
            const headerRow = tableContent.querySelector('.table-row');
            
            tableContent.innerHTML = '';
            tableContent.appendChild(headerRow);

            appState.nicValidations.forEach(validation => {
                const row = document.createElement('div');
                row.className = 'table-row';
                row.innerHTML = `
                    <div class="table-cell name" data-label="User">
                        <strong>${validation.name}</strong><br>
                        <small>NIC: ${validation.nic}</small>
                    </div>
                    <div class="table-cell" data-label="Age">${validation.age ? validation.age + ' years' : 'Unknown'}</div>
                    <div class="table-cell status" data-label="Status">
                        <span class="status-badge status-${getValidationStatusClass(validation.status)}">${formatValidationStatus(validation.status)}</span>
                    </div>
                    <div class="table-cell" data-label="Validated">${validation.date}</div>
                    <div class="table-cell" data-label="Actions">
                        ${validation.status === 'failed' ? 
                            `<button class="btn btn-primary btn-small" onclick="retryValidation('${validation.id}')">Retry</button>` :
                            validation.status === 'guardian-required' ?
                            `<button class="btn btn-primary btn-small" onclick="requestGuardianConsent('${validation.id}')">Request Guardian</button>` :
                            `<button class="btn btn-secondary btn-small" onclick="viewValidationDetails('${validation.id}')">View Details</button>`
                        }
                    </div>
                `;
                tableContent.appendChild(row);
            });
        }

        function showNICValidationModal() {
            const modal = document.getElementById('nic-modal');
            modal.classList.add('show');
        }

        function validateNIC(nic, userName) {
            // Simulate NIC validation with Election Commission API
            return new Promise((resolve) => {
                setTimeout(() => {
                    if (nic.match(/^\d{9}[vVxX]$/) || nic.match(/^\d{12}$/)) {
                        const age = calculateAgeFromNIC(nic);
                        const validation = {
                            success: true,
                            age: age,
                            status: age < 18 ? 'guardian-required' : age < 21 ? 'age-restricted' : 'validated',
                            message: age < 18 ? 'Guardian consent required for under 18' : 
                                    age < 21 ? 'Age restricted for donation (under 21)' : 
                                    'NIC validated successfully'
                        };
                        resolve(validation);
                    } else {
                        resolve({
                            success: false,
                            age: null,
                            status: 'failed',
                            message: 'Invalid NIC format'
                        });
                    }
                }, 1500);
            });
        }

        function calculateAgeFromNIC(nic) {
            // Simple age calculation from Sri Lankan NIC
            let year, days;
            
            if (nic.length === 10) {
                year = 1900 + parseInt(nic.substr(0, 2));
                days = parseInt(nic.substr(2, 3));
            } else {
                year = parseInt(nic.substr(0, 4));
                days = parseInt(nic.substr(4, 3));
            }
            
            if (days > 500) days -= 500; // Female adjustment
            
            const currentYear = new Date().getFullYear();
            return currentYear - year;
        }

        function retryFailedValidations() {
            const failedValidations = appState.nicValidations.filter(v => v.status === 'failed');
            let successCount = 0;
            
            failedValidations.forEach(validation => {
                // Simulate retry success for some
                if (Math.random() > 0.3) {
                    validation.status = 'validated';
                    successCount++;
                }
            });
            
            renderNICValidationsTable();
            showToast('info', `${successCount} of ${failedValidations.length} failed validations were successful on retry.`);
        }

        function retryValidation(validationId) {
            const validation = appState.nicValidations.find(v => v.id === validationId);
            if (validation) {
                showToast('info', `Retrying validation for ${validation.name}...`);
                setTimeout(() => {
                    validation.status = Math.random() > 0.5 ? 'validated' : 'failed';
                    renderNICValidationsTable();
                    showToast(validation.status === 'validated' ? 'success' : 'error', 
                             validation.status === 'validated' ? 'Validation successful!' : 'Validation failed again.');
                }, 1500);
            }
        }

        function requestGuardianConsent(validationId) {
            const validation = appState.nicValidations.find(v => v.id === validationId);
            if (validation) {
                showToast('info', `Guardian consent request sent for ${validation.name}.`);
                // Add notification
                const newNotification = {
                    id: appState.notifications.length + 1,
                    recipient: validation.name,
                    subject: 'Guardian Consent Required',
                    type: 'guardian',
                    status: 'delivered',
                    sentDate: new Date().toISOString().slice(0, 16).replace('T', ' ')
                };
                appState.notifications.unshift(newNotification);
            }
        }

        function viewValidationDetails(validationId) {
            const validation = appState.nicValidations.find(v => v.id === validationId);
            if (validation) {
                alert(`Validation Details:\nName: ${validation.name}\nNIC: ${validation.nic}\nAge: ${validation.age || 'Unknown'}\nStatus: ${formatValidationStatus(validation.status)}\nDate: ${validation.date}`);
            }
        }

        function manageGuardianRequests() {
            const guardianRequired = appState.nicValidations.filter(v => v.status === 'guardian-required');
            showToast('info', `${guardianRequired.length} users require guardian consent.`);
        }

        function exportValidationReport() {
            showToast('info', 'NIC validation report is being prepared.');
        }

        // Utility Functions
        function formatRole(role) {
            const roleMap = {
                'donor': 'Donor',
                'patient': 'Patient', 
                'hospital': 'Hospital',
                'financial': 'Financial Donor'
            };
            return roleMap[role] || role;
        }

        function formatStatus(status) {
            const statusMap = {
                'active': 'Active',
                'pending': 'Pending',
                'suspended': 'Suspended',
                'approved': 'Approved',
                'rejected': 'Rejected',
                'delivered': 'Delivered'
            };
            return statusMap[status] || status;
        }

        function formatDocType(type) {
            const typeMap = {
                'nic': 'NIC Document',
                'medical': 'Medical Certificate',
                'address': 'Address Proof',
                'guardian': 'Guardian Document'
            };
            return typeMap[type] || type;
        }

        function formatNotificationType(type) {
            const typeMap = {
                'approval': 'Approval',
                'rejection': 'Rejection',
                'update': 'Status Update',
                'reminder': 'Reminder',
                'welcome': 'Welcome'
            };
            return typeMap[type] || type;
        }

        function formatEligibility(eligibility) {
            const eligibilityMap = {
                'eligible': 'Eligible',
                'temp-ineligible': 'Temporarily Ineligible',
                'perm-ineligible': 'Permanently Ineligible',
                'under-review': 'Under Review'
            };
            return eligibilityMap[eligibility] || 'Not Assessed';
        }

        function formatValidationStatus(status) {
            const statusMap = {
                'validated': 'Validated',
                'failed': 'Validation Failed',
                'guardian-required': 'Guardian Required',
                'age-restricted': 'Age Restricted'
            };
            return statusMap[status] || status;
        }

        function getValidationStatusClass(status) {
            const classMap = {
                'validated': 'active',
                'failed': 'suspended',
                'guardian-required': 'pending',
                'age-restricted': 'suspended'
            };
            return classMap[status] || 'pending';
        }

        function showToast(type, message) {
            const toast = document.getElementById('toast');
            const messageEl = document.getElementById('toast-message');
            
            messageEl.textContent = message;
            toast.className = `notification ${type} show`;
            
            setTimeout(() => {
                toast.classList.remove('show');
            }, 4000);
        }

        // Form Handlers
document.getElementById('user-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = {
        name: document.getElementById('user-name').value,
        email: document.getElementById('user-email').value,
        phone: document.getElementById('user-phone').value,
        nic: document.getElementById('user-nic').value,
        role: document.getElementById('user-role').value,
        status: document.getElementById('user-status').value
    };

    // Add validation for email and phone
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const phoneRegex = /^[0-9]{10}$/;
    
    if (!emailRegex.test(formData.email)) {
        showToast('error', 'Please enter a valid email address.');
        return;
    }
    
    if (!phoneRegex.test(formData.phone.replace(/\D/g, ''))) {
        showToast('error', 'Please enter a valid 10-digit phone number.');
        return;
    }

    const loading = document.getElementById('user-form-loading');
    const text = document.getElementById('user-form-text');
    
    loading.style.display = 'inline-block';
    text.textContent = 'Saving...';

    // Validate NIC first
    validateNIC(formData.nic, formData.name).then(nicResult => {
        setTimeout(() => {
            if (nicResult.success) {
                // Check if this is an edit operation by looking at the modal title
                const modalTitle = document.getElementById('user-modal-title').textContent;
                const isEdit = modalTitle.includes('Edit');
                
                if (isEdit) {
                    // Find and update existing user (you'll need to track which user is being edited)
                    // For now, just show the updated message
                    closeModal('user-modal');
                    showToast('success', `User ${formData.name} has been updated successfully.`);
                } else {
                    // Add new user logic
                    const newUser = {
                        id: appState.users.length + 1,
                        name: formData.name,
                        email: formData.email,
                        role: formData.role,
                        status: formData.status,
                        registrationDate: new Date().toISOString().slice(0, 10),
                        bloodType: ['O+', 'A+', 'B+', 'AB+', 'O-', 'A-', 'B-', 'AB-'][Math.floor(Math.random() * 8)],
                        eligibility: formData.role === 'donor' ? 'under-review' : null
                    };
                    
                    appState.users.push(newUser);
                    
                    // Add NIC validation record
                    appState.nicValidations.push({
                        id: `VAL-${String(appState.nicValidations.length + 1).padStart(3, '0')}`,
                        name: formData.name,
                        nic: formData.nic,
                        age: nicResult.age,
                        status: nicResult.status,
                        date: new Date().toISOString().slice(0, 16).replace('T', ' ')
                    });

                    if (appState.currentSection === 'accounts') {
                        renderUsersTable();
                    }
                    
                    closeModal('user-modal');
                    showToast('success', `User ${formData.name} has been added successfully.`);
                    
                    // Send welcome notification
                    sendUserNotification(newUser, 'Welcome to LifeConnect', 'Your account has been created successfully.');
                }
            } else {
                showToast('error', `NIC validation failed: ${nicResult.message}`);
            }
            
            loading.style.display = 'none';
            text.textContent = 'Save User';
        }, 1000);
    });
});
        document.getElementById('notification-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const recipient = document.getElementById('notification-recipient').value;
            const type = document.getElementById('notification-type').value;
            const subject = document.getElementById('notification-subject').value;
            const message = document.getElementById('notification-message').value;
            
            const loading = document.getElementById('notification-form-loading');
            const text = document.getElementById('notification-form-text');
            
            loading.style.display = 'inline-block';
            text.textContent = 'Sending...';

            setTimeout(() => {
                let recipientCount = 0;
                
                if (recipient === 'specific') {
                    recipientCount = 1;
                } else {
                    const roleMap = {
                        'all-users': appState.users.length,
                        'donors': appState.users.filter(u => u.role === 'donor').length,
                        'patients': appState.users.filter(u => u.role === 'patient').length,
                        'hospitals': appState.users.filter(u => u.role === 'hospital').length
                    };
                    recipientCount = roleMap[recipient] || 0;
                }

                // Add to notifications
                const newNotification = {
                    id: appState.notifications.length + 1,
                    recipient: recipient === 'specific' ? document.getElementById('specific-user-email').value : `${recipientCount} users`,
                    subject: subject,
                    type: type,
                    status: 'delivered',
                    sentDate: new Date().toISOString().slice(0, 16).replace('T', ' ')
                };
                
                appState.notifications.unshift(newNotification);
                
                if (appState.currentSection === 'notifications') {
                    renderNotificationsTable();
                }
                
                closeModal('notification-modal');
                showToast('success', `Notification sent to ${recipientCount} recipient(s).`);
                
                loading.style.display = 'none';
                text.textContent = 'Send Notification';
            }, 1000);
        });

        document.getElementById('nic-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const nic = document.getElementById('nic-number').value;
            const userName = document.getElementById('nic-user-name').value;
            
            const loading = document.getElementById('nic-form-loading');
            const text = document.getElementById('nic-form-text');
            const resultsDiv = document.getElementById('validation-results');
            const detailsDiv = document.getElementById('validation-details');
            
            loading.style.display = 'inline-block';
            text.textContent = 'Validating...';

            validateNIC(nic, userName).then(result => {
                loading.style.display = 'none';
                text.textContent = 'Validate NIC';
                
                resultsDiv.style.display = 'block';
                resultsDiv.style.background = result.success ? '#dcfce7' : '#fef2f2';
                detailsDiv.innerHTML = `
                    <p><strong>Status:</strong> ${result.success ? 'Success' : 'Failed'}</p>
                    <p><strong>Age:</strong> ${result.age || 'Unknown'}</p>
                    <p><strong>Message:</strong> ${result.message}</p>
                `;

                if (result.success) {
                    // Add to validation records
                    appState.nicValidations.push({
                        id: `VAL-${String(appState.nicValidations.length + 1).padStart(3, '0')}`,
                        name: userName,
                        nic: nic,
                        age: result.age,
                        status: result.status,
                        date: new Date().toISOString().slice(0, 16).replace('T', ' ')
                    });

                    if (appState.currentSection === 'nic-validation') {
                        renderNICValidationsTable();
                    }
                }
            });
        });

        document.getElementById('eligibility-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const donorId = parseInt(document.getElementById('eligibility-donor').value);
            const newStatus = document.getElementById('new-eligibility-status').value;
            const reason = document.getElementById('eligibility-reason').value;
            const notes = document.getElementById('assessment-notes').value;
            
            const loading = document.getElementById('eligibility-form-loading');
            const text = document.getElementById('eligibility-form-text');
            
            loading.style.display = 'inline-block';
            text.textContent = 'Updating...';

            setTimeout(() => {
                const donor = appState.users.find(u => u.id === donorId);
                if (donor) {
                    donor.eligibility = newStatus;
                    
                    // Send notification based on status
                    let notificationMessage = '';
                    if (newStatus === 'eligible') {
                        notificationMessage = 'Congratulations! You are now eligible for donation.';
                    } else if (newStatus === 'temp-ineligible') {
                        notificationMessage = `You are temporarily ineligible for donation. ${reason ? 'Reason: ' + reason : ''}`;
                    } else if (newStatus === 'perm-ineligible') {
                        notificationMessage = `You are permanently ineligible for donation. ${reason ? 'Reason: ' + reason : ''}`;
                    }
                    
                    if (notificationMessage) {
                        sendUserNotification(donor, 'Eligibility Status Update', notificationMessage);
                    }
                    
                    if (appState.currentSection === 'eligibility') {
                        renderEligibilityTable();
                    }
                    
                    closeModal('eligibility-modal');
                    showToast('success', `Eligibility status updated for ${donor.name}.`);
                }
                
                loading.style.display = 'none';
                text.textContent = 'Update Status';
            }, 1000);
        });

        // Event Listeners
        document.getElementById('notification-recipient').addEventListener('change', function(e) {
            const specificGroup = document.getElementById('specific-user-group');
            if (e.target.value === 'specific') {
                specificGroup.style.display = 'block';
            } else {
                specificGroup.style.display = 'none';
            }
        });

        document.getElementById('notification-type').addEventListener('change', function(e) {
            const reasonGroup = document.getElementById('rejection-reason-group');
            if (e.target.value === 'rejection') {
                reasonGroup.style.display = 'block';
            } else {
                reasonGroup.style.display = 'none';
            }
        });

        document.getElementById('new-eligibility-status').addEventListener('change', function(e) {
            const reasonGroup = document.getElementById('eligibility-reason-group');
            if (e.target.value === 'temp-ineligible' || e.target.value === 'perm-ineligible') {
                reasonGroup.style.display = 'block';
            } else {
                reasonGroup.style.display = 'none';
            }
        });

        // Search and Filter Functions
        document.getElementById('user-search').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            filterUsers(searchTerm);
        });

        function filterUsers(searchTerm) {
            const rows = document.querySelectorAll('#users-table .table-row:not(:first-child)');
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = 'grid';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Initialize Dashboard
        document.addEventListener('DOMContentLoaded', function() {
            renderUsersTable();
            renderDocumentsTable();
            renderNotificationsTable();
            renderEligibilityTable();
            renderNICValidationsTable();
            updateRoleCounts();
        });

        // Auto-hide notifications
        setTimeout(() => {
            const notifications = document.querySelectorAll('.notification.show');
            notifications.forEach(notification => {
                notification.classList.remove('show');
            });
        }, 10000);

        // Dashboard Charts and Animations
        function drawDoughnutChart() {
            const canvas = document.getElementById('userChart');
            if (!canvas) return;
            
            const ctx = canvas.getContext('2d');
            canvas.width = 250;
            canvas.height = 250;

            const data = [
                { label: "Live Organ Donors", value: 287, color: "#005baa" },
                { label: "Deceased Organ Donors", value: 156, color: "#0076d1" },
                { label: "Aftercare Donors", value: 94, color: "#3498db" },
                { label: "Financial Donors", value: 178, color: "#74b9ff" },
                { label: "Patients", value: 421, color: "#a4c8e1" },
                { label: "Medical Staff", value: 111, color: "#dbeafe" }
            ];

            const total = data.reduce((sum, d) => sum + d.value, 0);
            const centerX = canvas.width / 2;
            const centerY = canvas.height / 2;
            const outerRadius = 100;
            const innerRadius = 50;

            let startAngle = -0.5 * Math.PI;
            data.forEach(d => {
                d.start = startAngle;
                d.end = startAngle + (d.value / total) * 2 * Math.PI;
                startAngle = d.end;
            });

            function renderChart(highlightIndex = -1) {
                ctx.clearRect(0, 0, canvas.width, canvas.height);

                data.forEach((d, i) => {
                    ctx.beginPath();
                    ctx.moveTo(centerX, centerY);
                    ctx.arc(centerX, centerY, outerRadius, d.start, d.end);
                    ctx.closePath();
                    ctx.fillStyle = d.color;
                    ctx.fill();

                    if (i === highlightIndex) {
                        ctx.beginPath();
                        ctx.moveTo(centerX, centerY);
                        ctx.arc(centerX, centerY, outerRadius, d.start, d.end);
                        ctx.closePath();
                        ctx.lineWidth = 3;
                        ctx.strokeStyle = getComputedStyle(document.documentElement).getPropertyValue('--primary-color').trim();
                        ctx.shadowColor = d.color + "33";
                        ctx.shadowBlur = 12;
                        ctx.stroke();
                        ctx.shadowBlur = 0;
                    }
                });

                ctx.globalCompositeOperation = "destination-out";
                ctx.beginPath();
                ctx.arc(centerX, centerY, innerRadius, 0, 2 * Math.PI);
                ctx.fill();
                ctx.globalCompositeOperation = "source-over";

                ctx.fillStyle = '#005baa';
                ctx.font = 'bold 20px sans-serif';
                ctx.textAlign = 'center';
                ctx.fillText(total.toLocaleString(), centerX, centerY - 6);

                ctx.fillStyle = '#718096';
                ctx.font = '12px sans-serif';
                ctx.fillText('Total Users', centerX, centerY + 12);
            }

            renderChart();

            const legendItems = document.querySelectorAll(".chart-legend .legend-item");
            legendItems.forEach((item, idx) => {
                item.addEventListener("mouseenter", () => {
                    renderChart(idx);
                    item.style.background = "var(--secondary-hover-color)";
                });
                item.addEventListener("mouseleave", () => {
                    renderChart();
                    item.style.background = "var(--gray-bg-color)";
                });
            });

            canvas.addEventListener("mousemove", (e) => {
                const rect = canvas.getBoundingClientRect();
                const x = e.clientX - rect.left - centerX;
                const y = e.clientY - rect.top - centerY;
                const angle = Math.atan2(y, x);
                const distance = Math.sqrt(x*x + y*y);

                let found = -1;
                if (distance >= innerRadius && distance <= outerRadius) {
                    let adjAngle = angle;
                    if (adjAngle < -0.5 * Math.PI) adjAngle += 2 * Math.PI;
                    data.forEach((d, i) => {
                        if (adjAngle >= d.start && adjAngle <= d.end) found = i;
                    });
                }

                renderChart(found);

                legendItems.forEach((el, i) => {
                    el.style.background = (i === found) ? "var(--secondary-hover-color)" : "var(--gray-bg-color)";
                });
            });

            canvas.addEventListener("mouseleave", () => {
                renderChart();
                legendItems.forEach((el) => {
                    el.style.background = "var(--gray-bg-color)";
                });
            });
        }

        function animateBars() {
            const bars = document.querySelectorAll('.bar');
            bars.forEach((bar, index) => {
                const originalHeight = bar.style.height;
                bar.style.height = '0%';
                bar.style.transition = 'height 0.8s ease-out';
                
                setTimeout(() => {
                    bar.style.height = originalHeight;
                }, index * 100);
            });
        }

        function updateActivityFeed() {
            const activities = [
                { icon: 'success', symbol: '✓', text: 'New donor registration approved', time: 'Just now' },
                { icon: 'info', symbol: '📋', text: 'Document submitted for verification', time: '2 minutes ago' },
                { icon: 'warning', symbol: '⚠️', text: 'Account suspended - missing documents', time: '5 minutes ago' },
                { icon: 'success', symbol: '🔗', text: 'NIC validation completed', time: '8 minutes ago' },
                { icon: 'info', symbol: '📧', text: 'Notification sent to user', time: '12 minutes ago' }
            ];
            
            const activityContainer = document.querySelector('.activity-feed');
            if (!activityContainer) return;
            
            const existingItems = activityContainer.querySelectorAll('.activity-item');
            
            if (existingItems.length > 5) {
                existingItems[existingItems.length - 1].remove();
            }
            
            const randomActivity = activities[Math.floor(Math.random() * activities.length)];
            const newActivity = document.createElement('div');
            newActivity.className = 'activity-item';
            newActivity.style.opacity = '0';
            newActivity.style.transform = 'translateY(-20px)';
            
            newActivity.innerHTML = `
                <div class="activity-icon ${randomActivity.icon}">${randomActivity.symbol}</div>
                <div class="activity-content">
                    <div class="activity-text">${randomActivity.text}</div>
                    <div class="activity-time">${randomActivity.time}</div>
                </div>
            `;
            
            const firstActivity = activityContainer.querySelector('.activity-item');
            if (firstActivity) {
                activityContainer.insertBefore(newActivity, firstActivity);
            } else {
                activityContainer.appendChild(newActivity);
            }
            
            setTimeout(() => {
                newActivity.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                newActivity.style.opacity = '1';
                newActivity.style.transform = 'translateY(0)';
            }, 100);
        }

        function initDashboard() {
            drawDoughnutChart();
            
            setTimeout(() => {
                animateBars();
            }, 500);
        }

        if (document.getElementById('dashboard') && document.getElementById('dashboard').classList.contains('content-section')) {
            initDashboard();
        }

        setInterval(updateActivityFeed, 30000);
    </script>
</body>
</html>
