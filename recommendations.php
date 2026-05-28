<?php
require_once("auth.php");
require_student();
include("config.php");

$userId = intval($_SESSION['student_id']);
$course = $_SESSION['student_course'] ?? '';
$recommendedBooks = [];
$recommendedNotes = [];
$recommendedPYQs = [];

$stmt = $connection->prepare("
    SELECT DISTINCT b.category
    FROM issued_books ib
    JOIN books b ON b.id = ib.book_id
    WHERE ib.student_id = ?
");
$stmt->bind_param("i", $userId);
$stmt->execute();
$categoryRows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$categories = array_values(array_filter(array_map(fn($row) => $row['category'], $categoryRows)));
if (empty($categories) && $course !== '') {
    $categories[] = $course;
}

if (!empty($categories)) {
    $category = $categories[0];
    $like = "%" . $category . "%";

    $stmt = $connection->prepare("
        SELECT id, title, author, category, copies
        FROM books
        WHERE (category LIKE ? OR title LIKE ?)
        AND id NOT IN (SELECT book_id FROM issued_books WHERE student_id = ?)
        ORDER BY copies DESC, title
        LIMIT 6
    ");
    $stmt->bind_param("ssi", $like, $like, $userId);
    $stmt->execute();
    $recommendedBooks = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    $stmt = $connection->prepare("
        SELECT subject, file_path, uploaded_at
        FROM notes_requests
        WHERE status = 'approved' AND subject LIKE ?
        ORDER BY uploaded_at DESC
        LIMIT 4
    ");
    $stmt->bind_param("s", $like);
    $stmt->execute();
    $recommendedNotes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    $stmt = $connection->prepare("
        SELECT subject, year, file_path, submitted_at
        FROM pyq_requests
        WHERE status = 'approved' AND subject LIKE ?
        ORDER BY year DESC, submitted_at DESC
        LIMIT 4
    ");
    $stmt->bind_param("s", $like);
    $stmt->execute();
    $recommendedPYQs = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}

$connection->close();
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="auto">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Recommended For You</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
    body { font-family: "Poppins", sans-serif; background: transparent; padding: 20px; }
    .wrap { max-width: 1050px; margin: auto; }
    .resource-card {
      background: var(--bs-tertiary-bg);
      border: 1px solid var(--bs-border-color);
      border-radius: 14px;
      padding: 18px;
      height: 100%;
      box-shadow: 0 8px 22px rgba(0,0,0,.07);
    }
    .signal { font-size: .78rem; color: var(--bs-secondary-color); }
  </style>
</head>
<body>
<div class="wrap">
  <div class="mb-4">
    <h3 class="fw-bold mb-1"><i class="bi bi-stars me-2"></i>Recommended For You</h3>
    <p class="text-muted mb-0">Personalized from your course and borrowing history.</p>
  </div>

  <?php if (empty($categories)): ?>
    <div class="alert alert-info border-0">Issue one book or update your course to unlock recommendations.</div>
  <?php else: ?>
    <div class="alert alert-primary border-0">Recommendation signal: <?= htmlspecialchars($categories[0]) ?></div>
  <?php endif; ?>

  <h5 class="fw-bold mt-4 mb-3">Books To Explore</h5>
  <div class="row g-3">
    <?php if (empty($recommendedBooks)): ?>
      <div class="col-12"><div class="alert alert-secondary border-0">No matching book recommendations yet.</div></div>
    <?php endif; ?>
    <?php foreach ($recommendedBooks as $book): ?>
      <div class="col-md-6 col-lg-4">
        <div class="resource-card">
          <span class="badge text-bg-primary mb-2">Book</span>
          <h6 class="fw-bold mb-1"><?= htmlspecialchars($book['title']) ?></h6>
          <div class="signal">By <?= htmlspecialchars($book['author']) ?></div>
          <div class="signal">Category: <?= htmlspecialchars($book['category']) ?></div>
          <div class="fw-semibold text-success mt-2"><?= intval($book['copies']) ?> copies available</div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <h5 class="fw-bold mt-5 mb-3">Matching Notes</h5>
  <div class="row g-3">
    <?php if (empty($recommendedNotes)): ?>
      <div class="col-12"><div class="alert alert-secondary border-0">No matching notes yet.</div></div>
    <?php endif; ?>
    <?php foreach ($recommendedNotes as $note): ?>
      <div class="col-md-6">
        <div class="resource-card">
          <span class="badge text-bg-success mb-2">Verified Notes</span>
          <h6 class="fw-bold"><?= htmlspecialchars($note['subject']) ?></h6>
          <div class="signal">Admin approved quality resource</div>
          <a class="btn btn-sm btn-outline-success mt-3" href="<?= htmlspecialchars($note['file_path']) ?>" download>Download</a>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <h5 class="fw-bold mt-5 mb-3">Exam Practice</h5>
  <div class="row g-3">
    <?php if (empty($recommendedPYQs)): ?>
      <div class="col-12"><div class="alert alert-secondary border-0">No matching PYQs yet.</div></div>
    <?php endif; ?>
    <?php foreach ($recommendedPYQs as $pyq): ?>
      <div class="col-md-6">
        <div class="resource-card">
          <span class="badge text-bg-warning mb-2">PYQ</span>
          <h6 class="fw-bold"><?= htmlspecialchars($pyq['subject']) ?></h6>
          <div class="signal">Year: <?= htmlspecialchars($pyq['year']) ?></div>
          <a class="btn btn-sm btn-outline-warning mt-3" href="<?= htmlspecialchars($pyq['file_path']) ?>" download>Download</a>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
