<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Donation | Life-Connect Sri Lanka</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= ROOT ?>/public/assets/css/home.css">
    <style>
        .hero { padding: 100px 0 80px; background: var(--blue-50); }
        .info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; margin-top: 50px; }
        .i-card { background: var(--white); border: 1px solid var(--g200); border-radius: var(--r); padding: 30px; transition: var(--tr); }
        .i-card:hover { border-color: var(--blue-300); transform: translateY(-5px); box-shadow: 0 10px 30px rgba(0, 91, 170, 0.08); }
        .i-icon { font-size: 2rem; color: var(--blue-600); margin-bottom: 20px; }
        .i-card h4 { margin-bottom: 15px; color: var(--slate); }
        .i-card p { font-size: 0.9rem; color: var(--g500); line-height: 1.6; }
        .highlight-box { background: var(--blue-900); color: var(--white); padding: 40px; border-radius: 16px; margin: 50px 0; display: flex; align-items: center; gap: 40px; flex-wrap: wrap; }
        .h-text h2 { color: var(--white); margin-bottom: 10px; }
        .h-text p { opacity: 0.8; font-size: 0.95rem; }
        .h-icon { font-size: 4rem; opacity: 0.2; }
        .warning-strip { background: #fff5f5; border-left: 4px solid #ef4444; padding: 25px; border-radius: 8px; margin-bottom: 30px; }
        .warning-strip h4 { color: #c53030; margin-bottom: 8px; display: flex; align-items: center; gap: 10px; }
        .stat-circle-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 20px; text-align: center; margin-top: 40px; }
        .sc-item { padding: 20px; }
        .sc-val { font-size: 2.2rem; font-weight: 800; color: var(--blue-600); display: block; }
        .sc-label { font-size: 0.8rem; color: var(--g500); font-weight: 600; text-transform: uppercase; }
    </style>
</head>
<body>

<?php include __DIR__ . '/../templates/home_header.view.php'; ?>

<!-- ========== HERO ========== -->
<section class="hero">
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
            <img src="<?= ROOT ?>/public/assets/images/faq-person1.jpg" alt="Live Donation" class="hero-img">
        </div>
    </div>
</section>

<!-- ========== WHAT IS LIVE DONATION ========== -->
<section class="serve" style="background: var(--white);">
    <div class="container">
        <div class="sec-header">
            <h2>What is Live Donation?</h2>
            <div class="underline"></div>
            <p>Donating an organ or part of an organ while you are alive to save another.</p>
        </div>
        
        <div class="info-grid">
            <div class="i-card">
                <div class="i-icon"><i class="fa-solid fa-kidneys"></i></div>
                <h4>One Kidney</h4>
                <p>Most common live donation. You can live a full, healthy life with just one kidney.</p>
            </div>
            <div class="i-card">
                <div class="i-icon"><i class="fa-solid fa-hospital-user"></i></div>
                <h4>Part of Liver</h4>
                <p>The liver regenerates to near-normal size within 3-6 months for both donor and recipient.</p>
            </div>
            <div class="i-card">
                <div class="i-icon"><i class="fa-solid fa-dna"></i></div>
                <h4>Bone Marrow</h4>
                <p>Naturally regenerates after donation, used to treat various blood disorders.</p>
            </div>
        </div>
    </div>
</section>

<!-- ========== HIGHLIGHT ========== -->
<div class="container">
    <div class="highlight-box">
        <div class="h-text">
            <h2>Your Body, Your Choice</h2>
            <p>There is NO obligation to donate, even to family members. Your decision must be completely voluntary, free from pressure or coercion.</p>
        </div>
        <div class="h-icon" style="flex-shrink: 0;"><i class="fa-solid fa-shield-heart"></i></div>
    </div>
</div>

<!-- ========== ELIGIBILITY & RULES ========== -->
<section class="legal" style="background: var(--g50);">
    <div class="container">
        <div class="sec-header">
            <h2>Eligibility & Relationships</h2>
            <div class="underline"></div>
        </div>
        
        <div class="info-grid">
            <div class="i-card">
                <h4>Basic Requirements</h4>
                <ul style="color: var(--g500); font-size: 0.9rem; padding-left: 20px;">
                    <li style="margin-bottom: 10px;">Aged 18 years or older</li>
                    <li style="margin-bottom: 10px;">Mentally capable of decision-making</li>
                    <li>Excellent physical & mental health</li>
                </ul>
            </div>
            <div class="i-card">
                <h4>Close Family</h4>
                <p>Includes parents, children, siblings, and spouses. These cases are generally simpler to process legally.</p>
            </div>
            <div class="i-card">
                <h4>Non-Related Donors</h4>
                <p>Requires HTTB approval and ethics committee review to ensure no financial benefit or coercion exists.</p>
            </div>
        </div>
        
        <div class="warning-strip" style="margin-top: 40px;">
            <h4><i class="fa-solid fa-ban"></i> Zero Tolerance for trafficking</h4>
            <p>Buying or selling organs is a serious crime in Sri Lanka. Any suspicion of financial exchange will lead to immediate legal action and cancellation of the donation.</p>
        </div>
    </div>
</section>

<!-- ========== STATS & FACTS ========== -->
<section class="stats" style="background: var(--white); border: none;">
    <div class="container">
        <div class="sec-header">
            <h2>Success & Survival</h2>
            <div class="underline"></div>
            <p>The safety of the donor is our absolute priority.</p>
        </div>
        
        <div class="stat-circle-grid">
            <div class="sc-item"><span class="sc-val">99.8%</span><span class="sc-label">Kidney survival</span></div>
            <div class="sc-item"><span class="sc-val">90%+</span><span class="sc-label">Graft success</span></div>
            <div class="sc-item"><span class="sc-val">4-6</span><span class="sc-label">Weeks recovery</span></div>
            <div class="sc-item"><span class="sc-val">200+</span><span class="sc-label">Annual transplants</span></div>
        </div>
    </div>
</section>

<!-- ========== CTA ========== -->
<section class="cta-section">
    <div class="container">
        <h2>Save a Life Today</h2>
        <p>Talk to our specialists or your family about becoming a live donor.</p>
        <a href="<?= ROOT ?>/signup" class="btn-hero" style="background: var(--white); color: var(--blue-900);">
            <span>Get Started</span> <i class="fa-solid fa-arrow-right"></i>
        </a>
    </div>
</section>

<?php include __DIR__ . '/../templates/home_footer.view.php'; ?>

</body>
</html>
