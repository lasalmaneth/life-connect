<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aftercare Guide | Life-Connect Sri Lanka</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= ROOT ?>/public/assets/css/home.css">
    <style>
        .page-hero{padding:110px 0 80px;background:linear-gradient(135deg, var(--blue-100) 0%, var(--blue-50) 35%, var(--white) 70%, var(--blue-50) 100%)}
        .page-hero h1{font-size:3rem;color:var(--slate);margin-bottom:12px;letter-spacing:-0.02em}
        .page-hero p{color:var(--g500);font-size:1.1rem;max-width:900px}
        
        .section-padding{padding:60px 0}
        .content-p{font-size:.95rem;color:var(--g500);line-height:1.8;margin-bottom:20px}
        .sec-h3{font-size:1.5rem;font-weight:700;color:var(--slate);margin-bottom:20px;display:flex;align-items:center;gap:12px}
        .sec-h3 i{color:var(--blue-600);font-size:1.3rem}
        
        .info-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:24px;margin-top:30px}
        .info-card{background:var(--white);border:1px solid var(--g200);padding:24px;border-radius:16px;transition:all var(--tr)}
        .info-card:hover{border-color:var(--blue-300);transform:translateY(-4px);box-shadow:0 10px 20px rgba(0,0,0,0.05)}
        .info-card h4{font-size:1.05rem;color:var(--slate);margin-bottom:10px;font-weight:700}
        .info-card p{font-size:.88rem;color:var(--g500);line-height:1.6;margin:0}
        
        /* ── Timeline ── */
        .timeline{position:relative;margin-top:40px;padding-left:30px;border-left:2px solid var(--blue-100)}
        .timeline-item{position:relative;margin-bottom:30px}
        .timeline-item::before{content:'';position:absolute;left:-37px;top:5px;width:12px;height:12px;border-radius:50%;background:var(--blue-600);border:3px solid var(--white)}
        .timeline-item h4{font-size:.95rem;color:var(--slate);font-weight:700;margin-bottom:5px}
        .timeline-item p{font-size:.88rem;color:var(--g500);margin:0}

        .notice-box{background:var(--blue-50);border:1px solid var(--blue-200);color:var(--blue-800);padding:30px;border-radius:20px;margin-top:40px;display:flex;align-items:center;gap:20px}
        .notice-box i{font-size:2rem;color:var(--blue-600)}
        .notice-box div h5{font-size:1.1rem;margin-bottom:5px;font-weight:700}
        .notice-box div p{font-size:.92rem;line-height:1.5;margin:0}

        /* ── CTA box ── */
        .cta-box{background:linear-gradient(135deg,var(--blue-600),var(--blue-800));color:var(--white);border-radius:20px;padding:50px 36px;text-align:center;margin-top:60px}
        .cta-box h2{color:var(--white);margin-bottom:24px;font-size:2rem}
        .cta-actions{display:flex;gap:14px;justify-content:center;flex-wrap:wrap}
        .cta-actions .btn-hero{background:#10b981;padding:14px 28px;border-radius:50px;color:var(--white);text-decoration:none;font-weight:600;display:inline-flex;align-items:center;gap:10px}
        .cta-actions .btn-hero:hover{background:#059669;transform:translateY(-2px)}
        .btn-outline{display:inline-flex;align-items:center;gap:10px;border:2px solid rgba(255,255,255,.4);color:var(--white);background:transparent;padding:14px 28px;border-radius:50px;font-weight:600;font-size:.95rem;transition:all var(--tr);text-decoration:none}
        .btn-outline:hover{background:rgba(255,255,255,.15);border-color:var(--white);transform:translateY(-2px)}

        .back-link{display:inline-flex;align-items:center;gap:8px;color:var(--blue-600);text-decoration:none;font-weight:600;margin-bottom:30px;font-size:.9rem;transition:transform var(--tr)}
        .back-link:hover{transform:translateX(-5px)}

        @media(max-width:768px){
            .page-hero h1{font-size:2rem}
            .notice-box{flex-direction:column;text-align:center}
        }
    </style>
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
