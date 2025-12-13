<?php
// get_note_notifications.php
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "lms");
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

// Fetch notes with 'pending' status
$sql = "SELECT id, subject, file_path, uploaded_at FROM notes_requests WHERE status = 'pending' ORDER BY uploaded_at DESC";
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
