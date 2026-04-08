<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deceased Donation | Life-Connect Sri Lanka</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= ROOT ?>/public/assets/css/home.css">
    <style>
        .hero { padding: 100px 0 80px; background: var(--g50); }
        .donation-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; margin-top: 40px; }
        .organ-list-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin-top: 20px; }
        .organ-item { display: flex; align-items: center; gap: 10px; font-size: 0.9rem; color: var(--g700); }
        .organ-item i { color: var(--blue-600); width: 20px; text-align: center; }
        .hospital-card { background: var(--white); border: 1px solid var(--g200); border-radius: var(--r); padding: 25px; margin-bottom: 20px; transition: var(--tr); }
        .hospital-card:hover { border-color: var(--blue-300); transform: translateY(-4px); box-shadow: 0 10px 30px rgba(0, 91, 170, 0.08); }
        .hospital-card h4 { color: var(--blue-700); margin-bottom: 8px; font-size: 1.1rem; }
        .hospital-card p { font-size: 0.9rem; color: var(--g500); }
        .danger-box { background: #fff5f5; border-left: 4px solid #ef4444; padding: 20px; border-radius: 8px; margin: 30px 0; }
        .danger-box h4 { color: #c53030; margin-bottom: 10px; display: flex; align-items: center; gap: 10px; }
        .cta-section { padding: 80px 0; background: var(--blue-900); color: var(--white); text-align: center; }
        .cta-section h2 { font-size: 2.5rem; margin-bottom: 20px; }
        .cta-section p { margin-bottom: 40px; opacity: 0.9; max-width: 600px; margin-left: auto; margin-right: auto; }
        .step-card { display: flex; gap: 20px; margin-bottom: 25px; align-items: flex-start; }
        .step-num { width: 40px; height: 40px; background: var(--blue-600); color: var(--white); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; flex-shrink: 0; }
        .step-text h4 { margin-bottom: 5px; color: var(--slate); }
        .step-text p { font-size: 0.9rem; color: var(--g500); }
    </style>
</head>
<body>

<?php include __DIR__ . '/../templates/home_header.view.php'; ?>

<!-- ========== HERO ========== -->
<section class="hero">
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
            <img src="<?= ROOT ?>/public/assets/images/faq-person1.jpg" alt="Deceased Donation" class="hero-img">
        </div>
    </div>
</section>

<!-- ========== TYPES OF DONATION ========== -->
<section class="serve">
    <div class="container">
        <div class="sec-header">
            <h2>Types of Donation After Death</h2>
            <div class="underline"></div>
            <p>Every donation counts. Whether it's specific organs or your entire body, your choice makes an impact.</p>
        </div>
        <div class="donation-grid">
            <!-- Organ Donation Card -->
            <div class="s-card">
                <div class="s-icon"><i class="fa-solid fa-heart-pulse"></i></div>
                <h3>Organ & Tissue Donation</h3>
                <p>Donate specific organs and tissues to save lives through transplants.</p>
                <div class="organ-list-grid">
                    <div class="organ-item"><i class="fa-solid fa-heart"></i><span>Heart</span></div>
                    <div class="organ-item"><i class="fa-solid fa-lungs"></i><span>Lungs</span></div>
                    <div class="organ-item"><i class="fa-solid fa-kidneys"></i><span>Kidneys</span></div>
                    <div class="organ-item"><i class="fa-solid fa-brain"></i><span>Liver</span></div>
                    <div class="organ-item"><i class="fa-solid fa-eye"></i><span>Eyes</span></div>
                    <div class="organ-item"><i class="fa-solid fa-bone"></i><span>Tissues</span></div>
                </div>
            </div>
            <!-- Body Donation Card -->
            <div class="s-card">
                <div class="s-icon"><i class="fa-solid fa-graduation-cap"></i></div>
                <h3>Full Body Donation</h3>
                <p>Equip the next generation of medical professionals through anatomy education and research.</p>
                <ul style="text-align: left; margin-top: 15px; color: var(--g500); font-size: 0.9rem;">
                    <li style="margin-bottom: 8px;">Managed by University Medical Faculties</li>
                    <li style="margin-bottom: 8px;">Critical for medical student training</li>
                    <li>Advances surgical research in Sri Lanka</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- ========== CRITICAL INFO ========== -->
<section class="legal" style="background: var(--white);">
    <div class="container">
        <div class="sec-header">
            <h2>Registration Requirements</h2>
            <div class="underline"></div>
            <p>Nothing happens automatically. Clear written consent is mandatory during your lifetime.</p>
        </div>
        
        <div class="danger-box">
            <h4><i class="fa-solid fa-triangle-exclamation"></i> CRITICAL: You MUST Register BEFORE Death</h4>
            <p>For Full Body Donation, your family <strong>CANNOT</strong> choose or transfer your body to any medical school unless <strong>YOU</strong> registered with that specific school during your lifetime. This is a legal requirement that cannot be bypassed.</p>
        </div>

        <div class="donation-grid">
            <div class="c-card" style="padding: 30px; border: 1px solid var(--g200); border-radius: var(--r);">
                <div class="step-card">
                    <div class="step-num">1</div>
                    <div class="step-text">
                        <h4>Written Consent</h4>
                        <p>Consent must be recorded, signed, and witnessed by authorized individuals.</p>
                    </div>
                </div>
                <div class="step-card">
                    <div class="step-num">2</div>
                    <div class="step-text">
                        <h4>Organ Selection</h4>
                        <p>Specify multiple organs or tissues. Each change requires a new witnessed signature.</p>
                    </div>
                </div>
                <div class="step-card">
                    <div class="step-num">3</div>
                    <div class="step-text">
                        <h4>Override Rule</h4>
                        <p>The most recent valid consent always overrides older decisions.</p>
                    </div>
                </div>
            </div>
            
            <div class="legal-detail" style="box-shadow: none; border: 1px solid var(--g200);">
                <div class="legal-text">
                    <h3>Where to Register?</h3>
                    <p><strong>Organ Donation:</strong> National registry coordinated via Sri Jayewardenepura General Hospital (SJGH).</p>
                    <p><strong>Full Body Donation:</strong> Directly with University Anatomy Departments (Colombo, Peradeniya, Jaffna, Kelaniya, SJPU, etc.).</p>
                    <a href="<?= ROOT ?>/legal" class="learn-more">View Legal Framework <i class="fa-solid fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========== HOSPITAL LIST ========== -->
<section class="stats" style="background: var(--g50); border-top: none;">
    <div class="container">
        <div class="sec-header">
            <h2>Participating Institutions</h2>
            <div class="underline"></div>
            <p>Major centers for organ transplants and medical education in Sri Lanka.</p>
        </div>
        
        <div class="donation-grid">
            <div>
                <h4><i class="fa-solid fa-hospital" style="color: var(--blue-600); margin-right: 10px;"></i> Transplant Centers</h4>
                <div class="hospital-card">
                    <h4>National Hospital, Colombo</h4>
                    <p>Main center for national transplant programs.</p>
                </div>
                <div class="hospital-card">
                    <h4>SJ General Hospital, Nugegoda</h4>
                    <p>National coordination unit for deceased donors.</p>
                </div>
                <div class="hospital-card">
                    <h4>Teaching Hospital, Kandy</h4>
                    <p>Advanced heart and lung transplant facilities.</p>
                </div>
            </div>
            <div>
                <h4><i class="fa-solid fa-building-columns" style="color: var(--blue-600); margin-right: 10px;"></i> Medical Faculties</h4>
                <div class="hospital-card">
                    <h4>University of Colombo</h4>
                    <p>Faculty of Medicine - Anatomy Teaching.</p>
                </div>
                <div class="hospital-card">
                    <h4>University of Peradeniya</h4>
                    <p>Medicine and Dental Science programs.</p>
                </div>
                <div class="hospital-card">
                    <h4>University of Kelaniya</h4>
                    <p>Anatomy and Research center.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========== REFUSAL REASONS ========== -->
<section class="faq">
    <div class="container">
        <div class="sec-header sec-header--left">
            <h2>When Donation May be Refused</h2>
            <div class="underline underline--left"></div>
            <p>Donation may be refused due to medical, legal, or practical reasons — not judgment.</p>
        </div>
        
        <div class="faq-grid">
            <div class="faq-list">
                <div class="faq-item">
                    <button class="faq-q"><span>Medical Suitability</span><i class="fa-solid fa-plus"></i></button>
                    <div class="faq-a"><p>Infections like HIV/Hepatitis, advanced cancer, or severe organ decomposition may prevent donation.</p></div>
                </div>
                <div class="faq-item">
                    <button class="faq-q"><span>Legal Constraints</span><i class="fa-solid fa-plus"></i></button>
                    <div class="faq-a"><p>Cases requiring legal post-mortems (accidents, violent deaths) usually cannot proceed with donation.</p></div>
                </div>
                <div class="faq-item">
                    <button class="faq-q"><span>Logistical Factors</span><i class="fa-solid fa-plus"></i></button>
                    <div class="faq-a"><p>Donation must typically occur within 8-24 hours. Delays beyond this or improper embalming can lead to refusal.</p></div>
                </div>
            </div>
            <div class="faq-img">
                <img src="<?= ROOT ?>/public/assets/images/medical-team.png" alt="Medical Team">
            </div>
        </div>
    </div>
</section>

<!-- ========== CTA ========== -->
<section class="cta-section">
    <div class="container">
        <h2>Ready to make a difference?</h2>
        <p>Your decision today can bring hope to those in need. Join the national movement to save lives.</p>
        <a href="<?= ROOT ?>/signup" class="btn-hero" style="background: var(--white); color: var(--blue-900);">
            <span>Register as a Donor</span> <i class="fa-solid fa-arrow-right"></i>
        </a>
    </div>
</section>

<?php include __DIR__ . '/../templates/home_footer.view.php'; ?>

<script>
// FAQ Accordion
document.querySelectorAll('.faq-q').forEach(btn=>{
    btn.addEventListener('click',()=>{
        const item=btn.closest('.faq-item');
        item.classList.toggle('open');
    });
});
</script>

</body>
</html>
