<div class="sidebar">
    <div class="sidebar-header">
        <h3>Hospital Portal </h3>
        <p>Clinical coordination</p>
    </div>

    <div class="menu-section">
        <div class="menu-section-title">MAIN</div>
        <a href="<?php echo ROOT; ?>/hospital" class="menu-item active" data-section="overview" style="text-decoration:none; color: inherit; display: flex; text-align: left;">
            <span class="icon"><i class="fas fa-chart-line"></i></span>
            <span>Main Dashboard</span>
        </a>
        <a href="<?php echo ROOT; ?>/hospital/notifications" class="menu-item" style="text-decoration:none; color: inherit; display: flex; text-align: left;">
            <span class="icon"><i class="fas fa-bell"></i></span>
            <span style="flex:1;">Notifications</span>
            <?php if (!empty($unread_count ?? 0)): ?>
                <span style="display:inline-flex; align-items:center; justify-content:center; min-width:20px; height:20px; padding:0 6px; border-radius:999px; background:var(--danger-color); color:var(--white-color); font-size:.7rem; font-weight:900; margin-left:auto;">
                    <?php echo (int)($unread_count ?? 0); ?>
                </span>
            <?php endif; ?>
        </a>

        <div class="menu-section-title" style="margin-top: 1rem;">BASIC ORGAN TESTING</div>
        <a href="<?php echo ROOT; ?>/hospital/consents" class="menu-item" data-section="consents" style="text-decoration:none; color: inherit; display: flex; text-align: left;">
            <span class="icon"><i class="fas fa-file-signature"></i></span>
            <span>Donor Consents</span>
        </a>
        <a href="<?php echo ROOT; ?>/hospital/appointments" class="menu-item" data-section="lab-reports" style="text-decoration:none; color: inherit; display: flex; text-align: left; white-space: nowrap;">
            <span class="icon"><i class="fas fa-calendar-alt"></i></span>
            <span>Schedule Test</span>
        </a>
        <a href="<?php echo ROOT; ?>/hospital/test-results" class="menu-item" data-section="test-results" style="text-decoration:none; color: inherit; display: flex; text-align: left; white-space: nowrap;">
            <span class="icon"><i class="fas fa-vial"></i></span>
            <span>Test Results</span>
        </a>

        <div class="menu-section-title" style="margin-top: 1rem;">ORGAN REQUEST</div>
        <a href="<?php echo ROOT; ?>/hospital/organ-requests" class="menu-item" data-section="organ-requests" style="text-decoration:none; color: inherit; display: flex; text-align: left;">
            <span class="icon"><i class="fas fa-heart"></i></span>
            <span>Manage Requests</span>
        </a>
        <a href="<?php echo ROOT; ?>/hospital/matching" class="menu-item" data-section="matching" onclick="showContent('matching', this); return false;" style="text-decoration:none; color: inherit; display: flex; text-align: left;">
            <span class="icon"><i class="fas fa-dna"></i></span>
            <span>Matching Results</span>
        </a>

        <div class="menu-section-title" style="margin-top: 1rem;">SURGERY MANAGEMENT</div>
        <a href="<?php echo ROOT; ?>/hospital/surgery-prep" class="menu-item" data-section="surgery-prep" onclick="showContent('surgery-prep', this); return false;" style="text-decoration:none; color: inherit; display: flex; text-align: left;">
            <span class="icon"><i class="fas fa-procedures"></i></span>
            <span>Details</span>
        </a>

        <div class="menu-section-title" style="margin-top: 1rem;">DECEASED ORGAN MANAGEMENT</div>
        <a href="<?php echo ROOT; ?>/hospital/deceased-requests" class="menu-item" data-section="deceased-requests" onclick="showContent('deceased-requests', this); return false;" style="text-decoration:none; color: inherit; display: flex; text-align: left;">
            <span class="icon"><i class="fas fa-inbox"></i></span>
            <span>Requests</span>
        </a>
        <a href="<?php echo ROOT; ?>/hospital/deceased-documents" class="menu-item" data-section="deceased-documents" onclick="showContent('deceased-documents', this); return false;" style="text-decoration:none; color: inherit; display: flex; text-align: left;">
            <span class="icon"><i class="fas fa-file-medical"></i></span>
            <span>Document & Success Stories</span>
        </a>
        <a href="<?php echo ROOT; ?>/hospital/deceased-final-flow" class="menu-item" data-section="deceased-final-flow" onclick="showContent('deceased-final-flow', this); return false;" style="text-decoration:none; color: inherit; display: flex; text-align: left;">
            <span class="icon"><i class="fas fa-check-double"></i></span>
            <span>Final Flow</span>
        </a>

        <div class="menu-section-title" style="margin-top: 1rem;">COMMUNITY</div>
        <a href="<?php echo ROOT; ?>/hospital/stories" class="menu-item" data-section="stories" style="text-decoration:none; color: inherit; display: flex; text-align: left;">
            <span class="icon"><i class="fas fa-star"></i></span>
            <span>Success Stories</span>
        </a>

        <div class="menu-section-title" style="margin-top: 1.5rem;">AFTERCARE ACCESS</div>
        <a href="<?php echo ROOT; ?>/hospital/addpatient" class="menu-item" style="text-decoration: none; color: inherit; display: flex; text-align: left;">
            <span class="icon"><i class="fas fa-user-plus"></i></span>
            <span>Add Aftercare Patient</span>
        </a>
    </div>

    <div class="menu-section menu-section--footer">
        <a href="<?php echo ROOT; ?>/logout" class="menu-item menu-item--danger" style="text-decoration: none; display: block; text-align: left;">
            <span class="icon"><i class="fas fa-right-from-bracket"></i></span>
            <span>Logout</span>
        </a>
    </div>
</div>
