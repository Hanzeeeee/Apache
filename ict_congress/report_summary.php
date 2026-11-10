<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Report (Summary)</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="header">
    <h1>UC ICT Congress Registration System</h1>
</div>

<div class="container">
    <div class="top-links">
        <a href="index.php">Back to Menu</a>
    </div>

    <h2 style="text-align:center;">Summary Report</h2>
    <p style="text-align:center;"><em>(All Campuses)</em></p>

    <table>
        <tr>
            <th>Campus</th>
            <th>Registered</th>
            <th>Attended</th>
            <th>Total Collection</th>
        </tr>

        <?php
        $grandRegistered = 0;
        $grandAttended = 0;
        $grandCollection = 0;

        foreach ($campusList as $c) {
            $campusEsc = mysqli_real_escape_string($conn, $c);

            // Aggregate functions: COUNT() for registered/attended, SUM() for total collection
            $sql = "SELECT COUNT(idNum) AS registered,
                            SUM(CASE WHEN attended = 'Yes' THEN 1 ELSE 0 END) AS attended,
                            SUM(amountPaid) AS totalCollection
                     FROM registration
                     WHERE campus = '$campusEsc'";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);

            $registered = $row['registered'] ? $row['registered'] : 0;
            $attended = $row['attended'] ? $row['attended'] : 0;
            $totalCollection = $row['totalCollection'] ? $row['totalCollection'] : 0;

            $grandRegistered += $registered;
            $grandAttended += $attended;
            $grandCollection += $totalCollection;

            echo "<tr>";
            echo "<td>" . htmlspecialchars($c) . "</td>";
            echo "<td>" . $registered . "</td>";
            echo "<td>" . $attended . "</td>";
            echo "<td>" . number_format($totalCollection, 2) . "</td>";
            echo "</tr>";
        }
        ?>

        <tr>
            <th>TOTALS</th>
            <th><?php echo $grandRegistered; ?></th>
            <th><?php echo $grandAttended; ?></th>
            <th><?php echo number_format($grandCollection, 2); ?></th>
        </tr>
    </table>

    <p style="text-align:right; margin-top:15px;"><strong>Date Generated:</strong> <?php echo date("m/d/Y"); ?></p>
</div>

</body>
</html>
