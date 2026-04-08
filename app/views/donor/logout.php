<?php
// FILE: app/views/donor/logout.php
// Proper donor logout handler (destroy session and redirect to login)
if (session_status() === PHP_SESSION_NONE) session_start();

// Clear all session data
$_SESSION = [];

// Remove session cookie
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// Destroy session
session_destroy();

// Redirect to the application home view
header('Location: /life-connect/app/views/home.view.php');
exit();
