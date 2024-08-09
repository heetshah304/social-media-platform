<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if the user is not logged in
    header("Location: login.php");
    exit();
}

// Use this file in any page where session management is needed
?>
