<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Report (By Campus)</title>
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

    <h2>Report (By Campus)</h2>

    <?php
    $reportCampuses = array("Main", "Banilad", "LM");
    $isSubmitted = ($_SERVER['REQUEST_METHOD'] == 'POST');

    if ($isSubmitted) {
        $selectedCampuses = isset($_POST['campus']) ? $_POST['campus'] : array();
    } else {
        // Default checked campus (matches sample interface: Banilad checked)
        $selectedCampuses = array("Banilad");
    }
    ?>

    <form method="POST" action="report_campus.php">
        <p>Set filters here:</p>
        <div class="checkbox-group">
            <?php foreach ($reportCampuses as $c) {
                $checked = in_array($c, $selectedCampuses) ? "checked" : "";
                echo "<label><input type='checkbox' name='campus[]' value='$c' $checked> $c</label>";
            } ?>
        </div>
        <button type="submit" name="generate" value="1" class="btn">Generate Report</button>
    </form>

    <?php
    if ($isSubmitted && isset($_POST['generate'])) {

        if (count($selectedCampuses) == 0) {
            echo "<div class='message error'>Please select at least one campus.</div>";
        } else {
            $inList = array();
            foreach ($selectedCampuses as $c) {
                $inList[] = "'" . mysqli_real_escape_string($conn, $c) . "'";
            }
            $inClause = implode(",", $inList);

            $sql = "SELECT * FROM registration WHERE campus IN ($inClause) ORDER BY idNum ASC";
            $result = mysqli_query($conn, $sql);
            ?>
            <table>
                <tr>
                    <th>ID #</th>
                    <th>Name</th>
                    <th>Campus</th>
                    <th>Amount</th>
                    <th>Attended</th>
                </tr>
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['idNum']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['studLName'] . ", " . $row['studFName']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['campus']) . "</td>";
                        echo "<td>" . number_format($row['amountPaid'], 2) . "</td>";
                        echo "<td>" . htmlspecialchars($row['attended']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No records found for the selected campus(es).</td></tr>";
                }
                ?>
            </table>

            <?php
            // Aggregate functions: COUNT() for registrants/attendees, SUM() for total collection
            $aggSql = "SELECT COUNT(idNum) AS totalRegistrants,
                              SUM(amountPaid) AS totalCollection,
                              SUM(CASE WHEN attended = 'Yes' THEN 1 ELSE 0 END) AS totalAttendees
                       FROM registration
                       WHERE campus IN ($inClause)";
            $aggResult = mysqli_query($conn, $aggSql);
            $aggRow = mysqli_fetch_assoc($aggResult);

            $totalRegistrants = $aggRow['totalRegistrants'] ? $aggRow['totalRegistrants'] : 0;
            $totalCollection = $aggRow['totalCollection'] ? $aggRow['totalCollection'] : 0;
            $totalAttendees = $aggRow['totalAttendees'] ? $aggRow['totalAttendees'] : 0;
            ?>

            <div class="summary-box">
                <p><strong># of Registrants (Total Collection):</strong> <?php echo $totalRegistrants; ?> (<?php echo number_format($totalCollection, 2); ?>)</p>
                <p><strong># of Attendees | Date Generated:</strong> <?php echo $totalAttendees; ?> | <?php echo date("m/d/Y"); ?></p>
            </div>
            <?php
        }
    }
    ?>
</div>

</body>
</html>
