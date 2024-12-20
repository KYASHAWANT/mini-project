<?php
session_start();

// Check if the manager is logged in
if (!isset($_SESSION['manager_id'])) {
    header("Location: login.php");
    exit;
}

// Redirect to depot.php
header("Location: depot.php");
exit;
?>
