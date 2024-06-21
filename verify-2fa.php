<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// Database connection details
$servername = "localhost";
$username = "root";
$password = "surayya"; // Replace with your actual MySQL password
$dbname = "Cyberlearn";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to sanitize user input
function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

$user_id = $_SESSION["user_id"];
$code = sanitizeInput($_POST["code"] ?? '');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($code)) {
        // Check the 2FA code
        $stmt = mysqli_prepare($conn, "SELECT 2fa_code, role FROM users WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $dbCode, $role);
        mysqli_stmt_fetch($stmt);

        if ($code === $dbCode) {
            // 2FA code is correct, clear it from the database
           

            // Set session role
            $_SESSION["role"] = $role;

            // Redirect based on role
            if ($role === 'admin') {
                header("Location: admin-dashboard.php");
            } else {
                header("Location: dashboard.php");
            }
            exit();
        } else {
            $error = "Invalid 2FA code.";
        }
        mysqli_stmt_close($stmt);
    } else {
        $error = "2FA code is required.";
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>2FA Verification</title>
    <link rel="stylesheet" href="main.css">
</head>
<body>
    <div class="form-container">
        <form method="POST" action="verify-2fa.php">
            <h2>Enter 2FA Code</h2>
            <input type="text" name="code" placeholder="2FA Code" required>
            <button type="submit">Verify</button>
            <?php if (isset($error)) { echo '<p class="error">'.$error.'</p>'; } ?>
        </form>
    </div>
</body>
</html>
