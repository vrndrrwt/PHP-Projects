<?php
$servername = "localhost";
$username = "root";  // Change if you have a different MySQL user
$password = "gppvss11";      // Your MySQL password (keep empty if not set)
$dbname = "user_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>
