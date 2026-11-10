<?php
include "db.php";
include "header.php";
?>
<h2>Welcome</h2>
<p>This is a simple Student Management System. Use the menu above to manage students, instructors, and courses, and to view or add StudyLoad (enrollment) records.</p>

<table class="menu-table">
    <tr>
        <td><a href="students.php">Student Management</a></td>
        <td>Add, search, update, delete, and view student records.</td>
    </tr>
    <tr>
        <td><a href="instructors.php">Instructor Management</a></td>
        <td>Add, search, update, delete, and view instructor records.</td>
    </tr>
    <tr>
        <td><a href="courses.php">Course Management</a></td>
        <td>Add, search, update, delete, and view course records.</td>
    </tr>
    <tr>
        <td><a href="studyloads.php">View StudyLoad Records</a></td>
        <td>View all StudyLoad (enrollment) records.</td>
    </tr>
    <tr>
        <td><a href="inquire_studyload.php">Inquire StudyLoad</a></td>
        <td>Inquire a student's enrolled courses and their assigned instructors.</td>
    </tr>
    <tr>
        <td><a href="add_studyload.php">Add StudyLoad</a></td>
        <td>Enroll a student to a course under an instructor.</td>
    </tr>
</table>

<?php include "footer.php"; $conn->close(); ?>
