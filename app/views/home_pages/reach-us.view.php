<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | Life-Connect Sri Lanka</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= ROOT ?>/public/assets/css/home.css">
    <style>
        .hero { padding: 100px 0 80px; background: var(--blue-50); }
        .contact-grid { display: grid; grid-template-columns: 1fr 1.5fr; gap: 50px; margin-top: 50px; }
        .contact-info { display: flex; flex-direction: column; gap: 30px; }
        .info-box { display: flex; gap: 20px; align-items: flex-start; }
        .info-box i { width: 50px; height: 50px; background: var(--blue-100); color: var(--blue-600); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0; }
        .info-box h4 { margin-bottom: 5px; color: var(--slate); }
        .info-box p { color: var(--g500); font-size: 0.95rem; }
        .contact-form { background: var(--white); border: 1px solid var(--g200); padding: 40px; border-radius: 20px; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05); }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: var(--slate); font-size: 0.9rem; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 12px 16px; border: 1.5px solid var(--g200); border-radius: 10px; font-family: inherit; transition: var(--tr); }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus { border-color: var(--blue-600); outline: none; box-shadow: 0 0 0 4px var(--blue-50); }
        .map-section { height: 400px; border-radius: 20px; overflow: hidden; margin-top: 80px; background: var(--g100); }
        @media (max-width: 992px) { .contact-grid { grid-template-columns: 1fr; } .contact-form { order: -1; } }
        @media (max-width: 600px) { .form-row { grid-template-columns: 1fr; } }
    </style>
</head>
<body>

<?php include __DIR__ . '/../templates/home_header.view.php'; ?>

<!-- ========== HERO ========== -->
<section class="hero">
    <div class="container hero-grid">
        <div class="hero-text">
            <h1>Reach Us</h1>
            <p>Have questions about donation, residency, or the process in Sri Lanka? We're here to help you every step of the way.</p>
        </div>
        <div class="hero-image-wrap">
            <div class="hero-shape"></div>
            <img src="<?= ROOT ?>/public/assets/images/contact-bg.jpg" alt="Contact Us" class="hero-img">
        </div>
    </div>
</section>

<!-- ========== CONTENT ========== -->
<section class="contact-section" style="padding: 80px 0;">
    <div class="container">
        <div class="contact-grid">
            <div class="contact-info">
                <div class="sec-header" style="text-align: left; margin-bottom: 40px;">
                    <h2>Get in Touch</h2>
                    <div class="underline" style="margin-inline: 0;"></div>
                    <p>Our dedicated support team is available to assist you with any inquiries regarding organ and body donation.</p>
                </div>

                <div class="info-box">
                    <i class="fa-solid fa-location-dot"></i>
                    <div>
                        <h4>Global Headquarters</h4>
                        <p>123/A, Galle Road, Colombo 03, Sri Lanka.</p>
                    </div>
                </div>
                <div class="info-box">
                    <i class="fa-solid fa-phone"></i>
                    <div>
                        <h4>Helpline (24/7)</h4>
                        <p>+94 11 234 5678</p>
                    </div>
                </div>
                <div class="info-box">
                    <i class="fa-solid fa-envelope"></i>
                    <div>
                        <h4>Email Support</h4>
                        <p>hello@lifeconnect.lk</p>
                    </div>
                </div>
                <div class="info-box" style="margin-top: 20px;">
                    <div style="display: flex; gap: 15px;">
                        <a href="#" style="font-size: 1.5rem; color: var(--blue-600);"><i class="fa-brands fa-facebook"></i></a>
                        <a href="#" style="font-size: 1.5rem; color: var(--blue-600);"><i class="fa-brands fa-twitter"></i></a>
                        <a href="#" style="font-size: 1.5rem; color: var(--blue-600);"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" style="font-size: 1.5rem; color: var(--blue-600);"><i class="fa-brands fa-linkedin"></i></a>
                    </div>
                </div>
            </div>

            <div class="contact-form">
                <form action="#" method="POST">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" placeholder="e.g. Kamal Perera" required>
                        </div>
                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="email" placeholder="kamal@example.com" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="tel" placeholder="+94 7X XXX XXXX">
                        </div>
                        <div class="form-group">
                            <label>Subject</label>
                            <select>
                                <option>General Inquiry</option>
                                <option>Donor Registration Help</option>
                                <option>Legal Questions</option>
                                <option>Support a Family</option>
                                <option>Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Your Message</label>
                        <textarea rows="5" placeholder="How can we help you?" required></textarea>
                    </div>
                    <button type="submit" class="btn-hero" style="width: 100%; border: none; cursor: pointer;">
                        <span>Send Message</span> <i class="fa-solid fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </div>

        <div class="map-section">
            <!-- Simulated map with background color for now -->
            <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; flex-direction:column; color: var(--g500);">
                <i class="fa-solid fa-map-location-dot" style="font-size: 3rem; margin-bottom: 20px; color: var(--blue-200);"></i>
                <p>Interactive Map of Life-Connect Centers Coming Soon</p>
                <p style="font-size:0.8rem;">Colombo • Kandy • Galle • Jaffna</p>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../templates/home_footer.view.php'; ?>

</body>
</html>
