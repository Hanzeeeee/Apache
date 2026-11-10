<?php
include "db.php";
$message = "";

$id = isset($_GET['id']) ? intval($_GET['id']) : (isset($_POST['instID']) ? intval($_POST['instID']) : 0);
$instFName = "";
$instMName = "";
$instLName = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $instFName = trim($_POST['instFName']);
    $instMName = trim($_POST['instMName']);
    $instLName = trim($_POST['instLName']);

    if ($instFName === "" || $instLName === "") {
        $message = "PLEASE FILL OUT THE REQUIRED FIELDS (FIRST NAME, LAST NAME)";
    } else {
        $stmt = $conn->prepare("UPDATE instructor SET instFName = ?, instMName = ?, instLName = ? WHERE instID = ?");
        $stmt->bind_param("sssi", $instFName, $instMName, $instLName, $id);
        $stmt->execute();
        $stmt->close();
        $conn->close();
        header("Location: instructors.php");
        exit();
    }
} else {
    if ($id > 0) {
        $stmt = $conn->prepare("SELECT * FROM instructor WHERE instID = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $instFName = $row['instFName'];
            $instMName = $row['instMName'];
            $instLName = $row['instLName'];
        } else {
            $message = "INSTRUCTOR NOT FOUND";
            $id = 0;
        }
        $stmt->close();
    } else {
        $message = "NO INSTRUCTOR SELECTED";
    }
}

include "header.php";
?>
<h2>Update Instructor</h2>

<?php if ($message !== "") { ?>
    <div class="prompt-box"><?php echo htmlspecialchars($message); ?></div>
<?php } ?>

<?php if ($id > 0) { ?>
<form method="POST" action="update_instructor.php">
    <input type="hidden" name="instID" value="<?php echo htmlspecialchars($id); ?>">

    <label for="instFName">First Name:</label>
    <input type="text" id="instFName" name="instFName" value="<?php echo htmlspecialchars($instFName); ?>" required>

    <label for="instMName">Middle Name:</label>
    <input type="text" id="instMName" name="instMName" value="<?php echo htmlspecialchars($instMName); ?>">

    <label for="instLName">Last Name:</label>
    <input type="text" id="instLName" name="instLName" value="<?php echo htmlspecialchars($instLName); ?>" required>

    <button type="submit">Update Instructor</button>
</form>
<?php } ?>

<a class="back-link" href="instructors.php">&laquo; Back to Instructors List</a>

<?php include "footer.php"; $conn->close(); ?>
