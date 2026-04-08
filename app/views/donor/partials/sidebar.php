<?php
/**
 * Donor Portal — Sidebar Partial
 * Include this after header.php in every donor page view.
 * 
 * Expected variables:
 *   $active_page (string: 'overview'|'donations'|'test-results'|'family'|'labs'|'documents')
 */
?>
            <!-- Sidebar -->
            <div class="sidebar glass">
                <div class="sidebar-header">
                    <h3>Menu</h3>
                    <p>LifeConnect Donor</p>
                </div>
                
                <div class="menu-section">
                    <div class="menu-section-title">General</div>
                    <a href="<?= ROOT ?>/donor" class="menu-item <?= ($active_page === 'overview') ? 'active' : '' ?>">
                        <span class="icon"><i class="fa-solid fa-chart-line"></i></span>
                        <span>Overview</span>
                    </a>
                    <a href="<?= ROOT ?>/donor/donations" class="menu-item <?= ($active_page === 'donations') ? 'active' : '' ?>">
                        <span class="icon"><i class="fa-solid fa-heart"></i></span>
                        <span>My Donations</span>
                    </a>
                </div>

                <div class="menu-section">
                    <div class="menu-section-title">Medical & Personal</div>
                    <a href="<?= ROOT ?>/donor/test-results" class="menu-item <?= ($active_page === 'test-results') ? 'active' : '' ?>">
                        <span class="icon"><i class="fa-solid fa-vial"></i></span>
                        <span>Test Results</span>
                    </a>
                    <a href="<?= ROOT ?>/donor/family-custodians" class="menu-item <?= ($active_page === 'family') ? 'active' : '' ?>">
                        <span class="icon"><i class="fa-solid fa-users"></i></span>
                        <span>Family & Custodians</span>
                    </a>
                    <a href="<?= ROOT ?>/donor/approved-labs" class="menu-item <?= ($active_page === 'labs') ? 'active' : '' ?>">
                        <span class="icon"><i class="fa-solid fa-microscope"></i></span>
                        <span>Approved Labs</span>
                    </a>
                    <a href="<?= ROOT ?>/donor/aftercare" class="menu-item <?= ($active_page === 'aftercare') ? 'active' : '' ?>">
                        <span class="icon"><i class="fa-solid fa-hand-holding-medical"></i></span>
                        <span>Aftercare Support</span>
                    </a>
                </div>

                <div class="menu-section">
                    <div class="menu-section-title">Documents</div>
                    <a href="<?= ROOT ?>/donor/documents" class="menu-item <?= ($active_page === 'documents') ? 'active' : '' ?>">
                        <span class="icon"><i class="fa-solid fa-file-signature"></i></span>
                        <span>Consent Forms</span>
                    </a>
                </div>

                <div class="menu-section mt-auto">
                    <a href="javascript:void(0)" onclick="openLogoutModal()" class="menu-item text-danger">
                        <span class="icon"><i class="fa-solid fa-right-from-bracket"></i></span>
                        <span>Logout</span>
                    </a>
                </div>
            </div>
            
            <div class="content-area" id="content-area">
