<?php
// delete_sale.php - DELETE a sale (asks for confirmation first)
// The sold quantity is returned to the item's stock.
require "db.php";
$id = (int)($_GET['id'] ?? $_POST['sale_id'] ?? 0);

$stmt = $conn->prepare("SELECT * FROM sales WHERE sale_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$sale = $stmt->get_result()->fetch_assoc();
if (!$sale) { header("Location: sales.php?msg=Sale not found&type=error"); exit; }

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $conn->begin_transaction();

    // 1) Return the quantity to stock
    $stmt = $conn->prepare("UPDATE items SET quantity = quantity + ? WHERE item_id = ?");
    $stmt->bind_param("ii", $sale['quantity_sold'], $sale['item_id']);
    $stmt->execute();

    // 2) Delete the sale
    $stmt = $conn->prepare("DELETE FROM sales WHERE sale_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $conn->commit();
    header("Location: sales.php?msg=Sale deleted and stock restored");
    exit;
}
page_header("Delete Sale");
?>
<p>Are you sure you want to delete sale #<?= $id ?>? The sold quantity (<?= $sale['quantity_sold'] ?>) will be returned to stock.</p>
<form method="post">
    <input type="hidden" name="sale_id" value="<?= $id ?>">
    <button class="btn btn-delete" type="submit">Yes, Delete</button>
    <a class="btn btn-gray" href="sales.php">Cancel</a>
</form>
<?php page_footer(); ?>
