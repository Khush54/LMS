<?php
include("config.php");
function alertAndRedirect($icon, $title, $text, $redirect = 'review_notes.php') {
    echo "
    <html>
    <head>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    </head>
    <body>
    <script>
        Swal.fire({
            icon: '$icon',
            title: '$title',
            text: '$text',
            confirmButtonText: 'OK',
            backdrop: false
        }).then(() => {
            window.location.href = '$redirect';
        });
    </script>
    </body>
    </html>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['id'])) {
    $id = intval($_POST['id']);
    $action = $_POST['action'];

    if (!in_array($action, ['approve', 'reject'])) {
        alertAndRedirect('error', 'Invalid Action', 'Invalid action specified.');
    }

    $new_status = ($action === 'approve') ? 'approved' : 'rejected';

    $stmt = $connection->prepare("UPDATE notes_requests SET status = ? WHERE id = ?");
    if (!$stmt) {
        alertAndRedirect('error', 'Database Error', 'Failed to prepare statement: ' . $connection->error);
    }
    $stmt->bind_param("si", $new_status, $id);

    if ($stmt->execute()) {
        $stmt->close();
        alertAndRedirect('success', ucfirst($action) . 'd', "Notes request has been $new_status successfully.");
    } else {
        $stmt->close();
        alertAndRedirect('error', 'Database Error', 'Failed to update request status: ' . $connection->error);
    }
}

$pendingRequests = $connection->query("SELECT * FROM notes_requests WHERE status = 'pending' ORDER BY uploaded_at DESC");
$approvedNotes = $connection->query("SELECT * FROM notes_requests WHERE status = 'approved' ORDER BY uploaded_at DESC");
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="auto">
<head>
  <meta charset="UTF-8" />
  <title>Review Notes</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

    :root { --accent-blue: #3d5a80; }

    body {
      font-family: 'Poppins', sans-serif;
      background-color: transparent;
      padding: 20px;
    }

    .section-header {
      border-left: 5px solid var(--accent-blue);
      padding-left: 15px;
      margin-bottom: 25px;
    }

    .custom-card {
      background-color: var(--bs-tertiary-bg);
      border-radius: 12px;
      border: 1px solid var(--bs-border-color);
      margin-bottom: 20px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      transition: transform 0.2s;
    }

    .card-body { padding: 1.5rem; }

    .subject-title {
      font-weight: 600;
      font-size: 1.1rem;
      color: var(--bs-emphasis-color);
      word-break: break-word;
    }

    .btn-action {
      font-weight: 600;
      font-size: 0.85rem;
      padding: 8px 16px;
      border-radius: 8px;
      transition: 0.3s;
    }

    @media (min-width: 769px) {
      .card-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
      }
      .action-container {
        display: flex;
        gap: 10px;
      }
      .btn-action { width: auto; min-width: 120px; }
    }

    @media (max-width: 768px) {
      body { padding: 10px; }
      .card-body { padding: 1rem; }
      .card-row { text-align: center; }
      .info-area { margin-bottom: 15px; }
      .action-container {
        display: flex;
        flex-direction: column;
        gap: 8px;
        width: 100%;
      }
      .btn-action { width: 100%; }
      .subject-title { font-size: 1rem; }
    }
  </style>
</head>
<body>

  <div class="container py-2">
    
    <div class="section-header">
      <h3 class="fw-bold mb-0 text-primary">Pending Notes Requests</h3>
      <p class="text-muted small">Verify and publish student notes</p>
    </div>

    <?php if ($pendingRequests && $pendingRequests->num_rows > 0): ?>
      <?php while ($row = $pendingRequests->fetch_assoc()): ?>
        <div class="custom-card shadow-sm">
          <div class="card-body">
            <div class="card-row">
              <div class="info-area">
                <span class="subject-title text-uppercase d-block mb-1"><?= htmlspecialchars($row['subject']) ?></span>
                <a href="<?= htmlspecialchars($row['file_path']) ?>" target="_blank" class="text-decoration-none small fw-bold" style="color: var(--accent-blue);">
                  <i class="bi bi-file-earmark-pdf-fill me-1"></i> Preview Notes
                </a>
              </div>
              
              <form method="POST" class="action-container">
                <input type="hidden" name="id" value="<?= intval($row['id']) ?>">
                <button type="submit" name="action" value="approve" class="btn btn-success btn-action shadow-sm">
                   <i class="bi bi-check-circle"></i> Approve
                </button>
                <button type="submit" name="action" value="reject" class="btn btn-danger btn-action shadow-sm">
                   <i class="bi bi-x-circle"></i> Reject
                </button>
              </form>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="alert alert-info border-0 shadow-sm">No pending notes at the moment.</div>
    <?php endif; ?>

    <hr class="my-5 opacity-25">

    <div class="section-header">
      <h3 class="fw-bold mb-0 text-success">Approved Notes</h3>
    </div>

    <div class="row g-3">
    <?php if ($approvedNotes && $approvedNotes->num_rows > 0): ?>
      <?php while ($note = $approvedNotes->fetch_assoc()): ?>
        <div class="col-12 col-md-6 col-lg-4">
          <div class="custom-card h-100 shadow-sm">
            <div class="card-body d-flex flex-column justify-content-between text-center text-md-start">
              <div class="mb-3">
                <span class="subject-title d-block mb-2"><?= htmlspecialchars($note['subject']) ?></span>
                <p class="text-muted small">Publicly accessible.</p>
              </div>
              <a href="<?= htmlspecialchars($note['file_path']) ?>" download class="btn btn-primary btn-action w-100 mt-auto" style="background-color: var(--accent-blue); border:none;">
                <i class="bi bi-cloud-arrow-down-fill me-1"></i> Download
              </a>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="col-12">
        <div class="alert alert-secondary border-0 shadow-sm">No approved notes found.</div>
      </div>
    <?php endif; ?>
    </div>

  </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $connection->close(); ?>