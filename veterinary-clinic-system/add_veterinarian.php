<?php
include 'db.php';

if(isset($_POST['save'])){
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $address = $_POST['address'];
    $special = $_POST['special'];

    mysqli_query($conn, "INSERT INTO veterinarians(vetFName, vetLName, vetAddress, vetSpecial)
    VALUES('$fname','$lname','$address','$special')");

    header('Location: veterinarians.php');
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Add Veterinarian</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
<h2>Add Veterinarian</h2>

<form method="POST">
<label>First Name</label>
<input type="text" name="fname" required>

<label>Last Name</label>
<input type="text" name="lname" required>

<label>Address</label>
<input type="text" name="address" required>

<label>Specialization</label>
<input type="text" name="special" required>

<button type="submit" name="save">Save</button>
</form>
</div>
</body>
</html>
