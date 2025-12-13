<?php
// Database connection
$connection = new mysqli("localhost", "root", "", "lms");
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

$result = $connection->query("SELECT * FROM books");
$books = [];
while ($row = $result->fetch_assoc()) {
    $books[] = $row;
}
$connection->close();
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="auto">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Book Stock</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: var(--bs-body-bg);
    }

    .book-card {
      border-radius: 20px;
      transition: all 0.3s ease-in-out;
      box-shadow: 0 6px 12px rgba(0, 0, 0, 0.05);
    }

    .book-card:hover {
      transform: scale(1.02);
      box-shadow: 0 12px 20px rgba(0, 0, 0, 0.15);
    }

    .book-title {
      font-size: 1.25rem;
      font-weight: 600;
    }

    .book-meta {
      font-size: 0.9rem;
      color: #6c757d;
    }

    [data-bs-theme="dark"] .book-card {
      box-shadow: 0 6px 12px rgba(255, 255, 255, 0.05);
    }

    [data-bs-theme="dark"] .book-card:hover {
      box-shadow: 0 12px 20px rgba(255, 255, 255, 0.1);
    }
  </style>
</head>

<body>
  <div class="container py-5">
    <h2 class="fw-bold mb-4">📖 Book Stock</h2>
    <div class="row g-4">
      <?php foreach ($books as $book): ?>
        <div class="col-lg-4 col-md-6">
          <div class="book-card p-4 border bg-body">
            <h5 class="book-title mb-1"><?php echo htmlspecialchars($book['title']); ?></h5>
            <p class="mb-1 book-meta">Author: <strong><?php echo htmlspecialchars($book['author']); ?></strong></p>
            <p class="mb-1 book-meta">Publisher: <?php echo htmlspecialchars($book['publisher']); ?></p>
            <p class="mb-1 book-meta">ISBN: <?php echo htmlspecialchars($book['isbn']); ?></p>
            <p class="mb-1 book-meta">Category: <?php echo htmlspecialchars($book['category']); ?></p>
            <p class="mb-1 book-meta">Copies Available: <span class="fw-bold text-success"><?php echo $book['copies']; ?></span></p>
            <p class="mb-0 book-meta">Published Date: <?php echo date('d M Y', strtotime($book['publish_date'])); ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>