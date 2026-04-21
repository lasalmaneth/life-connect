<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= ROOT ?>/public/assets/css/style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= ROOT ?>/public/assets/css/admin/style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

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
            box-shadow: inset 0px 0px 8px rgba(0, 0, 0, 0.05);
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

.bar-label {
  position: absolute;
  bottom: -30px;
  left: 50%;
  transform: translateX(-50%);
  color: #1e40af;
  font-size: 0.85rem;
  white-space: nowrap;
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

        .activity-icon.success {
            background: #ecfdf5;
            color: #059669;
        }

        .activity-icon.info {
            background: #eff6ff;
            color: #3b82f6;
        }

        .activity-icon.warning {
            background: #fffbeb;
            color: #d97706;
        }

        .activity-icon.error {
            background: #fee2e2;
            color: #dc2626;
        }

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

        .stats-grid {
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

        /* Status Badge for Withdrawn */
        .status-badge.status-withdrawn, .status-badge.status-withdraw_request {
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #cbd5e1;
        }

        /* User Management Tabs */
        .user-tabs {
            display: flex;
            gap: 2rem;
            margin-bottom: 1.5rem;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 0.5rem;
        }

        .user-tab {
            padding: 0.5rem 0.25rem;
            font-size: 0.95rem;
            font-weight: 600;
            color: #64748b;
            cursor: pointer;
            position: relative;
            transition: all 0.2s ease;
        }

        .user-tab:hover {
            color: #005baa;
        }

        .user-tab.active {
            color: #005baa;
        }

        .user-tab.active::after {
            content: '';
            position: absolute;
            bottom: -0.6rem;
            left: 0;
            right: 0;
            height: 3px;
            background: #005baa;
            border-radius: 3px 3px 0 0;
        }

        .user-tab-count {
            background: #f1f5f9;
            color: #64748b;
            padding: 2px 6px;
            border-radius: 10px;
            font-size: 0.75rem;
            margin-left: 0.5rem;
        }

        .user-tab.active .user-tab-count {
            background: #005baa;
            color: white;
        }

        .clickable-row:hover {
            background-color: #f1f5f9 !important;
            transition: background-color 0.2s ease;
        }

        /* ====== Modal System ====== */
        .modal {
            display: none;
            position: fixed;
            z-index: 10000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: hidden; /* Prevent page scroll */
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            align-items: center;
            justify-content: center;
        }

        .modal.show {
            display: flex !important;
        }

        .modal-content {
            background-color: #ffffff;
            margin: auto;
            border-radius: 12px;
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            position: relative;
            animation: modalFadeIn 0.3s ease-out;
            display: flex;
            flex-direction: column;
            overflow: hidden; /* Critical for horizontal scroll fix */
        }

        .modal-scroll-area {
            padding: 2rem;
            overflow-y: auto;
            overflow-x: hidden; /* Prevent horizontal scroll */
            flex: 1;
        }

        .modal-break-word {
            word-break: break-all;
            overflow-wrap: break-word;
            white-space: pre-wrap;
        }

        @keyframes modalFadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .modal-close {
            color: #64748b;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            line-height: 1;
        }

        .modal-close:hover {
            color: #1e293b;
        }
    </style>


</head>

<body>

    <?php
    if (session_status() === PHP_SESSION_NONE) session_start();
    $db = new class { use \App\Core\Database; };
    $uId = $_SESSION['user_id'] ?? 0;
    
    // Total Users
    $res = $db->query("SELECT COUNT(*) as count FROM users");
    $stats['totalUsers'] = $res[0]->count ?? 0;

    // Total Aftercare Patients
    $resPatient = $db->query("SELECT COUNT(*) as count FROM aftercare_patients");
    $stats['totalPatients'] = $resPatient[0]->count ?? 0;

    $adminData = $db->query("SELECT a.*, u.email, u.status 
                             FROM admins a 
                             JOIN users u ON a.user_id = u.id 
                             WHERE a.user_id = :id", ['id' => $uId]);
    $admin = !empty($adminData) ? $adminData[0] : null;
    $adminName = $admin ? ($admin->first_name . ' ' . $admin->last_name) : ($_SESSION['username'] ?? 'Admin');
    ?>

    <div class="header">
        <div class="header-content">
            <div class="header-left" style="display: flex; align-items: center; gap: 1rem;">
                <!-- Mobile Toggle Button -->
                <button id="sidebar-toggle" class="sidebar-toggle" onclick="toggleSidebar()" aria-label="Toggle Menu">
                    <i class="fa-solid fa-bars"></i>
                </button>

                <a href="<?= ROOT ?>" style="text-decoration:none; display:flex; align-items:center; gap:10px;">
                    <img src="<?= ROOT ?>/public/assets/images/logo.png" alt="LifeConnect" style="height:40px;">
                    <div>
                        <strong
                            style="display:block; font-size:1.1rem; color:#003b6e; line-height:1.2;">LifeConnect</strong>
                        <p style="margin:0; font-size:.68rem; color:#6b7280;">User Administration</p>
                    </div>
                </a>
            </div>

            <div class="header-right" style="display: flex; align-items: center; gap: 1.5rem;">
                <nav style="display: flex; align-items: center; gap: 1rem;">
                    <a href="<?= ROOT ?>" class="nav-icon-link" title="Home"
                        style="color: #64748b; font-size: 1.2rem; transition: color 0.2s;">
                        <i class="fa-solid fa-house"></i>
                    </a>
                </nav>

                <div class="user-info-wrapper" id="userProfileToggle" data-profile-toggle style="cursor: pointer;">
                    <div class="user-avatar"><?= strtoupper(substr($adminName, 0, 1)) ?></div>
                    <div class="user-details">
                        <span class="user-name"><?= htmlspecialchars($adminName) ?></span>
                        <span class="user-id">ID: <?= $admin->staff_id ?? 'N/A' ?></span>
                    </div>
                    <i class="fa-solid fa-chevron-down ms-2" style="font-size: 0.7rem; color: #94a3b8;"></i>
                    
                    <?php 
                    $adminRoleTitle = 'User Administrator';
                    include(__DIR__ . '/inc/profile_card.partial.php'); 
                    ?>
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const toggle = document.getElementById('userProfileToggle');
                    const dropdown = document.getElementById('userProfileDropdown');

                    if (toggle && dropdown) {
                        toggle.addEventListener('click', function(e) {
                            e.stopPropagation();
                            dropdown.classList.toggle('active');
                        });
                    }
                });
            </script>
        </div>
    </div>

    <!-- Sidebar Overlay -->
    <div id="sidebar-overlay" class="sidebar-overlay" onclick="toggleSidebar()"></div>

    <div class="container-fluid p-0">
        <div class="main-content">
            <div class="sidebar glass">
                <div class="sidebar-user-card">
                    <div class="sidebar-user-avatar">
                        R
                    </div>
                    <div class="sidebar-user-info">
                        <span class="sidebar-user-name">Reema</span>
                        <span class="sidebar-user-id">ID-00001</span>
                        <span class="sidebar-user-role">User Admin</span>
                    </div>
                </div>

                <div class="sidebar-nav">
                    <div class="menu-section">
                        <div class="menu-section-title">CORE</div>
                        <a href="javascript:void(0)" class="menu-item active" onclick="showContent('dashboard')">
                            <span class="icon"><i class="fa-solid fa-chart-line"></i></span>
                            <span>Dashboard</span>
                        </a>
                    </div>

                    <div class="menu-section">
                        <div class="menu-section-title">USERS & SECURITY</div>

                        <a href="javascript:void(0)" class="menu-item" onclick="showContent('accounts')"
                            style="display: flex; align-items: center;">
                            <span class="icon"><i class="fa-solid fa-users-gear"></i></span>
                            <span>User Accounts</span>
                            <span id="nav-pending-users-badge" class="badge"
                                style="display:none; background:#ef4444; color:white; border-radius:12px; padding:2px 7px; font-size:0.7rem; margin-left:auto; font-weight:bold;"></span>
                        </a>


                    </div>

                    <div class="menu-section">
                        <div class="menu-section-title">COMMUNICATION</div>

                        <a href="javascript:void(0)" class="menu-item" onclick="showContent('notifications')">
                            <span class="icon"><i class="fa-solid fa-bell"></i></span>
                            <span>Notifications</span>
                        </a>

                        <a href="javascript:void(0)" class="menu-item" onclick="showContent('feedbacks')"
                            style="display: flex; align-items: center;">
                            <span class="icon"><i class="fa-solid fa-comments"></i></span>
                            <span>Feedbacks</span>
                            <span id="nav-pending-feedbacks-badge" class="badge"
                                style="display:none; background:#ef4444; color:white; border-radius:12px; padding:2px 7px; font-size:0.7rem; margin-left:auto; font-weight:bold;"></span>
                        </a>

                        <a href="javascript:void(0)" class="menu-item" onclick="showContent('audit-logs')">
                            <span class="icon"><i class="fa-solid fa-list-check"></i></span>
                            <span>System Audit Logs</span>
                        </a>
                    </div>

                    <div class="menu-section mt-auto">
                        <a href="javascript:void(0)" onclick="openModal('logout-modal')" class="menu-item text-danger">
                            <span class="icon"><i class="fa-solid fa-right-from-bracket"></i></span>
                            <span>Logout</span>
                        </a>
                    </div>
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
                                <div class="stat-number quick-stat-number" id="stat-pending-docs"
                                    style="color: #dc2626;">0</div>
                                <div class="stat-label">Pending Verifications</div>
                                <div class="stat-change positive" id="change-pending-docs"></div>
                            </div>
                            <div class="stat-card glass-card">
                                <div class="stat-number quick-stat-number" id="stat-suspended-users"
                                    style="color: #dc2626;">0</div>
                                <div class="stat-label">Suspended Accounts</div>
                                <div class="stat-change negative" id="change-suspended-users" style="color: #dc2626;">
                                </div>
                            </div>
                            <div class="stat-card glass-card">
                                <div class="stat-number" id="stat-active-users" style="color: #059669;">0</div>
                                <div class="stat-label">Total Active Users</div>
                                <div class="stat-change positive" id="change-active-users" style="color: #059669;">
                                </div>
                            </div>
                            <div class="stat-card glass-card">
                                <div class="stat-number" id="stat-withdrawn-users" style="color: #64748b;">0</div>
                                <div class="stat-label">Withdrawn Accounts</div>
                                <div class="stat-change negative" id="change-withdrawn-users" style="color: #64748b;">
                                </div>
                            </div>
                        </div>
                        <div class="charts-section">
                            <div class="chart-card chart-card--distribution">
                                <h3 class="chart-title">User Distribution</h3>
                                <div class="chart-body">
                                    <div class="doughnut-container"
                                        style="position: relative; display: flex; justify-content: center;">
                                        <div id="css-user-chart" class="css-doughnut">
                                            <div class="css-doughnut-inner">
                                                <div id="css-doughnut-total"
                                                    style="color: #005baa; font-size: 20px; font-weight: bold;">0</div>
                                                <div style="color: #718096; font-size: 12px;">Total Users</div>
                                            </div>
                                        </div>
                                        <div id="chart-tooltip" class="chart-tooltip"></div>
                                    </div>
                                    <div class="chart-legend">
                                        <div class="legend-item">
                                            <div class="legend-left">
                                                <div class="legend-color" style="background:#005baa"></div>Donors
                                            </div>
                                            <div class="legend-count">0</div>
                                        </div>
                                        <div class="legend-item">
                                            <div class="legend-left">
                                                <div class="legend-color" style="background:#a4c8e1"></div>Patients
                                            </div>
                                            <div class="legend-count"><?= $stats['totalPatients'] ?></div>
                                        </div>
                                        <div class="legend-item">
                                            <div class="legend-left">
                                                <div class="legend-color" style="background:#059669"></div>Custodians
                                            </div>
                                            <div class="legend-count">0</div>
                                        </div>
                                        <div class="legend-item">
                                            <div class="legend-left">
                                                <div class="legend-color" style="background:#74b9ff"></div>Hospitals
                                            </div>
                                            <div class="legend-count">0</div>
                                        </div>
                                        <div class="legend-item">
                                            <div class="legend-left">
                                                <div class="legend-color" style="background:#16a34a"></div>Medical
                                                Schools
                                            </div>
                                            <div class="legend-count">0</div>
                                        </div>
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
                            <div style="padding: 20px; text-align: center; color: #64748b;">Loading recent activity...
                            </div>
                        </div>
                    </div>
                </div>


                <!-- User Accounts Management -->
                <div id="accounts" class="content-section" style="display: none;">
                    <div class="content-header">
                        <h2>User Account Management</h2>
                        <p>Navigate through user profiles, manage registration statuses, and oversee account security.</p>
                    </div>
                    <div class="content-body">
                        <div class="user-tabs">
                            <div class="user-tab active" data-status="" onclick="setUserTab(this)">
                                All Users <span class="user-tab-count" id="tab-count-all">0</span>
                            </div>
                            <div class="user-tab" data-status="active" onclick="setUserTab(this)">
                                Active <span class="user-tab-count" id="tab-count-active">0</span>
                            </div>
                            <div class="user-tab" data-status="pending" onclick="setUserTab(this)">
                                Pending <span class="user-tab-count" id="tab-count-pending">0</span>
                            </div>
                            <div class="user-tab" data-status="suspended" onclick="setUserTab(this)">
                                Suspended <span class="user-tab-count" id="tab-count-suspended">0</span>
                            </div>
                            <div class="user-tab" data-status="withdraw_request" onclick="setUserTab(this)">
                                Withdrawn <span class="user-tab-count" id="tab-count-withdrawn">0</span>
                            </div>
                        </div>

                        <div style="display: flex; gap: 16px; align-items: center; margin-bottom: 24px;">
                            <div class="search-bar" style="margin-bottom: 0; flex: 1;">
                                <span class="search-icon"><i class="fa-solid fa-magnifying-glass"></i></span>
                                <input type="text" class="search-input"
                                    placeholder="Search users by name, email, or ID..." id="user-search"
                                    onkeyup="fetchUsers()">
                            </div>

                            <div class="filter-section" style="margin-bottom: 0; display: flex; gap: 12px;">
                                <select class="filter-select" id="status-filter" onchange="syncTabsWithFilter(); fetchUsers();" style="display: none;">
                                    <option value="">All Statuses</option>
                                    <option value="active">Active</option>
                                    <option value="suspended">Suspended</option>
                                    <option value="pending">Pending</option>
                                    <option value="withdraw_request">Withdrawn</option>
                                </select>
                                <select class="filter-select" id="role-filter" onchange="fetchUsers()">
                                    <option value="">All Roles</option>
                                    <option value="donor">Donor</option>
                                    <option value="hospital">Hospital</option>
                                    <option value="medical_school">Medical School</option>
                                    <option value="custodian">Custodian</option>
                                    <option value="patient">Patient</option>
                                    <option value="u_admin">User Admin</option>
                                    <option value="f_admin">Financial Admin</option>
                                    <option value="ac_admin">Aftercare Admin</option>
                                    <option value="d_admin">Donation Admin</option>
                                </select>
                            </div>
                        </div>

                        <div class="data-table">
                            <div class="table-header"
                                style="display: flex; justify-content: space-between; align-items: center; padding-right: 1.5rem;">
                                <h4>User Accounts</h4>
                            </div>
                            <div class="table-content" id="users-table">
                                <div class="table-row" style="font-weight: 600; background: var(--gray-bg-color);">
                                    <div class="table-cell name">
                                        User Details
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
                        <p>Draft and dispatch critical system updates, approvals, and reminders to specific user groups.</p>
                    </div>
                    <div class="content-body">
                        <div class="data-table">
                            <div class="table-header" style="display: flex; justify-content: space-between; align-items: center; padding: 1rem 1.5rem;">
                                <h4 style="margin: 0;">Recent Notifications</h4>
                                
                            </div>
                            <div class="table-content" id="notifications-table">
                                <div class="table-row" style="font-weight: 600; background: var(--gray-bg-color);">
                                    <div class="table-cell">Recipient & Subject</div>
                                    <div class="table-cell">Type</div>
                                    <div class="table-cell">Status</div>
                                    <div class="table-cell">Priority</div>
                                    <div class="table-cell">Sent Date</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Feedbacks -->
                <?php include 'feedback_management.php'; ?>

                <!-- System Audit Logs -->
                <div id="audit-logs" class="content-section" style="display: none;">
                    <div class="content-header">
                        <h2>System Audit Logs</h2>
                        <p>Track all administrative actions, status changes, and security events for full system accountability.</p>
                    </div>
                    <div class="content-body">
                        <div class="search-bar">
                            <span class="search-icon"><i class="fa-solid fa-magnifying-glass"></i></span>
                            <input type="text" class="search-input" placeholder="Search logs by admin, target, or action..." id="audit-search" onkeyup="renderAuditTable()">
                        </div>

                        <div class="data-table">
                            <div class="table-header">
                                <h4>Activity Trail</h4>
                            </div>
                            <div class="table-content" id="audit-table">
                                <div class="table-row" style="font-weight: 600; background: var(--gray-bg-color);">
                                    <div class="table-cell">Admin (Actor)</div>
                                    <div class="table-cell">Action</div>
                                    <div class="table-cell">Target User</div>
                                    <div class="table-cell">Date</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
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
                        <option value="custodian">Custodian</option>
                    </select>
                </div>
                <div class="action-buttons" style="margin-top: 2rem;">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <button type="button" class="btn btn-secondary"
                        onclick="closeModal('edit-user-modal')">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Review User Modal (Detailed Premium View) -->
    <div id="review-user-modal" class="modal">
        <div class="modal-content">
            <div class="modal-scroll-area">
                <!-- Modal Header with Icon (Simplified Horizontal Layout) -->
                <div style="display: flex; flex-direction: column; gap: 1.25rem; position: relative;">
                    <button class="modal-close" style="position: absolute; top: -15px; right: -15px; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 50%; background: #f1f5f9; border: none; cursor: pointer; color: #64748b; z-index: 10;" onclick="closeModal('review-user-modal')">&times;</button>
                
                <div style="display: flex; align-items: center; gap: 1.25rem;">
                    <!-- Status Icon -->
                    <div id="review-status-icon-box"
                        style="flex-shrink: 0; width: 48px; height: 48px; background: #fee2e2; border-radius: 12px; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease;">
                        <i id="review-status-icon" class="fa-solid fa-circle-xmark"
                            style="font-size: 20px; color: #dc2626;"></i>
                    </div>

                    <!-- Title -->
                    <div>
                        <h2 id="review-modal-title"
                            style="margin: 0; font-size: 1.5rem; font-weight: 800; color: #0f172a; line-height: 1.2;">
                            Review Account</h2>
                    </div>
                </div>

                <p style="margin: 0; color: #64748b; font-size: 0.9rem; line-height: 1.5; font-weight: 500;">Please
                    review the information below and update the account status. This will be saved permanently.</p>

                <!-- Core Details Card (2-Column Grid) -->
                <div id="modal-summary-card"
                    style="background: #f0f7ff; border-radius: 16px; padding: 1.5rem; display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <!-- Standard Fields -->
                    <div>
                        <span
                            style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Username</span>
                        <div id="review-username-text" style="font-size: 1.05rem; font-weight: 700; color: #1e293b;">-
                        </div>
                    </div>
                    <div>
                        <span
                            style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Role/Reg
                            Date</span>
                        <div id="review-user-role-display"
                            style="font-size: 0.95rem; font-weight: 600; color: #334155;">-</div>
                        <div id="review-regdate-text" style="font-size: 0.8rem; color: #64748b;">-</div>
                    </div>

                    <div>
                        <span
                            style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Email
                            Address</span>
                        <div id="review-email-text"
                            style="font-size: 0.9rem; font-weight: 600; color: #1e293b; word-break: break-all;">-</div>
                    </div>
                    <div id="review-summary-phone">
                        <span
                            style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Phone
                            Number</span>
                        <div id="review-phone-text" style="font-size: 0.95rem; font-weight: 600; color: #1e293b;">-
                        </div>
                    </div>

                    <!-- Donor Identity Section (Conditional Grid Row) -->
                    <div id="donor-identity-section" style="display: contents;">
                        <div>
                            <span id="label-fullname"
                                style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Full
                                Name</span>
                            <div id="review-fullname-text"
                                style="font-size: 0.95rem; font-weight: 600; color: #1e293b; word-break: break-all;">-</div>
                        </div>
                        <div>
                            <span id="label-nic"
                                style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">NIC
                                Number</span>
                            <div id="review-nic-text" style="font-size: 0.95rem; font-weight: 600; color: #1e293b;">-
                            </div>
                        </div>
                        <div id="review-gender-group">
                            <span
                                style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Gender</span>
                            <div id="review-gender-text" style="font-size: 0.95rem; font-weight: 600; color: #1e293b;">-
                            </div>
                        </div>
                        <div id="review-dob-group">
                            <span
                                style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Date
                                of Birth</span>
                            <div id="review-dob-text" style="font-size: 0.95rem; font-weight: 600; color: #1e293b;">-
                            </div>
                        </div>
                    </div>

                    <!-- Hospital Identity Section (Conditional Grid Row) -->
                    <div id="hospital-identity-section" style="display: none; contents;">
                        <div style="grid-column: span 2;">
                            <span
                                style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Hospital
                                Official Name</span>
                            <div id="review-hosp-name" style="font-size: 1rem; font-weight: 800; color: #1e293b;">-
                            </div>
                        </div>
                        <div>
                            <span
                                style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Registration
                                Number</span>
                            <div id="review-hosp-reg" style="font-size: 0.95rem; font-weight: 600; color: #1e293b;">-
                            </div>
                        </div>
                        <div>
                            <span
                                style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Transplant
                                ID</span>
                            <div id="review-hosp-transplant"
                                style="font-size: 0.95rem; font-weight: 600; color: #1e293b;">-</div>
                        </div>
                        <div>
                            <span
                                style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Facility
                                Type</span>
                            <div id="review-hosp-type" style="font-size: 0.95rem; font-weight: 600; color: #1e293b;">-
                            </div>
                        </div>
                        <div>
                            <span
                                style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Medical
                                License Number</span>
                            <div id="review-hosp-license" style="font-size: 0.95rem; font-weight: 600; color: #1e293b;">
                                -</div>
                        </div>
                        <div>
                            <span
                                style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Chief
                                Medical Officer (CMO)</span>
                            <div id="review-hosp-cmo-name"
                                style="font-size: 0.95rem; font-weight: 600; color: #1e293b;">-</div>
                        </div>
                        <div>
                            <span
                                style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">CMO
                                NIC Number</span>
                            <div id="review-hosp-cmo-nic" style="font-size: 0.95rem; font-weight: 600; color: #1e293b;">
                                -</div>
                        </div>
                        <div>
                            <span
                                style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">District</span>
                            <div id="review-hosp-district"
                                style="font-size: 0.95rem; font-weight: 600; color: #1e293b; word-break: break-all;">-</div>
                        </div>
                        <div>
                            <span
                                style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Clinical
                                Contact Number</span>
                            <div id="review-hosp-phone" style="font-size: 0.95rem; font-weight: 600; color: #1e293b;">-
                            </div>
                        </div>
                        <div style="grid-column: span 2;">
                            <span
                                style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Hospital
                                Address</span>
                            <div id="review-hosp-address" style="font-size: 0.95rem; font-weight: 600; color: #1e293b; word-break: break-all;">
                                -</div>
                        </div>
                    </div>

                    <!-- Medical School Identity Section -->
                    <div id="medical-school-identity-section" style="display: none; contents;">
                        <div style="grid-column: span 2;">
                            <span style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Medical School Name</span>
                            <div id="review-med-name" style="font-size: 1rem; font-weight: 800; color: #1e293b;">-</div>
                        </div>
                        <div>
                            <span style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">University Affiliation</span>
                            <div id="review-med-univ" style="font-size: 0.95rem; font-weight: 600; color: #1e293b;">-</div>
                        </div>
                        <div>
                            <span style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">UGC Accreditation #</span>
                            <div id="review-med-ugc" style="font-size: 0.95rem; font-weight: 600; color: #1e293b;">-</div>
                        </div>
                        <div>
                            <span style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Contact Person</span>
                            <div id="review-med-contact-name" style="font-size: 0.95rem; font-weight: 600; color: #1e293b;">-</div>
                        </div>
                        <div>
                            <span style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Direct Contact Number</span>
                            <div id="review-med-contact-phone" style="font-size: 0.95rem; font-weight: 600; color: #1e293b;">-</div>
                        </div>
                        <div>
                            <span style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">District</span>
                            <div id="review-med-district" style="font-size: 0.95rem; font-weight: 600; color: #1e293b;">-</div>
                        </div>
                        <div style="grid-column: span 2;">
                            <span style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Institution Address</span>
                            <div id="review-med-address" style="font-size: 0.95rem; font-weight: 600; color: #1e293b; word-break: break-all;">-</div>
                        </div>
                    </div>

                    <!-- Admin Identity Section -->
                    <div id="admin-identity-section" style="display: none; contents;">
                        <div>
                            <span
                                style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Staff
                                ID</span>
                            <div id="review-admin-staff-id"
                                style="font-size: 0.95rem; font-weight: 600; color: #1e293b;">-</div>
                        </div>
                        <div>
                            <span
                                style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Designation</span>
                            <div id="review-admin-designation"
                                style="font-size: 0.95rem; font-weight: 600; color: #1e293b;">-</div>
                        </div>
                        <div style="grid-column: span 2;">
                            <span
                                style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Internal
                                Contact Number</span>
                            <div id="review-admin-contact"
                                style="font-size: 0.95rem; font-weight: 600; color: #1e293b;">-</div>
                        </div>
                    </div>

                    <!-- Recipient Patient Identity Section -->
                    <div id="recipient-identity-section" style="display: none; contents;">
                        <div>
                            <span style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">User ID</span>
                            <div id="review-recipient-uid" style="font-size: 1rem; font-weight: 800; color: #1e293b;">-</div>
                        </div>
                        <div>
                            <span style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Registration Number</span>
                            <div id="review-recipient-reg" style="font-size: 1rem; font-weight: 800; color: #1e293b;">-</div>
                        </div>
                        <div>
                            <span style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">NIC Number</span>
                            <div id="review-recipient-nic" style="font-size: 1rem; font-weight: 800; color: #1e293b;">-</div>
                        </div>
                        <div>
                            <span style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Full Name</span>
                            <div id="review-recipient-fullname" style="font-size: 1rem; font-weight: 800; color: #1e293b;">-</div>
                        </div>
                        <div>
                            <span style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Patient Type</span>
                            <div id="review-recipient-type" style="font-size: 1rem; font-weight: 800; color: #1e293b;">-</div>
                        </div>
                    </div>

                    <!-- Custodian Identity Section -->
                    <div id="custodian-identity-section" style="display: none; contents;">
                        <div style="grid-column: span 2;">
                            <span style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Represented Donor (Guardian for)</span>
                            <div id="review-custodian-donor" style="font-size: 1rem; font-weight: 800; color: #1e293b; word-break: break-all;">-</div>
                        </div>
                        <div>
                            <span style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Relationship</span>
                            <div id="review-custodian-relationship" style="font-size: 0.95rem; font-weight: 600; color: #1e293b;">-</div>
                        </div>
                        <div>
                            <span style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Full Name</span>
                            <div id="review-custodian-name" style="font-size: 0.95rem; font-weight: 600; color: #1e293b; word-break: break-all;">-</div>
                        </div>
                        <div>
                            <span style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">NIC Number</span>
                            <div id="review-custodian-nic" style="font-size: 0.95rem; font-weight: 600; color: #1e293b;">-</div>
                        </div>
                        <div>
                            <span style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Contact Phone</span>
                            <div id="review-custodian-phone" style="font-size: 0.95rem; font-weight: 600; color: #1e293b;">-</div>
                        </div>
                        <div style="grid-column: span 2;">
                            <span style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Residential Address</span>
                            <div id="review-custodian-address" style="font-size: 0.95rem; font-weight: 600; color: #1e293b; word-break: break-all;">-</div>
                        </div>
                    </div>

                    <!-- Organ Donor Administrative Section (Conditional Grid Row) -->
                    <div id="organ-donor-section" style="display: contents;">
                        <div>
                            <span
                                style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">District
                                / DS Division</span>
                            <div id="review-location-text"
                                style="font-size: 0.95rem; font-weight: 600; color: #1e293b; word-break: break-all;">-</div>
                        </div>
                        <div>
                            <span
                                style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">GN
                                Division</span>
                            <div id="review-gn-text" style="font-size: 0.95rem; font-weight: 600; color: #1e293b; word-break: break-all;">-
                            </div>
                        </div>
                        <div style="grid-column: span 2;">
                            <span
                                style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Residential
                                Address</span>
                            <div id="review-address-text" style="font-size: 0.95rem; font-weight: 600; color: #1e293b; word-break: break-all;">
                                -</div>
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
                    <div id="verification-section"
                        style="display:none; background: #fffcf0; border-left: 4px solid #fbbf24; padding: 1rem; border-radius: 12px;">
                        <span
                            style="display: block; font-size: 0.7rem; font-weight: 800; color: #92400e; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px;">Manual
                            Verification</span>

                        <!-- Common Check (Top) -->
                        <div style="margin-bottom: 8px;">
                            <label
                                style="display: flex; align-items: center; gap: 10px; font-size: 0.9rem; color: #1e293b; cursor: pointer; font-weight: 600;">
                                <input type="checkbox" id="verify-genuine"
                                    onchange="checkVerificationStatus(); generateReviewMessage()"
                                    style="width: 18px; height: 18px; cursor: pointer;">
                                Profile Information & Data Authenticity
                            </label>
                        </div>

                        <!-- Donor Specific (NIC) -->
                        <div id="donor-verification-controls" style="display: none;">
                            <label
                                style="display: flex; align-items: center; gap: 10px; font-size: 0.9rem; color: #1e293b; cursor: pointer; font-weight: 600;">
                                <input type="checkbox" id="verify-registry"
                                    onchange="checkVerificationStatus(); generateReviewMessage()"
                                    style="width: 18px; height: 18px; cursor: pointer;">
                                <span>Verified NIC via Election Commission Registry <a
                                        href="https://eservices.elections.gov.lk/pages/myVoterRegistrationSearch.aspx"
                                        target="_blank"
                                        style="color: #3b82f6; text-decoration: underline; font-size: 0.75rem; margin-left: 4px; font-weight: 700;"><i
                                            class="fa-solid fa-arrow-up-right-from-square"
                                            style="font-size: 0.65rem;"></i></a></span>
                            </label>
                        </div>

                        <!-- Hospital Specific (PHSRC) -->
                        <div id="hospital-verification-controls" style="display: none;">
                            <label
                                style="display: flex; align-items: center; gap: 10px; font-size: 0.9rem; color: #1e293b; cursor: pointer; font-weight: 600;">
                                <input type="checkbox" id="verify-hospital-registry"
                                    onchange="checkVerificationStatus(); generateReviewMessage()"
                                    style="width: 18px; height: 18px; cursor: pointer;">
                                <span>Verified via Private Health Services (PHSRC) <a href="https://www.phsrc.lk/"
                                        target="_blank"
                                        style="color: #3b82f6; text-decoration: underline; font-size: 0.75rem; margin-left: 4px; font-weight: 700;"><i
                                            class="fa-solid fa-arrow-up-right-from-square"
                                            style="font-size: 0.65rem;"></i></a></span>
                            </label>
                        </div>

                        <!-- Medical School Specific (UGC) -->
                        <div id="medical-school-verification-controls" style="display: none;">
                            <label style="display: flex; align-items: center; gap: 10px; font-size: 0.9rem; color: #1e293b; cursor: pointer; font-weight: 600;">
                                <input type="checkbox" id="verify-med-registry" onchange="checkVerificationStatus(); generateReviewMessage()" style="width: 18px; height: 18px; cursor: pointer;">
                                <span>Verified UGC Registry <a href="https://www.ugc.ac.lk/" target="_blank" style="color: #3b82f6; text-decoration: underline; font-size: 0.75rem; margin-left: 4px; font-weight: 700;"><i class="fa-solid fa-arrow-up-right-from-square" style="font-size: 0.65rem;"></i></a></span>
                            </label>
                        </div>
                    </div>

                    <!-- Status Dropdown -->
                    <div>
                        <span
                            style="display: block; font-size: 0.7rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px;">Update
                            Account Status *</span>
                        <select class="form-select" id="review-status-dropdown"
                            onchange="generateReviewMessage(); checkVerificationStatus()"
                            style="width: 100%; padding: 0.75rem; border-radius: 10px; border: 1.5px solid #e2e8f0; font-weight: 600; color: #0f172a; outline: none; transition: border-color 0.2s;">
                            <option value="PENDING">Pending</option>
                            <option value="ACTIVE">Active</option>
                            <option value="SUSPENDED">Suspended</option>
                            <option value="WITHDRAW_REQUEST">Withdrawn</option>
                        </select>
                    </div>

                    <!-- Review Notes -->
                    <div>
                        <span
                            style="display: block; font-size: 0.7rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px;">Review
                            Notes / Reason *</span>
                        <textarea id="review-message" rows="3" placeholder="Provide a reason for the update..."
                            style="width: 100%; padding: 0.75rem; border-radius: 10px; border: 1.5px solid #e2e8f0; font-size: 0.9rem; color: #1e293b; outline: none; transition: border-color 0.2s; resize: none;"></textarea>
                    </div>

                    </div>
                    
                    <!-- Suspension Secondary Confirmation (Inline) -->
                    <div id="suspension-notice" style="display: none; background: #fff1f2; border: 1.5px solid #fecdd3; border-radius: 12px; padding: 1rem; margin-top: 0.5rem; align-items: flex-start; gap: 12px;">
                        <div style="flex-shrink: 0; width: 32px; height: 32px; background: #fee2e2; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                            <i class="fa-solid fa-triangle-exclamation" style="font-size: 14px; color: #dc2626;"></i>
                        </div>
                        <div>
                            <p style="margin: 0; color: #991b1b; font-size: 0.85rem; line-height: 1.5; font-weight: 700;">Are you sure you want to suspend this account?</p>
                            <p style="margin: 2px 0 0 0; color: #b91c1c; font-size: 0.75rem; font-weight: 500;">Access will be immediately revoked. Click "Yes, Suspend Account" to proceed.</p>
                        </div>
                    </div>

                    <!-- Footer Buttons (Right Aligned) -->
                    <div style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 0.5rem;">
                        <button type="button" onclick="closeModal('review-user-modal')"
                            style="background: #f1f5f9; color: #475569; border: none; padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 700; cursor: pointer; transition: background 0.2s;">Cancel</button>
                        <button type="button" id="btn-save-details" onclick="submitUserReview('UPDATE')"
                            style="background: #dc2626; color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: all 0.2s;">
                            <i id="btn-save-icon" class="fa-solid fa-circle-xmark"></i>
                            <span id="btn-save-text">Confirm Rejection</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification Details Modal (Premium View) -->
    <div id="notif-details-modal" class="modal">
        <div class="modal-content">
            <div class="modal-scroll-area">
                <div style="display: flex; flex-direction: column; gap: 1.25rem; position: relative;">
                    <button class="modal-close" style="position: absolute; top: -15px; right: -15px; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 50%; background: #f1f5f9; border: none; cursor: pointer; color: #64748b; z-index: 10;" onclick="event.stopPropagation(); closeModal('notif-details-modal')">&times;</button>
                
                <div style="display: flex; align-items: center; gap: 1.25rem;">
                    <div id="notif-type-icon-box" style="flex-shrink: 0; width: 48px; height: 48px; background: #eff6ff; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                        <i id="notif-type-icon" class="fa-solid fa-bell" style="font-size: 20px; color: #3b82f6;"></i>
                    </div>
                    <div>
                        <h2 id="notif-modal-title" style="margin: 0; font-size: 1.5rem; font-weight: 800; color: #0f172a; line-height: 1.2;">Notification Details</h2>
                    </div>
                </div>

                <p id="notif-modal-date" style="margin: 0; color: #64748b; font-size: 0.9rem; font-weight: 500;">Sent on: -</p>

                <div style="background: #f0f7ff; border-radius: 16px; padding: 1.5rem; display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div>
                        <span style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Recipient</span>
                        <div id="notif-recipient-text" style="font-size: 1.05rem; font-weight: 700; color: #1e293b;">-</div>
                    </div>
                    <div>
                        <span style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Status / Type</span>
                        <div id="notif-status-badge-container">
                             <span id="notif-status-text" class="status-badge" style="font-size: 0.8rem;">-</span>
                        </div>
                        <div id="notif-type-text" style="font-size: 0.8rem; color: #64748b; margin-top: 4px; font-weight: 600;">-</div>
                    </div>
                </div>

                <div>
                    <span style="display: block; font-size: 0.7rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px;">Message Content</span>
                    <div id="notif-message-body" style="background: white; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 1.25rem; font-size: 0.95rem; color: #1e293b; line-height: 1.6; min-height: 100px;">
                        -
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end; margin-top: 0.5rem;">
                    <button type="button" onclick="event.stopPropagation(); closeModal('notif-details-modal')" style="background: #005baa; color: white; border: none; padding: 0.75rem 2rem; border-radius: 12px; font-weight: 700; cursor: pointer; transition: background 0.2s;">Got it</button>
            </div>
        </div>
    </div>
</div>

    <!-- Audit Details Modal (Premium View) -->
    <div id="audit-details-modal" class="modal">
        <div class="modal-content">
            <div class="modal-scroll-area">
                <div style="display: flex; flex-direction: column; gap: 1.25rem; position: relative;">
                    <button class="modal-close" style="position: absolute; top: -15px; right: -15px; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 50%; background: #f1f5f9; border: none; cursor: pointer; color: #64748b; z-index: 10;" onclick="event.stopPropagation(); closeModal('audit-details-modal')">&times;</button>
                
                <div style="display: flex; align-items: center; gap: 1.25rem;">
                    <div style="flex-shrink: 0; width: 48px; height: 48px; background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                        <i class="fa-solid fa-shield-halved" style="font-size: 20px; color: #64748b;"></i>
                    </div>
                    <div>
                        <h2 id="audit-modal-title" style="margin: 0; font-size: 1.5rem; font-weight: 800; color: #0f172a; line-height: 1.2;">Audit Event Details</h2>
                    </div>
                </div>

                <p id="audit-modal-date" style="margin: 0; color: #64748b; font-size: 0.9rem; font-weight: 500;">Recorded on: -</p>

                <!-- Actor & Target Section -->
                <div style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 16px; padding: 1.5rem; display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div>
                        <span style="display: block; font-size: 0.65rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Administrator</span>
                        <div id="audit-admin-text" style="font-size: 1.05rem; font-weight: 700; color: #1e293b;">-</div>
                    </div>
                    <div>
                        <span style="display: block; font-size: 0.65rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Target Account</span>
                        <div id="audit-target-text" style="font-size: 1.05rem; font-weight: 700; color: #1e293b;">-</div>
                    </div>
                </div>

                <!-- Values Comparison -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; padding: 0.5rem;">
                     <div>
                        <span style="display: block; font-size: 0.7rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px;">Previous Value</span>
                        <div id="audit-old-val" style="background: #fff1f2; color: #991b1b; padding: 0.75rem; border-radius: 10px; font-weight: 700; font-family: monospace; text-align: center;">-</div>
                    </div>
                    <div>
                        <span style="display: block; font-size: 0.7rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px;">New Value</span>
                        <div id="audit-new-val" style="background: #ecfdf5; color: #065f46; padding: 0.75rem; border-radius: 10px; font-weight: 700; font-family: monospace; text-align: center;">-</div>
                    </div>
                </div>

                <div>
                    <span style="display: block; font-size: 0.7rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px;">Administrative Notes</span>
                    <div id="audit-notes-body" style="background: white; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 1.25rem; font-size: 0.95rem; color: #1e293b; line-height: 1.6; min-height: 80px;">
                        -
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end; margin-top: 0.5rem;">
                    <button type="button" onclick="event.stopPropagation(); closeModal('audit-details-modal')" style="background: #1e293b; color: white; border: none; padding: 0.75rem 2rem; border-radius: 12px; font-weight: 700; cursor: pointer; transition: background 0.2s;">Secure Close</button>
                </div>
            </div>
        </div>
    </div>







    </div>
    </div>
    </div>

    <!-- Logout Confirmation Modal (Moved for visibility stability) -->
    <div id="logout-modal" class="modal">
        <div class="modal-content" style="max-width: 420px; text-align: center; padding: 2.5rem;">
            <div style="font-size: 2.5rem; color: #003b6e; margin-bottom: 1.5rem;">
                <i class="fa-solid fa-right-from-bracket"></i>
            </div>
            <h3 style="font-size: 1.5rem; font-weight: 800; color: #0f172a; margin-bottom: 1rem;">Confirm Logout</h3>
            <p style="color: #64748b; line-height: 1.5; margin-bottom: 2rem;">Are you sure you want to logout? You will need to login again to access your dashboard.</p>
            <div style="display: flex; gap: 1rem; justify-content: center;">
                <button onclick="closeModal('logout-modal')" class="btn btn-secondary" style="flex: 1; border-radius: 50px; padding: 0.75rem;">Cancel</button>
                <button onclick="window.location.href='<?= ROOT ?>/logout'" class="btn btn-danger" style="flex: 1; border-radius: 50px; padding: 0.75rem;">Logout</button>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="notification">
        <div id="toast-message">Operation completed successfully.</div>
    </div>
    <script>
        const ROOT = '<?= ROOT ?>';
    </script>
    <script src="<?= ROOT ?>/public/assets/js/admin/userAdmin.js?v=<?= time() ?>"></script>
</body>
</html>