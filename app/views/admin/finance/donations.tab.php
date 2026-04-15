<!-- Donor Payments -->
<div id="payments" class="content-section" style="display: none;">
    <div class="content-header">
        <h2>Financial Donations</h2>
        <p>Monitor global financial contributions, track transaction histories, and analyze donation trends.</p>
    </div>
    <div class="content-body">
        <div style="display: flex; gap: 16px; align-items: center; margin-bottom: 24px;">
            <div class="search-bar" style="margin-bottom: 0; flex: 1;">
                <span class="search-icon"><i class="fa-solid fa-magnifying-glass"></i></span>
                <input type="text" class="search-input" placeholder="Search.." id="payment-search">
            </div>

            <div class="filter-section" style="margin-bottom: 0; display: flex; gap: 12px; align-items: center;">
                <div style="position: relative;">
                    <button type="button" id="date-range-icon" title="Filter by Date Range" onclick="toggleDateRangePicker()" style="background: white; border: 1px solid #e2e8f0; border-radius: 8px; width: 44px; height: 44px; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #64748b; transition: all 0.2s;">
                        <i class="fa-solid fa-calendar-days"></i>
                    </button>
                    <div id="date-range-picker" style="display: none; position: absolute; top: 52px; right: 0; background: white; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.25rem; box-shadow: 0 10px 25px rgba(0,0,0,0.1); z-index: 100; width: 280px;">
                        <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                            <h5 style="margin: 0; font-size: 0.85rem; color: #1e293b; font-weight: 700;">Filter by Range</h5>
                            <div>
                                <label style="display: block; font-size: 0.7rem; color: #94a3b8; text-transform: uppercase; font-weight: 700; margin-bottom: 4px;">Quick Selections</label>
                                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px;">
                                    <button type="button" class="quick-range-btn" id="btn-3m" onclick="setQuickRange(3)" style="background: #f1f5f9; border: none; padding: 6px; border-radius: 6px; font-size: 0.7rem; font-weight: 600; color: #475569; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#e2e8f0'" onmouseout="if(!this.classList.contains('active')) this.style.background='#f1f5f9'">Last 3m</button>
                                    <button type="button" class="quick-range-btn" id="btn-6m" onclick="setQuickRange(6)" style="background: #f1f5f9; border: none; padding: 6px; border-radius: 6px; font-size: 0.7rem; font-weight: 600; color: #475569; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#e2e8f0'" onmouseout="if(!this.classList.contains('active')) this.style.background='#f1f5f9'">Last 6m</button>
                                    <button type="button" class="quick-range-btn" id="btn-12m" onclick="setQuickRange(12)" style="background: #f1f5f9; border: none; padding: 6px; border-radius: 6px; font-size: 0.7rem; font-weight: 600; color: #475569; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#e2e8f0'" onmouseout="if(!this.classList.contains('active')) this.style.background='#f1f5f9'">Last 12m</button>
                                </div>
                            </div>
                            <div style="position: relative;">
                                <label style="display: block; font-size: 0.7rem; color: #94a3b8; text-transform: uppercase; font-weight: 700; margin-bottom: 4px;">Search Donor (Optional)</label>
                                <div style="position: relative; display: flex; align-items: center;">
                                    <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 10px; font-size: 0.75rem; color: #94a3b8; pointer-events: none;"></i>
                                    <input type="text" id="export-donor-search" list="donor-list" oninput="handleFilter()" placeholder="All Donors" style="width: 100%; padding: 0.5rem 0.5rem 0.5rem 28px; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 0.85rem;">
                                </div>
                                <datalist id="donor-list"></datalist>
                            </div>
                            <div>
                                <label style="display: block; font-size: 0.7rem; color: #94a3b8; text-transform: uppercase; font-weight: 700; margin-bottom: 4px;">From</label>
                                <input type="date" id="export-start-date" onchange="handleFilter()" max="<?= date('Y-m-d') ?>" style="width: 100%; padding: 0.5rem; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 0.85rem;">
                            </div>
                            <div>
                                <label style="display: block; font-size: 0.7rem; color: #94a3b8; text-transform: uppercase; font-weight: 700; margin-bottom: 4px;">To</label>
                                <input type="date" id="export-end-date" onchange="handleFilter()" max="<?= date('Y-m-d') ?>" style="width: 100%; padding: 0.5rem; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 0.85rem;">
                            </div>
                            <button onclick="resetDateRange()" style="margin-top: 0.5rem; background: #fee2e2; border: none; padding: 0.6rem; border-radius: 6px; cursor: pointer; color: #991b1b; font-weight: 700; font-size: 0.75rem; transition: all 0.2s;" onmouseover="this.style.background='#fecaca'" onmouseout="this.style.background='#fee2e2'">
                                <i class="fa-solid fa-rotate-left" style="margin-right: 4px;"></i> Reset Dates
                            </button>
                        </div>
                    </div>
                </div>
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
