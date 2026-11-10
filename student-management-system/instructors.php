<?php
include "db.php";
include "header.php";

$search = isset($_GET['q']) ? trim($_GET['q']) : "";

if ($search !== "") {
    $likeTerm = "%" . $search . "%";
    $stmt = $conn->prepare("SELECT * FROM instructor WHERE instFName LIKE ? OR instLName LIKE ? OR instID LIKE ? ORDER BY instID ASC");
    $stmt->bind_param("sss", $likeTerm, $likeTerm, $likeTerm);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM instructor ORDER BY instID ASC");
}

$countResult = $conn->query("SELECT COUNT(*) AS totalCount FROM instructor");
$totalCount = $countResult->fetch_assoc()['totalCount'];
?>
<h2>Instructor Management</h2>
<a href="add_instructor.php" class="btn">+ Add New Instructor</a>

<form method="GET" action="instructors.php" class="search-box">
    <input type="text" name="q" placeholder="Search by Instructor ID or Name" value="<?php echo htmlspecialchars($search); ?>">
    <button type="submit">Search</button>
    <?php if ($search !== "") { ?>
        <a href="instructors.php" class="btn">Clear</a>
    <?php } ?>
</form>

<div class="record-count">Total Instructors (COUNT): <?php echo $totalCount; ?></div>

<table>
    <thead>
        <tr>
            <th>Inst ID</th>
            <th>Full Name</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result->num_rows > 0) { ?>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['instID']); ?></td>
                    <td><?php echo htmlspecialchars($row['instFName'] . " " . $row['instMName'] . " " . $row['instLName']); ?></td>
                    <td>
                        <a class="action-link" href="update_instructor.php?id=<?php echo $row['instID']; ?>">Edit</a>
                        <a class="action-link delete-link" href="delete_instructor.php?id=<?php echo $row['instID']; ?>">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="3">No instructors found.</td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<?php include "footer.php"; $conn->close(); ?>
