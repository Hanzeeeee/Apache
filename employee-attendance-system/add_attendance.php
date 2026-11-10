<?php
include 'db.php';
$error = '';
$attendanceDate = '';
$timeIn = '';
$timeOut = '';
$employeeID = 0;
$employees = mysqli_query($conn, "SELECT employeeID, employeeFName, employeeLName FROM employees ORDER BY employeeLName, employeeFName");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employeeID = intval($_POST['employeeID'] ?? 0);
    $attendanceDate = trim($_POST['attendanceDate'] ?? '');
    $timeIn = trim($_POST['timeIn'] ?? '');
    $timeOut = trim($_POST['timeOut'] ?? '');

    if ($employeeID <= 0 || $attendanceDate === '' || $timeIn === '') {
        $error = 'Please select an employee, date and time in.';
    } else {
        $attendanceDate = mysqli_real_escape_string($conn, $attendanceDate);
        $timeIn = mysqli_real_escape_string($conn, $timeIn);
        $timeOut = mysqli_real_escape_string($conn, $timeOut);
        mysqli_query($conn, "INSERT INTO attendance (employeeID, attendanceDate, timeIn, timeOut) VALUES ($employeeID, '$attendanceDate', '$timeIn', '$timeOut')");
        header('Location: attendance.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Add Attendance</title>
    <link rel="stylesheet" href="style.css?v=2">
</head>
<body>
    <div class="container">
        <h2>Add Attendance</h2>
        <div class="menu">
            <a href="index.php">Dashboard</a>
            <a href="departments.php">Departments</a>
            <a href="employees.php">Employees</a>
            <a href="attendance.php">Attendance</a>
            <a href="reports.php">Reports</a>
        </div>

        <?php if ($error !== ''): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>

        <?php if (mysqli_num_rows($employees) === 0): ?>
            <p>No employees exist. <a href="add_employee.php">Add an employee first.</a></p>
        <?php else: ?>
            <form method="post">
                <label>Employee</label>
                <select name="employeeID">
                    <option value="0">Select employee</option>
                    <?php while ($row = mysqli_fetch_assoc($employees)): ?>
                        <option value="<?php echo $row['employeeID']; ?>" <?php echo $row['employeeID'] == $employeeID ? 'selected' : ''; ?>><?php echo htmlspecialchars($row['employeeFName'] . ' ' . $row['employeeLName']); ?></option>
                    <?php endwhile; ?>
                </select>

                <label>Date</label>
                <input type="date" name="attendanceDate" value="<?php echo htmlspecialchars($attendanceDate); ?>">

                <label>Time In</label>
                <input type="time" name="timeIn" value="<?php echo htmlspecialchars($timeIn); ?>">

                <label>Time Out</label>
                <input type="time" name="timeOut" value="<?php echo htmlspecialchars($timeOut); ?>">

                <button type="submit">Save Attendance</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>