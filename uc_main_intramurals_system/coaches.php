<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
<title>Coaches</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
<h2>Coaches</h2>

<form method="POST">
<input type="text" name="userName" placeholder="Username" required>
<input type="text" name="name" placeholder="Name" required>
<input type="text" name="mobile" placeholder="Mobile" required>

<select name="deptID">
<?php
$d = mysqli_query($conn,"SELECT * FROM departments");
while($dept=mysqli_fetch_assoc($d)){
echo "<option value='{$dept['deptID']}'>{$dept['deptName']}</option>";
}
?>
</select>

<button type="submit" name="save">Save</button>
</form>

<?php
if(isset($_POST['save'])){
$userName=$_POST['userName'];
$name=$_POST['name'];
$mobile=$_POST['mobile'];
$deptID=$_POST['deptID'];

mysqli_query($conn,"INSERT INTO coaches VALUES('$userName','$name','$mobile','$deptID')");
header("Location: coaches.php");
}
?>

<table>
<tr>
<th>Username</th>
<th>Name</th>
<th>Mobile</th>
<th>Department</th>
<th>Action</th>
</tr>

<?php
$result=mysqli_query($conn,"SELECT coaches.*, departments.deptName 
FROM coaches 
LEFT JOIN departments ON coaches.deptID=departments.deptID");

while($row=mysqli_fetch_assoc($result)){
?>
<tr>
<td><?php echo $row['userName']; ?></td>
<td><?php echo $row['name']; ?></td>
<td><?php echo $row['mobile']; ?></td>
<td><?php echo $row['deptName']; ?></td>
<td><a href="?delete=<?php echo $row['userName']; ?>">Delete</a></td>
</tr>
<?php } ?>
</table>

<?php
if(isset($_GET['delete'])){
$id=$_GET['delete'];
mysqli_query($conn,"DELETE FROM coaches WHERE userName='$id'");
header("Location: coaches.php");
}
?>

<a href="index.php">Back</a>
</div>
</body>
</html>
