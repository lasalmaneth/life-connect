<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/life-connect/public/assets/css/style.css">
    <link rel="stylesheet" href="/life-connect/public/assets/css/admin/style.css">
    <link rel="stylesheet" href="/life-connect/public/assets/css/admin/donation-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css">
    <title>Donation Admin | LifeConnect</title>
    <style>
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
    .nav-link i { font-size: 1rem; }
    /* Sidebar scrollable — no visible scrollbar */
    .sidebar {
        overflow-y: auto;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    .sidebar::-webkit-scrollbar { display: none; }
    body { background-color: #f8fafc; min-height: 100vh; }
    .stats-grid { margin-top: 20px; }

    /* Success Stories Table Styles */
    .tribute-row {
        display: grid;
        grid-template-columns: 1.5fr 2.5fr 1.2fr 130px;
        gap: 1rem;
        padding: 1.2rem 1.5rem;
        border-bottom: 1px solid #f1f5f9;
        cursor: pointer;
        transition: all 0.2s;
        align-items: center;
    }
    .tribute-row:hover {
        background: #f8fafc;
        box-shadow: inset 4px 0 0 #3b82f6;
    }
    .status-published { background: #dcfce7; color: #166534; }
    .status-approved { background: #dbeafe; color: #1e40af; }
    .status-pending { background: #fef9c3; color: #854d0e; }
    .status-draft { background: #f1f5f9; color: #475569; }
    .status-archived { background: #fee2e2; color: #991b1b; }
    
    .organ-row {
        display: grid;
        grid-template-columns: 1.5fr 2.5fr 1.2fr 130px;
        gap: 1rem;
        padding: 1.2rem 1.5rem;
        border-bottom: 1px solid #f1f5f9;
        cursor: pointer;
        transition: all 0.2s;
        align-items: center;
    }
    .organ-row:hover {
        background: #f8fafc;
        box-shadow: inset 4px 0 0 #3b82f6;
    }

    /* Hospital Request Specific Status & Priority */
    .status-open { background: #fef9c3; color: #854d0e; }
    .status-matched { background: #dcfce7; color: #166534; }
    .status-closed { background: #fee2e2; color: #991b1b; }

    .priority-critical { background: #fee2e2; color: #991b1b; }
    .priority-urgent { background: #ffedd5; color: #9a3412; }
    .priority-normal { background: #f1f5f9; color: #475569; }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
        margin-top: 20px;
    }

    /* Progress Bar Chart Styles */
    .chart-card {
        background: white;
        padding: 1.5rem 2rem;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        border: 1px solid #e2e8f0;
        grid-column: span 2;
        max-width: 100%; /* Take full width of the 2-column span */
    }
    .chart-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .bar-group {
        margin-bottom: 1.5rem;
    }
    .bar-group:last-child { margin-bottom: 0; }
    .bar-label {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
        font-weight: 600;
        color: #475569;
    }
    .bar-track {
        height: 20px;
        background: #f1f5f9;
        border-radius: 50px;
        overflow: hidden;
    }
    .bar-fill {
        height: 100%;
        border-radius: 50px;
        transition: width 0.6s ease;
    }

    /* Stat Card See All Icon */
    .stat-card {
        position: relative;
    }
    .see-all-link {
        position: absolute;
        top: 1.25rem;
        right: 1.25rem;
        color: #94a3b8;
        font-size: 0.9rem;
        transition: all 0.2s;
        text-decoration: none;
    }
    .see-all-link:hover {
        color: #3b82f6;
        transform: translate(2px, -2px);
    }

    /* Vertical Pillar Chart Styles */
    .priority-chart-card {
        grid-column: span 1 !important;
        padding: 1.5rem !important;
    }
    .pillar-container {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        height: 160px;
        padding-top: 0.5rem;
        gap: 8px;
    }
    .pillar-item {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
        height: 100%;
        justify-content: flex-end;
    }
    .pillar-bar {
        width: 35px;
        border-radius: 50px 50px 10px 10px;
        transition: height 0.8s ease;
        position: relative;
        cursor: pointer;
    }
    .pillar-bar:hover {
        filter: brightness(1.1);
        transform: scaleX(1.05);
    }
    .pillar-label {
        font-size: 0.75rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .pillar-value {
        font-size: 0.85rem;
        font-weight: 700;
        color: #1e293b;
    }
    </style>
</head>
<body>
<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<script src="/life-connect/public/assets/js/admin/donation.js?v=<?= time() ?>" defer></script>
<script src="/life-connect/public/assets/js/admin/matching.js?v=<?= time() ?>" defer></script>
<script src="/life-connect/public/assets/js/admin/tributes.js?v=<?= time() ?>" defer></script>

    <div class="header">
        <div class="header-content">
            <div class="header-left">
                <a href="<?= ROOT ?>" style="text-decoration:none; display:flex; align-items:center; gap:10px;">
                    <img src="<?= ROOT ?>/public/assets/images/logo.png" alt="LifeConnect" style="height:40px;">
                    <div>
                        <strong style="display:block; font-size:1.1rem; color:#003b6e; line-height:1.2;">LifeConnect</strong>
                        <p style="margin:0; font-size:.68rem; color:#6b7280;">Donation Administration</p>
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
                    <div class="user-avatar">A</div>
                    <div class="user-details">
                        <span class="user-name"><?= htmlspecialchars($_SESSION['username'] ?? 'Admin', ENT_QUOTES, 'UTF-8') ?></span>
                        <span class="user-role">Donation Administrator</span>
                    </div>
                    <i class="fa-solid fa-chevron-down ms-2 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid p-0">
        <div class="main-content">
            <div class="sidebar glass">
                <div class="sidebar-user-card">
                    <div class="sidebar-user-avatar">A</div>
                    <div class="sidebar-user-info">
                        <span class="sidebar-user-name">admin_4</span>
                        <span class="sidebar-user-id">ID-00004</span>
                        <span class="sidebar-user-role">System Admin</span>
                    </div>
                </div>

                <div class="menu-section">
                    <div class="menu-section-title">Core</div>
                    <a href="javascript:void(0)" class="menu-item active" onclick="showContent('dashboard', this)">
                        <span class="icon"><i class="fa-solid fa-chart-line"></i></span>
                        <span>Dashboard</span>
                    </a>
                </div>

                <div class="menu-section">
                    <div class="menu-section-title">Organ Management</div>
                    <a href="javascript:void(0)" class="menu-item" onclick="showContent('donor-organs', this)">
                        <span class="icon"><i class="fa-solid fa-briefcase-medical"></i></span>
                        <span>Pledged Organs</span>
                    </a>
                    <a href="javascript:void(0)" class="menu-item" onclick="showContent('matching', this)">
                        <span class="icon"><i class="fa-solid fa-handshake"></i></span>
                        <span>Matching</span>
                    </a>
                    <a href="javascript:void(0)" class="menu-item" onclick="showContent('hospital-requests', this)">
                        <span class="icon"><i class="fa-solid fa-hospital-user"></i></span>
                        <span>Hospital Requests</span>
                    </a>
                </div>

                <div class="menu-section">
                    <div class="menu-section-title">Community</div>
                    <a href="javascript:void(0)" class="menu-item" onclick="showContent('tributes', this)" style="display: flex; align-items: center; justify-content: space-between; position: relative;">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <span class="icon"><i class="fa-solid fa-heart"></i></span>
                            <span>Success Stories</span>
                        </div>
                        <span id="nav-stories-badge" style="background: #ef4444; color: white; font-size: 0.7rem; font-weight: 700; min-width: 18px; height: 18px; line-height: 18px; text-align: center; border-radius: 50%; display: none;">0</span>
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
                        <div class="stats-grid">
                            <div class="stat-card">
                                <div class="stat-number" id="total-donors">0</div>
                                <div class="stat-label">Total Organ Pledgers</div>
                                <div class="stat-change positive">Loading...</div>
                            </div>
                            <div class="stat-card">
                                <a href="javascript:void(0)" class="see-all-link" title="See All Pledges" onclick="showContent('donor-organs', document.querySelector('.menu-item[onclick*=\'donor-organs\']'))">
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                </a>
                                <div class="stat-number" id="total-organs">0</div>
                                <div class="stat-label">Organ Pledges</div>
                                <div class="stat-change positive">Loading...</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number" id="pending-approvals">0</div>
                                <div class="stat-label">Action Required</div>
                                <div class="stat-change warning">Loading...</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number" id="completed-donations">0</div>
                                <div class="stat-label">Successful Matches</div>
                                <div class="stat-change positive">Loading...</div>
                            </div>

                            <!-- Organ Request Visualization (now inside the grid) -->
                            <div class="chart-card">
                                <h3 class="chart-title"> Hospital Organ Demand</h3>
                                <?php 
                                $total_req = $request_stats['total'] > 0 ? $request_stats['total'] : 1;
                                $stat_items = [
                                    ['label' => 'Pending Requests', 'count' => $request_stats['open'], 'color' => '#fef08a', 'border' => '#713F12'],
                                    ['label' => 'Matches Found', 'count' => $request_stats['matched'], 'color' => '#bbf7d0', 'border' => '#166534'],
                                    ['label' => 'Completed Transfers', 'count' => $request_stats['closed'], 'color' => '#fecaca', 'border' => '#991B1B']
                                ];
                                foreach($stat_items as $item):
                                    $percentage = ($item['count'] / $total_req) * 100;
                                ?>
                                <div class="bar-group">
                                    <div class="bar-label">
                                        <span><?= $item['label'] ?></span>
                                        <span><?= $item['count'] ?> <span style="font-weight: 400; color: #94a3b8; font-size: 0.8rem;">(<?= round($percentage, 1) ?>%)</span></span>
                                    </div>
                                    <div class="bar-track">
                                        <div class="bar-fill" style="width: <?= $percentage ?>%; background-color: <?= $item['color'] ?>; border-color: <?= $item['border'] ?>;"></div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>

                            <!-- Organ Request Priority Visualization (Vertical Pillars) -->
                            <div class="chart-card priority-chart-card">
                                <h3 class="chart-title"> Demand Urgency</h3>
                                <div class="pillar-container">
                                    <?php 
                                    $p_stats = $priority_stats;
                                    $max_val = max($p_stats['normal'], $p_stats['urgent'], $p_stats['critical'], 1);
                                    
                                    $items = [
                                        ['label' => 'Normal', 'count' => $p_stats['normal'], 'color' => '#e2e8f0', 'border' => '#374151'],
                                        ['label' => 'Urgent', 'count' => $p_stats['urgent'], 'color' => '#fed7aa', 'border' => '#9A3412'],
                                        ['label' => 'Critical', 'count' => $p_stats['critical'], 'color' => '#fecdd3', 'border' => '#9F1239']
                                    ];

                                    foreach($items as $item):
                                        $h_perc = ($item['count'] / $max_val) * 100;
                                        // Ensure minimum visible height for zero counts if desired, or just use 0
                                        $display_height = max($h_perc, 0); 
                                    ?>
                                    <div class="pillar-item">
                                        <div class="pillar-value"><?= $item['count'] ?></div>
                                        <div class="pillar-bar" style="height: <?= $display_height ?>%; background: <?= $item['color'] ?>; border-color: <?= $item['border'] ?>;" title="<?= $item['label'] ?>: <?= $item['count'] ?> requests">
                                        </div>
                                        <div class="pillar-label"><?= $item['label'] ?></div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Donor Organs Table -->
                <div id="donor-organs" class="content-section" style="display: none;">
                    <div class="content-header">
                        <h2>Donor Organs Management</h2>
                        <p>View and manage all organs pledged by donors for donation</p>
                    </div>
                        <div style="display: flex; gap: 12px; align-items: center; margin-top: 32px; margin-bottom: 32px; justify-content: space-between; padding: 0 4px;">
                            <div class="search-bar" style="margin-bottom: 0; flex: 1; max-width: 400px;">
                                <span class="search-icon"><i class="fa-solid fa-magnifying-glass"></i></span>
                                <input type="text" class="search-input" placeholder="Search donors, organs..." id="organ-search" oninput="handleOrganFilter()">
                            </div>
                            <div style="display: flex; gap: 10px; align-items: center;">
                                <select class="filter-select" id="organ-type-filter" onchange="handleOrganFilter()" style="min-width: 140px; height: 44px; padding: 0 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.9rem; background: white; cursor: pointer;">
                                    <option value="">All Organs</option>
                                    <option value="Heart">Heart</option>
                                    <option value="Liver">Liver</option>
                                    <option value="Kidney">Kidney</option>
                                    <option value="Lungs">Lungs</option>
                                    <option value="Pancreas">Pancreas</option>
                                    <option value="Intestine">Intestine</option>
                                    <option value="Eyes">Eyes</option>
                                    <option value="Skin">Skin</option>
                                    <option value="Bone">Bone</option>
                                </select>
                                <select class="filter-select" id="blood-type-filter" onchange="handleOrganFilter()" style="min-width: 110px; height: 44px; padding: 0 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.9rem; background: white; cursor: pointer;">
                                    <option value="">All Blood</option>
                                    <option value="A+">A+</option>
                                    <option value="A-">A-</option>
                                    <option value="B+">B+</option>
                                    <option value="B-">B-</option>
                                    <option value="AB+">AB+</option>
                                    <option value="AB-">AB-</option>
                                    <option value="O+">O+</option>
                                    <option value="O-">O-</option>
                                </select>
                                <select class="filter-select" id="status-filter" onchange="handleOrganFilter()" style="min-width: 120px; height: 44px; padding: 0 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.9rem; background: white; cursor: pointer;">
                                    <option value="">All Status</option>
                                    <option value="Pending">Pending</option>
                                    <option value="Approved">Approved</option>
                                    <option value="Completed">Completed</option>
                                    <option value="Rejected">Rejected</option>
                                </select>
                                <div style="position: relative;">
                                    <button type="button" id="date-range-icon" title="Filter by Date Range" onclick="toggleDateRangePicker()" style="background: white; border: 1px solid #e2e8f0; border-radius: 8px; width: 44px; height: 44px; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #64748b; transition: all 0.2s;">
                                        <i class="fa-solid fa-calendar-days"></i>
                                    </button>
                                    <div id="date-range-picker" style="display: none; position: absolute; top: 52px; right: 0; background: white; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.25rem; box-shadow: 0 10px 25px rgba(0,0,0,0.1); z-index: 100; width: 280px;">
                                        <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                                            <h5 style="margin: 0; font-size: 0.85rem; color: #1e293b; font-weight: 700;">Filter by Range</h5>
                                            <div>
                                                <label style="display: block; font-size: 0.7rem; color: #94a3b8; text-transform: uppercase; font-weight: 700; margin-bottom: 4px;">From</label>
                                                <input type="date" id="date-from" onchange="handleOrganFilter()" style="width: 100%; padding: 0.5rem; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 0.85rem;">
                                            </div>
                                            <div>
                                                <label style="display: block; font-size: 0.7rem; color: #94a3b8; text-transform: uppercase; font-weight: 700; margin-bottom: 4px;">To</label>
                                                <input type="date" id="date-to" onchange="handleOrganFilter()" style="width: 100%; padding: 0.5rem; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 0.85rem;">
                                            </div>
                                            <button onclick="resetDateRange()" style="margin-top: 0.5rem; background: #fee2e2; border: none; padding: 0.6rem; border-radius: 6px; cursor: pointer; color: #991b1b; font-weight: 700; font-size: 0.75rem; transition: all 0.2s;" onmouseover="this.style.background='#fecaca'" onmouseout="this.style.background='#fee2e2'">
                                                <i class="fa-solid fa-rotate-left" style="margin-right: 4px;"></i> Reset Dates
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="data-table">
                            <div class="table-content" id="donor-organs-table" style="width: 100%;">
                                <div class="table-row" style="font-weight: 600; color: #64748b; font-size:0.85rem; text-transform:uppercase; background: #f8fafc; display: grid; grid-template-columns: 1.5fr 2.5fr 1.2fr 130px; gap: 1rem; padding: 1rem 1.5rem; border-bottom: 1px solid #e5e7eb;">
                                    <div class="table-cell">Donor Name</div>
                                    <div class="table-cell">Organ & Blood Type</div>
                                    <div class="table-cell">Pledge Date</div>
                                    <div class="table-cell" style="text-align: center;">Status</div>
                                </div>
                                <!-- Data will be loaded via JS -->
                                <div id="organs-loader" style="text-align: center; padding: 3rem; color: #94a3b8;">
                                    <i class="fa-solid fa-spinner fa-spin" style="font-size: 2rem; margin-bottom: 1rem;"></i>
                                    <p>Loading donor pledges...</p>
                                </div>
                            </div>
                        </div>
                    </div>

                <!-- Matching Section -->
                <div id="matching" class="content-section" style="display: none;">
                    <div class="content-header" style="padding: 1.5rem 2rem;">
                        <h2>Donor-Recipient Matching</h2>
                        <p>Manage organ matching between donors and recipients</p>
                    </div>
                    <div class="content-body">
                        <div style="display: flex; gap: 12px; align-items: center; margin-top: 16px; margin-bottom: 24px; justify-content: space-between; padding: 0 4px;">
                            <div class="search-bar" style="margin-bottom: 0; flex: 1; max-width: 350px;">
                                <span class="search-icon"><i class="fa-solid fa-magnifying-glass"></i></span>
                                <input type="text" class="search-input" placeholder="Search donor or ID..." id="matching-search">
                            </div>
                            <div style="display: flex; gap: 10px; align-items: center;">
                                <select class="filter-select" id="matching-status-filter" style="min-width: 130px; height: 44px; padding: 0 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.9rem; background: white; cursor: pointer;">
                                    <option value="">All Status</option>
                                    <option value="Pending">Pending</option>
                                    <option value="In Progress">In Progress</option>
                                    <option value="Completed">Completed</option>
                                    <option value="Cancelled">Cancelled</option>
                                </select>
                            </div>
                        </div>

                        <div id="matching-table" style="width: 100%;">
                            <!-- Data will be loaded via PHP -->
                            <?php include 'matches.view.php'; ?>
                        </div>
                    </div>
                </div>

                <!-- Hospital Requests Section -->
                <div id="hospital-requests" class="content-section" style="display: none;">
                    <div class="content-header" style="padding: 1.5rem 2rem;">
                        <h2>Hospital Organ Requests</h2>
                        <p>View and manage organ requests submitted by hospitals</p>
                    </div>
                    <div class="content-body">
                        <div style="display: flex; gap: 12px; align-items: center; margin-top: 16px; margin-bottom: 24px; justify-content: space-between; padding: 0 4px;">
                            <div class="search-bar" style="margin-bottom: 0; flex: 1; max-width: 350px;">
                                <span class="search-icon"><i class="fa-solid fa-magnifying-glass"></i></span>
                                <input type="text" class="search-input" placeholder="Search hospital or organ..." id="hospital-request-search" oninput="handleHospitalRequestFilter()">
                            </div>
                            <div style="display: flex; gap: 10px; align-items: center;">
                                <select class="filter-select" id="request-organ-filter" onchange="handleHospitalRequestFilter()" style="min-width: 130px; height: 44px; padding: 0 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.9rem; background: white; cursor: pointer;">
                                    <option value="">All Organs</option>
                                    <option value="Heart">Heart</option>
                                    <option value="Liver">Liver</option>
                                    <option value="Kidney">Kidney</option>
                                    <option value="Lungs">Lungs</option>
                                    <option value="Pancreas">Pancreas</option>
                                    <option value="Intestine">Intestine</option>
                                    <option value="Eyes">Eyes</option>
                                    <option value="Skin">Skin</option>
                                    <option value="Bone">Bone</option>
                                </select>
                                <select class="filter-select" id="request-priority-filter" onchange="handleHospitalRequestFilter()" style="min-width: 120px; height: 44px; padding: 0 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.9rem; background: white; cursor: pointer;">
                                    <option value="">All Priority</option>
                                    <option value="NORMAL">Normal</option>
                                    <option value="URGENT">Urgent</option>
                                    <option value="CRITICAL">Critical</option>
                                </select>
                                <select class="filter-select" id="request-status-filter" onchange="handleHospitalRequestFilter()" style="min-width: 120px; height: 44px; padding: 0 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.9rem; background: white; cursor: pointer;">
                                    <option value="">All Status</option>
                                    <option value="Open">Open</option>
                                    <option value="Matched">Matched</option>
                                    <option value="Closed">Closed</option>
                                </select>
                                <div style="position: relative;">
                                    <button type="button" id="request-date-range-icon" title="Filter by Date Range" onclick="toggleRequestDateRangePicker()" style="background: white; border: 1px solid #e2e8f0; border-radius: 8px; width: 44px; height: 44px; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #64748b; transition: all 0.2s;">
                                        <i class="fa-solid fa-calendar-days"></i>
                                    </button>
                                    <div id="request-date-range-picker" style="display: none; position: absolute; top: 52px; right: 0; background: white; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.25rem; box-shadow: 0 10px 25px rgba(0,0,0,0.1); z-index: 100; width: 280px;">
                                        <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                                            <h5 style="margin: 0; font-size: 0.85rem; color: #1e293b; font-weight: 700;">Filter by Range</h5>
                                            <div>
                                                <label style="display: block; font-size: 0.7rem; color: #94a3b8; text-transform: uppercase; font-weight: 700; margin-bottom: 4px;">From</label>
                                                <input type="date" id="request-date-from" onchange="handleHospitalRequestFilter()" style="width: 100%; padding: 0.5rem; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 0.85rem;">
                                            </div>
                                            <div>
                                                <label style="display: block; font-size: 0.7rem; color: #94a3b8; text-transform: uppercase; font-weight: 700; margin-bottom: 4px;">To</label>
                                                <input type="date" id="request-date-to" onchange="handleHospitalRequestFilter()" style="width: 100%; padding: 0.5rem; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 0.85rem;">
                                            </div>
                                            <button onclick="resetRequestDateRange()" style="margin-top: 0.5rem; background: #fee2e2; border: none; padding: 0.6rem; border-radius: 6px; cursor: pointer; color: #991b1b; font-weight: 700; font-size: 0.75rem; transition: all 0.2s;" onmouseover="this.style.background='#fecaca'" onmouseout="this.style.background='#fee2e2'">
                                                <i class="fa-solid fa-rotate-left" style="margin-right: 4px;"></i> Reset Dates
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="data-table">
                            <div class="table-content" id="hospital-requests-table" style="width: 100%;">
                                <div class="table-row" style="font-weight: 600; color: #64748b; font-size:0.85rem; text-transform:uppercase; background: #f8fafc; display: grid; grid-template-columns: 1.5fr 1.5fr 1fr 1fr 120px; gap: 1rem; padding: 1rem 1.5rem; border-bottom: 1px solid #e5e7eb;">
                                    <div class="table-cell">Hospital Name</div>
                                    <div class="table-cell">Organ Requested</div>
                                    <div class="table-cell">Priority</div>
                                    <div class="table-cell">Requested Date</div>
                                    <div class="table-cell" style="text-align: center;">Status</div>
                                </div>
                                <!-- Data will be loaded via JS -->
                                <div id="requests-loader" style="text-align: center; padding: 3rem; color: #94a3b8;">
                                    <i class="fa-solid fa-spinner fa-spin" style="font-size: 2rem; margin-bottom: 1rem;"></i>
                                    <p>Loading hospital requests...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

<!-- Organ Details Modal -->
<div id="organModal" class="modal" style="display: none;">
    <div class="modal-content" style="margin: 5% auto; max-width: 650px; background: white; border-radius: 16px; box-shadow: 0 20px 50px rgba(0,0,0,0.15); border: none; overflow: hidden;">
        <div class="modal-header" style="padding: 1.5rem 2rem; background: linear-gradient(135deg, #003b6e 0%, #1e56a0 100%); color: white; display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="background: rgba(255,255,255,0.2); padding: 8px; border-radius: 10px;"><i class="fa-solid fa-briefcase-medical"></i></div>
                <h3 style="margin: 0; font-size: 1.25rem; font-weight: 600;">Pledge Details <span id="modal-organ-pledge-id" style="opacity: 0.7; font-size: 0.9rem; margin-left: 8px;">#0</span></h3>
            </div>
            <button class="modal-close" onclick="closeOrganModal()" style="background: none; border: none; color: white; font-size: 1.5rem; cursor: pointer; opacity: 0.8; transition: opacity 0.2s;" onmouseover="this.style.opacity=1" onmouseout="this.style.opacity=0.8">&times;</button>
        </div>
        <div class="modal-body" style="padding: 2rem; max-height: 70vh; overflow-y: auto;">
            <div class="tribute-details">
                <!-- Donor Info -->
                <div style="margin-bottom: 2rem;">
                    <h1 id="modal-donor-name" style="margin: 0 0 0.5rem 0; font-size: 1.5rem; color: #1e293b; line-height: 1.3;">-</h1>
                    <div style="display: flex; gap: 1.5rem; color: #64748b; font-size: 0.95rem;">
                        <span><i class="fa-solid fa-id-card" style="margin-right: 6px;"></i>Donor ID: <span id="modal-donor-id" style="font-weight: 600;">-</span></span>
                        <span><i class="fa-solid fa-droplet" style="margin-right: 6px; color: #ef4444;"></i>Blood Type: <span id="modal-blood-type" style="font-weight: 600;">-</span></span>
                    </div>
                </div>

                <!-- Pledge Info -->
                <div style="background: #f8fafc; padding: 1.5rem; border-radius: 12px; margin-bottom: 2rem; border: 1px solid #f1f5f9; display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin-bottom: 0.5rem; letter-spacing: 0.5px;">Organ Pledged</label>
                        <div style="display: flex; align-items: center; gap: 10px;">
                             <div style="width: 32px; height: 32px; background: #dbeafe; color: #1e40af; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1rem;"><i class="fa-solid fa-heart-pulse"></i></div>
                             <span id="modal-organ-type" style="font-size: 1.1rem; font-weight: 600; color: #1e293b;">-</span>
                        </div>
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin-bottom: 0.5rem; letter-spacing: 0.5px;">Pledge Date</label>
                        <div style="display: flex; align-items: center; gap: 10px;">
                             <div style="width: 32px; height: 32px; background: #fef9c3; color: #854d0e; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1rem;"><i class="fa-solid fa-calendar-check"></i></div>
                             <span id="modal-reg-date" style="font-size: 1.1rem; font-weight: 600; color: #1e293b;">-</span>
                        </div>
                    </div>
                </div>

                <!-- Status Select -->
                <div style="border-top: 1px solid #f1f5f9; padding-top: 2rem;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 1rem;">
                        <div>
                            <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #1e293b; margin-bottom: 0.5rem;">Update Pledge Status</label>
                            <span id="modal-status-badge" class="status-badge" style="display: inline-block; padding: 0.35rem 0.75rem; border-radius: 50px; font-size: 0.75rem; font-weight: 600;">-</span>
                        </div>
                    </div>
                    <div style="display: flex; gap: 12px;">
                        <select id="modal-status-select" style="flex: 1; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.95rem; background: #fff; cursor: pointer;">
                            <option value="Pending">Pending</option>
                            <option value="Approved">Approved</option>
                            <option value="Completed">Completed</option>
                            <option value="Rejected">Rejected</option>
                        </select>
                        <button onclick="updateOrganStatus()" class="btn btn-primary" style="padding: 0 1.5rem; font-weight: 600; border-radius: 8px;">
                            <i class="fa-solid fa-save" style="margin-right: 6px;"></i> Update
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer" style="padding: 1.25rem 2rem; background: #f8fafc; border-top: 1px solid #f1f5f9; display: flex; justify-content: flex-end; align-items: center;">
            <button onclick="closeOrganModal()" class="btn btn-secondary" style="padding: 0.6rem 1.25rem; font-weight: 600;">Close</button>
        </div>
    </div>
</div>

<!-- Matching Details Modal -->
<div id="matchingModal" class="modal">
    <div class="modal-content" style="max-width: 700px;">
        <div class="modal-header">
            <h3>Matching Details</h3>
            <button class="modal-close" onclick="closeMatchingModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div class="payment-details">
                <div class="detail-row">
                    <div class="detail-label">Donor ID</div>
                    <div class="detail-value" id="modal-matching-donor-id">-</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Donor Name</div>
                    <div class="detail-value" id="modal-matching-donor-name">-</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Blood Type</div>
                    <div class="detail-value" id="modal-matching-blood-type">-</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Organ Request ID</div>
                    <div class="detail-value" id="modal-organ-request-id">-</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Hospital Name</div>
                    <div class="detail-value" id="modal-hospital-name">-</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Hospital Location</div>
                    <div class="detail-value" id="modal-hospital-location">-</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Hospital Contact</div>
                    <div class="detail-value" id="modal-hospital-contact">-</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Match Date</div>
                    <div class="detail-value" id="modal-match-date">-</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Status</div>
                    <div class="detail-value">
                        <!-- Changed from dropdown to read-only status badge -->
                        <span class="status-badge" id="modal-matching-status-display">-</span>
                    </div>
                </div>
            </div>
            
            <div class="modal-actions">
                <button class="btn btn-secondary" onclick="closeMatchingModal()">
                    <i class="fa-solid fa-times"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>

              <!-- Success Stories Section -->
<div id="tributes" class="content-section" style="display: none;">
    <div class="content-header">
        <h2>Success Stories Management</h2>
    </div>
    <div class="content-body">
        <!-- Search Bar + Status Filter -->
        <div style="display: flex; gap: 12px; align-items: center; margin-bottom: 16px;">
            <div class="search-bar" style="margin-bottom: 0; flex: 1;">
                <span class="search-icon"><i class="fa-solid fa-magnifying-glass"></i></span>
                <input type="text" class="search-input" placeholder="Search stories by title or status..." id="tribute-search">
            </div>
            <select class="filter-select" id="tribute-status-filter" onchange="filterTributes()" style="min-width: 160px; height: 44px;">
                <option value="">All Status</option>
                <option value="Pending" selected>Pending</option>
                <option value="Approved">Approved</option>
                <option value="Published">Published</option>
            </select>
        </div>

        <!-- Success Stories Table -->
        <div class="data-table" style="width: 100%;">
            <div class="table-header" style="display: flex; justify-content: space-between; align-items: center; padding: 1rem 1.5rem; background: #fff; border-bottom: 2px solid #f3f4f6;">
                <h4 style="margin: 0; font-size: 1.1rem; color: #1e293b;">Success Stories</h4>
                <button onclick="showAddStoryModal()" title="Add New Story"
                    style="background: #16a34a; color: #fff; border: none; border-radius: 6px; padding: 0.5rem 1rem; font-size: 0.85rem; display: flex; align-items: center; gap: 8px; cursor: pointer; transition: all 0.2s; font-weight: 600; box-shadow: 0 1px 2px rgba(0,0,0,0.05);"
                    onmouseover="this.style.background='#15803d'; this.style.transform='translateY(-1px)';" 
                    onmouseout="this.style.background='#16a34a'; this.style.transform='translateY(0)';"
                    active="this.style.transform='translateY(0)';" >
                    <i class="fa-solid fa-plus" style="font-size: 0.8rem;"></i> Add Story
                </button>
            </div>
                <div class="table-content" id="tributes-table" style="width: 100%;">
                    <div class="table-row" style="font-weight: 600; color: #64748b; font-size:0.85rem; text-transform:uppercase; background: #f8fafc; display: grid; grid-template-columns: 1.5fr 2.5fr 1.2fr 130px; gap: 1rem; padding: 1rem 1.5rem; border-bottom: 1px solid #e5e7eb;">
                        <div class="table-cell">Title</div>
                        <div class="table-cell">Description</div>
                        <div class="table-cell">Date</div>
                        <div class="table-cell" style="text-align: center;">Status</div>
                    </div>
                    <!-- Data will be loaded via JS -->
                </div> <!-- Closes tributes-table -->
            </div> <!-- Closes data-table -->
        </div> <!-- Closes content-body -->
    </div> <!-- Closes tributes section -->
</div> <!-- Closes content-area -->




   
<!-- Success Story Details Modal -->
<div id="tributeModal" class="modal" style="display: none;">
    <div class="modal-content" style="margin: 5% auto; max-width: 650px; background: white; border-radius: 16px; box-shadow: 0 20px 50px rgba(0,0,0,0.15); border: none; overflow: hidden;">
        <div class="modal-header" style="padding: 1.5rem 2rem; background: linear-gradient(135deg, #003b6e 0%, #1e56a0 100%); color: white; display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="background: rgba(255,255,255,0.2); padding: 8px; border-radius: 10px;"><i class="fa-solid fa-heart"></i></div>
                <h3 style="margin: 0; font-size: 1.25rem; font-weight: 600;">Success Story Details <span id="modal-story-id" style="opacity: 0.7; font-size: 0.9rem; margin-left: 8px;">#0</span></h3>
            </div>
            <button class="modal-close" onclick="closeTributeModal()" style="background: none; border: none; color: white; font-size: 1.5rem; cursor: pointer; opacity: 0.8; transition: opacity 0.2s;" onmouseover="this.style.opacity=1" onmouseout="this.style.opacity=0.8">&times;</button>
        </div>
        <div class="modal-body" style="padding: 2rem; max-height: 70vh; overflow-y: auto;">
            <div class="tribute-details">
                <!-- Main Info -->
                <div style="margin-bottom: 2rem;">
                    <h1 id="modal-title" style="margin: 0 0 0.5rem 0; font-size: 1.5rem; color: #1e293b; line-height: 1.3;">-</h1>
                    <div style="display: flex; gap: 1rem; color: #64748b; font-size: 0.9rem;">
                        <span><i class="fa-solid fa-calendar-alt" style="margin-right: 6px;"></i><span id="modal-success-date">-</span></span>
                        <span><i class="fa-solid fa-hospital" style="margin-right: 6px;"></i><span id="modal-hospital-reg">-</span></span>
                    </div>
                </div>

                <!-- Description -->
                <div style="background: #f8fafc; padding: 1.5rem; border-radius: 12px; margin-bottom: 2rem; border: 1px solid #f1f5f9;">
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin-bottom: 0.75rem; letter-spacing: 0.5px;">Message Content</label>
                    <p id="modal-description" style="margin: 0; line-height: 1.6; color: #334155; white-space: pre-wrap; font-size: 1.05rem;">-</p>
                </div>

                <!-- Metadata Row -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 2.5rem; padding: 0 0.5rem;">
                    <div>
                        <label style="display: block; font-size: 0.7rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin-bottom: 0.25rem;">Current Status</label>
                        <span id="modal-status" class="status-badge status-pending" style="display: inline-block;">-</span>
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.7rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin-bottom: 0.25rem;">Submitted On</label>
                        <span id="modal-created-at" style="color: #475569; font-weight: 500;">-</span>
                    </div>
                </div>

                <!-- Status Update Section (Admin Actions) -->
                <div style="border-top: 1px solid #f1f5f9; padding-top: 2rem;">
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #1e293b; margin-bottom: 1rem;">Update Decision</label>
                    <div style="display: flex; gap: 12px;">
                        <select id="modal-status-update" style="flex: 1; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.95rem; background: #fff; cursor: pointer;">
                            <option value="Pending">Keep as Pending</option>
                            <option value="Approved">Approve Story</option>
                            <option value="Archived">Archive Story</option>
                        </select>
                        <button onclick="updateTributeStatusAction()" class="btn btn-primary" style="padding: 0 1.5rem; font-weight: 600; border-radius: 8px;">
                            <i class="fa-solid fa-check-circle" style="margin-right: 6px;"></i> Update
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer" style="padding: 1.25rem 2rem; background: #f8fafc; border-top: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
            <button onclick="deleteTribute()" class="btn-text-danger" style="background: none; border: none; color: #ef4444; font-weight: 600; cursor: pointer; font-size: 0.9rem; display: flex; align-items: center; gap: 6px;">
                <i class="fa-solid fa-trash-can"></i> Delete Permanently
            </button>
            <button onclick="closeTributeModal()" class="btn btn-secondary" style="padding: 0.6rem 1.25rem; font-weight: 600;">Close</button>
        </div>
    </div>
</div>

<!-- Add/Edit Story Modal -->
<div id="storyFormModal" class="modal" style="display: none;">
    <div class="modal-content" style="margin: 5% auto; max-width: 800px; background: white; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.3); position: relative;">
        <div class="modal-header" style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb; background: var(--primary-color); color: white; border-radius: 12px 12px 0 0;">
            <h3 id="story-form-title" style="margin: 0; font-size: 1.5rem;">Add New Success Story</h3>
            <button class="modal-close" onclick="closeStoryFormModal()" style="background: none; border: none; color: white; font-size: 1.5rem; cursor: pointer; position: absolute; right: 1.5rem; top: 1.5rem;">&times;</button>
        </div>
        <div class="modal-body" style="padding: 1.5rem; max-height: 80vh; overflow-y: auto;">
            <form id="storyForm">
                <input type="hidden" id="form-story-id" name="story_id" value="">
                
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label for="form-title" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #374151;">Title *</label>
                    <input type="text" id="form-title" name="title" class="form-control" required style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 1rem;">
                </div>
                
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label for="form-description" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #374151;">Description *</label>
                    <textarea id="form-description" name="description" class="form-control" rows="6" required style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 1rem; resize: vertical;"></textarea>
                </div>
                
                <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                    <div class="form-group">
                        <label for="form-success-date" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #374151;">Success Date *</label>
                        <input type="date" id="form-success-date" name="success_date" class="form-control" required style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 1rem;">
                    </div>
                    
                    <div class="form-group">
                        <label for="form-hospital-reg" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #374151;">Hospital Registration No</label>
                        <select id="form-hospital-reg" name="hospital_registration_no" class="form-control" style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 1rem;">
                            <option value="">Select Hospital</option>
                            <!-- Hospitals will be loaded dynamically -->
                        </select>
                    </div>
                </div>
                
                <div class="form-group" style="margin-bottom: 2rem;">
                    <label for="form-status" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #374151;">Status *</label>
                    <select id="form-status" name="status" class="form-control" required style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 1rem;">
                        <option value="Pending">Pending</option>
                        <option value="Approved">Approved</option>
                    </select>
                </div>
                
                <div class="modal-actions" style="display: flex; gap: 1rem; justify-content: flex-end; padding-top: 1rem; border-top: 1px solid #e5e7eb;">
                    <button type="button" class="btn btn-secondary" onclick="closeStoryFormModal()" style="padding: 0.75rem 1.5rem;">
                        <i class="fa-solid fa-times"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary" style="padding: 0.75rem 1.5rem;">
                        <i class="fa-solid fa-save"></i> Save Story
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>