<?php if(!defined('ROOT')) die(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $pageTitle ?? 'Medical School Portal' ?> | LifeConnect</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
  <link rel="stylesheet" href="<?= ROOT ?>/public/assets/css/medicalschools/medicalschools.css?v=<?= time() ?>">
</head>
<body>

<!-- Header -->
<header class="d-header">
  <div class="d-header__inner">
    <div class="logo">
      <button class="d-mobile-nav-toggle" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
      <img src="<?= ROOT ?>/public/assets/images/logo.png" alt="LifeConnect">
      <div>
        <strong>LifeConnect</strong>
        <p style="margin:0; font-size:.68rem; color:var(--g500);">Medical School Portal</p>
      </div>
    </div>
    <div class="d-header__right">

      <div class="d-user-chip" style="position: relative;" onclick="document.getElementById('medicalSchoolDropdown').classList.toggle('show')">
        <div class="d-user-avatar" style="cursor: pointer;"><?= isset($school) ? strtoupper(substr($school->school_name ?? 'MS', 0, 2)) : 'MS' ?></div>
        <div style="cursor: pointer;">
          <div class="d-user-chip__name"><?= $school->school_name ?? 'Medical School' ?></div>
          <div class="d-user-chip__badge" style="background:var(--blue-100); color:var(--blue-700);"><i class="fas fa-microscope"></i> Anatomy Dept</div>
        </div>
        <div id="medicalSchoolDropdown" class="d-notif-panel" style="display: none; top: 100%; right: 0; min-width: 150px;">
            <div class="d-notif-item" style="cursor: pointer;" onclick="window.location.href='<?= ROOT ?>/logout'">
                <div class="d-notif-item__title"><i class="fas fa-sign-out-alt" style="color:var(--danger, #ef4444);"></i> Logout</div>
            </div>
        </div>
      </div>
    </div>
  </div>
</header>

<div class="d-shell">
