<?php
define('NONDONOR_PAGE', true);
require_once __DIR__ . '/nondonor.view.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - LifeConnect Non-Donor Portal</title>
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="/life-connect/public/assets/css/nondonor/layout.css">
    <link rel="stylesheet" href="/life-connect/public/assets/css/nondonor/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <?php renderHeader($user_name, $user_email); ?>
    <?php renderSidebar('index'); ?>

    <!-- MAIN CONTENT -->
    <main class="main-content">
        
        <!-- Welcome Banner -->
        <div class="welcome-banner">
            <div class="welcome-content">
                <div class="welcome-text">
                    <h2>Welcome back, <?php echo htmlspecialchars($user_name); ?>! 👋</h2>
                    <p>Explore inspiring stories and learn how you can make a difference through organ donation.</p>
                </div>
                <div class="welcome-illustration">
                    <div class="illustration-circle">
                        <i class="fas fa-heart-pulse"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Dashboard Grid -->
        <div class="dashboard-grid">
            
            <!-- Success Stories Card -->
            <div class="card stories-card">
                <div class="card-header">
                    <div class="card-icon stories">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <div>
                        <h3>Inspiring Success Stories</h3>
                        <p>Real lives changed through organ donation</p>
                    </div>
                </div>
                <div class="stories-grid">
                    <?php if (empty($stories)): ?>
                        <div class="empty-state">
                            <i class="fas fa-inbox"></i>
                            <p>No stories available yet</p>
                        </div>
                    <?php else: ?>
                        <?php foreach($stories as $story): ?>
                            <div class="story-card">
                                <div class="story-header">
                                    <div class="story-icon">
                                        <i class="fas fa-heart"></i>
                                    </div>
                                    <div class="story-date">
                                        <i class="far fa-calendar"></i>
                                        <?php echo date('M d, Y', strtotime($story['success_date'])); ?>
                                    </div>
                                </div>
                                <h4><?php echo htmlspecialchars($story['title']); ?></h4>
                                <p><?php echo htmlspecialchars($story['description']); ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Become a Donor Card -->
            <div class="card action-card primary">
                <div class="card-icon-large">
                    <i class="fas fa-hand-holding-heart"></i>
                </div>
                <h3>Become a Donor</h3>
                <p>Register as an organ donor and join thousands of heroes who have chosen to give the gift of life.</p>
                <ul class="benefit-list">
                    <li><i class="fas fa-check-circle"></i> Save up to 8 lives</li>
                    <li><i class="fas fa-check-circle"></i> Make a lasting impact</li>
                    <li><i class="fas fa-check-circle"></i> Give hope to families</li>
                    <li><i class="fas fa-check-circle"></i> Leave a legacy of compassion</li>
                </ul>
                <a href="/life-connect/app/views/Non%20donor/become-donor.view.php" class="btn btn-primary btn-large">
                    <i class="fas fa-heart"></i> Register as Donor
                </a>
            </div>
            
            <!-- Why Donation Matters Card -->
            <div class="card info-card">
                <div class="card-icon-large secondary">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3>Why Organ Donation Matters</h3>
                <p>Every day, patients wait for life-saving organ transplants. Your decision can make a tremendous difference.</p>
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-content">
                            <h4>8 Lives</h4>
                            <p>One donor can save</p>
                        </div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-icon">
                            <i class="fas fa-eye"></i>
                        </div>
                        <div class="stat-content">
                            <h4>2 People</h4>
                            <p>Eye donation restores sight</p>
                        </div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-icon">
                            <i class="fas fa-hand-holding-medical"></i>
                        </div>
                        <div class="stat-content">
                            <h4>75+ People</h4>
                            <p>Tissue donation helps</p>
                        </div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-content">
                            <h4>20 Daily</h4>
                            <p>Die waiting for organs</p>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
        
    </main>

    <?php renderModals($user_name, $user_email); ?>
    <?php renderScripts(); ?>

</body>
</html>