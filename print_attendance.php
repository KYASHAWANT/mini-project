<?php
// Include database connection
include('db_connection.php'); // Ensure you have the connection file included
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "driver_work_details";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch driver details including contact number
$sql = "SELECT driver_name, work_time, attendance, date, contact_number FROM driver_details";
$result = $conn->query($sql);

// Check if there are any results
if ($result->num_rows > 0) {
    // Start the table
    echo "<!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Driver Attendance List</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                background: #f9f9f9;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                color: #333;
            }
            table {
                width: 80%;
                margin: 20px;
                border-collapse: collapse;
                border-radius: 5px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }
            table, th, td {
                border: 1px solid #ccc;
            }
            th, td {
                padding: 12px;
                text-align: center;
            }
            th {
                background-color: #0078d7;
                color: white;
            }
            tr:nth-child(even) {
                background-color: #f2f2f2;
            }
            tr:hover {
                background-color: #ddd;
            }
        </style>
    </head>
    <body>
        <table>
            <thead>
                <tr>
                    <th>Driver Name</th>
                    <th>Work Time (Hours)</th>
                    <th>Attendance</th>
                    <th>Date</th>
                    <th>Contact Number</th> <!-- Added column for Contact Number -->
                </tr>
            </thead>
            <tbody>";

    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . htmlspecialchars($row["driver_name"]) . "</td>
                <td>" . htmlspecialchars($row["work_time"]) . "</td>
                <td>" . htmlspecialchars($row["attendance"]) . "</td>
                <td>" . htmlspecialchars($row["date"]) . "</td>
                <td>" . htmlspecialchars($row["contact_number"]) . "</td> <!-- Display Contact Number -->
              </tr>";
    }

    echo "</tbody></table></body></html>";
} else {
    echo "<p>No attendance records found.</p>";
}

// Close connection
$conn->close();
?>
