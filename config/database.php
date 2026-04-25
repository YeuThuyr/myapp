<?php

$host = "localhost";
$dbname = "grade_management";
$dbUsername = "root";
$dbPassword = "";

$conn = new mysqli($host, $dbUsername, $dbPassword, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}