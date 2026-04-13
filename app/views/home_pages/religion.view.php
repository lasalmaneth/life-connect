<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Does My Religion Support Organ and Body Donation? | LifeConnect Sri Lanka</title>
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

        .page-hero .hero-quote {
            background: var(--g50);
            padding: 18px;
            border-radius: var(--r);
            border-left: 4px solid var(--blue-600);
            margin-bottom: 0
        }

        .page-hero .hero-quote p {
            color: var(--g500);
            font-style: italic;
            font-size: 1rem;
            margin: 0
        }

        /* ── Faith symbols ── */
        .faith-symbols {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 18px;
            flex-wrap: wrap
        }

        .fsym {
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

        .fsym:hover {
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

        /* ── Religion cards (colored tops) ── */
        .r-card {
            background: var(--white);
            border: 1px solid var(--g200);
            border-radius: var(--r);
            padding: 34px 26px;
            text-align: center;
            transition: all var(--tr);
            border-top: 4px solid var(--blue-600)
        }

        .r-card:hover {
            border-color: var(--blue-300);
            border-top-color: var(--blue-600);
            box-shadow: 0 8px 28px rgba(0, 91, 170, .08);
            transform: translateY(-4px)
        }

        .r-card.buddhism {
            border-top-color: #ff9800
        }

        .r-card.hinduism {
            border-top-color: #ff5722
        }

        .r-card.islam {
            border-top-color: #4caf50
        }

        .r-card.christianity {
            border-top-color: #2196f3
        }

        .r-card.sikhism {
            border-top-color: #ffc107
        }

        .r-card.others {
            border-top-color: #9c27b0
        }

        .r-icon {
            width: 56px;
            height: 56px;
            background: var(--blue-50);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 18px;
            transition: all var(--tr)
        }

        .r-icon i {
            font-size: 1.5rem;
            transition: color var(--tr)
        }

        .r-card.buddhism .r-icon i {
            color: #ff9800
        }

        .r-card.hinduism .r-icon i {
            color: #ff5722
        }

        .r-card.islam .r-icon i {
            color: #4caf50
        }

        .r-card.christianity .r-icon i {
            color: #2196f3
        }

        .r-card.sikhism .r-icon i {
            color: #ffc107
        }

        .r-card.others .r-icon i {
            color: #9c27b0
        }

        .r-card:hover .r-icon {
            background: var(--blue-600)
        }

        .r-card:hover .r-icon i {
            color: var(--white) !important
        }

        .r-card h3 {
            font-size: 1.05rem;
            font-weight: 600;
            margin-bottom: 10px;
            color: var(--slate)
        }

        .r-card p {
            font-size: .88rem;
            color: var(--g500);
            line-height: 1.65
        }

        /* ── Commitment section (dark) ── */
        .commit-section {
            background: linear-gradient(135deg, var(--blue-600), var(--blue-800));
            padding: 80px 0;
            color: var(--white)
        }

        .commit-section .sec-header h2 {
            color: var(--white)
        }

        .commit-intro {
            text-align: center;
            font-size: 1rem;
            margin-bottom: 44px;
            opacity: .9
        }

        .commit-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            max-width: 900px;
            margin: 0 auto
        }

        .commit-item {
            display: flex;
            align-items: center;
            gap: 16px;
            background: rgba(255, 255, 255, .08);
            padding: 24px;
            border-radius: var(--r);
            border: 1px solid rgba(255, 255, 255, .12);
            transition: all var(--tr);
            backdrop-filter: blur(10px)
        }

        .commit-item:hover {
            background: rgba(255, 255, 255, .14);
            transform: translateY(-4px)
        }

        .commit-icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .15);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            flex-shrink: 0
        }

        .commit-item h4 {
            font-size: .95rem;
            font-weight: 600;
            margin-bottom: 4px
        }

        .commit-item p {
            opacity: .85;
            line-height: 1.5;
            margin: 0;
            font-size: .85rem
        }

        /* ── Family section ── */
        .family-illus {
            text-align: center
        }

        .family-illus i {
            font-size: 6rem;
            color: var(--blue-200);
            opacity: .5
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

            .commit-grid {
                grid-template-columns: 1fr
            }
        }

        @media(max-width:600px) {
            .serve-cards {
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
                    <h1>Does My Religion Support Organ and Body Donation?</h1>
                    <p>Understanding how faith, belief, and compassion unite to give life in Sri Lanka</p>
                    <div class="hero-quote">
                        <p>"Compassion has no religion, kindness is universal."</p>
                    </div>
                </div>
                <div class="hero-visual">
                    <div class="faith-symbols">
                        <div class="fsym"><i class="fas fa-dharmachakra"></i></div>
                        <div class="fsym"><i class="fas fa-om"></i></div>
                        <div class="fsym"><i class="fas fa-moon"></i></div>
                        <div class="fsym"><i class="fas fa-cross"></i></div>
                        <div class="fsym"><i class="fas fa-khanda"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Faith Explanation Section -->
    <section class="serve">
        <div class="container two-col">
            <div>
                <p class="content-p">Sri Lanka is home to many faiths, traditions, and cultures — Buddhism, Hinduism,
                    Islam, Christianity, and others — all of which teach compassion, generosity, and the value of saving
                    life.</p>
                <p class="content-p">Across these faiths, organ and body donation is seen not as losing something, but
                    as <strong>giving life</strong> — a final act of kindness that helps another human being.</p>
                <div style="margin-top:20px">
                    <div class="c-card" style="margin-bottom:10px">
                        <div class="c-icon"><i class="fas fa-check-circle"></i></div>
                        <div>
                            <p style="font-size:.88rem;color:var(--slate);font-weight:500">All major Sri Lankan
                                religions value compassion and service</p>
                        </div>
                    </div>
                    <div class="c-card" style="margin-bottom:10px">
                        <div class="c-icon"><i class="fas fa-user-circle"></i></div>
                        <div>
                            <p style="font-size:.88rem;color:var(--slate);font-weight:500">Donors can record their faith
                                preferences in LifeConnect</p>
                        </div>
                    </div>
                    <div class="c-card">
                        <div class="c-icon"><i class="fas fa-users"></i></div>
                        <div>
                            <p style="font-size:.88rem;color:var(--slate);font-weight:500">Families and religious
                                leaders can participate in the consent process</p>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <img src="<?= ROOT ?>/public/assets/images/home-religion.png" alt="Sri Lankan people and faiths"
                    style="width:100%;height:360px;object-fit:cover;border-radius:var(--r);box-shadow:0 8px 30px rgba(0,0,0,.08)" />
            </div>
        </div>
    </section>

    <!-- Religion Cards Section -->
    <section class="legal" id="faithSection">
        <div class="container">
            <div class="sec-header">
                <h2>Faith-Based Perspectives on Donation in Sri Lanka</h2>
                <div class="underline"></div>
            </div>
            <div class="serve-cards" style="grid-template-columns:repeat(auto-fit,minmax(300px,1fr))">
                <div class="r-card buddhism" onclick="location.href='<?= ROOT ?>/religion/buddhism'"
                    style="cursor:pointer">
                    <div class="r-icon"><i class="fas fa-dharmachakra"></i></div>
                    <h3>Buddhism</h3>
                    <p>Buddhism teaches compassion (karuṇā) and the importance of helping others selflessly. Many
                        Buddhist scholars view organ donation as an act of <strong>dāna (generosity)</strong> that
                        continues even after death.</p>
                    <div style="margin-top:14px; font-size:0.85rem; font-weight:700; color:#ff9800;">Read More</div>
                </div>
                <div class="r-card hinduism" onclick="location.href='<?= ROOT ?>/religion/hinduism'"
                    style="cursor:pointer">
                    <div class="r-icon"><i class="fas fa-om"></i></div>
                    <h3>Hinduism</h3>
                    <p>Hindu philosophy values service to humanity and recognises that the soul (ātman) is eternal.
                        Donating organs aligns with <strong>seva (selfless service)</strong> and <strong>dharma
                            (duty)</strong>.</p>
                    <div style="margin-top:14px; font-size:0.85rem; font-weight:700; color:#ff5722;">Read More</div>
                </div>
                <div class="r-card islam" onclick="location.href='<?= ROOT ?>/religion/islam'" style="cursor:pointer">
                    <div class="r-icon"><i class="fas fa-moon"></i></div>
                    <h3>Islam</h3>
                    <p>In Islam, saving a life is among the highest good deeds — "Whoever saves one life, it is as if
                        they have saved all of humankind" (Qur'an 5:32). Many Islamic scholars accept organ
                        transplantation when it aims to save life.</p>
                    <div style="margin-top:14px; font-size:0.85rem; font-weight:700; color:#4caf50;">Read More</div>
                </div>
                <div class="r-card christianity" onclick="location.href='<?= ROOT ?>/religion/christianity'"
                    style="cursor:pointer">
                    <div class="r-icon"><i class="fas fa-cross"></i></div>
                    <h3>Christianity</h3>
                    <p>Christian values of <strong>love, compassion, and sacrifice</strong> strongly support organ
                        donation. Most Christian denominations in Sri Lanka encourage donation as an expression of faith
                        and service to humanity.</p>
                    <div style="margin-top:14px; font-size:0.85rem; font-weight:700; color:#2196f3;">Read More</div>
                </div>
                <div class="r-card sikhism" onclick="location.href='<?= ROOT ?>/religion/sikhism'"
                    style="cursor:pointer">
                    <div class="r-icon"><i class="fas fa-khanda"></i></div>
                    <h3>Sikhism</h3>
                    <p>Sikhism highlights <strong>Nishkam Seva</strong> — serving others without selfish motive. Organ
                        donation is viewed as <strong>acts of equality and compassion</strong>, helping to sustain
                        another life.</p>
                    <div style="margin-top:14px; font-size:0.85rem; font-weight:700; color:#ffc107;">Read More</div>
                </div>
                <div class="r-card judaism" onclick="location.href='<?= ROOT ?>/religion/judaism'"
                    style="cursor:pointer">
                    <div class="r-icon"><i class="fas fa-star-of-david"></i></div>
                    <h3>Judaism</h3>
                    <p>In Judaism, saving life is a central value that overrides most concerns. Organ donation is often
                        encouraged as a <strong>mitzvah (good deed)</strong> that fulfills the principle of saving
                        lives.</p>
                    <div style="margin-top:14px; font-size:0.85rem; font-weight:700; color:#0038b8;">Read More</div>
                </div>
                <div class="r-card others" onclick="location.href='<?= ROOT ?>/religion/other'" style="cursor:pointer">
                    <div class="r-icon"><i class="fas fa-heart"></i></div>
                    <h3>Other Beliefs</h3>
                    <p>LifeConnect welcomes perspectives from all spiritual traditions — including indigenous,
                        free-thinker, and non-religious beliefs. Many choose donation as a way to <strong>leave a
                            lasting good impact</strong>.</p>
                    <div style="margin-top:14px; font-size:0.85rem; font-weight:700; color:#9c27b0;">Read More</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Commitment Section -->
    <section class="commit-section">
        <div class="container">
            <div class="sec-header">
                <h2>Our Commitment to You</h2>
                <div class="underline underline--gold"></div>
            </div>
            <p class="commit-intro">LifeConnect Sri Lanka pledges to:</p>
            <div class="commit-grid">
                <div class="commit-item">
                    <div class="commit-icon"><i class="fas fa-hands-praying"></i></div>
                    <div>
                        <h4>Honour Your Faith</h4>
                        <p>Respect your faith and cultural traditions during every stage of donation</p>
                    </div>
                </div>
                <div class="commit-item">
                    <div class="commit-icon"><i class="fas fa-user-friends"></i></div>
                    <div>
                        <h4>Include Religious Representatives</h4>
                        <p>Involve religious or spiritual representatives at your family's request</p>
                    </div>
                </div>
                <div class="commit-item">
                    <div class="commit-icon"><i class="fas fa-shield-alt"></i></div>
                    <div>
                        <h4>Ensure Dignity & Transparency</h4>
                        <p>Conduct all processes with dignity, respect, and transparency</p>
                    </div>
                </div>
                <div class="commit-item">
                    <div class="commit-icon"><i class="fas fa-handshake"></i></div>
                    <div>
                        <h4>Family-Centred Communication</h4>
                        <p>Work closely with your family and faith leaders at every step</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Talk to Family Section -->
    <section class="serve">
        <div class="container two-col">
            <div>
                <div class="sec-header sec-header--left">
                    <h2>Talk to Your Family</h2>
                    <div class="underline underline--left"></div>
                </div>
                <p class="content-p">Talking about your decision helps your family and community understand your beliefs
                    and ensures your wishes are respected.</p>
                <p class="content-p">You can record your faith preferences on your <strong>LifeConnect donor
                        profile</strong>, or attach a note for your family to refer to during consent.</p>
                <a href="<?= ROOT ?>/signup" class="btn-hero" style="margin-top:20px"><i class="fas fa-user-plus"></i>
                    <span>Register</span></a>
            </div>
            <div>
                <div class="family-illus">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Information -->
    <section class="contact">
        <div class="container">
            <div class="sec-header">
                <h2>Need Guidance or Support?</h2>
                <div class="underline"></div>
                <p>For guidance, support, or to register your decision:</p>
            </div>
            <div style="display:flex;justify-content:center;gap:20px;flex-wrap:wrap">
                <div class="c-card" style="padding:18px 24px">
                    <div class="c-icon"><i class="fas fa-globe"></i></div>
                    <div>
                        <p style="font-size:.88rem"><a href="http://www.lifeconnect.lk/register"
                                style="color:var(--blue-600);font-weight:600">www.lifeconnect.lk/register</a></p>
                    </div>
                </div>
                <div class="c-card" style="padding:18px 24px">
                    <div class="c-icon"><i class="fas fa-phone"></i></div>
                    <div>
                        <p style="font-size:.88rem;color:var(--slate);font-weight:600">Hotline: 011 XXXX XXX</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include __DIR__ . '/../templates/home_footer.view.php'; ?>
    <script>
    </script>
</body>

</html>