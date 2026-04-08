<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/life-connect/public/assets/css/style.css">
    <link rel="stylesheet" href="/life-connect/public/assets/css/admin/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <title>Admin Profile | LifeConnect</title>
    <style>
        body {
            background-color: #f8fafc;
            min-height: 100vh;
        }
        
        .profile-container {
            max-width: 800px;
            margin: 0 auto;
        }

        .profile-header {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            padding: 2rem;
            border-bottom: 1px solid rgba(0, 91, 170, 0.1);
            border-radius: 12px 12px 0 0;
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .profile-avatar-large {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            font-size: 2.5rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            box-shadow: 0 4px 15px rgba(0, 91, 170, 0.2);
        }

        .profile-info h2 {
            margin-bottom: 0.25rem;
            color: var(--primary-text-color);
        }

        .profile-info p {
            color: var(--secondary-text-color);
            opacity: 0.8;
        }

        .profile-card {
            background: var(--white-color);
            border-radius: 12px;
            border: 1px solid rgba(0, 91, 170, 0.1);
            box-shadow: 0 4px 20px rgba(0, 91, 170, 0.05);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .profile-body {
            padding: 2rem;
        }

        .section-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--primary-text-color);
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid rgba(0, 91, 170, 0.1);
        }

        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-weight: 500;
        }

        .alert-success {
            background-color: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .alert-error {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="header-content">
            <div>
                <h1>LifeConnect Admin Dashboard</h1>
                <p>Healthcare Management System - User Administration</p>
            </div>
            <div style="display: flex; align-items: center;">
                <nav class="header-nav">
                    <a href="<?= ROOT ?>" class="nav-link">
                        <i class="fa-solid fa-house"></i> Home
                    </a>
                    <a href="<?= ROOT ?>/user-admin/profile" class="nav-link">
                        <i class="fa-solid fa-user-circle"></i> Profile
                    </a>
                </nav>
                <div class="user-info">
                    <div class="user-avatar"><?= strtoupper(substr($user->username ?? 'A', 0, 1)) ?></div>
                    <div>
                        <div style="font-weight: 600; font-size: 0.9rem;"><?= htmlspecialchars($user->username ?? 'Admin') ?></div>
                        <div style="font-size: 0.8rem; opacity: 0.8;"><?= ucfirst($user->role ?? 'Administrator') ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="main-content">
            <div class="sidebar">
                <div class="sidebar-header">
                    <h3>System Management</h3>
                    <p>Administrative Dashboard</p>
                </div>
                
                <div class="menu-section">
                    <div class="menu-section-title">Overview</div>
                    <a href="<?= ROOT ?>/user-admin" class="menu-item">
                        <span class="icon"><i class="fa-solid fa-house"></i></span>
                        <span>Dashboard Overview</span>
                    </a>
                </div>

                <div class="menu-section">
                    <div class="menu-section-title">User Management</div>
                    
                    <a href="<?= ROOT ?>/user-admin#accounts" class="menu-item">
                        <span class="icon"><i class="fa-solid fa-circle-user"></i></span>
                        <span>User Accounts</span>
                    </a>
                    
                    <a href="<?= ROOT ?>/user-admin#documents" class="menu-item">
                        <span class="icon"><i class="fa-solid fa-file"></i></span>
                        <span>Document Verification</span>
                    </a>
                    
                    <a href="<?= ROOT ?>/user-admin#notifications" class="menu-item">
                        <span class="icon"><i class="fa-solid fa-at"></i></span>
                        <span>User Notifications</span>
                    </a>
                    
                    <a href="<?= ROOT ?>/user-admin#eligibility" class="menu-item">
                        <span class="icon"><i class="fa-solid fa-heart-circle-check"></i></span>
                        <span>Donor Eligibility</span>
                    </a>
                    
                    <a href="<?= ROOT ?>/user-admin#nic-validation" class="menu-item">
                        <span class="icon"><i class="fa-solid fa-address-card"></i></span>
                        <span>NIC Validation</span>
                    </a>

                    <a href="<?= ROOT ?>/user-admin#feedbacks" class="menu-item">
                        <span class="icon"><i class="fa-solid fa-comment"></i></span>
                        <span>Feedbacks</span>
                    </a>
                </div>

                <div class="menu-section">
                    <div class="menu-section-title">Account</div>
                    <a href="<?= ROOT ?>/logout" class="menu-item" style="color: var(--danger-color);">
                        <span class="icon"><i class="fa-solid fa-sign-out-alt"></i></span>
                        <span>Logout</span>
                    </a>
                </div>
            </div>

            <div class="content-area">
                <div class="content-body">
                    <div class="profile-container">
                        
                        <?php if (!empty($message)): ?>
                            <div class="alert alert-<?= $messageType === 'success' ? 'success' : 'error' ?>">
                                <?= htmlspecialchars($message) ?>
                            </div>
                        <?php endif; ?>

                        <div class="profile-card">
                            <div class="profile-header">
                                <div class="profile-avatar-large">
                                    <?= strtoupper(substr($user->username ?? 'A', 0, 1)) ?>
                                </div>
                                <div class="profile-info">
                                    <h2><?= htmlspecialchars($user->username ?? 'Admin User') ?></h2>
                                    <p><?= htmlspecialchars($user->email ?? 'No email set') ?></p>
                                    <span class="status-badge status-active" style="margin-top: 0.5rem;"><?= ucfirst($user->role ?? 'Admin') ?></span>
                                </div>
                            </div>
                            
                            <div class="profile-body">
                                <form method="POST" action="">
                                    <div class="section-title">Profile Information</div>
                                    
                                    <div class="form-group">
                                        <label class="form-label">Username</label>
                                        <input type="text" name="username" class="form-input" value="<?= htmlspecialchars($user->username ?? '') ?>" required>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Email Address</label>
                                        <input type="email" class="form-input" value="<?= htmlspecialchars($user->email ?? '') ?>" disabled style="background: #f1f5f9; cursor: not-allowed;">
                                        <small style="color: var(--secondary-text-color); opacity: 0.8;">Email cannot be changed directly. Contact system support.</small>
                                    </div>

                                    <div class="section-title" style="margin-top: 2rem;">Security Settings</div>
                                    
                                    <div class="form-group">
                                        <label class="form-label">Current Password</label>
                                        <input type="password" name="current_password" class="form-input" placeholder="Enter current password to save changes">
                                    </div>

                                    <div class="feature-grid" style="margin-top: 1rem; margin-bottom: 1rem; grid-template-columns: 1fr 1fr; gap: 1rem;">
                                        <div class="form-group" style="margin-bottom: 0;">
                                            <label class="form-label">New Password</label>
                                            <input type="password" name="new_password" class="form-input" placeholder="Leave blank to keep current">
                                        </div>
                                        <div class="form-group" style="margin-bottom: 0;">
                                            <label class="form-label">Confirm New Password</label>
                                            <input type="password" name="confirm_password" class="form-input" placeholder="Confirm new password">
                                        </div>
                                    </div>

                                    <div class="action-buttons" style="margin-top: 2rem; justify-content: flex-end;">
                                        <button type="button" onclick="window.location.href='<?= ROOT ?>/user-admin'" class="btn btn-secondary">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const usernameInput = document.querySelector('input[name="username"]');
            const submitBtn = document.querySelector('.btn-primary');
            let originalUsername = usernameInput.value;
            
            // Create feedback element
            const feedback = document.createElement('small');
            feedback.style.display = 'block';
            feedback.style.marginTop = '0.5rem';
            feedback.style.fontWeight = '500';
            usernameInput.parentNode.appendChild(feedback);

            let timeout = null;

            usernameInput.addEventListener('input', function() {
                const username = this.value.trim();
                
                // Reset state
                feedback.textContent = '';
                feedback.style.color = '';
                submitBtn.disabled = false;
                this.style.borderColor = '';

                if (username === originalUsername) {
                    return;
                }

                if (username.length < 3) {
                    feedback.textContent = 'Username must be at least 3 characters.';
                    feedback.style.color = 'var(--danger-color)';
                    return;
                }

                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    checkUsername(username);
                }, 500);
            });

            async function checkUsername(username) {
                try {
                    feedback.textContent = 'Checking availability...';
                    feedback.style.color = 'var(--secondary-text-color)';
                    
                    const response = await fetch(`/life-connect/public/user-admin/checkUsername?username=${encodeURIComponent(username)}`);
                    const data = await response.json();

                    if (data.success && data.exists) {
                        feedback.textContent = 'Username is already taken.';
                        feedback.style.color = 'var(--danger-color)';
                        usernameInput.style.borderColor = 'var(--danger-color)';
                        submitBtn.disabled = true;
                    } else if (data.success) {
                        feedback.textContent = 'Username is available.';
                        feedback.style.color = 'var(--success-color)';
                        usernameInput.style.borderColor = 'var(--success-color)';
                        submitBtn.disabled = false;
                    }
                } catch (error) {
                    console.error('Error checking username:', error);
                }
            }
        });
    </script>
</body>
</html>
