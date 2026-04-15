<div class="modal" id="recipient-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add Recipient Patient</h3>
                <button class="modal-close" onclick="closeRecipientModal()">×</button>
            </div>
            <div>
                <div class="form-group">
                    <label class="form-label">Patient NIC <span style="color: #ef4444;">*</span></label>
                    <input type="text" class="form-input" id="recipient-nic" placeholder="1999XXXXXXX" onblur="validateAndFetchNIC()">
                    <small id="nic-error" style="color: #dc2626; display: none;"></small>
                    <small id="nic-loading" style="color: #0284c7; display: none;">Loading patient data...</small>
                </div>
                <div class="form-group">
                    <label class="form-label">Patient Name <span style="color: #ef4444;">*</span></label>
                    <input type="text" class="form-input" id="recipient-name" placeholder="Full name">
                </div>
                <div class="form-group">
                    <label class="form-label">Gender</label>
                    <input type="text" class="form-input" id="recipient-gender" placeholder="Male / Female" readonly style="background-color: #f3f4f6;">
                </div>
                <div class="form-group">
                    <label class="form-label">Select Organ <span style="color: #ef4444;">*</span></label>
                    <select class="form-select" id="recipient-organ">
                        <option value="">Select Organ</option>
                        <option value="Kidney">Kidney</option>
                        <option value="Part of Liver">Part of Liver</option>
                        <option value="Bone Marrow">Bone Marrow</option>
                        <option value="Cornea">Cornea</option>
                        <option value="Skin">Skin</option>
                        <option value="Bones">Bones</option>
                        <option value="Heart Valves">Heart Valves</option>
                        <option value="Tendons">Tendons</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Surgery Date <span style="color: #ef4444;">*</span></label>
                    <input type="date" class="form-input" id="surgery-date">
                </div>
                <div class="form-group">
                    <label class="form-label">Treatment Notes</label>
                    <textarea class="form-textarea" id="treatment-notes"
                        placeholder="Post-surgery treatment details..."></textarea>
                </div>
                <button class="btn btn-primary" onclick="saveRecipient()">Save Recipient</button>
            </div>
        </div>
    </div>