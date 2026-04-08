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
                        <p style="margin:0; font-size:.68rem; color:#6b7280;">Finance Administration</p>
                    </div>
                </a>
            </div>
            
            <div class="header-right">
                <nav class="header-nav" style="display: flex; gap: 1.5rem; align-items: center; margin-right: 1.5rem;">
                    <a href="<?= ROOT ?>" class="nav-link" style="color: white; text-decoration: none; font-size: 0.9rem; display: flex; align-items: center; justify-content: center; padding: 0.5rem 0.75rem; border-radius: 6px; gap: 0.5rem; transition: all 0.3s ease; background: rgba(255, 255, 255, 0.1);">
                        <i class="fa-solid fa-house"></i> <span>Home</span>
                    </a>
                </nav>
                <div class="user-info">
                    <div class="user-avatar">A</div>
                    <div class="user-details">
                        <span class="user-name"><?= htmlspecialchars($adminName, ENT_QUOTES, 'UTF-8') ?></span>
                        <span class="user-role">Finance Administrator</span>
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
                    <div class="menu-section-title">Financial Management</div>
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
                <!-- Dashboard Overview -->
                <div id="dashboard" class="content-section dashboard-page">
                    <div class="content-body" style="padding-top: 0;">
                        
                        <!-- Top 4 summary stats -->
                        <div class="stats-grid dashboard-metrics" style="grid-template-columns: repeat(4, 1fr); margin-bottom: 2rem;">
                            <div class="stat-card glass-card">
                                <div class="stat-number quick-stat-number" id="stat-contributors"><?= number_format($totalContributors) ?></div>
                                <div class="stat-label">Total Contributors</div>
                                <div class="stat-change positive" id="change-contributors">↑ <?= number_format($thisMonthContributors) ?> this month</div>
                            </div>
                            <div class="stat-card glass-card">
                                <div class="stat-number quick-stat-number" id="stat-total-amount"><?= fmtLKR($totalAmount) ?></div>
                                <div class="stat-label">Total Donations</div>
                                <div class="stat-change positive" id="change-total-amount">↑ <?= fmtLKR($thisMonth) ?> this month</div>
                            </div>
                            <div class="stat-card glass-card">
                                <div class="stat-number quick-stat-number" id="stat-retention"><?= $retentionRate ?>%</div>
                                <div class="stat-label">Donor Retention Rate</div>
                                <div class="stat-change" style="color: #64748b" id="change-retention">from last year</div>
                            </div>
                            <div class="stat-card glass-card">
                                <div class="stat-number quick-stat-number" id="stat-failed-transactions"><?= number_format($failedTransactions) ?></div>
                                <div class="stat-label"><span style="color: #ef4444">Failed</span> Transactions</div>
                                <div class="stat-change <?= $failedThisMonth > 0 ? 'negative' : 'positive' ?>" id="change-failed-transactions"><?= $failedThisMonth > 0 ? '↑' : '' ?> <?= number_format($failedThisMonth) ?> this month</div>
                            </div>
                        </div>

                        <!-- Period totals (left) + Bar chart (right) -->
                        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 16px; margin-top: 16px;">

                            <!-- Vertical period cards: 3 only -->
                            <div style="display: flex; flex-direction: column; justify-content: space-between; height: 100%; gap: 10px;">
                                <!-- This Month – blue -->
                                <div style="background:#fff; border:1px solid #e5e7eb; border-left:4px solid #005baa; border-radius:8px; padding:12px 14px;">
                                    <div style="font-size:1.1rem; font-weight:700; color:#005baa;"><?= fmtLKR($thisMonth) ?></div>
                                    <div style="font-size:0.72rem; color:#6b7280; margin-top:2px; text-transform:uppercase; letter-spacing:.4px;">This Month</div>
                                </div>
                                <!-- This Quarter – blue -->
                                <div style="background:#fff; border:1px solid #e5e7eb; border-left:4px solid #005baa; border-radius:8px; padding:12px 14px;">
                                    <div style="font-size:1.1rem; font-weight:700; color:#005baa;"><?= fmtLKR($thisQuarter) ?></div>
                                    <div style="font-size:0.72rem; color:#6b7280; margin-top:2px; text-transform:uppercase; letter-spacing:.4px;">This Quarter</div>
                                </div>
                                <!-- This Year – indigo -->
                                <div style="background:#fff; border:1px solid #e5e7eb; border-left:4px solid #6366f1; border-radius:8px; padding:12px 14px;">
                                    <div style="font-size:1.1rem; font-weight:700; color:#4338ca;"><?= fmtLKR($thisYear) ?></div>
                                    <div style="font-size:0.72rem; color:#6b7280; margin-top:2px; text-transform:uppercase; letter-spacing:.4px;">This Year</div>
                                </div>
                            </div>

                            <!-- Interactive Native SVG Line Chart area -->
                            <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; display:flex; flex-direction:column; position: relative;">
                                <div style="display:flex; justify-content:space-between; margin-bottom: 25px;">
                                    <div>
                                        <h4 style="margin:0; font-size: 1.05rem; color: #1e293b;">Donation Trends</h4>
                                        <span style="font-size:0.75rem; color:#64748b;">Click points to view exact amounts</span>
                                    </div>
                                    <div style="font-size:0.8rem; color:#64748b; background:#f1f5f9; padding:4px 10px; border-radius:12px; height:max-content;">Last 6 Months</div>
                                </div>
                                
                                <div style="width: 100%; flex: 1; min-height: 140px; position: relative;">
                                    <svg width="100%" height="100%" viewBox="0 -10 <?= $svgW ?> <?= $svgH + 20 ?>" preserveAspectRatio="none" style="overflow: visible;">
                                        <!-- Fill polygon mapping exactly to the line -->
                                        <polygon points="<?= $polyPoints ?>" fill="rgba(0, 91, 170, 0.15)" stroke="none"></polygon>
                                        
                                        <!-- Path overlay mapping trend -->
                                        <polyline points="<?= $linePoints ?>" fill="none" stroke="#005baa" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"></polyline>
                                        
                                        <!-- Interactive plotting points -->
                                        <?php foreach($monthlyTrend as $i => $t): 
                                            $x = $i * $xStep;
                                            $y = $svgH - (($t->total / $svgMax) * $svgH * 0.85);
                                            $monthLabel = $t->month_label;
                                            $valLabel = 'LKR ' . number_format($t->total, 2);
                                            $anchor = 'middle';
                                            if($i === 0) $anchor = 'start';
                                            if($i === count($monthlyTrend) - 1) $anchor = 'end';
                                        ?>
                                            <!-- Hit area to make clicking easier -->
                                            <circle cx="<?= $x ?>" cy="<?= $y ?>" r="15" fill="transparent" style="cursor: pointer; transition: 0.2s;" onclick="showTooltip('<?= $monthLabel ?>', '<?= $valLabel ?>', event)" onmouseover="event.target.nextElementSibling.setAttribute('r', '6');" onmouseout="event.target.nextElementSibling.setAttribute('r', '4');"></circle>
                                            <!-- Visible coordinate circle -->
                                            <circle cx="<?= $x ?>" cy="<?= $y ?>" r="4" fill="#fff" stroke="#005baa" stroke-width="2" style="pointer-events: none; transition: 0.2s;"></circle>
                                            <!-- Baseline X-Axis Label -->
                                            <text x="<?= $x ?>" y="<?= $svgH + 18 ?>" text-anchor="<?= $anchor ?>" font-size="12" fill="#94a3b8" font-family="sans-serif" font-weight="500"><?= $monthLabel ?></text>
                                        <?php endforeach; ?>
                                    </svg>

                                    <!-- Absolute Tooltip container -->
                                    <div id="svg-tooltip" style="display: none; position: absolute; background: #111827; color: #fff; padding: 8px 12px; border-radius: 6px; font-size: 0.8rem; pointer-events: none; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); transform: translate(-50%, -100%); margin-top: -12px; z-index: 10;">
                                        <div id="tt-month" style="font-size: 0.72rem; color: #94a3b8; margin-bottom: 3px;"></div>
                                        <div id="tt-val" style="font-weight: 600; color: #fff;"></div>
                                        <!-- CSS triangle -->
                                        <div style="position: absolute; bottom: -4px; left: 50%; transform: translateX(-50%); width: 0; height: 0; border-left: 5px solid transparent; border-right: 5px solid transparent; border-top: 5px solid #111827;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Transactions -->
                        <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin-top:20px;">
                            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 20px;">
                                <h4 style="margin:0; font-size: 1.1rem; color: #1e293b; font-weight:600;">Recent transactions</h4>
                                <div style="display:flex; gap:10px;">
                                    <button style="background:#fff; border:1px solid #e5e7eb; color:#475569; font-size:0.8rem; padding:6px 12px; border-radius:16px; cursor:pointer; transition:background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#fff'" onclick="showContent('payments', document.querySelectorAll('.menu-item')[1])">
                                        See all <i class="fa-solid fa-chevron-right" style="font-size:0.7rem; margin-left:4px;"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div style="width:100%; border-radius:8px; overflow:hidden; border:1px solid #f1f5f9;">
                                <div style="display:grid; grid-template-columns: 1.2fr 1fr 1.5fr 1fr; padding:12px 16px; background:#f8fafc; font-size:0.75rem; color:#94a3b8; font-weight:600; letter-spacing:0.5px; text-transform:uppercase;">
                                    <div>Date</div>
                                    <div style="text-align:right;">Amount</div>
                                    <div style="padding-left: 20px;">Payment Name</div>
                                    <div style="text-align:center;">Status</div>
                                </div>
                                <?php 
                                $recentTx = $kpis['recent_transactions'] ?? [];
                                foreach($recentTx as $tx): 
                                    $dateStr = date('d M H:i', strtotime($tx->date));
                                    $amountStr = number_format($tx->amount, 2);
                                    $donor = htmlspecialchars($tx->donor_name);
                                    $status = strtoupper($tx->status ?? 'PENDING');
                                    
                                    $statusColor = '#94a3b8';
                                    $statusBg = '#f1f5f9';
                                    if ($status === 'SUCCESS' || $status === 'COMPLETED') {
                                        $statusColor = '#16a34a';
                                        $statusBg = '#dcfce7';
                                    } elseif ($status === 'FAILED') {
                                        $statusColor = '#ef4444';
                                        $statusBg = '#fee2e2';
                                    }
                                ?>
                                <div style="display:grid; grid-template-columns: 1.2fr 1fr 1.5fr 1fr; padding:16px; border-bottom:1px solid #f8fafc; align-items:center;">
                                    <div style="font-size:0.85rem; color:#334155; font-weight:500;"><?= $dateStr ?></div>
                                    <div style="font-size:0.85rem; color:#64748b; font-weight:600; text-align:right;">LKR <?= $amountStr ?></div>
                                    <div style="padding-left:20px; display:flex; align-items:center; gap:10px;">
                                        <div style="width:28px; height:28px; border-radius:50%; background:#f1f5f9; color:#005baa; display:flex; align-items:center; justify-content:center; font-size:0.75rem; font-weight:bold;"><i class="fa-solid fa-user" style="opacity: 0.6;"></i></div>
                                        <span style="font-size:0.85rem; color:#334155; font-weight:500;"><?= $donor ?></span>
                                    </div>
                                    <div style="text-align:center;">
                                        <span style="display:inline-block; padding:4px 10px; border-radius:12px; font-size:0.7rem; font-weight:600; color:<?= $statusColor ?>; background:<?= $statusBg ?>;"><?= $status ?></span>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                                <?php if(empty($recentTx)): ?>
                                <div style="padding: 24px; text-align: center; color: #94a3b8; font-size: 0.85rem;">No recent transactions found.</div>
                                <?php endif; ?>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Donor Payments -->
                <div id="payments" class="content-section" style="display: none;">
                    <div class="content-header">
                        <h2>Financial Donations</h2>
                    </div>
                    <div class="content-body">
                        <div style="display: flex; gap: 16px; align-items: center; margin-bottom: 24px;">
                            <div class="search-bar" style="margin-bottom: 0; flex: 1;">
                                <span class="search-icon"><i class="fa-solid fa-magnifying-glass"></i></span>
                                <input type="text" class="search-input" placeholder="Search by donor ID, amount, or date..." id="payment-search">
                            </div>

                            <div class="filter-section" style="margin-bottom: 0; display: flex; gap: 12px;">
                                <select class="filter-select" id="date-range-filter">
                                    <option value="">All Dates</option>
                                    <option value="today">Today</option>
                                    <option value="week">This Week</option>
                                    <option value="month">This Month</option>
                                    <option value="quarter">This Quarter</option>
                                </select>
                                <select class="filter-select" id="amount-range-filter">
                                    <option value="">All Amounts</option>
                                    <option value="small">Under LKR 10,000</option>
                                    <option value="medium">LKR 10,000 - 50,000</option>
                                    <option value="large">Over LKR 50,000</option>
                                </select>
                            </div>

                            <div class="action-buttons" style="margin-bottom: 0;">
                                <button class="btn btn-secondary" onclick="exportPaymentsReport()">Export Report</button>
                            </div>
                        </div>

                        <div class="data-table">
                            <div class="table-header">
                                <h4>Financial Donations History</h4>
                            </div>
                            <div class="table-content" id="payments-table">
                                <div class="table-row" style="font-weight: 600; background: var(--gray-bg-color); grid-template-columns: 1fr 2fr 1.5fr 1.5fr 1fr;">
                                    <div class="table-cell">Payment ID</div>
                                    <div class="table-cell">Donor Name</div>
                                    <div class="table-cell">Amount</div>
                                    <div class="table-cell">Date</div>
                                    <div class="table-cell">Status</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Payment Details Modal -->
    <div id="paymentModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fa-solid fa-file-invoice" style="margin-right: 8px;"></i>Payment Details</h3>
                <button class="modal-close" onclick="closePaymentModal()">&times;</button>
            </div>
            <div id="feedback-details" style="padding: 0 20px;">
                <div class="form-group">
                    <label class="form-label">Payment ID</label>
                    <div class="form-input" style="background: var(--gray-bg-color);" id="modal-payment-id"></div>
                </div>
                <div class="form-group">
                    <label class="form-label">Donor Name</label>
                    <div class="form-input" style="background: var(--gray-bg-color);" id="modal-donor-id"></div>
                </div>
                <div class="form-group">
                    <label class="form-label">Amount</label>
                    <div class="form-input" style="background: var(--gray-bg-color); color: var(--primary-color); font-weight: bold; font-size: 1.1rem;" id="modal-amount"></div>
                </div>
                <div class="form-group">
                    <label class="form-label">Date</label>
                    <div class="form-input" style="background: var(--gray-bg-color);" id="modal-date"></div>
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <div class="form-input" style="background: var(--gray-bg-color);">
                        <span class="status-badge status-completed" id="modal-status"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Transaction Reference</label>
                    <div class="form-input" style="background: var(--gray-bg-color);" id="modal-reference"></div>
                </div>
            </div>
            <div class="action-buttons" style="margin-top: 2rem;">
                <button type="button" class="btn btn-primary" onclick="printPaymentDetails()">
                    <i class="fa-solid fa-print"></i> Print
                </button>
                <button type="button" class="btn btn-secondary" onclick="closePaymentModal()">Close</button>
            </div>
        </div>
    </div>
    
    <script>
        const ROOT = '<?= ROOT ?>';
        // Add minimal scripts for closing modals if not fully handled in finance.js
        function closePaymentModal() {
            document.getElementById('paymentModal').style.display = 'none';
        }

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
    <script src="/life-connect/public/assets/js/admin/finance.js?v=<?= time() ?>"></script>
</body>
</html>