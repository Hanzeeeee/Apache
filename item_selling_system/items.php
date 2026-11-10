<?php
// items.php - VIEW all items (JOIN gets the category name from the categories table)
require "db.php";
$result = $conn->query("SELECT items.*, categories.category_name
                        FROM items
                        JOIN categories ON items.category_id = categories.category_id
                        ORDER BY items.item_id");
page_header("Items");
?>
<a class="btn btn-add" href="add_item.php">+ Add Item</a>
<table>
    <tr><th>ID</th><th>Category</th><th>Name</th><th>Description</th><th>Price</th><th>Stock</th><th>Actions</th></tr>
    <?php while ($row = $result->fetch_assoc()) { ?>
    <tr>
        <td><?= $row['item_id'] ?></td>
        <td><?= h($row['category_name']) ?></td>
        <td><?= h($row['item_name']) ?></td>
        <td><?= h($row['description']) ?></td>
        <td><?= number_format($row['price'], 2) ?></td>
        <td><?= $row['quantity'] ?></td>
        <td>
            <a class="btn btn-edit" href="edit_item.php?id=<?= $row['item_id'] ?>">Edit</a>
            <a class="btn btn-delete" href="delete_item.php?id=<?= $row['item_id'] ?>">Delete</a>
        </td>
    </tr>
    <?php } ?>
</table>
<?php page_footer(); ?>
