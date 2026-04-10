<?php
$step = $step ?? 1;
$stepTotal = $stepTotal ?? 4;
$step3Label = $step3Label ?? 'Donation';
$step4Label = $step4Label ?? 'Review';
$fillPct = $stepTotal > 1 ? round(($step - 1) / ($stepTotal - 1) * 100) : 0;
$step1Class = $step > 1 ? 'done' : ($step === 1 ? 'active' : '');
$step2Class = $step > 2 ? 'done' : ($step === 2 ? 'active' : '');
$step3Class = $step > 3 ? 'done' : ($step === 3 ? 'active' : '');
$step4Class = $step > 4 ? 'done' : ($step === 4 ? 'active' : '');
?>
<div class="container">
  <div class="header">
    <div class="logo" style="text-align: center; margin-bottom: 10px;">
      <img src="<?= ROOT ?>/public/assets/images/logo.png" alt="LifeConnect Logo"
        style="max-height: 60px; transition: transform 0.2s; cursor: pointer;"
        onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
    </div>
    <h1>LifeConnect</h1>
    <p>Sri Lankan Organ &amp; Body Donation Registration System</p>
  </div>

  <?php if (!isset($hideProgress) || !$hideProgress): ?>
    <div class="progress-bar" id="progressBar">
      <div class="progress-steps">
        <div class="progress-line">
          <div class="progress-line-fill" id="pFill" style="width:<?php echo (int) $fillPct; ?>%"></div>
        </div>
        <div class="step <?php echo $step1Class; ?>" id="ps1">
          <div class="step-circle">1</div>
          <div class="step-lbl">Role</div>
        </div>
        <div class="step <?php echo $step2Class; ?>" id="ps2">
          <div class="step-circle">2</div>
          <div class="step-lbl">Details</div>
        </div>
        <div class="step <?php echo $step3Class; ?>" id="ps3">
          <div class="step-circle">3</div>
          <div class="step-lbl" id="ps3lbl"><?php echo htmlspecialchars($step3Label); ?></div>
        </div>
        <div class="step <?php echo $step4Class; ?>" id="ps4">
          <div class="step-circle">4</div>
          <div class="step-lbl"><?php echo htmlspecialchars($step4Label); ?></div>
        </div>
      </div>
    </div>
  <?php endif; ?>