<?php
include "db.php";
$message = "";

$id = isset($_GET['id']) ? intval($_GET['id']) : (isset($_POST['studID']) ? intval($_POST['studID']) : 0);
$student = null;

if ($id > 0) {
    $stmt = $conn->prepare("SELECT * FROM student WHERE studID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();
    $stmt->close();

    if (!$student) {
        $message = "STUDENT NOT FOUND";
    }
} else {
    $message = "NO STUDENT SELECTED";
}

if ($student && $_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['confirm_delete'])) {
    $checkStmt = $conn->prepare("SELECT COUNT(*) AS cnt FROM studyload WHERE studID = ?");
    $checkStmt->bind_param("i", $id);
    $checkStmt->execute();
    $cnt = $checkStmt->get_result()->fetch_assoc()['cnt'];
    $checkStmt->close();

    if ($cnt > 0) {
        $message = "CANNOT DELETE: THERE ARE " . $cnt . " STUDYLOAD RECORD(S) LINKED TO THIS STUDENT.";
    } else {
        $delStmt = $conn->prepare("DELETE FROM student WHERE studID = ?");
        $delStmt->bind_param("i", $id);
        $delStmt->execute();
        $delStmt->close();
        $conn->close();
        header("Location: students.php");
        exit();
    }
}

include "header.php";
?>
<h2>Delete Student</h2>

<?php if ($message !== "") { ?>
    <div class="prompt-box"><?php echo htmlspecialchars($message); ?></div>
<?php } ?>

<?php if ($student) { ?>
    <div class="readonly-box">
        <p><strong>Stud ID:</strong> <?php echo htmlspecialchars($student['studID']); ?></p>
        <p><strong>Full Name:</strong> <?php echo htmlspecialchars($student['studFName'] . " " . $student['studMName'] . " " . $student['studLName']); ?></p>
    </div>

    <p>Are you sure you want to delete this student?</p>
    <form method="POST" action="delete_student.php">
        <input type="hidden" name="studID" value="<?php echo htmlspecialchars($id); ?>">
        <button type="submit" name="confirm_delete">Yes, Delete This Student</button>
    </form>
<?php } ?>

<a class="back-link" href="students.php">&laquo; Back to Students List</a>

<?php include "footer.php"; $conn->close(); ?>
