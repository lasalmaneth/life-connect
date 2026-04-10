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
    </style>
</head>
<body>
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
                    <a href="javascript:void(0)" class="menu-item" onclick="showContent('patients', this)">
                        <span class="icon"><i class="fa-solid fa-user-injured"></i></span>
                        <span>Aftercare Patients</span>
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
                            <div class="stat-number quick-stat-number" id="dashboard-total-requests"><?= $stats['total'] ?></div>
                            <div class="stat-label">Total Support Requests</div>
                        </div>
                        <div class="stat-card glass-card">
                            <div class="stat-number quick-stat-number" id="dashboard-pending"><?= $stats['pending'] ?></div>
                            <div class="stat-label">Pending Approval</div>
                        </div>
                        <div class="stat-card glass-card">
                            <div class="stat-number quick-stat-number" id="dashboard-total-amount">LKR 0</div>
                            <div class="stat-label">Total Support Amount</div>
                        </div>
                        <div class="stat-card glass-card">
                            <div class="stat-number" id="dashboard-total-patients">5</div>
                            <div class="stat-label">Total Patients</div>
                        </div>
                        <div class="stat-card glass-card">
                            <div class="stat-number" id="dashboard-recipients">3</div>
                            <div class="stat-label">Recipient Patients</div>
                        </div>
                        <div class="stat-card glass-card">
                            <div class="stat-number" id="dashboard-donors">2</div>
                            <div class="stat-label">Post-Donation Patients</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Support Requests -->
            <div id="support-requests" class="content-section" style="display: none;">
                <div class="content-header">
                    <h2>Support Requests</h2>
                    <p>Manage and review patient support applications</p>
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
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                                <option value="confirmed">Confirmed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                            <select class="filter-select" id="request-type-filter">
                                <option value="">All Types</option>
                                <option value="patient">Patient</option>
                                <option value="hospital">Hospital</option>
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

                    <div class="data-table">
                        <div class="table-header">
                            <h4>Support Requests</h4>
                        </div>
                        <div class="table-content" id="support-requests-table">
                            <div class="table-row" style="font-weight: 600; background: var(--gray-bg-color);">
                                <div class="table-cell">Request ID</div>
                                <div class="table-cell">Requester</div>
                                <div class="table-cell">Description</div>
                                <div class="table-cell">Amount</div>
                                <div class="table-cell">Date</div>
                                <div class="table-cell">Status</div>
                                <div class="table-cell">Actions</div>
                            </div>
                            <?php include 'requests.view.php'; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Aftercare Patients -->
            <div id="patients" class="content-section" style="display: none;">
                <div class="content-header">
                    <h2>Aftercare Patients</h2>
                    <p>Monitor and manage post-surgery patient follow-ups</p>
                </div>
                <div class="content-body">
                    <div style="display: flex; gap: 16px; align-items: center; margin-bottom: 24px; padding-top: 2rem;">
                        <div class="search-bar" style="margin-bottom: 0; flex: 1;">
                            <span class="search-icon">🔍</span>
                            <input type="text" class="search-input" placeholder="Search by Patient Name, ID, or Surgery Type..." id="patient-search">
                        </div>

                        <div class="filter-section" style="margin-bottom: 0; display: flex; gap: 12px;">
                            <select class="filter-select" id="patient-type-filter">
                                <option value="">All Patient Types</option>
                                <option value="recipient">Recipient Patient</option>
                                <option value="donor">Post-Donation Patient</option>
                            </select>
                            <select class="filter-select" id="blood-type-filter">
                                <option value="">All Blood Types</option>
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
                    </div>

                    <div class="stats-grid dashboard-metrics" style="grid-template-columns: repeat(4, 1fr); margin-bottom: 2rem;">
                        <div class="stat-card glass-card">
                            <div class="stat-number quick-stat-number" id="total-patients">0</div>
                            <div class="stat-label">Total Patients</div>
                        </div>
                        <div class="stat-card glass-card">
                            <div class="stat-number quick-stat-number" id="recipient-patients">0</div>
                            <div class="stat-label">Recipient Patients</div>
                        </div>
                        <div class="stat-card glass-card">
                            <div class="stat-number quick-stat-number" id="donor-patients">0</div>
                            <div class="stat-label">Post-Donation Patients</div>
                        </div>
                        <div class="stat-card glass-card">
                            <div class="stat-number quick-stat-number" id="average-age">0</div>
                            <div class="stat-label">Average Age</div>
                        </div>
                    </div>

                    <div class="data-table">
                        <div class="table-header">
                            <h4>Patient Records</h4>
                        </div>
                        <div class="table-content" id="patients-table">
                            <div class="table-row" style="font-weight: 600; background: var(--gray-bg-color);">
                                <div class="table-cell">Patient ID</div>
                                <div class="table-cell">Patient Name</div>
                                <div class="table-cell">Age</div>
                                <div class="table-cell">Blood Type</div>
                                <div class="table-cell">Surgery Type</div>
                                <div class="table-cell">Patient Type</div>
                                <div class="table-cell">Actions</div>
                            </div>
                            <!-- AJAX will load data here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Support Request Details Modal -->
<div id="supportModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Support Request Details</h3>
            <button class="close-btn" onclick="closeSupportModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div class="payment-details">
                <div class="detail-row">
                    <div class="detail-label">Request ID</div>
                    <div class="detail-value" id="modal-request-id">-</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Requester Type</div>
                    <div class="detail-value" id="modal-requester-type">-</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Requester ID</div>
                    <div class="detail-value" id="modal-requester-id">-</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Description</div>
                    <div class="detail-value" id="modal-description">-</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Amount</div>
                    <div class="detail-value amount" id="modal-amount">-</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Date Submitted</div>
                    <div class="detail-value" id="modal-date">-</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Status</div>
                    <div class="detail-value">
                        <span class="status-badge" id="modal-status">-</span>
                    </div>
                </div>
            </div>
            
            <div class="modal-actions">
                <button class="btn btn-secondary" onclick="closeSupportModal()">
                    <i class="fa-solid fa-times"></i> Close
                </button>
                <button class="btn btn-primary" id="approve-btn" onclick="approveRequest()" style="display: none;">
                    <i class="fa-solid fa-check"></i> Approve
                </button>
                <button class="btn btn-danger" id="reject-btn" onclick="rejectRequest()" style="display: none;">
                    <i class="fa-solid fa-times"></i> Reject
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Patient Details Modal -->
<div id="patientModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Patient Details</h3>
            <button class="close-btn" onclick="closePatientModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div class="payment-details">
                <div class="detail-row">
                    <div class="detail-label">Patient ID</div>
                    <div class="detail-value" id="modal-patient-id">-</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Patient Name</div>
                    <div class="detail-value" id="modal-patient-name">-</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Email</div>
                    <div class="detail-value" id="modal-patient-email">-</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Age</div>
                    <div class="detail-value" id="modal-patient-age">-</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Blood Type</div>
                    <div class="detail-value" id="modal-patient-bloodtype">-</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Surgery Type</div>
                    <div class="detail-value" id="modal-patient-surgery">-</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Patient Type</div>
                    <div class="detail-value">
                        <span class="status-badge" id="modal-patient-type">-</span>
                    </div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Notes</div>
                    <div class="detail-value" id="modal-patient-notes">-</div>
                </div>
            </div>
            
            <div class="modal-actions">
                <button class="btn btn-secondary" onclick="closePatientModal()">
                    <i class="fa-solid fa-times"></i> Close
                </button>
                <button class="btn btn-primary" onclick="viewLabReport()">
                    <i class="fa-solid fa-file-medical"></i> Lab Report
                </button>
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
        
        if (dropdown && !userInfo.contains(e.target)) {
            dropdown.classList.remove('show');
        }
    });
</script>

</body>
</html>