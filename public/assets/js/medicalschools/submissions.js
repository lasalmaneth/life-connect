let currentDonorId = null;

function toggleSubmissionDetails(donorId) {
    const detailRow = document.getElementById('detail-' + donorId);
    const icon = document.getElementById('icon-' + donorId);
    if (detailRow.style.display === 'none') {
        detailRow.style.display = 'table-row';
        icon.classList.remove('fa-chevron-down');
        icon.classList.add('fa-chevron-up');
    } else {
        detailRow.style.display = 'none';
        icon.classList.remove('fa-chevron-up');
        icon.classList.add('fa-chevron-down');
    }
}

function acceptBody(donorId) {
    currentDonorId = donorId;
    openModal('acceptBodyModal');
}

function requestResubmission(donorId) {
    currentDonorId = donorId;
    document.getElementById('reqDocsReasonCategory').value = '';
    document.getElementById('reqDocsExplanation').value = '';
    openModal('resubmissionModal');
}

function rejectSubmission(donorId) {
    currentDonorId = donorId;
    document.getElementById('rejectReasonText').value = '';
    openModal('rejectSubmissionModal');
}

function confirmAcceptBody() {
    if (!currentDonorId) return;
    const date = document.getElementById('deliveryDate').value;
    if (!date) {
        alert("Please select a delivery date");
        return;
    }
    
    // Call API
    submitAction('accept', { date: date });
}

function confirmResubmission() {
    if (!currentDonorId) return;
    const cat = document.getElementById('reqDocsReasonCategory').value;
    const expl = document.getElementById('reqDocsExplanation').value;
    if (!cat || !expl) {
        alert("Please provide both a category and an explanation.");
        return;
    }
    
    // Call API
    submitAction('need_docs', { category: cat, explanation: expl });
}

function confirmRejectSubmission() {
    if (!currentDonorId) return;
    const reason = document.getElementById('rejectReasonText').value;
    if (!reason.trim()) {
        alert("Rejection reason is mandatory.");
        return;
    }
    
    // Call API
    submitAction('reject', { reason: reason });
}

function submitAction(actionType, extraData = {}) {
    let bodyData = new URLSearchParams();
    bodyData.append('donor_id', currentDonorId);
    bodyData.append('action', actionType);
    
    for (const key in extraData) {
        bodyData.append(key, extraData[key]);
    }

    fetch(`${ROOT}/medical-school/update-submission`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: bodyData.toString()
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
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
