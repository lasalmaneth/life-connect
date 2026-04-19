<!-- Consent Details Modal -->
<div id="consent-details-modal" class="modal-overlay">
    <div class="modal-container" style="max-width: 900px; width: 95%;">
        <div class="modal-header">
            <div style="display: flex; align-items: center; gap: 15px;">
                <button onclick="closeConsentDetailsModal()" style="background: #f1f5f9; border: none; width: 32px; height: 32px; border-radius: 8px; color: #64748b; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s;">
                    <i class="fas fa-arrow-left" style="font-size: 0.9rem;"></i>
                </button>
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="width: 40px; height: 40px; background: rgba(37, 99, 235, 0.1); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #2563eb;">
                        <i class="fas fa-file-contract" style="font-size: 1.2rem;"></i>
                    </div>
                    <div>
                        <h3 style="margin: 0; font-size: 1.25rem; font-weight: 700; color: #0f172a;">Consent Record Details</h3>
                        <p style="margin: 2px 0 0; font-size: 0.8rem; color: #64748b;">Reviewing official legal donation consent document.</p>
                    </div>
                </div>
            </div>
            <button class="close-modal" onclick="closeConsentDetailsModal()">&times;</button>
        </div>
        
        <div class="modal-body" style="padding: 0;">
            <div style="display: grid; grid-template-columns: 350px 1fr; min-height: 500px;">
                <!-- Left Column: Patient Details -->
                <div style="background: #f8fafc; border-right: 1px solid #e2e8f0; padding: 25px; display: flex; flex-direction: column;">
                    <div style="margin-bottom: 20px;">
                        <button onclick="closeConsentDetailsModal()" style="background: white; border: 1.5px solid #e2e8f0; border-radius: 8px; padding: 8px 15px; color: #475569; font-weight: 700; font-size: 0.8rem; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: all 0.2s; box-shadow: 0 2px 4px rgba(0,0,0,0.02);" onmouseover="this.style.background='#f1f5f9'; this.style.borderColor='#cbd5e1';" onmouseout="this.style.background='white'; this.style.borderColor='#e2e8f0';">
                            <i class="fas fa-arrow-left"></i> Back to Registry
                        </button>
                    </div>
                    <div style="margin-bottom: 30px;">
                        <h4 style="margin: 0 0 15px; font-size: 0.9rem; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.05em; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-user-circle"></i> Personal Information
                        </h4>
                        
                        <div style="display: flex; flex-direction: column; gap: 15px;">
                            <div>
                                <label style="display: block; font-size: 0.75rem; color: #94a3b8; margin-bottom: 4px;">Full Name</label>
                                <div id="modal-donor-name" style="font-weight: 600; color: #1e293b; font-size: 1.1rem; line-height: 1.2;">-</div>
                            </div>
                            
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                                <div>
                                    <label style="display: block; font-size: 0.75rem; color: #94a3b8; margin-bottom: 4px;">NIC Number</label>
                                    <div id="modal-donor-nic" style="font-weight: 600; color: #1e293b;">-</div>
                                </div>
                                <div>
                                    <label style="display: block; font-size: 0.75rem; color: #94a3b8; margin-bottom: 4px;">Date of Birth</label>
                                    <div id="modal-donor-dob" style="font-weight: 600; color: #1e293b;">-</div>
                                </div>
                            </div>
                            
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                                <div>
                                    <label style="display: block; font-size: 0.75rem; color: #94a3b8; margin-bottom: 4px;">Gender</label>
                                    <div id="modal-donor-gender" style="font-weight: 600; color: #1e293b;">-</div>
                                </div>
                                <div>
                                    <label style="display: block; font-size: 0.75rem; color: #94a3b8; margin-bottom: 4px;">Nationality</label>
                                    <div id="modal-donor-nationality" style="font-weight: 600; color: #1e293b;">-</div>
                                </div>
                            </div>

                            <div>
                                <label style="display: block; font-size: 0.75rem; color: #94a3b8; margin-bottom: 4px;">Contact</label>
                                <div id="modal-donor-contact" style="font-weight: 600; color: #1e293b;">-</div>
                            </div>

                            <div>
                                <label style="display: block; font-size: 0.75rem; color: #94a3b8; margin-bottom: 4px;">Email</label>
                                <div id="modal-donor-email" style="font-weight: 600; color: #1e293b; font-size: 0.85rem; word-break: break-all;">-</div>
                            </div>
                        </div>
                    </div>
                    
                    <div style="margin-bottom: 30px; padding-top: 20px; border-top: 1px dashed #cbd5e1;">
                        <h4 style="margin: 0 0 15px; font-size: 0.9rem; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.05em; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-hand-holding-heart"></i> Consent Information
                        </h4>
                        
                        <div style="display: flex; flex-direction: column; gap: 15px;">
                            <div>
                                <label style="display: block; font-size: 0.75rem; color: #94a3b8; margin-bottom: 4px;">Pledged Organ</label>
                                <div id="modal-organ-name" style="font-weight: 700; color: #2563eb; font-size: 1rem;">-</div>
                            </div>
                            
                            <div>
                                <label style="display: block; font-size: 0.75rem; color: #94a3b8; margin-bottom: 4px;">Pledge Date</label>
                                <div id="modal-pledge-date" style="font-weight: 600; color: #1e293b;">-</div>
                            </div>
                            
                            <div>
                                <label style="display: block; font-size: 0.75rem; color: #94a3b8; margin-bottom: 4px;">Registry Status</label>
                                <div id="modal-pledge-status" class="status-badge" style="display: inline-block;">-</div>
                            </div>
                        </div>
                    </div>

                    <div style="margin-top: auto; padding: 15px; background: rgba(239, 68, 68, 0.05); border: 1px solid rgba(239, 68, 68, 0.1); border-radius: 12px;">
                        <h4 style="margin: 0 0 10px; font-size: 0.85rem; font-weight: 700; color: #ef4444; display: flex; align-items: center; gap: 6px;">
                            <i class="fas fa-flag"></i> Flag Record
                        </h4>
                        <p style="font-size: 0.75rem; color: #64748b; margin: 0 0 12px; line-height: 1.4;">Report discrepancies in patient information or consent documentation.</p>
                        <button class="btn btn-danger btn-small" style="width: 100%; justify-content: center; background: #fee2e2; color: #ef4444; border: 1px solid #fecaca; font-weight: 700;" onclick="flagConsentRecord()">Flag for Review</button>
                    </div>
                </div>

                <!-- Right Column: Document Viewer -->
                <div style="padding: 25px; display: flex; flex-direction: column;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <h4 style="margin: 0; font-size: 1rem; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 10px;">
                            <i class="fas fa-file-pdf" style="color: #ef4444;"></i> Consent Document PDF
                        </h4>
                        <div id="pdf-controls" style="display: none;">
                            <a id="modal-pdf-download" href="#" target="_blank" class="btn btn-secondary btn-small" style="background: white; border: 1px solid #e2e8f0; color: #475569; font-weight: 700;">
                                <i class="fas fa-download" style="margin-right: 6px;"></i> Download PDF
                            </a>
                        </div>
                    </div>
                    
                    <div id="pdf-viewer-container" style="flex: 1; border: 1px solid #e2e8f0; border-radius: 12px; background: #f1f5f9; overflow: hidden; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; padding: 40px; min-height: 400px;">
                        <!-- Initial state / Loading / Missing -->
                        <div id="pdf-placeholder" style="display: block;">
                            <div style="width: 70px; height: 70px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; color: #94a3b8; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
                                <i class="fas fa-clock" style="font-size: 1.8rem;"></i>
                            </div>
                            <h3 id="pdf-status-title" style="color: #475569; font-size: 1.1rem; font-weight: 700; margin-bottom: 8px;">Document is Pending</h3>
                            <p id="pdf-status-desc" style="color: #94a3b8; font-size: 0.9rem; margin: 0; max-width: 300px;">The formal signed consent document has not been uploaded to the registry yet.</p>
                        </div>
                        
                        <!-- PDF Embed -->
                        <iframe id="pdf-iframe" style="display: none; width: 100%; height: 100%; border: none; border-radius: 8px;"></iframe>
                    </div>
                    
                    <div style="margin-top: 20px; font-size: 0.75rem; color: #94a3b8; display: flex; align-items: flex-start; gap: 10px; background: #f8fafc; padding: 12px; border-radius: 8px;">
                        <i class="fas fa-info-circle" style="color: #3b82f6; margin-top: 2px;"></i>
                        <span style="line-height: 1.4;">This document is a legally binding consent form under the National Organ Donation Act. Any alterations or discrepancies must be reported immediately using the 'Flag Record' feature.</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal-footer" style="padding: 15px 25px; background: white; border-top: 1px solid #f1f5f9; display: flex; justify-content: flex-end; gap: 12px;">
            <button class="btn btn-secondary" onclick="closeConsentDetailsModal()" style="padding: 10px 24px; font-weight: 700; border-radius: 8px;">Close Record</button>
            <button id="modal-approve-btn" class="btn btn-primary" style="padding: 10px 24px; font-weight: 700; border-radius: 8px; background: #2563eb; display: none;" onclick="approveSelectedEligibility()">Approve Eligibility</button>
        </div>
    </div>
</div>

<style>
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(4px);
        display: none;
        align-items: flex-start;
        justify-content: center;
        z-index: 9999;
        padding: 40px 20px;
        overflow-y: auto;
    }
    
    .modal-overlay.show {
        display: flex;
        animation: fadeIn 0.3s ease-out;
    }
    
    .modal-container {
        background: white;
        border-radius: 20px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.3);
        overflow: hidden;
        animation: slideUp 0.3s ease-out;
        margin-bottom: 40px;
    }
    
    .modal-header {
        padding: 20px 25px;
        background: white;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .close-modal {
        background: none;
        border: none;
        font-size: 2rem;
        color: #94a3b8;
        cursor: pointer;
        line-height: 1;
        transition: color 0.2s;
    }
    
    .close-modal:hover {
        color: #475569;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    @keyframes slideUp {
        from { transform: translateY(20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
</style>
