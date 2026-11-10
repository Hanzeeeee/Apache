<?php
include "db.php";
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $evName = trim($_POST['evName']);
    $evDate = trim($_POST['evDate']);
    $evVenue = trim($_POST['evVenue']);
    $evRFee = trim($_POST['evRFee']);

    if ($evName === "" || $evDate === "" || $evVenue === "" || $evRFee === "") {
        $message = "PLEASE FILL OUT ALL FIELDS";
    } elseif (!is_numeric($evRFee) || floatval($evRFee) < 0) {
        $message = "REGISTRATION FEE MUST BE A VALID NUMBER";
    } else {
        $evRFee = floatval($evRFee);
        $stmt = $conn->prepare("INSERT INTO events (evName, evDate, evVenue, evRFee) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssd", $evName, $evDate, $evVenue, $evRFee);
        $stmt->execute();
        $stmt->close();
        $conn->close();
        header("Location: events.php");
        exit();
    }
}

include "header.php";
?>
<h2>Add New Event</h2>

<?php if ($message !== "") { ?>
    <div class="prompt-box"><?php echo htmlspecialchars($message); ?></div>
<?php } ?>

<form method="POST" action="add_event.php">
    <label for="evName">Event Name:</label>
    <input type="text" id="evName" name="evName" value="<?php echo isset($_POST['evName']) ? htmlspecialchars($_POST['evName']) : ""; ?>" required>

    <label for="evDate">Event Date:</label>
    <input type="date" id="evDate" name="evDate" value="<?php echo isset($_POST['evDate']) ? htmlspecialchars($_POST['evDate']) : ""; ?>" required>

    <label for="evVenue">Venue:</label>
    <input type="text" id="evVenue" name="evVenue" value="<?php echo isset($_POST['evVenue']) ? htmlspecialchars($_POST['evVenue']) : ""; ?>" required>

    <label for="evRFee">Registration Fee:</label>
    <input type="number" step="0.01" min="0" id="evRFee" name="evRFee" value="<?php echo isset($_POST['evRFee']) ? htmlspecialchars($_POST['evRFee']) : ""; ?>" required>

    <button type="submit">Add Event</button>
</form>

<a class="back-link" href="events.php">&laquo; Back to Events List</a>

<?php include "footer.php"; $conn->close(); ?>
