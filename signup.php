<?php
session_start();

$conn = new mysqli("localhost", "root", "", "lms");

if ($conn->connect_error) {
    die("<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Connection Failed',
            text: '" . addslashes($conn->connect_error) . "',
            confirmButtonText: 'OK'
        }).then(() => {
            window.history.back();
        });
    </script>");
}

$admin_name = $_POST['admin-name'];
$admin_id = $_POST['admin-id'];
$admin_email = $_POST['admin-email'];
$password = $_POST['password']; 

$sql = "INSERT INTO admindetails (Name, ID, Email, Password)
        VALUES ('$admin_name', '$admin_id', '$admin_email', '$password')";

if ($conn->query($sql) === TRUE) {
    echo "
    <html>
    <head>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    </head>
    <body>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Signup Successful!',
            text: 'Admin registered successfully.',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location.href = 'index.html';
        });
    </script>
    </body>
    </html>
    ";
    exit();
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
            title: 'Error',
            text: '" . addslashes($conn->error) . "',
            confirmButtonText: 'Try Again'
        }).then(() => {
            window.history.back();
        });
    </script>
    </body>
    </html>
    ";
    exit();
}

$conn->close();
?>
