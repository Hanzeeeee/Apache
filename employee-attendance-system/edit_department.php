<?php
include 'db.php';
$error = '';
$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: departments.php');
    exit;
}
$result = mysqli_query($conn, "SELECT * FROM departments WHERE departmentID = $id");
$department = mysqli_fetch_assoc($result);
if (!$department) {
    header('Location: departments.php');
    exit;
}
$departmentName = $department['departmentName'];
$departmentHead = $department['departmentHead'];
$telephone = $department['telephone'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $departmentName = trim($_POST['departmentName'] ?? '');
    $departmentHead = trim($_POST['departmentHead'] ?? '');
    $telephone = trim($_POST['telephone'] ?? '');
    if ($departmentName === '' || $departmentHead === '' || $telephone === '') {
        $error = 'Please fill in all fields.';
    } else {
        $departmentName = mysqli_real_escape_string($conn, $departmentName);
        $departmentHead = mysqli_real_escape_string($conn, $departmentHead);
        $telephone = mysqli_real_escape_string($conn, $telephone);
        mysqli_query($conn, "UPDATE departments SET departmentName = '$departmentName', departmentHead = '$departmentHead', telephone = '$telephone' WHERE departmentID = $id");
        header('Location: departments.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Edit Department</title>
    <link rel="stylesheet" href="style.css?v=2">
</head>
<body>
    <div class="container">
        <h2>Edit Department</h2>
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
            <label>Department Name</label>
            <input type="text" name="departmentName" value="<?php echo htmlspecialchars($departmentName); ?>">

            <label>Department Head</label>
            <input type="text" name="departmentHead" value="<?php echo htmlspecialchars($departmentHead); ?>">

            <label>Telephone Number</label>
            <input type="text" name="telephone" value="<?php echo htmlspecialchars($telephone); ?>">

            <button type="submit">Update Department</button>
        </form>
    </div>
</body>
</html>