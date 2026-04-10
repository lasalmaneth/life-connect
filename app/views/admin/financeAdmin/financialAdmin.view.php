<?php
// Start session if not already started - MUST be before any HTML output
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get admin user information from session
$admin_user_id = $_SESSION['user_id'] ?? null;
$admin_username = $_SESSION['username'] ?? 'Admin User';
$admin_role = $_SESSION['role'] ?? 'Financial Administrator';

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
    <title>Financial Admin | LifeConnect</title>
    <link rel="stylesheet" href="/Life-Connect/public/assets/css/style.css">
    <link rel="stylesheet" href="/Life-Connect/public/assets/css/admin/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        /* Header User Dropdown Styles */
        .user-info {
            position: relative;
            cursor: pointer;
        }

        .user-details {
            flex: 1;
            min-width: 0;
        }

        .user-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-logout {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #ef4444;
            padding: 8px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .btn-logout::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(239, 68, 68, 0.15);
            transform: translate(-50%, -50%);
            transition: width 0.3s ease, height 0.3s ease;
        }

        .btn-logout:hover {
            background: rgba(239, 68, 68, 0.2);
            border-color: rgba(239, 68, 68, 0.6);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.25);
        }

        .btn-logout:hover::before {
            width: 100%;
            height: 100%;
        }

        .btn-logout svg {
            position: relative;
            z-index: 1;
            transition: transform 0.2s ease;
        }

        .btn-logout:hover svg {
            transform: scale(1.1);
        }

        .user-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15), 0 8px 16px rgba(0, 0, 0, 0.1);
            min-width: 320px;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(0, 0, 0, 0.05);
            margin-top: 8px;
        }

        .user-dropdown.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-header {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 20px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        }

        .user-avatar-large {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 20px;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .user-name {
            font-weight: 700;
            font-size: 16px;
            color: #1e293b;
            margin-bottom: 4px;
        }

        .user-role {
            font-size: 14px;
            color: #64748b;
        }

        .dropdown-content {
            padding: 16px 20px;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .detail-item:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-size: 14px;
            color: #64748b;
            font-weight: 500;
        }

        .detail-value {
            font-size: 14px;
            color: #1e293b;
            font-weight: 600;
            text-align: right;
            max-width: 150px;
            word-wrap: break-word;
        }

        .status-active {
            color: #10b981;
            font-weight: 700;
        }

        .dropdown-footer {
            padding: 16px 20px;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
            display: flex;
            gap: 8px;
            background: #f8fafc;
            border-radius: 0 0 12px 12px;
        }

        .btn-small {
            padding: 8px 16px;
            font-size: 12px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            flex: 1;
        }

        .btn-danger {
            background: #ef4444;
            color: white;
        }

        .btn-danger:hover {
            background: #dc2626;
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #64748b;
            padding: 0;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            transition: all 0.2s ease;
        }

        .close-btn:hover {
            background: #f1f5f9;
            color: #1e293b;
        }

        /* Inline Table Filter Styles */
        .inline-filter {
            width: 100%;
            padding: 6px 8px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 13px;
            color: #1e293b;
            background-color: white;
            transition: all 0.2s;
            box-sizing: border-box;
        }

        .inline-filter:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
        }

        /* Hide dd/mm/yyyy in empty date inputs */
        .date-clean {
            color: transparent;
        }
        .date-clean:focus, .date-clean:valid {
            color: #1e293b;
        }
        .date-clean::-webkit-datetime-edit { 
            color: transparent; 
        }
        .date-clean:focus::-webkit-datetime-edit, .date-clean:valid::-webkit-datetime-edit { 
            color: #1e293b; 
        }

        .table-filter-row {
            background: #f8fafc;
            border-bottom: 2px solid #e2e8f0;
        }
        .table-filter-row .table-cell {
            padding: 8px 16px;
            vertical-align: top;
        }
    </style>
</head>
<body>
<script src="/Life-Connect/public/assets/js/admin/financialAdmin.js?v=<?= time() ?>" defer></script>

<!-- Header Section -->
<div class="header">
    <div class="header-content">
        <div class="header-left">
            <a href="<?= ROOT ?>" style="text-decoration:none; display:flex; align-items:center; gap:10px;">
                <img src="<?= ROOT ?>/public/assets/images/logo.png" alt="LifeConnect" style="height:40px;">
                <div>
                    <strong style="display:block; font-size:1.1rem; color:#003b6e; line-height:1.2;">LifeConnect</strong>
                    <p style="margin:0; font-size:.68rem; color:#6b7280;">Financial Administration</p>
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

            <div class="user-info" onclick="toggleUserDropdown()">
            <div class="user-avatar"><?php echo strtoupper(substr($admin_full_name, 0, 1)); ?></div>
            <div class="user-details">
                <div style="font-weight: 600; font-size: 0.9rem;"><?php echo $admin_full_name; ?></div>
                <div style="font-size: 0.8rem; opacity: 0.8;"><?php echo $admin_role_display; ?></div>
                <div style="font-size: 0.7rem; opacity: 0.6;">ID: <?php echo $admin_id_display; ?></div>
            </div>
            <div class="user-actions">
                
            </div>
            
            <!-- User Details Dropdown -->
            <div class="user-dropdown" id="user-dropdown">
                <div class="dropdown-header">
                    <div class="user-avatar-large"><?php echo strtoupper(substr($admin_full_name, 0, 1)); ?></div>
                    <div>
                        <div class="user-name"><?php echo $admin_full_name; ?></div>
                        <div class="user-role"><?php echo $admin_role_display; ?></div>
                    </div>
                </div>
                <div class="dropdown-content">
                    <div class="detail-item">
                        <span class="detail-label">User ID:</span>
                        <span class="detail-value"><?php echo $admin_id_display; ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Email:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($admin_email); ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Role:</span>
                        <span class="detail-value"><?php echo $admin_role_display; ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Status:</span>
                        <span class="detail-value status-active"><?php echo $admin_status; ?></span>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="main-content">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h3>Financial Admin</h3>
                <p>Dashboard & Donations</p>
            </div>
            <div class="menu-section">
                <div class="menu-item active" onclick="showContent('dashboard', this)">
                    <span class="icon"><i class="fa-solid fa-house"></i></span>
                    <span>Dashboard</span>
                </div>
                <div class="menu-item" onclick="showContent('finances', this)">
                    <span class="icon"><i class="fa-solid fa-money-bill-wave"></i></span>
                    <span>Finances</span>
                </div>
                <div class="menu-item" onclick="logout()">
                    <span class="icon"><i class="fa-solid fa-right-from-bracket"></i></span>
                    <span>Logout</span>
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="content-area" id="content-area">
            <!-- Dashboard -->
            <div id="dashboard" class="content-section dashboard-page">
                <div class="content-body" style="padding-top: 0;">
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-number">Rs. <?= number_format($totalDonationsReceived) ?></div>
                            <div class="stat-label">Total Donations Received</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number"><?= $totalDonors ?></div>
                            <div class="stat-label">Total Donors</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">
                                <?php if($highestContributor): ?>
                                    <?= htmlspecialchars($highestContributor->full_name) ?><br>
                                    <small>Rs. <?= number_format($highestContributor->total_amount) ?></small>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </div>
                            <div class="stat-label">Highest Contributor</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number"><?= $donationsPastMonth ?></div>
                            <div class="stat-label">Donations Past Month</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number"><?= $donationsPast3Months ?></div>
                            <div class="stat-label">Donations Past 3 Months</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number"><?= $donationsThisYear ?></div>
                            <div class="stat-label">Donations This Year</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Finances Table -->
<div id="finances" class="content-section" style="display:none;">
    <div class="content-header">
        <h2>All Financial Donations</h2>
    </div>
    <div class="content-body">
        <div class="search-bar" style="margin-bottom: 20px;">
            <span class="search-icon">🔍</span>
            <input type="text" class="search-input" id="donation-search" placeholder="Search by donor, note or status...">
        </div>

        <div class="data-table">
            <div class="table-header">
                <h4>Donations</h4>
            </div>
            <div class="table-content" id="financial-donations-table">
                <div class="table-row" style="font-weight: 600; background: var(--gray-bg-color);">
                    <div class="table-cell">Donation ID</div>
                    <div class="table-cell">Donor Name</div>
                    <div class="table-cell">Amount</div>
                    <div class="table-cell">Date</div>
                    <div class="table-cell">Note</div>
                    <div class="table-cell">Status</div>
                    <div class="table-cell">Actions</div>
                </div>
                <!-- Inline Filters Row -->
                <div class="table-row table-filter-row">
                    <div class="table-cell"></div>
                    <div class="table-cell"></div>
                    <div class="table-cell">
                        <div style="display: flex; gap: 4px;">
                            <input type="number" id="filter-amount-min" class="inline-filter" placeholder="Min" onchange="applyFilters()">
                            <input type="number" id="filter-amount-max" class="inline-filter" placeholder="Max" onchange="applyFilters()">
                        </div>
                    </div>
                    <div class="table-cell">
                        <div style="display: flex; flex-direction: column; gap: 4px;">
                            <input type="date" id="filter-date-from" class="inline-filter date-clean" onchange="applyFilters()" title="From Date">
                            <input type="date" id="filter-date-to" class="inline-filter date-clean" onchange="applyFilters()" title="To Date">
                        </div>
                    </div>
                    <div class="table-cell"></div>
                    <div class="table-cell">
                        <select id="filter-status" class="inline-filter" onchange="applyFilters()">
                            <option value="all">All</option>
                            <option value="Pending">Pending</option>
                            <option value="Completed">Completed</option>
                            <option value="Failed">Failed</option>
                            <option value="Cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="table-cell">
                        <button class="btn btn-secondary btn-small" onclick="clearFilters()" style="width: 100%; padding: 6px;">Clear</button>
                    </div>
                </div>
                <div class="table-row">
                    <div class="table-cell" colspan="7" style="text-align: center; padding: 2rem;">
                        <i class="fa-solid fa-spinner fa-spin"></i> Loading donations...
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Status Update Modal -->
<div class="modal" id="status-modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Update Donation Status</h3>
            <button class="modal-close" onclick="closeStatusModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label class="form-label">Donation ID:</label>
                <p id="modal-donation-id" style="font-weight: 600;"></p>
            </div>
            <div class="form-group">
                <label class="form-label">Donor Name:</label>
                <p id="modal-donor-name" style="font-weight: 600;"></p>
            </div>
            <div class="form-group">
                <label class="form-label">Amount:</label>
                <p id="modal-amount" style="font-weight: 600; color: var(--primary-color);"></p>
            </div>
            <div class="form-group">
                <label class="form-label">Current Status:</label>
                <p id="modal-current-status"></p>
            </div>
            <div class="form-group">
                <label class="form-label">New Status:</label>
                <select class="form-select" id="modal-new-status">
                    <option value="Pending">Pending</option>
                    <option value="Completed">Completed</option>
                    <option value="Failed">Failed</option>
                    <option value="Cancelled">Cancelled</option>
                </select>
            </div>
        </div>
        <div class="modal-actions">
            <button class="btn btn-secondary" onclick="closeStatusModal()">Cancel</button>
            <button class="btn btn-primary" onclick="confirmStatusUpdate()">Update Status</button>
        </div>
    </div>
</div>

        </div>
    </div>
</div>

</body>
</html>