<?php
session_start();
include 'db_connect.php';

// Restrict access
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
  header("Location: login.php");
  exit();
}

// Role stats for static charts
$total_users = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];
$admins = $conn->query("SELECT COUNT(*) AS total FROM users WHERE role='admin'")->fetch_assoc()['total'];
$regular_users = $conn->query("SELECT COUNT(*) AS total FROM users WHERE role='user'")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html>
<head>
  <title>Admin Dashboard (AJAX Chart)</title>
  <style>
    body { font-family: Arial; margin: 40px; background: #fafafa; }
    h2 { margin-bottom: 10px; }
    form {
      margin: 20px 0;
      background: #fff;
      padding: 15px;
      border-radius: 8px;
      box-shadow: 0 0 6px rgba(0,0,0,0.1);
    }
    input[type="date"] {
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
    button {
      padding: 8px 12px;
      background: #007bff;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
    button:hover { background: #0056b3; }
    a {
      display: inline-block;
      margin-top: 20px;
      background-color: #28a745;
      color: white;
      padding: 8px 12px;
      text-decoration: none;
      border-radius: 5px;
    }
    a:hover { background-color: #1e7e34; }
    canvas {
      background: #fff;
      padding: 10px;
      border-radius: 8px;
      box-shadow: 0 0 6px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body>

<h2>👨‍💼 Admin Dashboard</h2>
<p>Welcome, <b><?php echo $_SESSION['username']; ?></b> (<?php echo $_SESSION['role']; ?>)</p>
<hr>

<h3>📊 User Statistics</h3>
<ul>
  <li>Total Users: <b><?php echo $total_users; ?></b></li>
  <li>Admins: <b><?php echo $admins; ?></b></li>
  <li>Regular Users: <b><?php echo $regular_users; ?></b></li>
</ul>

<h3>📅 User Registration Analytics (AJAX Filter)</h3>

<form id="filterForm">
  <label>Start Date:</label>
  <input type="date" id="start_date" name="start_date" value="<?php echo date('Y-m-d', strtotime('-6 days')); ?>">
  &nbsp;&nbsp;
  <label>End Date:</label>
  <input type="date" id="end_date" name="end_date" value="<?php echo date('Y-m-d'); ?>">
  &nbsp;&nbsp;
  <button type="submit">Filter</button>
  &nbsp;&nbsp;
  <button type="button" id="downloadCsv" style="background:#28a745;">⬇ Download CSV</button>
</form>


<canvas id="lineChart" width="900" height="300"></canvas>

<a href="view_users.php">👥 Manage Users</a>
<a href="logout.php">Logout</a>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Download CSV when button clicked
document.getElementById('downloadCsv').addEventListener('click', () => {
  const start = document.getElementById('start_date').value;
  const end = document.getElementById('end_date').value;
  const url = `export_csv.php?start_date=${start}&end_date=${end}`;
  window.location.href = url; // triggers download
});

let lineChart;

// Function to fetch data and update chart
async function loadChart(startDate, endDate) {
  const formData = new FormData();
  formData.append('start_date', startDate);
  formData.append('end_date', endDate);

  const response = await fetch('fetch_registrations.php', {
    method: 'POST',
    body: formData
  });

  const data = await response.json();

  const ctx = document.getElementById('lineChart').getContext('2d');

  if (lineChart) lineChart.destroy(); // Destroy old chart before creating new one

  lineChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: data.labels,
      datasets: [{
        label: `Registrations (${data.range[0]} to ${data.range[1]})`,
        data: data.data,
        borderColor: '#ff6600',
        backgroundColor: 'rgba(255,102,0,0.2)',
        fill: true,
        tension: 0.3
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { display: true, position: 'top' } },
      scales: { y: { beginAtZero: true } }
    }
  });
}

// Default load (last 7 days)
loadChart(document.getElementById('start_date').value, document.getElementById('end_date').value);

// Filter form submit
document.getElementById('filterForm').addEventListener('submit', e => {
  e.preventDefault();
  const start = document.getElementById('start_date').value;
  const end = document.getElementById('end_date').value;
  loadChart(start, end);
});
</script>

</body>
</html>
