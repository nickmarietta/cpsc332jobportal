<?php
$servername = "localhost";
$username = "root"; // default for laragon
$password = "";     // default for laragon
$dbname = "testdb";

// create new connection
$conn = new mysqli($servername, $username, $password, $dbname);

// check connection status 
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully to testdb";
?>