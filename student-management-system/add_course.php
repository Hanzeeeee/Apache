<?php
include "db.php";
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $courseName = trim($_POST['courseName']);
    $courseUnit = trim($_POST['courseUnit']);
    $courseDescription = trim($_POST['courseDescription']);

    if ($courseName === "" || $courseUnit === "") {
        $message = "PLEASE FILL OUT THE REQUIRED FIELDS (COURSE NAME, UNIT)";
    } else {
        $stmt = $conn->prepare("INSERT INTO course (courseName, courseUnit, courseDescription) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $courseName, $courseUnit, $courseDescription);
        $stmt->execute();
        $stmt->close();
        $conn->close();
        header("Location: courses.php");
        exit();
    }
}

include "header.php";
?>
<h2>Add New Course</h2>

<?php if ($message !== "") { ?>
    <div class="prompt-box"><?php echo htmlspecialchars($message); ?></div>
<?php } ?>

<form method="POST" action="add_course.php">
    <label for="courseName">Course Name:</label>
    <input type="text" id="courseName" name="courseName" value="<?php echo isset($_POST['courseName']) ? htmlspecialchars($_POST['courseName']) : ""; ?>" required>

    <label for="courseUnit">Unit:</label>
    <input type="text" id="courseUnit" name="courseUnit" value="<?php echo isset($_POST['courseUnit']) ? htmlspecialchars($_POST['courseUnit']) : ""; ?>" required>

    <label for="courseDescription">Description:</label>
    <textarea id="courseDescription" name="courseDescription"><?php echo isset($_POST['courseDescription']) ? htmlspecialchars($_POST['courseDescription']) : ""; ?></textarea>

    <button type="submit">Add Course</button>
</form>

<a class="back-link" href="courses.php">&laquo; Back to Courses List</a>

<?php include "footer.php"; $conn->close(); ?>
