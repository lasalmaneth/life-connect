document.addEventListener('DOMContentLoaded', () => {
    fetchDonations();

    // Search filter
    const searchInput = document.getElementById('donation-search');
    if (searchInput) {
        searchInput.addEventListener('input', filterDonations);
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', (e) => {
        const userInfo = document.querySelector('.user-info');
        const dropdown = document.getElementById('user-dropdown');

        if (dropdown && !userInfo.contains(e.target)) {
            dropdown.classList.remove('show');
        }
    });
});
// Also prevent caching on page load
window.addEventListener('pageshow', function (event) {
    // If page is loaded from cache, force reload
    if (event.persisted) {
        window.location.reload();
    }
});


function toggleUserDropdown() {
    const dropdown = document.getElementById('user-dropdown');
    if (dropdown) {
        dropdown.classList.toggle('show');
    }
    // Prevent event from bubbling to document click listener
    event.stopPropagation();
}


function logoLogout() {
    // No confirmation for logo logout
    // Replace current history entry to prevent back button
    window.history.replaceState(null, null, window.location.href);
    window.location.href = '/Life-Connect/public/financial-admin/logout?from=logo';
}

function logout() {
    // Confirmation for regular logout
    if (confirm('Are you sure you want to logout?')) {
        // Replace current history entry to prevent back button
        window.history.replaceState(null, null, window.location.href);
        window.location.href = '/Life-Connect/public/financial-admin/logout';
    }
}

// Also prevent caching on page load
window.addEventListener('pageshow', function (event) {
    // If page is loaded from cache, force reload
    if (event.persisted) {
        window.location.reload();
    }
});



function showContent(sectionId, element) {
    document.querySelectorAll('.menu-item').forEach(item => item.classList.remove('active'));
    element.classList.add('active');

    document.querySelectorAll('.content-section').forEach(section => section.style.display = 'none');
    const section = document.getElementById(sectionId);
    if (section) {
        section.style.display = 'block';
    }
}

function fetchDonations() {
    const table = document.getElementById('financial-donations-table');

    // Show loading state
    table.innerHTML = `
        <div class="table-row" style="font-weight: 600; background: var(--gray-bg-color);">
            <div class="table-cell">Donation ID</div>
            <div class="table-cell">Donor Name</div>
            <div class="table-cell">Amount</div>
            <div class="table-cell">Date</div>
            <div class="table-cell">Note</div>
            <div class="table-cell">Status</div>
        </div>
        <div class="table-row">
            <div class="table-cell" colspan="6" style="text-align: center; padding: 2rem;">
                <i class="fa-solid fa-spinner fa-spin"></i> Loading donations...
            </div>
        </div>
    `;

    fetch('/Life-Connect/public/financial-admin/getAllDonations')
        .then(res => {
            // If session expired, redirect to login
            if (res.status === 401) {
                window.location.href = '/Life-Connect/public/login';
                return;
            }
            // If access denied, redirect to login
            if (res.status === 403) {
                window.location.href = '/Life-Connect/public/login';
                return;
            }
            return res.json();
        })
        .then(data => {
            if (!data) return;

            // Clear table
            table.innerHTML = `
            <div class="table-row" style="font-weight: 600; background: var(--gray-bg-color);">
                <div class="table-cell">Donation ID</div>
                <div class="table-cell">Donor Name</div>
                <div class="table-cell">Amount</div>
                <div class="table-cell">Date</div>
                <div class="table-cell">Note</div>
                <div class="table-cell">Status</div>
            </div>
        `;

            // Check if there are donations
            if (data.length === 0) {
                table.innerHTML += `
                <div class="table-row">
                    <div class="table-cell" colspan="6" style="text-align: center; padding: 2rem; color: #666;">
                        No donations found.
                    </div>
                </div>
            `;
                return;
            }

            data.forEach(donation => {
                const row = document.createElement('div');
                row.classList.add('table-row');
                row.innerHTML = `
                <div class="table-cell">${donation.donation_id}</div>
                <div class="table-cell">${donation.full_name}</div>
                <div class="table-cell">$${parseFloat(donation.amount).toFixed(2)}</div>
                <div class="table-cell">${donation.date}</div>
                <div class="table-cell">${donation.note || '-'}</div>
                <div class="table-cell">
                    <span class="status-badge ${donation.status.toLowerCase()}">${donation.status}</span>
                </div>
            `;
                table.appendChild(row);
            });
        })
        .catch(error => {
            console.error('Error fetching donations:', error);
            table.innerHTML = `
            <div class="table-row" style="font-weight: 600; background: var(--gray-bg-color);">
                <div class="table-cell">Donation ID</div>
                <div class="table-cell">Donor Name</div>
                <div class="table-cell">Amount</div>
                <div class="table-cell">Date</div>
                <div class="table-cell">Note</div>
                <div class="table-cell">Status</div>
            </div>
            <div class="table-row">
                <div class="table-cell" colspan="6" style="text-align: center; padding: 2rem; color: #e74c3c;">
                    <i class="fa-solid fa-exclamation-triangle"></i> Error loading donations. Please try again.
                </div>
            </div>
        `;
        });
}

function filterDonations() {
    const query = this.value.toLowerCase();
    document.querySelectorAll('#financial-donations-table .table-row:not(:first-child)').forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(query) ? '' : 'none';
    });
}