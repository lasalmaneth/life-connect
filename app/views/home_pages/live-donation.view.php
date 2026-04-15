<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Donation | Life-Connect Sri Lanka</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= ROOT ?>/public/assets/css/home.css">
</head>
<body>

<?php include __DIR__ . '/../templates/home_header.view.php'; ?>

<!-- ========== HERO ========== -->
<section class="page-hero">
    <div class="container hero-grid">
        <div class="hero-text">
            <h1>Live Organ Donation</h1>
            <p>One of the most generous acts a person can make. Learn about the safe, legal, and voluntary process of giving life while you live.</p>
            <div class="hero-badges">
                <div class="badge-item">
                    <i class="fa-solid fa-hand-holding-heart"></i>
                    <div><strong>Voluntary</strong><span>Consent</span></div>
                </div>
                <div class="badge-item">
                    <i class="fa-solid fa-user-doctor"></i>
                    <div><strong>Medical</strong><span>Safety</span></div>
                </div>
            </div>
        </div>
        <div class="hero-image-wrap">
            <div class="hero-shape"></div>
            <img src="<?= ROOT ?>/public/assets/images/home-live-donor.png" class="hero-img" alt="Live Donation" />
        </div>
    </div>
</section>

<main class="container section-padding">

    <!-- Information Grid -->
    <div class="info-grid">
        <div class="info-card">
            <div class="i-icon"><i class="fa-solid fa-circle-question"></i></div>
            <h4>What is Living Donation?</h4>
            <p>Living organ donation involves a healthy person donating an organ (mostly a kidney) or a part of an organ (like the liver) to another person in need of a transplant.</p>
        </div>
        <div class="info-card">
            <div class="i-icon"><i class="fa-solid fa-user-check"></i></div>
            <h4>Who can be a Donor?</h4>
            <p>Most healthy adults over the age of 21 can be considered for living donation. A thorough medical and psychological evaluation is required to ensure donor safety.</p>
        </div>
    </div>

    <!-- Highlight Box -->
    <div class="highlight-box">
        <div class="h-text">
            <h2>Safety is our priority.</h2>
            <p>Before any donation occurs, medical teams perform extensive tests to make sure that the donor will be able to live a healthy life with one kidney or a regenerated liver.</p>
        </div>
        <div class="h-icon"><i class="fa-solid fa-shield-heart"></i></div>
    </div>

    <!-- Warning / Legal -->
    <div class="warning-strip">
        <h4><i class="fa-solid fa-circle-exclamation"></i> Mandatory Legal Check</h4>
        <p>Living donation in Sri Lanka must satisfy all requirements of the Human Tissue Transplantation Act. It must be strictly voluntary, without any financial gain or coercion.</p>
    </div>

    <!-- Stats Section -->
    <div class="stat-circle-grid">
        <div class="sc-item">
            <span class="sc-val">21+</span>
            <span class="sc-label">Minimum Age</span>
        </div>
        <div class="sc-item">
            <span class="sc-val">100%</span>
            <span class="sc-label">Voluntary</span>
        </div>
        <div class="sc-item">
            <span class="sc-val">Regen</span>
            <span class="sc-label">Liver Capacity</span>
        </div>
    </div>

</main>

<!-- ========== CTA ========== -->
<section class="cta-box container" style="margin-bottom:80px">
    <h2>Give the gift of life today.</h2>
    <div class="cta-actions">
        <a href="<?= ROOT ?>/signup" class="btn-hero"><i class="fa-solid fa-user-plus"></i> Start Your Registration</a>
        <a href="<?= ROOT ?>/home#tributes" class="btn-outline">Success Stories</a>
    </div>
</section>

<?php include __DIR__ . '/../templates/home_footer.view.php'; ?>

</body>
</html>
