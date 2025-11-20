<?php
session_start();
include 'db_connect.php';
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
  header("Location: login.php");
  exit();
}

$id = $_GET['id'];

// Prevent deleting themselves (optional safety)
if ($id == $_SESSION['id']) {
  echo "You cannot delete your own account!";
  exit();
}

$conn->query("DELETE FROM users WHERE id=$id");
header("Location: view_users.php");
exit();
?>
