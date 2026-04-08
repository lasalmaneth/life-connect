/**
 * LifeConnect — Custodian Portal JavaScript
 * UI interactions ONLY. No fake API calls, no workflow state simulation.
 * All data comes from the server via real page renders or real API calls.
 */

/* =============================================================================
   1. SIDEBAR TOGGLE (Mobile)
   ============================================================================= */
function toggleSidebar() {
    const sidebar  = document.getElementById('cp-sidebar');
    const overlay  = document.getElementById('cp-sidebar-overlay');
    if (!sidebar) return;
    sidebar.classList.toggle('active');
    overlay.classList.toggle('active');
    document.body.style.overflow = sidebar.classList.contains('active') ? 'hidden' : '';
}

function closeSidebar() {
    const sidebar = document.getElementById('cp-sidebar');
    const overlay = document.getElementById('cp-sidebar-overlay');
    if (!sidebar) return;
    sidebar.classList.remove('active');
    overlay.classList.remove('active');
    document.body.style.overflow = '';
}

/* =============================================================================
   2. POPUP NOTIFICATION (toast)
   ============================================================================= */
function showToast(message, type) {
    type = type || 'success';
    const popup = document.getElementById('cp-popup');
    if (!popup) return;
    const iconEl   = popup.querySelector('.cp-popup__icon');
    const titleEl  = popup.querySelector('.cp-popup__title');
    const msgEl    = popup.querySelector('.cp-popup__msg');

    const config = {
        success: { icon: 'fa-check',           title: 'Success',  bg: '#10b981' },
        error:   { icon: 'fa-exclamation',      title: 'Error',    bg: '#ef4444' },
        info:    { icon: 'fa-info',             title: 'Info',     bg: '#005baa' },
        warning: { icon: 'fa-triangle-exclamation', title: 'Warning', bg: '#f59e0b' },
    };

    const c = config[type] || config.success;
    if (iconEl)  { iconEl.className = `cp-popup__icon fas ${c.icon}`; iconEl.style.background = c.bg; }
    if (titleEl) titleEl.textContent = c.title;
    if (msgEl)   msgEl.textContent   = message;

    popup.classList.add('show');
    setTimeout(hideToast, 4000);
}

function hideToast() {
    const popup = document.getElementById('cp-popup');
    if (popup) popup.classList.remove('show');
}

/* =============================================================================
   3. MODALS
   ============================================================================= */
function openModal(id) {
    const modal = document.getElementById(id);
    if (modal) { modal.classList.add('active'); document.body.style.overflow = 'hidden'; }
}

function closeModal(id) {
    const modal = document.getElementById(id);
    if (modal) { modal.classList.remove('active'); document.body.style.overflow = ''; }
}

// Close modal on backdrop click
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('cp-modal')) {
        e.target.classList.remove('active');
        document.body.style.overflow = '';
    }
});

/* =============================================================================
   4. KEYBOARD: ESC closes modals / sidebar
   ============================================================================= */
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('.cp-modal.active').forEach(function(m) {
            m.classList.remove('active');
        });
        closeSidebar();
        document.body.style.overflow = '';
    }
});

/* =============================================================================
   5. AUTO-DISMISS SERVER FLASH MESSAGES
   ============================================================================= */
document.addEventListener('DOMContentLoaded', function() {
    const flash = document.querySelectorAll('.cp-flash-message');
    flash.forEach(function(el) {
        setTimeout(function() {
            el.style.opacity = '0';
            el.style.transform = 'translateX(110%)';
            setTimeout(function() { el.remove(); }, 350);
        }, 4500);
    });
});
