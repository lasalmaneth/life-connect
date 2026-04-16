<?php
$pageTitle = 'LifeConnect — Review';
$pageKey = 'review';
$step = 4;
require __DIR__ . '/partials/head.view.php';
require __DIR__ . '/partials/header.view.php';

// Bridge for Institution
$instData = $_SESSION['institution_registration'] ?? [];
$jsInstData = [
    'type' => $instData['type'] ?? 'hospital',
    'name' => $instData['name'] ?? '',
    'username' => $instData['username'] ?? '',
    'reg' => $instData['reg_no'] ?? '', 
    'transplant' => $instData['transplant_id'] ?? '',
    'email' => $instData['email'] ?? '',
    'phone' => $instData['phone'] ?? '',
    'address' => $instData['address'] ?? ''
];
?>
<style>
.edit-btn {
    background-color: #f8fafc;
    border: 1px solid #e2e8f0;
    color: var(--primary);
    cursor: pointer;
    font-size: 12px;
    font-weight: 600;
    padding: 6px 14px;
    border-radius: 6px;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.edit-btn:hover {
    background-color: var(--primary);
    color: #ffffff !important;
    border-color: var(--primary);
}
.edit-btn:focus-visible {
    outline: 2px solid var(--primary);
    outline-offset: 2px;
}
.edit-btn.save-btn {
    color: #059669 !important;
    border-color: #a7f3d0;
    background-color: #ecfdf5;
}
.edit-btn.save-btn:hover {
    background-color: #059669;
    color: #ffffff !important;
    border-color: #059669;
}
.edit-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    pointer-events: none;
}
</style>
<form id="reviewForm" method="POST" action="<?= ROOT ?>/registration/submit" data-otp-verified="false">
<div class="card">
  <?php if (!empty($_SESSION['reg_error'])): ?>
    <div id="regError" class="alert alert-danger" style="margin-bottom: 20px; padding: 15px; border-radius: 8px; background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; display: flex; align-items: center; gap: 10px;">
        <i class="fas fa-exclamation-circle"></i>
        <span><?= htmlspecialchars($_SESSION['reg_error']) ?></span>
    </div>
    <?php 
        $errorText = strtolower($_SESSION['reg_error'] ?? '');
        $autoTriggerOtp = (
            strpos($errorText, 'otp') !== false || 
            strpos($errorText, 'verification') !== false || 
            strpos($errorText, 'verified') !== false
        );
        unset($_SESSION['reg_error']); 
    ?>
  <?php endif; ?>

  <h2 class="card-title">Confirm Your Registration</h2>
  <p class="card-desc">Review your details carefully before submitting</p>
  
  <div id="sumDonor">
    <div class="sum-block">
      <div class="sum-head" style="justify-content: space-between;">
        <span><i class="fas fa-user"></i> Personal Information</span>
        <button type="button" id="btnEditDonor" class="edit-btn" onclick="toggleEditDonor()"><i class="fas fa-pen"></i> Edit Info</button>
      </div>
      <div class="sum-row"><span class="sum-key">First Name</span><span class="sum-val" id="sv_fname">—</span></div>
      <div class="sum-row"><span class="sum-key">Last Name</span><span class="sum-val" id="sv_lname">—</span></div>
      <div class="sum-row"><span class="sum-key">Username</span><span class="sum-val" id="sv_user">—</span></div>
      <div class="sum-row"><span class="sum-key">NIC</span><span class="sum-val" id="sv_nic">—</span></div>
      <div class="sum-row"><span class="sum-key">Date of Birth</span><span class="sum-val" id="sv_dob">—</span></div>
      <div class="sum-row"><span class="sum-key">Gender</span><span class="sum-val" id="sv_gender">—</span></div>
      <div class="sum-row"><span class="sum-key">Phone</span><span class="sum-val" id="sv_phone">—</span></div>
      <div class="sum-row"><span class="sum-key">Email</span><span class="sum-val" id="sv_email">—</span></div>
      <div class="sum-row"><span class="sum-key">Role</span><span class="sum-val">Individual (Donor)</span></div>
    </div>
    <div class="sum-block">
      <div class="sum-head">
        <span><i class="fas fa-heart"></i> Donation Intention</span>
      </div>
      <div class="sum-row"><span class="sum-key">Selected</span><span class="sum-val" id="sv_don">Not specified</span></div>
    </div>
  </div>

  <div id="sumInst" class="hidden">
    <div class="sum-block">
      <div class="sum-head" style="justify-content: space-between;">
        <span><i class="fas fa-hospital-alt"></i> Institution Details</span>
        <button type="button" id="btnEditInst" class="edit-btn" onclick="toggleEditInst()"><i class="fas fa-pen"></i> Edit Info</button>
      </div>
      <div class="sum-row"><span class="sum-key">Type</span><span class="sum-val" id="sv_itype">—</span></div>
      <div class="sum-row"><span class="sum-key">Registration No.</span><span class="sum-val" id="sv_ireg">—</span></div>
      <div class="sum-row" id="svTransplantRow"><span class="sum-key">Transplant Auth. ID</span><span class="sum-val" id="sv_itransplant">—</span></div>
      <div class="sum-row"><span class="sum-key">Email</span><span class="sum-val" id="sv_iemail">—</span></div>
      <div class="sum-row"><span class="sum-key">Phone</span><span class="sum-val" id="sv_iphone">—</span></div>
      <div class="sum-row"><span class="sum-key">Address</span><span class="sum-val" id="sv_iaddr">—</span></div>
      <div class="sum-row"><span class="sum-key">Role</span><span class="sum-val">Medical Institution</span></div>
    </div>
  </div>
  
  <input type="hidden" name="full_state_json" id="fullStateInput">

  <div class="btn-group" style="justify-content: center; gap: 16px; max-width: 600px; margin: 30px auto 0;">
    <button type="button" class="btn btn-outline" style="min-width: 140px;" onclick="reviewBack()"><i class="fas fa-arrow-left"></i> Back</button>
    <button type="submit" class="btn btn-success" style="min-width: 250px; font-weight: 500;">Confirm Registration</button>
  </div>
</div>
</form>

<script>
// Expose ROOT to javascript explicitly from PHP to ensure fetches work properly.
window.ROOT = '<?= ROOT ?>';
let editingSection = null;

function toggleEditDonor() {
    window.location.href = window.ROOT + '/registration/donor';
}

function toggleEditInst() {
    window.location.href = window.ROOT + '/registration/institution';
}



document.addEventListener('DOMContentLoaded', function() {
    var reviewForm = document.getElementById('reviewForm');
    if (!reviewForm) return;
    
    // Inject Institution Data
    var phpInst = <?= json_encode($jsInstData) ?>;
    if(phpInst.name) {
        setTimeout(function(){
            if(window.mergeSection) {
                window.mergeSection('institution', phpInst);
                  
                  // Fix: don't forcefully overwrite the role if they are actively registering as a donor
                  var currState = typeof window.readState === 'function' ? window.readState() : {};
                  if (currState.role !== 'donor') {
                      if (window.mergeState) window.mergeState({role: 'institution'});
                  }
                  
                  if (typeof window.buildReviewFromState === 'function') window.buildReviewFromState(); // Refresh UI
              }
          }, 300);
      }
                  
    // Attach submit handler to populate hidden input and intercept for OTP
    reviewForm.onsubmit = function(e) {
        try {
            // If already OTP-verified, fullStateInput was pre-populated in the OTP callback — just submit.
            if (reviewForm.dataset.otpVerified === "true") {
                // Safety: ensure fullStateInput has a value; if somehow empty, still populate it
                var si = document.getElementById('fullStateInput');
                if (si && (!si.value || si.value === '{}') && typeof window.readState === 'function') {
                    si.value = JSON.stringify(window.readState());
                }
                return true;
            }

            e.preventDefault();
            
            var state = typeof window.readState === 'function' ? window.readState() : {};
            var role = state.role || (phpInst.name ? 'institution' : 'donor');
            var phpEmail = <?= json_encode($_SESSION['donor_registration']['email'] ?? $_SESSION['institution_registration']['email'] ?? '') ?>;
            var email = phpEmail || (role === 'institution' ? (state.institution ? state.institution.email : '') : (state.donor ? state.donor.email : ''));

            if (typeof window.openOtpModal === 'function') {
                window.openOtpModal(role, String(email));
            } else {
                alert("Verifying your email is required, but the module failed to load.");
            }
            
            return false;
        } catch (error) {
            console.error("Error in onsubmit:", error);
            alert("A JavaScript error occurred: " + error.message);
            e.preventDefault();
            return false;
        }
    };

    // Auto-trigger OTP if directed back with OTP error
    <?php if (!empty($autoTriggerOtp)): ?>
    setTimeout(function() {
        var state = typeof window.readState === 'function' ? window.readState() : {};
        var role = state.role || (phpInst.name ? 'institution' : 'donor');
        var phpEmail = <?= json_encode($_SESSION['donor_registration']['email'] ?? $_SESSION['institution_registration']['email'] ?? '') ?>;
        var email = phpEmail || (role === 'institution' ? (state.institution ? state.institution.email : '') : (state.donor ? state.donor.email : ''));
        if (typeof window.openOtpModal === 'function') {
            window.openOtpModal(role, String(email));
        }
    }, 1000);
    <?php endif; ?>
});
</script>

<?php require __DIR__ . '/partials/footer.view.php'; ?>



