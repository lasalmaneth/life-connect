<?php
$rawPassword = "Admin@123";
$hashedPassword = password_hash($rawPassword, PASSWORD_DEFAULT);
echo $hashedPassword;
?>

<!-- all admins - Admin@123 
other users- User@123 -->