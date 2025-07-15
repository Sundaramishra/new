<?php
require_once 'config/database.php';
startSecureSession();

if (isLoggedIn()) {
    logActivity($_SESSION['user_id'], 'User logged out');
}

// Destroy session
session_destroy();

// Redirect to login
header("Location: login.php?message=logged_out");
exit();
?>