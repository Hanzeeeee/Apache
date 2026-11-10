<?php
include 'db.php';
$id = intval($_GET['id'] ?? 0);
if ($id > 0) {
    mysqli_query($conn, "DELETE FROM departments WHERE departmentID = $id");
}
header('Location: departments.php');
exit;
