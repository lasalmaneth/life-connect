// tributes.js - Success Stories Management JavaScript

document.addEventListener('DOMContentLoaded', function () {
    console.log('Success stories management initialized');
    setupTributeListeners();
    updateSidebarBadge(); // Initial badge update
});

function setupTributeListeners() {
    const searchInput = document.getElementById('tribute-search');
    if (searchInput) {
        searchInput.addEventListener('input', () => filterTributes());
    }
    const statusFilter = document.getElementById('tribute-status-filter');
    if (statusFilter) {
        statusFilter.addEventListener('change', () => filterTributes());
    }
    // Setup form submission for Add Story
    const storyForm = document.getElementById('storyForm');
    if (storyForm) {
        storyForm.addEventListener('submit', handleStoryFormSubmit);
    }
    
    // Initial data fetch
    fetchTributes();
}

/**
 * Fetch and update sidebar notification badge
 */
function updateSidebarBadge() {
    fetch('/life-connect/public/tributes-admin/getPendingCount')
        .then(response => response.json())
        .then(data => {
            const badge = document.getElementById('nav-stories-badge');
            if (badge) {
                if (data.success && data.count > 0) {
                    badge.textContent = data.count > 9 ? '9+' : data.count;
                    badge.style.display = 'block';
                } else {
                    badge.style.display = 'none';
                }
            }
        })
        .catch(error => console.error('Error fetching badge count:', error));
}

/**
 * Fetch all tributes from the controller
 */
function fetchTributes() {
    fetch('/life-connect/public/tributes-admin/getStories')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.allStories = data.stories; // Store globally for filtering
                renderTributesTable(data.stories);
            }
        })
        .catch(error => console.error('Error fetching stories:', error));
}

/**
 * Render stories into the table
 */
function renderTributesTable(stories) {
    const table = document.getElementById('tributes-table');
    if (!table) return;

    // Keep the header row
    const headerRow = table.querySelector('.table-row:first-child');
    if (!headerRow) {
        console.error('Tributes table header not found!');
        return;
    }
    
    table.innerHTML = '';
    table.appendChild(headerRow);

    if (stories.length === 0) {
        const noResults = document.createElement('div');
        noResults.className = 'table-row no-results-message';
        noResults.innerHTML = '<div class="table-cell" style="grid-column: 1 / -1; text-align: center; padding: 3rem; color: #94a3b8;">No success stories found in the system</div>';
        table.appendChild(noResults);
        return;
    }

    stories.forEach(story => {
        const description = story.description || '';
        const descriptionPreview = description.length > 100 ? description.substring(0, 100) + '...' : description;
        const status = story.status || 'Pending';
        
        const row = document.createElement('div');
        row.className = 'tribute-row'; // Use the new class we added to CSS
        row.onclick = () => viewTributeDetails(story.story_id);
        
        row.innerHTML = `
            <div class="table-cell" style="font-weight: 500; color: #1e293b;">
                ${story.title}
            </div>
            <div class="table-cell" style="color: #64748b; font-size: 0.9rem;">
                <div class="message-preview" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">${descriptionPreview}</div>
            </div>
            <div class="table-cell" style="color: #64748b;">
                ${new Date(story.success_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}
            </div>
            <div class="table-cell" style="display: flex; justify-content: center;">
                <span class="status-badge status-${status.toLowerCase()}" style="padding: 0.35rem 0.75rem; border-radius: 50px; font-size: 0.75rem; font-weight: 600;">${status}</span>
            </div>
        `;
        table.appendChild(row);
    });

    // Apply any current filters after rendering
    filterTributes();
}

/**
 * Combined filter for search text and status dropdown
 */
function filterTributes() {
    const searchInput = document.getElementById('tribute-search');
    const statusSelect = document.getElementById('tribute-status-filter');
    
    const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
    const statusValue = statusSelect ? statusSelect.value : '';
    
    const table = document.getElementById('tributes-table');
    if (table) {
        const rows = table.querySelectorAll('.tribute-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const statusBadge = row.querySelector('.status-badge');
            const rowStatus = statusBadge ? statusBadge.textContent.trim() : '';
            
            const matchesSearch = text.includes(searchTerm);
            const matchesStatus = !statusValue || rowStatus === statusValue;
            
            const isVisible = matchesSearch && matchesStatus;
            row.style.display = isVisible ? 'grid' : 'none';
            if (isVisible) visibleCount++;
        });

        // Show/hide no results message
        let noResults = table.querySelector('.no-results-message');
        if (visibleCount === 0 && rows.length > 0) {
            if (!noResults) {
                noResults = document.createElement('div');
                noResults.className = 'table-row no-results-message';
                noResults.innerHTML = '<div class="table-cell" style="grid-column: 1 / -1; text-align: center; padding: 3rem; color: #94a3b8;">No success stories matching your criteria</div>';
                table.appendChild(noResults);
            }
            noResults.style.display = 'grid';
        } else if (noResults) {
            noResults.style.display = 'none';
        }
    }
}

/**
 * Row click handler - Fetches details and opens modal
 */
function viewTributeDetails(storyId) {
    if (!storyId) return;
    
    fetch('/life-connect/public/tributes-admin/getTributeDetails?story_id=' + storyId)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showTributeModal(data.story);
            } else {
                alert('Error loading story details: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading story details.');
        });
}

function showTributeModal(story) {
    if (!story) return;
    
    // Populate read-only fields with safety checks
    const setEl = (id, val) => {
        const el = document.getElementById(id);
        if (el) el.textContent = val || 'N/A';
    };

    setEl('modal-story-id', '#' + story.story_id);
    setEl('modal-title', story.title);
    setEl('modal-hospital-reg', story.hospital_registration_no);
    setEl('modal-success-date', story.success_date ? new Date(story.success_date).toLocaleDateString() : 'N/A');
    
    const statusElement = document.getElementById('modal-status');
    if (statusElement) {
        statusElement.textContent = story.status || 'N/A';
        statusElement.className = 'status-badge status-' + (story.status ? story.status.toLowerCase() : 'pending');
    }
    
    setEl('modal-description', story.description || 'No description provided.');
    setEl('modal-created-at', story.created_at ? new Date(story.created_at).toLocaleString() : 'N/A');

    // Set value for status update dropdown
    const updateSelect = document.getElementById('modal-status-update');
    if (updateSelect) {
        updateSelect.value = story.status || 'Pending';
        updateSelect.setAttribute('data-story-id', story.story_id);
    }

    const modal = document.getElementById('tributeModal');
    if (modal) modal.style.display = 'block';
}

/**
 * Action: Update Story Status
 */
function updateTributeStatusAction() {
    const updateSelect = document.getElementById('modal-status-update');
    const storyId = updateSelect.getAttribute('data-story-id');
    const newStatus = updateSelect.value;

    if (!storyId) return;

    const formData = new FormData();
    formData.append('story_id', storyId);
    formData.append('status', newStatus);

    fetch('/life-connect/public/tributes-admin/updateStatus', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('success', data.message);
            closeTributeModal();
            fetchTributes(); // Dynamic refresh
            updateSidebarBadge();
        } else {
            showToast('error', 'Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error updating status:', error);
        showToast('error', 'Error updating status.');
    });
}

function showAddStoryModal() {
    document.getElementById('story-form-title').textContent = 'Add New Success Story';
    document.getElementById('storyForm').reset();
    document.getElementById('form-story-id').value = '';
    
    loadHospitals().then(() => {
        document.getElementById('storyFormModal').style.display = 'block';
    });
}

function loadHospitals() {
    return fetch('/life-connect/public/tributes-admin/getHospitals')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const hospitalSelect = document.getElementById('form-hospital-reg');
                hospitalSelect.innerHTML = '<option value="">Select Hospital</option>';
                data.hospitals.forEach(hospital => {
                    const option = document.createElement('option');
                    option.value = hospital.registration_no;
                    option.textContent = hospital.h_name + ' (' + hospital.registration_no + ')';
                    hospitalSelect.appendChild(option);
                });
            }
        })
        .catch(error => console.error('Error loading hospitals:', error));
}

function handleStoryFormSubmit(event) {
    event.preventDefault();
    const formData = new FormData(event.target);

    fetch('/life-connect/public/tributes-admin/saveStory', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('success', data.message);
            closeStoryFormModal();
            fetchTributes(); // Dynamic refresh
            updateSidebarBadge();
        } else {
            showToast('error', 'Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error saving story:', error);
        showToast('error', 'Error saving story.');
    });
}

function deleteTribute() {
    const storyId = document.getElementById('modal-story-id').textContent.replace('#', '');
    if (!storyId || storyId === '0') return;

    if (confirm('Are you sure you want to delete this success story permanently?')) {
        const formData = new FormData();
        formData.append('story_id', storyId);

        fetch('/life-connect/public/tributes-admin/deleteTribute', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('warning', data.message);
                closeTributeModal();
                fetchTributes(); // Dynamic refresh
                updateSidebarBadge();
            } else {
                showToast('error', 'Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error deleting story:', error);
            showToast('error', 'Error deleting story.');
        });
    }
}

function closeTributeModal() {
    document.getElementById('tributeModal').style.display = 'none';
}

function closeStoryFormModal() {
    document.getElementById('storyFormModal').style.display = 'none';
}

// Utility for toasts if not already globally defined
function showToast(type, message) {
    if (typeof window.showToast === 'function') {
        window.showToast(type, message);
    } else {
        alert(message);
    }
}

// Close modals when clicking outside
document.addEventListener('click', function (e) {
    if (e.target.id === 'tributeModal') closeTributeModal();
    if (e.target.id === 'storyFormModal') closeStoryFormModal();
});