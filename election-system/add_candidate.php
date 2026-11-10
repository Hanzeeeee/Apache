<?php
include "db.php";
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $candFName = trim($_POST['candFName']);
    $candMName = trim($_POST['candMName']);
    $candLName = trim($_POST['candLName']);
    $posID = trim($_POST['posID']);

    if ($candFName === "" || $candLName === "" || $posID === "") {
        $message = "PLEASE FILL OUT ALL REQUIRED FIELDS (FIRST NAME, LAST NAME, POSITION)";
    } else {
        $posID = intval($posID);
        $stmt = $conn->prepare("INSERT INTO candidates (candFName, candMName, candLName, posID, candStat) VALUES (?, ?, ?, ?, 'Active')");
        $stmt->bind_param("sssi", $candFName, $candMName, $candLName, $posID);
        $stmt->execute();
        $stmt->close();
        $conn->close();
        header("Location: candidates.php");
        exit();
    }
}

$positions = $conn->query("SELECT * FROM positions ORDER BY posName ASC");

include "header.php";
?>
<h2>Add New Candidate</h2>

<?php if ($message !== "") { ?>
    <div class="prompt-box"><?php echo htmlspecialchars($message); ?></div>
<?php } ?>

<form method="POST" action="add_candidate.php">
    <label for="candFName">First Name:</label>
    <input type="text" id="candFName" name="candFName" value="<?php echo isset($_POST['candFName']) ? htmlspecialchars($_POST['candFName']) : ""; ?>" required>

    <label for="candMName">Middle Name:</label>
    <input type="text" id="candMName" name="candMName" value="<?php echo isset($_POST['candMName']) ? htmlspecialchars($_POST['candMName']) : ""; ?>">

    <label for="candLName">Last Name:</label>
    <input type="text" id="candLName" name="candLName" value="<?php echo isset($_POST['candLName']) ? htmlspecialchars($_POST['candLName']) : ""; ?>" required>

    <label for="posID">Position:</label>
    <select id="posID" name="posID" required>
        <option value="">-- Select Position --</option>
        <?php while ($pos = $positions->fetch_assoc()) { ?>
            <option value="<?php echo $pos['posID']; ?>" <?php echo (isset($_POST['posID']) && $_POST['posID'] == $pos['posID']) ? "selected" : ""; ?>>
                <?php echo htmlspecialchars($pos['posName']); ?> (<?php echo $pos['posStat']; ?>)
            </option>
        <?php } ?>
    </select>

    <button type="submit">Add Candidate</button>
</form>

<a class="back-link" href="candidates.php">&laquo; Back to Candidates List</a>

<?php include "footer.php"; $conn->close(); ?>
