<?php
include "db.php";
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $studFName = trim($_POST['studFName']);
    $studMName = trim($_POST['studMName']);
    $studLName = trim($_POST['studLName']);

    if ($studFName === "" || $studLName === "") {
        $message = "PLEASE FILL OUT THE REQUIRED FIELDS (FIRST NAME, LAST NAME)";
    } else {
        $stmt = $conn->prepare("INSERT INTO student (studFName, studMName, studLName) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $studFName, $studMName, $studLName);
        $stmt->execute();
        $stmt->close();
        $conn->close();
        header("Location: students.php");
        exit();
    }
}

include "header.php";
?>
<h2>Add New Student</h2>

<?php if ($message !== "") { ?>
    <div class="prompt-box"><?php echo htmlspecialchars($message); ?></div>
<?php } ?>

<form method="POST" action="add_student.php">
    <label for="studFName">First Name:</label>
    <input type="text" id="studFName" name="studFName" value="<?php echo isset($_POST['studFName']) ? htmlspecialchars($_POST['studFName']) : ""; ?>" required>

    <label for="studMName">Middle Name:</label>
    <input type="text" id="studMName" name="studMName" value="<?php echo isset($_POST['studMName']) ? htmlspecialchars($_POST['studMName']) : ""; ?>">

    <label for="studLName">Last Name:</label>
    <input type="text" id="studLName" name="studLName" value="<?php echo isset($_POST['studLName']) ? htmlspecialchars($_POST['studLName']) : ""; ?>" required>

    <button type="submit">Add Student</button>
</form>

<a class="back-link" href="students.php">&laquo; Back to Students List</a>

<?php include "footer.php"; $conn->close(); ?>
