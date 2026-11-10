<?php
include 'db.php';
$id=$_GET['id'];
$result=mysqli_query($conn,"SELECT * FROM consultations WHERE consultID='$id'");
$row=mysqli_fetch_assoc($result);

if(isset($_POST['update'])){
mysqli_query($conn,"UPDATE consultations SET
petID='{$_POST['pet']}',
vetID='{$_POST['vet']}',
consultDate='{$_POST['date']}',
diagnoses='{$_POST['diagnoses']}',
prescription='{$_POST['prescription']}'
WHERE consultID='$id'");
header('Location: consultations.php');
}
?>
<!DOCTYPE html>
<html><head><title>Edit Consultation</title><link rel="stylesheet" href="style.css"></head>
<body><div class="container">
<h2>Edit Consultation</h2>
<form method="POST">

<label>Pet</label>
<select name="pet">
<?php
$pets=mysqli_query($conn,"SELECT * FROM pets");
while($p=mysqli_fetch_assoc($pets)){
?>
<option value="<?php echo $p['petID']; ?>" <?php if($p['petID']==$row['petID']) echo 'selected'; ?>>
<?php echo $p['petName']; ?>
</option>
<?php } ?>
</select>

<label>Veterinarian</label>
<select name="vet">
<?php
$vets=mysqli_query($conn,"SELECT * FROM veterinarians");
while($v=mysqli_fetch_assoc($vets)){
?>
<option value="<?php echo $v['vetID']; ?>" <?php if($v['vetID']==$row['vetID']) echo 'selected'; ?>>
<?php echo $v['vetFName'].' '.$v['vetLName']; ?>
</option>
<?php } ?>
</select>

<label>Consultation Date</label>
<input type="datetime-local" name="date" value="<?php echo date('Y-m-d\TH:i', strtotime($row['consultDate'])); ?>" required>

<label>Diagnosis</label>
<textarea name="diagnoses"><?php echo $row['diagnoses']; ?></textarea>

<label>Prescription</label>
<textarea name="prescription"><?php echo $row['prescription']; ?></textarea>

<button type="submit" name="update">Update</button>
</form>
</div></body></html>
