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
.summary-section {
  margin: 25px 0;
  background: #ffffff;
  padding: 25px;
  border-radius: 14px;
  border: 1px solid #e2e8f0;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

.activity-feed {
  margin: 25px 0;
  padding: 0;
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
  flex-direction: column;
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
  width: 100%;
  margin-top: 0;
  border-top: 1px solid #e1edff;
  border-left: none;
  padding-top: 20px;
  padding-left: 0;
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 8px 24px;
}
.chart-card--distribution .chart-legend .legend-item {
  margin: 0;
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

/* ====== Pure CSS Doughnut Chart ====== */
.css-doughnut {
  width: 180px;
  height: 180px;
  border-radius: 50%;
  background: conic-gradient(#3b82f6 0% 0%, #10b981 0% 0%, #8b5cf6 0% 0%, #f59e0b 0% 0%, #ef4444 0% 100%);
  position: relative;
  display: flex;
  justify-content: center;
  align-items: center;
  margin: 0 auto;
  transition: background 0.4s ease-out;
}

.css-doughnut-inner {
  width: 100px;
  height: 100px;
  background: #ffffff;
  border-radius: 50%;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  position: absolute;
  box-shadow: inset 0px 0px 8px rgba(0,0,0,0.05);
  pointer-events: none;
}

.chart-tooltip {
  position: absolute;
  background: rgba(15, 23, 42, 0.9);
  color: white;
  padding: 8px 12px;
  border-radius: 6px;
  font-size: 0.8rem;
  pointer-events: none;
  opacity: 0;
  transition: opacity 0.2s;
  z-index: 100;
  white-space: nowrap;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
  transform: translate(-50%, -100%);
  margin-top: -10px;
}

/* ====== Bar Chart Section ====== */
.bar-chart-container {
  position: relative;
  height: 200px;
  margin-top: 10px;
  margin-bottom: 30px; /* Space for labels */
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
  position: absolute;
  bottom: -30px;
  left: 50%;
  transform: translateX(-50%);
  color: #1e40af;
  font-size: 0.85rem;
  white-space: nowrap;
}

/* ====== Chart Stats (below bar chart) ====== */
.chart-stats {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
  margin-top: 35px;
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
  font-size: 1.15rem;
  font-weight: 700;
  margin-bottom: 20px;
  display: flex;
  align-items: center;
  gap: 10px;
}

.activity-title::before {
  content: '';
  display: block;
  width: 4px;
  height: 18px;
  background: #005baa;
  border-radius: 2px;
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

.activity-icon.success { background: #ecfdf5; color: #059669; }
.activity-icon.info { background: #eff6ff; color: #3b82f6; }
.activity-icon.warning { background: #fffbeb; color: #d97706; }
.activity-icon.error { background: #fee2e2; color: #dc2626; }

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
                <a href="<?= ROOT ?>" style="text-decoration:none; display:flex; align-items:center; gap:10px;">
                    <img src="<?= ROOT ?>/public/assets/images/logo.png" alt="LifeConnect" style="height:40px;">
                    <div>
                        <strong style="display:block; font-size:1.1rem; color:#003b6e; line-height:1.2;">LifeConnect</strong>
                        <p style="margin:0; font-size:.68rem; color:#6b7280;">User Administration</p>
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
                        <span class="user-role"><?= isset($_SESSION['role']) && $_SESSION['role'] === 'U_ADMIN' ? 'User Admin' : 'System Admin' ?></span>
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
                    <div class="stat-number quick-stat-number" id="stat-total-users" >0</div>
                    <div class="stat-label">Total Users</div>
                    <div class="stat-change positive" id="change-total-users"></div>
                </div>
                <div class="stat-card glass-card">
                    <div class="stat-number quick-stat-number" id="stat-pending-docs" style="color: #dc2626;">0</div>
                    <div class="stat-label">Pending Verifications</div>
                    <div class="stat-change positive" id="change-pending-docs"></div>
                </div>
                <div class="stat-card glass-card">
                    <div class="stat-number quick-stat-number" id="stat-suspended-users" style="color: #dc2626;">0</div>
                    <div class="stat-label">Suspended Accounts</div>
                    <div class="stat-change negative" id="change-suspended-users" style="color: #dc2626;"></div>
                </div>
                <div class="stat-card glass-card">
                    <div class="stat-number" id="stat-active-users" style="color: #059669;">0</div>
                    <div class="stat-label">Total Active Users</div>
                    <div class="stat-change positive" id="change-active-users" style="color: #059669;"></div>
                </div>
            </div>
            <div class="charts-section">
                <div class="chart-card chart-card--distribution">
                    <h3 class="chart-title">User Distribution</h3>
                    <div class="chart-body">
                        <div class="doughnut-container" style="position: relative; display: flex; justify-content: center;">
                            <div id="css-user-chart" class="css-doughnut">
                                <div class="css-doughnut-inner">
                                    <div id="css-doughnut-total" style="color: #005baa; font-size: 20px; font-weight: bold;">0</div>
                                    <div style="color: #718096; font-size: 12px;">Total Users</div>
                                </div>
                            </div>
                            <div id="chart-tooltip" class="chart-tooltip"></div>
                        </div>
                        <div class="chart-legend">
                            <div class="legend-item"><div class="legend-left"><div class="legend-color" style="background:#005baa"></div>Donors</div><div class="legend-count">0</div></div>
                            <div class="legend-item"><div class="legend-left"><div class="legend-color" style="background:#a4c8e1"></div>Patients</div><div class="legend-count">0</div></div>
                            <div class="legend-item"><div class="legend-left"><div class="legend-color" style="background:#059669"></div>Custodians</div><div class="legend-count">0</div></div>
                            <div class="legend-item"><div class="legend-left"><div class="legend-color" style="background:#74b9ff"></div>Hospitals</div><div class="legend-count">0</div></div>
                            <div class="legend-item"><div class="legend-left"><div class="legend-color" style="background:#e8f5e8"></div>Medical Schools</div><div class="legend-count">0</div></div>
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
                <div style="padding: 20px; text-align: center; color: #64748b;">Loading recent activity...</div>
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
                                    <button class="btn btn-danger" id="bulk-suspend" onclick="bulkSuspend()" disabled style="padding: 0.4rem 0.8rem; font-size: 0.85rem;">Bulk Suspend</button>
                                </div>
                            </div>
                            <div class="table-content" id="users-table">
                                <div class="table-row" style="font-weight: 600; background: var(--gray-bg-color);">
                                    <div class="table-cell name">
                                        <input type="checkbox" id="select-all" onchange="toggleSelectAll()"> User Details
                                    </div>
                                    <div class="table-cell">Role</div>
                                    <div class="table-cell">Status</div>
                                    <div class="table-cell">Registration Date</div>
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

    <!-- Review User Modal (Detailed Premium View) -->
    <div id="review-user-modal" class="modal">
        <div class="modal-content" style="padding: 2.5rem; border-radius: 24px; max-width: 500px;">
            <!-- Modal Header with Icon (Simplified Horizontal Layout) -->
            <div style="display: flex; flex-direction: column; gap: 1.25rem; position: relative;">
                <button class="modal-close" style="position: absolute; top: -15px; right: -15px; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 50%; background: #f1f5f9; border: none; cursor: pointer; color: #64748b; z-index: 10;" onclick="closeModal('review-user-modal')">&times;</button>
                
                <div style="display: flex; align-items: center; gap: 1.25rem;">
                    <!-- Status Icon -->
                    <div id="review-status-icon-box" style="flex-shrink: 0; width: 48px; height: 48px; background: #fee2e2; border-radius: 12px; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease;">
                        <i id="review-status-icon" class="fa-solid fa-circle-xmark" style="font-size: 20px; color: #dc2626;"></i>
                    </div>

                    <!-- Title -->
                    <div>
                        <h2 id="review-modal-title" style="margin: 0; font-size: 1.5rem; font-weight: 800; color: #0f172a; line-height: 1.2;">Review Account</h2>
                    </div>
                </div>

                <p style="margin: 0; color: #64748b; font-size: 0.9rem; line-height: 1.5; font-weight: 500;">Please review the information below and update the account status. This will be saved permanently.</p>

                <!-- Core Details Card (2-Column Grid) -->
                <div id="modal-summary-card" style="background: #f0f7ff; border-radius: 16px; padding: 1.5rem; display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <!-- Standard Fields -->
                    <div>
                        <span style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Username</span>
                        <div id="review-username-text" style="font-size: 1.05rem; font-weight: 700; color: #1e293b;">-</div>
                    </div>
                    <div>
                        <span style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Role/Reg Date</span>
                        <div id="review-user-role-display" style="font-size: 0.95rem; font-weight: 600; color: #334155;">-</div>
                        <div id="review-regdate-text" style="font-size: 0.8rem; color: #64748b;">-</div>
                    </div>
                    
                    <div>
                        <span style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Email Address</span>
                        <div id="review-email-text" style="font-size: 0.9rem; font-weight: 600; color: #1e293b; word-break: break-all;">-</div>
                    </div>
                    <div id="review-summary-phone">
                        <span style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Phone Number</span>
                        <div id="review-phone-text" style="font-size: 0.95rem; font-weight: 600; color: #1e293b;">-</div>
                    </div>

                    <!-- Donor Identity Section (Conditional Grid Row) -->
                    <div id="donor-identity-section" style="display: contents;">
                        <div>
                            <span id="label-fullname" style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Full Name</span>
                            <div id="review-fullname-text" style="font-size: 0.95rem; font-weight: 600; color: #1e293b;">-</div>
                        </div>
                        <div>
                            <span id="label-nic" style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">NIC Number</span>
                            <div id="review-nic-text" style="font-size: 0.95rem; font-weight: 600; color: #1e293b;">-</div>
                        </div>
                        <div id="review-gender-group">
                            <span style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Gender</span>
                            <div id="review-gender-text" style="font-size: 0.95rem; font-weight: 600; color: #1e293b;">-</div>
                        </div>
                        <div id="review-dob-group">
                            <span style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Date of Birth</span>
                            <div id="review-dob-text" style="font-size: 0.95rem; font-weight: 600; color: #1e293b;">-</div>
                        </div>
                    </div>

                    <!-- Hospital Identity Section (Conditional Grid Row) -->
                    <div id="hospital-identity-section" style="display: none; contents;">
                        <div style="grid-column: span 2;">
                            <span style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Hospital Official Name</span>
                            <div id="review-hosp-name" style="font-size: 1rem; font-weight: 800; color: #1e293b;">-</div>
                        </div>
                        <div>
                            <span style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Registration Number</span>
                            <div id="review-hosp-reg" style="font-size: 0.95rem; font-weight: 600; color: #1e293b;">-</div>
                        </div>
                        <div>
                            <span style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Transplant ID</span>
                            <div id="review-hosp-transplant" style="font-size: 0.95rem; font-weight: 600; color: #1e293b;">-</div>
                        </div>
                        <div>
                            <span style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Facility Type</span>
                            <div id="review-hosp-type" style="font-size: 0.95rem; font-weight: 600; color: #1e293b;">-</div>
                        </div>
                        <div>
                            <span style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Medical License Number</span>
                            <div id="review-hosp-license" style="font-size: 0.95rem; font-weight: 600; color: #1e293b;">-</div>
                        </div>
                        <div>
                            <span style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Chief Medical Officer (CMO)</span>
                            <div id="review-hosp-cmo-name" style="font-size: 0.95rem; font-weight: 600; color: #1e293b;">-</div>
                        </div>
                        <div>
                            <span style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">CMO NIC Number</span>
                            <div id="review-hosp-cmo-nic" style="font-size: 0.95rem; font-weight: 600; color: #1e293b;">-</div>
                        </div>
                        <div>
                            <span style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">District</span>
                            <div id="review-hosp-district" style="font-size: 0.95rem; font-weight: 600; color: #1e293b;">-</div>
                        </div>
                        <div>
                            <span style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Clinical Contact Number</span>
                            <div id="review-hosp-phone" style="font-size: 0.95rem; font-weight: 600; color: #1e293b;">-</div>
                        </div>
                        <div style="grid-column: span 2;">
                            <span style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Hospital Address</span>
                            <div id="review-hosp-address" style="font-size: 0.95rem; font-weight: 600; color: #1e293b;">-</div>
                        </div>
                    </div>

                    <!-- Admin Identity Section -->
                    <div id="admin-identity-section" style="display: none; contents;">
                        <div>
                            <span style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Staff ID</span>
                            <div id="review-admin-staff-id" style="font-size: 0.95rem; font-weight: 600; color: #1e293b;">-</div>
                        </div>
                        <div>
                            <span style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Designation</span>
                            <div id="review-admin-designation" style="font-size: 0.95rem; font-weight: 600; color: #1e293b;">-</div>
                        </div>
                        <div style="grid-column: span 2;">
                            <span style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Internal Contact Number</span>
                            <div id="review-admin-contact" style="font-size: 0.95rem; font-weight: 600; color: #1e293b;">-</div>
                        </div>
                    </div>

                    <!-- Organ Donor Administrative Section (Conditional Grid Row) -->
                    <div id="organ-donor-section" style="display: contents;">
                        <div>
                            <span style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">District / DS Division</span>
                            <div id="review-location-text" style="font-size: 0.95rem; font-weight: 600; color: #1e293b;">-</div>
                        </div>
                        <div>
                            <span style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">GN Division</span>
                            <div id="review-gn-text" style="font-size: 0.95rem; font-weight: 600; color: #1e293b;">-</div>
                        </div>
                        <div style="grid-column: span 2;">
                            <span style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Residential Address</span>
                            <div id="review-address-text" style="font-size: 0.95rem; font-weight: 600; color: #1e293b;">-</div>
                        </div>
                    </div>
                </div>

                <!-- Hidden Controls -->
                <input type="hidden" id="review-user-id">
                <input type="hidden" id="review-user-role">
                <input type="hidden" id="review-user-status">
                <!-- Data storage for other fields -->
                <input type="hidden" id="review-firstname">
                <input type="hidden" id="review-lastname">
                <input type="hidden" id="review-phone">

                <!-- Action Section -->
                <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                    
                    <!-- Verification Options -->
                    <div id="verification-section" style="display:none; background: #fffcf0; border-left: 4px solid #fbbf24; padding: 1rem; border-radius: 12px;">
                        <span style="display: block; font-size: 0.7rem; font-weight: 800; color: #92400e; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px;">Manual Verification</span>
                        
                        <!-- Common Check (Top) -->
                        <div style="margin-bottom: 8px;">
                            <label style="display: flex; align-items: center; gap: 10px; font-size: 0.9rem; color: #1e293b; cursor: pointer; font-weight: 600;">
                                <input type="checkbox" id="verify-genuine" onchange="checkVerificationStatus(); generateReviewMessage()" style="width: 18px; height: 18px; cursor: pointer;">
                                Profile Information & Data Authenticity
                            </label>
                        </div>

                        <!-- Donor Specific (NIC) -->
                        <div id="donor-verification-controls" style="display: none;">
                            <label style="display: flex; align-items: center; gap: 10px; font-size: 0.9rem; color: #1e293b; cursor: pointer; font-weight: 600;">
                                <input type="checkbox" id="verify-registry" onchange="checkVerificationStatus(); generateReviewMessage()" style="width: 18px; height: 18px; cursor: pointer;">
                                <span>Verified NIC via Election Commission Registry <a href="https://eservices.elections.gov.lk/pages/myVoterRegistrationSearch.aspx" target="_blank" style="color: #3b82f6; text-decoration: underline; font-size: 0.75rem; margin-left: 4px; font-weight: 700;"><i class="fa-solid fa-arrow-up-right-from-square" style="font-size: 0.65rem;"></i></a></span>
                            </label>
                        </div>

                        <!-- Hospital Specific (PHSRC) -->
                        <div id="hospital-verification-controls" style="display: none;">
                            <label style="display: flex; align-items: center; gap: 10px; font-size: 0.9rem; color: #1e293b; cursor: pointer; font-weight: 600;">
                                <input type="checkbox" id="verify-hospital-registry" onchange="checkVerificationStatus(); generateReviewMessage()" style="width: 18px; height: 18px; cursor: pointer;">
                                <span>Verified via Private Health Services (PHSRC) <a href="https://www.phsrc.lk/" target="_blank" style="color: #3b82f6; text-decoration: underline; font-size: 0.75rem; margin-left: 4px; font-weight: 700;"><i class="fa-solid fa-arrow-up-right-from-square" style="font-size: 0.65rem;"></i></a></span>
                            </label>
                        </div>
                    </div>

                    <!-- Status Dropdown -->
                    <div>
                        <span style="display: block; font-size: 0.7rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px;">Update Account Status *</span>
                        <select class="form-select" id="review-status-dropdown" onchange="generateReviewMessage(); checkVerificationStatus()" style="width: 100%; padding: 0.75rem; border-radius: 10px; border: 1.5px solid #e2e8f0; font-weight: 600; color: #0f172a; outline: none; transition: border-color 0.2s;">
                            <option value="PENDING">Pending</option>
                            <option value="ACTIVE">Active</option>
                            <option value="SUSPENDED">Suspended</option>
                        </select>
                    </div>

                    <!-- Review Notes -->
                    <div>
                        <span style="display: block; font-size: 0.7rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px;">Review Notes / Reason *</span>
                        <textarea id="review-message" rows="3" placeholder="Provide a reason for the update..." style="width: 100%; padding: 0.75rem; border-radius: 10px; border: 1.5px solid #e2e8f0; font-size: 0.9rem; color: #1e293b; outline: none; transition: border-color 0.2s; resize: none;"></textarea>
                    </div>

                    <!-- Footer Buttons (Right Aligned) -->
                    <div style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 0.5rem;">
                        <button type="button" onclick="closeModal('review-user-modal')" style="background: #f1f5f9; color: #475569; border: none; padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 700; cursor: pointer; transition: background 0.2s;">Cancel</button>
                        <button type="button" id="btn-save-details" onclick="submitUserReview('UPDATE')" style="background: #dc2626; color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: all 0.2s;">
                            <i id="btn-save-icon" class="fa-solid fa-circle-xmark"></i>
                            <span id="btn-save-text">Confirm Rejection</span>
                        </button>
                    </div>
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

    <!-- Bulk Action Authorization Modal -->
    <div id="bulk-action-modal" class="modal">
        <div class="modal-content" style="padding: 2.5rem; border-radius: 24px; max-width: 500px;">
            <div style="display: flex; flex-direction: column; gap: 1.25rem; position: relative;">
                <button class="modal-close" style="position: absolute; top: -15px; right: -15px; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 50%; background: #f1f5f9; border: none; cursor: pointer; color: #64748b; z-index: 10;" onclick="closeModal('bulk-action-modal')">&times;</button>
                
                <div style="display: flex; align-items: center; gap: 1.25rem;">
                    <div id="bulk-status-icon-box" style="flex-shrink: 0; width: 48px; height: 48px; background: #ecfdf5; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                        <i id="bulk-status-icon" class="fa-solid fa-circle-check" style="font-size: 20px; color: #059669;"></i>
                    </div>
                    <div>
                        <h2 id="bulk-modal-title" style="margin: 0; font-size: 1.5rem; font-weight: 800; color: #0f172a; line-height: 1.2;">Authorize Bulk Action</h2>
                    </div>
                </div>

                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 0.75rem 1rem; display: flex; align-items: center; gap: 10px;">
                    <span id="bulk-user-count-badge" style="background: #3b82f6; color: white; padding: 2px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 800;">0 Users</span>
                    <span style="color: #64748b; font-size: 0.85rem; font-weight: 600;">Selected for status update</span>
                </div>

                <p id="bulk-modal-desc" style="margin: 0; color: #64748b; font-size: 0.9rem; line-height: 1.5; font-weight: 500;">Authorize the following status transition and customize the review notification message below.</p>

                <!-- Editable Message Section -->
                <div>
                    <span style="display: block; font-size: 0.7rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px;">Review Message (Editable)</span>
                    <textarea id="bulk-review-message" rows="4" style="width: 100%; padding: 0.75rem; border-radius: 10px; border: 1.5px solid #e2e8f0; font-size: 0.9rem; color: #1e293b; outline: none; transition: border-color 0.2s; resize: none; font-weight: 500; line-height: 1.4;"></textarea>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 0.5rem;">
                    <button type="button" onclick="closeModal('bulk-action-modal')" style="background: #f1f5f9; color: #475569; border: none; padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 700; cursor: pointer;">Cancel</button>
                    <button type="button" id="btn-confirm-bulk" onclick="executeBulkUpdate()" style="background: #059669; color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: all 0.2s;">
                        <i id="bulk-confirm-icon" class="fa-solid fa-circle-check"></i>
                        <span id="bulk-confirm-text">Confirm Activation</span>
                    </button>
                </div>
            </div>
            <!-- Hidden context for the update -->
            <input type="hidden" id="bulk-target-status">
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
    setText('stat-suspended-users', (Number(stats.status_SUSPENDED || stats.status_suspended || 0)));
    setText('stat-active-users', (Number(stats.status_ACTIVE || stats.status_active || 0)));
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
    setChange('change-suspended-users', stats.suspendedThisMonth ?? 0);
    setChange('change-active-users', stats.activeThisMonth ?? 0);
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

    // Update Activity Feed
    if (stats.activities) {
        renderActivityFeed(stats.activities);
    }
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
        const heightPercent = (data.count / maxVal) * 80; // scale to 80% max height
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
        { label: "Custodians", value: Number(stats.role_CUSTODIAN || 0), color: "#059669" },
        { label: "Hospitals", value: Number(stats.role_HOSPITAL || 0), color: "#74b9ff" },
        { label: "Medical Schools", value: Number(stats.role_MEDICAL_SCHOOL || 0), color: "#e8f5e8" }
    ];
    
    // Update HTML legend counts
    const legendCounts = document.querySelectorAll('.chart-legend .legend-count');
    if (legendCounts.length >= 5) {
        legendCounts[0].textContent = stats.role_DONOR || 0;
        legendCounts[1].textContent = stats.role_PATIENT || 0;
        legendCounts[2].textContent = stats.role_CUSTODIAN || 0;
        legendCounts[3].textContent = stats.role_HOSPITAL || 0;
        legendCounts[4].textContent = stats.role_MEDICAL_SCHOOL || 0;
    }

    drawCssDoughnutChart(data);
}

function drawCssDoughnutChart(data) {
    const chart = document.getElementById('css-user-chart');
    const totalEl = document.getElementById('css-doughnut-total');
    const tooltip = document.getElementById('chart-tooltip');
    if (!chart || !totalEl || !tooltip) return;

    const total = data.reduce((sum, d) => sum + d.value, 0);
    totalEl.textContent = total.toLocaleString();

    if (total === 0) {
        chart.style.background = 'conic-gradient(#e2e8f0 0% 100%)';
        chart.onmousemove = null;
        chart.onmouseleave = null;
        return;
    }

    let gradientParts = [];
    let currentAngle = 0;
    const slices = [];

    data.forEach(d => {
        if (d.value === 0) return;
        const percentage = (d.value / total) * 100;
        const nextAngle = currentAngle + percentage;
        
        gradientParts.push(`${d.color} ${currentAngle}% ${nextAngle}%`);
        
        slices.push({
            label: d.label,
            value: d.value,
            percent: percentage.toFixed(1),
            start: currentAngle,
            end: nextAngle
        });
        
        currentAngle = nextAngle;
    });

    chart.style.background = `conic-gradient(${gradientParts.join(', ')})`;
    
    // Add Tooltip Hover Logic
    chart.onmousemove = (e) => {
        const rect = chart.getBoundingClientRect();
        const centerX = rect.width / 2;
        const centerY = rect.height / 2;
        
        const x = e.clientX - rect.left - centerX;
        const y = e.clientY - rect.top - centerY;
        const distance = Math.sqrt(x*x + y*y);
        
        // Only trigger if mouse is between the inner circle (hole, radius 50) and outer edge (radius 90)
        if (distance < 50 || distance > 90) {
            tooltip.style.opacity = 0;
            return;
        }

        // Calculate angle (Top is 0 degrees, clockwise)
        let angleDeg = Math.atan2(y, x) * (180 / Math.PI) + 90;
        if (angleDeg < 0) angleDeg += 360;
        
        const anglePercent = (angleDeg / 360) * 100;
        const found = slices.find(s => anglePercent >= s.start && anglePercent <= s.end);
        
        if (found) {
            tooltip.innerHTML = `<div style="font-weight: 700; margin-bottom: 2px;">${found.label}</div>
                                 <div style="font-size: 0.8rem; color: #cbd5e1;">${found.value.toLocaleString()} users (${found.percent}%)</div>`;
            
            tooltip.style.left = (e.clientX - rect.left + chart.offsetLeft) + 'px';
            tooltip.style.top = (e.clientY - rect.top + chart.offsetTop) + 'px';
            tooltip.style.opacity = 1;
        } else {
            tooltip.style.opacity = 0;
        }
    };
    
    chart.onmouseleave = () => tooltip.style.opacity = 0;
}

// Initialize dashboard
function initDashboard() {
  fetchDashboardStats();
}

window.onload = initDashboard;

// Update activity feed every 30 seconds
async function updateActivityFeedServer() {
    try {
        const response = await fetch(`${ROOT}/user-admin/getDashboardStats`);
        const data = await response.json();
        if (data.success && data.stats.activities) {
            renderActivityFeed(data.stats.activities);
        }
    } catch (error) {
        console.error('Error auto-updating activity feed:', error);
    }
}

function renderActivityFeed(activities) {
    const feed = document.querySelector('.activity-feed');
    if (!feed) return;
    
    // Clear existing items but keep title
    const title = feed.querySelector('.activity-title');
    feed.innerHTML = '';
    if (title) feed.appendChild(title);
    
    if (!activities || activities.length === 0) {
        feed.insertAdjacentHTML('beforeend', '<div style="padding: 20px; text-align: center; color: #64748b;">No recent activity</div>');
        return;
    }

    activities.forEach(activity => {
        const item = document.createElement('div');
        item.className = 'activity-item';
        
        // Format time
        const date = new Date(activity.date);
        const now = new Date();
        const diff = Math.floor((now - date) / 1000); // seconds
        
        let timeStr = 'Just now';
        if (diff < 60) timeStr = diff + 's ago';
        else if (diff < 3600) timeStr = Math.floor(diff / 60) + 'm ago';
        else if (diff < 86400) timeStr = Math.floor(diff / 3600) + 'h ago';
        else timeStr = date.toLocaleDateString();

        item.innerHTML = `
            <div class="activity-icon ${activity.category}">
                <i class="fa-solid fa-${activity.type}"></i>
            </div>
            <div class="activity-content">
                <div class="activity-text">${activity.title}</div>
                <div class="activity-detail" style="font-size: 0.85rem; color: #64748b;">${activity.detail}</div>
                <div class="activity-time">${timeStr}</div>
            </div>
        `;
        feed.appendChild(item);
    });
}

setInterval(updateActivityFeedServer, 30000);

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
            updateBulkButtons();
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
        `;

        tableContent.appendChild(row);

        // Event listener for the whole row
        row.addEventListener('click', (e) => {
            if (!e.target.closest('button') && !e.target.closest('input[type="checkbox"]')) {
                console.log('Row clicked for user:', user.id);
                viewDetailedUser(user.id, user.role, user.status);
            }
        });

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
                    console.log("Detailed User Data Received:", user);
                    
                    try {
                        // Hidden storage for form submission details
                        document.getElementById('review-user-id').value = user.id;
                        document.getElementById('review-user-role').value = user.role || '';
                        document.getElementById('review-user-status').value = user.status || '';

                        // Populate Card View
                        document.getElementById('review-username-text').innerText = user.username || '-';
                        document.getElementById('review-email-text').innerText = user.email || 'N/A';
                        document.getElementById('review-phone-text').innerText = user.phone || 'No phone';
                        document.getElementById('review-regdate-text').innerText = user.created_at ? "Member since " + new Date(user.created_at).toLocaleDateString() : 'N/A';
                        document.getElementById('review-user-role-display').innerText = (user.role || 'USER').replace('_', ' ');
                        
                        const donorIdentity = document.getElementById('donor-identity-section');
                        const hospitalIdentity = document.getElementById('hospital-identity-section');
                        const summaryPhoneGroup = document.getElementById('review-summary-phone');
                        const organDonorSection = document.getElementById('organ-donor-section');
                        const deepDetails = document.getElementById('deep-details-section');

                        const isHospital = (user.role && user.role.toUpperCase() === 'HOSPITAL');
                        const isDonor = (user.role && user.role.toUpperCase() === 'DONOR');
                        const isAdmin = (user.role && ['ADMIN', 'U_ADMIN', 'F_ADMIN', 'AC_ADMIN', 'D_ADMIN'].includes(user.role.toUpperCase()));

                        // Reset display
                        if (donorIdentity) donorIdentity.style.display = 'none';
                        if (hospitalIdentity) hospitalIdentity.style.display = 'none';
                        const adminIdentity = document.getElementById('admin-identity-section');
                        if (adminIdentity) adminIdentity.style.display = 'none';
                        
                        if (summaryPhoneGroup) summaryPhoneGroup.style.display = 'block'; 
                        if (organDonorSection) organDonorSection.style.display = 'none';
                        if (deepDetails) deepDetails.style.display = 'none';

                        if (isDonor) {
                            if (donorIdentity) donorIdentity.style.display = 'contents';
                            if (organDonorSection) organDonorSection.style.display = 'contents';
                            if (deepDetails) deepDetails.style.display = 'grid';

                            const fullNameText = document.getElementById('review-fullname-text');
                            if (fullNameText) fullNameText.innerText = (user.first_name || '') + ' ' + (user.last_name || '');
                            
                            const nicText = document.getElementById('review-nic-text');
                            if (nicText) nicText.innerText = user.nic || 'N/A';

                            const genderText = document.getElementById('review-gender-text');
                            if (genderText) genderText.innerText = user.gender || 'N/A';
                            
                            const dobText = document.getElementById('review-dob-text');
                            if (dobText) dobText.innerText = user.dob || 'N/A';

                            const locText = document.getElementById('review-location-text');
                            if (locText) locText.innerText = (user.district || 'Unspecified') + ' • ' + (user.ds_division || 'N/A');
                            
                            const gnText = document.getElementById('review-gn-text');
                            if (gnText) gnText.innerText = user.gn_division || 'N/A';

                            const addrText = document.getElementById('review-address-text');
                            if (addrText) addrText.innerText = user.address || 'No address provided';

                        } else if (isHospital) {
                            if (hospitalIdentity) hospitalIdentity.style.display = 'contents';
                            if (summaryPhoneGroup) summaryPhoneGroup.style.display = 'none'; // Hide generic phone
                            
                            // Populate Hospital Fields
                            const hName = document.getElementById('review-hosp-name');
                            if (hName) hName.innerText = user.first_name || '-';

                            const hReg = document.getElementById('review-hosp-reg');
                            if (hReg) hReg.innerText = user.nic || '-';

                            const hTrans = document.getElementById('review-hosp-transplant');
                            if (hTrans) hTrans.innerText = user.transplant_id || 'N/A';

                            const hType = document.getElementById('review-hosp-type');
                            if (hType) hType.innerText = user.facility_type || 'N/A';

                            const hLicense = document.getElementById('review-hosp-license');
                            if (hLicense) hLicense.innerText = user.medical_license_number || 'N/A';

                            const hCmoName = document.getElementById('review-hosp-cmo-name');
                            if (hCmoName) hCmoName.innerText = user.cmo_name || '-';

                            const hCmoNic = document.getElementById('review-hosp-cmo-nic');
                            if (hCmoNic) hCmoNic.innerText = user.cmo_nic || '-';

                            const hDistrict = document.getElementById('review-hosp-district');
                            if (hDistrict) hDistrict.innerText = user.district || 'N/A';

                            const hPhone = document.getElementById('review-hosp-phone');
                            if (hPhone) hPhone.innerText = user.hospital_contact_number || 'N/A';

                            const hAddress = document.getElementById('review-hosp-address');
                            if (hAddress) hAddress.innerText = user.address || 'No address provided';
                        } else if (isAdmin) {
                            if (adminIdentity) adminIdentity.style.display = 'contents';
                            
                            const adminStaffID = document.getElementById('review-admin-staff-id');
                            if (adminStaffID) adminStaffID.innerText = user.staff_id || 'N/A';

                            const adminDesignation = document.getElementById('review-admin-designation');
                            if (adminDesignation) adminDesignation.innerText = user.designation || 'N/A';

                            const adminContact = document.getElementById('review-admin-contact');
                            if (adminContact) adminContact.innerText = user.admin_contact || 'N/A';
                        }

                        document.getElementById('review-firstname').value = user.first_name || user.school_name || user.name || '';
                        document.getElementById('review-lastname').value = user.last_name || '';
                        document.getElementById('review-phone').value = user.phone || '';
                        
                        document.getElementById('review-status-dropdown').value = (user.status || 'PENDING').toUpperCase();
                        document.getElementById('review-message').value = user.review_message || '';
                        
                        const verifSection = document.getElementById('verification-section');
                        const statusUpper = (user.status || '').toUpperCase();
                        if (verifSection) {
                            if (statusUpper === 'PENDING') {
                                verifSection.style.display = 'block';
                                document.getElementById('verify-genuine').checked = false;
                                document.getElementById('verify-registry').checked = false;
                                                  // Show role-specific verifications
                            const donorControls = document.getElementById('donor-verification-controls');
                            const hospitalControls = document.getElementById('hospital-verification-controls');
                            
                            if (user.role && user.role.toLowerCase() === 'donor') {
                                if (donorControls) donorControls.style.display = 'block';
                                if (hospitalControls) hospitalControls.style.display = 'none';
                            } else if (user.role && user.role.toLowerCase() === 'hospital') {
                                if (donorControls) donorControls.style.display = 'none';
                                if (hospitalControls) hospitalControls.style.display = 'block';
                                if (document.getElementById('hosp-reg-num-text')) {
                                    document.getElementById('hosp-reg-num-text').innerText = user.registration_number || 'N/A';
                                }
                            } else {
                                if (donorControls) donorControls.style.display = 'none';
                                if (hospitalControls) hospitalControls.style.display = 'none';
                            }
                        } else {
                                verifSection.style.display = 'none';
                                document.getElementById('verify-genuine').checked = (statusUpper === 'ACTIVE');
                                document.getElementById('verify-registry').checked = (statusUpper === 'ACTIVE');
                            }
                        }

                        checkVerificationStatus();
                        document.getElementById('review-user-modal').classList.add('show');
                    } catch (uiErr) {
                        console.error("UI Population Error:", uiErr);
                        showToast('error', 'Critical UI error. Check console.');
                    }
                }
            } catch (error) {
                console.error('Error fetching user details:', error);
                showToast('error', 'Failed to load user records.');
            }
        }

        function checkVerificationStatus() {
            const genuine = document.getElementById('verify-genuine').checked;
            const donorRegistry = document.getElementById('verify-registry').checked;
            const hospitalRegistry = document.getElementById('verify-hospital-registry').checked;
            
            const status = document.getElementById('review-status-dropdown').value;
            const originalStatus = document.getElementById('review-user-status').value;
            const userRoleElement = document.getElementById('review-user-role-display'); // We can parse role from here or appState
            const currentRole = userRoleElement ? userRoleElement.innerText.split('|')[0].trim().toLowerCase() : '';

            let canSave = false;
            if (status === 'ACTIVE') {
                if (originalStatus === 'PENDING') {
                    // Force verification for donors/hospitals
                    if (currentRole === 'donor') {
                        canSave = genuine && donorRegistry;
                    } else if (currentRole === 'hospital') {
                        canSave = genuine && hospitalRegistry;
                    } else {
                        canSave = true; // Other roles
                    }
                } else {
                    canSave = true;
                }
            } else if (status === 'SUSPENDED') {
                canSave = true;
            } else if (status === 'PENDING') {
                canSave = (originalStatus !== 'PENDING');
            }

            // Reversion UI logic
            const verifSection = document.getElementById('verification-section');
            if (verifSection) {
                if (status === 'PENDING') {
                    verifSection.style.display = 'block';
                    if (originalStatus !== 'PENDING') {
                        document.getElementById('verify-genuine').checked = false;
                        document.getElementById('verify-registry').checked = false;
                        document.getElementById('verify-hospital-registry').checked = false;
                    }

                    // Ensure correct role controls are shown on reversion select
                    const donorControls = document.getElementById('donor-verification-controls');
                    const hospitalControls = document.getElementById('hospital-verification-controls');
                    if (currentRole === 'donor') {
                        if (donorControls) donorControls.style.display = 'block';
                        if (hospitalControls) hospitalControls.style.display = 'none';
                    } else if (currentRole === 'hospital') {
                        if (donorControls) donorControls.style.display = 'none';
                        if (hospitalControls) hospitalControls.style.display = 'block';
                    }
                } else if (originalStatus !== 'PENDING') {
                    verifSection.style.display = 'none';
                }
            }

            const btnSave = document.getElementById('btn-save-details');
            const btnText = document.getElementById('btn-save-text');
            const btnIcon = document.getElementById('btn-save-icon');
            const iconBox = document.getElementById('review-status-icon-box');
            const icon = document.getElementById('review-status-icon');

            if (canSave) {
                btnSave.disabled = false;
                btnSave.style.opacity = '1';
                btnSave.style.cursor = 'pointer';
            } else {
                btnSave.disabled = true;
                btnSave.style.opacity = '0.5';
                btnSave.style.cursor = 'not-allowed';
            }

            // Dynamic Styling based on Status
            if (status === 'ACTIVE') {
                btnSave.style.background = '#059669'; // Emerald-600
                btnText.innerText = 'Confirm Approval';
                btnIcon.className = 'fa-solid fa-circle-check';
                iconBox.style.background = '#ecfdf5'; // Emerald-50
                icon.className = 'fa-solid fa-circle-check';
                icon.style.color = '#059669';
            } else if (status === 'SUSPENDED') {
                btnSave.style.background = '#dc2626'; // Red-600
                btnText.innerText = 'Confirm Removal';
                btnIcon.className = 'fa-solid fa-circle-xmark';
                iconBox.style.background = '#fee2e2'; // Red-50
                icon.className = 'fa-solid fa-circle-xmark';
                icon.style.color = '#dc2626';
            } else {
                btnSave.style.background = '#3b82f6'; // Blue-600
                btnText.innerText = 'Update Record';
                btnIcon.className = 'fa-solid fa-circle-info';
                iconBox.style.background = '#eff6ff'; // Blue-50
                icon.className = 'fa-solid fa-circle-info';
                icon.style.color = '#3b82f6';
            }
        }

        function generateReviewMessage() {
            const genuine = document.getElementById('verify-genuine').checked;
            const registry = document.getElementById('verify-registry').checked;
            const status = document.getElementById('review-status-dropdown').value;
            const msgBox = document.getElementById('review-message');
            
            // Don't overwrite if the admin has already typed something custom 
            // (Only auto-generate if message is empty or matches standard patterns)
            const currentMsg = msgBox.value.trim();
            const standardPatterns = [
                "", 
                "Account verified successfully. All documentation matches official records.",
                "Verification failed: Profile information and submitted details could not be validated for authenticity.",
                "Verification failed: NIC record could not be verified via the official Election Commission registry.",
                "Verification failed: Profile data authenticity concerns and NIC record could not be verified.",
                "Verification reset: This account has been returned to pending status for details re-evaluation.",
                "Account reactivated: Following administrative review, your access has been restored and all issues have been resolved.",
                "Account suspended for administrative review."
            ];
            
            if (currentMsg !== "" && !standardPatterns.includes(currentMsg)) return;

            if (status === 'ACTIVE') {
                 const originalStatus = document.getElementById('review-user-status').value;
                 if (originalStatus === 'SUSPENDED') {
                     msgBox.value = "Account reactivated: Following administrative review, your access has been restored and all issues have been resolved.";
                 } else {
                     msgBox.value = "Account verified successfully. All documentation matches official records.";
                 }
            } else if (status === 'SUSPENDED') {
                 const donorRegistry = document.getElementById('verify-registry').checked;
                 const hospitalRegistry = document.getElementById('verify-hospital-registry').checked;
                 const userRoleElement = document.getElementById('review-user-role-display');
                 const currentRole = userRoleElement ? userRoleElement.innerText.split('|')[0].trim().toLowerCase() : '';
                 
                 const registry = (currentRole === 'hospital') ? hospitalRegistry : donorRegistry;
                 const registryName = (currentRole === 'hospital') ? "Hospital PHSRC registry" : "official Election Commission registry";

                 if (!genuine && !registry) {
                     msgBox.value = `Verification failed: Profile data authenticity concerns and ${currentRole === 'hospital' ? 'Hospital license' : 'NIC record'} could not be verified.`;
                 } else if (!genuine) {
                     msgBox.value = "Verification failed: Profile information and submitted details could not be validated for authenticity.";
                 } else if (!registry) {
                     msgBox.value = `Verification failed: ${currentRole === 'hospital' ? 'Hospital registration' : 'NIC record'} could not be verified via the ${registryName}.`;
                 } else {
                     msgBox.value = "Account suspended for administrative review.";
                 }
            } else if (status === 'PENDING') {
                const originalStatus = document.getElementById('review-user-status').value;
                if (originalStatus !== 'PENDING') {
                    msgBox.value = "Verification reset: This account has been returned to pending status for details re-evaluation.";
                } else {
                    msgBox.value = "";
                }
            } else {
                 msgBox.value = "";
            }
        }

        async function submitUserReview(action) {
            const userId = document.getElementById('review-user-id').value;
            const role = document.getElementById('review-user-role').value;
            const newStatus = document.getElementById('review-status-dropdown').value;
            
            const data = {
                first_name: document.getElementById('review-firstname')?.value || '',
                last_name: document.getElementById('review-lastname')?.value || '',
                phone: document.getElementById('review-phone')?.value || ''
            };

            try {
                // If the dropdown status was changed, we map it back to APPROVE/REJECT for the existing controller logic
                // or we just send the NEW status.
                // Let's refine the controller to accept 'UPDATE' action with 'new_status' parameter.
                
                let submitAction = action;
                if (action === 'UPDATE') {
                   // Determine if it looks like an approval or rejection for the activity log trigger
                   const currentStatus = document.getElementById('review-user-status').value;
                   if (newStatus !== currentStatus) {
                       submitAction = (newStatus === 'ACTIVE') ? 'APPROVE' : (newStatus === 'SUSPENDED' ? 'REJECT' : 'UPDATE');
                   }
                }

                const response = await fetch(`${ROOT}/user-admin/reviewUser`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ 
                        user_id: userId, 
                        role: role, 
                        action: submitAction, 
                        data: data,
                        new_status: newStatus,
                        review_message: document.getElementById('review-message').value
                    })
                });
                
                const text = await response.text();
                // console.log('RAW SERVER RESPONSE:', text); 
                
                let result;
                try {
                    result = JSON.parse(text);
                } catch (e) {
                    console.error('SERVER SENT MALFORMED DATA:', text);
                    showToast('error', 'Server error: Malformed data received. Check console.');
                    return;
                }

                if (result.success) {
                    showToast('success', result.message || 'Updated successfully');
                    closeModal('review-user-modal');
                    fetchUsers();
                    fetchDashboardStats();
                } else {
                    showToast('error', result.message || 'Failed to update record');
                }
            } catch (error) {
                console.error('Error submitting review:', error);
                showToast('error', 'Update failed: Check your connection or administrative permissions.');
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
            updateBulkButtons();
        }

        function updateBulkButtons() {
            const activateBtn = document.getElementById('bulk-activate');
            const suspendBtn = document.getElementById('bulk-suspend');
            
            if (!activateBtn && !suspendBtn) return;

            if (appState.selectedUsers.length === 0) {
                if (activateBtn) activateBtn.disabled = true;
                if (suspendBtn) suspendBtn.disabled = true;
                return;
            }

            const selectedUserData = appState.users.filter(u => appState.selectedUsers.includes(parseInt(u.id)));
            
            if (activateBtn) {
                activateBtn.disabled = !selectedUserData.every(u => (u.status || '').toUpperCase() === 'SUSPENDED');
            }
            
            if (suspendBtn) {
                suspendBtn.disabled = !selectedUserData.every(u => (u.status || '').toUpperCase() === 'ACTIVE');
            }
        }


        function bulkUpdateStatus(status) {
            if (appState.selectedUsers.length === 0) return;

            const modal = document.getElementById('bulk-action-modal');
            const iconBox = document.getElementById('bulk-status-icon-box');
            const icon = document.getElementById('bulk-status-icon');
            const title = document.getElementById('bulk-modal-title');
            const confirmBtn = document.getElementById('btn-confirm-bulk');
            const confirmIcon = document.getElementById('bulk-confirm-icon');
            const confirmText = document.getElementById('bulk-confirm-text');
            const countBadge = document.getElementById('bulk-user-count-badge');
            const messageArea = document.getElementById('bulk-review-message');
            const targetStatusInput = document.getElementById('bulk-target-status');

            targetStatusInput.value = status;
            countBadge.innerText = `${appState.selectedUsers.length} Users`;

            if (status === 'ACTIVE') {
                iconBox.style.background = '#ecfdf5';
                icon.className = 'fa-solid fa-circle-check';
                icon.style.color = '#059669';
                title.innerText = 'Authorize Bulk Activation';
                confirmBtn.style.background = '#059669';
                confirmIcon.className = 'fa-solid fa-circle-check';
                confirmText.innerText = 'Confirm Activation';
                messageArea.value = "Account reactivated: Following administrative review, your access has been restored and all issues have been resolved.";
            } else {
                iconBox.style.background = '#fee2e2';
                icon.className = 'fa-solid fa-circle-xmark';
                icon.style.color = '#dc2626';
                title.innerText = 'Authorize Bulk Suspension';
                confirmBtn.style.background = '#dc2626';
                confirmIcon.className = 'fa-solid fa-circle-xmark';
                confirmText.innerText = 'Confirm Suspension';
                messageArea.value = "Account suspended for administrative review.";
            }

            modal.classList.add('show');
        }

        async function executeBulkUpdate() {
            const status = document.getElementById('bulk-target-status').value;
            const message = document.getElementById('bulk-review-message').value;

            try {
                const response = await fetch(`${ROOT}/user-admin/bulkUpdateUserStatus`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ 
                        user_ids: appState.selectedUsers, 
                        status: status,
                        message: message 
                    })
                });
                const data = await response.json();
                if (data.success) {
                    showToast('success', data.message);
                    closeModal('bulk-action-modal');
                    appState.selectedUsers = [];
                    document.getElementById('select-all').checked = false;
                    fetchUsers();
                    fetchDashboardStats();
                } else {
                    showToast('error', data.message);
                }
            } catch (error) {
                console.error('Error in bulk update:', error);
                showToast('error', 'Failed to perform mass update. Check connection.');
            }
        }

        function bulkActivate() { bulkUpdateStatus('ACTIVE'); }
        function bulkSuspend() { bulkUpdateStatus('SUSPENDED'); }



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
            
            // Fallback for empty messages
            if (!message) {
                message = type === 'success' ? 'Action completed successfully' : 'An error occurred. Please try again.';
            }

            messageEl.textContent = message;
            toast.className = `notification ${type} show`;
            
            setTimeout(() => {
                toast.classList.remove('show');
            }, 5000);
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