<?php include 'db.php'; ?>
<!DOCTYPE html>
<html><head><title>Consultations</title><link rel="stylesheet" href="style.css"></head>
<body><div class="container">
<h2>Consultations</h2>
<a href="index.php">Back</a>
<a href="add_consultation.php">Add Consultation</a>
<table>
<tr>
<th>ID</th><th>Pet</th><th>Veterinarian</th><th>Date</th><th>Diagnosis</th><th>Prescription</th><th>Action</th>
</tr>
<?php
$result=mysqli_query($conn,"SELECT consultations.*, pets.petName, veterinarians.vetFName, veterinarians.vetLName
FROM consultations
INNER JOIN pets ON consultations.petID=pets.petID
INNER JOIN veterinarians ON consultations.vetID=veterinarians.vetID");
while($row=mysqli_fetch_assoc($result)){
?>
<tr>
<td><?php echo $row['consultID']; ?></td>
<td><?php echo $row['petName']; ?></td>
<td><?php echo $row['vetFName'].' '.$row['vetLName']; ?></td>
<td><?php echo $row['consultDate']; ?></td>
<td><?php echo $row['diagnoses']; ?></td>
<td><?php echo $row['prescription']; ?></td>
<td>
<a class="edit" href="edit_consultation.php?id=<?php echo $row['consultID']; ?>">Edit</a>
<a class="delete" href="delete_consultation.php?id=<?php echo $row['consultID']; ?>">Delete</a>
</td>
</tr>
<?php } ?>
</table>
</div></body></html>
