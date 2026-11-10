<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Attendance</title>
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

    <h2>Attendance</h2>

    <?php
    $idNum = '';
    $foundRow = null;
    $message = '';
    $messageType = '';

    // Step 1: Handle marking attendance (Attended button clicked)
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['markAttended'])) {
        $idNum = mysqli_real_escape_string($conn, $_POST['idNum']);

        $checkSql = "SELECT * FROM registration WHERE idNum = '$idNum'";
        $checkResult = mysqli_query($conn, $checkSql);

        if (mysqli_num_rows($checkResult) == 0) {
            $message = "ID # is NOT YET REGISTERED";
            $messageType = "error";
        } else {
            $foundRow = mysqli_fetch_assoc($checkResult);
            if ($foundRow['attended'] == 'Yes') {
                $message = "Student's Attendance RECORD ALREADY EXISTS";
                $messageType = "info";
            } else {
                $updateSql = "UPDATE registration SET attended = 'Yes' WHERE idNum = '$idNum'";
                mysqli_query($conn, $updateSql);
                $message = "Student Attendance is SUCCESSFULLY RECORDED";
                $messageType = "success";
                // refresh the row data after update
                $foundRow['attended'] = 'Yes';
            }
        }
    }
    // Step 2: Handle searching for an ID Number
    elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['searchID'])) {
        $idNum = mysqli_real_escape_string($conn, $_POST['idNum']);

        $checkSql = "SELECT * FROM registration WHERE idNum = '$idNum'";
        $checkResult = mysqli_query($conn, $checkSql);

        if (mysqli_num_rows($checkResult) == 0) {
            $message = "ID # is NOT YET REGISTERED";
            $messageType = "error";
        } else {
            $foundRow = mysqli_fetch_assoc($checkResult);
        }
    }
    ?>

    <form method="POST" action="attendance.php">
        <div class="form-group">
            <label>Input ID #:</label>
            <input type="text" name="idNum" value="<?php echo htmlspecialchars($idNum); ?>" required>
            <button type="submit" name="searchID" value="1" class="btn">Search</button>
        </div>
    </form>

    <?php if ($message != '') { ?>
        <div class="message <?php echo $messageType; ?>"><?php echo $message; ?></div>
    <?php } ?>

    <?php if ($foundRow != null) { ?>
        <table>
            <tr>
                <th>ID #</th>
                <th>Name</th>
                <th>Campus</th>
                <th>Amount</th>
                <th>Action</th>
            </tr>
            <tr>
                <td><?php echo htmlspecialchars($foundRow['idNum']); ?></td>
                <td><?php echo htmlspecialchars($foundRow['studLName'] . ", " . $foundRow['studFName']); ?></td>
                <td><?php echo htmlspecialchars($foundRow['campus']); ?></td>
                <td><?php echo number_format($foundRow['amountPaid'], 2); ?></td>
                <td>
                    <?php if ($foundRow['attended'] == 'Yes') { ?>
                        Attended
                    <?php } else { ?>
                        <form method="POST" action="attendance.php" style="margin:0;">
                            <input type="hidden" name="idNum" value="<?php echo htmlspecialchars($foundRow['idNum']); ?>">
                            <button type="submit" name="markAttended" value="1" class="btn">Attended</button>
                        </form>
                    <?php } ?>
                </td>
            </tr>
        </table>
    <?php } ?>

    <div class="summary-box">
        <p><strong>Note:</strong></p>
        <p>1. Display appropriate prompt for "ID # is NOT YET REGISTERED"</p>
        <p>2. Display appropriate prompt for "Student Attendance is SUCCESSFULLY RECORDED"</p>
        <p>3. Display appropriate prompt for "Student's Attendance RECORD ALREADY EXISTS"</p>
    </div>
</div>

</body>
</html>
