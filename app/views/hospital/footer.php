    <script>

        // Hospital portal popups aligned to Donor portal style
        function hcEnsurePopupUI() {
            if (!document.getElementById('hc-popup-style')) {
                const style = document.createElement('style');
                style.id = 'hc-popup-style';
                style.textContent = `
                    /* Donor-style toast */
                    #hc-toast{position:fixed;top:5.25rem;right:1.5rem;z-index:3000;padding:.75rem 1.25rem;border-radius:10px;font-weight:600;font-size:.9rem;box-shadow:0 8px 24px rgba(0,0,0,.15);transform:translateY(-80px);opacity:0;transition:all .35s ease;max-width:320px;}
                    #hc-toast.show{transform:translateY(0);opacity:1;}
                    #hc-toast.toast-success{background:#16a34a;color:#fff;}
                    #hc-toast.toast-error{background:#dc2626;color:#fff;}
                    #hc-toast.toast-info{background:#2563eb;color:#fff;}
                    #hc-toast.toast-warning{background:#d97706;color:#fff;}

                    /* Donor-style dialog */
                    #hc-dialog{position:fixed;inset:0;z-index:3500;display:none;align-items:center;justify-content:center;background:rgba(15,23,42,.45);backdrop-filter:blur(6px);padding:1.25rem;}
                    #hc-dialog.show{display:flex;}
                    #hc-dialog .hc-card{width:min(520px,100%);background:#fff;border-radius:14px;box-shadow:0 24px 60px rgba(0,0,0,.25);border:1px solid rgba(2,6,23,.08);overflow:hidden;}
                    #hc-dialog .hc-head{padding:1rem 1.25rem;border-bottom:1px solid rgba(2,6,23,.08);display:flex;align-items:center;justify-content:space-between;gap:.75rem;}
                    #hc-dialog .hc-title{font-weight:800;font-size:1rem;color:#0f172a;}
                    #hc-dialog .hc-body{padding:1rem 1.25rem;color:#334155;font-weight:600;}
                    #hc-dialog .hc-body pre{margin:0;white-space:pre-wrap;word-break:break-word;font:inherit;}
                    #hc-dialog .hc-input{margin-top:.75rem;width:100%;padding:.7rem .8rem;border-radius:10px;border:1px solid rgba(2,6,23,.15);outline:none;font-weight:600;}
                    #hc-dialog .hc-actions{display:flex;justify-content:flex-end;gap:.6rem;padding:1rem 1.25rem;border-top:1px solid rgba(2,6,23,.08);}
                    #hc-dialog .hc-btn{border:none;border-radius:10px;padding:.65rem 1rem;font-weight:800;cursor:pointer;}
                    #hc-dialog .hc-btn-cancel{background:#f1f5f9;color:#0f172a;}
                    #hc-dialog .hc-btn-cancel:hover{background:#e2e8f0;}
                    #hc-dialog .hc-btn-ok{background:linear-gradient(135deg,#2563eb,#1d4ed8);color:#fff;}
                    #hc-dialog .hc-btn-ok:hover{opacity:.95;}
                    #hc-dialog .hc-btn-danger{background:linear-gradient(135deg,#dc2626,#b91c1c);color:#fff;}
                    #hc-dialog .hc-btn-danger:hover{opacity:.95;}
                `;
                document.head.appendChild(style);
            }
            if (!document.getElementById('hc-toast')) {
                const t = document.createElement('div');
                t.id = 'hc-toast';
                document.body.appendChild(t);
            }
            if (!document.getElementById('hc-dialog')) {
                const d = document.createElement('div');
                d.id = 'hc-dialog';
                d.innerHTML = `
                    <div class="hc-card" role="dialog" aria-modal="true">
                        <div class="hc-head">
                            <div class="hc-title" id="hcDialogTitle">Message</div>
                        </div>
                        <div class="hc-body"><pre id="hcDialogText"></pre><input id="hcDialogInput" class="hc-input" style="display:none" /></div>
                        <div class="hc-actions" id="hcDialogActions"></div>
                    </div>
                `;
                d.addEventListener('click', (e) => { if (e.target === d) hcCloseDialog(); });
                document.body.appendChild(d);
            }
        }

        function showServerMessage(message, type) {
            hcEnsurePopupUI();
            const t = document.getElementById('hc-toast');

            let cls = 'toast-info';
            let prefix = 'i ';
            if (type === 'success') { cls = 'toast-success'; prefix = '✓ '; }
            else if (type === 'error') { cls = 'toast-error'; prefix = '✕ '; }
            else if (type === 'warning') { cls = 'toast-warning'; prefix = '! '; }

            t.className = 'show ' + cls;
            t.innerText = prefix + String(message ?? '');
            if (window.__hcToastTimer) clearTimeout(window.__hcToastTimer);
            window.__hcToastTimer = setTimeout(() => { t.className = ''; }, 3500);
        }

        function hcCloseDialog() {
            const d = document.getElementById('hc-dialog');
            if (d) d.classList.remove('show');
        }

        function hcShowDialog({ title = 'Message', text = '', input = null, okText = 'OK', cancelText = null, danger = false } = {}) {
            hcEnsurePopupUI();
            const d = document.getElementById('hc-dialog');
            const titleEl = document.getElementById('hcDialogTitle');
            const textEl = document.getElementById('hcDialogText');
            const inputEl = document.getElementById('hcDialogInput');
            const actionsEl = document.getElementById('hcDialogActions');

            titleEl.textContent = title;
            textEl.textContent = String(text ?? '');
            actionsEl.innerHTML = '';

            if (input !== null) {
                inputEl.style.display = 'block';
                inputEl.type = 'text';
                inputEl.value = String(input.value ?? '');
                inputEl.placeholder = String(input.placeholder ?? '');
            } else {
                inputEl.style.display = 'none';
                inputEl.value = '';
            }

            return new Promise((resolve) => {
                const cleanup = () => {
                    document.removeEventListener('keydown', onKey);
                };
                const finish = (result) => {
                    cleanup();
                    hcCloseDialog();
                    resolve(result);
                };
                const onKey = (e) => {
                    if (!d.classList.contains('show')) return;
                    if (e.key === 'Escape') {
                        e.preventDefault();
                        finish(null);
                    }
                    if (e.key === 'Enter' && input !== null) {
                        e.preventDefault();
                        finish(String(inputEl.value ?? ''));
                    }
                };
                document.addEventListener('keydown', onKey);

                if (cancelText) {
                    const btnCancel = document.createElement('button');
                    btnCancel.className = 'hc-btn hc-btn-cancel';
                    btnCancel.type = 'button';
                    btnCancel.textContent = cancelText;
                    btnCancel.onclick = () => finish(null);
                    actionsEl.appendChild(btnCancel);
                }

                const btnOk = document.createElement('button');
                btnOk.className = 'hc-btn ' + (danger ? 'hc-btn-danger' : 'hc-btn-ok');
                btnOk.type = 'button';
                btnOk.textContent = okText;
                btnOk.onclick = () => {
                    if (input !== null) return finish(String(inputEl.value ?? ''));
                    finish(true);
                };
                actionsEl.appendChild(btnOk);

                d.classList.add('show');
                setTimeout(() => {
                    if (input !== null) inputEl.focus();
                    else btnOk.focus();
                }, 0);
            });
        }

        function hcAlert(text, type = 'info') {
            const title = type === 'error' ? 'Error' : type === 'success' ? 'Success' : 'Message';
            return hcShowDialog({ title, text, okText: 'OK' });
        }
        function hcConfirm(text, { danger = false } = {}) {
            return hcShowDialog({ title: 'Confirm', text, okText: danger ? 'Confirm' : 'OK', cancelText: 'Cancel', danger }).then(v => v === true);
        }
        function hcPrompt(text, { placeholder = '', defaultValue = '' } = {}) {
            return hcShowDialog({ title: 'Input Required', text, input: { placeholder, value: defaultValue }, okText: 'Submit', cancelText: 'Cancel' });
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
            hcConfirm('Are you sure you want to logout?', { danger: true }).then((ok) => {
                if (!ok) return;
                showServerMessage('Logging out...', 'info');
                if (document.getElementById('user-dropdown')) {
                    document.getElementById('user-dropdown').classList.remove('show');
                }
                setTimeout(() => {
                    window.location.href = '<?php echo ROOT; ?>/logout';
                }, 500);
            });
        }
    </script>

    <?php
        // Show one-time server flash messages using the hospital toast.
        $flashTypes = [
            'flash_error' => 'error',
            'flash_success' => 'success',
            'flash_warning' => 'warning',
            'flash_info' => 'info',
        ];

        foreach ($flashTypes as $key => $type) {
            if (!empty($_SESSION[$key])) {
                $msg = (string)$_SESSION[$key];
                unset($_SESSION[$key]);
                echo '<script>showServerMessage(' . json_encode($msg) . ',' . json_encode($type) . ');</script>';
            }
        }
    ?>

    <!-- Footer -->
    <footer
        style="background: linear-gradient(135deg, #005baa 0%, #003b6e 100%); color: white; text-align: center; padding: 20px; margin-top: 40px; box-shadow: 0 -4px 20px rgba(0, 91, 170, 0.2);">
        <p style="margin: 0; font-size: 14px;">Copyright © 2026 Ministry of Health - LifeConnect Sri Lanka</p>
    </footer>
</body>

</html>
