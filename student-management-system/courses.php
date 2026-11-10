<?php
include "db.php";
include "header.php";

$search = isset($_GET['q']) ? trim($_GET['q']) : "";

if ($search !== "") {
    $likeTerm = "%" . $search . "%";
    $stmt = $conn->prepare("SELECT * FROM course WHERE courseName LIKE ? OR courseID LIKE ? ORDER BY courseID ASC");
    $stmt->bind_param("ss", $likeTerm, $likeTerm);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM course ORDER BY courseID ASC");
}

$countResult = $conn->query("SELECT COUNT(*) AS totalCount FROM course");
$totalCount = $countResult->fetch_assoc()['totalCount'];
?>
<h2>Course Management</h2>
<a href="add_course.php" class="btn">+ Add New Course</a>

<form method="GET" action="courses.php" class="search-box">
    <input type="text" name="q" placeholder="Search by Course ID or Course Name" value="<?php echo htmlspecialchars($search); ?>">
    <button type="submit">Search</button>
    <?php if ($search !== "") { ?>
        <a href="courses.php" class="btn">Clear</a>
    <?php } ?>
</form>

<div class="record-count">Total Courses (COUNT): <?php echo $totalCount; ?></div>

<table>
    <thead>
        <tr>
            <th>Course ID</th>
            <th>Course Name</th>
            <th>Unit</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result->num_rows > 0) { ?>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['courseID']); ?></td>
                    <td><?php echo htmlspecialchars($row['courseName']); ?></td>
                    <td><?php echo htmlspecialchars($row['courseUnit']); ?></td>
                    <td><?php echo htmlspecialchars($row['courseDescription']); ?></td>
                    <td>
                        <a class="action-link" href="update_course.php?id=<?php echo $row['courseID']; ?>">Edit</a>
                        <a class="action-link delete-link" href="delete_course.php?id=<?php echo $row['courseID']; ?>">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="5">No courses found.</td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<?php include "footer.php"; $conn->close(); ?>
