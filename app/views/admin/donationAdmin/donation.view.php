<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/life-connect/public/assets/css/style.css">
    <link rel="stylesheet" href="/life-connect/public/assets/css/admin/style.css">
    <link rel="stylesheet" href="/life-connect/public/assets/css/admin/donation-style.css">
    <link rel="stylesheet" href="/life-connect/public/assets/css/fontawesome.min.css?v=<?= time() ?>">
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
        grid-template-columns: 1.5fr 1.5fr 2.5fr 1fr 110px;
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

    /* Standard Modal Styles (Matching User Management) */
    .modal {
        background: rgba(15, 23, 42, 0.45);
        backdrop-filter: blur(12px);
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 2000;
        align-items: center;
        justify-content: center;
    }
    .modal.show {
        display: flex;
    }
    .modal-content {
        background: #ffffff !important;
        border-radius: 24px !important;
        padding: 0 !important;
        max-width: 680px !important;
        border: none !important;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25) !important;
        overflow: hidden !important;
    }
    .modal-scroll-area {
        padding: 2.5rem !important;
        max-height: 85vh;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }
    .modal-scroll-area::-webkit-scrollbar {
        width: 6px;
    }
    .modal-scroll-area::-webkit-scrollbar-track {
        background: transparent;
    }
    .modal-scroll-area::-webkit-scrollbar-thumb {
        background: #e2e8f0;
        border-radius: 10px;
    }
    
    .summary-card {
        background: #f0f7ff;
        border-radius: 16px;
        padding: 1.5rem;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem;
        border: 1px solid #e0f0ff;
    }

    .data-label {
        display: block;
        font-size: 0.65rem;
        font-weight: 800;
        color: #3b82f6;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 2px;
    }
    .data-value {
        font-size: 1rem;
        font-weight: 700;
        color: #1e293b;
        line-height: 1.4;
    }
    .data-value-sub {
        font-size: 0.8rem;
        color: #64748b;
        font-weight: 500;
    }

    .section-title {
        font-size: 0.7rem;
        font-weight: 800;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }

    .document-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 0.6rem 1rem;
        background: #f1f5f9;
        color: #475569;
        border-radius: 8px;
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 700;
        transition: all 0.2s;
        border: 1px solid #e2e8f0;
    }
    .document-link:hover {
        background: #e2e8f0;
        color: #1e293b;
    }
    .status-icon-box {
        flex-shrink: 0;
        width: 48px;
        height: 48px;
        background: #fee2e2;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }
    .status-icon-box i {
        font-size: 20px;
        color: #dc2626;
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
                    <div class="sidebar-user-avatar">S</div>
                    <div class="sidebar-user-info">
                        <span class="sidebar-user-name">Sahasna</span>
                        <span class="sidebar-user-id">ID-00004</span>
                        <span class="sidebar-user-role">Donation Admin</span>
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
                        <p>Monitor and audit all pledged biological contributions, ensuring accurate categorization for medical review.</p>
                    </div>
                        <div style="display: flex; gap: 12px; align-items: center; margin-top: 32px; margin-bottom: 32px; justify-content: space-between; padding: 0 4px;">
                            <div class="search-bar" style="margin-bottom: 0; flex: 1; max-width: 400px;">
                                <span class="search-icon"><i class="fa-solid fa-magnifying-glass"></i></span>
                                <input type="text" class="search-input" placeholder="Search donors, organs..." id="organ-search" oninput="handleOrganFilter()">
                            </div>
                            <div style="display: flex; gap: 10px; align-items: center;">
                                <select class="filter-select" id="organ-type-filter" onchange="handleOrganFilter()" style="min-width: 140px; height: 44px; padding: 0 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.9rem; background: white; cursor: pointer;">
                                    <option value="">All Organs</option>
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
                    <div class="content-header">
                        <h2>Donor-Recipient Matching</h2>
                        <p>Analyze medical compatibility and oversee the coordination of organ transfers between donors and hospitals.</p>
                    </div>
                    <div class="content-body">
                        <!-- Matching Engine Container -->
                        <div id="matching-dashboard-container" style="width: 100%;">
                            <?php include 'matches.view.php'; ?>
                        </div>
                    </div>
                </div>

                <!-- Hospital Requests Section -->
                <div id="hospital-requests" class="content-section" style="display: none;">
                    <div class="content-header">
                        <h2>Hospital Organ Requests</h2>
                        <p>Evaluate and prioritize medical demands from various healthcare facilities to facilitate timely organ delivery.</p>
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
                                </select>
                                <select class="filter-select" id="request-priority-filter" onchange="handleHospitalRequestFilter()" style="min-width: 120px; height: 44px; padding: 0 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.9rem; background: white; cursor: pointer;">
                                    <option value="">All Priority</option>
                                </select>
                                <select class="filter-select" id="request-status-filter" onchange="handleHospitalRequestFilter()" style="min-width: 120px; height: 44px; padding: 0 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.9rem; background: white; cursor: pointer;">
                                    <option value="">All Status</option>
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
<div id="organModal" class="modal">
    <div class="modal-content">
        <div class="modal-scroll-area">
            <!-- Modal Header -->
            <div style="display: flex; flex-direction: column; gap: 1.25rem; position: relative;">
                <button class="modal-close" 
                        style="position: absolute; top: -15px; right: -15px; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 50%; background: #f1f5f9; border: none; cursor: pointer; color: #64748b; z-index: 10;" 
                        onclick="closeOrganModal()">&times;</button>
                
                <div style="display: flex; align-items: center; gap: 1.25rem;">
                    <div id="modal-status-icon-box" class="status-icon-box">
                        <i id="modal-status-icon" class="fa-solid fa-circle-check"></i>
                    </div>
                    <div>
                        <h2 style="margin: 0; font-size: 1.5rem; font-weight: 800; color: #0f172a; line-height: 1.2;">
                            Pledge Details</h2>
                    </div>
                </div>
                <p style="margin: 0; color: #64748b; font-size: 0.9rem; line-height: 1.5; font-weight: 500;">
                    Review the medical history and logistical preferences for this organ pledge. Verify documentation before updating status.
                </p>

                <!-- Core Details Card -->
                <div class="summary-card">
                    <div>
                        <span class="data-label">Donor Name</span>
                        <div id="modal-donor-name" class="data-value">-</div>
                        <div id="modal-donor-id" class="data-value-sub">-</div>
                    </div>
                    <div>
                        <span class="data-label">Blood Type</span>
                        <div id="modal-blood-type" class="data-value" style="color: #ef4444;">-</div>
                        <div id="modal-reg-date" class="data-value-sub">-</div>
                    </div>
                    <div>
                        <span class="data-label">Organ Pledged</span>
                        <div id="modal-organ-type" class="data-value" style="color: #3b82f6;">-</div>
                        <div id="modal-organ-pledge-id" class="data-value-sub">#0</div>
                    </div>
                    <div>
                        <span class="data-label">Current Status</span>
                        <div id="modal-status-text" class="data-value">-</div>
                    </div>
                </div>

                <!-- Medical History Section -->
                <div class="section-title">
                    <i class="fa-solid fa-file-medical"></i> Medical History
                </div>
                <div class="grid-2" style="gap: 1.5rem 2rem;">
                    <div style="grid-column: span 2;">
                        <span class="data-label">Pre-existing Conditions</span>
                        <div id="modal-conditions" class="data-value" style="font-weight: 600; font-style: italic;">-</div>
                    </div>
                    <div>
                        <span class="data-label">Current Medications</span>
                        <div id="modal-medications" class="data-value" style="font-weight: 600;">-</div>
                    </div>
                    <div>
                        <span class="data-label">Known Allergies</span>
                        <div id="modal-allergies" class="data-value" style="color: #ef4444; font-weight: 600;">-</div>
                    </div>
                </div>

                <!-- Witnesses Section -->
                <div id="modal-witness-section" style="display: none;">
                    <div class="section-title">
                        <i class="fa-solid fa-users"></i> Registered Witnesses
                    </div>
                    <div id="modal-witness-list" style="display: flex; flex-direction: column; gap: 10px; margin-bottom: 1.5rem;">
                        <!-- Witnesses will be injected here -->
                    </div>
                </div>

                <!-- Living Donor Consent Details -->
                <div id="modal-living-consent-section" style="display: none;">
                    <div class="section-title">
                        <i class="fa-solid fa-file-signature"></i> Living Donor Consent Details
                    </div>
                    <div class="summary-card" style="background: #fdf2f2; border-color: #fee2e2; margin-bottom: 1.5rem;">
                        <div>
                            <span class="data-label" style="color: #ef4444;">Physical Stats</span>
                            <div class="data-value"><span id="modal-height">-</span> cm / <span id="modal-weight">-</span> kg</div>
                        </div>
                        <div>
                            <span class="data-label" style="color: #ef4444;">Medical Clearance</span>
                            <div id="modal-clearance-status" class="data-value">-</div>
                        </div>
                        <div>
                             <span class="data-label" style="color: #ef4444;">Recipient Relationship</span>
                             <div id="modal-recipient-known" class="data-value">-</div>
                        </div>
                         <div>
                             <span class="data-label" style="color: #ef4444;">Smoking/Alcohol</span>
                             <div id="modal-smoking-alcohol" class="data-value">-</div>
                        </div>
                        <div style="grid-column: span 2; margin-top: 10px; padding-top: 10px; border-top: 1px solid #fee2e2;">
                            <span class="data-label" style="color: #ef4444;">Emergency Contact</span>
                            <div class="data-value" id="modal-emergency-name">-</div>
                            <div class="data-value-sub" id="modal-emergency-phone">-</div>
                        </div>
                    </div>
                </div>

                <!-- After Death Consent Details -->
                <div id="modal-deceased-consent-section" style="display: none;">
                    <div class="section-title">
                        <i class="fa-solid fa-book-medical"></i> After Death Consent Details
                    </div>
                    <div class="summary-card" style="background: #f8fafc; border-color: #e2e8f0; margin-bottom: 1.5rem;">
                        <div>
                            <span class="data-label">Suitability (Any)</span>
                            <div id="modal-suitability" class="data-value">-</div>
                        </div>
                        <div>
                            <span class="data-label">Restricted</span>
                            <div id="modal-restricted" class="data-value">-</div>
                        </div>
                        <div style="grid-column: span 2;">
                            <span class="data-label">Special Instructions</span>
                            <div id="modal-instructions" class="data-value" style="font-size: 0.9rem; font-style: italic;">-</div>
                        </div>
                    </div>
                </div>

                <!-- Body Donation Consent Details -->
                <div id="modal-body-consent-section" style="display: none;">
                    <div class="section-title">
                        <i class="fa-solid fa-graduation-cap"></i> Body Donation Details
                    </div>
                    <div class="summary-card" style="background: #f0fdf4; border-color: #dcfce7; margin-bottom: 1.5rem;">
                         <div>
                            <span class="data-label" style="color: #16a34a;">Medical School ID</span>
                            <div id="modal-school-id" class="data-value">-</div>
                        </div>
                        <div>
                            <span class="data-label" style="color: #16a34a;">Responsible Person</span>
                            <div id="modal-resp-person" class="data-value">-</div>
                            <div id="modal-resp-contact" class="data-value-sub">-</div>
                        </div>
                        <div style="grid-column: span 2;">
                             <span class="data-label" style="color: #16a34a;">Transport Arrangement</span>
                             <div id="modal-transport" class="data-value">-</div>
                        </div>
                    </div>
                </div>

                <!-- Preferences & Logistics -->
                <div class="section-title">
                    <i class="fa-solid fa-hospital"></i> Preferences & Logistics
                </div>
                <div style="margin-bottom: 1.5rem;">
                    <span class="data-label">Preferred Hospital for Retrieval</span>
                    <div id="modal-preferred-hospital" class="data-value">-</div>
                </div>

                <!-- Documentation -->
                <div id="modal-docs-section">
                    <div class="section-title">
                        <i class="fa-solid fa-paperclip"></i> Verification Documents
                    </div>
                    <div id="modal-form-container" style="display: flex; align-items: center; justify-content: space-between; background: #fff; padding: 1rem; border-radius: 12px; border: 1px dashed #cbd5e1;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <i class="fa-solid fa-file-pdf" style="font-size: 1.5rem; color: #ef4444;"></i>
                            <div>
                                <div style="font-size: 0.9rem; font-weight: 700; color: #1e293b;">Signed Pledge Form</div>
                                <div style="font-size: 0.75rem; color: #64748b;">Official consent document</div>
                            </div>
                        </div>
                        <a id="modal-form-link" href="#" target="_blank" class="document-link">
                            <i class="fa-solid fa-arrow-up-right-from-square"></i> View Document
                        </a>
                    </div>
                    <div id="modal-no-docs" style="display: none; text-align: center; padding: 20px; color: #94a3b8; font-style: italic; font-size: 0.9rem;">
                        No documents uploaded for this pledge.
                    </div>
                </div>

                <!-- Administrative Actions -->
                <div id="modal-admin-actions-section" style="margin-top: 1rem; padding-top: 2rem; border-top: 2px solid #f1f5f9; display: flex; flex-direction: column; gap: 1.25rem;">
                    <div style="background: #fffcf0; border-left: 4px solid #fbbf24; padding: 1.25rem; border-radius: 12px;">
                        <span style="display: block; font-size: 0.7rem; font-weight: 800; color: #92400e; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 1rem;">Administrative Review</span>
                        
                        <!-- Main Status Selection Controls -->
                        <div id="modal-status-controls" style="display: flex; gap: 12px; align-items: flex-end;">
                            <div style="flex: 1;">
                                <label class="data-label">Update Status</label>
                                <select id="modal-status-select" 
                                        style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 0.95rem; background: #fff; cursor: pointer; font-weight: 600;">
                                    <option value="PENDING">Pending Review</option>
                                    <option value="UPLOADED">Uploaded</option>
                                    <option value="APPROVED">Approve Pledge</option>
                                    <option value="COMPLETED">Mark as Completed</option>
                                    <option value="REJECTED">Reject Pledge</option>
                                    <option value="SUSPENDED">Suspend Pledge</option>
                                </select>
                            </div>
                            <button onclick="handleStatusUpdateTrigger()" class="btn btn-primary" style="height: 48px; border-radius: 10px; padding: 0 1.5rem; font-weight: 700;">Update Status</button>
                        </div>

                        <!-- Confirmation Area (Hidden by default) -->
                        <div id="modal-status-confirmation" style="display: none; margin-top: 1rem; padding-top: 1rem; border-top: 1px dashed #fbbf24;">
                            <div style="display: flex; align-items: flex-start; gap: 12px; margin-bottom: 1rem;">
                                <div style="width: 32px; height: 32px; background: #fef3c7; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <i class="fa-solid fa-triangle-exclamation" style="color: #d97706; font-size: 0.9rem;"></i>
                                </div>
                                <div>
                                    <div style="font-weight: 700; color: #92400e; font-size: 0.9rem;">Confirm Status Change</div>
                                    <div style="font-size: 0.8rem; color: #b45309; line-height: 1.4;">Are you sure you want to update the status to <span id="modal-confirm-status-text" style="font-weight: 800; text-decoration: underline;">APPROVED</span>? This action is permanent and cannot be reversed.</div>
                                </div>
                            </div>
                            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                                <button onclick="cancelStatusUpdate()" class="btn btn-secondary" style="height: 38px; padding: 0 1.25rem; font-size: 0.85rem; border-radius: 8px;">No, Cancel</button>
                                <button id="btn-confirm-status" onclick="confirmStatusUpdate()" class="btn btn-primary" style="height: 38px; padding: 0 1.25rem; font-size: 0.85rem; border-radius: 8px; background: #059669; border-color: #059669;">Yes, Update Status</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div style="display: flex; justify-content: flex-end; margin-top: 1rem;">
                    <button onclick="closeOrganModal()" class="btn btn-secondary" style="border-radius: 10px; padding: 0.75rem 2rem; font-weight: 700;">Close Details</button>
                </div>
            </div>
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
        <p>Review and showcase inspiring donor-recipient journeys to celebrate the gift of life and promote community awareness.</p>
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
                <option value="Pending">Pending</option>
                <option value="Approved">Approved</option>
                <option value="Published">Published</option>
            </select>
        </div>

        <!-- Success Stories Table -->
        <div class="data-table" style="width: 100%;">
            <div class="table-header" style="display: flex; justify-content: space-between; align-items: center; padding: 1rem 1.5rem; background: #fff; border-bottom: 2px solid #f3f4f6;">
                <h4 style="margin: 0; font-size: 1.1rem; color: #1e293b;">Success Stories</h4>
            </div>
                <div class="table-content" id="tributes-table" style="width: 100%;">
                    <div class="table-row" style="font-weight: 600; color: #64748b; font-size:0.85rem; text-transform:uppercase; background: #f8fafc; display: grid; grid-template-columns: 1.5fr 1.5fr 2.5fr 1fr 110px; gap: 1rem; padding: 1rem 1.5rem; border-bottom: 1px solid #e5e7eb;">
                        <div class="table-cell">Title</div>
                        <div class="table-cell">Submitted By</div>
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
<div id="tributeModal" class="modal">
    <div class="modal-content">
        <div class="modal-scroll-area">
            <!-- Modal Header -->
            <div style="display: flex; flex-direction: column; gap: 1.25rem; position: relative;">
                <button class="modal-close" 
                        style="position: absolute; top: -15px; right: -15px; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 50%; background: #f1f5f9; border: none; cursor: pointer; color: #64748b; z-index: 10;" 
                        onclick="closeTributeModal()">&times;</button>
                
                <div style="display: flex; align-items: center; gap: 1.25rem;">
                    <div class="status-icon-box" style="background: #eff6ff; color: #3b82f6;">
                        <i class="fa-solid fa-heart-pulse"></i>
                    </div>
                    <div>
                        <h2 style="margin: 0; font-size: 1.5rem; font-weight: 800; color: #0f172a; line-height: 1.2;">
                            Success Story Details</h2>
                    </div>
                </div>
                <p style="margin: 0; color: #64748b; font-size: 0.9rem; line-height: 1.5; font-weight: 500;">
                    Review the inspirational journey and impact of this transplant success story.
                </p>

                <!-- Core Details Card -->
                <div class="summary-card">
                    <div>
                        <span class="data-label">Story Title</span>
                        <div id="modal-title" class="data-value">-</div>
                        <div id="modal-story-id" class="data-value-sub">#0</div>
                    </div>
                    <div>
                        <span class="data-label">Success Date</span>
                        <div id="modal-success-date" class="data-value" style="color: #3b82f6;">-</div>
                        <div id="modal-created-at" class="data-value-sub">-</div>
                    </div>
                    <div>
                        <span class="data-label">Submitted By</span>
                        <div id="modal-submitted-by" class="data-value" style="font-weight: 700; color: #0f172a;">-</div>
                        <div id="modal-user-role" class="data-value-sub" style="text-transform: capitalize;">-</div>
                    </div>
                    <div>
                        <span class="data-label">Current Status</span>
                        <div id="modal-status" class="data-value">-</div>
                    </div>
                </div>

                <!-- Impact & Metadata Card -->
                <div class="summary-card" style="background: #f8fafc; border-color: #e2e8f0;">
                    <div>
                        <span class="data-label">Story Type</span>
                        <div id="modal-story-type" class="data-value" style="font-size: 0.95rem;">-</div>
                    </div>
                    <div>
                        <span class="data-label">Donors Involved</span>
                        <div id="modal-donors-count" class="data-value" style="font-size: 0.95rem;">-</div>
                    </div>
                    <div>
                        <span class="data-label">Students Helped</span>
                        <div id="modal-students-helped" class="data-value" style="font-size: 0.95rem;">-</div>
                    </div>
                    <div>
                        <span class="data-label">Hospital / Author</span>
                        <div id="modal-hospital-reg" class="data-value" style="font-size: 0.95rem;">-</div>
                        <div id="modal-author-name" class="data-value-sub">-</div>
                    </div>
                </div>

                <!-- Story Content Section -->
                <div class="section-title">
                    <i class="fa-solid fa-quote-left"></i> Story Content
                </div>
                <div style="background: #f8fafc; padding: 2rem; border-radius: 16px; border: 1px solid #e2e8f0; line-height: 1.8; color: #334155; font-size: 1.05rem; white-space: pre-wrap; margin-bottom: 2rem;" id="modal-description">
                    -
                </div>

                <!-- Admin Action Section -->
                <div class="section-title">
                    <i class="fa-solid fa-gavel"></i> Administrative Decision
                </div>
                <div class="summary-card" style="background: #fff; border: 1px solid #e2e8f0; display: flex; align-items: center; gap: 1.5rem; padding: 1.5rem;">
                    <div style="flex: 1;">
                        <span class="data-label" style="margin-bottom: 0.5rem;">Update Story Status</span>
                        <select id="modal-status-update" style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 10px; font-weight: 600; color: #1e293b; background: #f8fafc;">
                            <option value="Pending">Pending</option>
                            <option value="Approved">Approve for Publication</option>
                            <option value="Archived">Archive Story</option>
                        </select>
                    </div>
                    <button onclick="updateTributeStatusAction()" class="btn btn-primary" style="height: 48px; padding: 0 2rem; border-radius: 10px; font-weight: 700; display: flex; align-items: center; gap: 8px; margin-top: 1.25rem;">
                        <i class="fa-solid fa-cloud-arrow-up"></i> Update Status
                    </button>
                </div>

                <!-- Footer Actions -->
                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1rem; padding-top: 2rem; border-top: 2px solid #f1f5f9;">
                    <button onclick="deleteTribute()" class="btn-text-danger" style="background: none; border: none; color: #ef4444; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 8px; font-size: 0.95rem;">
                        <i class="fa-solid fa-trash-can"></i> Delete Permanently
                    </button>
                    <button onclick="closeTributeModal()" class="btn btn-secondary" style="border-radius: 10px; padding: 0.75rem 2rem; font-weight: 700;">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Hospital Request Details Modal -->
<div id="hospitalRequestModal" class="modal">
    <div class="modal-content">
        <div class="modal-scroll-area">
            <!-- Modal Header -->
            <div style="display: flex; flex-direction: column; gap: 1.25rem; position: relative;">
                <button class="modal-close" 
                        style="position: absolute; top: -15px; right: -15px; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 50%; background: #f1f5f9; border: none; cursor: pointer; color: #64748b; z-index: 10;" 
                        onclick="closeHospitalRequestModal()">&times;</button>
                
                <div style="display: flex; align-items: center; gap: 1.25rem;">
                    <div id="request-modal-status-icon-box" class="status-icon-box">
                        <i id="request-modal-status-icon" class="fa-solid fa-hospital-user"></i>
                    </div>
                    <div>
                        <h2 style="margin: 0; font-size: 1.5rem; font-weight: 800; color: #0f172a; line-height: 1.2;">
                            Hospital Request Details</h2>
                    </div>
                </div>
                <p style="margin: 0; color: #64748b; font-size: 0.9rem; line-height: 1.5; font-weight: 500;">
                    Review the medical requirements and priority status for this facility's request.
                </p>

                <!-- Core Details Card -->
                <div class="summary-card">
                    <div>
                        <span class="data-label">Hospital Name</span>
                        <div id="request-modal-hospital-name" class="data-value">-</div>
                        <div id="request-modal-request-id" class="data-value-sub">#0</div>
                    </div>
                    <div>
                        <span class="data-label">Requested Organ</span>
                        <div id="request-modal-organ-name" class="data-value" style="color: #3b82f6;">-</div>
                        <div id="request-modal-created-at" class="data-value-sub">-</div>
                    </div>
                    <div>
                        <span class="data-label">Priority Level</span>
                        <div id="request-modal-priority" class="data-value" style="font-weight: 700;">-</div>
                    </div>
                    <div>
                        <span class="data-label">Current Status</span>
                        <div id="request-modal-status-text" class="data-value">-</div>
                    </div>
                </div>

                <!-- Recipient Information Section -->
                <div class="section-title">
                    <i class="fa-solid fa-user-injured"></i> Recipient Information
                </div>
                <div class="grid-2" style="gap: 1.5rem 2rem;">
                    <div>
                        <span class="data-label">Age</span>
                        <div id="request-modal-recipient-age" class="data-value">-</div>
                    </div>
                    <div>
                        <span class="data-label">Gender</span>
                        <div id="request-modal-recipient-gender" class="data-value">-</div>
                    </div>
                    <div>
                        <span class="data-label">Blood Group</span>
                        <div id="request-modal-recipient-blood" class="data-value" style="color: #ef4444; font-weight: 700;">-</div>
                    </div>
                </div>

                <!-- Medical Specifications (HLA Typing) -->
                <div class="section-title">
                    <i class="fa-solid fa-dna"></i> Medical Specifications (HLA Typing)
                </div>
                <div class="summary-card" style="background: #f8fafc; border-color: #e2e8f0; margin-bottom: 1.5rem;">
                    <div>
                        <span class="data-label">HLA-A</span>
                        <div class="data-value"><span id="request-modal-hla-a1">-</span> / <span id="request-modal-hla-a2">-</span></div>
                    </div>
                    <div>
                        <span class="data-label">HLA-B</span>
                        <div class="data-value"><span id="request-modal-hla-b1">-</span> / <span id="request-modal-hla-b2">-</span></div>
                    </div>
                    <div>
                        <span class="data-label">HLA-DR</span>
                        <div class="data-value"><span id="request-modal-hla-dr1">-</span> / <span id="request-modal-hla-dr2">-</span></div>
                    </div>
                </div>

                <!-- Request Context -->
                <div class="section-title">
                    <i class="fa-solid fa-notes-medical"></i> Clinical Context
                </div>
                <div style="margin-bottom: 1.5rem;">
                    <span class="data-label">Reason for Transplant</span>
                    <div id="request-modal-reason" class="data-value" style="line-height: 1.6;">-</div>
                </div>
                
                <div id="request-modal-edit-section" style="display: none; margin-bottom: 1.5rem;">
                    <span class="data-label">Edit History / Notes</span>
                    <div id="request-modal-edit-reason" class="data-value" style="font-size: 0.9rem; color: #64748b;">-</div>
                </div>

                <!-- Footer -->
                <div style="display: flex; justify-content: flex-end; margin-top: 1rem; padding-top: 2rem; border-top: 2px solid #f1f5f9;">
                    <button onclick="closeHospitalRequestModal()" class="btn btn-secondary" style="border-radius: 10px; padding: 0.75rem 2rem; font-weight: 700;">Close Details</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toast Notifications -->
<div id="toast" class="notification">
    <span id="toast-message"></span>
</div>

<!-- Success Story Deletion Confirmation (Custom Inline Modal) -->
<div id="delete-confirmation-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index: 3000; align-items: center; justify-content: center;">
    <div style="background: white; width: 400px; border-radius: 20px; padding: 2rem; text-align: center; box-shadow: 0 20px 50px rgba(0,0,0,0.2);">
        <div style="width: 60px; height: 60px; background: #fee2e2; color: #ef4444; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; font-size: 1.5rem;">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <h3 style="margin-bottom: 0.5rem; color: #0f172a; font-weight: 800;">Confirm Deletion</h3>
        <p style="color: #64748b; font-size: 0.95rem; line-height: 1.5; margin-bottom: 2rem;">Are you sure you want to permanently delete this success story? This action cannot be undone.</p>
        <div style="display: flex; gap: 1rem;">
            <button onclick="cancelDeleteTribute()" style="flex: 1; padding: 0.75rem; border: 1px solid #e2e8f0; background: white; border-radius: 12px; font-weight: 700; color: #64748b; cursor: pointer;">Cancel</button>
            <button onclick="confirmDeleteTribute()" style="flex: 1; padding: 0.75rem; background: #ef4444; color: white; border: none; border-radius: 12px; font-weight: 700; cursor: pointer;">Yes, Delete</button>
        </div>
    </div>
</div>

</body>
</html>