<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Raffle</title>
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

    <h2>Raffle</h2>

    <?php
    $raffleCampuses = array("Main", "Banilad", "LM");
    $isSubmitted = ($_SERVER['REQUEST_METHOD'] == 'POST');

    if ($isSubmitted) {
        $selectedCampuses = isset($_POST['campus']) ? $_POST['campus'] : array();
    } else {
        // Default checked campuses (matches sample interface: Main and Banilad checked)
        $selectedCampuses = array("Main", "Banilad");
    }
    ?>

    <form method="POST" action="raffle.php">
        <p>Set filters here:</p>
        <div class="checkbox-group">
            <?php foreach ($raffleCampuses as $c) {
                $checked = in_array($c, $selectedCampuses) ? "checked" : "";
                echo "<label><input type='checkbox' name='campus[]' value='$c' $checked> $c</label>";
            } ?>
        </div>
        <button type="submit" name="reveal" value="1" class="btn">Reveal the Lucky Winner!</button>
    </form>

    <?php
    if ($isSubmitted && isset($_POST['reveal'])) {
        if (count($selectedCampuses) == 0) {
            echo "<div class='message error'>Please select at least one campus.</div>";
        } else {
            $inList = array();
            foreach ($selectedCampuses as $c) {
                $inList[] = "'" . mysqli_real_escape_string($conn, $c) . "'";
            }
            $inClause = implode(",", $inList);

            $sql = "SELECT * FROM registration WHERE campus IN ($inClause) ORDER BY RAND() LIMIT 1";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                $winner = mysqli_fetch_assoc($result);
                ?>
                <table>
                    <tr>
                        <th>ID #</th>
                        <th>Name</th>
                        <th>Campus</th>
                    </tr>
                    <tr>
                        <td><?php echo htmlspecialchars($winner['idNum']); ?></td>
                        <td><?php echo htmlspecialchars($winner['studLName'] . ", " . $winner['studFName']); ?></td>
                        <td><?php echo htmlspecialchars($winner['campus']); ?></td>
                    </tr>
                </table>
                <div class="winner-box">
                    <h2>CONGRATULATIONS!!!</h2>
                </div>
                <?php
            } else {
                echo "<div class='message error'>No registered students found for the selected campus(es).</div>";
            }
        }
    }
    ?>
</div>

</body>
</html>
