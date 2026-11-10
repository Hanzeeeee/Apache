<?php include 'db.php'; ?>
<!DOCTYPE html>
<html><head><title>Pets</title><link rel="stylesheet" href="style.css"></head>
<body><div class="container">
<h2>Pets</h2>
<a href="index.php">Back</a>
<a href="add_pet.php">Add Pet</a>
<table>
<tr>
<th>ID</th><th>Name</th><th>Type</th><th>Breed</th><th>Birth Date</th><th>Owner</th><th>Action</th>
</tr>
<?php
$result=mysqli_query($conn,"SELECT pets.*, petowners.petOwnerFName, petowners.petOwnerLName FROM pets INNER JOIN petowners ON pets.petOwnerID=petowners.petOwnerID");
while($row=mysqli_fetch_assoc($result)){
?>
<tr>
<td><?php echo $row['petID']; ?></td>
<td><?php echo $row['petName']; ?></td>
<td><?php echo $row['petType']; ?></td>
<td><?php echo $row['petBreed']; ?></td>
<td><?php echo $row['petBDate']; ?></td>
<td><?php echo $row['petOwnerFName'].' '.$row['petOwnerLName']; ?></td>
<td>
<a class="edit" href="edit_pet.php?id=<?php echo $row['petID']; ?>">Edit</a>
<a class="delete" href="delete_pet.php?id=<?php echo $row['petID']; ?>">Delete</a>
</td>
</tr>
<?php } ?>
</table>
</div></body></html>
