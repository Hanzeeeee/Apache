<?php
include 'db.php';
$id=$_GET['id'];
$result=mysqli_query($conn,"SELECT * FROM petowners WHERE petOwnerID='$id'");
$row=mysqli_fetch_assoc($result);

if(isset($_POST['update'])){
mysqli_query($conn,"UPDATE petowners SET
petOwnerFName='{$_POST['fname']}',
petOwnerLName='{$_POST['lname']}',
petOwnerBDate='{$_POST['bdate']}',
petOwnerTelNo='{$_POST['tel']}'
WHERE petOwnerID='$id'");
header('Location: petowners.php');
}
?>
<!DOCTYPE html>
<html><head><title>Edit Pet Owner</title><link rel="stylesheet" href="style.css"></head>
<body><div class="container">
<h2>Edit Pet Owner</h2>
<form method="POST">
<label>First Name</label><input type="text" name="fname" value="<?php echo $row['petOwnerFName']; ?>" required>
<label>Last Name</label><input type="text" name="lname" value="<?php echo $row['petOwnerLName']; ?>" required>
<label>Birth Date</label><input type="date" name="bdate" value="<?php echo $row['petOwnerBDate']; ?>" required>
<label>Telephone</label><input type="text" name="tel" value="<?php echo $row['petOwnerTelNo']; ?>" required>
<button type="submit" name="update">Update</button>
</form>
</div></body></html>
