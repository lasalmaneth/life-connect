<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home | Life-Connect Sri Lanka</title>
    <meta name="description" content="LifeConnect Sri Lanka — Join the national movement to save lives through organ and body donation.">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= ROOT ?>/public/assets/css/home.css">
    <style>
        .hero {
            background: linear-gradient(135deg, var(--blue-100) 0%, var(--blue-50) 30%, var(--white) 65%, var(--blue-50) 100%) !important;
        }
        .home-hero-blend {
            box-shadow: none !important;
            border-radius: 0 !important;
            mix-blend-mode: multiply;
            -webkit-mask-image: linear-gradient(to right, transparent 0%, black 25%), linear-gradient(to bottom, transparent 0%, black 25%);
            -webkit-mask-composite: source-in;
            mask-image: linear-gradient(to right, transparent 0%, black 25%), linear-gradient(to bottom, transparent 0%, black 25%);
            mask-composite: intersect;
            height: auto !important;
            max-height: 700px !important;
            max-width: 160% !important;
            width: 150% !important;
            transform: translateX(12%);
            object-fit: contain !important;
            pointer-events: none;
        }
    </style>
</head>
<body>

<!-- ========== HEADER ========== -->
<?php include __DIR__ . '/templates/home_header.view.php'; ?>

<!-- ========== HERO ========== -->
<section class="hero">
    <div class="container hero-grid">
        <div class="hero-text">
            <h1>Life Connect <br>Sri Lanka</h1>
            <p>Join the national movement to save lives through organ and body donation. Register today and help create a healthier Sri Lanka.</p>
            <a href="<?= ROOT ?>/signup" class="btn-hero"><span>Become a Donor</span> <i class="fa-solid fa-arrow-right"></i></a>
        </div>
        <div class="hero-image-wrap">
            <img src="<?= ROOT ?>/public/assets/images/homeHero.png" alt="LifeConnect" class="hero-img home-hero-blend">
        </div>
    </div>
</section>

<!-- ========== STATS ========== -->
<section class="stats" id="stats">
    <div class="container stats-row">
        <div class="stat">
            <span class="stat-num" data-target="12450">0</span>
            <span class="stat-label">Registered Donors</span>
        </div>
        <div class="stat-divider"></div>
        <div class="stat">
            <span class="stat-num" data-target="850">0</span>
            <span class="stat-label">Successful Transplants</span>
        </div>
        <div class="stat-divider"></div>
        <div class="stat">
            <span class="stat-num" data-target="42">0</span>
            <span class="stat-label">Partner Hospitals</span>
        </div>
    </div>
</section>

<!-- ========== WHO WE SERVE ========== -->
<section class="serve">
    <div class="container">
        <div class="sec-header">
            <h2>Who We Serve</h2>
            <div class="underline"></div>
            <p>LifeConnect brings together donors, patients, and medical professionals to create a unified organ donation ecosystem across Sri Lanka.</p>
        </div>
        <div class="serve-cards">
            <div class="s-card">
                <div class="s-icon"><i class="fa-solid fa-hand-holding-heart"></i></div>
                <h3>For Donors</h3>
                <p>Donating organs saves lives. By registering as an organ donor, you can help someone in need of a life-saving transplant. Your generous act ensures a better quality of life for recipients.</p>
            </div>
            <div class="s-card">
                <div class="s-icon"><i class="fa-solid fa-user-injured"></i></div>
                <h3>For Patients</h3>
                <p>Organ transplantation provides hope to those suffering from organ failure. Patients who undergo transplants have the opportunity to lead a healthier, more fulfilling life.</p>
            </div>
            <div class="s-card">
                <div class="s-icon"><i class="fa-solid fa-user-doctor"></i></div>
                <h3>Health Professionals</h3>
                <p>Health professionals play a critical role in organ donation and transplantation — identifying potential donors, managing care, and providing aftercare throughout the process.</p>
            </div>
        </div>
    </div>
</section>

<!-- ========== LEGAL FRAMEWORK ========== -->
<section class="legal">
    <div class="container">
        <div class="sec-header sec-header--left">
            <h2>Legal Framework</h2>
            <div class="underline underline--left"></div>
            <p>LifeConnect operates in full compliance with Sri Lanka's national organ donation laws and regulations.</p>
        </div>
        <div class="legal-grid">
            <div class="legal-sidebar">
                <button class="legal-btn active" data-topic="laws"><i class="fa-solid fa-scale-balanced"></i> Relevant Laws</button>
                <button class="legal-btn" data-topic="consent"><i class="fa-solid fa-file-signature"></i> Consent Process</button>
                <button class="legal-btn" data-topic="privacy"><i class="fa-solid fa-shield-halved"></i> Privacy & Data</button>
                <button class="legal-btn" data-topic="liability"><i class="fa-solid fa-building-columns"></i> Liability & Compliance</button>
                <button class="legal-btn" data-topic="rights"><i class="fa-solid fa-gavel"></i> Donor & Recipient Rights</button>
            </div>
            <div class="legal-detail">
                <div class="legal-img-wrap">
                    <img src="<?= ROOT ?>/public/assets/images/certificate-badge.jpg" alt="Legal" id="legalImg">
                </div>
                <div class="legal-text">
                    <div class="l-panel active" id="p-laws">
                        <h3>The Human Tissue Act, No. 48 of 1987</h3>
                        <p>LifeConnect Sri Lanka operates in full compliance with national organ donation regulations. This includes adherence to the Human Tissue Act and related legal provisions governing organ and body donations.</p>
                        <a href="<?= ROOT ?>/legal" class="learn-more">Learn more <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                    <div class="l-panel" id="p-consent">
                        <h3>Digital Consent Recording</h3>
                        <p>Donor consent is recorded digitally and securely, allowing donors to select organs or full-body donation options. Consent can be updated, and family members can be added as authorized witnesses.</p>
                        <a href="<?= ROOT ?>/legal" class="learn-more">Learn more <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                    <div class="l-panel" id="p-privacy">
                        <h3>Data Protection Standards</h3>
                        <p>LifeConnect ensures all personal and medical data is protected under national privacy laws. Only authorized personnel have access to sensitive information.</p>
                        <a href="<?= ROOT ?>/legal" class="learn-more">Learn more <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                    <div class="l-panel" id="p-liability">
                        <h3>Institutional Compliance</h3>
                        <p>Hospitals and medical institutions using LifeConnect are responsible for lawful handling of organs and donor materials. The platform provides legal documentation and audit trails.</p>
                        <a href="<?= ROOT ?>/legal" class="learn-more">Learn more <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                    <div class="l-panel" id="p-rights">
                        <h3>Your Rights Matter</h3>
                        <p>Donors and recipients have full legal rights to access their personal data, withdraw consent, and request information on organ usage.</p>
                        <a href="<?= ROOT ?>/legal" class="learn-more">Learn more <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========== TRIBUTES ========== -->
<section class="tributes" id="tributes">
    <div class="container">
        <div class="sec-header sec-header--light">
            <span class="pill">In Loving Memory</span>
            <h2 style="color: #fff;">A Celebration of Life</h2>
            <div class="underline underline--gold"></div>
        </div>
        <div class="tribute-row">
            <div class="t-card">
                <div class="t-photo"><img src="<?= ROOT ?>/public/assets/images/tribute.jpg" alt="Sarah M."></div>
                <h4>Sarah M.</h4>
                <p>"Those we love never truly leave us."</p>
            </div>
            <div class="t-card">
                <div class="t-photo"><img src="<?= ROOT ?>/public/assets/images/faq-person.jpg" alt="David R."></div>
                <h4>David R.</h4>
                <p>"All that we love deeply becomes a part of us."</p>
            </div>
            <div class="t-card">
                <div class="t-photo"><img src="<?= ROOT ?>/public/assets/images/faq-person1.jpg" alt="Amara S."></div>
                <h4>Amara S.</h4>
                <p>"Family is the people who want you in their life."</p>
            </div>
            <div class="t-card">
                <div class="t-photo"><img src="<?= ROOT ?>/public/assets/images/donation-heart.jpg" alt="Kumari P."></div>
                <h4>Kumari P.</h4>
                <p>"May you find peace in the arms of angels."</p>
            </div>
        </div>
    </div>
</section>

<!-- ========== FAQ ========== -->
<section class="faq">
    <div class="container">
        <div class="sec-header">
            <h2>Frequently Asked Questions</h2>
            <div class="underline"></div>
            <p>Find answers to common questions about organ donation and LifeConnect.</p>
        </div>
        <div class="faq-grid">
            <div class="faq-img">
                <img src="<?= ROOT ?>/public/assets/images/faq-person1.jpg" alt="FAQ">
            </div>
            <div class="faq-list">
                <div class="faq-item">
                    <button class="faq-q"><span>How do I get started?</span><i class="fa-solid fa-plus"></i></button>
                    <div class="faq-a"><p>You can get started by registering online, visiting our front desk, or contacting our support team for guidance.</p></div>
                </div>
                <div class="faq-item">
                    <button class="faq-q"><span>Who do I contact for urgent support?</span><i class="fa-solid fa-plus"></i></button>
                    <div class="faq-a"><p>For urgent matters, please call our emergency support line listed on the contact page.</p></div>
                </div>
                <div class="faq-item">
                    <button class="faq-q"><span>Where are your locations?</span><i class="fa-solid fa-plus"></i></button>
                    <div class="faq-a"><p>We have multiple locations across the country. Check the "Locations" page for detailed addresses.</p></div>
                </div>
                <div class="faq-item">
                    <button class="faq-q"><span>What are the visitor rules?</span><i class="fa-solid fa-plus"></i></button>
                    <div class="faq-a"><p>Visitors must check in at reception, wear an ID badge, and follow our safety guidelines.</p></div>
                </div>
                <div class="faq-item">
                    <button class="faq-q"><span>How do I access telehealth?</span><i class="fa-solid fa-plus"></i></button>
                    <div class="faq-a"><p>Telehealth appointments are available through our secure portal. You'll receive login instructions after booking.</p></div>
                </div>
                <div class="faq-item">
                    <button class="faq-q"><span>What is the Health Hub?</span><i class="fa-solid fa-plus"></i></button>
                    <div class="faq-a"><p>The Health Hub is your central resource for educational materials, self-care tips, and community programs.</p></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========== CONTACT ========== -->
<section class="contact">
    <div class="container">
        <div class="sec-header sec-header--left">
            <h2>Get In Touch</h2>
            <div class="underline underline--left"></div>
        </div>
        <div class="contact-grid">
            <div class="contact-left">
                <div class="c-card"><div class="c-icon"><i class="fa-solid fa-location-dot"></i></div><div><h4>Our Address</h4><p>Ministry of Health, Colombo 10, Sri Lanka</p></div></div>
                <div class="c-card"><div class="c-icon"><i class="fa-solid fa-phone"></i></div><div><h4>Call Us</h4><p>+94 11 234 5678</p></div></div>
                <div class="c-card"><div class="c-icon"><i class="fa-solid fa-envelope"></i></div><div><h4>Email Us</h4><p>info@lifeconnect.gov.lk</p></div></div>
                <div class="c-card"><div class="c-icon"><i class="fa-solid fa-clock"></i></div><div><h4>Working Hours</h4><p>Mon – Fri: 8:30 AM – 4:30 PM</p></div></div>
                <div class="contact-photo"><img src="<?= ROOT ?>/public/assets/images/medical-team.png" alt="Team"></div>
            </div>
            <form class="contact-form" id="contactForm" novalidate>
                <div class="f-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" placeholder="Enter your name">
                    <span class="f-err" id="nameErr">Name is required</span>
                </div>
                <div class="f-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" placeholder="Enter your email">
                    <span class="f-err" id="emailErr">Valid email is required</span>
                </div>
                <div class="f-group">
                    <label for="msg">Message</label>
                    <textarea id="msg" rows="5" placeholder="Write your message"></textarea>
                    <span class="f-err" id="msgErr">Message cannot be empty</span>
                </div>
                <button type="submit" class="btn-send">Send Message <i class="fa-solid fa-paper-plane"></i></button>
                <p class="f-success" id="successMsg"><i class="fa-solid fa-check-circle"></i> Your message has been sent successfully!</p>
            </form>
        </div>
    </div>
</section>

<!-- ========== FOOTER ========== -->
<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <div class="f-brand">
                <div class="f-logo"><img src="<?= ROOT ?>/public/assets/images/logo.png" alt="Logo"><span>Life Connect</span></div>
                <p>Connecting lives through compassion. Sri Lanka's national platform for organ and body donation coordination.</p>
                <div class="f-social">
                    <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#"><i class="fa-brands fa-twitter"></i></a>
                    <a href="#"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
                </div>
            </div>
            <div class="f-col"><h4>Explore</h4><ul><li><a href="<?= ROOT ?>/home">Home</a></li><li><a href="#stats">Statistics</a></li><li><a href="<?= ROOT ?>/education">Education</a></li><li><a href="<?= ROOT ?>/legal">Legal Framework</a></li></ul></div>
            <div class="f-col"><h4>About</h4><ul><li><a href="<?= ROOT ?>/our-story">Our Story</a></li><li><a href="<?= ROOT ?>/religion">Faith & Donation</a></li><li><a href="<?= ROOT ?>/reach-us">Contact Us</a></li></ul></div>
            <div class="f-col"><h4>Donation</h4><ul><li><a href="<?= ROOT ?>/signup">Become a Donor</a></li><li><a href="<?= ROOT ?>/live-donation">Live Donation</a></li><li><a href="<?= ROOT ?>/deceased-donation">Deceased Donation</a></li></ul></div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?= date('Y') ?> Life Connect Sri Lanka. All Rights Reserved.</p>
            <div><a href="#">Privacy Policy</a><a href="#">Terms of Service</a></div>
        </div>
    </div>
</footer>

<script>
const ROOT = '<?= ROOT ?>';
// Hamburger
document.getElementById('hamburger').addEventListener('click',()=>{
    document.getElementById('navLinks').classList.toggle('open');
});
// Legal tabs
document.querySelectorAll('.legal-btn').forEach(btn=>{
    btn.addEventListener('click',()=>{
        document.querySelectorAll('.legal-btn').forEach(b=>b.classList.remove('active'));
        btn.classList.add('active');
        const t=btn.dataset.topic;
        document.querySelectorAll('.l-panel').forEach(p=>p.classList.remove('active'));
        document.getElementById('p-'+t).classList.add('active');
        const imgs={
            'laws': ROOT+'/public/assets/images/certificate-badge.jpg',
            'consent': ROOT+'/public/assets/images/faq-person1.jpg',
            'privacy': ROOT+'/public/assets/images/faq-person.jpg',
            'liability': ROOT+'/public/assets/images/medical-team.png',
            'rights': ROOT+'/public/assets/images/patient-care.jpg'
        };
        if(imgs[t]) document.getElementById('legalImg').src=imgs[t];
    });
});
// FAQ
document.querySelectorAll('.faq-q').forEach(btn=>{
    btn.addEventListener('click',()=>{
        const item=btn.closest('.faq-item');
        const open=item.classList.contains('open');
        document.querySelectorAll('.faq-item').forEach(i=>i.classList.remove('open'));
        if(!open) item.classList.add('open');
    });
});
// Contact form
document.getElementById('contactForm').addEventListener('submit',function(e){
    e.preventDefault();
    let ok=true;
    document.querySelectorAll('.f-err').forEach(el=>el.style.display='none');
    if(!document.getElementById('name').value.trim()){document.getElementById('nameErr').style.display='block';ok=false;}
    if(!/^[^\s@]+@[^\s@]+\.[a-z]{2,}$/i.test(document.getElementById('email').value)){document.getElementById('emailErr').style.display='block';ok=false;}
    if(!document.getElementById('msg').value.trim()){document.getElementById('msgErr').style.display='block';ok=false;}
    if(ok){document.getElementById('successMsg').style.display='flex';this.reset();setTimeout(()=>{document.getElementById('successMsg').style.display='none';},4000);}
});

// Animate stats
function animateStats() {
    const stats = document.querySelectorAll('.stat-num');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const counter = entry.target;
                const target = parseInt(counter.getAttribute('data-target'));
                const duration = 2000;
                const increment = target / (duration / 16);
                let current = 0;
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) { 
                        current = target; 
                        clearInterval(timer); 
                        counter.textContent = current.toLocaleString(); // keeps the comma for 12,450
                    } else {
                        counter.textContent = Math.floor(current).toLocaleString();
                    }
                }, 16);
                observer.unobserve(counter);
            }
        });
    }, { threshold: 0.5 });
    stats.forEach(stat => observer.observe(stat));
}
document.addEventListener('DOMContentLoaded', animateStats);

</script>
</body>
</html>
