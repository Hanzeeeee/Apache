<?php
include "db.php";
include "header.php";

// Viewing of ONLY studyload records (raw table view, per the specification)
$result = $conn->query("SELECT * FROM studyload ORDER BY studyLoadID ASC");

$countResult = $conn->query("SELECT COUNT(*) AS totalCount FROM studyload");
$totalCount = $countResult->fetch_assoc()['totalCount'];
?>
<h2>StudyLoad Records</h2>
<a href="add_studyload.php" class="btn">+ Add New StudyLoad</a>

<div class="record-count">Total StudyLoad Records (COUNT): <?php echo $totalCount; ?></div>

<table>
    <thead>
        <tr>
            <th>StudyLoad ID</th>
            <th>Stud ID</th>
            <th>Course ID</th>
            <th>Inst ID</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result->num_rows > 0) { ?>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['studyLoadID']); ?></td>
                    <td><?php echo htmlspecialchars($row['studID']); ?></td>
                    <td><?php echo htmlspecialchars($row['courseID']); ?></td>
                    <td><?php echo htmlspecialchars($row['instID']); ?></td>
                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="5">No StudyLoad records found.</td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<a class="back-link" href="inquire_studyload.php">Go to Inquire StudyLoad (view by student with names) &raquo;</a>

<?php include "footer.php"; $conn->close(); ?>
