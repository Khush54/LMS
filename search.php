<?php

include("config.php");

// Get all book titles
$sql = "SELECT title FROM books";
$result = $connection->query($sql);
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
