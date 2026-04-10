let currentDonorId = null;

function markValidConsent(donorId) {
    currentDonorId = donorId;
    openModal('markValidModal');
}

function flagConsent(donorId) {
    currentDonorId = donorId;
    document.getElementById('flagReasonCategory').value = '';
    document.getElementById('flagReasonText').value = '';
    openModal('flagRecordModal');
}

function confirmMarkValid() {
    if (!currentDonorId) return;
    
    // Simulate API call for marking as valid
    fetch(`${ROOT}/medical-school/update-consent`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `donor_id=${currentDonorId}&action=valid`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeModal('markValidModal');
            window.location.reload();
        } else {
            alert('Error: ' + (data.message || 'Could not process request'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred.');
    });
}

function confirmFlagRecord() {
    if (!currentDonorId) return;
    
    const category = document.getElementById('flagReasonCategory').value;
    const text = document.getElementById('flagReasonText').value;
    
    if (!category || !text.trim()) {
        alert("Please select a reason category and provide detailed text.");
        return;
    }
    
    fetch(`${ROOT}/medical-school/update-consent`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `donor_id=${currentDonorId}&action=flag&category=${encodeURIComponent(category)}&reason=${encodeURIComponent(text)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeModal('flagRecordModal');
            window.location.reload();
        } else {
            alert('Error: ' + (data.message || 'Could not process request'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred.');
    });
}

// Dummy standard functions for modals if not defined globally
window.openModal = window.openModal || function(id) {
    const modal = document.getElementById(id);
    if(modal) modal.classList.add('active');
};
window.closeModal = window.closeModal || function(id) {
    const modal = document.getElementById(id);
    if(modal) modal.classList.remove('active');
};
