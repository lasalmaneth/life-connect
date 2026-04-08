// aftercare.js - Aftercare Management (Pure UI Logic)

// Initialize
document.addEventListener('DOMContentLoaded', function () {
    console.log('Aftercare dashboard initialized');
    setupEventListeners();
});

// Show Content Section (Navigation)
function showContent(sectionId, element) {
    // Hide all content sections
    const sections = document.querySelectorAll('.content-section');
    sections.forEach(section => {
        section.style.display = 'none';
    });

    // Show selected section
    const selectedSection = document.getElementById(sectionId);
    if (selectedSection) {
        selectedSection.style.display = 'block';
    }

    // Update active menu item
    const menuItems = document.querySelectorAll('.menu-item');
    menuItems.forEach(item => {
        item.classList.remove('active');
    });

    if (element) {
        element.classList.add('active');
    }
}

// Setup Event Listeners
function setupEventListeners() {
    // Support Search (client-side DOM filtering)
    const searchInput = document.getElementById('support-search');
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const term = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('#support-requests-table .table-row:not(:first-child)');
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(term) ? 'flex' : 'none';
            });
        });
    }

    // Support Status Filter
    const statusFilter = document.getElementById('status-filter');
    if (statusFilter) {
        statusFilter.addEventListener('change', function(e) {
            const status = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('#support-requests-table .table-row:not(:first-child)');
            rows.forEach(row => {
                const rowStatus = row.querySelector('.status-badge').textContent.trim().toLowerCase();
                if (!status || rowStatus === status) {
                    row.style.display = 'flex';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
}

/**
 * Open the details modal using data passed from PHP
 * @param {Object} data - The request data object
 */
function openDetails(data) {
    if (!data) return;

    // Populate modal fields
    document.getElementById('modal-request-id').textContent = 'SUP' + String(data.id).padStart(3, '0');
    document.getElementById('modal-requester-type').textContent = data.patient_type || 'Patient';
    document.getElementById('modal-requester-id').textContent = data.patient_nic || 'N/A';
    document.getElementById('modal-description').textContent = data.description || data.reason || 'No description';
    document.getElementById('modal-amount').textContent = 'LKR 0.00'; // Amount not in DB
    document.getElementById('modal-date').textContent = data.submitted_date;

    // Status Badge
    const statusElement = document.getElementById('modal-status');
    const status = (data.status || 'pending').toLowerCase();
    statusElement.textContent = status.charAt(0).toUpperCase() + status.slice(1);
    statusElement.className = `status-badge status-${status}`;

    // Action Buttons
    const approveBtn = document.getElementById('approve-btn');
    const rejectBtn = document.getElementById('reject-btn');

    // We can't easily use the buttons in the modal for SSR without hidden forms, 
    // so it's better to tell the user to use the table buttons or add forms here.
    // For now, hide them in the modal to keep it safe.
    if (approveBtn) approveBtn.style.display = 'none';
    if (rejectBtn) rejectBtn.style.display = 'none';

    // Show modal
    const modal = document.getElementById('supportModal');
    modal.classList.add('show');
}

function closeSupportModal() {
    const modal = document.getElementById('supportModal');
    modal.classList.remove('show');
}

/**
 * Common Logic
 */
function logout() {
    if(confirm('Are you sure you want to logout?')) {
        // Path handled in view PHP
    }
}

function formatDate(dateString) {
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('en-US', options);
}