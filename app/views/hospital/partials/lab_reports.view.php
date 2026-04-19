<div id="lab-reports" class="content-section"
    style="<?php echo (isset($initialSection) && $initialSection === 'lab-reports') ? 'display:block' : 'display:none'; ?>">

    <!-- 1. Performance Summary (Top Context) -->
    <div
        style="padding: 25px 30px; background: #f8fafc; border-bottom: 1px solid #e2e8f0; border-radius: 16px 16px 0 0;">
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px;">
            <div
                style="background: white; padding: 20px; border-radius: 16px; border: 1px solid #e2e8f0; border-bottom: 3px solid #2563eb; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                    <div
                        style="width: 36px; height: 36px; background: #eff6ff; color: #2563eb; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-users-viewfinder"></i>
                    </div>
                    <span style="font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase;">Total
                        Screening</span>
                </div>
                <div style="font-size: 1.5rem; font-weight: 900; color: #0f172a;" id="stat-total-screening">0</div>
            </div>
            <div
                style="background: white; padding: 20px; border-radius: 16px; border: 1px solid #e2e8f0; border-bottom: 3px solid #f59e0b; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                    <div
                        style="width: 36px; height: 36px; background: #fffbeb; color: #d97706; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-clock"></i>
                    </div>
                    <span
                        style="font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase;">Pending
                        Acceptance</span>
                </div>
                <div style="font-size: 1.5rem; font-weight: 900; color: #0f172a;" id="stat-pending-tests">0</div>
            </div>
            <div
                style="background: white; padding: 20px; border-radius: 16px; border: 1px solid #e2e8f0; border-bottom: 3px solid #10b981; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                    <div
                        style="width: 36px; height: 36px; background: #ecfdf5; color: #10b981; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <span style="font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase;">Tests
                        Today</span>
                </div>
                <div style="font-size: 1.5rem; font-weight: 900; color: #0f172a;" id="stat-tests-today">0</div>
            </div>
            <div
                style="background: white; padding: 20px; border-radius: 16px; border: 1px solid #e2e8f0; border-bottom: 3px solid #7c3aed; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                    <div
                        style="width: 36px; height: 36px; background: #f5f3ff; color: #7c3aed; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-clipboard-check"></i>
                    </div>
                    <span
                        style="font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase;">Completed
                        Overall</span>
                </div>
                <div style="font-size: 1.5rem; font-weight: 900; color: #0f172a;" id="stat-completed-overall">0</div>
            </div>
        </div>
    </div>

    <!-- 2. Section Header & Controls (Middle Logic) -->
    <div
        style="padding: 25px 30px; background: white; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
        <div style="display: flex; align-items: center; gap: 20px;">
            <button onclick="hcShowSection('overview', this)"
                style="background: white; border: 1.5px solid #e2e8f0; width: 40px; height: 40px; border-radius: 10px; color: #64748b; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s;">
                <i class="fas fa-arrow-left"></i>
            </button>
            <div>
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 4px;">
                    <div
                        style="width: 32px; height: 32px; background: rgba(37, 99, 235, 0.1); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #2563eb;">
                        <i class="fas fa-microscope" style="font-size: 0.9rem;"></i>
                    </div>
                    <h2 id="lab-section-title" style="margin: 0; font-size: 1.4rem; color: #0f172a; font-weight: 800;">
                        Organ Testing</h2>
                </div>
                <p style="margin: 0; color: #64748b; font-size: 0.8rem; font-weight: 500;">Clinical screening and donor
                    compatibility management.</p>
            </div>
        </div>

        <div style="display: flex; align-items: center; gap: 15px;">
            <div
                style="display: flex; background: #f1f5f9; padding: 4px; border-radius: 12px; border: 1px solid #e2e8f0;">
                <button class="lab-nav-btn active" onclick="switchLabTab('active-screening', this)">Basic Organ
                    Testing</button>
                <button class="lab-nav-btn" onclick="switchLabTab('advanced-requests', this)">Advanced Organ
                    Testing</button>
            </div>
        </div>
        <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 15px;">
            <div style="position: relative; width: 300px;">
                <i class="fas fa-search"
                    style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.8rem;"></i>
                <input type="text" id="lab-main-search" placeholder="Search records by name or NIC..."
                    oninput="filterLabTables()"
                    style="width: 100%; padding: 10px 15px 10px 40px; border-radius: 10px; border: 1px solid #e2e8f0; outline: none; font-size: 0.85rem; font-weight: 500;">
            </div>

            <div id="basic-screening-actions">
                <button class="btn btn-primary" onclick="openLabReportModal('basic')"
                    style="background: #2563eb; color: white; border: none; padding: 12px 24px; border-radius: 12px; font-weight: 700; font-size: 0.85rem; cursor: pointer; display: flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2); transition: all 0.2s;">
                    <i class="fas fa-plus-circle"></i> Schedule Basic Testings
                </button>
            </div>
            <div id="advanced-testing-actions" style="display: none;">
                <button class="btn btn-primary" onclick="openLabReportModal('advanced')"
                    style="background: #7c3aed; color: white; border: none; padding: 12px 24px; border-radius: 12px; font-weight: 700; font-size: 0.85rem; cursor: pointer; display: flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(124, 58, 237, 0.2); transition: all 0.2s;">
                    <i class="fas fa-microscope"></i> Schedule Advanced Testing
                </button>
            </div>
        </div>
    </div>

    <!-- 3. Work Area (Bottom Content) -->
    <div class="content-body" style="padding: 25px 30px; background: #f8fafc; border-radius: 0 0 16px 16px;">

        <div id="tab-content-container">
            <!-- Basic Organ Testing Tab -->
            <div id="active-screening" class="lab-tab-content active">
                <div
                    style="background: white; border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.03);">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #f8fafc; border-bottom: 2px solid #f1f5f9;">
                                <th
                                    style="padding: 18px 25px; text-align: left; font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase;">
                                    Donor Details</th>
                                <th
                                    style="padding: 18px 25px; text-align: left; font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase;">
                                    NIC Number</th>
                                <th
                                    style="padding: 18px 25px; text-align: left; font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase;">
                                    Organ</th>
                                <th
                                    style="padding: 18px 25px; text-align: left; font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase;">
                                    Test Type</th>
                                <th
                                    style="padding: 18px 25px; text-align: left; font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase;">
                                    Status</th>
                                <th
                                    style="padding: 18px 25px; text-align: right; font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase;">
                                    Action</th>
                            </tr>
                        </thead>
                        <tbody id="lab-members-body">
                            <!-- Populated by JS -->
                        </tbody>
                    </table>
                    <div id="lab-empty-state" style="padding: 60px; text-align: center; display: none;">
                        <div
                            style="width: 60px; height: 60px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; color: #cbd5e1;">
                            <i class="fas fa-microscope" style="font-size: 1.5rem;"></i>
                        </div>
                        <h3 style="color: #1e293b; font-size: 1.1rem; font-weight: 800; margin-bottom: 8px;">No
                            Screening Activities</h3>
                        <p style="color: #64748b; font-size: 0.85rem; max-width: 400px; margin: 0 auto;">Active tests
                            and compatibility screenings for matched donors will appear here.</p>
                    </div>
                </div>
            </div>

            <!-- Advanced Organ Testing Tab -->
            <div id="advanced-requests" class="lab-tab-content" style="display: none;">
                <div
                    style="background: white; border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.03);">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #f8fafc; border-bottom: 2px solid #f1f5f9;">
                                <th
                                    style="padding: 18px 25px; text-align: left; font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase;">
                                    Eligible Donor</th>
                                <th
                                    style="padding: 18px 25px; text-align: left; font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase;">
                                    NIC</th>
                                <th
                                    style="padding: 18px 25px; text-align: left; font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase;">
                                    Organs</th>
                                <th
                                    style="padding: 18px 25px; text-align: left; font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase;">
                                    Status</th>
                                <th
                                    style="padding: 18px 25px; text-align: right; font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase;">
                                    Action</th>
                            </tr>
                        </thead>
                        <tbody id="lab-advanced-body">
                            <!-- Populated by JS -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <!-- Lab Specific Drawer -->
    <div class="cp-drawer-overlay" id="lab-drawer-overlay" onclick="closeLabDrawer()"
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(4px); z-index: 9998; align-items: center; justify-content: flex-end;">
    </div>
    <div class="cp-drawer" id="lab-drawer"
        style="position: fixed; top: 0; right: 0; width: 500px; height: 100vh; background: white; z-index: 9999; transform: translateX(100%); transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); box-shadow: -10px 0 30px rgba(0,0,0,0.1); display: flex; flex-direction: column;">
        <div class="cp-drawer__header"
            style="padding: 25px 30px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; background: #f8fafc;">
            <div>
                <h2 class="cp-drawer__title" id="drawer-donor-name"
                    style="margin: 0; font-size: 1.25rem; font-weight: 800; color: #0f172a;">Donor Screening</h2>
                <div id="drawer-donor-nic"
                    style="font-size: 0.8rem; color: #64748b; font-weight: 600; margin-top: 4px;"></div>
            </div>
            <button class="cp-drawer__close" onclick="closeLabDrawer()"
                style="border: none; background: #e2e8f0; width: 32px; height: 32px; border-radius: 8px; color: #64748b; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">&times;</button>
        </div>
        <div class="cp-drawer__body" style="flex: 1; overflow-y: auto; padding: 30px;">
            <div
                style="background: #eff6ff; padding: 20px; border-radius: 16px; margin-bottom: 25px; border: 1px solid #dbeafe;">
                <div
                    style="font-size: 0.7rem; font-weight: 800; color: #2563eb; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 0.05em;">
                    Appointment Context</div>
                <div id="drawer-organ-type"
                    style="font-weight: 800; color: #1e3a8a; font-size: 1.1rem; margin-bottom: 4px;"></div>
                <div id="drawer-test-type" style="font-weight: 700; color: #475569; font-size: 0.9rem;"></div>
            </div>

            <!-- Biological Profile (Shown for Advanced) -->
            <div id="drawer-bio-profile" style="display: none; grid-template-columns: repeat(3, 1fr); gap: 15px; margin-bottom: 25px;">
                <div style="background: white; padding: 12px; border: 1px solid #f1f5f9; border-radius: 12px; text-align: center;">
                    <div style="font-size: 0.65rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 4px;">Blood Group</div>
                    <div id="drawer-bio-blood" style="font-weight: 800; color: #1e293b; font-size: 1rem;">-</div>
                </div>
                <div style="background: white; padding: 12px; border: 1px solid #f1f5f9; border-radius: 12px; text-align: center;">
                    <div style="font-size: 0.65rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 4px;">Age</div>
                    <div id="drawer-bio-age" style="font-weight: 800; color: #1e293b; font-size: 1rem;">-</div>
                </div>
                <div style="background: white; padding: 12px; border: 1px solid #f1f5f9; border-radius: 12px; text-align: center;">
                    <div style="font-size: 0.65rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 4px;">Gender</div>
                    <div id="drawer-bio-gender" style="font-weight: 800; color: #1e293b; font-size: 1rem;">-</div>
                </div>
            </div>

            <div style="margin-bottom: 30px;">
                <h4
                    style="font-size: 0.85rem; font-weight: 800; color: #0f172a; margin: 0 0 15px; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-vial" style="color: #64748b;"></i> Included Diagnostics
                </h4>
                <div id="drawer-test-description"
                    style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 15px; font-size: 0.85rem; color: #475569; line-height: 1.6; font-style: italic;">
                    <!-- Populated by JS -->
                </div>
            </div>

            <div id="drawer-actions"
                style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #f1f5f9; display: flex; gap: 12px;">
                <!-- Buttons populated by JS -->
            </div>
        </div>
    </div>
</div>

<style>
    .lab-nav-btn {
        padding: 8px 16px;
        border: none;
        background: transparent;
        color: #64748b;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.8rem;
        cursor: pointer;
        transition: all 0.2s;
    }

    .lab-nav-btn.active {
        background: white;
        color: #2563eb;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06);
    }

    .status-pill {
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 0.7rem;
        font-weight: 800;
        text-transform: uppercase;
    }

    .status-pending {
        background: #ffffff;
        color: #64748b;
        border: 1px solid #e2e8f0;
    }

    .status-scheduled {
        background: #eff6ff;
        color: #2563eb;
        border: 1px solid #dbeafe;
    }

    .status-completed {
        background: #ecfdf5;
        color: #10b981;
        border: 1px solid #d1fae5;
    }

    .status-rejected {
        background: #fef2f2;
        color: #ef4444;
        border: 1px solid #fee2e2;
    }

    .status-requested {
        background: #eff6ff;
        color: #2563eb;
        border: 1px solid #dbeafe;
    }

    .test-card-premium {
        background: white;
        border: 1px solid #f1f5f9;
        border-radius: 14px;
        padding: 18px;
        transition: all 0.2s;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .test-card-premium:hover {
        border-color: #2563eb;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
    }
</style>