<?php
// Optionally, you can include server-side logic here (e.g., session management or dynamic content).
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Routing and Scheduling System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: url('home.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        header {
            position: fixed;
            top: 0;
            width: 75%;
            justify-content: right;
            color: #ffffff;
            padding: 20px 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
            font-family: cursive;
        }
        header h1 {
            margin: 0;
            font-size: 2.5rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
        }
        nav {
            position: fixed;
            top: 10px;
            right: 20px;
            display: flex;
            gap: 15px;
        }
        nav a {
            text-decoration: none;
            background: transparent;
            color: white;
            padding: 10px 20px;
            border-radius: 12px;
            transition: all 0.3s ease;
            font-size: 0.9rem;
            text-align: center;
            box-shadow: none;
        }
        nav a:hover {
            background: rgba(255, 255, 255, 0.3);
            color: black;
            box-shadow: 0 2px 3px rgba(0, 0, 0, 0.3);
        }
        .menu {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
            margin: 2cm;
            background: rgba(46, 44, 44, 0.8);
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.5);
        }
        .menu a {
            text-decoration: none;
            background-color: limegreen;
            color: black;
            padding: 15px 30px;
            border-radius: 12px;
            transition: all 0.3s ease;
            font-size: 1.2rem;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.5);
            width: 200px;
            text-align: center;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .menu a:hover {
            background: forestgreen;
            color: whitesmoke;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.5);
            transform: translateY(-5px);
        }
        .menu a i {
            font-size: 1rem;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <nav>
        <a href="index.php">Home</a>
        <a href="about.php">About</a>
        <a href="contact.php">Contact</a>
    </nav>
    <header>
        <h1>Vehicle Routing and Scheduling System</h1>
    </header>
    <div class="menu">
        <a href="login.php"><i class="fas fa-warehouse"></i>Depot Manager</a>
        <a href="customer.php"><i class="fas fa-user"></i>Customer</a>
        <a href="driver.php"><i class="fas fa-truck"></i>Driver</a>
    </div>
</body>
</html>
