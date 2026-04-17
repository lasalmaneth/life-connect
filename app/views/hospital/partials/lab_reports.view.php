<div id="lab-reports" class="content-section" style="display: none;">
    <!-- Premium Header -->
    <div class="content-header" style="background: white; border-bottom: 1px solid #f1f5f9; padding: 25px 30px; border-radius: 16px 16px 0 0; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 4px;">
                <div style="width: 32px; height: 32px; background: rgba(37, 99, 235, 0.1); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #2563eb;">
                    <i class="fas fa-microscope" style="font-size: 0.9rem;"></i>
                </div>
                <h2 style="margin: 0; font-size: 1.5rem; color: #0f172a; font-weight: 800;">Basic Organ Testing</h2>
            </div>
            <p style="margin: 0; color: #64748b; font-size: 0.85rem; font-weight: 500;">Clinical screening and donor compatibility management.</p>
        </div>
        <button class="btn btn-primary" onclick="openLabReportModal()" style="background: #2563eb; color: white; border: none; padding: 12px 24px; border-radius: 12px; font-weight: 700; font-size: 0.85rem; cursor: pointer; display: flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2); transition: all 0.2s;">
            <i class="fas fa-plus-circle"></i> Schedule New Test
        </button>
    </div>

    <div class="content-body" style="padding: 30px; background: #f8fafc; border-radius: 0 0 16px 16px; border: 1px solid #e2e8f0; border-top: none;">
        
        <!-- Stats Row -->
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 30px;">
            <div style="background: white; padding: 20px; border-radius: 16px; border: 1px solid #e2e8f0; border-bottom: 3px solid #2563eb; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                    <div style="width: 36px; height: 36px; background: #eff6ff; color: #2563eb; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-users-viewfinder"></i>
                    </div>
                    <span style="font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase;">Total Screening</span>
                </div>
                <div style="font-size: 1.5rem; font-weight: 900; color: #0f172a;" id="stat-total-screening">0</div>
            </div>
            <div style="background: white; padding: 20px; border-radius: 16px; border: 1px solid #e2e8f0; border-bottom: 3px solid #f59e0b; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                    <div style="width: 36px; height: 36px; background: #fffbeb; color: #d97706; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-clock"></i>
                    </div>
                    <span style="font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase;">Pending Acceptance</span>
                </div>
                <div style="font-size: 1.5rem; font-weight: 900; color: #0f172a;" id="stat-pending-tests">0</div>
            </div>
            <div style="background: white; padding: 20px; border-radius: 16px; border: 1px solid #e2e8f0; border-bottom: 3px solid #10b981; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                    <div style="width: 36px; height: 36px; background: #ecfdf5; color: #10b981; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <span style="font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase;">Tests Today</span>
                </div>
                <div style="font-size: 1.5rem; font-weight: 900; color: #0f172a;" id="stat-tests-today">0</div>
            </div>
            <div style="background: white; padding: 20px; border-radius: 16px; border: 1px solid #e2e8f0; border-bottom: 3px solid #7c3aed; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                    <div style="width: 36px; height: 36px; background: #f5f3ff; color: #7c3aed; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-clipboard-check"></i>
                    </div>
                    <span style="font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase;">Completed Overall</span>
                </div>
                <div style="font-size: 1.5rem; font-weight: 900; color: #0f172a;" id="stat-completed-overall">0</div>
            </div>
        </div>

        <!-- Filter Navigation -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <div style="display: flex; background: #f1f5f9; padding: 4px; border-radius: 12px; border: 1px solid #e2e8f0;">
                <button class="lab-nav-btn active" onclick="switchLabTab('active-screening', this)">Active Screenings</button>
                <button class="lab-nav-btn" onclick="switchLabTab('appointment-requests', this)">Donor Requests</button>
                <button class="lab-nav-btn" onclick="switchLabTab('aftercare-requests', this)">Aftercare Needs</button>
            </div>
            <div style="position: relative; width: 300px;">
                <i class="fas fa-search" style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.8rem;"></i>
                <input type="text" id="lab-main-search" placeholder="Search by name or NIC..." style="width: 100%; padding: 10px 15px 10px 40px; border-radius: 10px; border: 1px solid #e2e8f0; outline: none; font-size: 0.85rem; font-weight: 500; transition: border-color 0.2s;">
            </div>
        </div>

        <!-- Tables Container -->
        <div id="active-screening" class="lab-tab-content active">
            <div style="background: white; border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f8fafc; border-bottom: 2px solid #f1f5f9;">
                            <th style="padding: 18px 25px; text-align: left; font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Donor Member</th>
                            <th style="padding: 18px 25px; text-align: left; font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">NIC Number</th>
                            <th style="padding: 18px 25px; text-align: left; font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Organ Focus</th>
                            <th style="padding: 18px 25px; text-align: left; font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Test Progress</th>
                            <th style="padding: 18px 25px; text-align: left; font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Registry Status</th>
                            <th style="padding: 18px 25px; text-align: right; font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="lab-members-body">
                        <!-- Populated by JS -->
                    </tbody>
                </table>
                <div id="lab-empty-state" style="padding: 60px; text-align: center; display: none;">
                    <div style="width: 60px; height: 60px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; color: #cbd5e1;">
                        <i class="fas fa-microscope" style="font-size: 1.5rem;"></i>
                    </div>
                    <h3 style="color: #1e293b; font-size: 1.1rem; font-weight: 800; margin-bottom: 8px;">No Screening Activities</h3>
                    <p style="color: #64748b; font-size: 0.85rem; max-width: 400px; margin: 0 auto;">Active tests and compatibility screenings for matched donors will appear here.</p>
                </div>
            </div>
        </div>

        <div id="appointment-requests" class="lab-tab-content" style="display: none;">
             <div style="background: white; border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f8fafc; border-bottom: 2px solid #f1f5f9;">
                            <th style="padding: 18px 25px; text-align: left; font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase;">Requestor</th>
                            <th style="padding: 18px 25px; text-align: left; font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase;">Test Type</th>
                            <th style="padding: 18px 25px; text-align: left; font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase;">Preferred Date</th>
                            <th style="padding: 18px 25px; text-align: right; font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="lab-requests-body">
                        <!-- Populated by JS -->
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>



<style>
    .lab-nav-btn {
        padding: 10px 20px;
        border: none;
        background: transparent;
        color: #64748b;
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.2s;
    }
    .lab-nav-btn.active {
        background: white;
        color: #2563eb;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    .status-pill {
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 0.7rem;
        font-weight: 800;
        text-transform: uppercase;
    }
    .status-pending { background: #fff7ed; color: #ea580c; border: 1px solid #ffedd5; }
    .status-scheduled { background: #eff6ff; color: #2563eb; border: 1px solid #dbeafe; }
    .status-completed { background: #ecfdf5; color: #10b981; border: 1px solid #d1fae5; }
    
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
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
    }
</style>