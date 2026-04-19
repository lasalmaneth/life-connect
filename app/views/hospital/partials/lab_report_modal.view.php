<div class="modal" id="lab-report-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Schedule Appointment</h3>
                <button class="modal-close" onclick="closeLabReportModal()">×</button>
            </div>
            <input type="hidden" id="lab-report-id" value="">
            <div>
                <div class="form-group">
                    <label class="form-label">Select Donor <span style="color: #e74c3c;">*</span></label>
                    <select class="form-select" id="lab-donor-select">
                        <option value="">Select a Donor</option>
                    </select>
                    <input type="hidden" id="lab-donor-id" value="">
                </div>
                <div class="form-group">
                    <label class="form-label">Organ Type <span style="color: #e74c3c;">*</span></label>
                    <select class="form-select" id="lab-organ-id">
                        <option value="">Select Organ</option>
                        <?php foreach (($organs ?? []) as $organ): ?>
                            <?php if (in_array($organ->name, ['Kidney', 'Part of Liver', 'Bone Marrow'])): ?>
                                <option value="<?= (int)$organ->id ?>"><?= htmlspecialchars($organ->name) ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group" style="display: none;">
                    <label class="form-label">Recipient Patient (Optional)</label>
                    <select class="form-select" id="lab-recipient-patient">
                        <option value="">Select Recipient Patient</option>
                    </select>
                    <input type="hidden" id="lab-recipient-id" value="">
                </div>
                <div class="form-group">
                    <label class="form-label">Select Tests <span style="color: #e74c3c;">*</span></label>
                    <div id="lab-tests-container" style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-top: 8px;"></div>
                    <input type="text" id="lab_test_type_other_input" class="form-input"
                        placeholder="Enter other test name(s)..." style="display: none; margin-top: 10px;">
                    <input type="hidden" id="lab-test-type" value="">
                </div>
                <!-- Scheduling Duration Logic -->
                <div class="form-group" id="scheduling-duration-group" style="display: none;">
                    <label class="form-label">Clinical Protocol Duration <span style="color: #e74c3c;">*</span></label>
                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px;">
                        <button class="duration-btn active" onclick="setScheduleDuration(1, this)" style="padding: 10px; border: 1px solid #e2e8f0; border-radius: 8px; background: white; font-weight: 700; cursor: pointer; font-size: 0.75rem;">1 Day</button>
                        <button class="duration-btn" onclick="setScheduleDuration(2, this)" style="padding: 10px; border: 1px solid #e2e8f0; border-radius: 8px; background: white; font-weight: 700; cursor: pointer; font-size: 0.75rem;">2 Days</button>
                        <button class="duration-btn" onclick="setScheduleDuration(3, this)" style="padding: 10px; border: 1px solid #e2e8f0; border-radius: 8px; background: white; font-weight: 700; cursor: pointer; font-size: 0.75rem;">3 Days</button>
                    </div>
                    <input type="hidden" id="lab-schedule-duration" value="1">
                </div>

                <div id="lab-dates-container">
                    <div class="form-group" id="date-group-1">
                        <label class="form-label" id="date-label-1">Test Date <span style="color: #e74c3c;">*</span></label>
                        <input type="date" class="form-input" id="lab-test-date">
                    </div>
                    <div class="form-group" id="date-group-2" style="display: none;">
                        <label class="form-label">Day 2 Test Date <span style="color: #e74c3c;">*</span></label>
                        <input type="date" class="form-input" id="lab-test-date-2">
                    </div>
                    <div class="form-group" id="date-group-3" style="display: none;">
                        <label class="form-label">Day 3 Test Date <span style="color: #e74c3c;">*</span></label>
                        <input type="date" class="form-input" id="lab-test-date-3">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Notes (Optional)</label>
                    <textarea class="form-textarea" id="lab-result-notes"
                        placeholder="Detailed test results and measurements..."></textarea>
                </div>
                <div class="form-group" style="display: none;">
                    <label class="form-label">Blood Type (if applicable)</label>
                    <select class="form-select" id="lab-blood-type">
                        <option value="">Select Blood Type</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                    </select>
                </div>
                <button class="btn btn-primary" onclick="saveLabReport()">Schedule Appointment</button>
            </div>
        </div>
    </div>