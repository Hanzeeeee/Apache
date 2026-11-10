<?php
include 'db.php';
$id=$_GET['id'];
mysqli_query($conn,"DELETE FROM consultations WHERE consultID='$id'");
header('Location: consultations.php');
?>
