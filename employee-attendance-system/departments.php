<?php
include 'db.php';
$departments = mysqli_query($conn, "SELECT * FROM departments ORDER BY departmentName");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Departments</title>
    <link rel="stylesheet" href="style.css?v=2">
</head>
<body>
    <div class="container">
        <h2>Departments</h2>
        <div class="menu">
            <a href="index.php">Dashboard</a>
            <a href="departments.php">Departments</a>
            <a href="employees.php">Employees</a>
            <a href="attendance.php">Attendance</a>
            <a href="reports.php">Reports</a>
        </div>

        <p><a class="button" href="add_department.php">Add Department</a></p>

        <?php if (mysqli_num_rows($departments) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Department Name</th>
                        <th>Department Head</th>
                        <th>Telephone</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($departments)): ?>
                        <tr>
                            <td><?php echo $row['departmentID']; ?></td>
                            <td><?php echo htmlspecialchars($row['departmentName']); ?></td>
                            <td><?php echo htmlspecialchars($row['departmentHead']); ?></td>
                            <td><?php echo htmlspecialchars($row['telephone']); ?></td>
                            <td class="actions">
                                <a class="edit" href="edit_department.php?id=<?php echo $row['departmentID']; ?>">Edit</a>
                                <a class="delete" href="delete_department.php?id=<?php echo $row['departmentID']; ?>" onclick="return confirm('Delete this department?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No departments exist yet. <a class="button" href="add_department.php">Create department</a></p>
        <?php endif; ?>
    </div>
</body>
</html>