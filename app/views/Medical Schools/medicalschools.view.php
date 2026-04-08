<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Session check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'MedicalSchool') {
    header('Location: /life-connect/login');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>LifeConnect - Medical School Portal</title>
<link rel="stylesheet" href="/life-connect/public/assets/css/medicalschools/medicalschools.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
<div class="header">
  <div class="header-content">
    <div>
      <h1><i class="fas fa-graduation-cap"></i> LifeConnect Medical School Portal</h1>
      <p>Full Body Donation Management & Coordination System</p>
    </div>
    <div style="display:flex;align-items:center;gap:1.5rem;">
      <div style="position:relative;">
        <div class="notification-bell" onclick="toggleNotifications()">
          <i class="fas fa-bell"></i>
          <span class="notification-badge">5</span>
        </div>
        <div id="notificationPanel" class="notification-panel">
          <div class="notification-header"><i class="fas fa-bell"></i> Notifications</div>
          <div class="notification-item unread">
            <div class="notification-title"><i class="fas fa-check-circle" style="color:var(--success-color);"></i> Request Accepted</div>
            <div class="notification-desc">Mr. Ranjan Perera's family accepted your donation request</div>
            <div class="notification-time">2 hours ago</div>
          </div>
          <div class="notification-item unread">
            <div class="notification-title"><i class="fas fa-heart" style="color:var(--danger-color);"></i> New Donation Available</div>
            <div class="notification-desc">Mrs. Kamala Silva - Full Body donation now available</div>
            <div class="notification-time">5 hours ago</div>
          </div>
          <div class="notification-item unread">
            <div class="notification-title"><i class="fas fa-file-alt" style="color:var(--primary-color);"></i> Documents Uploaded</div>
            <div class="notification-desc">Death certificate and GN approval received for DON2025-018</div>
            <div class="notification-time">1 day ago</div>
          </div>
        </div>
      </div>
      <div class="user-info">
        <div class="user-avatar">UMS</div>
        <div>
          <div style="font-weight:600;">University Medical School</div>
          <div class="school-badge"><i class="fas fa-microscope"></i> Anatomy Department</div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="container">
  <div class="sidebar">
    <div class="sidebar-header">
      <h3>Medical School Portal</h3>
      <p>Body Donation Management</p>
    </div>
    <div class="menu-section">
      <div class="menu-section-title">Overview</div>
      <a class="menu-item active" onclick="showSection('dashboard')">
        <div class="icon"><i class="fas fa-home"></i></div>Dashboard
        <span class="badge">3</span>
      </a>
    </div>
    <div class="menu-section">
      <div class="menu-section-title">Management</div>
      <a class="menu-item" onclick="showSection('requests')">
        <div class="icon"><i class="fas fa-paper-plane"></i></div>Requests Sent
        <span class="badge">5</span>
      </a>
      <a class="menu-item" onclick="showSection('certificates')">
        <div class="icon"><i class="fas fa-file-certificate"></i></div>Certificates & Docs
      </a>
      <a class="menu-item" onclick="showSection('usage')">
        <div class="icon"><i class="fas fa-clipboard-list"></i></div>Usage Logs
      </a>
    </div>
    <div class="menu-section">
      <div class="menu-section-title">Resources</div>
      <a class="menu-item" onclick="showSection('reports')">
        <div class="icon"><i class="fas fa-chart-bar"></i></div>Reports
      </a>
      <a class="menu-item">
        <div class="icon"><i class="fas fa-cog"></i></div>Settings
      </a>
    </div>
  </div>

  <div class="content-area">
    <div class="content-header">
      <h2 id="sectionTitle"><i class="fas fa-home"></i> Dashboard Overview</h2>
      <p id="sectionDesc">Available full body donations and request management</p>
    </div>
    <div class="content-body">
      <!-- Dashboard -->
      <div id="dashboard" class="section-content active">
        <div class="stats-grid">
          <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-heart" style="color:var(--available-color);"></i></div>
            <div class="stat-label">Available Bodies</div>
            <div class="stat-value">3</div>
          </div>
          <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-clock" style="color:var(--pending-color);"></i></div>
            <div class="stat-label">Pending Requests</div>
            <div class="stat-value">5</div>
          </div>
          <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-check-circle" style="color:var(--success-color);"></i></div>
            <div class="stat-label">Accepted</div>
            <div class="stat-value">2</div>
          </div>
          <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-microscope" style="color:var(--primary-color);"></i></div>
            <div class="stat-label">In Use</div>
            <div class="stat-value">8</div>
          </div>
        </div>

        <div class="section-card">
          <h3 class="section-title"><i class="fas fa-heart-pulse"></i> Available Full Body Donations</h3>
          
          <table class="donor-table">
            <thead>
              <tr>
                <th>Donor ID</th>
                <th>Donor Name</th>
                <th>Availability Date</th>
                <th>Donation Type</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <tr onclick="selectDonor('DON2025-031', event)">
                <td><strong>DON2025-031</strong></td>
                <td>Mr. Sunil Fernando</td>
                <td>20 Oct, 2025</td>
                <td><i class="fas fa-user"></i> Full Body</td>
                <td><span class="status-indicator status-available"><span class="status-dot dot-available"></span>Available</span></td>
                <td>
                  <button class="btn btn-primary btn-sm" onclick="event.stopPropagation();openRequestModal('DON2025-031')"><i class="fas fa-paper-plane"></i> Send Request</button>
                </td>
              </tr>
              <tr onclick="selectDonor('DON2025-032', event)">
                <td><strong>DON2025-032</strong></td>
                <td>Mrs. Kamala Silva</td>
                <td>21 Oct, 2025</td>
                <td><i class="fas fa-user"></i> Full Body</td>
                <td><span class="status-indicator status-available"><span class="status-dot dot-available"></span>Available</span></td>
                <td>
                  <button class="btn btn-primary btn-sm" onclick="event.stopPropagation();openRequestModal('DON2025-032')"><i class="fas fa-paper-plane"></i> Send Request</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Requests -->
      <div id="requests" class="section-content">
        <div class="section-card">
          <h3 class="section-title"><i class="fas fa-paper-plane"></i> Sent Requests</h3>
          <div class="filter-bar">
            <button class="filter-btn active">All (5)</button>
            <button class="filter-btn">Pending (2)</button>
            <button class="filter-btn">Accepted (2)</button>
          </div>

          <table class="donor-table">
            <thead>
              <tr>
                <th>Donor ID</th>
                <th>Donor Name</th>
                <th>Request Date</th>
                <th>Family Custodian</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <tr onclick="selectDonor('DON2025-028', event)" class="selected">
                <td><strong>DON2025-028</strong></td>
                <td>Mr. Ranjan Perera</td>
                <td>18 Oct, 2025</td>
                <td>Mrs. Nimesha Perera</td>
                <td><span class="status-indicator status-accepted"><span class="status-dot dot-accepted"></span>Accepted</span></td>
                <td>
                  <button class="btn btn-success btn-sm" onclick="event.stopPropagation();"><i class="fas fa-file-alt"></i> View Docs</button>
                </td>
              </tr>
              <tr onclick="selectDonor('DON2025-029', event)">
                <td><strong>DON2025-029</strong></td>
                <td>Mrs. Dilani Jayasuriya</td>
                <td>19 Oct, 2025</td>
                <td>Mr. Upul Jayasuriya</td>
                <td><span class="status-indicator status-pending"><span class="status-dot dot-pending"></span>Pending</span></td>
                <td>
                  <button class="btn btn-secondary btn-sm" onclick="event.stopPropagation();"><i class="fas fa-phone"></i> Follow Up</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Certificates -->
      <div id="certificates" class="section-content">
        <div class="section-card">
          <h3 class="section-title"><i class="fas fa-file-certificate"></i> Certificates & Documents</h3>
          
          <div style="background:linear-gradient(135deg,#f0f9ff,#e0f2fe);border:2px solid #3b82f6;border-radius:12px;padding:2rem;margin-bottom:2rem;">
            <div style="font-size:1.3rem;font-weight:700;color:#1e40af;margin-bottom:1rem;">Mr. Ranjan Perera</div>
            
            <h4 style="font-weight:700;margin-bottom:1rem;color:#1e40af;"><i class="fas fa-folder-open"></i> Legal Documents</h4>
            <div class="document-preview">
              <div class="document-icon"><i class="fas fa-file-pdf"></i></div>
              <div class="document-info">
                <div class="document-name">Death Certificate</div>
                <div class="document-size">Uploaded: 17 Oct 2025 • 245 KB</div>
              </div>
              <button class="btn btn-secondary btn-sm"><i class="fas fa-eye"></i> View</button>
            </div>

            <div class="document-preview">
              <div class="document-icon"><i class="fas fa-file-pdf"></i></div>
              <div class="document-info">
                <div class="document-name">GN Approval Letter</div>
                <div class="document-size">Uploaded: 17 Oct 2025 • 198 KB</div>
              </div>
              <button class="btn btn-secondary btn-sm"><i class="fas fa-eye"></i> View</button>
            </div>

            <div class="document-preview">
              <div class="document-icon"><i class="fas fa-file-pdf"></i></div>
              <div class="document-info">
                <div class="document-name">Embalming Certificate</div>
                <div class="document-size">Uploaded: 18 Oct 2025 • 156 KB</div>
              </div>
              <button class="btn btn-secondary btn-sm"><i class="fas fa-eye"></i> View</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Usage Logs -->
      <div id="usage" class="section-content">
        <div class="section-card">
          <h3 class="section-title"><i class="fas fa-clipboard-list"></i> Usage Logs</h3>
          
          <div style="background:var(--gray-bg-color);padding:1.5rem;border-radius:12px;margin-bottom:2rem;">
            <h4 style="font-weight:700;margin-bottom:1rem;color:var(--primary-color);"><i class="fas fa-plus-circle"></i> Log New Usage</h4>
            <div class="form-group">
              <label class="form-label">Select Donor</label>
              <select class="form-select">
                <option>Mr. Ranjan Perera (DON2025-028)</option>
                <option>Mr. Anil Rathnayake (DON2025-027)</option>
              </select>
            </div>
            <div class="form-group">
              <label class="form-label">Usage Type</label>
              <select class="form-select">
                <option><i class="fas fa-graduation-cap"></i> Education - Anatomy Lab</option>
                <option><i class="fas fa-flask"></i> Research - Medical Study</option>
                <option><i class="fas fa-user-md"></i> Training - Surgical Practice</option>
              </select>
            </div>
            <button class="btn btn-success"><i class="fas fa-check"></i> Add Usage Log</button>
          </div>

          <div class="usage-log-card">
            <div class="usage-log-header">
              <div>
                <div style="font-size:1.2rem;font-weight:700;color:#1e40af;">Mr. Ranjan Perera</div>
                <div style="color:var(--secondary-text-color);font-size:0.9rem;">DON2025-028</div>
              </div>
              <span class="usage-type"><i class="fas fa-graduation-cap"></i> Education</span>
            </div>
            <div class="usage-details">
              <div>
                <div class="info-label">Date</div>
                <div class="info-value">19 Oct, 2025</div>
              </div>
              <div>
                <div class="info-label">Duration</div>
                <div class="info-value">4 hours</div>
              </div>
            </div>
            <div>
              <div class="info-label">Purpose</div>
              <div class="info-value">Undergraduate Anatomy Dissection - Batch 2025</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Reports -->
      <div id="reports" class="section-content">
        <div class="section-card">
          <h3 class="section-title"><i class="fas fa-chart-bar"></i> Reports & Analytics</h3>
          <div class="stats-grid">
            <div class="stat-card">
              <div class="stat-icon"><i class="fas fa-heart" style="color:var(--primary-color);"></i></div>
              <div class="stat-label">Total Donations</div>
              <div class="stat-value">127</div>
            </div>
            <div class="stat-card">
              <div class="stat-icon"><i class="fas fa-user-graduate" style="color:var(--success-color);"></i></div>
              <div class="stat-label">Students Educated</div>
              <div class="stat-value">2,450</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Right Panel -->
  <div class="right-panel">
    <div class="right-panel-header">
      <h3><i class="fas fa-info-circle"></i> Donor Information</h3>
    </div>
    <div class="right-panel-content" id="rightPanelContent">
      <div class="empty-state">
        <div class="empty-state-icon"><i class="fas fa-user"></i></div>
        <p>Select a donor to view details</p>
      </div>
    </div>
  </div>
</div>

<!-- Request Modal -->
<div id="requestModal" class="modal">
  <div class="modal-content">
    <div class="modal-title"><i class="fas fa-paper-plane"></i> Send Donation Request</div>
    <div class="modal-text">Send a request to the family custodian to use this full body donation.</div>
    
    <div class="form-group">
      <label class="form-label">Purpose of Request</label>
      <select class="form-select">
        <option>🎓 Undergraduate Medical Education - Anatomy Lab</option>
        <option>🔬 Medical Research Study</option>
        <option>👨‍⚕️ Surgical Training & Skills Development</option>
      </select>
    </div>

    <div class="form-group">
      <label class="form-label">Additional Details</label>
      <textarea class="form-textarea" rows="4" placeholder="Provide details about how the donation will be used..."></textarea>
    </div>

    <div class="modal-actions">
      <button class="btn btn-secondary" onclick="closeModal('requestModal')">Cancel</button>
      <button class="btn btn-primary" onclick="sendRequest()"><i class="fas fa-paper-plane"></i> Send Request</button>
    </div>
  </div>
</div>

<script>
const donorData = {
  'DON2025-028': {
    name: 'Mr. Ranjan Perera',
    id: 'DON2025-028',
    bloodType: 'A+',
    age: 62,
    deathDate: '17 Oct, 2025',
    availabilityDate: '18 Oct, 2025',
    status: 'Accepted',
    custodian: 'Mrs. Nimesha Perera (Spouse)',
    phone: '+94 77 234 5678',
    email: 'nimesha.perera@email.com',
    nic: '589765432V',
    certificates: ['Death Certificate', 'GN Approval', 'Embalming Certificate'],
    funeral: {
      parlour: 'Peace Haven Funeral Home',
      date: '18 Oct, 2025 - 3:00 PM',
      location: 'Borella, Colombo 08'
    }
  },
  'DON2025-029': {
    name: 'Mrs. Dilani Jayasuriya',
    id: 'DON2025-029',
    bloodType: 'O-',
    age: 58,
    deathDate: '18 Oct, 2025',
    availabilityDate: '19 Oct, 2025',
    status: 'Pending',
    custodian: 'Mr. Upul Jayasuriya (Spouse)',
    phone: '+94 76 345 6789',
    email: 'upul.jayasuriya@email.com',
    certificates: []
  },
  'DON2025-031': {
    name: 'Mr. Sunil Fernando',
    id: 'DON2025-031',
    bloodType: 'B+',
    age: 65,
    deathDate: '19 Oct, 2025',
    availabilityDate: '20 Oct, 2025',
    status: 'Available',
    custodian: 'Mrs. Amali Fernando (Spouse)',
    phone: '+94 75 456 7890',
    email: 'amali.fernando@email.com',
    certificates: []
  },
  'DON2025-032': {
    name: 'Mrs. Kamala Silva',
    id: 'DON2025-032',
    bloodType: 'AB+',
    age: 60,
    deathDate: '20 Oct, 2025',
    availabilityDate: '21 Oct, 2025',
    status: 'Available',
    custodian: 'Mr. Upul Silva (Spouse)',
    phone: '+94 74 567 8901',
    email: 'upul.silva@email.com',
    certificates: []
  }
};

function showSection(section) {
  document.querySelectorAll('.section-content').forEach(s => s.classList.remove('active'));
  document.querySelectorAll('.menu-item').forEach(m => m.classList.remove('active'));
  
  const sections = {
    dashboard: { title: '<i class="fas fa-home"></i> Dashboard Overview', desc: 'Available full body donations and request management' },
    requests: { title: '<i class="fas fa-paper-plane"></i> Requests Sent', desc: 'Track status of donation requests sent to families' },
    certificates: { title: '<i class="fas fa-file-certificate"></i> Certificates & Documents', desc: 'View and manage legal documents and certificates' },
    usage: { title: '<i class="fas fa-clipboard-list"></i> Usage Logs', desc: 'Record and track body donation usage' },
    reports: { title: '<i class="fas fa-chart-bar"></i> Reports & Analytics', desc: 'Performance metrics and usage statistics' }
  };
  
  document.getElementById(section).classList.add('active');
  document.getElementById('sectionTitle').innerHTML = sections[section].title;
  document.getElementById('sectionDesc').textContent = sections[section].desc;
  
  event.target.closest('.menu-item').classList.add('active');
}

function selectDonor(donorId, event) {
  const donor = donorData[donorId];
  if (!donor) return;
  
  document.querySelectorAll('.donor-table tbody tr').forEach(tr => tr.classList.remove('selected'));
  event.currentTarget.classList.add('selected');
  
  let statusBadge = '';
  if (donor.status === 'Accepted') {
    statusBadge = '<span class="status-indicator status-accepted"><span class="status-dot dot-accepted"></span>Accepted</span>';
  } else if (donor.status === 'Pending') {
    statusBadge = '<span class="status-indicator status-pending"><span class="status-dot dot-pending"></span>Pending</span>';
  } else if (donor.status === 'Available') {
    statusBadge = '<span class="status-indicator status-available"><span class="status-dot dot-available"></span>Available</span>';
  }
  
  let certificateList = '';
  if (donor.certificates && donor.certificates.length > 0) {
    certificateList = '<div class="certificate-list">';
    donor.certificates.forEach(cert => {
      certificateList += `
        <div class="certificate-item">
          <div class="certificate-name">
            <span class="certificate-icon"><i class="fas fa-check-circle"></i></span>
            ${cert}
          </div>
        </div>
      `;
    });
    certificateList += '</div>';
  } else {
    certificateList = '<p style="color:var(--secondary-text-color);font-size:0.9rem;">No certificates uploaded yet</p>';
  }
  
  let funeralInfo = '';
  if (donor.funeral) {
    funeralInfo = `
      <div style="padding-top:1.5rem;border-top:2px solid rgba(0,91,170,0.1);margin-top:1.5rem;">
        <h4 style="font-weight:700;color:var(--primary-color);margin-bottom:1rem;"><i class="fas fa-church"></i> Funeral Details</h4>
        <div class="info-row">
          <div class="info-label">Funeral Parlour</div>
          <div class="info-value">${donor.funeral.parlour}</div>
        </div>
        <div class="info-row">
          <div class="info-label">Date & Time</div>
          <div class="info-value">${donor.funeral.date}</div>
        </div>
        <div class="info-row">
          <div class="info-label">Location</div>
          <div class="info-value">${donor.funeral.location}</div>
        </div>
      </div>
    `;
  }
  
  let actionButton = '';
  if (donor.status === 'Available') {
    actionButton = `<button class="btn btn-primary" style="width:100%;" onclick="openRequestModal('${donor.id}')"><i class="fas fa-paper-plane"></i> Send Request</button>`;
  } else if (donor.status === 'Accepted') {
    actionButton = `
      <button class="btn btn-success" style="width:100%;margin-bottom:0.5rem;"><i class="fas fa-clipboard-check"></i> Log Usage</button>
      <button class="btn btn-primary" style="width:100%;"><i class="fas fa-envelope"></i> Message Family</button>
    `;
  } else {
    actionButton = '<button class="btn btn-secondary" style="width:100%;"><i class="fas fa-clock"></i> Request Pending</button>';
  }
  
  document.getElementById('rightPanelContent').innerHTML = `
    <div style="text-align:center;padding-bottom:1.5rem;border-bottom:2px solid rgba(0,91,170,0.1);">
      <div style="width:80px;height:80px;background:linear-gradient(135deg,var(--primary-color),var(--secondary-color));color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:2rem;margin:0 auto 1rem;font-weight:700;">
        ${donor.name.split(' ')[1].charAt(0)}
      </div>
      <h3 style="font-weight:700;color:var(--primary-color);margin-bottom:0.5rem;">${donor.name}</h3>
      <div style="color:var(--secondary-text-color);margin-bottom:0.75rem;">${donor.id}</div>
      ${statusBadge}
    </div>
    
    <div style="padding-top:1.5rem;">
      <div class="info-row">
        <div class="info-label">Donation Type</div>
        <div class="info-value"><i class="fas fa-user"></i> Full Body</div>
      </div>
      <div class="info-row">
        <div class="info-label">Blood Type</div>
        <div class="info-value">${donor.bloodType}</div>
      </div>
      <div class="info-row">
        <div class="info-label">Age</div>
        <div class="info-value">${donor.age} years</div>
      </div>
      <div class="info-row">
        <div class="info-label">Date of Death</div>
        <div class="info-value">${donor.deathDate}</div>
      </div>
      <div class="info-row">
        <div class="info-label">Availability</div>
        <div class="info-value">${donor.availabilityDate}</div>
      </div>
    </div>
    
    <div style="padding-top:1.5rem;border-top:2px solid rgba(0,91,170,0.1);margin-top:1.5rem;">
      <h4 style="font-weight:700;color:var(--primary-color);margin-bottom:1rem;"><i class="fas fa-user-shield"></i> Family Custodian</h4>
      <div class="info-row">
        <div class="info-label">Name</div>
        <div class="info-value">${donor.custodian}</div>
      </div>
      <div class="info-row">
        <div class="info-label">Phone</div>
        <div class="info-value">${donor.phone}</div>
      </div>
      <div class="info-row">
        <div class="info-label">Email</div>
        <div class="info-value" style="font-size:0.85rem;word-break:break-all;">${donor.email}</div>
      </div>
      ${donor.nic ? `<div class="info-row">
        <div class="info-label">NIC</div>
        <div class="info-value">${donor.nic}</div>
      </div>` : ''}
    </div>
    
    ${funeralInfo}
    
    <div style="padding-top:1.5rem;border-top:2px solid rgba(0,91,170,0.1);margin-top:1.5rem;">
      <h4 style="font-weight:700;color:var(--primary-color);margin-bottom:1rem;"><i class="fas fa-file-certificate"></i> Certificates</h4>
      ${certificateList}
    </div>
    
    <div style="padding-top:1.5rem;margin-top:1.5rem;">
      ${actionButton}
    </div>
  `;
}

function openRequestModal(donorId) {
  document.getElementById('requestModal').classList.add('active');
}

function closeModal(modalId) {
  document.getElementById(modalId).classList.remove('active');
}

function sendRequest() {
  alert('✅ Request sent successfully!\n\nThe family custodian will be notified.');
  closeModal('requestModal');
}

function toggleNotifications() {
  const panel = document.getElementById('notificationPanel');
  panel.classList.toggle('active');
}

document.querySelectorAll('.modal').forEach(modal => {
  modal.addEventListener('click', function(e) {
    if (e.target === this) {
      this.classList.remove('active');
    }
  });
});

document.addEventListener('click', function(e) {
  const notificationBell = document.querySelector('.notification-bell');
  const notificationPanel = document.getElementById('notificationPanel');
  
  if (notificationBell && notificationPanel && !notificationBell.contains(e.target) && !notificationPanel.contains(e.target)) {
    notificationPanel.classList.remove('active');
  }
});

window.addEventListener('load', function() {
  const firstRow = document.querySelector('.donor-table tbody tr');
  if (firstRow) {
    firstRow.click();
  }
});
</script>
</body>
</html>
