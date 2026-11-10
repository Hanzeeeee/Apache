<?php
include "db.php";
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $studID = trim($_POST['studID']);
    $courseID = trim($_POST['courseID']);
    $instID = trim($_POST['instID']);
    $status = trim($_POST['status']);

    if ($studID === "" || $courseID === "" || $instID === "" || $status === "") {
        $message = "PLEASE FILL OUT ALL FIELDS";
    } else {
        $studID = intval($studID);
        $courseID = intval($courseID);
        $instID = intval($instID);

        // Prevent duplicate enrollment of the same student in the same course
        $checkStmt = $conn->prepare("SELECT COUNT(*) AS cnt FROM studyload WHERE studID = ? AND courseID = ?");
        $checkStmt->bind_param("ii", $studID, $courseID);
        $checkStmt->execute();
        $cnt = $checkStmt->get_result()->fetch_assoc()['cnt'];
        $checkStmt->close();

        if ($cnt > 0) {
            $message = "THIS STUDENT IS ALREADY ENROLLED IN THIS COURSE";
        } else {
            $stmt = $conn->prepare("INSERT INTO studyload (studID, courseID, instID, status) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiis", $studID, $courseID, $instID, $status);
            $stmt->execute();
            $stmt->close();
            $conn->close();
            header("Location: studyloads.php");
            exit();
        }
    }
}

$students = $conn->query("SELECT * FROM student ORDER BY studLName ASC");
$courses = $conn->query("SELECT * FROM course ORDER BY courseName ASC");
$instructors = $conn->query("SELECT * FROM instructor ORDER BY instLName ASC");

include "header.php";
?>
<h2>Add New StudyLoad</h2>

<?php if ($message !== "") { ?>
    <div class="prompt-box"><?php echo htmlspecialchars($message); ?></div>
<?php } ?>

<form method="POST" action="add_studyload.php">
    <label for="studID">Student:</label>
    <select id="studID" name="studID" required>
        <option value="">-- Select Student --</option>
        <?php while ($s = $students->fetch_assoc()) { ?>
            <option value="<?php echo $s['studID']; ?>" <?php echo (isset($_POST['studID']) && $_POST['studID'] == $s['studID']) ? "selected" : ""; ?>>
                <?php echo htmlspecialchars($s['studFName'] . " " . $s['studLName']); ?>
            </option>
        <?php } ?>
    </select>

    <label for="courseID">Course:</label>
    <select id="courseID" name="courseID" required>
        <option value="">-- Select Course --</option>
        <?php while ($c = $courses->fetch_assoc()) { ?>
            <option value="<?php echo $c['courseID']; ?>" <?php echo (isset($_POST['courseID']) && $_POST['courseID'] == $c['courseID']) ? "selected" : ""; ?>>
                <?php echo htmlspecialchars($c['courseName']); ?>
            </option>
        <?php } ?>
    </select>

    <label for="instID">Instructor:</label>
    <select id="instID" name="instID" required>
        <option value="">-- Select Instructor --</option>
        <?php while ($i = $instructors->fetch_assoc()) { ?>
            <option value="<?php echo $i['instID']; ?>" <?php echo (isset($_POST['instID']) && $_POST['instID'] == $i['instID']) ? "selected" : ""; ?>>
                <?php echo htmlspecialchars($i['instFName'] . " " . $i['instLName']); ?>
            </option>
        <?php } ?>
    </select>

    <label for="status">Status:</label>
    <select id="status" name="status" required>
        <option value="Enrolled" <?php echo (isset($_POST['status']) && $_POST['status'] == 'Enrolled') ? "selected" : ""; ?>>Enrolled</option>
        <option value="Dropped" <?php echo (isset($_POST['status']) && $_POST['status'] == 'Dropped') ? "selected" : ""; ?>>Dropped</option>
        <option value="Completed" <?php echo (isset($_POST['status']) && $_POST['status'] == 'Completed') ? "selected" : ""; ?>>Completed</option>
    </select>

    <button type="submit">Add StudyLoad</button>
</form>

<a class="back-link" href="studyloads.php">&laquo; Back to StudyLoad Records</a>

<?php include "footer.php"; $conn->close(); ?>
