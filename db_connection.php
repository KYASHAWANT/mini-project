<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "customer_requests";  // Update this to your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>