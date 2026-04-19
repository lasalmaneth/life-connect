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
                <div class="stat-number quick-stat-number" id="stat-avg-gift"><?= fmtLKR($kpis['avg_gift_size'] ?? 0) ?></div>
                <div class="stat-label">Average Donation Value</div>
                <div class="stat-change" style="color: #64748b" id="change-avg-gift">per contributor</div>
            </div>
            <div class="stat-card glass-card">
                <div class="stat-number quick-stat-number" id="stat-pending-requests" style="color: #f59e0b;"><?= number_format($support_stats['pending'] ?? 0) ?></div>
                <div class="stat-label">Pending Support Requests</div>
                <div class="stat-change" style="color: #64748b" id="change-pending-requests">Awaiting Review</div>
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
                            $x = $chartPadding + ($i * $xStep);
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
            
            <div style="width:100%; border-radius:12px; overflow:hidden; border:1px solid #e5e7eb; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
                <div style="display:grid; grid-template-columns: 1fr 2fr 1.5fr 1.5fr; padding:14px 20px; background:#f8fafc; border-bottom: 2px solid #e2e8f0; font-size:0.75rem; color:#475569; font-weight:700; letter-spacing:0.05em; text-transform:uppercase;">
                    <div>Payment ID</div>
                    <div>Donor Name</div>
                    <div>Amount</div>
                    <div>Date</div>
                </div>
                <?php 
                $recentTx = $kpis['recent_transactions'] ?? [];
                foreach($recentTx as $tx): 
                    $dateStr = date('M d, Y', strtotime($tx->date));
                    $amountStr = number_format($tx->amount, 2);
                    $donor = htmlspecialchars($tx->donor_name);
                ?>
                <div class="recent-tx-row" 
                     onclick="showPaymentModal(<?= htmlspecialchars(json_encode($tx)) ?>)"
                     style="display:grid; grid-template-columns: 1fr 2fr 1.5fr 1.5fr; padding:18px 20px; border-bottom:1px solid #f1f5f9; align-items:center; cursor:pointer; transition: background 0.2s;">
                    <div style="font-size:0.85rem; color:#1e293b; font-weight:700;">#<?= $tx->id ?></div>
                    <div style="font-size:0.85rem; color:#475569; font-weight:500;"><?= $donor ?></div>
                    <div style="font-size:0.95rem; color:#0f172a; font-weight:800;">LKR <?= $amountStr ?></div>
                    <div style="font-size:0.85rem; color:#64748b; font-weight:500;"><?= $dateStr ?></div>
                </div>
                <?php endforeach; ?>
                <style>
                    .recent-tx-row:hover { background: #f0f7ff !important; }
                </style>
                <?php if(empty($recentTx)): ?>
                <div style="padding: 24px; text-align: center; color: #94a3b8; font-size: 0.85rem;">No recent transactions found.</div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>
