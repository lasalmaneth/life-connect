<div class="modal" id="test-result-modal">
        <div class="modal-content" style="max-width: 560px;">
            <div class="modal-header">
                <h3>Upload Test Result</h3>
                <button class="modal-close" onclick="closeTestResultModal()">×</button>
            </div>
            <div>
                <div class="form-group">
                    <label class="form-label">Patient Type <span style="color: #e74c3c;">*</span></label>
                    <select class="form-select" id="tr-patient-type" onchange="toggleTestResultPatientType()">
                        <option value="DONOR" selected>Donor</option>
                        <option value="RECIPIENT">Recipient</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Select Donor <span style="color: #e74c3c;">*</span></label>
                    <select class="form-select" id="tr-donor-select">
                        <option value="">Select a Donor</option>
                    </select>
                </div>
                <div class="form-group" id="tr-recipient-wrap" style="display:none;">
                    <label class="form-label">Select Recipient <span style="color: #e74c3c;">*</span></label>
                    <select class="form-select" id="tr-recipient-select">
                        <option value="">Select a Recipient</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Test Name <span style="color: #e74c3c;">*</span></label>
                    <input type="text" class="form-input" id="tr-test-name" placeholder="e.g., CBC / LFT / Kidney Function" />
                </div>
                <div class="form-group">
                    <label class="form-label">Test Date <span style="color: #e74c3c;">*</span></label>
                    <input type="date" class="form-input" id="tr-test-date" />
                </div>
                <div class="form-group">
                    <label class="form-label">Result Value (Optional)</label>
                    <input type="text" class="form-input" id="tr-result-value" placeholder="e.g., Normal / Positive / 12.5 g/dL" />
                </div>
                <div class="form-group">
                    <label class="form-label">Document (Optional: PDF/Image)</label>
                    <input type="file" class="form-input" id="tr-document" accept=".pdf,.png,.jpg,.jpeg,.webp" />
                </div>

                <div style="display:flex; gap:10px; justify-content:flex-end;">
                    <button class="btn btn-secondary" onclick="closeTestResultModal()">Cancel</button>
                    <button class="btn btn-primary" onclick="submitTestResult()">Upload</button>
                </div>
            </div>
        </div>
    </div>