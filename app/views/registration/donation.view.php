<?php
$pageTitle = 'LifeConnect — Donation Role Options (Preview)';
$pageKey = 'donation';
$step = 3;
require __DIR__ . '/partials/head.view.php';
require __DIR__ . '/partials/header.view.php';
?>
<div class="card">
  <h2 class="card-title">Donation Role Options (Preview)</h2>
  <p class="card-desc">The following donation roles are available in the system. You will be able to choose your preferred role from your profile dashboard after your account registration is approved.</p>
  <div class="info-box">
    <strong>Note:</strong> You are not required to select a donation role at this stage. This section is provided to help you understand the available donation roles. You may select your preferred role later from your profile dashboard after approval.
  </div>

  <div class="section-div">Donation Roles You Can Register For</div>

  <div class="don-option dis info-mode" onclick="toggleInfo(this)" style="opacity: 1; cursor: pointer; border-color: #e5e7eb;">
    <div class="don-content">
      <div class="don-lbl">
        Financial Donation
        <i class="fas fa-info-circle info-icon"></i>
      </div>
      <div class="don-desc">Contribute financially to support patient care programs, awareness initiatives, and medical research activities.</div>
      <div class="expandable-content">Financial donors help support treatment assistance programs, outreach activities, and hospital-led research initiatives.</div>
    </div>
  </div>

  <div class="don-option dis info-mode" onclick="toggleInfo(this)" style="opacity: 1; cursor: pointer; border-color: #e5e7eb;">
    <div class="don-content">
      <div class="don-lbl">
        Living Organ Donation
        <i class="fas fa-info-circle info-icon"></i>
      </div>
      <div class="don-desc">Register as a living organ donor (for example, kidney donation) after completing eligibility screening and medical approval procedures.</div>
      <div class="expandable-content">Living organ donation involves donating specific organs while alive, subject to detailed medical evaluation and approval by specialists.</div>
    </div>
  </div>

  <div class="don-option dis info-mode" onclick="toggleInfo(this)" style="opacity: 1; cursor: pointer; border-color: #e5e7eb;">
    <div class="don-content">
      <div class="don-lbl">
        Deceased Organ Donation
        <i class="fas fa-info-circle info-icon"></i>
      </div>
      <div class="don-desc">Register consent to donate organs and tissues after death to support life-saving transplantation programs.</div>
      <div class="expandable-content">Organs and tissues may be donated after death depending on medical suitability and hospital coordination procedures at the time.</div>
    </div>
  </div>

  <div class="don-option dis info-mode" onclick="toggleInfo(this)" style="opacity: 1; cursor: pointer; border-color: #e5e7eb;">
    <div class="don-content">
      <div class="don-lbl">
        Full Body Donation
        <i class="fas fa-info-circle info-icon"></i>
      </div>
      <div class="don-desc">Register for full body donation after death to support medical education and research conducted by authorized institutions.</div>
      <div class="expandable-content">Full body donation supports anatomy teaching and research programs in medical faculties, subject to institutional acceptance requirements.</div>
    </div>
  </div>

  <div class="don-option dis info-mode" onclick="toggleInfo(this)" style="opacity: 1; cursor: pointer; border-color: #e5e7eb;">
    <div class="don-content">
      <div class="don-lbl">
        Non-Donor
        <i class="fas fa-info-circle info-icon"></i>
      </div>
      <div class="don-desc">You may choose not to register as a donor at this time and update your decision later from your profile dashboard if you wish.</div>
      <div class="expandable-content">Selecting this option later means you are not participating in donation programs at the moment.</div>
    </div>
  </div>

  <p style="font-size: 14px; color: #4b5563; margin-top: 20px; text-align: center;">Final participation in any donation role requires completion of eligibility checks and institutional approval procedures.</p>

  <div class="btn-group">
    <a href="<?= ROOT ?>/registration/donor" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back</a>
    <button class="btn btn-primary" onclick="showReview()">Continue to Review <i class="fas fa-arrow-right"></i></button>
  </div>
</div>

<?php 
// State Injection Bridge
// Inject PHP Sesssion data into JS State so Review page (which relies on JS) has data
$donorData = $_SESSION['donor_registration'] ?? [];
// Prepare data for JS
$jsData = [
    'firstName' => $donorData['first_name'] ?? '',
    'lastName' => $donorData['last_name'] ?? '',
    'username' => $donorData['username'] ?? '',
    'nic' => $donorData['nic'] ?? '',
    'dobDisplay' => $donorData['dob'] ?? '',
    'gender' => $donorData['gender'] ?? '',
    'phone' => $donorData['phone'] ?? '',
    'email' => $donorData['email'] ?? ''
];
?>
<script>
window.onload = function() {
    // Determine if we need to sync. 
    // Always sync just in case user edited in PHP form.
    var phpDonor = <?= json_encode($jsData) ?>;
    if(phpDonor.firstName) {
        // We use a small timeout to ensure registration-split.js is loaded
        setTimeout(function(){
            if(window.mergeSection) {
                mergeSection('donor', phpDonor);
                mergeState({role: 'donor'}); 
                populateDonationFromState();
            }
        }, 300);
    }
    
    // Helper to go to review
    window.showReview = function() {
        window.location.href = "<?= ROOT ?>/registration/review";
    };
};

function toggleInfo(container) {
    const expandable = container.querySelector('.expandable-content');
    
    // Toggle current one
    if (expandable.classList.contains('open')) {
        expandable.classList.remove('open');
    } else {
        // Optional: close other open panels
        document.querySelectorAll('.expandable-content.open').forEach(el => el.classList.remove('open'));
        expandable.classList.add('open');
    }
}
</script>

<?php require __DIR__ . '/partials/footer.view.php'; ?>
