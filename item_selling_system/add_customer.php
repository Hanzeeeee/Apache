<?php
// add_customer.php - ADD a new customer
require "db.php";
$error = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $name    = trim($_POST['customer_name']);
    $contact = trim($_POST['contact_number']);
    $email   = trim($_POST['email']);
    $address = trim($_POST['address']);

    if ($name == "") {
        $error = "Customer name is required.";
    } else {
        $stmt = $conn->prepare("INSERT INTO customers (customer_name, contact_number, email, address) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $contact, $email, $address);
        $stmt->execute();
        header("Location: customers.php?msg=Customer added");
        exit;
    }
}
page_header("Add Customer");
if ($error != "") echo "<div class='msg error'>" . h($error) . "</div>";
?>
<form method="post">
    <label>Customer Name</label>
    <input type="text" name="customer_name" value="<?= h($_POST['customer_name'] ?? '') ?>">
    <label>Contact Number</label>
    <input type="text" name="contact_number" value="<?= h($_POST['contact_number'] ?? '') ?>">
    <label>Email</label>
    <input type="email" name="email" value="<?= h($_POST['email'] ?? '') ?>">
    <label>Address</label>
    <textarea name="address"><?= h($_POST['address'] ?? '') ?></textarea>
    <button class="btn btn-add" type="submit">Save</button>
    <a class="btn btn-gray" href="customers.php">Cancel</a>
</form>
<?php page_footer(); ?>
