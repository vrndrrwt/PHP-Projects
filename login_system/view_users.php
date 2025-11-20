<?php
session_start();
include 'db_connect.php';

// Restrict access to admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
  header("Location: login.php");
  exit();
}

// --- Pagination Setup ---
$limit = 5; // Number of users per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// --- Search Setup ---
$search = "";
$where = "";
if (isset($_GET['search']) && $_GET['search'] != "") {
  $search = $conn->real_escape_string($_GET['search']);
  $where = "WHERE username LIKE '%$search%' OR email LIKE '%$search%' OR role LIKE '%$search%'";
}

// --- Total Users Count ---
$totalQuery = $conn->query("SELECT COUNT(*) AS total FROM users $where");
$total = $totalQuery->fetch_assoc()['total'];
$totalPages = ceil($total / $limit);

// --- Fetch Users ---
$sql = "SELECT * FROM users $where ORDER BY id DESC LIMIT $start, $limit";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Manage Users</title>
  <style>
    body { font-family: Arial; margin: 40px; }
    table { border-collapse: collapse; width: 90%; }
    th, td { padding: 10px; border: 1px solid #ccc; }
    th { background-color: #f2f2f2; }
    .search-box { margin-bottom: 20px; }
    input[type="text"] { padding: 6px; width: 250px; }
    button { padding: 6px 10px; background: #007bff; color: white; border: none; cursor: pointer; }
    button:hover { background: #0056b3; }
    .pagination a {
      padding: 6px 10px;
      border: 1px solid #007bff;
      margin: 2px;
      text-decoration: none;
      color: #007bff;
      border-radius: 4px;
    }
    .pagination a.active {
      background-color: #007bff;
      color: white;
    }
    .pagination a:hover {
      background-color: #0056b3;
      color: white;
    }
  </style>
</head>
<body>

<h2>📋 Manage Users</h2>
<p>Welcome, <b><?php echo $_SESSION['username']; ?></b> (<?php echo $_SESSION['role']; ?>)</p>

<div class="search-box">
  <form method="GET" action="">
    <input type="text" name="search" placeholder="Search by username, email, or role" value="<?php echo htmlspecialchars($search); ?>">
    <button type="submit">Search</button>
    <?php if ($search != "") { ?>
      <a href="view_users.php" style="margin-left:10px;">Clear</a>
    <?php } ?>
  </form>
</div>

<table>
  <tr>
    <th>ID</th>
    <th>Username</th>
    <th>Email</th>
    <th>Role</th>
    <th>Actions</th>
  </tr>

  <?php if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) { ?>
      <tr>
        <td><?php echo $row['id']; ?></td>
        <td><?php echo $row['username']; ?></td>
        <td><?php echo $row['email']; ?></td>
        <td><?php echo $row['role']; ?></td>
        <td>
          <a href="edit_user.php?id=<?php echo $row['id']; ?>">✏️ Edit</a> |
          <a href="delete_user.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?')">🗑️ Delete</a>
        </td>
      </tr>
  <?php }
  } else {
    echo "<tr><td colspan='5' style='text-align:center;'>No users found</td></tr>";
  } ?>
</table>

<br>

<div class="pagination">
  <?php if ($page > 1): ?>
    <a href="?page=<?php echo $page - 1; ?>&search=<?php echo $search; ?>">⬅ Prev</a>
  <?php endif; ?>

  <?php for ($i = 1; $i <= $totalPages; $i++): ?>
    <a href="?page=<?php echo $i; ?>&search=<?php echo $search; ?>" class="<?php echo $page == $i ? 'active' : ''; ?>"><?php echo $i; ?></a>
  <?php endfor; ?>

  <?php if ($page < $totalPages): ?>
    <a href="?page=<?php echo $page + 1; ?>&search=<?php echo $search; ?>">Next ➡</a>
  <?php endif; ?>
</div>

<br>
<a href="admin_dashboard.php">⬅ Back to Dashboard</a>

</body>
</html>
