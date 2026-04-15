<?php
$pageTitle = 'LifeConnect — Institution Registration';
$pageKey = 'institution';
$step = 2;
require __DIR__ . '/partials/head.view.php';
require __DIR__ . '/partials/header.view.php';
?>
<form method="post" action="<?= ROOT ?>/registration/institution" id="instForm">
<div class="card">
  <h2 class="card-title">Medical Institution</h2>
  <p class="card-desc">Provide your institution's details for admin verification</p>
  
  <div class="alert-box" id="instErr"><div class="alert-box-title">Please fix the following errors</div><ul id="instErrList"></ul></div>

  <input type="hidden" name="type" id="inst_type_input" value="hospital">

  <div class="section-div">Institution Type</div>
  <div class="inst-type-grid">
    <div class="inst-type-card" id="it_school" onclick="pickInstType('school'); document.getElementById('inst_type_input').value='school';">
      <div class="itc-icon"><i class="fas fa-graduation-cap"></i></div>
      <div class="itc-title">Medical School</div>
      <div class="itc-desc">Body donation for medical education</div>
    </div>
    <div class="inst-type-card sel" id="it_hospital" onclick="pickInstType('hospital'); document.getElementById('inst_type_input').value='hospital';">
      <div class="itc-icon"><i class="fas fa-hospital"></i></div>
      <div class="itc-title">Hospital / Transplant Center</div>
      <div class="itc-desc">Organ transplant and clinical programs</div>
    </div>
  </div>

  <div class="section-div">Institution Details</div>
  <div class="form-row">
    <div class="form-group full">
      <label>Institution Name <span class="req">*</span></label>
      <input type="text" name="name" id="inst_name" placeholder="Full registered name" maxlength="150" oninput="lv('inst_name','inst_nameH','Institution name is required')" value="<?= htmlspecialchars($_POST['name'] ?? $sessionData['name'] ?? '') ?>">
      <span class="hint" id="inst_nameH">Official registered name</span>
    </div>
    <div class="form-group">
      <label>Username <span class="req">*</span></label>
      <input type="text" name="username" id="inst_user" placeholder="e.g. colombo_gen_hospital" maxlength="30" oninput="onUsername('inst_user','inst_userH')" onchange="onUsername('inst_user','inst_userH')" value="<?= htmlspecialchars($_POST['username'] ?? $sessionData['username'] ?? '') ?>">
      <span class="hint" id="inst_userH">Letters, numbers and underscores only</span>
    </div>
    <div class="form-group">
      <label>Registration Number <span class="req">*</span></label>
      <input type="text" name="reg_no" id="inst_reg" placeholder="e.g. MOH/2024/00123" oninput="lv('inst_reg','inst_regH','Registration number is required')" value="<?= htmlspecialchars($_POST['reg_no'] ?? $sessionData['reg_no'] ?? '') ?>">
      <span class="hint" id="inst_regH">Official MOH / UGC registration number</span>
    </div>
    <div class="form-group" id="transplantRow" style="display:block"> <!-- Default block, JS handles toggle -->
      <label>Transplant Authorization ID <span class="req">*</span></label>
      <input type="text" name="transplant_id" id="inst_transplant" placeholder="e.g. TA/2024/00456" oninput="lv('inst_transplant','inst_transplantH','Transplant ID is required')" value="<?= htmlspecialchars($_POST['transplant_id'] ?? $sessionData['transplant_id'] ?? '') ?>">
      <span class="hint" id="inst_transplantH">Issued by Ministry of Health</span>
    </div>
    <div class="form-group">
      <label>Official Email <span class="req">*</span></label>
      <input type="email" name="email" id="inst_email" placeholder="admin@hospital.lk" oninput="onEmail('inst_email','inst_emailH')" onchange="onEmail('inst_email','inst_emailH')" value="<?= htmlspecialchars($_POST['email'] ?? $sessionData['email'] ?? '') ?>">
      <span class="hint" id="inst_emailH">A confirmation OTP will be sent to verify this address upon submission.</span>
    </div>
    <div class="form-group">
      <label>Contact Number <span class="req">*</span></label>
      <input type="tel" name="phone" id="inst_phone" placeholder="0112345678" maxlength="10" oninput="onPhone('inst_phone','inst_phoneH')" value="<?= htmlspecialchars($_POST['phone'] ?? $sessionData['phone'] ?? '') ?>">
      <span class="hint" id="inst_phoneH">10-digit phone number</span>
    </div>
    <div class="form-group full">
      <label>Address <span class="req">*</span></label>
      <input type="text" name="address" id="inst_addr" placeholder="Street, city, district" oninput="lv('inst_addr','inst_addrH','Address is required')" value="<?= htmlspecialchars($_POST['address'] ?? $sessionData['address'] ?? '') ?>">
      <span class="hint" id="inst_addrH">Physical address of the institution</span>
    </div>
    <div class="form-group full">
      <label>Password <span class="req">*</span></label>
      <div class="pw-wrap">
        <input type="password" name="password" id="inst_pw" placeholder="Minimum 8 characters" oninput="onInstPw()">
        <button type="button" class="eye-btn" onclick="tPw('inst_pw',this)"><i class="fas fa-eye"></i></button>
      </div>
      <div class="strength-row">
        <div class="sbar" id="isb1"></div><div class="sbar" id="isb2"></div>
        <div class="sbar" id="isb3"></div><div class="sbar" id="isb4"></div>
      </div>
      <span class="hint" id="inst_pwH">Must include uppercase, lowercase, number and special character</span>
    </div>
    <div class="form-group full">
      <label>Legal Agreement <span class="req">*</span></label>
      <div class="terms-row" id="termsRow" onclick="tToggle('inst_terms','termsRow','termsH')" style="padding: 10px 15px; border-radius: 8px;">
            <label class="checkbox-label" style="font-size:0.85rem; line-height:1.2; display: flex; align-items: center; gap: 10px; cursor: pointer;">
                <input type="checkbox" name="terms_agreed" id="inst_terms" required style="width:16px; height:16px; cursor: pointer;">
                <span>I agree to the <a href="#" onclick="openTerms(event)">Terms & Conditions</a> and <a href="#" onclick="openPrivacy(event)">Privacy Policy</a>.</span>
            </label>
      </div>
      <span class="hint" id="instTermsH">Institutional consent is mandatory</span>
    </div>
  </div>

  <style>
    .terms-row a {
        color: #64748b !important;
        text-decoration: none;
        transition: all 0.2s ease;
        font-weight: 600;
    }
    .terms-row a:hover {
        color: #3b82f6 !important;
        text-decoration: underline;
    }
  </style>
  <div class="btn-group">
    <a href="<?= ROOT ?>/signup" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back</a>
    <button type="button" class="btn btn-primary" onclick="instNext()">Review &amp; Submit <i class="fas fa-arrow-right"></i></button>
  </div>
  <div class="login-link">Already have an account? <a href="<?= ROOT ?>/login">Sign in</a></div>
  <div class="login-link" style="margin-top:4px;"><a href="#" onclick="event.preventDefault(); openStatusModal();" style="color:var(--text-muted); font-size:0.85rem;">Already submitted an application? <strong>Check status</strong></a></div>
</div>
</form> 
<?php require __DIR__ . '/partials/footer.view.php'; ?>
