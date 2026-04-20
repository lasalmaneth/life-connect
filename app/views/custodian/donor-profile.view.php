<?php
/**
 * Custodian Portal — Donor Profile View
 * Refactored to be a lean, read-only bio/contact record.
 * Registry and outcomes have been moved to the dedicated Consent Registry page.
 */

$page_icon     = 'fa-id-card';
$page_heading  = 'Donor Profile';
$page_subtitle = 'General identity and contact information for the registered donor.';

ob_start();
?>

<!-- Page Header -->
<?php include __DIR__ . '/partials/page-header.php'; ?>

<div class="cp-content__body">

    <!-- -- Personal Information ---------------------------------------------- -->
    <div class="cp-section-card mb-4">
        <div class="cp-section-card__header">
            <div class="cp-section-card__title"><i class="fas fa-user-tag"></i> Personal Details</div>
        </div>
        <div class="cp-section-card__body">
            <div class="cp-profile-grid">
                <div class="cp-profile-form-group">
                    <label class="cp-label">Full Name</label>
                    <input type="text" class="cp-profile-input cp-font-bold" value="<?= htmlspecialchars($donor->first_name . ' ' . $donor->last_name) ?>" disabled />
                </div>
                <div class="cp-profile-form-group">
                    <label class="cp-label">NIC Number</label>
                    <input type="text" class="cp-profile-input" value="<?= htmlspecialchars($donor->nic_number) ?>" disabled />
                </div>
                <div class="cp-profile-form-group">
                    <label class="cp-label">Gender</label>
                    <input type="text" class="cp-profile-input" value="<?= htmlspecialchars($donor->gender) ?>" disabled />
                </div>
                <div class="cp-profile-form-group">
                    <label class="cp-label">Date of Birth</label>
                    <input type="text" class="cp-profile-input" value="<?= htmlspecialchars($donor->date_of_birth ?? '') ?>" disabled />
                </div>
            </div>
        </div>
    </div>

    <div class="cp-grid-2">

        <!-- -- Contact Information ------------------------------------------- -->
        <div class="cp-section-card h-100">
            <div class="cp-section-card__header">
                <div class="cp-section-card__title"><i class="fas fa-phone"></i> Contact Information</div>
            </div>
            <div class="cp-section-card__body">
                <div class="cp-profile-form-group">
                    <label class="cp-label">Primary Phone</label>
                    <input type="text" class="cp-profile-input" value="<?= htmlspecialchars($donor->user_phone ?? 'N/A') ?>" disabled />
                </div>
                <div class="cp-profile-form-group">
                    <label class="cp-label">Email Address</label>
                    <input type="email" class="cp-profile-input" value="<?= htmlspecialchars($donor->user_email ?? 'N/A') ?>" disabled />
                </div>
                <div class="cp-profile-form-group">
                    <label class="cp-label">Residential Address</label>
                    <textarea class="cp-profile-input" style="min-height:80px; resize:none;" rows="3" disabled><?= htmlspecialchars($donor->address ?? '') ?></textarea>
                </div>
            </div>
        </div>

        <!-- -- Registration Chain --------------------------------------------- -->
        <div class="cp-section-card h-100">
            <div class="cp-section-card__header">
                <div class="cp-section-card__title"><i class="fas fa-link"></i> Registration Chain</div>
            </div>
            <div class="cp-section-card__body">
                <div class="cp-profile-form-group">
                    <label class="cp-label">LifeConnect ID</label>
                    <input type="text" class="cp-profile-input" value="D-<?= str_pad($donor->id, 5, '0', STR_PAD_LEFT) ?>" disabled />
                </div>
                
                <div class="cp-profile-form-group">
                    <label class="cp-label">Date of Registration</label>
                    <input type="text" class="cp-profile-input" value="<?= !empty($donor->created_at) ? date('M j, Y', strtotime($donor->created_at)) : 'N/A' ?>" disabled />
                </div>
                
                <div class="mt-3">
                    <a href="<?= ROOT ?>/custodian/consent-registry" class="cp-btn cp-btn--outline cp-btn--sm w-100" style="justify-content: center;">
                        <i class="fas fa-book-medical"></i> View Consent Registry
                    </a>
                </div>
            </div>
        </div>

    </div>

    <!-- -- Medical Disclosure ---------------------------------------------- -->
    <div class="cp-section-card mt-4 mb-5">
        <div class="cp-section-card__header">
            <div class="cp-section-card__title"><i class="fas fa-notes-medical"></i> Medical Disclosure</div>
        </div>
        <div class="cp-section-card__body">
            <div class="cp-notice cp-notice--warning mb-3" style="font-size: 0.8rem;">
                <i class="fas fa-triangle-exclamation"></i>
                <div>This information was self-reported by the donor during registration and should be verified by clinical staff before procurement.</div>
            </div>
            
            <div class="cp-grid-2">
                <div class="cp-profile-form-group">
                    <label class="cp-label">Blood Group</label>
                    <input type="text" class="cp-profile-input" value="<?= htmlspecialchars($donor->blood_group ?? 'Not Specified') ?>" disabled />
                </div>
                <div class="cp-profile-form-group">
                    <label class="cp-label">Chronic Conditions</label>
                    <input type="text" class="cp-profile-input" value="<?= htmlspecialchars($donor->medical_conditions ?? 'None Reported') ?>" disabled />
                </div>
            </div>
        </div>
    </div>

</div><!-- /.cp-content__body -->

<?php
$page_content = ob_get_clean();
require dirname(__DIR__) . '/layouts/custodian.layout.php';
?>
