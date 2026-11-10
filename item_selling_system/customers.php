<?php
// customers.php - VIEW all customers
require "db.php";
$result = $conn->query("SELECT * FROM customers ORDER BY customer_id");
page_header("Customers");
?>
<a class="btn btn-add" href="add_customer.php">+ Add Customer</a>
<table>
    <tr><th>ID</th><th>Name</th><th>Contact</th><th>Email</th><th>Address</th><th>Actions</th></tr>
    <?php while ($row = $result->fetch_assoc()) { ?>
    <tr>
        <td><?= $row['customer_id'] ?></td>
        <td><?= h($row['customer_name']) ?></td>
        <td><?= h($row['contact_number']) ?></td>
        <td><?= h($row['email']) ?></td>
        <td><?= h($row['address']) ?></td>
        <td>
            <a class="btn btn-edit" href="edit_customer.php?id=<?= $row['customer_id'] ?>">Edit</a>
            <a class="btn btn-delete" href="delete_customer.php?id=<?= $row['customer_id'] ?>">Delete</a>
        </td>
    </tr>
    <?php } ?>
</table>
<?php page_footer(); ?>
