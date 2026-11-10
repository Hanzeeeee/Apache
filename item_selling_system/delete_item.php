<?php
// delete_item.php - DELETE an item (asks for confirmation first)
require "db.php";
$id = (int)($_GET['id'] ?? $_POST['item_id'] ?? 0);

// An item that appears in sales cannot be deleted (foreign key rule)
$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM sales WHERE item_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
if ($stmt->get_result()->fetch_assoc()['total'] > 0) {
    header("Location: items.php?msg=Cannot delete: this item has sales records&type=error");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $stmt = $conn->prepare("DELETE FROM items WHERE item_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: items.php?msg=Item deleted");
    exit;
}
page_header("Delete Item");
?>
<p>Are you sure you want to delete item #<?= $id ?>?</p>
<form method="post">
    <input type="hidden" name="item_id" value="<?= $id ?>">
    <button class="btn btn-delete" type="submit">Yes, Delete</button>
    <a class="btn btn-gray" href="items.php">Cancel</a>
</form>
<?php page_footer(); ?>
