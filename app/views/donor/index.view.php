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
    } catch (Exception $e) { $age = 0; }
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
                <p>Greetings, <?= htmlspecialchars($donor_data['first_name'] ?? 'Donor') ?>! Here is your life-saving contribution overview.</p>
            </div>
            <div class="d-status <?= isset($donor_data['verification_status']) && strtolower($donor_data['verification_status']) === 'active' ? 'd-status--success' : 'd-status--warning' ?>">
                <div class="d-status__dot"></div>
                <?= htmlspecialchars($donor_data['verification_status'] ?? 'Active') ?>
            </div>
        </div>
    </div>
    
    <div class="d-content__body">
        
        <!-- Compact Summary Bar -->
        <div class="d-summary-bar">
            <div class="d-summary-item">
                <div class="d-summary-item__icon"><i class="fas fa-hand-holding-heart"></i></div>
                <div class="d-summary-item__stats">
                    <h4>Pledged Organs</h4>
                    <span><?= count($pledged_organs ?? []) ?></span>
                </div>
            </div>
            <div class="d-summary-item">
                <div class="d-summary-item__icon" style="color: #10b981; background: #dcfce7;"><i class="fas fa-user-check"></i></div>
                <div class="d-summary-item__stats">
                    <h4>Approved Status</h4>
                    <span><?= $donor_stats['approved_organs'] ?? 0 ?></span>
                </div>
            </div>
            <div class="d-summary-item">
                <div class="d-summary-item__icon" style="color: #f59e0b; background: #fef3c7;"><i class="fas fa-clock"></i></div>
                <div class="d-summary-item__stats">
                    <h4>Pending Verification</h4>
                    <span><?= $donor_stats['pending_organs'] ?? 0 ?></span>
                </div>
            </div>
        </div>

        <div class="d-dashboard-grid">
            
            <!-- Widget 1: Profile Summary -->
            <div class="d-widget">
                <div class="d-widget__header">
                    <div class="d-widget__title"><i class="fas fa-id-card text-accent"></i> Profile Details</div>
                </div>
                <div class="d-widget__body" style="display: flex; gap: 1.5rem; align-items: flex-start;">
                    <div style="width: 80px; height: 80px; border-radius: 12px; background: linear-gradient(135deg, var(--blue-600), var(--blue-800)); color: white; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; font-weight: bold; flex-shrink: 0;">
                        <?= strtoupper(substr($donor_data['first_name'] ?? '?', 0, 1)) ?>
                    </div>
                    <div style="flex: 1;">
                        <div class="d-info-row">
                            <div class="d-info-label">Full Name</div>
                            <div class="d-info-value"><?= $donor_full_name ?></div>
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                            <div>
                                <div class="d-info-label">Donor ID</div>
                                <div class="d-info-value"><?= $donor_id_display ?></div>
                            </div>
                            <div>
                                <div class="d-info-label">Age</div>
                                <div class="d-info-value"><?= $age ?> Years</div>
                            </div>
                            <div>
                                <div class="d-info-label">Blood Group</div>
                                <div class="d-info-value" style="color: #ef4444;"><?= htmlspecialchars($donor_data['blood_group'] ?? 'N/A') ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Widget 2: Pledged Contributions -->
            <div class="d-widget">
                <div class="d-widget__header">
                    <div class="d-widget__title"><i class="fas fa-gift text-accent"></i> Pledged Contributions</div>
                </div>
                <div class="d-widget__body">
                    <div style="display: flex; flex-wrap: wrap; gap: 0.75rem; margin-bottom: 2rem;">
                        <?php if (!empty($pledged_organs)): foreach ($pledged_organs as $organ): ?>
                            <div style="background: var(--blue-50); border: 1px solid var(--blue-200); padding: 0.5rem 1rem; border-radius: 50px; font-size: 0.85rem; font-weight: 600; color: var(--blue-800); display: flex; align-items: center; gap: 0.5rem;">
                                <span><?= $organ['organ_icon'] ?? '<i class="fas fa-heart"></i>' ?></span>
                                <?= htmlspecialchars($organ['organ_name']) ?>
                            </div>
                        <?php endforeach; else: ?>
                            <p style="color: var(--g500); font-size: 0.9rem; font-style: italic;">No contributions pledged yet.</p>
                            <a href="<?= ROOT ?>/donor/donations" class="d-btn d-btn--outline d-btn--sm" style="margin-top: 0.5rem;">Start Pledging</a>
                        <?php endif; ?>
                    </div>

                    <div style="border-top: 1px solid var(--g200); padding-top: 1rem;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                            <span style="font-size: 0.8rem; color: var(--g500); font-weight: 600;">Registration Date:</span>
                            <span style="font-size: 0.8rem; font-weight: 600;"><?= date('M d, Y', strtotime($donor_data['registration_date'] ?? 'now')) ?></span>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span style="font-size: 0.8rem; color: var(--g500); font-weight: 600;">Last Consent Update:</span>
                            <span style="font-size: 0.8rem; font-weight: 600;"><?= $donor_data['consent_date'] ? date('M d, Y', strtotime($donor_data['consent_date'])) : 'Pending' ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Widget 3: Medical Investigations -->
            <div class="d-widget">
                <div class="d-widget__header">
                    <div class="d-widget__title"><i class="fas fa-microscope text-accent"></i> Medical Investigations</div>
                </div>
                <div class="d-widget__body">
                    <?php if (!empty($upcoming_appointments)): foreach ($upcoming_appointments as $apt): ?>
                        <div style="padding: 1rem; border-left: 3px solid var(--blue-600); background: var(--blue-50); border-radius: 6px; margin-bottom: 0.8rem;">
                            <div style="font-weight: 600; font-size: 0.9rem; color: var(--blue-800);"><?= htmlspecialchars($apt->test_type ?? 'Medical Test') ?></div>
                            <div style="font-size: 0.8rem; color: var(--g500); margin-top: 0.25rem;"><i class="far fa-calendar-alt"></i> <?= date('M d, Y', strtotime($apt->test_date)) ?></div>
                        </div>
                    <?php endforeach; else: ?>
                        <p style="color: var(--g500); font-size: 0.9rem; font-style: italic;">No upcoming investigations.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Widget 4: Latest Health Status -->
            <div class="d-widget">
                <div class="d-widget__header">
                    <div class="d-widget__title"><i class="fas fa-file-medical-alt text-accent"></i> Latest Health Status</div>
                </div>
                <div class="d-widget__body">
                    <?php if ($latest_health): ?>
                        <div style="padding: 1rem; border-left: 3px solid #10b981; background: #dcfce7; border-radius: 6px;">
                            <div style="font-weight: 600; color: #166534;"><?= htmlspecialchars($latest_health->test_name) ?></div>
                            <div style="font-size: 0.85rem; margin-top: 0.4rem;">Result: <span style="color: #10b981; font-weight: 700;"><?= htmlspecialchars($latest_health->result_value) ?></span></div>
                            <div style="font-size: 0.75rem; color: #065f46; margin-top: 0.5rem; opacity: 0.8;"><i class="fas fa-hospital"></i> Verified by <?= htmlspecialchars($latest_health->hospital_name) ?></div>
                        </div>
                    <?php else: ?>
                        <p style="color: var(--g500); font-size: 0.9rem; font-style: italic;">No medical records found.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Widget 5: Recent Updates -->
            <div class="d-widget" style="grid-column: 1 / -1;">
                <div class="d-widget__header">
                    <div class="d-widget__title"><i class="fas fa-history text-accent"></i> Recent Updates</div>
                </div>
                <div class="d-widget__body">
                    <?php if (!empty($notifications)): ?>
                        <ul class="d-timeline">
                            <?php foreach (array_slice($notifications, 0, 4) as $notif): ?>
                                <li>
                                    <div class="d-timeline__date"><?= date('M d, Y', strtotime($notif['created_at'])) ?></div>
                                    <div class="d-timeline__content"><?= htmlspecialchars($notif['message']) ?></div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <div style="text-align: center; padding: 2rem 0; color: var(--g400);">
                            <i class="fas fa-bell-slash" style="font-size: 2rem; margin-bottom: 1rem;"></i>
                            <p>No new notifications.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</main>

<?php if ($is_first_login): ?>
<!-- First Login: Role Selection Overlay -->
<div id="firstLoginOverlay" class="d-modal active" style="background: rgba(255, 255, 255, 0.98); backdrop-filter: blur(8px);">
    <div class="d-modal__body" style="max-width: 900px; padding: 3rem; background: transparent; box-shadow: none; width: 100%;">
        <div style="text-align: center; margin-bottom: 3rem;">
            <h1 style="font-size: 2.5rem; color: var(--blue-800); font-weight: 800; margin-bottom: 1rem;">Choose how you want to participate</h1>
            <p style="font-size: 1.1rem; color: var(--g500);">You can select one or more roles. You can add more roles later anytime.</p>
        </div>

        <div class="selection-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.5rem; margin-bottom: 3rem;">
            <!-- Organ Donor Card -->
            <div class="role-select-card" data-role="organ" onclick="toggleRoleCard(this)" style="padding: 2rem; display: flex; flex-direction: column; align-items: center; text-align: center;">
                <div class="role-select-card__icon" style="margin-bottom: 1.5rem;"><i class="fas fa-hand-holding-heart"></i></div>
                <h3 style="margin-bottom: 1rem; color: var(--blue-800);">Organ Donor</h3>
                <ul style="list-style: none; padding: 0; margin: 0; font-size: 0.85rem; color: var(--g600); line-height: 1.6; text-align: left;">
                    <li style="margin-bottom: 0.5rem;"><i class="fas fa-check-circle" style="color: var(--blue-500); margin-right: 8px;"></i> Donate organs or tissues in life</li>
                    <li style="margin-bottom: 0.5rem;"><i class="fas fa-check-circle" style="color: var(--blue-500); margin-right: 8px;"></i> Pledge body for medical research</li>
                    <li style="margin-bottom: 0.5rem;"><i class="fas fa-check-circle" style="color: var(--blue-500); margin-right: 8px;"></i> Manage legal representatives</li>
                </ul>
                <div class="role-select-card__check"><i class="fas fa-check-circle"></i></div>
            </div>
 
            <!-- Financial Donor Card -->
            <div class="role-select-card" data-role="financial" onclick="toggleRoleCard(this)" style="padding: 2rem; display: flex; flex-direction: column; align-items: center; text-align: center;">
                <div class="role-select-card__icon" style="background: #dcfce7; color: #10b981; margin-bottom: 1.5rem;"><i class="fas fa-hand-holding-dollar"></i></div>
                <h3 style="margin-bottom: 1rem; color: #166534;">Financial Donor</h3>
                <ul style="list-style: none; padding: 0; margin: 0; font-size: 0.85rem; color: var(--g600); line-height: 1.6; text-align: left;">
                    <li style="margin-bottom: 0.5rem;"><i class="fas fa-check-circle" style="color: #10b981; margin-right: 8px;"></i> Support patient medical costs</li>
                    <li style="margin-bottom: 0.5rem;"><i class="fas fa-check-circle" style="color: #10b981; margin-right: 8px;"></i> Fund transplant infrastructure</li>
                    <li style="margin-bottom: 0.5rem;"><i class="fas fa-check-circle" style="color: #10b981; margin-right: 8px;"></i> Track your donation impact</li>
                </ul>
                <div class="role-select-card__check"><i class="fas fa-check-circle"></i></div>
            </div>
 
            <!-- Non-Donor Card -->
            <div class="role-select-card" data-role="non" onclick="toggleRoleCard(this)" style="padding: 2rem; display: flex; flex-direction: column; align-items: center; text-align: center; border: 1px dashed var(--g300);">
                <div class="role-select-card__icon" style="background: var(--g100); color: var(--g500); margin-bottom: 1.5rem;"><i class="fas fa-user-slash"></i></div>
                <h3 style="margin-bottom: 1rem; color: var(--g700);">Non-Donor</h3>
                <div style="background: #fff1f2; border: 1px solid #fecaca; padding: 0.75rem; border-radius: 8px; margin-bottom: 1rem;">
                    <p style="font-size: 0.8rem; color: #be123c; font-weight: 600; line-height: 1.4; margin: 0;">
                        I do not wish to be a donor. I opt out of all organ and tissue recovery efforts, even after death.
                    </p>
                </div>
                <ul style="list-style: none; padding: 0; margin: 0; font-size: 0.85rem; color: var(--g600); line-height: 1.6; text-align: left;">
                    <li style="margin-bottom: 0.5rem;"><i class="fas fa-times-circle" style="color: var(--g400); margin-right: 8px;"></i> No personal donation impact</li>
                    <li style="margin-bottom: 0.5rem;"><i class="fas fa-info-circle" style="color: var(--g400); margin-right: 8px;"></i> Support through awareness only</li>
                </ul>
                <div class="role-select-card__check"><i class="fas fa-check-circle"></i></div>
            </div>
        </div>

        <div style="text-align: center;">
            <button class="d-btn d-btn--primary" id="continueToDashboardBtn" style="padding: 1.25rem 3rem; font-size: 1.1rem; border-radius: 12px; box-shadow: 0 10px 30px rgba(0, 91, 170, 0.2);" onclick="saveInitialRoles()">
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
