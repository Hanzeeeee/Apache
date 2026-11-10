<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Update Registration</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="header">
    <h1>UC ICT Congress Registration System</h1>
</div>

<div class="container">
    <div class="top-links">
        <a href="registration.php">Back to Registration List</a> |
        <a href="index.php">Back to Menu</a>
    </div>

    <h2>Update Registration</h2>

    <?php
    if (isset($_GET['idNum'])) {
        $idNum = mysqli_real_escape_string($conn, $_GET['idNum']);
    } elseif (isset($_POST['idNum'])) {
        $idNum = mysqli_real_escape_string($conn, $_POST['idNum']);
    } else {
        echo "<div class='message error'>No student record selected.</div>";
        echo "</div></body></html>";
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $campus = mysqli_real_escape_string($conn, $_POST['campus']);
        $studFName = mysqli_real_escape_string($conn, $_POST['studFName']);
        $studLName = mysqli_real_escape_string($conn, $_POST['studLName']);
        $amountPaid = mysqli_real_escape_string($conn, $_POST['amountPaid']);

        if ($campus == '' || $studFName == '' || $studLName == '' || $amountPaid == '') {
            echo "<div class='message error'>All fields are required.</div>";
        } else {
            $sql = "UPDATE registration SET campus='$campus', studFName='$studFName', studLName='$studLName', amountPaid='$amountPaid' WHERE idNum='$idNum'";
            if (mysqli_query($conn, $sql)) {
                header("Location: registration.php");
                exit();
            } else {
                echo "<div class='message error'>Error: " . mysqli_error($conn) . "</div>";
            }
        }
    }

    $sql = "SELECT * FROM registration WHERE idNum='$idNum'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 0) {
        echo "<div class='message error'>Student record not found.</div>";
        echo "</div></body></html>";
        exit();
    }

    $row = mysqli_fetch_assoc($result);
    ?>

    <form method="POST" action="update_registration.php">
        <input type="hidden" name="idNum" value="<?php echo htmlspecialchars($row['idNum']); ?>">
        <div class="form-group">
            <label>ID Number:</label>
            <input type="text" value="<?php echo htmlspecialchars($row['idNum']); ?>" disabled>
        </div>
        <div class="form-group">
            <label>Campus:</label>
            <select name="campus" required>
                <?php foreach ($campusList as $c) {
                    $selected = ($c == $row['campus']) ? "selected" : "";
                    echo "<option value='$c' $selected>$c</option>";
                } ?>
            </select>
        </div>
        <div class="form-group">
            <label>First Name:</label>
            <input type="text" name="studFName" value="<?php echo htmlspecialchars($row['studFName']); ?>" required>
        </div>
        <div class="form-group">
            <label>Last Name:</label>
            <input type="text" name="studLName" value="<?php echo htmlspecialchars($row['studLName']); ?>" required>
        </div>
        <div class="form-group">
            <label>Amount Paid:</label>
            <input type="number" step="0.01" name="amountPaid" value="<?php echo $row['amountPaid']; ?>" required>
        </div>
        <button type="submit" class="btn">Update Registration</button>
        <a href="registration.php" class="btn">Cancel</a>
    </form>
</div>

</body>
</html>
