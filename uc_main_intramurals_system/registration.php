
<?php
include 'db.php';
?>
<!DOCTYPE html>
<html>
<head>
<title>Registration</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
<h2>Registration</h2>

<form method="POST">

<input type="text" name="userName" required placeholder="Username">
<input type="password" name="password" required placeholder="Password">
<input type="password" name="confirmPassword" required placeholder="Confirm Password">
<select name="role">
<option>admin</option>
<option>athlete</option>
<option>coach</option>
<option>dean</option>
<option>tournament manager</option>
</select>

<button type="submit" name="save">Save</button>
</form>

<?php
if(isset($_POST['save'])) {
$u=$_POST["userName"];
$p=$_POST["password"];
$c=$_POST["confirmPassword"];
$r=$_POST["role"];
mysqli_query($conn,"INSERT INTO registration VALUES('$u','$p','$c','$r')");
header("Location: registration.php");
}
?>

<table>
<tr>
<th>Username</th><th>Role</th>
<th>Actions</th>
</tr>

<?php
$result = mysqli_query($conn, "SELECT * FROM registration");

while($row = mysqli_fetch_assoc($result)) {
?>
<tr>
<td><?php echo $row["userName"]; ?></td><td><?php echo $row["role"]; ?></td>
<td>
<a href="?delete=<?php echo $row["userName"]; ?>">Delete</a>
</td>
</tr>
<?php } ?>

</table>

<?php
if(isset($_GET['delete'])) {
$id = $_GET['delete'];
mysqli_query($conn, "DELETE FROM registration WHERE userName='$id'");
header("Location: registration.php");
}
?>

<a href="index.php">Back</a>
</div>
</body>
</html>
