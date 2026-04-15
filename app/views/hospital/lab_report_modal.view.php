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
                            <option value="<?= (int)$organ->id ?>"><?= htmlspecialchars($organ->name) ?></option>
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
                <div class="form-group">
                    <label class="form-label">Test Date <span style="color: #e74c3c;">*</span></label>
                    <input type="date" class="form-input" id="lab-test-date">
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