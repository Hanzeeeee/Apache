<?php
include 'db.php';
$summary = mysqli_query($conn, "SELECT e.employeeID, CONCAT(e.employeeFName, ' ', e.employeeLName) AS fullName, d.departmentName, COUNT(a.attendanceID) AS daysPresent, MIN(a.attendanceDate) AS firstAttendance, MAX(a.attendanceDate) AS lastAttendance FROM employees e LEFT JOIN departments d ON e.departmentID = d.departmentID LEFT JOIN attendance a ON e.employeeID = a.employeeID GROUP BY e.employeeID ORDER BY e.employeeLName, e.employeeFName");
$latest = mysqli_query($conn, "SELECT a.*, e.employeeFName, e.employeeLName FROM attendance a LEFT JOIN employees e ON a.employeeID = e.employeeID ORDER BY attendanceDate DESC, timeIn DESC LIMIT 10");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reports</title>
    <link rel="stylesheet" href="style.css?v=2">
</head>
<body>
    <div class="container">
        <h2>Reports</h2>
        <div class="menu">
            <a href="index.php">Dashboard</a>
            <a href="departments.php">Departments</a>
            <a href="employees.php">Employees</a>
            <a href="attendance.php">Attendance</a>
            <a href="reports.php">Reports</a>
        </div>

        <h3>Employee Attendance Summary</h3>
        <?php if (mysqli_num_rows($summary) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Department</th>
                        <th>Days Present</th>
                        <th>First Record</th>
                        <th>Last Record</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($summary)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['fullName']); ?></td>
                            <td><?php echo htmlspecialchars($row['departmentName'] ?: 'Unassigned'); ?></td>
                            <td><?php echo $row['daysPresent']; ?></td>
                            <td><?php echo htmlspecialchars($row['firstAttendance'] ?: '-'); ?></td>
                            <td><?php echo htmlspecialchars($row['lastAttendance'] ?: '-'); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No employee attendance data available.</p>
        <?php endif; ?>

        <h3>Recent Attendance</h3>
        <?php if (mysqli_num_rows($latest) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Employee</th>
                        <th>Time In</th>
                        <th>Time Out</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($latest)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['attendanceDate']); ?></td>
                            <td><?php echo htmlspecialchars($row['employeeFName'] . ' ' . $row['employeeLName']); ?></td>
                            <td><?php echo htmlspecialchars($row['timeIn']); ?></td>
                            <td><?php echo htmlspecialchars($row['timeOut']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No recent attendance records.</p>
        <?php endif; ?>
    </div>
</body>
</html>