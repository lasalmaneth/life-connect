<?php
// FILE: app/views/financial_donor/layout.view.php

$donor_id_display = 'FD_' . str_pad($donor_data['donor_id'] ?? 0, 5, '0', STR_PAD_LEFT);
$donor_initial = strtoupper(substr($donor_data['full_name'] ?? 'D', 0, 1));
$donor_full_name = htmlspecialchars($donor_data['full_name'] ?? '');
$donor_role = "Registered Financial Donor";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LifeConnect — Financial Donor</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/financialdonor/financialdonor.css">
    <style>
        .modal-overlay {
            display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000;
            align-items: center; justify-content: center; backdrop-filter: blur(2px);
        }
        .modal-overlay.active { display: flex; animation: fadeIn 0.2s ease; }
        .modal-content {
            background: var(--white); border-radius: var(--r); padding: 2rem;
            width: 100%; max-width: 500px; box-shadow: 0 10px 40px rgba(0,91,170,0.15);
        }
    </style>
</head>
<body>

<div class="hdr">
    <div class="hdr-c">
        <div class="hdr-brand">
            <div class="hdr-logo"><i class="fas fa-hand-holding-dollar" style="color: var(--blue-600);"></i></div>
            <div>
                <h1>Financial Donor</h1>
                <span>Manage your profile and track impacts</span>
            </div>
        </div>
        <div class="hdr-right">
            <div class="hdr-badge">
                Status: <strong style="color: var(--success); display: inline;">Active</strong>
            </div>
            <div class="avatar"><?= $donor_initial ?></div>
            <button onclick="document.getElementById('logoutModal').classList.add('active')" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</button>
        </div>
    </div>
</div>

<div class="wrap">
    <aside class="sidebar">
        <div class="sb-hdr">
            <div class="donor-av"><?= $donor_initial ?></div>
            <div>
                <div class="sb-name"><?= $donor_full_name ?></div>
                <div class="sb-meta"><?= $donor_id_display ?></div>
            </div>
        </div>
        
        <nav>
            <div class="ms-title">Overview</div>
            <a href="<?= ROOT ?>/financial-donor" class="mi <?= ($pageKey ?? '') === 'dashboard' ? 'active' : '' ?>">
                <i class="fas fa-home"></i> Dashboard
            </a>
            <a href="<?= ROOT ?>/financial-donor/history" class="mi <?= ($pageKey ?? '') === 'history' ? 'active' : '' ?>">
                <i class="fas fa-history"></i> Donation History
            </a>
            <a href="<?= ROOT ?>/financial-donor/donate" class="mi <?= ($pageKey ?? '') === 'donate' ? 'active' : '' ?>">
                <i class="fas fa-hand-holding-heart"></i> Make a Donation
            </a>
            
            <div class="mdiv"></div>
            <div class="ms-title">Support</div>
            <a href="javascript:void(0)" class="mi" onclick="document.getElementById('settingsModal').classList.add('active')">
                <i class="fas fa-cog"></i> Settings
            </a>
        </nav>
        
        <div class="sb-foot">
            <div class="sb-foot-lbl">Role</div>
            <div class="sb-foot-row">
                <div class="co-av"><i class="fas fa-user"></i></div>
                <div style="font-size: 0.8rem; font-weight: 600; color: var(--text);"><?= $donor_role ?></div>
            </div>
        </div>
    </aside>

    <main class="content">
        <!-- Individual view contents will be injected below this layout -->

        <!-- Settings Modal (Injected) -->
        <div id="settingsModal" class="modal-overlay">
            <div class="modal-content">
                <div class="ch">
                    <div class="ct">Donor Profile Settings</div>
                    <button class="btn btn-g btn-sm" onclick="document.getElementById('settingsModal').classList.remove('active')"><i class="fas fa-times"></i></button>
                </div>
                
                <form id="settingsForm" method="POST" action="<?= ROOT ?>/financial-donor/update-profile">
                    <input type="hidden" name="action" value="update_profile">
                    
                    <div class="fst">Personal Information</div>
                    <div class="fg">
                        <label class="fl">Full Name <span class="req">*</span></label>
                        <input type="text" class="fc" name="full_name" value="<?= htmlspecialchars($donor_data['full_name'] ?? '') ?>" required>
                    </div>
                    
                    <div class="fr2">
                        <div class="fg">
                            <label class="fl">NIC Number</label>
                            <input type="text" class="fc" value="<?= htmlspecialchars($donor_data['nic_number'] ?? '') ?>" readonly style="background: var(--g50);">
                        </div>
                        <div class="fg">
                            <label class="fl">Joined</label>
                            <input type="text" class="fc" value="<?= isset($donor_data['registration_date']) ? date('M d, Y', strtotime($donor_data['registration_date'])) : 'N/A' ?>" readonly style="background: var(--g50);">
                        </div>
                    </div>
                    
                    <div class="fst" style="margin-top: 1rem;">Contact (Read Only)</div>
                    <div class="fr2">
                        <div class="fg">
                            <label class="fl">Phone</label>
                            <input type="text" class="fc" value="<?= htmlspecialchars($donor_data['contact_number'] ?? 'N/A') ?>" readonly style="background: var(--g50);">
                        </div>
                        <div class="fg">
                            <label class="fl">Email</label>
                            <input type="text" class="fc" value="<?= htmlspecialchars($donor_data['email'] ?? 'N/A') ?>" readonly style="background: var(--g50);">
                        </div>
                    </div>
                    
                    <div class="ir" style="margin-top: 1.5rem; background: transparent; padding: 0;">
                        <button type="button" class="btn btn-g" onclick="document.getElementById('settingsModal').classList.remove('active')">Cancel</button>
                        <button type="submit" class="btn btn-p">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Logout Modal -->
        <div id="logoutModal" class="modal-overlay">
            <div class="modal-content" style="max-width: 400px; text-align: center;">
                <div style="font-size: 3rem; color: var(--warning); margin-bottom: 1rem;"><i class="fas fa-sign-out-alt"></i></div>
                <h2 style="font-size: 1.5rem; color: var(--navy); margin-bottom: 0.5rem;">Confirm Logout</h2>
                <p style="color: var(--g500); margin-bottom: 2rem;">Are you sure you want to end your session?</p>
                <div class="g2" style="background: transparent; padding: 0;">
                    <button class="btn btn-g btn-fw" onclick="document.getElementById('logoutModal').classList.remove('active')">Cancel</button>
                    <a href="<?= ROOT ?>/logout" class="btn btn-d btn-fw" style="text-decoration: none; text-align: center;">Yes, Logout</a>
                </div>
            </div>
        </div>
