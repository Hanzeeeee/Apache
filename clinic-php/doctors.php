<?php
include "db.php";

$countResult = $conn->query("SELECT COUNT(*) AS total_records FROM doctor");
$countData = $countResult->fetch_assoc();
$totalRecords = (int)($countData['total_records'] ?? 0);

$result = $conn->query("SELECT docID, docFName, docLName, docAddress, docSpecial, (SELECT COUNT(*) FROM doctor) AS total_records FROM doctor");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Doctors</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <a href="index.php">Back</a>
    <h1>Doctors Management</h1>
    <p>Total doctors: <?php echo htmlspecialchars($totalRecords); ?></p>

    <form action="add_doctor.php" method="POST">
        <input type="number" name="docID" placeholder="Doctor ID" required>
        <input type="text" name="docFName" placeholder="First Name" required>
        <input type="text" name="docLName" placeholder="Last Name" required>
        <input type="text" name="docAddress" placeholder="Address">
        <input type="text" name="docSpecial" placeholder="Specialization">
        <button type="submit">Add Doctor</button>
    </form>

    <table>
        <tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Address</th>
            <th>Specialization</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo htmlspecialchars($row['docID']); ?></td>
            <td><?php echo htmlspecialchars($row['docFName']); ?></td>
            <td><?php echo htmlspecialchars($row['docLName']); ?></td>
            <td><?php echo htmlspecialchars($row['docAddress']); ?></td>
            <td><?php echo htmlspecialchars($row['docSpecial']); ?></td>
            <td>
                <a href="edit_doctor.php?id=<?php echo urlencode($row['docID']); ?>">Update</a>
                ----|----   
                <a href="delete_doctor.php?id=<?php echo urlencode($row['docID']); ?>">Delete</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>
</body>
</html>