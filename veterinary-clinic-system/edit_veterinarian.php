<?php
include 'db.php';

$id = $_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM veterinarians WHERE vetID='$id'");
$row = mysqli_fetch_assoc($result);

if(isset($_POST['update'])){
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $address = $_POST['address'];
    $special = $_POST['special'];

    mysqli_query($conn, "UPDATE veterinarians SET
    vetFName='$fname',
    vetLName='$lname',
    vetAddress='$address',
    vetSpecial='$special'
    WHERE vetID='$id'");

    header('Location: veterinarians.php');
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Edit Veterinarian</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
<h2>Edit Veterinarian</h2>

<form method="POST">
<label>First Name</label>
<input type="text" name="fname" value="<?php echo $row['vetFName']; ?>" required>

<label>Last Name</label>
<input type="text" name="lname" value="<?php echo $row['vetLName']; ?>" required>

<label>Address</label>
<input type="text" name="address" value="<?php echo $row['vetAddress']; ?>" required>

<label>Specialization</label>
<input type="text" name="special" value="<?php echo $row['vetSpecial']; ?>" required>

<button type="submit" name="update">Update</button>
</form>
</div>
</body>
</html>
