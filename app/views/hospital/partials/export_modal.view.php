<div class="modal" id="export-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Export Recipient Records</h3>
                <button class="modal-close" onclick="closeExportModal()">×</button>
            </div>
            <div>
                <div class="form-group">
                    <label class="form-label">Select Export Format</label>
                    <select class="form-select" id="export-format">
                        <option value="">Choose format...</option>
                        <option value="xlsx">Excel (.xlsx) - For data analysis</option>
                        <option value="csv">CSV (.csv) - For generic data use</option>
                        <option value="pdf">PDF (.pdf) - For formal reports</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Include Sections</label>
                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                        <label style="display: flex; align-items: center; gap: 0.5rem;">
                            <input type="checkbox" checked disabled> Recipient Details
                        </label>
                        <label style="display: flex; align-items: center; gap: 0.5rem;">
                            <input type="checkbox" checked> Treatment History
                        </label>
                        <label style="display: flex; align-items: center; gap: 0.5rem;">
                            <input type="checkbox"> Clinical Remarks
                        </label>
                    </div>
                </div>
                <button class="btn btn-primary" onclick="downloadExport()">Download Report</button>
            </div>
        </div>
    </div>