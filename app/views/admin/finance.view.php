<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= ROOT ?>/public/assets/css/style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= ROOT ?>/public/assets/css/admin/style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Financial Donations | LifeConnect</title>
</head>
<body style="background-color: #f8fafc; min-height: 100vh;">

<?php
    if (session_status() === PHP_SESSION_NONE) session_start();
    $adminName = $_SESSION['username'] ?? ($_SESSION['user_name'] ?? 'Admin');
    
    // Parse KPIs
    $kpis = $data['kpis'] ?? [];
    $totalContributors = $kpis['total_contributors'] ?? 0;
    $totalAmount = $kpis['total_amount'] ?? 0;
    $thisMonthContributors = $kpis['this_month_contributors'] ?? 0;

    
    $failedTransactions = $kpis['failed_transactions'] ?? 0;
    $failedThisMonth = $kpis['failed_this_month'] ?? 0;
    $retentionRate = $kpis['retention_rate'] ?? 0;
    
    $thisMonth  = $kpis['this_month']    ?? 0;
    $prevMonth  = $kpis['prev_month']    ?? 0;
    $thisQuarter = $kpis['this_quarter'] ?? 0;
    $prevQuarter = $kpis['prev_quarter'] ?? 0;
    $thisYear   = $kpis['this_year']     ?? 0;

    // Short amount formatter e.g. 10000 -> LKR 10K
    function fmtLKR($n) {
        if ($n >= 1000000) return 'LKR ' . round($n / 1000000, 1) . 'M';
        if ($n >= 1000)    return 'LKR ' . round($n / 1000, 1) . 'K';
        return 'LKR ' . number_format($n, 2);
    }

    // Trend SVG Math mapping
    $monthlyTrend = $kpis['monthly_trend'] ?? [];
    if (empty($monthlyTrend)) {
        // Fallback for empty data
        $monthlyTrend = [
            (object)['month_label' => 'No Data', 'total' => 0],
            (object)['month_label' => 'Current', 'total' => 0]
        ];
    }
    
    $svgMax = 1;
    foreach($monthlyTrend as $t) { 
        if($t->total > $svgMax) $svgMax = $t->total; 
    }
    
    $svgW = 600;
    $svgH = 120;
    $paddingSteps = count($monthlyTrend) > 1 ? (count($monthlyTrend) - 1) : 1;
    $xStep = $svgW / $paddingSteps;

    $polyPoints = "0,$svgH ";
    $linePoints = "";
    
    foreach($monthlyTrend as $i => $t) {
        $x = $i * $xStep;
        $y = $svgH - (($t->total / $svgMax) * $svgH * 0.85);
        $polyPoints .= "$x,$y ";
        $linePoints .= "$x,$y ";
    }
    $polyPoints .= "$svgW,$svgH";
?>

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
                <div class="user-info">
                    <div class="user-avatar"><?= substr($adminName, 0, 1) ?></div>
                    <div class="user-details">
                        <span class="user-name"><?= htmlspecialchars($adminName) ?></span>
                        <span class="user-role">Financial Administrator</span>
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
                    <div class="sidebar-user-avatar">A</div>
                    <div class="sidebar-user-info">
                        <span class="sidebar-user-name">admin_4</span>
                        <span class="sidebar-user-id">ID-00004</span>
                        <span class="sidebar-user-role">Finance Admin</span>
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
                    <div class="menu-section-title">Support Management</div>
                    <a href="javascript:void(0)" class="menu-item" onclick="showContent('support-requests', this)">
                        <span class="icon"><i class="fa-solid fa-hand-holding-heart"></i></span>
                        <span>Support Requests</span>
                    </a>
                    <a href="javascript:void(0)" class="menu-item" onclick="showContent('vouchers', this)">
                        <span class="icon"><i class="fa-solid fa-ticket"></i></span>
                        <span>Voucher Management</span>
                    </a>
                </div>

                <div class="menu-section">
                    <div class="menu-section-title">Donation Management</div>
                    <a href="javascript:void(0)" class="menu-item" onclick="showContent('payments', this)">
                        <span class="icon"><i class="fa-solid fa-money-bill-transfer"></i></span>
                        <span>Financial Donations</span>
                    </a>
                </div>

                <div class="menu-section mt-auto">
                    <a href="<?= ROOT ?>/financial-admin/logout" class="menu-item text-danger">
                        <span class="icon"><i class="fa-solid fa-right-from-bracket"></i></span>
                        <span>Logout</span>
                    </a>
                </div>
            </div>

            <div class="content-area" id="content-area">
                <!-- Tab View Fragments -->
                <?php include 'finance/dashboard.tab.php'; ?>
                <?php include 'finance/donations.tab.php'; ?>
                <?php include 'finance/support_requests.tab.php'; ?>
                <?php include 'finance/vouchers.tab.php'; ?>
            </div>
        </div>
    </div>
    
    <!-- Payment Details Modal -->
    <div id="paymentModal" class="modal">
        <div class="modal-content">
            <div class="modal-scroll-area">
                <div style="display: flex; flex-direction: column; gap: 1.25rem; position: relative;">
                    <button class="modal-close" style="position: absolute; top: -15px; right: -15px; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 50%; background: #f1f5f9; border: none; cursor: pointer; color: #64748b; z-index: 10;" onclick="closePaymentModal()">&times;</button>
                
                <div style="display: flex; align-items: center; gap: 1.25rem;">
                    <!-- Status Icon -->
                    <div id="modal-status-icon-box" style="flex-shrink: 0; width: 48px; height: 48px; background: #dcfce7; border-radius: 12px; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease;">
                        <i id="modal-status-icon" class="fa-solid fa-circle-check" style="font-size: 20px; color: #16a34a;"></i>
                    </div>

                    <!-- Title -->
                    <div>
                        <h2 style="margin: 0; font-size: 1.5rem; font-weight: 800; color: #0f172a; line-height: 1.2;">Payment Details</h2>
                    </div>
                </div>

                <p style="margin: 0; color: #64748b; font-size: 0.9rem; line-height: 1.5; font-weight: 500;">Details of the financial donation transaction.</p>

                <!-- Details Card (2-Column Grid) -->
                <div style="background: #f0f7ff; border-radius: 16px; padding: 1.5rem; display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div>
                        <span style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Payment ID</span>
                        <div id="modal-payment-id" style="font-size: 1.05rem; font-weight: 700; color: #1e293b;">-</div>
                    </div>
                    <div>
                        <span style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Status</span>
                        <div id="modal-status" style="font-size: 0.95rem; font-weight: 700; color: #16a34a;">-</div>
                    </div>
                    
                    <div style="grid-column: span 2;">
                        <span style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Donor Name</span>
                        <div id="modal-donor-id" style="font-size: 1rem; font-weight: 700; color: #1e293b;">-</div>
                    </div>

                    <div style="grid-column: span 2;">
                        <span style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Email Address</span>
                        <div id="modal-email" style="font-size: 0.9rem; font-weight: 600; color: #1e293b; word-break: break-all;">-</div>
                    </div>

                    <div>
                        <span style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Amount</span>
                        <div id="modal-amount" style="font-size: 1.1rem; font-weight: 800; color: #0f172a;">-</div>
                    </div>
                    <div>
                        <span style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Date</span>
                        <div id="modal-date" style="font-size: 0.95rem; font-weight: 600; color: #0f172a;">-</div>
                    </div>

                    <div style="grid-column: span 2;">
                        <span style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Donor Note</span>
                        <div id="modal-note" style="font-size: 0.9rem; font-weight: 500; color: #475569; font-style: italic; background: #f8fafc; padding: 10px; border-radius: 8px; border: 1px solid #e2e8f0; margin-top: 5px;">-</div>
                    </div>
                </div>

                <!-- Footer Buttons -->
                <div style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 0.5rem;">
                    <button type="button" onclick="printPaymentDetails()" style="background: #005baa; color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: all 0.2s;">
                        <i class="fa-solid fa-print"></i> Print
                    </button>
                    <button type="button" onclick="closePaymentModal()" style="background: #f1f5f9; color: #475569; border: none; padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 700; cursor: pointer; transition: background 0.2s;">Close</button>
                </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        const ROOT = '<?= ROOT ?>';
        const ADMIN_NAME = '<?= $adminName ?>';


        // SVG Chart Tooltip Logic
        function showTooltip(month, val, event) {
            const tooltip = document.getElementById('svg-tooltip');
            document.getElementById('tt-month').innerText = month;
            document.getElementById('tt-val').innerText = val;
            
            const containerRect = tooltip.parentElement.getBoundingClientRect();
            const ptX = event.clientX - containerRect.left;
            const ptY = event.clientY - containerRect.top;

            tooltip.style.left = ptX + 'px';
            tooltip.style.top = ptY + 'px';
            tooltip.style.display = 'block';
        }

        // Hide tooltip if clicking outside the SVG points
        document.addEventListener('click', function(e) {
            if (!e.target.closest('circle')) {
                const tt = document.getElementById('svg-tooltip');
                if (tt) tt.style.display = 'none';
            }
        });

        function toggleUserDropdown() {
            const dropdown = document.getElementById('user-dropdown');
            if(dropdown) {
                dropdown.classList.toggle('show');
            }
            event.stopPropagation();
        }
    </script>
    <!-- Custom Toast Notification Container -->
    <div id="toast-container" style="position: fixed; top: 24px; right: 24px; z-index: 9999; display: flex; flex-direction: column; gap: 12px; pointer-events: none;"></div>
    
    <!-- Dashboard Logic -->
    <script src="<?= ROOT ?>/public/assets/js/admin/finance.js?v=<?= time() ?>"></script>
    <!-- Reporting Logic -->
    <script src="<?= ROOT ?>/public/assets/js/admin/finance.reports.js?v=<?= time() ?>"></script>
</body>
</html>