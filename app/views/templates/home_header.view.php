<?php
$currentPath = trim(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?? '', '/');
$rootPath = trim(parse_url(ROOT, PHP_URL_PATH) ?? '', '/');
if ($rootPath && str_starts_with($currentPath, $rootPath)) {
    $currentPath = trim(substr($currentPath, strlen($rootPath)), '/');
}
$currentPath = $currentPath === '' ? 'home' : $currentPath;
$activePage = $activePage ?? $currentPath;

$dashboardUrl = 'home';
if (isset($_SESSION['role'])) {
    $roleMap = [
        'U_ADMIN' => 'user-admin',
        'F_ADMIN' => 'financial-admin',
        'AC_ADMIN' => 'aftercare-admin',
        'D_ADMIN' => 'donation-admin',
        'FINANCIAL_DONOR' => 'financial-donor',
        'DONOR' => 'donor',
        'HOSPITAL' => 'hospital',
        'MEDICAL_SCHOOL' => 'medical-school',
        'CUSTODIAN' => 'custodian',
    ];
    $dashboardUrl = $roleMap[strtoupper($_SESSION['role'])] ?? 'home';
}
?>
<header class="site-header">
    <nav class="main-nav">
        <div class="container">
            <a href="<?= ROOT ?>/home" class="logo">
                <img src="<?= ROOT ?>/public/assets/images/logo.png" alt="Life Connect Logo">
                <div>
                    <strong>Life Connect</strong>
                    <small>Ministry of Health Sri Lanka</small>
                </div>
            </a>

            <div class="nav-links" id="navLinks">
                <a href="<?= ROOT ?>/home" class="<?= $activePage === 'home' ? 'active' : '' ?>">Home</a>
                <a href="<?= ROOT ?>/education" class="<?= $activePage === 'education' ? 'active' : '' ?>">Education</a>
                <a href="<?= ROOT ?>/religion" class="<?= $activePage === 'religion' ? 'active' : '' ?>">Religion</a>
                <a href="<?= ROOT ?>/legal" class="<?= $activePage === 'legal' ? 'active' : '' ?>">Legal</a>
                <a href="<?= ROOT ?>/our-story" class="<?= $activePage === 'our-story' ? 'active' : '' ?>">Our Story</a>
                <?php if(isset($_SESSION['username'])): ?>
                    <a href="<?= ROOT ?>/<?= $dashboardUrl ?>" class="nav-link-mobile-only">Dashboard</a>
                    <a href="<?= ROOT ?>/logout" class="nav-link-mobile-only">Log out</a>
                <?php else: ?>
                    <a href="<?= ROOT ?>/login" class="nav-link-mobile-only">Log In</a>
                    <a href="<?= ROOT ?>/signup" class="nav-link-cta">Become a Donor</a>
                <?php endif; ?>
            </div>

            <div class="nav-right">
                <a href="#" class="nav-search" aria-label="Search"><i class="fa-solid fa-magnifying-glass"></i></a>
                 <?php if(isset($_SESSION['username'])): ?>
                    <div class="user-avatar-dropdown" tabindex="0">
                        <?php $initial = strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                        <div class="user-avatar" title="<?= htmlspecialchars($_SESSION['username']) ?>">
                            <?= htmlspecialchars($initial) ?>
                        </div>
                        <div class="user-dropdown-menu">
                            <div class="user-info">
                                <?= htmlspecialchars($_SESSION['username']) ?>
                                <?php if(isset($_SESSION['role'])): ?>
                                    <small><?= htmlspecialchars(ucwords(strtolower(str_replace('_', ' ', $_SESSION['role'])))) ?></small>
                                <?php endif; ?>
                            </div>
                            <a href="<?= ROOT ?>/<?= $dashboardUrl ?>">Dashboard</a>
                            <a href="<?= ROOT ?>/logout">Log out</a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="<?= ROOT ?>/login" class="nav-login">Log In</a>
                    <a href="<?= ROOT ?>/signup" class="nav-cta">Become a Donor</a>
                <?php endif; ?>
            </div>

            <button class="hamburger" id="hamburger" aria-label="Open navigation">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>
    </nav>
</header>
