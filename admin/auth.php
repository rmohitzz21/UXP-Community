<?php
/**
 * Admin Authentication Check
 * Include this file at the top of all admin pages (except login.php)
 */

// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // Redirect to login page
    header('Location: login.php');
    exit;
}

// Optional: Session timeout (30 minutes)
$session_timeout = 30 * 60; // 30 minutes in seconds
if (isset($_SESSION['admin_login_time']) && (time() - $_SESSION['admin_login_time']) > $session_timeout) {
    // Session expired
    session_destroy();
    header('Location: login.php?expired=1');
    exit;
}

// Update last activity time
$_SESSION['admin_login_time'] = time();

/**
 * Logout function
 */
function adminLogout() {
    session_destroy();
    header('Location: login.php');
    exit;
}

// Handle logout action
if (isset($_GET['logout'])) {
    adminLogout();
}
