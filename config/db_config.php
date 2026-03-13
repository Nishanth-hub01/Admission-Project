<?php
// admission-system/config/db_config.php
// Database Configuration File

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'admission_system');

// Create MySQLi connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}

// Set charset to UTF-8
$conn->set_charset("utf8");

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/**
 * Function to sanitize inputs
 * @param string $data - Input data to sanitize
 * @return string - Sanitized data
 */
function sanitize($data) {
    global $conn;
    return mysqli_real_escape_string($conn, trim($data));
}

/**
 * Function to verify counselor session
 * Redirects to login if not authenticated
 */
function checkCounselorSession() {
    if (!isset($_SESSION['counselor_id'])) {
        header("Location: counselor_login.php");
        exit();
    }
}
?>
