<?php
include "db.php";
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $posName = trim($_POST['posName']);
    $numOfPositions = trim($_POST['numOfPositions']);

    if ($posName === "" || $numOfPositions === "") {
        $message = "PLEASE FILL OUT ALL FIELDS";
    } elseif (!is_numeric($numOfPositions) || intval($numOfPositions) < 1) {
        $message = "NUMBER OF POSITIONS MUST BE A WHOLE NUMBER OF AT LEAST 1";
    } else {
        $numOfPositions = intval($numOfPositions);
        $stmt = $conn->prepare("INSERT INTO positions (posName, numOfPositions, posStat) VALUES (?, ?, 'Active')");
        $stmt->bind_param("si", $posName, $numOfPositions);
        $stmt->execute();
        $stmt->close();
        $conn->close();
        header("Location: positions.php");
        exit();
    }
}

include "header.php";
?>
<h2>Add New Position</h2>

<?php if ($message !== "") { ?>
    <div class="prompt-box"><?php echo htmlspecialchars($message); ?></div>
<?php } ?>

<form method="POST" action="add_position.php">
    <label for="posName">Position Name:</label>
    <input type="text" id="posName" name="posName" value="<?php echo isset($_POST['posName']) ? htmlspecialchars($_POST['posName']) : ""; ?>" required>

    <label for="numOfPositions">Number of Positions (Vacant Slots):</label>
    <input type="number" id="numOfPositions" name="numOfPositions" min="1" value="<?php echo isset($_POST['numOfPositions']) ? htmlspecialchars($_POST['numOfPositions']) : ""; ?>" required>

    <button type="submit">Add Position</button>
</form>

<a class="back-link" href="positions.php">&laquo; Back to Positions List</a>

<?php include "footer.php"; $conn->close(); ?>
