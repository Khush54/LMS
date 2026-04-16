<?php
session_start();
include("config.php");

if (isset($_POST['admin-login'])) {

    $user_input = trim($_POST['admin-name']); 
    $password = trim($_POST['password']);
    $sql = "SELECT * FROM admindetails WHERE Email = ? OR ID = ?";
    $stmt = $connection->prepare($sql);

    if (!$stmt) {
        die("Query Failed: " . $connection->error);
    }

    $stmt->bind_param("ss", $user_input, $user_input);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 1) {

        $row = $result->fetch_assoc();
        if ($password === $row['Password']) {

            // Store session
            $_SESSION['admin_id'] = $row['ID'];
            $_SESSION['admin_name'] = $row['Name'];
            $_SESSION['admin_email'] = $row['Email'];

            echo "
            <html>
            <head>
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            </head>
            <body>
            <script>
            Swal.fire({
                icon: 'success',
                title: 'Login Successful 🎉',
                text: 'Welcome, " . addslashes($row['Name']) . "!',
                confirmButtonText: 'Continue'
            }).then(() => {
                window.location.href = 'home.html';
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
                title: 'Wrong Password ❌',
                text: 'Please enter the correct password.',
                confirmButtonText: 'Try Again'
            }).then(() => {
                window.location.href = 'index.html';
            });
            </script>
            </body>
            </html>
            ";
            exit();
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
            icon: 'warning',
            title: 'Admin Not Found ⚠️',
            text: 'No account found with this Email or ID.',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location.href = 'index.html';
        });
        </script>
        </body>
        </html>
        ";
        exit();
    }

    $stmt->close();
}

$connection->close();
?>