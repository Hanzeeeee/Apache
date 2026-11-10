<?php
// edit_customer.php - EDIT an existing customer
require "db.php";
$error = "";
$id = (int)($_GET['id'] ?? $_POST['customer_id'] ?? 0);

$stmt = $conn->prepare("SELECT * FROM customers WHERE customer_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
if (!$row) { header("Location: customers.php?msg=Customer not found&type=error"); exit; }

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $name    = trim($_POST['customer_name']);
    $contact = trim($_POST['contact_number']);
    $email   = trim($_POST['email']);
    $address = trim($_POST['address']);

    if ($name == "") {
        $error = "Customer name is required.";
        $row['customer_name'] = $name; $row['contact_number'] = $contact;
        $row['email'] = $email; $row['address'] = $address;
    } else {
        $stmt = $conn->prepare("UPDATE customers SET customer_name=?, contact_number=?, email=?, address=? WHERE customer_id=?");
        $stmt->bind_param("ssssi", $name, $contact, $email, $address, $id);
        $stmt->execute();
        header("Location: customers.php?msg=Customer updated");
        exit;
    }
}
page_header("Edit Customer");
if ($error != "") echo "<div class='msg error'>" . h($error) . "</div>";
?>
<form method="post">
    <input type="hidden" name="customer_id" value="<?= $id ?>">
    <label>Customer Name</label>
    <input type="text" name="customer_name" value="<?= h($row['customer_name']) ?>">
    <label>Contact Number</label>
    <input type="text" name="contact_number" value="<?= h($row['contact_number']) ?>">
    <label>Email</label>
    <input type="email" name="email" value="<?= h($row['email']) ?>">
    <label>Address</label>
    <textarea name="address"><?= h($row['address']) ?></textarea>
    <button class="btn btn-edit" type="submit">Update</button>
    <a class="btn btn-gray" href="customers.php">Cancel</a>
</form>
<?php page_footer(); ?>
