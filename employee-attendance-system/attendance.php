<?php
include 'db.php';
$attendance = mysqli_query($conn, "SELECT a.*, e.employeeFName, e.employeeLName, d.departmentName FROM attendance a LEFT JOIN employees e ON a.employeeID = e.employeeID LEFT JOIN departments d ON e.departmentID = d.departmentID ORDER BY attendanceDate DESC, timeIn DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Attendance Records</title>
    <link rel="stylesheet" href="style.css?v=2">
</head>
<body>
    <div class="container">
        <h2>Attendance Records</h2>
        <div class="menu">
            <a href="index.php">Dashboard</a>
            <a href="departments.php">Departments</a>
            <a href="employees.php">Employees</a>
            <a href="attendance.php">Attendance</a>
            <a href="reports.php">Reports</a>
        </div>

        <p><a class="button" href="add_attendance.php">Add Attendance</a></p>

        <?php if (mysqli_num_rows($attendance) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Record #</th>
                        <th>ID</th>
                        <th>Employee</th>
                        <th>Department</th>
                        <th>Date</th>
                        <th>Time In</th>
                        <th>Time Out</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $recordNumber = 1; ?>
                    <?php while ($row = mysqli_fetch_assoc($attendance)): ?>
                        <tr>
                            <td><?php echo $recordNumber++; ?></td>
                            <td><?php echo $row['attendanceID']; ?></td>
                            <td><?php echo htmlspecialchars($row['employeeFName'] . ' ' . $row['employeeLName']); ?></td>
                            <td><?php echo htmlspecialchars($row['departmentName'] ?: 'Unassigned'); ?></td>
                            <td><?php echo htmlspecialchars($row['attendanceDate']); ?></td>
                            <td><?php echo htmlspecialchars($row['timeIn']); ?></td>
                            <td><?php echo htmlspecialchars($row['timeOut']); ?></td>
                            <td class="actions">
                                <a class="delete" href="delete_attendance.php?id=<?php echo $row['attendanceID']; ?>" onclick="return confirm('Delete this attendance entry?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No attendance records are available. <a class="button" href="add_attendance.php">Add attendance</a></p>
        <?php endif; ?>
    </div>
</body>
</html>