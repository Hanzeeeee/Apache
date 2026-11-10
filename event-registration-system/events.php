<?php
include "db.php";
include "header.php";

$search = isset($_GET['q']) ? trim($_GET['q']) : "";

if ($search !== "") {
    $likeTerm = "%" . $search . "%";
    $stmt = $conn->prepare("SELECT * FROM events WHERE evName LIKE ? OR evCode LIKE ? ORDER BY evCode ASC");
    $stmt->bind_param("ss", $likeTerm, $likeTerm);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM events ORDER BY evCode ASC");
}

// Total record count using COUNT()
$countResult = $conn->query("SELECT COUNT(*) AS totalCount FROM events");
$totalCount = $countResult->fetch_assoc()['totalCount'];
?>
<h2>Events Management</h2>
<a href="add_event.php" class="btn">+ Add New Event</a>

<form method="GET" action="events.php" class="search-box">
    <input type="text" name="q" placeholder="Search by Event Code or Event Name" value="<?php echo htmlspecialchars($search); ?>">
    <button type="submit">Search</button>
    <?php if ($search !== "") { ?>
        <a href="events.php" class="btn">Clear</a>
    <?php } ?>
</form>

<div class="record-count">Total Events in Database (COUNT): <?php echo $totalCount; ?></div>

<table>
    <thead>
        <tr>
            <th>Ev Code</th>
            <th>Event Name</th>
            <th>Event Date</th>
            <th>Venue</th>
            <th>Registration Fee</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result->num_rows > 0) { ?>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['evCode']); ?></td>
                    <td><?php echo htmlspecialchars($row['evName']); ?></td>
                    <td><?php echo htmlspecialchars($row['evDate']); ?></td>
                    <td><?php echo htmlspecialchars($row['evVenue']); ?></td>
                    <td>&#8369;<?php echo number_format($row['evRFee'], 2); ?></td>
                    <td>
                        <a class="action-link" href="update_event.php?id=<?php echo $row['evCode']; ?>">Update</a>
                        <a class="action-link delete-link" href="delete_event.php?id=<?php echo $row['evCode']; ?>">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="6">No events found.</td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<a class="back-link" href="index.php">&laquo; Back to Home</a>

<?php include "footer.php"; $conn->close(); ?>
