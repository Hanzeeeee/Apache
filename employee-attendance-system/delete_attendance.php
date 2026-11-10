<?php
include 'db.php';
$id = intval($_GET['id'] ?? 0);
if ($id > 0) {
    mysqli_query($conn, "DELETE FROM attendance WHERE attendanceID = $id");
}
header('Location: attendance.php');
exit;
