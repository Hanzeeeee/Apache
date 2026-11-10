<?php
include "db.php";
$message = "";

// The ID can come from the Events list link (?id=) or from the search box below (evCode field)
$id = isset($_GET['id']) ? intval($_GET['id']) : (isset($_POST['searchCode']) ? intval($_POST['searchCode']) : (isset($_POST['evCode']) ? intval($_POST['evCode']) : 0));

$evName = "";
$evDate = "";
$evVenue = "";
$evRFee = "";
$found = false;

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update'])) {
    // SAVE the updated record
    $evName = trim($_POST['evName']);
    $evDate = trim($_POST['evDate']);
    $evVenue = trim($_POST['evVenue']);
    $evRFee = trim($_POST['evRFee']);

    if ($evName === "" || $evDate === "" || $evVenue === "" || $evRFee === "") {
        $message = "PLEASE FILL OUT ALL FIELDS";
        $found = true;
    } elseif (!is_numeric($evRFee) || floatval($evRFee) < 0) {
        $message = "REGISTRATION FEE MUST BE A VALID NUMBER";
        $found = true;
    } else {
        $evRFee = floatval($evRFee);
        $stmt = $conn->prepare("UPDATE events SET evName = ?, evDate = ?, evVenue = ?, evRFee = ? WHERE evCode = ?");
        $stmt->bind_param("sssdi", $evName, $evDate, $evVenue, $evRFee, $id);
        $stmt->execute();
        $stmt->close();
        $conn->close();
        header("Location: events.php");
        exit();
    }
} elseif ($id > 0) {
    // SEARCH for the record (either from ?id= link, or from the search form)
    $stmt = $conn->prepare("SELECT * FROM events WHERE evCode = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $evName = $row['evName'];
        $evDate = $row['evDate'];
        $evVenue = $row['evVenue'];
        $evRFee = $row['evRFee'];
        $found = true;
    } else {
        $message = "EVENT NOT FOUND";
    }
    $stmt->close();
}

include "header.php";
?>
<h2>Search / Update Event</h2>

<?php if ($message !== "") { ?>
    <div class="prompt-box"><?php echo htmlspecialchars($message); ?></div>
<?php } ?>

<form method="POST" action="update_event.php" class="search-box">
    <input type="text" name="searchCode" placeholder="Enter Event Code (Ev Code)" value="<?php echo ($id > 0) ? htmlspecialchars($id) : ""; ?>" required>
    <button type="submit">Search</button>
</form>

<?php if ($found) { ?>
<form method="POST" action="update_event.php">
    <input type="hidden" name="evCode" value="<?php echo htmlspecialchars($id); ?>">

    <label for="evName">Event Name:</label>
    <input type="text" id="evName" name="evName" value="<?php echo htmlspecialchars($evName); ?>" required>

    <label for="evDate">Event Date:</label>
    <input type="date" id="evDate" name="evDate" value="<?php echo htmlspecialchars($evDate); ?>" required>

    <label for="evVenue">Venue:</label>
    <input type="text" id="evVenue" name="evVenue" value="<?php echo htmlspecialchars($evVenue); ?>" required>

    <label for="evRFee">Registration Fee:</label>
    <input type="number" step="0.01" min="0" id="evRFee" name="evRFee" value="<?php echo htmlspecialchars($evRFee); ?>" required>

    <button type="submit" name="update">Update Event</button>
</form>
<?php } ?>

<a class="back-link" href="events.php">&laquo; Back to Events List</a>

<?php include "footer.php"; $conn->close(); ?>
