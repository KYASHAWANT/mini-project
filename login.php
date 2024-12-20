<?php
session_start();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Database connection
    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "depot_management";  // Replace with your database name

    $conn = new mysqli($host, $username, $password, $database);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get input values
    $manager_username = $_POST['username'];
    $manager_password = $_POST['password'];

    // Prevent SQL injection by escaping the input
    $manager_username = $conn->real_escape_string($manager_username);
    $manager_password = $conn->real_escape_string($manager_password);

    // Query to check if the manager exists
    $sql = "SELECT * FROM managers WHERE username='$manager_username' AND password='$manager_password'";

    $result = $conn->query($sql);

    // Check if a manager with the given credentials exists
    if ($result->num_rows > 0) {
        // Fetch the manager's details
        $row = $result->fetch_assoc();

        // Start a session and store manager's information
        $_SESSION['manager_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];

        // Redirect to manager_dashboard.php after successful login
        header("Location: manager_dashboard.php");
        exit;
    } else {
        $error_message = "Invalid username or password!";
    }

    // Close the database connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('office1.jpg') no-repeat center center fixed;
            background-size: cover;
            padding: 20px;
        }

        .container {
            width: 50%;
            margin: auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-top: 8px;
            margin-bottom: 16px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Manager Login</h1>

        <?php if (isset($error_message)): ?>
            <div class="error"><?= $error_message ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
