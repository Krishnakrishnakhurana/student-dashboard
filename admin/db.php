<?php
// Database configuration
$servername = "localhost";  // Hostname (default is localhost)
$username = "root";  // Database username
$password = "";  // Database password (usually empty for localhost)
$dbname = "college_dashboard";  // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
