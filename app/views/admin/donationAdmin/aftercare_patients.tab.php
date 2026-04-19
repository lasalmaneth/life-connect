<!-- Aftercare Patients Section -->
<div id="patients" class="content-section" style="display: none;">
    <div class="content-header">
        <h2>Aftercare Patient Records</h2>
        <p>Monitor and manage post-surgery patient follow-ups and long-term care records.</p>
    </div>
    <div class="content-body">
        <div style="display: flex; gap: 16px; align-items: center; margin-bottom: 24px; padding-top: 2rem;">
            <div class="search-bar" style="margin-bottom: 0; flex: 1;">
                <span class="search-icon">🔍</span>
                <input type="text" class="search-input" placeholder="Search by Patient Name, ID, or Surgery Type..." id="patient-search">
            </div>

            <div class="filter-section" style="margin-bottom: 0; display: flex; gap: 12px;">
                <select class="filter-select" id="patient-type-filter">
                    <option value="">All Patient Types</option>
                    <option value="recipient">Recipient Patient</option>
                    <option value="donor">Post-Donation Patient</option>
                </select>
                <select class="filter-select" id="blood-type-filter">
                    <option value="">All Blood Types</option>
                    <option value="A+">A+</option>
                    <option value="A-">A-</option>
                    <option value="B+">B+</option>
                    <option value="B-">B-</option>
                    <option value="AB+">AB+</option>
                    <option value="AB-">AB-</option>
                    <option value="O+">O+</option>
                    <option value="O-">O-</option>
                </select>
            </div>
        </div>

        <div class="stats-grid dashboard-metrics" style="grid-template-columns: repeat(4, 1fr); margin-bottom: 2rem;">
            <div class="stat-card glass-card">
                <div class="stat-number quick-stat-number" id="tab-total-patients">0</div>
                <div class="stat-label">Total Patients</div>
            </div>
            <div class="stat-card glass-card">
                <div class="stat-number quick-stat-number" id="tab-recipient-patients">0</div>
                <div class="stat-label">Recipient Patients</div>
            </div>
            <div class="stat-card glass-card">
                <div class="stat-number quick-stat-number" id="tab-donor-patients">0</div>
                <div class="stat-label">Post-Donation Patients</div>
            </div>
            <div class="stat-card glass-card">
                <div class="stat-number quick-stat-number" id="tab-average-age">0</div>
                <div class="stat-label">Average Age</div>
            </div>
        </div>

        <div class="data-table">
            <div class="table-header">
                <h4>Aftercare Patient Records</h4>
            </div>
            <div class="table-content" id="patients-table">
                <div class="table-row header-row" style="font-weight: 600; color: #64748b; font-size:0.85rem; text-transform:uppercase; background: #f8fafc; display: grid; grid-template-columns: 2.2fr 1.2fr 1fr 1.5fr 130px; gap: 1rem; padding: 1rem 1.5rem; border-bottom: 1px solid #e5e7eb;">
                    <div class="table-cell">Patient Details</div>
                    <div class="table-cell">Age / Gender</div>
                    <div class="table-cell" style="text-align: center;">Blood Type</div>
                    <div class="table-cell" style="text-align: center;">Patient Category</div>
                    <div class="table-cell" style="text-align: center;">Status</div>
                </div>
                <!-- AJAX will load patient rows here -->
            </div>
        </div>
    </div>
</div>

<!-- Patient Details Modal (Reference Matched Style) -->
<div id="patientModal" class="modal">
    <div class="modal-content" style="max-width: 680px !important;">
        <div class="modal-scroll-area">
            <!-- Modal Header -->
            <div style="display: flex; flex-direction: column; gap: 1.25rem; position: relative;">
                <button class="modal-close" 
                        style="position: absolute; top: -15px; right: -15px; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 50%; background: #f1f5f9; border: none; cursor: pointer; color: #64748b; z-index: 10;" 
                        onclick="closePatientModal()">&times;</button>
                
                <div style="display: flex; align-items: center; gap: 1.25rem;">
                    <div style="flex-shrink: 0; width: 48px; height: 48px; background: #fef3c7; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                        <i class="fa-solid fa-user-nurse" style="font-size: 20px; color: #d97706;"></i>
                    </div>
                    <div>
                        <h2 style="margin: 0; font-size: 1.5rem; font-weight: 800; color: #0f172a; line-height: 1.2;">
                            Patient Profile Review</h2>
                    </div>
                </div>
                <p style="margin: 0; color: #64748b; font-size: 0.9rem; line-height: 1.5; font-weight: 500;">
                    Review the medical history and administrative details for this aftercare patient. Verify registration data before clinical updates.
                </p>

                <!-- Core Details Card -->
                <div class="summary-card">
                    <div>
                        <span class="data-label">Patient Name</span>
                        <div id="modal-patient-name" class="data-value">-</div>
                        <div id="modal-patient-id" class="data-value-sub">-</div>
                    </div>
                    <div>
                        <span class="data-label">Blood Type</span>
                        <div id="modal-patient-bloodtype" class="data-value" style="color: #ef4444;">-</div>
                        <div id="modal-patient-type" class="data-value-sub">-</div>
                    </div>
                    <div>
                        <span class="data-label">Age / Gender</span>
                        <div id="modal-patient-age-gender" class="data-value" style="color: #3b82f6;">-</div>
                        <div id="modal-patient-category-sub" class="data-value-sub">Post-Operative Care</div>
                    </div>
                    <div>
                        <span class="data-label">Current Status</span>
                        <div id="modal-patient-status" class="data-value">-</div>
                    </div>
                </div>

                <!-- Registration & Logistics Section -->
                <div class="section-title">
                    <i class="fa-solid fa-id-card"></i> Administrative Data
                </div>
                <div class="grid-2" style="gap: 1.5rem 2rem;">
                    <div>
                        <span class="data-label">National ID (NIC)</span>
                        <div id="modal-patient-nic" class="data-value" style="font-weight: 600;">-</div>
                    </div>
                    <div>
                        <span class="data-label">Assigned Hospital</span>
                        <div id="modal-patient-hosp" class="data-value" style="font-weight: 600;">-</div>
                    </div>
                    <!-- Surgery Details (Recipients only) -->
                    <div id="modal-surgery-type-section">
                        <span class="data-label">Surgery Type</span>
                        <div id="modal-patient-surgery-type" class="data-value" style="font-weight: 600;">-</div>
                    </div>
                    <div id="modal-surgery-date-section">
                        <span class="data-label">Surgery Date</span>
                        <div id="modal-patient-surgery-date" class="data-value" style="font-weight: 600;">-</div>
                    </div>
                </div>

                <!-- Contact & Medical Notes (Recipients only) -->
                <div id="modal-extended-details">
                    <div class="section-title">
                        <i class="fa-solid fa-notes-medical"></i> Clinical Records
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        <div style="background: #f8fafc; padding: 1.25rem; border-radius: 12px; border: 1px solid #e2e8f0;">
                            <span class="data-label" style="margin-bottom: 0.5rem; display: block;">Medical History / Notes</span>
                            <div id="modal-patient-medical" style="font-size: 0.9rem; color: #1e293b; line-height: 1.6; font-weight: 500;">-</div>
                        </div>
                        <div style="background: #f8fafc; padding: 1.25rem; border-radius: 12px; border: 1px solid #e2e8f0;">
                            <span class="data-label" style="margin-bottom: 0.5rem; display: block;">Contact Details</span>
                            <div id="modal-patient-contact" style="font-size: 0.9rem; color: #1e293b; line-height: 1.6; font-weight: 500;">-</div>
                        </div>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 1rem; padding-top: 1.5rem; border-top: 2px solid #f1f5f9;">
                    <button onclick="closePatientModal();" class="btn btn-secondary" style="border-radius: 10px; padding: 0.75rem 1.5rem; font-weight: 700; cursor: pointer;">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
