<?php
// Database connection
$host = 'localhost';
$user = 'root';
$pass = '';   // agar password hai toh likh
$db   = 'lms';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get all book titles
$sql = "SELECT title FROM books";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Book Search</title>
</head>
<body>
  <h2>Search Books</h2>

  <input list="books" name="book" placeholder="Type a book title...">
  <datalist id="books">
    <?php
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<option value='" . htmlspecialchars($row['title']) . "'>";
        }
    }
    ?>
  </datalist>

</body>
</html>
