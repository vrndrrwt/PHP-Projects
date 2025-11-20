<?php
include 'db_connect.php';

// Get date range from query parameters
$startDate = $_GET['start_date'] ?? date('Y-m-d', strtotime('-6 days'));
$endDate = $_GET['end_date'] ?? date('Y-m-d');

// Set headers for CSV download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="user_registrations_'.$startDate.'_to_'.$endDate.'.csv"');

// Open output stream
$output = fopen('php://output', 'w');

// Write CSV header
fputcsv($output, ['Date', 'Registrations Count']);

// Generate date period
$period = new DatePeriod(
  new DateTime($startDate),
  new DateInterval('P1D'),
  (new DateTime($endDate))->modify('+1 day')
);

// Write rows
foreach ($period as $date) {
  $formatted = $date->format('Y-m-d');
  $query = $conn->query("SELECT COUNT(*) AS count FROM users WHERE DATE(created_at) = '$formatted'");
  $count = $query->fetch_assoc()['count'];
  fputcsv($output, [$formatted, $count]);
}

fclose($output);
exit;
?>
