<?php
$pageTitle = 'LifeConnect — Register';
$pageKey = 'role';
$step = 1;
require __DIR__ . '/partials/head.view.php';
require __DIR__ . '/partials/header.view.php';
?>
<div class="card">
  <h2 class="card-title">Create an Account</h2>
  <p class="card-desc">Select your account type to get started</p>
  <div class="role-grid">
    <a class="role-card" data-role-link data-role="donor" href="<?= ROOT ?>/registration/donor?role=donor">
      <div class="rc-icon"><i class="fas fa-user"></i></div>
      <div class="rc-title">Individual (Donor)</div>
      <div class="rc-desc">Register as a donor or declare non-donor status</div>
    </a>
    <a class="role-card" data-role-link data-role="institution" href="<?= ROOT ?>/registration/institution?role=institution">
      <div class="rc-icon"><i class="fas fa-hospital-alt"></i></div>
      <div class="rc-title">Medical Institution</div>
      <div class="rc-desc">Register your hospital or medical school</div>
    </a>
  </div>
  <div class="login-link" style="margin-top:30px; border-top: 1px solid #e2e8f0; padding-top: 15px;">
      <a href="#" onclick="event.preventDefault(); openStatusModal();" style="font-weight: 500; font-size: 0.95rem;">Already submitted an application? Check status</a>
  </div>
  
  <div class="login-link" style="margin-top:20px; text-align: center;">
      <div style="margin-bottom: 10px; display: flex; gap: 15px; justify-content: center;">
          <a href="javascript:void(0)" onclick="openTerms()" class="legal-link-footer">Terms and Conditions</a>
          <a href="javascript:void(0)" onclick="openPrivacy()" class="legal-link-footer">Privacy Policy</a>
      </div>
      <span>Already have an account? <a href="<?= ROOT ?>/login">Sign in</a></span>
  </div>
</div>

<style>
.legal-link-footer {
    font-size: 0.8rem;
    color: #a0aec0; /* gray */
    text-decoration: none;
    transition: color 0.3s ease;
}
.legal-link-footer:hover {
    color: #3b82f6; /* blue */
    text-decoration: underline;
}
</style>
<?php require __DIR__ . '/partials/footer.view.php'; ?>
