<?php
include "db.php";
$message = "";

$id = isset($_GET['id']) ? intval($_GET['id']) : (isset($_POST['studID']) ? intval($_POST['studID']) : 0);
$studFName = "";
$studMName = "";
$studLName = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $studFName = trim($_POST['studFName']);
    $studMName = trim($_POST['studMName']);
    $studLName = trim($_POST['studLName']);

    if ($studFName === "" || $studLName === "") {
        $message = "PLEASE FILL OUT THE REQUIRED FIELDS (FIRST NAME, LAST NAME)";
    } else {
        $stmt = $conn->prepare("UPDATE student SET studFName = ?, studMName = ?, studLName = ? WHERE studID = ?");
        $stmt->bind_param("sssi", $studFName, $studMName, $studLName, $id);
        $stmt->execute();
        $stmt->close();
        $conn->close();
        header("Location: students.php");
        exit();
    }
} else {
    if ($id > 0) {
        $stmt = $conn->prepare("SELECT * FROM student WHERE studID = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $studFName = $row['studFName'];
            $studMName = $row['studMName'];
            $studLName = $row['studLName'];
        } else {
            $message = "STUDENT NOT FOUND";
            $id = 0;
        }
        $stmt->close();
    } else {
        $message = "NO STUDENT SELECTED";
    }
}

include "header.php";
?>
<h2>Update Student</h2>

<?php if ($message !== "") { ?>
    <div class="prompt-box"><?php echo htmlspecialchars($message); ?></div>
<?php } ?>

<?php if ($id > 0) { ?>
<form method="POST" action="update_student.php">
    <input type="hidden" name="studID" value="<?php echo htmlspecialchars($id); ?>">

    <label for="studFName">First Name:</label>
    <input type="text" id="studFName" name="studFName" value="<?php echo htmlspecialchars($studFName); ?>" required>

    <label for="studMName">Middle Name:</label>
    <input type="text" id="studMName" name="studMName" value="<?php echo htmlspecialchars($studMName); ?>">

    <label for="studLName">Last Name:</label>
    <input type="text" id="studLName" name="studLName" value="<?php echo htmlspecialchars($studLName); ?>" required>

    <button type="submit">Update Student</button>
</form>
<?php } ?>

<a class="back-link" href="students.php">&laquo; Back to Students List</a>

<?php include "footer.php"; $conn->close(); ?>
