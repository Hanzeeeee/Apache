
<?php
include 'db.php';
?>
<!DOCTYPE html>
<html>
<head>
<title>Department Management</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
<h2>Department Management</h2>

<form method="POST">
<input type="text" name="deptName" required placeholder="Department Name">
<button type="submit" name="save">Save</button>
</form>

<?php
if(isset($_POST['save'])) {
$deptName=$_POST["deptName"]; mysqli_query($conn,"INSERT INTO departments(deptName) VALUES('$deptName')"); header("Location: departments.php");
}
?>

<table>
<tr>
<th>ID</th><th>Department</th>
<th>Actions</th>
</tr>

<?php
$result = mysqli_query($conn, "SELECT * FROM departments");

while($row = mysqli_fetch_assoc($result)) {
?>
<tr>
<td><?php echo $row["deptID"]; ?></td><td><?php echo $row["deptName"]; ?></td>
<td>
<a href="?delete=<?php echo $row["deptID"]; ?>">Delete</a>
</td>
</tr>
<?php } ?>

</table>

<?php
if(isset($_GET['delete'])) {
$id = $_GET['delete'];
mysqli_query($conn, "DELETE FROM departments WHERE deptID='$id'");
header("Location: departments.php");
}
?>

<a href="index.php">Back</a>
</div>
</body>
</html>
