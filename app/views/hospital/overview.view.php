<div id="overview" class="content-section" style="display: block;">
                    <div class="content-header">
                        <h2>Hospital Overview</h2>
                        <p>Monitor organ requests, donor eligibility, and aftercare management.</p>
                    </div>
                    <div class="content-body">
                        <!-- DYNAMIC URGENT ALERTS BANNER -->
                        <?php if ($stats['pending_requests'] > 0): ?>
                        <div class="urgent-alert-banner"
                            style="background: linear-gradient(90deg, #fff3cd 0%, #fff8e1 100%); border-left: 4px solid #ffc107; padding: 1rem 1.5rem; border-radius: 8px; margin-bottom: 2rem; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 4px 15px rgba(255, 193, 7, 0.1);">
                            <div style="display: flex; align-items: center; gap: 1rem;">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#d39e00"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path
                                        d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z">
                                    </path>
                                    <line x1="12" y1="9" x2="12" y2="13"></line>
                                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                                </svg>
                                <div>
                                    <h4 style="margin: 0; color: #856404; font-size: 1rem;">[URGENT] <?php echo $stats['pending_requests']; ?> Perfect Match
                                        Pending Review</h4>
                                    <p style="margin: 0.25rem 0 0; color: #664d03; font-size: 0.9rem;">Urgent screening results are available for pending organ requests.</p>
                                </div>
                            </div>
                            <button onclick="showContent('eligibility')"
                                style="background: #ffc107; color: #000; border: none; padding: 0.5rem 1rem; border-radius: 6px; font-weight: 600; cursor: pointer; transition: all 0.2s; box-shadow: 0 2px 4px rgba(255,193,7,0.3);">Initiate
                                Transfer</button>
                        </div>
                        <?php endif; ?>
                        <div class="stats-grid">
                            <div class="stat-card">
                                <div class="stat-number"><?php echo $stats['total_organ_requests']; ?></div>
                                <div class="stat-label">Total Organ Requests</div>
                                <div class="stat-change neutral"><?php echo $stats['pending_requests']; ?> pending</div>
                            </div>

                            <div class="stat-card">
                                <div class="stat-number"><?php echo $stats['total_aftercare_recipients']; ?></div>
                                <div class="stat-label">Aftercare Recipients</div>
                                <div class="stat-change positive">Active follow-up</div>
                            </div>

                            <div class="stat-card">
                                <div class="stat-number"><?php echo $stats['total_success_stories']; ?></div>
                                <div class="stat-label">Success Stories</div>
                                <div class="stat-change positive"><?php echo $stats['approved_stories']; ?> approved
                                </div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number"><?php echo $stats['total_appointments']; ?></div>
                                <div class="stat-label">Aftercare Appointments</div>
                                <div class="stat-change positive"><?php echo $stats['scheduled_appointments']; ?>
                                    scheduled</div>
                            </div>
                        </div>


                        <div class="feature-grid">
                            <div class="feature-card" onclick="showContent('organ-requests')" style="cursor: pointer;">
                                <div class="feature-icon"
                                    style="display: flex; align-items: center; justify-content: center; width: 60px; height: 60px; background: rgba(0, 91, 170, 0.1); border-radius: 12px; margin-bottom: 1rem;">
                                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#005baa"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path
                                            d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                                        </path>
                                    </svg>
                                </div>
                                <h3>Organ Requests</h3>
                                <p>Create, edit, and manage urgent organ requests for patient matching.</p>
                            </div>

                            <div class="feature-card" onclick="showContent('eligibility')" style="cursor: pointer;">
                                <div class="feature-icon"
                                    style="display: flex; align-items: center; justify-content: center; width: 60px; height: 60px; background: rgba(0, 91, 170, 0.1); border-radius: 12px; margin-bottom: 1rem;">
                                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#005baa"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                    </svg>
                                </div>
                                <h3>Update Eligibility</h3>
                                <p>Approve or modify a donor's eligibility status after clinical evaluations.</p>
                            </div>

                            <div class="feature-card" onclick="showContent('aftercare-recipients')" style="cursor: pointer;">
                                <div class="feature-icon"
                                    style="display: flex; align-items: center; justify-content: center; width: 60px; height: 60px; background: rgba(0, 91, 170, 0.1); border-radius: 12px; margin-bottom: 1rem;">
                                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#005baa"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="9" cy="7" r="4"></circle>
                                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                    </svg>
                                </div>
                                <h3>Recipient Patients</h3>
                                <p>Manage post-transplant aftercare for patients who received organs.</p>
                            </div>



                            <div class="feature-card" onclick="showContent('stories')" style="cursor: pointer;">
                                <div class="feature-icon"
                                    style="display: flex; align-items: center; justify-content: center; width: 60px; height: 60px; background: rgba(0, 91, 170, 0.1); border-radius: 12px; margin-bottom: 1rem;">
                                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#005baa"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path
                                            d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z">
                                        </path>
                                    </svg>
                                </div>
                                <h3>Success Stories</h3>
                                <p>Approve or share impactful post-transplant recovery stories and tributes.</p>
                            </div>

                            <div class="feature-card" onclick="showContent('lab-reports')" style="cursor: pointer;">
                                <div class="feature-icon"
                                    style="display: flex; align-items: center; justify-content: center; width: 60px; height: 60px; background: rgba(0, 91, 170, 0.1); border-radius: 12px; margin-bottom: 1rem;">
                                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#005baa"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14 2 14 8 20 8"></polyline>
                                        <line x1="16" y1="13" x2="8" y2="13"></line>
                                        <line x1="16" y1="17" x2="8" y2="17"></line>
                                        <polyline points="10 9 9 9 8 9"></polyline>
                                    </svg>
                                </div>
                                <h3>Upcoming Appointments</h3>
                                <p>Upload and analyze biological screening and laboratory test documents.</p>
                            </div>

                            <div class="feature-card" onclick="showContent('test-results')" style="cursor: pointer;">
                                <div class="feature-icon"
                                    style="display: flex; align-items: center; justify-content: center; width: 60px; height: 60px; background: rgba(0, 91, 170, 0.1); border-radius: 12px; margin-bottom: 1rem;">
                                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#005baa"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M6 8v8M10 8v8M14 8v8M18 8v8M6 16h12"></path>
                                        <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                                    </svg>
                                </div>
                                <h3>Test Results</h3>
                                <p>View laboratory test results and screening data for patients.</p>
                            </div>
                        </div>

                        <div class="data-table" style="margin-top: 1.5rem;">
                            <div class="table-header">
                                <h4>Aftercare Support Requests</h4>
                            </div>
                            <div class="table-content">
                                <div class="table-row" style="font-weight: 600; background: var(--gray-bg-color);">
                                    <div class="table-cell">Date Submitted</div>
                                    <div class="table-cell">Patient NIC</div>
                                    <div class="table-cell">Patient Name</div>
                                    <div class="table-cell">Reason</div>
                                    <div class="table-cell">Status</div>
                                    <div class="table-cell">Actions</div>
                                </div>

                                <?php if (!empty($aftercare_support_requests ?? [])): ?>
                                    <?php foreach (($aftercare_support_requests ?? []) as $req): ?>
                                        <?php
                                            $status = strtoupper(trim((string)($req->status ?? 'PENDING')));
                                            $badgeClass = 'status-pending';
                                            if ($status === 'APPROVED') $badgeClass = 'status-success';
                                            if ($status === 'REJECTED') $badgeClass = 'status-danger';
                                            $submitted = $req->submitted_date ?? ($req->created_at ?? '');
                                        ?>
                                        <div class="table-row">
                                            <div class="table-cell" data-label="Date Submitted"><?php echo htmlspecialchars($submitted ? date('d/m/Y', strtotime((string)$submitted)) : '—'); ?></div>
                                            <div class="table-cell" data-label="Patient NIC"><?php echo htmlspecialchars((string)($req->patient_nic ?? '')); ?></div>
                                            <div class="table-cell name" data-label="Patient Name"><?php echo htmlspecialchars((string)($req->patient_name ?? '')); ?></div>
                                            <div class="table-cell" data-label="Reason"><?php echo htmlspecialchars((string)($req->reason ?? '')); ?></div>
                                            <div class="table-cell" data-label="Status"><span class="status-badge <?php echo $badgeClass; ?>"><?php echo htmlspecialchars($status); ?></span></div>
                                            <div class="table-cell" data-label="Actions" style="display:flex; gap:.4rem; flex-wrap:wrap;">
                                                <?php if ($status === 'PENDING'): ?>
                                                    <button class="btn btn-success btn-small" type="button" onclick="approveSupportRequest(<?php echo (int)$req->id; ?>)">Approve</button>
                                                    <button class="btn btn-danger btn-small" type="button" onclick="rejectSupportRequest(<?php echo (int)$req->id; ?>)">Reject</button>
                                                <?php else: ?>
                                                    <span style="color:#6b7280; font-size:.9rem;">—</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="table-row">
                                        <div class="table-cell" style="grid-column:1/-1; text-align:center; padding:20px; color:#999;">No support requests found</div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <form id="supportRequestActionForm" method="POST" style="display:none;">
                            <input type="hidden" name="action" id="supportRequestAction" value="">
                            <input type="hidden" name="support_request_id" id="supportRequestId" value="">
                            <input type="hidden" name="reject_reason" id="supportRequestRejectReason" value="">
                        </form>
                    </div>
                </div>