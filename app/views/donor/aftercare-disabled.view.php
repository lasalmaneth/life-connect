<?php if (!defined('ROOT')) die(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?> - LifeConnect</title>
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/donor/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .disabled-container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: calc(100vh - 80px);
            text-align: center;
            padding: 2rem;
            background: #f8fafc;
        }
        .disabled-card {
            background: white;
            padding: 3rem 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            max-width: 500px;
            width: 100%;
        }
        .disabled-icon {
            font-size: 4rem;
            color: #cbd5e1;
            margin-bottom: 1.5rem;
        }
        .disabled-title {
            font-size: 1.5rem;
            color: #1e293b;
            margin-bottom: 1rem;
            font-weight: 600;
        }
        .disabled-text {
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>
    <?php include "inc/header.view.php"; ?>
    <div class="d-layout">
        <?php include "inc/sidebar.view.php"; ?>
        <main class="d-main">
            <div class="disabled-container">
                <div class="disabled-card">
                    <i class="fas fa-lock disabled-icon"></i>
                    <h2 class="disabled-title">Access Restricted</h2>
                    <p class="disabled-text">
                        Aftercare access will be available once approved by the hospital. 
                        Please contact your assigned hospital to request access to the Aftercare Support portal.
                    </p>
                    <a href="<?= ROOT ?>/donor" class="btn btn-primary" style="display: inline-block; padding: 0.75rem 1.5rem; background: #003b6e; color: white; text-decoration: none; border-radius: 6px; font-weight: 500;">
                        Return to Dashboard
                    </a>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
