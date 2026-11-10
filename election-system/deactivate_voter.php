<?php
include "db.php";

$id = isset($_GET['id']) ? trim($_GET['id']) : "";

if ($id !== "") {
    $stmt = $conn->prepare("SELECT voterStat FROM voters WHERE voterID = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $newStat = ($row['voterStat'] === 'Active') ? 'Inactive' : 'Active';
        $stmt2 = $conn->prepare("UPDATE voters SET voterStat = ? WHERE voterID = ?");
        $stmt2->bind_param("ss", $newStat, $id);
        $stmt2->execute();
        $stmt2->close();
    }
    $stmt->close();
}

$conn->close();
header("Location: voters.php");
exit();
?>
