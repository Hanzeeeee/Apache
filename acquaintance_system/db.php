<?php
// db.php - database connection
// CHANGE the 4th value if the professor gives a new database name.
$conn = new mysqli("localhost", "root", "", "acquaintance_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
