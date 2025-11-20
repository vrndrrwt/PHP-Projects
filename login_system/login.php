<link rel="stylesheet" href="style.css">

<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST['email'];
  $password = $_POST['password'];

  $sql = "SELECT * FROM users WHERE email='$email'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if (password_verify($password, $row['password'])) {
      $_SESSION['username'] = $row['username'];
      $_SESSION['role'] = $row['role'];
      $_SESSION['id'] = $row['id'];

      // Redirect based on role
      if ($row['role'] == 'admin') {
        header("Location: admin_dashboard.php");
      } else {
        header("Location: welcome.php");
      }
      exit();
    } else {
      echo "<p style='color:red;'>Invalid password!</p>";
    }
  } else {
    echo "<p style='color:red;'>No account found with that email.</p>";
  }
}
?>

<h2>Login</h2>
<form method="POST" action="">
  <label>Email:</label><br>
  <input type="email" name="email" required><br><br>

  <label>Password:</label><br>
  <input type="password" name="password" required><br><br>

  <button type="submit">Login</button>
</form>

<p>Don't have an account? <a href="register.php">Register</a></p>
