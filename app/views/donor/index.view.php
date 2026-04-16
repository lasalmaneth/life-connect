<?php
/**
 * Donor Portal — Overview Page
 */

// Age calculation
$age = 0;
if (!empty($donor_data['date_of_birth'])) {
    try {
        $dob = new DateTime($donor_data['date_of_birth']);
        $today = new DateTime();
        $age = $today->diff($dob)->y;
    } catch (Exception $e) {
        $age = 0;
    }
}

$donor_id = $donor_data['id'] ?? 0;

include __DIR__ . '/inc/header.view.php';
include __DIR__ . '/inc/sidebar.view.php';
?>

<main class="d-content">
    <div class="d-content__header">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <h2><i class="fas fa-home text-accent"></i> Dashboard Overview</h2>
                <p>Greetings,
                    <?= htmlspecialchars($donor_data['first_name'] ?? 'Donor') ?>! Here is your life-saving contribution
                    overview.
                </p>
            </div>

        </div>
    </div>
    
    <style>
        .d-overview-layout { display: flex; gap: 2rem; align-items: flex-start; }
        .d-profile-sidebar { flex: 0 0 320px; position: sticky; top: 2rem; display: grid; gap: 1.5rem; }
        .d-activity-feed { flex: 1; display: grid; gap: 1.5rem; }

        /* Profile Card */
        .d-profile-card { background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.05); text-align: center; padding-bottom: 1.5rem; border: 1px solid var(--g100); }
        .d-profile-card__banner { height: 80px; background: linear-gradient(135deg, var(--blue-600), var(--blue-800)); position: relative; }
        .d-profile-card__avatar { width: 100px; height: 100px; border-radius: 50%; border: 4px solid white; margin: -50px auto 1rem; position: relative; background: #f1f5f9; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; font-weight: 800; color: var(--blue-700); box-shadow: 0 4px 12px rgba(0,0,0,0.1); overflow: hidden; }
        .d-profile-card__info h3 { font-size: 1.25rem; font-weight: 800; color: var(--slate); margin: 0; }
        .d-profile-card__info p { font-size: 0.85rem; color: var(--g500); margin: 0.25rem 0 0; }

        /* Stats Grid */
        .d-stats-mini-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; padding: 0 1.5rem; }
        .d-stat-mini-card { background: #f8fafc; padding: 1rem; border-radius: 12px; border: 1.2px solid var(--g100); text-align: center; transition: all 0.2s; }
        .d-stat-mini-card:hover { transform: translateY(-2px); border-color: var(--blue-200); background: white; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .d-stat-mini-card i { font-size: 1.2rem; color: var(--blue-500); margin-bottom: 0.5rem; display: block; }
        .d-stat-mini-card span { display: block; font-size: 1.1rem; font-weight: 800; color: var(--slate); }
        .d-stat-mini-card label { font-size: 0.65rem; font-weight: 700; color: var(--g500); text-transform: uppercase; letter-spacing: 0.05em; }

        /* Profile Details */
        .d-sidebar-section { background: white; border-radius: 16px; padding: 1.5rem; border: 1px solid var(--g100); box-shadow: 0 4px 20px rgba(0,0,0,0.05); }
        .d-sidebar-section__title { font-size: 0.9rem; font-weight: 800; color: var(--slate); margin-bottom: 1.25rem; display: flex; align-items: center; gap: 10px; }
        .d-detail-item { margin-bottom: 1rem; }
        .d-detail-item label { display: block; font-size: 0.75rem; font-weight: 700; color: var(--g400); text-transform: uppercase; margin-bottom: 0.25rem; }
        .d-detail-item span { font-size: 0.9rem; font-weight: 600; color: var(--slate); display: flex; align-items: center; gap: 8px; }

        /* Activity Feed */
        .d-status-poster { background: white; border-radius: 16px; padding: 1.5rem; border: 1px solid var(--g200); box-shadow: 0 4px 20px rgba(0,0,0,0.05); }
        .d-status-poster textarea { width: 100%; border: none; background: #f8fafc; border-radius: 12px; padding: 1rem; font-size: 0.95rem; resize: none; min-height: 80px; margin-bottom: 1rem; border: 1px solid transparent; transition: all 0.2s; }
        .d-status-poster textarea:focus { background: white; border-color: var(--blue-200); outline: none; box-shadow: 0 0 0 4px var(--blue-50); }
        
        .d-feed-card { background: white; border-radius: 16px; padding: 1.5rem; border: 1px solid var(--g100); box-shadow: 0 4px 20px rgba(0,0,0,0.05); position: relative; animation: slideUp 0.4s ease-out backwards; }
        @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        
        .d-feed-card__header { display: flex; gap: 12px; align-items: center; margin-bottom: 1rem; }
        .d-feed-card__author-img { width: 40px; height: 40px; border-radius: 50%; background: var(--blue-50); color: var(--blue-600); display: flex; align-items: center; justify-content: center; font-weight: 800; }
        .d-feed-card__meta h4 { font-size: 0.95rem; font-weight: 700; color: var(--slate); margin: 0; }
        .d-feed-card__meta span { font-size: 0.75rem; color: var(--g400); }
        
        .d-feed-card__content { font-size: 0.95rem; color: var(--g700); line-height: 1.6; margin-bottom: 1.25rem; }
        .d-feed-card__actions { display: flex; gap: 1.5rem; border-top: 1px solid var(--g100); padding-top: 1rem; }
        .d-feed-action { background: none; border: none; font-size: 0.85rem; font-weight: 600; color: var(--g500); cursor: pointer; display: flex; align-items: center; gap: 6px; transition: all 0.2s; }
        .d-feed-action:hover { color: var(--blue-600); }
        .d-feed-action--active { color: var(--blue-600); }

        @media (max-width: 992px) {
            .d-overview-layout { flex-direction: column; }
            .d-profile-sidebar { flex: 1 1 auto; width: 100%; position: static; }
        }
    </style>

    <div class="d-content__body">
        <div class="d-overview-layout">
            
            <!-- Left Column: Profile Card & Stats -->
            <aside class="d-profile-sidebar">
                
                <div class="d-profile-card">
                    <div class="d-profile-card__banner"></div>
                    <div class="d-profile-card__avatar">
                        <?php if(!empty($donor_data['profile_image'])): ?>
                            <img src="<?= ROOT ?>/assets/uploads/profile/<?= $donor_data['profile_image'] ?>" alt="Profile" style="width:100%; height:100%; object-fit:cover;">
                        <?php else: ?>
                            <?= strtoupper(substr($donor_data['first_name'] ?? '?', 0, 1)) ?>
                        <?php endif; ?>
                    </div>
                    <div class="d-profile-card__info">
                        <h3><?= $donor_full_name ?></h3>
                        <p><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($donor_data['district'] ?? 'Sri Lanka') ?>, Sri Lanka</p>
                    </div>
                    
                    <?php 
                    $in_progress_donation = array_filter($pledged_organs ?? [], function($o) {
                        return ($o['status'] ?? '') === 'IN_PROGRESS';
                    });
                    $in_progress_donation = !empty($in_progress_donation) ? reset($in_progress_donation) : null;
                    ?>

                    <?php if ($in_progress_donation): ?>
                    <div style="background: linear-gradient(135deg, #e11d48, #9f1239); color: white; padding: 1rem; border-radius: 16px; margin: 0.5rem 1.5rem 1.25rem; text-align: left; font-size: 0.8rem; border: 1px solid rgba(255,255,255,0.15); box-shadow: 0 10px 25px rgba(225, 29, 72, 0.2); position: relative; overflow: hidden;">
                        <div style="position: absolute; top: -10px; right: -10px; font-size: 3.5rem; opacity: 0.12; transform: rotate(-15deg);">
                            <i class="fas fa-heartbeat"></i>
                        </div>
                        <div style="font-weight: 800; text-transform: uppercase; margin-bottom: 8px; display: flex; align-items: center; gap: 8px; letter-spacing: 0.08em; font-size: 0.65rem; color: rgba(255,255,255,0.95);">
                            <span style="width: 10px; height: 10px; background: #fff; border-radius: 50%; display: inline-block; animation: pulse 2s infinite; box-shadow: 0 0 10px #fff;"></span> In-Progress Donation
                        </div>
                        <div style="line-height: 1.4; position: relative; z-index: 1;">
                            <div style="font-size: 1.1rem; font-weight: 800; margin-bottom: 4px; color: white; letter-spacing: -0.01em;">
                                <?= htmlspecialchars($in_progress_donation['organ_name']) ?>
                            </div>
                            <div style="color: rgba(255,255,255,0.9); font-weight: 600; font-size: 0.8rem; display: flex; align-items: center; gap: 6px;">
                                <div style="width: 20px; height: 20px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.65rem;">
                                    <i class="fas fa-hospital-alt"></i>
                                </div>
                                <?= htmlspecialchars($in_progress_donation['hospital_name'] ?? 'Assigned Hospital') ?>
                            </div>
                            <style>
                                @keyframes pulse {
                                    0% {
                                        transform: scale(1);
                                        opacity: 1;
                                        box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.7);
                                    }

                                    70% {
                                        transform: scale(1.2);
                                        opacity: 0.4;
                                        box-shadow: 0 0 0 6px rgba(255, 255, 255, 0);
                                    }

                                    100% {
                                        transform: scale(1);
                                        opacity: 1;
                                        box-shadow: 0 0 0 0 rgba(255, 255, 255, 0);
                                    }
                                }
                            </style>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (($stats['total'] ?? 0) > 0): ?>
                        <div style="margin: 0.5rem 0 1rem; padding: 0 1.5rem;">
                            <button class="d-btn d-btn--primary d-btn--sm" style="width:100%; border-radius:10px;"
                                onclick="window.location.href='<?= ROOT ?>/donor/documents'">
                                <i class="fas fa-id-card"></i> Digital ID Card
                            </button>
                        </div>
                    <?php endif; ?>

                    <div class="d-stats-mini-grid" style="grid-template-columns: 1fr;">
                        <div class="d-stat-mini-card">
                            <i class="fas fa-hand-holding-heart"></i>
                            <span>
                                <?= count($pledged_organs ?? []) ?>
                            </span>
                            <label>Total Pledges</label>
                        </div>
                    </div>
                </div>

                <div class="d-sidebar-section">
                    <div class="d-sidebar-section__title"><i class="fas fa-info-circle text-accent"></i> About Me</div>
                    <div class="d-detail-item">
                        <label>National ID (NIC)</label>
                        <span><i class="fas fa-fingerprint text-blue-500" style="font-size:0.8rem;"></i> <?= htmlspecialchars($donor_data['nic_number'] ?? 'N/A') ?></span>
                    </div>
                    <div class="d-detail-item">
                        <label>Blood Group</label>
                        <span><i class="fas fa-tint text-danger" style="font-size:0.8rem;"></i> <?= htmlspecialchars($donor_data['blood_group'] ?? 'N/A') ?></span>
                    </div>
                    <div class="d-detail-item">
                        <label>Age</label>
                        <span><i class="fas fa-calendar-alt text-blue-500" style="font-size:0.8rem;"></i> <?= $age ?> Years</span>
                    </div>
                    <div class="d-detail-item">
                        <label>Contact Number</label>
                        <span><i class="fas fa-phone text-blue-500" style="font-size:0.8rem;"></i> <?= htmlspecialchars($donor_data['phone_number'] ?? 'N/A') ?></span>
                    </div>
                </div>

            </aside>

            <!-- Right Column: Activity Feed -->
            <div class="d-activity-feed">
                
                <!-- Completed Donations Acknowledgement -->
                <?php 
                $completed_list = array_filter($pledged_organs ?? [], function($o) {
                    return ($o['status'] ?? '') === 'COMPLETED' || (!empty($o['recovery_status']) && $o['recovery_status'] === 'RECOVERED');
                });
                if (!empty($completed_list)): foreach ($completed_list as $o): ?>
                    <div class="d-feed-card" style="border-left: 5px solid #10b981; background: #f0fdf4;">
                        <div class="d-feed-card__header">
                            <div class="d-feed-card__author-img" style="background:#dcfce7; color:#10b981;">
                                <i class="fas fa-award"></i>
                            </div>
                            <div class="d-feed-card__meta">
                                <h4>Life-Saving Contribution Completed</h4>
                                <span><i class="far fa-calendar-check"></i> Finalized on <?= !empty($o['consent_date']) ? date('M d, Y', strtotime($o['consent_date'])) : 'Recent' ?></span>
                            </div>
                            <button onclick="this.closest('.d-feed-card').style.display='none';" style="margin-left: auto; background: none; border: none; cursor: pointer; color: #10b981; font-size: 1.1rem; padding: 4px; opacity: 0.6; transition: opacity 0.2s;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.6'" title="Dismiss">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="d-feed-card__content">
                            <p style="font-weight: 700; color: #166534; font-size: 1.1rem; margin-bottom: 0.5rem;">Thank You, Hero!</p>
                            <p style="font-size: 0.9rem; color: #166534; line-height: 1.5;">
                                Your selfless contribution of your <strong><?= htmlspecialchars($o['organ_name']) ?></strong> has been successfully completed. 
                                We are profoundly grateful for your life-saving gift which has brought hope and healing to those in need.
                            </p>
                        </div>
                        <div class="d-feed-card__actions" style="border-color: #dcfce7;">
                            <button class="d-btn d-btn--primary d-btn--sm" style="border-radius: 50px; padding: 0.5rem 1.25rem;" onclick="window.location.href='<?=ROOT?>/donor/documents'">
                                <i class="fas fa-file-contract"></i> Download Certificates
                            </button>
                        </div>
                    </div>
                <?php endforeach; endif; ?>
                
                <!-- Financial Donation Acknowledgement -->
                <?php if (!empty($total_financial) && $total_financial > 0): ?>
                    <div class="d-feed-card" style="border-left: 5px solid #3b82f6; background: #eff6ff;">
                        <div class="d-feed-card__header">
                            <div class="d-feed-card__author-img" style="background:#dbeafe; color:#3b82f6;">
                                <i class="fas fa-hand-holding-dollar"></i>
                            </div>
                            <div class="d-feed-card__meta">
                                <h4>Life-Saving Financial Contribution</h4>
                                <span><i class="far fa-heart"></i> Total Impact Recorded</span>
                            </div>
                            <button onclick="this.closest('.d-feed-card').style.display='none';" style="margin-left: auto; background: none; border: none; cursor: pointer; color: #3b82f6; font-size: 1.1rem; padding: 4px; opacity: 0.6; transition: opacity 0.2s;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.6'" title="Dismiss">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="d-feed-card__content">
                            <p style="font-weight: 700; color: #1e40af; font-size: 1.1rem; margin-bottom: 0.1rem;">Thank You for Your Generosity!</p>
                        </div>
                        <div class="d-feed-card__actions" style="border-color: #dbeafe;">
                            <button class="d-btn d-btn--primary d-btn--sm" style="border-radius: 50px; padding: 0.5rem 1.25rem; background: #3b82f6;" onclick="window.location.href='<?=ROOT?>/donor/download-pdf?type=total_financial_certificate'">
                                <i class="fas fa-certificate"></i> Download Financial Certificate
                            </button>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Pending Pledges Tracking -->
                <div class="d-sidebar-section" style="border-left: 5px solid var(--blue-600);">
                    <div class="d-sidebar-section__title" style="margin-bottom:1rem;">
                        <i class="fas fa-clock text-blue-600"></i> Pending Pledges
                    </div>
                    <div style="display: flex; flex-wrap: wrap; gap: 0.75rem;">
                        <?php 
                        $pending_list = array_filter($pledged_organs ?? [], function($o) {
                            return ($o['status'] ?? '') === 'PENDING' && empty($o['signed_form_path']);
                        });
                        if (!empty($pending_list)): foreach ($pending_list as $o): ?>
                            <div style="background: var(--blue-50); border: 1px solid var(--blue-100); padding: 0.5rem 1rem; border-radius: 50px; font-size: 0.8rem; font-weight: 700; color: var(--blue-800); display: flex; align-items: center; gap: 8px;">
                                <i class="fas fa-hourglass-half" style="font-size: 0.7rem;"></i> <?= htmlspecialchars($o['organ_name']) ?>
                            </div>
                        <?php endforeach; else: ?>
                            <p style="color: var(--g400); font-size: 0.85rem; font-style: italic;">All pledged organs are currently verified or uploaded.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Recent Notifications -->
                <div class="d-sidebar-section__title" style="margin: 1rem 0 0.5rem; padding-left: 0.5rem;">
                    <i class="fas fa-bell text-accent"></i> Recent Notifications
                </div>

                <?php if (!empty($notifications)): ?>
                    <?php foreach ($notifications as $index => $notif): ?>
                        <div class="d-feed-card" style="animation-delay: <?= $index * 0.1 ?>s;">
                            <div class="d-feed-card__header">
                                <div class="d-feed-card__author-img">
                                    <i class="fas fa-hospital-user"></i>
                                </div>
                                <div class="d-feed-card__meta">
                                    <h4>Life-Connect System</h4>
                                    <span><i class="far fa-clock"></i> <?= date('M d, Y • H:i', strtotime($notif['created_at'])) ?></span>
                                </div>
                                <div style="margin-left: auto; color: var(--g300);"><i class="fas fa-ellipsis-h"></i></div>
                            </div>
                            <div class="d-feed-card__content">
                                <?= htmlspecialchars($notif['message']) ?>
                            </div>
                            <?php if(str_contains($notif['message'], 'pledge') || str_contains($notif['message'], 'donated')): ?>
                                <div style="margin-bottom: 1rem; border-radius: 12px; overflow: hidden; height: 120px; background: #f1f5f9; display: flex; align-items: center; justify-content: center; border: 1px solid var(--g100);">
                                    <i class="fas fa-hand-holding-heart" style="font-size: 3rem; color: var(--blue-200);"></i>
                                </div>
                            <?php endif; ?>
                            <div class="d-feed-card__actions">
                                <button class="d-feed-action d-feed-action--active"><i class="fas fa-heart"></i> Like</button>
                                <button class="d-feed-action"><i class="fas fa-comment"></i> Comment</button>
                                <button class="d-feed-action"><i class="fas fa-share"></i> Share</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="d-feed-card" style="text-align: center; padding: 3rem;">
                        <i class="fas fa-stream" style="font-size: 3rem; color: var(--g100); margin-bottom: 1rem;"></i>
                        <h4 style="color: var(--g400);">Your activity feed is empty.</h4>
                        <p style="color: var(--g400); font-size: 0.85rem;">Pledge an organ or schedule a health checkup to see updates here.</p>
                    </div>
                <?php endif; ?>

                <?php if ($latest_health): ?>
                    <div class="d-feed-card" style="border-left: 5px solid #10b981;">
                        <div class="d-feed-card__header">
                            <div class="d-feed-card__author-img" style="background:#dcfce7; color:#10b981;">
                                <i class="fas fa-stethoscope"></i>
                            </div>
                            <div class="d-feed-card__meta">
                                <h4>Medical Report Received</h4>
                                <span><i class="far fa-check-circle"></i> Verified by Hospital</span>
                            </div>
                        </div>
                        <div class="d-feed-card__content">
                            <div style="background: #f0fdf4; padding: 1rem; border-radius: 12px; border: 1px solid #dcfce7;">
                                <strong style="color: #166534;"><?= htmlspecialchars($latest_health->test_name) ?></strong>
                                <p style="margin: 0.5rem 0 0; font-size: 1.1rem; color: #10b981; font-weight: 800;"><?= htmlspecialchars($latest_health->result_value) ?></p>
                            </div>
                        </div>
                        <div class="d-feed-card__actions">
                            <button class="d-feed-action"><i class="fas fa-eye"></i> View Full Report</button>
                            <button class="d-feed-action"><i class="fas fa-download"></i> Save PDF</button>
                        </div>
                    </div>
                <?php endif; ?>

            </div>

        </div>
    </div>
</main>

<?php if ($is_first_login): ?>
    <!-- First Login: Role Selection Overlay -->
    <div id="firstLoginOverlay" class="d-modal active"
        style="background: rgba(255, 255, 255, 0.98); backdrop-filter: blur(8px);">
        <div class="d-modal__body"
            style="max-width: 900px; padding: 3rem; background: transparent; box-shadow: none; width: 100%;">
            <div style="text-align: center; margin-bottom: 3rem;">
                <h1 style="font-size: 2.5rem; color: var(--blue-800); font-weight: 800; margin-bottom: 1rem;">Choose how you
                    want to participate</h1>
                <p style="font-size: 1.1rem; color: var(--g500);">You can select one or more roles. You can add more roles
                    later anytime.</p>
            </div>

            <div class="selection-grid"
                style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.5rem; margin-bottom: 3rem;">
                <!-- Organ Donor Card -->
                <div class="role-select-card" data-role="organ" onclick="toggleRoleCard(this)"
                    style="padding: 2rem; display: flex; flex-direction: column; align-items: center; text-align: center;">
                    <div class="role-select-card__icon" style="margin-bottom: 1.5rem;"><i
                            class="fas fa-hand-holding-heart"></i></div>
                    <h3 style="margin-bottom: 1rem; color: var(--blue-800);">Organ Donor</h3>
                    <ul
                        style="list-style: none; padding: 0; margin: 0; font-size: 0.85rem; color: var(--g600); line-height: 1.6; text-align: left;">
                        <li style="margin-bottom: 0.5rem;"><i class="fas fa-check-circle"
                                style="color: var(--blue-500); margin-right: 8px;"></i> Donate organs or tissues in life
                        </li>
                        <li style="margin-bottom: 0.5rem;"><i class="fas fa-check-circle"
                                style="color: var(--blue-500); margin-right: 8px;"></i> Pledge body for medical research
                        </li>
                        <li style="margin-bottom: 0.5rem;"><i class="fas fa-check-circle"
                                style="color: var(--blue-500); margin-right: 8px;"></i> Manage legal representatives</li>
                    </ul>
                    <div class="role-select-card__check"><i class="fas fa-check-circle"></i></div>
                </div>

                <!-- Financial Donor Card -->
                <div class="role-select-card" data-role="financial" onclick="toggleRoleCard(this)"
                    style="padding: 2rem; display: flex; flex-direction: column; align-items: center; text-align: center;">
                    <div class="role-select-card__icon" style="background: #dcfce7; color: #10b981; margin-bottom: 1.5rem;">
                        <i class="fas fa-hand-holding-dollar"></i></div>
                    <h3 style="margin-bottom: 1rem; color: #166534;">Financial Donor</h3>
                    <ul
                        style="list-style: none; padding: 0; margin: 0; font-size: 0.85rem; color: var(--g600); line-height: 1.6; text-align: left;">
                        <li style="margin-bottom: 0.5rem;"><i class="fas fa-check-circle"
                                style="color: #10b981; margin-right: 8px;"></i> Support patient medical costs</li>
                        <li style="margin-bottom: 0.5rem;"><i class="fas fa-check-circle"
                                style="color: #10b981; margin-right: 8px;"></i> Fund transplant infrastructure</li>
                        <li style="margin-bottom: 0.5rem;"><i class="fas fa-check-circle"
                                style="color: #10b981; margin-right: 8px;"></i> Track your donation impact</li>
                    </ul>
                    <div class="role-select-card__check"><i class="fas fa-check-circle"></i></div>
                </div>

                <!-- Non-Donor Card -->
                <div class="role-select-card" data-role="non" onclick="toggleRoleCard(this)"
                    style="padding: 2rem; display: flex; flex-direction: column; align-items: center; text-align: center; border: 1px dashed var(--g300);">
                    <div class="role-select-card__icon"
                        style="background: var(--g100); color: var(--g500); margin-bottom: 1.5rem;"><i
                            class="fas fa-user-slash"></i></div>
                    <h3 style="margin-bottom: 1rem; color: var(--g700);">Non-Donor</h3>
                    <div
                        style="background: #fff1f2; border: 1px solid #fecaca; padding: 0.75rem; border-radius: 8px; margin-bottom: 1rem;">
                        <p style="font-size: 0.8rem; color: #be123c; font-weight: 600; line-height: 1.4; margin: 0;">
                            I do not wish to be a donor. I opt out of all organ and tissue recovery efforts, even after
                            death.
                        </p>
                    </div>
                    <ul
                        style="list-style: none; padding: 0; margin: 0; font-size: 0.85rem; color: var(--g600); line-height: 1.6; text-align: left;">
                        <li style="margin-bottom: 0.5rem;"><i class="fas fa-times-circle"
                                style="color: var(--g400); margin-right: 8px;"></i> No personal donation impact</li>
                        <li style="margin-bottom: 0.5rem;"><i class="fas fa-info-circle"
                                style="color: var(--g400); margin-right: 8px;"></i> Support through awareness only</li>
                    </ul>
                    <div class="role-select-card__check"><i class="fas fa-check-circle"></i></div>
                </div>
            </div>

            <div style="text-align: center;">
                <button class="d-btn d-btn--primary" id="continueToDashboardBtn"
                    style="padding: 1.25rem 3rem; font-size: 1.1rem; border-radius: 12px; box-shadow: 0 10px 30px rgba(0, 91, 170, 0.2);"
                    onclick="saveInitialRoles()">
                    Continue to Dashboard <i class="fas fa-arrow-right" style="margin-left: 10px;"></i>
                </button>
            </div>
        </div>
    </div>

    <script>
        function toggleRoleCard(card) {
            const role = card.dataset.role;
            const isSelected = card.classList.contains('selected');

            if (!isSelected) {
                if (role === 'non') {
                    // If selecting Non-Donor, deselect Organ Donor ONLY
                    document.querySelectorAll('.role-select-card.selected[data-role="organ"]').forEach(c => {
                        c.classList.remove('selected');
                    });
                } else if (role === 'organ') {
                    // If selecting Organ Donor, deselect Non-Donor if it was selected
                    const nonCard = document.querySelector('.role-select-card.selected[data-role="non"]');
                    if (nonCard) nonCard.classList.remove('selected');
                }
            }

            card.classList.toggle('selected');
        }

        async function saveInitialRoles() {
            const selected = document.querySelectorAll('.role-select-card.selected');
            const roles = Array.from(selected).map(c => c.dataset.role);

            if (roles.length === 0) {
                alert("Please select at least one role to continue.");
                return;
            }

            const btn = document.getElementById('continueToDashboardBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Setting up your portal...';

            const formData = new FormData();
            roles.forEach(r => formData.append('roles[]', r));

            try {
                const response = await fetch('<?= ROOT ?>/donor/update-roles', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();
                if (data.success) {
                    window.location.reload();
                } else {
                    alert(data.message || "Failed to save roles.");
                    btn.disabled = false;
                    btn.innerText = 'Continue to Dashboard';
                }
            } catch (e) {
                console.error(e);
                alert("An error occurred. Please try again.");
                btn.disabled = false;
                btn.innerText = 'Continue to Dashboard';
            }
        }
    </script>
<?php endif; ?>

<?php include __DIR__ . '/inc/footer.view.php'; ?>