<?php
include 'db.php';
if(isset($_POST['save'])){
mysqli_query($conn,"INSERT INTO pets(petName,petType,petBreed,petBDate,petOwnerID)
VALUES('{$_POST['name']}','{$_POST['type']}','{$_POST['breed']}','{$_POST['bdate']}','{$_POST['owner']}')");
header('Location: pets.php');
}
?>
<!DOCTYPE html>
<html><head><title>Add Pet</title><link rel="stylesheet" href="style.css"></head>
<body><div class="container">
<h2>Add Pet</h2>
<form method="POST">
<label>Pet Name</label><input type="text" name="name" required>
<label>Pet Type</label><input type="text" name="type" required>
<label>Breed</label><input type="text" name="breed" required>
<label>Birth Date</label><input type="date" name="bdate" required>

<label>Pet Owner</label>
<select name="owner" required>
<?php
$owners=mysqli_query($conn,"SELECT * FROM petowners");
while($o=mysqli_fetch_assoc($owners)){
?>
<option value="<?php echo $o['petOwnerID']; ?>">
<?php echo $o['petOwnerFName'].' '.$o['petOwnerLName']; ?>
</option>
<?php } ?>
</select>

<button type="submit" name="save">Save</button>
</form>
</div></body></html>
