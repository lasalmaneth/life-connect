<div id="lab-reports" class="content-section" style="display: none;">
                    <div class="content-header">
                        <h2>Schedule Appointments</h2>
                        <p>View and manage upcoming donor appointments scheduled for your hospital.</p>
                    </div>
                    <div class="content-body">
                        <div class="action-section">
                            <div class="action-buttons">
                                <button class="btn btn-primary" onclick="openLabReportModal()">Schedule an Appointment</button>
                                <button class="btn btn-secondary" type="button" id="lab-requested-tab" onclick="setLabAppointmentsView('requested')">Requested Appointments</button>
                                <button class="btn btn-secondary" type="button" id="lab-aftercare-requested-tab" onclick="setLabAppointmentsView('aftercare_requested')">Aftercare Requested Appointments</button>
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 440px; gap: 1.75rem; margin-bottom: 1.5rem; align-items: start;">
                            <!-- Left: Tab content panels -->
                            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                                <div id="lab-scheduled-wrap">
                                    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                                        <div style="background: var(--white-color); border: 1px solid var(--border-color); border-radius: 14px; padding: 1.25rem;">
                                            <h3 style="margin-top: 0; margin-bottom: 1rem; font-size: 1.05rem; font-weight: 800; color: var(--primary-text-color);">Select Donor</h3>
                                            <div class="search-bar" style="margin-bottom: 1.25rem;">
                                                <span class="search-icon">🔍</span>
                                                <input type="text" class="search-input" id="lab-donor-search"
                                                    placeholder="Search tests or donors..." style="width: 100%; box-sizing: border-box;">
                                            </div>
                                            <div id="lab-donor-tabs" class="lab-tabs vertical" style="display: flex; flex-direction: column; gap: 0.5rem; max-height: 250px; overflow-y: auto; padding-right: 5px;"></div>
                                        </div>

                                        <div style="background: var(--white-color); border: 1px solid var(--border-color); border-radius: 14px; padding: 1.25rem;">
                                            <div id="lab-cal-details" class="cal-details" style="margin-top: 0;"></div>
                                        </div>

                                        <div class="data-table">
                                            <div class="table-header">
                                                <h4>Upcoming Appointments</h4>
                                            </div>
                                            <div class="table-content" id="lab-reports-table">
                                                <div class="table-row" style="font-weight: 600; background: var(--gray-bg-color);">
                                                    <div class="table-cell">Patient ID</div>
                                                    <div class="table-cell">Donor NIC</div>
                                                    <div class="table-cell">Donor Name</div>
                                                    <div class="table-cell">Test Type</div>
                                                    <div class="table-cell">Test Date</div>
                                                    <div class="table-cell">Result Status</div>
                                                    <div class="table-cell">Actions</div>
                                                </div>
                                                <!-- Content populated by JS -->
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="lab-requested-wrap" style="display: none;">
                                    <div class="data-table">
                                        <div class="table-header">
                                            <h4>Requested Appointments</h4>
                                        </div>
                                        <div class="table-content" id="lab-requested-table">
                                            <div class="table-row" style="font-weight: 600; background: var(--gray-bg-color);">
                                                <div class="table-cell">Patient ID</div>
                                                <div class="table-cell">Donor NIC</div>
                                                <div class="table-cell">Donor Name</div>
                                                <div class="table-cell">Test Type</div>
                                                <div class="table-cell">Current Date</div>
                                                <div class="table-cell">Requested Date</div>
                                                <div class="table-cell">View more details</div>
                                                <div class="table-cell">Actions</div>
                                            </div>
                                            <!-- Content populated by JS -->
                                        </div>
                                    </div>
                                </div>

                                <div id="lab-aftercare-requested-wrap" style="display: none;">
                                    <div class="data-table">
                                        <div class="table-header">
                                            <h4>Aftercare Requested Appointments</h4>
                                        </div>
                                        <div class="table-content" id="aftercare-requested-table">
                                            <div class="table-row" style="font-weight: 600; background: var(--gray-bg-color);">
                                                <div class="table-cell">Patient NIC</div>
                                                <div class="table-cell">Patient Name</div>
                                                <div class="table-cell">Appointment Type</div>
                                                <div class="table-cell">Requested Date</div>
                                                <div class="table-cell">Description</div>
                                                <div class="table-cell">Actions</div>
                                            </div>
                                            <!-- Content populated by JS -->
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right: Calendar (always visible) -->
                            <div class="cal-wrap" aria-label="Appointment calendar" style="width: 100%; margin: 0; box-sizing: border-box; position: sticky; top: 1.5rem;">
                                <div class="cal-nav">
                                    <button type="button" class="cal-nav-btn" aria-label="Previous month" onclick="labCalPrev()">‹</button>
                                    <h3 id="lab-cal-title">—</h3>
                                    <button type="button" class="cal-nav-btn" aria-label="Next month" onclick="labCalNext()">›</button>
                                </div>
                                <div class="cal-grid" id="lab-cal-grid"></div>
                            </div>
                        </div>
                    </div>
                </div>