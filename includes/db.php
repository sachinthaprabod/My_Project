<?php
/**
 * Hotel S&B - Database Connection File
 * ICT 2209 Web Technologies Mini Project
 * Rajarata University of Sri Lanka
 */

// Database configuration settings (Default for XAMPP / WAMP)
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'hotel_sb');
define('DB_PORT', 3306);

try {
    // Establish PDO connection
    $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    
    // Also provide $conn alias for flexibility
    $conn = $pdo;
} catch (PDOException $e) {
    // Graceful error display with helpful guidance for university evaluation
    die("
    <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; padding: 25px; border: 1px solid #f5c6cb; background-color: #f8d7da; color: #721c24; border-radius: 8px;'>
        <h2 style='margin-top: 0;'>Database Connection Failed</h2>
        <p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>
        <hr style='border: 0; border-top: 1px solid #f5c6cb;'>
        <p><strong>Setup Instructions:</strong></p>
        <ol>
            <li>Make sure <strong>Apache</strong> and <strong>MySQL</strong> are started in your XAMPP/WAMP Control Panel.</li>
            <li>Open <a href='http://localhost/phpmyadmin' target='_blank'>phpMyAdmin</a>.</li>
            <li>Create a database named <code>hotel_sb</code> (or simply import <code>database.sql</code> from this project folder).</li>
            <li>Refresh this page once imported.</li>
        </ol>
    </div>
    ");
}
?>
