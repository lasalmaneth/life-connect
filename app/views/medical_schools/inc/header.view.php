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
      <div style="position:relative;">
        <div class="d-bell" onclick="toggleNotifications()">
          <i class="fas fa-bell"></i>
          <span class="d-bell__badge">5</span>
        </div>
        <div id="notificationPanel" class="d-notif-panel">
          <div class="d-notif-panel__header"><i class="fas fa-bell"></i> Notifications</div>
          <div class="d-notif-item unread">
            <div class="d-notif-item__title"><i class="fas fa-file-signature" style="color:var(--blue-400);"></i> New Pre-Death Consent</div>
            <div class="d-notif-item__desc">John Doe submitted willingness form - pending verification</div>
            <div class="d-notif-item__time">1 hour ago</div>
          </div>
          <div class="d-notif-item unread">
            <div class="d-notif-item__title"><i class="fas fa-user-times" style="color:#f59e0b;"></i> Donor Withdrawal</div>
            <div class="d-notif-item__desc">Jane Smith cancelled donation - consent withdrawn</div>
            <div class="d-notif-item__time">3 hours ago</div>
          </div>
          <div class="d-notif-item unread">
            <div class="d-notif-item__title"><i class="fas fa-upload" style="color:#10b981;"></i> Documents Submitted</div>
            <div class="d-notif-item__desc">Custodian uploaded post-death documents for Mr. Ranjan Perera</div>
            <div class="d-notif-item__time">5 hours ago</div>
          </div>
          <div class="d-notif-item">
            <div class="d-notif-item__title"><i class="fas fa-check-circle" style="color:#10b981;"></i> Body Accepted</div>
            <div class="d-notif-item__desc">Physical verification completed for BODY-2025-001</div>
            <div class="d-notif-item__time">1 day ago</div>
          </div>
        </div>
      </div>
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
      <style>
          .d-user-chip .show { display: block !important; }
      </style>
    </div>
  </div>
</header>

<div class="d-shell">
