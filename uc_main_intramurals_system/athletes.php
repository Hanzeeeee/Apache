<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
<title>Athletes</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
<h2>Athletes Profile</h2>

<form method="POST">
<input type="text" name="idNum" placeholder="ID Number" required>
<input type="text" name="lastName" placeholder="Last Name" required>
<input type="text" name="firstName" placeholder="First Name" required>
<input type="text" name="course" placeholder="Course" required>

<select name="eventID">
<?php
$events=mysqli_query($conn,"SELECT * FROM events");
while($e=mysqli_fetch_assoc($events)){
echo "<option value='{$e['eventID']}'>{$e['eventName']}</option>";
}
?>
</select>

<select name="deptID">
<?php
$depts=mysqli_query($conn,"SELECT * FROM departments");
while($d=mysqli_fetch_assoc($depts)){
echo "<option value='{$d['deptID']}'>{$d['deptName']}</option>";
}
?>
</select>

<button type="submit" name="save">Save</button>
</form>

<?php
if(isset($_POST['save'])){
$id=$_POST['idNum'];
$last=$_POST['lastName'];
$first=$_POST['firstName'];
$course=$_POST['course'];
$event=$_POST['eventID'];
$dept=$_POST['deptID'];

mysqli_query($conn,"INSERT INTO athletes(idNum,lastName,firstName,course,eventID,deptID)
VALUES('$id','$last','$first','$course','$event','$dept')");
header("Location: athletes.php");
}
?>

<form method="GET">
<input type="text" name="search" placeholder="Search Athlete">
<button type="submit">Search</button>
</form>

<table>
<tr>
<th>ID</th>
<th>Name</th>
<th>Course</th>
<th>Action</th>
</tr>

<?php
$search = "";
if(isset($_GET['search'])){
$search=$_GET['search'];
}

$result=mysqli_query($conn,"SELECT * FROM athletes 
WHERE firstName LIKE '%$search%' 
OR lastName LIKE '%$search%'");

while($row=mysqli_fetch_assoc($result)){
?>
<tr>
<td><?php echo $row['idNum']; ?></td>
<td><?php echo $row['firstName']." ".$row['lastName']; ?></td>
<td><?php echo $row['course']; ?></td>
<td><a href="?delete=<?php echo $row['idNum']; ?>">Delete</a></td>
</tr>
<?php } ?>
</table>

<?php
if(isset($_GET['delete'])){
$id=$_GET['delete'];
mysqli_query($conn,"DELETE FROM athletes WHERE idNum='$id'");
header("Location: athletes.php");
}
?>
<a href="index.php">Back</a>
</div>
</body>
</html>
