<?php
include 'db.php';

if (isset($_GET['idNum'])) {
    $idNum = mysqli_real_escape_string($conn, $_GET['idNum']);
    $sql = "DELETE FROM registration WHERE idNum='$idNum'";
    mysqli_query($conn, $sql);
}

header("Location: registration.php");
exit();
?>
