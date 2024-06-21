<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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

// Function to send 2FA code via email
function send2FACode($email, $code) {
    require 'PHPMailer/src/PHPMailer.php';
    require 'PHPMailer/src/Exception.php';
    require 'PHPMailer/src/SMTP.php';

    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Set the SMTP server to send through
        $mail->SMTPAuth = true;
        $mail->Username = 'salehsurayya019@gmail.com'; // SMTP username
        $mail->Password = 'lfosjlxlynjorrwk'; // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

         //Enable SMTP debugging
         $mail->SMTPDebug = 2; // Enable verbose debug output
         $mail->Debugoutput = 'html'; // Output debugging information in HTML format

        //Recipients
        $mail->setFrom('salehsurayya019@gmail.com', 'Cyberlearn');
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your 2FA Code';
        $mail->Body = 'Your 2FA code is: ' . $code;

        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

// Get username and password from login form
$username = sanitizeInput($_POST["username"] ?? '');
$password = sanitizeInput($_POST["password"] ?? '');

// Validate input
if (empty($username) || empty($password)) {
    die("Username and password are required.");
}

// Check if user exists in database
$stmt = mysqli_prepare($conn, "SELECT id, username, password, role, email FROM users WHERE username = ?");
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) == 1) {
    // Bind result variables
    mysqli_stmt_bind_result($stmt, $userId, $dbUsername, $dbPassword, $role, $email);
    mysqli_stmt_fetch($stmt);

    // Verify password
    if (password_verify($password, $dbPassword)) {
        // Password is correct, generate and send 2FA code
        $code = rand(100000, 999999); // Generate a 6-digit code
        

        // Store 2FA code in database
        $stmt = mysqli_prepare($conn, "UPDATE users SET 2fa_code = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "si", $code, $userId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        send2FACode($email, $code);

        // Redirect to 2FA verification page
        $_SESSION["user_id"] = $userId;
        header("Location: verify-2fa.php");
        exit();
    } else {
        die("Invalid username or password.");
    }
} else {
    die("Invalid username or password.");
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
