<?php
include "db.php";
include "header.php";

$search = isset($_GET['q']) ? trim($_GET['q']) : "";

if ($search !== "") {
    $likeTerm = "%" . $search . "%";
    $stmt = $conn->prepare("SELECT * FROM student WHERE studFName LIKE ? OR studLName LIKE ? OR studID LIKE ? ORDER BY studID ASC");
    $stmt->bind_param("sss", $likeTerm, $likeTerm, $likeTerm);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM student ORDER BY studID ASC");
}

$countResult = $conn->query("SELECT COUNT(*) AS totalCount FROM student");
$totalCount = $countResult->fetch_assoc()['totalCount'];
?>
<h2>Student Management</h2>
<a href="add_student.php" class="btn">+ Add New Student</a>

<form method="GET" action="students.php" class="search-box">
    <input type="text" name="q" placeholder="Search by Student ID or Name" value="<?php echo htmlspecialchars($search); ?>">
    <button type="submit">Search</button>
    <?php if ($search !== "") { ?>
        <a href="students.php" class="btn">Clear</a>
    <?php } ?>
</form>

<div class="record-count">Total Students (COUNT): <?php echo $totalCount; ?></div>

<table>
    <thead>
        <tr>
            <th>Stud ID</th>
            <th>Full Name</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result->num_rows > 0) { ?>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['studID']); ?></td>
                    <td><?php echo htmlspecialchars($row['studFName'] . " " . $row['studMName'] . " " . $row['studLName']); ?></td>
                    <td>
                        <a class="action-link" href="update_student.php?id=<?php echo $row['studID']; ?>">Edit</a>
                        <a class="action-link delete-link" href="delete_student.php?id=<?php echo $row['studID']; ?>">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="3">No students found.</td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<?php include "footer.php"; $conn->close(); ?>
