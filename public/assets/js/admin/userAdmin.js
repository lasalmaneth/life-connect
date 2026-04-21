// Global Modal Helpers (moved to top for maximum reliability)
window.openModal = function (modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'flex';
        setTimeout(() => {
            modal.classList.add('show');
        }, 10);
        document.body.style.overflow = 'hidden';
    }
};

window.closeModal = function (modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('show');
        setTimeout(() => {
            if (!modal.classList.contains('show')) {
                modal.style.display = 'none';
            }
        }, 300);
        document.body.style.overflow = '';
    }
};

// Application State
const appState = {
    currentSection: 'dashboard',
    users: [],
    documents: [],
    notifications: [],
    feedbacks: [],
    auditLogs: [],
    selectedDocuments: [],
    selectedFeedbacks: [],
    isProcessingNotif: false,
    isProcessingAudit: false,
    isProcessingFeedback: false
};

// Navigation Functions
function showContent(sectionId) {
    // Hide all sections
    document.querySelectorAll('.content-section').forEach(section => {
        section.style.display = 'none';
    });

    const targetSection = document.getElementById(sectionId);
    if (targetSection) {
        targetSection.style.display = 'block';
        appState.currentSection = sectionId;
    }

    // Update active menu item
    document.querySelectorAll('.menu-item').forEach(item => {
        item.classList.remove('active');
        // Check if this menu item corresponds to the section
        const onclickAttr = item.getAttribute('onclick');
        if (onclickAttr && onclickAttr.includes(`'${sectionId}'`)) {
            item.classList.add('active');
        }
    });

    // Load section-specific data
    loadSectionData(sectionId);
}

// Handle Hash Navigation on Load
document.addEventListener('DOMContentLoaded', function () {
    if (window.location.hash) {
        const sectionId = window.location.hash.substring(1);
        if (document.getElementById(sectionId)) {
            showContent(sectionId);
        }
    }
});

// Data Loading Functions
function loadSectionData(sectionId) {
    switch (sectionId) {
        case 'dashboard':
            fetchDashboardStats();
            break;
        case 'accounts':
            fetchUsers();
            break;
        case 'notifications':
            fetchNotifications();
            break;
        case 'audit-logs':
            fetchAuditLogs();
            break;
        case 'feedbacks':
            // Handled in feedback_management.php
            break;
    }
}

async function fetchDashboardStats() {
    try {
        const response = await fetch(`${ROOT}/user-admin/getDashboardStats`);
        const data = await response.json();
        if (data.success) {
            updateDashboardUI(data.stats);
        }
    } catch (error) {
        console.error('Error fetching dashboard stats:', error);
    }
}

function updateDashboardUI(stats) {
    const setText = (id, value) => {
        const el = document.getElementById(id);
        if (el) el.textContent = value;
    };

    // 7 cards (all derived from DB via /user-admin/getDashboardStats)
    setText('stat-total-users', stats.totalUsers ?? 0);
    setText('stat-pending-docs', (Number(stats.status_PENDING || stats.status_pending || 0)));
    setText('stat-suspended-users', (Number(stats.status_SUSPENDED || stats.status_suspended || 0)));
    setText('stat-active-users', (Number(stats.status_ACTIVE || stats.status_active || 0)));
    setText('stat-withdrawn-users', (Number(stats.status_WITHDRAW_REQUEST || stats.status_withdraw_request || stats.status_WITHDRAWN || stats.status_withdrawn || 0)));
    setText('stat-patients', stats.role_PATIENT ?? 0);
    setText('stat-hospitals', stats.role_HOSPITAL ?? 0);

    // Update changes
    const setChange = (id, count) => {
        const el = document.getElementById(id);
        if (el) {
            if (count > 0) {
                el.innerHTML = `↑ ${count} this month`;
                el.style.display = 'block';
            } else {
                el.style.display = 'none';
            }
        }
    }

    setChange('change-total-users', stats.usersThisMonth ?? 0);
    setChange('change-pending-docs', stats.pendingThisMonth ?? 0);
    setChange('change-suspended-users', stats.suspendedThisMonth ?? 0);
    setChange('change-active-users', stats.activeThisMonth ?? 0);
    setChange('change-withdrawn-users', stats.withdrawnThisMonth ?? 0);
    setChange('change-patients', stats.patientsThisMonth ?? 0);
    setChange('change-hospitals', stats.hospitalsThisMonth ?? 0);

    // Update tab counts
    setText('tab-count-all', stats.totalUsers ?? 0);
    setText('tab-count-active', Number(stats.status_ACTIVE || stats.status_active || 0));
    setText('tab-count-pending', Number(stats.status_PENDING || stats.status_pending || 0));
    setText('tab-count-suspended', Number(stats.status_SUSPENDED || stats.status_suspended || 0));
    setText('tab-count-withdrawn', Number(stats.status_WITHDRAW_REQUEST || stats.status_withdraw_request || stats.status_WITHDRAWN || stats.status_withdrawn || 0));

    // Update pending users badge in nav
    const pendingUsers = Number(stats.status_PENDING || stats.status_pending || 0);
    const navBadge = document.getElementById('nav-pending-users-badge');
    if (navBadge) {
        if (pendingUsers > 0) {
            navBadge.textContent = '+' + pendingUsers;
            navBadge.style.display = 'inline-block';
        } else {
            navBadge.style.display = 'none';
        }
    }

    // Update Doughnut Chart with real values
    updateUserChart(stats);

    // Update Weekly Registration Activity with real values
    updateWeeklyActivityChart(stats);

    // Update Activity Feed
    if (stats.activities) {
        renderActivityFeed(stats.activities);
    }
}

function updateWeeklyActivityChart(stats) {
    const chartContainer = document.getElementById('weekly-bar-chart');
    if (!chartContainer) return;

    if (!stats.weekly_chart_data || stats.weekly_chart_data.length === 0) {
        chartContainer.innerHTML = '<div style="width:100%; text-align:center; color:#64748b; padding-top:40px;">No data available</div>';
        return;
    }

    // Update the Stats Summary
    document.getElementById('stat-weekly-total').textContent = stats.weekly_total ?? 0;
    document.getElementById('stat-weekly-avg').textContent = stats.weekly_average ?? 0;

    const growthEl = document.getElementById('stat-weekly-growth');
    if (growthEl) {
        const growth = stats.weekly_growth ?? 0;
        growthEl.textContent = (growth >= 0 ? '+' : '') + growth + '%';
        growthEl.style.color = growth >= 0 ? '#059669' : '#dc2626';
    }

    // Render Bars
    chartContainer.innerHTML = '';
    const maxVal = Math.max(...stats.weekly_chart_data.map(d => d.count), 1); // Avoid div by zero

    // Blue shades for varied hues
    const barColors = ['#005baa', '#1e40af', '#2563eb', '#3b82f6', '#60a5fa', '#93c5fd', '#bfdbfe'];

    stats.weekly_chart_data.forEach((data, index) => {
        const heightPercent = (data.count / maxVal) * 80; // scale to 80% max height
        const barDiv = document.createElement('div');
        barDiv.className = 'bar';
        barDiv.style.height = '0%'; // Start at 0 for animation
        barDiv.style.transition = `height 0.8s ease-out ${index * 0.1}s`;
        barDiv.style.background = barColors[index % barColors.length]; // Apply varied blue hues

        barDiv.innerHTML = `
            <div class="bar-value" style="color: ${barColors[index % barColors.length]}">${data.count}</div>
            <div class="bar-label" style="color: ${barColors[index % barColors.length]}">${data.day}</div>
        `;

        chartContainer.appendChild(barDiv);

        // Trigger animation
        setTimeout(() => {
            barDiv.style.height = heightPercent + '%';
        }, 50);
    });
}

function updateUserChart(stats) {
    const data = [
        { label: "Donors", value: Number(stats.role_DONOR || 0), color: "#005baa" },
        { label: "Patients", value: Number(stats.totalPatients || 0), color: "#a4c8e1" },
        { label: "Custodians", value: Number(stats.role_CUSTODIAN || 0), color: "#059669" },
        { label: "Hospitals", value: Number(stats.role_HOSPITAL || 0), color: "#74b9ff" },
        { label: "Medical Schools", value: Number(stats.role_MEDICAL_SCHOOL || 0), color: "#16a34a" }
    ];

    // Update HTML legend counts
    const legendCounts = document.querySelectorAll('.chart-legend .legend-count');
    if (legendCounts.length >= 5) {
        legendCounts[0].textContent = stats.role_DONOR || 0;
        legendCounts[1].textContent = stats.totalPatients || 0;
        legendCounts[2].textContent = stats.role_CUSTODIAN || 0;
        legendCounts[3].textContent = stats.role_HOSPITAL || 0;
        legendCounts[4].textContent = stats.role_MEDICAL_SCHOOL || 0;
    }

    drawCssDoughnutChart(data);
}

function drawCssDoughnutChart(data) {
    const chart = document.getElementById('css-user-chart');
    const totalEl = document.getElementById('css-doughnut-total');
    const tooltip = document.getElementById('chart-tooltip');
    if (!chart || !totalEl || !tooltip) return;

    const total = data.reduce((sum, d) => sum + d.value, 0);
    totalEl.textContent = total.toLocaleString();

    if (total === 0) {
        chart.style.background = 'conic-gradient(#e2e8f0 0% 100%)';
        chart.onmousemove = null;
        chart.onmouseleave = null;
        return;
    }

    let gradientParts = [];
    let currentAngle = 0;
    const slices = [];

    data.forEach(d => {
        if (d.value === 0) return;
        const percentage = (d.value / total) * 100;
        const nextAngle = currentAngle + percentage;

        gradientParts.push(`${d.color} ${currentAngle}% ${nextAngle}%`);

        slices.push({
            label: d.label,
            value: d.value,
            percent: percentage.toFixed(1),
            start: currentAngle,
            end: nextAngle
        });

        currentAngle = nextAngle;
    });

    chart.style.background = `conic-gradient(${gradientParts.join(', ')})`;

    // Add Tooltip Hover Logic
    chart.onmousemove = (e) => {
        const rect = chart.getBoundingClientRect();
        const centerX = rect.width / 2;
        const centerY = rect.height / 2;

        const x = e.clientX - rect.left - centerX;
        const y = e.clientY - rect.top - centerY;
        const distance = Math.sqrt(x * x + y * y);

        // Only trigger if mouse is between the inner circle (hole, radius 50) and outer edge (radius 90)
        if (distance < 50 || distance > 90) {
            tooltip.style.opacity = 0;
            return;
        }

        // Calculate angle (Top is 0 degrees, clockwise)
        let angleDeg = Math.atan2(y, x) * (180 / Math.PI) + 90;
        if (angleDeg < 0) angleDeg += 360;

        const anglePercent = (angleDeg / 360) * 100;
        const found = slices.find(s => anglePercent >= s.start && anglePercent <= s.end);

        if (found) {
            tooltip.innerHTML = `<div style="font-weight: 700; margin-bottom: 2px;">${found.label}</div>
                                 <div style="font-size: 0.8rem; color: #cbd5e1;">${found.value.toLocaleString()} users (${found.percent}%)</div>`;

            tooltip.style.left = (e.clientX - rect.left + chart.offsetLeft) + 'px';
            tooltip.style.top = (e.clientY - rect.top + chart.offsetTop) + 'px';
            tooltip.style.opacity = 1;
        } else {
            tooltip.style.opacity = 0;
        }
    };

    chart.onmouseleave = () => tooltip.style.opacity = 0;
}

// Initialize dashboard
function initDashboard() {
    fetchDashboardStats();
}

window.onload = initDashboard;

// Update activity feed every 30 seconds
async function updateActivityFeedServer() {
    try {
        const response = await fetch(`${ROOT}/user-admin/getDashboardStats`);
        const data = await response.json();
        if (data.success && data.stats.activities) {
            renderActivityFeed(data.stats.activities);
        }
    } catch (error) {
        console.error('Error auto-updating activity feed:', error);
    }
}

function renderActivityFeed(activities) {
    const feed = document.querySelector('.activity-feed');
    if (!feed) return;

    // Clear existing items but keep title
    const title = feed.querySelector('.activity-title');
    feed.innerHTML = '';
    if (title) feed.appendChild(title);

    if (!activities || activities.length === 0) {
        feed.insertAdjacentHTML('beforeend', '<div style="padding: 20px; text-align: center; color: #64748b;">No recent activity</div>');
        return;
    }

    activities.forEach(activity => {
        const item = document.createElement('div');
        item.className = 'activity-item';

        // Format time
        const date = new Date(activity.date);
        const now = new Date();
        const diff = Math.floor((now - date) / 1000); // seconds

        let timeStr = 'Just now';
        if (diff < 60) timeStr = diff + 's ago';
        else if (diff < 3600) timeStr = Math.floor(diff / 60) + 'm ago';
        else if (diff < 86400) timeStr = Math.floor(diff / 3600) + 'h ago';
        else timeStr = date.toLocaleDateString();

        item.innerHTML = `
            <div class="activity-icon ${activity.category}">
                <i class="fa-solid fa-${activity.type}"></i>
            </div>
            <div class="activity-content">
                <div class="activity-text">${activity.title}</div>
                <div class="activity-detail" style="font-size: 0.85rem; color: #64748b;">${activity.detail}</div>
                <div class="activity-time">${timeStr}</div>
            </div>
        `;
        feed.appendChild(item);
    });
}

setInterval(updateActivityFeedServer, 30000);

// Tab synchronization with status filter
function setUserTab(el) {
    // Update UI
    document.querySelectorAll('.user-tab').forEach(t => t.classList.remove('active'));
    el.classList.add('active');

    // Update hidden filter and fetch
    const status = el.getAttribute('data-status');
    const statusEl = document.getElementById('status-filter');
    if (statusEl) {
        statusEl.value = status;
        fetchUsers();
    }
}

function syncTabsWithFilter() {
    const statusEl = document.getElementById('status-filter');
    if (!statusEl) return;
    const status = statusEl.value;

    document.querySelectorAll('.user-tab').forEach(t => {
        if (t.getAttribute('data-status') === status) {
            t.classList.add('active');
        } else {
            t.classList.remove('active');
        }
    });
}

// User Account Management Functions
async function fetchUsers() {
    try {
        const searchEl = document.getElementById('user-search');
        const statusEl = document.getElementById('status-filter');
        const roleEl = document.getElementById('role-filter');

        const searchTerm = searchEl ? searchEl.value : '';
        const status = statusEl ? statusEl.value : '';
        const role = roleEl ? roleEl.value : '';

        const qs = new URLSearchParams({
            search: searchTerm,
            status: status,
            role: role,
        });

        const response = await fetch(`${ROOT}/user-admin/getUsers?${qs.toString()}`);
        const data = await response.json();

        if (!data || !data.success) {
            showToast('error', (data && data.message) ? data.message : 'Failed to load users.');
            appState.users = [];
            renderUsersTable();
            return;
        }

        const users = Array.isArray(data.users)
            ? data.users
            : (data.users ? Object.values(data.users) : []);

        appState.users = users;
        renderUsersTable();
    } catch (error) {
        console.error('Error fetching users:', error);
        showToast('error', 'Failed to load users.');
    }
}

function ensureModalOnBody(modalId) {
    const modal = document.getElementById(modalId);
    if (!modal) return;
    if (modal.parentElement !== document.body) {
        document.body.appendChild(modal);
    }
}

function renderUsersTable() {
    const tableContent = document.getElementById('users-table');
    if (!tableContent) return;
    const headerRow = tableContent.querySelector('.table-row');

    tableContent.innerHTML = '';
    if (headerRow) tableContent.appendChild(headerRow);

    const users = Array.isArray(appState.users) ? appState.users : [];

    users.forEach(user => {
        const row = document.createElement('div');
        row.className = 'table-row';
        row.style.cursor = 'pointer';

        row.innerHTML = `
            <div class="table-cell name" data-label="User">
                <span>
                    <strong>${user.username}</strong><br>
                    <small>${user.email}</small>
                </span>
            </div>
            <div class="table-cell" data-label="Role">${formatRole(user.role)}</div>
            <div class="table-cell status" data-label="Status">
                <span class="status-badge status-${user.status.toLowerCase()}">${formatStatus(user.status)}</span>
            </div>
            <div class="table-cell" data-label="Registration">${new Date(user.created_at).toLocaleDateString()}</div>
        `;

        tableContent.appendChild(row);

        // Event listener for the whole row
        row.addEventListener('click', (e) => {
            if (!e.target.closest('button') && !e.target.closest('input[type="checkbox"]')) {
                console.log('Row clicked for user:', user.id);
                viewDetailedUser(user.id, user.role, user.status);
            }
        });

    });

}

async function updateUserStatus(userId, status) {
    try {
        const response = await fetch(`${ROOT}/user-admin/updateUserStatus`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ user_id: userId, status: status })
        });
        const data = await response.json();
        if (data.success) {
            showToast('success', data.message);
            fetchUsers();
            fetchDashboardStats();
        } else {
            showToast('error', data.message);
        }
    } catch (error) {
        console.error('Error updating status:', error);
    }
}

async function viewDetailedUser(userId, role, status) {
    try {
        showToast('info', 'Loading details...');

        const id = encodeURIComponent(String(userId ?? ''));
        const roleStr = (role === undefined || role === null) ? '' : String(role);
        const roleEnc = encodeURIComponent(roleStr);

        const url = (roleStr && roleStr !== 'undefined' && roleStr !== 'null')
            ? `${ROOT}/user-admin/getDetailedUser?id=${id}&role=${roleEnc}`
            : `${ROOT}/user-admin/getDetailedUser?id=${id}`;

        const response = await fetch(url);
        if (!response.ok) {
            showToast('error', `Failed to load details (${response.status}).`);
            return;
        }

        let data;
        try {
            data = await response.json();
        } catch (jsonErr) {
            showToast('error', 'Invalid server response. Check console.');
            console.error('JSON parse error:', jsonErr);
            return;
        }

        if (!data || !data.success) {
            showToast('error', (data && data.message) ? data.message : 'Failed to load user details.');
            return;
        }

        const user = data.user;
        const statusUpper = (user.status || '').toUpperCase();
        console.log("Detailed User Data Received:", user);

        try {
            // Hidden storage for form submission details
            document.getElementById('review-user-id').value = user.id;
            document.getElementById('review-user-role').value = user.role || '';
            document.getElementById('review-user-status').value = user.status || '';

            // Populate Card View
            document.getElementById('review-username-text').innerText = user.username || '-';
            document.getElementById('review-email-text').innerText = user.email || 'N/A';
            document.getElementById('review-phone-text').innerText = user.phone || 'No phone';
            document.getElementById('review-regdate-text').innerText = user.created_at ? "Member since " + new Date(user.created_at).toLocaleDateString() : 'N/A';
            document.getElementById('review-user-role-display').innerText = (user.role || 'USER').replace('_', ' ');

            const donorIdentity = document.getElementById('donor-identity-section');
            const hospitalIdentity = document.getElementById('hospital-identity-section');
            const summaryPhoneGroup = document.getElementById('review-summary-phone');
            const organDonorSection = document.getElementById('organ-donor-section');
            const deepDetails = document.getElementById('deep-details-section');

            const isHospital = (user.role && user.role.toUpperCase() === 'HOSPITAL');
            const isDonor = (user.role && user.role.toUpperCase() === 'DONOR');
            const isMedSchool = (user.role && user.role.toUpperCase() === 'MEDICAL_SCHOOL');
            const isRecipient = (user.role && (user.role.toUpperCase() === 'RECIPIENT_PATIENT' || user.role.toUpperCase() === 'AFTERCARE_PATIENT'));
            const isCustodian = (user.role && user.role.toUpperCase() === 'CUSTODIAN');
            const isAdmin = (user.role && ['ADMIN', 'U_ADMIN', 'F_ADMIN', 'AC_ADMIN', 'D_ADMIN'].includes(user.role.toUpperCase()));

            // Reset display
            if (donorIdentity) donorIdentity.style.display = 'none';
            if (hospitalIdentity) hospitalIdentity.style.display = 'none';
            const medIdentity = document.getElementById('medical-school-identity-section');
            if (medIdentity) medIdentity.style.display = 'none';
            const adminIdentity = document.getElementById('admin-identity-section');
            if (adminIdentity) adminIdentity.style.display = 'none';

            if (summaryPhoneGroup) summaryPhoneGroup.style.display = 'block';
            if (organDonorSection) organDonorSection.style.display = 'none';
            const recipientIdentity = document.getElementById('recipient-identity-section');
            if (recipientIdentity) recipientIdentity.style.display = 'none';
            const custodianIdentity = document.getElementById('custodian-identity-section');
            if (custodianIdentity) custodianIdentity.style.display = 'none';
            if (deepDetails) deepDetails.style.display = 'none';

            if (isDonor) {
                if (donorIdentity) donorIdentity.style.display = 'contents';
                if (organDonorSection) organDonorSection.style.display = 'contents';
                if (deepDetails) deepDetails.style.display = 'grid';

                const fullNameText = document.getElementById('review-fullname-text');
                if (fullNameText) fullNameText.innerText = (user.first_name || '') + ' ' + (user.last_name || '');

                const nicText = document.getElementById('review-nic-text');
                if (nicText) nicText.innerText = user.nic || 'N/A';

                const genderText = document.getElementById('review-gender-text');
                if (genderText) genderText.innerText = user.gender || 'N/A';

                const dobText = document.getElementById('review-dob-text');
                if (dobText) dobText.innerText = user.dob || 'N/A';

                const locText = document.getElementById('review-location-text');
                if (locText) locText.innerText = (user.district || 'Unspecified') + ' • ' + (user.ds_division || 'N/A');

                const gnText = document.getElementById('review-gn-text');
                if (gnText) gnText.innerText = user.gn_division || 'N/A';

                const addrText = document.getElementById('review-address-text');
                if (addrText) addrText.innerText = user.address || 'No address provided';

            } else if (isHospital) {
                if (hospitalIdentity) hospitalIdentity.style.display = 'contents';
                if (summaryPhoneGroup) summaryPhoneGroup.style.display = 'none'; // Hide generic phone

                // Populate Hospital Fields
                const hName = document.getElementById('review-hosp-name');
                if (hName) hName.innerText = user.first_name || '-';

                const hReg = document.getElementById('review-hosp-reg');
                if (hReg) hReg.innerText = user.nic || '-';

                const hTrans = document.getElementById('review-hosp-transplant');
                if (hTrans) hTrans.innerText = user.transplant_id || 'N/A';

                const hType = document.getElementById('review-hosp-type');
                if (hType) hType.innerText = user.facility_type || 'N/A';

                const hLicense = document.getElementById('review-hosp-license');
                if (hLicense) hLicense.innerText = user.medical_license_number || 'N/A';

                const hCmoName = document.getElementById('review-hosp-cmo-name');
                if (hCmoName) hCmoName.innerText = user.cmo_name || '-';

                const hCmoNic = document.getElementById('review-hosp-cmo-nic');
                if (hCmoNic) hCmoNic.innerText = user.cmo_nic || '-';

                const hDistrict = document.getElementById('review-hosp-district');
                if (hDistrict) hDistrict.innerText = user.district || 'N/A';

                const hPhone = document.getElementById('review-hosp-phone');
                if (hPhone) hPhone.innerText = user.hospital_contact_number || 'N/A';

                const hAddress = document.getElementById('review-hosp-address');
                if (hAddress) hAddress.innerText = user.address || 'No address provided';
            } else if (isMedSchool) {
                if (medIdentity) medIdentity.style.display = 'contents';

                const mName = document.getElementById('review-med-name');
                if (mName) mName.innerText = user.school_name || '-';

                const mUniv = document.getElementById('review-med-univ');
                if (mUniv) mUniv.innerText = user.univ_affiliation || '-';

                const mUgc = document.getElementById('review-med-ugc');
                if (mUgc) mUgc.innerText = user.ugc_number || '-';

                const mContact = document.getElementById('review-med-contact-name');
                if (mContact) mContact.innerText = user.contact_person || '-';

                const mPhone = document.getElementById('review-med-contact-phone');
                if (mPhone) mPhone.innerText = user.contact_phone || '-';

                const mDistrict = document.getElementById('review-med-district');
                if (mDistrict) mDistrict.innerText = user.district || '-';

                const mAddress = document.getElementById('review-med-address');
                if (mAddress) mAddress.innerText = user.address || 'No address provided';
            } else if (isAdmin) {
                if (adminIdentity) adminIdentity.style.display = 'contents';

                const adminStaffID = document.getElementById('review-admin-staff-id');
                if (adminStaffID) adminStaffID.innerText = user.staff_id || 'N/A';

                const adminDesignation = document.getElementById('review-admin-designation');
                if (adminDesignation) adminDesignation.innerText = user.designation || 'N/A';

                const adminContact = document.getElementById('review-admin-contact');
                if (adminContact) adminContact.innerText = user.admin_contact || 'N/A';
            } else if (isRecipient) {
                if (recipientIdentity) recipientIdentity.style.display = 'contents';

                const rType = document.getElementById('review-recipient-type');
                if (rType) rType.innerText = user.patient_type || 'N/A';
            } else if (isCustodian) {
                if (custodianIdentity) custodianIdentity.style.display = 'contents';

                const cDonor = document.getElementById('review-custodian-donor');
                if (cDonor) cDonor.innerText = user.represented_donor_name || 'N/A';

                const cRel = document.getElementById('review-custodian-relationship');
                if (cRel) cRel.innerText = user.relationship || '-';

                const cName = document.getElementById('review-custodian-name');
                if (cName) cName.innerText = (user.first_name || '-') + (user.last_name ? ' ' + user.last_name : '');

                const cNic = document.getElementById('review-custodian-nic');
                if (cNic) cNic.innerText = user.nic || '-';

                const cPhone = document.getElementById('review-custodian-phone');
                if (cPhone) cPhone.innerText = user.custodian_phone || user.phone || 'N/A';

                const cAddress = document.getElementById('review-custodian-address');
                if (cAddress) cAddress.innerText = user.address || 'No address provided';
            }

            document.getElementById('review-firstname').value = user.first_name || user.school_name || user.name || '';
            document.getElementById('review-lastname').value = user.last_name || '';
            document.getElementById('review-phone').value = user.phone || '';

            // Reset notices
            const suspNotice = document.getElementById('suspension-notice');
            const withNotice = document.getElementById('withdrawal-notice');
            if (suspNotice) suspNotice.style.display = 'none';
            if (withNotice) withNotice.style.display = 'none';

            if (statusUpper === 'SUSPENDED') {
                if (suspNotice) {
                    suspNotice.style.display = 'flex';
                    const reasonEl = document.getElementById('suspension-reason-text');
                    if (reasonEl) reasonEl.innerText = user.review_message || 'No reason specified.';
                }
            } else if (statusUpper === 'WITHDRAW_REQUEST' || statusUpper === 'WITHDRAWN') {
                if (withNotice) {
                    withNotice.style.display = 'flex';
                    const withReasonEl = document.getElementById('withdrawal-reason-text');
                    const withDateEl = document.getElementById('withdrawal-date-text');
                    if (withReasonEl) withReasonEl.innerText = user.withdrawal_reason || 'User has requested to withdraw from the system.';
                    if (withDateEl) withDateEl.innerText = 'Requested on: ' + (user.withdrawal_date ? new Date(user.withdrawal_date).toLocaleString() : 'N/A');
                }
            }

            const statusDropdown = document.getElementById('review-status-dropdown');
            const saveBtn = document.getElementById('btn-save-details');
            const verifSection = document.getElementById('verification-section');

            if (isAdmin) {
                if (statusDropdown) statusDropdown.disabled = true;
                if (saveBtn) saveBtn.style.display = 'none';
                if (verifSection) verifSection.style.display = 'none';
            } else {
                if (statusDropdown) statusDropdown.disabled = false;
                if (saveBtn) saveBtn.style.display = 'flex';
            }

            if (statusDropdown) statusDropdown.value = (user.status || 'PENDING').toUpperCase();
            document.getElementById('review-message').value = user.review_message || '';

            if (verifSection && !isAdmin) {
                if (statusUpper === 'PENDING') {
                    verifSection.style.display = 'block';
                    document.getElementById('verify-genuine').checked = false;
                    document.getElementById('verify-registry').checked = false;
                    // Show role-specific verifications
                    const donorControls = document.getElementById('donor-verification-controls');
                    const hospitalControls = document.getElementById('hospital-verification-controls');
                    const medControls = document.getElementById('medical-school-verification-controls');

                    if (user.role && (user.role.toLowerCase() === 'donor' || user.role.toLowerCase() === 'custodian')) {
                        if (donorControls) donorControls.style.display = 'block';
                        if (hospitalControls) hospitalControls.style.display = 'none';
                        if (medControls) medControls.style.display = 'none';
                    } else if (user.role && user.role.toLowerCase() === 'hospital') {
                        if (donorControls) donorControls.style.display = 'none';
                        if (hospitalControls) hospitalControls.style.display = 'block';
                        if (medControls) medControls.style.display = 'none';
                        if (document.getElementById('hosp-reg-num-text')) {
                            document.getElementById('hosp-reg-num-text').innerText = user.registration_number || 'N/A';
                        }
                    } else if (user.role && user.role.toLowerCase() === 'medical_school') {
                        if (donorControls) donorControls.style.display = 'none';
                        if (hospitalControls) hospitalControls.style.display = 'none';
                        if (medControls) medControls.style.display = 'block';
                    } else {
                        if (donorControls) donorControls.style.display = 'none';
                        if (hospitalControls) hospitalControls.style.display = 'none';
                        if (medControls) medControls.style.display = 'none';
                    }
                } else {
                    verifSection.style.display = 'none';
                    document.getElementById('verify-genuine').checked = (statusUpper === 'ACTIVE');
                    document.getElementById('verify-registry').checked = (statusUpper === 'ACTIVE');
                    if (document.getElementById('verify-med-registry')) {
                        document.getElementById('verify-med-registry').checked = (statusUpper === 'ACTIVE');
                    }
                }
            }

            checkVerificationStatus();
            ensureModalOnBody('review-user-modal');
            openModal('review-user-modal');
        } catch (uiErr) {
            console.error("UI Population Error:", uiErr);
            showToast('error', 'Critical UI error. Check console.');
        }
    } catch (error) {
        console.error('Error fetching user details:', error);
        showToast('error', 'Failed to load user records.');
    }
}

function checkVerificationStatus() {
    const genuine = document.getElementById('verify-genuine').checked;
    const donorRegistry = document.getElementById('verify-registry').checked;
    const hospitalRegistry = document.getElementById('verify-hospital-registry').checked;

    const status = document.getElementById('review-status-dropdown').value;
    const originalStatus = document.getElementById('review-user-status').value;
    const userRoleElement = document.getElementById('review-user-role-display'); // We can parse role from here or appState
    const currentRole = userRoleElement ? userRoleElement.innerText.split('|')[0].trim().toLowerCase() : '';

    let canSave = false;
    if (status === 'ACTIVE') {
        if (originalStatus === 'PENDING') {
            // Force verification for donors/hospitals
            if (currentRole === 'donor' || currentRole === 'custodian') {
                canSave = genuine && donorRegistry;
            } else if (currentRole === 'hospital') {
                canSave = genuine && hospitalRegistry;
            } else if (currentRole === 'medical_school') {
                const medRegistry = document.getElementById('verify-med-registry').checked;
                canSave = genuine && medRegistry;
            } else {
                canSave = true; // Other roles
            }
        } else {
            canSave = true;
        }
    } else if (status === 'SUSPENDED') {
        canSave = true;
    } else if (status === 'PENDING') {
        canSave = (originalStatus !== 'PENDING');
    }

    // Reversion UI logic
    const verifSection = document.getElementById('verification-section');
    if (verifSection) {
        if (status === 'PENDING') {
            verifSection.style.display = 'block';
            if (originalStatus !== 'PENDING') {
                document.getElementById('verify-genuine').checked = false;
                document.getElementById('verify-registry').checked = false;
                document.getElementById('verify-hospital-registry').checked = false;
            }

            // Ensure correct role controls are shown on reversion select
            const donorControls = document.getElementById('donor-verification-controls');
            const hospitalControls = document.getElementById('hospital-verification-controls');
            const medControls = document.getElementById('medical-school-verification-controls');
            if (currentRole === 'donor' || currentRole === 'custodian') {
                if (donorControls) donorControls.style.display = 'block';
                if (hospitalControls) hospitalControls.style.display = 'none';
                if (medControls) medControls.style.display = 'none';
            } else if (currentRole === 'hospital') {
                if (donorControls) donorControls.style.display = 'none';
                if (hospitalControls) hospitalControls.style.display = 'block';
                if (medControls) medControls.style.display = 'none';
            } else if (currentRole === 'medical_school') {
                if (donorControls) donorControls.style.display = 'none';
                if (hospitalControls) hospitalControls.style.display = 'none';
                if (medControls) medControls.style.display = 'block';
            }
        } else if (originalStatus !== 'PENDING') {
            verifSection.style.display = 'none';
        }
    }

    const btnSave = document.getElementById('btn-save-details');
    const btnText = document.getElementById('btn-save-text');
    const btnIcon = document.getElementById('btn-save-icon');
    const iconBox = document.getElementById('review-status-icon-box');
    const icon = document.getElementById('review-status-icon');

    if (canSave) {
        btnSave.disabled = false;
        btnSave.style.opacity = '1';
        btnSave.style.cursor = 'pointer';
    } else {
        btnSave.disabled = true;
        btnSave.style.opacity = '0.5';
        btnSave.style.cursor = 'not-allowed';
    }

    // Dynamic Styling based on Status
    const suspensionNotice = document.getElementById('suspension-notice');
    if (suspensionNotice) suspensionNotice.style.display = 'none'; // Reset notice on any status change

    if (status === 'ACTIVE') {
        btnSave.style.background = '#059669'; // Emerald-600
        btnText.innerText = 'Confirm Approval';
        btnIcon.className = 'fa-solid fa-circle-check';
        iconBox.style.background = '#ecfdf5'; // Emerald-50
        icon.className = 'fa-solid fa-circle-check';
        icon.style.color = '#059669';
    } else if (status === 'SUSPENDED') {
        btnSave.style.background = '#dc2626'; // Red-600
        btnText.innerText = 'Suspend Account';
        btnIcon.className = 'fa-solid fa-user-lock';
        iconBox.style.background = '#fee2e2'; // Red-50
        icon.className = 'fa-solid fa-circle-xmark';
        icon.style.color = '#dc2626';
    } else if (status === 'WITHDRAWN' || status === 'WITHDRAW_REQUEST') {
        btnSave.style.background = '#475569'; // Slate-600
        btnText.innerText = 'Finalize Withdrawal';
        btnIcon.className = 'fa-solid fa-user-slash';
        iconBox.style.background = '#f1f5f9'; // Slate-50
        icon.className = 'fa-solid fa-user-slash';
        icon.style.color = '#475569';
    } else {
        btnSave.style.background = '#1e56a0'; // Default Blue
        btnText.innerText = 'Save Changes';
        btnIcon.className = 'fa-solid fa-save';
        iconBox.style.background = '#eff6ff'; // Blue-50
        icon.className = 'fa-solid fa-circle-info';
        icon.style.color = '#1e56a0';
    }
}

function generateReviewMessage() {
    const genuine = document.getElementById('verify-genuine').checked;
    const registry = document.getElementById('verify-registry').checked;
    const status = document.getElementById('review-status-dropdown').value;
    const msgBox = document.getElementById('review-message');

    const originalStatus = document.getElementById('review-user-status').value;

    // Don't overwrite if the admin has already typed something custom 
    // (Only auto-generate if message is empty or matches standard patterns)
    const currentMsg = msgBox.value.trim();
    const standardPatterns = [
        "",
        "Account verified successfully. All documentation matches official records.",
        "Verification failed: Profile information and submitted details could not be validated for authenticity.",
        "Verification failed: NIC record could not be verified via the official Election Commission registry.",
        "Verification failed: Profile data authenticity concerns and NIC record could not be verified.",
        "Verification reset: This account has been returned to pending status for details re-evaluation.",
        "Account reactivated: Following administrative review, your access has been restored and all issues have been resolved.",
        "Account suspended for administrative review.",
        "user requested to withdraw so now suspended account"
    ];

    if (currentMsg !== "" && !standardPatterns.includes(currentMsg)) return;

    if (status === 'ACTIVE') {
        if (originalStatus === 'SUSPENDED') {
            msgBox.value = "Account reactivated: Following administrative review, your access has been restored and all issues have been resolved.";
        } else {
            msgBox.value = "Account verified successfully. All documentation matches official records.";
        }
    } else if (status === 'SUSPENDED') {
        if (originalStatus === 'WITHDRAW_REQUEST' || originalStatus === 'WITHDRAWN') {
            msgBox.value = "user requested to withdraw so now suspended account";
            return;
        }

        const donorRegistry = document.getElementById('verify-registry').checked;
        const hospitalRegistry = document.getElementById('verify-hospital-registry').checked;
        const medRegistryEl = document.getElementById('verify-med-registry');
        const medRegistry = medRegistryEl ? medRegistryEl.checked : false;
        const userRoleElement = document.getElementById('review-user-role-display');
        const roleText = userRoleElement ? userRoleElement.innerText.split('|')[0].trim().toLowerCase() : '';
        const currentRole = roleText.replace(' ', '_');

        let registry = false;
        let registryName = "";
        let registryFailTerm = "";

        if (currentRole === 'hospital') {
            registry = hospitalRegistry;
            registryName = "Hospital PHSRC registry";
            registryFailTerm = "Hospital registration";
        } else if (currentRole === 'medical_school') {
            registry = medRegistry;
            registryName = "official UGC universities registry";
            registryFailTerm = "UGC accreditation number";
        } else {
            registry = donorRegistry;
            registryName = "official Election Commission registry";
            registryFailTerm = "NIC record";
        }

        if (!genuine && !registry) {
            msgBox.value = `Verification failed: Profile data authenticity concerns and ${registryFailTerm} could not be verified.`;
        } else if (!genuine) {
            msgBox.value = "Verification failed: Profile information and submitted details could not be validated for authenticity.";
        } else if (!registry) {
            msgBox.value = `Verification failed: ${registryFailTerm} could not be verified via the ${registryName}.`;
        } else {
            msgBox.value = "Account suspended for administrative review.";
        }
    } else if (status === 'PENDING') {
        const originalStatus = document.getElementById('review-user-status').value;
        if (originalStatus !== 'PENDING') {
            msgBox.value = "Verification reset: This account has been returned to pending status for details re-evaluation.";
        } else {
            msgBox.value = "";
        }
    } else {
        msgBox.value = "";
    }
}

async function executeStatusUpdate(userId, role, newStatus, currentAction, reviewMessage, data) {
    try {
        const response = await fetch(`${ROOT}/user-admin/reviewUser`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                user_id: userId,
                role: role,
                action: currentAction,
                data: data,
                new_status: newStatus,
                review_message: reviewMessage
            })
        });

        const text = await response.text();
        let result;
        try {
            result = JSON.parse(text);
        } catch (e) {
            console.error('SERVER SENT MALFORMED DATA:', text);
            showToast('error', 'Server error: Malformed data received.');
            return;
        }

        if (result.success) {
            showToast('success', result.message || 'Updated successfully');
            closeModal('review-user-modal');
            fetchUsers();
            fetchDashboardStats();
        } else {
            showToast('error', result.message || 'Failed to update record');
        }
    } catch (error) {
        console.error('Error submitting review:', error);
        showToast('error', 'Update failed: Check your connection.');
    }
}

async function submitUserReview(action) {
    const userId = document.getElementById('review-user-id').value;
    const role = document.getElementById('review-user-role').value;
    const newStatus = document.getElementById('review-status-dropdown').value;
    const reviewMessage = document.getElementById('review-message').value;

    // Two-stage Inline Suspension Confirmation
    if (newStatus === 'SUSPENDED') {
        const notice = document.getElementById('suspension-notice');
        const btnText = document.getElementById('btn-save-text');
        const btnIcon = document.getElementById('btn-save-icon');

        if (notice && notice.style.display === 'none') {
            notice.style.display = 'flex';
            if (btnText) btnText.innerText = 'Yes, Suspend Account';
            if (btnIcon) btnIcon.className = 'fa-solid fa-user-slash';
            return; // Stop here for first stage
        }
    }

    const data = {
        first_name: document.getElementById('review-firstname')?.value || '',
        last_name: document.getElementById('review-lastname')?.value || '',
        phone: document.getElementById('review-phone')?.value || ''
    };

    let submitAction = action;
    if (action === 'UPDATE') {
        const currentStatus = document.getElementById('review-user-status').value;
        if (newStatus !== currentStatus) {
            submitAction = (newStatus === 'ACTIVE') ? 'APPROVE' : (newStatus === 'SUSPENDED' ? 'REJECT' : 'UPDATE');
        }
    }

    await executeStatusUpdate(userId, role, newStatus, submitAction, reviewMessage, data);
}

async function editUser(userId) {
    try {
        const response = await fetch(`${ROOT}/user-admin/getUser?id=${userId}`);
        const data = await response.json();
        if (data.success) {
            const user = data.user;
            document.getElementById('edit-user-id').value = user.id;
            document.getElementById('edit-username').value = user.username;
            document.getElementById('edit-email').value = user.email;
            document.getElementById('edit-role').value = user.role.toLowerCase();
            openModal('edit-user-modal');
        } else {
            showToast('error', data.message);
        }
    } catch (error) {
        console.error('Error fetching user:', error);
    }
}

// Handle Edit User Form Submission
document.getElementById('edit-user-form')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const userId = document.getElementById('edit-user-id').value;
    const updatedData = {
        username: document.getElementById('edit-username').value,
        email: document.getElementById('edit-email').value,
        role: document.getElementById('edit-role').value
    };

    try {
        const response = await fetch(`${ROOT}/user-admin/updateUser`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: userId, data: updatedData })
        });
        const data = await response.json();
        if (data.success) {
            showToast('success', data.message);
            closeModal('edit-user-modal');
            fetchUsers();
        } else {
            showToast('error', data.message);
        }
    } catch (error) {
        console.error('Error updating user:', error);
    }
});

// Modal functions have been moved to the global script block at the top of the file.




// Notification Functions
async function fetchNotifications() {
    try {
        const response = await fetch(`${ROOT}/user-admin/getNotifications`);
        const data = await response.json();
        if (data.success) {
            appState.notifications = data.notifications;
            renderNotificationsTable();
        }
    } catch (error) {
        console.error('Error fetching notifications:', error);
    }
}

function renderNotificationsTable() {
    const tableContent = document.getElementById('notifications-table');
    const headerRow = tableContent ? tableContent.querySelector('.table-row') : null;
    if (!tableContent || !headerRow) return;

    tableContent.innerHTML = '';
    tableContent.appendChild(headerRow);

    // Add delegation listener once if not already present
    if (!tableContent.dataset.hasListener) {
        tableContent.addEventListener('click', (e) => {
            const row = e.target.closest('.clickable-row');
            if (row && row.dataset.notifId) {
                e.stopImmediatePropagation();
                openNotificationDetail(row.dataset.notifId);
            }
        });
        tableContent.dataset.hasListener = 'true';
    }

    const notifs = Array.isArray(appState.notifications) ? appState.notifications : [];

    notifs.forEach(notification => {
        const row = document.createElement('div');
        row.className = 'table-row clickable-row';
        row.style.cursor = 'pointer';
        row.dataset.notifId = notification.id;

        row.innerHTML = `
                    <div class="table-cell name" data-label="Notification">
                        <strong>${notification.recipient_name || notification.recipient || 'System Record'}</strong><br>
                        <small>${notification.title}</small>
                    </div>
                    <div class="table-cell" data-label="Type">${(notification.type || 'SYSTEM').toUpperCase()}</div>
                    <div class="table-cell status" data-label="Status">
                        <span class="status-badge status-${notification.is_read ? 'active' : 'pending'}">${notification.is_read ? 'Read' : 'Unread'}</span>
                    </div>
                    <div class="table-cell" data-label="Priority">
                        <span class="status-badge status-${getPriorityClass(notification.priority)}">${notification.priority || 'LOW'}</span>
                    </div>
                    <div class="table-cell" data-label="Sent">${notification.created_at ? new Date(notification.created_at).toLocaleString() : 'N/A'}</div>
                `;
        tableContent.appendChild(row);
    });
}

function openNotificationDetail(notifId) {
    if (appState.isProcessingNotif) return;
    appState.isProcessingNotif = true;
    setTimeout(() => { appState.isProcessingNotif = false; }, 300);

    console.log('Requesting Notification Detail for ID:', notifId);
    console.trace('Notification detail trigger source:');

    // Ensure modal is unique and on body
    ensureModalOnBody('notif-details-modal');
    const notification = (appState.notifications || []).find(n => n.id == notifId);
    if (notification) {
        const titleEl = document.getElementById('notif-modal-title');
        const dateEl = document.getElementById('notif-modal-date');
        const recipientEl = document.getElementById('notif-recipient-text');
        const typeEl = document.getElementById('notif-type-text');
        const statusEl = document.getElementById('notif-status-text');
        const bodyEl = document.getElementById('notif-message-body');

        if (titleEl) titleEl.textContent = notification.title;
        if (dateEl) dateEl.textContent = 'Sent on: ' + (notification.created_at ? new Date(notification.created_at).toLocaleString() : 'N/A');
        if (recipientEl) recipientEl.textContent = notification.recipient_name || notification.recipient;
        if (typeEl) typeEl.textContent = (notification.type || 'SYSTEM').toUpperCase();

        if (statusEl) {
            statusEl.textContent = notification.is_read ? 'Read' : 'Unread';
            statusEl.className = `status-badge status-${notification.is_read ? 'active' : 'pending'}`;
        }

        if (bodyEl) {
            bodyEl.textContent = notification.message;
            bodyEl.className = 'modal-break-word'; // Prevent horizontal scroll
        }

        const icon = document.getElementById('notif-type-icon');
        const iconBox = document.getElementById('notif-type-icon-box');

        if (icon && iconBox) {
            if (notification.type === 'alert') {
                icon.className = 'fa-solid fa-triangle-exclamation';
                icon.style.color = '#dc2626';
                iconBox.style.background = '#fee2e2';
            } else if (notification.type === 'approval') {
                icon.className = 'fa-solid fa-circle-check';
                icon.style.color = '#059669';
                iconBox.style.background = '#ecfdf5';
            } else {
                icon.className = 'fa-solid fa-bell';
                icon.style.color = '#3b82f6';
                iconBox.style.background = '#eff6ff';
            }
        }

        openModal('notif-details-modal');
    } else {
        console.warn('Notification not found in state for ID:', notifId);
    }
}



// Event Listeners
// NOTE: notification-recipient listener removed as it is now handled by updateNotifTargeting()


// Utility Functions
function formatRole(role) {
    const roleMap = {
        'donor': 'Donor',
        'custodian': 'Custodian',
        'patient': 'Patient',
        'hospital': 'Hospital',
        'financial': 'Financial Donor',
        'medical_school': 'Medical School',
        'recipient_patient': 'Aftercare Recipient',
        'aftercare_patient': 'Aftercare Patient'
    };
    return roleMap[role.toLowerCase()] || role;
}

function formatStatus(status) {
    const statusMap = {
        'active': 'Active',
        'pending': 'Pending',
        'suspended': 'Suspended',
        'approved': 'Approved',
        'rejected': 'Rejected',
        'delivered': 'Delivered',
        'withdrawn': 'Withdrawn',
        'withdraw_request': 'Withdrawn'
    };
    return statusMap[String(status).toLowerCase()] || status;
}

function formatDocType(type) {
    const typeMap = {
        'nic': 'NIC Document',
        'medical': 'Medical Certificate',
        'address': 'Address Proof',
        'guardian': 'Guardian Document'
    };
    return typeMap[type] || type;
}

function getPriorityClass(priority) {
    switch (String(priority).toUpperCase()) {
        case 'HIGH': return 'suspended'; // Red
        case 'MEDIUM': return 'pending';  // Yellow/Orange
        case 'LOW':
        default: return 'active';   // Green
    }
}


function showToast(type, message) {
    const toast = document.getElementById('toast');
    const messageEl = document.getElementById('toast-message');
    if (!toast || !messageEl) return;

    messageEl.textContent = message || (type === 'success' ? 'Action completed successfully' : 'An error occurred.');
    toast.className = `notification ${type} show`;

    setTimeout(() => {
        toast.classList.remove('show');
    }, 5000);
}





// Initialize Dashboard
document.addEventListener('DOMContentLoaded', function () {
    // initDashboard is already called via window.onload
});

async function fetchAuditLogs() {
    try {
        const response = await fetch(`${ROOT}/user-admin/getAuditLogs`);
        const data = await response.json();
        if (data.success) {
            appState.auditLogs = data.auditLogs;
            renderAuditTable();
        }
    } catch (error) {
        console.error('Error fetching audit logs:', error);
    }
}

function renderAuditTable() {
    const tableContent = document.getElementById('audit-table');
    if (!tableContent) return;
    const headerRow = tableContent.querySelector('.table-row');
    const searchInput = document.getElementById('audit-search');
    const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';

    tableContent.innerHTML = '';
    if (headerRow) tableContent.appendChild(headerRow);

    // Add delegation listener once if not already present
    if (!tableContent.dataset.hasListener) {
        tableContent.addEventListener('click', (e) => {
            const row = e.target.closest('.clickable-row');
            if (row && row.dataset.logId) {
                e.stopImmediatePropagation();
                openAuditDetail(row.dataset.logId);
            }
        });
        tableContent.dataset.hasListener = 'true';
    }

    const filteredLogs = (appState.auditLogs || []).filter(log => {
        const admin = (log.admin_name || '').toLowerCase();
        const target = (log.target_name || '').toLowerCase();
        const action = (log.action || '').toLowerCase();
        return admin.includes(searchTerm) || target.includes(searchTerm) || action.includes(searchTerm);
    });

    filteredLogs.forEach(log => {
        const row = document.createElement('div');
        row.className = 'table-row clickable-row';
        row.style.cursor = 'pointer';
        row.dataset.logId = log.id;

        row.innerHTML = `
                    <div class="table-cell">
                        <strong>${log.admin_name || 'System'}</strong>
                    </div>
                    <div class="table-cell"><span class="status-badge" style="background:#f1f5f9; color:#475569;">${log.action || 'Unknown'}</span></div>
                    <div class="table-cell">${log.target_name || '<span style="color:#94a3b8">Global System</span>'}</div>
                    <div class="table-cell">${log.created_at ? new Date(log.created_at).toLocaleString() : 'N/A'}</div>
                `;
        tableContent.appendChild(row);
    });
}

function openAuditDetail(logId) {
    if (appState.isProcessingAudit) return;
    appState.isProcessingAudit = true;
    setTimeout(() => { appState.isProcessingAudit = false; }, 300);

    console.log('Requesting Audit Detail for ID:', logId);
    console.trace('Audit detail trigger source:');

    // Ensure modal is unique and on body
    ensureModalOnBody('audit-details-modal');
    const log = (appState.auditLogs || []).find(l => l.id == logId);
    if (log) {
        document.getElementById('audit-modal-title').textContent = (log.action || 'Event').replace(/_/g, ' ');
        document.getElementById('audit-modal-date').textContent = 'Recorded on: ' + (log.created_at ? new Date(log.created_at).toLocaleString() : 'N/A');

        const adminEl = document.getElementById('audit-admin-text');
        const targetEl = document.getElementById('audit-target-text');
        const oldValEl = document.getElementById('audit-old-val');
        const newValEl = document.getElementById('audit-new-val');
        const notesEl = document.getElementById('audit-notes-body');

        if (adminEl) adminEl.textContent = log.admin_name || 'System';
        if (targetEl) targetEl.textContent = log.target_name || 'Global System';
        if (oldValEl) {
            oldValEl.textContent = log.old_value || 'NULL';
            oldValEl.className = 'modal-break-word';
        }
        if (newValEl) {
            newValEl.textContent = log.new_value || 'NULL';
            newValEl.className = 'modal-break-word';
        }
        if (notesEl) {
            notesEl.textContent = log.notes || 'No additional notes provided.';
            notesEl.className = 'modal-break-word';
        }

        openModal('audit-details-modal');
    } else {
        console.warn('Audit Log not found in state for ID:', logId);
    }
}

// Auto-hide notifications
setTimeout(() => {
    const notifications = document.querySelectorAll('.notification.show');
    notifications.forEach(notification => {
        notification.classList.remove('show');
    });
}, 10000);

// Sidebar Mobile Toggle
function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    const body = document.body;

    sidebar.classList.toggle('active');
    overlay.classList.toggle('active');
    body.style.overflow = sidebar.classList.contains('active') ? 'hidden' : '';
}

