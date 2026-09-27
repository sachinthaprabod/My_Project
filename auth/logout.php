<?php
/**
 * Hotel S&B - Customer Logout
 * ICT 2209 Web Technologies Mini Project - Rajarata University of Sri Lanka
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Unset all session variables
$_SESSION = [];

// Destroy session cookie if set
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(), 
        '', 
        time() - 42000,
        $params["path"], 
        $params["domain"],
        $params["secure"], 
        $params["httponly"]
    );
}

// Destroy the session
session_destroy();

// Start fresh session to deliver logout flash notice
session_start();
$_SESSION['flash'] = [
    'type' => 'info',
    'message' => 'You have been successfully logged out. Come back soon!'
];

// Redirect to home page
header('Location: ../index.php');
exit();
?>
