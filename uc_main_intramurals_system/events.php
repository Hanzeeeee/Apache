<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
<title>Events</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
<h2>Events</h2>

<form method="POST">
<input type="text" name="category" placeholder="Category" required>
<input type="text" name="eventName" placeholder="Event Name" required>
<input type="number" name="noOfParticipant" placeholder="No. of Participants" required>

<select name="tournamentManager">
<?php
$result=mysqli_query($conn,"SELECT * FROM tournament_managers");
while($row=mysqli_fetch_assoc($result)){
echo "<option value='{$row['userName']}'>{$row['name']}</option>";
}
?>
</select>

<button type="submit" name="save">Save</button>
</form>

<?php
if(isset($_POST['save'])){
$c=$_POST['category'];
$e=$_POST['eventName'];
$n=$_POST['noOfParticipant'];
$t=$_POST['tournamentManager'];

mysqli_query($conn,"INSERT INTO events(category,eventName,noOfParticipant,tournamentManager)
VALUES('$c','$e','$n','$t')");
header("Location: events.php");
}
?>

<table>
<tr>
<th>ID</th>
<th>Category</th>
<th>Event</th>
<th>Participants</th>
<th>Action</th>
</tr>

<?php
$data=mysqli_query($conn,"SELECT * FROM events");
while($row=mysqli_fetch_assoc($data)){
?>
<tr>
<td><?php echo $row['eventID']; ?></td>
<td><?php echo $row['category']; ?></td>
<td><?php echo $row['eventName']; ?></td>
<td><?php echo $row['noOfParticipant']; ?></td>
<td><a href="?delete=<?php echo $row['eventID']; ?>">Delete</a></td>
</tr>
<?php } ?>
</table>

<?php
if(isset($_GET['delete'])){
$id=$_GET['delete'];
mysqli_query($conn,"DELETE FROM events WHERE eventID='$id'");
header("Location: events.php");
}
?>
<a href="index.php">Back</a>
</div>
</body>
</html>
