<?php
/**
 * Database connection for SocialNet.
 * Provides a $conn (mysqli) object.
 */

// Show errors in browser for debugging (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$db_host = "localhost";
$db_name = "socialnet";
$db_user = "myapp_user";
$db_pass = "your_strong_password";

try {
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }

    $conn->set_charset("utf8mb4");
} catch (Exception $e) {
    die("Database connection failed: " . $e->getMessage());
}
