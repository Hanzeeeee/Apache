<?php
include "db.php";
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $evCode = trim($_POST['evCode']);
    $partFName = trim($_POST['partFName']);
    $partLName = trim($_POST['partLName']);
    $partDRate = trim($_POST['partDRate']);

    if ($evCode === "" || $partFName === "" || $partLName === "" || $partDRate === "") {
        $message = "PLEASE FILL OUT ALL FIELDS";
    } elseif (!is_numeric($partDRate) || floatval($partDRate) < 0) {
        $message = "DISCOUNT RATE MUST BE A VALID NUMBER";
    } else {
        $evCode = intval($evCode);
        $partDRate = floatval($partDRate);
        $stmt = $conn->prepare("INSERT INTO participants (evCode, partFName, partLName, partDRate) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("issd", $evCode, $partFName, $partLName, $partDRate);
        $stmt->execute();
        $stmt->close();
        $conn->close();
        header("Location: participants.php");
        exit();
    }
}

$events = $conn->query("SELECT * FROM events ORDER BY evName ASC");

include "header.php";
?>
<h2>Add New Participant</h2>

<?php if ($message !== "") { ?>
    <div class="prompt-box"><?php echo htmlspecialchars($message); ?></div>
<?php } ?>

<form method="POST" action="add_participant.php">
    <label for="partFName">First Name:</label>
    <input type="text" id="partFName" name="partFName" value="<?php echo isset($_POST['partFName']) ? htmlspecialchars($_POST['partFName']) : ""; ?>" required>

    <label for="partLName">Last Name:</label>
    <input type="text" id="partLName" name="partLName" value="<?php echo isset($_POST['partLName']) ? htmlspecialchars($_POST['partLName']) : ""; ?>" required>

    <label for="evCode">Event:</label>
    <select id="evCode" name="evCode" required>
        <option value="">-- Select Event --</option>
        <?php while ($ev = $events->fetch_assoc()) { ?>
            <option value="<?php echo $ev['evCode']; ?>" <?php echo (isset($_POST['evCode']) && $_POST['evCode'] == $ev['evCode']) ? "selected" : ""; ?>>
                <?php echo htmlspecialchars($ev['evName']); ?> (Fee: &#8369;<?php echo number_format($ev['evRFee'], 2); ?>)
            </option>
        <?php } ?>
    </select>

    <label for="partDRate">Discount Rate (amount deducted from Registration Fee):</label>
    <input type="number" step="0.01" min="0" id="partDRate" name="partDRate" value="<?php echo isset($_POST['partDRate']) ? htmlspecialchars($_POST['partDRate']) : "0"; ?>" required>

    <button type="submit">Add Participant</button>
</form>

<a class="back-link" href="participants.php">&laquo; Back to Participants List</a>

<?php include "footer.php"; $conn->close(); ?>
