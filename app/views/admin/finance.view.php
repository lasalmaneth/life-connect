<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= ROOT ?>/public/assets/css/style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= ROOT ?>/public/assets/css/admin/style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <title>Financial Donations | LifeConnect</title>
</head>

<body style="background-color: #f8fafc; min-height: 100vh;">

    <?php
    if (session_status() === PHP_SESSION_NONE)
        session_start();
    $db = new class {
        use \App\Core\Database;
    };
    $uId = $_SESSION['user_id'] ?? 0;
    $adminData = $db->query("SELECT a.*, u.email, u.status FROM admins a JOIN users u ON a.user_id = u.id WHERE a.user_id = :id", ['id' => $uId]);
    $adminInfoFull = !empty($adminData) ? $adminData[0] : null;
    $adminName = $adminInfoFull ? ($adminInfoFull->first_name . ' ' . $adminInfoFull->last_name) : ($_SESSION['username'] ?? 'Admin');

    // Parse KPIs - ensure it is an array even if data is missing or false
    $kpis = (isset($data['kpis']) && is_array($data['kpis'])) ? $data['kpis'] : [];
    $totalContributors = $kpis['total_contributors'] ?? 0;
    $totalAmount = $kpis['total_amount'] ?? 0;
    $thisMonthContributors = $kpis['this_month_contributors'] ?? 0;


    $failedTransactions = $kpis['failed_transactions'] ?? 0;
    $failedThisMonth = $kpis['failed_this_month'] ?? 0;
    $retentionRate = $kpis['retention_rate'] ?? 0;

    $thisMonth = $kpis['this_month'] ?? 0;
    $prevMonth = $kpis['prev_month'] ?? 0;
    $thisQuarter = $kpis['this_quarter'] ?? 0;
    $prevQuarter = $kpis['prev_quarter'] ?? 0;
    $thisYear = $kpis['this_year'] ?? 0;

    // Short amount formatter e.g. 10000 -> LKR 10K
    function fmtLKR($n)
    {
        if ($n >= 1000000)
            return 'LKR ' . round($n / 1000000, 1) . 'M';
        if ($n >= 1000)
            return 'LKR ' . round($n / 1000, 1) . 'K';
        return 'LKR ' . number_format($n, 2);
    }

    // Trend SVG Math mapping - ensure it is an array
    $monthlyTrend = (isset($kpis['monthly_trend']) && is_array($kpis['monthly_trend'])) ? $kpis['monthly_trend'] : [];
    if (empty($monthlyTrend)) {
        // Fallback for empty data
        $monthlyTrend = [
            (object) ['month_label' => 'No Data', 'total' => 0],
            (object) ['month_label' => 'Current', 'total' => 0]
        ];
    }

    $svgMax = 1;
    foreach ($monthlyTrend as $t) {
        if ($t->total > $svgMax)
            $svgMax = $t->total;
    }

    $svgW = 600;
    $svgH = 120;
    $chartPadding = 30; // pixels to keep labels inside
    $paddingSteps = count($monthlyTrend) > 1 ? (count($monthlyTrend) - 1) : 1;
    $xStep = ($svgW - (2 * $chartPadding)) / $paddingSteps;

    $polyPoints = "$chartPadding,$svgH ";
    $linePoints = "";

    foreach ($monthlyTrend as $i => $t) {
        $x = $chartPadding + ($i * $xStep);
        $y = $svgH - (($t->total / $svgMax) * $svgH * 0.85);
        $polyPoints .= "$x,$y ";
        $linePoints .= "$x,$y ";
    }
    $polyPoints .= ($svgW - $chartPadding) . ",$svgH";
    ?>

    <div class="header">
        <div class="header-content">
            <div class="header-left" style="display: flex; align-items: center; gap: 1rem;">
                <!-- Mobile Toggle Button -->
                <button id="sidebar-toggle" class="sidebar-toggle" onclick="toggleSidebar()" aria-label="Toggle Menu">
                    <i class="fa-solid fa-bars"></i>
                </button>

                <a href="<?= ROOT ?>" style="text-decoration:none; display:flex; align-items:center; gap:10px;">
                    <img src="<?= ROOT ?>/public/assets/images/logo.png" alt="LifeConnect" style="height:40px;">
                    <div>
                        <strong
                            style="display:block; font-size:1.1rem; color:#003b6e; line-height:1.2;">LifeConnect</strong>
                        <p style="margin:0; font-size:.68rem; color:#6b7280;">Financial Administration</p>
                    </div>
                </a>
            </div>

            <div class="header-right" style="display: flex; align-items: center; gap: 1.5rem;">
                <nav style="display: flex; align-items: center; gap: 1rem;">
                    <a href="<?= ROOT ?>" class="nav-icon-link" title="Home"
                        style="color: #64748b; font-size: 1.2rem; transition: color 0.2s;">
                        <i class="fa-solid fa-house"></i>
                    </a>
                </nav>

                <div class="user-info-wrapper" id="userProfileToggleHeader" data-profile-toggle
                    style="cursor: pointer;">
                    <div class="user-avatar"><?= substr($adminName, 0, 1) ?></div>
                    <div class="user-details" style="display: flex; flex-direction: column; margin-left: 8px;">
                        <span class="user-name"
                            style="font-weight: 600; font-size: 0.9rem; color: #1e293b; line-height: 1.2;"><?= htmlspecialchars($adminName) ?></span>
                        <span class="user-role" style="font-size: 0.75rem; color: #64748b; font-weight: 500;">Finance
                            Admin</span>
                    </div>
                    <i class="fa-solid fa-chevron-down ms-2" style="font-size: 0.7rem; color: #94a3b8;"></i>

                    <?php
                    $adminRoleTitle = 'Financial Administrator';
                    $admin = $adminInfoFull;
                    $dropdownId = 'userProfileDropdownHeader';
                    include(__DIR__ . '/inc/profile_card.partial.php');
                    ?>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const toggle = document.getElementById('userProfileToggleHeader');
                const dropdown = document.getElementById('userProfileDropdownHeader');

                if (toggle && dropdown) {
                    toggle.addEventListener('click', function (e) {
                        e.stopPropagation();
                        dropdown.classList.toggle('active');
                    });
                }
            });
        </script>
    </div>
    </div>
    </div>
    </div>

    <!-- Sidebar Overlay -->
    <div id="sidebar-overlay" class="sidebar-overlay" onclick="toggleSidebar()"></div>

    <div class="container-fluid p-0">
        <div class="main-content">
            <div class="sidebar glass">
                <div class="sidebar-user-card">
                    <div class="sidebar-user-avatar">S</div>
                    <div class="sidebar-user-info">
                        <span class="sidebar-user-name">Sewmini</span>
                        <span class="sidebar-user-id">ID-00002</span>
                        <span class="sidebar-user-role">Finance Admin</span>
                    </div>
                </div>

                <div class="sidebar-nav">
                    <div class="menu-section">
                        <div class="menu-section-title">Core</div>
                        <a href="javascript:void(0)" class="menu-item active" onclick="showContent('dashboard', this)">
                            <span class="icon"><i class="fa-solid fa-chart-line"></i></span>
                            <span>Dashboard</span>
                        </a>
                    </div>

                    <div class="menu-section">
                        <div class="menu-section-title">Support Management</div>
                        <a href="javascript:void(0)" class="menu-item" onclick="showContent('support-requests', this)"
                            style="display: flex; align-items: center; justify-content: space-between;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <span class="icon"><i class="fa-solid fa-hand-holding-heart"></i></span>
                                <span>Support Requests</span>
                            </div>
                            <?php if (($support_stats['pending'] ?? 0) > 0): ?>
                                <span
                                    style="background: #ef4444; color: white; font-size: 0.65rem; font-weight: 800; padding: 2px 10px; border-radius: 20px; margin-right: 8px; display: inline-flex; align-items: center; justify-content: center;">+<?= $support_stats['pending'] ?></span>
                            <?php endif; ?>
                        </a>
                        <a href="javascript:void(0)" class="menu-item" onclick="showContent('vouchers', this)">
                            <span class="icon"><i class="fa-solid fa-ticket"></i></span>
                            <span>Voucher Management</span>
                        </a>
                    </div>

                    <div class="menu-section">
                        <div class="menu-section-title">Donation Management</div>
                        <a href="javascript:void(0)" class="menu-item" onclick="showContent('payments', this)"
                            style="display: flex; align-items: center; justify-content: space-between;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <span class="icon"><i class="fa-solid fa-money-bill-transfer"></i></span>
                                <span>Financial Donations</span>
                            </div>
                            <?php if (($kpis['today_donations_count'] ?? 0) > 0): ?>
                                <span
                                    style="background: #10b981; color: white; font-size: 0.65rem; font-weight: 800; padding: 2px 10px; border-radius: 20px; margin-right: 8px; display: inline-flex; align-items: center; justify-content: center;">+<?= $kpis['today_donations_count'] ?></span>
                            <?php endif; ?>
                        </a>
                    </div>

                    <div class="menu-section mt-auto">
                        <a href="javascript:void(0)" onclick="openModal('logout-modal')" class="menu-item text-danger">
                            <span class="icon"><i class="fa-solid fa-right-from-bracket"></i></span>
                            <span>Logout</span>
                        </a>
                    </div>
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
                    <button class="modal-close"
                        style="position: absolute; top: -15px; right: -15px; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 50%; background: #f1f5f9; border: none; cursor: pointer; color: #64748b; z-index: 10;"
                        onclick="closePaymentModal()">&times;</button>

                    <div style="display: flex; align-items: center; gap: 1.25rem;">
                        <!-- Status Icon -->
                        <div id="modal-status-icon-box"
                            style="flex-shrink: 0; width: 48px; height: 48px; background: #dcfce7; border-radius: 12px; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease;">
                            <i id="modal-status-icon" class="fa-solid fa-circle-check"
                                style="font-size: 20px; color: #16a34a;"></i>
                        </div>

                        <!-- Title -->
                        <div>
                            <h2
                                style="margin: 0; font-size: 1.5rem; font-weight: 800; color: #0f172a; line-height: 1.2;">
                                Payment Details</h2>
                        </div>
                    </div>

                    <p style="margin: 0; color: #64748b; font-size: 0.9rem; line-height: 1.5; font-weight: 500;">Details
                        of the financial donation transaction.</p>

                    <!-- Details Card (2-Column Grid) -->
                    <div
                        style="background: #f0f7ff; border-radius: 16px; padding: 1.5rem; display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div>
                            <span
                                style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Payment
                                ID</span>
                            <div id="modal-payment-id" style="font-size: 1.05rem; font-weight: 700; color: #1e293b;">-
                            </div>
                        </div>
                        <div>
                            <span
                                style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Status</span>
                            <div id="modal-status" style="font-size: 0.95rem; font-weight: 700; color: #16a34a;">-</div>
                        </div>

                        <div style="grid-column: span 2;">
                            <span
                                style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Donor
                                Name</span>
                            <div id="modal-donor-id" style="font-size: 1rem; font-weight: 700; color: #1e293b;">-</div>
                        </div>

                        <div style="grid-column: span 2;">
                            <span
                                style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Email
                                Address</span>
                            <div id="modal-email"
                                style="font-size: 0.9rem; font-weight: 600; color: #1e293b; word-break: break-all;">-
                            </div>
                        </div>

                        <div>
                            <span
                                style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Amount</span>
                            <div id="modal-amount" style="font-size: 1.1rem; font-weight: 800; color: #0f172a;">-</div>
                        </div>
                        <div>
                            <span
                                style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Date</span>
                            <div id="modal-date" style="font-size: 0.95rem; font-weight: 600; color: #0f172a;">-</div>
                        </div>

                        <div style="grid-column: span 2;">
                            <span
                                style="display: block; font-size: 0.65rem; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Donor
                                Note</span>
                            <div id="modal-note"
                                style="font-size: 0.9rem; font-weight: 500; color: #475569; font-style: italic; background: #f8fafc; padding: 10px; border-radius: 8px; border: 1px solid #e2e8f0; margin-top: 5px;">
                                -</div>
                        </div>
                    </div>

                    <!-- Footer Buttons -->
                    <div style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 0.5rem;">
                        <button type="button" onclick="printPaymentDetails()"
                            style="background: #005baa; color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: all 0.2s;">
                            <i class="fa-solid fa-print"></i> Print
                        </button>
                        <button type="button" onclick="closePaymentModal()"
                            style="background: #f1f5f9; color: #475569; border: none; padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 700; cursor: pointer; transition: background 0.2s;">Close</button>
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
        document.addEventListener('click', function (e) {
            if (!e.target.closest('circle')) {
                const tt = document.getElementById('svg-tooltip');
                if (tt) tt.style.display = 'none';
            }
        });

        function toggleUserDropdown() {
            const dropdown = document.getElementById('user-dropdown');
            if (dropdown) {
                dropdown.classList.toggle('show');
            }
            event.stopPropagation();
        }

        // Sidebar Mobile Toggle
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const body = document.body;

            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
            body.style.overflow = sidebar.classList.contains('active') ? 'hidden' : '';
        }

        // Generic Modal Helpers
        function openModal(id) {
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.add('show');
                document.body.style.overflow = 'hidden';
            }
        }
        function closeModal(id) {
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.remove('show');
                document.body.style.overflow = '';
            }
        }
    </script>
    <!-- Logout Confirmation Modal -->
    <div id="logout-modal" class="modal">
        <div class="modal-content" style="max-width: 420px; text-align: center; padding: 2.5rem;">
            <div style="font-size: 2.5rem; color: #003b6e; margin-bottom: 1.5rem;">
                <i class="fa-solid fa-right-from-bracket"></i>
            </div>
            <h3 style="font-size: 1.5rem; font-weight: 800; color: #0f172a; margin-bottom: 1rem;">Confirm Logout</h3>
            <p style="color: #64748b; line-height: 1.5; margin-bottom: 2rem;">Are you sure you want to logout? You will
                need to login again to access your dashboard.</p>
            <div style="display: flex; gap: 1rem; justify-content: center;">
                <button onclick="closeModal('logout-modal')" class="btn btn-secondary"
                    style="flex: 1; border-radius: 50px; padding: 0.75rem;">Cancel</button>
                <button onclick="window.location.href='<?= ROOT ?>/logout'" class="btn btn-danger"
                    style="flex: 1; border-radius: 50px; padding: 0.75rem;">Logout</button>
            </div>
        </div>
    </div>
    <!-- Custom Toast Notification Container -->
    <div id="toast-container"
        style="position: fixed; top: 24px; right: 24px; z-index: 9999; display: flex; flex-direction: column; gap: 12px; pointer-events: none;">
    </div>

    <!-- Dashboard Logic -->
    <script src="<?= ROOT ?>/public/assets/js/admin/finance.js?v=<?= time() ?>"></script>
    <!-- Reporting Logic -->
    <script src="<?= ROOT ?>/public/assets/js/admin/finance.reports.js?v=<?= time() ?>"></script>
</body>

</html>