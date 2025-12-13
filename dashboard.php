<?php
// Database connection
$connection = new mysqli("localhost", "root", "", "lms");
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Fetch counts from the database

// 1. Total Books (assuming table name is 'books')
$result = $connection->query("SELECT COUNT(*) as total FROM books");
$totalBooks = $result->fetch_assoc()['total'] ?? 0;

// 2. Issued Books (currently issued, i.e. in issued_books table)
$result = $connection->query("SELECT COUNT(*) as total FROM issued_books");
$issuedBooks = $result->fetch_assoc()['total'] ?? 0;

// 3. Returned Books (count from returned_books table)
$result = $connection->query("SELECT COUNT(*) as total FROM returned_books");
$returnedBooks = $result->fetch_assoc()['total'] ?? 0;

// 4. Total Students (assuming table name is 'students')
$result = $connection->query("SELECT COUNT(*) as total FROM students");
$totalStudents = $result->fetch_assoc()['total'] ?? 0;

// 5. Late Returns (returned books where actual_return_date > return_date in issued_books before deletion)
// Since you delete issued_books after return, you need to keep return_date in returned_books or compare dates in returned_books
// Assuming returned_books has return_date and actual_return_date
$result = $connection->query("
  SELECT COUNT(*) as total 
  FROM returned_books r
  JOIN issued_books i 
    ON r.student_id = i.student_id 
    AND r.book_id = i.book_id
  WHERE r.actual_return_date > i.return_date
");
$lateReturns = $result->fetch_assoc()['total'] ?? 0;


$connection->close();
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="auto">

<head>
  <meta charset="UTF-8">
  <title>Library Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description"
    content="Track your library's performance in real-time with an interactive admin dashboard - manage books, students, and daily updates easily.">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

    * {
      font-family: "Poppins", sans-serif;
    }

    .dashboard-card {
      border-radius: 16px;
      transition: all 0.3s ease-in-out;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.07);
    }

    .dashboard-card:hover {
      transform: translateY(-5px) scale(1.02);
      box-shadow: 0 12px 24px rgba(0, 0, 0, 0.12);
    }

    .icon-circle {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      font-size: 1.5rem;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    [data-bs-theme="dark"] .dashboard-card {
      box-shadow: 0 4px 12px rgba(255, 255, 255, 0.08);
    }

    [data-bs-theme="dark"] .dashboard-card:hover {
      box-shadow: 0 12px 24px rgba(255, 255, 255, 0.15);
    }

    [data-bs-theme="dark"] .text-body {
      color: #f8f9fa !important;
    }
  </style>
</head>

<body>
  <div class="container py-5">
    <h2 class="fw-bold mb-3">📚 Admin Dashboard</h2>
    <p class="text-muted mb-4">Quick overview of today's activity</p>

    <div class="row g-4">
      <div class="col-lg-4 col-md-6">
        <div class="dashboard-card p-3 bg-primary-subtle">
          <div class="d-flex align-items-center gap-3">
            <div class="icon-circle bg-primary text-white">📘</div>
            <div>
              <h5 class="mb-1 fw-semibold text-primary">Total Books</h5>
              <h4 class="mb-0 text-body"><?= $totalBooks ?></h4>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-md-6">
        <div class="dashboard-card p-3 bg-warning-subtle">
          <div class="d-flex align-items-center gap-3">
            <div class="icon-circle bg-warning text-white">📤</div>
            <div>
              <h5 class="mb-1 fw-semibold text-warning">Issued Books</h5>
              <h4 class="mb-0 text-body"><?= $issuedBooks ?></h4>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-md-6">
        <div class="dashboard-card p-3 bg-success-subtle">
          <div class="d-flex align-items-center gap-3">
            <div class="icon-circle bg-success text-white">📥</div>
            <div>
              <h5 class="mb-1 fw-semibold text-success">Returned Books</h5>
              <h4 class="mb-0 text-body"><?= $returnedBooks ?></h4>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-md-6">
        <div class="dashboard-card p-3 bg-info-subtle">
          <div class="d-flex align-items-center gap-3">
            <div class="icon-circle bg-info text-white">🎓</div>
            <div>
              <h5 class="mb-1 fw-semibold text-info">Total Students</h5>
              <h4 class="mb-0 text-body"><?= $totalStudents ?></h4>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-md-6">
        <div class="dashboard-card p-3 bg-danger-subtle">
          <div class="d-flex align-items-center gap-3">
            <div class="icon-circle bg-danger text-white">⏰</div>
            <div>
              <h5 class="mb-1 fw-semibold text-danger">Late Returns</h5>
              <h4 class="mb-0 text-body"><?= $lateReturns ?></h4>
            </div>
          </div>
        </div>
      </div>

      
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
