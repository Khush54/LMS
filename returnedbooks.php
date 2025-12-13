<?php
session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: index.html");
    exit();
}

$userId = intval($_SESSION['student_id']);

$connection = new mysqli("localhost", "root", "", "lms");
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Fetch returned books for the logged-in student
$sql = "SELECT b.title, b.author, rb.actual_return_date
        FROM returned_books rb
        JOIN books b ON rb.book_id = b.id
        WHERE rb.student_id = ?
        ORDER BY rb.actual_return_date DESC";  // Order by latest return first
$stmt = $connection->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$returnedBooks = [];
while ($row = $result->fetch_assoc()) {
    $returnedBooks[] = $row;
}

$stmt->close();
$connection->close();
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="auto">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Your Returned Books</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" />

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            color: var(--bs-body-color);
            padding: 3rem 1rem;
            min-height: 100vh;
        }

        h2 {
            margin-bottom: 2.5rem;
            font-weight: 700;
            color: var(--bs-primary);
            text-align: center;
            letter-spacing: 1px;
        }

        .table-container {
            max-width: 900px;
            margin: 0 auto;
            overflow-x: auto;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
            border-radius: 15px;
            background-color: var(--bs-body-bg);
            padding: 1.5rem 1rem;
            transition: box-shadow 0.3s ease;
        }

        .table-container:hover {
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.18);
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 16px;
        }

        thead tr th {
            border-bottom: 3px solid var(--bs-primary);
            padding-bottom: 10px;
            font-weight: 700;
            color: var(--bs-primary);
            text-align: left;
            letter-spacing: 0.5px;
        }

        tbody tr {
            background-color: var(--bs-secondary-bg);
            transition: background-color 0.3s ease, transform 0.2s ease;
            border-radius: 12px;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.05);
            cursor: default;
        }

        tbody tr:hover {
            background-color: var(--bs-primary-bg);
            color: var(--bs-primary-text);
            transform: translateY(-3px);
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.12);
        }

        tbody tr td {
            padding: 14px 18px;
            vertical-align: middle;
            border-left: 4px solid transparent;
            transition: border-color 0.3s ease;
        }

        tbody tr:hover td {
            border-left: 4px solid var(--bs-primary);
        }

        /* Colors for light/dark themes */
        [data-bs-theme="light"] {
            --bs-body-color: #212529;
            --bs-border-color: #dee2e6;
            --bs-primary: #0d6efd;
            --bs-primary-bg: #e7f1ff;
            --bs-primary-text: #0d6efd;
            --bs-secondary-bg: #f8f9fa;
        }

        [data-bs-theme="dark"] {
            --bs-body-color: #e1e1e1;
            --bs-border-color: #343a40;
            --bs-primary: #66b2ff;
            --bs-primary-bg: #1a1a1a;
            --bs-primary-text: #66b2ff;
            --bs-secondary-bg: #2a2a2a;
        }

        /* Responsive for smaller screens */
        @media (max-width: 576px) {
            .table-container {
                padding: 1rem;
            }

            table thead {
                display: none;
            }

            table,
            tbody,
            tr,
            td {
                display: block;
                width: 100%;
            }

            tr {
                margin-bottom: 1.5rem;
                border-radius: 15px;
                background-color: var(--bs-secondary-bg);
                padding: 1.2rem 1rem;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
                transition: background-color 0.3s ease;
            }

            tr:hover {
                background-color: var(--bs-primary-bg);
                color: var(--bs-primary-text);
            }

            td {
                padding-left: 55%;
                position: relative;
                text-align: left;
                border-left: none !important;
            }

            td::before {
                position: absolute;
                left: 1rem;
                width: 45%;
                white-space: nowrap;
                font-weight: 600;
                content: attr(data-label);
                color: var(--bs-primary);
            }
        }

        /* Alert styling for no books */
        .alert-warning {
            max-width: 500px;
            margin: 3rem auto;
            padding: 1.5rem 2rem;
            font-weight: 600;
            font-size: 1.1rem;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(255, 193, 7, 0.3);
            text-align: center;
            background-color: #fff3cd;
            color: #856404;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        [data-bs-theme="dark"] .alert-warning {
            background-color: #5a4700;
            color: #ffec99;
            box-shadow: 0 4px 15px rgba(255, 193, 7, 0.6);
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Your Returned Books</h2>

        <?php if (count($returnedBooks) === 0): ?>
            <div class="alert alert-warning" role="alert">
                You have no returned books currently.
            </div>
        <?php else: ?>
            <div class="table-container" role="table" aria-label="List of returned books">
                <table>
                    <thead>
                        <tr>
                            <th scope="col">Title</th>
                            <th scope="col">Author</th>
                            <th scope="col">Return Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($returnedBooks as $book): ?>
                            <tr>
                                <td data-label="Title"><?= htmlspecialchars($book['title']) ?></td>
                                <td data-label="Author"><?= htmlspecialchars($book['author']) ?></td>
                                <td data-label="Return Date"><?= htmlspecialchars(date('d M Y', strtotime($book['actual_return_date']))) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
