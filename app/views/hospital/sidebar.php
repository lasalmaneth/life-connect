<div class="sidebar">
    <div class="sidebar-header">
        <h3>Hospital Portal</h3>
        <p>Clinical coordination</p>
    </div>

    <div class="menu-section">
        <div class="menu-section-title">NAVIGATION</div>
        <a href="<?php echo ROOT; ?>/hospital" class="menu-item <?php echo ($current_page === 'overview') ? 'active' : ''; ?>">
            <span class="icon"></span>
            <span>Main Dashboard</span>
        </a>
        <a href="<?php echo ROOT; ?>/hospital/organ-requests" class="menu-item <?php echo ($current_page === 'organ-requests') ? 'active' : ''; ?>">
            <span class="icon"></span>
            <span>Organ Requests</span>
        </a>
        <a href="<?php echo ROOT; ?>/hospital/eligibility" class="menu-item <?php echo ($current_page === 'eligibility') ? 'active' : ''; ?>">
            <span class="icon"></span>
            <span>Update Eligibility</span>
        </a>
        <a href="<?php echo ROOT; ?>/hospital/recipients" class="menu-item <?php echo ($current_page === 'recipients') ? 'active' : ''; ?>">
            <span class="icon"></span>
            <span>Recipient Patients</span>
        </a>
        <a href="<?php echo ROOT; ?>/hospital/stories" class="menu-item <?php echo ($current_page === 'stories') ? 'active' : ''; ?>">
            <span class="icon"></span>
            <span>Success Stories</span>
        </a>
        <a href="<?php echo ROOT; ?>/hospital/lab-reports" class="menu-item <?php echo ($current_page === 'lab-reports') ? 'active' : ''; ?>">
            <span class="icon"></span>
            <span>Upcoming Appointments</span>
        </a>
        
        <div class="menu-section-title" style="margin-top: 1.5rem;">AFTERCARE</div>
        <a href="<?php echo ROOT; ?>/hospital/addpatient" class="menu-item <?php echo ($current_page === 'addpatient') ? 'active' : ''; ?>">
            <span class="icon"></span>
            <span>Add Aftercare Patient</span>
        </a>
    </div>
</div>
