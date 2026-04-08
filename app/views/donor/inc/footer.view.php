<?php if(!defined('ROOT')) die(); ?>
</div><!-- /.d-shell -->

<!-- Mobile Sidebar Overlay (active when sidebar is open) -->
<div class="d-sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<!-- Logout Modal -->
<div id="logoutModal" class="d-modal">
    <div class="d-modal__body" style="max-width: 400px; text-align: center;">
        <h3 class="d-modal__title" style="margin-bottom: 1rem;"><i class="fas fa-sign-out-alt"></i> Confirm Logout</h3>
        <p style="color: var(--g500); margin-bottom: 2rem;">Are you sure you want to logout? You will need to login again to access your dashboard.</p>
        <div style="display: flex; gap: 1rem; justify-content: center;">
            <button class="d-btn d-btn--outline" onclick="closeLogoutModal()">Cancel</button>
            <button class="d-btn d-btn--danger" onclick="window.location.href='<?= ROOT ?>/logout'">Logout</button>
        </div>
    </div>
</div>

<script>
    function toggleSidebar() {
        document.querySelector('.d-sidebar').classList.toggle('active');
        document.getElementById('sidebarOverlay').classList.toggle('active');
    }
    
    function openLogoutModal() {
        document.getElementById('logoutModal').classList.add('active');
    }
    
    function closeLogoutModal() {
        document.getElementById('logoutModal').classList.remove('active');
    }
    
    // Auto-hide alerts/notifications
    document.addEventListener("DOMContentLoaded", () => {
        const alerts = document.querySelectorAll('.d-alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.opacity = '0';
                alert.style.transition = 'opacity 0.5s ease';
                setTimeout(() => alert.remove(), 500);
            }, 4000);
        });
    });
</script>

<!-- Global scripts -->
<?php if (!empty($page_js)): foreach ($page_js as $js): ?>
<script src="<?= $js ?>?v=<?= time() ?>"></script>
<?php endforeach; endif; ?>

</body>
</html>
