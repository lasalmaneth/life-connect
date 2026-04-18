<?php
// Database connection and operations
$message = '';
$message_type = '';

// Handle form submissions (for non-AJAX operations)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle bulk delete
    if (isset($_POST['bulk_delete']) && isset($_POST['tribute_ids'])) {
        $ids = $_POST['tribute_ids'];
        
        // Use controller for database operations
        require_once __DIR__ . '/../../../core/config.php';
$pdo = new PDO("mysql:host=".DBHOST.";dbname=".DBNAME, DBUSER, DBPASS);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $query = "DELETE FROM tribute WHERE tribute_id IN ($placeholders)";
        $stm = $pdo->prepare($query);
        
        if ($stm->execute($ids)) {
            $message = count($ids) . ' tribute(s) deleted successfully';
            $message_type = 'success';
        } else {
            $message = 'Error deleting tributes';
            $message_type = 'error';
        }
        
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
    
    // Handle single delete
if (isset($_POST['delete_tribute'])) {
    $tribute_id = $_POST['tribute_id'];
    
    require_once __DIR__ . '/../../../core/config.php';
    $pdo = new PDO("mysql:host=".DBHOST.";dbname=".DBNAME, DBUSER, DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $query = "DELETE FROM tribute WHERE tribute_id = ?";
    $stm = $pdo->prepare($query);
    
    if ($stm->execute(array($tribute_id))) {
        $message = 'Tribute deleted successfully';
        $message_type = 'success';
    } else {
        $message = 'Error deleting tribute';
        $message_type = 'error';
    }
    
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/life-connect/public/assets/css/style.css">
    <link rel="stylesheet" href="/life-connect/public/assets/css/admin/style.css">
    <link rel="stylesheet" href="/life-connect/public/assets/css/admin/donation-style.css">
    <link rel="stylesheet" href="/life-connect/public/assets/css/fontawesome.min.css?v=<?= time() ?>">
    <title>Tributes Management | LifeConnect</title>
    <style>
        .modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    overflow: auto;
}

.modal-content {
    position: relative;
    margin: 5% auto;
    animation: modalSlideIn 0.3s ease-out;
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: translateY(-50px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
        </style>
</head>
<body>
    
<script src="/life-connect/public/assets/js/admin/tributes.js"></script>
    <div class="header">
        <div class="header-content">
            <div>
                <h1>LifeConnect Admin Dashboard</h1>
                <p>Organ Management System - Tributes Management</p>
            </div>
            <div class="user-info">
                <div class="user-avatar">A</div>
                <div>
                    <div style="font-weight: 600; font-size: 0.9rem;">Admin User</div>
                    <div style="font-size: 0.8rem; opacity: 0.8;">System Administrator</div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="main-content">
            <!-- Sidebar remains the same -->
            <div class="sidebar">
                <div class="sidebar-header">
                    <h3>Donation Management</h3>
                    <p>Administrative Dashboard</p>
                </div>
                
                <div class="menu-section">
                    <div class="menu-section-title">Navigation</div>
                    <div class="menu-item active" onclick="showContent('dashboard', this)">
                        <span class="icon"><i class="fa-solid fa-house"></i></span>
                        <span>Dashboard Overview</span>
                    </div>
                    <div class="menu-item" onclick="showContent('donor-organs', this)">
                        <span class="icon"><i class="fa-solid fa-briefcase-medical"></i></span>
                        <span>Donor Organs</span>
                    </div>
                    <div class="menu-item" onclick="showContent('matching', this)">
                        <span class="icon"><i class="fa-solid fa-handshake"></i></span>
                        <span>Matching</span>
                    </div>
                    <div class="menu-item" onclick="showContent('tributes', this)">
                        <span class="icon"><i class="fa-solid fa-heart"></i></span>
                        <span>Tributes</span>
                    </div>
                </div>
            </div>

            <div class="content-area" id="content-area">
                <!-- Tributes Management -->
                <div id="tributes" class="content-section">
                    <div class="content-header">
                        <h2>Tributes Management</h2>
                        <p>Manage user tributes and messages</p>
                    </div>
                    
                    <!-- Display Messages -->
                    <?php if ($message): ?>
                        <div class="alert <?php echo $message_type === 'success' ? 'alert-success' : 'alert-error'; ?>" 
                             style="margin: 1rem 0; padding: 1rem; border-radius: 8px;">
                            <?php echo htmlspecialchars($message); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="content-body">
                        <!-- Search Bar -->
                        <div class="search-bar">
                            <span class="search-icon">🔍</span>
                            <input type="text" class="search-input" placeholder="Search tributes..." id="tribute-search">
                        </div>

                        <!-- Action Buttons -->
                        <div class="action-section">
                            <h3>Tribute Actions</h3>
                            <div class="action-buttons">
                                <button class="btn btn-danger" id="bulk-delete-btn" onclick="bulkDeleteTributes()" disabled>
                                    <i class="fa-solid fa-trash"></i> Delete Selected
                                </button>
                                <button class="btn btn-secondary" onclick="refreshPage()">
                                    <i class="fa-solid fa-refresh"></i> Refresh
                                </button>
                            </div>
                        </div>

                        <!-- Tributes Table -->
                        <div class="data-table">
                            <div class="table-header">
                                <h4>User Tributes</h4>
                            </div>
                            
                            <form method="POST" action="" id="tributes-form">
                                <div class="table-content" id="tributes-table">
                                    
                            <div class="table-row" style="font-weight: 600; background: var(--gray-bg-color);">
    <div class="table-cell" style="width: 50px;">
        <input type="checkbox" id="select-all-tributes" onchange="toggleSelectAllTributes()">
    </div>
    <div class="table-cell">Tribute ID</div>
    <div class="table-cell">Submitted By</div>
    <div class="table-cell">Message</div>
    <div class="table-cell" style="width: 150px;">Actions</div>
</div>
                                    
                                    <!-- Data loaded from separate file -->
                                    <?php include 'tributes.data.php'; ?>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tribute Details Modal -->
<div id="tributeModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Tribute Details</h3>
            <button class="modal-close" onclick="closeTributeModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div class="payment-details">
                <div class="detail-row">
                    <div class="detail-label">Tribute ID</div>
                    <div class="detail-value" id="modal-tribute-id">-</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Submitted By</div>
                    <div class="detail-value" id="modal-user-name">-</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">User ID</div>
                    <div class="detail-value" id="modal-user-id">-</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Message</div>
                    <div class="detail-value">
                        <div class="message-full" id="modal-message" style="background: var(--gray-bg-color); padding: 1rem; border-radius: 8px; margin-top: 0.5rem; white-space: pre-wrap; max-height: 300px; overflow-y: auto;"></div>
                    </div>
                </div>
                
            </div>
            
            <div class="modal-actions">
                <button class="btn btn-secondary" onclick="closeTributeModal()">
                    <i class="fa-solid fa-times"></i> Close
                </button>
                <button class="btn btn-danger" id="delete-tribute-btn" onclick="deleteTribute()">
                    <i class="fa-solid fa-trash"></i> Delete Tribute
                </button>
            </div>
        </div>
    </div>
</div>

</body>
</html>