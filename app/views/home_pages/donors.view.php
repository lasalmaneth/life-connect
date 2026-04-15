<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organ and Body Donation Guide | Life-Connect Sri Lanka</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= ROOT ?>/public/assets/css/home.css">
</head>
<body>
    <?php include __DIR__ . '/../templates/home_header.view.php'; ?>

    <section class="page-hero">
        <div class="container">
            <h1>Organ and Body Donation</h1>
            <p>A guide for people who want to donate organs or donate their body for medical education and research.</p>
        </div>
    </section>

    <main class="container section-padding">
        <div style="max-width:1000px">
            <p class="content-p">Organ and body donation can save lives, support medical treatment, and contribute to education and research. Donation may happen while a person is alive, after death for transplantation, or after death as a whole-body donation for medical learning. Each pathway has different medical, legal, and ethical requirements, and all donation decisions must be based on informed consent and proper review.</p>
        </div>

        <div class="section-padding">
            <h3 class="sec-h3"><i class="fas fa-layer-group"></i> Types of Donation</h3>
            <div class="info-grid">
                <div class="info-card">
                    <h4>Living organ donation</h4>
                    <p>Living donation usually involves a healthy person donating an organ such as one kidney, or in some cases part of an organ, after medical evaluation. Because the donor is a healthy person undergoing major surgery, donor safety, voluntary consent, and long-term follow-up are essential.</p>
                </div>
                <div class="info-card">
                    <h4>Deceased organ donation</h4>
                    <p>Deceased organ donation happens after death has been properly confirmed under accepted medical and legal standards. Organs may then be retrieved for transplantation if the required consent or authorization exists and all medical conditions are satisfied.</p>
                </div>
                <div class="info-card">
                    <h4>Whole-body donation</h4>
                    <p>Whole-body donation is different from organ transplantation. It is generally used for medical education and research rather than to transplant organs into a recipient. In Sri Lanka, this process is handled through specific institutional rules, documentation, and acceptance criteria.</p>
                </div>
            </div>
        </div>

        <div class="section-padding" style="border-top:1px solid var(--g100)">
            <h3 class="sec-h3"><i class="fas fa-user-check"></i> Who Can Become a Donor</h3>
            <p class="content-p">Becoming a donor depends on the type of donation. Living donors are medically assessed to make sure donation is as safe as possible. Deceased donation depends on the condition of the body or organs at the time of death, medical suitability, and the legal consent status. Whole-body donation also depends on the receiving medical school’s acceptance requirements, including body condition, required documents, and timing of handover.</p>
        </div>

        <div class="section-padding" style="border-top:1px solid var(--g100)">
            <h3 class="sec-h3"><i class="fas fa-scale-balanced"></i> Consent and Legal Requirements</h3>
            <p class="content-p">Donation must be based on proper consent. Sri Lankan law recognizes donation made during life and donation that becomes effective after death. For living donation, written consent and medical explanation are required. For donation after death, prior donor consent is important, but in some situations next of kin or a lawful custodian may also have a role in authorization when the law allows it. Donation systems must also prevent coercion, conflicts of interest, and any commercial sale of human organs or tissues.</p>
        </div>

        <div class="section-padding" style="border-top:1px solid var(--g100)">
            <h3 class="sec-h3"><i class="fas fa-list-check"></i> What Donors Should Know</h3>
            <div class="list-card">
                <ul class="info-grid-list">
                    <li><i class="fas fa-check-circle"></i> Donation must be voluntary.</li>
                    <li><i class="fas fa-check-circle"></i> A donor should understand the nature and effect of the donation.</li>
                    <li><i class="fas fa-check-circle"></i> Living donors should be medically and psychologically assessed.</li>
                    <li><i class="fas fa-check-circle"></i> Deceased donation depends on lawful authorization and medical suitability.</li>
                    <li><i class="fas fa-check-circle"></i> Whole-body donation may still be refused by receiving institutions.</li>
                    <li><i class="fas fa-check-circle"></i> Donation should never involve buying or selling organs.</li>
                </ul>
            </div>
        </div>

        <div class="section-padding" style="border-top:1px solid var(--g100)">
            <h3 class="sec-h3"><i class="fas fa-clipboard-check"></i> What Happens After Registration</h3>
            <p class="content-p">After registering, the donor’s consent record should be stored securely. If the donor later changes or withdraws their consent, the newest valid consent decision should be treated as the active one in the system. For body donation, the relevant medical school may review the consent and later the custodian must still complete the post-death submission process with documents and handover steps.</p>
        </div>

        <div class="notice-box">
            <i class="fas fa-circle-exclamation"></i>
            <div>
                <h5>Important Notice</h5>
                <p>Registration shows a donor’s intention, but final donation still depends on legal confirmation, medical suitability, and the specific procedures followed by hospitals or medical schools at the relevant time.</p>
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
