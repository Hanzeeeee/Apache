<?php
include "db.php";
$message = "";

$id = isset($_GET['id']) ? intval($_GET['id']) : (isset($_POST['searchCode']) ? intval($_POST['searchCode']) : (isset($_POST['evCode']) ? intval($_POST['evCode']) : 0));
$event = null;

if ($id > 0) {
    $stmt = $conn->prepare("SELECT * FROM events WHERE evCode = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $event = $result->fetch_assoc();
    $stmt->close();

    if (!$event) {
        $message = "EVENT NOT FOUND";
    }
}

if ($event && $_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['confirm_delete'])) {
    // Check if any participants are linked to this event (foreign key protection)
    $checkStmt = $conn->prepare("SELECT COUNT(*) AS cnt FROM participants WHERE evCode = ?");
    $checkStmt->bind_param("i", $id);
    $checkStmt->execute();
    $cnt = $checkStmt->get_result()->fetch_assoc()['cnt'];
    $checkStmt->close();

    if ($cnt > 0) {
        $message = "CANNOT DELETE: THERE ARE " . $cnt . " PARTICIPANT(S) LINKED TO THIS EVENT. PLEASE REMOVE OR REASSIGN THEM FIRST.";
    } else {
        $delStmt = $conn->prepare("DELETE FROM events WHERE evCode = ?");
        $delStmt->bind_param("i", $id);
        $delStmt->execute();
        $delStmt->close();
        $conn->close();
        header("Location: events.php");
        exit();
    }
}

include "header.php";
?>
<h2>Search / Delete Event</h2>

<?php if ($message !== "") { ?>
    <div class="prompt-box"><?php echo htmlspecialchars($message); ?></div>
<?php } ?>

<form method="POST" action="delete_event.php" class="search-box">
    <input type="text" name="searchCode" placeholder="Enter Event Code (Ev Code)" value="<?php echo ($id > 0) ? htmlspecialchars($id) : ""; ?>" required>
    <button type="submit">Search</button>
</form>

<?php if ($event) { ?>
    <div class="readonly-box">
        <p><strong>Ev Code:</strong> <?php echo htmlspecialchars($event['evCode']); ?></p>
        <p><strong>Event Name:</strong> <?php echo htmlspecialchars($event['evName']); ?></p>
        <p><strong>Event Date:</strong> <?php echo htmlspecialchars($event['evDate']); ?></p>
        <p><strong>Venue:</strong> <?php echo htmlspecialchars($event['evVenue']); ?></p>
        <p><strong>Registration Fee:</strong> &#8369;<?php echo number_format($event['evRFee'], 2); ?></p>
    </div>

    <p>Are you sure you want to delete this event?</p>
    <form method="POST" action="delete_event.php">
        <input type="hidden" name="evCode" value="<?php echo htmlspecialchars($id); ?>">
        <button type="submit" name="confirm_delete">Yes, Delete This Event</button>
    </form>
<?php } ?>

<a class="back-link" href="events.php">&laquo; Back to Events List</a>

<?php include "footer.php"; $conn->close(); ?>
