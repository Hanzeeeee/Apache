<?php
// edit_category.php - EDIT an existing category
require "db.php";
$error = "";
$id = (int)($_GET['id'] ?? $_POST['category_id'] ?? 0);

// Load the current record
$stmt = $conn->prepare("SELECT * FROM categories WHERE category_id = ?");
$stmt->bind_param("i", $id);   // "i" = integer
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
if (!$row) { header("Location: categories.php?msg=Category not found&type=error"); exit; }

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $name = trim($_POST['category_name']);
    $desc = trim($_POST['description']);
    if ($name == "") {
        $error = "Category name is required.";
    } else {
        $stmt = $conn->prepare("UPDATE categories SET category_name = ?, description = ? WHERE category_id = ?");
        $stmt->bind_param("ssi", $name, $desc, $id);
        $stmt->execute();
        header("Location: categories.php?msg=Category updated");
        exit;
    }
    $row['category_name'] = $name;
    $row['description'] = $desc;
}
page_header("Edit Category");
if ($error != "") echo "<div class='msg error'>" . h($error) . "</div>";
?>
<form method="post">
    <input type="hidden" name="category_id" value="<?= $id ?>">
    <label>Category Name</label>
    <input type="text" name="category_name" value="<?= h($row['category_name']) ?>">
    <label>Description</label>
    <textarea name="description"><?= h($row['description']) ?></textarea>
    <button class="btn btn-edit" type="submit">Update</button>
    <a class="btn btn-gray" href="categories.php">Cancel</a>
</form>
<?php page_footer(); ?>
