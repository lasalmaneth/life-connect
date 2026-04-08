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
                    <style>
                        .user-avatar-dropdown {
                            position: relative;
                            display: inline-block;
                            margin-left: 15px;
                        }
                        .user-avatar {
                            width: 40px;
                            height: 40px;
                            border-radius: 50%;
                            background-color: var(--primary, #0fa57f);
                            color: white;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            font-weight: bold;
                            font-size: 1.2rem;
                            cursor: pointer;
                            text-transform: uppercase;
                            border: 2px solid white;
                            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                        }
                        .user-dropdown-menu {
                            display: none;
                            position: absolute;
                            right: 0;
                            top: 50px;
                            background-color: white;
                            min-width: 160px;
                            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
                            border-radius: 8px;
                            z-index: 1000;
                            overflow: hidden;
                        }
                        .user-avatar-dropdown:hover .user-dropdown-menu,
                        .user-avatar-dropdown:focus-within .user-dropdown-menu {
                            display: block;
                        }
                        .user-dropdown-menu a {
                            color: #333;
                            padding: 12px 16px;
                            text-decoration: none;
                            display: block;
                            transition: background-color 0.2s;
                        }
                        .user-dropdown-menu a:hover {
                            background-color: #f1f1f1;
                        }
                        .user-dropdown-menu .user-info {
                            padding: 12px 16px;
                            border-bottom: 1px solid #eee;
                            font-weight: bold;
                            background-color: #fafafa;
                            color: #333;
                        }
                    </style>
                    <div class="user-avatar-dropdown" tabindex="0">
                        <?php $initial = strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                        <div class="user-avatar" title="<?= htmlspecialchars($_SESSION['username']) ?>">
                            <?= htmlspecialchars($initial) ?>
                        </div>
                        <div class="user-dropdown-menu">
                            <div class="user-info">
                                <?= htmlspecialchars($_SESSION['username']) ?>
                                <?php if(isset($_SESSION['role'])): ?>
                                    <div style="font-size: 0.8em; font-weight: normal; color: #666; margin-top: 4px;">
                                        <?= htmlspecialchars(ucwords(strtolower(str_replace('_', ' ', $_SESSION['role'])))) ?>
                                    </div>
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
