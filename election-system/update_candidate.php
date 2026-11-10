<?php
include "db.php";
$message = "";

$id = isset($_GET['id']) ? intval($_GET['id']) : (isset($_POST['candID']) ? intval($_POST['candID']) : 0);
$candFName = "";
$candMName = "";
$candLName = "";
$posID = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $candFName = trim($_POST['candFName']);
    $candMName = trim($_POST['candMName']);
    $candLName = trim($_POST['candLName']);
    $posID = trim($_POST['posID']);

    if ($candFName === "" || $candLName === "" || $posID === "") {
        $message = "PLEASE FILL OUT ALL REQUIRED FIELDS (FIRST NAME, LAST NAME, POSITION)";
    } else {
        $posID = intval($posID);
        $stmt = $conn->prepare("UPDATE candidates SET candFName = ?, candMName = ?, candLName = ?, posID = ? WHERE candID = ?");
        $stmt->bind_param("sssii", $candFName, $candMName, $candLName, $posID, $id);
        $stmt->execute();
        $stmt->close();
        $conn->close();
        header("Location: candidates.php");
        exit();
    }
} else {
    if ($id > 0) {
        $stmt = $conn->prepare("SELECT * FROM candidates WHERE candID = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $candFName = $row['candFName'];
            $candMName = $row['candMName'];
            $candLName = $row['candLName'];
            $posID = $row['posID'];
        } else {
            $message = "CANDIDATE NOT FOUND";
        }
        $stmt->close();
    } else {
        $message = "NO CANDIDATE SELECTED";
    }
}

$positions = $conn->query("SELECT * FROM positions ORDER BY posName ASC");

include "header.php";
?>
<h2>Update Candidate</h2>

<?php if ($message !== "") { ?>
    <div class="prompt-box"><?php echo htmlspecialchars($message); ?></div>
<?php } ?>

<?php if ($id > 0) { ?>
<form method="POST" action="update_candidate.php">
    <input type="hidden" name="candID" value="<?php echo htmlspecialchars($id); ?>">

    <label for="candFName">First Name:</label>
    <input type="text" id="candFName" name="candFName" value="<?php echo htmlspecialchars($candFName); ?>" required>

    <label for="candMName">Middle Name:</label>
    <input type="text" id="candMName" name="candMName" value="<?php echo htmlspecialchars($candMName); ?>">

    <label for="candLName">Last Name:</label>
    <input type="text" id="candLName" name="candLName" value="<?php echo htmlspecialchars($candLName); ?>" required>

    <label for="posID">Position:</label>
    <select id="posID" name="posID" required>
        <option value="">-- Select Position --</option>
        <?php while ($pos = $positions->fetch_assoc()) { ?>
            <option value="<?php echo $pos['posID']; ?>" <?php echo ($posID == $pos['posID']) ? "selected" : ""; ?>>
                <?php echo htmlspecialchars($pos['posName']); ?> (<?php echo $pos['posStat']; ?>)
            </option>
        <?php } ?>
    </select>

    <button type="submit">Update Candidate</button>
</form>
<?php } ?>

<a class="back-link" href="candidates.php">&laquo; Back to Candidates List</a>

<?php include "footer.php"; $conn->close(); ?>
