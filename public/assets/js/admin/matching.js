// matching.js - JavaScript for Matching Management

var currentMatchingData = [];

// Initialize matching functionality when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initializeMatching();
});

// Initialize matching functionality
function initializeMatching() {
    loadMatchingData();
    setupMatchingSearch();
    setupMatchingFilters();
}

// Load matching data from the table
function loadMatchingData() {
    var tableRows = document.querySelectorAll('#matching-table .table-row:not(:first-child)');
    currentMatchingData = [];
    for (var i = 0; i < tableRows.length; i++) {
        var row = tableRows[i];
        
        // Skip "No results" rows
        if (row.classList.contains('no-results-message') || !row.children[4]) continue;

        var statusBadge = row.children[4].querySelector('.status-badge');
        if (!statusBadge) continue;

        currentMatchingData.push({
            matchId: row.getAttribute('data-match-id'),
            donorName: row.children[0].textContent.trim(),
            organRequestId: row.children[1].textContent.trim(),
            hospitalName: row.children[2].textContent.trim(),
            matchDate: row.children[3].textContent.trim(),
            status: statusBadge.textContent.trim()
        });
    }
}

// Setup search functionality for matching
function setupMatchingSearch() {
    var searchInput = document.getElementById('matching-search');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            filterMatchingTable();
        });
    }
}

// Setup filter functionality for matching
function setupMatchingFilters() {
    const pledgeFilter = document.getElementById('matching-pledge-status-filter');
    
    if (donorFilter) {
        donorFilter.addEventListener('change', filterMatchingTable);
    }
    if (hospitalFilter) {
        hospitalFilter.addEventListener('change', filterMatchingTable);
    }
    if (pledgeFilter) {
        pledgeFilter.addEventListener('change', filterMatchingTable);
    }
}

// Filter matching table based on search and filters
function filterMatchingTable() {
    const searchTerm = document.getElementById('matching-search').value.toLowerCase();
        const donorStatusFilter = document.getElementById('matching-donor-status-filter').value;
    const hospitalStatusFilter = document.getElementById('matching-hospital-status-filter').value;
    const pledgeStatusFilter = document.getElementById('matching-pledge-status-filter').value;
    
    const tableRows = document.querySelectorAll('#matching-table .table-row');
    const insightRows = document.querySelectorAll('#matching-table .insights-row');
    
    tableRows.forEach(row => {
        const donorName = row.children[0].textContent.toLowerCase();
        const requestInfo = row.children[1].textContent.toLowerCase();
        const hospitalName = row.children[2].textContent.toLowerCase();
        
        const rowDonorStatus = row.dataset.donorStatus;
        const rowHospitalStatus = row.dataset.hospitalMatchStatus;
        const rowPledgeStatus = row.dataset.pledgeStatus;
        const matchId = row.dataset.matchId;

        const matchesSearch = donorName.includes(searchTerm) || 
                            requestInfo.includes(searchTerm) || 
                            hospitalName.includes(searchTerm);
        
        const matchesDonorStatus = !donorStatusFilter || rowDonorStatus === donorStatusFilter;
        const matchesHospitalStatus = !hospitalStatusFilter || rowHospitalStatus === hospitalStatusFilter;
        const matchesPledgeStatus = !pledgeStatusFilter || rowPledgeStatus === pledgeStatusFilter;
        
        const isVisible = matchesSearch && matchesDonorStatus && matchesHospitalStatus && matchesPledgeStatus;
        
        // Hide/Show main row
        row.style.display = isVisible ? 'grid' : 'none';
        
        // Find and hide/show corresponding insights row
        const insightsRow = Array.from(insightRows).find(ir => ir.dataset.matchId === matchId);
        if (insightsRow) {
            insightsRow.style.display = isVisible ? 'flex' : 'none';
        }
    });
}

// View matching details with AJAX
function viewMatchDetails(matchId) {
    console.log('Fetching match details for ID:', matchId);
    
    const modal = document.getElementById('matchDetailModal');
    if (!modal) return;
    
    modal.style.display = 'flex';
    
    // Fetch from controller
    fetch('/life-connect/public/donation-admin/getMatchDetails', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ match_id: matchId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            populateMatchModal(data.details);
        } else {
            console.error('Error fetching match details:', data.message);
            closeMatchModal();
            if (typeof showToast === 'function') {
                showToast('error', 'Could not load match details: ' + data.message);
            } else {
                alert('Error: ' + data.message);
            }
        }
    })
    .catch(error => {
        console.error('AJAX Error:', error);
        closeMatchModal();
        alert('An error occurred while fetching match details.');
    });
}

// Populate the modal with retrieved data
function populateMatchModal(details) {
    // Basic Info
    document.getElementById('modal-match-id-badge').textContent = 'MATCH ID: #' + details.match_id;
    document.getElementById('modal-match-date').textContent = new Date(details.match_date).toLocaleDateString();
    
    // Quality Badge
    const qualityBadge = document.getElementById('modal-quality-badge');
    qualityBadge.textContent = details.clinical_match_quality;
    if (details.clinical_match_quality === 'MATCH WITH WARNING') {
        qualityBadge.style.background = '#fef3c7';
        qualityBadge.style.color = '#92400e';
    } else {
        qualityBadge.style.background = '#dcfce7';
        qualityBadge.style.color = '#166534';
    }

    const scoreDisplay = document.getElementById('modal-score-display');
    if (details.clinical_match_quality === 'MATCH WITH WARNING') {
        scoreDisplay.textContent = 'Partial Match';
        scoreDisplay.style.color = '#f59e0b';
    } else {
        scoreDisplay.textContent = 'Excellent Match';
        scoreDisplay.style.color = '#10b981';
    }

    // Donor Confirmation Status
    const donorStatusBadge = document.getElementById('modal-donor-status-badge');
    const donorStatusIcon = document.getElementById('modal-donor-status-icon');
    const donorStatusText = document.getElementById('modal-donor-status-text');
    const dStatus = (details.donor_status || 'PENDING').toUpperCase();

    donorStatusText.textContent = dStatus;
    donorStatusIcon.className = 'fa-solid';

    if (dStatus === 'ACCEPTED') {
        donorStatusBadge.style.background = '#dcfce7';
        donorStatusBadge.style.color = '#166534';
        donorStatusIcon.classList.add('fa-circle-check');
    } else if (dStatus === 'REJECTED') {
        donorStatusBadge.style.background = '#fee2e2';
        donorStatusBadge.style.color = '#991b1b';
        donorStatusIcon.classList.add('fa-circle-xmark');
    } else {
        donorStatusBadge.style.background = '#fef9c3';
        donorStatusBadge.style.color = '#854d0e';
        donorStatusIcon.classList.add('fa-clock');
    }
    
    // Hospital Response Logic
    const hospitalBadge = document.getElementById('modal-hospital-status-badge');
    const hospitalIcon = document.getElementById('modal-hospital-status-icon');
    const hospitalText = document.getElementById('modal-hospital-status-text');
    const hStatus = (details.hospital_match_status || 'PENDING').toUpperCase();

    hospitalText.textContent = hStatus === 'PENDING' ? 'REQUESTED' : (hStatus === 'ACCEPTED' ? 'VERIFIED' : 'REJECTED');
    hospitalIcon.className = 'fa-solid';

    if (hStatus === 'ACCEPTED') {
        hospitalBadge.style.background = '#dcfce7';
        hospitalBadge.style.color = '#15803d';
        hospitalIcon.classList.add('fa-circle-check');
    } else if (hStatus === 'REJECTED') {
        hospitalBadge.style.background = '#fee2e2';
        hospitalBadge.style.color = '#b91c1c';
        hospitalIcon.classList.add('fa-circle-xmark');
    } else {
        hospitalBadge.style.background = '#f1f5f9';
        hospitalBadge.style.color = '#475569';
        hospitalIcon.classList.add('fa-hotel');
    }

    // Hospital Rejection Reason Visibility
    const rejectionBox = document.getElementById('modal-hospital-rejection-box');
    const rejectionText = document.getElementById('modal-hospital-rejection-text');
    if (hStatus === 'REJECTED' && details.hospital_reject_reason) {
        rejectionBox.style.display = 'block';
        rejectionText.textContent = details.hospital_reject_reason;
    } else {
        rejectionBox.style.display = 'none';
    }

    // Donor Side
    document.getElementById('modal-donor-name').textContent = details.first_name + ' ' + details.last_name;
    document.getElementById('modal-donor-nic').textContent = 'NIC: ' + details.nic_number;
    document.getElementById('modal-donor-blood').textContent = details.donor_blood;
    document.getElementById('modal-donor-gender').textContent = details.gender;

    // Status-based Age Logic (Using NIC if available)
    let donorAgeDisplay = 'N/A';
    if (details.nic_number) {
        const nic = details.nic_number.toString();
        let birthYear = 0;
        if (nic.length === 10 || nic.length === 9) { // Old NIC (e.g., 85...V)
            birthYear = 1900 + parseInt(nic.substring(0, 2));
        } else if (nic.length === 12) { // New NIC (e.g., 1985...)
            birthYear = parseInt(nic.substring(0, 4));
        }
        
        if (birthYear > 0) {
            donorAgeDisplay = (new Date().getFullYear() - birthYear) + ' Years';
        }
    } else if (details.date_of_birth) {
        const dob = new Date(details.date_of_birth);
        donorAgeDisplay = (new Date().getFullYear() - dob.getFullYear()) + ' Years';
    }
    document.getElementById('modal-donor-age').textContent = donorAgeDisplay;
    
    // Update Clinical Pledge Status Badge
    const pledgeBadge = document.getElementById('modal-donor-pledge-status-badge');
    const pledgeText = document.getElementById('modal-donor-pledge-status-text');
    const pStatus = (details.pledge_status || 'UNKNOWN').toUpperCase();
    
    pledgeText.textContent = pStatus;
    if (pStatus === 'IN_PROGRESS') {
        pledgeBadge.style.background = '#eff6ff';
        pledgeBadge.style.color = '#1e40af';
    } else if (pStatus === 'APPROVED' || pStatus === 'COMPLETED') {
        pledgeBadge.style.background = '#dcfce7';
        pledgeBadge.style.color = '#166534';
    } else if (pStatus === 'SUSPENDED' || pStatus === 'REJECTED') {
        pledgeBadge.style.background = '#fee2e2';
        pledgeBadge.style.color = '#991b1b';
    } else {
        pledgeBadge.style.background = '#f1f5f9';
        pledgeBadge.style.color = '#475569';
    }

    // Calculate and show BMI if height/weight available
    const bmiEl = document.getElementById('modal-donor-bmi');
    if (details.height && details.weight) {
        const bmi = (details.weight / ((details.height / 100) ** 2)).toFixed(1);
        bmiEl.textContent = `${details.weight}kg / ${bmi} BMI`;
        
        if (parseFloat(bmi) > 25) {
            bmiEl.style.color = '#ef4444';
            bmiEl.style.fontWeight = '800';
            bmiEl.innerHTML += ' <i class="fa-solid fa-triangle-exclamation" title="High BMI"></i>';
        } else {
            bmiEl.style.color = '';
            bmiEl.style.fontWeight = '';
        }
    } else {
        bmiEl.textContent = 'N/A';
        bmiEl.style.color = '';
    }

    document.getElementById('modal-donor-phone').innerHTML = `<i class="fa-solid fa-phone" style="color: #ef4444; font-size: 0.75rem;"></i> ${details.donor_phone || 'N/A'}`;
    document.getElementById('modal-donor-email').innerHTML = `<i class="fa-solid fa-envelope" style="color: #ef4444; font-size: 0.75rem;"></i> ${details.donor_email || 'N/A'}`;
    
    // HLA Comparison Breakdown logic handles the comparison now
    renderHLABreakdown(details);

    // Recipient Side
    document.getElementById('modal-hospital-name').textContent = details.hospital_name;
    document.getElementById('modal-request-organ').textContent = details.req_organ;
    document.getElementById('modal-request-priority').textContent = 'Priority: ' + details.priority_level;
    
    // Consolidated Recipient Profile
    const recipientAge = details.recipient_age ? details.recipient_age + 'Y' : 'N/A';
    const recipientGender = details.recipient_gender ? details.recipient_gender : 'Unknown';
    document.getElementById('modal-recipient-age-gender').textContent = `${recipientAge} / ${recipientGender}`;
    document.getElementById('modal-request-reason').textContent = 'Reason: ' + (details.transplant_reason || 'Not Specified');

    document.getElementById('modal-hospital-phone').innerHTML = `<i class="fa-solid fa-phone" style="color: #3b82f6; font-size: 0.75rem;"></i> ${details.hospital_phone || 'N/A'}`;
    document.getElementById('modal-hospital-email').innerHTML = `<i class="fa-solid fa-envelope" style="color: #3b82f6; font-size: 0.75rem;"></i> ${details.hospital_email || 'N/A'}`;
    
    // Clinical Insights & Persistent Warnings (BMI, Age Gap, etc.)
    const insightsSection = document.getElementById('modal-insights-section');
    const insightsText = document.getElementById('modal-insights-text');
    
    if (details.warning_details) {
        // Show all warnings stored in DB (including the persistent BMI warning)
        insightsText.innerHTML = details.warning_details.split(',').map(w => `<div>• ${w.trim()}</div>`).join('');
        insightsSection.style.display = 'block';
    } else {
        insightsText.textContent = `All clinical parameters for ${details.organ_name} compatibility (Blood Group ${details.donor_blood} and HLA Typing) are within standard medical thresholds.`;
        insightsSection.style.display = 'block';
    }
}

// Render HLA Comparison Table
function renderHLABreakdown(details) {
    const container = document.getElementById('modal-hla-comparison-table-container');
    if (!container) return;

    // Check if it's a tissue match where HLA isn't primary
    const isTissueMatch = details.warning_details && details.warning_details.includes('not mandatory');

    const markers = [
        { label: 'HLA-A1', donor: details.hla_a1, recipient: details.req_hla_a1 },
        { label: 'HLA-A2', donor: details.hla_a2, recipient: details.req_hla_a2 },
        { label: 'HLA-B1', donor: details.hla_b1, recipient: details.req_hla_b1 },
        { label: 'HLA-B2', donor: details.hla_b2, recipient: details.req_hla_b2 },
        { label: 'HLA-DR1', donor: details.hla_dr1, recipient: details.req_hla_dr1 },
        { label: 'HLA-DR2', donor: details.hla_dr2, recipient: details.req_hla_dr2 }
    ];

    let html = `
        <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem;">
            <thead>
                <tr style="text-align: left; border-bottom: 2px solid #f1f5f9;">
                    <th style="padding: 8px; color: #64748b;">Marker</th>
                    <th style="padding: 8px; color: #64748b;">Donor</th>
                    <th style="padding: 8px; color: #64748b;">Recipient</th>
                    <th style="padding: 8px; color: #64748b; text-align: center;">Result</th>
                </tr>
            </thead>
            <tbody>
    `;

    markers.forEach(m => {
        const isMatch = m.donor && m.recipient && m.donor === m.recipient;
        const icon = isMatch 
            ? '<i class="fa-solid fa-circle-check" style="color: #10b981;"></i>' 
            : (isTissueMatch ? '<i class="fa-solid fa-minus" style="color: #cbd5e1;"></i>' : '<i class="fa-solid fa-circle-xmark" style="color: #f43f5e; opacity: 0.5;"></i>');
        
        html += `
            <tr style="border-bottom: 1px solid #f8fafc;">
                <td style="padding: 10px 8px; font-weight: 700; color: #475569;">${m.label}</td>
                <td style="padding: 10px 8px; color: #1e293b; font-family: monospace;">${m.donor || '—'}</td>
                <td style="padding: 10px 8px; color: #1e293b; font-family: monospace;">${m.recipient || '—'}</td>
                <td style="padding: 10px 8px; text-align: center;">${icon}</td>
            </tr>
        `;
    });

    html += `</tbody></table>`;
    
    if (isTissueMatch) {
        html += `<div style="margin-top: 10px; font-size: 0.75rem; color: #64748b; font-style: italic; text-align: center;">* HLA sequence analysis bypassed for tissue-type matching. Compatibility relies on ABO and clinical screening.</div>`;
    }

    container.innerHTML = html;
}

// Close match modal
function closeMatchModal() {
    const modal = document.getElementById('matchDetailModal');
    if (modal) modal.style.display = 'none';
}

// Legacy function mapping
function viewMatchingDetails(matchId) {
    viewMatchDetails(matchId);
}

// Close modal when clicking outside
window.addEventListener('click', function(event) {
    const modal = document.getElementById('matchDetailModal');
    if (event.target === modal) {
        closeMatchModal();
    }
});

// Update dashboard stats on load to reflect engine results
document.addEventListener('DOMContentLoaded', function() {
    if (typeof loadDashboardStats === 'function') {
        setTimeout(loadDashboardStats, 500); // Small delay to ensure DB sync
    }
});