<?php
// Database connection
$connection = new mysqli("localhost", "root", "", "lms");
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Get POST data
$studentId = $_POST['studentId'] ?? '';
$bookId = $_POST['bookId'] ?? '';
$actualReturnDate = $_POST['actualReturnDate'] ?? '';

// Function to show SweetAlert2 popup and redirect back
function alertAndBack($icon, $title, $text) {
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
            window.history.back();
        });
    </script>
    </body>
    </html>";
    exit;
}

// Check required fields
if (!$studentId || !$bookId || !$actualReturnDate) {
    alertAndBack('warning', 'Missing Fields', 'Please fill all required fields.');
}

// Validate date format YYYY-MM-DD
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $actualReturnDate)) {
    alertAndBack('error', 'Invalid Date', 'Invalid date format. Please use YYYY-MM-DD.');
}

// Step 1: Check if book is issued
$stmtCheckIssued = $connection->prepare("SELECT * FROM issued_books WHERE student_id = ? AND book_id = ?");
$stmtCheckIssued->bind_param("ii", $studentId, $bookId);
$stmtCheckIssued->execute();
$resultIssued = $stmtCheckIssued->get_result();

if ($resultIssued->num_rows == 0) {
    $stmtCheckIssued->close();
    $connection->close();
    alertAndBack('error', 'Not Found', 'No issued book record found for this student and book.');
}

// Step 2: Check if already returned
$stmtCheckReturned = $connection->prepare("SELECT * FROM returned_books WHERE student_id = ? AND book_id = ?");
$stmtCheckReturned->bind_param("ii", $studentId, $bookId);
$stmtCheckReturned->execute();
$resultReturned = $stmtCheckReturned->get_result();

if ($resultReturned->num_rows > 0) {
    $stmtCheckIssued->close();
    $stmtCheckReturned->close();
    $connection->close();
    alertAndBack('info', 'Already Returned', 'This book has already been returned by the student.');
}

// Step 3: Insert into returned_books
$stmtInsertReturn = $connection->prepare("INSERT INTO returned_books (student_id, book_id, actual_return_date) VALUES (?, ?, ?)");
$stmtInsertReturn->bind_param("iis", $studentId, $bookId, $actualReturnDate);

if ($stmtInsertReturn->execute()) {
    // Step 4: Delete from issued_books
    $stmtDeleteIssued = $connection->prepare("DELETE FROM issued_books WHERE student_id = ? AND book_id = ?");
    $stmtDeleteIssued->bind_param("ii", $studentId, $bookId);
    
    if ($stmtDeleteIssued->execute()) {
        echo "
        <html>
        <head>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Book Returned Successfully!',
                text: 'The book has been marked as returned and removed from issued books.',
                confirmButtonText: 'OK',
                backdrop: false
            }).then(() => {
                window.location.href = 'return.html';
            });
        </script>
        </body>
        </html>
        ";
    } else {
        $error = addslashes($connection->error);
        alertAndBack('error', 'Deletion Failed', "Error deleting issued book record: $error");
    }
    
    $stmtDeleteIssued->close();
} else {
    $error = addslashes($connection->error);
    alertAndBack('error', 'Insert Failed', "Error recording return: $error");
}

// Close statements and connection
$stmtCheckIssued->close();
$stmtCheckReturned->close();
$stmtInsertReturn->close();
$connection->close();
?>
