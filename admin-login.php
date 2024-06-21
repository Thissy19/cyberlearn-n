<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "surayya"; 
$dbname = "Cyberlearn";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

$username = sanitizeInput($_POST["username"] ?? '');
$password = sanitizeInput($_POST["password"] ?? '');

if (empty($username) || empty($password)) {
    die("Username and password are required.");
}

$stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows == 1) {
    $stmt->bind_result($userId, $dbUsername, $dbPassword, $role);
    $stmt->fetch();

    if (password_verify($password, $dbPassword) && $role === 'admin') {
        $_SESSION["user_id"] = $userId;
        $_SESSION["username"] = $dbUsername;
        $_SESSION["role"] = $role;

        header("Location: admin-dashboard.php");
        exit();
    } else {
        die("Invalid username or password.");
    }
} else {
    die("Invalid username or password.");
}

$stmt->close();
$conn->close();
?>
