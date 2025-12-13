<?php
// --- Database Connection ---
$host = "localhost";        // your DB host
$user = "root";             // your DB username
$password = "";             // your DB password
$dbname = "lms";       // your DB name

$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// --- Fetch approved notes ---
$sql = "SELECT id, subject, file_path FROM pyq_requests WHERE status = 'approved'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="auto">
<head>
  <meta charset="UTF-8">
  <title>Approved Notes</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      padding-top: 2rem;
    }

    .table-container {
      border-radius: 1rem;
      padding: 2rem;
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
    }

    .btn-download {
      border-radius: 0.5rem;
    }
  </style>
</head>
<body>

<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="text-primary fw-bold d-flex align-items-center">
      <i class="bi bi-folder2-open me-2"></i> Approved PYQs
    </h3>
    <a href="upload_notes.html" class="btn btn-outline-secondary">
      <i class="bi bi-arrow-left"></i> Back to Upload
    </a>
  </div>

  <div class="table-container">
    <?php if ($result->num_rows > 0): ?>
      <table class="table table-bordered align-middle">
        <thead class="table">
          <tr>
            <th>#</th>
            <th>Subject</th>
            <th>Download</th>
          </tr>
        </thead>
        <tbody>
          <?php $i = 1; while($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= $i++ ?></td>
              <td><?= htmlspecialchars($row['subject']) ?></td>
              <td>
                <a href="<?= htmlspecialchars($row['file_path']) ?>" class="btn btn-primary btn-download" download>
                  <i class="bi bi-download"></i> Download
                </a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p class="text-muted">No approved Pyqs found.</p>
    <?php endif; ?>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
