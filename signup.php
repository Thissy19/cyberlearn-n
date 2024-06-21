<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection details
$servername = "localhost";
$username = "root";
$password = "surayya";
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

// Get form data
$username = sanitizeInput($_POST["username"]);
$email = sanitizeInput($_POST["email"]);
$password = sanitizeInput($_POST["password"]);
$confirmPassword = sanitizeInput($_POST["confirm_password"]);

// Basic validation
$errors = [];
if (empty($username)) {
    $errors[] = "Username is required.";
}
if (empty($email)) {
    $errors[] = "Email is required.";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format.";
}
if (empty($password)) {
    $errors[] = "Password is required.";
} elseif (strlen($password) < 8) {
    $errors[] = "Password must be at least 8 characters long.";
} elseif ($password !== $confirmPassword) {
    $errors[] = "Passwords do not match.";
}

// Process data if no validation errors
if (empty($errors)) {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare and execute SQL statement
    $stmt = mysqli_prepare($conn, "INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashedPassword);
        
        if (mysqli_stmt_execute($stmt)) {
            header("Location: signup_success.html"); 
            exit();
        } else {
            echo "Error: Registration failed. Please try again.";
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Error: Could not prepare the SQL statement.";
    }
} else {
    // Display error messages if validation fails
    echo "Registration failed:<br>";
    foreach ($errors as $error) {
        echo "- " . $error . "<br>";
    }
}

mysqli_close($conn);
?>
