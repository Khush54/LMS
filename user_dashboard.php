<?php
require_once("auth.php");
require_student();

$userId = intval($_SESSION['student_id']);

include("config.php");
$stmt = $connection->prepare("SELECT COUNT(*) as total FROM issued_books WHERE student_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$issuedBooks = $result->fetch_assoc()['total'] ?? 0;
$stmt->close();

$stmt = $connection->prepare("SELECT COUNT(*) as total FROM returned_books WHERE student_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$returnedBooks = $result->fetch_assoc()['total'] ?? 0;
$stmt->close();


$stmt = $connection->prepare("
    SELECT COUNT(*) as total 
    FROM issued_books i
    WHERE i.student_id = ? 
    AND i.return_date < CURDATE()
    AND i.book_id NOT IN (
        SELECT r.book_id 
        FROM returned_books r 
        WHERE r.student_id = ?
    )
");
$stmt->bind_param("ii", $userId, $userId);
$stmt->execute();
$result = $stmt->get_result();
$dueBooks = $result->fetch_assoc()['total'] ?? 0;
$stmt->close();

$connection->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>Student Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  
  <link rel="preconnect" href="https://cdn.jsdelivr.net">
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
    }

    .dashboard-card {
      border: none;
      border-radius: 16px;
      transition: all 0.3s ease;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
      background: #fff;
    }

    .dashboard-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
    }

    .icon-circle {
      width: 55px;
      height: 55px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.4rem;
    }

    @media (min-width: 992px) {
      .col-custom { width: 33.33%; }
    }
  </style>
</head>

<body>
  <div class="container py-5">
    <div class="row mb-4">
        <div class="col-12 text-center text-md-start">
            <h2 class="fw-bold mb-1">🎓 Student Dashboard</h2>
            <p class="text-muted">Welcome back! Manage your library activities here.</p>
        </div>
    </div>

    <div class="row g-4">
      
      <div class="col-lg-4 col-md-6 col-12">
        <div class="dashboard-card p-4 h-100 bg-warning-subtle">
          <div class="d-flex align-items-center gap-3">
            <div class="icon-circle bg-warning text-white">📤</div>
            <div>
              <h6 class="fw-semibold text-warning mb-1">Books Issued</h6>
              <h3 class="fw-bold mb-0"><?= htmlspecialchars($issuedBooks) ?></h3>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-md-6 col-12">
        <div class="dashboard-card p-4 h-100 bg-success-subtle">
          <div class="d-flex align-items-center gap-3">
            <div class="icon-circle bg-success text-white">📥</div>
            <div>
              <h6 class="fw-semibold text-success mb-1">Books Returned</h6>
              <h3 class="fw-bold mb-0"><?= htmlspecialchars($returnedBooks) ?></h3>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-md-6 col-12">
        <div class="dashboard-card p-4 h-100 bg-danger-subtle">
          <div class="d-flex align-items-center gap-3">
            <div class="icon-circle bg-danger text-white">⏰</div>
            <div>
              <h6 class="fw-semibold text-danger mb-1">Books Due</h6>
              <h3 class="fw-bold mb-0"><?= htmlspecialchars($dueBooks) ?></h3>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
