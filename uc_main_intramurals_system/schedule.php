<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
<title>Schedules</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
<h2>Schedule Management</h2>

<form method="POST">
<input type="text" name="eventName" placeholder="Event Name" required>
<input type="date" name="scheduleDate" required>
<input type="text" name="scheduleTime" placeholder="Time" required>
<input type="text" name="venue" placeholder="Venue" required>
<button type="submit" name="save">Save</button>
</form>

<?php
if(isset($_POST['save'])){
$e=$_POST['eventName'];
$d=$_POST['scheduleDate'];
$t=$_POST['scheduleTime'];
$v=$_POST['venue'];

mysqli_query($conn,"INSERT INTO schedules(eventName,scheduleDate,scheduleTime,venue)
VALUES('$e','$d','$t','$v')");
header("Location: schedule.php");
}
?>

<table>
<tr>
<th>Event</th>
<th>Date</th>
<th>Time</th>
<th>Venue</th>
<th>Action</th>
</tr>

<?php
$result=mysqli_query($conn,"SELECT * FROM schedules");
while($row=mysqli_fetch_assoc($result)){
?>
<tr>
<td><?php echo $row['eventName']; ?></td>
<td><?php echo $row['scheduleDate']; ?></td>
<td><?php echo $row['scheduleTime']; ?></td>
<td><?php echo $row['venue']; ?></td>
<td><a href="?delete=<?php echo $row['scheduleID']; ?>">Delete</a></td>
</tr>
<?php } ?>
</table>

<?php
if(isset($_GET['delete'])){
$id=$_GET['delete'];
mysqli_query($conn,"DELETE FROM schedules WHERE scheduleID='$id'");
header("Location: schedule.php");
}
?>

<a href="index.php">Back</a>
</div>
</body>
</html>
