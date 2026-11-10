<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
<title>Reports</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
<h2>Reports and Inquiry</h2>

<h3>Total Athletes Per Event</h3>

<table>
<tr>
<th>Event</th>
<th>Total Athletes</th>
</tr>

<?php
$query=mysqli_query($conn,"
SELECT events.eventName, COUNT(athletes.idNum) AS totalAthletes
FROM events
LEFT JOIN athletes ON events.eventID = athletes.eventID
GROUP BY events.eventName
");

while($row=mysqli_fetch_assoc($query)){
?>
<tr>
<td><?php echo $row['eventName']; ?></td>
<td><?php echo $row['totalAthletes']; ?></td>
</tr>
<?php } ?>

</table>

<h3>Total Athletes Per Department</h3>

<table>
<tr>
<th>Department</th>
<th>Total Athletes</th>
</tr>

<?php
$query2=mysqli_query($conn,"
SELECT departments.deptName, COUNT(athletes.idNum) AS totalAthletes
FROM departments
LEFT JOIN athletes ON departments.deptID = athletes.deptID
GROUP BY departments.deptName
");

while($row=mysqli_fetch_assoc($query2)){
?>
<tr>
<td><?php echo $row['deptName']; ?></td>
<td><?php echo $row['totalAthletes']; ?></td>
</tr>
<?php } ?>

</table>

<a href="index.php">Back</a>
</div>
</body>
</html>
