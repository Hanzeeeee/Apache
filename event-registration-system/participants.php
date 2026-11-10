<?php
include "db.php";
include "header.php";

$search = isset($_GET['q']) ? trim($_GET['q']) : "";

if ($search !== "") {
    $likeTerm = "%" . $search . "%";
    $stmt = $conn->prepare("
        SELECT p.*, e.evName
        FROM participants p
        LEFT JOIN events e ON p.evCode = e.evCode
        WHERE p.partFName LIKE ? OR p.partLName LIKE ? OR p.partID LIKE ?
        ORDER BY p.partID ASC
    ");
    $stmt->bind_param("sss", $likeTerm, $likeTerm, $likeTerm);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("
        SELECT p.*, e.evName
        FROM participants p
        LEFT JOIN events e ON p.evCode = e.evCode
        ORDER BY p.partID ASC
    ");
}

// Total record count using COUNT()
$countResult = $conn->query("SELECT COUNT(*) AS totalCount FROM participants");
$totalCount = $countResult->fetch_assoc()['totalCount'];
?>
<h2>Participants Management</h2>
<a href="add_participant.php" class="btn">+ Add New Participant</a>

<form method="GET" action="participants.php" class="search-box">
    <input type="text" name="q" placeholder="Search by Participant ID or Name" value="<?php echo htmlspecialchars($search); ?>">
    <button type="submit">Search</button>
    <?php if ($search !== "") { ?>
        <a href="participants.php" class="btn">Clear</a>
    <?php } ?>
</form>

<div class="record-count">Total Participants in Database (COUNT): <?php echo $totalCount; ?></div>

<table>
    <thead>
        <tr>
            <th>Part ID</th>
            <th>Full Name</th>
            <th>Event</th>
            <th>Discount Rate</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result->num_rows > 0) { ?>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['partID']); ?></td>
                    <td><?php echo htmlspecialchars($row['partFName'] . " " . $row['partLName']); ?></td>
                    <td><?php echo htmlspecialchars($row['evName'] !== null ? $row['evName'] : "N/A"); ?></td>
                    <td>&#8369;<?php echo number_format($row['partDRate'], 2); ?></td>
                    <td>
                        <a class="action-link" href="update_participant.php?id=<?php echo $row['partID']; ?>">Update</a>
                        <a class="action-link delete-link" href="delete_participant.php?id=<?php echo $row['partID']; ?>">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="5">No participants found.</td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<a class="back-link" href="index.php">&laquo; Back to Home</a>

<?php include "footer.php"; $conn->close(); ?>
