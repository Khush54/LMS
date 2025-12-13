<?php
session_start();

// (Optional) Check if admin is logged in
if (!isset($_SESSION['admin_name'])) {
    echo "
    <html>
    <head>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    </head>
    <body>
    <script>
        Swal.fire({
            icon: 'warning',
            title: 'Please login first as admin!',
            confirmButtonText: 'OK',
            backdrop: false
        }).then(() => {
            window.location.href = 'index.html';
        });
    </script>
    </body>
    </html>";
    exit();
}

// DB connection
$conn = new mysqli("localhost", "root", "", "lms");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form values
$name     = $_POST['student-name'];
$roll     = $_POST['student-roll'];
$email    = $_POST['student-email'];
$course   = $_POST['student-course'];
$reg_id   = $_POST['reg-id'];
$password = $_POST['student-password'];  // No hashing here

// Prepare query
$sql = "INSERT INTO students (name, roll_number, email, course, reg_id, password) 
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssss", $name, $roll, $email, $course, $reg_id, $password);

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
            title: 'Student registered successfully!',
            confirmButtonText: 'OK',
            backdrop: false
        }).then(() => {
            window.location.href = 'register.html';
        });
    </script>
    </body>
    </html>";
} else {
    // Check duplicate entry (email or roll)
    $errorMsg = $stmt->error;
    $alertText = str_contains($errorMsg, "Duplicate entry") 
                 ? 'Roll number or email already exists!' 
                 : 'Error: ' + $errorMsg;

    // Escape the error message for JS safely
    $escapedError = addslashes($alertText);

    echo "
    <html>
    <head>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    </head>
    <body>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Registration Failed!',
            text: '$escapedError',
            confirmButtonText: 'OK',
            backdrop: false
        }).then(() => {
            window.location.href = 'register.html';
        });
    </script>
    </body>
    </html>";
}

$stmt->close();
$conn->close();
?>
