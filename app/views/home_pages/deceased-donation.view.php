<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deceased Donation | Life-Connect Sri Lanka</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= ROOT ?>/public/assets/css/home.css">
</head>
<body>

<?php include __DIR__ . '/../templates/home_header.view.php'; ?>

<!-- ========== HERO ========== -->
<section class="page-hero">
    <div class="container hero-grid">
        <div class="hero-text">
            <h1>Deceased Organ & Full Body Donation</h1>
            <p>A selfless gift that saves lives, teaches future doctors, and creates a lasting legacy of compassion in Sri Lanka.</p>
            <div class="hero-badges">
                <div class="badge-item">
                    <i class="fa-solid fa-file-signature"></i>
                    <div><strong>Legal</strong><span>Process</span></div>
                </div>
                <div class="badge-item">
                    <i class="fa-solid fa-shield-halved"></i>
                    <div><strong>Secure</strong><span>Consent</span></div>
                </div>
            </div>
        </div>
        <div class="hero-image-wrap">
            <div class="hero-shape"></div>
            <img src="<?= ROOT ?>/public/assets/images/home-deceased.png" class="hero-img" alt="Deceased Donation" />
        </div>
    </div>
</section>

<main class="container section-padding">

    <!-- Overview Section -->
    <div class="two-col" style="gap:80px">
        <div>
            <div class="sec-header sec-header--left">
                <h2>Saving lives after life.</h2>
                <div class="underline underline--left"></div>
            </div>
            <p class="content-p">Deceased donation happens when organs or tissues are donated for transplantation after a person has died. One deceased donor can save up to eight lives and improve the lives of many others through tissue donation.</p>
            <p class="content-p">In Sri Lanka, deceased donation is managed by authorized hospitals and transplant coordinators, ensuring that the process is handled with the highest medical and ethical standards.</p>
        </div>
        <div>
            <div class="card-highlight">
                <i class="fa-solid fa-heart-pulse"></i>
                <p>One donor can save 8 lives</p>
            </div>
        </div>
    </div>

    <!-- Organ List Section -->
    <div class="section-padding">
        <div class="sec-header">
            <h2>What can be donated after death?</h2>
            <div class="underline"></div>
        </div>
        <div class="info-grid">
            <div class="info-card">
                <h4>Major Organs</h4>
                <div class="organ-list-grid">
                    <div class="organ-item"><i class="fa-solid fa-heart"></i> Heart</div>
                    <div class="organ-item"><i class="fa-solid fa-lungs"></i> Lungs</div>
                    <div class="organ-item"><i class="fa-solid fa-kidney"></i> Kidneys</div>
                    <div class="organ-item"><i class="fa-solid fa-dna"></i> Liver</div>
                </div>
            </div>
            <div class="info-card">
                <h4>Tissues & Others</h4>
                <div class="organ-list-grid">
                    <div class="organ-item"><i class="fa-solid fa-eye"></i> Corneas</div>
                    <div class="organ-item"><i class="fa-solid fa-layer-group"></i> Tissues</div>
                    <div class="organ-item"><i class="fa-solid fa-bone"></i> Bones</div>
                    <div class="organ-item"><i class="fa-solid fa-person"></i> Full Body</div>
                </div>
            </div>
        </div>
    </div>

    <!-- The Process Section -->
    <div class="section-padding">
        <div class="sec-header">
            <h2>The Donation Process</h2>
            <div class="underline"></div>
        </div>
        <div class="steps">
            <div class="step">
                <div class="num">1</div>
                <div class="step-text">
                    <h4>Medical Confirmation</h4>
                    <p>Death is confirmed by independent medical specialists according to strict legal and clinical standards.</p>
                </div>
            </div>
            <div class="step">
                <div class="num">2</div>
                <div class="step-text">
                    <h4>Consent Verification</h4>
                    <p>Transplant coordinators check the Life-Connect system and consult the family to confirm the donor's wishes.</p>
                </div>
            </div>
            <div class="step">
                <div class="num">3</div>
                <div class="step-text">
                    <h4>Medical Suitability</h4>
                    <p>Doctors evaluate the condition of organs and tissues to ensure they are safe for transplantation.</p>
                </div>
            </div>
            <div class="step">
                <div class="num">4</div>
                <div class="step-text">
                    <h4>Retrieval & Transplant</h4>
                    <p>Organs are surgically retrieved and transported to matching patients on the national waiting list.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Danger Box / Important Note -->
    <div class="danger-box">
        <h4><i class="fa-solid fa-triangle-exclamation"></i> Important Legal Notice</h4>
        <p>In Sri Lanka, organ donation must be entirely voluntary. It is strictly illegal to buy or sell human organs. Any attempt to commercialize donation is a criminal offense.</p>
    </div>

</main>

<!-- ========== CTA ========== -->
<section class="cta-box container" style="margin-bottom:80px">
    <h2>Leave a legacy of life.</h2>
    <p style="margin-bottom:30px;opacity:0.9">Registering your decision now ensures your family and medical teams can honor your wishes in the future.</p>
    <div class="cta-actions">
        <a href="<?= ROOT ?>/signup" class="btn-hero"><i class="fa-solid fa-user-plus"></i> Join the Registry</a>
        <a href="<?= ROOT ?>/legal" class="btn-outline">Read Legal Guidelines</a>
    </div>
</section>

<?php include __DIR__ . '/../templates/home_footer.view.php'; ?>

</body>
</html>
