<?php include 'db.php'; ?>
<!DOCTYPE html>
<html><head><title>Inquiry Reports</title><link rel="stylesheet" href="style.css"></head>
<body><div class="container">
<h2>Consultation Inquiry Reports</h2>

<form method="GET">
<label>Veterinarian Specialization</label>
<input type="text" name="special" value="<?php echo isset($_GET['special']) ? htmlspecialchars($_GET['special']) : ''; ?>">
<button type="submit">Search</button>
</form>

<table>
<tr>
<th>Specialization</th>
<th>Consultation Count</th>
</tr>

<?php
$special = '';
$where = '';
if(isset($_GET['special']) && trim($_GET['special']) !== ''){
    $special = mysqli_real_escape_string($conn, trim($_GET['special']));
    $where = "WHERE veterinarians.vetSpecial LIKE '%$special%'";
}

$query = mysqli_query($conn, "SELECT veterinarians.vetSpecial, COUNT(consultations.consultID) AS totalConsultations
FROM consultations
INNER JOIN veterinarians ON consultations.vetID=veterinarians.vetID
$where
GROUP BY veterinarians.vetSpecial
ORDER BY totalConsultations DESC");

while($row=mysqli_fetch_assoc($query)){
?>
<tr>
<td><?php echo $row['vetSpecial']; ?></td>
<td><?php echo $row['totalConsultations']; ?></td>
</tr>
<?php } ?>
</table>

</div></body></html>
