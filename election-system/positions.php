<?php
include "db.php";
include "header.php";

$result = $conn->query("SELECT * FROM positions ORDER BY posID ASC");
?>
<h2>Positions Management</h2>
<a href="add_position.php" class="btn">+ Add New Position</a>

<table>
    <thead>
        <tr>
            <th>Pos ID</th>
            <th>Position Name</th>
            <th>Number of Positions</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result->num_rows > 0) { ?>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['posID']); ?></td>
                    <td><?php echo htmlspecialchars($row['posName']); ?></td>
                    <td><?php echo htmlspecialchars($row['numOfPositions']); ?></td>
                    <td>
                        <?php if ($row['posStat'] === 'Active') { ?>
                            <span class="status-active">Active</span>
                        <?php } else { ?>
                            <span class="status-inactive">Inactive</span>
                        <?php } ?>
                    </td>
                    <td>
                        <a class="action-link" href="update_position.php?id=<?php echo $row['posID']; ?>">Edit</a>
                        <?php if ($row['posStat'] === 'Active') { ?>
                            <a class="action-link deactivate-link" href="deactivate_position.php?id=<?php echo $row['posID']; ?>">Deactivate</a>
                        <?php } else { ?>
                            <a class="action-link activate-link" href="deactivate_position.php?id=<?php echo $row['posID']; ?>">Activate</a>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="5">No positions found.</td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<?php include "footer.php"; $conn->close(); ?>
