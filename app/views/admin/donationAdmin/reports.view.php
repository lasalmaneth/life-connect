<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/life-connect/public/assets/css/style.css">
    <link rel="stylesheet" href="/life-connect/public/assets/css/admin/style.css">
    <link rel="stylesheet" href="/life-connect/public/assets/css/admin/donation-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Reports & Analytics | LifeConnect</title>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <div>
                <h1>LifeConnect Admin Dashboard</h1>
                <p>Organ Management System - Reports & Analytics</p>
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
                <!-- Reports & Analytics -->
                <div id="reports" class="content-section">
                    <div class="content-header">
                        <h2>Reports & Analytics</h2>
                    </div>
                    <div class="content-body">
                        <div class="action-section">
                            <h3>Report Generation</h3>
                            <div class="action-buttons">
                                <button class="btn btn-primary" onclick="generateMonthlyReport()">Monthly Report</button>
                                <button class="btn btn-secondary" onclick="generateQuarterlyReport()">Quarterly Report</button>
                                <button class="btn btn-success" onclick="generateCustomReport()">Custom Report</button>
                                <button class="btn btn-secondary" onclick="exportAllData()">Export All Data</button>
                            </div>
                        </div>

                        <div class="stats-grid" style="margin-top: 2rem;">
                            <div class="stat-card">
                                <div class="stat-number">94.5%</div>
                                <div class="stat-label">Success Rate</div>
                                <div class="stat-change positive">↑ 2.3% improvement</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number">12 days</div>
                                <div class="stat-label">Avg. Match Time</div>
                                <div class="stat-change positive">↓ 3 days faster</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number">96.8%</div>
                                <div class="stat-label">Satisfaction Rate</div>
                                <div class="stat-change positive">↑ 1.5% this quarter</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number">234</div>
                                <div class="stat-label">Lives Saved</div>
                                <div class="stat-change positive">↑ 18 this year</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="/life-connect/public/assets/js/admin/donation.js"></script>
</body>
</html>