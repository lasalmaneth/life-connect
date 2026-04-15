/**
 * Hospital Portal - Core JavaScript Functions
 * Pure JavaScript (no PHP) - loaded from public/assets/js/
 */

function showContent(id, element) {
    // Hide all content sections
    document.querySelectorAll('.content-section').forEach(s => {
        s.style.display = 'none';
        s.classList.remove('active');
    });
    
    const target = document.getElementById(id);
    if (target) {
        target.style.display = 'block';
        target.classList.add('active');
    }

    // Update active menu item
    document.querySelectorAll('.menu-item').forEach(i => i.classList.remove('active'));
    if (element) {
        element.classList.add('active');
    } else {
        // Find and activate the menu item
        const items = document.querySelectorAll('.menu-item');
        items.forEach(item => {
            if (item.getAttribute('onclick') && item.getAttribute('onclick').includes(id)) {
                item.classList.add('active');
            }
        });
    }

    // Scroll to top
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    showContent('overview');
});

// MODAL FUNCTIONS
function openRequestModal() { document.getElementById('request-modal').classList.add('show'); }
function closeRequestModal() { document.getElementById('request-modal').classList.remove('show'); document.getElementById('age-error').style.display = 'none'; }
function closeDetailsModal() { document.getElementById('request-details-modal').classList.remove('show'); }
function openRecipientModal() { document.getElementById('recipient-modal').classList.add('show'); }
function closeRecipientModal() { document.getElementById('recipient-modal').classList.remove('show'); }
function openStoryModal() { document.getElementById('story-modal').classList.add('show'); }
function closeStoryModal() { document.getElementById('story-modal').classList.remove('show'); }
function openLabReportModal() { document.getElementById('lab-report-modal').classList.add('show'); }
function closeLabReportModal() { document.getElementById('lab-report-modal').classList.remove('show'); }
function openTestResultModal() { document.getElementById('test-result-modal').classList.add('show'); }
function closeTestResultModal() { document.getElementById('test-result-modal').classList.remove('show'); }
function closeExportModal() { document.getElementById('export-modal').classList.remove('show'); }
function closeProfileModal() { document.getElementById('profile-modal').classList.remove('show'); }

// AGE VALIDATION
function validateRecipientAge() {
    const ageInput = document.getElementById('recipient-age');
    const ageError = document.getElementById('age-error');
    if (!ageInput || !ageError) return;
    
    const age = ageInput.value;
    if (age === '') {
        ageError.style.display = 'none';
        return;
    }
    
    if (isNaN(age)) {
        ageError.textContent = 'Age must be a number';
        ageError.style.display = 'block';
        return;
    }
    
    const ageNum = parseInt(age);
    if (ageNum < 0) {
        ageError.textContent = 'Age cannot be negative';
        ageError.style.display = 'block';
        return;
    }
    if (ageNum < 18) {
        ageError.textContent = 'Minimum age is 18 years';
        ageError.style.display = 'block';
        return;
    }
    if (ageNum > 80) {
        ageError.textContent = 'Maximum age is 80 years';
        ageError.style.display = 'block';
        return;
    }
    
    ageError.style.display = 'none';
}

// NIC VALIDATION
function validateAndFetchNIC() {
    const nicInput = document.getElementById('recipient-nic');
    const nicError = document.getElementById('nic-error');
    const nicLoading = document.getElementById('nic-loading');
    const nameInput = document.getElementById('recipient-name');
    const genderInput = document.getElementById('recipient-gender');
    
    if (!nicInput) return;
    
    const nic = nicInput.value.trim();
    if (!nic) {
        if (nicError) { nicError.textContent = 'Please enter a NIC number'; nicError.style.display = 'block'; }
        return;
    }
    
    if (nicLoading) nicLoading.style.display = 'block';
    if (nicError) nicError.style.display = 'none';
    
    fetch('/life-connect/hospital/searchPatientByNIC?nic=' + encodeURIComponent(nic))
        .then(r => r.json())
        .then(data => {
            if (nicLoading) nicLoading.style.display = 'none';
            if (data.success && data.patient) {
                if (nameInput) nameInput.value = data.patient.name || '';
                if (genderInput) genderInput.value = data.patient.gender || '';
                if (nicError) nicError.style.display = 'none';
            } else {
                if (nicError) { nicError.textContent = data.message || 'Patient not found'; nicError.style.display = 'block'; }
                if (nameInput) nameInput.value = '';
                if (genderInput) genderInput.value = '';
            }
        })
        .catch(e => {
            if (nicLoading) nicLoading.style.display = 'none';
            if (nicError) { nicError.textContent = 'Error fetching patient'; nicError.style.display = 'block'; }
        });
}

// SAVE/SUBMIT FUNCTIONS
function saveRequest() { alert('Save Request - feature available'); }
function saveRecipient() { alert('Save Recipient - feature available'); }
function saveProfile() { alert('Save Profile - feature available'); }
function saveStory() { alert('Save Story - feature available'); }
function saveLabReport() { alert('Save Lab Report - feature available'); }
function submitTestResult() { alert('Submit Test Result - feature available'); }
function downloadExport() { alert('Download Export - feature available'); }

// EDIT/DELETE FUNCTIONS
function editRequest(id) { alert('Edit Request #' + id); }
function deleteRequest(id) { if (confirm('Delete this organ request?')) alert('Deleted #' + id); }
function editRecipient(id) { alert('Edit Recipient #' + id); }
function deleteRecipient(id) { if (confirm('Delete this recipient?')) alert('Deleted #' + id); }
function editStory(id) { alert('Edit Story #' + id); }
function deleteStory(id) { if (confirm('Delete this story?')) alert('Deleted #' + id); }

// FILTER/SEARCH FUNCTIONS
function applyOrganFilters() { console.log('Filtering organs...'); }
function applyRecipientFilters() { console.log('Filtering recipients...'); }
function selectOrganType(id, name) { openRequestModal(); document.getElementById('organ-type').value = id; }
function toggleTestResultPatientType() { 
    const type = document.getElementById('tr-patient-type').value;
    const wrap = document.getElementById('tr-recipient-wrap');
    if (wrap) wrap.style.display = (type === 'RECIPIENT') ? 'block' : 'none';
}
function exportRecipients() { document.getElementById('export-modal').classList.add('show'); }
function toggleUserDropdown() { 
    const dd = document.getElementById('user-dropdown');
    if (dd) dd.classList.toggle('active');
}
function logout() { window.location.href = '/life-connect/logout'; }
function editProfile() { document.getElementById('profile-modal').classList.add('show'); }

// HELPER FUNCTIONS
function approveSupportRequest(id) { alert('Approve support request #' + id); }
function rejectSupportRequest(id) { alert('Reject support request #' + id); }

