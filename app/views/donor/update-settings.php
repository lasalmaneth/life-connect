<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Get form data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $user_id = $_SESSION['user_id'];
    
    // Database connection
    $conn = mysqli_connect("localhost", "root", "", "lifeconnect");
    
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    
    // Update query
    $sql = "UPDATE donors SET address = ?, phone = ?, email = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssi", $address, $phone, $email, $user_id);
    
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success'] = "Settings updated successfully!";
    } else {
        $_SESSION['error'] = "Error updating settings: " . mysqli_error($conn);
    }
    
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    
    // Redirect back to dashboard
    header('Location: index.php');
    exit();
}
?>