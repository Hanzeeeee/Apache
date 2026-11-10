<?php
// delete_category.php - DELETE a category (asks for confirmation first)
require "db.php";
$id = (int)($_GET['id'] ?? $_POST['category_id'] ?? 0);

// A category that still has items cannot be deleted (foreign key rule)
$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM items WHERE category_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
if ($stmt->get_result()->fetch_assoc()['total'] > 0) {
    header("Location: categories.php?msg=Cannot delete: this category still has items&type=error");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $stmt = $conn->prepare("DELETE FROM categories WHERE category_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: categories.php?msg=Category deleted");
    exit;
}
page_header("Delete Category");
?>
<p>Are you sure you want to delete category #<?= $id ?>?</p>
<form method="post">
    <input type="hidden" name="category_id" value="<?= $id ?>">
    <button class="btn btn-delete" type="submit">Yes, Delete</button>
    <a class="btn btn-gray" href="categories.php">Cancel</a>
</form>
<?php page_footer(); ?>
