<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Story - LifeConnect Sri Lanka</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="<?= ROOT ?>/public/assets/css/home.css">
</head>

<body>
    <?php include __DIR__ . '/../templates/home_header.view.php'; ?>

    <!-- Hero Section -->
    <section class="hero page-hero">
        <div class="hero-glass-box container">
            <div class="hero-grid">
                <div class="hero-text">
                    <h1>Our Journey Towards Saving Lives</h1>
                    <p>LifeConnect Sri Lanka is dedicated to making organ and body donation transparent, easy, and
                        life-changing for every Sri Lankan family.</p>
                    <div class="hero-quote" style="background:var(--g50); padding:18px; border-radius:var(--r); border-left:4px solid var(--blue-600); margin-bottom:24px">
                        <p style="color:var(--g500); font-style:italic; font-size:1rem; margin:0">"Every life saved is a story of hope, courage, and the power of human connection."</p>
                    </div>
                    <div class="hero-btns" style="display:flex; gap:14px; flex-wrap:wrap">
                        <a href="<?= ROOT ?>/signup" class="btn-hero"><i class="fas fa-heart"></i> <span>Join Us
                                Today</span></a>
                        <button class="btn-outline" onclick="scrollToSection('mission')" style="color:var(--blue-600); border-color:var(--blue-200); background:var(--white)"><i
                                class="fas fa-play"></i> Our Mission</button>
                    </div>
                </div>
                <div class="hero-visual">
                    <div class="story-symbols">
                        <div class="ssym"><i class="fas fa-heart"></i></div>
                        <div class="ssym"><i class="fas fa-hands-helping"></i></div>
                        <div class="ssym"><i class="fas fa-shield-alt"></i></div>
                        <div class="ssym"><i class="fas fa-users"></i></div>
                        <div class="ssym"><i class="fas fa-hospital"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission & Vision Section -->
    <section class="section-padding" id="mission" style="padding-bottom: 0;">
        <div class="container two-col" style="align-items: stretch;">
            <div>
                <div class="sec-header sec-header--left">
                    <h2>Our Mission & Vision</h2>
                    <div class="underline underline--left"></div>
                </div>
                <div class="mv-card">
                    <div class="mv-icon"><i class="fas fa-bullseye"></i></div>
                    <div>
                        <h3>Our Mission</h3>
                        <p>To create a centralized, transparent, and user-friendly platform that connects donors,
                            recipients, hospitals, and medical institutions across Sri Lanka, making organ and body
                            donation accessible, trustworthy, and life-changing for every family.</p>
                    </div>
                </div>
                <div class="mv-card">
                    <div class="mv-icon"><i class="fas fa-eye"></i></div>
                    <div>
                        <h3>Our Vision</h3>
                        <p>A society where organ donation is understood, embraced, and accessible for everyone - where
                            no life is lost due to lack of awareness, and every family can make informed decisions about
                            giving the gift of life.</p>
                    </div>
                </div>
            </div>
            <div style="display: flex;">
                <img src="<?= ROOT ?>/public/assets/images/home-ourstory.jpg" alt="LifeConnect mission"
                    style="width:100%; height:100%; min-height:420px; object-fit:cover; border-radius:var(--r); box-shadow:0 8px 30px rgba(0,0,0,.08)" />
            </div>
        </div>
    </section>

    <!-- Timeline Section -->
    <section class="section-padding">
        <div class="container">
            <div class="sec-header">
                <h2>Our Journey</h2>
                <div class="underline"></div>
                <p>From a simple idea to a life-saving platform - here's how LifeConnect Sri Lanka came to be.</p>
            </div>
            <div class="timeline" style="max-width:720px; margin:0 auto; position:relative; padding-left:44px">
                <div style="content:''; position:absolute; left:15px; top:0; bottom:0; width:2px; background:var(--g200); border-radius:1px"></div>
                <div class="tl-item">
                    <div class="tl-dot"></div>
                    <span class="tl-year">2023</span>
                    <div class="tl-card">
                        <div class="tl-head" style="display:flex; align-items:center; gap:12px; margin-bottom:10px">
                            <div class="tl-ico" style="width:38px; height:38px; border-radius:10px; background:var(--blue-50); display:flex; align-items:center; justify-content:center; flex-shrink:0"><i class="fas fa-lightbulb"></i></div>
                            <h3>The Idea</h3>
                        </div>
                        <p>Recognizing the need for a centralized organ donation platform in Sri Lanka, our founders
                            began researching the challenges families face when making donation decisions.</p>
                    </div>
                </div>
                <div class="tl-item">
                    <div class="tl-dot"></div>
                    <span class="tl-year">2024</span>
                    <div class="tl-card">
                        <div class="tl-head" style="display:flex; align-items:center; gap:12px; margin-bottom:10px">
                            <div class="tl-ico" style="width:38px; height:38px; border-radius:10px; background:var(--blue-50); display:flex; align-items:center; justify-content:center; flex-shrink:0"><i class="fas fa-search"></i></div>
                            <h3>Research & Development</h3>
                        </div>
                        <p>Conducted extensive surveys with families, hospitals, and medical schools. Developed
                            partnerships with leading medical institutions and legal experts to ensure compliance with
                            Sri Lankan laws.</p>
                    </div>
                </div>
                <div class="tl-item">
                    <div class="tl-dot"></div>
                    <span class="tl-year">2025</span>
                    <div class="tl-card">
                        <div class="tl-head" style="display:flex; align-items:center; gap:12px; margin-bottom:10px">
                            <div class="tl-ico" style="width:38px; height:38px; border-radius:10px; background:var(--blue-50); display:flex; align-items:center; justify-content:center; flex-shrink:0"><i class="fas fa-rocket"></i></div>
                            <h3>Platform Launch</h3>
                        </div>
                        <p>LifeConnect Sri Lanka officially launched with our first registered donors and hospital
                            partnerships. Successfully facilitated our first organ donation coordination.</p>
                    </div>
                </div>
                <div class="tl-item">
                    <div class="tl-dot" style="background:var(--blue-600)"></div>
                    <span class="tl-year">2026</span>
                    <div class="tl-card">
                        <div class="tl-head" style="display:flex; align-items:center; gap:12px; margin-bottom:10px">
                            <div class="tl-ico" style="width:38px; height:38px; border-radius:10px; background:var(--blue-50); display:flex; align-items:center; justify-content:center; flex-shrink:0"><i class="fas fa-expand-arrows-alt"></i></div>
                            <h3>Expansion & Growth</h3>
                        </div>
                        <p>Expanded to all major hospitals and medical schools across Sri Lanka. Introduced advanced
                            features including family custodian management and digital donor cards.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="section-padding" style="background:var(--blue-25)">
        <div class="container">
            <div class="sec-header">
                <h2>Meet Our Team</h2>
                <div class="underline"></div>
                <p>The passionate individuals behind LifeConnect Sri Lanka, dedicated to saving lives and supporting
                    families.</p>
            </div>
            <div class="info-grid" style="grid-template-columns:repeat(auto-fit,minmax(240px,1fr))">
                <div class="team-card">
                    <div class="team-photo"><i class="fas fa-user-md"></i></div>
                    <h3>Dr. Priya Fernando</h3>
                    <p class="team-role" style="color:var(--blue-600); font-weight:600; font-size:.85rem; margin-bottom:12px">Founder & Medical Director</p>
                    <p class="team-bio" style="color:var(--g500); line-height:1.6; font-size:.85rem; margin-bottom:14px">Leading transplant surgeon with 15+ years experience. Passionate about making
                        organ donation accessible to all Sri Lankan families.</p>
                    <div class="team-social" style="display:flex; justify-content:center; gap:12px"><i class="fab fa-linkedin" style="color:var(--blue-600); cursor:pointer"></i><i class="fas fa-envelope" style="color:var(--blue-600); cursor:pointer"></i></div>
                </div>
                <div class="team-card">
                    <div class="team-photo"><i class="fas fa-code"></i></div>
                    <h3>Nuwan Perera</h3>
                    <p class="team-role" style="color:var(--blue-600); font-weight:600; font-size:.85rem; margin-bottom:12px">Tech Lead & Co-Founder</p>
                    <p class="team-bio" style="color:var(--g500); line-height:1.6; font-size:.85rem; margin-bottom:14px">Software engineer specializing in healthcare technology. Committed to building
                        secure, user-friendly platforms that save lives.</p>
                    <div class="team-social" style="display:flex; justify-content:center; gap:12px"><i class="fab fa-linkedin" style="color:var(--blue-600); cursor:pointer"></i><i class="fas fa-envelope" style="color:var(--blue-600); cursor:pointer"></i></div>
                </div>
                <div class="team-card">
                    <div class="team-photo"><i class="fas fa-gavel"></i></div>
                    <h3>Adv. Ravi Silva</h3>
                    <p class="team-role" style="color:var(--blue-600); font-weight:600; font-size:.85rem; margin-bottom:12px">Legal Advisor</p>
                    <p class="team-bio" style="color:var(--g500); line-height:1.6; font-size:.85rem; margin-bottom:14px">Legal expert ensuring LifeConnect complies with all Sri Lankan laws and
                        regulations. Advocate for donor and recipient rights.</p>
                    <div class="team-social" style="display:flex; justify-content:center; gap:12px"><i class="fab fa-linkedin" style="color:var(--blue-600); cursor:pointer"></i><i class="fas fa-envelope" style="color:var(--blue-600); cursor:pointer"></i></div>
                </div>
                <div class="team-card">
                    <div class="team-photo"><i class="fas fa-heart"></i></div>
                    <h3>Amali Perera</h3>
                    <p class="team-role" style="color:var(--blue-600); font-weight:600; font-size:.85rem; margin-bottom:12px">Family Support Coordinator</p>
                    <p class="team-bio" style="color:var(--g500); line-height:1.6; font-size:.85rem; margin-bottom:14px">Dedicated to supporting families through the donation process. Ensures every
                        family feels supported and informed.</p>
                    <div class="team-social" style="display:flex; justify-content:center; gap:12px"><i class="fab fa-linkedin" style="color:var(--blue-600); cursor:pointer"></i><i class="fas fa-envelope" style="color:var(--blue-600); cursor:pointer"></i></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Impact & Stats Section -->
    <section class="section-padding">
        <div class="container">
            <div class="sec-header">
                <h2>Our Impact</h2>
                <div class="underline"></div>
                <p>Real numbers, real lives saved - see how LifeConnect Sri Lanka is making a difference.</p>
            </div>
            <div class="info-grid stat-cards" style="grid-template-columns:repeat(auto-fit,minmax(200px,1fr))">
                <div class="stat-card">
                    <div class="s-ico"><i class="fas fa-heart"></i></div>
                    <div class="s-num" style="font-size: 2.4rem; font-weight: 800; margin-bottom: 6px;" data-target="<?= $stats->lives_saved ?? 127 ?>">0</div>
                    <div class="s-lbl" style="font-size: .92rem; font-weight: 600; margin-bottom: 4px;">Lives Saved</div>
                    <div class="s-desc" style="font-size: .78rem; opacity: .75;">Successful organ transplants facilitated</div>
                </div>
                <div class="stat-card">
                    <div class="s-ico"><i class="fas fa-user-plus"></i></div>
                    <div class="s-num" style="font-size: 2.4rem; font-weight: 800; margin-bottom: 6px;" data-target="<?= $stats->donor_count ?? 2847 ?>">0</div>
                    <div class="s-lbl" style="font-size: .92rem; font-weight: 600; margin-bottom: 4px;">Registered Donors</div>
                    <div class="s-desc" style="font-size: .78rem; opacity: .75;">People who have registered to save lives</div>
                </div>
                <div class="stat-card">
                    <div class="s-ico"><i class="fas fa-hospital"></i></div>
                    <div class="s-num" style="font-size: 2.4rem; font-weight: 800; margin-bottom: 6px;" data-target="<?= $stats->hospital_count ?? 23 ?>">0</div>
                    <div class="s-lbl" style="font-size: .92rem; font-weight: 600; margin-bottom: 4px;">Partner Hospitals</div>
                    <div class="s-desc" style="font-size: .78rem; opacity: .75;">Medical institutions using our platform</div>
                </div>
                <div class="stat-card">
                    <div class="s-ico"><i class="fas fa-graduation-cap"></i></div>
                    <div class="s-num" style="font-size: 2.4rem; font-weight: 800; margin-bottom: 6px;" data-target="<?= $stats->medical_school_count ?? 8 ?>">0</div>
                    <div class="s-lbl" style="font-size: .92rem; font-weight: 600; margin-bottom: 4px;">Medical Schools</div>
                    <div class="s-desc" style="font-size: .78rem; opacity: .75;">Educational institutions for body donation</div>
                </div>
            </div>

            <!-- Testimonials -->
            <div style="margin-top:60px">
                <div class="sec-header">
                    <h2>What Families Say</h2>
                    <div class="underline"></div>
                </div>
                <div class="info-grid" style="grid-template-columns:repeat(auto-fit,minmax(300px,1fr))">
                    <div class="test-card">
                        <div class="test-quote" style="color:var(--blue-600); font-size:1.6rem; margin-bottom:14px; opacity:.6"><i class="fas fa-quote-left"></i></div>
                        <p style="color:var(--g500); line-height:1.7; font-style:italic; font-size:.9rem; margin-bottom:16px">"LifeConnect made the impossible possible. When my father passed, his organs saved three
                            lives. The platform guided us through every step with compassion and clarity."</p>
                        <div class="test-author">
                            <strong style="color:var(--slate); font-size:.88rem; display:block; margin-bottom:2px">Mrs. Kamala Wickramasinghe</strong>
                            <span style="color:var(--blue-600); font-size:.8rem">Daughter of organ donor</span>
                        </div>
                    </div>
                    <div class="test-card">
                        <div class="test-quote" style="color:var(--blue-600); font-size:1.6rem; margin-bottom:14px; opacity:.6"><i class="fas fa-quote-left"></i></div>
                        <p style="color:var(--g500); line-height:1.7; font-style:italic; font-size:.9rem; margin-bottom:16px">"As a transplant surgeon, I've seen how LifeConnect streamlines the donation process. It's a
                            game-changer for Sri Lankan healthcare."</p>
                        <div class="test-author">
                            <strong style="color:var(--slate); font-size:.88rem; display:block; margin-bottom:2px">Dr. Suresh Mendis</strong>
                            <span style="color:var(--blue-600); font-size:.8rem">Transplant Surgeon, National Hospital</span>
                        </div>
                    </div>
                    <div class="test-card">
                        <div class="test-quote" style="color:var(--blue-600); font-size:1.6rem; margin-bottom:14px; opacity:.6"><i class="fas fa-quote-left"></i></div>
                        <p style="color:var(--g500); line-height:1.7; font-style:italic; font-size:.9rem; margin-bottom:16px">"The transparency and support provided by LifeConnect gave our family peace of mind during a
                            difficult time. We knew our loved one's wishes were being honored."</p>
                        <div class="test-author">
                            <strong style="color:var(--slate); font-size:.88rem; display:block; margin-bottom:2px">Mr. Rajesh Kumar</strong>
                            <span style="color:var(--blue-600); font-size:.8rem">Family member</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Partners Section -->
    <section class="section-padding" style="background:var(--blue-25)">
        <div class="container">
            <div class="sec-header">
                <h2>Our Trusted Partners</h2>
                <div class="underline"></div>
                <p>Working together with leading medical institutions across Sri Lanka.</p>
            </div>
            <div class="info-grid" style="grid-template-columns:repeat(auto-fit,minmax(200px,1fr))">
                <div class="info-card">
                    <div class="s-icon" style="background:var(--blue-50); width:48px; height:48px; border-radius:10px; display:flex; align-items:center; justify-content:center; margin-bottom:12px"><i class="fas fa-hospital" style="color:var(--blue-600)"></i></div>
                    <h3>National Hospital of Sri Lanka</h3>
                    <p>Premier transplant center</p>
                </div>
                <div class="info-card">
                    <div class="s-icon" style="background:var(--blue-50); width:48px; height:48px; border-radius:10px; display:flex; align-items:center; justify-content:center; margin-bottom:12px"><i class="fas fa-university" style="color:var(--blue-600)"></i></div>
                    <h3>University of Colombo</h3>
                    <p>Medical education partner</p>
                </div>
                <div class="info-card">
                    <div class="s-icon" style="background:var(--blue-50); width:48px; height:48px; border-radius:10px; display:flex; align-items:center; justify-content:center; margin-bottom:12px"><i class="fas fa-hospital" style="color:var(--blue-600)"></i></div>
                    <h3>Colombo General Hospital</h3>
                    <p>Cardiac transplant center</p>
                </div>
                <div class="info-card">
                    <div class="s-icon" style="background:var(--blue-50); width:48px; height:48px; border-radius:10px; display:flex; align-items:center; justify-content:center; margin-bottom:12px"><i class="fas fa-graduation-cap" style="color:var(--blue-600)"></i></div>
                    <h3>University of Peradeniya</h3>
                    <p>Medical school partner</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action Section -->
    <section class="cta-box container" style="margin-top:60px; margin-bottom:80px">
        <div class="two-col">
            <div>
                <div class="sec-header sec-header--left">
                    <h2 style="color:var(--white)">Become a Life-Saver Today</h2>
                    <div class="underline underline--left underline--gold"></div>
                </div>
                <p style="color:var(--white); opacity:.9; margin-bottom:24px">Join thousands of Sri Lankans who have chosen to give the gift of life. Your decision today could
                    save multiple lives tomorrow.</p>
                <div class="cta-actions">
                    <a href="<?= ROOT ?>/signup" class="btn-hero"><i class="fas fa-heart"></i> <span>Register as
                            Donor</span></a>
                    <a href="<?= ROOT ?>/signup" class="btn-outline"><i class="fas fa-donate"></i> Donate
                        Financially</a>
                </div>
            </div>
            <div class="cta-illus" style="text-align:center">
                <i class="fas fa-hands-helping" style="font-size:7rem; color:rgba(255,255,255,.2)"></i>
            </div>
        </div>
    </section>

    <?php include __DIR__ . '/../templates/home_footer.view.php'; ?>
    <script>
        function scrollToSection(id) {
            document.getElementById(id).scrollIntoView({ behavior: 'smooth' });
        }
        // Animate counters
        function animateCounters() {
            const counters = document.querySelectorAll('.s-num');
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
                            if (current >= target) { current = target; clearInterval(timer); }
                            counter.textContent = Math.floor(current);
                        }, 16);
                        observer.unobserve(counter);
                    }
                });
            }, { threshold: 0.5 });
            counters.forEach(counter => observer.observe(counter));
        }
        document.addEventListener('DOMContentLoaded', animateCounters);
    </script>
</body>

</html>