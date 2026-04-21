<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Custodian Responsibilities | Life-Connect Sri Lanka</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= ROOT ?>/public/assets/css/home.css">
</head>
<body>
    <?php include __DIR__ . '/../templates/home_header.view.php'; ?>

    <section class="page-hero">
        <div class="container">
            <h1>Custodian Responsibilities After Death</h1>
            <p>Guidance for custodians involved in organ donation or whole-body donation.</p>
        </div>
    </section>

    <main class="container section-padding">
        <div style="max-width:1000px">
            <p class="content-p">After death, the custodian plays a key role in making sure the donor’s wishes are handled lawfully and properly. This includes confirming documents, communicating with the relevant hospital or medical school, arranging handover steps, and understanding that donation can still depend on medical suitability and institutional acceptance.</p>
        </div>

        <div class="section-padding">
            <h3 class="sec-h3"><i class="fas fa-user-shield"></i> Role of the Custodian</h3>
            <div class="role-box">
                <p class="content-p" style="margin:0">A custodian acts as the person who helps carry the donor’s post-death process forward. The custodian may need to confirm the donor’s consent status, provide required legal and identity documents, coordinate with the institution receiving the body or organs, and respond quickly because many parts of the process are time-sensitive.</p>
            </div>
        </div>

        <div class="section-padding" style="border-top:1px solid var(--g100)">
            <h3 class="sec-h3"><i class="fas fa-hospital-user"></i> Organ Donation Pathway</h3>
            <p class="content-p">For organ donation, the process depends on lawful authorization, confirmation of death under proper medical standards, and medical suitability assessment by the appropriate transplant team. The custodian or next of kin may be involved in confirming or authorizing donation depending on the donor’s prior consent status and the legal circumstances. The treating team and transplant team should have separate responsibilities to preserve trust and avoid conflicts of interest.</p>
        </div>

        <div class="section-padding" style="border-top:1px solid var(--g100)">
            <h3 class="sec-h3"><i class="fas fa-university"></i> Whole-Body Donation Pathway</h3>
            <p class="content-p">For whole-body donation, the custodian usually has a direct operational role. This may include contacting the medical school, preparing required documents, arranging body transport, following handover timing requirements, and understanding that the body can be accepted or refused based on institutional criteria such as body condition or missing documentation. Once accepted, the handover is generally treated as final.</p>
        </div>

        <div class="section-padding" style="border-top:1px solid var(--g100)">
            <h3 class="sec-h3"><i class="fas fa-file-invoice"></i> Common Documents Required</h3>
            <div class="list-card">
                <ul class="info-grid-list">
                    <li><i class="fas fa-check-square"></i> Death certificate or official notice</li>
                    <li><i class="fas fa-check-square"></i> Custodian affidavit / declaration</li>
                    <li><i class="fas fa-check-square"></i> Certified NIC or identity copies</li>
                    <li><i class="fas fa-check-square"></i> Medical certificate / documents</li>
                    <li><i class="fas fa-check-square"></i> Additional institutional documents</li>
                </ul>
            </div>
        </div>

        <div class="section-padding" style="border-top:1px solid var(--g100)">
            <h3 class="sec-h3"><i class="fas fa-diagram-next"></i> General Process After Death</h3>
            <div class="process-steps">
                <div class="process-step"><div class="step-num">1</div><span>Death occurs</span></div>
                <div class="process-step"><div class="step-num">2</div><span>Donation type is identified</span></div>
                <div class="process-step"><div class="step-num">3</div><span>Consent status is checked</span></div>
                <div class="process-step"><div class="step-num">4</div><span>Custodian contacts the relevant institution</span></div>
                <div class="process-step"><div class="step-num">5</div><span>Required documents are prepared and verified</span></div>
                <div class="process-step"><div class="step-num">6</div><span>Body or donation materials are presented for handover</span></div>
                <div class="process-step"><div class="step-num">7</div><span>Institution accepts or refuses according to requirements</span></div>
            </div>
        </div>

        <div class="section-padding" style="border-top:1px solid var(--g100)">
            <h3 class="sec-h3"><i class="fas fa-circle-info"></i> Important Rules for Custodians</h3>
            <p class="content-p">Custodians should act promptly, keep documents ready, and communicate early with the relevant institution. In body donation cases, transport may need to be arranged by the family or custodian, and acceptance is not automatic. If the institution refuses the body, the custodian remains responsible for making alternative arrangements. Custodians should also understand that documentation may be retained after handover and that the process may be irreversible once accepted.</p>
        </div>

        <div class="notice-box">
            <i class="fas fa-circle-exclamation"></i>
            <div>
                <h5>Important Notice</h5>
                <p>Custodians help carry out the process, but final donation still depends on legal validity, medical suitability, institutional rules, and timely completion of the required steps.</p>
            </div>
        </div>

        <div class="cta-box">
            <h2>Be the reason someone lives.</h2>
            <div class="cta-actions">
                <a href="<?= ROOT ?>/signup" class="btn-hero"><i class="fas fa-user-plus"></i> <span>Register as a Donor</span></a>
                <a href="<?= ROOT ?>/home#tributes" class="btn-outline"><i class="fas fa-users"></i> Read Donor Stories</a>
            </div>
        </div>
    </main>

    <?php include __DIR__ . '/../templates/home_footer.view.php'; ?>
</body>
</html>
