<?php
include 'db.php';
if(isset($_POST['save'])){
mysqli_query($conn,"INSERT INTO petowners(petOwnerFName,petOwnerLName,petOwnerBDate,petOwnerTelNo)
VALUES('{$_POST['fname']}','{$_POST['lname']}','{$_POST['bdate']}','{$_POST['tel']}')");
header('Location: petowners.php');
}
?>
<!DOCTYPE html>
<html><head><title>Add Pet Owner</title><link rel="stylesheet" href="style.css"></head>
<body><div class="container">
<h2>Add Pet Owner</h2>
<form method="POST">
<label>First Name</label><input type="text" name="fname" required>
<label>Last Name</label><input type="text" name="lname" required>
<label>Birth Date</label><input type="date" name="bdate" required>
<label>Telephone</label><input type="text" name="tel" required>
<button type="submit" name="save">Save</button>
</form>
</div></body></html>
