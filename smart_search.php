<?php
require_once("auth.php");
require_portal_user();
include("config.php");

$query = trim($_GET['q'] ?? '');
$like = "%" . $query . "%";
$books = [];
$notes = [];
$pyqs = [];

if ($query !== '') {
    $stmt = $connection->prepare("
        SELECT id, title, author, category, copies
        FROM books
        WHERE title LIKE ? OR author LIKE ? OR category LIKE ? OR isbn LIKE ?
        ORDER BY title
        LIMIT 12
    ");
    $stmt->bind_param("ssss", $like, $like, $like, $like);
    $stmt->execute();
    $books = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    $stmt = $connection->prepare("
        SELECT id, subject, file_path, uploaded_at
        FROM notes_requests
        WHERE status = 'approved' AND subject LIKE ?
        ORDER BY uploaded_at DESC
        LIMIT 8
    ");
    $stmt->bind_param("s", $like);
    $stmt->execute();
    $notes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    $stmt = $connection->prepare("
        SELECT id, subject, year, file_path, submitted_at
        FROM pyq_requests
        WHERE status = 'approved' AND (subject LIKE ? OR year LIKE ?)
        ORDER BY submitted_at DESC
        LIMIT 8
    ");
    $stmt->bind_param("ss", $like, $like);
    $stmt->execute();
    $pyqs = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}

$connection->close();
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="auto">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Smart Search</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
    body { font-family: "Poppins", sans-serif; background: transparent; padding: 20px; }
    .search-panel {
      max-width: 1050px;
      margin: auto;
      background: var(--bs-tertiary-bg);
      border: 1px solid var(--bs-border-color);
      border-radius: 14px;
      padding: 24px;
      box-shadow: 0 8px 24px rgba(0,0,0,.08);
    }
    .result-card {
      border: 1px solid var(--bs-border-color);
      border-radius: 12px;
      background: var(--bs-body-bg);
      padding: 16px;
      height: 100%;
    }
    .type-badge { font-size: .72rem; letter-spacing: .04em; text-transform: uppercase; }
    .muted-small { color: var(--bs-secondary-color); font-size: .86rem; }
  </style>
</head>
<body>
<div class="search-panel">
  <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
      <h3 class="fw-bold mb-1"><i class="bi bi-search me-2"></i>Smart Search</h3>
      <p class="text-muted mb-0">Search books, approved notes, and PYQs from one place.</p>
    </div>
  </div>

  <form method="GET" class="input-group input-group-lg mb-4">
    <input type="search" name="q" class="form-control" value="<?= htmlspecialchars($query) ?>" placeholder="Try AI, compiler, cloud, 2024, CSE..." autofocus>
    <button class="btn btn-primary" type="submit"><i class="bi bi-arrow-right"></i></button>
  </form>

  <?php if ($query === ''): ?>
    <div class="alert alert-info border-0">Enter a subject, title, author, category, ISBN, or year to discover related resources.</div>
  <?php else: ?>
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="fw-bold mb-0">Results for "<?= htmlspecialchars($query) ?>"</h5>
      <span class="badge text-bg-secondary"><?= count($books) + count($notes) + count($pyqs) ?> matches</span>
    </div>

    <?php if (empty($books) && empty($notes) && empty($pyqs)): ?>
      <div class="alert alert-warning border-0">No matching resources found.</div>
    <?php endif; ?>

    <div class="row g-3">
      <?php foreach ($books as $book): ?>
        <div class="col-md-6 col-xl-4">
          <div class="result-card">
            <span class="badge text-bg-primary type-badge mb-2">Book</span>
            <h6 class="fw-bold mb-1"><?= htmlspecialchars($book['title']) ?></h6>
            <div class="muted-small">By <?= htmlspecialchars($book['author']) ?></div>
            <div class="muted-small">Category: <?= htmlspecialchars($book['category']) ?></div>
            <div class="mt-2 fw-semibold text-success"><?= intval($book['copies']) ?> copies available</div>
          </div>
        </div>
      <?php endforeach; ?>

      <?php foreach ($notes as $note): ?>
        <div class="col-md-6 col-xl-4">
          <div class="result-card">
            <span class="badge text-bg-success type-badge mb-2">Verified Notes</span>
            <h6 class="fw-bold mb-1"><?= htmlspecialchars($note['subject']) ?></h6>
            <div class="muted-small">Quality: admin approved resource</div>
            <a class="btn btn-sm btn-outline-success mt-3" href="<?= htmlspecialchars($note['file_path']) ?>" download>
              <i class="bi bi-download me-1"></i>Download
            </a>
          </div>
        </div>
      <?php endforeach; ?>

      <?php foreach ($pyqs as $pyq): ?>
        <div class="col-md-6 col-xl-4">
          <div class="result-card">
            <span class="badge text-bg-warning type-badge mb-2">PYQ</span>
            <h6 class="fw-bold mb-1"><?= htmlspecialchars($pyq['subject']) ?></h6>
            <div class="muted-small">Year: <?= htmlspecialchars($pyq['year']) ?></div>
            <div class="muted-small">Quality: admin approved resource</div>
            <a class="btn btn-sm btn-outline-warning mt-3" href="<?= htmlspecialchars($pyq['file_path']) ?>" download>
              <i class="bi bi-download me-1"></i>Download
            </a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
