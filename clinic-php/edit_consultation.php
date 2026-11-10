<?php
include "db.php";

$message = '';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $consultID = isset($_POST['consultID']) ? intval($_POST['consultID']) : 0;
    $patID = isset($_POST['patID']) ? intval($_POST['patID']) : 0;
    $docID = isset($_POST['docID']) ? intval($_POST['docID']) : 0;
    $consultDate = isset($_POST['consultDate']) ? str_replace('T', ' ', trim($_POST['consultDate'])) : '';
    $diagnosis = trim($_POST['diagnosis'] ?? '');
    $prescription = trim($_POST['prescription'] ?? '');

    if ($consultID > 0) {
        $stmt = $conn->prepare("UPDATE consultation SET patID = ?, docID = ?, consultDate = ?, diagnosis = ?, prescription = ? WHERE consultID = ?");
        if ($stmt) {
            $stmt->bind_param("iisssi", $patID, $docID, $consultDate, $diagnosis, $prescription, $consultID);
            $stmt->execute();
            $stmt->close();
            header("Location: consultations.php");
            exit;
        }
    }

    $message = 'Unable to update consultation record.';
} elseif ($id > 0) {
    $stmt = $conn->prepare("SELECT consultation.consultID, consultation.patID, consultation.docID, consultation.consultDate, consultation.diagnosis, consultation.prescription, patient.patFName, patient.patLName, doctor.docFName, doctor.docLName, (SELECT COUNT(*) FROM consultation) AS total_records FROM consultation LEFT JOIN patient ON consultation.patID = patient.patID LEFT JOIN doctor ON consultation.docID = doctor.docID WHERE consultation.consultID = ?");
    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $consultation = $result->fetch_assoc();
        $stmt->close();
    }
}

$consultation = $consultation ?? null;
$consultDateValue = $consultation['consultDate'] ?? '';
$consultDateValue = str_replace(' ', 'T', $consultDateValue);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Consultation</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <a href="consultations.php">Back</a>
    <h1>Edit Consultation</h1>
    <?php if ($message !== ''): ?>
        <p><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <?php if ($consultation): ?>
    <form action="edit_consultation.php?id=<?php echo urlencode($consultation['consultID']); ?>" method="POST">
        <input type="number" name="consultID" value="<?php echo htmlspecialchars($consultation['consultID']); ?>" required>
        <input type="number" name="patID" value="<?php echo htmlspecialchars($consultation['patID']); ?>" required>
        <input type="number" name="docID" value="<?php echo htmlspecialchars($consultation['docID']); ?>" required>
        <input type="datetime-local" name="consultDate" value="<?php echo htmlspecialchars($consultDateValue); ?>" required>
        <input type="text" name="diagnosis" value="<?php echo htmlspecialchars($consultation['diagnosis']); ?>">
        <input type="text" name="prescription" value="<?php echo htmlspecialchars($consultation['prescription']); ?>">
        <button type="submit">Update Consultation</button>
    </form>
    <?php else: ?>
        <p>No consultation record found.</p>
    <?php endif; ?>
</div>
</body>
</html>
