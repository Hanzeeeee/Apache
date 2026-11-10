<?php
// categories.php - VIEW all categories
require "db.php";
$result = $conn->query("SELECT * FROM categories ORDER BY category_id");
page_header("Categories");
?>
<a class="btn btn-add" href="add_category.php">+ Add Category</a>
<table>
    <tr><th>ID</th><th>Name</th><th>Description</th><th>Actions</th></tr>
    <?php while ($row = $result->fetch_assoc()) { ?>
    <tr>
        <td><?= $row['category_id'] ?></td>
        <td><?= h($row['category_name']) ?></td>
        <td><?= h($row['description']) ?></td>
        <td>
            <a class="btn btn-edit" href="edit_category.php?id=<?= $row['category_id'] ?>">Edit</a>
            <a class="btn btn-delete" href="delete_category.php?id=<?= $row['category_id'] ?>">Delete</a>
        </td>
    </tr>
    <?php } ?>
</table>
<?php page_footer(); ?>
