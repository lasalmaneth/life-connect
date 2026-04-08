<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>LifeConnect - Donor Dashboard</title>
<!-- CSS -->
<link rel="stylesheet" href="/life-connect/public/assets/css/deceased donor/deceased-donor.css?v=2.0">
<!-- Font Awesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&display=swap" rel="stylesheet">
</head>
<body>
<div class="header">
  <div class="header-content">
    <div>
      <h1><i class="fas fa-heart"></i> LifeConnect Donor Portal</h1>
      <p id="headerSubtitle">Your journey of giving life - Manage your organ and body donation preferences</p>
    </div>
    <div class="user-info">
      <div class="user-avatar" id="userAvatar">NP</div>
      <div>
        <div style="font-weight:600;" id="userName">Mr. Nuwan Perera</div>
        <div class="status-badge" id="userStatus"><i class="fas fa-check"></i> Verified Donor</div>
      </div>
    </div>
  </div>
</div>

<!-- Demo Role Switcher (Remove in production) -->
<div class="role-switch">
  
  <button class="active" onclick="switchRole('donor')"><i class="fas fa-user"></i> Donor View (Pre-Death)</button>
  <button onclick="switchRole('custodian')"><i class="fas fa-users"></i> Custodian View (Post-Death)</button>
</div>

<div class="container">
  <div class="sidebar">
    <div class="sidebar-header">
      <h3>Donor Management</h3>
      <p id="sidebarDesc">Your donation dashboard</p>
    </div>
    <div class="menu-section">
      <div class="menu-section-title">Overview</div>
      <a class="menu-item active" onclick="showSection('dashboard')">
        <div class="icon"><i class="fas fa-home"></i></div>Dashboard Overview
      </a>
    </div>
    <div class="menu-section">
      <div class="menu-section-title">My Profile</div>
      <a class="menu-item" onclick="showSection('profile')">
        <div class="icon"><i class="fas fa-user"></i></div>Profile & Registration
      </a>
      <a class="menu-item" onclick="showSection('donations')">
        <div class="icon"><i class="fas fa-heart"></i></div>My Donations
      </a>
      <a class="menu-item" onclick="showSection('family')">
        <div class="icon"><i class="fas fa-users"></i></div>Family Custodians
      </a>
    </div>
    <div class="menu-section" id="custodianMenu" style="display:none;">
      <div class="menu-section-title">Coordination</div>
      <a class="menu-item" onclick="showSection('death-confirmation')">
        <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>Death Confirmation
      </a>
      <a class="menu-item" onclick="showSection('documents')">
        <div class="icon"><i class="fas fa-file-upload"></i></div>Document Upload
      </a>
      <a class="menu-item" onclick="showSection('hospitals')">
        <div class="icon"><i class="fas fa-hospital"></i></div>Hospital Coordination
      </a>
      <a class="menu-item" onclick="showSection('medical-schools')">
        <div class="icon"><i class="fas fa-graduation-cap"></i></div>Medical School Coordination
      </a>
      <a class="menu-item" onclick="showSection('tribute')">
        <div class="icon"><i class="fas fa-medal"></i></div>Tribute & Certificate
      </a>
    </div>
    <div class="menu-section">
      <div class="menu-section-title">Documents</div>
      <a class="menu-item" onclick="showSection('legal')">
        <div class="icon"><i class="fas fa-gavel"></i></div>Legal & Consent
      </a>
      <a class="menu-item" onclick="showSection('card')">
        <div class="icon"><i class="fas fa-id-card"></i></div>Digital Donor Card
      </a>
    </div>
    <div class="menu-section">
      <div class="menu-section-title">Support</div>
      <a class="menu-item" onclick="showSection('activity')">
        <div class="icon"><i class="fas fa-clipboard-list"></i></div>Activity Log
      </a>
      <a class="menu-item" onclick="showSection('settings')">
        <div class="icon"><i class="fas fa-cog"></i></div>Settings
      </a>
      <a class="menu-item">
        <div class="icon"><i class="fas fa-question-circle"></i></div>Help & Support
      </a>
    </div>
  </div>

  <div class="content-area">
    <div class="content-header">
      <h2 id="contentTitle"><i class="fas fa-home"></i> Dashboard Overview</h2>
      <p id="sectionDesc">Welcome back! Manage your donation preferences and profile information.</p>
    </div>
    <div class="content-body" id="contentBody">
      
      <!-- DONOR DASHBOARD (Pre-Death) -->
      <div id="dashboard" class="section-content">
        <div class="welcome-banner">
          <div class="welcome-title"><i class="fas fa-hand-wave"></i> Welcome back, Nuwan!</div>
          <div class="welcome-text">
            Thank you for your commitment to saving lives through organ and body donation. Your registration is <strong>verified and active</strong>. You can update your preferences, manage your family custodians, and download your donor card anytime.
          </div>
        </div>

        <div class="stats-grid">
          <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-heart"></i></div>
            <div class="stat-label">Organs Registered</div>
            <div class="stat-value" id="dashboardOrgansCount">6 Organs</div>
          </div>
          <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-user"></i></div>
            <div class="stat-label">Full Body Donation</div>
            <div class="stat-value" id="dashboardFullBody"><i class="fas fa-check"></i> Yes</div>
          </div>
          <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-calendar"></i></div>
            <div class="stat-label">Registration Date</div>
            <div class="stat-value">Aug 12, 2025</div>
          </div>
          <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-users"></i></div>
            <div class="stat-label">Family Custodians</div>
            <div class="stat-value" id="dashboardCustodiansCount">2 Assigned</div>
          </div>
        </div>

        <div class="section-card">
          <h3 class="section-title">👤 Profile Summary</h3>
          <div class="info-grid">
            <div class="info-item">
              <div class="info-label">Full Name</div>
              <div class="info-value">Mr. Nuwan Perera</div>
            </div>
            <div class="info-item">
              <div class="info-label">Donor ID</div>
              <div class="info-value">DOD2025-014</div>
            </div>
            <div class="info-item">
              <div class="info-label">Blood Type</div>
              <div class="info-value">O+ (Universal Donor)</div>
            </div>
            <div class="info-item">
              <div class="info-label">NIC Number</div>
              <div class="info-value">872345678V</div>
            </div>
            <div class="info-item">
              <div class="info-label">Contact Number</div>
              <div class="info-value">+94 77 123 4567</div>
            </div>
            <div class="info-item">
              <div class="info-label">Status</div>
              <div class="info-value" style="color:var(--success-color);">✓ Verified Donor</div>
            </div>
          </div>
          <div class="btn-group">
            <button class="btn btn-primary" onclick="showSection('profile')">Edit Profile</button>
            <button class="btn btn-secondary" onclick="downloadPDF('Profile Summary')">Download Profile (PDF)</button>
          </div>
        </div>

        <div class="section-card">
          <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;">
            <h3 class="section-title" style="margin:0;"><i class="fas fa-heart-pulse" style="color:var(--primary-color);"></i> Donation Summary (Sri Lankan Law Compliant)</h3>
          </div>
          
          <div class="organ-summary" id="dashboardOrganSummary">
            <!-- Dynamically populated from consent data -->
          </div>
          
          <div class="note-box" id="dashboardFullBodyNote" style="display:block;">
            <strong><i class="fas fa-check-circle"></i> Full Body Donation Registered</strong>
            <p>You have also chosen to donate your full body for medical education and research. This will be coordinated with registered medical schools after organ donation, as per Human Tissue Transplantation Act No. 48 of 1987.</p>
          </div>
          
          <div class="btn-group" style="margin-top:1.5rem;">
            <button class="btn btn-primary" onclick="showSection('donations')"><i class="fas fa-file-contract"></i> View Consent Details</button>
            <button class="btn btn-secondary" onclick="downloadPDF('Donation Guidelines')"><i class="fas fa-download"></i> View Guidelines</button>
          </div>
        </div>

        <div class="section-card">
          <h3 class="section-title"><i class="fas fa-users"></i> Family Custodians (Minimum 2 Required)</h3>
          <div class="custodian-grid" id="dashboardCustodianCards">
            <!-- Dynamically populated from custodian data -->
          </div>
        </div>
      </div>

      <!-- CUSTODIAN: DASHBOARD OVERVIEW -->
      <div id="custodian-dashboard" class="section-content" style="display:none;">
        <div class="welcome-banner">
          <div class="welcome-title"><i class="fas fa-users"></i> Custodian Dashboard</div>
          <div class="welcome-text">
            You are managing the donation coordination for <strong>Mr. Nuwan Perera</strong>. 
            Complete the workflow steps below to ensure proper organ and body donation coordination.
          </div>
        </div>

        <div class="stats-grid">
          <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-exclamation-triangle"></i></div>
            <div class="stat-label">Death Status</div>
            <div class="stat-value">Pending Confirmation</div>
          </div>
          <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-file-upload"></i></div>
            <div class="stat-label">Documents</div>
            <div class="stat-value">2 Pending</div>
          </div>
          <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-hospital"></i></div>
            <div class="stat-label">Hospitals</div>
            <div class="stat-value">5 Assigned</div>
          </div>
          <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-graduation-cap"></i></div>
            <div class="stat-label">Medical Schools</div>
            <div class="stat-value">1 Accepted</div>
          </div>
        </div>

        <div class="section-card">
          <h3 class="section-title"><i class="fas fa-clipboard-list"></i> Coordination Workflow Status</h3>
          <div class="checklist">
            <div class="checklist-item">
              <div class="checklist-number">1</div>
              <div class="checklist-content">
                <div class="checklist-title">Confirm Death Status</div>
                <div class="checklist-desc">Mark donor as deceased to activate coordination</div>
              </div>
              <button class="btn btn-warning btn-sm" onclick="showSection('death-confirmation')"><i class="fas fa-exclamation-triangle"></i> Confirm Death</button>
            </div>
            <div class="checklist-item">
              <div class="checklist-number">2</div>
              <div class="checklist-content">
                <div class="checklist-title">Upload Legal Documents</div>
                <div class="checklist-desc">Submit death certificate and temporary GN letter</div>
              </div>
              <button class="btn btn-primary btn-sm" onclick="showSection('documents')"><i class="fas fa-file-upload"></i> Upload Documents</button>
            </div>
            <div class="checklist-item">
              <div class="checklist-number">3</div>
              <div class="checklist-content">
                <div class="checklist-title">Coordinate with Hospitals</div>
                <div class="checklist-desc">Contact assigned hospitals for organ collection</div>
              </div>
              <button class="btn btn-primary btn-sm" onclick="showSection('hospitals')"><i class="fas fa-hospital"></i> Hospital Coordination</button>
            </div>
            <div class="checklist-item">
              <div class="checklist-number">4</div>
              <div class="checklist-content">
                <div class="checklist-title">Manage Full Body Donation</div>
                <div class="checklist-desc">Coordinate with medical school for body collection</div>
              </div>
              <button class="btn btn-primary btn-sm" onclick="showSection('medical-schools')"><i class="fas fa-graduation-cap"></i> Medical School Coordination</button>
            </div>
            <div class="checklist-item">
              <div class="checklist-number">5</div>
              <div class="checklist-content">
                <div class="checklist-title">Download Tribute Certificate</div>
                <div class="checklist-desc">Receive official recognition of donation</div>
              </div>
              <button class="btn btn-secondary btn-sm" onclick="showSection('tribute')"><i class="fas fa-medal"></i> View Certificates</button>
            </div>
          </div>
        </div>

        <div class="section-card">
          <h3 class="section-title"><i class="fas fa-user"></i> Donor Profile (Read-Only)</h3>
          <div class="info-grid">
            <div class="info-item">
              <div class="info-label">Donor Name</div>
              <div class="info-value">Mr. Nuwan Perera</div>
            </div>
            <div class="info-item">
              <div class="info-label">Donor ID</div>
              <div class="info-value">DOD2025-014</div>
            </div>
            <div class="info-item">
              <div class="info-label">Blood Type</div>
              <div class="info-value">O+ (Universal Donor)</div>
            </div>
            <div class="info-item">
              <div class="info-label">NIC Number</div>
              <div class="info-value">872345678V</div>
            </div>
            <div class="info-item">
              <div class="info-label">Contact Number</div>
              <div class="info-value">+94 77 123 4567</div>
            </div>
            <div class="info-item">
              <div class="info-label">Registration Date</div>
              <div class="info-value">Aug 12, 2025</div>
            </div>
          </div>
        </div>

        <div class="section-card">
          <h3 class="section-title"><i class="fas fa-heart"></i> Donation Preferences</h3>
          <div class="organ-summary">
            <div class="organ-badge">
              <span>Eyes</span> <span class="organ-status">✓</span>
            </div>
            <div class="organ-badge">
              <span>Kidneys</span> <span class="organ-status">✓</span>
            </div>
            <div class="organ-badge">
              <span>Heart</span> <span class="organ-status">✓</span>
            </div>
            <div class="organ-badge">
              <span>Lungs</span> <span class="organ-status">✓</span>
            </div>
            <div class="organ-badge">
              <span>Liver</span> <span class="organ-status">✓</span>
            </div>
          </div>
          <div class="note-box">
            <strong><i class="fas fa-check"></i> Full Body Donation Registered</strong>
            <p>Donor has also chosen to donate their full body for medical education and research. This will be coordinated with registered medical schools after organ donation.</p>
          </div>
        </div>

        <div class="section-card">
          <h3 class="section-title"><i class="fas fa-users"></i> Family Custodians</h3>
          <div class="custodian-grid">
            <div class="family-card">
              <div style="padding: 1.25rem 1.5rem; display: flex; gap: 1.25rem; align-items: center; position: relative; z-index: 2;">
                <div class="family-avatar">AP</div>
                <div class="family-info">
                  <div class="family-name">Mrs. Amali Perera</div>
                  <div class="family-relation">Relationship: <strong>Spouse</strong></div>
                  <div class="family-contact">
                    <div><i class="fas fa-id-card"></i> NIC: 897654321V</div>
                    <div><i class="fas fa-phone"></i> +94 77 234 5678</div>
                    <div><i class="fas fa-envelope"></i> amali.perera@email.com</div>
                  </div>
                  <div style="margin-top:0.75rem;">
                    <span class="status-badge" style="background:var(--success-color);"><i class="fas fa-crown"></i> Primary Custodian</span>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="family-card">
              <div style="padding: 1.25rem 1.5rem; display: flex; gap: 1.25rem; align-items: center; position: relative; z-index: 2;">
                <div class="family-avatar">KP</div>
                <div class="family-info">
                  <div class="family-name">Mr. Kamal Perera</div>
                  <div class="family-relation">Relationship: <strong>Brother</strong></div>
                  <div class="family-contact">
                    <div><i class="fas fa-id-card"></i> NIC: 856789123V</div>
                    <div><i class="fas fa-phone"></i> +94 77 345 6789</div>
                    <div><i class="fas fa-envelope"></i> kamal.perera@email.com</div>
                  </div>
                  <div style="margin-top:0.75rem;">
                    <span class="status-badge" style="background:var(--primary-color);"><i class="fas fa-shield-alt"></i> Secondary Custodian</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- CUSTODIAN: DEATH CONFIRMATION -->
      <div id="death-confirmation" class="section-content" style="display:none;">
        <div class="alert-banner warning">
          <div class="alert-icon"><i class="fas fa-exclamation-triangle"></i></div>
          <div class="alert-content">
            <div class="alert-title">Action Required: Confirm Deceased Status</div>
            <div>To activate the organ and body donation coordination workflow, you must confirm the donor's passing. This will unlock hospital and medical school coordination panels.</div>
          </div>
        </div>

        <div class="section-card">
          <h3 class="section-title"><i class="fas fa-clipboard-list"></i> Coordination Workflow Checklist</h3>
          <div class="checklist">
            <div class="checklist-item">
              <div class="checklist-number">1</div>
              <div class="checklist-content">
                <div class="checklist-title">Confirm Death Status</div>
                <div class="checklist-desc">Mark donor as deceased to activate coordination</div>
              </div>
            </div>
            <div class="checklist-item">
              <div class="checklist-number">2</div>
              <div class="checklist-content">
                <div class="checklist-title">Upload Legal Documents</div>
                <div class="checklist-desc">Submit death certificate and temporary GN letter</div>
              </div>
            </div>
            <div class="checklist-item">
              <div class="checklist-number">3</div>
              <div class="checklist-content">
                <div class="checklist-title">Coordinate with Hospitals</div>
                <div class="checklist-desc">Contact assigned hospitals for organ collection</div>
              </div>
            </div>
            <div class="checklist-item">
              <div class="checklist-number">4</div>
              <div class="checklist-content">
                <div class="checklist-title">Manage Full Body Donation</div>
                <div class="checklist-desc">Coordinate with medical school for body collection</div>
              </div>
            </div>
            <div class="checklist-item">
              <div class="checklist-number">5</div>
              <div class="checklist-content">
                <div class="checklist-title">Download Tribute Certificate</div>
                <div class="checklist-desc">Receive official recognition of donation</div>
              </div>
            </div>
          </div>
        </div>

        <div class="section-card">
          <h3 class="section-title"><i class="fas fa-exclamation-triangle"></i> Confirm Deceased Status</h3>
          <p style="color:var(--secondary-text-color);margin-bottom:1.5rem;">By confirming the donor's passing, you will activate the coordination workflow and lock donor editing capabilities. This action cannot be undone.</p>
          
          <div class="form-group">
            <label class="form-label">Date of Death <span style="color: red;">*</span></label>
            <input type="date" class="form-input" id="deathDate" required max="<?php echo date('Y-m-d'); ?>">
          </div>
          
          <div class="form-group">
            <label class="form-label">Time of Death <span style="color: red;">*</span></label>
            <input type="time" class="form-input" id="deathTime" required>
          </div>
          
          <div class="form-group">
            <label class="form-label">Place of Death <span style="color: red;">*</span></label>
            <select class="form-select" id="deathPlace" required>
              <option value="">Select place of death</option>
              <option>Hospital</option>
              <option>Home</option>
              <option>Other Location</option>
            </select>
          </div>
          
          <div class="form-group">
            <label class="form-label">Additional Notes</label>
            <textarea class="form-textarea" placeholder="Any relevant details about the circumstances..." maxlength="500"></textarea>
          </div>

          <div class="warning-box">
            <strong><i class="fas fa-info-circle"></i> Important Notice:</strong>
            <p>Confirming death will immediately notify assigned hospitals and medical schools. Ensure all information is accurate before proceeding.</p>
          </div>

          <div class="btn-group" style="margin-top:1.5rem;">
            <button class="btn btn-warning" onclick="confirmDeath()"><i class="fas fa-exclamation-triangle"></i> Confirm Death & Activate Workflow</button>
            <button class="btn btn-secondary" onclick="showSection('dashboard')"><i class="fas fa-times"></i> Cancel</button>
          </div>
        </div>
      </div>

      <!-- CUSTODIAN: DOCUMENT UPLOAD -->
      <div id="documents" class="section-content" style="display:none;">
        <div class="alert-banner info">
          <div class="alert-icon"><i class="fas fa-info-circle"></i></div>
          <div class="alert-content">
            <div class="alert-title">Document Verification Required</div>
            <div>Please upload the death certificate and temporary GN letter to proceed with organ and body donation coordination.</div>
          </div>
        </div>

        <div class="section-card">
          <h3 class="section-title"><i class="fas fa-file-upload"></i> Required Legal Documents</h3>
          <p style="color:var(--secondary-text-color);margin-bottom:1.5rem;">Upload verified documents to validate the deceased status and enable coordination.</p>

          <table class="data-table">
            <thead>
              <tr>
                <th>Document Type</th>
                <th>Status</th>
                <th>Upload Date</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><strong>Death Certificate</strong></td>
                <td><span class="status-pill pending"><i class="fas fa-clock"></i> Pending Upload</span></td>
                <td>-</td>
                <td><button class="btn btn-primary btn-sm" onclick="openModal('uploadModal')"><i class="fas fa-upload"></i> Upload</button></td>
              </tr>
              <tr>
                <td><strong>Temporary GN Letter</strong></td>
                <td><span class="status-pill pending"><i class="fas fa-clock"></i> Pending Upload</span></td>
                <td>-</td>
                <td><button class="btn btn-primary btn-sm" onclick="openModal('uploadModal')"><i class="fas fa-upload"></i> Upload</button></td>
              </tr>
              <tr>
                <td><strong>Custodian ID Proof</strong></td>
                <td><span class="status-pill completed"><i class="fas fa-check"></i> Verified</span></td>
                <td>Aug 15, 2025</td>
                <td>
                  <button class="btn btn-secondary btn-sm" onclick="viewDocument('Custodian ID')"><i class="fas fa-eye"></i> View</button>
                  <button class="btn btn-secondary btn-sm" onclick="downloadPDF('Custodian ID')"><i class="fas fa-download"></i> Download</button>
                </td>
              </tr>
            </tbody>
          </table>

          <div class="note-box" style="margin-top:1.5rem;">
            <strong><i class="fas fa-info-circle"></i> Document Requirements:</strong>
            <p>• Death Certificate: Official certificate from hospital or registrar<br>
            • Temporary GN Letter: Letter from Grama Niladhari confirming death<br>
            • Format: PDF, JPG, or PNG (Max 5MB per file)</p>
          </div>
        </div>

        <div class="section-card">
          <h3 class="section-title"><i class="fas fa-file-upload"></i> Upload New Document</h3>
          <div class="upload-area" onclick="openModal('uploadModal')">
            <div class="upload-icon"><i class="fas fa-cloud-upload-alt"></i></div>
            <div style="font-weight:700;font-size:1.1rem;margin-bottom:0.5rem;">Click to Upload Document</div>
            <div style="color:var(--secondary-text-color);">Supported formats: PDF, JPG, PNG (Max 5MB)</div>
          </div>
        </div>
      </div>

      <!-- CUSTODIAN: HOSPITAL COORDINATION -->
      <div id="hospitals" class="section-content" style="display:none;">
        <div class="alert-banner success">
          <div class="alert-icon"><i class="fas fa-check-circle"></i></div>
          <div class="alert-content">
            <div class="alert-title">Death Confirmed - Coordination Active</div>
            <div>Hospitals have been automatically notified. Contact details and guidelines are provided below.</div>
          </div>
        </div>

        <div class="section-card">
          <h3 class="section-title"><i class="fas fa-hospital"></i> Hospital Coordination Table</h3>
          <p style="color:var(--secondary-text-color);margin-bottom:1.5rem;">Coordinate with assigned hospitals for organ collection. Update status as coordination progresses.</p>

          <table class="data-table">
            <thead>
              <tr>
                <th>Organ</th>
                <th>Assigned Hospital</th>
                <th>Contact Person</th>
                <th>Phone</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><i class="fas fa-eye"></i> Eyes</td>
                <td>National Eye Hospital</td>
                <td>Dr. Perera</td>
                <td>+94 11 269 4444</td>
                <td><span class="status-pill completed"><i class="fas fa-check"></i> Collected</span></td>
                <td><button class="btn btn-secondary btn-sm" onclick="viewHospitalDetails('National Eye Hospital')"><i class="fas fa-eye"></i> View Details</button></td>
              </tr>
              <tr>
                <td><i class="fas fa-kidneys"></i> Kidneys</td>
                <td>National Hospital of Sri Lanka</td>
                <td>Dr. Fernando</td>
                <td>+94 11 269 1111</td>
                <td><span class="status-pill inprogress"><i class="fas fa-sync"></i> In Progress</span></td>
                <td>
                  <button class="btn btn-primary btn-sm" onclick="callHospital('National Hospital of Sri Lanka', 'Dr. Fernando')"><i class="fas fa-phone"></i> Call</button>
                  <button class="btn btn-secondary btn-sm" onclick="updateHospitalStatus('Kidneys', 'National Hospital')"><i class="fas fa-edit"></i> Update Status</button>
                </td>
              </tr>
              <tr>
                <td><i class="fas fa-heart"></i> Heart</td>
                <td>Colombo General Hospital</td>
                <td>Dr. Silva</td>
                <td>+94 11 244 1111</td>
                <td><span class="status-pill pending"><i class="fas fa-clock"></i> Pending Contact</span></td>
                <td>
                  <button class="btn btn-primary btn-sm" onclick="callHospital('Colombo General Hospital', 'Dr. Silva')"><i class="fas fa-phone"></i> Call</button>
                  <button class="btn btn-secondary btn-sm" onclick="updateHospitalStatus('Heart', 'Colombo General Hospital')"><i class="fas fa-edit"></i> Update Status</button>
                </td>
              </tr>
              <tr>
                <td><i class="fas fa-lungs"></i> Lungs</td>
                <td>Karapitiya Teaching Hospital</td>
                <td>Dr. Jayasinghe</td>
                <td>+94 91 223 4529</td>
                <td><span class="status-pill pending"><i class="fas fa-clock"></i> Pending Contact</span></td>
                <td>
                  <button class="btn btn-primary btn-sm" onclick="callHospital('Karapitiya Teaching Hospital', 'Dr. Jayasinghe')"><i class="fas fa-phone"></i> Call</button>
                  <button class="btn btn-secondary btn-sm" onclick="updateHospitalStatus('Lungs', 'Karapitiya Hospital')"><i class="fas fa-edit"></i> Update Status</button>
                </td>
              </tr>
              <tr>
                <td><i class="fas fa-liver"></i> Liver</td>
                <td>Colombo South Teaching Hospital</td>
                <td>Dr. Wijesinghe</td>
                <td>+94 11 261 1111</td>
                <td><span class="status-pill pending"><i class="fas fa-clock"></i> Pending Contact</span></td>
                <td>
                  <button class="btn btn-primary btn-sm" onclick="callHospital('Colombo South Teaching Hospital', 'Dr. Wijesinghe')"><i class="fas fa-phone"></i> Call</button>
                  <button class="btn btn-secondary btn-sm" onclick="updateHospitalStatus('Liver', 'Colombo South Hospital')"><i class="fas fa-edit"></i> Update Status</button>
                </td>
              </tr>
            </tbody>
          </table>

          <div class="btn-group" style="margin-top:1.5rem;">
            <button class="btn btn-primary" onclick="downloadPDF('Hospital Coordination Guide')"><i class="fas fa-download"></i> Download Coordination Guide (PDF)</button>
            <button class="btn btn-secondary" onclick="sendReminders()"><i class="fas fa-bell"></i> Send Reminder to All Hospitals</button>
          </div>
        </div>

        <div class="section-card">
          <h3 class="section-title"><i class="fas fa-clipboard-list"></i> Coordination Guidelines</h3>
          <div style="background:var(--gray-bg-color);padding:1.5rem;border-radius:10px;">
            <div style="margin-bottom:1rem;">
              <strong style="color:var(--primary-color);"><i class="fas fa-phone"></i> Step 1: Initial Contact</strong>
              <p style="font-size:0.9rem;color:var(--secondary-text-color);margin-top:0.25rem;">Call the hospital contact person and inform them of the donor's passing. Provide donor ID: DOD2025-014</p>
            </div>
            <div style="margin-bottom:1rem;">
              <strong style="color:var(--primary-color);"><i class="fas fa-calendar"></i> Step 2: Arrange Collection</strong>
              <p style="font-size:0.9rem;color:var(--secondary-text-color);margin-top:0.25rem;">Coordinate timing and location for organ collection. Hospitals will dispatch retrieval teams.</p>
            </div>
            <div style="margin-bottom:1rem;">
              <strong style="color:var(--primary-color);"><i class="fas fa-file-alt"></i> Step 3: Document Handover</strong>
              <p style="font-size:0.9rem;color:var(--secondary-text-color);margin-top:0.25rem;">Provide copies of death certificate and consent forms to retrieval teams.</p>
            </div>
            <div>
              <strong style="color:var(--primary-color);"><i class="fas fa-check"></i> Step 4: Confirm Completion</strong>
              <p style="font-size:0.9rem;color:var(--secondary-text-color);margin-top:0.25rem;">Update status in the dashboard once organs are successfully collected.</p>
            </div>
          </div>
        </div>
      </div>

      <!-- CUSTODIAN: MEDICAL SCHOOL COORDINATION -->
      <div id="medical-schools" class="section-content" style="display:none;">
        <div class="alert-banner info">
          <div class="alert-icon"><i class="fas fa-graduation-cap"></i></div>
          <div class="alert-content">
            <div class="alert-title">Full Body Donation - Medical School Coordination</div>
            <div>Medical schools have been notified. Coordinate body transfer and funeral arrangements as detailed below.</div>
          </div>
        </div>

        <div class="section-card">
          <h3 class="section-title"><i class="fas fa-graduation-cap"></i> Medical School Response Table</h3>
          <p style="color:var(--secondary-text-color);margin-bottom:1.5rem;">Medical schools will indicate their need for body donation. Coordinate with the accepting institution.</p>

          <table class="data-table">
            <thead>
              <tr>
                <th>Medical School</th>
                <th>Location</th>
                <th>Contact Person</th>
                <th>Phone</th>
                <th>Need Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><strong>University of Peradeniya</strong></td>
                <td>Peradeniya</td>
                <td>Dr. Bandara</td>
                <td>+94 81 238 9001</td>
                <td><span class="status-pill completed"><i class="fas fa-check"></i> Accepted</span></td>
                <td>
                  <button class="btn btn-primary btn-sm" onclick="coordinateTransfer('University of Peradeniya')"><i class="fas fa-handshake"></i> Coordinate Transfer</button>
                  <button class="btn btn-secondary btn-sm" onclick="viewSchoolDetails('Peradeniya')"><i class="fas fa-eye"></i> View Details</button>
                </td>
              </tr>
              <tr>
                <td><strong>University of Colombo</strong></td>
                <td>Colombo 08</td>
                <td>Dr. Fonseka</td>
                <td>+94 11 269 5301</td>
                <td><span class="status-pill rejected"><i class="fas fa-times"></i> No Current Need</span></td>
                <td><button class="btn btn-secondary btn-sm" onclick="viewSchoolDetails('Colombo')"><i class="fas fa-eye"></i> View Response</button></td>
              </tr>
              <tr>
                <td><strong>University of Kelaniya</strong></td>
                <td>Ragama</td>
                <td>Dr. Gunasekara</td>
                <td>+94 11 296 1328</td>
                <td><span class="status-pill rejected"><i class="fas fa-times"></i> No Current Need</span></td>
                <td><button class="btn btn-secondary btn-sm" onclick="viewSchoolDetails('Kelaniya')"><i class="fas fa-eye"></i> View Response</button></td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="section-card" id="funeral-coordination">
          <h3 class="section-title"><i class="fas fa-church"></i> Funeral Parlor Coordination</h3>
          <p style="color:var(--secondary-text-color);margin-bottom:1.5rem;">After organ collection, coordinate body preservation and transfer to the accepting medical school.</p>

          <div class="note-box">
            <strong><i class="fas fa-check"></i> Medical School Accepted:</strong>
            <p>University of Peradeniya has accepted the body donation. Follow the steps below to complete the transfer process.</p>
          </div>

          <div class="checklist" style="margin-top:1.5rem;">
            <div class="checklist-item completed">
              <div class="checklist-number">1</div>
              <div class="checklist-content">
                <div class="checklist-title">Download Funeral Parlor Guidelines</div>
                <div class="checklist-desc">Review requirements for body preservation and handling</div>
              </div>
              <button class="btn btn-secondary btn-sm" onclick="downloadPDF('Funeral Parlor Guidelines')"><i class="fas fa-download"></i> Download PDF</button>
            </div>
            <div class="checklist-item">
              <div class="checklist-number">2</div>
              <div class="checklist-content">
                <div class="checklist-title">Arrange Funeral Parlor Services</div>
                <div class="checklist-desc">Contact a licensed funeral parlor for embalming and preservation</div>
              </div>
              <button class="btn btn-primary btn-sm" onclick="openModal('parlorModal')"><i class="fas fa-plus"></i> Enter Parlor Details</button>
            </div>
            <div class="checklist-item">
              <div class="checklist-number">3</div>
              <div class="checklist-content">
                <div class="checklist-title">Upload Preservation Certificate</div>
                <div class="checklist-desc">Submit certificate from funeral parlor confirming proper preservation</div>
              </div>
              <button class="btn btn-primary btn-sm" onclick="openModal('uploadModal')"><i class="fas fa-upload"></i> Upload Certificate</button>
            </div>
            <div class="checklist-item">
              <div class="checklist-number">4</div>
              <div class="checklist-content">
                <div class="checklist-title">Enter Funeral Details</div>
                <div class="checklist-desc">Provide date, time, and location for final rites</div>
              </div>
              <button class="btn btn-primary btn-sm" onclick="openModal('funeralModal')"><i class="fas fa-calendar"></i> Enter Details</button>
            </div>
            <div class="checklist-item">
              <div class="checklist-number">5</div>
              <div class="checklist-content">
                <div class="checklist-title">Medical School Collects Body</div>
                <div class="checklist-desc">University will coordinate collection after funeral</div>
              </div>
            </div>
          </div>

          <div class="warning-box" style="margin-top:1.5rem;">
            <strong><i class="fas fa-clock"></i> Important Timeline:</strong>
            <p>Body must be preserved within 24 hours of death. Coordinate with funeral parlor immediately. Medical school will collect the body within 48 hours after funeral completion.</p>
          </div>
        </div>
      </div>

      <!-- CUSTODIAN: TRIBUTE & CERTIFICATE -->
      <div id="tribute" class="section-content" style="display:none;">
        <div class="alert-banner success">
          <div class="alert-icon"><i class="fas fa-medal"></i></div>
          <div class="alert-content">
            <div class="alert-title">Donation Process Complete</div>
            <div>All coordination steps have been completed. Download tribute certificates to honor the donor's legacy.</div>
          </div>
        </div>

        <div class="section-card">
          <h3 class="section-title"><i class="fas fa-trophy"></i> Tribute Certificates</h3>
          <p style="color:var(--secondary-text-color);margin-bottom:1.5rem;">Official certificates recognizing the donor's life-saving contribution.</p>

          <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:1.5rem;">
            <div style="background:linear-gradient(135deg,#f0f9ff,#e0f2fe);border:2px solid var(--primary-color);border-radius:12px;padding:1.5rem;text-align:center;">
              <div style="font-size:3rem;margin-bottom:1rem;"><i class="fas fa-medal"></i></div>
              <div style="font-weight:700;font-size:1.2rem;color:var(--primary-color);margin-bottom:0.5rem;">Certificate of Organ Donation</div>
              <div class="btn-group" style="margin-top:2rem;">
                <button class="btn btn-primary" onclick="downloadPDF('Organ Donation Certificate')"><i class="fas fa-download"></i> Download Certificate</button>
              </div>
            </div>

            <div style="background:linear-gradient(135deg,#f0fdf4,#dcfce7);border:2px solid var(--success-color);border-radius:12px;padding:1.5rem;text-align:center;">
              <div style="font-size:3rem;margin-bottom:1rem;"><i class="fas fa-graduation-cap"></i></div>
              <div style="font-weight:700;font-size:1.2rem;color:var(--success-color);margin-bottom:0.5rem;">Body Donation Tribute</div>
              <div style="font-size:0.9rem;color:var(--secondary-text-color);margin-bottom:1rem;">Honoring contribution to medical education</div>
              <button class="btn btn-success" onclick="downloadPDF('Body Donation Tribute Certificate')"><i class="fas fa-download"></i> Download Certificate</button>
            </div>

            <div style="background:linear-gradient(135deg,#fef3c7,#fde68a);border:2px solid var(--warning-color);border-radius:12px;padding:1.5rem;text-align:center;">
              <div style="font-size:3rem;margin-bottom:1rem;"><i class="fas fa-star"></i></div>
              <div style="font-weight:700;font-size:1.2rem;color:#92400e;margin-bottom:0.5rem;">LifeConnect Hero Award</div>
              <div style="font-size:0.9rem;color:var(--secondary-text-color);margin-bottom:1rem;">Commemorative certificate of appreciation</div>
              <button class="btn btn-warning" onclick="downloadPDF('LifeConnect Hero Award')"><i class="fas fa-download"></i> Download Certificate</button>
            </div>
          </div>
        </div>

        <div class="section-card">
          <h3 class="section-title"><i class="fas fa-chart-line"></i> Donation Impact Log</h3>
          <p style="color:var(--secondary-text-color);margin-bottom:1.5rem;">Track how the donor's contribution is being used for transplantation, research, and education.</p>

          <div class="timeline-item">
            <div>
              <div class="timeline-date">October 20, 2025</div>
              <div class="timeline-action">Eyes Transplanted Successfully</div>
              <div class="timeline-desc">Corneas restored sight for 2 patients at National Eye Hospital</div>
            </div>
          </div>

          <div class="timeline-item">
            <div>
              <div class="timeline-date">October 21, 2025</div>
              <div class="timeline-action">Kidney Transplant Completed</div>
              <div class="timeline-desc">Both kidneys transplanted to patients with renal failure - National Hospital</div>
            </div>
          </div>

          <div class="timeline-item">
            <div>
              <div class="timeline-date">October 22, 2025</div>
              <div class="timeline-action">Heart Used for Cardiac Research</div>
              <div class="timeline-desc">Heart tissue contributed to cardiovascular research program</div>
            </div>
          </div>

          <div class="timeline-item">
            <div>
              <div class="timeline-date">October 23, 2025</div>
              <div class="timeline-action">Body Received by Medical School</div>
              <div class="timeline-desc">University of Peradeniya received body for anatomy education (Class of 2026)</div>
            </div>
          </div>

          <div class="timeline-item">
            <div>
              <div class="timeline-date">Ongoing</div>
              <div class="timeline-action">Medical Education in Progress</div>
              <div class="timeline-desc">Body being used to train 120 medical students in human anatomy</div>
            </div>
          </div>
        </div>

        <div class="section-card">
          <h3 class="section-title"><i class="fas fa-share-alt"></i> Share the Legacy</h3>
          <div class="btn-group">
            <button class="btn btn-primary" onclick="downloadPDF('All Certificates Bundle')"><i class="fas fa-envelope"></i> Email Certificates to Family</button>
            <button class="btn btn-secondary" onclick="alert('🖨️ Printing all certificates...')"><i class="fas fa-print"></i> Print All Certificates</button>
            <button class="btn btn-secondary" onclick="downloadPDF('Complete Donation Report')"><i class="fas fa-download"></i> Download Complete Report (PDF)</button>
            <button class="btn btn-secondary" onclick="alert('🌐 Opening share dialog...')"><i class="fas fa-share"></i> Share Impact Story</button>
          </div>
        </div>
      </div>

      <!-- Profile Section -->
      <div id="profile" class="section-content" style="display:none;">
        <div class="section-card">
          <h3 class="section-title">👤 Profile & Registration (Read-Only Fields)</h3>
          <p style="color:var(--secondary-text-color);margin-bottom:1.5rem;">Your registered donor profile. Only contact information can be updated for security reasons.</p>
          
          <!-- Read-Only Information -->
          <div style="background:linear-gradient(135deg,#f0f9ff,#e0f2fe);padding:1.5rem;border-radius:10px;margin-bottom:2rem;">
            <h4 style="font-weight:700;margin-bottom:1rem;color:var(--primary-color);">Locked Information (Cannot be changed)</h4>
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1rem;">
              <div>
                <div style="font-size:0.85rem;color:var(--secondary-text-color);margin-bottom:0.25rem;">Full Name</div>
                <div style="font-weight:700;color:var(--primary-color);">Mr. Nuwan Perera</div>
              </div>
              <div>
                <div style="font-size:0.85rem;color:var(--secondary-text-color);margin-bottom:0.25rem;">NIC Number</div>
                <div style="font-weight:700;">872345678V</div>
              </div>
              <div>
                <div style="font-size:0.85rem;color:var(--secondary-text-color);margin-bottom:0.25rem;">Date of Birth</div>
                <div style="font-weight:700;">May 15, 1987</div>
              </div>
              <div>
                <div style="font-size:0.85rem;color:var(--secondary-text-color);margin-bottom:0.25rem;">Gender</div>
                <div style="font-weight:700;">Male</div>
              </div>
              <div>
                <div style="font-size:0.85rem;color:var(--secondary-text-color);margin-bottom:0.25rem;">Blood Group</div>
                <div style="font-weight:700;color:var(--success-color);">O+ ✓ Verified</div>
              </div>
              <div>
                <div style="font-size:0.85rem;color:var(--secondary-text-color);margin-bottom:0.25rem;">Donor ID</div>
                <div style="font-weight:700;">DOD2025-014</div>
              </div>
            </div>
          </div>

          <!-- Editable Contact Information -->
          <h4 style="font-weight:700;margin-bottom:1rem;color:var(--primary-color);">Editable Contact Information</h4>
          <div class="form-group">
            <label class="form-label">Contact Number <span style="color: red;">*</span></label>
            <input type="tel" class="form-input" id="profilePhone" value="+94 77 123 4567" required pattern="^(\+94|0)?[0-9\s]{9,12}$" title="Enter valid Sri Lankan phone number">
          </div>
          <div class="form-group">
            <label class="form-label">Email Address <span style="color: red;">*</span></label>
            <input type="email" class="form-input" id="profileEmail" value="nuwan.perera@email.com" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" title="Enter a valid email address">
          </div>
          <div class="form-group">
            <label class="form-label">Residential Address <span style="color: red;">*</span></label>
            <textarea class="form-textarea" id="profileAddress" rows="2" required minlength="10" maxlength="300">45, Galle Road, Colombo 03</textarea>
          </div>

          <div class="btn-group">
            <button class="btn btn-primary" onclick="updateContactInfo()">Update Contact Information</button>
            <button class="btn btn-secondary" onclick="showSection('dashboard')">Cancel</button>
          </div>

          <div class="note-box" style="margin-top:1.5rem;">
            <strong>Security Notice:</strong>
            <p>Name, NIC, Date of Birth, Gender, and Blood Group are locked for security and legal compliance. If you need to update these fields, please contact LifeConnect support with proper documentation.</p>
          </div>
        </div>
      </div>

      <!-- My Donations Section - READ-ONLY VIEW -->
      <div id="donations" class="section-content" style="display:none;">
        <div class="alert-banner warning">
          <div class="alert-icon">👁️</div>
          <div class="alert-content">
            <div class="alert-title">Read-Only View - Current Consent Status</div>
            <div>This section displays your current consent. To update your organ donation preferences, please go to <strong>"Legal & Consent"</strong> section.</div>
          </div>
        </div>

        <div class="section-card">
          <h3 class="section-title">📋 Current Consent Status</h3>
          
          <!-- Current Consent Display -->
          <div class="consent-form">
            <div class="consent-header">
              <div class="consent-title">Organ & Tissue Donation Consent</div>
              <div class="consent-subtitle">Human Tissue Transplantation Act No. 48 of 1987</div>
            </div>

            <!-- Donor Information (Read-only) -->
            <div class="consent-section">
              <h4>Donor Information</h4>
              <div style="background:var(--gray-bg-color);padding:1.5rem;border-radius:10px;">
                <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:1rem;">
                  <div>
                    <div style="font-size:0.85rem;color:var(--secondary-text-color);">Full Name:</div>
                    <div style="font-weight:700;">Mr. Nuwan Perera</div>
                  </div>
                  <div>
                    <div style="font-size:0.85rem;color:var(--secondary-text-color);">NIC Number:</div>
                    <div style="font-weight:700;">872345678V</div>
                  </div>
                  <div>
                    <div style="font-size:0.85rem;color:var(--secondary-text-color);">Date of Birth:</div>
                    <div style="font-weight:700;">May 15, 1987</div>
                  </div>
                  <div>
                    <div style="font-size:0.85rem;color:var(--secondary-text-color);">Blood Group:</div>
                    <div style="font-weight:700;">O+</div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Current Organ Selection -->
            <div class="consent-section">
              <h4 style="color:var(--primary-text-color);font-weight:700;margin-bottom:1rem;display:flex;align-items:center;gap:0.5rem;">
                <i class="fas fa-list-check" style="color:var(--primary-color);"></i> Currently Selected Organs & Tissues
              </h4>
              <div id="currentOrgans" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:1rem;">
                <!-- Dynamically populated with selected organs only -->
              </div>
            </div>

            <!-- Full Body Donation Status -->
            <div class="consent-section">
              <h4>Full Body Donation Status</h4>
              <div style="background:linear-gradient(135deg,#f0fdf4,#dcfce7);border:2px solid var(--success-color);border-radius:10px;padding:1.5rem;">
                <div style="display:flex;align-items:center;gap:1rem;">
                  <input type="checkbox" checked disabled style="width:22px;height:22px;">
                  <div>
                    <div style="font-weight:700;font-size:1.05rem;">✅ Full Body Donation Enabled</div>
                    <div style="font-size:0.9rem;color:var(--secondary-text-color);margin-top:0.5rem;">After organ donation, body will be donated to registered medical schools for education and research.</div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Consent Declaration -->
            <div class="consent-section">
              <h4>Digital Consent Confirmation</h4>
              <div class="consent-declaration">
                <label>
                  <input type="checkbox" checked disabled style="width:22px;height:22px;">
                  <div>
                    <div style="font-weight:700;font-size:1.05rem;margin-bottom:0.5rem;">I hereby give my voluntary consent</div>
                    <div>
                      I understand and voluntarily agree to donate the selected organs, tissues, and/or my full body for transplantation, medical education, and research following my death (cardiac death) as per the Human Tissue Transplantation Act No. 48 of 1987 of Sri Lanka. I understand this consent is legally binding and can be updated by me at any time before my death.
                    </div>
                  </div>
                </label>
              </div>
            </div>

            <div style="margin-top:1.5rem;padding:1rem;background:var(--gray-bg-color);border-radius:8px;font-size:0.9rem;color:var(--secondary-text-color);">
              <strong>Consent Last Updated:</strong> August 18, 2025 at 2:30 PM
            </div>
          </div>

        </div>

        <!-- Consent History -->
        <div class="section-card">
          <h3 class="section-title">📋 Consent Update History</h3>
          <div class="consent-history">
            <div class="consent-history-item">
              <div class="consent-history-icon" style="background:var(--success-color);">✓</div>
              <div class="consent-history-content">
                <div class="consent-history-title">Added Heart, Lungs, Liver</div>
                <div class="consent-history-date">August 18, 2025 at 2:30 PM</div>
                <div class="consent-history-desc">Expanded organ donation to include heart, lungs, and liver. Full body donation maintained.</div>
              </div>
              <button class="btn btn-secondary btn-sm" onclick="viewConsentVersion('Aug 18 2025')">View</button>
            </div>
            <div class="consent-history-item">
              <div class="consent-history-icon" style="background:var(--primary-color);">✓</div>
              <div class="consent-history-content">
                <div class="consent-history-title">Full Body Donation Added</div>
                <div class="consent-history-date">August 15, 2025 at 11:15 AM</div>
                <div class="consent-history-desc">Enabled full body donation for medical education and research.</div>
              </div>
              <button class="btn btn-secondary btn-sm" onclick="viewConsentVersion('Aug 15 2025')">View</button>
            </div>
            <div class="consent-history-item">
              <div class="consent-history-icon" style="background:#6b7280;">1</div>
              <div class="consent-history-content">
                <div class="consent-history-title">Initial Consent Registration</div>
                <div class="consent-history-date">August 12, 2025 at 10:00 AM</div>
                <div class="consent-history-desc">Initial registration with eyes and kidneys donation.</div>
              </div>
              <button class="btn btn-secondary btn-sm" onclick="viewConsentVersion('Initial')">View</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Family Custodians Section -->
      <div id="family" class="section-content" style="display:none;">
        <div class="section-card">
          <h3 class="section-title"><i class="fas fa-users"></i> Family Custodians Management</h3>
          <p style="color:var(--secondary-text-color);margin-bottom:1.5rem;">Manage your family custodians who will coordinate organ and body donation after your passing. Minimum 2 custodians required.</p>
          
          <div class="custodian-grid" id="custodianCardsContainer">
            <!-- Custodian cards will be dynamically rendered here -->
          </div>

          <div class="btn-group">
            <button class="btn btn-primary" onclick="addCustodian()"><i class="fas fa-plus"></i> Add New Custodian</button>
          </div>

          <div style="margin-top:2rem;">
            <h4 style="font-weight:700;margin-bottom:1rem;color:var(--primary-color);">Custodian Responsibilities</h4>
            <div style="background:var(--gray-bg-color);padding:1.5rem;border-radius:10px;">
              <div style="display:flex;gap:1rem;margin-bottom:1rem;">
                <div style="width:40px;height:40px;background:var(--primary-color);color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;"><i class="fas fa-file-upload"></i></div>
                <div style="flex:1;">
                  <strong><i class="fas fa-exclamation-triangle"></i> Confirm Death & Upload Certificate</strong>
                  <p style="font-size:0.9rem;color:var(--secondary-text-color);margin-top:0.25rem;">Verify deceased status and provide death certificate or temporary GN letter</p>
                </div>
              </div>
              <div style="display:flex;gap:1rem;margin-bottom:1rem;">
                <div style="width:40px;height:40px;background:var(--primary-color);color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;"><i class="fas fa-hospital"></i></div>
                <div style="flex:1;">
                  <strong><i class="fas fa-phone"></i> Coordinate with Hospitals</strong>
                  <p style="font-size:0.9rem;color:var(--secondary-text-color);margin-top:0.25rem;">Contact assigned hospitals for organ collection and handover</p>
                </div>
              </div>
              <div style="display:flex;gap:1rem;margin-bottom:1rem;">
                <div style="width:40px;height:40px;background:var(--primary-color);color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;"><i class="fas fa-graduation-cap"></i></div>
                <div style="flex:1;">
                  <strong><i class="fas fa-user"></i> Manage Body Donation</strong>
                  <p style="font-size:0.9rem;color:var(--secondary-text-color);margin-top:0.25rem;">Coordinate with medical school for body collection and provide funeral details</p>
                </div>
              </div>
              <div style="display:flex;gap:1rem;">
                <div style="width:40px;height:40px;background:var(--primary-color);color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;"><i class="fas fa-medal"></i></div>
                <div style="flex:1;">
                  <strong><i class="fas fa-download"></i> Receive Tribute Certificate</strong>
                  <p style="font-size:0.9rem;color:var(--secondary-text-color);margin-top:0.25rem;">Download and print donor appreciation certificate</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Legal Section -->
      <div id="legal" class="section-content" style="display:none;">
        <div class="alert-banner warning">
          <div class="alert-icon">📝</div>
          <div class="alert-content">
            <div class="alert-title">Your Legal Consent Form</div>
            <div>This displays your current consent. Changes made here or in "My Donations" are automatically synchronized.</div>
          </div>
        </div>

        <!-- Consent Summary Card -->
        <div class="section-card">
          <h3 class="section-title"><i class="fas fa-clipboard-check"></i> Consent Summary</h3>
          
          <div style="background:#e0f2fe;padding:1.5rem;border-radius:10px;border-left:4px solid var(--primary-color);margin-bottom:1.5rem;">
            <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:1.5rem;">
              <div>
                <div style="font-size:0.85rem;color:var(--secondary-text-color);margin-bottom:0.25rem;">Donor Name:</div>
                <div style="font-weight:700;font-size:1.1rem;">Mr. Nuwan Perera</div>
              </div>
              <div>
                <div style="font-size:0.85rem;color:var(--secondary-text-color);margin-bottom:0.25rem;">NIC Number:</div>
                <div style="font-weight:700;font-size:1.1rem;">872345678V</div>
              </div>
              <div>
                <div style="font-size:0.85rem;color:var(--secondary-text-color);margin-bottom:0.25rem;">Consent Type:</div>
                <div style="font-weight:700;font-size:1.1rem;">Brain Death / Cardiac Death</div>
              </div>
              <div>
                <div style="font-size:0.85rem;color:var(--secondary-text-color);margin-bottom:0.25rem;">Full Body Donation:</div>
                <div style="font-weight:700;font-size:1.1rem;color:var(--success-color);">✓ Yes</div>
              </div>
            </div>
            
            <div style="margin-top:1rem;">
              <div style="font-size:0.85rem;color:var(--secondary-text-color);margin-bottom:0.5rem;">Selected Organs:</div>
              <div style="font-weight:700;font-size:1.05rem;" id="consentSummaryOrgans">Eyes, Kidneys, Heart, Lungs, Liver, Pancreas</div>
            </div>
            
            <div style="margin-top:1rem;">
              <div style="font-size:0.85rem;color:var(--secondary-text-color);margin-bottom:0.25rem;">Last Updated:</div>
              <div style="font-weight:700;">August 18, 2025 at 2:30 PM</div>
            </div>
          </div>

          <div class="btn-group">
            <button class="btn btn-primary" onclick="openConsentEditModal()"><i class="fas fa-edit"></i> Edit Consent</button>
            <button class="btn btn-success" onclick="downloadPDF('Current Consent Form')"><i class="fas fa-download"></i> Download PDF</button>
            <button class="btn btn-secondary" onclick="viewConsentDetails()"><i class="fas fa-eye"></i> View Update Log</button>
          </div>

          <div style="background:#d1f4e0;padding:1rem;border-radius:8px;margin-top:1.5rem;border:1px solid var(--success-color);">
            <strong>Auto-Sync:</strong>
            <p style="margin:0.5rem 0 0 0;font-size:0.9rem;">Any changes to your consent will automatically update your donation preferences in the "My Donations" section.</p>
          </div>
        </div>

        <!-- Official Legal Consent Document Display -->
        <div class="section-card">
          <h3 class="section-title"><i class="fas fa-file-contract"></i> Official Legal Consent Document</h3>
          
          <div style="border:3px solid var(--primary-color);border-radius:12px;padding:2rem;background:#fff;">
            <div style="text-align:center;margin-bottom:2rem;">
              <h2 style="color:var(--primary-color);font-size:1.8rem;margin-bottom:0.5rem;">ORGAN & TISSUE DONATION CONSENT FORM</h2>
              <p style="color:var(--secondary-text-color);font-size:0.95rem;">Human Tissue Transplantation Act No. 48 of 1987</p>
            </div>

            <!-- Donor Information -->
            <div style="background:#f8f9fa;padding:1.5rem;border-radius:8px;margin-bottom:1.5rem;">
              <h4 style="font-weight:700;margin-bottom:1rem;color:var(--primary-text-color);">DONOR INFORMATION</h4>
              <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:1rem;">
                <div>
                  <div style="font-weight:600;">Full Name:</div>
                  <div>Mr. Nuwan Perera</div>
                </div>
                <div>
                  <div style="font-weight:600;">NIC:</div>
                  <div>872345678V</div>
                </div>
                <div>
                  <div style="font-weight:600;">DOB:</div>
                  <div>May 15, 1987</div>
                </div>
                <div>
                  <div style="font-weight:600;">Blood:</div>
                  <div>O+</div>
                </div>
              </div>
            </div>

            <!-- Organs & Tissues Consented -->
            <div style="margin-bottom:1.5rem;" id="officialConsentOrgans">
              <h4 style="font-weight:700;margin-bottom:1rem;color:var(--primary-text-color);">ORGANS & TISSUES CONSENTED</h4>
              <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:0.75rem;">
                <div style="display:flex;align-items:center;gap:0.5rem;padding:0.75rem;background:#f0fdf4;border:2px solid var(--success-color);border-radius:6px;">
                  <i class="fas fa-check-square" style="color:var(--success-color);"></i>
                  <span>Eyes</span>
                </div>
                <div style="display:flex;align-items:center;gap:0.5rem;padding:0.75rem;background:#f0fdf4;border:2px solid var(--success-color);border-radius:6px;">
                  <i class="fas fa-check-square" style="color:var(--success-color);"></i>
                  <span>Kidneys</span>
                </div>
                <div style="display:flex;align-items:center;gap:0.5rem;padding:0.75rem;background:#f0fdf4;border:2px solid var(--success-color);border-radius:6px;">
                  <i class="fas fa-check-square" style="color:var(--success-color);"></i>
                  <span>Heart</span>
                </div>
                <div style="display:flex;align-items:center;gap:0.5rem;padding:0.75rem;background:#f0fdf4;border:2px solid var(--success-color);border-radius:6px;">
                  <i class="fas fa-check-square" style="color:var(--success-color);"></i>
                  <span>Lungs</span>
                </div>
                <div style="display:flex;align-items:center;gap:0.5rem;padding:0.75rem;background:#f0fdf4;border:2px solid var(--success-color);border-radius:6px;">
                  <i class="fas fa-check-square" style="color:var(--success-color);"></i>
                  <span>Liver</span>
                </div>
                <div style="display:flex;align-items:center;gap:0.5rem;padding:0.75rem;background:#f0fdf4;border:2px solid var(--success-color);border-radius:6px;">
                  <i class="fas fa-check-square" style="color:var(--success-color);"></i>
                  <span>Pancreas</span>
                </div>
              </div>
            </div>

            <!-- Full Body Donation -->
            <div style="background:#d1f4e0;padding:1.25rem;border-radius:8px;border:2px solid var(--success-color);margin-bottom:1.5rem;">
              <div style="display:flex;align-items:center;gap:0.75rem;">
                <i class="fas fa-check-square" style="color:var(--success-color);font-size:1.3rem;"></i>
                <span style="font-weight:700;font-size:1.1rem;">FULL BODY DONATION: Yes</span>
              </div>
            </div>

            <!-- Legal Declaration -->
            <div>
              <h4 style="font-weight:700;margin-bottom:1rem;color:var(--primary-text-color);">LEGAL DECLARATION</h4>
              <p style="line-height:1.6;color:var(--secondary-text-color);">
                I understand this consent is given under the Human Tissue Transplantation Act No. 48 of 1987 of Sri Lanka. I have the right to modify or withdraw this consent at any time before my death.
              </p>
            </div>
          </div>
        </div>

        <!-- Update History -->
        <div class="section-card">
          <h3 class="section-title"><i class="fas fa-history"></i> Update History</h3>
          
          <table class="data-table">
            <thead>
              <tr>
                <th>Date</th>
                <th>Action</th>
                <th>Organs Changed</th>
              </tr>
            </thead>
            <tbody id="consentHistoryTable">
              <tr>
                <td>Aug 18, 2025</td>
                <td><span style="color:var(--success-color);font-weight:600;"><i class="fas fa-plus-circle"></i> Added</span></td>
                <td>Heart, Lungs, Liver</td>
              </tr>
              <tr>
                <td>Aug 15, 2025</td>
                <td><span style="color:var(--success-color);font-weight:600;"><i class="fas fa-plus-circle"></i> Added</span></td>
                <td>Pancreas</td>
              </tr>
              <tr>
                <td>Aug 12, 2025</td>
                <td><span style="color:var(--success-color);font-weight:600;"><i class="fas fa-plus-circle"></i> Added</span></td>
                <td>Eyes, Kidneys</td>
              </tr>
            </tbody>
          </table>
        </div>

      </div>

      <!-- Digital Card Section -->
      <div id="card" class="section-content" style="display:none;">
        <div class="section-card">
          <h3 class="section-title"><i class="fas fa-id-card" style="color:var(--primary-color);"></i> Digital Donor Card</h3>
          <p style="color:var(--secondary-text-color);margin-bottom:1.25rem;">Tip: Keep a printed card in your wallet and a digital copy on your phone for quick access.</p>
          
          <!-- Credit Card Style Donor Card -->
          <div class="donor-card-wrapper">
            <div class="donor-card" style="
              background: linear-gradient(135deg, #6B46C1 0%, #9333EA 50%, #7C3AED 100%);
              border-radius: 20px;
              padding: 2rem;
              color: white;
              box-shadow: 0 20px 60px rgba(107, 70, 193, 0.4);
              position: relative;
              overflow: hidden;
              min-height: 350px;
              max-width: 600px;
              margin: 0 auto;
            ">
              <!-- Background Pattern -->
              <div style="
                position: absolute;
                top: -50px;
                right: -50px;
                width: 200px;
                height: 200px;
                background: rgba(255, 255, 255, 0.1);
                border-radius: 50%;
                z-index: 0;
              "></div>
              <div style="
                position: absolute;
                bottom: -30px;
                left: -30px;
                width: 150px;
                height: 150px;
                background: rgba(255, 255, 255, 0.08);
                border-radius: 50%;
                z-index: 0;
              "></div>

              <div style="position: relative; z-index: 1;">
                <!-- Card Header -->
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2.5rem;">
                  <div>
                    <div style="font-size: 1.5rem; font-weight: 800; letter-spacing: 1px; text-shadow: 2px 2px 4px rgba(0,0,0,0.2);">
                      LifeConnect
                    </div>
                    <div style="font-size: 0.85rem; font-weight: 600; letter-spacing: 2px; margin-top: 0.25rem; opacity: 0.95;">
                      ORGAN DONOR CARD
                    </div>
                  </div>
                  <div style="font-size: 2.5rem; opacity: 0.9;">
                    <i class="fas fa-heartbeat"></i>
                  </div>
                </div>

                <!-- Chip Simulation -->
                <div style="margin-bottom: 1.5rem;">
                  <div style="
                    width: 50px;
                    height: 40px;
                    background: linear-gradient(135deg, #FFD700, #FFA500);
                    border-radius: 8px;
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    box-shadow: 0 4px 8px rgba(0,0,0,0.3);
                  ">
                    <i class="fas fa-microchip" style="color: #333; font-size: 1.2rem;"></i>
                  </div>
                </div>

                <!-- Donor ID Number (Card Number Style) -->
                <div style="
                  font-size: 1.4rem;
                  font-weight: 600;
                  letter-spacing: 4px;
                  margin-bottom: 2rem;
                  font-family: 'Courier New', monospace;
                  text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
                ">
                  DOD2025-014
                </div>

                <!-- Cardholder Info -->
                <div style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                  <div>
                    <div style="font-size: 0.7rem; opacity: 0.8; margin-bottom: 0.25rem; letter-spacing: 1px;">DONOR NAME</div>
                    <div style="font-size: 1.1rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">
                      Mr. Nuwan Perera
                    </div>
                  </div>
                  <div>
                    <div style="font-size: 0.7rem; opacity: 0.8; margin-bottom: 0.25rem; letter-spacing: 1px;">DOB</div>
                    <div style="font-size: 0.95rem; font-weight: 600;">05/15/1987</div>
                  </div>
                  <div>
                    <div style="font-size: 0.7rem; opacity: 0.8; margin-bottom: 0.25rem; letter-spacing: 1px;">BLOOD</div>
                    <div style="font-size: 0.95rem; font-weight: 700;">O+</div>
                  </div>
                </div>

                <!-- Additional Info -->
                <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                  <div>
                    <div style="font-size: 0.7rem; opacity: 0.8; margin-bottom: 0.25rem; letter-spacing: 1px;">NIC NUMBER</div>
                    <div style="font-size: 0.95rem; font-weight: 600; letter-spacing: 1px;">872345678V</div>
                  </div>
                  <div>
                    <div style="font-size: 0.7rem; opacity: 0.8; margin-bottom: 0.25rem; letter-spacing: 1px;">CONTACT</div>
                    <div style="font-size: 0.85rem; font-weight: 600;">+94 77 123 4567</div>
                  </div>
                </div>

                <!-- Registered Organs -->
                <div style="margin-bottom: 1.5rem;">
                  <div style="font-size: 0.75rem; opacity: 0.85; margin-bottom: 0.5rem; letter-spacing: 1px;">
                    <i class="fas fa-heart-pulse"></i> REGISTERED ORGANS
                  </div>
                  <div id="donorCardOrgans" style="display: flex; flex-wrap: wrap; gap: 0.4rem;">
                    <!-- Organs will be dynamically populated here -->
                  </div>
                </div>

                <!-- Emergency Contact -->
                <div style="margin-bottom: 1rem; padding: 0.8rem; background: rgba(255,255,255,0.15); border-radius: 10px; backdrop-filter: blur(10px);">
                  <div style="font-size: 0.7rem; opacity: 0.85; margin-bottom: 0.3rem; letter-spacing: 1px;">
                    <i class="fas fa-user-shield"></i> EMERGENCY CONTACT
                  </div>
                  <div style="font-size: 0.9rem; font-weight: 600;">Mrs. Amali Perera (Spouse)</div>
                  <div style="font-size: 0.85rem; opacity: 0.95;"><i class="fas fa-phone"></i> +94 77 234 5678</div>
                </div>

                <!-- Footer -->
                <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 1rem; border-top: 1px solid rgba(255,255,255,0.3);">
                  <div style="font-size: 0.7rem; opacity: 0.85; max-width: 60%;">
                    <i class="fas fa-shield-alt"></i> Human Tissue Act No. 48 of 1987
                  </div>
                  <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-check-circle" style="color: #4ADE80; font-size: 1.2rem;"></i>
                    <span style="font-size: 0.85rem; font-weight: 700;">VERIFIED</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="btn-group" style="margin-top:2.5rem;">
            <button class="btn btn-primary" onclick="downloadPDF('Digital Donor Card')"><i class="fas fa-download"></i> Download Card (PDF)</button>
            <button class="btn btn-secondary" onclick="alert('🖨️ Printing card...')"><i class="fas fa-print"></i> Print Card</button>
            <button class="btn btn-secondary" onclick="alert('📧 Email sharing opened...')"><i class="fas fa-envelope"></i> Share via Email</button>
          </div>

          <div class="note-box" style="margin-top:2rem;">
            <strong><i class="fas fa-info-circle"></i> Keep Your Card Accessible</strong>
            <p>Store a physical or digital copy of your donor card in your wallet, phone, or with your ID documents. Inform family members about your donor status.</p>
          </div>
        </div>
      </div>

      <!-- Activity Log Section -->
      <div id="activity" class="section-content" style="display:none;">
        <div class="section-card">
          <h3 class="section-title">📋 Activity Log</h3>
          <p style="color:var(--secondary-text-color);margin-bottom:1.5rem;">Complete history of all actions and updates to your donor account.</p>
          
          <div class="timeline-item">
            <div>
              <div class="timeline-date">August 12, 2025</div>
              <div class="timeline-action">Registration Completed</div>
              <div class="timeline-desc">Successfully registered as organ and body donor</div>
            </div>
          </div>
          <div class="timeline-item">
            <div>
              <div class="timeline-date">August 14, 2025</div>
              <div class="timeline-action">Medical Verification Completed</div>
              <div class="timeline-desc">Medical eligibility verified by LifeConnect team</div>
            </div>
          </div>
          <div class="timeline-item">
            <div>
              <div class="timeline-date">August 15, 2025</div>
              <div class="timeline-action">Family Custodians Assigned</div>
              <div class="timeline-desc">Mrs. Amali Perera and Mr. Kamal Perera linked as family custodians</div>
            </div>
          </div>
          <div class="timeline-item">
            <div>
              <div class="timeline-date">August 18, 2025</div>
              <div class="timeline-action">Consent Updated</div>
              <div class="timeline-desc">Added Heart, Lungs, Liver to donation consent</div>
            </div>
          </div>
          <div class="timeline-item">
            <div>
              <div class="timeline-date">September 5, 2025</div>
              <div class="timeline-action">Digital Donor Card Generated</div>
              <div class="timeline-desc">Official donor card created and downloaded</div>
            </div>
          </div>
          <div class="timeline-item">
            <div>
              <div class="timeline-date">October 10, 2025</div>
              <div class="timeline-action">Profile Updated</div>
              <div class="timeline-desc">Contact information and address updated</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Settings Section -->
      <div id="settings" class="section-content" style="display:none;">
        <div class="section-card">
          <h3 class="section-title">⚙️ Account Settings</h3>
          
          <h4 style="font-weight:700;margin-bottom:1rem;color:var(--primary-color);">Security</h4>
          <div class="form-group">
            <label class="form-label">Current Password</label>
            <input type="password" class="form-input" placeholder="Enter current password">
          </div>
          <div class="form-group">
            <label class="form-label">New Password</label>
            <input type="password" class="form-input" placeholder="Enter new password">
          </div>
          <div class="form-group">
            <label class="form-label">Confirm New Password</label>
            <input type="password" class="form-input" placeholder="Confirm new password">
          </div>
          <button class="btn btn-primary" onclick="updatePassword()">Update Password</button>

          <div style="height:2rem;"></div>
          <h4 style="font-weight:700;margin-bottom:1rem;color:var(--primary-color);">Notifications</h4>
          <div style="background:var(--gray-bg-color);padding:1rem;border-radius:8px;margin-bottom:0.75rem;display:flex;justify-content:space-between;align-items:center;">
            <div>
              <div style="font-weight:600;">Email Notifications</div>
              <div style="font-size:0.85rem;color:var(--secondary-text-color);">Receive updates about your donor account</div>
            </div>
            <input type="checkbox" checked style="width:20px;height:20px;" onchange="toggleNotification('email', this.checked)">
          </div>
          <div style="background:var(--gray-bg-color);padding:1rem;border-radius:8px;margin-bottom:0.75rem;display:flex;justify-content:space-between;align-items:center;">
            <div>
              <div style="font-weight:600;">SMS Notifications</div>
              <div style="font-size:0.85rem;color:var(--secondary-text-color);">Important alerts via text message</div>
            </div>
            <input type="checkbox" checked style="width:20px;height:20px;" onchange="toggleNotification('sms', this.checked)">
          </div>

          <div style="height:2rem;"></div>
          <h4 style="font-weight:700;margin-bottom:1rem;color:var(--primary-color);">Privacy</h4>
          <div class="btn-group">
            <button class="btn btn-secondary" onclick="downloadMyData()">Download My Data</button>
            <button class="btn btn-secondary" onclick="viewPrivacyPolicy()">Privacy Policy</button>
          </div>

          <div style="height:2rem;"></div>
          <h4 style="font-weight:700;margin-bottom:1rem;color:var(--danger-color);">Danger Zone</h4>
          <div style="background:#fef2f2;border:2px solid var(--danger-color);border-radius:10px;padding:1.5rem;">
            <div style="font-weight:600;margin-bottom:0.5rem;">Deactivate Donor Registration</div>
            <div style="font-size:0.9rem;color:var(--secondary-text-color);margin-bottom:1rem;">This will remove you from the active donor list. You can re-register anytime.</div>
            <button class="btn btn-danger" onclick="deactivateAccount()">Deactivate Account</button>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<!-- MODALS -->
<div id="custodianModal" class="modal">
  <div class="modal-content">
    <div class="modal-title"><i class="fas fa-users"></i> Add/Edit Family Custodian</div>
    <div class="form-group">
      <label class="form-label">Full Name <span style="color: red;">*</span></label>
      <input type="text" class="form-input" id="custodianName" placeholder="Enter full name" required minlength="3" maxlength="100" pattern="[A-Za-z\s.]+" title="Name should only contain letters, spaces, and periods">
    </div>
    <div class="form-group">
      <label class="form-label">Relationship <span style="color: red;">*</span></label>
      <select class="form-select" id="custodianRelationship" required>
        <option>Spouse</option>
        <option>Child</option>
        <option>Parent</option>
        <option>Sibling</option>
        <option>Other Relative</option>
      </select>
    </div>
    <div class="form-group">
      <label class="form-label">NIC Number <span style="color: red;">*</span></label>
      <input type="text" class="form-input" id="custodianNIC" placeholder="Enter NIC number (e.g., 872345678V or 198723456789)" required pattern="^(\d{9}[VvXx]|\d{12})$" title="Enter valid Sri Lankan NIC (9 digits + V/X or 12 digits)" maxlength="12">
    </div>
    <div class="form-group">
      <label class="form-label">Contact Number <span style="color: red;">*</span></label>
      <input type="tel" class="form-input" id="custodianPhone" placeholder="+94 77 123 4567" required pattern="^(\+94|0)?[0-9\s]{9,12}$" title="Enter valid Sri Lankan phone number">
    </div>
    <div class="form-group">
      <label class="form-label">Email Address <span style="color: red;">*</span></label>
      <input type="email" class="form-input" id="custodianEmail" placeholder="Enter email address" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" title="Enter a valid email address">
    </div>
    
    <div class="modal-actions">
      <button class="btn btn-secondary" onclick="closeModal('custodianModal')"><i class="fas fa-times"></i> Cancel</button>
      <button class="btn btn-success" onclick="saveCustodian()"><i class="fas fa-save"></i> Save Custodian</button>
    </div>
  </div>
</div>

<!-- Legal Custodian Modal -->
<div id="legalCustodianModal" class="modal">
  <div class="modal-content">
    <div class="modal-title"><i class="fas fa-gavel"></i> Add Legal Custodian</div>
    <div class="alert-banner info">
      <div class="alert-icon"><i class="fas fa-info-circle"></i></div>
      <div class="alert-content">
        <div class="alert-title">Legal Custodian Information</div>
        <div>A legal custodian is a professional (lawyer, legal representative) who can act on your behalf in legal matters related to organ donation.</div>
      </div>
    </div>
    <div class="form-group">
      <label class="form-label">Full Name <span style="color: red;">*</span></label>
      <input type="text" class="form-input" id="legalCustodianName" placeholder="Enter full name" required minlength="3" maxlength="100" pattern="[A-Za-z\s.]+" title="Name should only contain letters, spaces, and periods">
    </div>
    <div class="form-group">
      <label class="form-label">Professional Title <span style="color: red;">*</span></label>
      <select class="form-select" id="legalCustodianTitle" required>
        <option>Attorney at Law</option>
        <option>Legal Representative</option>
        <option>Notary Public</option>
        <option>Legal Advisor</option>
        <option>Other Legal Professional</option>
      </select>
    </div>
    <div class="form-group">
      <label class="form-label">Law Firm/Organization <span style="color: red;">*</span></label>
      <input type="text" class="form-input" id="legalCustodianFirm" placeholder="Enter law firm or organization name" required minlength="3" maxlength="150">
    </div>
    <div class="form-group">
      <label class="form-label">Bar Registration Number <span style="color: red;">*</span></label>
      <input type="text" class="form-input" id="legalCustodianBarNumber" placeholder="Enter bar registration number" required pattern="^[A-Z0-9\-/]+$" title="Enter valid bar registration number (alphanumeric)" maxlength="30">
    </div>
    <div class="form-group">
      <label class="form-label">NIC Number <span style="color: red;">*</span></label>
      <input type="text" class="form-input" id="legalCustodianNIC" placeholder="Enter NIC number (e.g., 872345678V or 198723456789)" required pattern="^(\d{9}[VvXx]|\d{12})$" title="Enter valid Sri Lankan NIC (9 digits + V/X or 12 digits)" maxlength="12">
    </div>
    <div class="form-group">
      <label class="form-label">Contact Number <span style="color: red;">*</span></label>
      <input type="tel" class="form-input" id="legalCustodianPhone" placeholder="+94 77 123 4567" required pattern="^(\+94|0)?[0-9\s]{9,12}$" title="Enter valid Sri Lankan phone number">
    </div>
    <div class="form-group">
      <label class="form-label">Email Address <span style="color: red;">*</span></label>
      <input type="email" class="form-input" id="legalCustodianEmail" placeholder="Enter email address" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" title="Enter a valid email address">
    </div>
    <div class="form-group">
      <label class="form-label">Office Address <span style="color: red;">*</span></label>
      <textarea class="form-textarea" id="legalCustodianAddress" placeholder="Enter office address" required minlength="10" maxlength="300"></textarea>
    </div>
    <div class="form-group">
      <label class="form-label">Authorization Document <span style="color: red;">*</span></label>
      <input type="file" class="form-input" id="legalCustodianDocument" accept=".pdf,.jpg,.jpeg,.png" required>
      <small style="color:var(--secondary-text-color);font-size:0.8rem;">Upload power of attorney or legal authorization document (PDF, JPG, PNG - Max 5MB)</small>
    </div>
    <div class="modal-actions">
      <button class="btn btn-secondary" onclick="closeModal('legalCustodianModal')"><i class="fas fa-times"></i> Cancel</button>
      <button class="btn btn-warning" onclick="saveLegalCustodian()"><i class="fas fa-gavel"></i> Save Legal Custodian</button>
    </div>
  </div>
</div>

<div id="uploadModal" class="modal">
  <div class="modal-content">
    <div class="modal-title"><i class="fas fa-file-upload"></i> Upload Document</div>
    <div class="form-group">
      <label class="form-label">Document Type</label>
      <select class="form-select">
        <option>Signed Consent Form</option>
        <option>Notarized Document</option>
        <option>Witness Statement</option>
        <option>Other Legal Document</option>
      </select>
    </div>
    <div class="form-group">
      <label class="form-label">Select File</label>
      <input type="file" class="form-input" accept=".pdf,.jpg,.jpeg,.png">
    </div>
    <div class="form-group">
      <label class="form-label">Notes (Optional)</label>
      <textarea class="form-textarea" placeholder="Add any relevant notes about this document..."></textarea>
    </div>
    <div class="modal-actions">
      <button class="btn btn-secondary" onclick="closeModal('uploadModal')"><i class="fas fa-times"></i> Cancel</button>
      <button class="btn btn-success" onclick="uploadDocument()"><i class="fas fa-upload"></i> Upload Document</button>
    </div>
  </div>
</div>

<!-- Consent Edit Modal -->
<div id="consentEditModal" class="modal">
  <div class="modal-content" style="max-width:800px;">
    <div class="modal-title" style="font-size:1.5rem;border-bottom:3px solid var(--primary-color);padding-bottom:1rem;margin-bottom:1.5rem;">
      <i class="fas fa-edit" style="color:var(--primary-color);"></i> Edit Consent - Organ & Tissue Donation
    </div>
    
    <div style="background:linear-gradient(135deg,#eff6ff 0%,#dbeafe 100%);padding:1.25rem;border-radius:12px;border-left:4px solid var(--primary-color);margin-bottom:2rem;">
      <div style="font-weight:700;color:var(--primary-color);margin-bottom:0.5rem;font-size:1.05rem;">
        <i class="fas fa-balance-scale"></i> Human Tissue Transplantation Act No. 48 of 1987
      </div>
      <div style="color:var(--secondary-text-color);font-size:0.95rem;">
        Update your organ and tissue donation consent. Changes will be automatically reflected in "My Donations" and Dashboard.
      </div>
    </div>
    
    <!-- Donor Info (Read-only) -->
    <div style="background:linear-gradient(135deg,#f8fafc 0%,#f1f5f9 100%);padding:1.5rem;border-radius:12px;margin-bottom:2rem;border:2px solid #e2e8f0;">
      <div style="font-weight:700;color:var(--primary-text-color);margin-bottom:1rem;font-size:1rem;display:flex;align-items:center;gap:0.5rem;">
        <i class="fas fa-user-circle" style="color:var(--primary-color);"></i> Donor Information
      </div>
      <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:1rem;font-size:0.9rem;">
        <div><strong style="color:var(--secondary-text-color);">Name:</strong> <span style="color:var(--primary-text-color);font-weight:600;">Mr. Nuwan Perera</span></div>
        <div><strong style="color:var(--secondary-text-color);">NIC:</strong> <span style="color:var(--primary-text-color);font-weight:600;">872345678V</span></div>
        <div><strong style="color:var(--secondary-text-color);">DOB:</strong> <span style="color:var(--primary-text-color);font-weight:600;">May 15, 1987</span></div>
        <div><strong style="color:var(--secondary-text-color);">Blood:</strong> <span style="color:var(--primary-text-color);font-weight:600;">O+</span></div>
      </div>
    </div>

    <!-- Organs & Tissues Selection -->
    <div class="form-group">
      <label class="form-label" style="font-size:1rem;font-weight:700;color:var(--primary-text-color);margin-bottom:1rem;display:flex;align-items:center;gap:0.5rem;">
        <i class="fas fa-heart-pulse" style="color:var(--primary-color);"></i> ORGANS & TISSUES CONSENTED
      </label>
      <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:0.85rem;">
        <label class="checkbox-label">
          <input type="checkbox" id="organEyes" checked onchange="updateConsentPreview()">
          <span>Eyes (Corneas)</span>
        </label>
        <label class="checkbox-label">
          <input type="checkbox" id="organKidneys" checked onchange="updateConsentPreview()">
          <span>Kidneys</span>
        </label>
        <label class="checkbox-label">
          <input type="checkbox" id="organHeart" checked onchange="updateConsentPreview()">
          <span>Heart</span>
        </label>
        <label class="checkbox-label">
          <input type="checkbox" id="organLungs" checked onchange="updateConsentPreview()">
          <span>Lungs</span>
        </label>
        <label class="checkbox-label">
          <input type="checkbox" id="organLiver" checked onchange="updateConsentPreview()">
          <span>Liver</span>
        </label>
        <label class="checkbox-label">
          <input type="checkbox" id="organPancreas" checked onchange="updateConsentPreview()">
          <span>Pancreas</span>
        </label>
        <label class="checkbox-label">
          <input type="checkbox" id="organBowels" onchange="updateConsentPreview()">
          <span>Bowels</span>
        </label>
        <label class="checkbox-label">
          <input type="checkbox" id="organBones" onchange="updateConsentPreview()">
          <span>Bones</span>
        </label>
        <label class="checkbox-label">
          <input type="checkbox" id="organSkin" onchange="updateConsentPreview()">
          <span>Skin</span>
        </label>
      </div>
    </div>

    <!-- Full Body Donation -->
    <div class="form-group" style="margin-top:1.5rem;">
      <label class="checkbox-label" style="background:linear-gradient(135deg,#f0fdf4 0%,#dcfce7 100%);padding:1.25rem;border-radius:12px;border:2px solid var(--success-color);box-shadow:0 2px 8px rgba(16,185,129,0.1);">
        <input type="checkbox" id="fullBodyDonation" checked onchange="updateConsentPreview()">
        <span style="font-weight:700;font-size:1.05rem;color:var(--success-color);"><i class="fas fa-dna"></i> Full Body Donation: Yes</span>
      </label>
    </div>

    <!-- Consent Preview -->
    <div style="background:linear-gradient(135deg,#eff6ff 0%,#dbeafe 100%);padding:1.5rem;border-radius:12px;border:2px solid var(--primary-color);margin-top:2rem;box-shadow:0 4px 12px rgba(0,91,170,0.1);">
      <div style="font-weight:700;color:var(--primary-color);margin-bottom:0.75rem;font-size:1rem;display:flex;align-items:center;gap:0.5rem;">
        <i class="fas fa-eye"></i> Consent Preview
      </div>
      <p id="consentPreviewText" style="margin:0;font-size:0.95rem;color:var(--primary-text-color);line-height:1.6;font-weight:600;">Eyes, Kidneys, Heart, Lungs, Liver, Pancreas • Full Body: Yes</p>
    </div>

    <div class="modal-actions">
      <button class="btn btn-secondary" onclick="closeModal('consentEditModal')"><i class="fas fa-times"></i> Cancel</button>
      <button class="btn btn-success" onclick="saveConsentChanges()"><i class="fas fa-save"></i> Save Changes</button>
    </div>
  </div>
</div>

<script>
let currentRole = 'donor';
let isDonorDeceased = false;
let currentCustodianId = null;

// Donor data state
let donorData = {
  organs: {
    eyes: true,
    kidneys: true,
    heart: true,
    lungs: true,
    liver: true,
    pancreas: false,
    bowels: false,
    bones: false,
    skin: false,
    tendons: false,
    valves: false,
    other: false
  },
  fullBody: true,
  profile: {
    name: 'Mr. Nuwan Perera',
    bloodType: 'O+',
    contact: '+94 77 123 4567',
    email: 'nuwan.perera@email.com'
  },
  custodians: [
    {
      id: 1,
      name: 'Mrs. Amali Perera',
      relationship: 'Spouse',
      nic: '897654321V',
      contact: '+94 77 234 5678',
      email: 'amali.perera@email.com',
      address: '45, Galle Road, Colombo 03',
      type: 'Primary'
    },
    {
      id: 2,
      name: 'Mr. Kamal Perera',
      relationship: 'Brother',
      nic: '856789123V',
      contact: '+94 77 345 6789',
      email: 'kamal.perera@email.com',
      address: '78, Main Street, Kandy',
      type: 'Secondary'
    }
  ],
  consent: {
    organs: ['Eyes', 'Kidneys', 'Heart', 'Lungs', 'Liver', 'Pancreas'],
    fullBody: true,
    lastUpdated: 'August 18, 2025 at 2:30 PM',
    history: [
      {
        date: 'Aug 18, 2025',
        action: 'Added',
        organs: 'Heart, Lungs, Liver'
      },
      {
        date: 'Aug 15, 2025',
        action: 'Added',
        organs: 'Pancreas'
      },
      {
        date: 'Aug 12, 2025',
        action: 'Added',
        organs: 'Eyes, Kidneys'
      }
    ] 
  }
};

function switchRole(role) {
  currentRole = role;
  isDonorDeceased = (role === 'custodian');
  
  document.querySelectorAll('.role-switch button').forEach(btn => btn.classList.remove('active'));
  event.target.classList.add('active');
  
  if (role === 'custodian') {
    document.getElementById('userName').textContent = 'Mrs. Amali Perera (Custodian)';
    document.getElementById('userAvatar').textContent = 'AP';
    document.getElementById('userStatus').innerHTML = '<i class="fas fa-users"></i> Custodian Access';
    document.getElementById('userStatus').classList.remove('deceased');
    document.getElementById('userStatus').classList.add('custodian');
    document.getElementById('headerSubtitle').textContent = 'Managing donation coordination for Mr. Nuwan Perera';
    document.getElementById('sidebarDesc').textContent = 'Coordination dashboard';
    document.getElementById('custodianMenu').style.display = 'block';
    
    showSection('custodian-dashboard');
  } else {
    document.getElementById('userName').textContent = 'Mr. Nuwan Perera';
    document.getElementById('userAvatar').textContent = 'NP';
    document.getElementById('userStatus').innerHTML = '<i class="fas fa-check"></i> Verified Donor';
    document.getElementById('userStatus').classList.remove('custodian', 'deceased');
    document.getElementById('headerSubtitle').textContent = 'Your journey of giving life - Manage your organ and body donation preferences';
    document.getElementById('sidebarDesc').textContent = 'Your donation dashboard';
    document.getElementById('custodianMenu').style.display = 'none';
    
    showSection('dashboard');
  }
}

function showSection(section) {
  document.querySelectorAll('.section-content').forEach(s => s.style.display = 'none');
  document.querySelectorAll('.menu-item').forEach(m => m.classList.remove('active'));
  
  const sections = {
    dashboard: { title: '<i class="fas fa-home"></i> Dashboard Overview', desc: 'Welcome back! Manage your donation preferences and profile information.' },
    'custodian-dashboard': { title: '<i class="fas fa-users"></i> Custodian Dashboard', desc: 'Manage donation coordination workflow for the deceased donor.' },
    profile: { title: '<i class="fas fa-user"></i> Profile & Registration', desc: 'Update your personal information and contact details.' },
    donations: { title: '<i class="fas fa-heart"></i> My Donations (Read-Only)', desc: 'View your current consent. To update organs, go to Legal & Consent.' },
    family: { title: '<i class="fas fa-users"></i> Family Custodians', desc: 'Manage your designated family members who will coordinate donations.' },
    legal: { title: '<i class="fas fa-gavel"></i> Legal & Consent', desc: 'View and manage your consent documents and legal agreements.' },
    card: { title: '<i class="fas fa-id-card"></i> Digital Donor Card', desc: 'Download and share your official donor identification card.' },
    activity: { title: '<i class="fas fa-clipboard-list"></i> Activity Log', desc: 'Complete history of all actions and updates to your donor account.' },
    settings: { title: '<i class="fas fa-cog"></i> Settings', desc: 'Manage your account security, notifications, and preferences.' },
    'death-confirmation': { title: '<i class="fas fa-exclamation-triangle"></i> Death Confirmation', desc: 'Confirm deceased status to activate coordination workflow.' },
    documents: { title: '<i class="fas fa-file-upload"></i> Document Upload', desc: 'Upload and manage required legal documents.' },
    hospitals: { title: '<i class="fas fa-hospital"></i> Hospital Coordination', desc: 'Coordinate organ collection with assigned hospitals.' },
    'medical-schools': { title: '<i class="fas fa-graduation-cap"></i> Medical School Coordination', desc: 'Manage full body donation with medical schools.' },
    tribute: { title: '<i class="fas fa-medal"></i> Tribute & Certificate', desc: 'Download tribute certificates and view donation impact.' }
  };
  
  const sectionEl = document.getElementById(section);
  if (sectionEl) {
    sectionEl.style.display = 'block';
    document.getElementById('contentTitle').innerHTML = sections[section].title;
    document.getElementById('sectionDesc').textContent = sections[section].desc;
    
    const menuItems = document.querySelectorAll('.menu-item');
    menuItems.forEach(item => {
      if (item.getAttribute('onclick') && item.getAttribute('onclick').includes(section)) {
        item.classList.add('active');
      }
    });
    
    // Render custodian cards when family section is shown
    if (section === 'family') {
      renderCustodianCards();
    }
    
    // Update dashboard when dashboard section is shown
    if (section === 'dashboard') {
      updateDashboardOverview();
    }
    
    // Update My Donations display when donations section is shown
    if (section === 'donations') {
      syncConsentToMyDonations();
    }
    
    // Update Digital Donor Card when card section is shown
    if (section === 'card') {
      updateDigitalDonorCard();
    }
    
    // Update Custodian Dashboard cards when custodian dashboard is shown
    if (section === 'custodian-dashboard') {
      updateCustodianDashboardCards();
    }
  }
}

function updateContactInfo() {
  const phone = document.getElementById('profilePhone').value.trim();
  const email = document.getElementById('profileEmail').value.trim().toLowerCase();
  const address = document.getElementById('profileAddress').value.trim();
  
  // Validation
  if (!phone || !email || !address) {
    showNotification('⚠️ Please fill in all required fields', 'warning');
    return;
  }
  
  // Validate phone number
  const phonePattern = /^(\+94|0)?[0-9\s]{9,12}$/;
  if (!phonePattern.test(phone)) {
    showNotification('⚠️ Invalid phone number format', 'warning');
    return;
  }
  
  // Validate email
  const emailPattern = /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/;
  if (!emailPattern.test(email)) {
    showNotification('⚠️ Invalid email address format', 'warning');
    return;
  }
  
  donorData.profile.contact = phone;
  donorData.profile.email = email;
  
  showNotification('✅ Contact information updated successfully!', 'success');
  addActivityLog('Contact Updated', 'Phone: ' + phone + ', Email: ' + email);
  setTimeout(() => showSection('dashboard'), 1000);
}

function updateConsent() {
  showNotification('🔄 Opening consent update form...', 'info');
  addActivityLog('Consent Update Started', 'User initiated consent modification');
  
  // In production, this would open a modal or redirect to consent update page
  setTimeout(() => {
    showNotification('📋 Consent update form opened. You can modify your organ and tissue selections.', 'info');
  }, 500);
}

function addCustodian() {
  currentCustodianId = null;
  document.getElementById('custodianModal').classList.add('active');
  
  // Clear form
  document.getElementById('custodianName').value = '';
  document.getElementById('custodianRelationship').value = 'Spouse';
  document.getElementById('custodianNIC').value = '';
  document.getElementById('custodianNIC').disabled = false; // ensure NIC is editable for new custodian
  document.getElementById('custodianNIC').title = '';
  document.getElementById('custodianPhone').value = '';
  document.getElementById('custodianEmail').value = '';
}

function addLegalCustodian() {
  document.getElementById('legalCustodianModal').classList.add('active');
  
  // Clear form
  document.getElementById('legalCustodianName').value = '';
  document.getElementById('legalCustodianTitle').value = 'Attorney at Law';
  document.getElementById('legalCustodianFirm').value = '';
  document.getElementById('legalCustodianBarNumber').value = '';
  document.getElementById('legalCustodianNIC').value = '';
  document.getElementById('legalCustodianPhone').value = '';
  document.getElementById('legalCustodianEmail').value = '';
  document.getElementById('legalCustodianAddress').value = '';
  document.getElementById('legalCustodianDocument').value = '';
}

function editCustodian(id) {
  currentCustodianId = id;
  const custodian = donorData.custodians.find(c => c.id === id);
  
  if (custodian) {
    document.getElementById('custodianName').value = custodian.name;
    document.getElementById('custodianRelationship').value = custodian.relationship;
    document.getElementById('custodianNIC').value = custodian.nic;
    document.getElementById('custodianPhone').value = custodian.contact;
    document.getElementById('custodianEmail').value = custodian.email;

    // Lock NIC if status is Accepted
    const nicInput = document.getElementById('custodianNIC');
    if ((custodian.status || 'Accepted') === 'Accepted') {
      nicInput.disabled = true;
      nicInput.title = 'NIC cannot be edited after acceptance';
    } else {
      nicInput.disabled = false;
      nicInput.title = '';
    }
  }
  
  document.getElementById('custodianModal').classList.add('active');
}

function removeCustodian(id) {
  if (donorData.custodians.length <= 2) {
    showNotification('⚠️ Minimum 2 custodians required. Cannot remove.', 'warning');
    return;
  }
  
  const custodian = donorData.custodians.find(c => c.id === id);
  // Enforce: After deletion, at least 2 Accepted custodians must remain (any type)
  const acceptedCount = donorData.custodians.filter(c => (c.status || 'Accepted') === 'Accepted').length;
  if (custodian && (custodian.status || 'Accepted') === 'Accepted') {
    if (acceptedCount <= 2) {
      showNotification('⚠️ At least 2 accepted custodians must remain. Cannot remove.', 'warning');
      return;
    }
  }
  if (custodian && confirm('⚠️ Remove ' + custodian.name + ' as custodian?\n\nThis action will update all dashboards.')) {
    donorData.custodians = donorData.custodians.filter(c => c.id !== id);
    showNotification('✅ Custodian removed successfully', 'success');
    addActivityLog('Custodian Removed', custodian.name);
    renderCustodianCards(); // Dynamically update the cards in Family Custodians section
    updateDashboardOverview(); // Update dashboard overview
    updateCustodianDashboardCards(); // Update Custodian Dashboard
  }
}

function renderCustodianCards() {
  const container = document.getElementById('custodianCardsContainer');
  if (!container) return;
  
  container.innerHTML = '';
  
  donorData.custodians.forEach((custodian, index) => {
    const initials = custodian.name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
    const isLegal = custodian.type === 'Legal';
    
    let badgeHtml = '';
    if (custodian.type === 'Primary') {
      badgeHtml = '<span class="status-badge" style="background:var(--success-color);"><i class="fas fa-crown"></i> Primary Custodian</span>';
    } else if (custodian.type === 'Secondary') {
      badgeHtml = '<span class="status-badge" style="background:var(--primary-color);"><i class="fas fa-shield-alt"></i> Secondary Custodian</span>';
    } else if (isLegal) {
      badgeHtml = '<span class="status-badge" style="background:var(--warning-color);"><i class="fas fa-gavel"></i> Legal Custodian</span>';
    } else {
      badgeHtml = '<span class="status-badge" style="background:#6b7280;"><i class="fas fa-user"></i> Additional Custodian</span>';
    }
    const status = (custodian.status || 'Accepted');
    const statusColor = status === 'Pending' ? 'var(--warning-color)' : 'var(--success-color)';
    const statusBadge = '<span class="status-badge" style="background:' + statusColor + ';"><i class="fas ' + (status === 'Pending' ? 'fa-hourglass-half' : 'fa-check') + '"></i> ' + status + '</span>';
    
    const cardHtml = `
      <div class="family-card">
        <div>
          <div class="family-avatar">${initials}</div>
          <div class="family-info">
            <div class="family-name">${custodian.name}</div>
            <div class="family-relation">Relationship: <strong>${custodian.relationship || custodian.title || 'N/A'}</strong></div>
            <div class="family-contact">
              <div><i class="fas fa-phone"></i> ${custodian.contact}</div>
              <div><i class="fas fa-envelope"></i> ${custodian.email}</div>
            </div>
            <div style="margin-top:0.75rem; display:flex; gap:0.5rem; flex-wrap:wrap;">
              ${badgeHtml} ${statusBadge}
            </div>
          </div>
        </div>
        <div class="custodian-actions">
          <button class="btn btn-secondary btn-sm" onclick="editCustodian(${custodian.id})"><i class="fas fa-edit"></i> Edit</button>
          <button class="btn btn-danger btn-sm" onclick="removeCustodian(${custodian.id})"><i class="fas fa-trash"></i> Remove</button>
        </div>
      </div>
    `;
    
    container.innerHTML += cardHtml;
  });
}

function saveCustodian() {
  const name = document.getElementById('custodianName').value.trim();
  const relationship = document.getElementById('custodianRelationship').value;
  let nic = document.getElementById('custodianNIC').value.trim().toUpperCase();
  const phone = document.getElementById('custodianPhone').value.trim();
  const email = document.getElementById('custodianEmail').value.trim().toLowerCase();
  
  // Validation
  if (!name || !nic || !phone || !email) {
    showNotification('⚠️ Please fill in all required fields', 'warning');
    return;
  }
  
  // Validate NIC format unless editing an Accepted custodian (locked NIC)
  let editingAccepted = false;
  if (currentCustodianId) {
    const existing = donorData.custodians.find(c => c.id === currentCustodianId);
    if (existing && (existing.status || 'Accepted') === 'Accepted') {
      editingAccepted = true;
      nic = existing.nic; // enforce original NIC
    }
  }
  if (!editingAccepted) {
    const nicPattern = /^(\d{9}[VvXx]|\d{12})$/;
    if (!nicPattern.test(nic)) {
      showNotification('⚠️ Invalid NIC format. Use 9 digits + V/X or 12 digits', 'warning');
      return;
    }
  }
  
  // Validate phone number
  const phonePattern = /^(\+94|0)?[0-9\s]{9,12}$/;
  if (!phonePattern.test(phone)) {
    showNotification('⚠️ Invalid phone number format', 'warning');
    return;
  }
  
  // Validate email
  const emailPattern = /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/;
  if (!emailPattern.test(email)) {
    showNotification('⚠️ Invalid email address format', 'warning');
    return;
  }
  
  if (currentCustodianId) {
    // Update existing custodian
    const custodian = donorData.custodians.find(c => c.id === currentCustodianId);
    if (custodian) {
      custodian.name = name;
      custodian.relationship = relationship;
      custodian.nic = editingAccepted ? custodian.nic : nic;
      custodian.contact = phone;
      custodian.email = email;
      
      showNotification('✅ Custodian updated successfully', 'success');
      addActivityLog('Custodian Updated', name);
    }
  } else {
    // Add new custodian
    const newId = Math.max(...donorData.custodians.map(c => c.id)) + 1;
    const newCustodian = {
      id: newId,
      name: name,
      relationship: relationship,
      nic: nic,
      contact: phone,
      email: email,
      type: 'Additional',
      status: 'Pending'
    };
    
    donorData.custodians.push(newCustodian);
    showNotification('✅ New custodian added successfully', 'success');
    addActivityLog('Custodian Added', name);
  }
  
  closeModal('custodianModal');
  renderCustodianCards(); // Dynamically update the cards in Family Custodians section
  updateDashboardOverview(); // Update dashboard overview
  updateCustodianDashboardCards(); // Update Custodian Dashboard
}

function saveLegalCustodian() {
  const name = document.getElementById('legalCustodianName').value.trim();
  const title = document.getElementById('legalCustodianTitle').value;
  const firm = document.getElementById('legalCustodianFirm').value.trim();
  const barNumber = document.getElementById('legalCustodianBarNumber').value.trim().toUpperCase();
  const nic = document.getElementById('legalCustodianNIC').value.trim().toUpperCase();
  const phone = document.getElementById('legalCustodianPhone').value.trim();
  const email = document.getElementById('legalCustodianEmail').value.trim().toLowerCase();
  const address = document.getElementById('legalCustodianAddress').value.trim();
  const document = document.getElementById('legalCustodianDocument').files[0];
  
  // Validation
  if (!name || !title || !firm || !barNumber || !nic || !phone || !email || !address) {
    showNotification('⚠️ Please fill in all required fields', 'warning');
    return;
  }
  
  // Validate NIC format
  const nicPattern = /^(\d{9}[VvXx]|\d{12})$/;
  if (!nicPattern.test(nic)) {
    showNotification('⚠️ Invalid NIC format. Use 9 digits + V/X or 12 digits', 'warning');
    return;
  }
  
  // Validate phone number
  const phonePattern = /^(\+94|0)?[0-9\s]{9,12}$/;
  if (!phonePattern.test(phone)) {
    showNotification('⚠️ Invalid phone number format', 'warning');
    return;
  }
  
  // Validate email
  const emailPattern = /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/;
  if (!emailPattern.test(email)) {
    showNotification('⚠️ Invalid email address format', 'warning');
    return;
  }
  
  // Validate document
  if (!document) {
    showNotification('⚠️ Please upload authorization document', 'warning');
    return;
  }
  
  // Validate file size (max 5MB)
  const maxSize = 5 * 1024 * 1024; // 5MB in bytes
  if (document.size > maxSize) {
    showNotification('⚠️ File size exceeds 5MB. Please upload a smaller file', 'warning');
    return;
  }
  
  // Validate file type
  const allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
  if (!allowedTypes.includes(document.type)) {
    showNotification('⚠️ Invalid file type. Only PDF, JPG, and PNG files are allowed', 'warning');
    return;
  }
  
  // Add legal custodian
  const newId = Math.max(...donorData.custodians.map(c => c.id)) + 1;
  const newLegalCustodian = {
    id: newId,
    name: name,
    relationship: title,
    nic: nic,
    contact: phone,
    email: email,
    address: address,
    type: 'Legal',
    status: 'Pending',
    firm: firm,
    barNumber: barNumber,
    document: document.name
  };
  
  donorData.custodians.push(newLegalCustodian);
  showNotification('✅ Legal custodian added successfully', 'success');
  addActivityLog('Legal Custodian Added', name + ' (' + title + ')');
  
  closeModal('legalCustodianModal');
  renderCustodianCards(); // Dynamically update the cards in Family Custodians section
  updateDashboardOverview(); // Update dashboard overview
  updateCustodianDashboardCards(); // Update Custodian Dashboard
}

function sendNotificationToAll() {
  showNotification('📧 Sending notifications to all custodians...', 'info');
  addActivityLog('Notifications Sent', 'All custodians notified');
  
  setTimeout(() => {
    showNotification('✅ Notifications sent to ' + donorData.custodians.length + ' custodians', 'success');
  }, 1500);
}

function downloadPDF(type) {
  showNotification('📄 Downloading ' + type + '...', 'info');
  addActivityLog('PDF Downloaded', type);
  
  setTimeout(() => {
    showNotification('✅ Download complete!', 'success');
  }, 1000);
}

// Consent Management Functions
function openConsentEditModal() {
  // Pre-fill current consent state
  document.getElementById('organEyes').checked = donorData.consent.organs.includes('Eyes');
  document.getElementById('organKidneys').checked = donorData.consent.organs.includes('Kidneys');
  document.getElementById('organHeart').checked = donorData.consent.organs.includes('Heart');
  document.getElementById('organLungs').checked = donorData.consent.organs.includes('Lungs');
  document.getElementById('organLiver').checked = donorData.consent.organs.includes('Liver');
  document.getElementById('organPancreas').checked = donorData.consent.organs.includes('Pancreas');
  document.getElementById('organBowels').checked = donorData.consent.organs.includes('Bowels');
  document.getElementById('organBones').checked = donorData.consent.organs.includes('Bones');
  document.getElementById('organSkin').checked = donorData.consent.organs.includes('Skin');
  document.getElementById('fullBodyDonation').checked = donorData.consent.fullBody;
  
  updateConsentPreview();
  document.getElementById('consentEditModal').classList.add('active');
}

function updateConsentPreview() {
  const selectedOrgans = [];
  const organMap = {
    'organEyes': 'Eyes',
    'organKidneys': 'Kidneys',
    'organHeart': 'Heart',
    'organLungs': 'Lungs',
    'organLiver': 'Liver',
    'organPancreas': 'Pancreas',
    'organBowels': 'Bowels',
    'organBones': 'Bones',
    'organSkin': 'Skin'
  };
  
  Object.keys(organMap).forEach(id => {
    const checkbox = document.getElementById(id);
    if (checkbox && checkbox.checked) {
      selectedOrgans.push(organMap[id]);
    }
  });
  
  const fullBody = document.getElementById('fullBodyDonation').checked;
  const previewText = selectedOrgans.join(', ') + (fullBody ? ' • Full Body: Yes' : '');
  document.getElementById('consentPreviewText').textContent = previewText || 'No organs selected';
}

function saveConsentChanges() {
  const organMap = {
    'organEyes': 'Eyes',
    'organKidneys': 'Kidneys',
    'organHeart': 'Heart',
    'organLungs': 'Lungs',
    'organLiver': 'Liver',
    'organPancreas': 'Pancreas',
    'organBowels': 'Bowels',
    'organBones': 'Bones',
    'organSkin': 'Skin'
  };
  
  const newOrgans = [];
  Object.keys(organMap).forEach(id => {
    const checkbox = document.getElementById(id);
    if (checkbox && checkbox.checked) {
      newOrgans.push(organMap[id]);
    }
  });
  
  const newFullBody = document.getElementById('fullBodyDonation').checked;
  
  // Check what changed
  const addedOrgans = newOrgans.filter(o => !donorData.consent.organs.includes(o));
  const removedOrgans = donorData.consent.organs.filter(o => !newOrgans.includes(o));
  
  // Update consent data
  donorData.consent.organs = newOrgans;
  donorData.consent.fullBody = newFullBody;
  donorData.consent.lastUpdated = new Date().toLocaleString('en-US', { 
    month: 'long', 
    day: 'numeric', 
    year: 'numeric', 
    hour: 'numeric', 
    minute: 'numeric', 
    hour12: true 
  });
  
  // Add to history - only Added or Removed actions
  if (addedOrgans.length > 0) {
    donorData.consent.history.unshift({
      date: new Date().toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }),
      action: 'Added',
      organs: addedOrgans.join(', ')
    });
  }
  
  if (removedOrgans.length > 0) {
    donorData.consent.history.unshift({
      date: new Date().toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }),
      action: 'Removed',
      organs: removedOrgans.join(', ')
    });
  }
  
  // Update history table
  updateConsentHistoryTable();
  
  // Sync to My Donations section
  syncConsentToMyDonations();
  
  // Update Legal & Consent summary
  updateConsentSummary();
  
  // Update official consent document
  updateOfficialConsentDocument();
  
  // Update dashboard overview
  updateDashboardOverview();
  
  // Update digital donor card
  updateDigitalDonorCard();
  
  closeModal('consentEditModal');
  showNotification('✅ Consent updated successfully! Changes synced to "My Donations", Dashboard, and Digital Donor Card.', 'success');
  addActivityLog('Consent Updated', newOrgans.join(', ') + (newFullBody ? ' + Full Body' : ''));
}

function syncConsentToMyDonations() {
  // Update the organs display in My Donations section - show only selected organs
  const organsContainer = document.getElementById('currentOrgans');
  if (!organsContainer) return;
  
  organsContainer.innerHTML = '';
  
  // Only show selected organs
  if (donorData.consent.organs.length === 0) {
    organsContainer.innerHTML = `
      <div style="grid-column:1/-1;text-align:center;padding:2rem;color:var(--secondary-text-color);">
        <i class="fas fa-info-circle" style="font-size:2rem;margin-bottom:1rem;color:var(--warning-color);"></i>
        <p style="margin:0;font-weight:600;">No organs selected yet</p>
        <p style="margin:0.5rem 0 0 0;font-size:0.9rem;">Go to "Legal & Consent" to select organs for donation</p>
      </div>
    `;
    return;
  }
  
  donorData.consent.organs.forEach(organ => {
    organsContainer.innerHTML += `
      <div class="selected-organ-card">
        <div class="selected-organ-name">${organ}</div>
        <div class="selected-organ-status"><i class="fas fa-check-circle"></i> Active</div>
      </div>
    `;
  });
}

function updateConsentSummary() {
  // Update the consent summary in Legal & Consent section
  const summaryElement = document.getElementById('consentSummaryOrgans');
  if (summaryElement) {
    summaryElement.textContent = donorData.consent.organs.join(', ') || 'No organs selected';
  }
}

function updateDigitalDonorCard() {
  // Update the digital donor card with current organ selection
  const cardOrgansContainer = document.getElementById('donorCardOrgans');
  if (!cardOrgansContainer) return;
  
  cardOrgansContainer.innerHTML = '';
  
  // Add organ badges
  donorData.consent.organs.forEach(organ => {
    const badge = document.createElement('span');
    badge.style.cssText = 'background: rgba(255,255,255,0.25); padding: 0.3rem 0.7rem; border-radius: 15px; font-size: 0.75rem; font-weight: 600; backdrop-filter: blur(10px);';
    badge.textContent = organ;
    cardOrgansContainer.appendChild(badge);
  });
  
  // Add full body donation badge if enabled
  if (donorData.consent.fullBody) {
    const fullBodyBadge = document.createElement('span');
    fullBodyBadge.style.cssText = 'background: rgba(255,255,255,0.3); padding: 0.3rem 0.8rem; border-radius: 15px; font-size: 0.75rem; font-weight: 700; backdrop-filter: blur(10px);';
    fullBodyBadge.innerHTML = '<i class="fas fa-check-circle"></i> Full Body';
    cardOrgansContainer.appendChild(fullBodyBadge);
  }
  
  // Show message if no organs selected
  if (donorData.consent.organs.length === 0 && !donorData.consent.fullBody) {
    cardOrgansContainer.innerHTML = '<span style="color: rgba(255,255,255,0.7); font-size: 0.8rem; font-style: italic;">No organs selected yet</span>';
  }
}

function viewConsentDetails() {
  showNotification('📋 Opening detailed consent view...', 'info');
  // This could open a modal or navigate to a detailed view
}

function updateConsentHistoryTable() {
  const historyTable = document.getElementById('consentHistoryTable');
  if (!historyTable) return;
  
  historyTable.innerHTML = '';
  
  donorData.consent.history.forEach((entry) => {
    const actionIcon = entry.action === 'Added' 
      ? '<i class="fas fa-plus-circle"></i>' 
      : '<i class="fas fa-minus-circle"></i>';
    const actionColor = entry.action === 'Added' 
      ? 'var(--success-color)' 
      : 'var(--danger-color)';
    
    historyTable.innerHTML += `
      <tr>
        <td>${entry.date}</td>
        <td><span style="color:${actionColor};font-weight:600;">${actionIcon} ${entry.action}</span></td>
        <td>${entry.organs}</td>
      </tr>
    `;
  });
}

function updateOfficialConsentDocument() {
  const consentOrgansDiv = document.getElementById('officialConsentOrgans');
  if (!consentOrgansDiv) return;
  
  const gridDiv = consentOrgansDiv.querySelector('div');
  if (!gridDiv) return;
  
  gridDiv.innerHTML = '';
  
  donorData.consent.organs.forEach(organ => {
    gridDiv.innerHTML += `
      <div style="display:flex;align-items:center;gap:0.5rem;padding:0.75rem;background:#f0fdf4;border:2px solid var(--success-color);border-radius:6px;">
        <i class="fas fa-check-square" style="color:var(--success-color);"></i>
        <span>${organ}</span>
      </div>
    `;
  });
}

// Dashboard Overview Update Functions
function updateDashboardOverview() {
  // Update organs count
  const organsCount = donorData.consent.organs.length;
  const organsCountEl = document.getElementById('dashboardOrgansCount');
  if (organsCountEl) {
    organsCountEl.textContent = `${organsCount} Organ${organsCount !== 1 ? 's' : ''}`;
  }
  
  // Update full body status
  const fullBodyEl = document.getElementById('dashboardFullBody');
  if (fullBodyEl) {
    fullBodyEl.innerHTML = donorData.consent.fullBody 
      ? '<i class="fas fa-check"></i> Yes' 
      : '<i class="fas fa-times"></i> No';
  }
  
  // Update full body note visibility
  const fullBodyNote = document.getElementById('dashboardFullBodyNote');
  if (fullBodyNote) {
    fullBodyNote.style.display = donorData.consent.fullBody ? 'block' : 'none';
  }
  
  // Update organ summary
  updateDashboardOrganSummary();
  
  // Update custodians count
  const custodiansCount = donorData.custodians.length;
  const custodiansCountEl = document.getElementById('dashboardCustodiansCount');
  if (custodiansCountEl) {
    custodiansCountEl.textContent = `${custodiansCount} Assigned`;
  }
  
  // Update custodian cards in dashboard
  updateDashboardCustodianCards();
}

function updateDashboardOrganSummary() {
  const summaryContainer = document.getElementById('dashboardOrganSummary');
  if (!summaryContainer) return;
  
  summaryContainer.innerHTML = '';
  
  donorData.consent.organs.forEach(organ => {
    summaryContainer.innerHTML += `
      <div class="organ-badge">
        <span>${organ}</span>
        <span class="organ-status">✓</span>
      </div>
    `;
  });
}

function updateDashboardCustodianCards() {
  const container = document.getElementById('dashboardCustodianCards');
  if (!container) return;
  
  container.innerHTML = '';
  
  donorData.custodians.forEach((custodian) => {
    const initials = custodian.name.split(' ').map(n => n[0]).join('').toUpperCase();
    
    let badgeIcon = '<i class="fas fa-user"></i>';
    let badgeText = custodian.type;
    let badgeColor = 'var(--primary-color)';
    
    if (custodian.type === 'Primary') {
      badgeIcon = '<i class="fas fa-crown"></i>';
      badgeColor = 'var(--success-color)';
    } else if (custodian.type === 'Secondary') {
      badgeIcon = '<i class="fas fa-shield-alt"></i>';
      badgeColor = 'var(--primary-color)';
    } else if (custodian.type === 'Legal') {
      badgeIcon = '<i class="fas fa-gavel"></i>';
      badgeColor = 'var(--warning-color)';
    }
    
    const status = (custodian.status || 'Accepted');
    const statusColor = status === 'Pending' ? 'var(--warning-color)' : 'var(--success-color)';
    container.innerHTML += `
      <div class="family-card">
        <div style="padding: 1.25rem 1.5rem; display: flex; gap: 1.25rem; align-items: center; position: relative; z-index: 2;">
          <div class="family-avatar">${initials}</div>
          <div class="family-info">
            <div class="family-name">${custodian.name}</div>
            <div class="family-relation">Relationship: <strong>${custodian.relationship}</strong></div>
            <div class="family-contact">
              <div><i class="fas fa-phone"></i> ${custodian.contact}</div>
              <div><i class="fas fa-envelope"></i> ${custodian.email}</div>
            </div>
            <div style="margin-top:0.75rem; display:flex; gap:0.5rem; flex-wrap:wrap;">
              <span class="status-badge" style="background:${badgeColor};">${badgeIcon} ${badgeText} Custodian</span>
              <span class="status-badge" style="background:${statusColor};"><i class="fas ${status === 'Pending' ? 'fa-hourglass-half' : 'fa-check'}"></i> ${status}</span>
            </div>
          </div>
        </div>
      </div>
    `;
  });
}

function updateCustodianDashboardCards() {
  // Find the custodian dashboard custodian grid container
  const custodianDashboard = document.querySelector('#custodian-dashboard .custodian-grid');
  if (!custodianDashboard) return;
  
  custodianDashboard.innerHTML = '';
  
  donorData.custodians.forEach((custodian) => {
    const initials = custodian.name.split(' ').map(n => n[0]).join('').toUpperCase();
    
    let badgeIcon = '<i class="fas fa-user"></i>';
    let badgeText = custodian.type;
    let badgeColor = 'var(--primary-color)';
    
    if (custodian.type === 'Primary') {
      badgeIcon = '<i class="fas fa-crown"></i>';
      badgeColor = 'var(--success-color)';
    } else if (custodian.type === 'Secondary') {
      badgeIcon = '<i class="fas fa-shield-alt"></i>';
      badgeColor = 'var(--primary-color)';
    } else if (custodian.type === 'Legal') {
      badgeIcon = '<i class="fas fa-gavel"></i>';
      badgeColor = 'var(--warning-color)';
    }
    
    const status = (custodian.status || 'Accepted');
    const statusColor = status === 'Pending' ? 'var(--warning-color)' : 'var(--success-color)';
    custodianDashboard.innerHTML += `
      <div class="family-card">
        <div style="padding: 1.25rem 1.5rem; display: flex; gap: 1.25rem; align-items: center; position: relative; z-index: 2;">
          <div class="family-avatar">${initials}</div>
          <div class="family-info">
            <div class="family-name">${custodian.name}</div>
            <div class="family-relation">Relationship: <strong>${custodian.relationship}</strong></div>
            <div class="family-contact">
              <div><i class="fas fa-id-card"></i> NIC: ${custodian.nic}</div>
              <div><i class="fas fa-phone"></i> ${custodian.contact}</div>
              <div><i class="fas fa-envelope"></i> ${custodian.email}</div>
            </div>
            <div style="margin-top:0.75rem; display:flex; gap:0.5rem; flex-wrap:wrap;">
              <span class="status-badge" style="background:${badgeColor};">${badgeIcon} ${badgeText} Custodian</span>
              <span class="status-badge" style="background:${statusColor};"><i class="fas ${status === 'Pending' ? 'fa-hourglass-half' : 'fa-check'}"></i> ${status}</span>
            </div>
          </div>
        </div>
      </div>
    `;
  });
}

// Custodian View Functions
function confirmDeath() {
  const deathDate = document.getElementById('deathDate').value;
  const deathTime = document.getElementById('deathTime').value;
  const deathPlace = document.getElementById('deathPlace').value;
  
  // Validation
  if (!deathDate || !deathTime || !deathPlace) {
    showNotification('⚠️ Please fill in all required fields', 'warning');
    return;
  }
  
  // Validate date is not in the future
  const selectedDateTime = new Date(deathDate + ' ' + deathTime);
  const now = new Date();
  
  if (selectedDateTime > now) {
    showNotification('⚠️ Death date and time cannot be in the future', 'warning');
    return;
  }
  
  // Validate date is not too far in the past (e.g., more than 30 days)
  const thirtyDaysAgo = new Date();
  thirtyDaysAgo.setDate(thirtyDaysAgo.getDate() - 30);
  
  if (selectedDateTime < thirtyDaysAgo) {
    if (!confirm('The death date is more than 30 days ago. Are you sure this is correct?')) {
      return;
    }
  }
  
  showNotification('⚠️ Confirming death status...', 'warning');
  setTimeout(() => {
    showNotification('✅ Death confirmed. Coordination workflow activated!', 'success');
    addActivityLog('Death Confirmed', 'Death confirmed by custodian - workflow activated');
    
    // Update user status
    document.getElementById('userStatus').innerHTML = '<i class="fas fa-skull"></i> Deceased';
    document.getElementById('userStatus').classList.add('deceased');
    
    // Show coordination sections
    showSection('documents');
  }, 2000);
}

function callHospital(hospitalName, contactPerson) {
  showNotification('📞 Calling ' + contactPerson + ' at ' + hospitalName + '...', 'info');
  setTimeout(() => {
    showNotification('✅ Call completed. Coordination in progress.', 'success');
    addActivityLog('Hospital Contacted', hospitalName + ' - ' + contactPerson);
  }, 2000);
}

function updateHospitalStatus(organ, hospital) {
  showNotification('📝 Updating status for ' + organ + ' at ' + hospital + '...', 'info');
  setTimeout(() => {
    showNotification('✅ Status updated successfully!', 'success');
    addActivityLog('Status Updated', organ + ' - ' + hospital);
  }, 1500);
}

function viewHospitalDetails(hospitalName) {
  showNotification('👁️ Viewing details for ' + hospitalName + '...', 'info');
  setTimeout(() => {
    showNotification('📋 Hospital details displayed', 'success');
  }, 1000);
}

function sendReminders() {
  showNotification('📧 Sending reminders to all hospitals...', 'info');
  setTimeout(() => {
    showNotification('✅ Reminders sent to all hospitals!', 'success');
    addActivityLog('Reminders Sent', 'All hospitals notified');
  }, 2000);
}

function coordinateTransfer(schoolName) {
  showNotification('🤝 Coordinating transfer with ' + schoolName + '...', 'info');
  setTimeout(() => {
    showNotification('✅ Transfer coordination initiated!', 'success');
    addActivityLog('Transfer Coordinated', schoolName);
  }, 2000);
}

function viewSchoolDetails(school) {
  showNotification('👁️ Viewing details for ' + school + '...', 'info');
  setTimeout(() => {
    showNotification('📋 School details displayed', 'success');
  }, 1000);
}

function viewDocument(docType) {
  showNotification('📄 Opening ' + docType + '...', 'info');
  addActivityLog('Document Viewed', docType);
}

function viewConsentVersion(version) {
  showNotification('📋 Opening consent version: ' + version, 'info');
  addActivityLog('Consent Version Viewed', version);
}

function uploadDocument() {
  const docType = document.querySelector('#uploadModal select').value;
  const fileInput = document.querySelector('#uploadModal input[type="file"]');
  
  if (!fileInput.files.length) {
    showNotification('⚠️ Please select a file to upload', 'warning');
    return;
  }
  
  closeModal('uploadModal');
  showNotification('✅ ' + docType + ' uploaded successfully', 'success');
  addActivityLog('Document Uploaded', docType + ' - ' + fileInput.files[0].name);
}

function viewLegalInfo() {
  showNotification('📜 Opening legal information...', 'info');
  addActivityLog('Legal Info Viewed', 'Human Tissue Transplantation Act');
}

function updatePassword() {
  const currentPwd = document.querySelector('#settings input[type="password"]').value;
  if (!currentPwd) {
    showNotification('⚠️ Please enter your current password', 'warning');
    return;
  }
  showNotification('✅ Password updated successfully!', 'success');
  addActivityLog('Password Changed', 'Security update');
}

function toggleNotification(type, enabled) {
  const status = enabled ? 'enabled' : 'disabled';
  showNotification('✅ ' + type.toUpperCase() + ' notifications ' + status, 'success');
  addActivityLog('Notification Settings', type + ' ' + status);
}

function downloadMyData() {
  showNotification('📦 Preparing your data export...', 'info');
  addActivityLog('Data Export Requested', 'GDPR compliance');
  
  setTimeout(() => {
    showNotification('✅ You will receive an email with download link within 24 hours', 'success');
  }, 2000);
}

function viewPrivacyPolicy() {
  showNotification('📜 Opening Privacy Policy...', 'info');
  window.open('https://lifeconnect.lk/privacy', '_blank');
}

function deactivateAccount() {
  if (confirm('⚠️ WARNING: Deactivate your donor registration?\n\nThis will:\n- Remove you from active donor list\n- Notify your family custodians\n- Archive your records\n\nYou can re-register later.\n\nContinue?')) {
    if (confirm('⚠️ FINAL CONFIRMATION\n\nAre you absolutely sure you want to deactivate?')) {
      showNotification('✅ Account deactivated', 'success');
      addActivityLog('Account Deactivated', 'User requested deactivation');
      
      setTimeout(() => {
        alert('Thank you for considering organ donation. Your account has been deactivated.\n\nYou can reactivate anytime by logging in again.');
      }, 1500);
    }
  }
}

function showNotification(message, type = 'success') {
  const notification = document.createElement('div');
  notification.style.cssText = `
    position: fixed;
    top: 20px;
    right: 20px;
    background: ${type === 'success' ? '#10b981' : type === 'warning' ? '#f59e0b' : '#3b82f6'};
    color: white;
    padding: 1rem 1.5rem;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    z-index: 10000;
    animation: slideIn 0.3s ease-out;
    max-width: 400px;
    font-weight: 600;
  `;
  notification.textContent = message;
  document.body.appendChild(notification);
  
  setTimeout(() => {
    notification.style.animation = 'slideOut 0.3s ease-in';
    setTimeout(() => notification.remove(), 300);
  }, 3000);
}

function addActivityLog(action, description) {
  const timestamp = new Date().toLocaleString('en-US', {
    month: 'short',
    day: 'numeric',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
  
  console.log(`[${timestamp}] ${action}: ${description}`);
}

function openModal(id) {
  document.getElementById(id).classList.add('active');
}

function closeModal(id) {
  document.getElementById(id).classList.remove('active');
}

// Close modals when clicking outside
document.querySelectorAll('.modal').forEach(modal => {
  modal.addEventListener('click', function(e) {
    if (e.target === this) {
      this.classList.remove('active');
    }
  });
});

// Add CSS for notifications animation
const style = document.createElement('style');
style.textContent = `
  @keyframes slideIn {
    from {
      transform: translateX(400px);
      opacity: 0;
    }
    to {
      transform: translateX(0);
      opacity: 1;
    }
  }
  @keyframes slideOut {
    from {
      transform: translateX(0);
      opacity: 1;
    }
    to {
      transform: translateX(400px);
      opacity: 0;
    }
  }
`;
document.head.appendChild(style);

// ===== VALIDATION HELPER FUNCTIONS =====

// Validate Sri Lankan NIC
function validateNIC(nic) {
  const nicPattern = /^(\d{9}[VvXx]|\d{12})$/;
  return nicPattern.test(nic);
}

// Validate phone number
function validatePhone(phone) {
  const phonePattern = /^(\+94|0)?[0-9\s]{9,12}$/;
  return phonePattern.test(phone);
}

// Validate email
function validateEmail(email) {
  const emailPattern = /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/;
  return emailPattern.test(email.toLowerCase());
}

// Add real-time validation to input fields
function addInputValidation(inputId, validationType) {
  const input = document.getElementById(inputId);
  if (!input) return;
  
  input.addEventListener('blur', function() {
    const value = this.value.trim();
    if (!value) return; // Skip validation if empty
    
    let isValid = false;
    
    switch(validationType) {
      case 'nic':
        isValid = validateNIC(value);
        break;
      case 'phone':
        isValid = validatePhone(value);
        break;
      case 'email':
        isValid = validateEmail(value);
        break;
      case 'name':
        isValid = /^[A-Za-z\s.]+$/.test(value) && value.length >= 3;
        break;
    }
    
    if (isValid) {
      this.classList.remove('error');
      this.classList.add('success');
    } else {
      this.classList.remove('success');
      this.classList.add('error');
    }
  });
  
  input.addEventListener('input', function() {
    this.classList.remove('error', 'success');
  });
}

// Initialize on load
document.addEventListener('DOMContentLoaded', function() {
  console.log('LifeConnect Dashboard Initialized');
  
  // Initialize dashboard overview with current data
  updateDashboardOverview();
  
  // Initialize My Donations display
  syncConsentToMyDonations();
  
  // Initialize Digital Donor Card with current organs
  updateDigitalDonorCard();
  
  // Initialize Custodian Dashboard cards
  updateCustodianDashboardCards();
  
  // Setup real-time validation for forms
  // Family Custodian Form
  addInputValidation('custodianName', 'name');
  addInputValidation('custodianNIC', 'nic');
  addInputValidation('custodianPhone', 'phone');
  addInputValidation('custodianEmail', 'email');
  
  // Legal Custodian Form
  addInputValidation('legalCustodianName', 'name');
  addInputValidation('legalCustodianNIC', 'nic');
  addInputValidation('legalCustodianPhone', 'phone');
  addInputValidation('legalCustodianEmail', 'email');
  
  // Profile Contact Info
  addInputValidation('profilePhone', 'phone');
  addInputValidation('profileEmail', 'email');
  
  showNotification('👋 Welcome to LifeConnect!', 'info');
});
</script>
</body>
</html>
