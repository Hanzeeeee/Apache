<?php
include "db.php";
$message = "";

$id = isset($_GET['id']) ? intval($_GET['id']) : (isset($_POST['searchCode']) ? intval($_POST['searchCode']) : (isset($_POST['partID']) ? intval($_POST['partID']) : 0));
$participant = null;

if ($id > 0) {
    $stmt = $conn->prepare("
        SELECT p.*, e.evName
        FROM participants p
        LEFT JOIN events e ON p.evCode = e.evCode
        WHERE p.partID = ?
    ");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $participant = $result->fetch_assoc();
    $stmt->close();

    if (!$participant) {
        $message = "PARTICIPANT NOT FOUND";
    }
}

if ($participant && $_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['confirm_delete'])) {
    // Check if any registration records are linked to this participant (foreign key protection)
    $checkStmt = $conn->prepare("SELECT COUNT(*) AS cnt FROM registration WHERE partID = ?");
    $checkStmt->bind_param("i", $id);
    $checkStmt->execute();
    $cnt = $checkStmt->get_result()->fetch_assoc()['cnt'];
    $checkStmt->close();

    if ($cnt > 0) {
        $message = "CANNOT DELETE: THERE ARE " . $cnt . " REGISTRATION RECORD(S) LINKED TO THIS PARTICIPANT.";
    } else {
        $delStmt = $conn->prepare("DELETE FROM participants WHERE partID = ?");
        $delStmt->bind_param("i", $id);
        $delStmt->execute();
        $delStmt->close();
        $conn->close();
        header("Location: participants.php");
        exit();
    }
}

include "header.php";
?>
<h2>Search / Delete Participant</h2>

<?php if ($message !== "") { ?>
    <div class="prompt-box"><?php echo htmlspecialchars($message); ?></div>
<?php } ?>

<form method="POST" action="delete_participant.php" class="search-box">
    <input type="text" name="searchCode" placeholder="Enter Participant ID (Part ID)" value="<?php echo ($id > 0) ? htmlspecialchars($id) : ""; ?>" required>
    <button type="submit">Search</button>
</form>

<?php if ($participant) { ?>
    <div class="readonly-box">
        <p><strong>Part ID:</strong> <?php echo htmlspecialchars($participant['partID']); ?></p>
        <p><strong>Full Name:</strong> <?php echo htmlspecialchars($participant['partFName'] . " " . $participant['partLName']); ?></p>
        <p><strong>Event:</strong> <?php echo htmlspecialchars($participant['evName'] !== null ? $participant['evName'] : "N/A"); ?></p>
        <p><strong>Discount Rate:</strong> &#8369;<?php echo number_format($participant['partDRate'], 2); ?></p>
    </div>

    <p>Are you sure you want to delete this participant?</p>
    <form method="POST" action="delete_participant.php">
        <input type="hidden" name="partID" value="<?php echo htmlspecialchars($id); ?>">
        <button type="submit" name="confirm_delete">Yes, Delete This Participant</button>
    </form>
<?php } ?>

<a class="back-link" href="participants.php">&laquo; Back to Participants List</a>

<?php include "footer.php"; $conn->close(); ?>
