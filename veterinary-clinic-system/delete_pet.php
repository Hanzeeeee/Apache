<?php
include 'db.php';
$id=$_GET['id'];
mysqli_query($conn,"DELETE FROM pets WHERE petID='$id'");
header('Location: pets.php');
?>
