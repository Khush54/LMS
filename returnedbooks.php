<?php
session_start();
if (!isset($_SESSION['student_id'])) {
    header("Location: index.html");
    exit();
}
$userId = intval($_SESSION['student_id']);
include("config.php");
$sql = "SELECT b.title, b.author, rb.actual_return_date
        FROM returned_books rb
        JOIN books b ON rb.book_id = b.id
        WHERE rb.student_id = ?
        ORDER BY rb.actual_return_date DESC";
$stmt = $connection->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$returnedBooks = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$connection->close();
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="auto">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Your Returned Books</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
            padding: 20px 10px;
            background-color: var(--bs-body-bg); 
        }

        .books-wrapper {
            max-width: 800px;
            margin: 0 auto;
        }

        .book-card {
             background-color: var(--bs-tertiary-bg); 
            color: var(--bs-body-color);
            border-radius: 12px;
            margin-bottom: 20px;
            padding: 15px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            border-left: 5px solid #198754; 
            border-top: none;
            border-right: none;
            border-bottom: none;
        }
        
        .info-group {
            margin-bottom: 10px;
            display: flex;
            flex-direction: column;
        }

        .info-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            color: #198754;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .info-value {
            font-size: 0.95rem;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .date-badge {
            background-color: var(--bs-success-border-subtle);
            color: var(--bs-success-text-emphasis);
            padding: 3px 10px;
            border-radius: 6px;
            font-size: 0.85rem;
            display: inline-block;
        }

        @media (min-width: 600px) {
             .book-card {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 15px;
            }
        }

        .main-heading {
            color: var(--bs-heading-color); 
        }
    </style>
</head>
<body>

<div class="container books-wrapper">
    <h3 class="text-center fw-bold mb-4" style="color: var(--bs-heading-color);">📥 My Returned Books</h3>

    <?php if (empty($returnedBooks)): ?>
        <div class="alert alert-success text-center border-0 shadow-sm">
           No Book has been returned.
        </div>
    <?php else: ?>
        <?php foreach ($returnedBooks as $book): ?>
            <div class="book-card">
                <div class="info-group">
                    <span class="info-label">Book Title</span>
                    <span class="info-value fw-bold"><?= htmlspecialchars($book['title']) ?></span>
                </div>
                
                <div class="info-group">
                    <span class="info-label">Author</span>
                    <span class="info-value"><?= htmlspecialchars($book['author']) ?></span>
                </div>

                <div class="info-group">
                    <span class="info-label">Status</span>
                    <span class="info-value text-success fw-bold">Returned ✅</span>
                </div>

                <div class="info-group">
                    <span class="info-label">Date of Return</span>
                    <div class="info-value">
                        <span class="date-badge fw-bold">
                            <?= date('d M Y', strtotime($book['actual_return_date'])) ?>
                        </span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>