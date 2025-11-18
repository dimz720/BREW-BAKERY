<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'brew_bakery');

// Create Connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check Connection
if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}

// Set Charset
$conn->set_charset("utf8mb4");

// Handle Errors
if (!$conn) {
    http_response_code(500);
    die("Database connection error");
}
?>
