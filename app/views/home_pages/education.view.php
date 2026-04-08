<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learn About Organ and Body Donation | LifeConnect Sri Lanka</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= ROOT ?>/public/assets/css/home.css">
    <style>
        /* ── Page hero override ── */
        .page-hero{padding:80px 0 60px;background:linear-gradient(135deg, var(--blue-100) 0%, var(--blue-50) 30%, var(--white) 65%, var(--blue-50) 100%)}
        .page-hero .hero-text h1{font-size:2.8rem}
        .page-hero .hero-img{height:360px}

        /* ── Two-column layout ── */
        .two-col{display:grid;grid-template-columns:1fr 1fr;gap:50px;align-items:center}

        /* ── Highlight card ── */
        .card-highlight{background:linear-gradient(135deg,var(--blue-50),var(--white));border:1px solid var(--blue-200);border-radius:var(--r);padding:34px;display:flex;flex-direction:column;align-items:center;text-align:center;gap:14px}
        .card-highlight i{font-size:2.4rem;color:var(--blue-600)}
        .card-highlight p{color:var(--g500);line-height:1.6;font-weight:500;font-size:.95rem}

        /* ── Key points list ── */
        .key-points{list-style:none;padding:0;margin-top:18px}
        .key-points li{display:flex;align-items:center;gap:10px;margin:8px 0;padding:12px 14px;background:var(--white);border:1px solid var(--g200);border-radius:10px;font-size:.9rem;color:var(--slate);transition:all var(--tr)}
        .key-points li:hover{border-color:var(--blue-300)}
        .key-points i{color:#10b981;flex-shrink:0}

        /* ── Section h3 titles ── */
        .sec-h3{font-size:1.5rem;font-weight:700;color:var(--slate);margin-bottom:20px;letter-spacing:-.01em}

        /* ── Note/callout ── */
        .note-box{margin-top:20px;background:var(--blue-50);border:1px solid var(--blue-200);color:var(--blue-700);padding:14px 18px;border-radius:10px;display:flex;align-items:flex-start;gap:10px;font-size:.9rem;line-height:1.6}
        .note-box i{flex-shrink:0;margin-top:2px;font-size:1.1rem;color:var(--blue-600)}

        /* ── Content paragraphs ── */
        .content-p{font-size:.92rem;color:var(--g500);line-height:1.7;margin-bottom:14px}
        .content-p i{color:var(--blue-600);margin-right:6px}

        /* ── Tabs ── */
        .tab-nav{display:flex;justify-content:center;gap:10px;margin-bottom:30px;flex-wrap:wrap}
        .tab-btn{padding:10px 22px;border-radius:50px;border:1.5px solid var(--g200);background:var(--white);font-weight:600;font-size:.88rem;cursor:pointer;transition:all var(--tr);display:inline-flex;align-items:center;gap:8px;color:var(--g700);font-family:var(--font)}
        .tab-btn:hover{border-color:var(--blue-400);color:var(--blue-600);background:var(--blue-50)}
        .tab-btn.active{background:var(--blue-600);border-color:var(--blue-600);color:var(--white);box-shadow:0 4px 14px rgba(0,91,170,.22)}
        .tab-panel{display:none}
        .tab-panel.active{display:block;animation:fadeIn .35s ease}

        /* ── Bullet list (inside tabs/panels) ── */
        .bl{list-style:none;padding:0}
        .bl li{display:flex;align-items:flex-start;gap:10px;margin:8px 0;padding:12px 14px;background:var(--white);border:1px solid var(--g200);border-radius:10px;font-size:.88rem;color:var(--slate);line-height:1.6;transition:all var(--tr)}
        .bl li:hover{border-color:var(--blue-300)}
        .bl li::before{content:'•';color:var(--blue-600);font-weight:700;font-size:1.2rem;line-height:1}

        /* ── Process steps ── */
        .steps{display:grid;gap:14px;list-style:none;padding:0}
        .step{display:flex;gap:14px;align-items:flex-start;background:var(--white);padding:16px 18px;border-radius:var(--r);border:1px solid var(--g200);transition:all var(--tr)}
        .step:hover{border-color:var(--blue-300);transform:translateY(-2px);box-shadow:0 4px 14px rgba(0,91,170,.06)}
        .step .num{width:34px;height:34px;border-radius:50%;background:var(--blue-600);color:var(--white);display:flex;align-items:center;justify-content:center;font-weight:800;font-size:.9rem;flex-shrink:0}
        .step p{margin:0;color:var(--g500);line-height:1.6;font-size:.9rem}

        /* ── Resource list ── */
        .res-list{list-style:none;padding:0}
        .res-list li{display:flex;align-items:flex-start;gap:12px;margin:10px 0;padding:14px 16px;background:var(--white);border:1px solid var(--g200);border-radius:10px;font-size:.9rem;color:var(--slate);transition:all var(--tr)}
        .res-list li:hover{border-color:var(--blue-300);transform:translateY(-2px)}
        .res-list li i{color:var(--blue-600);flex-shrink:0;margin-top:2px}

        /* ── CTA box ── */
        .cta-box{background:linear-gradient(135deg,var(--blue-600),var(--blue-800));color:var(--white);border-radius:20px;padding:50px 36px;text-align:center}
        .cta-box h2{color:var(--white);margin-bottom:24px;font-size:2rem}
        .cta-actions{display:flex;gap:14px;justify-content:center;flex-wrap:wrap}
        .cta-actions .btn-hero{background:#10b981}
        .cta-actions .btn-hero:hover{background:#059669}
        .btn-outline{display:inline-flex;align-items:center;gap:10px;border:2px solid rgba(255,255,255,.4);color:var(--white);background:transparent;padding:14px 28px;border-radius:50px;font-weight:600;font-size:.95rem;transition:all var(--tr);text-decoration:none}
        .btn-outline:hover{background:rgba(255,255,255,.15);border-color:var(--white);transform:translateY(-2px)}

        /* ── Contact block ── */
        .ct-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:20px}

        @media(max-width:768px){
            .two-col{grid-template-columns:1fr}
            .page-hero .hero-grid{text-align:center;display:flex;flex-direction:column-reverse;gap:30px}
            .page-hero .hero-text>p{margin:0 auto 20px}
            .page-hero .btn-hero{margin:0 auto}
            .tab-nav{gap:6px}
            .tab-btn{padding:8px 16px;font-size:.82rem}
        }
        @media(max-width:600px){
            .serve-cards{grid-template-columns:1fr !important}
            .ct-grid{grid-template-columns:1fr}
        }
    </style>
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
                <img src="<?= ROOT ?>/public/assets/images/faq-person.jpg" class="hero-img" alt="Education Hero" />
            </div>
            </div>
        </div>
    </section>

    <main>
        <!-- 1. Introduction -->
        <section class="serve">
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
        <section class="legal">
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
        <section class="serve">
            <div class="container">
                <div class="sec-header">
                    <h2>Types of Donation</h2>
                    <div class="underline"></div>
                </div>
                <div class="serve-cards">
                    <div class="s-card">
                        <div class="s-icon"><i class="fas fa-user-plus"></i></div>
                        <h3>Live Donation</h3>
                        <p>A healthy individual donates an organ or tissue (e.g., kidney, part of liver, bone marrow) while alive.</p>
                    </div>
                    <div class="s-card">
                        <div class="s-icon"><i class="fas fa-heartbeat"></i></div>
                        <h3>Deceased Organ Donation</h3>
                        <p>Organs are transplanted after medical confirmation of death, saving multiple lives.</p>
                    </div>
                    <div class="s-card">
                        <div class="s-icon"><i class="fas fa-university"></i></div>
                        <h3>Full Body Donation</h3>
                        <p>The whole body is donated for teaching and research at approved Sri Lankan medical institutions.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- 4. What can be donated (Tabs) -->
        <section class="legal">
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
                <div class="note-box">
                    <i class="fas fa-star"></i>
                    <span>Even donating one organ or tissue can save or improve the lives of multiple Sri Lankans.</span>
                </div>
            </div>
        </section>

        <!-- 5. Donation Process -->
        <section class="serve">
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
        <section class="faq">
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

        <!-- 7. Benefits -->
        <section class="legal">
            <div class="container">
                <div class="sec-header">
                    <h2>Benefits of Donation</h2>
                    <div class="underline"></div>
                </div>
                <div class="serve-cards">
                    <div class="s-card">
                        <div class="s-icon"><i class="fas fa-list-check"></i></div>
                        <h3>Reduces waiting lists</h3>
                        <p>Transplants reduce the time patients spend waiting for suitable organs.</p>
                    </div>
                    <div class="s-card">
                        <div class="s-icon"><i class="fas fa-microscope"></i></div>
                        <h3>Improves education & research</h3>
                        <p>Body donation helps train future doctors and advance medical science.</p>
                    </div>
                    <div class="s-card">
                        <div class="s-icon"><i class="fas fa-people-group"></i></div>
                        <h3>Strengthens compassion</h3>
                        <p>Encourages a culture of care and responsibility across communities.</p>
                    </div>
                </div>
                <div style="margin-top:28px">
                    <div class="serve-cards" style="grid-template-columns:1fr">
                        <div class="s-card">
                            <div class="s-icon"><i class="fas fa-award"></i></div>
                            <h3>Honors donors</h3>
                            <p>We recognize donors and their families with gratitude and respect.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 8. Family Conversations -->
        <section class="serve">
            <div class="container two-col">
                <div>
                    <div class="sec-header sec-header--left">
                        <h2>Family Conversations</h2>
                        <div class="underline underline--left"></div>
                    </div>
                    <p class="content-p">Talking about your decision helps your family and community understand your beliefs and ensures wishes are respected.</p>
                    <ul class="key-points">
                        <li><i class="fas fa-check-circle"></i> Discuss your choice openly with family</li>
                        <li><i class="fas fa-check-circle"></i> Record faith or cultural preferences in your LifeConnect profile</li>
                        <li><i class="fas fa-check-circle"></i> Add trusted family members to your account for consent guidance</li>
                        <li><i class="fas fa-check-circle"></i> Share or print your donor card</li>
                    </ul>
                </div>
                <div>
                    <div class="card-highlight">
                        <i class="fas fa-comments"></i>
                        <p>Start the conversation today</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- 9. Educational Resources -->
        <section class="legal">
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

        <!-- 10. LifeConnect Support -->
        <section class="serve">
            <div class="container">
                <div class="sec-header">
                    <h2>LifeConnect Support</h2>
                    <div class="underline"></div>
                </div>
                <div class="serve-cards" style="grid-template-columns:repeat(auto-fit,minmax(240px,1fr))">
                    <div class="s-card">
                        <div class="s-icon"><i class="fas fa-shield-heart"></i></div>
                        <h3>Secure registration</h3>
                        <p>We protect your data and preferences with modern security practices.</p>
                    </div>
                    <div class="s-card">
                        <div class="s-icon"><i class="fas fa-scale-balanced"></i></div>
                        <h3>Legal compliance</h3>
                        <p>Aligned with Sri Lankan health authorities and national regulations.</p>
                    </div>
                    <div class="s-card">
                        <div class="s-icon"><i class="fas fa-hospital"></i></div>
                        <h3>Hospital coordination</h3>
                        <p>We work with hospitals and medical schools to honour your wishes.</p>
                    </div>
                    <div class="s-card">
                        <div class="s-icon"><i class="fas fa-hands-praying"></i></div>
                        <h3>Cultural respect</h3>
                        <p>We respect religious and cultural practices at every step.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- 11. Call to Action -->
        <section class="legal">
            <div class="container">
                <div class="cta-box">
                    <h2>Be the reason someone lives.</h2>
                    <div class="cta-actions">
                        <a href="<?= ROOT ?>/signup" class="btn-hero"><i class="fas fa-user-plus"></i> <span>Register as a Donor</span></a>
                        <a href="<?= ROOT ?>/deceased-donation" class="btn-outline"><i class="fas fa-book-open"></i> Learn More About Body Donation</a>
                        <a href="<?= ROOT ?>/tributes" class="btn-outline"><i class="fas fa-users"></i> Read Donor Stories</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- 12. Contact & Support -->
        <section class="contact">
            <div class="container">
                <div class="sec-header">
                    <h2>Need Guidance?</h2>
                    <div class="underline"></div>
                </div>
                <div class="ct-grid">
                    <div class="c-card" style="flex-direction:column;align-items:flex-start;padding:24px">
                        <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px"><div class="c-icon"><i class="fas fa-headset"></i></div><h4 style="font-size:.92rem;font-weight:700;color:var(--slate)">Need Guidance?</h4></div>
                        <p style="font-size:.88rem;color:var(--g500);line-height:1.7">Whether exploring, deciding, or already a donor, LifeConnect Sri Lanka will guide you with clarity, care, and respect.</p>
                    </div>
                    <div class="c-card" style="flex-direction:column;align-items:flex-start;padding:24px;gap:10px">
                        <p style="display:flex;align-items:center;gap:10px;font-size:.88rem;color:var(--g500)"><i class="fas fa-phone" style="color:var(--blue-600)"></i> Hotline: 011 XXXX XXX</p>
                        <p style="display:flex;align-items:center;gap:10px;font-size:.88rem;color:var(--g500)"><i class="fas fa-globe" style="color:var(--blue-600)"></i> Website: www.lifeconnect.lk/register</p>
                        <p style="display:flex;align-items:center;gap:10px;font-size:.88rem;color:var(--g500)"><i class="fas fa-envelope" style="color:var(--blue-600)"></i> Email: info@lifeconnect.lk</p>
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