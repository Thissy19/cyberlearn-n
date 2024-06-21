<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin-login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    
    <link rel="stylesheet" href="main.css">
</head>
<body>
    
        <div class="login">
            <h2>Welcome Admin User</h2>
            <div class="links">
                <a href="manage-modules.php">Manage Modules</a>
                <a href="manage-users.php">Manage Users</a>
                <a href="login.html">Logout</a>
            </div>
        </div>
    
</body>
</html>
