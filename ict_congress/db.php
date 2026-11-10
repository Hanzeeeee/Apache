<?php
// ==========================================================
// Database Connection File
// Edit these values if your XAMPP MySQL settings are different
// ==========================================================
$host = "localhost";
$username = "root";
$password = "";
$database = "ict_congress_db";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Fixed list of campuses used throughout the system
$campusList = array("Main", "Banilad", "LM", "Pardo");
?>
