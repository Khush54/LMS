<?php
require_once("auth.php");
block_demo_admin('register.html');
include("config.php");
$name     = $_POST['student-name'];
$roll     = $_POST['student-roll'];
$email    = $_POST['student-email'];
$course   = $_POST['student-course'];
$reg_id   = $_POST['reg-id'];
$password = $_POST['student-password'];  
$sql = "INSERT INTO students (name, roll_number, email, course, reg_id, password) 
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $connection->prepare($sql);
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
    $errorMsg = $stmt->error;
    $alertText = str_contains($errorMsg, "Duplicate entry")
                 ? 'Roll number or email already exists!'
                 : 'Error: ' . $errorMsg;

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
$connection->close();
?>
