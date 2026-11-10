<?php
include 'db.php';
$id = intval($_GET['id'] ?? 0);
if ($id > 0) {
    mysqli_query($conn, "DELETE FROM attendance WHERE employeeID = $id");
    mysqli_query($conn, "DELETE FROM employees WHERE employeeID = $id");
}
header('Location: employees.php');
exit;
