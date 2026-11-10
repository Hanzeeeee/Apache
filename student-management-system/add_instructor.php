<?php
include "db.php";
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $instFName = trim($_POST['instFName']);
    $instMName = trim($_POST['instMName']);
    $instLName = trim($_POST['instLName']);

    if ($instFName === "" || $instLName === "") {
        $message = "PLEASE FILL OUT THE REQUIRED FIELDS (FIRST NAME, LAST NAME)";
    } else {
        $stmt = $conn->prepare("INSERT INTO instructor (instFName, instMName, instLName) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $instFName, $instMName, $instLName);
        $stmt->execute();
        $stmt->close();
        $conn->close();
        header("Location: instructors.php");
        exit();
    }
}

include "header.php";
?>
<h2>Add New Instructor</h2>

<?php if ($message !== "") { ?>
    <div class="prompt-box"><?php echo htmlspecialchars($message); ?></div>
<?php } ?>

<form method="POST" action="add_instructor.php">
    <label for="instFName">First Name:</label>
    <input type="text" id="instFName" name="instFName" value="<?php echo isset($_POST['instFName']) ? htmlspecialchars($_POST['instFName']) : ""; ?>" required>

    <label for="instMName">Middle Name:</label>
    <input type="text" id="instMName" name="instMName" value="<?php echo isset($_POST['instMName']) ? htmlspecialchars($_POST['instMName']) : ""; ?>">

    <label for="instLName">Last Name:</label>
    <input type="text" id="instLName" name="instLName" value="<?php echo isset($_POST['instLName']) ? htmlspecialchars($_POST['instLName']) : ""; ?>" required>

    <button type="submit">Add Instructor</button>
</form>

<a class="back-link" href="instructors.php">&laquo; Back to Instructors List</a>

<?php include "footer.php"; $conn->close(); ?>
