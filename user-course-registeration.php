<?php
session_start();

// Store selected courses in the session
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_SESSION['registered_courses'] = $_POST['courses'] ?? [];
}
header("Location: dashboard.html");
exit();
?>
