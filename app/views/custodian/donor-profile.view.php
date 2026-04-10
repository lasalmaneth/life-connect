<?php
/**
 * Custodian Portal — Donor Profile View
 * Route: GET /custodian/donor-profile
 * Active page key: donor-profile
 */

$page_icon     = 'fa-id-card';
$page_heading  = 'Donor Profile';
$page_subtitle = 'View the registered donor\'s personal and medical information.';

ob_start();
?>

<!-- Page Header -->
<?php include __DIR__ . '/partials/page-header.php'; ?>

<div class="cp-content__body">

    <div class="cp-notice cp-notice--info mb-4">
        <i class="fas fa-circle-info"></i>
        <div>
            <strong>Read-Only View</strong>
            <p>Donor profile data is managed by the system administrator. This page is view-only.</p>
        </div>
    </div>

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
                <div class="cp-section-card__title"><i class="fas fa-phone"></i> Contact Details</div>
            </div>
            <div class="cp-section-card__body">
                <div class="cp-profile-form-group">
                    <label class="cp-label">Phone</label>
                    <input type="text" class="cp-profile-input" value="<?= htmlspecialchars($donor->user_phone ?? 'N/A') ?>" disabled />
                </div>
                <div class="cp-profile-form-group">
                    <label class="cp-label">Email Address</label>
                    <input type="email" class="cp-profile-input" value="<?= htmlspecialchars($donor->user_email ?? 'N/A') ?>" disabled />
                </div>
                <div class="cp-profile-form-group">
                    <label class="cp-label">Address</label>
                    <textarea class="cp-profile-input" style="min-height:80px; resize:none;" rows="3" disabled><?= htmlspecialchars($donor->address ?? '') ?></textarea>
                </div>
            </div>
        </div>

        <!-- -- Registration Info --------------------------------------------- -->
        <div class="cp-section-card h-100">
            <div class="cp-section-card__header">
                <div class="cp-section-card__title"><i class="fas fa-file-invoice"></i> Registration Details</div>
            </div>
            <div class="cp-section-card__body">
                <div class="cp-profile-form-group">
                    <label class="cp-label">Registration ID</label>
                    <input type="text" class="cp-profile-input" value="D-<?= str_pad($donor->id, 5, '0', STR_PAD_LEFT) ?>" disabled />
                </div>
                <div class="cp-profile-form-group">
                    <label class="cp-label">Initial Pledge Type</label>
                    <input type="text" class="cp-profile-input" value="<?= str_replace('_', ' ', $donor->pledge_type ?? '') ?>" disabled />
                </div>
                <div class="cp-profile-form-group">
                    <label class="cp-label">Registered Date</label>
                    <input type="text" class="cp-profile-input" value="<?= !empty($donor->created_at) ? date('M j, Y', strtotime($donor->created_at)) : 'N/A' ?>" disabled />
                </div>
            </div>
        </div>

    </div>

</div><!-- /.cp-content__body -->

<?php
$page_content = ob_get_clean();
require dirname(__DIR__) . '/layouts/custodian.layout.php';
?>
