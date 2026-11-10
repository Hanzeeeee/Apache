<?php
// add_sale.php - RECORD a new sale
// Steps: check stock -> calculate total -> save sale -> subtract stock
require "db.php";
$error = "";

$customers = $conn->query("SELECT customer_id, customer_name FROM customers ORDER BY customer_name");
$items     = $conn->query("SELECT item_id, item_name, price, quantity FROM items ORDER BY item_name");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $customer_id = (int)$_POST['customer_id'];
    $item_id     = (int)$_POST['item_id'];
    $qty         = (int)$_POST['quantity_sold'];

    // Get the item's price and current stock
    $stmt = $conn->prepare("SELECT price, quantity FROM items WHERE item_id = ?");
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $item = $stmt->get_result()->fetch_assoc();

    if ($customer_id <= 0 || !$item) {
        $error = "Please choose a customer and an item.";
    } elseif ($qty <= 0) {
        $error = "Quantity must be at least 1.";
    } elseif ($qty > $item['quantity']) {
        // Prevent selling more than the available stock
        $error = "Not enough stock. Only " . $item['quantity'] . " available.";
    } else {
        // total_amount = item price x quantity_sold
        $total = $item['price'] * $qty;

        // A transaction makes both queries succeed together, or neither
        $conn->begin_transaction();

        // 1) Save the sale
        $stmt = $conn->prepare("INSERT INTO sales (customer_id, item_id, quantity_sold, total_amount) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiid", $customer_id, $item_id, $qty, $total);
        $stmt->execute();

        // 2) Subtract the quantity from the item's stock
        $stmt = $conn->prepare("UPDATE items SET quantity = quantity - ? WHERE item_id = ?");
        $stmt->bind_param("ii", $qty, $item_id);
        $stmt->execute();

        $conn->commit();
        header("Location: sales.php?msg=Sale recorded");
        exit;
    }
}
page_header("Add Sale");
if ($error != "") echo "<div class='msg error'>" . h($error) . "</div>";
if ($customers->num_rows == 0 || $items->num_rows == 0) {
    echo "<p>You need at least one <a href='add_customer.php'>customer</a> and one <a href='add_item.php'>item</a> first.</p>";
    page_footer(); exit;
}
?>
<form method="post">
    <label>Customer</label>
    <select name="customer_id">
        <?php while ($c = $customers->fetch_assoc()) { ?>
            <option value="<?= $c['customer_id'] ?>" <?= (($_POST['customer_id'] ?? '') == $c['customer_id']) ? 'selected' : '' ?>>
                <?= h($c['customer_name']) ?>
            </option>
        <?php } ?>
    </select>
    <label>Item</label>
    <select name="item_id">
        <?php while ($i = $items->fetch_assoc()) { ?>
            <option value="<?= $i['item_id'] ?>" <?= (($_POST['item_id'] ?? '') == $i['item_id']) ? 'selected' : '' ?>>
                <?= h($i['item_name']) ?> - Price: <?= number_format($i['price'], 2) ?> - Stock: <?= $i['quantity'] ?>
            </option>
        <?php } ?>
    </select>
    <label>Quantity</label>
    <input type="number" min="1" name="quantity_sold" value="<?= h($_POST['quantity_sold'] ?? '1') ?>">
    <p>The total amount is calculated automatically (price x quantity).</p>
    <button class="btn btn-add" type="submit">Save Sale</button>
    <a class="btn btn-gray" href="sales.php">Cancel</a>
</form>
<?php page_footer(); ?>
