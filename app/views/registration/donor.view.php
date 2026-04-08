<?php
$pageTitle = 'LifeConnect — Donor Registration';
$pageKey = 'donor';
$step = 2;
require __DIR__ . '/partials/head.view.php';
require __DIR__ . '/partials/header.view.php';
?>
<form id="donorForm" method="post" action="<?= ROOT ?>/registration/donor" onsubmit="event.preventDefault(); donorNext();">
  <div class="card">
  <h2 class="card-title">Basic Information</h2>
  <p class="card-desc">Your NIC number will automatically fill in your date of birth and gender</p>
  
  <div class="info-box">
    <strong>Privacy Notice —</strong> Your NIC is used solely to extract Date of Birth and Gender as per Sri Lankan national identity standards. It is stored securely and never shared.
  </div>
  
  <?php if (!empty($errors)): ?>
  <div class="alert-box show" id="donorErr">
      <div class="alert-box-title">Please fix the following errors</div>
      <ul id="donorErrList">
          <?php foreach ($errors as $error): ?>
              <li><?= $error ?></li>
          <?php endforeach; ?>
      </ul>
  </div>
  <?php else: ?>
  <div class="alert-box" id="donorErr"><div class="alert-box-title">Please fix the following errors</div><ul id="donorErrList"></ul></div>
  <?php endif; ?>

  <div class="form-row">
    <div class="form-group">
      <label>First Name <span class="req">*</span></label>
      <input type="text" name="first_name" id="d_fname" placeholder="First name" maxlength="50" oninput="lv('d_fname','d_fnameH','First name is required')" value="<?= htmlspecialchars($_POST['first_name'] ?? $sessionData['first_name'] ?? '') ?>">
      <span class="hint" id="d_fnameH">As it appears on your NIC</span>
    </div>
    <div class="form-group">
      <label>Last Name <span class="req">*</span></label>
      <input type="text" name="last_name" id="d_lname" placeholder="Last name" maxlength="50" oninput="lv('d_lname','d_lnameH','Last name is required')" value="<?= htmlspecialchars($_POST['last_name'] ?? $sessionData['last_name'] ?? '') ?>">
      <span class="hint" id="d_lnameH">As it appears on your NIC</span>
    </div>
    <div class="form-group">
      <label>Username <span class="req">*</span></label>
      <input type="text" name="username" id="d_user" placeholder="e.g. kamal_perera" maxlength="30" oninput="onUsername('d_user','d_userH')" value="<?= htmlspecialchars($_POST['username'] ?? $sessionData['username'] ?? '') ?>">
      <span class="hint" id="d_userH">Letters, numbers and underscores only</span>
    </div>
    <div class="form-group">
      <label>NIC Number <span class="req">*</span></label>
      <input type="text" name="nic" id="d_nic" placeholder="e.g. 200012345678 or 841234567V" maxlength="12" oninput="onNIC()" value="<?= htmlspecialchars($_POST['nic'] ?? $sessionData['nic'] ?? '') ?>">
      <span class="hint" id="d_nicH">New 12-digit format or old 9-digit + V / X</span>
    </div>
    <div class="form-group">
      <label>Date of Birth <span class="badge-auto">Auto</span></label>
      <input type="text" name="dob" id="d_dob" readonly placeholder="Extracted from NIC" value="<?= htmlspecialchars($_POST['dob'] ?? $sessionData['dob'] ?? '') ?>">
      <span class="hint ok" id="d_dobH" style="display:none">Extracted from NIC</span>
    </div>
    <div class="form-group">
      <label>Gender <span class="badge-auto">Auto</span></label>
      <input type="text" name="gender" id="d_gender" readonly placeholder="Extracted from NIC" value="<?= htmlspecialchars($_POST['gender'] ?? $sessionData['gender'] ?? '') ?>">
      <span class="hint ok" id="d_genderH" style="display:none">Extracted from NIC</span>
    </div>
    <div class="form-group">
      <label>Phone Number <span class="req">*</span></label>
      <input type="tel" name="phone" id="d_phone" placeholder="0771234567" maxlength="10" oninput="onPhone('d_phone','d_phoneH')" value="<?= htmlspecialchars($_POST['phone'] ?? $sessionData['phone'] ?? '') ?>">
      <span class="hint" id="d_phoneH">10 digits, starting with 0</span>
    </div>
    <div class="form-group full">
      <label>Email Address <span class="req">*</span></label>
      <input type="email" name="email" id="d_email" placeholder="your.email@example.com" oninput="onEmail('d_email','d_emailH')" value="<?= htmlspecialchars($_POST['email'] ?? $sessionData['email'] ?? '') ?>">
      <span class="hint" id="d_emailH">A confirmation OTP will be sent to verify this address upon submission.</span>
    </div>
    <div class="form-group full">
      <label>Password <span class="req">*</span></label>
      <div class="pw-wrap">
        <input type="password" name="password" id="d_pw" placeholder="Minimum 8 characters" oninput="onPw()">
        <button type="button" class="eye-btn" onclick="tPw('d_pw',this)"><i class="fas fa-eye"></i></button>
      </div>
      <div class="strength-row">
        <div class="sbar" id="sb1"></div><div class="sbar" id="sb2"></div>
        <div class="sbar" id="sb3"></div><div class="sbar" id="sb4"></div>
      </div>
      <span class="hint" id="d_pwH">Must include uppercase, lowercase, number and special character</span>
    </div>
    <div class="form-group full">
      <label>Confirm Password <span class="req">*</span></label>
      <div class="pw-wrap">
        <input type="password" name="confirm_password" id="d_cpw" placeholder="Re-enter your password" oninput="onCpw()">
        <button type="button" class="eye-btn" onclick="tPw('d_cpw',this)"><i class="fas fa-eye"></i></button>
      </div>
      <span class="hint" id="d_cpwH">Must match the password above</span>
    </div>
    <div class="form-group full">
      <label>Terms &amp; Conditions <span class="req">*</span></label>
      <div class="terms-row" id="termsRow" onclick="tToggle('d_terms','termsRow','termsH')">
        <input type="checkbox" name="terms" id="d_terms" onclick="event.stopPropagation(); tToggle('d_terms','termsRow','termsH', true)">
        <span>I agree to the <a href="#" onclick="event.stopPropagation()">Terms &amp; Conditions</a> and <a href="#" onclick="event.stopPropagation()">Privacy Policy</a> of LifeConnect.</span>
      </div>
      <span class="hint" id="termsH">You must accept the terms to continue</span>
    </div>
  </div>
  <div class="btn-group">
    <a href="<?= ROOT ?>/signup" class="btn btn-outline" ><i class="fas fa-arrow-left"></i> Back</a>
    <button type="submit" name="basicinfo" class="btn btn-primary" >Next <i class="fas fa-arrow-right"></i></button>
  </div>
  <div class="login-link">Already have an account? <a href="<?= ROOT ?>/login">Sign in</a></div>
  <div class="login-link" style="margin-top:4px;"><a href="#" onclick="event.preventDefault(); openStatusModal();" style="color:var(--text-muted); font-size:0.85rem;">Already submitted an application? <strong>Check status</strong></a></div>
</div>

</form>

<?php require __DIR__ . '/partials/footer.view.php'; ?>
