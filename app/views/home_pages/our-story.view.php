<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Story - LifeConnect Sri Lanka</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="<?= ROOT ?>/public/assets/css/home.css">
    <style>
        /* ── Page hero override ── */
        .page-hero {
            padding: 80px 0 60px;
            background: linear-gradient(135deg, var(--blue-100) 0%, var(--blue-50) 30%, var(--white) 65%, var(--blue-50) 100%)
        }

        .page-hero .hero-text h1 {
            font-size: 2.8rem
        }

        .page-hero .btn-hero-outline {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            border: 2px solid var(--blue-200);
            color: var(--blue-600);
            background: var(--white);
            padding: 14px 28px;
            border-radius: 50px;
            font-weight: 600;
            font-size: .95rem;
            transition: all var(--tr);
            text-decoration: none;
            backdrop-filter: blur(10px);
            cursor: pointer
        }

        .page-hero .btn-hero-outline:hover {
            background: var(--blue-50);
            border-color: var(--blue-300);
            transform: translateY(-2px)
        }

        .page-hero .hero-quote {
            background: var(--g50);
            padding: 18px;
            border-radius: var(--r);
            border-left: 4px solid var(--blue-600);
            margin-bottom: 24px
        }

        .page-hero .hero-quote p {
            color: var(--g500);
            font-style: italic;
            font-size: 1rem;
            margin: 0
        }

        .hero-btns {
            display: flex;
            gap: 14px;
            flex-wrap: wrap
        }

        /* ── Story symbols ── */
        .story-symbols {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 18px;
            flex-wrap: wrap
        }

        .ssym {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: var(--blue-600);
            background: var(--white);
            border: 2px solid var(--blue-100);
            transition: all var(--tr);
            box-shadow: 0 4px 14px rgba(0, 91, 170, .08)
        }

        .ssym:hover {
            transform: scale(1.12);
            background: var(--blue-50);
            border-color: var(--blue-300)
        }

        /* ── Two-column layout ── */
        .two-col {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
            align-items: center
        }

        /* ── Content paragraphs ── */
        .content-p {
            font-size: .92rem;
            color: var(--g500);
            line-height: 1.7;
            margin-bottom: 14px
        }

        /* ── Mission/Vision cards ── */
        .mv-card {
            background: var(--white);
            padding: 24px;
            border-radius: var(--r);
            box-shadow: 0 4px 18px rgba(0, 0, 0, .05);
            border: 1px solid var(--g200);
            margin-bottom: 20px;
            display: flex;
            align-items: flex-start;
            gap: 16px;
            transition: all var(--tr)
        }

        .mv-card:hover {
            border-color: var(--blue-300);
            transform: translateY(-4px);
            box-shadow: 0 8px 28px rgba(0, 91, 170, .08)
        }

        .mv-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--blue-600), var(--blue-800));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: var(--white);
            flex-shrink: 0
        }

        .mv-card h3 {
            font-size: 1rem;
            font-weight: 700;
            color: var(--slate);
            margin-bottom: 8px
        }

        .mv-card p {
            font-size: .88rem;
            color: var(--g500);
            line-height: 1.65;
            margin: 0
        }

        /* ── Timeline (clean vertical) ── */
        .timeline {
            max-width: 720px;
            margin: 0 auto;
            position: relative;
            padding-left: 44px
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: var(--g200);
            border-radius: 1px
        }

        .tl-item {
            position: relative;
            margin-bottom: 36px;
            padding-left: 32px
        }

        .tl-item:last-child {
            margin-bottom: 0
        }

        /* Dot on the line */
        .tl-dot {
            position: absolute;
            left: -36px;
            top: 24px;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background: var(--white);
            border: 3px solid var(--blue-600);
            z-index: 2;
            transition: all var(--tr)
        }

        .tl-item:hover .tl-dot {
            background: var(--blue-600);
            box-shadow: 0 0 0 5px rgba(0, 91, 170, .12)
        }

        /* Latest item pulse */
        .tl-item:last-child .tl-dot {
            background: var(--blue-600)
        }

        .tl-item:last-child .tl-dot::after {
            content: '';
            position: absolute;
            inset: -5px;
            border-radius: 50%;
            border: 2px solid var(--blue-600);
            animation: dotPulse 2s ease-in-out infinite
        }

        @keyframes dotPulse {

            0%,
            100% {
                opacity: .4;
                transform: scale(1)
            }

            50% {
                opacity: 0;
                transform: scale(1.6)
            }
        }

        /* Year pill */
        .tl-year {
            display: inline-block;
            background: var(--blue-600);
            color: var(--white);
            font-size: .75rem;
            font-weight: 700;
            padding: 4px 14px;
            border-radius: 50px;
            letter-spacing: .5px;
            margin-bottom: 10px
        }

        /* Card */
        .tl-card {
            background: var(--white);
            border: 1px solid var(--g200);
            border-radius: var(--r);
            padding: 22px 24px;
            transition: all var(--tr)
        }

        .tl-card:hover {
            border-color: var(--blue-300);
            box-shadow: 0 8px 24px rgba(0, 91, 170, .07);
            transform: translateY(-3px)
        }

        .tl-head {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 10px
        }

        .tl-ico {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: var(--blue-50);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: all var(--tr)
        }

        .tl-ico i {
            font-size: 1rem;
            color: var(--blue-600);
            transition: color var(--tr)
        }

        .tl-card:hover .tl-ico {
            background: var(--blue-600)
        }

        .tl-card:hover .tl-ico i {
            color: var(--white)
        }

        .tl-card h3 {
            font-size: 1rem;
            font-weight: 700;
            color: var(--slate);
            margin: 0
        }

        .tl-card p {
            color: var(--g500);
            line-height: 1.65;
            margin: 0;
            font-size: .88rem
        }

        /* ── Team member cards ── */
        .team-card {
            background: var(--white);
            border: 1px solid var(--g200);
            border-radius: var(--r);
            padding: 28px 20px;
            text-align: center;
            transition: all var(--tr)
        }

        .team-card:hover {
            border-color: var(--blue-300);
            box-shadow: 0 8px 28px rgba(0, 91, 170, .08);
            transform: translateY(-4px)
        }

        .team-photo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--blue-600), var(--blue-800));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: var(--white);
            margin: 0 auto 16px
        }

        .team-card h3 {
            font-size: 1rem;
            font-weight: 700;
            color: var(--slate);
            margin-bottom: 4px
        }

        .team-role {
            color: var(--blue-600);
            font-size: .85rem;
            font-weight: 600;
            margin-bottom: 12px
        }

        .team-bio {
            color: var(--g500);
            line-height: 1.6;
            margin-bottom: 14px;
            font-size: .85rem
        }

        .team-social {
            display: flex;
            justify-content: center;
            gap: 12px
        }

        .team-social i {
            color: var(--blue-600);
            font-size: 1rem;
            cursor: pointer;
            transition: color var(--tr)
        }

        .team-social i:hover {
            color: var(--blue-800)
        }

        /* ── Stat cards ── */
        .stat-card {
            background: linear-gradient(135deg, var(--blue-600), var(--blue-800));
            color: var(--white);
            padding: 30px 18px;
            border-radius: var(--r);
            text-align: center;
            transition: all var(--tr);
            position: relative;
            overflow: hidden
        }

        .stat-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(45deg, rgba(255, 255, 255, .08), transparent);
            opacity: 0;
            transition: opacity var(--tr)
        }

        .stat-card:hover::before {
            opacity: 1
        }

        .stat-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 36px rgba(0, 91, 170, .25)
        }

        .stat-card .s-ico {
            font-size: 2rem;
            margin-bottom: 10px;
            opacity: .85
        }

        .stat-card .s-num {
            font-size: 2.4rem;
            font-weight: 800;
            margin-bottom: 6px;
            position: relative;
            z-index: 2
        }

        .stat-card .s-lbl {
            font-size: .92rem;
            font-weight: 600;
            margin-bottom: 4px;
            position: relative;
            z-index: 2
        }

        .stat-card .s-desc {
            font-size: .78rem;
            opacity: .75;
            position: relative;
            z-index: 2
        }

        /* ── Testimonial cards ── */
        .test-card {
            background: var(--g50);
            border: 1px solid var(--g200);
            padding: 24px;
            border-radius: var(--r);
            transition: all var(--tr)
        }

        .test-card:hover {
            border-color: var(--blue-300);
            transform: translateY(-4px);
            box-shadow: 0 8px 28px rgba(0, 91, 170, .08)
        }

        .test-quote {
            color: var(--blue-600);
            font-size: 1.6rem;
            margin-bottom: 14px;
            opacity: .6
        }

        .test-card>p {
            color: var(--g500);
            line-height: 1.7;
            font-style: italic;
            font-size: .9rem;
            margin-bottom: 16px
        }

        .test-author strong {
            color: var(--slate);
            font-size: .88rem;
            display: block;
            margin-bottom: 2px
        }

        .test-author span {
            color: var(--blue-600);
            font-size: .8rem
        }

        /* ── CTA section ── */
        .cta-dark {
            background: linear-gradient(135deg, var(--blue-600), var(--blue-800));
            padding: 80px 0;
            color: var(--white)
        }

        .cta-dark .sec-header h2 {
            color: var(--white)
        }

        .cta-dark p {
            opacity: .9;
            line-height: 1.6;
            font-size: 1rem;
            margin-bottom: 24px
        }

        .cta-dark .btn-hero {
            background: #10b981
        }

        .cta-dark .btn-hero:hover {
            background: #059669
        }

        .cta-dark .btn-outline {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            border: 2px solid rgba(255, 255, 255, .3);
            color: var(--white);
            background: rgba(255, 255, 255, .08);
            padding: 14px 28px;
            border-radius: 50px;
            font-weight: 600;
            font-size: .95rem;
            transition: all var(--tr);
            text-decoration: none;
            backdrop-filter: blur(10px)
        }

        .cta-dark .btn-outline:hover {
            background: rgba(255, 255, 255, .18);
            transform: translateY(-2px)
        }

        .cta-btns {
            display: flex;
            gap: 14px;
            flex-wrap: wrap
        }

        .cta-illus {
            text-align: center
        }

        .cta-illus i {
            font-size: 7rem;
            color: rgba(255, 255, 255, .2)
        }

        /* ── Contact section ── */
        .ct-flex {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap
        }

        @media(max-width:768px) {
            .two-col {
                grid-template-columns: 1fr
            }

            .page-hero .hero-grid {
                text-align: center;
                display: flex;
                flex-direction: column-reverse;
                gap: 30px
            }

            .page-hero .hero-text>p {
                margin: 0 auto 20px
            }

            .hero-btns {
                justify-content: center
            }

            .timeline {
                padding-left: 30px
            }

            .timeline::before {
                left: 8px
            }

            .tl-dot {
                left: -29px
            }

            .cta-dark .two-col {
                text-align: center
            }

            .cta-btns {
                justify-content: center
            }
        }

        @media(max-width:600px) {
            .serve-cards {
                grid-template-columns: 1fr !important
            }

            .stat-cards {
                grid-template-columns: repeat(2, 1fr) !important
            }
        }

        @media(max-width:480px) {
            .stat-cards {
                grid-template-columns: 1fr !important
            }
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
                    <h1>Our Journey Towards Saving Lives</h1>
                    <p>LifeConnect Sri Lanka is dedicated to making organ and body donation transparent, easy, and
                        life-changing for every Sri Lankan family.</p>
                    <div class="hero-quote">
                        <p>"Every life saved is a story of hope, courage, and the power of human connection."</p>
                    </div>
                    <div class="hero-btns">
                        <a href="<?= ROOT ?>/signup" class="btn-hero"><i class="fas fa-heart"></i> <span>Join Us
                                Today</span></a>
                        <button class="btn-hero-outline" onclick="scrollToSection('mission')"><i
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
    <section class="serve" id="mission" style="padding-bottom: 0;">
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
    <section class="legal">
        <div class="container">
            <div class="sec-header">
                <h2>Our Journey</h2>
                <div class="underline"></div>
                <p>From a simple idea to a life-saving platform - here's how LifeConnect Sri Lanka came to be.</p>
            </div>
            <div class="timeline">
                <div class="tl-item">
                    <div class="tl-dot"></div>
                    <span class="tl-year">2023</span>
                    <div class="tl-card">
                        <div class="tl-head">
                            <div class="tl-ico"><i class="fas fa-lightbulb"></i></div>
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
                        <div class="tl-head">
                            <div class="tl-ico"><i class="fas fa-search"></i></div>
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
                        <div class="tl-head">
                            <div class="tl-ico"><i class="fas fa-rocket"></i></div>
                            <h3>Platform Launch</h3>
                        </div>
                        <p>LifeConnect Sri Lanka officially launched with our first registered donors and hospital
                            partnerships. Successfully facilitated our first organ donation coordination.</p>
                    </div>
                </div>
                <div class="tl-item">
                    <div class="tl-dot"></div>
                    <span class="tl-year">2026</span>
                    <div class="tl-card">
                        <div class="tl-head">
                            <div class="tl-ico"><i class="fas fa-expand-arrows-alt"></i></div>
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
    <section class="serve">
        <div class="container">
            <div class="sec-header">
                <h2>Meet Our Team</h2>
                <div class="underline"></div>
                <p>The passionate individuals behind LifeConnect Sri Lanka, dedicated to saving lives and supporting
                    families.</p>
            </div>
            <div class="serve-cards" style="grid-template-columns:repeat(auto-fit,minmax(240px,1fr))">
                <div class="team-card">
                    <div class="team-photo"><i class="fas fa-user-md"></i></div>
                    <h3>Dr. Priya Fernando</h3>
                    <p class="team-role">Founder & Medical Director</p>
                    <p class="team-bio">Leading transplant surgeon with 15+ years experience. Passionate about making
                        organ donation accessible to all Sri Lankan families.</p>
                    <div class="team-social"><i class="fab fa-linkedin"></i><i class="fas fa-envelope"></i></div>
                </div>
                <div class="team-card">
                    <div class="team-photo"><i class="fas fa-code"></i></div>
                    <h3>Nuwan Perera</h3>
                    <p class="team-role">Tech Lead & Co-Founder</p>
                    <p class="team-bio">Software engineer specializing in healthcare technology. Committed to building
                        secure, user-friendly platforms that save lives.</p>
                    <div class="team-social"><i class="fab fa-linkedin"></i><i class="fas fa-envelope"></i></div>
                </div>
                <div class="team-card">
                    <div class="team-photo"><i class="fas fa-gavel"></i></div>
                    <h3>Adv. Ravi Silva</h3>
                    <p class="team-role">Legal Advisor</p>
                    <p class="team-bio">Legal expert ensuring LifeConnect complies with all Sri Lankan laws and
                        regulations. Advocate for donor and recipient rights.</p>
                    <div class="team-social"><i class="fab fa-linkedin"></i><i class="fas fa-envelope"></i></div>
                </div>
                <div class="team-card">
                    <div class="team-photo"><i class="fas fa-heart"></i></div>
                    <h3>Amali Perera</h3>
                    <p class="team-role">Family Support Coordinator</p>
                    <p class="team-bio">Dedicated to supporting families through the donation process. Ensures every
                        family feels supported and informed.</p>
                    <div class="team-social"><i class="fab fa-linkedin"></i><i class="fas fa-envelope"></i></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Impact & Stats Section -->
    <section class="legal">
        <div class="container">
            <div class="sec-header">
                <h2>Our Impact</h2>
                <div class="underline"></div>
                <p>Real numbers, real lives saved - see how LifeConnect Sri Lanka is making a difference.</p>
            </div>
            <div class="serve-cards stat-cards" style="grid-template-columns:repeat(auto-fit,minmax(200px,1fr))">
                <div class="stat-card">
                    <div class="s-ico"><i class="fas fa-heart"></i></div>
                    <div class="s-num" data-target="<?= $stats->lives_saved ?? 127 ?>">0</div>
                    <div class="s-lbl">Lives Saved</div>
                    <div class="s-desc">Successful organ transplants facilitated</div>
                </div>
                <div class="stat-card">
                    <div class="s-ico"><i class="fas fa-user-plus"></i></div>
                    <div class="s-num" data-target="<?= $stats->donor_count ?? 2847 ?>">0</div>
                    <div class="s-lbl">Registered Donors</div>
                    <div class="s-desc">People who have registered to save lives</div>
                </div>
                <div class="stat-card">
                    <div class="s-ico"><i class="fas fa-hospital"></i></div>
                    <div class="s-num" data-target="<?= $stats->hospital_count ?? 23 ?>">0</span></div>
                    <div class="s-lbl">Partner Hospitals</div>
                    <div class="s-desc">Medical institutions using our platform</div>
                </div>
                <div class="stat-card">
                    <div class="s-ico"><i class="fas fa-graduation-cap"></i></div>
                    <div class="s-num" data-target="<?= $stats->medical_school_count ?? 8 ?>">0</div>
                    <div class="s-lbl">Medical Schools</div>
                    <div class="s-desc">Educational institutions for body donation</div>
                </div>
            </div>

            <!-- Testimonials -->
            <div style="margin-top:60px">
                <div class="sec-header">
                    <h2>What Families Say</h2>
                    <div class="underline"></div>
                </div>
                <div class="serve-cards" style="grid-template-columns:repeat(auto-fit,minmax(300px,1fr))">
                    <div class="test-card">
                        <div class="test-quote"><i class="fas fa-quote-left"></i></div>
                        <p>"LifeConnect made the impossible possible. When my father passed, his organs saved three
                            lives. The platform guided us through every step with compassion and clarity."</p>
                        <div class="test-author">
                            <strong>Mrs. Kamala Wickramasinghe</strong>
                            <span>Daughter of organ donor</span>
                        </div>
                    </div>
                    <div class="test-card">
                        <div class="test-quote"><i class="fas fa-quote-left"></i></div>
                        <p>"As a transplant surgeon, I've seen how LifeConnect streamlines the donation process. It's a
                            game-changer for Sri Lankan healthcare."</p>
                        <div class="test-author">
                            <strong>Dr. Suresh Mendis</strong>
                            <span>Transplant Surgeon, National Hospital</span>
                        </div>
                    </div>
                    <div class="test-card">
                        <div class="test-quote"><i class="fas fa-quote-left"></i></div>
                        <p>"The transparency and support provided by LifeConnect gave our family peace of mind during a
                            difficult time. We knew our loved one's wishes were being honored."</p>
                        <div class="test-author">
                            <strong>Mr. Rajesh Kumar</strong>
                            <span>Family member</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose LifeConnect Section -->
    <section class="serve">
        <div class="container">
            <div class="sec-header">
                <h2>Why Choose LifeConnect?</h2>
                <div class="underline"></div>
                <p>We're not just another platform - we're your trusted partner in the journey of giving life.</p>
            </div>
            <div class="serve-cards" style="grid-template-columns:repeat(auto-fit,minmax(300px,1fr))">
                <div class="s-card">
                    <div class="s-icon"><i class="fas fa-shield-alt"></i></div>
                    <h3>Legal Compliance</h3>
                    <p>Fully compliant with Sri Lankan laws and regulations. All processes are legally sound and
                        transparent.</p>
                </div>
                <div class="s-card">
                    <div class="s-icon"><i class="fas fa-mobile-alt"></i></div>
                    <h3>Easy Online Registration</h3>
                    <p>Simple, secure online registration process. Update your preferences anytime, anywhere.</p>
                </div>
                <div class="s-card">
                    <div class="s-icon"><i class="fas fa-eye"></i></div>
                    <h3>Complete Transparency</h3>
                    <p>Track every step of the process. Know exactly what happens with your donation and how it helps
                        others.</p>
                </div>
                <div class="s-card">
                    <div class="s-icon"><i class="fas fa-users"></i></div>
                    <h3>Family-Centered</h3>
                    <p>Involve your family in the process. Designate custodians and ensure your wishes are respected.
                    </p>
                </div>
                <div class="s-card">
                    <div class="s-icon"><i class="fas fa-headset"></i></div>
                    <h3>24/7 Support</h3>
                    <p>Round-the-clock support for families and medical professionals. We're here when you need us most.
                    </p>
                </div>
                <div class="s-card">
                    <div class="s-icon"><i class="fas fa-certificate"></i></div>
                    <h3>Digital Documentation</h3>
                    <p>Secure digital donor cards and certificates. Easy access to all your donation information.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Partners Section -->
    <section class="legal">
        <div class="container">
            <div class="sec-header">
                <h2>Our Trusted Partners</h2>
                <div class="underline"></div>
                <p>Working together with leading medical institutions across Sri Lanka.</p>
            </div>
            <div class="serve-cards" style="grid-template-columns:repeat(auto-fit,minmax(200px,1fr))">
                <div class="s-card">
                    <div class="s-icon"><i class="fas fa-hospital"></i></div>
                    <h3>National Hospital of Sri Lanka</h3>
                    <p>Premier transplant center</p>
                </div>
                <div class="s-card">
                    <div class="s-icon"><i class="fas fa-university"></i></div>
                    <h3>University of Colombo</h3>
                    <p>Medical education partner</p>
                </div>
                <div class="s-card">
                    <div class="s-icon"><i class="fas fa-hospital"></i></div>
                    <h3>Colombo General Hospital</h3>
                    <p>Cardiac transplant center</p>
                </div>
                <div class="s-card">
                    <div class="s-icon"><i class="fas fa-graduation-cap"></i></div>
                    <h3>University of Peradeniya</h3>
                    <p>Medical school partner</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action Section -->
    <section class="cta-dark">
        <div class="container two-col">
            <div>
                <div class="sec-header sec-header--left">
                    <h2>Become a Life-Saver Today</h2>
                    <div class="underline underline--left underline--gold"></div>
                </div>
                <p>Join thousands of Sri Lankans who have chosen to give the gift of life. Your decision today could
                    save multiple lives tomorrow.</p>
                <div class="cta-btns">
                    <a href="<?= ROOT ?>/signup" class="btn-hero"><i class="fas fa-heart"></i> <span>Register as
                            Donor</span></a>
                    <a href="<?= ROOT ?>/signup" class="btn-outline"><i class="fas fa-donate"></i> Donate
                        Financially</a>
                </div>
            </div>
            <div class="cta-illus">
                <i class="fas fa-hands-helping"></i>
            </div>
        </div>
    </section>

    <!-- Contact Information -->
    <section class="contact">
        <div class="container">
            <div class="sec-header">
                <h2>Get in Touch</h2>
                <div class="underline"></div>
                <p>Have questions about our story or want to learn more about LifeConnect Sri Lanka?</p>
            </div>
            <div class="ct-flex">
                <div class="c-card" style="padding:18px 24px">
                    <div class="c-icon"><i class="fas fa-globe"></i></div>
                    <div>
                        <p style="font-size:.88rem"><a href="http://www.lifeconnect.lk"
                                style="color:var(--blue-600);font-weight:600">www.lifeconnect.lk</a></p>
                    </div>
                </div>
                <div class="c-card" style="padding:18px 24px">
                    <div class="c-icon"><i class="fas fa-phone"></i></div>
                    <div>
                        <p style="font-size:.88rem;color:var(--slate);font-weight:600">Hotline: 011 XXXX XXX</p>
                    </div>
                </div>
                <div class="c-card" style="padding:18px 24px">
                    <div class="c-icon"><i class="fas fa-envelope"></i></div>
                    <div>
                        <p style="font-size:.88rem;color:var(--slate);font-weight:600">info@lifeconnect.lk</p>
                    </div>
                </div>
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