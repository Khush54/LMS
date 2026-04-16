<?php
include("config.php");

$studentId  = $_POST['studentId'] ?? '';
$bookId     = $_POST['bookId'] ?? '';
$issueDate  = $_POST['issueDate'] ?? '';
$returnDate = $_POST['returnDate'] ?? '';

if (!$studentId || !$bookId || !$issueDate || !$returnDate) {
    echo "
    <html>
    <head>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    </head>
    <body>
    <script>
        Swal.fire({
            icon: 'warning',
            title: 'Missing Fields!',
            text: 'Please fill all fields before submitting.',
            confirmButtonText: 'Back',
            backdrop: false
        }).then(() => {
            window.history.back();
        });
    </script>
    </body>
    </html>";
    exit;
}

$query = "INSERT INTO issued_books (student_id, book_id, issue_date, return_date) 
          VALUES ('$studentId', '$bookId', '$issueDate', '$returnDate')";

if ($connection->query($query) === TRUE) {
    echo "
<html>
<head>
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
</head>
<body>
<script>
Swal.fire({
    icon: 'success',
    title: 'Book Issued Successfully!',
    text: 'The book has been issued to the student.',
    confirmButtonText: 'OK',
    backdrop: false
}).then(() => {
    window.location.href = 'issue.html';
});
</script>
</body>
</html>";
} else {
    echo "
<html>
<head>
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
</head>
<body>
<script>
Swal.fire({
    icon: 'error',
    title: 'Error Issuing Book!',
    text: '" . addslashes($connection->error) . "',
    confirmButtonText: 'Try Again',
    backdrop: false
}).then(() => {
    window.location.href = 'issue.html';
});
</script>
</body>
</html>";
}

$connection->close();
?>
