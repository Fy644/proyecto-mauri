<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'agencia_loulou';

// Create connection with error reporting
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to utf8mb4
if (!$conn->set_charset("utf8mb4")) {
    die("Error setting charset: " . $conn->error);
}
?> 