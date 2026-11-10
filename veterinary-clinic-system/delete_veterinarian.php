<?php
include 'db.php';
if(isset($_GET['id']) && $_GET['id'] !== ''){
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    mysqli_query($conn, "DELETE FROM veterinarians WHERE vetID='$id'");
}
header('Location: veterinarians.php');
exit;
?>

