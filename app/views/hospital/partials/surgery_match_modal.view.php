<div id="surgeryMatchModal" class="modal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.5); backdrop-filter: blur(4px);">
    <div class="modal-content" style="background-color: #f8fafc; margin: 2% auto; padding: 0; border: none; width: 850px; border-radius: 20px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); max-height: 90vh; overflow-y: auto; animation: modalSlideIn 0.3s ease-out;">
        
        <!-- Modal Header -->
        <div style="background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); padding: 30px; color: white; position: relative;">
            <div style="display: flex; align-items: center; gap: 20px;">
                <div style="background: rgba(255,255,255,0.2); width: 60px; height: 60px; border-radius: 16px; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(10px);">
                    <i class="fas fa-procedures" style="font-size: 1.8rem;"></i>
                </div>
                <div>
                    <h2 id="matchTitle" style="margin: 0; font-size: 1.6rem; font-weight: 800; letter-spacing: -0.025em;">Surgery Match Details</h2>
                    <p id="matchSubtitle" style="margin: 6px 0 0; opacity: 0.9; font-size: 0.95rem; font-weight: 500;"></p>
                </div>
            </div>
            <button onclick="closeSurgeryMatchModal()" style="position: absolute; top: 25px; right: 25px; background: rgba(255,255,255,0.15); border: none; color: white; width: 36px; height: 36px; border-radius: 12px; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s; hover: { background: rgba(255,255,255,0.25); transform: rotate(90deg); }">
                <i class="fas fa-times" style="font-size: 1.1rem;"></i>
            </button>
        </div>

        <div style="padding: 35px;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
                <!-- Donor Info Group -->
                <div style="background: white; padding: 25px; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px; padding-bottom: 12px; border-bottom: 1px solid #f1f5f9;">
                        <div style="width: 32px; height: 32px; background: #dcfce7; color: #166534; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 0.9rem;">
                            <i class="fas fa-user-donor"></i>
                        </div>
                        <h4 style="margin: 0; color: #1e293b; font-size: 1rem; font-weight: 700;">Donor Information</h4>
                    </div>
                    <div id="donorDetails" style="display: flex; flex-direction: column; gap: 14px;">
                        <!-- Populate via JS -->
                    </div>
                </div>

                <!-- Recipient Info Group -->
                <div style="background: white; padding: 25px; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px; padding-bottom: 12px; border-bottom: 1px solid #f1f5f9;">
                        <div style="width: 32px; height: 32px; background: #e0f2fe; color: #0369a1; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 0.9rem;">
                            <i class="fas fa-user-recipient"></i>
                        </div>
                        <h4 style="margin: 0; color: #1e293b; font-size: 1rem; font-weight: 700;">Recipient Requirements</h4>
                    </div>
                    <div id="recipientDetails" style="display: flex; flex-direction: column; gap: 14px;">
                        <!-- Populate via JS -->
                    </div>
                </div>
            </div>

            <!-- Warning Section (Condition-based) -->
            <div id="medicalWarningBox" style="display: none; margin-top: 25px; padding: 18px; background: #fffbeb; border-radius: 12px; border: 1px solid #fef3c7; color: #92400e;">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                    <i class="fas fa-exclamation-triangle" style="font-size: 1.1rem;"></i>
                    <strong style="font-weight: 700;">Important Medical Notes</strong>
                </div>
                <p id="matchWarningText" style="margin: 0; font-size: 0.9rem; line-height: 1.5;"></p>
            </div>

            <!-- Surgery Date & Status -->
            <div style="margin-top: 25px; padding: 20px; background: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <div style="font-size: 0.75rem; color: #64748b; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 5px;">Surgery Scheduled For</div>
                    <div id="matchSurgeryDate" style="font-size: 1.1rem; color: #0f172a; font-weight: 800;"></div>
                </div>
                <div id="matchStatusBadgeContainer" style="display: flex; flex-direction: column; align-items: flex-end; gap: 8px;">
                    <!-- Dynamic Badge -->
                </div>
            </div>

            <!-- Scenario Summary -->
            <div style="margin-top: 25px; padding: 25px; background: white; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px; padding-bottom: 12px; border-bottom: 1px solid #f1f5f9;">
                    <div style="width: 32px; height: 32px; background: #f1f5f9; color: #475569; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 0.9rem;">
                        <i class="fas fa-list-alt"></i>
                    </div>
                    <h4 style="margin: 0; color: #1e293b; font-size: 1rem; font-weight: 700;">Scenario Summary</h4>
                </div>
                <div id="scenarioSummary" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; font-size: 0.9rem;">
                    <!-- Populate via JS -->
                </div>
            </div>

            <!-- Hospital Decision Display (Existing) -->
            <div id="hospitalDecisionBox" style="display: none; margin-top: 20px; padding: 20px; background: #fff; border-radius: 12px; border: 1px solid #e2e8f0;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                    <h4 style="margin: 0; color: #1e293b; font-size: 0.95rem; font-weight: 700;">Hospital Decision</h4>
                    <span id="hospitalStatusPill" style="padding: 4px 12px; border-radius: 6px; font-size: 0.7rem; font-weight: 800; text-transform: uppercase;"></span>
                </div>
                <div style="color: #475569; font-size: 0.9rem; font-weight: 500; line-height: 1.5;" id="hospitalRemarksText"></div>
            </div>

            <!-- Action Form (Conditional....) -->
            <div id="matchActionForm" style="display: none; margin-top: 30px; padding-top: 25px; border-top: 1px solid #e2e8f0;">
                <h4 style="margin: 0 0 15px; color: #1e293b; font-size: 1rem; font-weight: 700;">Confirm Decision</h4>
                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; color: #475569; font-weight: 600; font-size: 0.9rem;">Your Reason / Notes</label>
                    <textarea id="matchReason" style="width: 100%; padding: 12px; border: 1px solid #cbd5e1; border-radius: 10px; min-height: 100px; font-family: inherit; font-size: 0.95rem; resize: vertical;" placeholder="Why are you approving or rejecting this match?"></textarea>
                </div>
                <div style="display: flex; justify-content: flex-end; gap: 15px;">
                    <button onclick="submitMatchAction('reject')" style="background: white; border: 1px solid #ef4444; color: #ef4444; padding: 12px 24px; border-radius: 10px; font-weight: 700; cursor: pointer; transition: all 0.2s;">Reject Match</button>
                    <button onclick="submitMatchAction('approve')" style="background: #10b981; border: none; color: white; padding: 12px 24px; border-radius: 10px; font-weight: 700; cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 14px rgba(16, 185, 129, 0.3);">Approve Match</button>
                </div>
            </div>

            <!-- Documents Section (Post-Approval) -->
            <div id="matchDocsSection" style="display: none; margin-top: 30px; padding-top: 25px; border-top: 1px solid #e2e8f0;">
                <h4 style="margin: 0 0 15px; color: #1e293b; font-size: 1rem; font-weight: 700;">Registry Documents</h4>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <a id="certLink" href="#" target="_blank" style="display: flex; align-items: center; gap: 15px; padding: 15px; background: white; border: 1px solid #e2e8f0; border-radius: 12px; text-decoration: none; transition: all 0.2s;">
                        <div style="width: 40px; height: 40px; background: #e0f7fa; color: #00838f; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-file-contract"></i>
                        </div>
                        <div>
                            <div style="color: #0f172a; font-weight: 700; font-size: 0.95rem;">Donation Certificate</div>
                            <div style="color: #64748b; font-size: 0.75rem;">Official hospital registry copy</div>
                        </div>
                    </a>
                    <a id="letterLink" href="#" target="_blank" style="display: flex; align-items: center; gap: 15px; padding: 15px; background: white; border: 1px solid #e2e8f0; border-radius: 12px; text-decoration: none; transition: all 0.2s;">
                        <div style="width: 40px; height: 40px; background: #f3f4f6; color: #4b5563; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-envelope-open-text"></i>
                        </div>
                        <div>
                            <div style="color: #0f172a; font-weight: 700; font-size: 0.95rem;">Appreciation Letter</div>
                            <div style="color: #64748b; font-size: 0.75rem;">Formal letter for the donor</div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Surgery Completion Section (Visible only when IN_PROGRESS) -->
            <div id="surgeryCompletionSection" style="display: none; margin-top: 30px; padding: 25px; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 16px;">
                <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 20px;">
                    <div style="width: 45px; height: 45px; background: #10b981; color: white; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                        <i class="fas fa-check-double"></i>
                    </div>
                    <div>
                        <h4 style="margin: 0; color: #065f46; font-size: 1.1rem; font-weight: 700;">Mark Surgery Completion</h4>
                        <p style="margin: 3px 0 0; color: #065f46; opacity: 0.8; font-size: 0.85rem;">Finalize the transplant result in the central registry.</p>
                    </div>
                </div>
                <div style="display: flex; justify-content: flex-end;">
                    <button onclick="confirmSurgeryCompletion()" style="background: #059669; border: none; color: white; padding: 12px 28px; border-radius: 10px; font-weight: 700; cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 14px rgba(5, 150, 105, 0.3); display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-vial"></i>
                        Surgery Successfully Completed
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes modalSlideIn {
    from { transform: translateY(-30px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}
.match-detail-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}
.match-detail-label {
    color: #64748b;
    font-size: 0.8rem;
    font-weight: 600;
}
.match-detail-value {
    color: #1e293b;
    font-size: 0.9rem;
    font-weight: 700;
}
</style>
