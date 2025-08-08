<?php
// Database configuration
$servername = "localhost";
$username = "root";  // For XAMPP/WAMP
$password = "";      // For XAMPP/WAMP (empty)
$dbname = "student_records";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset
$conn->set_charset("utf8");
?>
