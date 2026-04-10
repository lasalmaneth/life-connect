<?php
/**
 * Medical School Portal — Footer / Scripts Partial
 */
?>
<script>
/* ── Sidebar toggle ─────────────────────────────────────────── */
function toggleSidebar() {
    const sidebar = document.getElementById('cp-sidebar');
    const overlay = document.getElementById('cp-sidebar-overlay');
    if (!sidebar || !overlay) return;
    sidebar.classList.toggle('open');
    overlay.classList.toggle('active');
}
function closeSidebar() {
    const sidebar = document.getElementById('cp-sidebar');
    const overlay = document.getElementById('cp-sidebar-overlay');
    if (sidebar) sidebar.classList.remove('open');
    if (overlay) overlay.classList.remove('active');
}

/* ── Auto-dismiss flash popups ──────────────────────────────── */
document.querySelectorAll('.cp-popup.show').forEach(function(el) {
    setTimeout(function() { el.classList.remove('show'); }, 4000);
});
</script>

<!-- Side Drawer Placeholder for Details -->
<div class="cp-drawer" id="caseDetailsDrawer">
    <div class="cp-drawer__header">
        <h3 class="cp-drawer__title" id="drawerTitle">Case Details</h3>
        <button class="cp-drawer__close"><i class="fas fa-times"></i></button>
    </div>
    <div class="cp-drawer__body" id="drawerBody">
        <!-- Dynamic content via JS -->
        <div class="cp-loading">
            <i class="fas fa-circle-notch fa-spin"></i> Loading details...
        </div>
    </div>
</div>

<script src="<?= ROOT ?>/public/assets/js/custodian/drawer.js?v=<?= time() ?>"></script>
