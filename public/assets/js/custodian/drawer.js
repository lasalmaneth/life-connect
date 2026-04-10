class Drawer {
    constructor(drawerId) {
        this.drawer = document.getElementById(drawerId);
        if (!this.drawer) return;
        
        this.overlay = document.createElement('div');
        this.overlay.className = 'cp-drawer-overlay';
        document.body.appendChild(this.overlay);

        this.closeBtn = this.drawer.querySelector('.cp-drawer__close');
        
        this.bindEvents();
    }

    bindEvents() {
        if (this.closeBtn) {
            this.closeBtn.addEventListener('click', () => this.close());
        }
        this.overlay.addEventListener('click', () => this.close());
        
        // Escape key to close
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.isOpen()) this.close();
        });
    }

    open() {
        this.overlay.classList.add('active');
        this.drawer.classList.add('active');
        document.body.style.overflow = 'hidden'; // Prevent background scrolling
    }

    close() {
        this.overlay.classList.remove('active');
        this.drawer.classList.remove('active');
        document.body.style.overflow = '';
    }

    isOpen() {
        return this.drawer.classList.contains('active');
    }
}

// Auto-initialize standard case drawer if it exists
document.addEventListener('DOMContentLoaded', () => {
    window.CaseDrawer = new Drawer('caseDetailsDrawer');
});
