<?php
// FILE: app/views/financial_donor/index.view.php
$pageKey = 'dashboard';
require __DIR__ . '/layout.view.php';

// Calculate total donated safely from objects
$total_donated = 0;
if (!empty($donation_history)) {
    foreach ($donation_history as $donation) {
        $total_donated += (float)($donation->amount ?? 0);
    }
}
$first_name = htmlspecialchars(explode(' ', $donor_data['full_name'] ?? 'Donor')[0]);
?>

<div class="sec active">
    <!-- Header -->
    <div class="c-hdr">
        <div class="c-ey">Financial Dashboard</div>
        <h2>Welcome back, <?= $first_name ?>!</h2>
        <p>Thank you for your generous contributions to LifeConnect.</p>
    </div>

    <!-- Stats Grid -->
    <div class="g3" style="margin-top: 1.5rem;">
        <div class="card bb">
            <div class="ch">
                <div class="ct"><i class="fas fa-hand-holding-heart"></i> Contributed</div>
            </div>
            <div style="font-size: 2rem; font-weight: 700; color: var(--blue-600);">
                LKR <?= number_format($total_donated, 2) ?>
            </div>
            <div style="font-size: 0.8rem; color: var(--g500); margin-top: 0.5rem; text-transform: uppercase;">
                Total Donation
            </div>
        </div>
        
        <div class="card pb">
            <div class="ch">
                <div class="ct"><i class="fas fa-calendar-check"></i> Records</div>
            </div>
            <div style="font-size: 2rem; font-weight: 700; color: var(--blue-600);">
                <?= count($donation_history) ?>
            </div>
            <div style="font-size: 0.8rem; color: var(--g500); margin-top: 0.5rem; text-transform: uppercase;">
                Donations Made
            </div>
        </div>
        
        <div class="card gb">
            <div class="ch">
                <div class="ct"><i class="fas fa-award"></i> Status</div>
            </div>
            <div style="font-size: 2rem; font-weight: 700; color: var(--success);">
                Active
            </div>
            <div style="font-size: 0.8rem; color: var(--g500); margin-top: 0.5rem; text-transform: uppercase;">
                Donor Record
            </div>
        </div>
    </div>

    <!-- Profile Overview -->
    <div class="card" style="margin-top: 1.5rem;">
        <div class="ch">
            <div class="ct"><i class="fas fa-id-card"></i> Profile Overview</div>
        </div>
        
        <div class="g2">
            <div class="ir">
                <div class="il">Full Name</div>
                <div class="iv"><?= htmlspecialchars($donor_data['full_name'] ?? 'Not specified') ?></div>
            </div>
            <div class="ir">
                <div class="il">NIC Number</div>
                <div class="iv"><?= htmlspecialchars($donor_data['nic_number'] ?? 'Not specified') ?></div>
            </div>
            <div class="ir" style="grid-column: 1 / -1;">
                <div class="il">Address</div>
                <div class="iv"><?= htmlspecialchars(($donor_data['address'] ?? '') ?: 'Not specified') ?></div>
            </div>
        </div>
    </div>
</div> <!-- .sec -->

    </main>
</div> <!-- .wrap -->
</body>
</html>
