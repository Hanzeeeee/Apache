<?php
include 'db.php';
$error = '';
$employeeFName = '';
$employeeLName = '';
$employeePosition = '';
$ratePerHour = '';
$departmentID = '';
$departments = mysqli_query($conn, "SELECT departmentID, departmentName FROM departments ORDER BY departmentName");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employeeFName = trim($_POST['employeeFName'] ?? '');
    $employeeLName = trim($_POST['employeeLName'] ?? '');
    $employeePosition = trim($_POST['employeePosition'] ?? '');
    $ratePerHour = trim($_POST['ratePerHour'] ?? '');
    $departmentID = intval($_POST['departmentID'] ?? 0);

    if ($employeeFName === '' || $employeeLName === '' || $employeePosition === '' || $ratePerHour === '' || $departmentID <= 0) {
        $error = 'Please fill in all fields and select a department.';
    } else {
        $employeeFName = mysqli_real_escape_string($conn, $employeeFName);
        $employeeLName = mysqli_real_escape_string($conn, $employeeLName);
        $employeePosition = mysqli_real_escape_string($conn, $employeePosition);
        $ratePerHour = mysqli_real_escape_string($conn, $ratePerHour);
        mysqli_query($conn, "INSERT INTO employees (employeeFName, employeeLName, employeePosition, ratePerHour, departmentID) VALUES ('$employeeFName', '$employeeLName', '$employeePosition', $ratePerHour, $departmentID)");
        header('Location: employees.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Add Employee</title>
    <link rel="stylesheet" href="style.css?v=2">
</head>
<body>
    <div class="container">
        <h2>Add Employee</h2>
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

        <form method="post">
            <label>First Name</label>
            <input type="text" name="employeeFName" value="<?php echo htmlspecialchars($employeeFName); ?>">

            <label>Last Name</label>
            <input type="text" name="employeeLName" value="<?php echo htmlspecialchars($employeeLName); ?>">

            <label>Position</label>
            <input type="text" name="employeePosition" value="<?php echo htmlspecialchars($employeePosition); ?>">

            <label>Rate Per Hour</label>
            <input type="text" name="ratePerHour" value="<?php echo htmlspecialchars($ratePerHour); ?>" placeholder="e.g. 15.00">

            <label>Department</label>
            <select name="departmentID">
                <option value="0">Select department</option>
                <?php while ($row = mysqli_fetch_assoc($departments)): ?>
                    <option value="<?php echo $row['departmentID']; ?>" <?php echo $row['departmentID'] == $departmentID ? 'selected' : ''; ?>><?php echo htmlspecialchars($row['departmentName']); ?></option>
                <?php endwhile; ?>
            </select>

            <button type="submit">Save Employee</button>
        </form>
    </div>
</body>
</html>