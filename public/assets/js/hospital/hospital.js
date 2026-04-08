// Hospital Management JavaScript Functions
// This file contains all the JavaScript functionality for the Hospital portal

// Global variables for PHP data (will be set by the HTML page)
let organRequests = [];
let recipients = [];
let successStories = [];

// Function to set PHP data from the page
function setPHPData(organRequestsData, recipientsData, storiesData) {
    organRequests = organRequestsData;
    recipients = recipientsData;
    successStories = storiesData;
}

function showContent(id) {
    // Hide all content sections
    document.querySelectorAll('.content-section').forEach(s => s.style.display = 'none');
    const target = document.getElementById(id);
    if (target) target.style.display = '';
    
    // Update active menu item
    document.querySelectorAll('.menu-item').forEach(i => i.classList.remove('active'));
    const item = Array.from(document.querySelectorAll('.menu-item')).find(mi => mi.getAttribute('onclick')?.includes(id));
    if (item) item.classList.add('active');
    
    // Load data for specific sections
    if (id === 'recipients') {
        loadRecipients();
    } else if (id === 'organ-requests') {
        loadOrganRequests();
    } else if (id === 'stories') {
        loadStories();
    }
}

// Organ Request Functions
function openRequestModal() { document.getElementById('request-modal').classList.add('show'); }
function closeRequestModal() { document.getElementById('request-modal').classList.remove('show'); }
function saveRequest() { 
    const organ = document.getElementById('organ-type').value;
    const urgency = document.getElementById('urgency-level').value;
    const notes = document.getElementById('request-notes').value;
    
    if (!organ || !urgency) {
        showServerMessage('localhost: Error - Please fill all required fields', 'error');
        return;
    }
    
    // Submit form to same page
    const form = document.createElement('form');
    form.method = 'POST';
    form.style.display = 'none';
    
    const actionInput = document.createElement('input');
    actionInput.type = 'hidden';
    actionInput.name = 'action';
    actionInput.value = 'add_organ_request';
    form.appendChild(actionInput);
    
    const organInput = document.createElement('input');
    organInput.type = 'hidden';
    organInput.name = 'organ_type';
    organInput.value = organ;
    form.appendChild(organInput);
    
    const urgencyInput = document.createElement('input');
    urgencyInput.type = 'hidden';
    urgencyInput.name = 'urgency';
    urgencyInput.value = urgency;
    form.appendChild(urgencyInput);
    
    const notesInput = document.createElement('input');
    notesInput.type = 'hidden';
    notesInput.name = 'notes';
    notesInput.value = notes;
    form.appendChild(notesInput);
    
    document.body.appendChild(form);
    form.submit();
}
function editRequest(requestId) { 
    showServerMessage('localhost: Opening edit form for organ request ID: ' + requestId, 'info'); 
}
function deleteRequest(requestId) { 
    if (confirm('Are you sure you want to delete this organ request?')) {
        const formData = new FormData();
        formData.append('request_id', requestId);
        
        fetch('app/controllers/Hospital.php?action=delete_organ_request', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            showServerMessage('localhost: ' + data.message, data.success ? 'success' : 'error');
            if (data.success) loadOrganRequests();
        })
        .catch(error => {
            console.error('Error:', error);
            showServerMessage('localhost: Network error occurred', 'error');
        });
    }
}

function loadOrganRequests() {
    // Use global data
    updateOrganRequestsTable(organRequests);
}

function updateOrganRequestsTable(requests) {
    const tableContent = document.querySelector('#organ-requests .table-content');
    if (!tableContent) return;
    
    // Clear existing rows (except header)
    const existingRows = tableContent.querySelectorAll('.table-row:not(:first-child)');
    existingRows.forEach(row => row.remove());
    
    // Add new rows
    requests.forEach(request => {
        const row = document.createElement('div');
        row.className = 'table-row';
        row.innerHTML = `
            <div class="table-cell name" data-label="Organ Type">${request.organ_type}</div>
            <div class="table-cell" data-label="Urgency">
                <span class="status-badge ${request.urgency === 'urgent' ? 'status-danger' : request.urgency === 'high' ? 'status-active' : 'status-pending'}">${request.urgency}</span>
            </div>
            <div class="table-cell" data-label="Notes">${request.notes || 'No notes'}</div>
            <div class="table-cell" data-label="Created Date">${new Date(request.request_date || request.created_at).toLocaleDateString()}</div>
            <div class="table-cell" data-label="Actions">
                <button class="btn btn-secondary btn-small" onclick="editRequest(${request.request_id})">Edit</button>
                <button class="btn btn-danger btn-small" onclick="deleteRequest(${request.request_id})">Delete</button>
            </div>
        `;
        tableContent.appendChild(row);
    });
}

// Eligibility Functions
function approveEligibility() { showServerMessage('localhost: Donor eligibility approved and updated in database', 'success'); }
function rejectEligibility() { showServerMessage('localhost: Donor eligibility rejected and status updated', 'error'); }

// Recipient Functions
function openRecipientModal() { document.getElementById('recipient-modal').classList.add('show'); }
function closeRecipientModal() { 
    document.getElementById('recipient-modal').classList.remove('show');
    // Reset modal to add mode
    document.querySelector('#recipient-modal .modal-header h3').textContent = 'Add Recipient Patient';
    document.getElementById('recipient-nic').value = '';
    document.getElementById('recipient-name').value = '';
    document.getElementById('recipient-organ').value = '';
    document.getElementById('surgery-date').value = '';
    document.getElementById('treatment-notes').value = '';
    
    // Reset button
    const saveButton = document.querySelector('#recipient-modal button[onclick*="updateRecipient"]');
    if (saveButton) {
        saveButton.textContent = 'Save Recipient';
        saveButton.setAttribute('onclick', 'saveRecipient()');
    }
}
function saveRecipient() { 
    const nic = document.getElementById('recipient-nic').value;
    const name = document.getElementById('recipient-name').value;
    const organ = document.getElementById('recipient-organ').value;
    const date = document.getElementById('surgery-date').value;
    const notes = document.getElementById('treatment-notes').value;
    
    if (!nic || !name || !organ || !date) {
        showServerMessage('localhost: Error - Please fill all required fields', 'error');
        return;
    }
    
    // Submit form to same page
    const form = document.createElement('form');
    form.method = 'POST';
    form.style.display = 'none';
    
    const actionInput = document.createElement('input');
    actionInput.type = 'hidden';
    actionInput.name = 'action';
    actionInput.value = 'add_recipient';
    form.appendChild(actionInput);
    
    const nicInput = document.createElement('input');
    nicInput.type = 'hidden';
    nicInput.name = 'nic';
    nicInput.value = nic;
    form.appendChild(nicInput);
    
    const nameInput = document.createElement('input');
    nameInput.type = 'hidden';
    nameInput.name = 'name';
    nameInput.value = name;
    form.appendChild(nameInput);
    
    const organInput = document.createElement('input');
    organInput.type = 'hidden';
    organInput.name = 'organ_received';
    organInput.value = organ;
    form.appendChild(organInput);
    
    const dateInput = document.createElement('input');
    dateInput.type = 'hidden';
    dateInput.name = 'surgery_date';
    dateInput.value = date;
    form.appendChild(dateInput);
    
    const notesInput = document.createElement('input');
    notesInput.type = 'hidden';
    notesInput.name = 'treatment_notes';
    notesInput.value = notes;
    form.appendChild(notesInput);
    
    document.body.appendChild(form);
    form.submit();
}
function editRecipient(recipientId) { 
    // Get recipient data and populate edit form
    const recipient = recipients.find(r => r.recipient_id == recipientId);
    
    if (recipient) {
        // Update modal header
        document.querySelector('#recipient-modal .modal-header h3').textContent = 'Edit Recipient Patient';
        
        // Populate form fields
        document.getElementById('recipient-nic').value = recipient.nic;
        document.getElementById('recipient-name').value = recipient.name;
        document.getElementById('recipient-organ').value = recipient.organ_received;
        document.getElementById('surgery-date').value = recipient.surgery_date;
        document.getElementById('treatment-notes').value = recipient.treatment_notes;
        
        // Change the save button to update button
        const saveButton = document.querySelector('#recipient-modal button[onclick="saveRecipient()"]');
        if (saveButton) {
            saveButton.textContent = 'Update Recipient';
            saveButton.setAttribute('onclick', 'updateRecipient(' + recipientId + ')');
        }
        
        // Show the modal
        document.getElementById('recipient-modal').classList.add('show');
    }
}
function updateRecipient(recipientId) {
    const nic = document.getElementById('recipient-nic').value;
    const name = document.getElementById('recipient-name').value;
    const organ = document.getElementById('recipient-organ').value;
    const surgery_date = document.getElementById('surgery-date').value;
    const notes = document.getElementById('treatment-notes').value;
    
    if (!nic || !name || !organ || !surgery_date) {
        showServerMessage('localhost: Error - Please fill all required fields', 'error');
        return;
    }
    
    // Submit form to same page
    const form = document.createElement('form');
    form.method = 'POST';
    form.style.display = 'none';
    
    const actionInput = document.createElement('input');
    actionInput.type = 'hidden';
    actionInput.name = 'action';
    actionInput.value = 'update_recipient';
    form.appendChild(actionInput);
    
    const recipientIdInput = document.createElement('input');
    recipientIdInput.type = 'hidden';
    recipientIdInput.name = 'recipient_id';
    recipientIdInput.value = recipientId;
    form.appendChild(recipientIdInput);
    
    const nicInput = document.createElement('input');
    nicInput.type = 'hidden';
    nicInput.name = 'nic';
    nicInput.value = nic;
    form.appendChild(nicInput);
    
    const nameInput = document.createElement('input');
    nameInput.type = 'hidden';
    nameInput.name = 'name';
    nameInput.value = name;
    form.appendChild(nameInput);
    
    const organInput = document.createElement('input');
    organInput.type = 'hidden';
    organInput.name = 'organ_received';
    organInput.value = organ;
    form.appendChild(organInput);
    
    const dateInput = document.createElement('input');
    dateInput.type = 'hidden';
    dateInput.name = 'surgery_date';
    dateInput.value = surgery_date;
    form.appendChild(dateInput);
    
    const notesInput = document.createElement('input');
    notesInput.type = 'hidden';
    notesInput.name = 'treatment_notes';
    notesInput.value = notes;
    form.appendChild(notesInput);
    
    const statusInput = document.createElement('input');
    statusInput.type = 'hidden';
    statusInput.name = 'status';
    statusInput.value = 'Active'; // Default status
    form.appendChild(statusInput);
    
    document.body.appendChild(form);
    form.submit();
}

function deleteRecipient(recipientId) {
    if (confirm('Are you sure you want to delete this recipient?')) {
        // Submit form to same page
        const form = document.createElement('form');
        form.method = 'POST';
        form.style.display = 'none';
        
        const actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        actionInput.value = 'delete_recipient';
        form.appendChild(actionInput);
        
        const recipientIdInput = document.createElement('input');
        recipientIdInput.type = 'hidden';
        recipientIdInput.name = 'recipient_id';
        recipientIdInput.value = recipientId;
        form.appendChild(recipientIdInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}
function viewTreatmentLog() { showServerMessage('localhost: Loading treatment log from database', 'success'); }
function exportRecipients() { showServerMessage('localhost: Exporting recipient data to Excel file', 'success'); }

function loadRecipients() {
    // Use global data
    updateRecipientsTable(recipients);
}

function updateRecipientsTable(recipients) {
    const tableContent = document.querySelector('#recipients .table-content');
    if (!tableContent) return;
    
    // Clear existing rows (except header)
    const existingRows = tableContent.querySelectorAll('.table-row:not(:first-child)');
    existingRows.forEach(row => row.remove());
    
    // Add new rows
    recipients.forEach(recipient => {
        const row = document.createElement('div');
        row.className = 'table-row';
        row.innerHTML = `
            <div class="table-cell name" data-label="NIC">${recipient.nic}</div>
            <div class="table-cell" data-label="Name">${recipient.name}</div>
            <div class="table-cell" data-label="Organ">${recipient.organ_received}</div>
            <div class="table-cell" data-label="Surgery Date">${new Date(recipient.surgery_date).toLocaleDateString()}</div>
            <div class="table-cell" data-label="Status">
                <span class="status-badge ${recipient.status === 'Active' ? 'status-active' : recipient.status === 'Discharged' ? 'status-success' : 'status-pending'}">${recipient.status}</span>
            </div>
            <div class="table-cell" data-label="Actions">
                <button class="btn btn-secondary btn-small" onclick="editRecipient(${recipient.recipient_id})">Edit</button>
                <button class="btn btn-danger btn-small" onclick="deleteRecipient(${recipient.recipient_id})">Delete</button>
            </div>
        `;
        tableContent.appendChild(row);
    });
}

// Story Functions
function openStoryModal() { document.getElementById('story-modal').classList.add('show'); }
function closeStoryModal() { 
    document.getElementById('story-modal').classList.remove('show');
    // Reset modal to add mode
    document.querySelector('#story-modal .modal-header h3').textContent = 'Add Success Story';
    document.getElementById('story-title').value = '';
    document.getElementById('story-description').value = '';
    document.getElementById('success-date').value = '';
    
    // Reset button
    const saveButton = document.querySelector('#story-modal button[onclick*="updateStory"]');
    if (saveButton) {
        saveButton.textContent = 'Save Story';
        saveButton.setAttribute('onclick', 'saveStory()');
    }
}
function saveStory() { 
    const title = document.getElementById('story-title').value;
    const description = document.getElementById('story-description').value;
    const date = document.getElementById('success-date').value;
    
    if (!title || !description || !date) {
        showServerMessage('localhost: Error - Please fill all required fields', 'error');
        return;
    }
    
    // Submit form to same page
    const form = document.createElement('form');
    form.method = 'POST';
    form.style.display = 'none';
    
    const actionInput = document.createElement('input');
    actionInput.type = 'hidden';
    actionInput.name = 'action';
    actionInput.value = 'add_success_story';
    form.appendChild(actionInput);
    
    const titleInput = document.createElement('input');
    titleInput.type = 'hidden';
    titleInput.name = 'title';
    titleInput.value = title;
    form.appendChild(titleInput);
    
    const descriptionInput = document.createElement('input');
    descriptionInput.type = 'hidden';
    descriptionInput.name = 'description';
    descriptionInput.value = description;
    form.appendChild(descriptionInput);
    
    const dateInput = document.createElement('input');
    dateInput.type = 'hidden';
    dateInput.name = 'success_date';
    dateInput.value = date;
    form.appendChild(dateInput);
    
    document.body.appendChild(form);
    form.submit();
}
function editStory(storyId) { 
    // Get story data and populate edit form
    const story = successStories.find(s => s.story_id == storyId);
    
    if (story) {
        // Update modal header
        document.querySelector('#story-modal .modal-header h3').textContent = 'Edit Success Story';
        
        // Populate form fields
        document.getElementById('story-title').value = story.title;
        document.getElementById('story-description').value = story.description;
        document.getElementById('success-date').value = story.success_date;
        
        // Change the save button to update button
        const saveButton = document.querySelector('#story-modal button[onclick="saveStory()"]');
        if (saveButton) {
            saveButton.textContent = 'Update Story';
            saveButton.setAttribute('onclick', 'updateStory(' + storyId + ')');
        }
        
        // Show the modal
        document.getElementById('story-modal').classList.add('show');
    }
}

function updateStory(storyId) {
    const title = document.getElementById('story-title').value;
    const description = document.getElementById('story-description').value;
    const success_date = document.getElementById('success-date').value;
    
    if (!title || !description || !success_date) {
        showServerMessage('localhost: Error - Please fill all required fields', 'error');
        return;
    }
    
    // Submit form to same page
    const form = document.createElement('form');
    form.method = 'POST';
    form.style.display = 'none';
    
    const actionInput = document.createElement('input');
    actionInput.type = 'hidden';
    actionInput.name = 'action';
    actionInput.value = 'update_success_story';
    form.appendChild(actionInput);
    
    const storyIdInput = document.createElement('input');
    storyIdInput.type = 'hidden';
    storyIdInput.name = 'story_id';
    storyIdInput.value = storyId;
    form.appendChild(storyIdInput);
    
    const titleInput = document.createElement('input');
    titleInput.type = 'hidden';
    titleInput.name = 'title';
    titleInput.value = title;
    form.appendChild(titleInput);
    
    const descriptionInput = document.createElement('input');
    descriptionInput.type = 'hidden';
    descriptionInput.name = 'description';
    descriptionInput.value = description;
    form.appendChild(descriptionInput);
    
    const dateInput = document.createElement('input');
    dateInput.type = 'hidden';
    dateInput.name = 'success_date';
    dateInput.value = success_date;
    form.appendChild(dateInput);
    
    const statusInput = document.createElement('input');
    statusInput.type = 'hidden';
    statusInput.name = 'status';
    statusInput.value = 'Pending'; // Default status
    form.appendChild(statusInput);
    
    document.body.appendChild(form);
    form.submit();
}

function deleteStory(storyId) {
    if (confirm('Are you sure you want to delete this success story?')) {
        // Submit form to same page
        const form = document.createElement('form');
        form.method = 'POST';
        form.style.display = 'none';
        
        const actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        actionInput.value = 'delete_success_story';
        form.appendChild(actionInput);
        
        const storyIdInput = document.createElement('input');
        storyIdInput.type = 'hidden';
        storyIdInput.name = 'story_id';
        storyIdInput.value = storyId;
        form.appendChild(storyIdInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}

function loadStories() {
    // Use global data
    updateStoriesTable(successStories);
}

function updateStoriesTable(stories) {
    const tableContent = document.querySelector('#stories .table-content');
    if (!tableContent) return;
    
    // Clear existing rows (except header)
    const existingRows = tableContent.querySelectorAll('.table-row:not(:first-child)');
    existingRows.forEach(row => row.remove());
    
    // Add new rows
    stories.forEach(story => {
        const row = document.createElement('div');
        row.className = 'table-row';
        row.innerHTML = `
            <div class="table-cell name" data-label="Title">${story.title}</div>
            <div class="table-cell" data-label="Description">${story.description.substring(0, 100)}${story.description.length > 100 ? '...' : ''}</div>
            <div class="table-cell" data-label="Success Date">${new Date(story.success_date).toLocaleDateString()}</div>
            <div class="table-cell" data-label="Status">
                <span class="status-badge ${story.status === 'Approved' ? 'status-success' : story.status === 'Pending' ? 'status-pending' : 'status-danger'}">${story.status}</span>
            </div>
            <div class="table-cell" data-label="Actions">
                <button class="btn btn-secondary btn-small" onclick="editStory(${story.story_id})">Edit</button>
                <button class="btn btn-danger btn-small" onclick="deleteStory(${story.story_id})">Delete</button>
            </div>
        `;
        tableContent.appendChild(row);
    });
}

function showServerMessage(message, type) {
    // Remove any existing notifications to prevent stacking
    const existingNotifications = document.querySelectorAll('.server-notification');
    existingNotifications.forEach(notification => notification.remove());
    
    const n = document.createElement('div');
    n.className = 'server-notification';
    n.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? 'linear-gradient(135deg, #10b981, #059669)' : 
                   type === 'error' ? 'linear-gradient(135deg, #ef4444, #dc2626)' : 
                   type === 'info' ? 'linear-gradient(135deg, #3b82f6, #2563eb)' : 
                   'linear-gradient(135deg, #f59e0b, #d97706)'};
        color: white;
        padding: 16px 24px;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2), 0 4px 12px rgba(0,0,0,0.1);
        z-index: 10000;
        font-weight: 600;
        font-size: 14px;
        max-width: 350px;
        word-wrap: break-word;
        transform: translateX(120%);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid rgba(255,255,255,0.2);
        backdrop-filter: blur(10px);
        cursor: pointer;
    `;
    
    // Add close button
    n.innerHTML = `
        <div style="display: flex; align-items: center; gap: 12px; position: relative;">
            <div style="display: flex; align-items: center; gap: 8px; flex: 1;">
                <span style="font-size: 18px; filter: drop-shadow(0 1px 2px rgba(0,0,0,0.3));">
                    ${type === 'success' ? '✅' : type === 'error' ? '❌' : type === 'info' ? 'ℹ️' : '⚠️'}
                </span>
                <span style="text-shadow: 0 1px 2px rgba(0,0,0,0.3);">${message}</span>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" 
                    style="background: rgba(255,255,255,0.2); border: none; color: white; 
                           border-radius: 50%; width: 24px; height: 24px; cursor: pointer; 
                           display: flex; align-items: center; justify-content: center; 
                           font-size: 12px; font-weight: bold; transition: background 0.2s;">
                ×
            </button>
        </div>
    `;
    
    document.body.appendChild(n);
    
    // Animate in
    requestAnimationFrame(() => {
        n.style.transform = 'translateX(0)';
        n.style.opacity = '1';
    });
    
    // Auto-hide after 3 seconds
    setTimeout(() => { 
        n.style.transform = 'translateX(120%)';
        n.style.opacity = '0';
        setTimeout(() => n.remove(), 400);
    }, 3000);
    
    // Add hover effect
    n.addEventListener('mouseenter', () => {
        n.style.transform = 'translateX(0) scale(1.02)';
        n.style.boxShadow = '0 15px 35px rgba(0,0,0,0.3), 0 6px 16px rgba(0,0,0,0.15)';
    });
    
    n.addEventListener('mouseleave', () => {
        n.style.transform = 'translateX(0) scale(1)';
        n.style.boxShadow = '0 10px 25px rgba(0,0,0,0.2), 0 4px 12px rgba(0,0,0,0.1)';
    });
}

function notify(message, type) {
    showServerMessage(message, type);
}

// User dropdown functions
function toggleUserDropdown() {
    const dropdown = document.getElementById('user-dropdown');
    dropdown.classList.toggle('show');
}

function editProfile() {
    showServerMessage('localhost: Opening profile edit form', 'info');
    // Close dropdown
    document.getElementById('user-dropdown').classList.remove('show');
}

function logout() {
    if (confirm('Are you sure you want to logout?')) {
        showServerMessage('localhost: Logging out...', 'info');
        // Close dropdown
        document.getElementById('user-dropdown').classList.remove('show');
        
        // Simulate logout process
        setTimeout(() => {
            // In a real application, this would redirect to login page
            showServerMessage('localhost: Logged out successfully. Redirecting to login...', 'success');
            // For now, just show a message
            // window.location.href = '/life-connect/app/views/login.view.php';
        }, 1000);
    }
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const userInfo = document.querySelector('.user-info');
    const dropdown = document.getElementById('user-dropdown');
    
    if (!userInfo.contains(event.target)) {
        dropdown.classList.remove('show');
    }
});

// Show notifications based on URL parameters
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const success = urlParams.get('success');
    const error = urlParams.get('error');
    
    if (success) {
        let message = '';
        let type = 'success';
        
        switch (success) {
            case 'organ_request_added':
                message = 'Organ request added successfully!';
                break;
            case 'recipient_added':
                message = 'Recipient added successfully!';
                break;
            case 'recipient_updated':
                message = 'Recipient updated successfully!';
                break;
            case 'recipient_deleted':
                message = 'Recipient deleted successfully!';
                break;
            case 'story_added':
                message = 'Success story added successfully!';
                break;
            case 'story_updated':
                message = 'Success story updated successfully!';
                break;
            case 'story_deleted':
                message = 'Success story deleted successfully!';
                break;
        }
        
        if (message) {
            showServerMessage(message, type);
        }
    }
    
    if (error) {
        let message = '';
        let type = 'error';
        
        switch (error) {
            case 'organ_request_failed':
                message = 'Failed to add organ request!';
                break;
            case 'recipient_failed':
                message = 'Failed to add recipient!';
                break;
            case 'recipient_update_failed':
                message = 'Failed to update recipient!';
                break;
            case 'recipient_delete_failed':
                message = 'Failed to delete recipient!';
                break;
            case 'story_failed':
                message = 'Failed to add success story!';
                break;
        }
        
        if (message) {
            showServerMessage(message, type);
        }
    }
    
    // Clean URL to remove parameters
    if (success || error) {
        const newUrl = window.location.pathname;
        window.history.replaceState({}, document.title, newUrl);
    }
});

// Initialize
function initializeHospital() {
    showContent('overview');
    
    // Load initial data
    loadOrganRequests();
    loadRecipients();
    loadStories();
}

// Auto-initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', initializeHospital);
