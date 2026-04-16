<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aftercare Guide | Life-Connect Sri Lanka</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= ROOT ?>/public/assets/css/home.css">
</head>
<body>
    <?php include __DIR__ . '/../templates/home_header.view.php'; ?>

    <section class="page-hero">
        <div class="container">
            <h1>Aftercare for Donors and Recipients</h1>
            <p>Recovery, monitoring, and long-term support after donation or transplantation.</p>
        </div>
    </section>

    <main class="container section-padding">
        <div style="max-width:1000px">
            <p class="content-p">Aftercare is an essential part of ethical donation and transplantation. Living donors need follow-up to protect their health after surgery, while recipients need long-term monitoring, medicines, and support to protect the transplanted organ and reduce complications such as rejection or infection.</p>
        </div>

        <div class="section-padding">
            <h3 class="sec-h3"><i class="fas fa-heart-pulse"></i> Living Donor Aftercare</h3>
            <p class="content-p">Living donors usually need a period of recovery after surgery, including rest, pain management, gradual return to activity, and follow-up medical reviews. Recovery plans vary, but many donors need weeks of healing and should avoid heavy physical strain during the early period. Long-term checkups are also important because donor follow-up is part of protecting a healthy person who underwent surgery without direct medical benefit.</p>
        </div>

        <div class="section-padding" style="border-top:1px solid var(--g100)">
            <h3 class="sec-h3"><i class="fas fa-stethoscope"></i> Why Follow-Up Matters</h3>
            <p class="content-p">Follow-up helps doctors monitor blood pressure, kidney function, healing, and any later health concerns. Even when long-term risks are low, structured review is important to make sure the donor remains healthy over time and receives proper support if any complication appears.</p>
        </div>

        <div class="section-padding" style="border-top:1px solid var(--g100)">
            <h3 class="sec-h3"><i class="fas fa-user-shield"></i> Recipient Aftercare</h3>
            <p class="content-p">Recipients need closer and longer-term medical care after transplantation. This usually includes regular clinic visits, blood tests, medicine review, and observation for rejection or infection. Early after transplant, appointments may be frequent, and over time the schedule may reduce if the patient remains stable. However, follow-up remains important for the long term.</p>
        </div>

        <div class="section-padding" style="border-top:1px solid var(--g100)">
            <h3 class="sec-h3"><i class="fas fa-pills"></i> Medicines and Adherence</h3>
            <p class="content-p">Recipients usually need ongoing immunosuppressive medicine after transplant. These medicines help prevent rejection, but they also require careful monitoring. Taking medicines correctly and attending follow-up visits are critical parts of aftercare, because missed treatment can place the transplanted organ at risk.</p>
        </div>

        <div class="section-padding" style="border-top:1px solid var(--g100)">
            <h3 class="sec-h3"><i class="fas fa-circle-nodes"></i> Common Areas That Need Monitoring</h3>
            <div class="info-grid">
                <div class="info-card">
                    <h4>Rejection</h4>
                    <p>The body may try to reject the transplanted organ, so doctors monitor symptoms, blood results, and overall graft function.</p>
                </div>
                <div class="info-card">
                    <h4>Infection</h4>
                    <p>Transplant recipients can be more vulnerable to infections, especially because of immunosuppressive treatment.</p>
                </div>
                <div class="info-card">
                    <h4>Healing and recovery</h4>
                    <p>Both donors and recipients need monitoring of recovery, wound healing, and safe return to activity.</p>
                </div>
                <div class="info-card">
                    <h4>Long-term health</h4>
                    <p>Ongoing review supports long-term wellbeing, safe medication use, and better quality of life.</p>
                </div>
            </div>
        </div>

        <div class="section-padding" style="border-top:1px solid var(--g100)">
            <h3 class="sec-h3"><i class="fas fa-timeline"></i> General Follow-Up Timeline</h3>
            <div class="timeline">
                <div class="timeline-item">
                    <h4>Immediately after surgery</h4>
                    <p>Hospital recovery and early monitoring.</p>
                </div>
                <div class="timeline-item">
                    <h4>First few weeks</h4>
                    <p>Wound healing, activity guidance, and professional medical review.</p>
                </div>
                <div class="timeline-item">
                    <h4>First months</h4>
                    <p>Regular follow-up, medicine adjustments, and stability checks.</p>
                </div>
                <div class="timeline-item">
                    <h4>Long term</h4>
                    <p>Ongoing review, routine diagnostic tests, and long-term support.</p>
                </div>
            </div>
        </div>

        <div class="notice-box">
            <i class="fas fa-info-circle"></i>
            <div>
                <h5>Important Notice</h5>
                <p>Aftercare plans vary depending on the donor, recipient, procedure, hospital, and medical condition. This information is for educational purposes and is not a substitute for professional medical advice.</p>
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
