<?php
// edit_item.php - EDIT an existing item
require "db.php";
$error = "";
$id = (int)($_GET['id'] ?? $_POST['item_id'] ?? 0);

$stmt = $conn->prepare("SELECT * FROM items WHERE item_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
if (!$row) { header("Location: items.php?msg=Item not found&type=error"); exit; }

$categories = $conn->query("SELECT category_id, category_name FROM categories ORDER BY category_name");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $category_id = (int)$_POST['category_id'];
    $name  = trim($_POST['item_name']);
    $desc  = trim($_POST['description']);
    $price = (float)$_POST['price'];
    $qty   = (int)$_POST['quantity'];

    if ($name == "" || $category_id <= 0) {
        $error = "Please choose a category and enter the item name.";
    } elseif ($price < 0 || $qty < 0) {
        $error = "Price and quantity cannot be negative.";
    } else {
        $stmt = $conn->prepare("UPDATE items SET category_id=?, item_name=?, description=?, price=?, quantity=? WHERE item_id=?");
        $stmt->bind_param("issdii", $category_id, $name, $desc, $price, $qty, $id);
        $stmt->execute();
        header("Location: items.php?msg=Item updated");
        exit;
    }
    // Keep what the user typed if there was an error
    $row['category_id'] = $category_id; $row['item_name'] = $name; $row['description'] = $desc;
    $row['price'] = $price; $row['quantity'] = $qty;
}
page_header("Edit Item");
if ($error != "") echo "<div class='msg error'>" . h($error) . "</div>";
?>
<form method="post">
    <input type="hidden" name="item_id" value="<?= $id ?>">
    <label>Category</label>
    <select name="category_id">
        <?php while ($c = $categories->fetch_assoc()) { ?>
            <option value="<?= $c['category_id'] ?>" <?= ($row['category_id'] == $c['category_id']) ? 'selected' : '' ?>>
                <?= h($c['category_name']) ?>
            </option>
        <?php } ?>
    </select>
    <label>Item Name</label>
    <input type="text" name="item_name" value="<?= h($row['item_name']) ?>">
    <label>Description</label>
    <textarea name="description"><?= h($row['description']) ?></textarea>
    <label>Price</label>
    <input type="number" step="0.01" min="0" name="price" value="<?= h($row['price']) ?>">
    <label>Quantity in Stock</label>
    <input type="number" min="0" name="quantity" value="<?= h($row['quantity']) ?>">
    <button class="btn btn-edit" type="submit">Update</button>
    <a class="btn btn-gray" href="items.php">Cancel</a>
</form>
<?php page_footer(); ?>
