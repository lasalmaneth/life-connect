<?php
// Patient Aftercare Portal Account Management View
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="<?php echo ROOT; ?>/assets/css/hospital/hospital.css">
    <title>Patient Aftercare Portal - LifeConnect</title>
    <style>
        .ac-alert { padding: 1rem; border-radius: 10px; margin-bottom: 1.25rem; border-left: 4px solid; font-weight: 600; }
        .ac-alert.success { background: #ecfdf5; color: #065f46; border-left-color: #10b981; }
        .ac-alert.error { background: #fef2f2; color: #991b1b; border-left-color: #ef4444; }

        .ac-cards {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-top: 1.25rem;
        }
        @media (max-width: 860px) {
            .ac-cards { grid-template-columns: 1fr; }
        }
        .ac-card {
            display: block;
            text-decoration: none;
            color: inherit;
            background: #fff;
            border: 1px solid rgba(0, 91, 170, 0.12);
            border-radius: 14px;
            padding: 1.5rem;
            box-shadow: 0 10px 35px rgba(0, 91, 170, 0.08);
            transition: transform .15s ease, box-shadow .15s ease, border-color .15s ease;
        }
        .ac-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 18px 50px rgba(0, 91, 170, 0.14);
            border-color: rgba(0, 91, 170, 0.35);
        }
        .ac-card__top { display:flex; gap: 1rem; align-items: flex-start; }
        .ac-icon {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            background: rgba(0, 91, 170, 0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 auto;
        }
        .ac-icon svg { stroke: var(--primary-color); }
        .ac-title { font-weight: 900; font-size: 1.05rem; color: var(--secondary-text-color); margin: 0 0 0.25rem; }
        .ac-desc { margin: 0; color: #64748b; font-size: 0.95rem; line-height: 1.5; }
        .ac-cta { margin-top: 1rem; display:flex; align-items:center; gap: 0.5rem; color: var(--primary-color); font-weight: 800; }
        .ac-cta svg { stroke: var(--primary-color); }
    </style>
</head>

<body>
    <?php include __DIR__ . '/inc/header.view.php'; ?>

    <div class="container">
        <div class="main-content">
            <?php include __DIR__ . '/inc/sidebar_aftercare.view.php'; ?>

            <div class="content-area" id="content-area">
                <div class="content-section" style="display: block;">
                    <div class="content-header">
                        <h2>Aftercare Portal Account Generation</h2>
                        <p>Generate login credentials for transplant recipients to access the patient aftercare app.</p>
                    </div>

                    <div class="content-body">
                        <?php if (!empty($_SESSION['flash_error'])): ?>
                            <div class="ac-alert error"><?php echo htmlspecialchars($_SESSION['flash_error']); unset($_SESSION['flash_error']); ?></div>
                        <?php endif; ?>

                        <?php if (!empty($_SESSION['flash_success'])): ?>
                            <div class="ac-alert success"><?php echo htmlspecialchars($_SESSION['flash_success']); unset($_SESSION['flash_success']); ?></div>
                        <?php endif; ?>

                        <div class="ac-cards">
                            <a class="ac-card" href="<?php echo ROOT; ?>/hospital/addpatient/recipient">
                                <div class="ac-card__top">
                                    <div class="ac-icon" aria-hidden="true">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M20 21v-2a4 4 0 0 0-3-3.87"></path>
                                            <path d="M4 21v-2a4 4 0 0 1 4-4h4"></path>
                                            <circle cx="9" cy="7" r="4"></circle>
                                            <path d="M16 11h6"></path>
                                            <path d="M19 8v6"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="ac-title">Add Recipient Patient</h3>
                                        <p class="ac-desc">Create a recipient account with a generated registration number (REG-YYYY-0001) for Aftercare Portal login.</p>
                                        <div class="ac-cta">
                                            Continue
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M5 12h14"></path>
                                                <path d="M13 5l7 7-7 7"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </a>

                            <a class="ac-card" href="<?php echo ROOT; ?>/hospital/addpatient/donor">
                                <div class="ac-card__top">
                                    <div class="ac-icon" aria-hidden="true">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M12 21s-7-4.35-9.5-9A5.8 5.8 0 0 1 12 4a5.8 5.8 0 0 1 9.5 8c-2.5 4.65-9.5 9-9.5 9z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="ac-title">Enable Donor Aftercare Access</h3>
                                        <p class="ac-desc">Grant Aftercare Support access for an existing donor (enables the donor portal Aftercare tab).</p>
                                        <div class="ac-cta">
                                            Continue
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M5 12h14"></path>
                                                <path d="M13 5l7 7-7 7"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include __DIR__ . '/inc/footer.view.php'; ?>
