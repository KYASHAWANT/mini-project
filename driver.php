<?php
// Database connection
$servername = "localhost";
$username = "root";  // Update with your database username
$password = "";      // Update with your database password
$dbname = "driver_work_details";  // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $driver_name = mysqli_real_escape_string($conn, $_POST['driver_name']);
    $work_time = mysqli_real_escape_string($conn, $_POST['work_time']);
    $attendance = mysqli_real_escape_string($conn, $_POST['attendance']);
    $contact_number = mysqli_real_escape_string($conn, $_POST['contact_number']);  // Get contact number
    $date = date('Y-m-d');  // Automatically set today's date as the attendance date

    // Insert data into the database
    $sql = "INSERT INTO driver_details (driver_name, work_time, attendance, contact_number, date) 
            VALUES ('$driver_name', '$work_time', '$attendance', '$contact_number', '$date')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Driver details saved successfully!');</script>";
    } else {
        echo "<script>alert('Error: " . $sql . "<br>" . $conn->error . "');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Work Details</title>
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
        .form-container {
            background: #ffffff;
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 400px;
        }
        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #0078d7;
        }
        .form-container label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }
        .form-container input, .form-container select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
        }
        .form-container button {
            width: 100%;
            padding: 10px;
            background: #0078d7;
            color: white;
            font-size: 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .form-container button:hover {
            background: #005fa3;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Driver Work Details</h2>
        <form method="post">
            <label for="driver-name">Driver Name:</label>
            <input type="text" id="driver-name" name="driver_name" placeholder="Enter driver's name" required>

            <label for="work-time">Work Time (Hours):</label>
            <input type="number" id="work-time" name="work_time" placeholder="Enter hours worked" min="0" required>

            <label for="attendance">Attendance:</label>
            <select id="attendance" name="attendance" required>
                <option value="">Select Attendance</option>
                <option value="Present">Present</option>
                <option value="Absent">Absent</option>
            </select>

            <label for="contact-number">Contact Number:</label>
            <input type="text" id="contact-number" name="contact_number" placeholder="Enter contact number" required>

            <button type="submit">Submit</button>
        </form>
    </div>
</body>
</html>
