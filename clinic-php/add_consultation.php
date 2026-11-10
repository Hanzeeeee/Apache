<?php

include "db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $consultID = isset($_POST['consultID']) ? intval($_POST['consultID']) : 0;
    $patID = isset($_POST['patID']) ? intval($_POST['patID']) : 0;
    $docID = isset($_POST['docID']) ? intval($_POST['docID']) : 0;
    $consultDate = isset($_POST['consultDate']) ? str_replace('T', ' ', trim($_POST['consultDate'])) : '';
    $diagnosis = trim($_POST['diagnosis'] ?? '');
    $prescription = trim($_POST['prescription'] ?? '');

    $stmt = $conn->prepare("INSERT INTO consultation (consultID, patID, docID, consultDate, diagnosis, prescription) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("iiisss", $consultID, $patID, $docID, $consultDate, $diagnosis, $prescription);
        $stmt->execute();
        $stmt->close();
    }
}

header("Location: consultations.php");
exit;
?>

