<?php
// Team members array
$teamMembers = [
    "K Yashawanth",
    "Kumara T",
    "Manikantha Khatavakar",
    "Kuberan A"
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color:blue;
            background: url('office1.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: auto;
            text-align: center;
            padding: 20px;
        }

        h1 {
            color: white;
            margin-bottom: 20px;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        ul li {
            background-color: #fff;
            margin: 10px 0;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            font-size: 18px;
            color: #555;
        }

        footer {
            margin-top: 20px;
            font-size: 14px;
            color: #aaa;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>About Our Team</h1>
        <p>We are a group of dedicated professionals committed to delivering excellence.</p>
        <ul>
            <?php foreach ($teamMembers as $member): ?>
                <li><?php echo htmlspecialchars($member); ?></li>
            <?php endforeach; ?>
        </ul>
        <footer>
            &copy; <?php echo date("Y"); ?> Our Team. All rights reserved.
        </footer>
    </div>
</body>
</html>
