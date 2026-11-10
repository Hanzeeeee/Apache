<?php
include "db.php";
include "header.php";

$result = $conn->query("SELECT c.*, p.posName FROM candidates c LEFT JOIN positions p ON c.posID = p.posID ORDER BY c.posID ASC, c.candID ASC");
?>
<h2>Candidates Management</h2>
<a href="add_candidate.php" class="btn">+ Add New Candidate</a>

<table>
    <thead>
        <tr>
            <th>Cand ID</th>
            <th>Full Name</th>
            <th>Position</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result->num_rows > 0) { ?>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['candID']); ?></td>
                    <td><?php echo htmlspecialchars($row['candFName'] . " " . $row['candMName'] . " " . $row['candLName']); ?></td>
                    <td><?php echo htmlspecialchars($row['posName'] !== null ? $row['posName'] : "N/A"); ?></td>
                    <td>
                        <?php if ($row['candStat'] === 'Active') { ?>
                            <span class="status-active">Active</span>
                        <?php } else { ?>
                            <span class="status-inactive">Inactive</span>
                        <?php } ?>
                    </td>
                    <td>
                        <a class="action-link" href="update_candidate.php?id=<?php echo $row['candID']; ?>">Edit</a>
                        <?php if ($row['candStat'] === 'Active') { ?>
                            <a class="action-link deactivate-link" href="deactivate_candidate.php?id=<?php echo $row['candID']; ?>">Deactivate</a>
                        <?php } else { ?>
                            <a class="action-link activate-link" href="deactivate_candidate.php?id=<?php echo $row['candID']; ?>">Activate</a>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="5">No candidates found.</td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<?php include "footer.php"; $conn->close(); ?>
