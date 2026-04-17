<?php
// Hospital Portal — Grant Donor Aftercare Access
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="<?php echo ROOT; ?>/assets/css/hospital/hospital.css">
    <title>Grant Donor Aftercare Access - LifeConnect</title>
    <style>
        .ac-alert { padding: 1rem; border-radius: 10px; margin-bottom: 1.25rem; border-left: 4px solid; font-weight: 600; }
        .ac-alert.success { background: #ecfdf5; color: #065f46; border-left-color: #10b981; }
        .ac-alert.error { background: #fef2f2; color: #991b1b; border-left-color: #ef4444; }
    </style>
</head>

<body>
    <?php include __DIR__ . '/inc/header.view.php'; ?>

    <div class="container">
        <div class="main-content">
            <?php include __DIR__ . '/inc/sidebar_aftercare.view.php'; ?>

            <div class="content-area">
                <div class="content-section" style="display:block;">
                    <div class="content-header">
                        <h2>Enable Donor Aftercare Access</h2>
                        <p>Grant Aftercare Support access for an existing donor account.</p>
                    </div>

                    <div class="content-body">
                        <?php if (!empty($_SESSION['flash_error'])): ?>
                            <div class="ac-alert error"><?php echo htmlspecialchars($_SESSION['flash_error']); unset($_SESSION['flash_error']); ?></div>
                        <?php endif; ?>

                        <?php if (!empty($_SESSION['flash_success'])): ?>
                            <div class="ac-alert success"><?php echo htmlspecialchars($_SESSION['flash_success']); unset($_SESSION['flash_success']); ?></div>
                        <?php endif; ?>

                        <div style="background: white; border-radius: 12px; padding: 2rem; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                            <form action="<?php echo ROOT; ?>/hospital/addpatient/donor" method="POST">
                                <div style="margin-bottom: 1.5rem;">
                                    <label style="display: block; font-weight: 700; margin-bottom: 0.5rem; color: var(--secondary-text-color);">Select Donor *</label>
                                    <select id="donor_select" required style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--border-color); border-radius: 8px; font-size: 1rem; background: white;">
                                        <option value="">Select a Donor</option>
                                        <?php foreach (($donors ?? []) as $d): ?>
                                            <option value="<?= htmlspecialchars((string)($d->nic_number ?? '')) ?>">
                                                <?= htmlspecialchars((string)($d->nic_number ?? '')) ?> - <?= htmlspecialchars(trim((string)($d->first_name ?? '') . ' ' . (string)($d->last_name ?? ''))) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input type="hidden" id="donor_search_nic" name="donor_nic" value="" />
                                </div>

                                <div id="donor-details-preview" style="display: none; margin-bottom: 1.5rem; padding: 1.5rem; background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0;">
                                    <h4 style="margin-top: 0; margin-bottom: 1rem; color: #334155;">Donor Data Found:</h4>
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; color: #475569;">
                                        <div><strong>Name:</strong> <span id="preview_name"></span></div>
                                        <div><strong>Gender:</strong> <span id="preview_gender"></span></div>
                                        <div><strong>Blood Group:</strong> <span id="preview_blood"></span></div>
                                    </div>
                                </div>

                                <button type="submit" id="grant-access-btn" class="btn btn-primary" style="display: none; padding: 0.75rem 1.5rem; font-size: 1rem; cursor: pointer;">Grant Aftercare Access</button>
                            </form>

                            <div style="margin-top: 1.25rem; color:#64748b; font-size: 0.9rem; line-height: 1.5;">
                                This enables the "Aftercare Support" tab in the donor portal when <strong>aftercare_access</strong> is granted.
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        async function fetchDonorDetails(nicValue) {
            const nic = (nicValue ?? document.getElementById('donor_search_nic')?.value ?? '').trim();
            if (!nic) {
                document.getElementById('donor-details-preview').style.display = 'none';
                document.getElementById('grant-access-btn').style.display = 'none';
                return;
            }

            const hiddenNic = document.getElementById('donor_search_nic');
            if (hiddenNic) hiddenNic.value = nic;

            try {
                const response = await fetch('<?php echo ROOT; ?>/hospital/fetch-donor-details?nic=' + encodeURIComponent(nic));
                const data = await response.json();

                if (data.success) {
                    document.getElementById('donor-details-preview').style.display = 'block';
                    document.getElementById('preview_name').innerText = (data.donor.first_name || '') + ' ' + (data.donor.last_name || '');
                    document.getElementById('preview_gender').innerText = data.donor.gender || 'Not specified';
                    document.getElementById('preview_blood').innerText = data.donor.blood_group || 'Not specified';
                    document.getElementById('grant-access-btn').style.display = 'inline-block';
                } else {
                    document.getElementById('donor-details-preview').style.display = 'none';
                    document.getElementById('grant-access-btn').style.display = 'none';
                }
            } catch (e) {
                hcAlert('Connection error occurred.', 'error');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const select = document.getElementById('donor_select');
            if (select) {
                select.addEventListener('change', function() {
                    fetchDonorDetails(this.value);
                });
            }
        });
    </script>

    <?php include __DIR__ . '/inc/footer.view.php'; ?>
