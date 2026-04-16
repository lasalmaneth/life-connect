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

    <!-- -- Donation Consent History ------------------------------------------ -->
    <div class="cp-section-card mt-4">
        <div class="cp-section-card__header">
            <div class="cp-section-card__title">
                <i class="fas fa-file-signature"></i> Donation Consent History Timeline
            </div>
        </div>
        <div class="cp-section-card__body p-0">
            <?php 
                $timeline = [];
                if (!empty($consent)) {
                    foreach ($consent["body_consents"] ?? [] as $bc) {
                        $timeline[] = (object)[
                            "type"   => "BODY DONATION",
                            "date"   => $bc->consent_date,
                            "status" => "ACTIVE", 
                            "details"=> ($bc->school_name ?? "Medical School")
                        ];
                    }
                    foreach ($consent["organ_pledges"] ?? [] as $op) {
                        $timeline[] = (object)[
                            "type"   => "ORGAN PLEDGE",
                            "date"   => $op->created_at,
                            "status" => $op->status,
                            "details"=> $op->organ_name
                        ];
                    }
                    usort($timeline, fn($a, $b) => strtotime($b->date ?: "2000-01-01") - strtotime($a->date ?: "2000-01-01"));
                }
            ?>
            
            <?php if (empty($timeline)): ?>
                <div class="p-5 text-center cp-text-g500">No documented consent history available for this donor.</div>
            <?php else: ?>
                <table class="cp-table w-100 text-left">
                    <thead>
                        <tr class="cp-bg-g50 border-bottom">
                            <th class="p-3">Type</th>
                            <th class="p-3">Description</th>
                            <th class="p-3">Registered Date</th>
                            <th class="p-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($timeline as $t): ?>
                            <tr class="border-bottom">
                                <td class="p-3 cp-font-semibold" style="font-size: 0.85rem;"><?= $t->type ?></td>
                                <td class="p-3" style="font-size: 0.85rem; color: var(--g600);"><?= htmlspecialchars($t->details) ?></td>
                                <td class="p-3" style="font-size: 0.85rem;"><?= !empty($t->date) ? date("M j, Y", strtotime($t->date)) : "N/A" ?></td>
                                <td class="p-3">
                                    <?php 
                                        $pillClass = "pending";
                                        if ($t->status === "ACTIVE" || $t->status === "GIVEN") $pillClass = "approved";
                                        if ($t->status === "WITHDRAWN") $pillClass = "rejected";
                                    ?>
                                    <span class="cp-status-pill cp-status-pill--<?= $pillClass ?>" style="padding: 2px 10px; font-size: 0.75rem;">
                                        <?= htmlspecialchars($t->status) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

</div><!-- /.cp-content__body -->

<?php
$page_content = ob_get_clean();
require dirname(__DIR__) . '/layouts/custodian.layout.php';
?>
