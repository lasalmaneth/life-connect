<?php
// Patient Aftercare Portal Account Management View
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo ROOT; ?>/assets/css/hospital/hospital.css">
    <title>Patient Aftercare Portal - LifeConnect</title>
</head>

<body>
    <div class="header">
        <div class="header-content">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <a href="<?php echo ROOT ?? '/life-connect'; ?>" style="text-decoration:none; display:flex; align-items:center; gap:10px;">
                    <img src="<?php echo ROOT ?? '/life-connect'; ?>/public/assets/images/logo.png" alt="LifeConnect" style="height:40px; width: auto;">
                    <div>
                        <strong style="display:block; font-size:1.1rem; color:#003b6e; line-height:1.2;">LifeConnect</strong>
                        <p style="margin:0; font-size:.68rem; color:#6b7280; padding-top:2px;">Hospital Portal</p>
                    </div>
                </a>
            </div>
            <div class="user-info">
                <div class="user-avatar"><?php echo strtoupper(substr($hospital_details['name'], 0, 1)); ?></div>
                <div class="user-details">
                    <div style="font-weight: 600; font-size: 0.9rem;"><?php echo htmlspecialchars($hospital_details['name']); ?></div>
                    <div style="font-size: 0.8rem; opacity: 0.8;"><?php echo htmlspecialchars($hospital_details['role']); ?></div>
                </div>
                <div class="user-actions">
                    <button class="btn-logout" onclick="window.location.href='<?php echo ROOT; ?>/logout'" title="Logout">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16,17 21,12 16,7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>


    <div class="container">
        <div class="main-content">
            <div class="sidebar">
                <div class="sidebar-header">
                    <h3>Hospital Portal</h3>
                    <p>Clinical coordination</p>
                </div>

                <div class="menu-section">
                    <div class="menu-section-title">NAVIGATION</div>
                    <a href="<?php echo ROOT; ?>/hospital" class="menu-item" style="text-decoration:none; color:inherit; display:block;">
                        <span class="icon"></span>
                        <span>Main Dashboard</span>
                    </a>
                </div>
            </div>

            <div class="content-area" id="content-area">
                <div class="content-section" style="display: block;">
                    <div class="content-header">
                        <h2>Aftercare Portal Account Generation</h2>
                        <p>Generate login credentials for transplant recipients to access the patient aftercare app.</p>
                    </div>

                    <div class="content-body">
                        <?php if (isset($_SESSION['flash_success'])): ?>
                            <div style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 8px; margin-bottom: 2rem; border-left: 4px solid #28a745; font-weight: 500;">
                                <?php 
                                    echo $_SESSION['flash_success']; 
                                    unset($_SESSION['flash_success']); 
                                ?>
                            </div>
                        <?php endif; ?>

                        <!-- Create Patient Account Form -->
                        <div style="background: white; border-radius: 12px; padding: 2rem; box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 2rem;">
                            <h3 style="color: var(--primary-text-color); margin-bottom: 1.5rem; border-bottom: 2px solid var(--border-color); padding-bottom: 0.5rem;">Generate Account</h3>
                            <form action="<?php echo ROOT; ?>/hospital/addpatient" method="POST">
                                <input type="hidden" name="action" value="create_aftercare_account">
                                
                                <div style="margin-bottom: 1.5rem;">
                                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: var(--secondary-text-color);">Patient NIC (Used as default password)</label>
                                    <input type="text" name="nic" placeholder="Enter Patient's NIC" required style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--border-color); border-radius: 8px; font-size: 1rem;">
                                    <small style="color: #6c757d; display: block; margin-top: 0.5rem;">The system will automatically generate a secure User ID for the patient. They must log in using this generated ID and their NIC as the password.</small>
                                </div>

                                <button type="submit" class="btn btn-primary" style="padding: 0.75rem 1.5rem; font-size: 1rem; cursor: pointer;">Generate Login Credentials</button>
                            </form>
                        </div>

                        <!-- Generated Accounts Table -->
                        <div class="data-table">
                            <div class="table-header">
                                <h4>Generated Patient Accounts</h4>
                            </div>
                            <div class="table-content">
                                <div class="table-row" style="font-weight: 600; background: var(--gray-bg-color);">
                                    <div class="table-cell">System User ID</div>
                                    <div class="table-cell">Account Type</div>
                                    <div class="table-cell">Generated On</div>
                                    <div class="table-cell">Actions</div>
                                </div>
                                <?php if (!empty($patient_accounts)): ?>
                                    <?php foreach ($patient_accounts as $account): ?>
                                        <div class="table-row">
                                            <div class="table-cell" style="font-family: monospace; font-weight: bold; color: var(--primary-color);">
                                                <?php echo htmlspecialchars(explode('@', $account->user_id)[0]); ?>
                                            </div>
                                            <div class="table-cell"><span style="background: #e1f5fe; color: #0288d1; padding: 4px 8px; border-radius: 12px; font-size: 0.8rem; font-weight: 600;">Aftercare Portal</span></div>
                                            <div class="table-cell"><?php echo date('M d, Y · H:i A', strtotime($account->created_at)); ?></div>
                                            <div class="table-cell" style="display: flex; gap: 0.5rem;">
                                                <!-- Mock Edit/Delete for UI demonstration -->
                                                <button class="btn btn-secondary btn-small" onclick="alert('Functionality to reset password coming soon.')">Reset Password</button>
                                                <button class="btn btn-danger btn-small" onclick="if(confirm('Are you sure you want to deactivate this portal access?')) { alert('Account deactivated.'); }">Deactivate</button>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div style="padding: 3rem; text-align: center; color: var(--secondary-text-color);">
                                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 1rem; opacity: 0.5;"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                        <p>No aftercare accounts generated yet.</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Aftercare Management Section (Simulated) -->
                        <div style="margin-top: 3rem; border-top: 2px solid var(--border-color); padding-top: 2rem;">
                            <h3 style="color: var(--primary-color); margin-bottom: 0.5rem; display:flex; align-items:center; gap: 0.5rem;">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-4l-3 9L9 3l-3 9H2"></path></svg>
                                Patient Aftercare Management
                            </h3>
                            <p style="color: var(--secondary-text-color); margin-bottom: 2rem;">Select a patient from the active accounts above to instantly schedule their post-surgery appointments and file support requests.</p>

                            <!-- Visual Calendar Section -->
                            <div style="background: white; border-radius: 12px; padding: 1rem; box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 2rem; max-width: 800px; margin-left: auto; margin-right: auto;">
                                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding-bottom: 1rem; margin-bottom: 1.5rem;">
                                    <h4 style="margin: 0; font-size: 1rem; color: #1a202c;"><span style="margin-right: 8px;">📅</span>Appointments Calendar</h4>
                                    <div style="font-weight: 600; color: var(--primary-color);">April 2026</div>
                                    <div style="display:flex; gap: 0.5rem;">
                                        <button class="btn btn-secondary btn-small" style="padding: 0.25rem 0.5rem;">&lt; Prev</button>
                                        <button class="btn btn-secondary btn-small" style="padding: 0.25rem 0.5rem;">Next &gt;</button>
                                    </div>
                                </div>
                                <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 0.5rem; text-align: center;">
                                    <div style="font-weight: 600; color: #718096; font-size: 0.85rem; padding-bottom: 0.5rem;">Sun</div>
                                    <div style="font-weight: 600; color: #718096; font-size: 0.85rem; padding-bottom: 0.5rem;">Mon</div>
                                    <div style="font-weight: 600; color: #718096; font-size: 0.85rem; padding-bottom: 0.5rem;">Tue</div>
                                    <div style="font-weight: 600; color: #718096; font-size: 0.85rem; padding-bottom: 0.5rem;">Wed</div>
                                    <div style="font-weight: 600; color: #718096; font-size: 0.85rem; padding-bottom: 0.5rem;">Thu</div>
                                    <div style="font-weight: 600; color: #718096; font-size: 0.85rem; padding-bottom: 0.5rem;">Fri</div>
                                    <div style="font-weight: 600; color: #718096; font-size: 0.85rem; padding-bottom: 0.5rem;">Sat</div>
                                    
                                    <!-- Calendar full month grid (April 2026) -->
                                    <div style="padding: 0.35rem; border: 1px solid #e2e8f0; border-radius: 6px; color: transparent;">0</div>
                                    <div style="padding: 0.35rem; border: 1px solid #e2e8f0; border-radius: 6px; color: transparent;">0</div>
                                    <div style="padding: 0.35rem; border: 1px solid #e2e8f0; border-radius: 6px; color: transparent;">0</div>
                                    <div style="padding: 0.35rem; border: 1px solid #e2e8f0; border-radius: 6px; cursor: pointer; transition: 0.2s;">1<br><small style="color:#a0aec0;font-size:0.65rem;">Book</small></div>
                                    <div style="padding: 0.35rem; border: 1px solid #e2e8f0; border-radius: 6px; cursor: pointer; transition: 0.2s;">2<br><small style="color:#a0aec0;font-size:0.65rem;">Book</small></div>
                                    <div style="padding: 0.35rem; border: 1px solid #e2e8f0; border-radius: 6px; cursor: pointer; transition: 0.2s;">3<br><small style="color:#a0aec0;font-size:0.65rem;">Book</small></div>
                                    <div style="padding: 0.35rem; border: 1px solid #e2e8f0; border-radius: 6px; cursor: pointer; transition: 0.2s;">4<br><small style="color:#a0aec0;font-size:0.65rem;">Book</small></div>
                                    
                                    <div style="padding: 0.35rem; border: 1px solid #e2e8f0; border-radius: 6px; cursor: pointer; transition: 0.2s;">5<br><small style="color:#a0aec0;font-size:0.65rem;">Book</small></div>
                                    <div style="padding: 0.35rem; border: 1px solid #e2e8f0; border-radius: 6px; cursor: pointer; transition: 0.2s;">6<br><small style="color:#a0aec0;font-size:0.65rem;">Book</small></div>
                                    <div style="padding: 0.35rem; border: 1px solid #e2e8f0; border-radius: 6px; cursor: pointer; transition: 0.2s;">7<br><small style="color:#a0aec0;font-size:0.65rem;">Book</small></div>
                                    <div style="padding: 0.35rem; border: 1px solid #e2e8f0; border-radius: 6px; cursor: pointer; transition: 0.2s;">8<br><small style="color:#a0aec0;font-size:0.65rem;">Book</small></div>
                                    <div style="padding: 0.35rem; border: 1px solid #e2e8f0; border-radius: 6px; cursor: pointer; transition: 0.2s;">9<br><small style="color:#a0aec0;font-size:0.65rem;">Book</small></div>
                                    <div style="padding: 0.35rem; border: 1px solid #e2e8f0; border-radius: 6px; cursor: pointer; transition: 0.2s;">10<br><small style="color:#a0aec0;font-size:0.65rem;">Book</small></div>
                                    <div style="padding: 0.35rem; border: 1px solid #e2e8f0; border-radius: 6px; cursor: pointer; transition: 0.2s;">11<br><small style="color:#a0aec0;font-size:0.65rem;">Book</small></div>
                                    
                                    <div style="padding: 0.35rem; border: 1px solid #e2e8f0; border-radius: 6px; cursor: pointer; transition: 0.2s;">12<br><small style="color:#a0aec0;font-size:0.65rem;">Book</small></div>
                                    <div style="padding: 0.35rem; border: 1px solid #e2e8f0; border-radius: 6px; cursor: pointer; transition: 0.2s;">13<br><small style="color:#a0aec0;font-size:0.65rem;">Book</small></div>
                                    <div style="padding: 0.35rem; border: 1px solid #e2e8f0; border-radius: 6px; cursor: pointer; transition: 0.2s;">14<br><small style="color:#a0aec0;font-size:0.65rem;">Book</small></div>
                                    <div style="padding: 0.35rem; border: 1px solid #e2e8f0; border-radius: 6px; cursor: pointer; transition: 0.2s;">15<br><small style="color:#a0aec0;font-size:0.65rem;">Book</small></div>
                                    <div style="padding: 0.35rem; border: 1px solid #e2e8f0; border-radius: 6px; cursor: pointer; transition: 0.2s;">16<br><small style="color:#a0aec0;font-size:0.65rem;">Book</small></div>
                                    <div style="padding: 0.35rem; border: 2px solid var(--primary-color); background:#e1f5fe; border-radius: 6px; font-weight: bold; color: var(--primary-color); cursor: pointer; transition: 0.2s;">17<br><small style="color:var(--primary-color);font-size:0.65rem;">1 appt</small></div>
                                    <div style="padding: 0.35rem; border: 1px solid #e2e8f0; border-radius: 6px; cursor: pointer; transition: 0.2s;">18<br><small style="color:#a0aec0;font-size:0.65rem;">Book</small></div>

                                    <!-- Added missing days to complete April 2026 -->
                                    <div style="padding: 0.35rem; border: 1px solid #e2e8f0; border-radius: 6px; cursor: pointer; transition: 0.2s;">19<br><small style="color:#a0aec0;font-size:0.65rem;">Book</small></div>
                                    <div style="padding: 0.35rem; border: 1px solid #e2e8f0; border-radius: 6px; cursor: pointer; transition: 0.2s;">20<br><small style="color:#a0aec0;font-size:0.65rem;">Book</small></div>
                                    <div style="padding: 0.35rem; border: 1px solid #e2e8f0; border-radius: 6px; cursor: pointer; transition: 0.2s;">21<br><small style="color:#a0aec0;font-size:0.65rem;">Book</small></div>
                                    <div style="padding: 0.35rem; border: 1px solid #e2e8f0; border-radius: 6px; cursor: pointer; transition: 0.2s;">22<br><small style="color:#a0aec0;font-size:0.65rem;">Book</small></div>
                                    <div style="padding: 0.35rem; border: 1px solid #e2e8f0; border-radius: 6px; cursor: pointer; transition: 0.2s;">23<br><small style="color:#a0aec0;font-size:0.65rem;">Book</small></div>
                                    <div style="padding: 0.35rem; border: 1px solid #e2e8f0; border-radius: 6px; cursor: pointer; transition: 0.2s;">24<br><small style="color:#a0aec0;font-size:0.65rem;">Book</small></div>
                                    <div style="padding: 0.35rem; border: 1px solid #e2e8f0; border-radius: 6px; cursor: pointer; transition: 0.2s;">25<br><small style="color:#a0aec0;font-size:0.65rem;">Book</small></div>
                                    
                                    <div style="padding: 0.35rem; border: 1px solid #e2e8f0; border-radius: 6px; cursor: pointer; transition: 0.2s;">26<br><small style="color:#a0aec0;font-size:0.65rem;">Book</small></div>
                                    <div style="padding: 0.35rem; border: 1px solid #e2e8f0; border-radius: 6px; cursor: pointer; transition: 0.2s;">27<br><small style="color:#a0aec0;font-size:0.65rem;">Book</small></div>
                                    <div style="padding: 0.35rem; border: 1px solid #e2e8f0; border-radius: 6px; cursor: pointer; transition: 0.2s;">28<br><small style="color:#a0aec0;font-size:0.65rem;">Book</small></div>
                                    <div style="padding: 0.35rem; border: 1px solid #e2e8f0; border-radius: 6px; cursor: pointer; transition: 0.2s;">29<br><small style="color:#a0aec0;font-size:0.65rem;">Book</small></div>
                                    <div style="padding: 0.35rem; border: 1px solid #e2e8f0; border-radius: 6px; cursor: pointer; transition: 0.2s;">30<br><small style="color:#a0aec0;font-size:0.65rem;">Book</small></div>
                                    
                                    <div style="padding: 0.5rem; border: 1px solid transparent; border-radius: 8px; color: transparent;">0</div>
                                    <div style="padding: 0.5rem; border: 1px solid transparent; border-radius: 8px; color: transparent;">0</div>
                                </div>
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                                <!-- Book Appointment Panel -->
                                <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                                    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding-bottom: 1rem; margin-bottom: 1.5rem;">
                                        <h4 style="margin: 0; font-size: 1.1rem; color: #1a202c;"><span style="margin-right: 8px;">📝</span>My Appointments</h4>
                                        <button class="btn btn-primary btn-small" style="width: auto; margin: 0; padding: 0.4rem 1rem;" onclick="alert('Select a PAT- user first!')">+ Book Appointment</button>
                                    </div>
                                    <div class="data-table" style="box-shadow: none; border: 1px solid var(--border-color); margin-top: 0;">
                                        <div class="table-header" style="background:#005baa; color:white; padding: 0.75rem; font-size: 0.85rem;">
                                            <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; gap:1rem;">
                                                <div>DATE & TIME</div>
                                                <div>TYPE</div>
                                                <div>STATUS</div>
                                            </div>
                                        </div>
                                        <div style="padding: 1.5rem; text-align: center; color: #718096; font-size: 0.9rem;">No upcoming appointments booked.</div>
                                    </div>
                                </div>

                                <!-- Support Requests Panel -->
                                <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                                    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding-bottom: 1rem; margin-bottom: 1.5rem;">
                                        <h4 style="margin: 0; font-size: 1.1rem; color: #1a202c;"><span style="margin-right: 8px;">💼</span>Support Requests</h4>
                                        <button class="btn btn-primary btn-small" style="width: auto; margin: 0; padding: 0.4rem 1rem;" onclick="alert('Select a PAT- user first!')">+ New Request</button>
                                    </div>
                                    <div class="data-table" style="box-shadow: none; border: 1px solid var(--border-color); margin-top: 0;">
                                        <div class="table-header" style="background:#005baa; color:white; padding: 0.75rem; font-size: 0.85rem;">
                                            <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; gap:1rem;">
                                                <div>DATE</div>
                                                <div>REASON</div>
                                                <div>STATUS</div>
                                            </div>
                                        </div>
                                        <div style="padding: 1.5rem; text-align: center; color: #718096; font-size: 0.9rem;">No active support requests filed.</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
