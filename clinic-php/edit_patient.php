<?php
include "db.php";

$message = '';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patID = isset($_POST['patID']) ? intval($_POST['patID']) : 0;
    $patFName = trim($_POST['patFName'] ?? '');
    $patLName = trim($_POST['patLName'] ?? '');
    $patBDate = trim($_POST['patBDate'] ?? '');
    $patTelNo = trim($_POST['patTelNo'] ?? '');

    if ($patID > 0) {
        $stmt = $conn->prepare("UPDATE patient SET patFName = ?, patLName = ?, patBDate = ?, patTelNo = ? WHERE patID = ?");
        if ($stmt) {
            $stmt->bind_param("ssssi", $patFName, $patLName, $patBDate, $patTelNo, $patID);
            $stmt->execute();
            $stmt->close();
            header("Location: patients.php");
            exit;
        }
    }

    $message = 'Unable to update patient record.';
} elseif ($id > 0) {
    $stmt = $conn->prepare("SELECT patID, patFName, patLName, patBDate, patTelNo, (SELECT COUNT(*) FROM patient) AS total_records FROM patient WHERE patID = ?");
    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $patient = $result->fetch_assoc();
        $stmt->close();
    }
}

$patient = $patient ?? null;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Patient</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <a href="patients.php">Back</a>
    <h1>Edit Patient</h1>
    <?php if ($message !== ''): ?>
        <p><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <?php if ($patient): ?>
    <form action="edit_patient.php?id=<?php echo urlencode($patient['patID']); ?>" method="POST">
        <input type="number" name="patID" value="<?php echo htmlspecialchars($patient['patID']); ?>" required>
        <input type="text" name="patFName" value="<?php echo htmlspecialchars($patient['patFName']); ?>" required>
        <input type="text" name="patLName" value="<?php echo htmlspecialchars($patient['patLName']); ?>" required>
        <input type="date" name="patBDate" value="<?php echo htmlspecialchars($patient['patBDate']); ?>">
        <input type="text" name="patTelNo" value="<?php echo htmlspecialchars($patient['patTelNo']); ?>">
        <button type="submit">Update Patient</button>
    </form>
    <?php else: ?>
        <p>No patient record found.</p>
    <?php endif; ?>
</div>
</body>
</html>
