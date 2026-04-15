// finance.js - Finance Admin Dashboard JavaScript

let allPayments = [];
let filteredPayments = [];
let activeRangeContext = ''; // Tracks 3m/6m/12m context for PDF title

// Initialize
document.addEventListener('DOMContentLoaded', function () {
    initializeDashboard();
    setupEventListeners();
    loadPaymentsFromApi();
});

// Show Content Section (Navigation)
function showContent(sectionId, element) {
    const sections = document.querySelectorAll('.content-section');
    sections.forEach(section => {
        section.style.display = 'none';
    });

    const selectedSection = document.getElementById(sectionId);
    if (selectedSection) {
        selectedSection.style.display = 'block';
        if (sectionId === 'payments') {
            loadPaymentsFromApi();
        } else if (sectionId === 'support-requests') {
            // Already loaded via PHP, but can add refresh if needed
        } else if (sectionId === 'vouchers') {
            // Logic for vouchers if needed
        }
    }

    const menuItems = document.querySelectorAll('.menu-item');
    menuItems.forEach(item => item.classList.remove('active'));
    if (element) element.classList.add('active');
}

// Initialize Dashboard
function initializeDashboard() {
    // Stats are rendered server-side via PHP; nothing to do here.
}

// Setup Event Listeners
function setupEventListeners() {
    const searchInput = document.getElementById('payment-search');
    if (searchInput) searchInput.addEventListener('input', handleSearch);

    const startDate = document.getElementById('export-start-date');
    const endDate = document.getElementById('export-end-date');
    const amountFilter = document.getElementById('amount-range-filter');

    if (startDate) startDate.addEventListener('change', handleFilter);
    if (endDate) endDate.addEventListener('change', handleFilter);
    if (amountFilter) amountFilter.addEventListener('change', handleFilter);
}

// Load Payments from API
function loadPaymentsFromApi() {
    fetch(`${ROOT}/financial-admin/getAllDonations`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                allPayments = data.donations;
                filteredPayments = [...allPayments];
                populateDonorDatalist();
                loadPaymentsTable();
            }
        })
        .catch(err => {
            console.error('Failed to load donations:', err);
            showToast("error", "Failed to connect to the donor database. Please refresh or try again.");
        });
}

function populateDonorDatalist() {
    const list = document.getElementById('donor-list');
    if (!list) return;
    
    // Extract unique names from ALL payments
    const names = [...new Set(allPayments.map(p => p.full_name))].filter(Boolean).sort();
    list.innerHTML = names.map(name => `<option value="${name}">`).join('');
}

// Quick Range Selection
function setQuickRange(months) {
    const startEl = document.getElementById('export-start-date');
    const endEl = document.getElementById('export-end-date');
    
    const today = new Date();
    const fromDate = new Date();
    fromDate.setMonth(today.getMonth() - months);
    
    endEl.value = today.toISOString().split('T')[0];
    startEl.value = fromDate.toISOString().split('T')[0];
    
    // Store context for report heading
    activeRangeContext = months === 12 ? "Annual Report (Last 12 Months)" : `Custom Report (Last ${months} Months)`;
    
    // Toggle active UI state
    document.querySelectorAll('.quick-range-btn').forEach(btn => {
        btn.classList.remove('active');
        btn.style.background = '#f1f5f9';
        btn.style.color = '#475569';
        btn.style.fontWeight = '600';
    });
    
    const activeBtn = document.getElementById(`btn-${months}m`);
    if (activeBtn) {
        activeBtn.classList.add('active');
        activeBtn.style.background = '#e0f2fe';
        activeBtn.style.color = '#0369a1';
        activeBtn.style.fontWeight = '700';
    }
    
    handleFilter();
}

// Load Payments Table
function loadPaymentsTable() {
    const table = document.getElementById('payments-table');
    if (!table) return;

    // Clear existing rows (except header)
    const existingRows = table.querySelectorAll('.table-row:not(:first-child)');
    existingRows.forEach(row => row.remove());

    if (filteredPayments.length === 0) {
        const empty = document.createElement('div');
        empty.className = 'table-row empty-message';
        empty.style.cssText = 'padding:24px; text-align:center; color:#94a3b8; font-size:0.85rem; display:block;';
        empty.textContent = 'No donations found.';
        table.appendChild(empty);
        return;
    }

    filteredPayments.forEach(payment => {
        const row = document.createElement('div');
        row.className = 'table-row';
        row.style.cursor = 'pointer';
        row.style.gridTemplateColumns = '1fr 2fr 1.5fr 1.5fr 1fr';

        const status = (payment.status || 'PENDING').toUpperCase();
        let statusColor = '#94a3b8', statusBg = '#f1f5f9';
        if (status === 'SUCCESS' || status === 'COMPLETED') { statusColor = '#16a34a'; statusBg = '#dcfce7'; }
        else if (status === 'FAILED') { statusColor = '#ef4444'; statusBg = '#fee2e2'; }
        else if (status === 'PENDING') { statusColor = '#d97706'; statusBg = '#fef3c7'; }

        row.innerHTML = `
            <div class="table-cell"><strong>#${payment.id}</strong></div>
            <div class="table-cell">${payment.full_name || '—'}</div>
            <div class="table-cell"><strong>LKR ${parseFloat(payment.amount).toLocaleString('en-US', { minimumFractionDigits: 2 })}</strong></div>
            <div class="table-cell">${formatDate(payment.date)}</div>
            <div class="table-cell">
                <span style="display:inline-block; padding:4px 10px; border-radius:12px; font-size:0.72rem; font-weight:600; color:${statusColor}; background:${statusBg};">${status}</span>
            </div>
        `;

        row.addEventListener('click', () => showPaymentModal(payment));
        table.appendChild(row);
    });
}

// Filter
function handleFilter() {
    const startEl = document.getElementById('export-start-date');
    const endEl = document.getElementById('export-end-date');
    const donorQuery = document.getElementById('export-donor-search').value.toLowerCase();
    const startDate = startEl.value;
    const endDate = endEl.value;
    const amountVal = document.getElementById('amount-range-filter').value;

    const todayStr = new Date().toISOString().split('T')[0];
    
    if (startDate) {
        endEl.min = startDate;
        endEl.max = todayStr;

        // Validation: Allow 12 months (1 year)
        let limit = new Date(startDate);
        limit.setMonth(limit.getMonth() + 12);
        const limitStr = limit.toISOString().split('T')[0];

        if (endDate && endDate > limitStr) {
            showToast("warning", "The selected date range cannot exceed 12 months (1 year). Please narrow your selection.");
            endEl.value = '';
            handleFilter();
            return;
        }

        if (endDate && endDate < startDate) {
            endEl.value = '';
            handleFilter();
            return;
        }
    } else {
        endEl.min = "";
        endEl.max = todayStr;
    }

    if (endDate) {
        startEl.max = endDate;
    } else {
        startEl.max = todayStr;
    }

    // Combined Filtering Logic
    filteredPayments = allPayments.filter(payment => {
        // Date check
        if (startDate && payment.date < startDate) return false;
        if (endDate && payment.date > endDate) return false;

        // Donor Search check
        if (donorQuery && !(payment.full_name || '').toLowerCase().includes(donorQuery)) return false;

        // Amount check
        const amt = parseFloat(payment.amount);
        if (amountVal === 'small' && amt >= 10000) return false;
        if (amountVal === 'medium' && (amt < 10000 || amt > 50000)) return false;
        if (amountVal === 'large' && amt <= 50000) return false;

        return true;
    });

    loadPaymentsTable();
}

// Handle Search
function handleSearch(event) {
    const searchTerm = event.target.value.toLowerCase();
    filteredPayments = allPayments.filter(p =>
        String(p.id).includes(searchTerm) ||
        (p.full_name || '').toLowerCase().includes(searchTerm) ||
        String(p.amount).includes(searchTerm) ||
        (p.status || '').toLowerCase().includes(searchTerm)
    );
    loadPaymentsTable();
}

// Date Helpers
function isSameDay(d1, d2) {
    return d1.getDate() === d2.getDate() && d1.getMonth() === d2.getMonth() && d1.getFullYear() === d2.getFullYear();
}
function isSameWeek(d1, d2) {
    const start = new Date(d2); start.setDate(d2.getDate() - d2.getDay()); start.setHours(0, 0, 0, 0);
    const end = new Date(start); end.setDate(start.getDate() + 6); end.setHours(23, 59, 59, 999);
    return d1 >= start && d1 <= end;
}
function isSameMonth(d1, d2) { return d1.getMonth() === d2.getMonth() && d1.getFullYear() === d2.getFullYear(); }
function isSameQuarter(d1, d2) { return Math.floor(d1.getMonth() / 3) === Math.floor(d2.getMonth() / 3) && d1.getFullYear() === d2.getFullYear(); }

function formatDate(dateString) {
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('en-US', options);
}

function toggleDateRangePicker() {
    const picker = document.getElementById('date-range-picker');
    picker.style.display = picker.style.display === 'none' ? 'block' : 'none';
}

function resetDateRange() {
    document.getElementById('export-start-date').value = '';
    document.getElementById('export-end-date').value = '';
    document.getElementById('export-donor-search').value = '';
    activeRangeContext = '';
    
    // Reset buttons
    document.querySelectorAll('.quick-range-btn').forEach(btn => {
        btn.classList.remove('active');
        btn.style.background = '#f1f5f9';
        btn.style.color = '#475569';
        btn.style.fontWeight = '600';
    });
    
    handleFilter();
    toggleDateRangePicker();
}

// Close date picker when clicking outside
document.addEventListener('click', function (e) {
    const picker = document.getElementById('date-range-picker');
    const iconButton = document.getElementById('date-range-icon');

    if (picker && picker.style.display === 'block') {
        if (!picker.contains(e.target) && !iconButton.contains(e.target)) {
            picker.style.display = 'none';
        }
    }
});

let currentPayment = null;

// Payment Modal
function showPaymentModal(payment) {
    currentPayment = payment;
    document.getElementById('modal-payment-id').textContent = '#' + payment.id;
    document.getElementById('modal-donor-id').textContent = payment.full_name || '—';
    document.getElementById('modal-email').textContent = payment.email || 'N/A';
    document.getElementById('modal-note').textContent = payment.note || 'No additional notes provided.';
    document.getElementById('modal-amount').textContent = `LKR ${parseFloat(payment.amount).toLocaleString('en-US', { minimumFractionDigits: 2 })}`;
    document.getElementById('modal-date').textContent = formatDate(payment.date);

    const status = (payment.status || 'PENDING').toUpperCase();
    const statusEl = document.getElementById('modal-status');
    const iconEl = document.getElementById('modal-status-icon');
    const iconBox = document.getElementById('modal-status-icon-box');

    statusEl.textContent = status;

    // Theming based on status
    if (status === 'SUCCESS' || status === 'COMPLETED') {
        statusEl.style.color = '#16a34a';
        iconEl.className = 'fa-solid fa-circle-check';
        iconEl.style.color = '#16a34a';
        iconBox.style.background = '#dcfce7';
    } else if (status === 'FAILED') {
        statusEl.style.color = '#ef4444';
        iconEl.className = 'fa-solid fa-circle-xmark';
        iconEl.style.color = '#ef4444';
        iconBox.style.background = '#fee2e2';
    } else {
        // Pending or others (Yellow/Orange)
        statusEl.style.color = '#d97706';
        iconEl.className = 'fa-solid fa-clock';
        iconEl.style.color = '#d97706';
        iconBox.style.background = '#fef3c7';
    }

    const modal = document.getElementById('paymentModal');
    modal.classList.add('show');
    modal.onclick = e => { if (e.target === modal) closePaymentModal(); };
    document.addEventListener('keydown', function esc(e) {
        if (e.key === 'Escape') { closePaymentModal(); document.removeEventListener('keydown', esc); }
    });
}

function closePaymentModal() {
    document.getElementById('paymentModal').classList.remove('show');
    currentPayment = null;
}

function printPaymentDetails() {
    if (!currentPayment) return;

    const printWindow = window.open('', '_blank');
    const logoUrl = `${ROOT}/public/assets/images/logo.png`;
    const amountStr = `LKR ${parseFloat(currentPayment.amount).toLocaleString('en-US', { minimumFractionDigits: 2 })}`;
    const dateStr = formatDate(currentPayment.date);
    const status = (currentPayment.status || 'PENDING').toUpperCase();
    const currentAdmin = typeof ADMIN_NAME !== 'undefined' ? ADMIN_NAME : 'System Administrator';

    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>&#8203;</title>
            <style>
                body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding: 40px; color: #334155; line-height: 1.6; background: #fff; }
                .receipt-container { max-width: 800px; margin: 0 auto; padding: 20px; position: relative; }
                .receipt-header { display: flex; align-items: center; justify-content: space-between; border-bottom: 2px solid #005baa; padding-bottom: 20px; margin-bottom: 30px; }
                .logo-area { display: flex; align-items: center; gap: 15px; }
                .logo-img { height: 50px; }
                .brand-name { font-size: 1.8rem; font-weight: 800; color: #003b6e; letter-spacing: -0.02em; }
                .receipt-title { font-size: 1.2rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.1em; }
                .details-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                .details-table td { padding: 14px 0; border-bottom: 1px solid #f1f5f9; vertical-align: top; }
                .label { font-weight: 700; color: #005baa; width: 220px; text-transform: uppercase; font-size: 0.7rem; letter-spacing: 0.05em; }
                .value { color: #0f172a; font-size: 0.95rem; font-weight: 500; }
                .amount-highlight { font-size: 1.6rem; font-weight: 800; color: #005baa; }
                .footer { margin-top: 60px; text-align: center; border-top: 1px solid #f1f5f9; padding-top: 25px; }
                .footer-text { font-size: 0.8rem; color: #94a3b8; margin: 0; }
                .auto-gen-msg { font-size: 0.75rem; color: #94a3b8; margin-top: 8px; display: block; font-weight: 500; }
                .admin-tag { font-size: 0.65rem; color: #cbd5e1; text-transform: uppercase; letter-spacing: 0.05em; margin-top: 15px; display: block; }
                @media print {
                    @page { margin: 15mm; }
                    body { padding: 0; }
                    .receipt-container { max-width: 100%; margin: 0; padding: 0; }
                }
            </style>
        </head>
        <body>
            <div class="receipt-container">
                <div class="receipt-header">
                    <div class="logo-area">
                        <img src="${logoUrl}" class="logo-img" onerror="this.style.display='none'">
                        <div class="brand-name">LifeConnect</div>
                    </div>
                    <div class="receipt-title">Donation Receipt #${currentPayment.id}</div>
                </div>
                <table class="details-table">
                    <tr><td class="label">Contributor Name</td><td class="value">${currentPayment.full_name || '—'}</td></tr>
                    <tr><td class="label">Email Address</td><td class="value">${currentPayment.email || 'N/A'}</td></tr>
                    <tr><td class="label">Donation Amount</td><td class="value amount-highlight">${amountStr}</td></tr>
                    <tr><td class="label">Transaction Date</td><td class="value">${dateStr}</td></tr>
                    <tr><td class="label">Status</td><td class="value" style="font-weight: 700;">${status}</td></tr>
                    <tr><td class="label">Donor Note / Message</td><td class="value" style="font-style: italic; color: #475569;">${currentPayment.note || 'No additional notes provided.'}</td></tr>
                </table>
                <div class="footer">
                    <p class="footer-text">Thank you for your generous contribution to LifeConnect. Your support saves lives.</p>
                    <span class="auto-gen-msg">This is a computer-generated receipt and does not require a physical signature.</span>
                    <span class="admin-tag">Report Generated by: ${currentAdmin}</span>
                </div>
            </div>
            <script>
                window.onload = function() {
                    window.print();
                    setTimeout(function() { window.close(); }, 500);
                };
            </script>
        </body>
        </html>
    `);
    printWindow.document.close();
}

// SVG Chart Tooltip Logic
function showTooltip(month, val, event) {
    const tooltip = document.getElementById('svg-tooltip');
    document.getElementById('tt-month').innerText = month;
    document.getElementById('tt-val').innerText = val;
    const rect = tooltip.parentElement.getBoundingClientRect();
    tooltip.style.left = (event.clientX - rect.left) + 'px';
    tooltip.style.top = (event.clientY - rect.top) + 'px';
    tooltip.style.display = 'block';
}

document.addEventListener('click', function (e) {
    if (!e.target.closest('circle')) {
        const tt = document.getElementById('svg-tooltip');
        if (tt) tt.style.display = 'none';
    }
});

// Toast Notification System
function showToast(type, message) {
    const container = document.getElementById('toast-container');
    if (!container) return;

    const toast = document.createElement('div');
    toast.style.cssText = `
        background: #fff;
        color: #334155;
        padding: 12px 16px;
        border-radius: 12px;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 320px;
        max-width: 450px;
        border-left: 4px solid #005baa;
        transform: translateX(120%);
        transition: transform 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        pointer-events: auto;
    `;

    let icon = "fa-circle-info";
    let color = "#005baa";

    if (type === "success") { icon = "fa-circle-check"; color = "#16a34a"; }
    else if (type === "error") { icon = "fa-circle-xmark"; color = "#ef4444"; }
    else if (type === "warning") { icon = "fa-triangle-exclamation"; color = "#f59e0b"; }

    toast.style.borderLeftColor = color;

    toast.innerHTML = `
        <div style="background: ${color}20; color: ${color}; width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 1.1rem;">
            <i class="fa-solid ${icon}"></i>
        </div>
        <div style="flex: 1;">
            <p style="margin: 0; font-size: 0.85rem; font-weight: 600; color: #1e293b;">${type.charAt(0).toUpperCase() + type.slice(1)}</p>
            <p style="margin: 2px 0 0; font-size: 0.8rem; color: #64748b; line-height: 1.4;">${message}</p>
        </div>
        <button style="background: none; border: none; color: #94a3b8; cursor: pointer; padding: 4px; font-size: 1rem; opacity: 0.6;" onclick="this.parentElement.remove()">
            <i class="fa-solid fa-times"></i>
        </button>
    `;

    container.appendChild(toast);

    // Trigger animation
    requestAnimationFrame(() => {
        toast.style.transform = 'translateX(0)';
    });

    // Auto-remove
    setTimeout(() => {
        if (toast.parentElement) {
            toast.style.transform = 'translateX(120%)';
            setTimeout(() => toast.remove(), 300);
        }
    }, 4500);
}

// Support Requests State
let currentSupportReq = null;

// Filter Support Requests (Local Search)
function filterSupportRequests() {
    const query = document.getElementById('support-search-input').value.toLowerCase();
    const statusFilter = document.getElementById('support-status-filter').value;
    const rows = document.querySelectorAll('.support-row');

    rows.forEach(row => {
        const searchData = row.getAttribute('data-search');
        const statusData = row.getAttribute('data-status');
        
        const matchesSearch = searchData.includes(query);
        const matchesStatus = (statusFilter === 'ALL' || statusData === statusFilter);

        row.style.display = (matchesSearch && matchesStatus) ? 'table-row' : 'none';
    });
}

// Refresh Support Requests (Reload Page)
function refreshSupportRequests() {
    location.reload();
}

// Update Support Status (AJAX)
function updateSupportStatus(id, newStatus) {
    if (!confirm(`Are you sure you want to ${newStatus.toLowerCase()} this support request?`)) return;

    const formData = new FormData();
    formData.append('id', id);
    formData.append('status', newStatus);

    const endpoint = (typeof USER_ROLE !== 'undefined' && USER_ROLE === 'AC_ADMIN') 
        ? `${ROOT}/aftercare-admin/updateSupportStatus` 
        : `${ROOT}/financial-admin/updateSupportStatus`;

    fetch(endpoint, {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showToast("success", `Request #${id} has been ${newStatus.toLowerCase()} successfully.`);
            // Update UI locally or reload
            const row = document.querySelector(`.support-row[onclick*="${id}"]`) || document.querySelector(`tr:has(button[onclick*="${id}"])`);
            if (row) {
                location.reload(); // Simplest to keep stats in sync
            } else {
                location.reload();
            }
        } else {
            showToast("error", data.message || "Failed to update status.");
        }
    })
    .catch(err => {
        console.error('Update failed:', err);
        showToast("error", "Network error. Please try again.");
    });
}

// Modal Logic for Support
function openSupportDetails(req) {
    currentSupportReq = req;
    document.getElementById('modal-req-id').innerText = req.id;
    document.getElementById('modal-req-name').innerText = req.patient_name;
    document.getElementById('modal-req-nic').innerText = req.patient_nic;
    document.getElementById('modal-req-date').innerText = formatDate(req.submitted_date);
    document.getElementById('modal-req-reason').innerText = req.reason;
    document.getElementById('modal-req-desc').innerText = `"${req.description || 'No detailed description provided.'}"`;
    document.getElementById('modal-req-amount').innerText = `LKR ${parseFloat(req.amount || 0).toLocaleString('en-US', { minimumFractionDigits: 2 })}`;
    
    const tag = document.getElementById('modal-req-status-tag');
    tag.innerText = req.status;
    tag.style.cssText = `padding: 0.5rem 1rem; border-radius: 8px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase;`;
    
    if (req.status === 'APPROVED') { tag.style.background = '#ecfdf5'; tag.style.color = '#10b981'; }
    else if (req.status === 'REJECTED') { tag.style.background = '#fff1f2'; tag.style.color = '#f43f5e'; }
    else { tag.style.background = '#f0f9ff'; tag.style.color = '#0ea5e9'; }

    const actionBox = document.getElementById('modal-req-actions');
    actionBox.style.display = (req.status === 'PENDING') ? 'flex' : 'none';

    const modal = document.getElementById('supportRequestModal');
    modal.style.display = 'flex';
}

function closeSupportModal() {
    document.getElementById('supportRequestModal').style.display = 'none';
}

function approveFromModal() {
    if (currentSupportReq) updateSupportStatus(currentSupportReq.id, 'APPROVED');
}

function rejectFromModal() {
    if (currentSupportReq) updateSupportStatus(currentSupportReq.id, 'REJECTED');
}