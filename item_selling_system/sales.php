<?php
// sales.php - VIEW all sales (JOIN gets customer and item names)
require "db.php";
$result = $conn->query("SELECT sales.*, customers.customer_name, items.item_name
                        FROM sales
                        JOIN customers ON sales.customer_id = customers.customer_id
                        JOIN items ON sales.item_id = items.item_id
                        ORDER BY sales.sale_id DESC");
page_header("Sales");
?>
<a class="btn btn-add" href="add_sale.php">+ Add Sale</a>
<table>
    <tr><th>ID</th><th>Customer</th><th>Item</th><th>Qty</th><th>Total</th><th>Date</th><th>Actions</th></tr>
    <?php while ($row = $result->fetch_assoc()) { ?>
    <tr>
        <td><?= $row['sale_id'] ?></td>
        <td><?= h($row['customer_name']) ?></td>
        <td><?= h($row['item_name']) ?></td>
        <td><?= $row['quantity_sold'] ?></td>
        <td><?= number_format($row['total_amount'], 2) ?></td>
        <td><?= h($row['sale_date']) ?></td>
        <td>
            <a class="btn btn-edit" href="edit_sale.php?id=<?= $row['sale_id'] ?>">Edit</a>
            <a class="btn btn-delete" href="delete_sale.php?id=<?= $row['sale_id'] ?>">Delete</a>
        </td>
    </tr>
    <?php } ?>
</table>
<?php page_footer(); ?>
