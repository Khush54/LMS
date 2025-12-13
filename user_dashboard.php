<?php
session_start();

// Check if student is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: index.html");
    exit();
}

$userId = intval($_SESSION['student_id']);

// Connect to database
$connection = new mysqli("localhost", "root", "", "lms");
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Books issued count
$stmt = $connection->prepare("SELECT COUNT(*) as total FROM issued_books WHERE student_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$issuedBooks = $result->fetch_assoc()['total'] ?? 0;
$stmt->close();

// Books returned count
$stmt = $connection->prepare("SELECT COUNT(*) as total FROM returned_books WHERE student_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$returnedBooks = $result->fetch_assoc()['total'] ?? 0;
$stmt->close();

// Books due count (issued but return date passed)
$stmt = $connection->prepare("SELECT COUNT(*) as total FROM issued_books WHERE student_id = ? AND return_date < CURDATE()");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$dueBooks = $result->fetch_assoc()['total'] ?? 0;
$stmt->close();


$connection->close();
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="auto">

<head>
  <meta charset="UTF-8" />
  <title>Student Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    * {
      font-family: 'Poppins', sans-serif;
    }

    .dashboard-card {
      border-radius: 16px;
      transition: 0.3s;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.07);
    }

    .dashboard-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 24px rgba(0, 0, 0, 0.12);
    }

    .icon-circle {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
    }
  </style>
</head>

<body>
  <div class="container py-5">
    <h2 class="fw-bold mb-3">🎓 Student Dashboard</h2>
    <p class="text-muted mb-4">Your current activity and resources</p>
    <div class="row g-4">
      <div class="col-md-4">
        <div class="dashboard-card p-3 bg-warning-subtle">
          <div class="d-flex align-items-center gap-3">
            <div class="icon-circle bg-warning text-white">📤</div>
            <div>
              <h6 class="fw-semibold text-warning">Books Issued</h6>
              <h4><?= htmlspecialchars($issuedBooks) ?></h4>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="dashboard-card p-3 bg-success-subtle">
          <div class="d-flex align-items-center gap-3">
            <div class="icon-circle bg-success text-white">📥</div>
            <div>
              <h6 class="fw-semibold text-success">Books Returned</h6>
              <h4><?= htmlspecialchars($returnedBooks) ?></h4>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="dashboard-card p-3 bg-danger-subtle">
          <div class="d-flex align-items-center gap-3">
            <div class="icon-circle bg-danger text-white">⏰</div>
            <div>
              <h6 class="fw-semibold text-danger">Books Due</h6>
              <h4><?= htmlspecialchars($dueBooks) ?></h4>
            </div>
          </div>
        </div>
      </div>

      
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
