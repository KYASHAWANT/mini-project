<?php
session_start();

// Check if the manager is logged in
if (!isset($_SESSION['manager_id'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
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
            text-align: center;
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
        }

        button {
            padding: 15px 30px;
            margin: 10px;
            font-size: 16px;
            color: white;
            background-color: #4CAF50;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 200px;
        }

        button:hover {
            background-color: #45a049;
        }

        .button-container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
        }

        .button-container button {
            margin: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome to the Manager Dashboard</h1>
        <div class="button-container">
            <!-- Button to view customer requests -->
            <button onclick="window.location.href='customer_requests.php'">Customer Requests</button>

            <!-- Button to assign routes to drivers (Redirects to depot.php) -->
            <button onclick="window.location.href='depot.php'">Assign Routes to Drivers</button>

            <!-- Driver Attendance Button -->
            <button onclick="window.location.href='print_attendance.php'">Driver Attendance</button>
        </div>
    </div>
</body>
</html>
