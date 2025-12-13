<?php
session_start();

// DB connection
$conn = new mysqli("localhost", "root", "", "lms");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['student-login'])) {
    $username = $_POST['student-username'];
    $password = $_POST['student-password'];

    // Prepare statement to avoid SQL injection
    $sql = "SELECT * FROM students WHERE name = ? OR email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $student = $result->fetch_assoc();

        // Since password is stored plain, compare directly
        if ($password === $student['password']) {
            // Login success, set session variables
            $_SESSION['student_name'] = $student['name'];
            $_SESSION['student_id'] = $student['id']; // or any unique ID

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
                text: 'Welcome, " . addslashes($student['name']) . "',
                confirmButtonText: 'OK',
                backdrop: false
            }).then(() => {
                window.location.href = 'user_home.php';
            });
            </script>
            </body>
            </html>
            ";
            exit();
        } else {
            // Wrong password
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
            exit();
        }
    } else {
        // Username/email not found
        echo "
        <html>
        <head>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body>
        <script>
        Swal.fire({
            icon: 'error',
            title: 'User Not Found!',
            text: 'No user matches this name or email.',
            confirmButtonText: 'OK',
            backdrop: false
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

$conn->close();
?>
