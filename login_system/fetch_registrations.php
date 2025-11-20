<?php
include 'db_connect.php';

// Read POST data
$startDate = $_POST['start_date'] ?? date('Y-m-d', strtotime('-6 days'));
$endDate = $_POST['end_date'] ?? date('Y-m-d');

$period = new DatePeriod(
  new DateTime($startDate),
  new DateInterval('P1D'),
  (new DateTime($endDate))->modify('+1 day')
);

$days = [];
$registrationData = [];

foreach ($period as $date) {
  $formatted = $date->format('Y-m-d');
  $days[] = $formatted;

  $result = $conn->query("SELECT COUNT(*) AS count FROM users WHERE DATE(created_at) = '$formatted'");
  $count = $result->fetch_assoc()['count'];
  $registrationData[] = $count;
}

echo json_encode([
  'labels' => $days,
  'data' => $registrationData,
  'range' => [$startDate, $endDate]
]);
?>
