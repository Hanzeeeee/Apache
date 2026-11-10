<?php
include "db.php";
$message = "";
$focus_id = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $voterID = trim($_POST['voterID']);
    $voterPass = trim($_POST['voterPass']);
    $voterFName = trim($_POST['voterFName']);
    $voterMName = trim($_POST['voterMName']);
    $voterLName = trim($_POST['voterLName']);
    $voterAge = trim($_POST['voterAge']);

    if ($voterID === "" || $voterPass === "" || $voterFName === "" || $voterLName === "" || $voterAge === "") {
        $message = "PLEASE FILL OUT ALL REQUIRED FIELDS";
        $focus_id = true;
    } elseif (!is_numeric($voterAge) || intval($voterAge) < 0) {
        $message = "PLEASE ENTER A VALID AGE";
    } else {
        // Check if voter ID already exists
        $stmt = $conn->prepare("SELECT voterID FROM voters WHERE voterID = ?");
        $stmt->bind_param("s", $voterID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->fetch_assoc()) {
            $message = "VOTER ID ALREADY EXISTS";
            $focus_id = true;
        } else {
            // A voter must be 18 or above to be an Active (eligible) voter
            $voterStat = (intval($voterAge) >= 18) ? "Active" : "Inactive";

            $stmt2 = $conn->prepare("INSERT INTO voters (voterID, voterPass, voterFName, voterMName, voterLName, voterStat, voted) VALUES (?, ?, ?, ?, ?, ?, 0)");
            $stmt2->bind_param("ssssss", $voterID, $voterPass, $voterFName, $voterMName, $voterLName, $voterStat);
            $stmt2->execute();
            $stmt2->close();
            $stmt->close();
            $conn->close();
            header("Location: voters.php");
            exit();
        }
        $stmt->close();
    }
}

include "header.php";
?>
<h2>Add New Voter</h2>

<?php if ($message !== "") { ?>
    <div class="prompt-box"><?php echo htmlspecialchars($message); ?></div>
<?php } ?>

<form method="POST" action="add_voter.php">
    <label for="voterID">Voter ID:</label>
    <input type="text" id="voterID" name="voterID" value="<?php echo isset($_POST['voterID']) ? htmlspecialchars($_POST['voterID']) : ""; ?>" <?php echo $focus_id ? "autofocus" : ""; ?> required>

    <label for="voterPass">Password:</label>
    <input type="text" id="voterPass" name="voterPass" value="<?php echo isset($_POST['voterPass']) ? htmlspecialchars($_POST['voterPass']) : ""; ?>" required>

    <label for="voterFName">First Name:</label>
    <input type="text" id="voterFName" name="voterFName" value="<?php echo isset($_POST['voterFName']) ? htmlspecialchars($_POST['voterFName']) : ""; ?>" required>

    <label for="voterMName">Middle Name:</label>
    <input type="text" id="voterMName" name="voterMName" value="<?php echo isset($_POST['voterMName']) ? htmlspecialchars($_POST['voterMName']) : ""; ?>">

    <label for="voterLName">Last Name:</label>
    <input type="text" id="voterLName" name="voterLName" value="<?php echo isset($_POST['voterLName']) ? htmlspecialchars($_POST['voterLName']) : ""; ?>" required>

    <label for="voterAge">Age:</label>
    <input type="number" id="voterAge" name="voterAge" min="0" value="<?php echo isset($_POST['voterAge']) ? htmlspecialchars($_POST['voterAge']) : ""; ?>" required>
    <small>Voters aged 18 and above are automatically marked as "Active" (eligible to vote). Below 18 will be marked "Inactive".</small>

    <button type="submit">Add Voter</button>
</form>

<a class="back-link" href="voters.php">&laquo; Back to Voters List</a>

<?php include "footer.php"; $conn->close(); ?>
