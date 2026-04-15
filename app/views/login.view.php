<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LifeConnect Login - Sri Lanka</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="<?= ROOT ?>/public/assets/css/style.css">
    <link rel="stylesheet" href="<?= ROOT ?>/public/assets/css/auth.css">
</head>
<body class="login-page-body">
    <div class="shape shape-1"></div>
    <div class="shape shape-2"></div>

    <div class="login-wrapper">
        <div class="login-card glass-card">
            <a href="<?= ROOT ?>/">
                <img src="<?= ROOT ?>/public/assets/images/logo.png" alt="LifeConnect Logo" class="login-logo">
            </a>
            <h1 class="text-gradient">Welcome back</h1>
            <p>Enter your credentials to access the portal</p>

            <div id="errorBox" class="error-message">
                <i class="fas fa-exclamation-circle"></i>
                <span id="errorText"></span>
            </div>

            <form id="loginForm">
                <div class="form-group">
                    <label for="username">Username</label>
                    <div class="input-wrapper">
                        <i class="fas fa-user"></i>
                        <input type="text" id="username" name="username" placeholder="johndoe" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" placeholder="••••••••" required>
                        <button type="button" class="eye-btn" onclick="tPw('password', this)"><i class="fas fa-eye"></i></button>
                    </div>
                </div>

                <div class="forgot-pw-wrapper">
                    <a href="<?= ROOT ?>/forgot-password" class="forgot-pw-link">Forgot password?</a>
                </div>

                <button type="submit" class="btn-premium btn-submit">
                    <span>Sign In</span>
                    <i class="fas fa-arrow-right"></i>
                </button>
            </form>

            <div class="login-footer">
                Don't have an account? <a href="<?= ROOT ?>/signup">Create an account</a><br>
                <div class="auth-footer-links">
                    <a href="javascript:void(0)" onclick="openTerms()" class="legal-link-footer">Terms and Conditions</a>
                    <a href="javascript:void(0)" onclick="openPrivacy()" class="legal-link-footer">Privacy Policy</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Terms and Conditions Modal -->
    <div id="termsModal" class="modal-overlay">
        <div class="modal-container">
            <div class="modal-header">
                <h2>Terms and Conditions – Life Connect</h2>
                <button class="modal-close" onclick="closeTerms()">&times;</button>
            </div>
            <div class="modal-body">
                <div class="terms-content">
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
            <div class="modal-footer">
                <button class="btn-premium" onclick="closeTerms()">
                    <span>I Understand</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Privacy Policy Modal -->
    <div id="privacyModal" class="modal-overlay">
        <div class="modal-container">
            <div class="modal-header">
                <h2>Privacy Policy – Life Connect</h2>
                <button class="modal-close" onclick="closePrivacy()">&times;</button>
            </div>
            <div class="modal-body">
                <div class="terms-content">
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
            <div class="modal-footer">
                <button class="btn-premium" onclick="closePrivacy()">
                    <span>I Understand</span>
                </button>
            </div>
        </div>
    </div>


    <script>
    function openTerms() {
        document.getElementById('termsModal').classList.add('show');
        document.body.style.overflow = 'hidden';
    }
    function closeTerms() {
        document.getElementById('termsModal').classList.remove('show');
        document.body.style.overflow = 'auto';
    }
    function openPrivacy() {
        document.getElementById('privacyModal').classList.add('show');
        document.body.style.overflow = 'hidden';
    }
    function closePrivacy() {
        document.getElementById('privacyModal').classList.remove('show');
        document.body.style.overflow = 'auto';
    }
    window.onclick = function(event) {
        let tModal = document.getElementById('termsModal');
        let pModal = document.getElementById('privacyModal');
        if (event.target == tModal) closeTerms();
        if (event.target == pModal) closePrivacy();
    }
    function tPw(id, btn) {
        var field = document.getElementById(id);
        if (!field || !btn) return;
        var icon = btn.querySelector("i");
        if (field.type === "password") {
            field.type = "text";
            if (icon) icon.classList.replace("fa-eye", "fa-eye-slash");
        } else {
            field.type = "password";
            if (icon) icon.classList.replace("fa-eye-slash", "fa-eye");
        }
    }

    function showError(message) {
        const errorBox = document.getElementById('errorBox');
        const errorText = document.getElementById('errorText');
        errorText.textContent = message;
        errorBox.classList.add('show');
    }

    function hideError() {
        document.getElementById('errorBox').classList.remove('show');
    }

    document.getElementById('loginForm').addEventListener('submit', function(e) {
        e.preventDefault();
        hideError();

        const username = document.getElementById('username').value.trim();
        const password = document.getElementById('password').value.trim();
        const submitBtn = this.querySelector('.btn-submit');
        
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span> Signing In...</span>';
        submitBtn.disabled = true;

        fetch('<?= ROOT ?>/login/verify', { 
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ username, password })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                submitBtn.innerHTML = '<i class="fas fa-check"></i><span> Success! Redirecting...</span>';

                setTimeout(() => {
                    const baseUrl = '<?= ROOT ?>/';
                    const userRole = (data.role || '').trim().toUpperCase();
                    switch (userRole) {
                        case 'AFTERCARE_PATIENT':
                            window.location.href = baseUrl + 'aftercare';
                            break;
                        case 'U_ADMIN':
                            window.location.href = baseUrl + 'user-admin';
                            break;
                        case 'F_ADMIN':
                            window.location.href = baseUrl + 'financial-admin';
                            break;
                        case 'AC_ADMIN':
                            window.location.href = baseUrl + 'aftercare-admin';
                            break;
                        case 'D_ADMIN':
                            window.location.href = baseUrl + 'donation-admin';
                            break;
                        case 'FINANCIAL_DONOR':
                            window.location.href = baseUrl + 'donor';
                            break;
                        case 'DONOR':
                            window.location.href = baseUrl + 'donor';
                            break;
                        case 'HOSPITAL':
                            window.location.href = baseUrl + 'hospital';
                            break;
                        case 'MEDICAL_SCHOOL':
                            window.location.href = baseUrl + 'medical-school';
                            break;
                        case 'CUSTODIAN':
                            if (data.must_change_credentials == 1) {
                                window.location.href = baseUrl + 'custodian/security-setup';
                            } else {
                                window.location.href = baseUrl + 'custodian/dashboard';
                            }
                            break;
                        default:
                            showError('Unknown user role: ' + userRole + '. Contact Administrator.');
                            submitBtn.innerHTML = '<span>Sign In</span> <i class="fas fa-arrow-right"></i>';
                            submitBtn.disabled = false;
                            // Remove auto-redirect to home on unknown roles so user can see the error
                    }
                }, 1000);
            } else {
                showError(data.message || 'Invalid username or password.');
                submitBtn.innerHTML = '<span>Sign In</span> <i class="fas fa-arrow-right"></i>';
                submitBtn.disabled = false;
            }
        })
        .catch(err => {
            console.error('Login error:', err);
            showError('An unexpected error occurred. Please try again.');
            submitBtn.innerHTML = '<span>Sign In</span> <i class="fas fa-arrow-right"></i>';
            submitBtn.disabled = false;
        });
    });
    </script>
</body>
</html>
