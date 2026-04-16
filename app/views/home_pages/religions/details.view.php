<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $faith['title'] ?> and Organ Donation | LifeConnect Sri Lanka</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= ROOT ?>/public/assets/css/home.css">
    <style>
        :root {
            --faith-primary: <?= $faith['color'] ?>;
            --faith-bg: <?= $faith['color'] ?>10;
        }
        
        .faith-hero {
            padding: 100px 0 60px;
            background: linear-gradient(135deg, var(--faith-bg) 0%, var(--white) 100%);
            border-bottom: 1px solid var(--g100);
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--blue-600);
            text-decoration: none;
            font-weight: 600;
            margin-bottom: 24px;
            font-size: 0.9rem;
            transition: transform var(--tr);
        }
        .back-link:hover { transform: translateX(-5px); }

        .faith-title-group {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 16px;
        }
        .faith-title-icon {
            width: 64px;
            height: 64px;
            background: var(--white);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: var(--faith-primary);
            box-shadow: 0 8px 20px rgba(0,0,0,0.06);
        }
        .faith-hero h1 {
            font-size: 3rem;
            color: var(--slate);
            margin: 0;
            letter-spacing: -0.02em;
        }

        .faith-quote-box {
            margin-top: 30px;
            padding: 24px 30px;
            background: var(--white);
            border-radius: var(--r);
            border-left: 5px solid var(--faith-primary);
            box-shadow: 0 10px 30px rgba(0,0,0,0.04);
            max-width: 800px;
        }
        .faith-quote-box p {
            font-size: 1.25rem;
            font-style: italic;
            color: var(--slate);
            line-height: 1.6;
            margin: 0;
            font-weight: 500;
        }

        .summary-ribbon {
            display: flex;
            gap: 12px;
            margin-top: 30px;
            flex-wrap: wrap;
        }
        .summary-pill {
            padding: 8px 18px;
            background: var(--white);
            border: 1px solid var(--g200);
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--g600);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .summary-pill i { color: var(--faith-primary); }

        .faith-main { padding: 80px 0; }
        .faith-grid {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 60px;
        }

        .faith-section { margin-bottom: 60px; }
        .sec-h3 {
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--slate);
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .sec-h3 i { color: var(--faith-primary); font-size: 1.4rem; }
        .content-p {
            font-size: 1rem;
            color: var(--g500);
            line-height: 1.8;
            margin-bottom: 20px;
        }

        .feature-box {
            background: var(--g50);
            padding: 30px;
            border-radius: var(--r);
            border: 1px solid var(--g100);
        }
        .feature-list { list-style: none; padding: 0; }
        .feature-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 16px;
            font-size: 0.95rem;
            color: var(--slate);
            font-weight: 500;
        }
        .feature-item i { color: var(--faith-primary); margin-top: 4px; }

        .faq-item {
            background: var(--white);
            border: 1px solid var(--g200);
            border-radius: 12px;
            margin-bottom: 12px;
            overflow: hidden;
        }
        .faq-q {
            padding: 18px 24px;
            font-weight: 700;
            color: var(--slate);
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            transition: background var(--tr);
        }
        .faq-q:hover { background: var(--g50); }
        .faq-a {
            padding: 10px 24px 24px;
            color: var(--g500);
            font-size: 0.95rem;
            line-height: 1.6;
            display: none;
        }
        .faq-item.active .faq-a { display: block; }
        .faq-item.active .faq-q { color: var(--faith-primary); border-bottom: 1px solid var(--g100); }

        .cta-dark {
            background: var(--slate);
            color: var(--white);
            padding: 50px;
            border-radius: var(--r);
            text-align: center;
            margin-top: 40px;
        }
        .cta-dark h2 { color: var(--white); margin-bottom: 20px; }
        .btn-faith {
            background: var(--faith-primary);
            color: var(--white);
            padding: 14px 32px;
            border-radius: 50px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            transition: all var(--tr);
        }
        .btn-faith:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.15);
            filter: brightness(1.1);
        }

        @media(max-width: 900px) {
            .faith-grid { grid-template-columns: 1fr; gap: 40px; }
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../../templates/home_header.view.php'; ?>

    <section class="faith-hero">
        <div class="container">
            <div class="faith-title-group">
                <div class="faith-title-icon"><i class="<?= $faith['icon'] ?>"></i></div>
                <h1><?= $faith['title'] ?></h1>
            </div>

            <div class="faith-quote-box">
                <p>"<?= $faith['quote'] ?>"</p>
            </div>

            <div class="summary-ribbon">
                <?php foreach($faith['summary'] as $item): ?>
                    <div class="summary-pill"><i class="fas fa-check-circle"></i> <?= $item ?></div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <main class="faith-main container">
        <div class="faith-grid">
            <div class="faith-col-left">
                <div class="faith-section">
                    <h3 class="sec-h3"><i class="fas fa-info-circle"></i> Introduction</h3>
                    <p class="content-p"><?= $faith['intro'] ?></p>
                </div>

                <?php foreach($faith['sections'] as $sec): ?>
                <div class="faith-section">
                    <h3 class="sec-h3"><i class="<?= $sec['icon'] ?>"></i> <?= $sec['title'] ?></h3>
                    <p class="content-p"><?= $sec['content'] ?></p>
                </div>
                <?php endforeach; ?>

                <?php if(!empty($faith['faqs'])): ?>
                <div class="faith-section" id="faq">
                    <h3 class="sec-h3"><i class="fas fa-question-circle"></i> Common Questions</h3>
                    <div class="faq-list">
                        <?php foreach($faith['faqs'] as $index => $faq): ?>
                        <div class="faq-item" onclick="this.classList.toggle('active')">
                            <div class="faq-q">
                                <?= $faq['q'] ?>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="faq-a"><?= $faq['a'] ?></div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <div class="faith-col-right">
                <?php if(!empty($faith['considerations'])): ?>
                <div class="faith-section">
                    <h3 class="sec-h3"><i class="fas fa-clipboard-list"></i> Key Considerations</h3>
                    <div class="feature-box">
                        <ul class="feature-list">
                            <?php foreach($faith['considerations'] as $item): ?>
                                <li class="feature-item"><i class="fas fa-check"></i> <?= $item ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                <?php endif; ?>

                <?php if(!empty($faith['misunderstandings'])): ?>
                <div class="faith-section">
                    <h3 class="sec-h3"><i class="fas fa-lightbulb"></i> Common Myths</h3>
                    <div class="feature-box" style="background: #fff8f0; border-color: #ffe0b2;">
                        <ul class="feature-list">
                            <?php foreach($faith['misunderstandings'] as $item): ?>
                                <li class="feature-item"><i class="fas fa-info-circle" style="color: #f57c00;"></i> <?= $item ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                <?php endif; ?>

                <?php if(!empty($faith['family']) || !empty($faith['care'])): ?>
                <div class="faith-section">
                    <h3 class="sec-h3"><i class="fas fa-users"></i> Family & Care</h3>
                    <div class="feature-box" style="background: var(--blue-50); border-color: var(--blue-100);">
                        <?php if(!empty($faith['family'])): ?>
                            <p class="content-p" style="font-size: 0.9rem; margin-bottom: 12px;"><strong>Family Role:</strong> <?= $faith['family'] ?></p>
                        <?php endif; ?>
                        <?php if(!empty($faith['care'])): ?>
                            <p class="content-p" style="font-size: 0.9rem; margin: 0;"><strong>Care & Respect:</strong> <?= $faith['care'] ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <div class="cta-dark">
                    <h2>Make your decision count.</h2>
                    <a href="<?= ROOT ?>/signup" class="btn-faith">
                        <i class="fas fa-user-plus"></i> Register Today
                    </a>
                </div>
            </div>
        </div>
    </main>

    <?php include __DIR__ . '/../../templates/home_footer.view.php'; ?>

    <script>
        // Simple accordion logic handled by inline onclick, but adding script for better control if needed
    </script>
</body>
</html>
