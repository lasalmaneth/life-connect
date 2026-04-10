<div class="sidebar">
    <div class="sidebar-header">
        <h3>Hospital Portal</h3>
        <p>Clinical coordination</p>
    </div>

    <div class="menu-section">
        <div class="menu-section-title">NAVIGATION</div>
        <a href="<?php echo ROOT; ?>/hospital" class="menu-item <?php echo ($current_page === 'overview') ? 'active' : ''; ?>">
            <span class="icon"><i class="fas fa-chart-line"></i></span>
            <span>Main Dashboard</span>
        </a>
        <a href="<?php echo ROOT; ?>/hospital/organ-requests" class="menu-item <?php echo ($current_page === 'organ-requests') ? 'active' : ''; ?>">
            <span class="icon"><i class="fas fa-heart"></i></span>
            <span>Organ Requests</span>
        </a>
        <a href="<?php echo ROOT; ?>/hospital/eligibility" class="menu-item <?php echo ($current_page === 'eligibility') ? 'active' : ''; ?>">
            <span class="icon"><i class="fas fa-check-circle"></i></span>
            <span>Update Eligibility</span>
        </a>
        <a href="<?php echo ROOT; ?>/hospital/recipients" class="menu-item <?php echo ($current_page === 'recipients') ? 'active' : ''; ?>">
            <span class="icon"><i class="fas fa-user"></i></span>
            <span>Recipient Patients</span>
        </a>
        <a href="<?php echo ROOT; ?>/hospital/stories" class="menu-item <?php echo ($current_page === 'stories') ? 'active' : ''; ?>">
            <span class="icon"><i class="fas fa-star"></i></span>
            <span>Success Stories</span>
        </a>
        <a href="<?php echo ROOT; ?>/hospital/lab-reports" class="menu-item <?php echo ($current_page === 'lab-reports') ? 'active' : ''; ?>">
            <span class="icon"><i class="fas fa-calendar-alt"></i></span>
            <span>Upcoming Appointments</span>
        </a>
        
        <div class="menu-section-title" style="margin-top: 1.5rem;">AFTERCARE</div>
        <a href="<?php echo ROOT; ?>/hospital/addpatient" class="menu-item <?php echo ($current_page === 'addpatient') ? 'active' : ''; ?>">
            <span class="icon"><i class="fas fa-hand-holding-medical"></i></span>
            <span>Add Aftercare Patient</span>
        </a>
    </div>

    <div class="menu-section menu-section--footer">
        <a href="<?php echo ROOT; ?>/logout" class="menu-item menu-item--danger">
            <span class="icon"><i class="fas fa-right-from-bracket"></i></span>
            <span>Logout</span>
        </a>
    </div>
</div>
