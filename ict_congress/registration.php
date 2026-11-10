<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Registration</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="header">
    <h1>UC ICT Congress Registration System</h1>
</div>

<div class="container">
    <div class="top-links">
        <a href="add_registration.php">Register a Student Here</a> |
        <a href="index.php">Back to Menu</a>
    </div>

    <h2>Registration</h2>

    <div class="search-box">
        <form method="GET" action="registration.php">
            <label>Search ID # or Last Name:</label>
            <input type="text" name="search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <button type="submit" class="btn">Search</button>
            <a href="registration.php" class="btn">Reset</a>
        </form>
    </div>

    <table>
        <tr>
            <th>ID #</th>
            <th>Name</th>
            <th>Campus</th>
            <th>Amount</th>
            <th>Actions</th>
        </tr>

        <?php
        if (isset($_GET['search']) && $_GET['search'] != '') {
            $search = mysqli_real_escape_string($conn, $_GET['search']);
            $sql = "SELECT * FROM registration WHERE idNum LIKE '%$search%' OR studLName LIKE '%$search%' ORDER BY idNum ASC";
        } else {
            $sql = "SELECT * FROM registration ORDER BY idNum ASC";
        }

        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['idNum']) . "</td>";
                echo "<td>" . htmlspecialchars($row['studLName'] . ", " . $row['studFName']) . "</td>";
                echo "<td>" . htmlspecialchars($row['campus']) . "</td>";
                echo "<td>" . number_format($row['amountPaid'], 2) . "</td>";
                echo "<td>";
                echo "<a href='update_registration.php?idNum=" . urlencode($row['idNum']) . "' class='btn btn-edit'>Edit</a> ";
                echo "<a href='delete_registration.php?idNum=" . urlencode($row['idNum']) . "' class='btn btn-delete'>Del</a>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No registration records found.</td></tr>";
        }
        ?>
    </table>
</div>

</body>
</html>
