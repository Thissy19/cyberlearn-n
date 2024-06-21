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

// Fetch list of modules from the database
$sql = "SELECT module_id, module_name FROM module";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Error retrieving modules: " . mysqli_error($conn));
}

// Process deletion of a module
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_module'])) {
  $module_id = $_POST['module_id'];

  // Delete the references in user_module first
  $sql = "DELETE FROM user_module WHERE module_id = ?";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt, "i", $module_id);

  if (mysqli_stmt_execute($stmt)) {
      // Now delete the module from the module table
      $sql = "DELETE FROM module WHERE module_id = ?";
      $stmt = mysqli_prepare($conn, $sql);
      mysqli_stmt_bind_param($stmt, "i", $module_id);

      if (mysqli_stmt_execute($stmt)) {
          $success = "Module deleted successfully.";
      } else {
          $error = "Error deleting module: " . mysqli_error($conn);
      }
  } else {
      $error = "Error deleting references: " . mysqli_error($conn);
  }

  mysqli_stmt_close($stmt);
}
// Process addition of a module
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_module'])) {
    $module_name = $_POST['module_name'];

    // Validate input (You can add more validation as per your requirements)
    if (empty($module_name)) {
        $error = "Module name is required.";
    } else {
        // Insert the module into the database
        $sql = "INSERT INTO module (module_name, module_id) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $module_name, $module_id);

        if (mysqli_stmt_execute($stmt)) {
            $module_id = mysqli_insert_id($conn); // Get the ID of the newly inserted module
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            header("Location: manage-module-details.php?module_id=$module_id");
            exit();
        } else {
            $error = "Error adding module: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    }
}

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Modules</title>
  <link rel="stylesheet" href="main.css">
</head>
<body>
  
    <div class="manage">
      <h2>Manage Modules</h2>

      <!-- Display success or error messages -->
      <?php if (isset($success)) : ?>
        <div class="success"><?php echo $success; ?></div>
      <?php endif; ?>
      <?php if (isset($error)) : ?>
        <div class="error"><?php echo $error; ?></div>
      <?php endif; ?>

      <div class="actions">
        <!-- Form to add a new module -->
        <form action="" method="post">
          <input type="text" name="module_name" placeholder="Enter module name" required>
          <button type="submit" name="add_module">Add Module</button>
        </form>
      </div>

      <div class="modules-list">
        <h3>List of Modules</h3>
        <ul>
          <?php while ($row = mysqli_fetch_assoc($result)) : ?>
            <li>
              <?php echo htmlspecialchars($row['module_name']); ?>
              <form action="" method="post">
                <input type="hidden" name="module_id" value="<?php echo $row['module_id']; ?>">
                <button type="submit" name="delete_module">Delete</button>
              </form>
            </li>
          <?php endwhile; ?>
        </ul>
      </div>

      <div class="back-to-dashboard">
        <a href="admin-dashboard.php">Back to Dashboard</a>
      </div>
    </div>
  
</body>
</html>