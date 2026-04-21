/**
 * Helper to show toast notifications
 */
function showToast(type, message) {
    const toast = document.getElementById('toast');
    const toastMessage = document.getElementById('toast-message');

    if (toast && toastMessage) {
        toastMessage.textContent = message;
        toast.className = 'notification show ' + type; // success, error, warning

        setTimeout(() => {
            toast.classList.remove('show');
        }, 3000);
    } else {
        console.log('Toast feedback:', message);
    }
}

// Initialize dashboard
document.addEventListener('DOMContentLoaded', function () {
    console.log('Initializing donation dashboard...');
    loadDashboardStats();
    loadFilterMetadata();
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
        const response = await fetch(`${ROOT}/donation-admin/getDashboardStats`);
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
    
    // Update Counts
    document.getElementById('total-donors').textContent = stats.totalDonors.count || '0';
    document.getElementById('total-organs').textContent = stats.totalOrgans.count || '0';
    document.getElementById('pending-approvals').textContent = stats.pendingApprovals.count || '0';
    document.getElementById('completed-donations').textContent = stats.successfulMatches.count || '0';
    
    // Inject Aftercare Counts
    const recEl = document.getElementById('total-recipients');
    if (recEl) recEl.textContent = stats.totalRecipients || '0';
    
    const donEl = document.getElementById('total-donors-aftercare');
    if (donEl) donEl.textContent = stats.totalDonorsAftercare || '0';
    
    // Update Change Indicators
    updateStatChanges(stats);
}

function updateStatChanges(stats) {
    const indicators = [
        { id: 'total-donors-change', val: stats.totalDonors.change },
        { id: 'total-organs-change', val: stats.totalOrgans.change },
        { id: 'pending-pledges-change', val: stats.pendingApprovals.change },
        { id: 'matches-change', val: stats.successfulMatches.change }
    ];

    indicators.forEach(item => {
        const el = document.getElementById(item.id);
        if (el) {
            const isPositive = item.val >= 0;
            const arrow = isPositive ? '↗' : '↘';
            const absVal = Math.abs(item.val);
            
            el.textContent = `${arrow} ${isPositive ? '+' : '-'}${absVal}% this month`;
            el.className = `stat-change ${isPositive ? 'positive' : 'negative'}`;
        }
    });
}

/**
 * Fetches organ names, pledge statuses, and request priorities from the server
 * and populates all relevant filter dropdowns dynamically.
 */
function loadFilterMetadata() {
    fetch(`${ROOT}/donation-admin/getFilterMetadata`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // 1. Populate Organ Filters (used in both tabs)
                const organFilters = ['organ-type-filter', 'request-organ-filter'];
                organFilters.forEach(id => {
                    const select = document.getElementById(id);
                    if (select) {
                        data.organs.forEach(organName => {
                            const option = document.createElement('option');
                            option.value = organName;
                            option.textContent = organName;
                            select.appendChild(option);
                        });
                    }
                });

                // 2. Populate Pledge Status Filter
                const pledgeStatusSelect = document.getElementById('status-filter');
                if (pledgeStatusSelect) {
                    data.pledgeStatuses.forEach(status => {
                        const option = document.createElement('option');
                        option.value = status;
                        // Format for display (e.g., PENDING -> Pending)
                        option.textContent = status.charAt(0).toUpperCase() + status.slice(1).toLowerCase();
                        pledgeStatusSelect.appendChild(option);
                    });
                }

                // 3. Populate Request Priority Filter
                const reqPrioritySelect = document.getElementById('request-priority-filter');
                if (reqPrioritySelect) {
                    data.requestPriorities.forEach(priority => {
                        const option = document.createElement('option');
                        option.value = priority;
                        option.textContent = priority.charAt(0).toUpperCase() + priority.slice(1).toLowerCase();
                        reqPrioritySelect.appendChild(option);
                    });
                }

                // 4. Populate Request Status Filter
                const reqStatusSelect = document.getElementById('request-status-filter');
                if (reqStatusSelect) {
                    data.requestStatuses.forEach(status => {
                        const option = document.createElement('option');
                        option.value = status;
                        option.textContent = status.charAt(0).toUpperCase() + status.slice(1).toLowerCase();
                        reqStatusSelect.appendChild(option);
                    });
                }
            } else {
                console.error('Failed to load filter metadata:', data.message);
            }
        })
        .catch(error => console.error('Error loading filter metadata:', error));
}


// Setup event listeners
function setupEventListeners() {
    // Search functionality
    const searchInput = document.getElementById('organ-search');
    if (searchInput) {
        searchInput.addEventListener('input', handleOrganFilter);
    }

    // Filter functionality
    const organFilter = document.getElementById('organ-type-filter');
    const statusFilter = document.getElementById('status-filter');

    if (organFilter) organFilter.addEventListener('change', handleOrganFilter);
    if (statusFilter) statusFilter.addEventListener('change', handleOrganFilter);

    const bloodFilter = document.getElementById('blood-type-filter');
    if (bloodFilter) bloodFilter.addEventListener('change', handleOrganFilter);

    // Aftercare Patient Search
    const patientSearch = document.getElementById('patient-search');
    if (patientSearch) {
        patientSearch.addEventListener('input', () => {
            // Simple debounce for search
            clearTimeout(this.patientSearchTimeout);
            this.patientSearchTimeout = setTimeout(fetchPatients, 300);
        });
    }

    // Aftercare Patient Type Filter
    const patientTypeFilterTab = document.getElementById('patient-type-filter');
    if (patientTypeFilterTab) {
        patientTypeFilterTab.addEventListener('change', fetchPatients);
    }

    // Blood Type Filter (Tab)
    const bloodFilterTab = document.getElementById('blood-type-filter');
    if (bloodFilterTab) {
        bloodFilterTab.addEventListener('change', fetchPatients);
    }
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

        if (sectionId === 'patients') {
            console.log('Triggering patients fetch...');
            fetchPatients();
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
        const response = await fetch(`${ROOT}/donation-admin/getPledges`);
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
                showToast('error', 'Error loading organ details: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('error', 'Error loading organ details.');
        });
}

// Show organ modal with exact User Review popup styling
function showOrganModal(organ) {
    console.log('Showing organ modal with data:', organ);

    // Populate Modal Summary Card
    document.getElementById('modal-donor-name').textContent = (organ.first_name || '') + ' ' + (organ.last_name || '');
    document.getElementById('modal-donor-id').textContent = 'Donor ID: ' + (organ.donor_id || 'N/A');
    document.getElementById('modal-blood-type').textContent = organ.blood_type || 'N/A';
    document.getElementById('modal-reg-date').textContent = 'Pledged ' + (organ.pledged_date ? new Date(organ.pledged_date).toLocaleDateString(undefined, { year: 'numeric', month: 'long', day: 'numeric' }) : 'N/A');

    document.getElementById('modal-organ-type').textContent = organ.organ_name || 'N/A';
    document.getElementById('modal-organ-pledge-id').textContent = 'Pledge ID: #' + organ.id;

    // Populate Medical History
    document.getElementById('modal-conditions').textContent = organ.conditions || 'None reported';
    document.getElementById('modal-medications').textContent = organ.medications || 'None reported';
    document.getElementById('modal-allergies').textContent = organ.allergies || 'None reported';

    // Populate Logistics
    document.getElementById('modal-preferred-hospital').textContent = organ.preferred_hospital_name || 'No preference specified';

    // Handle Document
    const formContainer = document.getElementById('modal-form-container');
    const noDocsMsg = document.getElementById('modal-no-docs');
    const formLink = document.getElementById('modal-form-link');

    if (organ.signed_form_path) {
        if (formContainer) formContainer.style.display = 'flex';
        if (noDocsMsg) noDocsMsg.style.display = 'none';
        if (formLink) formLink.href = '/life-connect/' + organ.signed_form_path;
    } else {
        if (formContainer) formContainer.style.display = 'none';
        if (noDocsMsg) noDocsMsg.style.display = 'block';
    }

    // Handle Status Styling (Matching User Management Icon Box pattern)
    const status = (organ.status || 'PENDING').toUpperCase();
    const statusText = document.getElementById('modal-status-text');
    const iconBox = document.getElementById('modal-status-icon-box');
    const icon = document.getElementById('modal-status-icon');

    if (statusText) statusText.textContent = status;

    if (iconBox && icon) {
        // Reset
        icon.className = 'fa-solid';

        switch (status) {
            case 'PENDING':
                iconBox.style.background = '#fef9c3';
                icon.style.color = '#854d0e';
                icon.classList.add('fa-clock');
                break;
            case 'UPLOADED':
                iconBox.style.background = '#e0f2fe';
                icon.style.color = '#0369a1';
                icon.classList.add('fa-file-arrow-up');
                break;
            case 'APPROVED':
                iconBox.style.background = '#dcfce7';
                icon.style.color = '#166534';
                icon.classList.add('fa-circle-check');
                break;
            case 'COMPLETED':
                iconBox.style.background = '#dbeafe';
                icon.style.color = '#1e40af';
                icon.classList.add('fa-check-double');
                break;
            case 'REJECTED':
                iconBox.style.background = '#fee2e2';
                icon.style.color = '#991b1b';
                icon.classList.add('fa-circle-xmark');
                break;
            default:
                iconBox.style.background = '#f1f5f9';
                icon.style.color = '#475569';
                icon.classList.add('fa-ban');
        }
    }

    // --- Conditional Sections Logic ---

    // Reset Sections
    const witnessSection = document.getElementById('modal-witness-section');
    const livingSection = document.getElementById('modal-living-consent-section');
    const deceasedSection = document.getElementById('modal-deceased-consent-section');
    const bodySection = document.getElementById('modal-body-consent-section');

    if (witnessSection) witnessSection.style.display = 'none';
    if (livingSection) livingSection.style.display = 'none';
    if (deceasedSection) deceasedSection.style.display = 'none';
    if (bodySection) bodySection.style.display = 'none';

    // Populate Witnesses
    if (organ.witnesses && organ.witnesses.length > 0) {
        if (witnessSection) witnessSection.style.display = 'block';
        const witnessList = document.getElementById('modal-witness-list');
        if (witnessList) {
            witnessList.innerHTML = organ.witnesses.map(w => `
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 12px; display: flex; align-items: center; gap: 15px;">
                    <div style="width: 32px; height: 32px; background: #3b82f6; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.8rem;">${w.witness_number || ''}</div>
                    <div style="flex: 1;">
                        <div style="font-weight: 700; color: #1e293b; font-size: 0.9rem;">${w.name}</div>
                        <div style="font-size: 0.75rem; color: #64748b;">NIC: ${w.nic_number} | Phone: ${w.contact_number || 'N/A'}</div>
                    </div>
                </div>
            `).join('');
        }
    }

    // Populate Consent Data
    if (organ.consent_data) {
        if (organ.consent_type === 'LIVING') {
            if (livingSection) livingSection.style.display = 'block';
            document.getElementById('modal-height').textContent = organ.consent_data.height || '-';
            document.getElementById('modal-weight').textContent = organ.consent_data.weight || '-';
            document.getElementById('modal-clearance-status').textContent = organ.consent_data.medical_clearance_status || '-';
            document.getElementById('modal-recipient-known').textContent = organ.consent_data.is_recipient_known || '-';
            document.getElementById('modal-smoking-alcohol').textContent = organ.consent_data.smoking_alcohol_status || '-';
            document.getElementById('modal-emergency-name').textContent = organ.consent_data.emergency_contact_name || '-';
            document.getElementById('modal-emergency-phone').textContent = (organ.consent_data.emergency_relationship || '') + ' | ' + (organ.consent_data.emergency_phone || '');
        } else if (organ.consent_type === 'DECEASED') {
            if (deceasedSection) deceasedSection.style.display = 'block';
            document.getElementById('modal-suitability').textContent = organ.consent_data.suitability_any == 1 ? 'Yes' : 'No';
            document.getElementById('modal-restricted').textContent = organ.consent_data.is_restricted == 1 ? 'Yes' : 'No';
            document.getElementById('modal-instructions').textContent = organ.consent_data.special_instructions || 'None';
        } else if (organ.consent_type === 'BODY') {
            if (bodySection) bodySection.style.display = 'block';
            document.getElementById('modal-school-id').textContent = organ.consent_data.medical_school_id || '-';
            document.getElementById('modal-resp-person').textContent = organ.consent_data.responsible_person || '-';
            document.getElementById('modal-resp-contact').textContent = organ.consent_data.responsible_contact || '-';
            document.getElementById('modal-transport').textContent = organ.consent_data.transport_arrangement || 'Not specified';
        }
    }

    const statusSelect = document.getElementById('modal-status-select');
    if (statusSelect) {
        // Enforce status update rules: from UPLOADED only APPROVED/SUSPENDED allowed
        const options = statusSelect.options;
        for (let i = 0; i < options.length; i++) {
            const opt = options[i];
            const optVal = opt.value.toUpperCase();

            // Reset visibility/disabled
            opt.style.display = 'block';
            opt.disabled = false;

            if (status === 'UPLOADED') {
                if (optVal !== 'APPROVED' && optVal !== 'SUSPENDED' && optVal !== 'UPLOADED') {
                    opt.style.display = 'none';
                    opt.disabled = true;
                }
            } else if (status === 'APPROVED' || status === 'COMPLETED') {
                // If already approved/completed, don't allow going back to pending/uploaded
                if (optVal === 'PENDING' || optVal === 'UPLOADED') {
                    opt.style.display = 'none';
                    opt.disabled = true;
                }
            }
        }

        statusSelect.value = status;
        statusSelect.setAttribute('data-organ-id', organ.id);
    }

    // --- Administrative Actions Visibility ---
    const adminActionsSection = document.getElementById('modal-admin-actions-section');
    if (adminActionsSection) {
        // Enforce: only UPLOADED status allows administrative updates
        if (status === 'UPLOADED') {
            adminActionsSection.style.display = 'flex';

            // Reset confirmation area if it was open from a previous modal session
            const confirmArea = document.getElementById('modal-status-confirmation');
            const controlsArea = document.getElementById('modal-status-controls');
            if (confirmArea) confirmArea.style.display = 'none';
            if (controlsArea) controlsArea.style.display = 'flex';
        } else {
            // Hide review section for all other states (Approved, Pending, etc.)
            adminActionsSection.style.display = 'none';
        }
    }

    // Show modal
    const modal = document.getElementById('organModal');
    if (modal) modal.classList.add('show');
}

// --- Status Update Confirmation Workflow ---

/**
 * Triggered by the initial "Update Status" button
 * Shows the "Are you sure?" confirmation area
 */
function handleStatusUpdateTrigger() {
    const statusSelect = document.getElementById('modal-status-select');
    const newStatus = statusSelect.value;

    const confirmArea = document.getElementById('modal-status-confirmation');
    const controlsArea = document.getElementById('modal-status-controls');
    const confirmText = document.getElementById('modal-confirm-status-text');

    if (confirmArea && controlsArea && confirmText) {
        confirmText.textContent = newStatus;
        controlsArea.style.display = 'none';
        confirmArea.style.display = 'block';
    }
}

/**
 * Reverts the UI if the admin chooses "No, Cancel"
 */
function cancelStatusUpdate() {
    const confirmArea = document.getElementById('modal-status-confirmation');
    const controlsArea = document.getElementById('modal-status-controls');

    if (confirmArea && controlsArea) {
        confirmArea.style.display = 'none';
        controlsArea.style.display = 'flex';
    }
}

/**
 * Executes the actual API call once confirmed
 */
function confirmStatusUpdate() {
    const statusSelect = document.getElementById('modal-status-select');
    const organId = statusSelect.getAttribute('data-organ-id');
    const newStatus = statusSelect.value;

    if (!organId) {
        showToast('error', 'Error: Organ ID not found');
        return;
    }

    const btn = document.getElementById('btn-confirm-status');
    const originalBtnText = btn ? btn.textContent : 'Yes, Update Status';

    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Updating...';
    }

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
                showToast('success', `Pledge status updated successfully to ${newStatus}`);

                // Immediately hide the admin actions to prevent subsequent edits
                const adminActions = document.getElementById('modal-admin-actions-section');
                if (adminActions) adminActions.style.display = 'none';

                // Wait slightly for the toast to be readable before closing the modal
                setTimeout(() => {
                    closeOrganModal();
                    refreshOrgans(); // Refresh table
                }, 1500);
            } else {
                showToast('error', data.message || 'Error updating status');
                cancelStatusUpdate(); // Return to original state if failed
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('error', 'An error occurred. Check console for details.');
            cancelStatusUpdate();
        })
        .finally(() => {
            if (btn) {
                btn.disabled = false;
                btn.textContent = originalBtnText;
            }
        });
}

// Close organ modal
function closeOrganModal() {
    const modal = document.getElementById('organModal');
    if (modal) modal.classList.remove('show');
}

// Handle clicking outside modal or date picker to close
window.addEventListener('click', function (event) {
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
        row.style.cursor = 'pointer';
        row.onclick = () => viewHospitalRequestDetails(req.id);

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

    // Apply any current filters after rendering
    handleHospitalRequestFilter();
}

/**
 * Fetches single hospital request details
 */
function viewHospitalRequestDetails(id) {
    console.log('Viewing hospital request details for ID:', id);

    fetch(`/life-connect/public/donation-admin/getHospitalRequests?id=${id}`)
        .then(response => response.json())
        .then(data => {
            console.log('Hospital request details response:', data);
            if (data.success) {
                showHospitalRequestModal(data.request);
            } else {
                showToast('error', 'Error loading request details: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('error', 'Error loading request details');
        });
}

/**
 * Populates and shows the hospital request modal
 */
function showHospitalRequestModal(req) {
    console.log('Showing hospital request modal:', req);

    // Basic Info
    document.getElementById('request-modal-hospital-name').textContent = req.hospital_name || 'N/A';
    document.getElementById('request-modal-request-id').textContent = 'Request ID: #' + req.id;
    document.getElementById('request-modal-organ-name').textContent = req.organ_name || 'N/A';
    document.getElementById('request-modal-created-at').textContent = 'Requested ' + (req.created_at ? new Date(req.created_at).toLocaleDateString(undefined, { year: 'numeric', month: 'long', day: 'numeric' }) : 'N/A');
    document.getElementById('request-modal-priority').textContent = req.priority_level || 'NORMAL';
    document.getElementById('request-modal-status-text').textContent = req.status || 'PENDING';

    // Recipient Info
    document.getElementById('request-modal-recipient-age').textContent = (req.recipient_age || 'N/A') + ' Years';
    document.getElementById('request-modal-recipient-gender').textContent = req.gender || 'N/A';
    document.getElementById('request-modal-recipient-blood').textContent = req.blood_group || 'N/A';

    // HLA Typing
    document.getElementById('request-modal-hla-a1').textContent = req.hla_a1 || '-';
    document.getElementById('request-modal-hla-a2').textContent = req.hla_a2 || '-';
    document.getElementById('request-modal-hla-b1').textContent = req.hla_b1 || '-';
    document.getElementById('request-modal-hla-b2').textContent = req.hla_b2 || '-';
    document.getElementById('request-modal-hla-dr1').textContent = req.hla_dr1 || '-';
    document.getElementById('request-modal-hla-dr2').textContent = req.hla_dr2 || '-';

    // Context
    document.getElementById('request-modal-reason').textContent = req.transplant_reason || 'No clinical reason specified';

    const editSection = document.getElementById('request-modal-edit-section');
    if (req.edited_reason) {
        editSection.style.display = 'block';
        document.getElementById('request-modal-edit-reason').textContent = req.edited_reason;
    } else {
        editSection.style.display = 'none';
    }

    // Status Styling
    const status = (req.status || 'PENDING').toUpperCase();
    const iconBox = document.getElementById('request-modal-status-icon-box');

    if (iconBox) {
        iconBox.className = 'status-icon-box status-' + status.toLowerCase();
    }

    // Show Modal
    const modal = document.getElementById('hospitalRequestModal');
    if (modal) modal.classList.add('show');
}

/**
 * Closes the hospital request modal
 */
function closeHospitalRequestModal() {
    const modal = document.getElementById('hospitalRequestModal');
    if (modal) modal.classList.remove('show');
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

/**
 * AJAX: Fetch Aftercare Patients
 */
async function fetchPatients() {
    const tableBody = document.getElementById('patients-table');
    if (!tableBody) return;

    const loadingHtml = '<div class="table-row" style="justify-content: center; padding: 2rem;"><span><i class="fa-solid fa-spinner fa-spin"></i> Loading patients...</span></div>';
    
    // Preserve header
    const headerRow = tableBody.querySelector('.header-row');
    const headerHtml = headerRow ? headerRow.outerHTML : '';
    tableBody.innerHTML = headerHtml + loadingHtml;

    const typeFilter = document.getElementById('patient-type-filter')?.value || '';
    const bloodFilter = document.getElementById('blood-type-filter')?.value || '';
    const searchTerm = document.getElementById('patient-search')?.value || '';

    try {
        const response = await fetch(`${ROOT}/donation-admin/getPatients?type=${typeFilter}&blood=${bloodFilter}&search=${searchTerm}`);
        const data = await response.json();

        if (data.success) {
            renderPatientsTable(data.patients);
        } else {
            console.error('Failed to fetch patients:', data.message);
            tableBody.innerHTML = headerHtml + `<div class="table-row text-danger" style="padding: 2rem; justify-content: center;">Error: ${data.message}</div>`;
        }
    } catch (error) {
        console.error('AJAX Error:', error);
        tableBody.innerHTML = headerHtml + '<div class="table-row text-danger" style="padding: 2rem; justify-content: center;">Network error while fetching patient database.</div>';
    }
}

/**
 * Render Patients Table
 */
function renderPatientsTable(patients) {
    const tableBody = document.getElementById('patients-table');
    if (!tableBody) return;
    const headerRow = tableBody.querySelector('.header-row');
    const headerHtml = headerRow ? headerRow.outerHTML : '';
    
    if (!patients || patients.length === 0) {
        tableBody.innerHTML = headerHtml + '<div class="table-row" style="justify-content: center; padding: 4rem; color: #64748b; flex-direction: column; gap: 1rem;">' +
            '<i class="fa-solid fa-folder-open" style="font-size: 3rem; opacity: 0.2;"></i>' +
            '<span>No aftercare patients found.</span></div>';
        return;
    }

    let rowsHtml = headerHtml;
    let recipientCount = 0;
    let donorCount = 0;
    let totalAge = 0;

    patients.forEach(p => {
        const typeColor = p.patient_type === 'RECIPIENT' ? '#3b82f6' : '#10b981';
        if (p.patient_type === 'RECIPIENT') recipientCount++;
        else donorCount++;
        totalAge += parseInt(p.age || 0);

        rowsHtml += `
            <div class="table-row" 
                 style="display: grid; grid-template-columns: 2.2fr 1.2fr 1fr 1.5fr 130px; gap: 1rem; padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9; align-items: center; transition: all 0.2s ease; cursor: pointer;"
                 onclick="viewPatientDetails(${p.id})"
                 onmouseover="this.style.background='#f8fafc'; this.style.boxShadow='inset 4px 0 0 #1e40af'"
                 onmouseout="this.style.background='transparent'; this.style.boxShadow='none'">
                
                <div class="table-cell" style="display: flex; align-items: center; gap: 12px;">
                    <div style="width: 38px; height: 38px; background: ${typeColor}15; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: ${typeColor}; font-weight: 700; border: 1px solid ${typeColor}30;">
                        ${(p.full_name || 'P').charAt(0)}
                    </div>
                    <div style="display: flex; flex-direction: column;">
                        <span style="font-weight: 700; color: #1e293b; font-size: 0.95rem;">${p.full_name}</span>
                        <span style="font-size: 0.75rem; color: #64748b; font-weight: 500;">${p.registration_number}</span>
                    </div>
                </div>
                
                <div class="table-cell" style="font-weight: 600; color: #1e293b;">
                    ${p.age || 'N/A'} Yrs <span style="color: #94a3b8; font-weight: 400; font-size: 0.8rem; margin-left: 4px;">/ ${p.gender || 'M'}</span>
                </div>
                
                <div class="table-cell" style="text-align: center;">
                    <span style="background: #fee2e2; color: #991b1b; padding: 4px 10px; border-radius: 8px; font-size: 0.75rem; font-weight: 800; border: 1px solid #fecaca;">
                        ${p.blood_group || 'N/A'}
                    </span>
                </div>
                
                <div class="table-cell" style="text-align: center;">
                    <span style="background: ${typeColor}15; color: ${typeColor}; padding: 5px 12px; border-radius: 10px; font-size: 0.75rem; font-weight: 700; border: 1px solid ${typeColor}25; text-transform: uppercase; letter-spacing: 0.02em;">
                        ${p.patient_type}
                    </span>
                </div>
                
                <div class="table-cell" style="display: flex; justify-content: center;">
                    <span style="background: #ecfdf5; color: #059669; padding: 5px 12px; border-radius: 10px; font-size: 0.7rem; font-weight: 800; display: flex; align-items: center; gap: 6px; border: 1px solid #d1fae5; text-transform: uppercase;">
                        <span style="width: 6px; height: 6px; background: #10b981; border-radius: 50%; box-shadow: 0 0 0 3px #d1fae5;"></span>
                        ${p.status}
                    </span>
                </div>
            </div>
        `;
    });

    tableBody.innerHTML = rowsHtml;

    // Update Tab Stats
    if (document.getElementById('tab-total-patients')) document.getElementById('tab-total-patients').innerText = patients.length;
    if (document.getElementById('tab-recipient-patients')) document.getElementById('tab-recipient-patients').innerText = recipientCount;
    if (document.getElementById('tab-donor-patients')) document.getElementById('tab-donor-patients').innerText = donorCount;
    if (document.getElementById('tab-average-age')) document.getElementById('tab-average-age').innerText = patients.length > 0 ? Math.round(totalAge / patients.length) : 0;
}

/**
 * AJAX: View Patient Details
 */
async function viewPatientDetails(id) {
    try {
        const response = await fetch(`${ROOT}/donation-admin/getPatientDetails?id=${id}`);
        const data = await response.json();

        if (data.success) {
            const p = data.patient;
            
            // Map Elements
            const nameEl = document.getElementById('modal-patient-name');
            const idEl = document.getElementById('modal-patient-id');
            const ageGenderEl = document.getElementById('modal-patient-age-gender');
            const bloodTypeEl = document.getElementById('modal-patient-bloodtype');
            const typeSubEl = document.getElementById('modal-patient-type');
            const statusEl = document.getElementById('modal-patient-status');
            const nicEl = document.getElementById('modal-patient-nic');
            const hospEl = document.getElementById('modal-patient-hosp');
            
            // New Fields
            const surgeryTypeEl = document.getElementById('modal-patient-surgery-type');
            const surgeryDateEl = document.getElementById('modal-patient-surgery-date');
            const medicalNotesEl = document.getElementById('modal-patient-medical');
            const contactDetailsEl = document.getElementById('modal-patient-contact');
            
            // Conditional Sections
            const surgeryTypeSec = document.getElementById('modal-surgery-type-section');
            const surgeryDateSec = document.getElementById('modal-surgery-date-section');
            const extendedDetailsSec = document.getElementById('modal-extended-details');

            if (nameEl) nameEl.textContent = p.full_name || 'N/A';
            if (idEl) idEl.textContent = p.registration_number || 'N/A';
            
            // Age/Gender formatting
            if (ageGenderEl) {
                const ageDisplay = p.age ? `${p.age}Y` : '--';
                const genderDisplay = p.gender || 'N/A';
                ageGenderEl.textContent = `${ageDisplay} / ${genderDisplay}`;
            }
            
            if (bloodTypeEl) bloodTypeEl.textContent = p.blood_group || 'N/A';
            if (typeSubEl) typeSubEl.textContent = p.patient_type === 'RECIPIENT' ? 'Organ Recipient' : 'Organ Donor';
            if (statusEl) {
                statusEl.textContent = p.status || 'ACTIVE';
                statusEl.style.color = (p.status || '').toUpperCase() === 'ACTIVE' ? '#10b981' : '#f59e0b';
            }
            if (nicEl) nicEl.textContent = p.nic || 'N/A';
            if (hospEl) hospEl.textContent = p.hospital_name || (p.hospital_registration_no ? `Reg No: ${p.hospital_registration_no}` : 'General Hospital');

            // Conditional display based on type
            if (p.patient_type === 'RECIPIENT') {
                if (surgeryTypeSec) surgeryTypeSec.style.display = 'block';
                if (surgeryDateSec) surgeryDateSec.style.display = 'block';
                if (extendedDetailsSec) extendedDetailsSec.style.display = 'block';
                
                if (surgeryTypeEl) surgeryTypeEl.textContent = p.surgery_type || 'N/A';
                if (surgeryDateEl) surgeryDateEl.textContent = p.surgery_date || 'N/A';
                if (medicalNotesEl) medicalNotesEl.textContent = p.medical_details || 'No clinical notes available.';
                if (contactDetailsEl) contactDetailsEl.textContent = p.contact_details || 'No contact information provided.';
            } else {
                // Donor - Hide surgery and clinical sections
                if (surgeryTypeSec) surgeryTypeSec.style.display = 'none';
                if (surgeryDateSec) surgeryDateSec.style.display = 'none';
                if (extendedDetailsSec) extendedDetailsSec.style.display = 'none';
            }

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
