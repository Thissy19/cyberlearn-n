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

// Fetch list of users from the database
$sql = "SELECT id, username FROM users";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Error retrieving users: " . mysqli_error($conn));
}

// Process deletion of a user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];

    // Delete the user from the database
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $user_id);

    if (mysqli_stmt_execute($stmt)) {
        $success = "User deleted successfully.";
    } else {
        $error = "Error deleting user: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
}

// Process addition of a user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];

    // Validate input (You can add more validation as per your requirements)
    if (empty($username) || empty($email)) {
        $error = "Username and email are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        // Check if the email already exists
        $sql = "SELECT id FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            $error = "Email already exists.";
        } else {
            // Insert the user into the database
            $sql = "INSERT INTO users (username, email) VALUES (?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ss", $username, $email);

            if (mysqli_stmt_execute($stmt)) {
                $success = "User added successfully.";
            } else {
                $error = "Error adding user: " . mysqli_error($conn);
            }

            mysqli_stmt_close($stmt);
        }
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
  <title>Manage Users</title>
  <link rel="stylesheet" href="dashboard.css">
</head>
<body>
  <div class="ring">
    <i style="--clr:#00ff0a;"></i>
    <i style="--clr:#ff0057;"></i>
    <i style="--clr:#fffd44;"></i>
    <div class="manage">
      <h2>Manage Users</h2>

      <!-- Display success or error messages -->
      <?php if (isset($success)) : ?>
        <div class="success"><?php echo $success; ?></div>
      <?php endif; ?>
      <?php if (isset($error)) : ?>
        <div class="error"><?php echo $error; ?></div>
      <?php endif; ?>

      <div class="actions">
        <!-- Form to add a new user -->
        <form action="" method="post">
          <input type="text" name="username" placeholder="Enter username" required>
          <input type="email" name="email" placeholder="Enter email" required>
          <button type="submit" name="add_user">Add User</button>
        </form>
      </div>

      <div class="users-list">
        <h3>List of Users</h3>
        <ul>
          <?php while ($row = mysqli_fetch_assoc($result)) : ?>
            <li>
              <?php echo htmlspecialchars($row['username']); ?>
              <form action="" method="post">
                <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                <button type="submit" name="delete_user">Delete</button>
              </form>
            </li>
          <?php endwhile; ?>
        </ul>
      </div>

      <div class="back-to-dashboard">
        <a href="admin-dashboard.php">Back to Dashboard</a>
      </div>
    </div>
  </div>
</body>
</html>
