<?php
// Hospital dashboard view
// Data passed from controller: $hospital_name, $hospital_registration, $organ_requests, $recipients, $success_stories, $aftercare_appointments, $stats
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="<?php echo ROOT ?? '/life-connect'; ?>/public/assets/css/hospital/hospital.css">
    <style>
        /* Donor-style calendar (reused for Hospital Upcoming Appointments) */
        :root {
            --cal-green:  var(--success-color);
            --cal-blue:   var(--primary-color);
            --cal-yellow: var(--warning-color);
            --cal-red:    var(--danger-color);
        }

        .lab-tabs { display:flex; flex-wrap:wrap; gap:.45rem; }
        .lab-tab {
            border: 1px solid var(--border-color);
            background: var(--white-color);
            color: var(--primary-text-color);
            border-radius: 999px;
            padding: .38rem .75rem;
            font-size: .82rem;
            font-weight: 700;
            cursor: pointer;
            transition: .15s;
        }
        .lab-tab:hover { background: var(--gray-bg-color); }
        .lab-tab.active { background: var(--primary-color); border-color: var(--primary-color); color: var(--white-color); }

        .lab-tabs.vertical .lab-tab {
            border-radius: 10px;
            padding: .75rem 1rem;
            text-align: left;
            font-size: .95rem;
            width: 100%;
        }

        .cal-wrap { padding: 1rem; background: var(--white-color); border: 1px solid var(--border-color); border-radius: 14px; width: 320px; }
        .cal-nav { display:flex; justify-content:space-between; align-items:center; margin-bottom: 1rem; }
        .cal-nav h3 { margin:0; font-size: 1.15rem; font-weight: 800; color: var(--primary-text-color); }
        .cal-nav-btn {
            width: 40px; height: 40px; border-radius: 999px;
            border: 2px solid var(--primary-color);
            background: var(--white-color);
            color: var(--primary-color);
            font-weight: 900;
            cursor: pointer;
        }
        .cal-grid { display:grid; grid-template-columns:repeat(7, 1fr); gap: 6px; text-align:center; }
        .cal-day-hdr { font-size: .8rem; font-weight: 800; color: var(--secondary-text-color); padding: .35rem 0; }
        .cal-day { padding: .75rem .25rem; border-radius: 12px; font-weight: 800; font-size: 1.02rem;
                   cursor: default; position: relative; color: var(--primary-text-color); border: 2.5px solid transparent; }
        .cal-day.clickable { cursor: pointer; }
        .cal-day.clickable:hover { filter: brightness(.96); }
        .cal-day.is-today { border-color: var(--primary-color); }
        .cal-day.is-selected { outline: 2.5px solid var(--primary-color); outline-offset: 1px; }
        .cal-day::before { content:''; position:absolute; inset:0; border-radius: 12px; opacity: .18; z-index: 0; }
        .cal-day span { position: relative; z-index: 1; }

        .cal-day.apt-green { color: var(--cal-green); }
        .cal-day.apt-green::before { background: var(--cal-green); }
        .cal-day.apt-blue { color: var(--cal-blue); }
        .cal-day.apt-blue::before { background: var(--cal-blue); }
        .cal-day.apt-yellow { color: var(--cal-yellow); }
        .cal-day.apt-yellow::before { background: var(--cal-yellow); }
        .cal-day.apt-red { color: var(--cal-red); }
        .cal-day.apt-red::before { background: var(--cal-red); }
        .cal-day:not(.apt-green):not(.apt-blue):not(.apt-yellow):not(.apt-red)::before { background: transparent; }

        /* Calendar details (shown after clicking a date) */
        .cal-details { margin-top: .9rem; }
        .cal-details__title { font-weight: 900; font-size: .95rem; color: var(--primary-text-color); margin-bottom: .25rem; }
        .cal-details__hint {
            font-weight: 700;
            font-size: .86rem;
            color: var(--secondary-text-color);
            padding: .65rem;
            background: var(--gray-bg-color);
            border-radius: 12px;
            border: 1px dashed var(--border-color);
        }
        .cal-details-list { display:flex; flex-direction:column; gap: .55rem; }
        .cal-details-item {
            display:flex;
            justify-content: space-between;
            align-items: center;
            gap: .75rem;
            padding: .7rem .75rem;
            border: 1px solid var(--border-color);
            border-radius: 14px;
            background: var(--white-color);
        }
        .cal-details-left { min-width: 0; }
        .cal-details-test { font-weight: 900; font-size: .95rem; color: var(--primary-text-color); white-space: nowrap; overflow:hidden; text-overflow: ellipsis; }
        .cal-details-sub { font-weight: 700; font-size: .82rem; color: var(--secondary-text-color); margin-top: .12rem; }
    </style>
    <title>Hospital Management - LifeConnect</title>
</head>

<body>
    <div class="header">
        <div class="header-content">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <a href="<?php echo rtrim((ROOT ?? '/life-connect'), '/'); ?>/"
                    style="text-decoration: none; display: flex; align-items: center; gap: 10px;">
                    <img src="<?php echo ROOT ?? '/life-connect'; ?>/public/assets/images/logo.png" alt="LifeConnect"
                        style="height: 40px; width: auto;">
                    <div>
                        <strong
                            style="display:block; font-size:1.1rem; color:#003b6e; line-height:1.2;">LifeConnect</strong>
                        <p style="margin:0; font-size:.68rem; color:#6b7280; padding-top:2px;">Hospital Portal</p>
                    </div>
                </a>
            </div>
            <div class="header-right">
                <a class="nav-link" href="<?php echo rtrim((ROOT ?? '/life-connect'), '/'); ?>/" title="Home">
                    <i class="fa-solid fa-house"></i>
                    <span>Home</span>
                </a>

                <button class="notification-bell" type="button" title="Notifications">
                    <i class="fa-solid fa-bell"></i>
                </button>

                <div class="user-info" onclick="toggleUserDropdown()">
                    <div class="user-avatar"><?php echo strtoupper(substr($hospital_details['name'], 0, 1)); ?></div>
                    <div class="user-details">
                        <div style="font-weight: 600; font-size: 0.9rem;">
                            <?php echo htmlspecialchars($hospital_details['name']); ?>
                        </div>
                        <div style="font-size: 0.8rem; opacity: 0.8;">
                            <?php echo htmlspecialchars($hospital_details['role']); ?>
                        </div>
                        <div style="font-size: 0.7rem; opacity: 0.6;">ID:
                            <?php echo htmlspecialchars($hospital_details['registration']); ?>
                        </div>
                    </div>
                    <div class="user-actions">
                        <button class="btn-logout" onclick="logout()" title="Logout">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                <polyline points="16,17 21,12 16,7"></polyline>
                                <line x1="21" y1="12" x2="9" y2="12"></line>
                            </svg>
                        </button>
                    </div>

                    <!-- User Details Dropdown -->
                    <div class="user-dropdown" id="user-dropdown">
                        <div class="dropdown-header">
                            <div class="user-avatar-large">
                                <?php echo strtoupper(substr($hospital_details['name'], 0, 1)); ?>
                            </div>
                            <div>
                                <div class="user-name"><?php echo htmlspecialchars($hospital_details['name']); ?></div>
                                <div class="user-role"><?php echo htmlspecialchars($hospital_details['role']); ?></div>
                            </div>
                        </div>
                        <div class="dropdown-content">
                            <div class="detail-item">
                                <span class="detail-label">Hospital ID:</span>
                                <span
                                    class="detail-value"><?php echo htmlspecialchars($hospital_details['registration']); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Email:</span>
                                <span
                                    class="detail-value"><?php echo htmlspecialchars($hospital_details['email']); ?></span>
                            </div>
                            <?php
                            $displayAddress = $hospital_details['address'] ?? 'Not specified';
                            $displayPhone = $hospital_details['phone'] ?? 'Not specified';

                            // If address contains our special [Phone] marker, parse it
                            if ($displayAddress && strpos($displayAddress, '[Phone]:') !== false) {
                                $parts = explode(' | [Address]: ', $displayAddress);
                                $displayPhone = str_replace('[Phone]: ', '', $parts[0]);
                                $displayAddress = $parts[1] ?? 'Not specified';
                            }
                            ?>
                            <div class="detail-item">
                                <span class="detail-label">Address:</span>
                                <span class="detail-value"><?php echo htmlspecialchars($displayAddress); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Phone:</span>
                                <span class="detail-value"><?php echo htmlspecialchars($displayPhone); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Status:</span>
                                <span
                                    class="detail-value status-active"><?php echo htmlspecialchars($hospital_details['status']); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Last Login:</span>
                                <span
                                    class="detail-value"><?php echo date('M d, Y H:i', strtotime($hospital_details['last_login'])); ?></span>
                            </div>
                        </div>
                        <div class="dropdown-footer">
                            <button class="btn btn-secondary btn-small" onclick="editProfile()">Edit Profile</button>
                            <button class="btn btn-danger btn-small" onclick="logout()">Logout</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="container">
        <div class="main-content">
            <div class="sidebar">
                <div class="sidebar-header">
                    <h3>Hospital Portal </h3>
                    <p>Clinical coordination</p>
                </div>

                <div class="menu-section">
                    <div class="menu-section-title">SECTION</div>
                    <div class="menu-item active" onclick="showContent('overview', this)" style="text-align: left;">
                        <span class="icon"></span>
                        <span>Main Dashboard</span>
                    </div>
                    <div class="menu-item" onclick="showContent('organ-requests', this)" style="text-align: left;">
                        <span class="icon"></span>
                        <span>Organ Requests</span>
                    </div>
                    <div class="menu-item" onclick="showContent('eligibility', this)" style="text-align: left;">
                        <span class="icon"></span>
                        <span>Update Eligibility</span>
                    </div>
                    <div class="menu-item" onclick="showContent('recipients', this)" style="text-align: left;">
                        <span class="icon"></span>
                        <span>Recipient Patients</span>
                    </div>
                    <div class="menu-item" onclick="showContent('stories', this)" style="text-align: left;">
                        <span class="icon"></span>
                        <span>Success Stories</span>
                    </div>
                    <div class="menu-item" onclick="showContent('lab-reports', this)" style="text-align: left; white-space: nowrap;">
                        <span class="icon"></span>
                        <span>Upcoming Appointments</span>
                    </div>
                    <div class="menu-item" onclick="showContent('test-results', this)" style="text-align: left; white-space: nowrap;">
                        <span class="icon"></span>
                        <span>Test Results</span>
                    </div>
                    
                    <div class="menu-section-title" style="margin-top: 1.5rem;">AFTERCARE</div>
                    <a href="<?php echo ROOT; ?>/hospital/addpatient" class="menu-item" style="text-decoration: none; color: inherit; display: block; text-align: left;">
                        <span class="icon"></span>
                        <span>Add Aftercare Patient</span>
                    </a>
                </div>
            </div>

            <div class="content-area" id="content-area">
                <div id="overview" class="content-section" style="display: block;">
                    <div class="content-header">
                        <h2>Hospital Overview</h2>
                        <p>Monitor organ requests, donor eligibility, and recipient management.</p>
                    </div>
                    <div class="content-body">
                        <!-- DYNAMIC URGENT ALERTS BANNER -->
                        <?php if ($stats['pending_requests'] > 0): ?>
                        <div class="urgent-alert-banner"
                            style="background: linear-gradient(90deg, #fff3cd 0%, #fff8e1 100%); border-left: 4px solid #ffc107; padding: 1rem 1.5rem; border-radius: 8px; margin-bottom: 2rem; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 4px 15px rgba(255, 193, 7, 0.1);">
                            <div style="display: flex; align-items: center; gap: 1rem;">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#d39e00"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path
                                        d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z">
                                    </path>
                                    <line x1="12" y1="9" x2="12" y2="13"></line>
                                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                                </svg>
                                <div>
                                    <h4 style="margin: 0; color: #856404; font-size: 1rem;">[URGENT] <?php echo $stats['pending_requests']; ?> Perfect Match
                                        Pending Review</h4>
                                    <p style="margin: 0.25rem 0 0; color: #664d03; font-size: 0.9rem;">Urgent screening results are available for pending organ requests.</p>
                                </div>
                            </div>
                            <button onclick="showContent('eligibility')"
                                style="background: #ffc107; color: #000; border: none; padding: 0.5rem 1rem; border-radius: 6px; font-weight: 600; cursor: pointer; transition: all 0.2s; box-shadow: 0 2px 4px rgba(255,193,7,0.3);">Initiate
                                Transfer</button>
                        </div>
                        <?php endif; ?>
                        <div class="stats-grid">
                            <div class="stat-card">
                                <div class="stat-number"><?php echo $stats['total_organ_requests']; ?></div>
                                <div class="stat-label">Total Organ Requests</div>
                                <div class="stat-change neutral"><?php echo $stats['pending_requests']; ?> pending</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number"><?php echo $stats['total_recipients']; ?></div>
                                <div class="stat-label">Total Recipients</div>
                                <div class="stat-change positive"><?php echo $stats['active_recipients']; ?> active
                                </div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number"><?php echo $stats['total_success_stories']; ?></div>
                                <div class="stat-label">Success Stories</div>
                                <div class="stat-change positive"><?php echo $stats['approved_stories']; ?> approved
                                </div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number"><?php echo $stats['total_appointments']; ?></div>
                                <div class="stat-label">Aftercare Appointments</div>
                                <div class="stat-change positive"><?php echo $stats['scheduled_appointments']; ?>
                                    scheduled</div>
                            </div>
                        </div>


                        <div class="feature-grid">
                            <div class="feature-card" onclick="showContent('organ-requests')" style="cursor: pointer;">
                                <div class="feature-icon"
                                    style="display: flex; align-items: center; justify-content: center; width: 60px; height: 60px; background: rgba(0, 91, 170, 0.1); border-radius: 12px; margin-bottom: 1rem;">
                                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#005baa"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path
                                            d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                                        </path>
                                    </svg>
                                </div>
                                <h3>Organ Requests</h3>
                                <p>Create, edit, and manage urgent organ requests for patient matching.</p>
                            </div>

                            <div class="feature-card" onclick="showContent('eligibility')" style="cursor: pointer;">
                                <div class="feature-icon"
                                    style="display: flex; align-items: center; justify-content: center; width: 60px; height: 60px; background: rgba(0, 91, 170, 0.1); border-radius: 12px; margin-bottom: 1rem;">
                                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#005baa"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                    </svg>
                                </div>
                                <h3>Update Eligibility</h3>
                                <p>Approve or modify a donor's eligibility status after clinical evaluations.</p>
                            </div>

                            <div class="feature-card" onclick="showContent('recipients')" style="cursor: pointer;">
                                <div class="feature-icon"
                                    style="display: flex; align-items: center; justify-content: center; width: 60px; height: 60px; background: rgba(0, 91, 170, 0.1); border-radius: 12px; margin-bottom: 1rem;">
                                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#005baa"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="9" cy="7" r="4"></circle>
                                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                    </svg>
                                </div>
                                <h3>Recipient Patients</h3>
                                <p>Manage priority waitlists and view records of matched recipient patients.</p>
                            </div>

                            <div class="feature-card" onclick="showContent('stories')" style="cursor: pointer;">
                                <div class="feature-icon"
                                    style="display: flex; align-items: center; justify-content: center; width: 60px; height: 60px; background: rgba(0, 91, 170, 0.1); border-radius: 12px; margin-bottom: 1rem;">
                                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#005baa"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path
                                            d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z">
                                        </path>
                                    </svg>
                                </div>
                                <h3>Success Stories</h3>
                                <p>Approve or share impactful post-transplant recovery stories and tributes.</p>
                            </div>

                            <div class="feature-card" onclick="showContent('lab-reports')" style="cursor: pointer;">
                                <div class="feature-icon"
                                    style="display: flex; align-items: center; justify-content: center; width: 60px; height: 60px; background: rgba(0, 91, 170, 0.1); border-radius: 12px; margin-bottom: 1rem;">
                                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#005baa"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14 2 14 8 20 8"></polyline>
                                        <line x1="16" y1="13" x2="8" y2="13"></line>
                                        <line x1="16" y1="17" x2="8" y2="17"></line>
                                        <polyline points="10 9 9 9 8 9"></polyline>
                                    </svg>
                                </div>
                                <h3>Upcoming Appointments</h3>
                                <p>Upload and analyze biological screening and laboratory test documents.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="organ-requests" class="content-section" style="display: none;">
                    <div class="content-header">
                        <h2>Organ Requests Management</h2>
                        <p>Create, edit, and delete organ requests with urgency selection.</p>
                    </div>
                    <div class="content-body">
                        <div class="action-section">
                            <h3>Request Actions</h3>
                            <div class="action-buttons">
                                <button class="btn btn-primary" onclick="openRequestModal()">Add New Request</button>
                            </div>
                        </div>

                        <!-- Organ Request Options with Emojis -->
                        <div class="organ-request-options">
                            <h3 style="text-align: center; margin-bottom: 2rem; color: #2c3e50; font-size: 1.5rem;">
                                Organ Request Types</h3>
                            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; margin-top: 1rem;">
                                <?php
                                    $organsList = $organs ?? [];

                                    $iconForOrgan = function($organName) {
                                        $n = strtolower(trim((string)$organName));
                                        if ($n === 'kidney') {
                                            return '<img src="' . ROOT . '/public/assets/icons/kidneys.png" style="width: 48px; height: 48px; object-fit: contain;">';
                                        }
                                        if ($n === 'bone marrow') {
                                            return '<img src="' . ROOT . '/public/assets/icons/bone_marrow.png" style="width: 48px; height: 48px; object-fit: contain;">';
                                        }
                                        if ($n === 'part of liver') {
                                            return '<img src="' . ROOT . '/public/assets/icons/liver.png" style="width: 48px; height: 48px; object-fit: contain;">';
                                        }
                                        if ($n === 'cornea') {
                                            return '<i class="fas fa-eye" style="font-size: 40px; color: #3b82f6;"></i>';
                                        }
                                        if ($n === 'skin') {
                                            return '<i class="fas fa-bandage" style="font-size: 40px; color: #16a34a;"></i>';
                                        }
                                        if ($n === 'bones') {
                                            return '<i class="fas fa-bone" style="font-size: 40px; color: #64748b;"></i>';
                                        }
                                        if ($n === 'heart valves') {
                                            return '<img src="' . ROOT . '/public/assets/icons/heart.png" style="width: 48px; height: 48px; object-fit: contain;">';
                                        }
                                        if ($n === 'tendons') {
                                            return '🦵';
                                        }
                                        return '';
                                    };
                                ?>

                                <?php foreach ($organsList as $organ): ?>
                                    <div class="organ-option-card" onclick='selectOrganType(<?= (int)$organ->id ?>, <?= json_encode($organ->name, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)'
                                        style="cursor: pointer; transition: all 0.3s ease;">
                                        <div class="option-emoji"
                                            style="margin-bottom: 1rem; display: flex; justify-content: center; background: #f8fafc; padding: 15px; border-radius: 20px;">
                                            <?= $iconForOrgan($organ->name) ?>
                                        </div>
                                        <h4 style="margin: 0.5rem 0; color: #1f2937; font-weight: 600;">
                                            <?= htmlspecialchars($organ->name) ?>
                                        </h4>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="search-bar">
                            <input type="text" id="organ-search" class="search-input"
                                placeholder="Search by organ type or Urgency" onkeyup="applyOrganFilters()">
                        </div>

                        <div class="filter-section">
                            <select id="organ-type-filter" class="filter-select" onchange="applyOrganFilters()">
                                <option value="">All Organs</option>
                                <?php foreach (($organs ?? []) as $organ): ?>
                                    <option value="<?= (int)$organ->id ?>"><?= htmlspecialchars($organ->name) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <select id="urgency-filter" class="filter-select" onchange="applyOrganFilters()">
                                <option value="">All Urgency</option>
                                <option value="CRITICAL">Critical</option>
                                <option value="URGENT">Urgent</option>
                                <option value="NORMAL">Normal</option>
                            </select>
                        </div>

                        <div class="data-table">
                            <div class="table-header">
                                <h4>Organ Requests</h4>
                            </div>
                            <div class="table-content">
                                <div class="table-row" style="font-weight: 600; background: var(--gray-bg-color);">
                                    <div class="table-cell">Organ Type</div>
                                    <div class="table-cell">Urgency</div>
                                    <div class="table-cell">Created Date</div>
                                    <div class="table-cell">Status</div>
                                    <div class="table-cell">Actions</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div id="eligibility" class="content-section" style="display: none;">
                    <div class="content-header">
                        <h2>Update Donor Eligibility</h2>
                        <p>Update donor eligibility status after medical evaluations and screening.</p>
                    </div>
                    <div class="content-body">
                        <div class="search-bar">
                            <span class="search-icon">🔍</span>
                            <input type="text" class="search-input" placeholder="Search by donor NIC or name...">
                        </div>

                        <div class="data-table">
                            <div class="table-header">
                                <h4>Pending Eligibility Reviews</h4>
                            </div>
                            <div class="table-content">
                                <div class="table-row" style="font-weight: 600; background: var(--gray-bg-color);">
                                    <div class="table-cell">Donor Details</div>
                                    <div class="table-cell">Organ Type</div>
                                    <div class="table-cell">Test Date</div>
                                    <div class="table-cell">Current Status</div>
                                    <div class="table-cell">Actions</div>
                                </div>

                                <?php if (!empty($eligibility_pledges ?? [])): ?>
                                    <?php foreach (($eligibility_pledges ?? []) as $p): ?>
                                        <div class="table-row">
                                            <div class="table-cell name" data-label="Donor Details">
                                                NIC <?= htmlspecialchars($p->nic_number ?? 'N/A') ?> -
                                                <?= htmlspecialchars(trim(($p->first_name ?? '') . ' ' . ($p->last_name ?? '')) ?: 'N/A') ?>
                                            </div>
                                            <div class="table-cell" data-label="Organ Type"><?= htmlspecialchars($p->organ_name ?? 'N/A') ?></div>
                                            <div class="table-cell" data-label="Test Date"><?= htmlspecialchars(isset($p->pledge_date) ? date('d/m/Y', strtotime($p->pledge_date)) : 'N/A') ?></div>
                                            <div class="table-cell" data-label="Current Status"><span class="status-badge status-pending">Under Review</span></div>
                                            <div class="table-cell" data-label="Actions" style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                                <button class="btn btn-secondary btn-small" onclick="viewDonorLabData('<?= htmlspecialchars($p->nic_number ?? '') ?>')">View Labs</button>
                                                <button class="btn btn-success btn-small" onclick="approveEligibility('<?= (int)($p->pledge_id ?? 0) ?>')">Approve</button>
                                                <button class="btn btn-danger btn-small" onclick="rejectEligibility('<?= (int)($p->pledge_id ?? 0) ?>')">Reject</button>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="table-row">
                                        <div class="table-cell" style="text-align:center; color:#999; grid-column: 1 / -1;">
                                            No approved donor pledges assigned to this hospital.
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="recipients" class="content-section" style="display: none;">
                    <div class="content-header">
                        <h2>Recipient Patient Management</h2>
                        <p>Add, update, and view recipient patient records and treatment logs.</p>
                    </div>
                    <div class="content-body">
                        <div class="action-section"
                            style="position: relative; overflow: hidden; display: flex; justify-content: space-between; align-items: center;">
                            <div style="position: relative; z-index: 2;">
                                <h3>Patient Actions</h3>
                                <div class="action-buttons">
                                    <button class="btn btn-primary" onclick="openRecipientModal()">Add
                                        Recipient</button>
                                    <button class="btn btn-secondary" onclick="exportRecipients()">Export
                                        Records</button>
                                </div>
                            </div>

                            <!-- Decorative Medical Background Illustrations -->
                            <div
                                style="display: flex; gap: 10px; align-items: center; position: absolute; right: 20px; top: -50px; opacity: 0.1; pointer-events: none;">
                                <svg width="200" height="200" viewBox="0 0 24 24" fill="none" stroke="#005baa"
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M22 12h-4l-3 9L9 3l-3 9H2"></path>
                                </svg>
                                <svg width="180" height="180" viewBox="0 0 24 24" fill="none" stroke="#005baa"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    style="margin-left: -50px; transform: translateY(10px);">
                                    <path
                                        d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                                    </path>
                                </svg>
                            </div>
                        </div>

                        <div class="search-bar">
                            <span class="search-icon">Search:</span>
                            <input type="text" id="recipient-search" class="search-input"
                                placeholder="Search by recipient name, NIC, or ID..." onkeyup="applyRecipientFilters()">
                        </div>

                        <div class="data-table">
                            <div class="table-header">
                                <h4>Recipient Patients</h4>
                            </div>
                            <div class="table-content" id="recipients-table">
                                <div class="table-row" style="font-weight: 600; background: var(--gray-bg-color);">
                                    <div class="table-cell">Patient NIC</div>
                                    <div class="table-cell">Patient Name</div>
                                    <div class="table-cell">Organ Received</div>
                                    <div class="table-cell">Surgery Date</div>
                                    <div class="table-cell">Status</div>
                                    <div class="table-cell">Actions</div>
                                </div>
                                <!-- Content populated by JS -->
                            </div>
                        </div>
                    </div>
                </div>

                <div id="lab-reports" class="content-section" style="display: none;">
                    <div class="content-header">
                        <h2>Schedule Appointments</h2>
                        <p>View and manage upcoming donor appointments scheduled for your hospital.</p>
                    </div>
                    <div class="content-body">
                        <div class="action-section">
                            <div class="action-buttons">
                                <button class="btn btn-primary" onclick="openLabReportModal()">Schedule an Appointment</button>
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 340px; gap: 1.5rem; margin-bottom: 1.5rem; align-items: start;">
                            <!-- Left: Donors Menu and Tests List -->
                            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                                <div style="background: var(--white-color); border: 1px solid var(--border-color); border-radius: 14px; padding: 1.25rem;">
                                    <h3 style="margin-top: 0; margin-bottom: 1rem; font-size: 1.05rem; font-weight: 800; color: var(--primary-text-color);">Select Donor</h3>
                                    <div class="search-bar" style="margin-bottom: 1.25rem;">
                                        <span class="search-icon">🔍</span>
                                        <input type="text" class="search-input" id="lab-donor-search"
                                            placeholder="Search tests or donors..." style="width: 100%; box-sizing: border-box;">
                                    </div>
                                    <div id="lab-donor-tabs" class="lab-tabs vertical" style="display: flex; flex-direction: column; gap: 0.5rem; max-height: 250px; overflow-y: auto; padding-right: 5px;"></div>
                                </div>

                                <div style="background: var(--white-color); border: 1px solid var(--border-color); border-radius: 14px; padding: 1.25rem;">
                                    <div id="lab-cal-details" class="cal-details" style="margin-top: 0;"></div>
                                </div>
                            </div>

                            <!-- Right: Calendar -->
                            <div class="cal-wrap" aria-label="Appointment calendar" style="width: 100%; margin: 0; box-sizing: border-box; position: sticky; top: 1.5rem;">
                                <div class="cal-nav">
                                    <button type="button" class="cal-nav-btn" aria-label="Previous month" onclick="labCalPrev()">‹</button>
                                    <h3 id="lab-cal-title">—</h3>
                                    <button type="button" class="cal-nav-btn" aria-label="Next month" onclick="labCalNext()">›</button>
                                </div>
                                <div class="cal-grid" id="lab-cal-grid"></div>
                            </div>
                        </div>

                        <div class="data-table">
                            <div class="table-header">
                                <h4>Upcoming Appointments</h4>
                            </div>
                            <div class="table-content" id="lab-reports-table">
                                <div class="table-row" style="font-weight: 600; background: var(--gray-bg-color);">
                                    <div class="table-cell">Patient ID</div>
                                    <div class="table-cell">Donor NIC</div>
                                    <div class="table-cell">Donor Name</div>
                                    <div class="table-cell">Test Type</div>
                                    <div class="table-cell">Test Date</div>
                                    <div class="table-cell">Result Status</div>
                                    <div class="table-cell">Actions</div>
                                </div>
                                <!-- Content populated by JS -->
                            </div>
                        </div>
                    </div>
                </div>

                <div id="test-results" class="content-section" style="display: none;">
                    <div class="content-header">
                        <h2>Test Results</h2>
                        <p>Upload and review lab reports submitted by your hospital. Donors can view these under their Test Results page.</p>
                    </div>
                    <div class="content-body">
                        <div class="action-section">
                            <div class="action-buttons">
                                <button class="btn btn-primary" onclick="openTestResultModal()">Upload Test Result</button>
                            </div>
                        </div>

                        <div class="data-table">
                            <div class="table-header">
                                <h4>Uploaded Results</h4>
                            </div>
                            <div class="table-content">
                                <div class="table-row" style="font-weight: 600; background: var(--gray-bg-color);">
                                    <div class="table-cell">Donor ID</div>
                                    <div class="table-cell">Test Name</div>
                                    <div class="table-cell">Test Date</div>
                                    <div class="table-cell">Result</div>
                                    <div class="table-cell">Document</div>
                                </div>
                                <?php if (!empty($test_results)): foreach ($test_results as $tr): ?>
                                    <div class="table-row">
                                        <div class="table-cell" data-label="Donor ID"><?php echo htmlspecialchars($tr->donor_id ?? ''); ?></div>
                                        <div class="table-cell" data-label="Test Name"><?php echo htmlspecialchars($tr->test_name ?? ''); ?></div>
                                        <div class="table-cell" data-label="Test Date"><?php echo htmlspecialchars(!empty($tr->test_date) ? date('d/m/Y', strtotime($tr->test_date)) : ''); ?></div>
                                        <div class="table-cell" data-label="Result"><?php echo htmlspecialchars($tr->result_value ?? ''); ?></div>
                                        <div class="table-cell" data-label="Document">
                                            <?php if (!empty($tr->document_path)): ?>
                                                <?php
                                                    $doc = (string)$tr->document_path;
                                                    $root = (string)(ROOT ?? '');
                                                    $isAbs = (strpos($doc, 'http://') === 0 || strpos($doc, 'https://') === 0);
                                                    $isRooted = ($root !== '' && strpos($doc, $root) === 0);
                                                    $href = ($isAbs || $isRooted) ? $doc : ($root . '/' . ltrim($doc, '/'));
                                                ?>
                                                <a href="<?php echo htmlspecialchars($href); ?>" target="_blank" rel="noopener" class="btn btn-secondary btn-small">View</a>
                                            <?php else: ?>
                                                <span style="color:#6b7280; font-size:.9rem;">—</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; else: ?>
                                    <div class="table-row">
                                        <div class="table-cell" style="grid-column:1/-1; text-align:center; padding:20px; color:#999;">No test results uploaded yet</div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                    </div>
                </div>

                <div id="stories" class="content-section" style="display: none;">
                    <div class="content-header">
                        <h2>Success Stories Management</h2>
                        <p>Add and manage success stories with photos and media uploads.</p>
                    </div>
                    <div class="content-body">
                        <div class="action-section">
                            <h3>Story Actions</h3>
                            <div class="action-buttons">
                                <button class="btn btn-primary" onclick="openStoryModal()">Add Success Story</button>
                            </div>
                        </div>

                        <div class="data-table">
                            <div class="table-header">
                                <h4>Success Stories</h4>
                            </div>
                            <div class="table-content">
                                <div class="table-row" style="font-weight: 600; background: var(--gray-bg-color);">
                                    <div class="table-cell">Story Title</div>
                                    <div class="table-cell">Description</div>
                                    <div class="table-cell">Date</div>
                                    <div class="table-cell">Status</div>
                                    <div class="table-cell">Actions</div>
                                </div>

                                <div class="table-row">
                                    <div class="table-cell name" data-label="Story Title">A Life Saved - Kidney
                                        Transplant Success</div>
                                    <div class="table-cell" data-label="Description">Kidney transplant is successful</div>
                                    <div class="table-cell" data-label="Date">2025-09-15</div>
                                    <div class="table-cell" data-label="Status"><span class="status-badge status-pending">Pending Review</span></div>
                                    <div class="table-cell" data-label="Actions">
                                        <button class="btn btn-secondary btn-small" onclick="editStory()">Edit</button>
                                        <button class="btn btn-danger btn-small" onclick="deleteStory()">Delete</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Modal -->
    <div class="modal" id="profile-modal">
        <div class="modal-content" style="max-width: 500px;">
            <div class="modal-header">
                <h3>Edit Hospital Profile</h3>
                <button class="modal-close" onclick="closeProfileModal()">×</button>
            </div>
            <div>
                <div class="form-group">
                    <label class="form-label">Hospital Name</label>
                    <input type="text" class="form-input" id="profile-name"
                        value="<?php echo htmlspecialchars($hospital_details['name']); ?>">
                </div>
                <?php
                $modalAddress = $hospital_details['address'] ?? '';
                $modalPhone = $hospital_details['phone'] ?? '';
                if ($modalAddress && strpos($modalAddress, '[Phone]:') !== false) {
                    $parts = explode(' | [Address]: ', $modalAddress);
                    $modalPhone = str_replace('[Phone]: ', '', $parts[0]);
                    $modalAddress = $parts[1] ?? '';
                }
                ?>
                <div class="form-group">
                    <label class="form-label">Address</label>
                    <input type="text" class="form-input" id="profile-address"
                        value="<?php echo htmlspecialchars($modalAddress); ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Phone Number</label>
                    <input type="text" class="form-input" id="profile-phone"
                        value="<?php echo htmlspecialchars($modalPhone); ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Email (Login Account)</label>
                    <input type="text" class="form-input"
                        value="<?php echo htmlspecialchars($hospital_details['email']); ?>" disabled
                        style="background: #f8f9fa;">
                </div>
                <button class="btn btn-primary" onclick="saveProfile()" style="width: 100%;">Update Information</button>
            </div>
        </div>
    </div>

    <!-- Organ Request Modal -->
    <div class="modal" id="request-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add Organ Request</h3>
                <button class="modal-close" onclick="closeRequestModal()">×</button>
            </div>
            <div>
                <input type="hidden" id="request-id" value="">
                <div class="form-group">
                    <label class="form-label">Organ Type</label>
                    <select class="form-select" id="organ-type">
                        <option value="">Select Organ</option>
                        <?php foreach (($organs ?? []) as $organ): ?>
                            <option value="<?= (int)$organ->id ?>"><?= htmlspecialchars($organ->name) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Urgency Level</label>
                    <select class="form-select" id="urgency-level">
                        <option value="">Select Urgency</option>
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                        <option value="emergency">Emergency</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Recipient Age</label>
                    <input class="form-input" id="recipient-age" type="number" min="18" max="80" placeholder="18 - 80">
                </div>
                <div class="form-group">
                    <label class="form-label">Recipient Blood Group</label>
                    <select class="form-select" id="recipient-blood-group">
                        <option value="">Select Blood Group</option>
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Recipient Gender</label>
                    <select class="form-select" id="recipient-gender">
                        <option value="">Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">HLA-typing</label>
                    <input class="form-input" id="recipient-hla-typing" type="text" placeholder="e.g., HLA-A*02:01, HLA-B*07:02">
                </div>
                <div id="urgency-reason-group" class="form-group" style="display: none;">
                    <label class="form-label">Reason for Change <span style="color:red">*</span></label>
                    <textarea class="form-textarea" id="urgency-reason"
                        placeholder="Explain why the urgency was updated..."></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Reason for Transplant</label>
                    <textarea class="form-textarea" id="transplant-reason"
                        placeholder="e.g., End-stage renal disease"></textarea>
                </div>
                <button class="btn btn-primary" onclick="saveRequest()">Save Request</button>
            </div>
        </div>
    </div>

    <!-- Organ Request Details Modal -->
    <div class="modal" id="request-details-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Organ Request Details</h3>
                <button class="modal-close" onclick="closeDetailsModal()">×</button>
            </div>
            <div style="display: grid; gap: 0.75rem;">
                <div><strong>Organ Type:</strong> <span id="details-organ"></span></div>
                <div><strong>Urgency:</strong> <span id="details-urgency"></span></div>
                <div><strong>Status:</strong> <span id="details-status"></span></div>
                <div><strong>Edited:</strong> <span id="details-edited"></span></div>
                <div><strong>Edit Reason:</strong> <span id="details-edit-reason"></span></div>
                <div><strong>Recipient Age:</strong> <span id="details-age"></span></div>
                <div><strong>Blood Group:</strong> <span id="details-blood"></span></div>
                <div><strong>Gender:</strong> <span id="details-gender"></span></div>
                <div><strong>HLA-typing:</strong> <span id="details-hla"></span></div>
                <div><strong>Reason for Transplant:</strong> <span id="details-reason"></span></div>
            </div>
        </div>
    </div>

    <!-- Recipient Modal -->
    <div class="modal" id="recipient-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add Recipient Patient</h3>
                <button class="modal-close" onclick="closeRecipientModal()">×</button>
            </div>
            <div>
                <div class="form-group">
                    <label class="form-label">Patient NIC</label>
                    <input type="text" class="form-input" id="recipient-nic" placeholder="1999XXXXXXX">
                </div>
                <div class="form-group">
                    <label class="form-label">Patient Name</label>
                    <input type="text" class="form-input" id="recipient-name" placeholder="Full name">
                </div>
                <div class="form-group">
                    <label class="form-label">Organ Received</label>
                    <select class="form-select" id="recipient-organ">
                        <option value="">Select Organ</option>
                        <option value="kidney">Kidney</option>
                        <option value="liver">Liver</option>
                        <option value="heart">Heart</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Surgery Date</label>
                    <input type="date" class="form-input" id="surgery-date">
                </div>
                <div class="form-group">
                    <label class="form-label">Treatment Notes</label>
                    <textarea class="form-textarea" id="treatment-notes"
                        placeholder="Post-surgery treatment details..."></textarea>
                </div>
                <button class="btn btn-primary" onclick="saveRecipient()">Save Recipient</button>
            </div>
        </div>
    </div>

    <!-- Story Modal -->
    <div class="modal" id="story-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add Success Story</h3>
                <button class="modal-close" onclick="closeStoryModal()">×</button>
            </div>
            <div>
                <div class="form-group">
                    <label class="form-label">Story Title</label>
                    <input type="text" class="form-input" id="story-title" placeholder="Enter story title">
                </div>
                <div class="form-group">
                    <label class="form-label">Story Description</label>
                    <textarea class="form-textarea" id="story-description"
                        placeholder="Describe the success story..."></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Date of Success</label>
                    <input type="date" class="form-input" id="success-date">
                </div>
                <button class="btn btn-primary" onclick="saveStory()">Save Story</button>
            </div>
        </div>
    </div>

    <!-- Lab Report Modal -->
    <div class="modal" id="lab-report-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Schedule Appointment</h3>
                <button class="modal-close" onclick="closeLabReportModal()">×</button>
            </div>
            <input type="hidden" id="lab-report-id" value="">
            <div>
                <div class="form-group">
                    <label class="form-label">Select Donor <span style="color: #e74c3c;">*</span></label>
                    <select class="form-select" id="lab-donor-select">
                        <option value="">Select a Donor</option>
                    </select>
                    <input type="hidden" id="lab-donor-id" value="">
                </div>
                <div class="form-group">
                    <label class="form-label">Organ Type <span style="color: #e74c3c;">*</span></label>
                    <select class="form-select" id="lab-organ-id">
                        <option value="">Select Organ</option>
                        <?php foreach (($organs ?? []) as $organ): ?>
                            <option value="<?= (int)$organ->id ?>"><?= htmlspecialchars($organ->name) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group" style="display: none;">
                    <label class="form-label">Recipient Patient (Optional)</label>
                    <select class="form-select" id="lab-recipient-patient">
                        <option value="">Select Recipient Patient</option>
                    </select>
                    <input type="hidden" id="lab-recipient-id" value="">
                </div>
                <div class="form-group">
                    <label class="form-label">Select Tests <span style="color: #e74c3c;">*</span></label>
                    <div id="lab-tests-container" style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-top: 8px;"></div>
                    <input type="text" id="lab_test_type_other_input" class="form-input"
                        placeholder="Enter other test name(s)..." style="display: none; margin-top: 10px;">
                    <input type="hidden" id="lab-test-type" value="">
                </div>
                <div class="form-group">
                    <label class="form-label">Test Date <span style="color: #e74c3c;">*</span></label>
                    <input type="date" class="form-input" id="lab-test-date">
                </div>
                <div class="form-group">
                    <label class="form-label">Notes (Optional)</label>
                    <textarea class="form-textarea" id="lab-result-notes"
                        placeholder="Detailed test results and measurements..."></textarea>
                </div>
                <div class="form-group" style="display: none;">
                    <label class="form-label">Blood Type (if applicable)</label>
                    <select class="form-select" id="lab-blood-type">
                        <option value="">Select Blood Type</option>
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
                <button class="btn btn-primary" onclick="saveLabReport()">Schedule Appointment</button>
            </div>
        </div>
    </div>

    <!-- Test Result Upload Modal -->
    <div class="modal" id="test-result-modal">
        <div class="modal-content" style="max-width: 560px;">
            <div class="modal-header">
                <h3>Upload Test Result</h3>
                <button class="modal-close" onclick="closeTestResultModal()">×</button>
            </div>
            <div>
                <div class="form-group">
                    <label class="form-label">Select Donor <span style="color: #e74c3c;">*</span></label>
                    <select class="form-select" id="tr-donor-select">
                        <option value="">Select a Donor</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Test Name <span style="color: #e74c3c;">*</span></label>
                    <input type="text" class="form-input" id="tr-test-name" placeholder="e.g., CBC / LFT / Kidney Function" />
                </div>
                <div class="form-group">
                    <label class="form-label">Test Date <span style="color: #e74c3c;">*</span></label>
                    <input type="date" class="form-input" id="tr-test-date" />
                </div>
                <div class="form-group">
                    <label class="form-label">Result Value (Optional)</label>
                    <input type="text" class="form-input" id="tr-result-value" placeholder="e.g., Normal / Positive / 12.5 g/dL" />
                </div>
                <div class="form-group">
                    <label class="form-label">Document (Optional: PDF/Image)</label>
                    <input type="file" class="form-input" id="tr-document" accept=".pdf,.png,.jpg,.jpeg,.webp" />
                </div>

                <div style="display:flex; gap:10px; justify-content:flex-end;">
                    <button class="btn btn-secondary" onclick="closeTestResultModal()">Cancel</button>
                    <button class="btn btn-primary" onclick="submitTestResult()">Upload</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Modal -->
    <div class="modal" id="export-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Export Recipient Records</h3>
                <button class="modal-close" onclick="closeExportModal()">×</button>
            </div>
            <div>
                <div class="form-group">
                    <label class="form-label">Select Export Format</label>
                    <select class="form-select" id="export-format">
                        <option value="">Choose format...</option>
                        <option value="xlsx">Excel (.xlsx) - For data analysis</option>
                        <option value="csv">CSV (.csv) - For generic data use</option>
                        <option value="pdf">PDF (.pdf) - For formal reports</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Include Sections</label>
                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                        <label style="display: flex; align-items: center; gap: 0.5rem;">
                            <input type="checkbox" checked disabled> Recipient Details
                        </label>
                        <label style="display: flex; align-items: center; gap: 0.5rem;">
                            <input type="checkbox" checked> Treatment History
                        </label>
                        <label style="display: flex; align-items: center; gap: 0.5rem;">
                            <input type="checkbox"> Clinical Remarks
                        </label>
                    </div>
                </div>
                <button class="btn btn-primary" onclick="downloadExport()">Download Report</button>
            </div>
        </div>
    </div>

    <script>
        function showContent(id, element) {
            // Hide all content sections
            document.querySelectorAll('.content-section').forEach(s => {
                s.style.display = 'none';
                s.classList.remove('active');
            });
            
            const target = document.getElementById(id);
            if (target) {
                target.style.display = 'block';
                target.classList.add('active');
            }

            // Update active menu item
            document.querySelectorAll('.menu-item').forEach(i => i.classList.remove('active'));
            if (element) {
                element.classList.add('active');
            } else {
                // Find and activate the menu item by ID string in its onclick attribute if no element passed
                const items = document.querySelectorAll('.menu-item');
                items.forEach(item => {
                    if (item.getAttribute('onclick') && item.getAttribute('onclick').includes(id)) {
                        item.classList.add('active');
                    }
                });
            }

            // Scroll to top of content area
            window.scrollTo({ top: 0, behavior: 'smooth' });

            // Load data for specific sections
            if (id === 'recipients') loadRecipients();
            else if (id === 'organ-requests') loadOrganRequests();
            else if (id === 'stories') loadStories();
            else if (id === 'lab-reports') loadLabReports();
        }

        // Initialize display
        document.addEventListener('DOMContentLoaded', function() {
            showContent('overview');
        });

        // Organ Request Functions
        function openRequestModal() { document.getElementById('request-modal').classList.add('show'); }
        function closeRequestModal() { document.getElementById('request-modal').classList.remove('show'); }

        // Organ Type Selection Function
        function selectOrganType(organId, organName) {
            // Remove selected class from all cards
            document.querySelectorAll('.organ-option-card').forEach(card => {
                card.classList.remove('selected');
            });

            // Add selected class to clicked card
            event.currentTarget.classList.add('selected');

            // Set the organ type in the modal form
            const organSelect = document.getElementById('organ-type');
            if (organSelect) {
                organSelect.value = String(organId);
            }

            // Open the request modal
            openRequestModal();
        }
        function editRequest(requestId) {
            const requests = <?php echo json_encode($organ_requests); ?>;
            const request = requests.find(r => r.id == requestId);
            if (!request) return;

            document.getElementById('request-modal').classList.add('show');
            document.querySelector('#request-modal h3').textContent = 'Edit Organ Request';

            document.getElementById('request-id').value = request.id;
            document.getElementById('organ-type').value = String(request.organ_id);
            document.getElementById('organ-type').disabled = true; // Cannot change organ type, only priority

            // Map DB enum -> UI select
            const priorityToUi = {
                'NORMAL': 'low',
                'URGENT': 'medium',
                'CRITICAL': 'emergency',
                'HIGH': 'high'
            };
            document.getElementById('urgency-level').value = priorityToUi[request.priority_level] || 'low';

            const ageEl = document.getElementById('recipient-age');
            const bgEl = document.getElementById('recipient-blood-group');
            const genderEl = document.getElementById('recipient-gender');
            const hlaEl = document.getElementById('recipient-hla-typing');
            const transplantReasonEl = document.getElementById('transplant-reason');

            if (ageEl) ageEl.value = request.recipient_age ?? '';
            if (bgEl) bgEl.value = request.blood_group || '';
            if (genderEl) genderEl.value = request.gender || '';
            if (hlaEl) hlaEl.value = request.hla_typing || '';
            if (transplantReasonEl) transplantReasonEl.value = request.transplant_reason || '';

            // Show reason field during edit
            document.getElementById('urgency-reason-group').style.display = 'block';
            document.getElementById('urgency-reason').value = ''; // Clear for new entry

            showServerMessage('Editing urgency for Request ID: ' + requestId, 'info');
        }

        function closeRequestModal() {
            document.getElementById('request-modal').classList.remove('show');
            document.querySelector('#request-modal h3').textContent = 'Add Organ Request';
            document.getElementById('request-id').value = '';
            document.getElementById('organ-type').disabled = false;
            document.getElementById('urgency-reason-group').style.display = 'none';
            const ageEl = document.getElementById('recipient-age');
            const bgEl = document.getElementById('recipient-blood-group');
            const genderEl = document.getElementById('recipient-gender');
            const hlaEl = document.getElementById('recipient-hla-typing');
            const transplantReasonEl = document.getElementById('transplant-reason');
            if (ageEl) ageEl.value = '';
            if (bgEl) bgEl.value = '';
            if (genderEl) genderEl.value = '';
            if (hlaEl) hlaEl.value = '';
            if (transplantReasonEl) transplantReasonEl.value = '';
            document.getElementById('urgency-level').value = '';
            document.getElementById('organ-type').value = '';
        }

        function saveRequest() {
            const requestId = document.getElementById('request-id').value;
            const organId = document.getElementById('organ-type').value;
            const urgency = document.getElementById('urgency-level').value;
            const reason = document.getElementById('urgency-reason').value;
            const age = document.getElementById('recipient-age') ? document.getElementById('recipient-age').value : '';
            const bloodGroup = document.getElementById('recipient-blood-group') ? document.getElementById('recipient-blood-group').value : '';
            const gender = document.getElementById('recipient-gender') ? document.getElementById('recipient-gender').value : '';
            const hlaTyping = document.getElementById('recipient-hla-typing') ? document.getElementById('recipient-hla-typing').value : '';
            const transplantReason = document.getElementById('transplant-reason') ? document.getElementById('transplant-reason').value : '';

            if (!organId || !urgency) {
                showServerMessage('Please complete all required fields', 'error');
                return;
            }

            const ageNum = parseInt(age, 10);
            if (!age || isNaN(ageNum) || ageNum < 18 || ageNum > 80) {
                showServerMessage('Recipient age must be between 18 and 80', 'error');
                return;
            }

            if (!bloodGroup) {
                showServerMessage('Please select a blood group', 'error');
                return;
            }

            if (!gender) {
                showServerMessage('Please select a gender', 'error');
                return;
            }

            if (!transplantReason || !transplantReason.trim()) {
                showServerMessage('Reason for transplant is required', 'error');
                return;
            }

            // If editing, reason is mandatory
            if (requestId && !reason) {
                showServerMessage('Please provide a reason for the urgency change', 'error');
                return;
            }

            const form = document.createElement('form');
            form.method = 'POST';
            form.style.display = 'none';

            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = requestId ? 'edit_organ_request' : 'add_organ_request';
            form.appendChild(actionInput);

            if (requestId) {
                const idInput = document.createElement('input');
                idInput.type = 'hidden';
                idInput.name = 'request_id';
                idInput.value = requestId;
                form.appendChild(idInput);

                const reasonInput = document.createElement('input');
                reasonInput.type = 'hidden';
                reasonInput.name = 'edited_reason';
                reasonInput.value = reason;
                form.appendChild(reasonInput);
            }

            const organInput = document.createElement('input');
            organInput.type = 'hidden';
            organInput.name = 'organ_id';
            organInput.value = organId;
            form.appendChild(organInput);

            const urgencyInput = document.createElement('input');
            urgencyInput.type = 'hidden';
            urgencyInput.name = 'urgency';
            urgencyInput.value = urgency;
            form.appendChild(urgencyInput);

            const ageInput = document.createElement('input');
            ageInput.type = 'hidden';
            ageInput.name = 'recipient_age';
            ageInput.value = ageNum;
            form.appendChild(ageInput);

            const bgInput = document.createElement('input');
            bgInput.type = 'hidden';
            bgInput.name = 'blood_group';
            bgInput.value = bloodGroup;
            form.appendChild(bgInput);

            const genderInput = document.createElement('input');
            genderInput.type = 'hidden';
            genderInput.name = 'gender';
            genderInput.value = gender;
            form.appendChild(genderInput);

            const hlaInput = document.createElement('input');
            hlaInput.type = 'hidden';
            hlaInput.name = 'hla_typing';
            hlaInput.value = hlaTyping;
            form.appendChild(hlaInput);

            const transplantReasonInput = document.createElement('input');
            transplantReasonInput.type = 'hidden';
            transplantReasonInput.name = 'transplant_reason';
            transplantReasonInput.value = transplantReason;
            form.appendChild(transplantReasonInput);

            document.body.appendChild(form);
            form.submit();
        }
        function deleteRequest(requestId) {
            if (confirm('Are you sure you want to delete this organ request?')) {
                // Submit form to same page
                const form = document.createElement('form');
                form.method = 'POST';
                form.style.display = 'none';

                const actionInput = document.createElement('input');
                actionInput.type = 'hidden';
                actionInput.name = 'action';
                actionInput.value = 'delete_organ_request';
                form.appendChild(actionInput);

                const requestIdInput = document.createElement('input');
                requestIdInput.type = 'hidden';
                requestIdInput.name = 'request_id';
                requestIdInput.value = requestId;
                form.appendChild(requestIdInput);

                document.body.appendChild(form);
                form.submit();
            }
        }

        function loadOrganRequests() {
            // Use PHP data directly
            const requests = <?php echo json_encode($organ_requests); ?>;
            updateOrganRequestsTable(requests);
        }

        function applyOrganFilters() {
            const searchTerm = document.getElementById('organ-search').value.toLowerCase();
            const organFilter = document.getElementById('organ-type-filter').value;
            const urgencyFilter = document.getElementById('urgency-filter').value;

            // Access original organ requests from PHP
            const allRequests = <?php echo json_encode($organ_requests); ?>;

            const filtered = allRequests.filter(req => {
                const organName = String(req.organ_name || '').toLowerCase();
                const priority = String(req.priority_level || '').toUpperCase().trim();
                const q = (searchTerm || '').trim();

                let matchesSearch = true;
                if (q) {
                    const matchesOrgan = organName.includes(q);

                    // Allow searching by urgency keywords too
                    // DB values: NORMAL / URGENT / CRITICAL
                    // UI labels: low/medium -> NORMAL, high -> URGENT, emergency -> CRITICAL
                    let matchesUrgency = priority.toLowerCase().includes(q);
                    if (!matchesUrgency) {
                        if (q === 'emergency' || q === 'critical') {
                            matchesUrgency = (priority === 'CRITICAL');
                        } else if (q === 'high' || q === 'urgent') {
                            matchesUrgency = (priority === 'URGENT');
                        } else if (q === 'low' || q === 'medium' || q === 'normal') {
                            matchesUrgency = (priority === 'NORMAL');
                        }
                    }

                    matchesSearch = matchesOrgan || matchesUrgency;
                }
                const matchesOrgan = !organFilter || String(req.organ_id) === String(organFilter);
                const matchesUrgency = !urgencyFilter || req.priority_level === urgencyFilter;

                return matchesSearch && matchesOrgan && matchesUrgency;
            });

            updateOrganRequestsTable(filtered);
        }

        function updateOrganRequestsTable(requests) {
            const tableContent = document.querySelector('#organ-requests .table-content');
            if (!tableContent) return;

            // Clear existing rows (except header)
            const existingRows = tableContent.querySelectorAll('.table-row:not(:first-child)');
            existingRows.forEach(row => row.remove());

            // Add new rows
            requests.forEach(request => {
                const row = document.createElement('div');
                row.className = 'table-row';
                if (request.edited_reason && String(request.edited_reason).trim() !== '') {
                    row.style.fontStyle = 'italic';
                }

                row.innerHTML = `
                    <div class="table-cell name" data-label="Organ Type">${request.organ_name}</div>
                    <div class="table-cell" data-label="Urgency">
                        <span class="status-badge ${request.priority_level === 'CRITICAL' ? 'status-danger' : request.priority_level === 'URGENT' ? 'status-active' : 'status-pending'}">
                            ${request.priority_level}
                        </span>
                    </div>
                    <div class="table-cell" data-label="Created Date">${new Date(request.created_at).toLocaleDateString('en-GB')}</div>
                    <div class="table-cell" data-label="Status">${request.status || 'PENDING'}</div>
                    <div class="table-cell" data-label="Actions">
                        <div style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: nowrap;">
                            <button class="btn btn-secondary btn-small" onclick="viewDetails(${request.id})" style="white-space: nowrap;">More Details</button>
                            <button class="btn btn-secondary btn-small" onclick="editRequest(${request.id})" style="white-space: nowrap;">Edit</button>
                            <button class="btn btn-danger btn-small" onclick="deleteRequest(${request.id})" style="white-space: nowrap;">Delete</button>
                        </div>
                    </div>
                `;
                tableContent.appendChild(row);
            });
        }

        function viewDetails(requestId) {
            const requests = <?php echo json_encode($organ_requests); ?>;
            const request = requests.find(r => r.id == requestId);
            if (!request) return;

            document.getElementById('details-organ').textContent = request.organ_name || '';
            document.getElementById('details-urgency').textContent = request.priority_level || '';
            document.getElementById('details-status').textContent = request.status || 'PENDING';
            const editedText = (request.edited_reason && String(request.edited_reason).trim() !== '') ? 'Yes' : 'No';
            document.getElementById('details-edited').textContent = editedText;
            document.getElementById('details-edit-reason').textContent = (request.edited_reason && String(request.edited_reason).trim() !== '') ? request.edited_reason : 'N/A';
            document.getElementById('details-age').textContent = request.recipient_age ?? 'N/A';
            document.getElementById('details-blood').textContent = request.blood_group || 'N/A';
            document.getElementById('details-gender').textContent = request.gender || 'N/A';
            document.getElementById('details-hla').textContent = request.hla_typing || 'N/A';
            document.getElementById('details-reason').textContent = request.transplant_reason || 'N/A';

            document.getElementById('request-details-modal').classList.add('show');
        }

        function closeDetailsModal() {
            document.getElementById('request-details-modal').classList.remove('show');
        }


        // Eligibility Functions
        function viewDonorLabData(nic) {
            // Step 1: View Lab Data
            const message = `Medical Lab Profile for ${nic}\n\n- Blood Group: O+\n- HIV: Negative (Clear)\n- Hepatitis B: Negative (Clear)\n- Hepatitis C: Negative (Clear)\n- CBC: Normal Range\n\nOverall Screening: Medically Fit for Donation`;
            alert(message);
        }

        function approveEligibility(nic) {
            // Step 3: Match Algorithm Trigger
            showServerMessage(`Donor ${nic} approved! Transitioning to active donor pool...`, 'success');

            // Simulate automated matching lookup sequence
            setTimeout(() => {
                const matchFound = confirm(`AUTOMATED MATCH DETECTED!\n\nDonor ${nic} (Blood Type: O+, Organ: Kidney) perfectly matches an Urgent Organ Request.\n\nWould you like to initiate the transfer and notify the surgical team?`);
                if (matchFound) {
                    showServerMessage('Automated transplant protocol initiated!', 'info');
                }
            }, 1500);
        }

        function rejectEligibility(nic) {
            // Step 2: Reason for Rejection Prompt
            const reason = prompt(`IMPORTANT: You are rejecting donor ${nic}.\n\nPlease provide the medical reason for this rejection (e.g. "Positive for Hepatitis"):`);
            if (reason) {
                showServerMessage(`Donor disqualified. Reason securely logged: "${reason}"`, 'error');
            } else {
                showServerMessage('Action cancelled. You must provide a valid medical reason to reject a donor.', 'info');
            }
        }

        // Recipient Functions
        function openRecipientModal() { document.getElementById('recipient-modal').classList.add('show'); }
        function closeRecipientModal() {
            document.getElementById('recipient-modal').classList.remove('show');
            // Reset modal to add mode
            document.querySelector('#recipient-modal .modal-header h3').textContent = 'Add Recipient Patient';
            document.getElementById('recipient-nic').value = '';
            document.getElementById('recipient-name').value = '';
            document.getElementById('recipient-organ').value = '';
            document.getElementById('surgery-date').value = '';
            document.getElementById('treatment-notes').value = '';

            // Reset button
            const saveButton = document.querySelector('#recipient-modal button[onclick*="updateRecipient"]');
            if (saveButton) {
                saveButton.textContent = 'Save Recipient';
                saveButton.setAttribute('onclick', 'saveRecipient()');
            }
        }
        function saveRecipient() {
            const nic = document.getElementById('recipient-nic').value;
            const name = document.getElementById('recipient-name').value;
            const organ = document.getElementById('recipient-organ').value;
            const date = document.getElementById('surgery-date').value;
            const notes = document.getElementById('treatment-notes').value;

            if (!nic || !name || !organ || !date) {
                showServerMessage('localhost: Error - Please fill all required fields', 'error');
                return;
            }

            // Submit form to same page
            const form = document.createElement('form');
            form.method = 'POST';
            form.style.display = 'none';

            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = 'add_recipient';
            form.appendChild(actionInput);

            const nicInput = document.createElement('input');
            nicInput.type = 'hidden';
            nicInput.name = 'nic';
            nicInput.value = nic;
            form.appendChild(nicInput);

            const nameInput = document.createElement('input');
            nameInput.type = 'hidden';
            nameInput.name = 'name';
            nameInput.value = name;
            form.appendChild(nameInput);

            const organInput = document.createElement('input');
            organInput.type = 'hidden';
            organInput.name = 'organ_received';
            organInput.value = organ;
            form.appendChild(organInput);

            const dateInput = document.createElement('input');
            dateInput.type = 'hidden';
            dateInput.name = 'surgery_date';
            dateInput.value = date;
            form.appendChild(dateInput);

            const notesInput = document.createElement('input');
            notesInput.type = 'hidden';
            notesInput.name = 'treatment_notes';
            notesInput.value = notes;
            form.appendChild(notesInput);

            document.body.appendChild(form);
            form.submit();
        }
        function editRecipient(recipientId) {
            // Get recipient data and populate edit form
            const recipients = <?php echo json_encode($recipients); ?>;
            const recipient = recipients.find(r => r.recipient_id == recipientId);

            if (recipient) {
                // Update modal header
                document.querySelector('#recipient-modal .modal-header h3').textContent = 'Edit Recipient Patient';

                // Populate form fields
                document.getElementById('recipient-nic').value = recipient.nic;
                document.getElementById('recipient-name').value = recipient.name;
                document.getElementById('recipient-organ').value = recipient.organ_received;
                document.getElementById('surgery-date').value = recipient.surgery_date;
                document.getElementById('treatment-notes').value = recipient.treatment_notes;

                // Change the save button to update button
                const saveButton = document.querySelector('#recipient-modal button[onclick="saveRecipient()"]');
                if (saveButton) {
                    saveButton.textContent = 'Update Recipient';
                    saveButton.setAttribute('onclick', 'updateRecipient(' + recipientId + ')');
                }

                // Show the modal
                document.getElementById('recipient-modal').classList.add('show');
            }
        }
        function updateRecipient(recipientId) {
            const nic = document.getElementById('recipient-nic').value;
            const name = document.getElementById('recipient-name').value;
            const organ = document.getElementById('recipient-organ').value;
            const surgery_date = document.getElementById('surgery-date').value;
            const notes = document.getElementById('treatment-notes').value;

            if (!nic || !name || !organ || !surgery_date) {
                showServerMessage('localhost: Error - Please fill all required fields', 'error');
                return;
            }

            // Submit form to same page
            const form = document.createElement('form');
            form.method = 'POST';
            form.style.display = 'none';

            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = 'update_recipient';
            form.appendChild(actionInput);

            const recipientIdInput = document.createElement('input');
            recipientIdInput.type = 'hidden';
            recipientIdInput.name = 'recipient_id';
            recipientIdInput.value = recipientId;
            form.appendChild(recipientIdInput);

            const nicInput = document.createElement('input');
            nicInput.type = 'hidden';
            nicInput.name = 'nic';
            nicInput.value = nic;
            form.appendChild(nicInput);

            const nameInput = document.createElement('input');
            nameInput.type = 'hidden';
            nameInput.name = 'name';
            nameInput.value = name;
            form.appendChild(nameInput);

            const organInput = document.createElement('input');
            organInput.type = 'hidden';
            organInput.name = 'organ_received';
            organInput.value = organ;
            form.appendChild(organInput);

            const dateInput = document.createElement('input');
            dateInput.type = 'hidden';
            dateInput.name = 'surgery_date';
            dateInput.value = surgery_date;
            form.appendChild(dateInput);

            const notesInput = document.createElement('input');
            notesInput.type = 'hidden';
            notesInput.name = 'treatment_notes';
            notesInput.value = notes;
            form.appendChild(notesInput);

            const statusInput = document.createElement('input');
            statusInput.type = 'hidden';
            statusInput.name = 'status';
            statusInput.value = 'Active'; // Default status
            form.appendChild(statusInput);

            document.body.appendChild(form);
            form.submit();
        }

        function deleteRecipient(recipientId) {
            if (confirm('Are you sure you want to delete this recipient?')) {
                // Submit form to same page
                const form = document.createElement('form');
                form.method = 'POST';
                form.style.display = 'none';

                const actionInput = document.createElement('input');
                actionInput.type = 'hidden';
                actionInput.name = 'action';
                actionInput.value = 'delete_recipient';
                form.appendChild(actionInput);

                const recipientIdInput = document.createElement('input');
                recipientIdInput.type = 'hidden';
                recipientIdInput.name = 'recipient_id';
                recipientIdInput.value = recipientId;
                form.appendChild(recipientIdInput);

                document.body.appendChild(form);
                form.submit();
            }
        }
        function viewTreatmentLog() { showServerMessage('localhost: Loading treatment log from database', 'success'); }
        function exportRecipients() { showServerMessage('localhost: Exporting recipient data to Excel file', 'success'); }

        function loadRecipients() {
            // Use PHP data directly
            const recipients = <?php echo json_encode($recipients); ?>;
            // Load recipients data
            updateRecipientsTable(recipients);
        }

        function updateRecipientsTable(recipients) {
            const tableContent = document.querySelector('#recipients .table-content');
            if (!tableContent) return;

            // Clear existing rows (except header)
            const existingRows = tableContent.querySelectorAll('.table-row:not(:first-child)');
            existingRows.forEach(row => row.remove());

            // Add new rows
            recipients.forEach(recipient => {
                const row = document.createElement('div');
                row.className = 'table-row';
                row.innerHTML = `
                    <div class="table-cell name" data-label="NIC">${recipient.nic}</div>
                    <div class="table-cell" data-label="Name">${recipient.name}</div>
                    <div class="table-cell" data-label="Organ">${recipient.organ_received}</div>
                    <div class="table-cell" data-label="Surgery Date">${new Date(recipient.surgery_date).toLocaleDateString('en-GB')}</div>
                    <div class="table-cell" data-label="Status">
                        <span class="status-badge ${recipient.status === 'Active' ? 'status-active' : recipient.status === 'Discharged' ? 'status-success' : 'status-pending'}">${recipient.status}</span>
                    </div>
                    <div class="table-cell" data-label="Actions">
                        <button class="btn btn-secondary btn-small" onclick="editRecipient(${recipient.recipient_id})">Edit</button>
                        <button class="btn btn-danger btn-small" onclick="deleteRecipient(${recipient.recipient_id})">Delete</button>
                    </div>
                `;
                tableContent.appendChild(row);
            });
        }

        // Story Functions
        function openStoryModal() { document.getElementById('story-modal').classList.add('show'); }
        function closeStoryModal() {
            document.getElementById('story-modal').classList.remove('show');
            // Reset modal to add mode
            document.querySelector('#story-modal .modal-header h3').textContent = 'Add Success Story';
            document.getElementById('story-title').value = '';
            document.getElementById('story-description').value = '';
            document.getElementById('success-date').value = '';

            // Reset button
            const saveButton = document.querySelector('#story-modal button[onclick*="updateStory"]');
            if (saveButton) {
                saveButton.textContent = 'Save Story';
                saveButton.setAttribute('onclick', 'saveStory()');
            }
        }
        function saveStory() {
            const title = document.getElementById('story-title').value;
            const description = document.getElementById('story-description').value;
            const date = document.getElementById('success-date').value;

            if (!title || !description || !date) {
                showServerMessage('localhost: Error - Please fill all required fields', 'error');
                return;
            }

            // Submit form to same page
            const form = document.createElement('form');
            form.method = 'POST';
            form.style.display = 'none';

            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = 'add_success_story';
            form.appendChild(actionInput);

            const titleInput = document.createElement('input');
            titleInput.type = 'hidden';
            titleInput.name = 'title';
            titleInput.value = title;
            form.appendChild(titleInput);

            const descriptionInput = document.createElement('input');
            descriptionInput.type = 'hidden';
            descriptionInput.name = 'description';
            descriptionInput.value = description;
            form.appendChild(descriptionInput);

            const dateInput = document.createElement('input');
            dateInput.type = 'hidden';
            dateInput.name = 'success_date';
            dateInput.value = date;
            form.appendChild(dateInput);

            document.body.appendChild(form);
            form.submit();
        }
        function editStory(storyId) {
            // Get story data and populate edit form
            const stories = <?php echo json_encode($success_stories); ?>;
            const story = stories.find(s => s.story_id == storyId);

            if (story) {
                // Update modal header
                document.querySelector('#story-modal .modal-header h3').textContent = 'Edit Success Story';

                // Populate form fields
                document.getElementById('story-title').value = story.title;
                document.getElementById('story-description').value = story.description;
                document.getElementById('success-date').value = story.success_date;

                // Change the save button to update button
                const saveButton = document.querySelector('#story-modal button[onclick="saveStory()"]');
                if (saveButton) {
                    saveButton.textContent = 'Update Story';
                    saveButton.setAttribute('onclick', 'updateStory(' + storyId + ')');
                }

                // Show the modal
                document.getElementById('story-modal').classList.add('show');
            }
        }

        function updateStory(storyId) {
            const title = document.getElementById('story-title').value;
            const description = document.getElementById('story-description').value;
            const success_date = document.getElementById('success-date').value;

            if (!title || !description || !success_date) {
                showServerMessage('localhost: Error - Please fill all required fields', 'error');
                return;
            }

            // Submit form to same page
            const form = document.createElement('form');
            form.method = 'POST';
            form.style.display = 'none';

            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = 'update_success_story';
            form.appendChild(actionInput);

            const storyIdInput = document.createElement('input');
            storyIdInput.type = 'hidden';
            storyIdInput.name = 'story_id';
            storyIdInput.value = storyId;
            form.appendChild(storyIdInput);

            const titleInput = document.createElement('input');
            titleInput.type = 'hidden';
            titleInput.name = 'title';
            titleInput.value = title;
            form.appendChild(titleInput);

            const descriptionInput = document.createElement('input');
            descriptionInput.type = 'hidden';
            descriptionInput.name = 'description';
            descriptionInput.value = description;
            form.appendChild(descriptionInput);

            const dateInput = document.createElement('input');
            dateInput.type = 'hidden';
            dateInput.name = 'success_date';
            dateInput.value = success_date;
            form.appendChild(dateInput);

            const statusInput = document.createElement('input');
            statusInput.type = 'hidden';
            statusInput.name = 'status';
            statusInput.value = 'Pending'; // Default status
            form.appendChild(statusInput);

            document.body.appendChild(form);
            form.submit();
        }

        function deleteStory(storyId) {
            if (confirm('Are you sure you want to delete this success story?')) {
                // Submit form to same page
                const form = document.createElement('form');
                form.method = 'POST';
                form.style.display = 'none';

                const actionInput = document.createElement('input');
                actionInput.type = 'hidden';
                actionInput.name = 'action';
                actionInput.value = 'delete_success_story';
                form.appendChild(actionInput);

                const storyIdInput = document.createElement('input');
                storyIdInput.type = 'hidden';
                storyIdInput.name = 'story_id';
                storyIdInput.value = storyId;
                form.appendChild(storyIdInput);

                document.body.appendChild(form);
                form.submit();
            }
        }

        // Lab Report Functions
        function openLabReportModal() {
            const modal = document.getElementById('lab-report-modal');
            modal.classList.add('show');

            // Scroll modal content to top
            const modalContent = modal.querySelector('.modal-content');
            if (modalContent) {
                modalContent.scrollTop = 0;
            }

            const headerTitle = modal.querySelector('.modal-header h3');
            if (headerTitle) headerTitle.textContent = 'Schedule Appointment';
            const submitBtn = modal.querySelector('button[onclick="saveLabReport()"]');
            if (submitBtn) submitBtn.style.display = 'inline-flex';

            document.getElementById('lab-donor-select').value = '';
            document.getElementById('lab-donor-id').value = '';
            const organEl = document.getElementById('lab-organ-id');
            if (organEl) organEl.value = '';
            document.getElementById('lab-test-type').value = '';
            renderLabTests();
            document.getElementById('lab-test-date').value = '';
            document.getElementById('lab-test-date').value = '';
            document.getElementById('lab-result-notes').value = '';
            document.getElementById('lab-blood-type').value = '';

            const fields = ['lab-donor-select', 'lab-organ-id', 'lab-recipient-patient', 'lab-test-date', 'lab-result-notes', 'lab-blood-type'];
            fields.forEach(id => {
                const el = document.getElementById(id);
                if (el) el.disabled = false;
            });

            // Load donors into dropdown
            fetch('<?php echo ROOT; ?>/hospital/search-donors?q=')
                .then(response => response.json())
                .then(donors => {
                    const donorSelect = document.getElementById('lab-donor-select');
                    donorSelect.innerHTML = '<option value="">Select a Donor</option>';

                    if (donors && Array.isArray(donors) && donors.length > 0) {
                        donors.forEach(donor => {
                            const option = document.createElement('option');
                            option.value = donor.id;
                            option.text = `${donor.nic_number || ''} - ${donor.first_name} ${donor.last_name}`;
                            donorSelect.appendChild(option);
                        });
                    }
                    console.log('Donors loaded:', donors);
                })
                .catch(error => console.error('Error loading donors:', error));

            // Load recipients into dropdown
            const recipients = <?php echo json_encode($recipients ?? []); ?>;
            const recipientSelect = document.getElementById('lab-recipient-patient');

            if (!recipientSelect) {
                console.error('Recipient select element not found');
                return;
            }

            // Clear existing options except the first one
            recipientSelect.innerHTML = '<option value="">Select Recipient Patient</option>';

            // Add recipient options
            if (recipients && Array.isArray(recipients) && recipients.length > 0) {
                recipients.forEach(recipient => {
                    const option = document.createElement('option');
                    option.value = recipient.recipient_id || '';
                    const patientNic = recipient.nic || recipient.patient_nic || '';
                    const patientName = recipient.name || recipient.patient_name || '';
                    const organ = recipient.organ_received || '';
                    option.text = `${patientNic} - ${patientName} (${organ})`;
                    recipientSelect.appendChild(option);
                });
            }

            // Handle donor selection
            const donorSelect = document.getElementById('lab-donor-select');
            if (donorSelect) {
                donorSelect.addEventListener('change', function () {
                    document.getElementById('lab-donor-id').value = this.value;
                });
            }

            // Render tests when organ changes (assign, avoid stacking listeners)
            const organSelect = document.getElementById('lab-organ-id');
            if (organSelect) {
                organSelect.onchange = function() { renderLabTests(); };
            }

            // Focus on donor select field
            setTimeout(() => {
                document.getElementById('lab-donor-select').focus();
            }, 100);
        }

        // Handle recipient patient selection
        function handleRecipientSelection() {
            const recipientSelect = document.getElementById('lab-recipient-patient');
            if (recipientSelect) {
                recipientSelect.addEventListener('change', function () {
                    document.getElementById('lab-recipient-id').value = this.value;
                    console.log('Selected recipient:', this.value);
                });
            }
        }

        // Initialize recipient selection handler after DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', handleRecipientSelection);
        } else {
            handleRecipientSelection();
        }

        function closeLabReportModal() {
            const modal = document.getElementById('lab-report-modal');
            modal.classList.remove('show');

            // Reset modal header
            modal.querySelector('.modal-header h3').textContent = 'Schedule Appointment';

            // Reset all form fields
            document.getElementById('lab-report-id').value = '';
            document.getElementById('lab-donor-select').value = '';
            document.getElementById('lab-donor-id').value = '';
            const organEl = document.getElementById('lab-organ-id');
            if (organEl) organEl.value = '';
            document.getElementById('lab-test-type').value = '';
            document.getElementById('lab_test_type_other_input').value = '';
            document.getElementById('lab_test_type_other_input').style.display = 'none';
            renderLabTests();
            document.getElementById('lab-test-date').value = '';
            document.getElementById('lab-test-date').value = '';
            document.getElementById('lab-result-notes').value = '';
            document.getElementById('lab-blood-type').value = '';
            document.getElementById('lab-recipient-patient').value = '';
            document.getElementById('lab-recipient-id').value = '';

            // Make all fields enabled for new entry
            const fields = ['lab-donor-select', 'lab-organ-id', 'lab-recipient-patient', 'lab-test-date', 'lab-result-notes', 'lab-blood-type'];
            fields.forEach(id => {
                const el = document.getElementById(id);
                if (el) el.disabled = false;
            });
        }

        // Close donor suggestions when clicking outside
        document.addEventListener('click', function (event) {
            const donorInput = document.getElementById('lab-donor-input');
            const suggestionDiv = document.getElementById('lab-donor-suggestions');

            if (donorInput && suggestionDiv) {
                if (!donorInput.contains(event.target) && !suggestionDiv.contains(event.target)) {
                    suggestionDiv.style.display = 'none';
                }
            }
        });

        function searchDonorsForLabReport() {
            const query = document.getElementById('lab-donor-input').value.trim();
            const suggestionsDiv = document.getElementById('lab-donor-suggestions');

            console.log('Searching for donors with query:', query);

            if (query.length < 1) {
                suggestionsDiv.style.display = 'none';
                document.getElementById('lab-donor-id').value = '';
                return;
            }

            // Fetch donors from API using GET with query parameter
            fetch('<?php echo ROOT; ?>/hospital/search-donors?q=' + encodeURIComponent(query), {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                },
                credentials: 'include'
            })
                .then(response => response.json())
                .then(data => {
                    console.log('API Response:', data);
                    const donors = data.data || [];

                    if (donors.length > 0) {
                        suggestionsDiv.innerHTML = donors.map((donor, index) => `
                        <div style="padding: 12px 15px; border-bottom: 1px solid #f0f0f0; cursor: pointer; background: white; transition: background-color 0.2s;" 
                             onmouseover="this.style.background='#f9f9f9'" 
                             onmouseout="this.style.background='white'"
                             onclick="selectDonorForLabReport('${donor.id}', '${donor.first_name} ${donor.last_name} (${donor.nic_number})')">
                            <div style="font-weight: 600; color: #1f2937;">${donor.first_name} ${donor.last_name}</div>
                            <div style="font-size: 0.9rem; color: #6b7280;">NIC: ${donor.nic_number}</div>
                            ${donor.blood_type ? '<div style="font-size: 0.9rem; color: #6b7280;">Blood Type: ' + donor.blood_type + '</div>' : ''}
                        </div>
                    `).join('');
                        suggestionsDiv.style.display = 'block';
                    } else {
                        suggestionsDiv.innerHTML = '<div style="padding: 12px 15px; color: #999; text-align: center;">No donors found</div>';
                        suggestionsDiv.style.display = 'block';
                    }
                })
                .catch(err => {
                    console.error('Error searching donors:', err);
                    suggestionsDiv.style.display = 'none';
                });
        }

        function selectDonorForLabReport(donorId, donorName) {
            console.log('Selected donor:', donorId, donorName);
            document.getElementById('lab-donor-input').value = donorName;
            document.getElementById('lab-donor-id').value = donorId;
            document.getElementById('lab-donor-suggestions').style.display = 'none';
            // Focus on next field
            document.getElementById('lab-test-type').focus();
        }

        function saveLabReport() {
            const reportId = document.getElementById('lab-report-id').value;
            const donorId = document.getElementById('lab-donor-id').value;
            const organId = (document.getElementById('lab-organ-id') || {}).value || '';
            let selectedTests = [];
            document.querySelectorAll('input[name="lab_test_types[]"]:checked').forEach(cb => selectedTests.push(cb.value));
            const otherCheckbox = document.getElementById('lab_test_type_other_checkbox');
            if (otherCheckbox && otherCheckbox.checked) {
                const otherVal = document.getElementById('lab_test_type_other_input').value.trim();
                if (otherVal) selectedTests.push(otherVal);
            }
            document.getElementById('lab-test-type').value = selectedTests.join(', ');

            const testType = document.getElementById('lab-test-type').value;
            const testDate = document.getElementById('lab-test-date').value;
            const resultNotes = document.getElementById('lab-result-notes').value;
            const bloodType = document.getElementById('lab-blood-type').value;

            if (!donorId) {
                showServerMessage('localhost: Error - Please select a donor', 'error');
                return;
            }

            if (!organId || !testType || !testDate) {
                showServerMessage('localhost: Error - Please fill all required fields', 'error');
                return;
            }

            // Submit form to same page
            const form = document.createElement('form');
            form.method = 'POST';
            form.style.display = 'none';

            // Determine action: edit or submit
            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = 'schedule_appointment';
            form.appendChild(actionInput);

            const donorIdInput = document.createElement('input');
            donorIdInput.type = 'hidden';
            donorIdInput.name = 'donor_id';
            donorIdInput.value = donorId;
            form.appendChild(donorIdInput);

            const organIdInput = document.createElement('input');
            organIdInput.type = 'hidden';
            organIdInput.name = 'organ_id';
            organIdInput.value = organId;
            form.appendChild(organIdInput);

            // Send selected tests as tests[]
            selectedTests.forEach(t => {
                const testInput = document.createElement('input');
                testInput.type = 'hidden';
                testInput.name = 'tests[]';
                testInput.value = t;
                form.appendChild(testInput);
            });

            const testDateInput = document.createElement('input');
            testDateInput.type = 'hidden';
            testDateInput.name = 'test_date';
            testDateInput.value = testDate;
            form.appendChild(testDateInput);

            const resultNotesInput = document.createElement('input');
            resultNotesInput.type = 'hidden';
            resultNotesInput.name = 'notes';
            resultNotesInput.value = resultNotes;
            form.appendChild(resultNotesInput);

            const bloodTypeInput = document.createElement('input');
            bloodTypeInput.type = 'hidden';
            bloodTypeInput.name = 'blood_type';
            bloodTypeInput.value = bloodType;
            form.appendChild(bloodTypeInput);

            const recipientIdInput = document.createElement('input');
            recipientIdInput.type = 'hidden';
            recipientIdInput.name = 'recipient_id';
            recipientIdInput.value = document.getElementById('lab-recipient-id').value || '';
            form.appendChild(recipientIdInput);

            document.body.appendChild(form);
            form.submit();
        }

        // Auto-filter tests list by organ selection
        function getOrganKeyFromName(name) {
            const n = String(name || '').toLowerCase().trim();
            if (!n) return 'generic';
            if (n.includes('kidney')) return 'kidney';
            if (n.includes('liver')) return 'liver';
            if (n.includes('bone marrow') || n.includes('marrow')) return 'bone_marrow';
            if (n.includes('cornea') || n.includes('skin') || n.includes('heart valve') || n.includes('valve') || n.includes('tendon') || (n === 'bones') || n.includes('tissue')) return 'tissue';
            return 'generic';
        }

        function getTestsForOrganKey(key) {
            const infectious = [
                { value: 'Infectious Disease Screening - HIV', label: 'Infectious Disease (HIV)' },
                { value: 'Infectious Disease Screening - Hepatitis B', label: 'Infectious Disease (Hepatitis B)' },
                { value: 'Infectious Disease Screening - Hepatitis C', label: 'Infectious Disease (Hepatitis C)' },
                { value: 'Infectious Disease Screening - Syphilis', label: 'Infectious Disease (Syphilis)' },
            ];

            if (key === 'kidney') {
                return [
                    { value: 'ABO Typing', label: 'ABO Typing' },
                    { value: 'HLA Typing (6 markers)', label: 'HLA Typing (6 markers)' },
                    { value: 'Crossmatch Test', label: 'Crossmatch Test' },
                    { value: 'Renal Function Tests', label: 'Renal Function Tests' },
                    ...infectious,
                ];
            }

            if (key === 'liver') {
                return [
                    { value: 'ABO Typing', label: 'ABO Typing' },
                    { value: 'Volumetric CT Scan (Liver size matching)', label: 'Volumetric CT Scan' },
                    { value: 'Liver Function Tests (LFTs)', label: 'Liver Function Tests (LFTs)' },
                    { value: 'BMI Assessment', label: 'BMI Assessment' },
                    ...infectious,
                ];
            }

            if (key === 'bone_marrow') {
                return [
                    { value: 'High-Resolution HLA Typing (10 markers)', label: 'High-Resolution HLA Typing (10 markers)' },
                    { value: 'Complete Blood Count (CBC)', label: 'Complete Blood Count (CBC)' },
                    ...infectious,
                ];
            }

            if (key === 'tissue') {
                return [
                    ...infectious,
                    { value: 'Tissue Quality Assessment', label: 'Tissue Quality Assessment' },
                    { value: 'ABO Matching (Heart Valves)', label: 'ABO Matching (Heart Valves)' },
                ];
            }

            // Generic fallback
            return [
                ...infectious,
                { value: 'Complete Blood Count (CBC)', label: 'Complete Blood Count (CBC)' },
                { value: 'Blood Pressure', label: 'Blood Pressure' },
            ];
        }

        function renderLabTests() {
            const container = document.getElementById('lab-tests-container');
            if (!container) return;

            const organSelect = document.getElementById('lab-organ-id');
            const organName = organSelect && organSelect.selectedOptions && organSelect.selectedOptions[0]
                ? organSelect.selectedOptions[0].text
                : '';

            const key = getOrganKeyFromName(organName);
            const tests = getTestsForOrganKey(key);

            container.innerHTML = '';
            tests.forEach(t => {
                const label = document.createElement('label');
                label.style.display = 'flex';
                label.style.alignItems = 'center';
                label.style.gap = '8px';

                const input = document.createElement('input');
                input.type = 'checkbox';
                input.name = 'lab_test_types[]';
                input.value = t.value;

                label.appendChild(input);
                label.appendChild(document.createTextNode(' ' + t.label));
                container.appendChild(label);
            });

            // Always include Other option
            const otherLabel = document.createElement('label');
            otherLabel.style.display = 'flex';
            otherLabel.style.alignItems = 'center';
            otherLabel.style.gap = '8px';

            const otherInput = document.createElement('input');
            otherInput.type = 'checkbox';
            otherInput.id = 'lab_test_type_other_checkbox';
            otherInput.value = 'Other';
            otherInput.onclick = function() {
                const otherText = document.getElementById('lab_test_type_other_input');
                if (otherText) otherText.style.display = this.checked ? 'block' : 'none';
            };

            otherLabel.appendChild(otherInput);
            otherLabel.appendChild(document.createTextNode(' Other'));
            container.appendChild(otherLabel);

            // Reset other input visibility
            const otherText = document.getElementById('lab_test_type_other_input');
            if (otherText) otherText.style.display = 'none';
        }

        // Test Results Upload
        function openTestResultModal() {
            const modal = document.getElementById('test-result-modal');
            modal.classList.add('show');

            document.getElementById('tr-donor-select').innerHTML = '<option value="">Select a Donor</option>';
            document.getElementById('tr-test-name').value = '';
            document.getElementById('tr-test-date').value = '';
            document.getElementById('tr-result-value').value = '';
            const file = document.getElementById('tr-document');
            if (file) file.value = '';

            fetch('<?php echo ROOT; ?>/hospital/search-donors?q=')
                .then(response => response.json())
                .then(donors => {
                    const donorSelect = document.getElementById('tr-donor-select');
                    if (donors && Array.isArray(donors) && donors.length > 0) {
                        donors.forEach(donor => {
                            const option = document.createElement('option');
                            option.value = donor.id;
                            option.text = `${donor.nic_number || ''} - ${donor.first_name} ${donor.last_name}`;
                            donorSelect.appendChild(option);
                        });
                    }
                })
                .catch(error => console.error('Error loading donors:', error));
        }

        function closeTestResultModal() {
            const modal = document.getElementById('test-result-modal');
            if (modal) modal.classList.remove('show');
        }

        function submitTestResult() {
            const donorId = document.getElementById('tr-donor-select').value;
            const testName = document.getElementById('tr-test-name').value.trim();
            const testDate = document.getElementById('tr-test-date').value;
            const resultValue = document.getElementById('tr-result-value').value.trim();
            const documentFile = document.getElementById('tr-document').files[0] || null;

            if (!donorId || !testName || !testDate) {
                alert('Please select donor, test name, and test date.');
                return;
            }

            const fd = new FormData();
            fd.append('action', 'submit_test_result');
            fd.append('donor_id', donorId);
            fd.append('test_name', testName);
            fd.append('test_date', testDate);
            fd.append('result_value', resultValue);
            if (documentFile) fd.append('document', documentFile);

            fetch(window.location.href, { method: 'POST', body: fd, credentials: 'include' })
                .then(() => window.location.reload())
                .catch(() => window.location.reload());
        }

        function deleteLabReport(reportId) {
            if (confirm('Are you sure you want to delete this lab report?')) {
                // Submit form to same page
                const form = document.createElement('form');
                form.method = 'POST';
                form.style.display = 'none';

                const actionInput = document.createElement('input');
                actionInput.type = 'hidden';
                actionInput.name = 'action';
                actionInput.value = 'delete_lab_report';
                form.appendChild(actionInput);

                const reportIdInput = document.createElement('input');
                reportIdInput.type = 'hidden';
                reportIdInput.name = 'report_id';
                reportIdInput.value = reportId;
                form.appendChild(reportIdInput);

                document.body.appendChild(form);
                form.submit();
            }
        }

        function loadLabReports() {
            // Use PHP data directly from the initial page load
            const labReports = <?php echo json_encode($lab_reports ?? []); ?>;
            // Store globally so editLabReport can access it
            window.allLabReports = labReports;
            console.log('Lab reports loaded:', window.allLabReports);
            // Do not show deleted schedules in Upcoming Appointments
            const visible = (labReports || []).filter(r => {
                const raw = (r && (r.status ?? r.result_status)) ?? '';
                return String(raw || '').trim().toLowerCase() !== 'deleted';
            });

            // Keep visible list for filtering
            window.allVisibleLabReports = visible;
            initLabReportsFilters(visible);
            applyLabReportsFilters();
        }

        function initLabReportsFilters(labReports) {
            const searchInput = document.getElementById('lab-donor-search');
            const tabsWrap = document.getElementById('lab-donor-tabs');
            if (!tabsWrap) return;

            // Build unique donors list from scheduled appointments
            const byId = new Map();
            (labReports || []).forEach(r => {
                const donorId = r && (r.donor_id ?? r.patient_id);
                const id = String(donorId ?? '').trim();
                if (!id) return;
                if (!byId.has(id)) {
                    const name = String(r.donor_name ?? '').trim();
                    const nic = String(r.donor_nic ?? '').trim();
                    byId.set(id, { id, name, nic });
                }
            });

            const donors = Array.from(byId.values()).sort((a, b) => (a.name || '').localeCompare(b.name || ''));

            window.labDonors = donors;
            if (!window.labCalState) {
                const now = new Date();
                window.labCalState = {
                    year: now.getFullYear(),
                    month: now.getMonth(),
                    selectedDate: '',
                    activeDonorId: '',
                    touched: false,
                };
            }

            // Render donor tabs
            tabsWrap.innerHTML = '';
            donors.forEach(d => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'lab-tab';
                btn.dataset.donorId = d.id;
                // Keep it simple: don't show full donor details in the tab
                btn.textContent = d.name ? d.name : `Donor ${d.id}`;
                btn.addEventListener('click', () => {
                    window.labCalState.activeDonorId = d.id;
                    window.labCalState.selectedDate = '';
                    window.labCalState.touched = false;
                    applyLabReportsFilters();
                });
                tabsWrap.appendChild(btn);
            });

            // Require explicit selection; auto-select only when there is exactly one donor
            if (!window.labCalState.activeDonorId && donors.length === 1) {
                window.labCalState.activeDonorId = donors[0].id;
            }

            // Hook search (bind once)
            if (searchInput && !searchInput.dataset.bound) {
                searchInput.addEventListener('input', applyLabReportsFilters);
                searchInput.dataset.bound = '1';
            }
        }

        function applyLabReportsFilters() {
            const labReports = window.allVisibleLabReports || [];
            const searchInput = document.getElementById('lab-donor-search');

            const state = window.labCalState || { activeDonorId: '', selectedDate: '', year: new Date().getFullYear(), month: new Date().getMonth(), touched: false };
            const donorId = String(state.activeDonorId || '').trim();

            const dateVal = String(state.selectedDate || '').trim();
            const q = searchInput ? String(searchInput.value || '').trim().toLowerCase() : '';

            // update tab active state
            const tabsWrap = document.getElementById('lab-donor-tabs');
            if (tabsWrap) {
                tabsWrap.querySelectorAll('.lab-tab').forEach(b => {
                    b.classList.toggle('active', String(b.dataset.donorId || '') === donorId);
                });
            }

            // Require donor selection (as requested)
            if (!donorId) {
                updateLabReportsTable([]);
                renderLabCalendar([]);
                renderLabCalendarDetails([], '', '');
                return;
            }

            const donorReports = labReports.filter(r => String((r && (r.donor_id ?? r.patient_id)) ?? '').trim() === donorId);

            // If calendar hasn't been navigated yet, jump to first appointment month for this donor
            const allDates = donorReports
                .map(r => String(r.test_date || '').slice(0, 10))
                .filter(d => /^\d{4}-\d{2}-\d{2}$/.test(d))
                .sort();
            if (allDates.length > 0 && state && !state.touched) {
                const first = allDates[0];
                const y = parseInt(first.slice(0, 4), 10);
                const m = parseInt(first.slice(5, 7), 10) - 1;
                if (!Number.isNaN(y) && !Number.isNaN(m) && m >= 0 && m <= 11) {
                    state.year = y;
                    state.month = m;
                }
            }

            renderLabCalendar(donorReports);

            let filtered = donorReports;

            if (dateVal) {
                filtered = filtered.filter(r => {
                    const d = String(r.test_date || '').slice(0, 10);
                    return d === dateVal;
                });
            }

            if (q) {
                filtered = filtered.filter(r => {
                    const tt = String(r.test_type || '').toLowerCase();
                    return tt.includes(q);
                });
            }

            updateLabReportsTable(filtered);

            // Render details inside the calendar card (only after selecting a date)
            const donorLabel = (window.labDonors || []).find(d => String(d.id) === donorId);
            const donorName = donorLabel ? String(donorLabel.name || '').trim() : '';
            const donorDisplay = donorName ? donorName : `Donor ${donorId}`;
            renderLabCalendarDetails(donorReports, donorDisplay, dateVal);
        }

        function escapeHtml(value) {
            return String(value ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function renderLabCalendarDetails(donorReports, donorDisplay, dateVal) {
            const container = document.getElementById('lab-cal-details');
            if (!container) return;

            const safeLabel = String(donorDisplay || '').trim();
            if (!safeLabel) {
                container.innerHTML = '';
                return;
            }

            // Only show details after user clicks a date
            if (!dateVal) {
                container.innerHTML = `
                    <div class="cal-details__title">Scheduled tests</div>
                    <div class="cal-details__hint">Click a highlighted date to see the scheduled tests for that date.</div>
                `;
                return;
            }

            const rows = (donorReports || []).filter(r => String(r.test_date || '').slice(0, 10) === dateVal);

            if (rows.length === 0) {
                container.innerHTML = `
                    <div class="cal-details__title">${escapeHtml(safeLabel)} — ${escapeHtml(dateVal)}</div>
                    <div class="cal-details__hint">No tests scheduled for this date.</div>
                `;
                return;
            }

            const listHtml = rows
                .slice()
                .sort((a, b) => String(a.test_type || '').localeCompare(String(b.test_type || '')))
                .map(r => {
                    const testType = escapeHtml(String(r.test_type || '').trim() || 'Test');
                    const testDate = escapeHtml(String(r.test_date || '').trim() || dateVal);
                    return `
                        <div class="cal-details-item">
                            <div class="cal-details-left">
                                <div class="cal-details-test">${testType}</div>
                                <div class="cal-details-sub">${testDate}</div>
                            </div>
                        </div>
                    `;
                })
                .join('');

            container.innerHTML = `
                <div class="cal-details__title">${escapeHtml(safeLabel)} — ${escapeHtml(dateVal)}</div>
                <div class="cal-details-list">${listHtml}</div>
            `;
        }

        function normalizeAptStatus(report) {
            const raw = (report && (report.status ?? report.result_status)) ?? 'Pending';
            const s = String(raw || '').trim().toLowerCase();
            if (s === 'rejected' || s === 'positive') return 'rejected';
            if (s === 'pending') return 'pending';
            if (s === 'approved' || s === 'negative' || s === 'active') return 'approved';
            return 'pending';
        }

        function dateToIso(dateObj) {
            const y = dateObj.getFullYear();
            const m = String(dateObj.getMonth() + 1).padStart(2, '0');
            const d = String(dateObj.getDate()).padStart(2, '0');
            return `${y}-${m}-${d}`;
        }

        function renderLabCalendar(donorReports) {
            const grid = document.getElementById('lab-cal-grid');
            const title = document.getElementById('lab-cal-title');
            const state = window.labCalState;
            if (!grid || !title || !state) return;

            const year = state.year;
            const month = state.month;
            const monthNames = ['January','February','March','April','May','June','July','August','September','October','November','December'];
            title.textContent = `${monthNames[month]} ${year}`;

            const todayIso = dateToIso(new Date());
            const selectedIso = String(state.selectedDate || '').trim();

            // Build appointment presence per date (Hospital: single highlight color)
            const byDate = new Set();
            (donorReports || []).forEach(r => {
                const iso = String(r.test_date || '').slice(0, 10);
                if (!/^\d{4}-\d{2}-\d{2}$/.test(iso)) return;
                byDate.add(iso);
            });

            // Calendar structure: day headers + blanks + days
            grid.innerHTML = '';
            ['Su','Mo','Tu','We','Th','Fr','Sa'].forEach(d => {
                const hdr = document.createElement('div');
                hdr.className = 'cal-day-hdr';
                hdr.textContent = d;
                grid.appendChild(hdr);
            });

            const first = new Date(year, month, 1);
            const startDow = first.getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();

            for (let i = 0; i < startDow; i++) {
                const blank = document.createElement('div');
                blank.className = 'cal-day';
                blank.style.visibility = 'hidden';
                grid.appendChild(blank);
            }

            for (let day = 1; day <= daysInMonth; day++) {
                const mm = String(month + 1).padStart(2, '0');
                const dd = String(day).padStart(2, '0');
                const iso = `${year}-${mm}-${dd}`;
                const has = byDate.has(iso);
                const cls = has ? 'apt-blue' : '';

                const cell = document.createElement('div');
                cell.className = ['cal-day', cls, has ? 'clickable' : ''].filter(Boolean).join(' ');
                if (iso === todayIso) cell.classList.add('is-today');
                if (selectedIso && iso === selectedIso) cell.classList.add('is-selected');
                cell.innerHTML = `<span>${day}</span>`;

                if (has) {
                    cell.addEventListener('click', () => {
                        state.selectedDate = (state.selectedDate === iso) ? '' : iso;
                        applyLabReportsFilters();
                    });
                }
                grid.appendChild(cell);
            }
        }

        function labCalPrev() {
            const state = window.labCalState;
            if (!state) return;
            state.touched = true;
            state.month -= 1;
            if (state.month < 0) { state.month = 11; state.year -= 1; }
            applyLabReportsFilters();
        }

        function labCalNext() {
            const state = window.labCalState;
            if (!state) return;
            state.touched = true;
            state.month += 1;
            if (state.month > 11) { state.month = 0; state.year += 1; }
            applyLabReportsFilters();
        }

        function updateLabReportsTable(labReports) {
            const tableContent = document.querySelector('#lab-reports-table');
            if (!tableContent) return;

            const normalizeStatus = (report) => {
                // Backwards-compatible: some code paths used `result_status`, but DB uses `status`.
                const raw = (report && (report.status ?? report.result_status)) ?? 'Pending';
                const str = String(raw || '').trim();
                return str !== '' ? str : 'Pending';
            };

            const statusClass = (status) => {
                const s = String(status || '').toLowerCase();
                if (s === 'approved' || s === 'negative' || s === 'active') return 'status-success';
                if (s === 'rejected' || s === 'positive') return 'status-danger';
                return 'status-pending';
            };

            // Clear existing rows (except header)
            const existingRows = tableContent.querySelectorAll('.table-row:not(:first-child)');
            existingRows.forEach(row => row.remove());

            if (labReports.length === 0) {
                const row = document.createElement('div');
                row.className = 'table-row';
                row.innerHTML = '<div style="text-align: center; padding: 20px; color: #999; grid-column: 1/-1;">No lab reports found</div>';
                tableContent.appendChild(row);
                return;
            }

            // Add new rows
            labReports.forEach(report => {
                const status = normalizeStatus(report);
                const row = document.createElement('div');
                row.className = 'table-row';
                row.innerHTML = `
                    <div class="table-cell" data-label="Patient ID">${report.patient_id}</div>
                    <div class="table-cell" data-label="Donor NIC">${report.donor_nic}</div>
                    <div class="table-cell name" data-label="Donor Name">${report.donor_name}</div>
                    <div class="table-cell" data-label="Test Type">${report.test_type}</div>
                    <div class="table-cell" data-label="Test Date">${new Date(report.test_date).toLocaleDateString('en-GB')}</div>
                    <div class="table-cell" data-label="Result Status">
                        <span class="status-badge ${statusClass(status)}">${status}</span>
                    </div>
                    <div class="table-cell" data-label="Actions" style="display: flex; gap: 0.2rem; align-items: center; flex-wrap: wrap;">
                        <button class="btn btn-secondary btn-small" onclick="editLabReport(${report.id})" style="padding: 4px 8px; font-size: 0.75rem;">Edit</button>
                        <button class="btn btn-danger btn-small" onclick="deleteLabReport(${report.id})" style="padding: 4px 8px; font-size: 0.75rem;">Delete</button>
                    </div>
                `;
                tableContent.appendChild(row);
            });
        }

        function approveLabReport(reportId) {
            if (confirm('Are you sure you want to approve this lab report results?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.style.display = 'none';
                
                const actionInput = document.createElement('input');
                actionInput.type = 'hidden'; actionInput.name = 'action'; actionInput.value = 'update_lab_report_status';
                form.appendChild(actionInput);
                
                const idInput = document.createElement('input');
                idInput.type = 'hidden'; idInput.name = 'report_id'; idInput.value = reportId;
                form.appendChild(idInput);
                
                const statusInput = document.createElement('input');
                statusInput.type = 'hidden'; statusInput.name = 'status'; statusInput.value = 'Negative'; // Clear
                form.appendChild(statusInput);
                
                document.body.appendChild(form);
                form.submit();
            }
        }

        function rejectLabReport(reportId) {
            if (confirm('Are you sure you want to reject this lab report results?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.style.display = 'none';
                
                const actionInput = document.createElement('input');
                actionInput.type = 'hidden'; actionInput.name = 'action'; actionInput.value = 'update_lab_report_status';
                form.appendChild(actionInput);
                
                const idInput = document.createElement('input');
                idInput.type = 'hidden'; idInput.name = 'report_id'; idInput.value = reportId;
                form.appendChild(idInput);
                
                const statusInput = document.createElement('input');
                statusInput.type = 'hidden'; statusInput.name = 'status'; statusInput.value = 'Positive'; // Infected
                form.appendChild(statusInput);
                
                document.body.appendChild(form);
                form.submit();
            }
        }

        function editLabReport(reportId) {
            try {
                console.log('Editing lab report:', reportId);

                // Get all reports from the current page data - use global variable
                const labReports = window.allLabReports || [];
                console.log('Available reports:', labReports);

                // Find the specific report
                const report = labReports.find(r => r.id == reportId);
                console.log('Found report:', report);

                if (!report) {
                    console.error('Report not found');
                    alert('Report not found');
                    return;
                }

                // Show modal
                const modal = document.getElementById('lab-report-modal');
                if (!modal) {
                    console.error('Modal not found');
                    return;
                }

                modal.classList.add('show');

                // Update modal header
                const header = modal.querySelector('.modal-header h3');
                if (header) header.textContent = 'Edit Lab Report';

                // Store report ID for editing
                const reportIdField = document.getElementById('lab-report-id');
                if (reportIdField) reportIdField.value = reportId;

                // Populate donor select - ensure the option exists
                const donorSelect = document.getElementById('lab-donor-select');
                if (donorSelect) {
                    // Check if option exists, if not, add it
                    if (report.donor_id && !Array.from(donorSelect.options).some(opt => opt.value == report.donor_id)) {
                        const option = document.createElement('option');
                        option.value = report.donor_id;
                        option.text = `${report.donor_nic || ''} - ${report.donor_name || 'Unknown'}`;
                        donorSelect.appendChild(option);
                    }
                    donorSelect.value = report.donor_id || '';
                }

                // Populate recipient select
                const recipientSelect = document.getElementById('lab-recipient-patient');
                if (recipientSelect) {
                    recipientSelect.value = report.recipient_id || '';
                }

                // Populate test type
                const testTypeSelect = document.getElementById('lab-test-type');
                if (testTypeSelect) {
                    testTypeSelect.value = report.test_type || '';
                    document.querySelectorAll('input[name="lab_test_types[]"]').forEach(cb => cb.checked = false);
                    document.getElementById('lab_test_type_other_input').value = '';
                    document.getElementById('lab_test_type_other_input').style.display = 'none';
                    const otherCb = document.getElementById('lab_test_type_other_checkbox');
                    if (otherCb) otherCb.checked = false;

                    if (report.test_type) {
                        let otherTests = [];
                        report.test_type.split(', ').forEach(t => {
                            let cb = document.querySelector(`input[name="lab_test_types[]"][value="${t}"]`);
                            if (cb) cb.checked = true;
                            else if (t.trim() !== '') {
                                otherTests.push(t);
                                if (otherCb) otherCb.checked = true;
                                document.getElementById('lab_test_type_other_input').style.display = 'block';
                            }
                        });
                        if (otherTests.length > 0) {
                            document.getElementById('lab_test_type_other_input').value = otherTests.join(', ');
                        }
                    }
                }

                // Populate test date
                const testDateInput = document.getElementById('lab-test-date');
                if (testDateInput) {
                    testDateInput.value = report.test_date ? report.test_date.split(' ')[0] : '';
                }

                // Populate result status


                // Populate result notes
                const resultNotesInput = document.getElementById('lab-result-notes');
                if (resultNotesInput) {
                    resultNotesInput.value = report.result_notes || '';
                }

                // Populate blood type
                const bloodTypeSelect = document.getElementById('lab-blood-type');
                if (bloodTypeSelect) {
                    bloodTypeSelect.value = report.blood_type || '';
                }

                // Scroll to top
                const modalContent = modal.querySelector('.modal-content');
                if (modalContent) {
                    modalContent.scrollTop = 0;
                }

                console.log('Modal opened and form populated');
            } catch (error) {
                console.error('Error in editLabReport:', error);
                alert('Error opening edit form: ' + error.message);
            }
        }

        // Search lab reports and recipients
        document.addEventListener('DOMContentLoaded', function () {
            // Lab Reports Search
            const labSearchInput = document.getElementById('lab-donor-search');
            if (labSearchInput) {
                labSearchInput.addEventListener('keyup', function () {
                    const query = this.value.toLowerCase();
                    const rows = document.querySelectorAll('#lab-reports-table .table-row:not(:first-child)');

                    rows.forEach(row => {
                        const nic = row.querySelector('[data-label="Donor NIC"]')?.textContent.toLowerCase() || '';
                        const name = row.querySelector('[data-label="Donor Name"]')?.textContent.toLowerCase() || '';
                        const testType = row.querySelector('[data-label="Test Type"]')?.textContent.toLowerCase() || '';

                        if (nic.includes(query) || name.includes(query) || testType.includes(query)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            }

            // Recipient Patients Search
            const recipientSearchInput = document.getElementById('recipient-search');
            if (recipientSearchInput) {
                recipientSearchInput.addEventListener('keyup', function () {
                    const query = this.value.toLowerCase();
                    const rows = document.querySelectorAll('#recipients-table .table-row:not(:first-child)');

                    rows.forEach(row => {
                        const nic = row.querySelector('[data-label="NIC"]')?.textContent.toLowerCase() || '';
                        const name = row.querySelector('[data-label="Name"]')?.textContent.toLowerCase() || '';
                        const organ = row.querySelector('[data-label="Organ"]')?.textContent.toLowerCase() || '';

                        if (nic.includes(query) || name.includes(query) || organ.includes(query)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            }
        });

        function loadStories() {
            // Use PHP data directly
            const stories = <?php echo json_encode($success_stories); ?>;
            // Load stories data
            updateStoriesTable(stories);
        }

        function updateStoriesTable(stories) {
            const tableContent = document.querySelector('#stories .table-content');
            if (!tableContent) return;

            // Clear existing rows (except header)
            const existingRows = tableContent.querySelectorAll('.table-row:not(:first-child)');
            existingRows.forEach(row => row.remove());

            // Add new rows
            stories.forEach(story => {
                const row = document.createElement('div');
                row.className = 'table-row';
                row.innerHTML = `
                    <div class="table-cell name" data-label="Story Title">${story.title}</div>
                    <div class="table-cell" data-label="Description">${story.description.substring(0, 100)}${story.description.length > 100 ? '...' : ''}</div>
                    <div class="table-cell" data-label="Date">${new Date(story.success_date).toLocaleDateString('en-GB')}</div>
                    <div class="table-cell" data-label="Status" style="text-align:center;">
                        <span class="status-badge ${story.status === 'Approved' ? 'status-success' : story.status === 'Pending' ? 'status-pending' : 'status-danger'}">${story.status}</span>
                    </div>
                    <div class="table-cell" data-label="Actions">
                        <div class="table-actions">
                            <button class="btn btn-secondary btn-small" onclick="editStory(${story.story_id})">Edit</button>
                            <button class="btn btn-danger btn-small" onclick="deleteStory(${story.story_id})">Delete</button>
                        </div>
                    </div>
                `;
                tableContent.appendChild(row);
            });
        }

        function showServerMessage(message, type) {
            // Remove any existing notifications to prevent stacking
            const existingNotifications = document.querySelectorAll('.server-notification');
            existingNotifications.forEach(notification => notification.remove());

            const n = document.createElement('div');
            n.className = 'server-notification';
            n.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${type === 'success' ? 'linear-gradient(135deg, #10b981, #059669)' :
                    type === 'error' ? 'linear-gradient(135deg, #ef4444, #dc2626)' :
                        type === 'info' ? 'linear-gradient(135deg, #3b82f6, #2563eb)' :
                            'linear-gradient(135deg, #f59e0b, #d97706)'};
                color: white;
                padding: 16px 24px;
                border-radius: 12px;
                box-shadow: 0 10px 25px rgba(0,0,0,0.2), 0 4px 12px rgba(0,0,0,0.1);
                z-index: 10000;
                font-weight: 600;
                font-size: 14px;
                max-width: 350px;
                word-wrap: break-word;
                transform: translateX(120%);
                transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                border: 1px solid rgba(255,255,255,0.2);
                backdrop-filter: blur(10px);
                cursor: pointer;
            `;

            // Add close button
            n.innerHTML = `
                <div style="display: flex; align-items: center; gap: 12px; position: relative;">
                    <div style="display: flex; align-items: center; gap: 8px; flex: 1;">
                        <span style="font-size: 18px; filter: drop-shadow(0 1px 2px rgba(0,0,0,0.3));">
                            ${type === 'success' ? '✅' : type === 'error' ? '❌' : type === 'info' ? 'ℹ️' : '⚠️'}
                        </span>
                        <span style="text-shadow: 0 1px 2px rgba(0,0,0,0.3);">${message}</span>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" 
                            style="background: rgba(255,255,255,0.2); border: none; color: white; 
                                   border-radius: 50%; width: 24px; height: 24px; cursor: pointer; 
                                   display: flex; align-items: center; justify-content: center; 
                                   font-size: 12px; font-weight: bold; transition: background 0.2s;">
                        ×
                    </button>
                </div>
            `;

            document.body.appendChild(n);

            // Animate in
            requestAnimationFrame(() => {
                n.style.transform = 'translateX(0)';
                n.style.opacity = '1';
            });

            // Auto-hide after 3 seconds
            setTimeout(() => {
                n.style.transform = 'translateX(120%)';
                n.style.opacity = '0';
                setTimeout(() => n.remove(), 400);
            }, 3000);

            // Add hover effect
            n.addEventListener('mouseenter', () => {
                n.style.transform = 'translateX(0) scale(1.02)';
                n.style.boxShadow = '0 15px 35px rgba(0,0,0,0.3), 0 6px 16px rgba(0,0,0,0.15)';
            });

            n.addEventListener('mouseleave', () => {
                n.style.transform = 'translateX(0) scale(1)';
                n.style.boxShadow = '0 10px 25px rgba(0,0,0,0.2), 0 4px 12px rgba(0,0,0,0.1)';
            });
        }

        function notify(message, type) {
            showServerMessage(message, type);
        }

        // User dropdown functions
        function toggleUserDropdown() {
            const dropdown = document.getElementById('user-dropdown');
            dropdown.classList.toggle('show');
        }

        function editProfile() {
            document.getElementById('profile-modal').classList.add('show');
        }
        function closeProfileModal() {
            document.getElementById('profile-modal').classList.remove('show');
        }
        function saveProfile() {
            const name = document.getElementById('profile-name').value;
            const address = document.getElementById('profile-address').value;
            const phone = document.getElementById('profile-phone').value;

            if (!name) {
                showServerMessage('Hospital Name is required', 'error');
                return;
            }

            const form = document.createElement('form');
            form.method = 'POST';
            form.style.display = 'none';

            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = 'update_hospital_profile';
            form.appendChild(actionInput);

            const nameInput = document.createElement('input');
            nameInput.type = 'hidden';
            nameInput.name = 'hospital_name';
            nameInput.value = name;
            form.appendChild(nameInput);

            const addressInput = document.createElement('input');
            addressInput.type = 'hidden';
            addressInput.name = 'address';
            addressInput.value = address;
            form.appendChild(addressInput);

            const phoneInput = document.createElement('input');
            phoneInput.type = 'hidden';
            phoneInput.name = 'phone';
            phoneInput.value = phone;
            form.appendChild(phoneInput);

            document.body.appendChild(form);
            form.submit();
        }

        function logout() {
            if (confirm('Are you sure you want to logout?')) {
                showServerMessage('Logging out...', 'info');
                // Close dropdown
                document.getElementById('user-dropdown').classList.remove('show');

                // Redirect to actual logout route
                setTimeout(() => {
                    window.location.href = '<?php echo ROOT; ?>/logout';
                }, 500);
            }
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function (event) {
            const userInfo = document.querySelector('.user-info');
            const dropdown = document.getElementById('user-dropdown');

            if (!userInfo.contains(event.target)) {
                dropdown.classList.remove('show');
            }
        });

        // Show notifications based on URL parameters
        document.addEventListener('DOMContentLoaded', function () {
            const urlParams = new URLSearchParams(window.location.search);
            const success = urlParams.get('success');
            const error = urlParams.get('error');

            if (success) {
                let message = '';
                let type = 'success';

                switch (success) {
                    case 'organ_request_added':
                        message = 'Organ request added successfully!';
                        break;
                    case 'recipient_added':
                        message = 'Recipient added successfully!';
                        break;
                    case 'recipient_updated':
                        message = 'Recipient updated successfully!';
                        break;
                    case 'recipient_deleted':
                        message = 'Recipient deleted successfully!';
                        break;
                    case 'story_added':
                        message = 'Success story added successfully! Stats will be updated.';
                        break;
                    case 'story_updated':
                        message = 'Success story updated successfully!';
                        break;
                    case 'story_deleted':
                        message = 'Success story deleted successfully!';
                        break;
                }

                if (message) {
                    showServerMessage(message, type);
                }
            }

            if (error) {
                let message = '';
                let type = 'error';

                switch (error) {
                    case 'organ_request_failed':
                        message = 'Failed to add organ request!';
                        break;
                    case 'recipient_failed':
                        message = 'Failed to add recipient!';
                        break;
                    case 'recipient_update_failed':
                        message = 'Failed to update recipient!';
                        break;
                    case 'recipient_delete_failed':
                        message = 'Failed to delete recipient!';
                        break;
                    case 'story_failed':
                        message = 'Failed to add success story!';
                        break;
                }

                if (message) {
                    showServerMessage(message, type);
                }
            }

            // Clean URL to remove parameters
            if (success || error) {
                const newUrl = window.location.pathname;
                window.history.replaceState({}, document.title, newUrl);
            }
        });

        // Initialize
        showContent('overview');

        // Load initial data
        document.addEventListener('DOMContentLoaded', function () {
            loadOrganRequests();
            loadRecipients();
            loadStories();
        });

        // Function to refresh all data
        function refreshAllData() {
            // Reload the page to get fresh data from database
            window.location.reload();
        }

        // Export Functions
        function exportRecipients() {
            document.getElementById('export-modal').classList.add('show');
        }

        function closeExportModal() {
            document.getElementById('export-modal').classList.remove('show');
            document.getElementById('export-format').value = '';
        }

        function downloadExport() {
            const format = document.getElementById('export-format').value;

            if (!format) {
                showServerMessage('Please select an export format', 'error');
                return;
            }

            // Close modal
            closeExportModal();

            // Show loading message
            showServerMessage('Preparing export file...', 'info');

            const recipients = <?php echo json_encode($recipients ?? []); ?>;

            setTimeout(() => {
                if (format === 'csv') {
                    let csvContent = "NIC,Name,Organ Received,Surgery Date,Treatment Notes,Status\n";
                    recipients.forEach(r => {
                        const row = [
                            `"${(r.nic || '').replace(/"/g, '""')}"`,
                            `"${(r.name || '').replace(/"/g, '""')}"`,
                            `"${(r.organ_received || '').replace(/"/g, '""')}"`,
                            `"${(r.surgery_date || '').replace(/"/g, '""')}"`,
                            `"${(r.treatment_notes || '').replace(/"/g, '""')}"`,
                            `"${(r.status || '').replace(/"/g, '""')}"`
                        ];
                        csvContent += row.join(",") + "\n";
                    });

                    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
                    const link = document.createElement("a");
                    link.href = URL.createObjectURL(blob);
                    link.download = "Recipient_Records.csv";
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    showServerMessage('Export file (Recipient_Records.csv) downloaded successfully!', 'success');

                } else if (format === 'xlsx') {
                    let html = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
                    html += '<head><meta charset="utf-8"></head><body><table>';
                    html += '<tr><th>NIC</th><th>Name</th><th>Organ Received</th><th>Surgery Date</th><th>Treatment Notes</th><th>Status</th></tr>';
                    recipients.forEach(r => {
                        html += `<tr><td>${r.nic}</td><td>${r.name}</td><td>${r.organ_received}</td><td>${r.surgery_date}</td><td>${r.treatment_notes}</td><td>${r.status}</td></tr>`;
                    });
                    html += '</table></body></html>';

                    const blob = new Blob([html], { type: 'application/vnd.ms-excel' });
                    const link = document.createElement("a");
                    link.href = URL.createObjectURL(blob);
                    link.download = "Recipient_Records.xls";
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    showServerMessage('Export file (Recipient_Records.xls) downloaded successfully!', 'success');

                } else if (format === 'pdf') {
                    let printWin = window.open('', '_blank');
                    const logoUrl = '<?php echo ROOT; ?>/public/assets/images/logo.png';
                    let html = '<html><head><meta charset="utf-8"><title>Recipient Records Report</title>';
                    html += '<style>';
                    html += 'body{margin:0;padding:24px;font-family:Arial,sans-serif;color:#111827;}';
                    html += '.watermark{position:fixed;inset:0;z-index:0;display:flex;align-items:center;justify-content:center;pointer-events:none;}';
                    html += '.watermark img{width:520px;max-width:85%;opacity:.06;}';
                    html += '.watermark .wm-text{position:absolute;font-size:64px;font-weight:700;color:#9ca3af;opacity:.10;transform:rotate(-25deg);text-align:center;letter-spacing:2px;}';
                    html += '.content{position:relative;z-index:1;}';
                    html += '.report-header{display:flex;align-items:center;gap:14px;margin-bottom:16px;}';
                    html += '.report-header img{height:52px;width:auto;}';
                    html += '.report-title{margin:0;font-size:20px;font-weight:700;color:#111827;line-height:1.2;}';
                    html += '.report-sub{margin:2px 0 0;font-size:12px;color:#6b7280;}';
                    html += 'table{width:100%;border-collapse:collapse;font-family:Arial,sans-serif;}';
                    html += 'th,td{border:1px solid #e5e7eb;padding:8px;text-align:left;font-size:12px;}';
                    html += 'th{background-color:#f3f4f6;font-weight:700;}';
                    html += '@media print{body{padding:0} .watermark{display:flex}}';
                    html += '</style>';
                    html += '</head><body>';
                    html += '<div class="watermark"><img src="'+logoUrl+'" alt="LifeConnect"><div class="wm-text">LifeConnect Sri Lanka</div></div>';
                    html += '<div class="content">';
                    html += '<div class="report-header">';
                    html += '<img src="'+logoUrl+'" alt="LifeConnect">';
                    html += '<div><div class="report-title">Recipient Patients Report</div><div class="report-sub">LifeConnect Sri Lanka</div></div>';
                    html += '</div>';
                    html += '<table>';
                    html += '<tr><th>NIC</th><th>Name</th><th>Organ Received</th><th>Surgery Date</th><th>Status</th></tr>';
                    recipients.forEach(r => {
                        html += `<tr><td>${r.nic}</td><td>${r.name}</td><td>${r.organ_received}</td><td>${r.surgery_date}</td><td>${r.status}</td></tr>`;
                    });
                    html += '</table>';
                    html += '</div></body></html>';
                    printWin.document.write(html);
                    printWin.document.close();
                    printWin.focus();

                    // Small delay to ensure it renders before printing
                    setTimeout(() => {
                        printWin.print();
                        showServerMessage('Print dialog opened for PDF export', 'success');
                    }, 500);
                }
            }, 800);
        }

    </script>

    <!-- Footer -->
    <footer
        style="background: linear-gradient(135deg, #005baa 0%, #003b6e 100%); color: white; text-align: center; padding: 20px; margin-top: 40px; box-shadow: 0 -4px 20px rgba(0, 91, 170, 0.2);">
        <p style="margin: 0; font-size: 14px;">Copyright © 2025 Ministry of Health - LifeConnect Sri Lanka</p>
    </footer>
</body>

</html>