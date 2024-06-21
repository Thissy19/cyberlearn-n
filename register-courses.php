<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection details
$servername = "localhost";
$username = "root";
$password = "surayya";
$dbname = "cyberlearn";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Process module registration
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register_module'])) {
    $user_id = $_SESSION['user_id'];
    $module_id = $_POST['module_id'];

    // Check if user is already registered for this module
    $sql_check = "SELECT id FROM user_module WHERE user_id = ? AND module_id = ?";
    $stmt_check = mysqli_prepare($conn, $sql_check);
    mysqli_stmt_bind_param($stmt_check, "ii", $user_id, $module_id);
    mysqli_stmt_execute($stmt_check);
    mysqli_stmt_store_result($stmt_check);

    if (mysqli_stmt_num_rows($stmt_check) > 0) {
        $error = "You are already registered for this module.";
    } else {
        // Register user for the module
        $sql_register = "INSERT INTO user_module (user_id, module_id) VALUES (?, ?)";
        $stmt_register = mysqli_prepare($conn, $sql_register);
        mysqli_stmt_bind_param($stmt_register, "ii", $user_id, $module_id);

        if (mysqli_stmt_execute($stmt_register)) {
            $success = "Module registered successfully.";
        } else {
            $error = "Error registering module: " . mysqli_error($conn);
        }

        mysqli_stmt_close($stmt_register);
    }

    mysqli_stmt_close($stmt_check);
}

// Fetch list of modules to display in the dropdown
$sql_modules = "SELECT module_id, module_name FROM module";
$result_modules = mysqli_query($conn, $sql_modules);

if (!$result_modules) {
    die("Error retrieving module: " . mysqli_error($conn));
}

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register Modules</title>
  <link rel="stylesheet" href="register-courses.css">
</head>
<body>
  <div class="ring">
    <i style="--clr:#00ff0a;"></i>
    <i style="--clr:#ff0057;"></i>
    <i style="--clr:#fffd44;"></i>
    <div class="register">
      <h2>Register for Modules</h2>
      <div class="modules-list">
        <?php if (isset($success)) : ?>
          <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        <?php if (isset($error)) : ?>
          <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="register-courses.php" method="post">
          <label for="module_id">Select Module:</label>
          <select name="module_id" id="module_id" required>
            <option value="">Select Module</option>
            <?php
            while ($row = $result_modules->fetch_assoc()) {
              echo '<option value="' . htmlspecialchars($row['module_id']) . '">' . htmlspecialchars($row['module_name']) . '</option>';
            }
            ?>
          </select>
          <button type="submit" name="register_module">Register Module</button>
        </form>
      </div>
      <div class="back-to-dashboard">
        <a href="dashboard.php">Back to Dashboard</a>
      </div>
    </div>
  </div>
</body>
</html>
