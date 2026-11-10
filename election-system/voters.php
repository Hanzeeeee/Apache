<?php
include "db.php";
include "header.php";

$result = $conn->query("SELECT * FROM voters ORDER BY voterID ASC");
?>
<h2>Voters Management</h2>
<a href="add_voter.php" class="btn">+ Add New Voter</a>

<table>
    <thead>
        <tr>
            <th>Voter ID</th>
            <th>Full Name</th>
            <th>Status</th>
            <th>Voted?</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result->num_rows > 0) { ?>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['voterID']); ?></td>
                    <td><?php echo htmlspecialchars($row['voterFName'] . " " . $row['voterMName'] . " " . $row['voterLName']); ?></td>
                    <td>
                        <?php if ($row['voterStat'] === 'Active') { ?>
                            <span class="status-active">Active</span>
                        <?php } else { ?>
                            <span class="status-inactive">Inactive</span>
                        <?php } ?>
                    </td>
                    <td><?php echo ($row['voted'] == 1) ? "Yes" : "No"; ?></td>
                    <td>
                        <a class="action-link" href="update_voter.php?id=<?php echo urlencode($row['voterID']); ?>">Edit</a>
                        <?php if ($row['voterStat'] === 'Active') { ?>
                            <a class="action-link deactivate-link" href="deactivate_voter.php?id=<?php echo urlencode($row['voterID']); ?>">Deactivate</a>
                        <?php } else { ?>
                            <a class="action-link activate-link" href="deactivate_voter.php?id=<?php echo urlencode($row['voterID']); ?>">Activate</a>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="5">No voters found.</td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<?php include "footer.php"; $conn->close(); ?>
