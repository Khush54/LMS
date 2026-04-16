<?php
session_start();
include("config.php");

if (isset($_POST['student-login'])) {

    $username = trim($_POST['student-username']);
    $password = trim($_POST['student-password']);
    $sql = "SELECT * FROM students WHERE email = ? OR roll_number = ?";
    $stmt = $connection->prepare($sql);

    if (!$stmt) {
        die("Query Failed: " . $connection->error);
    }

    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 1) {

        $student = $result->fetch_assoc();
        if ($password === $student['password']) {

            $_SESSION['student_id'] = $student['id'];
            $_SESSION['student_name'] = $student['name'];
            $_SESSION['student_email'] = $student['email'];
            $_SESSION['student_course'] = $student['course'];

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
                    text: 'Welcome, " . addslashes($student['name']) . "!',
                    confirmButtonText: 'Continue'
                }).then(() => {
                    window.location.href = 'user_home.php';
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
                title: 'User Not Found ⚠️',
                text: 'No account found with this Email or Roll Number.',
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