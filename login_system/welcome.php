<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'user') {
  header("Location: login.php");
  exit();
}
?>

<h2>👋 Welcome, <?php echo $_SESSION['username']; ?>!</h2>
<p>You are logged in as a <b><?php echo $_SESSION['role']; ?></b>.</p>
<a href="logout.php">Logout</a>
