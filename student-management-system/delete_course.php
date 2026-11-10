<?php
include "db.php";
$message = "";

$id = isset($_GET['id']) ? intval($_GET['id']) : (isset($_POST['courseID']) ? intval($_POST['courseID']) : 0);
$course = null;

if ($id > 0) {
    $stmt = $conn->prepare("SELECT * FROM course WHERE courseID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $course = $result->fetch_assoc();
    $stmt->close();

    if (!$course) {
        $message = "COURSE NOT FOUND";
    }
} else {
    $message = "NO COURSE SELECTED";
}

if ($course && $_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['confirm_delete'])) {
    $checkStmt = $conn->prepare("SELECT COUNT(*) AS cnt FROM studyload WHERE courseID = ?");
    $checkStmt->bind_param("i", $id);
    $checkStmt->execute();
    $cnt = $checkStmt->get_result()->fetch_assoc()['cnt'];
    $checkStmt->close();

    if ($cnt > 0) {
        $message = "CANNOT DELETE: THERE ARE " . $cnt . " STUDYLOAD RECORD(S) LINKED TO THIS COURSE.";
    } else {
        $delStmt = $conn->prepare("DELETE FROM course WHERE courseID = ?");
        $delStmt->bind_param("i", $id);
        $delStmt->execute();
        $delStmt->close();
        $conn->close();
        header("Location: courses.php");
        exit();
    }
}

include "header.php";
?>
<h2>Delete Course</h2>

<?php if ($message !== "") { ?>
    <div class="prompt-box"><?php echo htmlspecialchars($message); ?></div>
<?php } ?>

<?php if ($course) { ?>
    <div class="readonly-box">
        <p><strong>Course ID:</strong> <?php echo htmlspecialchars($course['courseID']); ?></p>
        <p><strong>Course Name:</strong> <?php echo htmlspecialchars($course['courseName']); ?></p>
        <p><strong>Unit:</strong> <?php echo htmlspecialchars($course['courseUnit']); ?></p>
        <p><strong>Description:</strong> <?php echo htmlspecialchars($course['courseDescription']); ?></p>
    </div>

    <p>Are you sure you want to delete this course?</p>
    <form method="POST" action="delete_course.php">
        <input type="hidden" name="courseID" value="<?php echo htmlspecialchars($id); ?>">
        <button type="submit" name="confirm_delete">Yes, Delete This Course</button>
    </form>
<?php } ?>

<a class="back-link" href="courses.php">&laquo; Back to Courses List</a>

<?php include "footer.php"; $conn->close(); ?>
