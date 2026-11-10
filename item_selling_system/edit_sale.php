<?php
// edit_sale.php - EDIT a sale
// The old quantity is returned to stock, then the new quantity is subtracted.
// The total is recalculated using the item's CURRENT price.
require "db.php";
$error = "";
$id = (int)($_GET['id'] ?? $_POST['sale_id'] ?? 0);

$stmt = $conn->prepare("SELECT * FROM sales WHERE sale_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$sale = $stmt->get_result()->fetch_assoc();
if (!$sale) { header("Location: sales.php?msg=Sale not found&type=error"); exit; }

$customers = $conn->query("SELECT customer_id, customer_name FROM customers ORDER BY customer_name");
$items     = $conn->query("SELECT item_id, item_name, price, quantity FROM items ORDER BY item_name");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $customer_id = (int)$_POST['customer_id'];
    $item_id     = (int)$_POST['item_id'];
    $qty         = (int)$_POST['quantity_sold'];

    $stmt = $conn->prepare("SELECT price, quantity FROM items WHERE item_id = ?");
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $item = $stmt->get_result()->fetch_assoc();

    if ($customer_id <= 0 || !$item) {
        $error = "Please choose a customer and an item.";
    } elseif ($qty <= 0) {
        $error = "Quantity must be at least 1.";
    } else {
        // Available stock = current stock + the quantity this sale already took
        $available = $item['quantity'];
        if ($item_id == $sale['item_id']) {
            $available += $sale['quantity_sold'];
        }

        if ($qty > $available) {
            $error = "Not enough stock. Only $available available.";
        } else {
            $total = $item['price'] * $qty;
            $conn->begin_transaction();

            // 1) Put the OLD quantity back into the old item's stock
            $stmt = $conn->prepare("UPDATE items SET quantity = quantity + ? WHERE item_id = ?");
            $stmt->bind_param("ii", $sale['quantity_sold'], $sale['item_id']);
            $stmt->execute();

            // 2) Subtract the NEW quantity from the chosen item's stock
            $stmt = $conn->prepare("UPDATE items SET quantity = quantity - ? WHERE item_id = ?");
            $stmt->bind_param("ii", $qty, $item_id);
            $stmt->execute();

            // 3) Update the sale record
            $stmt = $conn->prepare("UPDATE sales SET customer_id=?, item_id=?, quantity_sold=?, total_amount=? WHERE sale_id=?");
            $stmt->bind_param("iiidi", $customer_id, $item_id, $qty, $total, $id);
            $stmt->execute();

            $conn->commit();
            header("Location: sales.php?msg=Sale updated");
            exit;
        }
    }
    $sale['customer_id'] = $customer_id; $sale['item_id'] = $item_id; $sale['quantity_sold'] = $qty;
}
page_header("Edit Sale");
if ($error != "") echo "<div class='msg error'>" . h($error) . "</div>";
?>
<form method="post">
    <input type="hidden" name="sale_id" value="<?= $id ?>">
    <label>Customer</label>
    <select name="customer_id">
        <?php while ($c = $customers->fetch_assoc()) { ?>
            <option value="<?= $c['customer_id'] ?>" <?= ($sale['customer_id'] == $c['customer_id']) ? 'selected' : '' ?>>
                <?= h($c['customer_name']) ?>
            </option>
        <?php } ?>
    </select>
    <label>Item</label>
    <select name="item_id">
        <?php while ($i = $items->fetch_assoc()) { ?>
            <option value="<?= $i['item_id'] ?>" <?= ($sale['item_id'] == $i['item_id']) ? 'selected' : '' ?>>
                <?= h($i['item_name']) ?> - Price: <?= number_format($i['price'], 2) ?> - Stock: <?= $i['quantity'] ?>
            </option>
        <?php } ?>
    </select>
    <label>Quantity</label>
    <input type="number" min="1" name="quantity_sold" value="<?= h($sale['quantity_sold']) ?>">
    <button class="btn btn-edit" type="submit">Update Sale</button>
    <a class="btn btn-gray" href="sales.php">Cancel</a>
</form>
<?php page_footer(); ?>
