<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/life-connect/public/assets/css/style.css">
    <link rel="stylesheet" href="/life-connect/public/assets/css/admin/style.css">
    <link rel="stylesheet" href="/life-connect/public/assets/css/admin/donation-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Status Management | LifeConnect</title>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <div>
                <h1>LifeConnect Admin Dashboard</h1>
                <p>Organ Management System - Status Management</p>
            </div>
            <div class="user-info">
                <div class="user-avatar">A</div>
                <div>
                    <div style="font-weight: 600; font-size: 0.9rem;">Admin User</div>
                    <div style="font-size: 0.8rem; opacity: 0.8;">System Administrator</div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="main-content">
            <div class="sidebar">
                <div class="sidebar-header">
                    <h3>System Management</h3>
                    <p>Administrative Dashboard</p>
                </div>
                
                <div class="menu-section">
                    <div class="menu-section-title">Overview</div>
                    <div class="menu-item">
                        <span class="icon"><i class="fa-solid fa-house"></i></span>
                        <a href="/life-connect/app/views/admin/donationAdmin/donation.view.php" style="text-decoration: none; color: inherit;">Dashboard Overview</a>
                    </div>
                </div>

                <div class="menu-section">
                    <div class="menu-section-title">Donation Management</div>
                    
                    <div class="menu-item">
                        <span class="icon"><i class="fa-solid fa-handshake"></i></span>
                        <a href="/life-connect/app/views/admin/donationAdmin/matches" style="text-decoration: none; color: inherit;">Match Coordination</a>
                    </div>
                    
                    <div class="menu-item active">
                        <span class="icon"><i class="fa-solid fa-clipboard-check"></i></span>
                        <a href="/life-connect/app/views/admin/donationAdmin/status" style="text-decoration: none; color: inherit;">Status Management</a>
                    </div>
                    
                    <div class="menu-item">
                        <span class="icon"><i class="fa-solid fa-chart-line"></i></span>
                        <a href="/life-connect/app/views/admin/donationAdmin/reports" style="text-decoration: none; color: inherit;">Reports & Analytics</a>
                    </div>

                    <div class="menu-item">
                        <span class="icon"><i class="fa-solid fa-chart-line"></i></span>
                        <a href="/life-connect/app/views/admin/donationAdmin/tributes" style="text-decoration: none; color: inherit;">Tributes</a>
                    </div>
                    
                </div>
            </div>

            <div class="content-area" id="content-area">
    <!-- Status Management -->
    <div id="status" class="content-section">
        <div class="content-header">
            <h2>Status Management</h2>
            <p>Track donation status between donors and organ requests</p>
        </div>
        <div class="content-body">
            <div class="action-section">
                <h3>Status Actions</h3>
                <div class="action-buttons">
                    <button class="btn btn-success" id="progress-to-next" onclick="progressToNextStage()" disabled>Progress to Next Stage</button>
                    <button class="btn btn-secondary" onclick="sendStatusNotifications()">Send Status Notifications</button>
                    <button class="btn btn-info" onclick="viewNotifications()" style="margin-left: auto;">View Sent Notifications</button>
                </div>
            </div>

            <div class="data-table">
                <div class="table-header">
                    <h4>Donation Status Tracking</h4>
                    <div class="table-actions">
                        <input type="text" class="search-input" placeholder="Search donors or request IDs..." id="status-search" oninput="handleSearch(event)">
                    </div>
                </div>
                <div class="table-content" id="status-table">
                    <div class="table-row" style="font-weight: 600; background: var(--gray-bg-color);">
                        <div class="table-cell" style="width: 15%;">
                            <input type="checkbox" id="select-all-status" onchange="toggleSelectAllStatus()"> Donation Details
                        </div>
                        <div class="table-cell" style="width: 20%;">Donor Information</div>
                        <div class="table-cell" style="width: 12%;">Organ Request ID</div>
                        <div class="table-cell" style="width: 15%;">Organ & Hospital</div>
                        <div class="table-cell" style="width: 10%;">Current Status</div>
                        <div class="table-cell" style="width: 12%;">Last Updated</div>
                        <div class="table-cell" style="width: 10%;">Next Action</div>
                        <div class="table-cell" style="width: 6%;">Actions</div>
                    </div>
                    <!-- Table rows will be populated by JavaScript -->
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Update Status Modal -->
<div id="update-status-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Update Donation Status</h3>
            <button class="modal-close" onclick="closeModal('update-status-modal')">&times;</button>
        </div>
        <form id="status-update-form" onsubmit="submitStatusUpdate(event)">
            <div style="padding: 2rem;">
                <div class="form-group">
                    <label class="form-label">Match ID</label>
                    <input type="text" class="form-input" id="status-match-id" readonly>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Donor Name</label>
                        <input type="text" class="form-input" id="status-donor-name" readonly>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Donor ID</label>
                        <input type="text" class="form-input" id="status-donor-id" readonly>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Organ Request ID</label>
                    <input type="text" class="form-input" id="status-request-id" readonly>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Organ Type</label>
                        <input type="text" class="form-input" id="status-organ-type" readonly>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Hospital</label>
                        <input type="text" class="form-input" id="status-hospital" readonly>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">New Status</label>
                    <select class="form-select" id="status-new-value" required>
                        <option value="">Select Status</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="in-progress">In Progress</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Notification Message</label>
                    <textarea class="form-textarea" id="status-notification-message" placeholder="Enter notification message for donor..." required rows="3"></textarea>
                    <div class="form-hint">This message will be sent to the donor</div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Internal Notes (Optional)</label>
                    <textarea class="form-textarea" id="status-notes" placeholder="Add any internal notes about this status change..." rows="2"></textarea>
                </div>
                
                <div class="action-buttons" style="margin-top: 2rem;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-paper-plane"></i> Update Status & Send Notification
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="closeModal('update-status-modal')">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>

    <!-- Send Notification Modal -->
<div id="notification-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Send Status Notifications</h3>
            <button class="modal-close" onclick="closeModal('notification-modal')">&times;</button>
        </div>
        <div style="padding: 2rem;">
            <div class="selected-donors" id="selected-donors-list" style="margin-bottom: 1.5rem; padding: 1rem; background: var(--gray-bg-color); border-radius: 8px;">
                <h4 style="margin-bottom: 0.5rem;">
                    <i class="fa-solid fa-users"></i> Selected Donors 
                    <span class="badge" id="selected-count">0</span>
                </h4>
                <div id="donors-list" style="max-height: 200px; overflow-y: auto;">
                    <!-- Donors list will be populated here -->
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">Notification Message</label>
                <textarea class="form-textarea" id="bulk-notification-message" placeholder="Enter notification message for selected donors..." required rows="4"></textarea>
                <div class="form-hint">This message will be sent to all selected donors</div>
            </div>
            
            <div class="action-buttons" style="margin-top: 2rem;">
                <button type="button" class="btn btn-primary" onclick="confirmSendNotifications()">
                    <i class="fa-solid fa-paper-plane"></i> Send to Selected Donors
                </button>
                <button type="button" class="btn btn-secondary" onclick="closeModal('notification-modal')">Cancel</button>
            </div>
        </div>
    </div>
</div>

    <script src="/life-connect/public/assets/js/admin/donation.js"></script>
</body>
</html>