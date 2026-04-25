<?php

$host = "localhost";
$dbname = "grade_management";
$dbUsername = "myapp_user";
$dbPassword = "your_strong_password";

$conn = new mysqli($host, $dbUsername, $dbPassword, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}