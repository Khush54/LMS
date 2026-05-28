<?php
require_once("auth.php");
require_admin();
include("config.php");

$topBooks = $connection->query("
    SELECT b.title, b.category, COUNT(*) AS issued_count
    FROM issued_books ib
    JOIN books b ON b.id = ib.book_id
    GROUP BY b.id, b.title, b.category
    ORDER BY issued_count DESC, b.title
    LIMIT 5
");

$categoryDemand = $connection->query("
    SELECT category, SUM(copies) AS total_copies, COUNT(*) AS book_count
    FROM books
    GROUP BY category
    ORDER BY book_count DESC, category
    LIMIT 8
");

$overdue = $connection->query("
    SELECT s.name, b.title, ib.return_date, DATEDIFF(CURDATE(), ib.return_date) AS days_late
    FROM issued_books ib
    JOIN students s ON s.id = ib.student_id
    JOIN books b ON b.id = ib.book_id
    WHERE ib.return_date < CURDATE()
    ORDER BY days_late DESC
    LIMIT 6
");

$resourceStats = $connection->query("
    SELECT 'Notes' AS type, status, COUNT(*) AS total FROM notes_requests GROUP BY status
    UNION ALL
    SELECT 'PYQs' AS type, status, COUNT(*) AS total FROM pyq_requests GROUP BY status
    ORDER BY type, status
");
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="auto">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Library Analytics</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
    body { font-family: "Poppins", sans-serif; background: transparent; padding: 20px; }
    .analytics-wrap { max-width: 1100px; margin: auto; }
    .panel {
      background: var(--bs-tertiary-bg);
      border: 1px solid var(--bs-border-color);
      border-radius: 14px;
      padding: 20px;
      height: 100%;
      box-shadow: 0 8px 24px rgba(0,0,0,.08);
    }
    .bar {
      height: 9px;
      border-radius: 999px;
      background: linear-gradient(90deg, #3d5a80, #10b981);
    }
  </style>
</head>
<body>
<div class="analytics-wrap">
  <div class="mb-4">
    <h3 class="fw-bold mb-1"><i class="bi bi-graph-up-arrow me-2"></i>Operational Analytics</h3>
    <p class="text-muted mb-0">Signals that help an interviewer see this as a decision-support system, not only CRUD.</p>
  </div>

  <div class="row g-4">
    <div class="col-lg-6">
      <div class="panel">
        <h5 class="fw-bold mb-3">Most Issued Books</h5>
        <?php if ($topBooks && $topBooks->num_rows > 0): ?>
          <?php while ($book = $topBooks->fetch_assoc()): ?>
            <div class="mb-3">
              <div class="d-flex justify-content-between gap-3">
                <span class="fw-semibold"><?= htmlspecialchars($book['title']) ?></span>
                <span class="badge text-bg-primary"><?= intval($book['issued_count']) ?></span>
              </div>
              <div class="small text-muted mb-1"><?= htmlspecialchars($book['category']) ?></div>
              <div class="bar" style="width: <?= min(100, intval($book['issued_count']) * 20) ?>%"></div>
            </div>
          <?php endwhile; ?>
        <?php else: ?>
          <div class="alert alert-info border-0 mb-0">No issue data yet.</div>
        <?php endif; ?>
      </div>
    </div>

    <div class="col-lg-6">
      <div class="panel">
        <h5 class="fw-bold mb-3">Category Coverage</h5>
        <?php while ($cat = $categoryDemand->fetch_assoc()): ?>
          <div class="d-flex justify-content-between border-bottom py-2">
            <span><?= htmlspecialchars($cat['category']) ?></span>
            <span class="fw-semibold"><?= intval($cat['book_count']) ?> titles · <?= intval($cat['total_copies']) ?> copies</span>
          </div>
        <?php endwhile; ?>
      </div>
    </div>

    <div class="col-lg-6">
      <div class="panel">
        <h5 class="fw-bold mb-3">Overdue Watchlist</h5>
        <?php if ($overdue && $overdue->num_rows > 0): ?>
          <?php while ($row = $overdue->fetch_assoc()): ?>
            <div class="border-bottom py-2">
              <div class="fw-semibold"><?= htmlspecialchars($row['name']) ?></div>
              <div class="small text-muted"><?= htmlspecialchars($row['title']) ?> · <?= intval($row['days_late']) ?> days late</div>
            </div>
          <?php endwhile; ?>
        <?php else: ?>
          <div class="alert alert-success border-0 mb-0">No overdue books right now.</div>
        <?php endif; ?>
      </div>
    </div>

    <div class="col-lg-6">
      <div class="panel">
        <h5 class="fw-bold mb-3">Digital Resource Pipeline</h5>
        <?php if ($resourceStats && $resourceStats->num_rows > 0): ?>
          <?php while ($stat = $resourceStats->fetch_assoc()): ?>
            <div class="d-flex justify-content-between border-bottom py-2">
              <span><?= htmlspecialchars($stat['type']) ?> · <?= htmlspecialchars(ucfirst($stat['status'])) ?></span>
              <span class="badge text-bg-secondary"><?= intval($stat['total']) ?></span>
            </div>
          <?php endwhile; ?>
        <?php else: ?>
          <div class="alert alert-info border-0 mb-0">No resource submissions yet.</div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $connection->close(); ?>
