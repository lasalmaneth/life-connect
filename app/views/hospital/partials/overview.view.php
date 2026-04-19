<div id="overview" class="content-section" style="<?php echo (isset($initialSection) && $initialSection === 'overview') ? 'display:block' : 'display:none'; ?>">
    <div class="content-header">
        <div style="display: flex; align-items: center; gap: 15px;">
            <div style="width: 48px; height: 48px; background: rgba(37, 99, 235, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #2563eb;">
                <i class="fas fa-chart-line" style="font-size: 1.5rem;"></i>
            </div>
            <div>
                <h2 style="margin: 0;">Hospital Overview</h2>
                <p style="margin: 0;">Monitor organ requests, donor eligibility, and aftercare management.</p>
            </div>
        </div>
    </div>
    
    <div class="content-body" style="background: #f8fafc; padding: 30px;">
        <!-- STATS GRID -->
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 2.5rem;">
            <!-- Total Requests -->
            <div style="background: white; padding: 22px; border-radius: 16px; border: 1px solid #e2e8f0; border-bottom: 4px solid #3b82f6; box-shadow: 0 2px 4px rgba(0,0,0,0.02); transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 15px;">
                    <div style="width: 36px; height: 36px; background: #eff6ff; color: #3b82f6; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-file-medical"></i>
                    </div>
                    <span style="font-size: 0.7rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Total Requests</span>
                </div>
                <div style="font-size: 1.75rem; font-weight: 900; color: #0f172a;"><?php echo $stats['total_organ_requests']; ?></div>
                <div style="font-size: 0.75rem; color: #3b82f6; font-weight: 700; margin-top: 5px;"><?php echo $stats['pending_requests']; ?> critical pending</div>
            </div>

            <!-- Aftercare Active -->
            <div style="background: white; padding: 22px; border-radius: 16px; border: 1px solid #e2e8f0; border-bottom: 4px solid #10b981; box-shadow: 0 2px 4px rgba(0,0,0,0.02); transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 15px;">
                    <div style="width: 36px; height: 36px; background: #ecfdf5; color: #10b981; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <span style="font-size: 0.7rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Aftercare Patients</span>
                </div>
                <div style="font-size: 1.75rem; font-weight: 900; color: #0f172a;"><?php echo $stats['total_aftercare_recipients']; ?></div>
                <div style="font-size: 0.75rem; color: #10b981; font-weight: 700; margin-top: 5px;">Active monitoring</div>
            </div>

            <!-- Success Stories -->
            <div style="background: white; padding: 22px; border-radius: 16px; border: 1px solid #e2e8f0; border-bottom: 4px solid #f59e0b; box-shadow: 0 2px 4px rgba(0,0,0,0.02); transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 15px;">
                    <div style="width: 36px; height: 36px; background: #fffbeb; color: #f59e0b; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-heart"></i>
                    </div>
                    <span style="font-size: 0.7rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Success Stories</span>
                </div>
                <div style="font-size: 1.75rem; font-weight: 900; color: #0f172a;"><?php echo $stats['total_success_stories']; ?></div>
                <div style="font-size: 0.75rem; color: #f59e0b; font-weight: 700; margin-top: 5px;"><?php echo $stats['approved_stories']; ?> verified</div>
            </div>

            <!-- Upcoming Appointments -->
            <div style="background: white; padding: 22px; border-radius: 16px; border: 1px solid #e2e8f0; border-bottom: 4px solid #6366f1; box-shadow: 0 2px 4px rgba(0,0,0,0.02); transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 15px;">
                    <div style="width: 36px; height: 36px; background: #f5f3ff; color: #6366f1; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <span style="font-size: 0.7rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Appointments</span>
                </div>
                <div style="font-size: 1.75rem; font-weight: 900; color: #0f172a;"><?php echo $stats['total_appointments']; ?></div>
                <div style="font-size: 0.75rem; color: #6366f1; font-weight: 700; margin-top: 5px;"><?php echo $stats['scheduled_appointments']; ?> scheduled</div>
            </div>
        </div>

        <!-- CENTRALIZED HOSPITAL CALENDAR SECTION -->
        <div style="background: white; border-radius: 20px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.03);">
            <div style="padding: 25px 30px; background: white; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <div style="width: 40px; height: 40px; background: rgba(37, 99, 235, 0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #2563eb;">
                        <i class="fas fa-calendar-day" style="font-size: 1.1rem;"></i>
                    </div>
                    <div>
                        <h3 style="margin: 0; font-size: 1.25rem; color: #0f172a; font-weight: 800;">Centralized Schedule</h3>
                        <p style="margin: 0; color: #64748b; font-size: 0.8rem; font-weight: 500;">A unified view of all scheduled surgeries, appointments, and requests.</p>
                    </div>
                </div>
            </div>
            <div style="padding: 30px;">
                <?php require __DIR__ . '/centralized_calendar.view.php'; ?>
            </div>
        </div>

        <form id="supportRequestActionForm" method="POST" style="display:none;">
            <input type="hidden" name="action" id="supportRequestAction" value="">
            <input type="hidden" name="support_request_id" id="supportRequestId" value="">
            <input type="hidden" name="reject_reason" id="supportRequestRejectReason" value="">
        </form>
    </div>
</div>