<?php if(!defined('ROOT')) die(); ?>
</div><!-- /.d-shell -->

<!-- Mobile Sidebar Overlay (active when sidebar is open) -->
<div class="d-sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<!-- Donor Details Modal (Centered Popup) -->
<div id="donorDetailsModal" class="d-modal">
  <div class="d-modal__body" style="max-width: 650px;">
    <div class="d-modal__header">
      <h3 class="d-modal__title"><i class="fas fa-info-circle"></i> Donor Details</h3>
      <button onclick="closeModal('donorDetailsModal')" class="d-modal__close"><i class="fas fa-times"></i></button>
    </div>
    <div id="donorDetailsContent">
      <div class="d-empty">
        <div class="d-empty__icon"><i class="fas fa-user-circle"></i></div>
        <p>Select a record to view details</p>
      </div>
    </div>
  </div>
</div>

<script src="<?= ROOT ?>/public/assets/js/medicalschools/portal.js?v=<?= time() ?>"></script>
</body>
</html>
