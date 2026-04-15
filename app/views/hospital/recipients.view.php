<div id="recipients" class="content-section" style="display: none;">
                    <div class="content-header">
                        <h2>Recipient Patient Management</h2>
                        <p>Add, update, and view recipient patient records and treatment logs.</p>
                    </div>
                    <div class="content-body">
                        <div class="action-section"
                            style="position: relative; overflow: hidden; display: flex; justify-content: space-between; align-items: center;">
                            <div style="position: relative; z-index: 2;">
                                <h3>Patient Actions</h3>
                                <div class="action-buttons">
                                    <button class="btn btn-primary" onclick="openRecipientModal()">Add
                                        Recipient</button>
                                    <button class="btn btn-secondary" onclick="exportRecipients()">Export
                                        Records</button>
                                </div>
                            </div>

                            <!-- Decorative Medical Background Illustrations -->
                            <div
                                style="display: flex; gap: 10px; align-items: center; position: absolute; right: 20px; top: -50px; opacity: 0.1; pointer-events: none;">
                                <svg width="200" height="200" viewBox="0 0 24 24" fill="none" stroke="#005baa"
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M22 12h-4l-3 9L9 3l-3 9H2"></path>
                                </svg>
                                <svg width="180" height="180" viewBox="0 0 24 24" fill="none" stroke="#005baa"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    style="margin-left: -50px; transform: translateY(10px);">
                                    <path
                                        d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                                    </path>
                                </svg>
                            </div>
                        </div>

                        <div class="search-bar">
                            <span class="search-icon">Search:</span>
                            <input type="text" id="recipient-search" class="search-input"
                                placeholder="Search by recipient name, NIC, or ID..." onkeyup="applyRecipientFilters()">
                        </div>

                        <div class="data-table">
                            <div class="table-header">
                                <h4>Recipient Patients</h4>
                            </div>
                            <div class="table-content" id="recipients-table">
                                <div class="table-row" style="font-weight: 600; background: var(--gray-bg-color);">
                                    <div class="table-cell">Patient NIC</div>
                                    <div class="table-cell">Patient Name</div>
                                    <div class="table-cell">Organ Received</div>
                                    <div class="table-cell">Surgery Date</div>
                                    <div class="table-cell">Status</div>
                                    <div class="table-cell">Actions</div>
                                </div>
                                <!-- Content populated by JS -->
                            </div>
                        </div>
                    </div>
                </div>