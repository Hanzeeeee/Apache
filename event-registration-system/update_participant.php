<?php
include "db.php";
$message = "";

$id = isset($_GET['id']) ? intval($_GET['id']) : (isset($_POST['searchCode']) ? intval($_POST['searchCode']) : (isset($_POST['partID']) ? intval($_POST['partID']) : 0));

$evCode = "";
$partFName = "";
$partLName = "";
$partDRate = "";
$found = false;

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update'])) {
    $evCode = trim($_POST['evCode']);
    $partFName = trim($_POST['partFName']);
    $partLName = trim($_POST['partLName']);
    $partDRate = trim($_POST['partDRate']);

    if ($evCode === "" || $partFName === "" || $partLName === "" || $partDRate === "") {
        $message = "PLEASE FILL OUT ALL FIELDS";
        $found = true;
    } elseif (!is_numeric($partDRate) || floatval($partDRate) < 0) {
        $message = "DISCOUNT RATE MUST BE A VALID NUMBER";
        $found = true;
    } else {
        $evCode = intval($evCode);
        $partDRate = floatval($partDRate);
        $stmt = $conn->prepare("UPDATE participants SET evCode = ?, partFName = ?, partLName = ?, partDRate = ? WHERE partID = ?");
        $stmt->bind_param("issdi", $evCode, $partFName, $partLName, $partDRate, $id);
        $stmt->execute();
        $stmt->close();
        $conn->close();
        header("Location: participants.php");
        exit();
    }
} elseif ($id > 0) {
    $stmt = $conn->prepare("SELECT * FROM participants WHERE partID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $evCode = $row['evCode'];
        $partFName = $row['partFName'];
        $partLName = $row['partLName'];
        $partDRate = $row['partDRate'];
        $found = true;
    } else {
        $message = "PARTICIPANT NOT FOUND";
    }
    $stmt->close();
}

$events = $conn->query("SELECT * FROM events ORDER BY evName ASC");

include "header.php";
?>
<h2>Search / Update Participant</h2>

<?php if ($message !== "") { ?>
    <div class="prompt-box"><?php echo htmlspecialchars($message); ?></div>
<?php } ?>

<form method="POST" action="update_participant.php" class="search-box">
    <input type="text" name="searchCode" placeholder="Enter Participant ID (Part ID)" value="<?php echo ($id > 0) ? htmlspecialchars($id) : ""; ?>" required>
    <button type="submit">Search</button>
</form>

<?php if ($found) { ?>
<form method="POST" action="update_participant.php">
    <input type="hidden" name="partID" value="<?php echo htmlspecialchars($id); ?>">

    <label for="partFName">First Name:</label>
    <input type="text" id="partFName" name="partFName" value="<?php echo htmlspecialchars($partFName); ?>" required>

    <label for="partLName">Last Name:</label>
    <input type="text" id="partLName" name="partLName" value="<?php echo htmlspecialchars($partLName); ?>" required>

    <label for="evCode">Event:</label>
    <select id="evCode" name="evCode" required>
        <option value="">-- Select Event --</option>
        <?php while ($ev = $events->fetch_assoc()) { ?>
            <option value="<?php echo $ev['evCode']; ?>" <?php echo ($evCode == $ev['evCode']) ? "selected" : ""; ?>>
                <?php echo htmlspecialchars($ev['evName']); ?> (Fee: &#8369;<?php echo number_format($ev['evRFee'], 2); ?>)
            </option>
        <?php } ?>
    </select>

    <label for="partDRate">Discount Rate (amount deducted from Registration Fee):</label>
    <input type="number" step="0.01" min="0" id="partDRate" name="partDRate" value="<?php echo htmlspecialchars($partDRate); ?>" required>

    <button type="submit" name="update">Update Participant</button>
</form>
<?php } ?>

<a class="back-link" href="participants.php">&laquo; Back to Participants List</a>

<?php include "footer.php"; $conn->close(); ?>
