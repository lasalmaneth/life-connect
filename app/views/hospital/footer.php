    <script>
        
        function showServerMessage(message, type) {
                        const existingNotifications = document.querySelectorAll('.server-notification');
            existingNotifications.forEach(notification => notification.remove());
            
            const n = document.createElement('div');
            n.className = 'server-notification';
            n.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${type === 'success' ? 'linear-gradient(135deg, #10b981, #059669)' : 
                           type === 'error' ? 'linear-gradient(135deg, #ef4444, #dc2626)' : 
                           type === 'info' ? 'linear-gradient(135deg, #3b82f6, #2563eb)' : 
                           'linear-gradient(135deg, #f59e0b, #d97706)'};
                color: white;
                padding: 16px 24px;
                border-radius: 12px;
                box-shadow: 0 10px 25px rgba(0,0,0,0.2), 0 4px 12px rgba(0,0,0,0.1);
                z-index: 10000;
                font-weight: 600;
                font-size: 14px;
                max-width: 350px;
                word-wrap: break-word;
                transform: translateX(120%);
                transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                border: 1px solid rgba(255,255,255,0.2);
                backdrop-filter: blur(10px);
                cursor: pointer;
            `;
            
                        n.innerHTML = `
                <div style="display: flex; align-items: center; gap: 12px; position: relative;">
                    <div style="display: flex; align-items: center; gap: 8px; flex: 1;">
                        <span style="font-size: 18px; filter: drop-shadow(0 1px 2px rgba(0,0,0,0.3));">
                            ${type === 'success' ? '✅' : type === 'error' ? '❌' : type === 'info' ? 'ℹ️' : '⚠️'}
                        </span>
                        <span style="text-shadow: 0 1px 2px rgba(0,0,0,0.3);">${message}</span>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" 
                            style="background: rgba(255,255,255,0.2); border: none; color: white; 
                                   border-radius: 50%; width: 24px; height: 24px; cursor: pointer; 
                                   display: flex; align-items: center; justify-content: center; 
                                   font-size: 12px; font-weight: bold; transition: background 0.2s;">
                        ×
                    </button>
                </div>
            `;
            
            document.body.appendChild(n);
            
                        requestAnimationFrame(() => {
                n.style.transform = 'translateX(0)';
                n.style.opacity = '1';
            });
            
                        setTimeout(() => { 
                n.style.transform = 'translateX(120%)';
                n.style.opacity = '0';
                setTimeout(() => n.remove(), 400);
            }, 3000);
            
                        n.addEventListener('mouseenter', () => {
                n.style.transform = 'translateX(0) scale(1.02)';
                n.style.boxShadow = '0 15px 35px rgba(0,0,0,0.3), 0 6px 16px rgba(0,0,0,0.15)';
            });
            
            n.addEventListener('mouseleave', () => {
                n.style.transform = 'translateX(0) scale(1)';
                n.style.boxShadow = '0 10px 25px rgba(0,0,0,0.2), 0 4px 12px rgba(0,0,0,0.1)';
            });
        }

        function notify(message, type) {
            showServerMessage(message, type);
        }

                function toggleUserDropdown() {
            const dropdown = document.getElementById('user-dropdown');
            dropdown.classList.toggle('show');
        }

        function editProfile() {
                        const dropdown = document.getElementById('user-dropdown');
            if (dropdown) {
                dropdown.classList.remove('show');
            }
            
                        const modal = document.getElementById('edit-profile-modal');
            if (modal) {
                modal.classList.add('show');
            }
        }

        function logout() {
            if (confirm('Are you sure you want to logout?')) {
                showServerMessage('Logging out...', 'info');
                                if (document.getElementById('user-dropdown')) {
                    document.getElementById('user-dropdown').classList.remove('show');
                }
                
                                setTimeout(() => {
                    window.location.href = '<?php echo ROOT; ?>/logout';
                }, 500);
            }
        }
    </script>
