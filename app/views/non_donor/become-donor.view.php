<?php
define('NONDONOR_PAGE', true);
require_once __DIR__ . '/nondonor.view.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Become a Donor - LifeConnect Non-Donor Portal</title>
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="/life-connect/public/assets/css/nondonor/layout.css">
    <link rel="stylesheet" href="/life-connect/public/assets/css/nondonor/become-donor.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <?php renderHeader($user_name, $user_email); ?>
    <?php renderSidebar('become-donor'); ?>

    <!-- MAIN CONTENT -->
    <main class="main-content">
        
        <!-- Hero Section -->
        <div class="hero-section">
            <div class="hero-content">
                <div class="hero-icon">
                    <i class="fas fa-heart-circle-plus"></i>
                </div>
                <h1>Join as a Donor Today</h1>
                <p>Your decision to become an organ donor can save and improve countless lives. Join our community of heroes making a difference.</p>
            </div>
            <div class="hero-illustration">
                <div class="floating-hearts">
                    <i class="fas fa-heart heart-1"></i>
                    <i class="fas fa-heart heart-2"></i>
                    <i class="fas fa-heart heart-3"></i>
                </div>
            </div>
        </div>

        <!-- Why Become a Donor Section -->
        <div class="content-section">
            <div class="section-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <h2>Why Become a Donor?</h2>
                </div>
                <div class="benefits-grid">
                    <div class="benefit-card">
                        <div class="benefit-icon">
                            <i class="fas fa-life-ring"></i>
                        </div>
                        <h3>Save Lives</h3>
                        <p>One organ donor can save up to 8 lives and enhance the lives of 75+ people through tissue donation.</p>
                    </div>
                    <div class="benefit-card">
                        <div class="benefit-icon">
                            <i class="fas fa-hands-helping"></i>
                        </div>
                        <h3>Help Families</h3>
                        <p>Give hope to families waiting for that life-saving call and end their loved one's suffering.</p>
                    </div>
                    <div class="benefit-card">
                        <div class="benefit-icon">
                            <i class="fas fa-award"></i>
                        </div>
                        <h3>Leave a Legacy</h3>
                        <p>Create a lasting legacy of compassion and generosity that will be remembered forever.</p>
                    </div>
                    <div class="benefit-card">
                        <div class="benefit-icon">
                            <i class="fas fa-shield-heart"></i>
                        </div>
                        <h3>Safe & Secure</h3>
                        <p>Your information is protected, and you maintain full control over your donation preferences.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- How It Works Section -->
        <div class="content-section">
            <div class="section-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-list-check"></i>
                    </div>
                    <h2>How It Works</h2>
                </div>
                <div class="steps-container">
                    <div class="step-item">
                        <div class="step-number">1</div>
                        <div class="step-content">
                            <h3>Create Your Account</h3>
                            <p>Register with LifeConnect by providing your basic information and creating a secure account.</p>
                        </div>
                    </div>
                    <div class="step-item">
                        <div class="step-number">2</div>
                        <div class="step-content">
                            <h3>Complete Registration</h3>
                            <p>Fill out the donor registration form with your consent and preferences for organ donation.</p>
                        </div>
                    </div>
                    <div class="step-item">
                        <div class="step-number">3</div>
                        <div class="step-content">
                            <h3>Receive Your Card</h3>
                            <p>Download your official donor card and appreciation certificate to carry with you.</p>
                        </div>
                    </div>
                    <div class="step-item">
                        <div class="step-number">4</div>
                        <div class="step-content">
                            <h3>Make a Difference</h3>
                            <p>Rest easy knowing you've made the decision to help save lives when the time comes.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="cta-section">
            <div class="cta-card">
                <div class="cta-content">
                    <h2>Ready to Make a Difference?</h2>
                    <p>Join thousands of heroes who have already registered as organ donors. Your decision today could save lives tomorrow.</p>
                    <div class="cta-buttons">
                        <a href="\life-connect/app/views/login.view.php" class="btn btn-primary btn-large">
                            <i class="fas fa-user-plus"></i> Create Donor Account
                        </a>
                        <a href="/life-connect/app/views/Non%20donor/index.view.php" class="btn btn-secondary btn-large">
                            <i class="fas fa-arrow-left"></i> Back to Dashboard
                        </a>
                    </div>
                </div>
                <div class="cta-stats">
                    <div class="cta-stat">
                        <i class="fas fa-users"></i>
                        <h3>10,000+</h3>
                        <p>Registered Donors</p>
                    </div>
                    <div class="cta-stat">
                        <i class="fas fa-heart"></i>
                        <h3>2,500+</h3>
                        <p>Lives Saved</p>
                    </div>
                    <div class="cta-stat">
                        <i class="fas fa-star"></i>
                        <h3>98%</h3>
                        <p>Satisfaction Rate</p>
                    </div>
                </div>
            </div>
        </div>
        
    </main>

    <?php renderModals($user_name, $user_email); ?>
    <?php renderScripts(); ?>

</body>
</html>
