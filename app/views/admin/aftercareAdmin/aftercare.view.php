<?php
// Start session if not already started - MUST be before any HTML output
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get admin user information from session
$admin_user_id = $_SESSION['user_id'] ?? null;
$admin_username = $_SESSION['username'] ?? 'Admin User';
$admin_role = $_SESSION['role'] ?? 'Aftercare Administrator';

// Prepare admin details for header
$admin_full_name = htmlspecialchars($admin_username);
$admin_role_display = htmlspecialchars($admin_role);
$admin_id_display = htmlspecialchars($admin_user_id ?? 'N/A');
$admin_email = $_SESSION['email'] ?? 'admin@lifeconnect.lk';
$admin_status = 'Active';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aftercare Admin | LifeConnect</title>
    <link rel="stylesheet" href="<?= ROOT ?>/public/assets/css/style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= ROOT ?>/public/assets/css/admin/style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" />
    <style>
        body {
            background-color: #f8fafc;
            min-height: 100vh;
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

        .stats-grid {
            margin-top: 20px;
        }

        /* Review Section Styling */
        .review-section {
            background: #f8fafc;
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid #e2e8f0;
        }

        .review-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        .review-item {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .review-label {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
        }

        .review-value {
            font-size: 0.95rem;
            font-weight: 600;
            color: #1e293b;
        }

        /* Table Styles - Matching User Management */
        .data-table {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border: 1px solid #e2e8f0;
        }

        .table-header {
            background: #f8fafc;
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .table-header h4 {
            margin: 0;
            color: #003b6e;
            font-weight: 700;
        }

        .table-row {
            display: flex;
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #f1f5f9;
            align-items: center;
            transition: background 0.2s ease;
        }

        .table-row:hover:not(.header-row) {
            background: #f8fafc;
        }

        .header-row {
            background: #f8fafc;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
        }

        .table-cell {
            flex: 1;
        }
    </style>
</head>
<body>
    <script>
        const ROOT = "<?= ROOT ?>";
    </script>
    <script src="<?= ROOT ?>/public/assets/js/admin/aftercare.js" defer></script>
    <div class="header">
        <div class="header-content">
            <div class="header-left">
                <a href="<?= ROOT ?>" style="text-decoration:none; display:flex; align-items:center; gap:10px;">
                    <img src="<?= ROOT ?>/public/assets/images/logo.png" alt="LifeConnect" style="height:40px;">
                    <div>
                        <strong style="display:block; font-size:1.1rem; color:#003b6e; line-height:1.2;">LifeConnect</strong>
                        <p style="margin:0; font-size:.68rem; color:#6b7280;">Aftercare Administration</p>
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
                    <div class="user-avatar"><?php echo strtoupper(substr($admin_full_name, 0, 1)); ?></div>
                    <div class="user-details">
                        <span class="user-name"><?php echo $admin_full_name; ?></span>
                        <span class="user-role"><?php echo $admin_role_display; ?></span>
                    </div>
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
                    <div class="menu-section-title">Patient Care</div>
                    <a href="javascript:void(0)" class="menu-item" onclick="showContent('support-requests', this)">
                        <span class="icon"><i class="fa-solid fa-hand-holding-heart"></i></span>
                        <span>Support Requests</span>
                    </a>
                </div>

                <div class="menu-section mt-auto">
                    <a href="javascript:void(0)" onclick="logout()" class="menu-item text-danger">
                        <span class="icon"><i class="fa-solid fa-right-from-bracket"></i></span>
                        <span>Logout</span>
                    </a>
                </div>
            </div>

        <!-- Content Area -->
        <div class="content-area" id="content-area">
            <!-- Dashboard Overview -->
            <div id="dashboard" class="content-section dashboard-page">
                <div class="content-body" style="padding-top: 0;">

                    <div class="stats-grid dashboard-metrics">
                        <div class="stat-card glass-card">
                            <div class="stat-number quick-stat-number" id="dashboard-total-requests"><?= $stats['total'] ?? 0 ?></div>
                            <div class="stat-label">Total Support Requests</div>
                        </div>
                        <div class="stat-card glass-card">
                            <div class="stat-number quick-stat-number" id="dashboard-pending"><?= $stats['pending'] ?? 0 ?></div>
                            <div class="stat-label">Pending Approval</div>
                        </div>
                        <div class="stat-card glass-card">
                            <div class="stat-number quick-stat-number" id="dashboard-total-patients"><?= $stats['total_patients'] ?? 0 ?></div>
                            <div class="stat-label">Total Aftercare Patients</div>
                        </div>
                        <div class="stat-card glass-card">
                            <div class="stat-number" id="dashboard-recipients" style="color: #005baa;"><?= $stats['recipient_patients'] ?? 0 ?></div>
                            <div class="stat-label">Recipient Patients</div>
                        </div>
                        <div class="stat-card glass-card">
                            <div class="stat-number" id="dashboard-donors" style="color: #059669;"><?= $stats['donor_patients'] ?? 0 ?></div>
                            <div class="stat-label">Donor Patients</div>
                        </div>
                        <div class="stat-card glass-card">
                            <div class="stat-number" id="dashboard-avg-age"><?= $stats['average_age'] ?? 0 ?></div>
                            <div class="stat-label">Average Patient Age</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Support Requests -->
            <div id="support-requests" class="content-section" style="display: none;">
                <div class="content-header">
                    <h2>Support Requests</h2>
                    <p>Manage and review patient support applications and medical assistance requests.</p>
                </div>
                <div class="content-body">
                    <div style="display: flex; gap: 16px; align-items: center; margin-bottom: 24px; padding-top: 2rem;">
                        <div class="search-bar" style="margin-bottom: 0; flex: 1;">
                            <span class="search-icon">🔍</span>
                            <input type="text" class="search-input" placeholder="Search by Patient ID, Registration No, or Description..." id="support-search">
                        </div>

                        <div class="filter-section" style="margin-bottom: 0; display: flex; gap: 12px;">
                            <select class="filter-select" id="status-filter">
                                <option value="">All Status</option>
                                <option value="pending">Pending</option>
                                <option value="verified">Verified</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                    </div>

                    <div class="stats-grid dashboard-metrics" style="grid-template-columns: repeat(4, 1fr); margin-bottom: 2rem;">
                        <div class="stat-card glass-card">
                            <div class="stat-number quick-stat-number" id="total-requests"><?= $stats['total'] ?></div>
                            <div class="stat-label">Total Requests</div>
                        </div>
                        <div class="stat-card glass-card">
                            <div class="stat-number quick-stat-number" id="pending-requests"><?= $stats['pending'] ?></div>
                            <div class="stat-label">Pending</div>
                        </div>
                        <div class="stat-card glass-card">
                            <div class="stat-number quick-stat-number" id="approved-requests"><?= $stats['approved'] ?></div>
                            <div class="stat-label">Approved</div>
                        </div>
                        <div class="stat-card glass-card">
                            <div class="stat-number quick-stat-number" id="confirmed-requests"><?= $stats['rejected'] ?></div>
                            <div class="stat-label">Rejected</div>
                        </div>
                    </div>

                    <div class="data-table" style="background: white; border-radius: 24px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05); width: 100%;">
                        <div class="table-header" style="background: #f8fafc; border-bottom: 2px solid #e2e8f0; padding: 1.5rem 2rem;">
                            <h4 style="font-size: 1.25rem; font-weight: 700; color: #1e293b; margin: 0;">Support Requests Review</h4>
                        </div>
                        <div class="table-content" id="support-requests-table" style="width: 100%;">
                            <div class="table-row" style="font-weight: 700; background: #f1f5f9; border-bottom: 1px solid #e2e8f0; color: #475569; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.05em; display: flex; width: 100%;">
                                <div class="table-cell" style="padding: 1rem 1.5rem; flex: 2;">Patient Contact</div>
                                <div class="table-cell" style="padding: 1rem 1.5rem; flex: 2;">Request Type / Reason</div>
                                <div class="table-cell" style="padding: 1rem 1.5rem; flex: 1;">Amount Requested</div>
                                <div class="table-cell" style="padding: 1rem 1.5rem; flex: 1;">Submitted Date</div>
                                <div class="table-cell" style="padding: 1rem 1.5rem; flex: 1.5;">Verification Status</div>
                            </div>
                            <?php include 'requests.view.php'; ?>
                        </div>
                    </div>
                </div>
            </div>

            </div>
        </div>
    </div>
        </div>
    </div>
</div>

<!-- Support Request Details Modal (Premium Alignment) -->
<div id="supportModal" class="modal">
    <div class="modal-content">
        <!-- Modal Scroll Area -->
        <div class="modal-scroll-area">
            <!-- Modal Header with Icon -->
            <div style="display: flex; flex-direction: column; gap: 1.25rem; position: relative;">
                <button type="button" class="modal-close" style="position: absolute; top: -15px; right: -15px; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; border-radius: 50%; background: #f1f5f9; border: none; z-index: 100;" onclick="closeSupportModal()">&times;</button>
                
                <div style="display: flex; align-items: center; gap: 1.25rem;">
                    <!-- Status Icon -->
                    <div id="modal-status-icon-box" style="flex-shrink: 0; width: 48px; height: 48px; background: #fee2e2; border-radius: 12px; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease;">
                        <i id="modal-status-icon" class="fa-solid fa-circle-xmark" style="font-size: 20px; color: #dc2626;"></i>
                    </div>

                    <!-- Title -->
                    <div>
                        <h2 style="margin: 0; font-size: 1.5rem; font-weight: 800; color: #0f172a; line-height: 1.2;">Review Support</h2>
                        <span id="modal-status-badge" style="font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; margin-top: 4px; display: inline-block;">PENDING</span>
                    </div>
                </div>

                <p style="margin: 0; color: #64748b; font-size: 0.9rem; line-height: 1.5; font-weight: 500;">Review the financial aid request details below and authorize the verification status for forwarding.</p>

                <!-- Grid Summary Card (#f0f7ff) -->
                <div style="background: #f0f7ff; border-radius: 16px; padding: 1.5rem; display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; border: 1px solid #e0f2fe;">
                    <div>
                        <span style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Request ID</span>
                        <div id="modal-request-id" style="font-size: 1.05rem; font-weight: 700; color: #1e293b;">-</div>
                    </div>
                    <div>
                        <span style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Submission Date</span>
                        <div id="modal-date" style="font-size: 0.95rem; font-weight: 600; color: #334155;">-</div>
                    </div>
                    <div>
                        <span style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Patient Name</span>
                        <div id="modal-patient-name" style="font-size: 0.95rem; font-weight: 600; color: #1e293b;">-</div>
                    </div>
                    <div>
                        <span style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">National ID (NIC)</span>
                        <div id="modal-patient-nic" style="font-size: 0.95rem; font-weight: 600; color: #1e293b;">-</div>
                    </div>
                    <div style="grid-column: span 2;">
                        <span style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Request Type / Reason</span>
                        <div id="modal-reason" style="font-size: 1rem; font-weight: 800; color: #1e293b;">-</div>
                    </div>
                    <div style="grid-column: span 2;">
                        <span style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Requested Amount</span>
                        <div id="modal-amount" style="font-size: 1.25rem; font-weight: 800; color: #1e3a8a;">-</div>
                    </div>
                </div>

                <!-- Description Field -->
                <div>
                    <span style="display: block; font-size: 0.7rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px;">Rationale / Description</span>
                    <div id="modal-description" style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 1rem; font-size: 0.9rem; color: #475569; line-height: 1.5; min-height: 80px;">-</div>
                </div>

                <!-- Action Section -->
                <div style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 1rem; border-top: 1px solid #f1f5f9; padding-top: 1.5rem;">
                    <button type="button" class="btn btn-secondary" onclick="closeSupportModal()" style="background: #f1f5f9; color: #475569; border: none; padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 700; cursor: pointer; transition: background 0.2s;">Cancel</button>
                    
                    <div id="modal-pending-actions" style="display: none; gap: 0.75rem;">
                        <button type="button" id="reject-btn" class="btn btn-danger" style="background: #dc2626; color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: all 0.2s;">
                            Decline
                        </button>
                        <button type="button" id="approve-btn" class="btn btn-primary" style="background: #005baa; color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: all 0.2s;">
                            Verify & Forward
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</div>

<!-- Notification System -->
<div id="notification" class="notification" style="display: none;">
    <div class="notification-content">
        <span id="notification-message"></span>
    </div>
</div>

<script>
    function toggleUserDropdown() {
        const dropdown = document.getElementById('user-dropdown');
        if(dropdown) {
            dropdown.classList.toggle('show');
        }
        event.stopPropagation();
    }

    function logout() {
        if(confirm('Are you sure you want to logout?')) {
            window.location.href = '<?= ROOT ?>/logout';
        }
    }

    function logoLogout() {
        if(confirm('Clicking the logo will logout. Continue?')) {
            window.location.href = '<?= ROOT ?>/logout';
        }
    }

    function showContent(sectionId, element) {
        document.querySelectorAll('.menu-item').forEach(item => item.classList.remove('active'));
        element.classList.add('active');

        document.querySelectorAll('.content-section').forEach(section => section.style.display = 'none');
        const section = document.getElementById(sectionId);
        if(section) {
            section.style.display = 'block';
        }
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', (e) => {
        const userInfo = document.querySelector('.user-info');
        const dropdown = document.getElementById('user-dropdown');
        
        if (dropdown && userInfo && !userInfo.contains(e.target)) {
            dropdown.classList.remove('show');
        }
    });

    function openSupportDetails(data) {
        // ... (data mapping stays the same) ...
        document.getElementById('modal-request-id').innerText = `SUP${String(data.id).padStart(3, '0')}`;
        document.getElementById('modal-date').innerText = new Date(data.submitted_date).toLocaleDateString();
        document.getElementById('modal-patient-name').innerText = data.patient_name;
        document.getElementById('modal-patient-nic').innerText = data.patient_nic;
        document.getElementById('modal-reason').innerText = data.reason;
        document.getElementById('modal-description').innerText = data.description || "No additional details provided.";
        document.getElementById('modal-amount').innerText = `LKR ${parseFloat(data.amount).toLocaleString(undefined, {minimumFractionDigits: 2})}`;

        const badge = document.getElementById('modal-status-badge');
        const iconBox = document.getElementById('modal-status-icon-box');
        const icon = document.getElementById('modal-status-icon');
        const status = data.status.toUpperCase();
        
        badge.innerText = status === 'VERIFIED' ? 'VERIFIED' : status;
        
        const themes = {
            'PENDING':  { bg: '#fee2e2', text: '#dc2626', badgeBg: '#fef2f2', badgeText: '#dc2626', icon: 'fa-circle-xmark' },
            'VERIFIED': { bg: '#ecfdf5', text: '#059669', badgeBg: '#f0fdf4', badgeText: '#059669', icon: 'fa-circle-check' },
            'APPROVED': { bg: '#eff6ff', text: '#3b82f6', badgeBg: '#ebf5ff', badgeText: '#3b82f6', icon: 'fa-certificate' },
            'REJECTED': { bg: '#f8fafc', text: '#64748b', badgeBg: '#f1f5f9', badgeText: '#64748b', icon: 'fa-ban' }
        };
        
        const theme = themes[status] || themes['PENDING'];
        iconBox.style.background = theme.bg;
        icon.style.color = theme.text;
        icon.className = `fa-solid ${theme.icon}`;
        badge.style.background = theme.badgeBg;
        badge.style.color = theme.badgeText;

        const actions = document.getElementById('modal-pending-actions');
        if (status === 'PENDING') {
            actions.style.display = 'flex';
            document.getElementById('approve-btn').onclick = () => handleSupportAction(data.id, 'approved');
            document.getElementById('reject-btn').onclick = () => handleSupportAction(data.id, 'rejected');
        } else {
            actions.style.display = 'none';
        }

        document.getElementById('supportModal').classList.add('show');
    }

    function closeSupportModal() {
        document.getElementById('supportModal').classList.remove('show');
    }


    function handleSupportAction(id, action) {
        // ... (action logic mapping stays the same) ...
        const confirmMsg = action === 'approved' 
            ? 'Are you sure you want to verify and forward this request to Finance?' 
            : 'Are you sure you want to reject this request?';
            
        if (confirm(confirmMsg)) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?= ROOT ?>/aftercare-admin/handle-action';
            
            const idInput = document.createElement('input');
            idInput.type = 'hidden';
            idInput.name = 'request_id';
            idInput.value = id;
            
            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = action;
            
            form.appendChild(idInput);
            form.appendChild(actionInput);
            document.body.appendChild(form);
            form.submit();
        }
    }

    // Close modal on click outside
    window.onclick = function(event) {
        const supportModal = document.getElementById('supportModal');
        if (event.target == supportModal) closeSupportModal();
    }

    // Support Request Filtering System
    const supportSearch = document.getElementById('support-search');
    const statusFilter = document.getElementById('status-filter');

    if (supportSearch) supportSearch.addEventListener('input', debounce(applySupportFilters, 300));
    if (statusFilter) statusFilter.addEventListener('change', applySupportFilters);

    function applySupportFilters() {
        const searchTerm = supportSearch.value;
        const status = statusFilter.value;
        
        // Loader or some visual feedback could go here
        fetch(`<?= ROOT ?>/aftercare-admin/filter-support?status=${status}&search=${searchTerm}`)
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    refreshSupportTable(data.requests);
                }
            })
            .catch(err => console.error("Filter error:", err));
    }

    function refreshSupportTable(requests) {
        const container = document.getElementById('support-requests-table');
        if (!container) return;

        // Keep the header row
        const header = container.querySelector('.table-row[style*="font-weight: 700"]');
        container.innerHTML = '';
        if (header) container.appendChild(header);

        if (!requests || requests.length === 0) {
            const emptyRow = document.createElement('div');
            emptyRow.style.padding = '4rem 2rem';
            emptyRow.style.textAlign = 'center';
            emptyRow.innerHTML = `
                <i class="fa-solid fa-folder-open" style="font-size: 3rem; color: #e2e8f0; margin-bottom: 1rem; display: block;"></i>
                <div style="color: #94a3b8; font-weight: 500;">No support requests found matching your filters.</div>
            `;
            container.appendChild(emptyRow);
            return;
        }

        requests.forEach(request => {
            const status = (request.status || 'PENDING').toUpperCase();
            const colors = {
                'PENDING':  { bg: '#fef9c3', text: '#854d0e', label: 'Pending Verification' },
                'VERIFIED': { bg: '#dcfce7', text: '#166534', label: 'Verified (To Finance)' },
                'APPROVED': { bg: '#dbeafe', text: '#1e40af', label: 'Approved' },
                'REJECTED': { bg: '#fee2e2', text: '#991b1b', label: 'Rejected' }
            };
            const c = colors[status] || { bg: '#f1f5f9', text: '#475569', label: status };

            const row = document.createElement('div');
            row.className = 'table-row support-row';
            row.style.cssText = 'cursor: pointer; transition: all 0.2s ease; border-bottom: 1px solid #f1f5f9; align-items: center; display: flex; width: 100%;';
            row.onclick = () => openSupportDetails(request);
            
            // Mouseover effects
            row.onmouseover = function() {
                this.style.background = '#f8fafc';
                this.style.transform = 'translateY(-1px)';
                this.style.boxShadow = '0 4px 6px -1px rgba(0,0,0,0.05)';
            };
            row.onmouseout = function() {
                this.style.background = 'white';
                this.style.transform = 'none';
                this.style.boxShadow = 'none';
            };

            const amount = parseFloat(request.amount || 0).toLocaleString(undefined, {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });

            const date = new Date(request.submitted_date).toLocaleDateString('en-US', {
                month: 'short', day: '2-digit', year: 'numeric'
            });

            row.innerHTML = `
                <div class="table-cell" style="padding: 1.25rem 1.5rem; flex: 2;">
                    <div style="font-weight: 600; color: #1e293b;">${request.patient_name}</div>
                    <div style="font-size: 0.75rem; color: #94a3b8; font-weight: 500;">${request.patient_nic}</div>
                </div>
                <div class="table-cell" style="padding: 1.25rem 1.5rem; flex: 2;">
                    <div style="font-weight: 500; color: #334155; line-height: 1.4;">${request.reason}</div>
                </div>
                <div class="table-cell" style="padding: 1.25rem 1.5rem; flex: 1;">
                    <div style="font-weight: 700; color: #0f172a; font-size: 1rem;">LKR ${amount}</div>
                </div>
                <div class="table-cell" style="padding: 1.25rem 1.5rem; flex: 1;">
                    <div style="color: #64748b; font-size: 0.875rem; font-weight: 500;">${date}</div>
                </div>
                <div class="table-cell" style="padding: 1.25rem 1.5rem; flex: 1.5;">
                    <span style="display: inline-block; padding: 0.4rem 0.8rem; border-radius: 50px; font-size: 0.75rem; font-weight: 700; background: ${c.bg}; color: ${c.text}; white-space: nowrap;">
                        ${c.label}
                    </span>
                </div>
            `;
            container.appendChild(row);
        });
    }

    function debounce(func, wait) {
        let timeout;
        return function() {
            const context = this, args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(context, args), wait);
        };
    }

    // Notification System Helper
    function showNotification(message, type = 'success') {
        const notification = document.getElementById('notification');
        const messageEl = document.getElementById('notification-message');
        if(!notification || !messageEl) return;

        messageEl.innerText = message;
        notification.className = `notification ${type}`;
        notification.style.display = 'block';

        setTimeout(() => {
            notification.style.display = 'none';
        }, 3000);
    }
</script>

</body>
</html>