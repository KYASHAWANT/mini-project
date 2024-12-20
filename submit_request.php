<?php
// Database connection settings
$host = "localhost"; // Change if your MySQL is on another server
$username = "root"; // Replace with your MySQL username
$password = ""; // Replace with your MySQL password
$database = "customer_requests"; // Replace with your database name

// Establish a connection to the database
$conn = new mysqli($host, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $address = $conn->real_escape_string($_POST['address']);

    // Insert data into the database
    $sql = "INSERT INTO requests (username, email, address) VALUES ('$username', '$email', '$address')";

    if ($conn->query($sql) === TRUE) {
        echo "Request submitted successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close the connection
$conn->close();
?>
