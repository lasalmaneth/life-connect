</div>

<!-- OTP Verification Modal -->
<div class="modal-overlay" id="otpModal">
    <div class="modal-content text-center">
        <h3 class="modal-title">Verify Your Email</h3>
        <p class="modal-desc" style="margin-bottom:20px;">Please enter the 6-digit verification code sent to <br><strong id="otpDisplayEmail" style="color:var(--primary-color);"></strong></p>
        
        <div class="otp-inputs" style="display:flex; justify-content:center; gap:8px; margin-bottom:20px;">
            <input type="text" maxlength="1" id="otp_1" class="otp-box" onkeyup="moveOtpFocus(this, 1, event)" onpaste="handleOtpPaste(event)">
            <input type="text" maxlength="1" id="otp_2" class="otp-box" onkeyup="moveOtpFocus(this, 2, event)" onpaste="handleOtpPaste(event)">
            <input type="text" maxlength="1" id="otp_3" class="otp-box" onkeyup="moveOtpFocus(this, 3, event)" onpaste="handleOtpPaste(event)">
            <input type="text" maxlength="1" id="otp_4" class="otp-box" onkeyup="moveOtpFocus(this, 4, event)" onpaste="handleOtpPaste(event)">
            <input type="text" maxlength="1" id="otp_5" class="otp-box" onkeyup="moveOtpFocus(this, 5, event)" onpaste="handleOtpPaste(event)">
            <input type="text" maxlength="1" id="otp_6" class="otp-box" onkeyup="moveOtpFocus(this, 6, event)" onpaste="handleOtpPaste(event)">
        </div>
        
        <div id="otpModalErr" style="display:none; color:var(--danger); margin-bottom:15px; font-weight:500;"></div>
        
        <button type="button" class="btn btn-primary" style="width:100%; margin-bottom: 10px;" onclick="verifyOtpModal()" id="btnVerifyModal">Verify Code</button>
        <button type="button" class="btn btn-outline" style="width:100%; margin-bottom: 20px;" onclick="resendOtpModal()" id="btnResendModal">Resend OTP (60s)</button>
        
        <a href="#" onclick="event.preventDefault(); closeOtpModal();" style="color:var(--text-muted); font-size:0.9rem; text-decoration:underline;">Change Email Address</a>
    </div>
</div>

<!-- Status Check Modal -->
<div class="modal-overlay" id="statusModal">
    <div class="modal-content text-center">
        <button type="button" class="modal-close" onclick="closeStatusModal()" style="position:absolute; top:15px; right:15px; background:none; border:none; font-size:1.2rem; cursor:pointer; color:var(--text-muted);"><i class="fas fa-times"></i></button>
        <h3 class="modal-title" style="margin-top:10px;">Check Application Status</h3>
        <p class="modal-desc" style="margin-bottom:20px;">Enter your Username, Email, or NIC number to view your current review status.</p>
        
        <div class="form-group" style="text-align:left;">
            <input type="text" id="statusModal_input" placeholder="e.g. 200012345678" style="width:100%; padding:0.75rem 1rem; border:1px solid #cbd5e1; border-radius:6px; margin-bottom:15px;">
        </div>
        <div id="statusModalResult" style="margin-bottom:15px; font-weight:600; font-size:1.05rem; min-height:24px;"></div>
        <button type="button" class="btn btn-primary" style="width:100%;" onclick="checkRegStatusModal()">Check Status</button>
    </div>
</div>

<!-- Legal Modals (Terms & Privacy) -->
<div id="termsModal" class="modal-overlay">
    <div class="modal-container-legal">
        <div class="modal-header-legal">
            <h2>Terms and Conditions – Life Connect</h2>
            <button class="close-legal" onclick="closeTerms()">&times;</button>
        </div>
        <div class="modal-body-legal">
            <div class="legal-content">
                <section>
                    <h3>1. Introduction</h3>
                    <p>Life Connect is a digital platform designed to facilitate and manage organ donation, body donation, aftercare support, and related contributions by connecting donors, custodians, hospitals, medical institutions, recipients, and supporters. By using this system, you agree to comply with the following terms and conditions.</p>
                </section>
                <section>
                    <h3>2. Purpose of the Platform</h3>
                    <p>Life Connect is intended for the registration, coordination, and management of donation processes, recipient tracking (aftercare patients), and financial contributions. The platform does not replace medical, legal, or institutional decision-making.</p>
                </section>
                <section>
                    <h3>3. User Responsibilities</h3>
                    <p>All users must provide accurate, complete, and up-to-date information. This includes donors, custodians, medical institutions, recipients, and financial contributors. Misleading or false information may result in suspension or termination of access.</p>
                </section>
                <section>
                    <h3>4. Consent and Authorization</h3>
                    <p>All donations must be based on valid consent. Donor consent provided during lifetime or authorization by a legally recognized custodian after death must be respected. Users must ensure they have proper authority to act in any donation-related process.</p>
                </section>
                <section>
                    <h3>5. Institutional Authority</h3>
                    <p>Hospitals and medical schools have full authority to accept, reject, or discontinue any donation or medical process based on medical, legal, ethical, or operational requirements. Life Connect does not guarantee acceptance or outcome.</p>
                </section>
                <section>
                    <h3>6. Time-Sensitive Nature of Donations</h3>
                    <p>Users acknowledge that organ donation is highly time-sensitive and may become unviable if delayed. The platform is designed to support timely coordination, but delays in user actions may affect outcomes.</p>
                </section>
                <section>
                    <h3>7. Aftercare and Recipient Information</h3>
                    <p>The platform may include information related to transplant recipients or aftercare patients. Such data is handled with strict confidentiality and is used only for authorized medical, administrative, or support purposes. Users must not misuse or disclose recipient information.</p>
                </section>
                <section>
                    <h3>8. Financial Contributions and Donations</h3>
                    <p>Life Connect may facilitate financial contributions to support donation-related activities or patient care. All financial donors are responsible for ensuring that contributions are made voluntarily and appropriately. The platform does not guarantee specific allocation outcomes unless explicitly stated.</p>
                </section>
                <section>
                    <h3>9. Data Usage and Privacy</h3>
                    <p>All personal, medical, and contribution-related data collected through Life Connect will be used strictly for platform-related purposes. Reasonable security measures are applied; however, users acknowledge that no digital system is completely risk-free.</p>
                </section>
                <section>
                    <h3>10. Communication</h3>
                    <p>The platform may enable communication between custodians, institutions, recipients, and administrators. All communications sent through the system may be recorded and treated as official for coordination and reference purposes.</p>
                </section>
                <section>
                    <h3>11. Ethical Use</h3>
                    <p>All users must act respectfully and ethically when using the platform, particularly when dealing with deceased individuals, medical data, recipients, and sensitive situations.</p>
                </section>
                <section>
                    <h3>12. Limitation of Liability and Modifications</h3>
                    <p>Life Connect serves as a coordination platform and is not responsible for medical outcomes, institutional decisions, or financial allocations. The platform reserves the right to update these terms at any time. Continued use indicates acceptance of any changes.</p>
                </section>
            </div>
        </div>
        <div class="modal-footer-legal">
            <button class="btn btn-primary" onclick="closeTerms()" style="padding: 10px 40px;">I Understand</button>
        </div>
    </div>
</div>

<div id="privacyModal" class="modal-overlay">
    <div class="modal-container-legal">
        <div class="modal-header-legal">
            <h2>Privacy Policy – Life Connect</h2>
            <button class="close-legal" onclick="closePrivacy()">&times;</button>
        </div>
        <div class="modal-body-legal">
            <div class="legal-content">
                <section>
                    <h3>1. Data Collection</h3>
                    <p>Life Connect collects personal, medical, and contact information necessary for managing donation processes, aftercare support, and communication.</p>
                </section>
                <section>
                    <h3>2. Use of Information</h3>
                    <p>Collected data is used only for managing donation workflows, coordinating with hospitals and institutions, supporting aftercare patients, and handling communication and records.</p>
                </section>
                <section>
                    <h3>3. Data Sharing</h3>
                    <p>Information may be shared only with authorized hospitals, medical schools, system administrators, and relevant authorities when required. No data is shared for unrelated purposes.</p>
                </section>
                <section>
                    <h3>4. Confidentiality</h3>
                    <p>Sensitive data, especially medical and recipient information, is handled with strict confidentiality and access control.</p>
                </section>
                <section>
                    <h3>5. Data Security</h3>
                    <p>The platform applies reasonable technical and organizational measures to protect data. However, users acknowledge that no system is completely secure.</p>
                </section>
                <section>
                    <h3>6. User Rights</h3>
                    <p>Users may request to view their data, correct inaccurate information, and request deletion (subject to legal requirements).</p>
                </section>
                <section>
                    <h3>7. Data Retention</h3>
                    <p>Data is retained only as long as necessary for operational, legal, and administrative purposes.</p>
                </section>
                <section>
                    <h3>8. Financial Data</h3>
                    <p>Financial contribution records are stored securely and used only for tracking and reporting purposes.</p>
                </section>
                <section>
                    <h3>9. Cookies and System Logs</h3>
                    <p>The platform may use basic tracking (logs/cookies) to improve functionality and security.</p>
                </section>
                <section>
                    <h3>10. Updates to Policy</h3>
                    <p>This Privacy Policy may be updated periodically. Continued use of the platform indicates acceptance of changes.</p>
                </section>
            </div>
        </div>
        <div class="modal-footer-legal">
            <button class="btn btn-primary" onclick="closePrivacy()" style="padding: 10px 40px;">I Understand</button>
        </div>
    </div>
</div>

<style>
/* Modal Overlay Base Style */
.modal-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.4);
    backdrop-filter: blur(8px);
    z-index: 9999;
    align-items: center;
    justify-content: center;
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

.modal-overlay.show {
    display: flex !important;
}

/* Legal Modal Styling */
.modal-container-legal {
    background: white;
    width: 90%;
    max-width: 600px;
    max-height: 80vh;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    position: relative;
    animation: slideUp 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}

@keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
/* ... (existing styles) */
.modal-header-legal {
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.modal-header-legal h2 { font-size: 1.15rem; font-weight: 700; color: #1e293b; }
.close-legal { background: none; border: none; font-size: 1.5rem; color: #94a3b8; cursor: pointer; }
.modal-body-legal { padding: 1.5rem; overflow-y: auto; flex: 1; }
.legal-content section { margin-bottom: 1.5rem; }
.legal-content h3 { font-size: 0.9rem; font-weight: 700; color: #334155; margin-bottom: 0.5rem; }
.legal-content p { font-size: 0.85rem; color: #64748b; line-height: 1.6; text-align: justify; }
.modal-footer-legal {
    padding: 1rem 1.5rem;
    border-top: 1px solid #e2e8f0;
    display: flex;
    justify-content: center;
    background: #f8fafc;
}
/* Scrollbar */
.modal-body-legal::-webkit-scrollbar { width: 6px; }
.modal-body-legal::-webkit-scrollbar-track { background: #f1f5f9; }
.modal-body-legal::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
</style>

<script>
function openTerms(e) {
    if (e) {
        e.preventDefault();
        e.stopPropagation();
    }
    const m = document.getElementById('termsModal');
    m.style.display = 'flex';
    m.style.opacity = '1';
    m.style.pointerEvents = 'auto';
    document.body.style.overflow = 'hidden';
}
function closeTerms() {
    const m = document.getElementById('termsModal');
    m.style.display = 'none';
    m.style.opacity = '0';
    m.style.pointerEvents = 'none';
    document.body.style.overflow = 'auto';
}
function openPrivacy(e) {
    if (e) {
        e.preventDefault();
        e.stopPropagation();
    }
    const m = document.getElementById('privacyModal');
    m.style.display = 'flex';
    m.style.opacity = '1';
    m.style.pointerEvents = 'auto';
    document.body.style.overflow = 'hidden';
}
function closePrivacy() {
    const m = document.getElementById('privacyModal');
    m.style.display = 'none';
    m.style.opacity = '0';
    m.style.pointerEvents = 'none';
    document.body.style.overflow = 'auto';
}
// Handle outside clicks for all registration modals
window.addEventListener('click', function(event) {
    const tModal = document.getElementById('termsModal');
    const pModal = document.getElementById('privacyModal');
    const oModal = document.getElementById('otpModal');
    const sModal = document.getElementById('statusModal');
    
    // Only close if the exact overlay backdrop was clicked
    if (event.target.classList.contains('modal-overlay')) {
        if (event.target == tModal) closeTerms();
        if (event.target == pModal) closePrivacy();
        if (event.target == oModal) closeOtpModal();
        if (event.target == sModal) closeStatusModal();
    }
});
</script>

<script src="<?= ROOT ?>/assets/js/registration-split.js?v=<?= time() ?>"></script>
</body>
</html>
