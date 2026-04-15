// aftercare.js - Aftercare Management Logic

// Initialize
document.addEventListener('DOMContentLoaded', function () {
    console.log('Aftercare dashboard initialized');
    setupEventListeners();
    
    // Check if we should load patients on start (if section is active)
    const activeSection = document.querySelector('.content-section[style*="display: block"]');
    if (activeSection && activeSection.id === 'patients') {
        fetchPatients();
    }
});

// Show Content Section (Navigation)
function showContent(sectionId, element) {
    const sections = document.querySelectorAll('.content-section');
    sections.forEach(section => section.style.display = 'none');

    const selectedSection = document.getElementById(sectionId);
    if (selectedSection) {
        selectedSection.style.display = 'block';
        
        // Trigger data fetch if switching to patients
        if (sectionId === 'patients') {
            fetchPatients();
        }
    }

    const menuItems = document.querySelectorAll('.menu-item');
    menuItems.forEach(item => item.classList.remove('active'));
    if (element) element.classList.add('active');
}

// Setup Event Listeners
function setupEventListeners() {
    // Support Search
    const supportSearch = document.getElementById('support-search');
    if (supportSearch) {
        supportSearch.addEventListener('input', (e) => filterTable('support-requests-table', e.target.value));
    }

    // Patients Search
    const patientSearch = document.getElementById('patient-search');
    if (patientSearch) {
        patientSearch.addEventListener('input', (e) => filterTable('patients-table', e.target.value));
    }
}

function filterTable(tableId, term) {
    const searchTerm = term.toLowerCase();
    const rows = document.querySelectorAll(`#${tableId} .table-row:not(.header-row)`);
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? 'flex' : 'none';
    });
}

/**
 * AJAX: Fetch Patients
 */
async function fetchPatients() {
    const tableBody = document.getElementById('patients-table');
    const loadingHtml = '<div class="table-row" style="justify-content: center; padding: 2rem;"><span><i class="fa-solid fa-spinner fa-spin"></i> Loading patients...</span></div>';
    
    // Preserve header
    const header = tableBody.querySelector('.header-row').outerHTML;
    tableBody.innerHTML = header + loadingHtml;

    try {
        const response = await fetch(`${ROOT}/aftercare-admin/getPatients`);
        const data = await response.json();

        if (data.success) {
            renderPatientsTable(data.patients);
        } else {
            console.error('Failed to fetch patients:', data.message);
            tableBody.innerHTML = header + `<div class="table-row text-danger">Error: ${data.message}</div>`;
        }
    } catch (error) {
        console.error('AJAX Error:', error);
        tableBody.innerHTML = header + '<div class="table-row text-danger">Network error while fetching database.</div>';
    }
}

/**
 * Render Patients Table
 */
function renderPatientsTable(patients) {
    const tableBody = document.getElementById('patients-table');
    const header = tableBody.querySelector('.header-row').outerHTML;
    
    if (!patients || patients.length === 0) {
        tableBody.innerHTML = header + '<div class="table-row" style="justify-content: center; padding: 2rem; color: #64748b;">No patients found in records.</div>';
        return;
    }

    let rowsHtml = header;
    patients.forEach(p => {
        const typeBadgeClass = p.patient_type === 'RECIPIENT' ? 'status-active' : 'status-suspended'; // Reusing existing CSS if possible, or custom
        const typeColor = p.patient_type === 'RECIPIENT' ? '#005baa' : '#059669';
        
        rowsHtml += `
            <div class="table-row">
                <div class="table-cell" style="flex: 1.5; display: flex; align-items: center; gap: 12px;">
                    <div style="width: 38px; height: 38px; background: #f1f5f9; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #1e56a0; font-weight: 700;">
                        ${p.full_name.charAt(0)}
                    </div>
                    <div style="display: flex; flex-direction: column;">
                        <span style="font-weight: 600; color: #1e293b;">${p.full_name}</span>
                        <span style="font-size: 0.75rem; color: #64748b;">${p.registration_number}</span>
                    </div>
                </div>
                <div class="table-cell" style="font-weight: 500;">${p.age || 'N/A'} Yrs</div>
                <div class="table-cell">
                    <span style="background: #fee2e2; color: #991b1b; padding: 4px 8px; border-radius: 6px; font-size: 0.75rem; font-weight: 700;">${p.blood_group || 'O+'}</span>
                </div>
                <div class="table-cell">
                    <span style="background: ${typeColor}15; color: ${typeColor}; padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 700;">${p.patient_type}</span>
                </div>
                <div class="table-cell">
                    <span style="background: #ecfdf5; color: #059669; padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; display: flex; align-items: center; gap: 4px; width: fit-content;">
                        <span style="width: 6px; height: 6px; background: #10b981; border-radius: 50%;"></span>
                        ${p.status}
                    </span>
                </div>
                <div class="table-cell">
                    <button class="btn btn-primary" style="padding: 6px 14px; font-size: 0.8rem; border-radius: 8px; background: #1e56a0;" onclick="viewPatientDetails(${p.id})">
                        <i class="fa-solid fa-eye"></i> View Profile
                    </button>
                </div>
            </div>
        `;
    });

    tableBody.innerHTML = rowsHtml;

    // Update Dashboard counts if they exist in DOM
    const totalCount = document.getElementById('total-patients');
    if (totalCount) totalCount.innerText = patients.length;
}

/**
 * AJAX: View Patient Details
 */
async function viewPatientDetails(id) {
    try {
        const response = await fetch(`${ROOT}/aftercare-admin/getPatientDetails?id=${id}`);
        const data = await response.json();

        if (data.success) {
            const p = data.patient;
            
            // Populate Modal
            document.getElementById('modal-patient-id').textContent = p.registration_number;
            document.getElementById('modal-patient-name').textContent = p.full_name;
            document.getElementById('modal-patient-nic').textContent = p.nic;
            document.getElementById('modal-patient-status').textContent = p.status;
            document.getElementById('modal-patient-age').textContent = p.age + ' Years';
            document.getElementById('modal-patient-bloodtype').textContent = p.blood_group || 'N/A';
            document.getElementById('modal-patient-gender').textContent = p.gender || 'N/A';
            document.getElementById('modal-patient-type').textContent = p.patient_type;
            document.getElementById('modal-patient-hosp').textContent = p.hospital_registration_no || 'N/A';

            // Show Modal
            const modal = document.getElementById('patientModal');
            modal.classList.add('show');
        } else {
            alert('Error fetching details: ' + data.message);
        }
    } catch (error) {
        console.error('Modal Fetch Error:', error);
        alert('Network error while opening patient profile.');
    }
}

function closePatientModal() {
    document.getElementById('patientModal').classList.remove('show');
}

function closeSupportModal() {
    document.getElementById('supportModal').classList.remove('show');
}

/**
 * Legacy/Shared
 */
function formatDate(dateString) {
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('en-US', options);
}