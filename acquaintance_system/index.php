<?php
// index.php - the whole system (login check + all CRUD)
ob_start();          // lets header() redirects work anywhere in the file
session_start();
include "db.php";

// ---------- LOGIN PROTECTION ----------
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

// ---------- WHICH TABLE ARE WE SHOWING? ----------
$t = $_GET['t'] ?? $_POST['t'] ?? 'acquaintance';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Acquaintance Registration System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="header">
    <h1>Acquaintance Registration System</h1>
    <div>
        <a href="index.php?t=acquaintance">Acquaintances</a>
        <a href="index.php?t=student">Students</a>
        <a href="index.php?t=instructor">Instructors</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">
<?php

// ==========================================
// CRUD SECTION: ACQUAINTANCE
// ==========================================
// COPY THIS ENTIRE SECTION TO CREATE ANOTHER TABLE'S CRUD.
// CHANGE ONLY:
// 1. TABLE NAME        (acquaintances)
// 2. PRIMARY KEY       (acquaintance_id)
// 3. COLUMN NAMES
// 4. FORM FIELD NAMES  (name="...")
// 5. PHP VARIABLE NAMES
// 6. SQL STATEMENTS
// 7. HTML TABLE HEADINGS
// ==========================================
if ($t == 'acquaintance') {

    // ---------- ADD ----------
    if (isset($_POST['add'])) {
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $address = $_POST['address'];
        $contact_number = $_POST['contact_number'];
        $email = $_POST['email'];
        $age = $_POST['age'];

        $stmt = $conn->prepare("INSERT INTO acquaintances (first_name, last_name, address, contact_number, email, age) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssi", $first_name, $last_name, $address, $contact_number, $email, $age);
        $stmt->execute();
        header("Location: index.php?t=acquaintance");
        exit;
    }

    // ---------- UPDATE ----------
    if (isset($_POST['update'])) {
        $acquaintance_id = $_POST['acquaintance_id'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $address = $_POST['address'];
        $contact_number = $_POST['contact_number'];
        $email = $_POST['email'];
        $age = $_POST['age'];

        $stmt = $conn->prepare("UPDATE acquaintances SET first_name=?, last_name=?, address=?, contact_number=?, email=?, age=? WHERE acquaintance_id=?");
        $stmt->bind_param("sssssii", $first_name, $last_name, $address, $contact_number, $email, $age, $acquaintance_id);
        $stmt->execute();
        header("Location: index.php?t=acquaintance");
        exit;
    }

    // ---------- DELETE (after clicking Yes) ----------
    if (isset($_GET['yes'])) {
        $acquaintance_id = $_GET['yes'];
        $stmt = $conn->prepare("DELETE FROM acquaintances WHERE acquaintance_id=?");
        $stmt->bind_param("i", $acquaintance_id);
        $stmt->execute();
        header("Location: index.php?t=acquaintance");
        exit;
    }

    // ---------- LOAD ROW FOR EDIT ----------
    $edit = null;
    if (isset($_GET['edit'])) {
        $acquaintance_id = $_GET['edit'];
        $stmt = $conn->prepare("SELECT * FROM acquaintances WHERE acquaintance_id=?");
        $stmt->bind_param("i", $acquaintance_id);
        $stmt->execute();
        $edit = $stmt->get_result()->fetch_assoc();
    }

    // ---------- SEARCH + READ ----------
    $search = $_GET['search'] ?? '';
    $like = "%" . $search . "%";
    $stmt = $conn->prepare("SELECT * FROM acquaintances WHERE first_name LIKE ? OR last_name LIKE ? ORDER BY acquaintance_id DESC");
    $stmt->bind_param("ss", $like, $like);
    $stmt->execute();
    $result = $stmt->get_result();
    ?>

    <h2>Acquaintance List</h2>

    <?php if (isset($_GET['delete'])) { ?>
        <div class="confirm-box">
            Are you sure you want to delete this record?
            <a class="btn btn-delete" href="index.php?t=acquaintance&yes=<?php echo $_GET['delete']; ?>">Yes, Delete</a>
            <a class="btn btn-gray" href="index.php?t=acquaintance">Cancel</a>
        </div>
    <?php } ?>

    <!-- ADD / EDIT FORM -->
    <form method="post" action="index.php?t=acquaintance">
        <?php if ($edit) { ?>
            <h3>Edit Acquaintance</h3>
            <input type="hidden" name="acquaintance_id" value="<?php echo $edit['acquaintance_id']; ?>">
        <?php } else { ?>
            <h3>Add Acquaintance</h3>
        <?php } ?>

            <label>First Name</label>
            <input type="text" name="first_name" value="<?php echo $edit['first_name'] ?? ''; ?>" required>
            <label>Last Name</label>
            <input type="text" name="last_name" value="<?php echo $edit['last_name'] ?? ''; ?>" required>
            <label>Address</label>
            <input type="text" name="address" value="<?php echo $edit['address'] ?? ''; ?>" required>
            <label>Contact Number</label>
            <input type="text" name="contact_number" value="<?php echo $edit['contact_number'] ?? ''; ?>" required>
            <label>Email</label>
            <input type="text" name="email" value="<?php echo $edit['email'] ?? ''; ?>" required>
            <label>Age</label>
            <input type="number" name="age" value="<?php echo $edit['age'] ?? ''; ?>" required>

        <?php if ($edit) { ?>
            <button type="submit" name="update">Update</button>
            <a class="btn btn-gray" href="index.php?t=acquaintance">Cancel</a>
        <?php } else { ?>
            <button type="submit" name="add">Add</button>
        <?php } ?>
    </form>

    <!-- SEARCH FORM -->
    <form method="get" class="search-box">
        <input type="hidden" name="t" value="acquaintance">
        <input type="text" name="search" placeholder="Search..." value="<?php echo $search; ?>">
        <button type="submit">Search</button>
    </form>

    <!-- TABLE -->
    <table>
        <tr>
            <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Address</th>
                <th>Contact Number</th>
                <th>Email</th>
                <th>Age</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['acquaintance_id']; ?></td>
                <td><?php echo $row['first_name']; ?></td>
                <td><?php echo $row['last_name']; ?></td>
                <td><?php echo $row['address']; ?></td>
                <td><?php echo $row['contact_number']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['age']; ?></td>
                <td>
                    <a class="btn btn-edit" href="index.php?t=acquaintance&edit=<?php echo $row['acquaintance_id']; ?>">Edit</a>
                    <a class="btn btn-delete" href="index.php?t=acquaintance&delete=<?php echo $row['acquaintance_id']; ?>">Delete</a>
                </td>
            </tr>
        <?php } ?>
    </table>

    <?php
}
// ============ END OF ACQUAINTANCE SECTION ============

// ==========================================
// CRUD SECTION: STUDENT
// ==========================================
// COPY THIS ENTIRE SECTION TO CREATE ANOTHER TABLE'S CRUD.
// CHANGE ONLY:
// 1. TABLE NAME        (students)
// 2. PRIMARY KEY       (student_id)
// 3. COLUMN NAMES
// 4. FORM FIELD NAMES  (name="...")
// 5. PHP VARIABLE NAMES
// 6. SQL STATEMENTS
// 7. HTML TABLE HEADINGS
// ==========================================
if ($t == 'student') {

    // ---------- ADD ----------
    if (isset($_POST['add'])) {
        $student_name = $_POST['student_name'];
        $course = $_POST['course'];
        $year_level = $_POST['year_level'];
        $section = $_POST['section'];

        $stmt = $conn->prepare("INSERT INTO students (student_name, course, year_level, section) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssis", $student_name, $course, $year_level, $section);
        $stmt->execute();
        header("Location: index.php?t=student");
        exit;
    }

    // ---------- UPDATE ----------
    if (isset($_POST['update'])) {
        $student_id = $_POST['student_id'];
        $student_name = $_POST['student_name'];
        $course = $_POST['course'];
        $year_level = $_POST['year_level'];
        $section = $_POST['section'];

        $stmt = $conn->prepare("UPDATE students SET student_name=?, course=?, year_level=?, section=? WHERE student_id=?");
        $stmt->bind_param("ssisi", $student_name, $course, $year_level, $section, $student_id);
        $stmt->execute();
        header("Location: index.php?t=student");
        exit;
    }

    // ---------- DELETE (after clicking Yes) ----------
    if (isset($_GET['yes'])) {
        $student_id = $_GET['yes'];
        $stmt = $conn->prepare("DELETE FROM students WHERE student_id=?");
        $stmt->bind_param("i", $student_id);
        $stmt->execute();
        header("Location: index.php?t=student");
        exit;
    }

    // ---------- LOAD ROW FOR EDIT ----------
    $edit = null;
    if (isset($_GET['edit'])) {
        $student_id = $_GET['edit'];
        $stmt = $conn->prepare("SELECT * FROM students WHERE student_id=?");
        $stmt->bind_param("i", $student_id);
        $stmt->execute();
        $edit = $stmt->get_result()->fetch_assoc();
    }

    // ---------- SEARCH + READ ----------
    $search = $_GET['search'] ?? '';
    $like = "%" . $search . "%";
    $stmt = $conn->prepare("SELECT * FROM students WHERE student_name LIKE ? OR course LIKE ? ORDER BY student_id DESC");
    $stmt->bind_param("ss", $like, $like);
    $stmt->execute();
    $result = $stmt->get_result();
    ?>

    <h2>Student List</h2>

    <?php if (isset($_GET['delete'])) { ?>
        <div class="confirm-box">
            Are you sure you want to delete this record?
            <a class="btn btn-delete" href="index.php?t=student&yes=<?php echo $_GET['delete']; ?>">Yes, Delete</a>
            <a class="btn btn-gray" href="index.php?t=student">Cancel</a>
        </div>
    <?php } ?>

    <!-- ADD / EDIT FORM -->
    <form method="post" action="index.php?t=student">
        <?php if ($edit) { ?>
            <h3>Edit Student</h3>
            <input type="hidden" name="student_id" value="<?php echo $edit['student_id']; ?>">
        <?php } else { ?>
            <h3>Add Student</h3>
        <?php } ?>

            <label>Student Name</label>
            <input type="text" name="student_name" value="<?php echo $edit['student_name'] ?? ''; ?>" required>
            <label>Course</label>
            <input type="text" name="course" value="<?php echo $edit['course'] ?? ''; ?>" required>
            <label>Year Level</label>
            <input type="number" name="year_level" value="<?php echo $edit['year_level'] ?? ''; ?>" required>
            <label>Section</label>
            <input type="text" name="section" value="<?php echo $edit['section'] ?? ''; ?>" required>

        <?php if ($edit) { ?>
            <button type="submit" name="update">Update</button>
            <a class="btn btn-gray" href="index.php?t=student">Cancel</a>
        <?php } else { ?>
            <button type="submit" name="add">Add</button>
        <?php } ?>
    </form>

    <!-- SEARCH FORM -->
    <form method="get" class="search-box">
        <input type="hidden" name="t" value="student">
        <input type="text" name="search" placeholder="Search..." value="<?php echo $search; ?>">
        <button type="submit">Search</button>
    </form>

    <!-- TABLE -->
    <table>
        <tr>
            <th>ID</th>
                <th>Student Name</th>
                <th>Course</th>
                <th>Year Level</th>
                <th>Section</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['student_id']; ?></td>
                <td><?php echo $row['student_name']; ?></td>
                <td><?php echo $row['course']; ?></td>
                <td><?php echo $row['year_level']; ?></td>
                <td><?php echo $row['section']; ?></td>
                <td>
                    <a class="btn btn-edit" href="index.php?t=student&edit=<?php echo $row['student_id']; ?>">Edit</a>
                    <a class="btn btn-delete" href="index.php?t=student&delete=<?php echo $row['student_id']; ?>">Delete</a>
                </td>
            </tr>
        <?php } ?>
    </table>

    <?php
}
// ============ END OF STUDENT SECTION ============

// ==========================================
// CRUD SECTION: INSTRUCTOR
// ==========================================
// COPY THIS ENTIRE SECTION TO CREATE ANOTHER TABLE'S CRUD.
// CHANGE ONLY:
// 1. TABLE NAME        (instructors)
// 2. PRIMARY KEY       (instructor_id)
// 3. COLUMN NAMES
// 4. FORM FIELD NAMES  (name="...")
// 5. PHP VARIABLE NAMES
// 6. SQL STATEMENTS
// 7. HTML TABLE HEADINGS
// ==========================================
if ($t == 'instructor') {

    // ---------- ADD ----------
    if (isset($_POST['add'])) {
        $instructor_name = $_POST['instructor_name'];
        $specialization = $_POST['specialization'];
        $contact_number = $_POST['contact_number'];

        $stmt = $conn->prepare("INSERT INTO instructors (instructor_name, specialization, contact_number) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $instructor_name, $specialization, $contact_number);
        $stmt->execute();
        header("Location: index.php?t=instructor");
        exit;
    }

    // ---------- UPDATE ----------
    if (isset($_POST['update'])) {
        $instructor_id = $_POST['instructor_id'];
        $instructor_name = $_POST['instructor_name'];
        $specialization = $_POST['specialization'];
        $contact_number = $_POST['contact_number'];

        $stmt = $conn->prepare("UPDATE instructors SET instructor_name=?, specialization=?, contact_number=? WHERE instructor_id=?");
        $stmt->bind_param("sssi", $instructor_name, $specialization, $contact_number, $instructor_id);
        $stmt->execute();
        header("Location: index.php?t=instructor");
        exit;
    }

    // ---------- DELETE (after clicking Yes) ----------
    if (isset($_GET['yes'])) {
        $instructor_id = $_GET['yes'];
        $stmt = $conn->prepare("DELETE FROM instructors WHERE instructor_id=?");
        $stmt->bind_param("i", $instructor_id);
        $stmt->execute();
        header("Location: index.php?t=instructor");
        exit;
    }

    // ---------- LOAD ROW FOR EDIT ----------
    $edit = null;
    if (isset($_GET['edit'])) {
        $instructor_id = $_GET['edit'];
        $stmt = $conn->prepare("SELECT * FROM instructors WHERE instructor_id=?");
        $stmt->bind_param("i", $instructor_id);
        $stmt->execute();
        $edit = $stmt->get_result()->fetch_assoc();
    }

    // ---------- SEARCH + READ ----------
    $search = $_GET['search'] ?? '';
    $like = "%" . $search . "%";
    $stmt = $conn->prepare("SELECT * FROM instructors WHERE instructor_name LIKE ? OR specialization LIKE ? ORDER BY instructor_id DESC");
    $stmt->bind_param("ss", $like, $like);
    $stmt->execute();
    $result = $stmt->get_result();
    ?>

    <h2>Instructor List</h2>

    <?php if (isset($_GET['delete'])) { ?>
        <div class="confirm-box">
            Are you sure you want to delete this record?
            <a class="btn btn-delete" href="index.php?t=instructor&yes=<?php echo $_GET['delete']; ?>">Yes, Delete</a>
            <a class="btn btn-gray" href="index.php?t=instructor">Cancel</a>
        </div>
    <?php } ?>

    <!-- ADD / EDIT FORM -->
    <form method="post" action="index.php?t=instructor">
        <?php if ($edit) { ?>
            <h3>Edit Instructor</h3>
            <input type="hidden" name="instructor_id" value="<?php echo $edit['instructor_id']; ?>">
        <?php } else { ?>
            <h3>Add Instructor</h3>
        <?php } ?>

            <label>Instructor Name</label>
            <input type="text" name="instructor_name" value="<?php echo $edit['instructor_name'] ?? ''; ?>" required>
            <label>Specialization</label>
            <input type="text" name="specialization" value="<?php echo $edit['specialization'] ?? ''; ?>" required>
            <label>Contact Number</label>
            <input type="text" name="contact_number" value="<?php echo $edit['contact_number'] ?? ''; ?>" required>

        <?php if ($edit) { ?>
            <button type="submit" name="update">Update</button>
            <a class="btn btn-gray" href="index.php?t=instructor">Cancel</a>
        <?php } else { ?>
            <button type="submit" name="add">Add</button>
        <?php } ?>
    </form>

    <!-- SEARCH FORM -->
    <form method="get" class="search-box">
        <input type="hidden" name="t" value="instructor">
        <input type="text" name="search" placeholder="Search..." value="<?php echo $search; ?>">
        <button type="submit">Search</button>
    </form>

    <!-- TABLE -->
    <table>
        <tr>
            <th>ID</th>
                <th>Instructor Name</th>
                <th>Specialization</th>
                <th>Contact Number</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['instructor_id']; ?></td>
                <td><?php echo $row['instructor_name']; ?></td>
                <td><?php echo $row['specialization']; ?></td>
                <td><?php echo $row['contact_number']; ?></td>
                <td>
                    <a class="btn btn-edit" href="index.php?t=instructor&edit=<?php echo $row['instructor_id']; ?>">Edit</a>
                    <a class="btn btn-delete" href="index.php?t=instructor&delete=<?php echo $row['instructor_id']; ?>">Delete</a>
                </td>
            </tr>
        <?php } ?>
    </table>

    <?php
}
// ============ END OF INSTRUCTOR SECTION ============

?>
</div>

</body>
</html>
