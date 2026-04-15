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
                <div class="mt-20">
                    <div class="c-card mb-10">
                        <div class="c-icon"><i class="fas fa-check-circle"></i></div>
                        <div>
                            <p>All major Sri Lankan religions value compassion and service</p>
                        </div>
                    </div>
                    <div class="c-card mb-10">
                        <div class="c-icon"><i class="fas fa-user-circle"></i></div>
                        <div>
                            <p>Donors can record their faith preferences in LifeConnect</p>
                        </div>
                    </div>
                    <div class="c-card">
                        <div class="c-icon"><i class="fas fa-users"></i></div>
                        <div>
                            <p>Families and religious leaders can participate in the consent process</p>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <img src="<?= ROOT ?>/public/assets/images/home-religion.png" alt="Sri Lankan people and faiths" class="img-standard" />
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
            <div class="serve-cards">
                <div class="r-card buddhism" onclick="location.href='<?= ROOT ?>/religion/buddhism'"
                    style="cursor:pointer">
                    <div class="r-icon"><i class="fas fa-dharmachakra"></i></div>
                    <h3>Buddhism</h3>
                    <p>Buddhism teaches compassion (karuṇā) and the importance of helping others selflessly. Many
                        Buddhist scholars view organ donation as an act of <strong>dāna (generosity)</strong> that
                        continues even after death.</p>
                    <div class="read-more">Read More</div>
                </div>
                <div class="r-card hinduism" onclick="location.href='<?= ROOT ?>/religion/hinduism'"
                    style="cursor:pointer">
                    <div class="r-icon"><i class="fas fa-om"></i></div>
                    <h3>Hinduism</h3>
                    <p>Hindu philosophy values service to humanity and recognises that the soul (ātman) is eternal.
                        Donating organs aligns with <strong>seva (selfless service)</strong> and <strong>dharma
                            (duty)</strong>.</p>
                    <div class="read-more">Read More</div>
                </div>
                <div class="r-card islam" onclick="location.href='<?= ROOT ?>/religion/islam'" style="cursor:pointer">
                    <div class="r-icon"><i class="fas fa-moon"></i></div>
                    <h3>Islam</h3>
                    <p>In Islam, saving a life is among the highest good deeds — "Whoever saves one life, it is as if
                        they have saved all of humankind" (Qur'an 5:32). Many Islamic scholars accept organ
                        transplantation when it aims to save life.</p>
                    <div class="read-more">Read More</div>
                </div>
                <div class="r-card christianity" onclick="location.href='<?= ROOT ?>/religion/christianity'"
                    style="cursor:pointer">
                    <div class="r-icon"><i class="fas fa-cross"></i></div>
                    <h3>Christianity</h3>
                    <p>Christian values of <strong>love, compassion, and sacrifice</strong> strongly support organ
                        donation. Most Christian denominations in Sri Lanka encourage donation as an expression of faith
                        and service to humanity.</p>
                    <div class="read-more">Read More</div>
                </div>
                <div class="r-card sikhism" onclick="location.href='<?= ROOT ?>/religion/sikhism'"
                    style="cursor:pointer">
                    <div class="r-icon"><i class="fas fa-khanda"></i></div>
                    <h3>Sikhism</h3>
                    <p>Sikhism highlights <strong>Nishkam Seva</strong> — serving others without selfish motive. Organ
                        donation is viewed as <strong>acts of equality and compassion</strong>, helping to sustain
                        another life.</p>
                    <div class="read-more">Read More</div>
                </div>
                <div class="r-card judaism" onclick="location.href='<?= ROOT ?>/religion/judaism'"
                    style="cursor:pointer">
                    <div class="r-icon"><i class="fas fa-star-of-david"></i></div>
                    <h3>Judaism</h3>
                    <p>In Judaism, saving life is a central value that overrides most concerns. Organ donation is often
                        encouraged as a <strong>mitzvah (good deed)</strong> that fulfills the principle of saving
                        lives.</p>
                    <div class="read-more">Read More</div>
                </div>
                <div class="r-card others" onclick="location.href='<?= ROOT ?>/religion/other'" style="cursor:pointer">
                    <div class="r-icon"><i class="fas fa-heart"></i></div>
                    <h3>Other Beliefs</h3>
                    <p>LifeConnect welcomes perspectives from all spiritual traditions — including indigenous,
                        free-thinker, and non-religious beliefs. Many choose donation as a way to <strong>leave a
                            lasting good impact</strong>.</p>
                    <div class="read-more">Read More</div>
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
                <a href="<?= ROOT ?>/signup" class="btn-hero mt-20"><i class="fas fa-user-plus"></i>
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
            <div class="faith-symbols">
                <div class="c-card">
                    <div class="c-icon"><i class="fas fa-globe"></i></div>
                    <div>
                        <p><a href="http://www.lifeconnect.lk/register"
                                style="color:var(--blue-600);font-weight:600">www.lifeconnect.lk/register</a></p>
                    </div>
                </div>
                <div class="c-card">
                    <div class="c-icon"><i class="fas fa-phone"></i></div>
                    <div>
                        <p>Hotline: 011 XXXX XXX</p>
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