<?php
include "db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $docID = isset($_POST['docID']) ? intval($_POST['docID']) : 0;
    $docFName = trim($_POST['docFName'] ?? '');
    $docLName = trim($_POST['docLName'] ?? '');
    $docAddress = trim($_POST['docAddress'] ?? '');
    $docSpecial = trim($_POST['docSpecial'] ?? '');

    $stmt = $conn->prepare("INSERT INTO doctor (docID, docFName, docLName, docAddress, docSpecial) VALUES (?, ?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("issss", $docID, $docFName, $docLName, $docAddress, $docSpecial);
        $stmt->execute();
        $stmt->close();
    }
}

header("Location: doctors.php");
exit;
?>
