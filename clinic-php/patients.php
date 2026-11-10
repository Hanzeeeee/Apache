<?php
include "db.php";

$countResult = $conn->query("SELECT COUNT(*) AS total_records FROM patient");
$countData = $countResult->fetch_assoc();
$totalRecords = (int)($countData['total_records'] ?? 0);

$result = $conn->query("SELECT patID, patFName, patLName, patBDate, patTelNo, (SELECT COUNT(*) FROM patient) AS total_records FROM patient");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Patients</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <a href="index.php">Back</a>
    <h1>Patients Management</h1>
    <p>Total patients: <?php echo htmlspecialchars($totalRecords); ?></p>

    <form action="add_patient.php" method="POST">
        <input type="number" name="patID" placeholder="Patient ID" required>
        <input type="text" name="patFName" placeholder="First Name" required>
        <input type="text" name="patLName" placeholder="Last Name" required>
        <input type="date" name="patBDate">
        <input type="text" name="patTelNo" placeholder="Contact Number">
        <button type="submit">Add Patient</button>
    </form>

    <table>
        <tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Birthdate</th>
            <th>Contact</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo htmlspecialchars($row['patID']); ?></td>
            <td><?php echo htmlspecialchars($row['patFName']); ?></td>
            <td><?php echo htmlspecialchars($row['patLName']); ?></td>
            <td><?php echo htmlspecialchars($row['patBDate']); ?></td>
            <td><?php echo htmlspecialchars($row['patTelNo']); ?></td>
            <td>
                <a href="edit_patient.php?id=<?php echo urlencode($row['patID']); ?>">Update</a>
                ----|----
                <a href="delete_patient.php?id=<?php echo urlencode($row['patID']); ?>">Delete</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>
</body>
</html>