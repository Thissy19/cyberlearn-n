<!-- manage-module-details.php -->
<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin-login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "surayya"; 
$dbname = "Cyberlearn";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch module details based on module_id from URL parameter
if (isset($_GET['module_id'])) {
    $module_id = $_GET['module_id'];

    // Fetch module details from the database
    $sql = "SELECT module_name, module_id FROM module WHERE module_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $module_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) == 1) {
        mysqli_stmt_bind_result($stmt, $module_name, $module_id);
        mysqli_stmt_fetch($stmt);
    } else {
        // Module not found
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        header("Location: manage-modules.php");
        exit();
    }

    mysqli_stmt_close($stmt);
} else {
    // Redirect if module_id is not provided in the URL
    header("Location: manage-modules.php");
    exit();
}

// Process form submission to add content for the module
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_content'])) {
    $content_type = $_POST['content_type']; // Example: materials, quiz, etc.
    $content = $_POST['content']; // Content details (e.g., material text, quiz questions)

    // Insert the content into the database (you need to implement this part based on your structure)
    // Example SQL query: INSERT INTO module_contents (module_id, content_type, content) VALUES (?, ?, ?)
    // Remember to handle validation and error checking

    // Redirect or show success message upon successful addition
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Module Details</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <div class="ring">
        <i style="--clr:#00ff0a;"></i>
        <i style="--clr:#ff0057;"></i>
        <i style="--clr:#fffd44;"></i>
        <div class="manage">
            <h2>Manage Module: <?php echo htmlspecialchars($module_name); ?></h2>
            <p><?php echo htmlspecialchars($module_id); ?></p>

            <div class="actions">
                <!-- Form to add content for the module -->
                <form action="" method="post">
                    <input type="hidden" name="module_id" value="<?php echo $module_id; ?>">
                    <label for="content_type">Content Type:</label>
                    <select name="content_type" id="content_type" required>
                        <option value="materials">Materials</option>
                        <option value="quiz">Quiz</option>
                        <!-- Add more options as needed -->
                    </select>
                    <label for="description">Description:</label>
                      <textarea id="description" name="description" rows="4"></textarea>
                    <textarea name="content" placeholder="Enter content details" required></textarea>
                    <button type="submit" name="add_content">Add Content</button>
                </form>
            </div>

            <div class="back-to-dashboard">
                <a href="admin-dashboard.php">Back to Dashboard</a>
            </div>
        </div>
    </div>
</body>
</html>
