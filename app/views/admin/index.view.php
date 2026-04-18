<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= ROOT ?>/public/assets/css/style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= ROOT ?>/public/assets/css/admin/style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= ROOT ?>/public/assets/css/fontawesome.min.css?v=<?= time() ?>">

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
            display: flex;
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
    if (session_status() === PHP_SESSION_NONE)
        session_start();
    $adminName = $_SESSION['username'] ?? ($_SESSION['user_name'] ?? 'Admin');
    ?>

    <div class="header">
        <div class="header-content">
            <div class="header-left">
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
                    <a href="javascript:void(0)" class="nav-icon-link" title="Notifications"
                        style="color: #64748b; font-size: 1.2rem; transition: color 0.2s; position: relative;">
                        <i class="fa-solid fa-bell"></i>
                        <span
                            style="position: absolute; top: -2px; right: -2px; width: 8px; height: 8px; background: #ef4444; border-radius: 50%; border: 2px solid white;"></span>
                    </a>
                </nav>

                <div class="user-info">
                    <div class="user-avatar" style="width: 32px; height: 32px; font-size: 0.9rem;">
                        <?= substr($adminName, 0, 1) ?></div>
                    <div class="user-details">
                        <span class="user-name"><?= htmlspecialchars($adminName) ?></span>
                        <span
                            class="user-role"><?= isset($_SESSION['role']) && $_SESSION['role'] === 'U_ADMIN' ? 'User Admin' : 'System Admin' ?></span>
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
                                            <div class="legend-count">0</div>
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
                                                <div class="legend-color" style="background:#e8f5e8"></div>Medical
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
                                <div class="action-buttons" style="margin-top: 0; display: flex; gap: 0.5rem;">
                                    <button class="btn btn-success" id="bulk-activate" onclick="bulkActivate()" disabled
                                        style="padding: 0.4rem 0.8rem; font-size: 0.85rem;">Bulk Activate</button>
                                    <button class="btn btn-danger" id="bulk-suspend" onclick="bulkSuspend()" disabled
                                        style="padding: 0.4rem 0.8rem; font-size: 0.85rem;">Bulk Suspend</button>
                                </div>
                            </div>
                            <div class="table-content" id="users-table">
                                <div class="table-row" style="font-weight: 600; background: var(--gray-bg-color);">
                                    <div class="table-cell name">
                                        <input type="checkbox" id="select-all" onchange="toggleSelectAll()"> User
                                        Details
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
                                    <div class="table-cell">Sent Date</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Donor Eligibility -->
                <div id="eligibility" class="content-section" style="display: none;">
                    <div class="content-header">
                        <h2>Donor Eligibility</h2>
                        <p>Review and update donor eligibility criteria based on medical assessments and regulatory standards.</p>
                    </div>
                    <div class="content-body">
                        <div class="search-bar">
                            <span class="search-icon"><i class="fa-solid fa-magnifying-glass"></i></span>
                            <input type="text" class="search-input"
                                placeholder="Search donors by name, ID, or blood type...">
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
                                <button class="btn btn-primary" onclick="showEligibilityModal()">Update Eligibility
                                    Status</button>
                                <button class="btn btn-success" id="bulk-approve-eligibility"
                                    onclick="bulkApproveEligibility()" disabled>Mark as Eligible</button>
                                <button class="btn btn-secondary" id="bulk-temp-ineligible"
                                    onclick="bulkTempIneligible()" disabled>Temporarily Ineligible</button>
                                <button class="btn btn-danger" id="bulk-perm-ineligible" onclick="bulkPermIneligible()"
                                    disabled>Permanently Ineligible</button>
                            </div>
                        </div>

                        <div class="data-table">
                            <div class="table-header">
                                <h4>Donor Eligibility Status</h4>
                            </div>
                            <div class="table-content" id="eligibility-table">
                                <div class="table-row" style="font-weight: 600; background: var(--gray-bg-color);">
                                    <div class="table-cell">
                                        <input type="checkbox" id="select-all-eligibility"
                                            onchange="toggleSelectAllEligibility()"> Donor Information
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
                    <textarea class="form-textarea" id="eligibility-reason"
                        placeholder="Provide detailed reason for ineligibility status..."></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Assessment Notes</label>
                    <textarea class="form-textarea" id="assessment-notes"
                        placeholder="Add any additional notes about the assessment..."></textarea>
                </div>
                <div class="action-buttons" style="margin-top: 2rem;">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <span class="loading" id="eligibility-form-loading" style="display: none;"></span>
                        <span id="eligibility-form-text">Update Status</span>
                    </button>
                    <button type="button" class="btn btn-secondary"
                        onclick="closeModal('eligibility-modal')">Cancel</button>
                </div>
            </form>
        </div>
    </div>



    <!-- Bulk Action Authorization Modal (Existing) -->
    <div id="bulk-action-modal" class="modal">
        <div class="modal-content">
            <div class="modal-scroll-area">
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
            </div>
            <input type="hidden" id="bulk-target-status">
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
            auditLogs: [],
            selectedUsers: [],
            selectedDocuments: [],
            selectedEligibility: [],
            selectedFeedbacks: [],
            isProcessingNotif: false,
            isProcessingAudit: false,
            isProcessingFeedback: false
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
        document.addEventListener('DOMContentLoaded', function () {
            if (window.location.hash) {
                const sectionId = window.location.hash.substring(1);
                if (document.getElementById(sectionId)) {
                    showContent(sectionId);
                }
            }
        });

        // Data Loading Functions
        function loadSectionData(sectionId) {
            switch (sectionId) {
                case 'dashboard':
                    fetchDashboardStats();
                    break;
                case 'accounts':
                    fetchUsers();
                    break;
                case 'notifications':
                    fetchNotifications();
                    break;
                case 'audit-logs':
                    fetchAuditLogs();
                    break;
                case 'eligibility':
                    renderEligibilityTable();
                    break;
                case 'feedbacks':
                    // Handled in feedback_management.php
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
            setText('stat-withdrawn-users', (Number(stats.status_WITHDRAW_REQUEST || stats.status_withdraw_request || stats.status_WITHDRAWN || stats.status_withdrawn || 0)));
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
            setChange('change-withdrawn-users', stats.withdrawnThisMonth ?? 0);
            setChange('change-patients', stats.patientsThisMonth ?? 0);
            setChange('change-hospitals', stats.hospitalsThisMonth ?? 0);

            // Update tab counts
            setText('tab-count-all', stats.totalUsers ?? 0);
            setText('tab-count-active', Number(stats.status_ACTIVE || stats.status_active || 0));
            setText('tab-count-pending', Number(stats.status_PENDING || stats.status_pending || 0));
            setText('tab-count-suspended', Number(stats.status_SUSPENDED || stats.status_suspended || 0));
            setText('tab-count-withdrawn', Number(stats.status_WITHDRAW_REQUEST || stats.status_withdraw_request || stats.status_WITHDRAWN || stats.status_withdrawn || 0));

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
                const distance = Math.sqrt(x * x + y * y);

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
        
        // Tab synchronization with status filter
        function setUserTab(el) {
            // Update UI
            document.querySelectorAll('.user-tab').forEach(t => t.classList.remove('active'));
            el.classList.add('active');
            
            // Update hidden filter and fetch
            const status = el.getAttribute('data-status');
            const statusEl = document.getElementById('status-filter');
            if (statusEl) {
                statusEl.value = status;
                fetchUsers();
            }
        }

        function syncTabsWithFilter() {
            const statusEl = document.getElementById('status-filter');
            if (!statusEl) return;
            const status = statusEl.value;
            
            document.querySelectorAll('.user-tab').forEach(t => {
                if (t.getAttribute('data-status') === status) {
                    t.classList.add('active');
                } else {
                    t.classList.remove('active');
                }
            });
        }

        // User Account Management Functions
        async function fetchUsers() {
            try {
                const searchEl = document.getElementById('user-search');
                const statusEl = document.getElementById('status-filter');
                const roleEl = document.getElementById('role-filter');

                const searchTerm = searchEl ? searchEl.value : '';
                const status = statusEl ? statusEl.value : '';
                const role = roleEl ? roleEl.value : '';

                const qs = new URLSearchParams({
                    search: searchTerm,
                    status: status,
                    role: role,
                });

                const response = await fetch(`${ROOT}/user-admin/getUsers?${qs.toString()}`);
                const data = await response.json();

                if (!data || !data.success) {
                    showToast('error', (data && data.message) ? data.message : 'Failed to load users.');
                    appState.users = [];
                    renderUsersTable();
                    return;
                }

                const users = Array.isArray(data.users)
                    ? data.users
                    : (data.users ? Object.values(data.users) : []);

                appState.users = users;
                appState.selectedUsers = [];
                const selectAll = document.getElementById('select-all');
                if (selectAll) selectAll.checked = false;
                updateBulkButtons();
                renderUsersTable();
            } catch (error) {
                console.error('Error fetching users:', error);
                showToast('error', 'Failed to load users.');
            }
        }

        function ensureModalOnBody(modalId) {
            const modal = document.getElementById(modalId);
            if (!modal) return;
            if (modal.parentElement !== document.body) {
                document.body.appendChild(modal);
            }
        }

        function renderUsersTable() {
            const tableContent = document.getElementById('users-table');
            if (!tableContent) return;
            const headerRow = tableContent.querySelector('.table-row');

            tableContent.innerHTML = '';
            if (headerRow) tableContent.appendChild(headerRow);

            const users = Array.isArray(appState.users) ? appState.users : [];

            users.forEach(user => {
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

                const id = encodeURIComponent(String(userId ?? ''));
                const roleStr = (role === undefined || role === null) ? '' : String(role);
                const roleEnc = encodeURIComponent(roleStr);

                const url = (roleStr && roleStr !== 'undefined' && roleStr !== 'null')
                    ? `${ROOT}/user-admin/getDetailedUser?id=${id}&role=${roleEnc}`
                    : `${ROOT}/user-admin/getDetailedUser?id=${id}`;

                const response = await fetch(url);
                if (!response.ok) {
                    showToast('error', `Failed to load details (${response.status}).`);
                    return;
                }

                let data;
                try {
                    data = await response.json();
                } catch (jsonErr) {
                    showToast('error', 'Invalid server response. Check console.');
                    console.error('JSON parse error:', jsonErr);
                    return;
                }

                if (!data || !data.success) {
                    showToast('error', (data && data.message) ? data.message : 'Failed to load user details.');
                    return;
                }

                const user = data.user;
                const statusUpper = (user.status || '').toUpperCase();
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
                        const isMedSchool = (user.role && user.role.toUpperCase() === 'MEDICAL_SCHOOL');
                        const isRecipient = (user.role && (user.role.toUpperCase() === 'RECIPIENT_PATIENT' || user.role.toUpperCase() === 'AFTERCARE_PATIENT'));
                        const isCustodian = (user.role && user.role.toUpperCase() === 'CUSTODIAN');
                        const isAdmin = (user.role && ['ADMIN', 'U_ADMIN', 'F_ADMIN', 'AC_ADMIN', 'D_ADMIN'].includes(user.role.toUpperCase()));

                        // Reset display
                        if (donorIdentity) donorIdentity.style.display = 'none';
                        if (hospitalIdentity) hospitalIdentity.style.display = 'none';
                        const medIdentity = document.getElementById('medical-school-identity-section');
                        if (medIdentity) medIdentity.style.display = 'none';
                        const adminIdentity = document.getElementById('admin-identity-section');
                        if (adminIdentity) adminIdentity.style.display = 'none';

                        if (summaryPhoneGroup) summaryPhoneGroup.style.display = 'block';
                        if (organDonorSection) organDonorSection.style.display = 'none';
                        const recipientIdentity = document.getElementById('recipient-identity-section');
                        if (recipientIdentity) recipientIdentity.style.display = 'none';
                        const custodianIdentity = document.getElementById('custodian-identity-section');
                        if (custodianIdentity) custodianIdentity.style.display = 'none';
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
                        } else if (isMedSchool) {
                            if (medIdentity) medIdentity.style.display = 'contents';
                            
                            const mName = document.getElementById('review-med-name');
                            if (mName) mName.innerText = user.school_name || '-';
                            
                            const mUniv = document.getElementById('review-med-univ');
                            if (mUniv) mUniv.innerText = user.univ_affiliation || '-';
                            
                            const mUgc = document.getElementById('review-med-ugc');
                            if (mUgc) mUgc.innerText = user.ugc_number || '-';
                            
                            const mContact = document.getElementById('review-med-contact-name');
                            if (mContact) mContact.innerText = user.contact_person || '-';
                            
                            const mPhone = document.getElementById('review-med-contact-phone');
                            if (mPhone) mPhone.innerText = user.contact_phone || '-';
                            
                            const mDistrict = document.getElementById('review-med-district');
                            if (mDistrict) mDistrict.innerText = user.district || '-';
                            
                            const mAddress = document.getElementById('review-med-address');
                            if (mAddress) mAddress.innerText = user.address || 'No address provided';
                        } else if (isAdmin) {
                            if (adminIdentity) adminIdentity.style.display = 'contents';

                            const adminStaffID = document.getElementById('review-admin-staff-id');
                            if (adminStaffID) adminStaffID.innerText = user.staff_id || 'N/A';

                            const adminDesignation = document.getElementById('review-admin-designation');
                            if (adminDesignation) adminDesignation.innerText = user.designation || 'N/A';

                            const adminContact = document.getElementById('review-admin-contact');
                            if (adminContact) adminContact.innerText = user.admin_contact || 'N/A';
                        } else if (isRecipient) {
                            if (recipientIdentity) recipientIdentity.style.display = 'contents';

                            const rType = document.getElementById('review-recipient-type');
                            if (rType) rType.innerText = user.patient_type || 'N/A';
                        } else if (isCustodian) {
                            if (custodianIdentity) custodianIdentity.style.display = 'contents';

                            const cDonor = document.getElementById('review-custodian-donor');
                            if (cDonor) cDonor.innerText = user.represented_donor_name || 'N/A';

                            const cRel = document.getElementById('review-custodian-relationship');
                            if (cRel) cRel.innerText = user.relationship || '-';

                            const cName = document.getElementById('review-custodian-name');
                            if (cName) cName.innerText = (user.first_name || '-') + (user.last_name ? ' ' + user.last_name : '');

                            const cNic = document.getElementById('review-custodian-nic');
                            if (cNic) cNic.innerText = user.nic || '-';

                            const cPhone = document.getElementById('review-custodian-phone');
                            if (cPhone) cPhone.innerText = user.custodian_phone || user.phone || 'N/A';

                            const cAddress = document.getElementById('review-custodian-address');
                            if (cAddress) cAddress.innerText = user.address || 'No address provided';
                        }

                        document.getElementById('review-firstname').value = user.first_name || user.school_name || user.name || '';
                        document.getElementById('review-lastname').value = user.last_name || '';
                        document.getElementById('review-phone').value = user.phone || '';

                        // Reset notices
                        const suspNotice = document.getElementById('suspension-notice');
                        const withNotice = document.getElementById('withdrawal-notice');
                        if (suspNotice) suspNotice.style.display = 'none';
                        if (withNotice) withNotice.style.display = 'none';

                        if (statusUpper === 'SUSPENDED') {
                            if (suspNotice) {
                                suspNotice.style.display = 'flex';
                                const reasonEl = document.getElementById('suspension-reason-text');
                                if (reasonEl) reasonEl.innerText = user.review_message || 'No reason specified.';
                            }
                        } else if (statusUpper === 'WITHDRAW_REQUEST' || statusUpper === 'WITHDRAWN') {
                            if (withNotice) {
                                withNotice.style.display = 'flex';
                                const withReasonEl = document.getElementById('withdrawal-reason-text');
                                const withDateEl = document.getElementById('withdrawal-date-text');
                                if (withReasonEl) withReasonEl.innerText = user.withdrawal_reason || 'User has requested to withdraw from the system.';
                                if (withDateEl) withDateEl.innerText = 'Requested on: ' + (user.withdrawal_date ? new Date(user.withdrawal_date).toLocaleString() : 'N/A');
                            }
                        }

                        document.getElementById('review-status-dropdown').value = (user.status || 'PENDING').toUpperCase();
                        document.getElementById('review-message').value = user.review_message || '';

                        const verifSection = document.getElementById('verification-section');
                        if (verifSection) {
                            if (statusUpper === 'PENDING') {
                                verifSection.style.display = 'block';
                                document.getElementById('verify-genuine').checked = false;
                                document.getElementById('verify-registry').checked = false;
                                                  // Show role-specific verifications
                            const donorControls = document.getElementById('donor-verification-controls');
                            const hospitalControls = document.getElementById('hospital-verification-controls');
                            const medControls = document.getElementById('medical-school-verification-controls');
                            
                            if (user.role && (user.role.toLowerCase() === 'donor' || user.role.toLowerCase() === 'custodian')) {
                                if (donorControls) donorControls.style.display = 'block';
                                if (hospitalControls) hospitalControls.style.display = 'none';
                                if (medControls) medControls.style.display = 'none';
                            } else if (user.role && user.role.toLowerCase() === 'hospital') {
                                if (donorControls) donorControls.style.display = 'none';
                                if (hospitalControls) hospitalControls.style.display = 'block';
                                if (medControls) medControls.style.display = 'none';
                                if (document.getElementById('hosp-reg-num-text')) {
                                    document.getElementById('hosp-reg-num-text').innerText = user.registration_number || 'N/A';
                                }
                            } else if (user.role && user.role.toLowerCase() === 'medical_school') {
                                if (donorControls) donorControls.style.display = 'none';
                                if (hospitalControls) hospitalControls.style.display = 'none';
                                if (medControls) medControls.style.display = 'block';
                            } else {
                                if (donorControls) donorControls.style.display = 'none';
                                if (hospitalControls) hospitalControls.style.display = 'none';
                                if (medControls) medControls.style.display = 'none';
                            }
                        } else {
                                verifSection.style.display = 'none';
                                document.getElementById('verify-genuine').checked = (statusUpper === 'ACTIVE');
                                document.getElementById('verify-registry').checked = (statusUpper === 'ACTIVE');
                                if (document.getElementById('verify-med-registry')) {
                                    document.getElementById('verify-med-registry').checked = (statusUpper === 'ACTIVE');
                                }
                            }
                        }

                        checkVerificationStatus();
                        ensureModalOnBody('review-user-modal');
                        document.getElementById('review-user-modal').classList.add('show');
                    } catch (uiErr) {
                        console.error("UI Population Error:", uiErr);
                        showToast('error', 'Critical UI error. Check console.');
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
                    if (currentRole === 'donor' || currentRole === 'custodian') {
                        canSave = genuine && donorRegistry;
                    } else if (currentRole === 'hospital') {
                        canSave = genuine && hospitalRegistry;
                    } else if (currentRole === 'medical_school') {
                        const medRegistry = document.getElementById('verify-med-registry').checked;
                        canSave = genuine && medRegistry;
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
                const medControls = document.getElementById('medical-school-verification-controls');
                if (currentRole === 'donor' || currentRole === 'custodian') {
                    if (donorControls) donorControls.style.display = 'block';
                    if (hospitalControls) hospitalControls.style.display = 'none';
                    if (medControls) medControls.style.display = 'none';
                } else if (currentRole === 'hospital') {
                    if (donorControls) donorControls.style.display = 'none';
                    if (hospitalControls) hospitalControls.style.display = 'block';
                    if (medControls) medControls.style.display = 'none';
                } else if (currentRole === 'medical_school') {
                    if (donorControls) donorControls.style.display = 'none';
                    if (hospitalControls) hospitalControls.style.display = 'none';
                    if (medControls) medControls.style.display = 'block';
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
            const suspensionNotice = document.getElementById('suspension-notice');
            if (suspensionNotice) suspensionNotice.style.display = 'none'; // Reset notice on any status change

            if (status === 'ACTIVE') {
                btnSave.style.background = '#059669'; // Emerald-600
                btnText.innerText = 'Confirm Approval';
                btnIcon.className = 'fa-solid fa-circle-check';
                iconBox.style.background = '#ecfdf5'; // Emerald-50
                icon.className = 'fa-solid fa-circle-check';
                icon.style.color = '#059669';
            } else if (status === 'SUSPENDED') {
                btnSave.style.background = '#dc2626'; // Red-600
                btnText.innerText = 'Suspend Account';
                btnIcon.className = 'fa-solid fa-user-lock';
                iconBox.style.background = '#fee2e2'; // Red-50
                icon.className = 'fa-solid fa-circle-xmark';
                icon.style.color = '#dc2626';
            } else if (status === 'WITHDRAWN' || status === 'WITHDRAW_REQUEST') {
                btnSave.style.background = '#475569'; // Slate-600
                btnText.innerText = 'Finalize Withdrawal';
                btnIcon.className = 'fa-solid fa-user-slash';
                iconBox.style.background = '#f1f5f9'; // Slate-50
                icon.className = 'fa-solid fa-user-slash';
                icon.style.color = '#475569';
            } else {
                btnSave.style.background = '#1e56a0'; // Default Blue
                btnText.innerText = 'Save Changes';
                btnIcon.className = 'fa-solid fa-save';
                iconBox.style.background = '#eff6ff'; // Blue-50
                icon.className = 'fa-solid fa-circle-info';
                icon.style.color = '#1e56a0';
            }
        }

        function generateReviewMessage() {
            const genuine = document.getElementById('verify-genuine').checked;
            const registry = document.getElementById('verify-registry').checked;
            const status = document.getElementById('review-status-dropdown').value;
            const msgBox = document.getElementById('review-message');

            const originalStatus = document.getElementById('review-user-status').value;

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
                "Account suspended for administrative review.",
                "user requested to withdraw so now suspended account"
            ];

            if (currentMsg !== "" && !standardPatterns.includes(currentMsg)) return;

            if (status === 'ACTIVE') {
                if (originalStatus === 'SUSPENDED') {
                    msgBox.value = "Account reactivated: Following administrative review, your access has been restored and all issues have been resolved.";
                } else {
                    msgBox.value = "Account verified successfully. All documentation matches official records.";
                }
            } else if (status === 'SUSPENDED') {
                 if (originalStatus === 'WITHDRAW_REQUEST' || originalStatus === 'WITHDRAWN') {
                     msgBox.value = "user requested to withdraw so now suspended account";
                     return;
                 }

                 const donorRegistry = document.getElementById('verify-registry').checked;
                 const hospitalRegistry = document.getElementById('verify-hospital-registry').checked;
                 const medRegistryEl = document.getElementById('verify-med-registry');
                 const medRegistry = medRegistryEl ? medRegistryEl.checked : false;
                 const userRoleElement = document.getElementById('review-user-role-display');
                 const roleText = userRoleElement ? userRoleElement.innerText.split('|')[0].trim().toLowerCase() : '';
                 const currentRole = roleText.replace(' ', '_');
                 
                 let registry = false;
                 let registryName = "";
                 let registryFailTerm = "";

                 if (currentRole === 'hospital') {
                     registry = hospitalRegistry;
                     registryName = "Hospital PHSRC registry";
                     registryFailTerm = "Hospital registration";
                 } else if (currentRole === 'medical_school') {
                     registry = medRegistry;
                     registryName = "official UGC universities registry";
                     registryFailTerm = "UGC accreditation number";
                 } else {
                     registry = donorRegistry;
                     registryName = "official Election Commission registry";
                     registryFailTerm = "NIC record";
                 }

                 if (!genuine && !registry) {
                     msgBox.value = `Verification failed: Profile data authenticity concerns and ${registryFailTerm} could not be verified.`;
                 } else if (!genuine) {
                     msgBox.value = "Verification failed: Profile information and submitted details could not be validated for authenticity.";
                 } else if (!registry) {
                     msgBox.value = `Verification failed: ${registryFailTerm} could not be verified via the ${registryName}.`;
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

        async function executeStatusUpdate(userId, role, newStatus, currentAction, reviewMessage, data) {
            try {
                const response = await fetch(`${ROOT}/user-admin/reviewUser`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ 
                        user_id: userId, 
                        role: role, 
                        action: currentAction, 
                        data: data,
                        new_status: newStatus,
                        review_message: reviewMessage
                    })
                });

                const text = await response.text();
                let result;
                try {
                    result = JSON.parse(text);
                } catch (e) {
                    console.error('SERVER SENT MALFORMED DATA:', text);
                    showToast('error', 'Server error: Malformed data received.');
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
                showToast('error', 'Update failed: Check your connection.');
            }
        }

        async function submitUserReview(action) {
            const userId = document.getElementById('review-user-id').value;
            const role = document.getElementById('review-user-role').value;
            const newStatus = document.getElementById('review-status-dropdown').value;
            const reviewMessage = document.getElementById('review-message').value;

            // Two-stage Inline Suspension Confirmation
            if (newStatus === 'SUSPENDED') {
                const notice = document.getElementById('suspension-notice');
                const btnText = document.getElementById('btn-save-text');
                const btnIcon = document.getElementById('btn-save-icon');

                if (notice && notice.style.display === 'none') {
                    notice.style.display = 'flex';
                    if (btnText) btnText.innerText = 'Yes, Suspend Account';
                    if (btnIcon) btnIcon.className = 'fa-solid fa-user-slash';
                    return; // Stop here for first stage
                }
            }
            
            const data = {
                first_name: document.getElementById('review-firstname')?.value || '',
                last_name: document.getElementById('review-lastname')?.value || '',
                phone: document.getElementById('review-phone')?.value || ''
            };

            let submitAction = action;
            if (action === 'UPDATE') {
                const currentStatus = document.getElementById('review-user-status').value;
                if (newStatus !== currentStatus) {
                    submitAction = (newStatus === 'ACTIVE') ? 'APPROVE' : (newStatus === 'SUSPENDED' ? 'REJECT' : 'UPDATE');
                }
            }

            await executeStatusUpdate(userId, role, newStatus, submitAction, reviewMessage, data);
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
                    ensureModalOnBody('edit-user-modal');
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

        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.style.display = 'flex';
                setTimeout(() => {
                    modal.classList.add('show');
                }, 10);
            }
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.remove('show');
                // Ensure inline display is also reset for premium modals
                setTimeout(() => {
                    if (!modal.classList.contains('show')) {
                        modal.style.display = 'none';
                    }
                }, 300); // Wait for transition
            }
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
            const headerRow = tableContent ? tableContent.querySelector('.table-row') : null;
            if (!tableContent || !headerRow) return;

            tableContent.innerHTML = '';
            tableContent.appendChild(headerRow);

            // Add delegation listener once if not already present
            if (!tableContent.dataset.hasListener) {
                tableContent.addEventListener('click', (e) => {
                    const row = e.target.closest('.clickable-row');
                    if (row && row.dataset.notifId) {
                        e.stopImmediatePropagation();
                        openNotificationDetail(row.dataset.notifId);
                    }
                });
                tableContent.dataset.hasListener = 'true';
            }

            const notifs = Array.isArray(appState.notifications) ? appState.notifications : [];

            notifs.forEach(notification => {
                const row = document.createElement('div');
                row.className = 'table-row clickable-row';
                row.style.cursor = 'pointer';
                row.dataset.notifId = notification.id;
                
                row.innerHTML = `
                    <div class="table-cell name" data-label="Notification">
                        <strong>${notification.recipient_name || notification.recipient || 'System Record'}</strong><br>
                        <small>${notification.title}</small>
                    </div>
                    <div class="table-cell" data-label="Type">${(notification.type || 'SYSTEM').toUpperCase()}</div>
                    <div class="table-cell status" data-label="Status">
                        <span class="status-badge status-${notification.is_read ? 'active' : 'pending'}">${notification.is_read ? 'Read' : 'Unread'}</span>
                    </div>
                    <div class="table-cell" data-label="Sent">${notification.created_at ? new Date(notification.created_at).toLocaleString() : 'N/A'}</div>
                `;
                tableContent.appendChild(row);
            });
        }

        function openNotificationDetail(notifId) {
            if (appState.isProcessingNotif) return;
            appState.isProcessingNotif = true;
            setTimeout(() => { appState.isProcessingNotif = false; }, 300);

            console.log('Requesting Notification Detail for ID:', notifId);
            console.trace('Notification detail trigger source:');
            
            // Ensure modal is unique and on body
            ensureModalOnBody('notif-details-modal');
            const notification = (appState.notifications || []).find(n => n.id == notifId);
            if (notification) {
                const titleEl = document.getElementById('notif-modal-title');
                const dateEl = document.getElementById('notif-modal-date');
                const recipientEl = document.getElementById('notif-recipient-text');
                const typeEl = document.getElementById('notif-type-text');
                const statusEl = document.getElementById('notif-status-text');
                const bodyEl = document.getElementById('notif-message-body');
                
                if(titleEl) titleEl.textContent = notification.title;
                if(dateEl) dateEl.textContent = 'Sent on: ' + (notification.created_at ? new Date(notification.created_at).toLocaleString() : 'N/A');
                if(recipientEl) recipientEl.textContent = notification.recipient_name || notification.recipient;
                if(typeEl) typeEl.textContent = (notification.type || 'SYSTEM').toUpperCase();
                
                if (statusEl) {
                    statusEl.textContent = notification.is_read ? 'Read' : 'Unread';
                    statusEl.className = `status-badge status-${notification.is_read ? 'active' : 'pending'}`;
                }
                
                if(bodyEl) {
                    bodyEl.textContent = notification.message;
                    bodyEl.className = 'modal-break-word'; // Prevent horizontal scroll
                }
                
                const icon = document.getElementById('notif-type-icon');
                const iconBox = document.getElementById('notif-type-icon-box');
                
                if (icon && iconBox) {
                    if (notification.type === 'alert') {
                        icon.className = 'fa-solid fa-triangle-exclamation';
                        icon.style.color = '#dc2626';
                        iconBox.style.background = '#fee2e2';
                    } else if (notification.type === 'approval') {
                        icon.className = 'fa-solid fa-circle-check';
                        icon.style.color = '#059669';
                        iconBox.style.background = '#ecfdf5';
                    } else {
                        icon.className = 'fa-solid fa-bell';
                        icon.style.color = '#3b82f6';
                        iconBox.style.background = '#eff6ff';
                    }
                }
                
                const modal = document.getElementById('notif-details-modal');
                if (modal) {
                    modal.style.display = 'flex';
                    modal.classList.add('show');
                }
            } else {
                console.warn('Notification not found in state for ID:', notifId);
            }
        }


        document.getElementById('eligibility-form').addEventListener('submit', function (e) {
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
        // NOTE: notification-recipient listener removed as it is now handled by updateNotifTargeting()

        document.getElementById('new-eligibility-status').addEventListener('change', function (e) {
            const reasonGroup = document.getElementById('eligibility-reason-group');
            if (e.target.value === 'temp-ineligible' || e.target.value === 'perm-ineligible') {
                reasonGroup.style.display = 'block';
            } else {
                reasonGroup.style.display = 'none';
            }
        });

        // Utility Functions
        function formatRole(role) {
            const roleMap = {
                'donor': 'Donor',
                'custodian': 'Custodian',
                'patient': 'Patient',
                'hospital': 'Hospital',
                'financial': 'Financial Donor',
                'medical_school': 'Medical School',
                'recipient_patient': 'Aftercare Recipient',
                'aftercare_patient': 'Aftercare Patient'
            };
            return roleMap[role.toLowerCase()] || role;
        }

        function formatStatus(status) {
            const statusMap = {
                'active': 'Active',
                'pending': 'Pending',
                'suspended': 'Suspended',
                'approved': 'Approved',
                'rejected': 'Rejected',
                'delivered': 'Delivered',
                'withdrawn': 'Withdrawn',
                'withdraw_request': 'Withdrawn'
            };
            return statusMap[String(status).toLowerCase()] || status;
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
            if (!toast || !messageEl) return;

            messageEl.textContent = message || (type === 'success' ? 'Action completed successfully' : 'An error occurred.');
            toast.className = `notification ${type} show`;

            setTimeout(() => {
                toast.classList.remove('show');
            }, 5000);
        }





        // Initialize Dashboard
        document.addEventListener('DOMContentLoaded', function () {
            // initDashboard is already called via window.onload
        });

        async function fetchAuditLogs() {
            try {
                const response = await fetch(`${ROOT}/user-admin/getAuditLogs`);
                const data = await response.json();
                if (data.success) {
                    appState.auditLogs = data.auditLogs;
                    renderAuditTable();
                }
            } catch (error) {
                console.error('Error fetching audit logs:', error);
            }
        }

        function renderAuditTable() {
            const tableContent = document.getElementById('audit-table');
            if (!tableContent) return;
            const headerRow = tableContent.querySelector('.table-row');
            const searchInput = document.getElementById('audit-search');
            const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';

            tableContent.innerHTML = '';
            if (headerRow) tableContent.appendChild(headerRow);

            // Add delegation listener once if not already present
            if (!tableContent.dataset.hasListener) {
                tableContent.addEventListener('click', (e) => {
                    const row = e.target.closest('.clickable-row');
                    if (row && row.dataset.logId) {
                        e.stopImmediatePropagation();
                        openAuditDetail(row.dataset.logId);
                    }
                });
                tableContent.dataset.hasListener = 'true';
            }

            const filteredLogs = (appState.auditLogs || []).filter(log => {
                const admin = (log.admin_name || '').toLowerCase();
                const target = (log.target_name || '').toLowerCase();
                const action = (log.action || '').toLowerCase();
                return admin.includes(searchTerm) || target.includes(searchTerm) || action.includes(searchTerm);
            });

            filteredLogs.forEach(log => {
                const row = document.createElement('div');
                row.className = 'table-row clickable-row';
                row.style.cursor = 'pointer';
                row.dataset.logId = log.id;
                
                row.innerHTML = `
                    <div class="table-cell">
                        <strong>${log.admin_name || 'System'}</strong>
                    </div>
                    <div class="table-cell"><span class="status-badge" style="background:#f1f5f9; color:#475569;">${log.action || 'Unknown'}</span></div>
                    <div class="table-cell">${log.target_name || '<span style="color:#94a3b8">Global System</span>'}</div>
                    <div class="table-cell">${log.created_at ? new Date(log.created_at).toLocaleString() : 'N/A'}</div>
                `;
                tableContent.appendChild(row);
            });
        }

        function openAuditDetail(logId) {
            if (appState.isProcessingAudit) return;
            appState.isProcessingAudit = true;
            setTimeout(() => { appState.isProcessingAudit = false; }, 300);

            console.log('Requesting Audit Detail for ID:', logId);
            console.trace('Audit detail trigger source:');

            // Ensure modal is unique and on body
            ensureModalOnBody('audit-details-modal');
            const log = (appState.auditLogs || []).find(l => l.id == logId);
            if (log) {
                document.getElementById('audit-modal-title').textContent = (log.action || 'Event').replace(/_/g, ' ');
                document.getElementById('audit-modal-date').textContent = 'Recorded on: ' + (log.created_at ? new Date(log.created_at).toLocaleString() : 'N/A');
                
                const adminEl = document.getElementById('audit-admin-text');
                const targetEl = document.getElementById('audit-target-text');
                const oldValEl = document.getElementById('audit-old-val');
                const newValEl = document.getElementById('audit-new-val');
                const notesEl = document.getElementById('audit-notes-body');

                if (adminEl) adminEl.textContent = log.admin_name || 'System';
                if (targetEl) targetEl.textContent = log.target_name || 'Global System';
                if (oldValEl) {
                    oldValEl.textContent = log.old_value || 'NULL';
                    oldValEl.className = 'modal-break-word';
                }
                if (newValEl) {
                    newValEl.textContent = log.new_value || 'NULL';
                    newValEl.className = 'modal-break-word';
                }
                if (notesEl) {
                    notesEl.textContent = log.notes || 'No additional notes provided.';
                    notesEl.className = 'modal-break-word';
                }
                
                const modal = document.getElementById('audit-details-modal');
                if (modal) {
                    modal.style.display = 'flex';
                    modal.classList.add('show');
                }
            } else {
                console.warn('Audit Log not found in state for ID:', logId);
            }
        }

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