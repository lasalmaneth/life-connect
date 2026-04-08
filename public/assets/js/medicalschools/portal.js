// LifeConnect Medical School Portal - Shared Application Logic
// This file contains all shared functions, modals, and data for the Medical School Portal

let currentAction = '';
let currentDonorId = '';

function viewDonorDetails(donorId, type) {
  const targetContent = document.getElementById('donorDetailsContent');
  if (targetContent) {
    targetContent.innerHTML = '<div style="padding: 2rem; text-align: center;"><i class="fas fa-spinner fa-spin fa-2x" style="color: var(--primary-color);"></i><p style="margin-top: 1rem; color: var(--text-secondary);">Loading records...</p></div>';
  }

  const modal = document.getElementById('donorDetailsModal');
  if (modal) {
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
  }

  // Fetch actual data from backend
  fetch(`/life-connect/medical-school/get-donor-details?id=${donorId}`)
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        data.data.type = type; // Inject the view context type
        renderDonorDetails(data.data);
      } else {
        if (targetContent) {
          targetContent.innerHTML = `<div style="padding: 2rem; text-align: center; color: var(--danger-color);"><i class="fas fa-exclamation-circle fa-2x"></i><p style="margin-top: 1rem;">${data.message || 'Error loading details'}</p></div>`;
        }
      }
    })
    .catch(error => {
      if (targetContent) {
        targetContent.innerHTML = '<div style="padding: 2rem; text-align: center; color: var(--danger-color);"><i class="fas fa-exclamation-triangle fa-2x"></i><p style="margin-top: 1rem;">Network error fetching details</p></div>';
      }
      console.error('Error fetching donor details:', error);
    });
}

function renderDonorDetails(donor) {
  if (!donor) return;

  // Format full name
  const fullName = `${donor.first_name} ${donor.last_name}`;

  // Basic date formatting helper
  const formatDate = (dateString) => {
    if (!dateString) return 'N/A';
    const d = new Date(dateString);
    if (isNaN(d.getTime())) return dateString;
    return d.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
  };

  // Status formatting
  const formattedStatus = donor.consent_status ?
    donor.consent_status.replace(/_/g, ' ').replace(/\w\S*/g, (w) => (w.replace(/^\w/, (c) => c.toUpperCase()))) :
    'Unknown';

  let statusBadge = getStatusBadge(formattedStatus);
  let documentList = ''; // Add later if files exist in db

  let content = `
    <div class="d-detail-header">
      <div class="d-detail-avatar">
        ${donor.last_name ? donor.last_name.charAt(0) : '?'}
      </div>
      <h3 class="d-detail-name">${fullName}</h3>
      <div class="d-detail-id">ID: ${donor.id}</div>
      ${statusBadge}
    </div>
    
    <div class="d-detail-section" style="border-top:none; padding-top:0; margin-top:0;">
  `;

  if (donor.type === 'pre-death') {
    content += `
      <div class="d-info">
        <div class="d-info__label">Blood Type</div>
        <div class="d-info__value">${donor.blood_type || 'N/A'}</div>
      </div>
      <div class="d-info">
        <div class="d-info__label">NIC</div>
        <div class="d-info__value">${donor.nic_number || 'N/A'}</div>
      </div>
      <div class="d-info">
        <div class="d-info__label">Submission Date</div>
        <div class="d-info__value">${formatDate(donor.consent_date)}</div>
      </div>
      <div class="d-info">
        <div class="d-info__label">Witness 1</div>
        <div class="d-info__value">${donor.witness1_name || 'Not Provided'}</div>
      </div>
      <div class="d-info">
        <div class="d-info__label">Witness 2</div>
        <div class="d-info__value">${donor.witness2_name || 'Not Provided'}</div>
      </div>
      <div class="d-info">
        <div class="d-info__label">Admin Verification</div>
        <div class="d-info__value">${donor.verification_status}</div>
      </div>
      
      <div class="d-info" style="grid-column: 1 / -1; margin-top: 1rem;">
        <div class="d-info__label">Contact Details</div>
        <div class="d-info__value"><i class="fas fa-phone"></i> ${donor.phone || 'N/A'} &nbsp; | &nbsp; <i class="fas fa-envelope"></i> ${donor.email || 'N/A'}</div>
      </div>
    `;
  } else if (donor.type === 'withdrawn') {
    content += `
      <div class="d-info">
        <div class="d-info__label">Original Consent</div>
        <div class="d-info__value">${formatDate(donor.consent_date)}</div>
      </div>
      <div class="d-info">
        <div class="d-info__label">Withdrawal Reason</div>
        <div class="d-info__value" style="grid-column: 1 / -1; margin-top: 0.5rem;">${donor.opt_out_reason || 'N/A'}</div>
      </div>
    `;
  } else if (donor.type === 'post-death' || donor.type === 'body-accepted') {
    content += `
      <div class="d-info">
        <div class="d-info__label">Blood Type</div>
        <div class="d-info__value">${donor.blood_type || 'N/A'}</div>
      </div>
      <div class="d-info">
        <div class="d-info__label">Consent Date</div>
        <div class="d-info__value">${formatDate(donor.consent_date)}</div>
      </div>
      <div class="d-info">
        <div class="d-info__label">Date of Death</div>
        <div class="d-info__value">${formatDate(donor.date_of_death) || 'Not recorded'}</div>
      </div>
    </div>
    
    <div class="d-detail-section">
      <h4 class="d-detail-section__title"><i class="fas fa-user-shield"></i> Registered Custodian / Next of Kin</h4>
      <div class="d-info">
        <div class="d-info__label">Name</div>
        <div class="d-info__value">${donor.nok_name || 'Not Registered'}</div>
      </div>
      <div class="d-info">
        <div class="d-info__label">Relationship</div>
        <div class="d-info__value">${donor.nok_relationship || 'N/A'}</div>
      </div>
      <div class="d-info">
        <div class="d-info__label">Phone</div>
        <div class="d-info__value">${donor.nok_phone || 'N/A'}</div>
      </div>
    `;
  } else if (donor.type === 'archived') {
    content += `
      <div class="d-info">
        <div class="d-info__label">Registration Type</div>
        <div class="d-info__value">${donor.pledge_type || 'N/A'}</div>
      </div>
      <div class="d-info">
        <div class="d-info__label">Last Status</div>
        <div class="d-info__value">${donor.consent_status || 'N/A'}</div>
      </div>
    `;
  }

  content += `</div>`;

  const targetContent = document.getElementById('donorDetailsContent');
  if (targetContent) targetContent.innerHTML = content;
}

function getStatusBadge(status) {
  const statusMap = {
    'Pending': 'd-status--warning',
    'Given': 'd-status--success',
    'Withdrawn': 'd-status--neutral',
    'In Use': 'd-status--success',
    'Disposed': 'd-status--neutral'
  };

  const statusClass = statusMap[status] || 'd-status--info';

  return `<span class="d-status ${statusClass}"><span class="d-status__dot"></span>${status}</span>`;
}

function approveConsent(donorId) {
  currentDonorId = donorId;
  currentAction = 'approve';
  const titleEl = document.getElementById('consentModalTitle');
  const textEl = document.getElementById('consentModalText');
  const btnEl = document.getElementById('consentConfirmBtn');

  if (titleEl) titleEl.innerHTML = '<i class="fas fa-check-circle"></i> Accept Consent';
  if (textEl) textEl.textContent = 'You are about to accept this pre-death consent form. The donor will receive an acknowledgment letter.';
  if (btnEl) {
    btnEl.innerHTML = 'Accept Consent';
    btnEl.className = 'btn btn-success';
  }

  const modal = document.getElementById('consentActionModal');
  if (modal) modal.classList.add('active');
}

function rejectConsent(donorId) {
  currentDonorId = donorId;
  currentAction = 'reject';
  const titleEl = document.getElementById('consentModalTitle');
  const textEl = document.getElementById('consentModalText');
  const btnEl = document.getElementById('consentConfirmBtn');

  if (titleEl) titleEl.innerHTML = '<i class="fas fa-times-circle"></i> Reject Consent';
  if (textEl) textEl.textContent = 'You are about to reject this pre-death consent form. Please provide a reason for rejection.';
  if (btnEl) {
    btnEl.innerHTML = 'Reject Consent';
    btnEl.className = 'btn btn-danger';
  }

  const modal = document.getElementById('consentActionModal');
  if (modal) modal.classList.add('active');
}

function confirmConsentAction() {
  if (currentAction === 'approve') {
    alert('✅ Consent Accepted!\n\nAcknowledgment letter has been generated and sent to the donor.');
  } else if (currentAction === 'reject') {
    alert('❌ Consent Rejected!\n\nRejection notification has been sent to the donor.');
  }
  closeModal('consentActionModal');
}

function acceptBody(donorId) {
  currentDonorId = donorId;
  const modal = document.getElementById('acceptBodyModal');
  if (modal) modal.classList.add('active');
}

function confirmAcceptBody() {
  const deliveryDate = document.getElementById('deliveryDate').value;

  if (!deliveryDate) {
    alert('Please select a delivery date');
    return;
  }

  alert('✅ Documents Accepted!\n\nThe custodian will receive:\n• Email notification with delivery details\n• Scheduled date and location\n• Contact information\n\nNOTE: Body Acceptance Certificate will be generated AFTER physical verification when body is delivered.');

  closeModal('acceptBodyModal');
}

function completePhysicalVerification(donorId) {
  currentDonorId = donorId;
  const modal = document.getElementById('physicalVerificationModal');
  if (modal) modal.classList.add('active');
}

function confirmPhysicalVerification() {
  const verificationDate = document.getElementById('verificationDate').value;
  const verifiedBy = document.getElementById('verifiedBy').value;

  if (!verificationDate || !verifiedBy) {
    alert('Please fill in verification date and verified by');
    return;
  }

  alert('✅ Physical Verification Complete!\n\nBody Acceptance Certificate Generated:\n• Certificate PDF created\n• Emailed to custodian\n• Available in Certificates section\n\nStatus updated to "Verified & Accepted"');

  closeModal('physicalVerificationModal');
}

function generateAppreciation(donorId) {
  currentDonorId = donorId;
  const modal = document.getElementById('appreciationModal');
  if (modal) modal.classList.add('active');
}

function confirmGenerateAppreciation() {
  const usageSummary = document.getElementById('usageSummary').value;
  const studentsEducated = document.getElementById('studentsEducated').value;

  if (!usageSummary || !studentsEducated) {
    alert('Please fill in usage summary and students educated');
    return;
  }

  alert('✅ Appreciation Certificate Generated!\n\nThe custodian will receive:\n• Email with Appreciation Certificate PDF\n• Certificate thanking them for their contribution\n\nDonor status updated to "Completed" and moved to archive.');

  closeModal('appreciationModal');
}

function approveWithdrawal(donorId) {
  currentDonorId = donorId;
  const modal = document.getElementById('approveWithdrawalModal');
  if (modal) modal.classList.add('active');
}

function confirmApproveWithdrawal() {
  const approvalDate = document.getElementById('withdrawalApprovalDate').value;

  if (!approvalDate) {
    alert('Please select approval date');
    return;
  }

  alert('✅ Withdrawal Approved!\n\nActions completed:\n• Withdrawal confirmation letter generated\n• Email sent to donor/custodian\n• Consent invalidated\n• Custodian portal updated (registration card removed)\n• Record archived with "Withdrawn" status');

  closeModal('approveWithdrawalModal');
}

function rejectWithdrawal(donorId) {
  currentDonorId = donorId;
  const modal = document.getElementById('rejectWithdrawalModal');
  if (modal) modal.classList.add('active');
}

function confirmRejectWithdrawal() {
  const rejectionReason = document.getElementById('withdrawalRejectionReason').value;

  if (!rejectionReason) {
    alert('Please provide rejection reason');
    return;
  }

  alert('❌ Withdrawal Request Rejected!\n\nActions completed:\n• Email sent to donor/custodian with reason\n• Consent remains valid (status: Accepted)\n• Donor can submit new request if needed');

  closeModal('rejectWithdrawalModal');
}

function requestResubmission(donorId) {
  currentDonorId = donorId;
  const modal = document.getElementById('resubmissionModal');
  if (modal) modal.classList.add('active');
}

function confirmResubmission() {
  alert('📧 Resubmission Request Sent!\n\nThe custodian has been notified with detailed instructions for document correction.');
  closeModal('resubmissionModal');
}

function viewLetter(type) {
  alert(`📄 Opening ${type} letter in new window...`);
}

function logUsage(donorId) {
  showSection('usage');
}

function closeModal(modalId) {
  const modal = document.getElementById(modalId);
  if (modal) modal.classList.remove('active');
}

function toggleNotifications() {
  const panel = document.getElementById('notificationPanel');
  if (panel) panel.classList.toggle('active');
}

// Initialize event listeners when DOM is loaded
document.addEventListener('DOMContentLoaded', function () {
  // Modal close on background click
  document.querySelectorAll('.modal').forEach(modal => {
    modal.addEventListener('click', function (e) {
      if (e.target === this) {
        this.classList.remove('active');
      }
    });
  });

  // Notification panel close on outside click
  document.addEventListener('click', function (e) {
    const notificationBell = document.querySelector('.notification-bell');
    const notificationPanel = document.getElementById('notificationPanel');

    if (notificationBell && notificationPanel && !notificationBell.contains(e.target) && !notificationPanel.contains(e.target)) {
      notificationPanel.classList.remove('active');
    }
  });
});

function toggleSidebar() {
  const sidebar = document.querySelector('.d-sidebar');
  const overlay = document.getElementById('sidebarOverlay');

  if (sidebar) {
    sidebar.classList.toggle('active');

    if (sidebar.classList.contains('active')) {
      document.body.style.overflow = 'hidden';
      if (overlay) overlay.classList.add('active');
    } else {
      document.body.style.overflow = 'auto';
      if (overlay) overlay.classList.remove('active');
    }
  }
}
