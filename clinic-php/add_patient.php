<?php
include "db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patID = isset($_POST['patID']) ? intval($_POST['patID']) : 0;
    $patFName = trim($_POST['patFName'] ?? '');
    $patLName = trim($_POST['patLName'] ?? '');
    $patBDate = trim($_POST['patBDate'] ?? '');
    $patTelNo = trim($_POST['patTelNo'] ?? '');

    $stmt = $conn->prepare("INSERT INTO patient (patID, patFName, patLName, patBDate, patTelNo) VALUES (?, ?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("issss", $patID, $patFName, $patLName, $patBDate, $patTelNo);
        $stmt->execute();
        $stmt->close();
    }
}

header("Location: patients.php");
exit;
?>
