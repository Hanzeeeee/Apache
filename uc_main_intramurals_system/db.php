<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "uc_main_intramurals";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
