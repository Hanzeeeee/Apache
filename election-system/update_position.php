<?php
include "db.php";
$message = "";

$id = isset($_GET['id']) ? intval($_GET['id']) : (isset($_POST['posID']) ? intval($_POST['posID']) : 0);
$posName = "";
$numOfPositions = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $posName = trim($_POST['posName']);
    $numOfPositions = trim($_POST['numOfPositions']);

    if ($posName === "" || $numOfPositions === "") {
        $message = "PLEASE FILL OUT ALL FIELDS";
    } elseif (!is_numeric($numOfPositions) || intval($numOfPositions) < 1) {
        $message = "NUMBER OF POSITIONS MUST BE A WHOLE NUMBER OF AT LEAST 1";
    } else {
        $numOfPositions = intval($numOfPositions);
        $stmt = $conn->prepare("UPDATE positions SET posName = ?, numOfPositions = ? WHERE posID = ?");
        $stmt->bind_param("sii", $posName, $numOfPositions, $id);
        $stmt->execute();
        $stmt->close();
        $conn->close();
        header("Location: positions.php");
        exit();
    }
} else {
    if ($id > 0) {
        $stmt = $conn->prepare("SELECT * FROM positions WHERE posID = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $posName = $row['posName'];
            $numOfPositions = $row['numOfPositions'];
        } else {
            $message = "POSITION NOT FOUND";
        }
        $stmt->close();
    } else {
        $message = "NO POSITION SELECTED";
    }
}

include "header.php";
?>
<h2>Update Position</h2>

<?php if ($message !== "") { ?>
    <div class="prompt-box"><?php echo htmlspecialchars($message); ?></div>
<?php } ?>

<?php if ($id > 0) { ?>
<form method="POST" action="update_position.php">
    <input type="hidden" name="posID" value="<?php echo htmlspecialchars($id); ?>">

    <label for="posName">Position Name:</label>
    <input type="text" id="posName" name="posName" value="<?php echo htmlspecialchars($posName); ?>" required>

    <label for="numOfPositions">Number of Positions (Vacant Slots):</label>
    <input type="number" id="numOfPositions" name="numOfPositions" min="1" value="<?php echo htmlspecialchars($numOfPositions); ?>" required>

    <button type="submit">Update Position</button>
</form>
<?php } ?>

<a class="back-link" href="positions.php">&laquo; Back to Positions List</a>

<?php include "footer.php"; $conn->close(); ?>
