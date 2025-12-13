<?php
session_start();

$conn = new mysqli("localhost", "root", "", "lms");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$admin_name = $_POST['admin-name'];
$admin_id = $_POST['admin-id'];
$admin_email = $_POST['admin-email'];
$password = $_POST['password']; 

$sql = "INSERT INTO admindetails (Name, ID, Email, Password)
        VALUES ('$admin_name', '$admin_id', '$admin_email', '$password')";

if ($conn->query($sql) === TRUE) {
    echo "Signup Successful!";
    header("Location: index.php?msg=Signup Successfully");
    exit();
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>

