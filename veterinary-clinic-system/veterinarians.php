<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
<title>Veterinarians</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
<h2>Veterinarians</h2>
<a href="index.php">Back</a>
<a href="add_veterinarian.php">Add Veterinarian</a>

<table>
<tr>
<th>ID</th>
<th>First Name</th>
<th>Last Name</th>
<th>Address</th>
<th>Specialization</th>
<th>Action</th>
</tr>

<?php
$result = mysqli_query($conn, "SELECT * FROM veterinarians");
while($row = mysqli_fetch_assoc($result)){
?>
<tr>
<td><?php echo $row['vetID']; ?></td>
<td><?php echo $row['vetFName']; ?></td>
<td><?php echo $row['vetLName']; ?></td>
<td><?php echo $row['vetAddress']; ?></td>
<td><?php echo $row['vetSpecial']; ?></td>
<td>
<a class="edit" href="edit_veterinarian.php?id=<?php echo urlencode($row['vetID']); ?>">Edit</a>
<a class="delete" href="delete_veterinarian.php?id=<?php echo urlencode($row['vetID']); ?>" onclick="return confirm('Delete this veterinarian?');">Delete</a>
</td>
</tr>
<?php } ?>

</table>
</div>
</body>
</html>
