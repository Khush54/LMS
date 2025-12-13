<?php
session_start();

// (Optional) Check if admin is logged in
if (!isset($_SESSION['admin_name'])) {
    echo "<script>alert('Please login first!'); window.location.href='index.html';</script>";
    exit();
}

// Connect to MySQL
$conn = new mysqli("localhost", "root", "", "lms");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Collect form data
$title        = $_POST['book-title'];
$author       = $_POST['author-name'];
$publisher    = $_POST['publisher'];
$isbn         = $_POST['isbn'];
$category     = $_POST['category'];
$copies       = $_POST['copies'];
$publish_date = $_POST['publish-date'];

// Insert using prepared statement
$sql = "INSERT INTO books (title, author, publisher, isbn, category, copies, publish_date) 
        VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssis", $title, $author, $publisher, $isbn, $category, $copies, $publish_date);

if ($stmt->execute()) {
    echo "
<html>
<head>
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
</head>
<body>
<script>
Swal.fire({
    icon: 'success',
    title: 'Book Added Successfully!',
    text: 'The book has been added.',
    confirmButtonText: 'OK',
    backdrop: false
}).then(() => {
    window.location.href = 'add_book.html';
});
</script>
</body>
</html>
";
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
    title: 'Failed to Add Book!',
    text: '" . addslashes($stmt->error) . "',
    confirmButtonText: 'Try Again',
    backdrop: false
}).then(() => {
    window.location.href = 'add_book.html';
});
</script>
</body>
</html>
";
}

$stmt->close();
$conn->close();
?>
