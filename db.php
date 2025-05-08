<?php
$servername = "localhost";
$username = "root";
$password = "somepassword"; // Change if you have a password
$dbname = "testdb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("❌ Database connection failed: " . $conn->connect_error);
}
?>