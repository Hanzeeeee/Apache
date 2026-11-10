<?php
include "db.php";
$message = "";

$id = isset($_GET['id']) ? intval($_GET['id']) : (isset($_POST['instID']) ? intval($_POST['instID']) : 0);
$instructor = null;

if ($id > 0) {
    $stmt = $conn->prepare("SELECT * FROM instructor WHERE instID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $instructor = $result->fetch_assoc();
    $stmt->close();

    if (!$instructor) {
        $message = "INSTRUCTOR NOT FOUND";
    }
} else {
    $message = "NO INSTRUCTOR SELECTED";
}

if ($instructor && $_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['confirm_delete'])) {
    $checkStmt = $conn->prepare("SELECT COUNT(*) AS cnt FROM studyload WHERE instID = ?");
    $checkStmt->bind_param("i", $id);
    $checkStmt->execute();
    $cnt = $checkStmt->get_result()->fetch_assoc()['cnt'];
    $checkStmt->close();

    if ($cnt > 0) {
        $message = "CANNOT DELETE: THERE ARE " . $cnt . " STUDYLOAD RECORD(S) LINKED TO THIS INSTRUCTOR.";
    } else {
        $delStmt = $conn->prepare("DELETE FROM instructor WHERE instID = ?");
        $delStmt->bind_param("i", $id);
        $delStmt->execute();
        $delStmt->close();
        $conn->close();
        header("Location: instructors.php");
        exit();
    }
}

include "header.php";
?>
<h2>Delete Instructor</h2>

<?php if ($message !== "") { ?>
    <div class="prompt-box"><?php echo htmlspecialchars($message); ?></div>
<?php } ?>

<?php if ($instructor) { ?>
    <div class="readonly-box">
        <p><strong>Inst ID:</strong> <?php echo htmlspecialchars($instructor['instID']); ?></p>
        <p><strong>Full Name:</strong> <?php echo htmlspecialchars($instructor['instFName'] . " " . $instructor['instMName'] . " " . $instructor['instLName']); ?></p>
    </div>

    <p>Are you sure you want to delete this instructor?</p>
    <form method="POST" action="delete_instructor.php">
        <input type="hidden" name="instID" value="<?php echo htmlspecialchars($id); ?>">
        <button type="submit" name="confirm_delete">Yes, Delete This Instructor</button>
    </form>
<?php } ?>

<a class="back-link" href="instructors.php">&laquo; Back to Instructors List</a>

<?php include "footer.php"; $conn->close(); ?>
