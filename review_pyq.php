<?php
$conn = new mysqli("localhost", "root", "", "lms");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SweetAlert2 popup function
function alertAndRedirect($icon, $title, $text, $redirect = 'review_pyq.php') {
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

    $stmt = $conn->prepare("UPDATE pyq_requests SET status = ? WHERE id = ?");
    if (!$stmt) {
        alertAndRedirect('error', 'Database Error', 'Failed to prepare statement: ' . $conn->error);
    }
    $stmt->bind_param("si", $new_status, $id);

    if ($stmt->execute()) {
        $stmt->close();
        alertAndRedirect('success', ucfirst($action) . 'd', "PYQ request has been $new_status successfully.");
    } else {
        $stmt->close();
        alertAndRedirect('error', 'Database Error', 'Failed to update request status: ' . $conn->error);
    }
}

$pendingRequests = $conn->query("SELECT * FROM pyq_requests WHERE status = 'pending' ORDER BY submitted_at DESC");
$approvedPYQs = $conn->query("SELECT * FROM pyq_requests WHERE status = 'approved' ORDER BY submitted_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>PYQ Upload Requests & Approved PYQs</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 40px 20px;
      min-height: 100vh;
      color: black;
    }
    
    [data-bs-theme="dark"] body {
      color:rgb(156, 148, 148) !important;
    }
   
    
    .container {
      max-width: 850px;
      margin: 0 auto;
      padding: 30px 40px;
      border-radius: 14px;
      box-shadow: 0 10px 25px rgb(0 0 0 / 0.1);
    }

    h2.section-title {
      font-weight: 700;
      font-size: 1.8rem;
      margin-bottom: 24px;
      border-bottom: 3px solid #007bff;
      padding-bottom: 8px;
      color: #007bff;
    }

    .request-card, .pyq-card {
      border-radius: 12px;
      box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
      padding: 20px 25px;
      margin-bottom: 22px;
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      justify-content: space-between;
      transition: transform 0.15s ease-in-out;
    }
    .request-card:hover, .pyq-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 12px 24px rgba(0,0,0,0.12);
    }

    .request-info, .pyq-info {
      max-width: 60%;
      min-width: 200px;
    }

    .request-info p, .pyq-info p {
      margin: 6px 0;
      font-size: 1rem;
       color: inherit;
    }

    a.file-link {
      color: #007bff;
      font-weight: 600;
      text-decoration: none;
      transition: color 0.2s ease-in-out;
    }
    a.file-link:hover {
      color: #0056b3;
      text-decoration: underline;
    }

    form.action-buttons {
      display: flex;
      gap: 14px;
      flex-wrap: wrap;
      margin-top: 12px;
      flex-grow: 1;
      justify-content: flex-end;
      min-width: 120px;
    }

    button.btn-approve {
      background-color: #28a745;
      color: white;
      border: none;
      padding: 8px 18px;
      border-radius: 7px;
      font-weight: 600;
      cursor: pointer;
      transition: background-color 0.2s ease-in-out;
      flex: 1 1 auto;
      min-width: 100px;
    }
    button.btn-approve:hover {
      background-color: #218838;
    }

    button.btn-reject {
      background-color: #dc3545;
      color: white;
      border: none;
      padding: 8px 18px;
      border-radius: 7px;
      font-weight: 600;
      cursor: pointer;
      transition: background-color 0.2s ease-in-out;
      flex: 1 1 auto;
      min-width: 100px;
    }
    button.btn-reject:hover {
      background-color: #c82333;
    }

    a.btn-download {
      background-color: #007bff;
      color: white;
      padding: 10px 22px;
      border-radius: 8px;
      font-weight: 600;
      text-decoration: none;
      transition: background-color 0.25s ease-in-out;
      min-width: 130px;
      text-align: center;
      display: inline-block;
    }
    a.btn-download:hover {
      background-color: #0056b3;
      color: white;
    }

    /* Responsive */
    @media (max-width: 600px) {
      .request-info, .pyq-info {
        max-width: 100%;
      }
      form.action-buttons {
        justify-content: center;
        gap: 12px;
      }
      button.btn-approve, button.btn-reject {
        min-width: unset;
        flex-grow: 1;
      }
      a.btn-download {
        min-width: unset;
        padding: 10px 16px;
      }
    }
  </style>
</head>
<body>

  <div class="container">

    <h2 class="section-title">Pending PYQ Upload Requests</h2>

    <?php if ($pendingRequests && $pendingRequests->num_rows > 0): ?>
      <?php while ($row = $pendingRequests->fetch_assoc()): ?>
        <div class="request-card">
          <div class="request-info">
            <p><strong>Subject:</strong> <?= htmlspecialchars($row['subject']) ?></p>
            <p><strong>Year:</strong> <?= htmlspecialchars($row['year']) ?></p>
            <p><a href="<?= htmlspecialchars($row['file_path']) ?>" target="_blank" class="file-link" rel="noopener noreferrer">View Uploaded File</a></p>
          </div>

          <form method="POST" class="action-buttons">
            <input type="hidden" name="id" value="<?= intval($row['id']) ?>">
            <button type="submit" name="action" value="approve" class="btn-approve">✅ Approve</button>
            <button type="submit" name="action" value="reject" class="btn-reject">❌ Reject</button>
          </form>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p>No pending requests at the moment.</p>
    <?php endif; ?>

    <hr style="margin: 40px 0 32px 0; border-color: #007bff;">

    <h2 class="section-title">Approved PYQs</h2>

    <?php if ($approvedPYQs && $approvedPYQs->num_rows > 0): ?>
      <?php while ($pyq = $approvedPYQs->fetch_assoc()): ?>
        <div class="pyq-card">
          <div class="pyq-info">
            <p><strong>Subject:</strong> <?= htmlspecialchars($pyq['subject']) ?></p>
            <p><strong>Year:</strong> <?= htmlspecialchars($pyq['year']) ?></p>
          </div>
          <a href="<?= htmlspecialchars($pyq['file_path']) ?>" download class="btn-download" target="_blank" rel="noopener noreferrer">⬇️ Download</a>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p>No approved PYQs found.</p>
    <?php endif; ?>

  </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>
