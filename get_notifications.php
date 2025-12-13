<?php
// get_notifications.php
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "lms");
if ($conn->connect_error) {
  http_response_code(500);
  echo json_encode(["error" => "Database connection failed"]);
  exit;
}

$sql = "SELECT id, subject, year, file_path, submitted_at FROM pyq_requests WHERE status = 'pending' ORDER BY submitted_at DESC";
$result = $conn->query($sql);

$notifications = [];
if ($result) {
  while ($row = $result->fetch_assoc()) {
    $notifications[] = $row;
  }
}

echo json_encode($notifications);
$conn->close();
?>
