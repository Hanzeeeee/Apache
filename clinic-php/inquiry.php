<?php
include "db.php";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Inquiry</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">

<a href="index.php">Back</a>

<h1>Consultation Inquiry</h1>

<h2>Doctors by Specialization</h2>

<form method="GET">

<input type="text" name="special" placeholder="Specialization">

<button type="submit">Search</button>

</form>

<?php

if(isset($_GET['special'])){

$special = $_GET['special'];

$sql = "
SELECT *,
(SELECT COUNT(*) FROM doctor WHERE docSpecial='$special') AS total
FROM doctor
WHERE docSpecial='$special'
";

$result = $conn->query($sql);

echo "<h3>Total: ".$result->num_rows."</h3>";

echo "<table>";

echo "<tr>
<th>ID</th>
<th>First Name</th>
<th>Last Name</th>
<th>Specialization</th>
</tr>";

while($row = $result->fetch_assoc()){

echo "<tr>";

echo "<td>".$row['docID']."</td>";
echo "<td>".$row['docFName']."</td>";
echo "<td>".$row['docLName']."</td>";
echo "<td>".$row['docSpecial']."</td>";

echo "</tr>";

}

echo "</table>";

}

?>

</div>

</body>
</html>
```
