// donation.js - Donation Admin Dashboard JavaScript

// Initialize dashboard
document.addEventListener('DOMContentLoaded', function () {
    console.log('Initializing donation dashboard...');
    loadDashboardStats();
    setupEventListeners();

    // Show dashboard by default
    showContent('dashboard', document.querySelector('.menu-item.active'));
    
    // Initial data fetch for organs
    fetchOrgans();
});

// Load dashboard statistics from database
async function loadDashboardStats() {
    try {
        console.log('Loading dashboard stats...');
        const response = await fetch('/life-connect/public/donation-admin/getDashboardStats');
        const data = await response.json();

        console.log('Dashboard stats response:', data);

        if (data.success) {
            updateDashboardStats(data.stats);
        } else {
            console.error('Failed to load dashboard stats:', data.message);
            loadSampleStats();
        }
    } catch (error) {
        console.error('Error loading dashboard stats:', error);
        loadSampleStats();
    }
}

// Update dashboard statistics
function updateDashboardStats(stats) {
    console.log('Updating stats with:', stats);
    document.getElementById('total-donors').textContent = stats.totalDonors || '0';
    document.getElementById('total-organs').textContent = stats.totalOrgans || '0';
    document.getElementById('pending-approvals').textContent = stats.pendingApprovals || '0';
    document.getElementById('completed-donations').textContent = stats.completedDonations || '0';
    // Update change indicators
    updateStatChanges();
}

function updateStatChanges() {
    const changes = document.querySelectorAll('.stat-change');
    changes.forEach(change => {
        change.textContent = '↗ +12% this month';
        change.className = 'stat-change positive';
    });
}

// Setup event listeners
function setupEventListeners() {
    // Search functionality
    const searchInput = document.getElementById('organ-search');
    if (searchInput) {
        searchInput.addEventListener('input', handleOrganSearch);
    }

    // Filter functionality
    const organFilter = document.getElementById('organ-type-filter');
    const statusFilter = document.getElementById('status-filter');

    if (organFilter) organFilter.addEventListener('change', handleOrganFilter);
    if (statusFilter) statusFilter.addEventListener('change', handleOrganFilter);

    const bloodFilter = document.getElementById('blood-type-filter');
    if (bloodFilter) bloodFilter.addEventListener('change', handleOrganFilter);
}

// Show Content Section (Navigation) - FIXED
function showContent(sectionId, element) {
    console.log('Showing content:', sectionId);
    // Hide all content sections
    const sections = document.querySelectorAll('.content-section');
    sections.forEach(section => {
        section.style.display = 'none';
    });

    // Show selected section
    const selectedSection = document.getElementById(sectionId);
    if (selectedSection) {
        selectedSection.style.display = 'block';
        console.log(`Successfully switched to section: ${sectionId}`);
        
        // Custom triggers for specific sections
        if (sectionId === 'tributes' && typeof filterTributes === 'function') {
            console.log('Triggering tribute filter...');
            filterTributes();
        }

        if (sectionId === 'hospital-requests') {
            console.log('Triggering hospital requests fetch...');
            fetchHospitalRequests();
        }
    } else {
        console.error(`FAILED to find section with ID: ${sectionId}`);
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

// Fetch all donor pledges from the controller
async function fetchOrgans() {
    const loader = document.getElementById('organs-loader');
    if (loader) loader.style.display = 'block';

    try {
        const response = await fetch('/life-connect/public/donation-admin/getPledges');
        const data = await response.json();

        if (data.success) {
            window.allOrgans = data.pledges; // Store globally for filtering
            renderOrgansTable(data.pledges);
        } else {
            console.error('Failed to load organs:', data.message);
        }
    } catch (error) {
        console.error('Error fetching organs:', error);
    } finally {
        if (loader) loader.style.display = 'none';
    }
}

// Render organs into the table
function renderOrgansTable(organs) {
    const table = document.getElementById('donor-organs-table');
    if (!table) return;

    // Keep the header row
    const headerRow = table.querySelector('.table-row:first-child');
    table.innerHTML = '';
    table.appendChild(headerRow);

    if (organs.length === 0) {
        const noResults = document.createElement('div');
        noResults.className = 'organ-row no-results-message';
        noResults.innerHTML = '<div class="table-cell" style="grid-column: 1 / -1; text-align: center; padding: 3rem; color: #94a3b8;">No donor pledges found in the system</div>';
        table.appendChild(noResults);
        return;
    }

    organs.forEach(organ => {
        const status = organ.status || 'Pending';
        const row = document.createElement('div');
        row.className = 'organ-row';
        row.onclick = () => viewOrganDetails(organ.id);
        
        const pledgedDate = organ.pledged_date ? new Date(organ.pledged_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : 'N/A';
        
        row.innerHTML = `
            <div class="table-cell">
                <div style="font-weight: 600; color: #1e293b;">${organ.first_name} ${organ.last_name}</div>
                <div style="font-size: 0.75rem; color: #64748b;">ID: ${organ.donor_id}</div>
            </div>
            <div class="table-cell">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <span style="font-weight: 500; color: #1e293b;">${organ.organ_name}</span>
                    <span style="font-size: 0.7rem; padding: 2px 6px; background: #fee2e2; color: #991b1b; border-radius: 4px; font-weight: 700;">${organ.blood_type}</span>
                </div>
            </div>
            <div class="table-cell" style="color: #64748b;" data-date="${organ.pledged_date || ''}">
                ${pledgedDate}
            </div>
            <div class="table-cell" style="display: flex; justify-content: center;">
                <span class="status-badge status-${status.toLowerCase()}" style="padding: 0.35rem 0.75rem; border-radius: 50px; font-size: 0.75rem; font-weight: 600;">${status}</span>
            </div>
        `;
        table.appendChild(row);
    });

    // Apply any current filters after rendering
    handleOrganFilter();
}

// Handle organ search
function handleOrganSearch(event) {
    handleOrganFilter(); // Combined filter approach
}

// Combined filter for search text and status/type/blood dropdowns + date range
function handleOrganFilter() {
    const searchInput = document.getElementById('organ-search');
    const organTypeSelect = document.getElementById('organ-type-filter');
    const bloodTypeSelect = document.getElementById('blood-type-filter');
    const statusSelect = document.getElementById('status-filter');
    const dateFromInput = document.getElementById('date-from');
    const dateToInput = document.getElementById('date-to');
    
    const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
    const typeValue = organTypeSelect ? organTypeSelect.value : '';
    const bloodValue = bloodTypeSelect ? bloodTypeSelect.value : '';
    const statusValue = statusSelect ? statusSelect.value : '';
    const dateFrom = dateFromInput ? dateFromInput.value : '';
    const dateTo = dateToInput ? dateToInput.value : '';
    
    const table = document.getElementById('donor-organs-table');
    if (table) {
        const rows = table.querySelectorAll('.organ-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const statusBadge = row.querySelector('.status-badge');
            const rowStatus = statusBadge ? statusBadge.textContent.trim() : '';
            
            const organCell = row.querySelector('.table-cell:nth-child(2)');
            const organName = organCell ? organCell.querySelector('span:first-child').textContent.trim() : '';
            const bloodType = organCell ? organCell.querySelector('span:last-child').textContent.trim() : '';
            
            // Get date from data attribute for precise comparison
            const dateCell = row.querySelector('.table-cell:nth-child(3)');
            const rawPledgeDateStr = dateCell ? dateCell.getAttribute('data-date') : '';
            const pledgeDate = rawPledgeDateStr ? new Date(rawPledgeDateStr) : null;
            
            const matchesSearch = text.includes(searchTerm);
            const matchesType = !typeValue || organName === typeValue;
            const matchesBlood = !bloodValue || bloodType === bloodValue;
            const matchesStatus = !statusValue || rowStatus.toLowerCase() === statusValue.toLowerCase();
            
            // Date Range logic
            let matchesDate = true;
            if (pledgeDate) {
                // Force comparison based on local midnight to avoid UTC issues
                if (dateFrom) {
                    const fromDate = new Date(dateFrom + 'T00:00:00');
                    if (pledgeDate < fromDate) matchesDate = false;
                }
                
                if (dateTo) {
                    const toDate = new Date(dateTo + 'T23:59:59');
                    if (pledgeDate > toDate) matchesDate = false;
                }
            } else if (dateFrom || dateTo) {
                matchesDate = false; // Filter out rows without valid dates if a range is set
            }
            
            const isVisible = matchesSearch && matchesType && matchesBlood && matchesStatus && matchesDate;
            row.style.display = isVisible ? 'grid' : 'none';
            if (isVisible) visibleCount++;
        });

        // Show/hide no results message
        let noResults = table.querySelector('.no-results-message');
        if (visibleCount === 0 && rows.length > 0) {
            if (!noResults) {
                noResults = document.createElement('div');
                noResults.className = 'organ-row no-results-message';
                noResults.innerHTML = '<div class="table-cell" style="grid-column: 1 / -1; text-align: center; padding: 3rem; color: #94a3b8;">No matching pledges found</div>';
                table.appendChild(noResults);
            }
            noResults.style.display = 'grid';
        } else if (noResults) {
            noResults.style.display = 'none';
        }
    }
}

function toggleDateRangePicker() {
    const picker = document.getElementById('date-range-picker');
    const iconBtn = document.getElementById('date-range-icon');
    if (picker) {
        const isVisible = picker.style.display === 'block';
        picker.style.display = isVisible ? 'none' : 'block';
        if (iconBtn) {
            iconBtn.style.background = isVisible ? 'white' : '#f1f5f9';
            iconBtn.style.color = isVisible ? '#64748b' : '#3b82f6';
        }
    }
}

function resetDateRange() {
    const dateFrom = document.getElementById('date-from');
    const dateTo = document.getElementById('date-to');
    if (dateFrom) dateFrom.value = '';
    if (dateTo) dateTo.value = '';
    
    handleOrganFilter();
}

// Refresh organs table
function refreshOrgans() {
    console.log('Refreshing organs table...');
    fetchOrgans();
}



// View organ details
function viewOrganDetails(organId) {
    console.log('Viewing organ details for ID:', organId);

    // Fetch organ details from database
    fetch(`/life-connect/public/donation-admin/getOrganDetails?organ_id=${organId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log('Organ details response:', data);
            if (data.success) {
                showOrganModal(data.organ);
            } else {
                alert('Error loading organ details: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading organ details: ' + error.message);
        });
}

// Show organ modal with premium styling
function showOrganModal(organ) {
    console.log('Showing organ modal with data:', organ);

    // Populate modal fields
    document.getElementById('modal-organ-pledge-id').textContent = '#' + organ.id;
    document.getElementById('modal-donor-id').textContent = organ.donor_id || 'N/A';
    document.getElementById('modal-donor-name').textContent = (organ.first_name || '') + ' ' + (organ.last_name || '');
    document.getElementById('modal-organ-type').textContent = organ.organ_name || 'N/A';
    document.getElementById('modal-blood-type').textContent = organ.blood_type || 'N/A';
    document.getElementById('modal-reg-date').textContent = organ.pledged_date ? new Date(organ.pledged_date).toLocaleDateString() : 'N/A';

    // Set status badge and dropdown
    const status = organ.status || 'Pending';
    const badge = document.getElementById('modal-status-badge');
    if (badge) {
        badge.textContent = status;
        badge.className = 'status-badge status-' + status.toLowerCase();
    }

    const statusSelect = document.getElementById('modal-status-select');
    if (statusSelect) {
        statusSelect.value = status;
        statusSelect.setAttribute('data-organ-id', organ.id);
    }

    // Show modal
    const modal = document.getElementById('organModal');
    if (modal) modal.style.display = 'block';
}

// Update organ status
function updateOrganStatus() {
    const statusSelect = document.getElementById('modal-status-select');
    const organId = statusSelect.getAttribute('data-organ-id');
    const newStatus = statusSelect.value;

    if (!organId) {
        alert('Error: Organ ID not found');
        return;
    }

    if (confirm(`Are you sure you want to update the status to "${newStatus}"?`)) {
        // Send update request
        fetch('/life-connect/public/donation-admin/updateOrganStatus', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                organ_id: organId,
                status: newStatus
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Status updated successfully!');
                    closeOrganModal();
                    refreshOrgans(); // Refresh the table to show updated status
                } else {
                    alert('Error updating status: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error updating status: ' + error.message);
            });
    }
}

// Close organ modal
function closeOrganModal() {
    const modal = document.getElementById('organModal');
    if (modal) modal.style.display = 'none';
}

// Handle clicking outside modal or date picker to close
window.addEventListener('click', function(event) {
    const organModal = document.getElementById('organModal');
    if (event.target === organModal) {
        closeOrganModal();
    }
    
    const datePicker = document.getElementById('date-range-picker');
    const dateIcon = document.getElementById('date-range-icon');
    
    // Improved check: don't close if clicking inside the picker OR on the icon itself
    if (datePicker && datePicker.style.display === 'block') {
        const isOutsidePicker = !datePicker.contains(event.target);
        const isOutsideIcon = !dateIcon.contains(event.target) && !dateIcon.querySelector('i').contains(event.target);
        
        // Only toggle (close) if explicitly clicking outside both
        if (isOutsidePicker && isOutsideIcon) {
            // Check if we're clicking on a date picker popup (some browsers use separate windows/elements)
            if (event.target.type !== 'date') {
                 toggleDateRangePicker();
            }
        }
    }
});



// Fallback to sample stats
function loadSampleStats() {
    console.log('Loading sample stats as fallback');
    const sampleStats = {
        totalDonors: 156,
        totalOrgans: 243,
        pendingApprovals: 23,
        completedDonations: 89
    };
    updateDashboardStats(sampleStats);
}

// --- Hospital Requests Functions ---
async function fetchHospitalRequests() {
    const loader = document.getElementById('requests-loader');
    if (loader) loader.style.display = 'block';

    try {
        const response = await fetch('/life-connect/public/donation-admin/getHospitalRequests');
        const data = await response.json();

        if (data.success) {
            window.allHospitalRequests = data.requests; // Store globally for filtering
            renderHospitalRequestsTable(data.requests);
        } else {
            console.error('Failed to load hospital requests:', data.message);
        }
    } catch (error) {
        console.error('Error fetching hospital requests:', error);
    } finally {
        if (loader) loader.style.display = 'none';
    }
}

function renderHospitalRequestsTable(requests) {
    const table = document.getElementById('hospital-requests-table');
    if (!table) return;

    // Keep the header row
    const headerRow = table.querySelector('.table-row:first-child');
    table.innerHTML = '';
    table.appendChild(headerRow);

    if (requests.length === 0) {
        const noResults = document.createElement('div');
        noResults.className = 'table-row no-results-message';
        noResults.innerHTML = '<div class="table-cell" style="grid-column: 1 / -1; text-align: center; padding: 3rem; color: #94a3b8;">No hospital requests found in the system</div>';
        table.appendChild(noResults);
        return;
    }

    requests.forEach(req => {
        const status = req.status || 'Open';
        const row = document.createElement('div');
        row.className = 'table-row hospital-request-row';
        row.style.display = 'grid';
        row.style.gridTemplateColumns = '1.5fr 1.5fr 1fr 1fr 120px';
        row.style.gap = '1rem';
        row.style.padding = '1.2rem 1.5rem';
        row.style.borderBottom = '1px solid #f1f5f9';
        row.style.alignItems = 'center';
        
        // Add data attributes for reliable filtering
        row.setAttribute('data-hospital', req.hospital_name.toLowerCase());
        row.setAttribute('data-organ', req.organ_name.toLowerCase());
        row.setAttribute('data-priority', req.priority_level.toUpperCase());
        row.setAttribute('data-status', status.toLowerCase());
        
        const reqDate = req.created_at ? new Date(req.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : 'N/A';
        const priorityClass = getPriorityClass(req.priority_level);
        
        row.innerHTML = `
            <div class="table-cell">
                <div style="font-weight: 600; color: #1e293b;">${req.hospital_name}</div>
                <div style="font-size: 0.75rem; color: #64748b;">ID: ${req.hospital_id}</div>
            </div>
            <div class="table-cell">
                <div style="font-weight: 500; color: #1e293b;">${req.organ_name}</div>
            </div>
            <div class="table-cell">
                <span class="status-badge ${priorityClass}">${req.priority_level}</span>
            </div>
            <div class="table-cell" style="color: #64748b;" data-date="${req.created_at || ''}">
                ${reqDate}
            </div>
            <div class="table-cell" style="display: flex; justify-content: center;">
                <span class="status-badge status-${status.toLowerCase()}" style="padding: 0.35rem 0.75rem; border-radius: 50px; font-size: 0.75rem; font-weight: 600;">${status}</span>
            </div>
        `;
        table.appendChild(row);
    });
}

function handleHospitalRequestFilter() {
    const searchInput = document.getElementById('hospital-request-search');
    const organSelect = document.getElementById('request-organ-filter');
    const prioritySelect = document.getElementById('request-priority-filter');
    const statusSelect = document.getElementById('request-status-filter');
    const dateFromInput = document.getElementById('request-date-from');
    const dateToInput = document.getElementById('request-date-to');
    
    const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
    const organValue = organSelect ? organSelect.value.toLowerCase() : '';
    const priorityValue = prioritySelect ? prioritySelect.value.toUpperCase() : '';
    const statusValue = statusSelect ? statusSelect.value.toLowerCase() : '';
    const dateFrom = dateFromInput ? dateFromInput.value : '';
    const dateTo = dateToInput ? dateToInput.value : '';
    
    const table = document.getElementById('hospital-requests-table');
    if (table) {
        const rows = table.querySelectorAll('.hospital-request-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const hospitalName = row.getAttribute('data-hospital') || '';
            const organName = row.getAttribute('data-organ') || '';
            const rowPriority = row.getAttribute('data-priority') || '';
            const rowStatus = row.getAttribute('data-status') || '';
            
            // Date comparison
            const dateCell = row.querySelector('.table-cell:nth-child(4)');
            const rawDateStr = dateCell ? dateCell.getAttribute('data-date') : '';
            const reqDate = rawDateStr ? new Date(rawDateStr) : null;
            
            const matchesSearch = hospitalName.includes(searchTerm) || organName.includes(searchTerm);
            const matchesOrgan = !organValue || organName === organValue;
            const matchesPriority = !priorityValue || rowPriority === priorityValue;
            const matchesStatus = !statusValue || rowStatus === statusValue;
            
            let matchesDate = true;
            if (reqDate) {
                if (dateFrom) {
                    const fromDate = new Date(dateFrom + 'T00:00:00');
                    if (reqDate < fromDate) matchesDate = false;
                }
                if (dateTo) {
                    const toDate = new Date(dateTo + 'T23:59:59');
                    if (reqDate > toDate) matchesDate = false;
                }
            } else if (dateFrom || dateTo) {
                matchesDate = false;
            }
            
            const isVisible = matchesSearch && matchesOrgan && matchesPriority && matchesStatus && matchesDate;
            row.style.display = isVisible ? 'grid' : 'none';
            if (isVisible) visibleCount++;
        });

        // Show/hide no results message
        let noResults = table.querySelector('.no-results-message');
        if (visibleCount === 0 && rows.length > 0) {
            if (!noResults) {
                noResults = document.createElement('div');
                noResults.className = 'table-row no-results-message';
                noResults.innerHTML = '<div class="table-cell" style="grid-column: 1 / -1; text-align: center; padding: 3rem; color: #94a3b8;">No matching requests found</div>';
                table.appendChild(noResults);
            }
            noResults.style.display = 'grid';
        } else if (noResults) {
            noResults.style.display = 'none';
        }
    }
}

function getPriorityClass(priority) {
    if (!priority) return 'priority-normal';
    switch (priority.toUpperCase()) {
        case 'CRITICAL': return 'priority-critical';
        case 'URGENT': return 'priority-urgent';
        case 'NORMAL': return 'priority-normal';
        default: return 'priority-normal';
    }
}

function toggleRequestDateRangePicker() {
    const picker = document.getElementById('request-date-range-picker');
    const iconBtn = document.getElementById('request-date-range-icon');
    if (picker) {
        const isVisible = picker.style.display === 'block';
        picker.style.display = isVisible ? 'none' : 'block';
        if (iconBtn) {
            iconBtn.style.background = isVisible ? 'white' : '#f1f5f9';
            iconBtn.style.color = isVisible ? '#64748b' : '#3b82f6';
        }
    }
}

function resetRequestDateRange() {
    const dateFrom = document.getElementById('request-date-from');
    const dateTo = document.getElementById('request-date-to');
    if (dateFrom) dateFrom.value = '';
    if (dateTo) dateTo.value = '';
    handleHospitalRequestFilter();
}
