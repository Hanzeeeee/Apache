<?php
include "db.php";

$countResult = $conn->query("SELECT COUNT(*) AS total_records FROM consultation");
$countData = $countResult->fetch_assoc();
$totalRecords = (int)($countData['total_records'] ?? 0);

$sql = "
SELECT consultation.consultID,
       consultation.patID,
       consultation.docID,
       consultation.consultDate,
       consultation.diagnosis,
       consultation.prescription,
       patient.patFName,
       patient.patLName,
       doctor.docFName,
       doctor.docLName,
       (SELECT COUNT(*) FROM consultation) AS total_records
FROM consultation
LEFT JOIN patient ON consultation.patID = patient.patID
LEFT JOIN doctor ON consultation.docID = doctor.docID
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Consultations</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">

<a href="index.php">Back</a>

<h1>Consultations</h1>
<p>Total consultations: <?php echo htmlspecialchars($totalRecords); ?></p>

<form action="add_consultation.php" method="POST">
    <input type="number" name="consultID" placeholder="Consultation ID" required>
    <input type="number" name="patID" placeholder="Patient ID" required>
    <input type="number" name="docID" placeholder="Doctor ID" required>
    <input type="datetime-local" name="consultDate" required>
    <input type="text" name="diagnosis" placeholder="Diagnosis">
    <input type="text" name="prescription" placeholder="Prescription">
    <button type="submit">Add Consultation</button>
</form>

<table>
    <tr>
        <th>ID</th>
        <th>Patient</th>
        <th>Doctor</th>
        <th>Date</th>
        <th>Diagnosis</th>
        <th>Prescription</th>
        <th>Action</th>
    </tr>

    <?php while ($row = $result->fetch_assoc()) {
        $patientName = trim((string)($row['patFName'] ?? '') . ' ' . (string)($row['patLName'] ?? '')) ?: 'Unknown';
        $doctorName = trim((string)($row['docFName'] ?? '') . ' ' . (string)($row['docLName'] ?? '')) ?: 'Unknown';
    ?>
    <tr>
        <td><?php echo htmlspecialchars($row['consultID']); ?></td>
        <td><?php echo htmlspecialchars($patientName); ?></td>
        <td><?php echo htmlspecialchars($doctorName); ?></td>
        <td><?php echo htmlspecialchars($row['consultDate']); ?></td>
        <td><?php echo htmlspecialchars($row['diagnosis']); ?></td>
        <td><?php echo htmlspecialchars($row['prescription']); ?></td>
        <td>
            <a href="edit_consultation.php?id=<?php echo urlencode($row['consultID']); ?>">Update</a>
            |
            <a href="delete_consultation.php?id=<?php echo urlencode($row['consultID']); ?>">Delete</a>
        </td>
    </tr>
    <?php } ?>
</table>

</div>

</body>
</html>

