<?php
session_start();

$conn = new mysqli("localhost", "root", "", "lms");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_input = $_POST['admin-name']; // Can be name or email
$password = $_POST['password'];     // Plain password

$sql = "SELECT * FROM admindetails WHERE Email = '$user_input' OR Name = '$user_input'";
$result = $conn->query($sql);

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();

    if ($password === $row['Password']) {
        $_SESSION['admin_name'] = $row['Name'];
        header("Location: admin/home.html");
        exit();
    } else {
        echo "Incorrect password!";
    }
} else {
    echo "No account found with this Email or Name!";
}

$conn->close();
?>
