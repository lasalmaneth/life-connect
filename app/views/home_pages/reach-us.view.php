<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | Life-Connect Sri Lanka</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= ROOT ?>/public/assets/css/home.css">
</head>
<body>

<?php include __DIR__ . '/../templates/home_header.view.php'; ?>

<!-- ========== HERO ========== -->
<section class="page-hero">
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
<section class="section-padding">
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
                <?php if (isset($_SESSION['contact_success'])): ?>
                    <div class="cp-notice cp-notice--success mb-4" style="background: #f0fdf4; border: 1px solid #bbf7d0; padding: 15px; border-radius: 10px; color: #166534; margin-bottom: 25px;">
                        <i class="fas fa-circle-check" style="margin-right: 8px;"></i>
                        <?= $_SESSION['contact_success'] ?>
                        <?php unset($_SESSION['contact_success']); ?>
                    </div>
                <?php endif; ?>

                <?php 
                $errors = $_SESSION['contact_errors'] ?? [];
                $formData = $_SESSION['contact_data'] ?? [];
                unset($_SESSION['contact_errors'], $_SESSION['contact_data']);
                ?>

                <form action="<?= ROOT ?>/reach-us/submit" method="POST">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" name="full_name" placeholder="e.g. Kamal Perera" value="<?= htmlspecialchars($formData['full_name'] ?? '') ?>" required>
                            <?php if (isset($errors['full_name'])): ?><span style="color: #dc2626; font-size: 0.8rem;"><?= $errors['full_name'] ?></span><?php endif; ?>
                        </div>
                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="email" name="email" placeholder="kamal@example.com" value="<?= htmlspecialchars($formData['email'] ?? '') ?>" required>
                            <?php if (isset($errors['email'])): ?><span style="color: #dc2626; font-size: 0.8rem;"><?= $errors['email'] ?></span><?php endif; ?>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="tel" name="phone" placeholder="+94 7X XXX XXXX" value="<?= htmlspecialchars($formData['phone'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label>Subject</label>
                            <select name="subject">
                                <?php 
                                $subjects = ['General Inquiry', 'Donor Registration Help', 'Legal Questions', 'Support a Family', 'Other'];
                                foreach ($subjects as $sub):
                                    $sel = ($formData['subject'] ?? '') === $sub ? 'selected' : '';
                                    echo "<option value=\"$sub\" $sel>$sub</option>";
                                endforeach;
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Your Message</label>
                        <textarea name="message" rows="5" placeholder="How can we help you?" required><?= htmlspecialchars($formData['message'] ?? '') ?></textarea>
                        <?php if (isset($errors['message'])): ?><span style="color: #dc2626; font-size: 0.8rem;"><?= $errors['message'] ?></span><?php endif; ?>
                    </div>
                    <button type="submit" class="btn-hero" style="width: 100%; border: none; cursor: pointer;">
                        <span>Send Message</span> <i class="fa-solid fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </div>

        <div class="map-section">
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
