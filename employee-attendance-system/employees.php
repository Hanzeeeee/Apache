<?php
include 'db.php';
$employees = mysqli_query($conn, "SELECT e.*, d.departmentName FROM employees e LEFT JOIN departments d ON e.departmentID = d.departmentID ORDER BY employeeLName, employeeFName");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Employees</title>
    <link rel="stylesheet" href="style.css?v=2">
</head>
<body>
    <div class="container">
        <h2>Employees</h2>
        <div class="menu">
            <a href="index.php">Dashboard</a>
            <a href="departments.php">Departments</a>
            <a href="employees.php">Employees</a>
            <a href="attendance.php">Attendance</a>
            <a href="reports.php">Reports</a>
        </div>

        <p><a class="button" href="add_employee.php">Add Employee</a></p>

        <?php if (mysqli_num_rows($employees) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Position</th>
                        <th>Rate / Hour</th>
                        <th>Department</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($employees)): ?>
                        <tr>
                            <td><?php echo $row['employeeID']; ?></td>
                            <td><?php echo htmlspecialchars($row['employeeFName'] . ' ' . $row['employeeLName']); ?></td>
                            <td><?php echo htmlspecialchars($row['employeePosition']); ?></td>
                            <td><?php echo number_format($row['ratePerHour'], 2); ?></td>
                            <td><?php echo htmlspecialchars($row['departmentName'] ?: 'Unassigned'); ?></td>
                            <td class="actions">
                                <a class="edit" href="edit_employee.php?id=<?php echo $row['employeeID']; ?>">Edit</a>
                                <a class="delete" href="delete_employee.php?id=<?php echo $row['employeeID']; ?>" onclick="return confirm('Delete this employee?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No employees have been added yet. <a class="button" href="add_employee.php">Create employee</a></p>
        <?php endif; ?>
    </div>
</body>
</html>