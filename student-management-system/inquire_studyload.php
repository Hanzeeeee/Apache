<?php
include "db.php";
include "header.php";

$studID = isset($_GET['studID']) ? intval($_GET['studID']) : 0;
$selectedStudent = null;
$rows = array();

if ($studID > 0) {
    $stmt = $conn->prepare("SELECT * FROM student WHERE studID = ?");
    $stmt->bind_param("i", $studID);
    $stmt->execute();
    $selectedStudent = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($selectedStudent) {
        // Inquiring enrolled courses + assigned instructor for each course
        $stmt2 = $conn->prepare("
            SELECT sl.studyLoadID, c.courseName, c.courseUnit,
                   i.instFName, i.instMName, i.instLName, sl.status
            FROM studyload sl
            JOIN course c ON sl.courseID = c.courseID
            JOIN instructor i ON sl.instID = i.instID
            WHERE sl.studID = ?
            ORDER BY sl.studyLoadID ASC
        ");
        $stmt2->bind_param("i", $studID);
        $stmt2->execute();
        $res2 = $stmt2->get_result();
        while ($r = $res2->fetch_assoc()) {
            $rows[] = $r;
        }
        $stmt2->close();
    }
}

$students = $conn->query("SELECT * FROM student ORDER BY studLName ASC");
?>
<h2>Inquire StudyLoad</h2>
<p>Select a student to view his/her enrolled courses and the assigned instructor for each course.</p>

<form method="GET" action="inquire_studyload.php" class="search-box">
    <select name="studID" required>
        <option value="">-- Select Student --</option>
        <?php while ($s = $students->fetch_assoc()) { ?>
            <option value="<?php echo $s['studID']; ?>" <?php echo ($studID == $s['studID']) ? "selected" : ""; ?>>
                <?php echo htmlspecialchars($s['studFName'] . " " . $s['studLName']); ?> (ID: <?php echo $s['studID']; ?>)
            </option>
        <?php } ?>
    </select>
    <button type="submit">Inquire</button>
</form>

<?php if ($studID > 0) { ?>
    <?php if (!$selectedStudent) { ?>
        <div class="prompt-box">STUDENT NOT FOUND</div>
    <?php } else { ?>
        <h3>Enrolled Courses of <?php echo htmlspecialchars($selectedStudent['studFName'] . " " . $selectedStudent['studMName'] . " " . $selectedStudent['studLName']); ?></h3>
        <table>
            <thead>
                <tr>
                    <th>Course Name</th>
                    <th>Unit</th>
                    <th>Assigned Instructor</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($rows) > 0) { ?>
                    <?php foreach ($rows as $r) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($r['courseName']); ?></td>
                            <td><?php echo htmlspecialchars($r['courseUnit']); ?></td>
                            <td><?php echo htmlspecialchars($r['instFName'] . " " . $r['instMName'] . " " . $r['instLName']); ?></td>
                            <td><?php echo htmlspecialchars($r['status']); ?></td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="4">This student has no enrolled courses yet.</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } ?>
<?php } ?>

<a class="back-link" href="studyloads.php">&laquo; Back to StudyLoad Records</a>

<?php include "footer.php"; $conn->close(); ?>
