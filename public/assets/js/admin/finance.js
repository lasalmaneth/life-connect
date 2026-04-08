// finance.js - Finance Admin Dashboard JavaScript

let allPayments = [];
let filteredPayments = [];

// Initialize
document.addEventListener('DOMContentLoaded', function() {
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

    const dateFilter = document.getElementById('date-range-filter');
    const amountFilter = document.getElementById('amount-range-filter');
    if (dateFilter) dateFilter.addEventListener('change', handleFilter);
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
                loadPaymentsTable();
            }
        })
        .catch(err => console.error('Failed to load donations:', err));
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
        empty.style.cssText = 'padding:24px; text-align:center; color:#94a3b8; font-size:0.85rem;';
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
            <div class="table-cell"><strong>LKR ${parseFloat(payment.amount).toLocaleString('en-US', {minimumFractionDigits:2})}</strong></div>
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
    const dateFilter = document.getElementById('date-range-filter').value;
    const amountFilter = document.getElementById('amount-range-filter').value;

    filteredPayments = [...allPayments];

    if (dateFilter) {
        filteredPayments = filteredPayments.filter(payment => {
            const paymentDate = new Date(payment.date);
            const today = new Date();
            switch (dateFilter) {
                case 'today':   return isSameDay(paymentDate, today);
                case 'week':    return isSameWeek(paymentDate, today);
                case 'month':   return isSameMonth(paymentDate, today);
                case 'quarter': return isSameQuarter(paymentDate, today);
                default:        return true;
            }
        });
    }

    if (amountFilter) {
        filteredPayments = filteredPayments.filter(payment => {
            const amt = parseFloat(payment.amount);
            switch (amountFilter) {
                case 'small':  return amt < 10000;
                case 'medium': return amt >= 10000 && amt <= 50000;
                case 'large':  return amt > 50000;
                default:       return true;
            }
        });
    }

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
    const start = new Date(d2); start.setDate(d2.getDate() - d2.getDay()); start.setHours(0,0,0,0);
    const end = new Date(start); end.setDate(start.getDate() + 6); end.setHours(23,59,59,999);
    return d1 >= start && d1 <= end;
}
function isSameMonth(d1, d2) { return d1.getMonth() === d2.getMonth() && d1.getFullYear() === d2.getFullYear(); }
function isSameQuarter(d1, d2) { return Math.floor(d1.getMonth()/3) === Math.floor(d2.getMonth()/3) && d1.getFullYear() === d2.getFullYear(); }

function formatDate(dateString) {
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('en-US', options);
}

// Payment Modal
function showPaymentModal(payment) {
    document.getElementById('modal-payment-id').textContent = '#' + payment.id;
    document.getElementById('modal-donor-id').textContent = payment.full_name || '—';
    document.getElementById('modal-amount').textContent = `LKR ${parseFloat(payment.amount).toLocaleString('en-US', {minimumFractionDigits:2})}`;
    document.getElementById('modal-date').textContent = formatDate(payment.date);

    const status = (payment.status || 'PENDING').toUpperCase();
    document.getElementById('modal-status').textContent = status;

    const txRef = payment.transaction_id || `TRX-${String(payment.id).padStart(4,'0')}`;
    document.getElementById('modal-reference').textContent = txRef;

    const modal = document.getElementById('paymentModal');
    modal.classList.add('show');
    modal.onclick = e => { if (e.target === modal) closePaymentModal(); };
    document.addEventListener('keydown', function esc(e) {
        if (e.key === 'Escape') { closePaymentModal(); document.removeEventListener('keydown', esc); }
    });
}

function closePaymentModal() {
    document.getElementById('paymentModal').classList.remove('show');
}

function printPaymentDetails() {
    const modalContent = document.querySelector('.modal-content').cloneNode(true);
    const actions = modalContent.querySelector('.action-buttons');
    if (actions) actions.remove();
    const orig = document.body.innerHTML;
    document.body.innerHTML = modalContent.outerHTML;
    window.print();
    document.body.innerHTML = orig;
    document.getElementById('paymentModal').classList.add('show');
}

function exportPaymentsReport() {
    let csv = "Payment ID,Donor Name,Amount,Date,Status\n";
    filteredPayments.forEach(p => {
        csv += `${p.id},"${p.full_name || ''}",${p.amount},"${p.date}",${p.status}\n`;
    });
    const blob = new Blob([csv], { type: 'text/csv' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url; a.download = `donations-report-${new Date().toISOString().split('T')[0]}.csv`;
    document.body.appendChild(a); a.click(); document.body.removeChild(a);
}

// SVG Chart Tooltip Logic
function showTooltip(month, val, event) {
    const tooltip = document.getElementById('svg-tooltip');
    document.getElementById('tt-month').innerText = month;
    document.getElementById('tt-val').innerText = val;
    const rect = tooltip.parentElement.getBoundingClientRect();
    tooltip.style.left = (event.clientX - rect.left) + 'px';
    tooltip.style.top  = (event.clientY - rect.top) + 'px';
    tooltip.style.display = 'block';
}

document.addEventListener('click', function(e) {
    if (!e.target.closest('circle')) {
        const tt = document.getElementById('svg-tooltip');
        if (tt) tt.style.display = 'none';
    }
});