<?php
include "db.php";
$message = "";

$id = isset($_GET['id']) ? trim($_GET['id']) : (isset($_POST['voterID']) ? trim($_POST['voterID']) : "");
$voterPass = "";
$voterFName = "";
$voterMName = "";
$voterLName = "";
$voterAge = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $voterPass = trim($_POST['voterPass']);
    $voterFName = trim($_POST['voterFName']);
    $voterMName = trim($_POST['voterMName']);
    $voterLName = trim($_POST['voterLName']);
    $voterAge = trim($_POST['voterAge']);

    if ($voterPass === "" || $voterFName === "" || $voterLName === "") {
        $message = "PLEASE FILL OUT ALL REQUIRED FIELDS";
    } else {
        if ($voterAge !== "" && (!is_numeric($voterAge) || intval($voterAge) < 0)) {
            $message = "PLEASE ENTER A VALID AGE";
        } else {
            if ($voterAge !== "") {
                // Age supplied - recompute eligibility status
                $voterStat = (intval($voterAge) >= 18) ? "Active" : "Inactive";
                $stmt = $conn->prepare("UPDATE voters SET voterPass = ?, voterFName = ?, voterMName = ?, voterLName = ?, voterStat = ? WHERE voterID = ?");
                $stmt->bind_param("ssssss", $voterPass, $voterFName, $voterMName, $voterLName, $voterStat, $id);
            } else {
                // Age not supplied - keep current status
                $stmt = $conn->prepare("UPDATE voters SET voterPass = ?, voterFName = ?, voterMName = ?, voterLName = ? WHERE voterID = ?");
                $stmt->bind_param("sssss", $voterPass, $voterFName, $voterMName, $voterLName, $id);
            }
            $stmt->execute();
            $stmt->close();
            $conn->close();
            header("Location: voters.php");
            exit();
        }
    }
} else {
    if ($id !== "") {
        $stmt = $conn->prepare("SELECT * FROM voters WHERE voterID = ?");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $voterPass = $row['voterPass'];
            $voterFName = $row['voterFName'];
            $voterMName = $row['voterMName'];
            $voterLName = $row['voterLName'];
        } else {
            $message = "VOTER NOT FOUND";
            $id = "";
        }
        $stmt->close();
    } else {
        $message = "NO VOTER SELECTED";
    }
}

include "header.php";
?>
<h2>Update Voter</h2>

<?php if ($message !== "") { ?>
    <div class="prompt-box"><?php echo htmlspecialchars($message); ?></div>
<?php } ?>

<?php if ($id !== "") { ?>
<form method="POST" action="update_voter.php">
    <input type="hidden" name="voterID" value="<?php echo htmlspecialchars($id); ?>">

    <label>Voter ID:</label>
    <input type="text" value="<?php echo htmlspecialchars($id); ?>" disabled>

    <label for="voterPass">Password:</label>
    <input type="text" id="voterPass" name="voterPass" value="<?php echo htmlspecialchars($voterPass); ?>" required>

    <label for="voterFName">First Name:</label>
    <input type="text" id="voterFName" name="voterFName" value="<?php echo htmlspecialchars($voterFName); ?>" required>

    <label for="voterMName">Middle Name:</label>
    <input type="text" id="voterMName" name="voterMName" value="<?php echo htmlspecialchars($voterMName); ?>">

    <label for="voterLName">Last Name:</label>
    <input type="text" id="voterLName" name="voterLName" value="<?php echo htmlspecialchars($voterLName); ?>" required>

    <label for="voterAge">Age (optional - leave blank to keep current status):</label>
    <input type="number" id="voterAge" name="voterAge" min="0" value="<?php echo htmlspecialchars($voterAge); ?>">
    <small>If entered, voters aged 18 and above will be set to "Active"; below 18 will be set to "Inactive".</small>

    <button type="submit">Update Voter</button>
</form>
<?php } ?>

<a class="back-link" href="voters.php">&laquo; Back to Voters List</a>

<?php include "footer.php"; $conn->close(); ?>
