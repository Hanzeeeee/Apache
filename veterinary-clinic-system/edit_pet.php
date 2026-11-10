<?php
include 'db.php';
$id=$_GET['id'];
$result=mysqli_query($conn,"SELECT * FROM pets WHERE petID='$id'");
$row=mysqli_fetch_assoc($result);

if(isset($_POST['update'])){
mysqli_query($conn,"UPDATE pets SET
petName='{$_POST['name']}',
petType='{$_POST['type']}',
petBreed='{$_POST['breed']}',
petBDate='{$_POST['bdate']}',
petOwnerID='{$_POST['owner']}'
WHERE petID='$id'");
header('Location: pets.php');
}
?>
<!DOCTYPE html>
<html><head><title>Edit Pet</title><link rel="stylesheet" href="style.css"></head>
<body><div class="container">
<h2>Edit Pet</h2>
<form method="POST">
<label>Pet Name</label><input type="text" name="name" value="<?php echo $row['petName']; ?>" required>
<label>Pet Type</label><input type="text" name="type" value="<?php echo $row['petType']; ?>" required>
<label>Breed</label><input type="text" name="breed" value="<?php echo $row['petBreed']; ?>" required>
<label>Birth Date</label><input type="date" name="bdate" value="<?php echo $row['petBDate']; ?>" required>

<label>Pet Owner</label>
<select name="owner">
<?php
$owners=mysqli_query($conn,"SELECT * FROM petowners");
while($o=mysqli_fetch_assoc($owners)){
?>
<option value="<?php echo $o['petOwnerID']; ?>" <?php if($o['petOwnerID']==$row['petOwnerID']) echo 'selected'; ?>>
<?php echo $o['petOwnerFName'].' '.$o['petOwnerLName']; ?>
</option>
<?php } ?>
</select>

<button type="submit" name="update">Update</button>
</form>
</div></body></html>
