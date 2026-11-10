<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
<title>Pet Owners</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
<h2>Pet Owners</h2>
<a href="index.php">Back</a>
<a href="add_petowner.php">Add Pet Owner</a>
<table>
<tr>
<th>ID</th><th>First Name</th><th>Last Name</th><th>Birth Date</th><th>Telephone</th><th>Action</th>
</tr>
<?php
$result = mysqli_query($conn,"SELECT * FROM petowners");
while($row=mysqli_fetch_assoc($result)){
?>
<tr>
<td><?php echo $row['petOwnerID']; ?></td>
<td><?php echo $row['petOwnerFName']; ?></td>
<td><?php echo $row['petOwnerLName']; ?></td>
<td><?php echo $row['petOwnerBDate']; ?></td>
<td><?php echo $row['petOwnerTelNo']; ?></td>
<td>
<a class="edit" href="edit_petowner.php?id=<?php echo urlencode($row['petOwnerID']); ?>">Edit</a>
<a class="delete" href="delete_petowner.php?id=<?php echo urlencode($row['petOwnerID']); ?>" onclick="return confirm('Delete this pet owner?');">Delete</a>
</td>
</tr>
<?php } ?>

</table>
</div>
</body>
</html>
