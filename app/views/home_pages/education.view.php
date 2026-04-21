<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learn About Organ and Body Donation | LifeConnect Sri Lanka</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= ROOT ?>/public/assets/css/home.css">
</head>
<body>
    <?php include __DIR__ . '/../templates/home_header.view.php'; ?>

    <!-- Hero Section -->
    <section class="hero page-hero">
        <div class="hero-glass-box container">
            <div class="hero-grid">
            <div class="hero-text">
                <h1>Learn About Organ and Body Donation in Sri Lanka</h1>
                <p>Empowering Sri Lankans with knowledge, compassion, and the courage to give life.</p>
                <a href="<?= ROOT ?>/signup" class="btn-hero"><i class="fas fa-user-plus"></i> <span>Register as a Donor</span></a>
            </div>
            <div class="hero-image-wrap">
                <div class="hero-shape"></div>
                <img src="<?= ROOT ?>/public/assets/images/home-education.png" class="hero-img" alt="Education Hero" />
            </div>
            </div>
        </div>
    </section>

    <main>
        <!-- 1. Introduction -->
        <section class="section-padding">
            <div class="container two-col">
                <div>
                    <div class="sec-header sec-header--left">
                        <h2>Knowledge builds trust. Awareness saves lives.</h2>
                        <div class="underline underline--left"></div>
                    </div>
                    <p class="content-p">Organ and body donation is one of the most meaningful gifts a person can give. Many Sri Lankans are unaware of the process, legal rights, and impact of donation. This section helps you understand donation medically, ethically, and culturally, so you can make informed decisions.</p>
                    <ul class="key-points">
                        <li><i class="fas fa-check"></i> Clears myths and misconceptions</li>
                        <li><i class="fas fa-check"></i> Explains how donations save lives daily in Sri Lanka</li>
                        <li><i class="fas fa-check"></i> Encourages discussion with family and community</li>
                    </ul>
                </div>
                <div>
                    <div class="card-highlight">
                        <i class="fas fa-heart"></i>
                        <p>Giving life through knowledge and compassion</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- 2. What is Donation -->
        <section class="section-padding" style="background:var(--blue-25)">
            <div class="container">
                <h3 class="sec-h3">What Is Organ and Body Donation?</h3>
                <p class="content-p"><i class="fas fa-hand-holding-heart"></i> <strong>Organ Donation:</strong> Donating organs like the heart, kidneys, liver, lungs, corneas, or tissues to patients in need of transplants.</p>
                <p class="content-p"><i class="fas fa-user-graduate"></i> <strong>Body Donation:</strong> Donating the entire body after death for medical education and scientific research.</p>
                <div class="note-box">
                    <i class="fas fa-scale-balanced"></i>
                    <span>In Sri Lanka, organ and body donations are legally recognized under the Human Tissue Transplantation Act, ensuring dignity, consent, and transparency.</span>
                </div>
            </div>
        </section>

        <!-- 3. Types of Donation -->
        <section class="section-padding">
            <div class="container">
                <div class="sec-header">
                    <h2>Types of Donation</h2>
                    <div class="underline"></div>
                </div>
                <div class="info-grid">
                    <div class="info-card">
                        <div class="s-icon" style="background:var(--blue-50);width:48px;height:48px;border-radius:10px;display:flex;align-items:center;justify-content:center;margin-bottom:12px"><i class="fas fa-user-plus" style="color:var(--blue-600)"></i></div>
                        <h4>Live Donation</h4>
                        <p>A healthy individual donates an organ or tissue (e.g., kidney, part of liver, bone marrow) while alive.</p>
                    </div>
                    <div class="info-card">
                        <div class="s-icon" style="background:var(--blue-50);width:48px;height:48px;border-radius:10px;display:flex;align-items:center;justify-content:center;margin-bottom:12px"><i class="fas fa-heartbeat" style="color:var(--blue-600)"></i></div>
                        <h4>Deceased Organ Donation</h4>
                        <p>Organs are transplanted after medical confirmation of death, saving multiple lives.</p>
                    </div>
                    <div class="info-card">
                        <div class="s-icon" style="background:var(--blue-50);width:48px;height:48px;border-radius:10px;display:flex;align-items:center;justify-content:center;margin-bottom:12px"><i class="fas fa-university" style="color:var(--blue-600)"></i></div>
                        <h4>Full Body Donation</h4>
                        <p>The whole body is donated for teaching and research at approved Sri Lankan medical institutions.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- 4. What can be donated (Tabs) -->
        <section class="section-padding" style="background:var(--blue-25)">
            <div class="container">
                <div class="sec-header">
                    <h2>Which Organs and Tissues Can You Donate?</h2>
                    <div class="underline"></div>
                </div>
                <div class="tab-nav" id="donationTabs">
                    <button class="tab-btn active" data-tab="living"><i class="fas fa-user-plus"></i> Living Donor</button>
                    <button class="tab-btn" data-tab="deceased"><i class="fas fa-heartbeat"></i> Deceased Donor</button>
                    <button class="tab-btn" data-tab="tissues"><i class="fas fa-eye"></i> Tissues</button>
                </div>
                <div>
                    <div class="tab-panel active" data-panel="living">
                        <ul class="bl">
                            <li>Kidney – Most common</li>
                            <li>Part of the Liver – Regenerates for donor and recipient</li>
                            <li>Bone Marrow – Treats blood disorders</li>
                        </ul>
                    </div>
                    <div class="tab-panel" data-panel="deceased">
                        <ul class="bl">
                            <li>Heart – Life-saving for heart failure patients</li>
                            <li>Lungs – Single or double transplants</li>
                            <li>Liver – Entire organ</li>
                            <li>Kidneys – Frequently transplanted</li>
                            <li>Pancreas – For diabetes patients</li>
                            <li>Small Bowel (Intestine) – Rare, life-saving transplant</li>
                        </ul>
                    </div>
                    <div class="tab-panel" data-panel="tissues">
                        <ul class="bl">
                            <li>Corneas / Eyes – Restore sight</li>
                            <li>Skin – For burn victims</li>
                            <li>Heart Valves – For cardiac patients</li>
                            <li>Bones and Ligaments – Reconstructive surgery</li>
                            <li>Cartilage – Orthopedic repairs</li>
                        </ul>
                    </div>
                </div>
                <div class="note-box" style="margin-top:30px">
                    <i class="fas fa-star"></i>
                    <span>Even donating one organ or tissue can save or improve the lives of multiple Sri Lankans.</span>
                </div>
            </div>
        </section>

        <!-- 5. Donation Process -->
        <section class="section-padding">
            <div class="container">
                <div class="sec-header">
                    <h2>From decision to legacy.</h2>
                    <div class="underline"></div>
                </div>
                <div class="steps">
                    <div class="step"><span class="num">1</span><p><strong>Registration:</strong> Sign up through LifeConnect Sri Lanka and indicate donation preferences.</p></div>
                    <div class="step"><span class="num">2</span><p><strong>Consent:</strong> Record consent digitally, including faith and family preferences.</p></div>
                    <div class="step"><span class="num">3</span><p><strong>Family Awareness:</strong> Inform family, as their support is vital at the time of donation.</p></div>
                    <div class="step"><span class="num">4</span><p><strong>Hospital Coordination:</strong> Hospitals verify medical suitability, consent, and legal certificates.</p></div>
                    <div class="step"><span class="num">5</span><p><strong>Post-Donation:</strong> Families receive appreciation certificates and updates about how the donation helped.</p></div>
                </div>
            </div>
        </section>

        <!-- 6. Myths & Facts (Accordion) -->
        <section class="section-padding" style="background:var(--blue-25)">
            <div class="container">
                <div class="sec-header">
                    <h2>Common Myths & Facts</h2>
                    <div class="underline"></div>
                </div>
                <div class="faq-list" id="mythsAccordion">
                    <div class="faq-item">
                        <button class="faq-q"><span>Doctors won't save you if you're a donor</span><i class="fa-solid fa-plus"></i></button>
                        <div class="faq-a"><p>Medical teams always try to save your life first. Donation is considered only after certified death.</p></div>
                    </div>
                    <div class="faq-item">
                        <button class="faq-q"><span>Donation goes against religion</span><i class="fa-solid fa-plus"></i></button>
                        <div class="faq-a"><p>All major Sri Lankan religions value compassion and giving life — see our Religion page.</p></div>
                    </div>
                    <div class="faq-item">
                        <button class="faq-q"><span>I'm too old or unhealthy</span><i class="fa-solid fa-plus"></i></button>
                        <div class="faq-a"><p>Medical teams assess each organ individually; many people over 70 have successfully donated.</p></div>
                    </div>
                    <div class="faq-item">
                        <button class="faq-q"><span>My family must pay</span><i class="fa-solid fa-plus"></i></button>
                        <div class="faq-a"><p>There is no cost to families for organ or body donation.</p></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 7. Educational Resources -->
        <section class="section-padding">
            <div class="container">
                <div class="sec-header">
                    <h2>Educational Resources</h2>
                    <div class="underline"></div>
                </div>
                <ul class="res-list">
                    <li><i class="fas fa-video"></i> Awareness videos featuring Sri Lankan doctors and transplant teams</li>
                    <li><i class="fas fa-film"></i> Animated explainers of the donation process</li>
                    <li><i class="fas fa-file-pdf"></i> PDF guides on legal consent and donor card procedures</li>
                    <li><i class="fas fa-user"></i> Real-life donor and recipient stories</li>
                    <li><i class="fas fa-chart-column"></i> Infographics showing national transplant statistics</li>
                </ul>
            </div>
        </section>

        <!-- 11. Call to Action -->
        <section class="section-padding">
            <div class="container">
                <div class="cta-box">
                    <h2>Be the reason someone lives.</h2>
                    <div class="cta-actions">
                        <a href="<?= ROOT ?>/signup" class="btn-hero"><i class="fas fa-user-plus"></i> <span>Register as a Donor</span></a>
                        <a href="<?= ROOT ?>/deceased-donation" class="btn-outline"><i class="fas fa-book-open"></i> Learn More About Body Donation</a>
                        <a href="<?= ROOT ?>/home#tributes" class="btn-outline"><i class="fas fa-users"></i> Read Donor Stories</a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include __DIR__ . '/../templates/home_footer.view.php'; ?>
    <script>
    // Tabs
    document.querySelectorAll('#donationTabs .tab-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const tab = btn.getAttribute('data-tab');
            document.querySelectorAll('#donationTabs .tab-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            document.querySelectorAll('.tab-panel').forEach(p => {
                p.classList.toggle('active', p.getAttribute('data-panel') === tab);
            });
        });
    });
    // FAQ Accordion
    document.querySelectorAll('#mythsAccordion .faq-item').forEach(item => {
        item.querySelector('.faq-q').addEventListener('click', () => {
            const open = item.classList.contains('open');
            document.querySelectorAll('#mythsAccordion .faq-item').forEach(i => i.classList.remove('open'));
            if (!open) item.classList.add('open');
        });
    });
    </script>
</body>
</html>