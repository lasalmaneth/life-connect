<?php if(!defined('ROOT')) die(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LifeConnect — Acknowledgement Letter: <?= htmlspecialchars($letter->ref_number) ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=Cormorant+Garamond:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= ROOT ?>/public/assets/css/medicalschools/appreciation.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= ROOT ?>/public/assets/css/print.css?v=<?= time() ?>">
</head>
<body>

<?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'MEDICAL_SCHOOL'): ?>
<div class="controls">
    <?php 
        $backUrl = (isset($_GET['from']) && $_GET['from'] === 'examinations') 
            ? ROOT . '/medical-school/final-examinations' 
            : ROOT . '/medical-school/usage-logs';
    ?>
    <a href="<?= $backUrl ?>" class="btn-action">
        <i class="fas fa-arrow-left"></i> Back
    </a>
    <button onclick="window.print()" class="btn-action primary">
        <i class="fas fa-print"></i> Print Letter
    </button>
</div>
<?php endif; ?>

<div class="page">
    <div class="letter-card">
        <!-- Card Header -->
        <div class="letter-header">
            <div class="lh-icon">
                <img src="<?= ROOT ?>/assets/images/logo.png" alt="LifeConnect Logo" style="height: 32px; width: auto;">
            </div>
            <div>
                <div class="lh-title"><?= htmlspecialchars($letter->school_name) ?></div>
                <div class="lh-sub">Official Acknowledgement Registry • Ref: <?= htmlspecialchars($letter->ref_number) ?></div>
            </div>
        </div>

        <!-- Card Body -->
        <div class="letter-body" style="--logo-url: url('<?= ROOT ?>/assets/images/logo.png');">
            <div class="letter-content">
                <div class="date"><?= date('d F Y', strtotime($letter->issued_at)) ?></div>
                
                <div class="from">
                    <?= htmlspecialchars($letter->school_name) ?><br>
                    <span style="font-weight: 400; color: #64748b; font-size: 0.8rem;"><?= htmlspecialchars($letter->school_address) ?></span>
                </div>

                <div class="salutation">Dear <?= htmlspecialchars($letter->custodian_name) ?>,</div>
                
                <div class="para">
                    On behalf of the <strong><?= htmlspecialchars($letter->school_name) ?></strong>, we write to formally acknowledge 
                    and express our deepest gratitude for the whole body donation of 
                    <strong style="color: #0f172a;">Mr. <?= htmlspecialchars($letter->first_name . ' ' . $letter->last_name) ?></strong> (NIC: <?= htmlspecialchars($letter->nic_number) ?>).
                </div>

                <div class="para">
                    The donated body has been received by the <strong><?= htmlspecialchars($letter->usage_department) ?></strong> 
                    and was utilized on <strong><?= date('d F Y', strtotime($letter->usage_date)) ?></strong> 
                    exclusively for the purpose of medical advancement in the area of <strong><?= htmlspecialchars($letter->purpose) ?></strong>, 
                    honouring the donor's selfless and extraordinary final wish.
                </div>

                <div class="para">
                    The Faculty extends its deepest respect and heartfelt condolences to you and your family. 
                    This profound gift serves as a cornerstone for the training of future physicians, benefiting 
                    countless lives. 
                </div>

                <div class="para">
                    The sacrifice and courage shown by you in fulfilling this wish is a direct contribution to 
                    medical science that will never be forgotten by our institution.
                </div>

                <div class="sign-off">Yours sincerely,</div>
                
                <div class="sig-block">
                    <div class="name">Professor of Anatomy</div>
                    <div class="dept"><?= htmlspecialchars($letter->school_name) ?></div>
                </div>
            </div>
        </div>

        <!-- Card Footer -->
        <div class="letter-footer">
            <div class="ref">System Reference: <?= htmlspecialchars($letter->ref_number) ?> • Digital Copy</div>
            <div class="status-pill">
                <i class="fas fa-shield-halved"></i>
                Digitally Authenticated via LifeConnect
            </div>
        </div>
    </div>
</div>

</body>
</html>
