<?php
session_start();
include 'db_connect.php';
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
  header("Location: login.php");
  exit();
}

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM users WHERE id=$id");
$user = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST['username'];
  $email = $_POST['email'];
  $role = $_POST['role'];

  $conn->query("UPDATE users SET username='$username', email='$email', role='$role' WHERE id=$id");
  header("Location: view_users.php");
  exit();
}
?>

<h2>✏️ Edit User</h2>
<form method="POST">
  <label>Username:</label><br>
  <input type="text" name="username" value="<?php echo $user['username']; ?>" required><br><br>

  <label>Email:</label><br>
  <input type="email" name="email" value="<?php echo $user['email']; ?>" required><br><br>

  <label>Role:</label><br>
  <select name="role">
    <option value="user" <?php if ($user['role'] == 'user') echo 'selected'; ?>>User</option>
    <option value="admin" <?php if ($user['role'] == 'admin') echo 'selected'; ?>>Admin</option>
  </select><br><br>

  <button type="submit">Update</button>
</form>

<a href="view_users.php">⬅ Back</a>
