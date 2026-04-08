<?php
/**
 * NON-DONOR COMMON FUNCTIONS & DATA
 * Handles: Session, Auth, Database, POST requests
 */

// Prevent direct access
if (!defined('NONDONOR_PAGE')) {
    die('Direct access not permitted');
}

// Session & Authentication
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Handle AJAX POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "life-connect";
    
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'User not authenticated']);
        exit();
    }
    
    $user_id = $_SESSION['user_id'];
    
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Save Settings
        if (isset($_POST['action']) && $_POST['action'] === 'save_settings') {
            $name = trim($_POST['name']);
            $email = trim($_POST['email']);
            
            if (empty($name) || empty($email)) {
                echo json_encode(['success' => false, 'message' => 'Name and email are required']);
                exit();
            }
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo json_encode(['success' => false, 'message' => 'Invalid email format']);
                exit();
            }
            
            $stmt = $conn->prepare("UPDATE users SET user_name = :name, updated_at = NOW() WHERE user_id = :user_id");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            
            $_SESSION['user_name'] = $name;
            
            echo json_encode(['success' => true, 'message' => 'Settings saved successfully']);
            exit();
        }
        
        // Withdraw Profile
        if (isset($_POST['action']) && $_POST['action'] === 'withdraw_profile') {
            $conn->beginTransaction();
            // Get all donor_ids for this user
            $stmt = $conn->prepare("SELECT donor_id FROM donors WHERE user_id = :user_id");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            $donor_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);
            // Delete related donor_organ records
            if (!empty($donor_ids)) {
                $in = str_repeat('?,', count($donor_ids) - 1) . '?';
                $stmt = $conn->prepare("DELETE FROM donor_organ WHERE donor_id IN ($in)");
                $stmt->execute($donor_ids);
            }
            // Delete donors for this user
            $stmt = $conn->prepare("DELETE FROM donors WHERE user_id = :user_id");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            // Now delete user
            $stmt = $conn->prepare("DELETE FROM users WHERE user_id = :user_id");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            $conn->commit();
            session_destroy();
            echo json_encode(['success' => true]);
            exit();
        }
        
    } catch(PDOException $e) {
        if (isset($conn) && $conn->inTransaction()) {
            $conn->rollBack();
        }
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        exit();
    }
}

// Handle Logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    $_SESSION = array();
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/');
    }
    session_destroy();
    header('Location: /life-connect/');
    exit();
}

// Check Authentication
if (!isset($_SESSION['user_id'])) {
    header('Location: /life-connect/login');
    exit();
}

// User Data
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'User';
$user_email = isset($_SESSION['email']) ? $_SESSION['email'] : 'user@example.com';

// Fetch Success Stories for Dashboard
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "life-connect";

$stories = array();
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $conn->prepare("SELECT * FROM success_stories ORDER BY success_date DESC LIMIT 6");
    $stmt->execute();
    $stories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $stories = array();
}

// Function to render header (ORIGINAL HEADER DESIGN)
function renderHeader($user_name, $user_email) {
?>
    <!-- HEADER -->
    <div class="header">
        <div class="header-content">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <a href="/life-connect/app/views/Non%20donor/index.view.php" style="text-decoration: none; display: flex; align-items: center;">
                    <div style="background: white; border-radius: 50%; padding: 8px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);">
                        <img src="/life-connect/public/assets/images/logo.png" alt="Life Connect Logo" style="height: 50px; width: auto;">
                    </div>
                </a>
                <div>
                    <h1>Non-Donor Portal</h1>
                    <p>Explore resources and learn more about organ donation</p>
                </div>
            </div>

            <div class="user-info" onclick="toggleUserDropdown()">
                <div class="user-avatar"><?php echo strtoupper(substr($user_name, 0, 1)); ?></div>
                <div class="user-details">
                    <div style="font-weight: 600; font-size: 0.9rem;"><?php echo htmlspecialchars($user_name); ?></div>
                    <div style="font-size: 0.8rem; opacity: 0.8;">Non-Donor</div>
                    <div style="font-size: 0.7rem; opacity: 0.6;">&nbsp;</div>
                </div>
                <div class="user-actions">
                    <button class="btn-logout" onclick="confirmLogout()" title="Logout">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16,17 21,12 16,7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                    </button>
                </div>

                <div class="user-dropdown" id="userDropdown">
                    <div class="dropdown-header">
                        <div class="user-avatar-large"><?php echo strtoupper(substr($user_name, 0, 1)); ?></div>
                        <div>
                            <div class="user-name"><?php echo htmlspecialchars($user_name); ?></div>
                            <div class="user-role">Non-Donor</div>
                        </div>
                    </div>
                    <div class="dropdown-content">
                        <div class="detail-item">
                            <span class="detail-label">Email:</span>
                            <span class="detail-value"><?php echo htmlspecialchars($user_email); ?></span>
                        </div>
                    </div>
                    <div class="dropdown-footer">
                        <button class="btn btn-secondary btn-small" onclick="openSettingsModal(); document.getElementById('userDropdown').classList.remove('show');">Settings</button>
                        <button class="btn btn-warning btn-small" onclick="openWithdrawModal(); document.getElementById('userDropdown').classList.remove('show');">Withdraw</button>
                        <button class="btn btn-danger btn-small" onclick="confirmLogout(); document.getElementById('userDropdown').classList.remove('show');">Logout</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
}

// Function to render sidebar
function renderSidebar($currentPage = 'index') {
?>
    <!-- SIDEBAR -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h3>Account</h3>
            <p id="sidebarDesc">Your portal</p>
        </div>

        <div class="menu-section">
            <div class="menu-section-title">Overview</div>
            <a href="/life-connect/app/views/Non%20donor/index.view.php" class="menu-item <?php echo $currentPage === 'index' ? 'active' : ''; ?>">
                <span class="icon"><i class="fas fa-home"></i></span>
                <span class="label">Dashboard</span>
            </a>
        </div>

        <div class="menu-section">
            <div class="menu-section-title">Profile</div>
            <a href="/life-connect/app/views/Non%20donor/become-donor.view.php" class="menu-item <?php echo $currentPage === 'become-donor' ? 'active' : ''; ?>">
                <span class="icon"><i class="fas fa-heart"></i></span>
                <span class="label">Become a Donor</span>
            </a>
            <a href="#" class="menu-item" onclick="openWithdrawModal(); return false;">
                <span class="icon"><i class="fas fa-user-slash"></i></span>
                <span class="label">Withdraw Profile</span>
            </a>
        </div>

        <div class="menu-section">
            <div class="menu-section-title">Support</div>
            <a href="#" class="menu-item" onclick="openSettingsModal(); return false;">
                <span class="icon"><i class="fas fa-cog"></i></span>
                <span class="label">Settings</span>
            </a>
            <a href="/life-connect/help" class="menu-item">
                <span class="icon"><i class="fas fa-question-circle"></i></span>
                <span class="label">Help</span>
            </a>
            <a href="#" class="menu-item" onclick="confirmLogout(); return false;">
                <span class="icon"><i class="fas fa-sign-out-alt"></i></span>
                <span class="label">Logout</span>
            </a>
        </div>
    </div>
<?php
}

// Function to render modals
function renderModals($user_name, $user_email) {
?>
    <!-- SETTINGS MODAL -->
    <div id="settingsModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>⚙️ Settings</h3>
                <button class="close-btn" onclick="closeSettingsModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div class="settings-section">
                    <h4>Personal Information</h4>
                    <div class="form-group">
                        <label>Name:</label>
                        <input type="text" id="settingsName" value="<?php echo htmlspecialchars($user_name); ?>" class="form-input">
                    </div>
                    <div class="form-group">
                        <label>Email:</label>
                        <input type="email" id="settingsEmail" value="<?php echo htmlspecialchars($user_email); ?>" class="form-input">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" onclick="saveSettings()">Save</button>
                <button class="btn btn-secondary" onclick="closeSettingsModal()">Cancel</button>
            </div>
        </div>
    </div>

    <!-- WITHDRAW MODAL -->
    <div id="withdrawModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Withdraw Profile</h3>
                <button class="close-btn" onclick="closeWithdrawModal()">&times;</button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to withdraw your non-donor profile? Your opt-out declaration will be removed from our system.</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" onclick="withdrawProfile()">Yes, Withdraw</button>
                <button class="btn btn-secondary" onclick="closeWithdrawModal()">Cancel</button>
            </div>
        </div>
    </div>
<?php
}

// Function to render common scripts
function renderScripts() {
?>
    <script>
        // User Dropdown
        function toggleUserDropdown() {
            const dropdown = document.getElementById('userDropdown');
            if (dropdown) {
                dropdown.classList.toggle('show');
            }
        }
        
        function closeUserDropdown() {
            const dropdown = document.getElementById('userDropdown');
            if (dropdown) {
                dropdown.classList.remove('show');
            }
        }
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const userInfo = document.querySelector('.user-info');
            const dropdown = document.getElementById('userDropdown');
            if (!userInfo || !dropdown) return;
            if (!userInfo.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.remove('show');
            }
        });
        
        // Modals
        function openSettingsModal() {
            document.getElementById('settingsModal').classList.add('active');
        }
        
        function closeSettingsModal() {
            document.getElementById('settingsModal').classList.remove('active');
        }
        
        function openWithdrawModal() {
            document.getElementById('withdrawModal').classList.add('active');
        }
        
        function closeWithdrawModal() {
            document.getElementById('withdrawModal').classList.remove('active');
        }
        
        // Save Settings
        function saveSettings() {
            const name = document.getElementById('settingsName').value.trim();
            const email = document.getElementById('settingsEmail').value.trim();
            
            if (!name || !email) {
                alert('Please fill in all fields');
                return;
            }
            
            fetch('', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `action=save_settings&name=${encodeURIComponent(name)}&email=${encodeURIComponent(email)}`
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert('Settings saved successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(err => alert('Error saving settings'));
        }
        
        // Withdraw Profile
        function withdrawProfile() {
            fetch('', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'action=withdraw_profile'
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert('Profile withdrawn successfully');
                    window.location.href = '/life-connect/app/views/home.view.php';
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(err => alert('Error withdrawing profile'));
        }
        
        // Logout
        function confirmLogout() {
            if (confirm('Are you sure you want to logout?')) {
                window.location.href = '?action=logout';
            }
        }
    </script>
<?php
}
?>