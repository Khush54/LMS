<?php
include("config.php");

$sql = "SELECT id, subject, file_path FROM pyq_requests WHERE status = 'approved'";
$result = $connection->query($sql);
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="auto">
<head>
  <meta charset="UTF-8">
  <title>Approved PYQs</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');

    :root {
      --primary-green: #064e3b;
      --hover-green: #10b981;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background-color: var(--bs-body-bg);
      color: var(--bs-body-color);
      padding: 20px 10px;
    }

    .main-wrapper {
      max-width: 900px;
      margin: 0 auto;
    }

    .header-section {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 2rem;
      flex-wrap: wrap;
      gap: 15px;
    }

    .text-green {
      color: var(--primary-green) !important;
    }

    .custom-card {
      background-color: var(--bs-tertiary-bg);
      border-radius: 12px;
      border-left: 6px solid var(--primary-green);
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      padding: 15px;
      margin-bottom: 15px;
      transition: transform 0.2s;
    }

    .custom-card:hover {
      transform: translateY(-3px);
    }

    .btn-download {
      background-color: var(--primary-green);
      color: white;
      border-radius: 8px;
      border: none;
      padding: 8px 16px;
      font-weight: 500;
      width: 100%; 
      text-decoration: none;
      display: inline-block;
      text-align: center;
    }

    .btn-download:hover {
      background-color: var(--hover-green);
      color: white;
    }

    .btn-back {
      border-color: var(--primary-green);
      color: var(--primary-green);
      font-weight: 600;
      text-decoration: none;
    }

    .btn-back:hover {
      background-color: var(--primary-green);
      color: white;
    }

    @media (min-width: 600px) {
      .custom-card {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 25px;
      }
      .note-info {
        flex: 1;
      }
      .btn-download {
        width: auto;
      }
    }

    @media (max-width: 300px) {
      .header-section {
        justify-content: center;
        text-align: center;
      }
      .note-title {
        font-size: 0.85rem;
      }
      .btn-download {
        font-size: 0.8rem;
        padding: 6px 10px;
      }
    }
  </style>
</head>
<body>

<div class="container main-wrapper">
  
  <div class="header-section">
    <h3 class="fw-bold text-green mb-0">
      <i class="bi bi-file-earmark-check-fill me-2"></i> Approved PYQs
    </h3>
    <a href="upload_pyq.html" class="btn btn-outline-secondary btn-sm btn-back shadow-sm">
      <i class="bi bi-arrow-left"></i> Back
    </a>
  </div>

  <div class="pyq-container">
    <?php if ($result->num_rows > 0): ?>
      <?php while($row = $result->fetch_assoc()): ?>
        <div class="custom-card">
          <div class="note-info">
            <div class="text-muted small text-uppercase fw-bold" style="font-size: 0.65rem; letter-spacing: 1px;">Subject / Paper</div>
            <div class="note-title fw-bold mb-3 mb-md-0"><?= htmlspecialchars($row['subject']) ?></div>
          </div>
          <div class="note-action">
            <a href="<?= htmlspecialchars($row['file_path']) ?>" class="btn btn-download shadow-sm" download>
              <i class="bi bi-cloud-arrow-down-fill me-2"></i> Download PDF
            </a>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="alert alert-info text-center border-0 shadow-sm">
        <i class="bi bi-info-circle me-2"></i> No Approved PYQs.
      </div>
    <?php endif; ?>
  </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$connection->close();
?>