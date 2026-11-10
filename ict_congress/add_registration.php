<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Register a Student</title>
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

    <h2>Register a Student Here</h2>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $idNum = mysqli_real_escape_string($conn, $_POST['idNum']);
        $campus = mysqli_real_escape_string($conn, $_POST['campus']);
        $studFName = mysqli_real_escape_string($conn, $_POST['studFName']);
        $studLName = mysqli_real_escape_string($conn, $_POST['studLName']);
        $amountPaid = mysqli_real_escape_string($conn, $_POST['amountPaid']);

        if ($idNum == '' || $campus == '' || $studFName == '' || $studLName == '' || $amountPaid == '') {
            echo "<div class='message error'>All fields are required.</div>";
        } else {
            // Check if the ID Number already exists
            $checkSql = "SELECT idNum FROM registration WHERE idNum = '$idNum'";
            $checkResult = mysqli_query($conn, $checkSql);

            if (mysqli_num_rows($checkResult) > 0) {
                echo "<div class='message error'>ID # already registered. Please use a different ID Number.</div>";
            } else {
                $sql = "INSERT INTO registration (idNum, campus, studFName, studLName, amountPaid, attended)
                        VALUES ('$idNum', '$campus', '$studFName', '$studLName', '$amountPaid', 'No')";
                if (mysqli_query($conn, $sql)) {
                    header("Location: registration.php");
                    exit();
                } else {
                    echo "<div class='message error'>Error: " . mysqli_error($conn) . "</div>";
                }
            }
        }
    }
    ?>

    <form method="POST" action="add_registration.php">
        <div class="form-group">
            <label>ID Number:</label>
            <input type="text" name="idNum" required>
        </div>
        <div class="form-group">
            <label>Campus:</label>
            <select name="campus" required>
                <option value="">-- Select Campus --</option>
                <?php foreach ($campusList as $c) { ?>
                    <option value="<?php echo $c; ?>"><?php echo $c; ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <label>First Name:</label>
            <input type="text" name="studFName" required>
        </div>
        <div class="form-group">
            <label>Last Name:</label>
            <input type="text" name="studLName" required>
        </div>
        <div class="form-group">
            <label>Amount Paid:</label>
            <input type="number" step="0.01" name="amountPaid" required>
        </div>
        <button type="submit" class="btn">Save Registration</button>
        <a href="registration.php" class="btn">Cancel</a>
    </form>
</div>

</body>
</html>
