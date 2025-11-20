<link rel="stylesheet" href="style.css">

<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST['username'];
  $email = $_POST['email'];
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
  $role = $_POST['role']; // new line

  $check = $conn->query("SELECT * FROM users WHERE email='$email'");
  if ($check->num_rows > 0) {
    echo "<p style='color:red;'>Email already exists!</p>";
  } else {
    $sql = "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$password', '$role')";
    if ($conn->query($sql) === TRUE) {
      echo "<p style='color:green;'>Registration successful! <a href='login.php'>Login Now</a></p>";
    } else {
      echo "Error: " . $conn->error;
    }
  }
}
?>

<h2>Register</h2>
<form method="POST" action="">
  <label>Username:</label><br>
  <input type="text" name="username" required><br><br>

  <label>Email:</label><br>
  <input type="email" name="email" required><br><br>

  <label>Password:</label><br>
  <input type="password" name="password" required><br><br>

  <label>Role:</label><br>
  <select name="role">
    <option value="user">User</option>
    <option value="admin">Admin</option>
  </select><br><br>

  <button type="submit">Register</button>
</form>
