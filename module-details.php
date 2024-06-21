<?php
session_start();

// Database connection details
$servername = "localhost";
$username = "root";
$password = "surayya"; // Replace with your actual MySQL password
$dbname = "cyberlearn";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch module details based on module_id from URL parameter
$module_id = $_GET['module_id'] ?? 0;
if ($module_id > 0) {
    $sql = "SELECT * FROM module WHERE module_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $module_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $module = mysqli_fetch_assoc($result);
        // Now display module details, materials, quizzes, etc.
        echo "<h2>" . htmlspecialchars($module['module_name']) . "</h2>";
        echo "<p>Module Description: " . htmlspecialchars($module['description']) . "</p>";
        // Add more HTML to display materials, quizzes, etc.
        // Placeholder for materials
        echo "<h3>Materials</h3>";
        echo "<ul>";
        // Example of listing materials (replace with actual data from database)
        echo "<li>Material 1</li>";
        echo "<li>Material 2</li>";
        // Add more as needed
        echo "</ul>";
        
        // Placeholder for quizzes
        echo "<h3>Quizzes</h3>";
        echo "<ul>";
        // Example of listing quizzes (replace with actual data from database)
        echo "<li>Quiz 1</li>";
        echo "<li>Quiz 2</li>";
        // Add more as needed
        echo "</ul>";
    } else {
        echo "Module not found.";
    }

    mysqli_stmt_close($stmt);
} else {
    echo "Invalid module ID.";
}

mysqli_close($conn);
?>
