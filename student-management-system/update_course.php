<?php
include "db.php";
$message = "";

$id = isset($_GET['id']) ? intval($_GET['id']) : (isset($_POST['courseID']) ? intval($_POST['courseID']) : 0);
$courseName = "";
$courseUnit = "";
$courseDescription = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $courseName = trim($_POST['courseName']);
    $courseUnit = trim($_POST['courseUnit']);
    $courseDescription = trim($_POST['courseDescription']);

    if ($courseName === "" || $courseUnit === "") {
        $message = "PLEASE FILL OUT THE REQUIRED FIELDS (COURSE NAME, UNIT)";
    } else {
        $stmt = $conn->prepare("UPDATE course SET courseName = ?, courseUnit = ?, courseDescription = ? WHERE courseID = ?");
        $stmt->bind_param("sssi", $courseName, $courseUnit, $courseDescription, $id);
        $stmt->execute();
        $stmt->close();
        $conn->close();
        header("Location: courses.php");
        exit();
    }
} else {
    if ($id > 0) {
        $stmt = $conn->prepare("SELECT * FROM course WHERE courseID = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $courseName = $row['courseName'];
            $courseUnit = $row['courseUnit'];
            $courseDescription = $row['courseDescription'];
        } else {
            $message = "COURSE NOT FOUND";
            $id = 0;
        }
        $stmt->close();
    } else {
        $message = "NO COURSE SELECTED";
    }
}

include "header.php";
?>
<h2>Update Course</h2>

<?php if ($message !== "") { ?>
    <div class="prompt-box"><?php echo htmlspecialchars($message); ?></div>
<?php } ?>

<?php if ($id > 0) { ?>
<form method="POST" action="update_course.php">
    <input type="hidden" name="courseID" value="<?php echo htmlspecialchars($id); ?>">

    <label for="courseName">Course Name:</label>
    <input type="text" id="courseName" name="courseName" value="<?php echo htmlspecialchars($courseName); ?>" required>

    <label for="courseUnit">Unit:</label>
    <input type="text" id="courseUnit" name="courseUnit" value="<?php echo htmlspecialchars($courseUnit); ?>" required>

    <label for="courseDescription">Description:</label>
    <textarea id="courseDescription" name="courseDescription"><?php echo htmlspecialchars($courseDescription); ?></textarea>

    <button type="submit">Update Course</button>
</form>
<?php } ?>

<a class="back-link" href="courses.php">&laquo; Back to Courses List</a>

<?php include "footer.php"; $conn->close(); ?>
