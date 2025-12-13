<?php
session_start();

$conn = new mysqli("localhost", "root", "", "lms");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_input = $_POST['admin-name']; // Can be name or email
$password = $_POST['password'];     // Plain password

$sql = "SELECT * FROM admindetails WHERE Email = ? OR Name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $user_input, $user_input);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();

    if ($password === $row['Password']) {
        $_SESSION['admin_name'] = $row['Name'];
        echo "
        <html>
        <head>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body>
        <script>
        Swal.fire({
            icon: 'success',
            title: 'Login Successful!',
            text: 'Welcome, " . addslashes($row['Name']) . "',
            confirmButtonText: 'OK',
            backdrop: false
        }).then(() => {
            window.location.href = 'home.html';
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
            title: 'Incorrect Password!',
            text: 'Please try again.',
            confirmButtonText: 'OK',
            backdrop: false
        }).then(() => {
            window.location.href = 'index.html';
        });
        </script>
        </body>
        </html>
        ";
    }
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
        title: 'No Account Found!',
        text: 'No account matches this Email or Name.',
        confirmButtonText: 'OK',
        backdrop: false
    }).then(() => {
        window.location.href = 'index.html';
    });
    </script>
    </body>
    </html>
    ";
}

$stmt->close();
$conn->close();
?>
