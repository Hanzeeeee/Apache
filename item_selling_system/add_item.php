<?php
// add_item.php - ADD a new item
require "db.php";
$error = "";

// Categories for the dropdown list
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
        $stmt = $conn->prepare("INSERT INTO items (category_id, item_name, description, price, quantity) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issdi", $category_id, $name, $desc, $price, $qty); // i=int s=string d=decimal
        $stmt->execute();
        header("Location: items.php?msg=Item added");
        exit;
    }
}
page_header("Add Item");
if ($error != "") echo "<div class='msg error'>" . h($error) . "</div>";
if ($categories->num_rows == 0) {
    echo "<p>Please <a href='add_category.php'>add a category</a> first.</p>";
    page_footer(); exit;
}
?>
<form method="post">
    <label>Category</label>
    <select name="category_id">
        <?php while ($c = $categories->fetch_assoc()) { ?>
            <option value="<?= $c['category_id'] ?>" <?= (($_POST['category_id'] ?? '') == $c['category_id']) ? 'selected' : '' ?>>
                <?= h($c['category_name']) ?>
            </option>
        <?php } ?>
    </select>
    <label>Item Name</label>
    <input type="text" name="item_name" value="<?= h($_POST['item_name'] ?? '') ?>">
    <label>Description</label>
    <textarea name="description"><?= h($_POST['description'] ?? '') ?></textarea>
    <label>Price</label>
    <input type="number" step="0.01" min="0" name="price" value="<?= h($_POST['price'] ?? '') ?>">
    <label>Quantity in Stock</label>
    <input type="number" min="0" name="quantity" value="<?= h($_POST['quantity'] ?? '') ?>">
    <button class="btn btn-add" type="submit">Save</button>
    <a class="btn btn-gray" href="items.php">Cancel</a>
</form>
<?php page_footer(); ?>
