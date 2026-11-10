<?php
// add_category.php - ADD a new category
require "db.php";
$error = "";

// Runs only when the form is submitted
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $name = trim($_POST['category_name']);
    $desc = trim($_POST['description']);

    if ($name == "") {
        $error = "Category name is required.";
    } else {
        // Prepared statement: the ? marks are filled in safely by bind_param
        $stmt = $conn->prepare("INSERT INTO categories (category_name, description) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $desc);   // "ss" = two strings
        $stmt->execute();
        header("Location: categories.php?msg=Category added");
        exit;
    }
}
page_header("Add Category");
if ($error != "") echo "<div class='msg error'>" . h($error) . "</div>";
?>
<form method="post">
    <label>Category Name</label>
    <input type="text" name="category_name" value="<?= h($_POST['category_name'] ?? '') ?>">
    <label>Description</label>
    <textarea name="description"><?= h($_POST['description'] ?? '') ?></textarea>
    <button class="btn btn-add" type="submit">Save</button>
    <a class="btn btn-gray" href="categories.php">Cancel</a>
</form>
<?php page_footer(); ?>
