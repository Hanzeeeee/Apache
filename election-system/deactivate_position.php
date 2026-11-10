<?php
include "db.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    $stmt = $conn->prepare("SELECT posStat FROM positions WHERE posID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $newStat = ($row['posStat'] === 'Active') ? 'Inactive' : 'Active';
        $stmt2 = $conn->prepare("UPDATE positions SET posStat = ? WHERE posID = ?");
        $stmt2->bind_param("si", $newStat, $id);
        $stmt2->execute();
        $stmt2->close();
    }
    $stmt->close();
}

$conn->close();
header("Location: positions.php");
exit();
?>
