<?php
// delete_customer.php - DELETE a customer (asks for confirmation first)
require "db.php";
$id = (int)($_GET['id'] ?? $_POST['customer_id'] ?? 0);

// A customer who has sales cannot be deleted (foreign key rule)
$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM sales WHERE customer_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
if ($stmt->get_result()->fetch_assoc()['total'] > 0) {
    header("Location: customers.php?msg=Cannot delete: this customer has sales records&type=error");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $stmt = $conn->prepare("DELETE FROM customers WHERE customer_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: customers.php?msg=Customer deleted");
    exit;
}
page_header("Delete Customer");
?>
<p>Are you sure you want to delete customer #<?= $id ?>?</p>
<form method="post">
    <input type="hidden" name="customer_id" value="<?= $id ?>">
    <button class="btn btn-delete" type="submit">Yes, Delete</button>
    <a class="btn btn-gray" href="customers.php">Cancel</a>
</form>
<?php page_footer(); ?>
