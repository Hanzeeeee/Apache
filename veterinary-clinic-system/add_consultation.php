<?php
include 'db.php';
if(isset($_POST['save'])){
mysqli_query($conn,"INSERT INTO consultations(petID,vetID,consultDate,diagnoses,prescription)
VALUES('{$_POST['pet']}','{$_POST['vet']}','{$_POST['date']}','{$_POST['diagnoses']}','{$_POST['prescription']}')");
header('Location: consultations.php');
}
?>
<!DOCTYPE html>
<html><head><title>Add Consultation</title><link rel="stylesheet" href="style.css"></head>
<body><div class="container">
<h2>Add Consultation</h2>
<form method="POST">

<label>Pet</label>
<select name="pet">
<?php
$pets=mysqli_query($conn,"SELECT * FROM pets");
while($p=mysqli_fetch_assoc($pets)){
?>
<option value="<?php echo $p['petID']; ?>"><?php echo $p['petName']; ?></option>
<?php } ?>
</select>

<label>Veterinarian</label>
<select name="vet">
<?php
$vets=mysqli_query($conn,"SELECT * FROM veterinarians");
while($v=mysqli_fetch_assoc($vets)){
?>
<option value="<?php echo $v['vetID']; ?>"><?php echo $v['vetFName'].' '.$v['vetLName']; ?></option>
<?php } ?>
</select>

<label>Consultation Date</label>
<input type="datetime-local" name="date" required>

<label>Diagnosis</label>
<textarea name="diagnoses" required></textarea>

<label>Prescription</label>
<textarea name="prescription" required></textarea>

<button type="submit" name="save">Save</button>
</form>
</div></body></html>
