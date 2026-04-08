<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= ROOT ?>/public/assets/css/style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= ROOT ?>/public/assets/css/admin/style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <title>User Admin | LifeConnect</title>
    <style>
/* User Profile modal: Close matches Save (green theme) but white fill + green text for approved users */
#review-user-modal #btn-close-modal.btn-close-modal-active {
    background: #fff !important;
    color: #059669 !important;
    border: 1px solid #059669 !important;
    box-shadow: none;
}
#review-user-modal #btn-close-modal.btn-close-modal-active:hover:not(:disabled) {
    background: #ecfdf5 !important;
    color: #047857 !important;
}

.quick-stats {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 20px;
  background: transparent;
  padding: 0;
  border-radius: 0;
  border: none;
  box-shadow: none;
}

.quick-stat-card {
  background: #ffffff;
  border: 1px solid #bcd3ff;
  border-radius: 10px;
  text-align: center;
  padding: 20px 10px;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.quick-stat-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 4px 10px rgba(0, 90, 200, 0.15);
}

.quick-stat-number {
  font-size: 2rem;
  font-weight: 700;
  color: #1e56a0;
  margin-bottom: 5px;
}

.quick-stat-label {
  font-size: 0.95rem;
  color: #3d6cb9;
  font-weight: 500;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

/* ====== General Section ====== */
.summary-section, .activity-feed {
  margin: 25px 0;
  background: #ffffff;
  padding: 25px;
  border-radius: 14px;
  border: 1px solid #e2e8f0;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

/* Two chart cards side by side — no outer wrapper box */
.charts-section {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
  gap: 24px;
  margin: 30px 0;
}

.chart-card {
  background: #ffffff;
  border-radius: 12px;
  border: 1px solid #cfe1ff;
  padding: 24px;
  box-shadow: 0 2px 6px rgba(0, 80, 180, 0.07);
  display: flex;
  flex-direction: column;
}

.chart-card--distribution {
  min-height: 420px;
}

.chart-card--distribution .chart-title {
  margin-bottom: 0;
}

.chart-card--distribution .chart-body {
  display: flex;
  align-items: center;
  gap: 24px;
  flex: 1;
  margin-top: 10px;
}

.chart-card--distribution .doughnut-container {
  flex: 0 0 180px;
  min-height: 180px;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0;
  margin: 0;
}

.chart-card--distribution .chart-legend {
  flex: 1;
  margin-top: 0;
  border-top: none;
  border-left: 1px solid #e1edff;
  padding-top: 0;
  padding-left: 20px;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.chart-title {
  color: #1e56a0;
  font-size: 1.1rem;
  font-weight: 600;
  margin-bottom: 12px;
}

/* ====== Doughnut Chart & Legend ====== */
.doughnut-container {
  position: relative;
}

.chart-legend {
  margin-top: 10px;
  border-top: 1px solid #e1edff;
  padding-top: 8px;
}

.legend-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin: 6px 0;
  font-size: 0.9rem;
  color: #234e8a;
}

.legend-left {
  display: flex;
  align-items: center;
  gap: 8px;
}

.legend-color {
  width: 14px;
  height: 14px;
  border-radius: 3px;
  box-shadow: 0 0 2px rgba(0, 0, 0, 0.15);
}

.legend-count {
  font-weight: 600;
  color: #1d4ed8;
}

/* ====== Bar Chart Section ====== */
.bar-chart-container {
  position: relative;
  height: 200px;
  margin-top: 10px;
}

.chart-grid-line {
  position: absolute;
  left: 0;
  right: 0;
  height: 1px;
  background: #d6e5ff;
}

.bar-chart {
  display: flex;
  justify-content: space-around;
  align-items: flex-end;
  height: 100%;
}

.bar {
  width: 30px;
  background: linear-gradient(180deg, #0076d1, #005baa);
  border-radius: 6px 6px 0 0;
  position: relative;
  text-align: center;
  transition: all 0.3s ease;
}

.bar:hover {
  background: linear-gradient(180deg, #3b82f6, #1e40af);
  transform: scaleY(1.05);
}

.bar-value {
  position: absolute;
  top: -24px;
  font-size: 0.8rem;
  font-weight: 600;
  color: #0f3a85;
}

.bar-label {
  margin-top: 6px;
  color: #1e40af;
  font-size: 0.85rem;
}

/* ====== Chart Stats (below bar chart) ====== */
.chart-stats {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
  margin-top: 15px;
  background: #f8fafc;
  border-radius: 10px;
  padding: 10px;
  border: 1px solid #e2e8f0;
}

.chart-stat {
  text-align: center;
}

.chart-stat-value {
  color: #1e56a0;
  font-weight: 700;
  font-size: 1.1rem;
}

.chart-stat-label {
  color: #4a6fb3;
  font-size: 0.85rem;
}

/* ====== Summary Section ====== */
.summary-title {
  color: #1e56a0;
  font-size: 1.1rem;
  font-weight: 600;
  margin-bottom: 15px;
}

.summary-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
  gap: 12px;
}

.summary-item {
  background: #ffffff;
  border: 1px solid #cfe1ff;
  border-radius: 10px;
  text-align: center;
  padding: 12px;
  box-shadow: 0 2px 4px rgba(0, 80, 180, 0.05);
  transition: transform 0.2s ease;
}

.summary-item:hover {
  transform: translateY(-3px);
  background: #f0f6ff;
}

.summary-number {
  font-size: 1.4rem;
  font-weight: 700;
  color: #005baa;
}

.summary-text {
  font-size: 0.9rem;
  color: #3d6cb9;
  margin-top: 4px;
}

/* ====== Activity Feed ====== */
.activity-title {
  color: #1e56a0;
  font-size: 1.1rem;
  font-weight: 600;
  margin-bottom: 15px;
}

.activity-item {
  display: flex;
  align-items: center;
  background: #ffffff;
  border: 1px solid #cfe1ff;
  border-radius: 10px;
  padding: 10px 12px;
  margin-bottom: 10px;
  box-shadow: 0 2px 5px rgba(0, 70, 160, 0.05);
}

.activity-icon {
  font-size: 1.1rem;
  width: 30px;
  height: 30px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 8px;
  margin-right: 10px;
  flex-shrink: 0;
}

.activity-icon.success { background: #dbeafe; color: #1e40af; }
.activity-icon.info { background: #e0f2fe; color: #0369a1; }
.activity-icon.warning { background: #fef9c3; color: #a16207; }
.activity-icon.error { background: #fee2e2; color: #991b1b; }

.activity-content {
  display: flex;
  flex-direction: column;
}

.activity-text {
  color: #1e3a8a;
  font-weight: 500;
}

.activity-time {
  font-size: 0.8rem;
  color: #5b76b6;
}


body {
    background-color: #f8fafc;
    min-height: 100vh;
}

.stats-grid{
    margin-top: 20px;
}

.header-nav {
    display: flex;
    gap: 1.5rem;
    align-items: center;
    margin-right: 1.5rem;
}

.nav-link {
    color: white;
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 0.75rem;
    border-radius: 6px;
    transition: all 0.3s ease;
    background: rgba(255, 255, 255, 0.1);
}

.nav-link:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: translateY(-1px);
}

.nav-link i {
    font-size: 1rem;
}

</style>


</head>
<body>

<?php
    if (session_status() === PHP_SESSION_NONE) session_start();
    $adminName = $_SESSION['username'] ?? ($_SESSION['user_name'] ?? 'Admin');
?>

    <div class="header">
        <div class="header-content">
            <div class="header-left">
                <a href="<?= ROOT ?>" style="text-decoration:none; display:flex; align-items:center; gap:12px;">
                    <img src="<?= ROOT ?>/public/assets/images/logo.png" alt="LifeConnect" style="height:38px;">
                    <div>
                        <strong style="display:block; font-size:1.15rem; color:#005baa; line-height:1; letter-spacing: -0.02em;">LifeConnect</strong>
                        <p style="margin:0; font-size:.65rem; color:#94a3b8; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-top: 2px;">User Administration</p>
                    </div>
                </a>
            </div>
            
            <div class="header-right" style="display: flex; align-items: center; gap: 1.5rem;">
                <nav style="display: flex; align-items: center; gap: 1rem;">
                    <a href="<?= ROOT ?>" class="nav-icon-link" title="Home" style="color: #64748b; font-size: 1.2rem; transition: color 0.2s;">
                        <i class="fa-solid fa-house"></i>
                    </a>
                    <a href="javascript:void(0)" class="nav-icon-link" title="Notifications" style="color: #64748b; font-size: 1.2rem; transition: color 0.2s; position: relative;">
                        <i class="fa-solid fa-bell"></i>
                        <span style="position: absolute; top: -2px; right: -2px; width: 8px; height: 8px; background: #ef4444; border-radius: 50%; border: 2px solid white;"></span>
                    </a>
                </nav>

                <div class="user-info">
                    <div class="user-avatar" style="width: 32px; height: 32px; font-size: 0.9rem;"><?= substr($adminName, 0, 1) ?></div>
                    <div class="user-details">
                        <span class="user-name"><?= htmlspecialchars($adminName) ?></span>
                        <span class="user-role"><?= $_SESSION['role'] === 'AC_ADMIN' ? 'Aftercare Admin' : 'System Admin' ?></span>
                    </div>
                    <i class="fa-solid fa-chevron-down ms-2" style="font-size: 0.7rem; color: #94a3b8;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid p-0">
        <div class="main-content">
            <div class="sidebar glass">
                <div class="sidebar-user-card">
                    <div class="sidebar-user-avatar">
                        A
                    </div>
                    <div class="sidebar-user-info">
                        <span class="sidebar-user-name">admin_4</span>
                        <span class="sidebar-user-id">ID-00004</span>
                        <span class="sidebar-user-role">System Admin</span>
                    </div>
                </div>
                
                <div class="menu-section">
                    <div class="menu-section-title">CORE</div>
                    <a href="javascript:void(0)" class="menu-item active" onclick="showContent('dashboard')">
                        <span class="icon"><i class="fa-solid fa-chart-line"></i></span>
                        <span>Dashboard</span>
                    </a>
                </div>

                <div class="menu-section">
                    <div class="menu-section-title">USERS & SECURITY</div>
                    
                    <a href="javascript:void(0)" class="menu-item" onclick="showContent('accounts')" style="display: flex; align-items: center;">
                        <span class="icon"><i class="fa-solid fa-users-gear"></i></span>
                        <span>User Accounts</span>
                        <span id="nav-pending-users-badge" class="badge" style="display:none; background:#ef4444; color:white; border-radius:12px; padding:2px 7px; font-size:0.7rem; margin-left:auto; font-weight:bold;"></span>
                    </a>
                    
                    <a href="javascript:void(0)" class="menu-item" onclick="showContent('documents')">
                        <span class="icon"><i class="fa-solid fa-file-shield"></i></span>
                        <span>Verifications</span>
                    </a>
                </div>

                <div class="menu-section">
                    <div class="menu-section-title">COMMUNICATION</div>
                    
                    <a href="javascript:void(0)" class="menu-item" onclick="showContent('notifications')">
                        <span class="icon"><i class="fa-solid fa-bell"></i></span>
                        <span>Notifications</span>
                    </a>

                    <a href="javascript:void(0)" class="menu-item" onclick="showContent('feedbacks')">
                        <span class="icon"><i class="fa-solid fa-comment-dots"></i></span>
                        <span>Feedbacks</span>
                    </a>
                </div>

                <div class="menu-section mt-auto">
                    <a href="<?= ROOT ?>/logout" class="menu-item text-danger">
                        <span class="icon"><i class="fa-solid fa-right-from-bracket"></i></span>
                        <span>Logout</span>
                    </a>
                </div>
            </div>

            <div class="content-area" id="content-area">
    <!-- Dashboard Overview -->
    <div id="dashboard" class="content-section dashboard-page">
        <div class="content-body" style="padding-top: 0;">
            <div class="stats-grid dashboard-metrics">
                <div class="stat-card glass-card">
                    <div class="stat-number quick-stat-number" id="stat-total-users">0</div>
                    <div class="stat-label">Total Users</div>
                    <div class="stat-change positive" id="change-total-users"></div>
                </div>
                <div class="stat-card glass-card">
                    <div class="stat-number quick-stat-number" id="stat-pending-docs">0</div>
                    <div class="stat-label">Pending Verifications</div>
                    <div class="stat-change positive" id="change-pending-docs"></div>
                </div>
                <div class="stat-card glass-card">
                    <div class="stat-number quick-stat-number" id="stat-active-donors">0</div>
                    <div class="stat-label">Active Donors</div>
                    <div class="stat-change positive" id="change-active-donors"></div>
                </div>
                <div class="stat-card glass-card">
                    <div class="stat-number" id="stat-live-donors">0</div>
                    <div class="stat-label">Live Organ Donors</div>
                    <div class="stat-change positive" id="change-live-donors"></div>
                </div>
            </div>
            <div class="charts-section">
                <div class="chart-card chart-card--distribution">
                    <h3 class="chart-title">User Distribution</h3>
                    <div class="chart-body">
                        <div class="doughnut-container"><canvas id="userChart" style="max-width:180px; max-height:180px;"></canvas></div>
                        <div class="chart-legend">
                            <div class="legend-item"><div class="legend-left"><div class="legend-color" style="background:#005baa"></div>Donors</div><div class="legend-count">0</div></div>
                            <div class="legend-item"><div class="legend-left"><div class="legend-color" style="background:#a4c8e1"></div>Patients</div><div class="legend-count">0</div></div>
                            <div class="legend-item"><div class="legend-left"><div class="legend-color" style="background:#74b9ff"></div>Financial Donors</div><div class="legend-count">0</div></div>
                            <div class="legend-item"><div class="legend-left"><div class="legend-color" style="background:#e8f5e8"></div>Hospitals</div><div class="legend-count">0</div></div>
                            <div class="legend-item"><div class="legend-left"><div class="legend-color" style="background:#dbeafe"></div>Medical Schools</div><div class="legend-count">0</div></div>
                        </div>
                    </div>
                </div>
                <div class="chart-card">
                    <h3 class="chart-title">Weekly Registration Activity</h3>
                    <div class="bar-chart-container">
                        <div class="chart-grid">
                            <div class="chart-grid-line" style="bottom:80%;"></div>
                            <div class="chart-grid-line" style="bottom:60%;"></div>
                            <div class="chart-grid-line" style="bottom:40%;"></div>
                            <div class="chart-grid-line" style="bottom:20%;"></div>
                        </div>
                        <div class="bar-chart" id="weekly-bar-chart">
                            <!-- Bars will be inserted here dynamically -->
                        </div>
                    </div>
                    <div class="chart-stats" style="grid-template-columns: repeat(3, 1fr);">
                        <div class="chart-stat">
                            <div class="chart-stat-value" id="stat-weekly-total">0</div>
                            <div class="chart-stat-label">Weekly Total</div>
                        </div>
                        <div class="chart-stat">
                            <div class="chart-stat-value" id="stat-weekly-avg">0</div>
                            <div class="chart-stat-label">Daily Average</div>
                        </div>
                        <div class="chart-stat">
                            <div class="chart-stat-value" id="stat-weekly-growth">0%</div>
                            <div class="chart-stat-label">vs Last Week</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="activity-feed">
                <h3 class="activity-title">Recent System Activity</h3>
                <div class="activity-item">
                    <div class="activity-icon success"><i class="fa-solid fa-check"></i></div>
                    <div class="activity-content">
                        <div class="activity-text">5 new donor registrations approved</div>
                        <div class="activity-time">2 minutes ago</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon info"><i class="fa-solid fa-file-invoice"></i></div>
                    <div class="activity-content">
                        <div class="activity-text">12 documents submitted for verification</div>
                        <div class="activity-time">15 minutes ago</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon warning"><i class="fa-solid fa-triangle-exclamation"></i></div>
                    <div class="activity-content">
                        <div class="activity-text">3 accounts suspended for incomplete documentation</div>
                        <div class="activity-time">1 hour ago</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon success"><i class="fa-solid fa-rotate"></i></div>
                    <div class="activity-content">
                        <div class="activity-text">Election Commission API sync completed</div>
                        <div class="activity-time">2 hours ago</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon info"><i class="fa-solid fa-envelope"></i></div>
                    <div class="activity-content">
                        <div class="activity-text">89 approval notifications sent to users</div>
                        <div class="activity-time">3 hours ago</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon error"><i class="fa-solid fa-xmark"></i></div>
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
                       </div>
                    <div class="content-body">
                        <div style="display: flex; gap: 16px; align-items: center; margin-bottom: 24px;">
                            <div class="search-bar" style="margin-bottom: 0; flex: 1;">
                                <span class="search-icon"><i class="fa-solid fa-magnifying-glass"></i></span>
                                <input type="text" class="search-input" placeholder="Search users by name, email, or ID..." id="user-search" onkeyup="fetchUsers()">
                            </div>

                            <div class="filter-section" style="margin-bottom: 0; display: flex; gap: 12px;">
                                <select class="filter-select" id="status-filter" onchange="fetchUsers()">
                                    <option value="">All Statuses</option>
                                    <option value="active">Active</option>
                                    <option value="suspended">Suspended</option>
                                    <option value="pending">Pending</option>
                                </select>
                                <select class="filter-select" id="role-filter" onchange="fetchUsers()">
                                    <option value="">All Roles</option>
                                    <option value="donor">Donor</option>
                                    <option value="patient">Patient</option>
                                    <option value="hospital">Hospital</option>
                                    <option value="financial">Financial Donor</option>
                                </select>
                            </div>
                        </div>

                        <div class="data-table">
                            <div class="table-header" style="display: flex; justify-content: space-between; align-items: center; padding-right: 1.5rem;">
                                <h4>User Accounts</h4>
                                <div class="action-buttons" style="margin-top: 0; display: flex; gap: 0.5rem;">
                                    <button class="btn btn-success" id="bulk-activate" onclick="bulkActivate()" disabled style="padding: 0.4rem 0.8rem; font-size: 0.85rem;">Bulk Activate</button>
                                    <button class="btn btn-secondary" id="bulk-deactivate" onclick="bulkDeactivate()" disabled style="padding: 0.4rem 0.8rem; font-size: 0.85rem;">Bulk Deactivate</button>
                                    <button class="btn btn-danger" id="bulk-suspend" onclick="bulkSuspend()" disabled style="padding: 0.4rem 0.8rem; font-size: 0.85rem;">Bulk Suspend</button>
                                </div>
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
                        <p>Review and verify submitted donor documents</p>
                    </div>
                    <div class="content-body">
                        <div class="search-bar">
                            <span class="search-icon"><i class="fa-solid fa-magnifying-glass"></i></span>
                            <input type="text" class="search-input" placeholder="Search by document ID, user name, or type...">
                        </div>

                        <div class="filter-section">
                            <select class="filter-select" id="doc-type-filter">
                                <option value="">All Document Types</option>
                                <option value="nic">Guardian Documents</option>
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
                        <p>Manage and send system notifications to users</p>
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
                        <p>Assess and update donor eligibility status</p>
                    </div>
                    <div class="content-body">
                        <div class="search-bar">
                            <span class="search-icon"><i class="fa-solid fa-magnifying-glass"></i></span>
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


                <!-- Feedbacks -->
<div id="feedbacks" class="content-section" style="display: none;">
    <div class="content-header">
        <h2>Feedback Management</h2>
    </div>
    <div class="content-body">
        <div class="action-section">
            <h3>Feedback Actions</h3>
            <div class="action-buttons">
                <button class="btn btn-danger" id="bulk-delete-feedbacks" onclick="bulkDeleteFeedbacks()" disabled>Bulk Delete</button>
            </div>
        </div>

        <div class="data-table">
            <div class="table-header">
                <h4>Feedback Messages</h4>
            </div>
            <div class="table-content" id="feedbacks-table">
                <div class="table-row" style="font-weight: 600; background: var(--gray-bg-color);">
                    <div class="table-cell">
                        <input type="checkbox" id="select-all-feedbacks" onchange="toggleSelectAllFeedbacks()"> User Details
                    </div>
                    <div class="table-cell">Message</div>
                    <div class="table-cell">Date</div>
                    <div class="table-cell">Actions</div>
                </div>
            </div>
        </div>
    </div>
</div>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div id="edit-user-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Edit User Details</h3>
                <button class="modal-close" onclick="closeModal('edit-user-modal')">&times;</button>
            </div>
            <form id="edit-user-form">
                <input type="hidden" id="edit-user-id">
                <div class="form-group">
                    <label class="form-label">Username</label>
                    <input type="text" class="form-input" id="edit-username" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-input" id="edit-email" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Role</label>
                    <select class="form-select" id="edit-role" required>
                        <option value="donor">Donor</option>
                        <option value="patient">Patient</option>
                        <option value="hospital">Hospital</option>
                        <option value="financial">Financial Donor</option>
                        <option value="medical_school">Medical School</option>
                    </select>
                </div>
                <div class="action-buttons" style="margin-top: 2rem;">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <button type="button" class="btn btn-secondary" onclick="closeModal('edit-user-modal')">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Review User Modal (Detailed View) -->
    <div id="review-user-modal" class="modal">
        <div class="modal-content" style="padding: 0; overflow-y: auto; overflow-x: hidden;">
            <div class="modal-header" style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); padding: 1.5rem 2rem; margin-bottom: 0;">
                <h3 style="margin: 0; display: flex; align-items: center; gap: 0.5rem;"><i class="fa-solid fa-user-shield" aria-hidden="true"></i> User Profile</h3>
                <button class="modal-close" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 50%; background: rgba(0,91,170,0.1);" onclick="closeModal('review-user-modal')">&times;</button>
            </div>
            <div id="review-user-body" style="padding: 2rem;">
                <input type="hidden" id="review-user-id">
                <input type="hidden" id="review-user-role">
                <input type="hidden" id="review-user-status">

                <div class="form-group">
                    <label class="form-label">Username</label>
                    <input type="text" class="form-input" id="review-username" readonly style="background: var(--gray-bg-color);">
                </div>
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-input" id="review-email" readonly style="background: var(--gray-bg-color);">
                </div>
                
                <div class="form-group" style="display:flex; gap:10px;">
                    <div style="flex:1;">
                        <label class="form-label">First Name / Name</label>
                        <input type="text" class="form-input" id="review-firstname">
                    </div>
                    <div style="flex:1;" id="review-lastname-group">
                        <label class="form-label">Last Name</label>
                        <input type="text" class="form-input" id="review-lastname">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Phone</label>
                    <input type="text" class="form-input" id="review-phone">
                </div>

                <div class="form-group" style="display:flex; gap:10px;">
                    <div style="flex:1;" id="review-role-group">
                        <label class="form-label">Role</label>
                        <input type="text" class="form-input" id="review-role-text" readonly style="background: var(--gray-bg-color);">
                    </div>
                    <div style="flex:1;" id="review-regdate-group">
                        <label class="form-label">Registration Date</label>
                        <input type="text" class="form-input" id="review-regdate" readonly style="background: var(--gray-bg-color);">
                    </div>
                </div>

                <hr style="margin: 15px 0; border: none; border-top: 1px solid var(--gray-bg-color);">

                <!-- Extra Role specific fields -->
                <div class="form-group" style="display:flex; gap:10px;">
                    <div style="flex:1;" id="review-nic-group">
                        <label class="form-label">NIC / Ref No</label>
                        <input type="text" class="form-input" id="review-nic" readonly style="background: var(--gray-bg-color);">
                    </div>
                    <div style="flex:1;" id="review-dob-group">
                        <label class="form-label">Date of Birth</label>
                        <input type="date" class="form-input" id="review-dob" readonly style="background: var(--gray-bg-color);">
                    </div>
                </div>
                <div class="form-group" id="review-gender-group">
                    <label class="form-label">Gender</label>
                    <input type="text" class="form-input" id="review-gender" readonly style="background: var(--gray-bg-color);">
                </div>
                
                <!-- Manual Verification: only shown for PENDING (see viewDetailedUser) -->
                <div id="verification-section" style="display:none; background: #fff8e1; border-left: 4px solid #ffb300; padding: 15px; margin-bottom: 20px;">
                    <h4 style="margin-top:0; margin-bottom: 10px; color:#b27d00;">Manual Verification</h4>
                    <label style="display:flex; align-items:center; gap:8px; margin-bottom: 10px; cursor: pointer;">
                        <input type="checkbox" id="verify-genuine" onchange="checkVerificationStatus()">
                        Name looks genuine
                    </label>
                    <div style="display:flex; align-items:flex-start; gap:8px;">
                        <input type="checkbox" id="verify-registry" onchange="checkVerificationStatus()" style="margin-top:3px;">
                        <div style="line-height:1.45;">
                            <span>Checked with official registry — </span>
                            <a href="https://eservices.elections.gov.lk/pages/myVoterRegistrationSearch.aspx" target="_blank" rel="noopener noreferrer" onclick="event.stopPropagation()" style="color:#005baa; font-weight:600;">Voter registration search</a>
                            <span style="color:#64748b; font-size:0.85rem;"> · Election Commission</span>
                        </div>
                    </div>
                </div>

                <!-- Admin Action buttons -->
                <div class="action-buttons" style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid rgba(0, 91, 170, 0.1); justify-content: flex-end;" id="admin-decision-section">
                    <button type="button" class="btn btn-success" id="btn-approve" onclick="submitUserReview('APPROVE')" style="display:none;">Approve</button>
                    <button type="button" class="btn btn-danger" id="btn-reject" onclick="submitUserReview('REJECT')" style="display:none;">Reject</button>
                    <button type="button" class="btn btn-primary" id="btn-save-details" onclick="submitUserReview('UPDATE')">Save Details</button>
                    <button type="button" class="btn btn-secondary" id="btn-close-modal" onclick="closeModal('review-user-modal')">Close</button>
                </div>
            </div>
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
                    <button type="submit" class="btn btn-primary btn-sm">
                        <span class="loading" id="eligibility-form-loading" style="display: none;"></span>
                        <span id="eligibility-form-text">Update Status</span>
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="closeModal('eligibility-modal')">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Feedback Message Modal -->
<div id="feedback-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Feedback Message</h3>
            <button class="modal-close" onclick="closeModal('feedback-modal')">&times;</button>
        </div>
        <div id="feedback-details">
            <div class="form-group">
                <label class="form-label">From</label>
                <div class="form-input" style="background: var(--gray-bg-color);" id="feedback-from"></div>
            </div>
            <div class="form-group">
                <label class="form-label">Email</label>
                <div class="form-input" style="background: var(--gray-bg-color);" id="feedback-email"></div>
            </div>
            <div class="form-group">
                <label class="form-label">Message</label>
                <div class="form-textarea" style="background: var(--gray-bg-color); min-height: 150px; white-space: pre-wrap;" id="feedback-message"></div>
            </div>
            <div class="form-group">
                <label class="form-label">Submitted</label>
                <div class="form-input" style="background: var(--gray-bg-color);" id="feedback-date"></div>
            </div>
        </div>
        <div class="action-buttons" style="margin-top: 2rem;">
            <button type="button" class="btn btn-danger" onclick="deleteFeedback(currentFeedbackId)">Delete Feedback</button>
            <button type="button" class="btn btn-secondary" onclick="closeModal('feedback-modal')">Close</button>
        </div>
    </div>
</div>

    <!-- Toast Notification -->
    <div id="toast" class="notification">
        <div id="toast-message">Operation completed successfully.</div>
    </div>

    <script>
        const ROOT = '<?= $data['ROOT'] ?>';
    </script>
    <script>
        // Application State
        const appState = {
            currentSection: 'dashboard',
            users: [],
            documents: [],
            notifications: [],
            feedbacks: [],
            selectedUsers: [],
            selectedDocuments: [],
            selectedEligibility: [],
            selectedFeedbacks: []
        };

        // Navigation Functions
        function showContent(sectionId) {
            // Hide all sections
            document.querySelectorAll('.content-section').forEach(section => {
                section.style.display = 'none';
            });

            const targetSection = document.getElementById(sectionId);
            if (targetSection) {
                targetSection.style.display = 'block';
                appState.currentSection = sectionId;
            }

            // Update active menu item
            document.querySelectorAll('.menu-item').forEach(item => {
                item.classList.remove('active');
                // Check if this menu item corresponds to the section
                const onclickAttr = item.getAttribute('onclick');
                if (onclickAttr && onclickAttr.includes(`'${sectionId}'`)) {
                    item.classList.add('active');
                }
            });

            // Load section-specific data
            loadSectionData(sectionId);
        }

        // Handle Hash Navigation on Load
        document.addEventListener('DOMContentLoaded', function() {
            if (window.location.hash) {
                const sectionId = window.location.hash.substring(1);
                if (document.getElementById(sectionId)) {
                    showContent(sectionId);
                }
            }
        });

        // Data Loading Functions
        function loadSectionData(sectionId) {
            switch(sectionId) {
                case 'dashboard':
                    fetchDashboardStats();
                    break;
                case 'accounts':
                    fetchUsers();
                    break;
                case 'documents':
                    fetchPendingDocuments();
                    break;
                case 'notifications':
                    fetchNotifications();
                    break;
                case 'eligibility':
                    renderEligibilityTable();
                    break;
                case 'feedbacks':
                    renderFeedbacksTable();
                    break;
            }
        }

async function fetchDashboardStats() {
    try {
        const response = await fetch(`${ROOT}/user-admin/getDashboardStats`);
        const data = await response.json();
        if (data.success) {
            updateDashboardUI(data.stats);
        }
    } catch (error) {
        console.error('Error fetching dashboard stats:', error);
    }
}

function updateDashboardUI(stats) {
    const setText = (id, value) => {
        const el = document.getElementById(id);
        if (el) el.textContent = value;
    };

    // 7 cards (all derived from DB via /user-admin/getDashboardStats)
    setText('stat-total-users', stats.totalUsers ?? 0);
    setText('stat-pending-docs', (Number(stats.status_PENDING || stats.status_pending || 0)));
    setText('stat-active-donors', stats.role_DONOR ?? 0);
    setText('stat-live-donors', stats.pledge_LIVE_ORGAN ?? stats.pledge_LIVE ?? stats.pledge_ORGAN ?? 0);
    setText('stat-patients', stats.role_PATIENT ?? 0);
    setText('stat-hospitals', stats.role_HOSPITAL ?? 0);
    
    // Update changes
    const setChange = (id, count) => {
        const el = document.getElementById(id);
        if (el) {
            if (count > 0) {
                el.innerHTML = `↑ ${count} this month`;
                el.style.display = 'block';
            } else {
                el.style.display = 'none';
            }
        }
    }
    
    setChange('change-total-users', stats.usersThisMonth ?? 0);
    setChange('change-pending-docs', stats.pendingThisMonth ?? 0);
    setChange('change-active-donors', stats.donorsThisMonth ?? 0);
    setChange('change-live-donors', stats.liveDonorsThisMonth ?? 0);
    setChange('change-patients', stats.patientsThisMonth ?? 0);
    setChange('change-hospitals', stats.hospitalsThisMonth ?? 0);

    // Update pending users badge in nav
    const pendingUsers = Number(stats.status_PENDING || stats.status_pending || 0);
    const navBadge = document.getElementById('nav-pending-users-badge');
    if (navBadge) {
        if (pendingUsers > 0) {
            navBadge.textContent = '+' + pendingUsers;
            navBadge.style.display = 'inline-block';
        } else {
            navBadge.style.display = 'none';
        }
    }

    // Update Doughnut Chart with real values
    updateUserChart(stats);

    // Update Weekly Registration Activity with real values
    updateWeeklyActivityChart(stats);
}

function updateWeeklyActivityChart(stats) {
    const chartContainer = document.getElementById('weekly-bar-chart');
    if (!chartContainer) return;

    if (!stats.weekly_chart_data || stats.weekly_chart_data.length === 0) {
        chartContainer.innerHTML = '<div style="width:100%; text-align:center; color:#64748b; padding-top:40px;">No data available</div>';
        return;
    }

    // Update the Stats Summary
    document.getElementById('stat-weekly-total').textContent = stats.weekly_total ?? 0;
    document.getElementById('stat-weekly-avg').textContent = stats.weekly_average ?? 0;
    
    const growthEl = document.getElementById('stat-weekly-growth');
    if (growthEl) {
        const growth = stats.weekly_growth ?? 0;
        growthEl.textContent = (growth >= 0 ? '+' : '') + growth + '%';
        growthEl.style.color = growth >= 0 ? '#059669' : '#dc2626';
    }

    // Render Bars
    chartContainer.innerHTML = '';
    const maxVal = Math.max(...stats.weekly_chart_data.map(d => d.count), 1); // Avoid div by zero

    // Blue shades for varied hues
    const barColors = ['#005baa', '#1e40af', '#2563eb', '#3b82f6', '#60a5fa', '#93c5fd', '#bfdbfe'];

    stats.weekly_chart_data.forEach((data, index) => {
        const heightPercent = (data.count / maxVal) * 90; // scale to 90% max height
        const barDiv = document.createElement('div');
        barDiv.className = 'bar';
        barDiv.style.height = '0%'; // Start at 0 for animation
        barDiv.style.transition = `height 0.8s ease-out ${index * 0.1}s`;
        barDiv.style.background = barColors[index % barColors.length]; // Apply varied blue hues
        
        barDiv.innerHTML = `
            <div class="bar-value" style="color: ${barColors[index % barColors.length]}">${data.count}</div>
            <div class="bar-label" style="color: ${barColors[index % barColors.length]}">${data.day}</div>
        `;
        
        chartContainer.appendChild(barDiv);
        
        // Trigger animation
        setTimeout(() => {
            barDiv.style.height = heightPercent + '%';
        }, 50);
    });
}

function updateUserChart(stats) {
    const data = [
        { label: "Donors", value: Number(stats.role_DONOR || 0), color: "#005baa" },
        { label: "Patients", value: Number(stats.role_PATIENT || 0), color: "#a4c8e1" },
        { label: "Financial Donors", value: Number(stats.role_FINANCIAL_DONOR || 0), color: "#74b9ff" },
        { label: "Hospitals", value: Number(stats.role_HOSPITAL || 0), color: "#e8f5e8" },
        { label: "Medical Schools", value: Number(stats.role_MEDICAL_SCHOOL || 0), color: "#dbeafe" }
    ];
    
    // Update HTML legend counts
    const legendCounts = document.querySelectorAll('.chart-legend .legend-count');
    if (legendCounts.length >= 5) {
        legendCounts[0].textContent = stats.role_DONOR || 0;
        legendCounts[1].textContent = stats.role_PATIENT || 0;
        legendCounts[2].textContent = stats.role_FINANCIAL_DONOR || 0;
        legendCounts[3].textContent = stats.role_HOSPITAL || 0;
        legendCounts[4].textContent = stats.role_MEDICAL_SCHOOL || 0;
    }

    drawDoughnutChart(data);
}

function drawDoughnutChart(chartData) {
  const canvas = document.getElementById('userChart');
  if (!canvas) return;
  const ctx = canvas.getContext('2d');
  canvas.width = 250;
  canvas.height = 250;

  const data = chartData || [
    { label: "Donors", value: 287, color: "#005baa" },
    { label: "Financial Donors", value: 178, color: "#74b9ff" },
    { label: "Patients", value: 421, color: "#a4c8e1" },
    { label: "Hospitals", value: 23, color: "#e8f5e8" },
    { label: "Medical Schools", value: 47, color: "#dbeafe" }
  ];

  const total = data.reduce((sum, d) => sum + Number(d.value), 0);
  const centerX = canvas.width / 2;
  const centerY = canvas.height / 2;
  const outerRadius = 100;
  const innerRadius = 50;

  let startAngle = -0.5 * Math.PI;
  data.forEach(d => {
    d.start = startAngle;
    const sliceAngle = total > 0 ? (Number(d.value) / total) * 2 * Math.PI : 0;
    d.end = startAngle + sliceAngle;
    startAngle = d.end;
  });

  function renderChart(highlightIndex = -1) {
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    data.forEach((d, i) => {
      if (Number(d.value) === 0) return;
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
        ctx.strokeStyle = '#1e56a0';
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

  // Restore Interactivity
  canvas.onmousemove = (e) => {
    const rect = canvas.getBoundingClientRect();
    const x = e.clientX - rect.left - centerX;
    const y = e.clientY - rect.top - centerY;
    const distance = Math.sqrt(x*x + y*y);
    const angle = Math.atan2(y, x);
    
    let foundIndex = -1;
    if (distance >= innerRadius && distance <= outerRadius) {
      let adjAngle = angle;
      if (adjAngle < -0.5 * Math.PI) adjAngle += 2 * Math.PI;
      data.forEach((d, i) => {
        if (adjAngle >= d.start && adjAngle <= d.end) foundIndex = i;
      });
    }
    renderChart(foundIndex);
  };

  canvas.onmouseleave = () => renderChart();
}

// Initialize dashboard
function initDashboard() {
  fetchDashboardStats();
}

window.onload = initDashboard;

// Update activity feed every 30 seconds
setInterval(updateActivityFeed, 30000);

        // User Account Management Functions
async function fetchUsers() {
    try {
        const searchTerm = document.getElementById('user-search').value;
        const status = document.getElementById('status-filter').value;
        const role = document.getElementById('role-filter').value;
        
        const response = await fetch(`${ROOT}/user-admin/getUsers?search=${searchTerm}&status=${status}&role=${role}`);
        const data = await response.json();
        if (data.success) {
            appState.users = data.users;
            appState.selectedUsers = [];
            const selectAll = document.getElementById('select-all');
            if (selectAll) selectAll.checked = false;
            updateBulkButtons(['bulk-activate', 'bulk-deactivate', 'bulk-suspend']);
            renderUsersTable();
        }
    } catch (error) {
        console.error('Error fetching users:', error);
    }
}

function renderUsersTable() {
    const tableContent = document.getElementById('users-table');
    const headerRow = tableContent.querySelector('.table-row');
    
    tableContent.innerHTML = '';
    tableContent.appendChild(headerRow);

    appState.users.forEach(user => {
        const row = document.createElement('div');
        row.className = 'table-row';
        row.style.cursor = 'pointer';
        
        row.innerHTML = `
            <div class="table-cell name" data-label="User">
                <input type="checkbox" class="user-checkbox" data-user-id="${user.id}">
                <span style="margin-left: 0.5rem;">
                    <strong>${user.username}</strong><br>
                    <small>${user.email}</small>
                </span>
            </div>
            <div class="table-cell" data-label="Role">${formatRole(user.role)}</div>
            <div class="table-cell status" data-label="Status">
                <span class="status-badge status-${user.status.toLowerCase()}">${formatStatus(user.status)}</span>
            </div>
            <div class="table-cell" data-label="Registration">${new Date(user.created_at).toLocaleDateString()}</div>
            <div class="table-cell action-cell" data-label="Actions">
                ${user.status.toUpperCase() === 'ACTIVE' ? 
                    `<button class="btn btn-danger btn-small suspend-btn">Suspend</button>` :
                    user.status.toUpperCase() === 'PENDING' ?
                    `<button class="btn btn-success btn-small activate-btn">Activate</button>` : ''
                }
            </div>
        `;

        tableContent.appendChild(row);

        // Event listener for the whole row
        row.addEventListener('click', (e) => {
            if (!e.target.closest('button') && !e.target.closest('input[type="checkbox"]')) {
                console.log('Row clicked for user:', user.id);
                viewDetailedUser(user.id, user.role, user.status);
            }
        });

        const suspendBtn = row.querySelector('.suspend-btn');
        if (suspendBtn) {
            suspendBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                updateUserStatus(user.id, 'SUSPENDED');
            });
        }

        const activateBtn = row.querySelector('.activate-btn');
        if (activateBtn) {
            activateBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                updateUserStatus(user.id, 'ACTIVE');
            });
        }
    });

    document.querySelectorAll('.user-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedUsers);
    });
}

async function updateUserStatus(userId, status) {
    try {
        const response = await fetch(`${ROOT}/user-admin/updateUserStatus`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ user_id: userId, status: status })
        });
        const data = await response.json();
        if (data.success) {
            showToast('success', data.message);
            fetchUsers();
            fetchDashboardStats();
        } else {
            showToast('error', data.message);
        }
    } catch (error) {
        console.error('Error updating status:', error);
    }
}

        async function viewDetailedUser(userId, role, status) {
            try {
                showToast('info', 'Loading details...');
                const response = await fetch(`${ROOT}/user-admin/getDetailedUser?id=${userId}&role=${role}`);
                let data;
                try {
                    data = await response.json();
                } catch (jsonErr) {
                    showToast('error', 'Invalid server response. Check console.');
                    console.error('JSON parse error:', jsonErr);
                    return;
                }
                
                if (data.success) {
                    const user = data.user;
                    document.getElementById('review-user-id').value = user.id;
                    document.getElementById('review-user-role').value = user.role;
                    document.getElementById('review-user-status').value = user.status;

                    document.getElementById('review-username').value = user.username || '';
                    document.getElementById('review-email').value = user.email || '';
                    document.getElementById('review-firstname').value = user.first_name || '';
                    document.getElementById('review-phone').value = user.phone || '';
                    document.getElementById('review-role-text').value = user.role;
                    document.getElementById('review-regdate').value = new Date(user.created_at).toLocaleDateString();

                    // Show/hide based on role
                    if (user.role === 'FINANCIAL_DONOR' || user.role === 'HOSPITAL' || user.role === 'MEDICAL_SCHOOL') {
                        document.getElementById('review-lastname-group').style.display = 'none';
                        document.getElementById('review-dob-group').style.display = 'none';
                        document.getElementById('review-gender-group').style.display = 'none';
                    } else {
                        document.getElementById('review-lastname-group').style.display = 'block';
                        document.getElementById('review-lastname').value = user.last_name || '';
                        
                        document.getElementById('review-dob-group').style.display = 'block';
                        document.getElementById('review-dob').value = user.dob || '';
                        
                        document.getElementById('review-gender-group').style.display = 'block';
                        document.getElementById('review-gender').value = user.gender || '';
                    }

                    if (user.nic) {
                        document.getElementById('review-nic-group').style.display = 'block';
                        document.getElementById('review-nic').value = user.nic;
                    } else {
                        document.getElementById('review-nic-group').style.display = 'none';
                    }

                    const isPending = user.status.toUpperCase() === 'PENDING';
                    const isActive = user.status.toUpperCase() === 'ACTIVE';
                    
                    const verifSection = document.getElementById('verification-section');
                    const btnApprove = document.getElementById('btn-approve');
                    const btnReject = document.getElementById('btn-reject');
                    const btnSave = document.getElementById('btn-save-details');
                    const btnClose = document.getElementById('btn-close-modal');

                    // Reset buttons
                    btnSave.className = 'btn btn-primary';
                    if (btnClose) {
                        btnClose.className = 'btn btn-secondary';
                        btnClose.classList.remove('btn-close-modal-active');
                    }

                    if (isPending) {
                        verifSection.style.display = 'block';
                        document.getElementById('verify-genuine').checked = false;
                        document.getElementById('verify-registry').checked = false;

                        btnApprove.style.display = 'inline-block';
                        btnReject.style.display = 'inline-block';
                        btnSave.style.display = 'none';

                        btnApprove.disabled = true;
                    } else {
                        verifSection.style.display = 'none';
                        btnApprove.style.display = 'none';
                        btnReject.style.display = 'none';
                        btnSave.style.display = 'inline-block';
                        
                        if (isActive) {
                            btnSave.className = 'btn btn-success';
                            if (btnClose) {
                                btnClose.className = 'btn btn-secondary btn-close-modal-active';
                            }
                        }
                    }

                    document.getElementById('review-user-modal').classList.add('show');
                } else {
                    showToast('error', data.message);
                }
            } catch (error) {
                console.error('Error fetching user details:', error);
                showToast('error', 'Network error. Could not fetch details.');
            }
        }

        function checkVerificationStatus() {
            const isGenuineChecked = document.getElementById('verify-genuine').checked;
            const isRegistryChecked = document.getElementById('verify-registry').checked;
            const btnApprove = document.getElementById('btn-approve');
            if (btnApprove) {
                btnApprove.disabled = !(isGenuineChecked && isRegistryChecked);
            }
        }

        async function submitUserReview(action) {
            const userId = document.getElementById('review-user-id').value;
            const role = document.getElementById('review-user-role').value;
            const data = {
                first_name: document.getElementById('review-firstname').value,
                last_name: document.getElementById('review-lastname') ? document.getElementById('review-lastname').value : '',
                phone: document.getElementById('review-phone').value
            };

            try {
                const payload = {
                    id: userId,
                    role: role,
                    action: action,
                    data: data
                };

                const response = await fetch(`${ROOT}/user-admin/reviewUser`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });
                const respData = await response.json();
                
                if (respData.success) {
                    showToast('success', respData.message);
                    closeModal('review-user-modal');
                    fetchUsers();
                    fetchDashboardStats();
                } else {
                    showToast('error', respData.message);
                }
            } catch (error) {
                console.error('Error saving user review:', error);
            }
        }

        async function editUser(userId) {
            try {
                const response = await fetch(`${ROOT}/user-admin/getUser?id=${userId}`);
                const data = await response.json();
                if (data.success) {
                    const user = data.user;
                    document.getElementById('edit-user-id').value = user.id;
                    document.getElementById('edit-username').value = user.username;
                    document.getElementById('edit-email').value = user.email;
                    document.getElementById('edit-role').value = user.role.toLowerCase();
                    document.getElementById('edit-user-modal').classList.add('show');
                } else {
                    showToast('error', data.message);
                }
            } catch (error) {
                console.error('Error fetching user:', error);
            }
        }

        // Handle Edit User Form Submission
        document.getElementById('edit-user-form')?.addEventListener('submit', async (e) => {
            e.preventDefault();
            const userId = document.getElementById('edit-user-id').value;
            const updatedData = {
                username: document.getElementById('edit-username').value,
                email: document.getElementById('edit-email').value,
                role: document.getElementById('edit-role').value
            };

            try {
                const response = await fetch(`${ROOT}/user-admin/updateUser`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: userId, data: updatedData })
                });
                const data = await response.json();
                if (data.success) {
                    showToast('success', data.message);
                    closeModal('edit-user-modal');
                    fetchUsers();
                } else {
                    showToast('error', data.message);
                }
            } catch (error) {
                console.error('Error updating user:', error);
            }
        });

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
                    button.disabled = appState.selectedUsers.length === 0;
                }
            });
        }


        async function bulkUpdateStatus(status) {
            if (appState.selectedUsers.length === 0) return;
            
            if (confirm(`Update status to ${status} for ${appState.selectedUsers.length} user(s)?`)) {
                try {
                    const response = await fetch(`${ROOT}/user-admin/bulkUpdateUserStatus`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ user_ids: appState.selectedUsers, status: status })
                    });
                    const data = await response.json();
                    if (data.success) {
                        showToast('success', data.message);
                        appState.selectedUsers = [];
                        document.getElementById('select-all').checked = false;
                        fetchUsers();
                        fetchDashboardStats();
                    } else {
                        showToast('error', data.message);
                    }
                } catch (error) {
                    console.error('Error in bulk update:', error);
                }
            }
        }

        function bulkActivate() { bulkUpdateStatus('ACTIVE'); }
        function bulkDeactivate() { bulkUpdateStatus('PENDING'); }
        function bulkSuspend() { bulkUpdateStatus('SUSPENDED'); }

        // Document Verification Functions
        async function fetchPendingDocuments() {
            try {
                const response = await fetch(`${ROOT}/user-admin/getPendingDocuments`);
                const data = await response.json();
                if (data.success) {
                    appState.documents = data.documents;
                    appState.selectedDocuments = [];
                    const selectAll = document.getElementById('select-all-docs');
                    if (selectAll) selectAll.checked = false;
                    updateDocumentBulkButtons(['bulk-approve-docs', 'bulk-reject-docs']);
                    renderDocumentsTable();
                }
            } catch (error) {
                console.error('Error fetching documents:', error);
            }
        }

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
                        <input type="checkbox" class="doc-checkbox" data-doc-id="${doc.id}" data-entity-type="${doc.entity_type}">
                        <span style="margin-left: 0.5rem;">
                            <strong>${doc.doc_id}</strong><br>
                            <small>${doc.first_name} ${doc.last_name} - ${doc.entity_type}</small>
                        </span>
                    </div>
                    <div class="table-cell" data-label="Type">${doc.type}</div>
                    <div class="table-cell status" data-label="Status">
                        <span class="status-badge status-${doc.status.toLowerCase()}">${doc.status}</span>
                    </div>
                    <div class="table-cell" data-label="Submitted">${new Date(doc.date).toLocaleDateString()}</div>
                    <div class="table-cell" data-label="Actions">
                        ${doc.status === 'PENDING' ? `
                            <button class="btn btn-success btn-small" onclick="approveDocument('${doc.entity_type}', ${doc.id})">Approve</button>
                            <button class="btn btn-danger btn-small" onclick="rejectDocument('${doc.entity_type}', ${doc.id})">Reject</button>
                        ` : `
                            <button class="btn btn-secondary btn-small" onclick="viewDocument('${doc.id}')">View</button>
                        `}
                    </div>
                `;
                tableContent.appendChild(row);
            });

            document.querySelectorAll('.doc-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', updateSelectedDocuments);
            });
        }

        async function approveDocument(entityType, id) {
            try {
                const response = await fetch(`${ROOT}/user-admin/updateEntityVerification`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ entity_type: entityType, id: id, status: 'APPROVED' })
                });
                const data = await response.json();
                if (data.success) {
                    showToast('success', data.message);
                    fetchPendingDocuments();
                    fetchDashboardStats();
                }
            } catch (error) {
                console.error('Error approving document:', error);
            }
        }

        async function rejectDocument(entityType, id) {
            const reason = prompt('Please provide reason for rejection:');
            if (reason) {
                try {
                    const response = await fetch(`${ROOT}/user-admin/updateEntityVerification`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ entity_type: entityType, id: id, status: 'REJECTED' })
                    });
                    const data = await response.json();
                    if (data.success) {
                        showToast('warning', data.message);
                        fetchPendingDocuments();
                        fetchDashboardStats();
                    }
                } catch (error) {
                    console.error('Error rejecting document:', error);
                }
            }
        }

        async function bulkApproveDocuments() {
            if (appState.selectedDocuments.length === 0) return;
            
            if (confirm(`Approve ${appState.selectedDocuments.length} document(s)?`)) {
                try {
                    const entities = appState.selectedDocuments.map(docId => {
                        const doc = appState.documents.find(d => d.id === docId);
                        return { id: doc.id, entity_type: doc.entity_type };
                    });

                    const response = await fetch(`${ROOT}/user-admin/bulkUpdateEntityVerification`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ entities: entities, status: 'APPROVED' })
                    });
                    const data = await response.json();
                    if (data.success) {
                        showToast('success', data.message);
                        appState.selectedDocuments = [];
                        const selectAll = document.getElementById('select-all-docs');
                        if (selectAll) selectAll.checked = false;
                        fetchPendingDocuments();
                        fetchDashboardStats();
                    } else {
                        showToast('error', data.message);
                    }
                } catch (error) {
                    console.error('Error in bulk approve:', error);
                }
            }
        }

        async function bulkRejectDocuments() {
            if (appState.selectedDocuments.length === 0) return;
            
            const reason = prompt('Please provide reason for rejection:');
            if (reason) {
                try {
                    const entities = appState.selectedDocuments.map(docId => {
                        const doc = appState.documents.find(d => d.id === docId);
                        return { id: doc.id, entity_type: doc.entity_type };
                    });

                    const response = await fetch(`${ROOT}/user-admin/bulkUpdateEntityVerification`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ entities: entities, status: 'REJECTED' })
                    });
                    const data = await response.json();
                    if (data.success) {
                        showToast('warning', data.message);
                        appState.selectedDocuments = [];
                        const selectAll = document.getElementById('select-all-docs');
                        if (selectAll) selectAll.checked = false;
                        fetchPendingDocuments();
                        fetchDashboardStats();
                    } else {
                        showToast('error', data.message);
                    }
                } catch (error) {
                    console.error('Error in bulk reject:', error);
                }
            }
        }

        function updateSelectedDocuments() {
            const checkboxes = document.querySelectorAll('.doc-checkbox:checked');
            appState.selectedDocuments = Array.from(checkboxes).map(cb => parseInt(cb.dataset.docId));
            updateDocumentBulkButtons(['bulk-approve-docs', 'bulk-reject-docs']);
        }

        function updateDocumentBulkButtons(buttonIds) {
            buttonIds.forEach(id => {
                const button = document.getElementById(id);
                if (button) {
                    button.disabled = appState.selectedDocuments.length === 0;
                }
            });
        }

        function toggleSelectAllDocs() {
            const selectAll = document.getElementById('select-all-docs');
            if (!selectAll) return;
            const checkboxes = document.querySelectorAll('.doc-checkbox');
            checkboxes.forEach(cb => cb.checked = selectAll.checked);
            updateSelectedDocuments();
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

        async function fetchNotifications() {
            try {
                const response = await fetch(`${ROOT}/user-admin/getNotifications`);
                const data = await response.json();
                if (data.success) {
                    appState.notifications = data.notifications;
                    renderNotificationsTable();
                }
            } catch (error) {
                console.error('Error fetching notifications:', error);
            }
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
                        <small>${notification.title}</small>
                    </div>
                    <div class="table-cell" data-label="Type">${notification.type}</div>
                    <div class="table-cell status" data-label="Status">
                        <span class="status-badge status-${notification.is_read ? 'active' : 'pending'}">${notification.is_read ? 'Read' : 'Unread'}</span>
                    </div>
                    <div class="table-cell" data-label="Sent">${new Date(notification.created_at).toLocaleString()}</div>
                    <div class="table-cell" data-label="Actions">
                        <button class="btn btn-secondary btn-small" onclick="alert('${notification.message.replace(/'/g, "\\'")}')">View</button>
                    </div>
                `;
                tableContent.appendChild(row);
            });
        }

        async function sendUserNotification(userId, title, message) {
            try {
                const response = await fetch(`${ROOT}/user-admin/sendNotification`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ user_id: userId, title: title, message: message })
                });
                const data = await response.json();
                if (data.success) {
                    showToast('success', 'Notification sent');
                    fetchNotifications();
                }
            } catch (error) {
                console.error('Error sending notification:', error);
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
                            <small>ID: DNR-${String(donor.id).padStart(3, '0')} | Blood Type: ${donor.bloodType}</small>
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

        // Removed user-form event listener

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

        // Feedback Functions
function renderFeedbacksTable() {
    const tableContent = document.getElementById('feedbacks-table');
    const headerRow = tableContent.querySelector('.table-row');
    
    tableContent.innerHTML = '';
    tableContent.appendChild(headerRow);

    appState.feedbacks.forEach(feedback => {
        const row = document.createElement('div');
        row.className = 'table-row';
        row.innerHTML = `
            <div class="table-cell name" data-label="User">
                <input type="checkbox" class="feedback-checkbox" data-feedback-id="${feedback.id}">
                <span style="margin-left: 0.5rem;">
                    <strong>${feedback.name}</strong><br>
                    <small>${feedback.email}</small>
                </span>
            </div>
            <div class="table-cell" data-label="Message">
                <div style="max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                    ${feedback.message}
                </div>
            </div>
            <div class="table-cell" data-label="Date">${feedback.date}</div>
            <div class="table-cell" data-label="Actions">
                <button class="btn btn-primary btn-small" onclick="viewFeedback(${feedback.id})">View</button>
                <button class="btn btn-danger btn-small" onclick="deleteFeedback(${feedback.id})">Delete</button>
            </div>
        `;
        tableContent.appendChild(row);
    });

    document.querySelectorAll('.feedback-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedFeedbacks);
    });
}

let currentFeedbackId = null;

function viewFeedback(feedbackId) {
    const feedback = appState.feedbacks.find(f => f.id === feedbackId);
    if (feedback) {
        currentFeedbackId = feedbackId;
        document.getElementById('feedback-from').textContent = feedback.name;
        document.getElementById('feedback-email').textContent = feedback.email;
        document.getElementById('feedback-message').textContent = feedback.message;
        document.getElementById('feedback-date').textContent = feedback.date;
        
        document.getElementById('feedback-modal').classList.add('show');
    }
}

function deleteFeedback(feedbackId) {
    if (confirm('Are you sure you want to delete this feedback?')) {
        appState.feedbacks = appState.feedbacks.filter(f => f.id !== feedbackId);
        renderFeedbacksTable();
        closeModal('feedback-modal');
        showToast('warning', 'Feedback deleted successfully.');
    }
}

function toggleSelectAllFeedbacks() {
    const selectAll = document.getElementById('select-all-feedbacks');
    const checkboxes = document.querySelectorAll('.feedback-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
    
    updateSelectedFeedbacks();
}

function updateSelectedFeedbacks() {
    const checkboxes = document.querySelectorAll('.feedback-checkbox:checked');
    appState.selectedFeedbacks = Array.from(checkboxes).map(cb => parseInt(cb.dataset.feedbackId));
    
    const deleteBtn = document.getElementById('bulk-delete-feedbacks');
    if (deleteBtn) deleteBtn.disabled = appState.selectedFeedbacks.length === 0;
}

function bulkDeleteFeedbacks() {
    if (appState.selectedFeedbacks.length === 0) return;
    
    if (confirm(`Delete ${appState.selectedFeedbacks.length} feedback message(s)?`)) {
        appState.feedbacks = appState.feedbacks.filter(f => !appState.selectedFeedbacks.includes(f.id));
        renderFeedbacksTable();
        showToast('warning', `${appState.selectedFeedbacks.length} feedback(s) deleted.`);
        appState.selectedFeedbacks = [];
        document.getElementById('select-all-feedbacks').checked = false;
    }
}



        // Initialize Dashboard
        document.addEventListener('DOMContentLoaded', function() {
            // initDashboard is already called via window.onload
        });

        // Auto-hide notifications
        setTimeout(() => {
            const notifications = document.querySelectorAll('.notification.show');
            notifications.forEach(notification => {
                notification.classList.remove('show');
            });
        }, 10000);
    </script>
</body>
</html>