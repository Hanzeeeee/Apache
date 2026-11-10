<?php
include "db.php";

$message = '';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $docID = isset($_POST['docID']) ? intval($_POST['docID']) : 0;
    $docFName = trim($_POST['docFName'] ?? '');
    $docLName = trim($_POST['docLName'] ?? '');
    $docAddress = trim($_POST['docAddress'] ?? '');
    $docSpecial = trim($_POST['docSpecial'] ?? '');

    if ($docID > 0) {
        $stmt = $conn->prepare("UPDATE doctor SET docFName = ?, docLName = ?, docAddress = ?, docSpecial = ? WHERE docID = ?");
        if ($stmt) {
            $stmt->bind_param("ssssi", $docFName, $docLName, $docAddress, $docSpecial, $docID);
            $stmt->execute();
            $stmt->close();
            header("Location: doctors.php");
            exit;
        }
    }

    $message = 'Unable to update doctor record.';
} elseif ($id > 0) {
    $stmt = $conn->prepare("SELECT docID, docFName, docLName, docAddress, docSpecial, (SELECT COUNT(*) FROM doctor) AS total_records FROM doctor WHERE docID = ?");
    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $doctor = $result->fetch_assoc();
        $stmt->close();
    }
}

$doctor = $doctor ?? null;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Doctor</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <a href="doctors.php">Back</a>
    <h1>Edit Doctor</h1>
    <?php if ($message !== ''): ?>
        <p><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <?php if ($doctor): ?>
    <form action="edit_doctor.php?id=<?php echo urlencode($doctor['docID']); ?>" method="POST">
        <input type="number" name="docID" value="<?php echo htmlspecialchars($doctor['docID']); ?>" required>
        <input type="text" name="docFName" value="<?php echo htmlspecialchars($doctor['docFName']); ?>" required>
        <input type="text" name="docLName" value="<?php echo htmlspecialchars($doctor['docLName']); ?>" required>
        <input type="text" name="docAddress" value="<?php echo htmlspecialchars($doctor['docAddress']); ?>">
        <input type="text" name="docSpecial" value="<?php echo htmlspecialchars($doctor['docSpecial']); ?>">
        <button type="submit">Update Doctor</button>
    </form>
    <?php else: ?>
        <p>No doctor record found.</p>
    <?php endif; ?>
</div>
</body>
</html>
