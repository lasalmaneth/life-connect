<div class="sidebar">
    <div class="sidebar-header">
        <h3>Hospital Portal</h3>
        <p>Aftercare Management</p>
    </div>

    <div class="menu-section">
        <div class="menu-section-title">NAVIGATION</div>
        <a href="<?php echo ROOT; ?>/hospital" class="menu-item" style="text-decoration:none; color: inherit; display: flex; text-align: left;">
            <span class="icon"><i class="fas fa-arrow-left"></i></span>
            <span>Back to Dashboard</span>
        </a>
        <a href="<?php echo ROOT; ?>/hospital/aftercare-recipients" class="menu-item <?php echo (strpos($_SERVER['REQUEST_URI'], 'aftercare-recipients') !== false) ? 'active' : ''; ?>" style="text-decoration:none; color: inherit; display: flex; text-align: left;">
            <span class="icon"><i class="fas fa-users"></i></span>
            <span>Recipient Patients</span>
        </a>
        <a href="<?php echo ROOT; ?>/hospital/addpatient" class="menu-item <?php echo (strpos($_SERVER['REQUEST_URI'], 'addpatient') !== false) ? 'active' : ''; ?>" style="text-decoration:none; color: inherit; display: flex; text-align: left;">
            <span class="icon"><i class="fas fa-user-plus"></i></span>
            <span>Add Patient Access</span>
        </a>
    </div>

    <div class="menu-section menu-section--footer">
        <a href="<?php echo ROOT; ?>/logout" class="menu-item menu-item--danger" style="text-decoration: none; display: block; text-align: left;">
            <span class="icon"><i class="fas fa-right-from-bracket"></i></span>
            <span>Logout</span>
        </a>
    </div>
</div>
