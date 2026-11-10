<?php
// index.php - Dashboard: shows the total number of records in each table.
require "db.php";

// COUNT(*) counts the rows in a table
$categories = $conn->query("SELECT COUNT(*) AS total FROM categories")->fetch_assoc()['total'];
$items      = $conn->query("SELECT COUNT(*) AS total FROM items")->fetch_assoc()['total'];
$customers  = $conn->query("SELECT COUNT(*) AS total FROM customers")->fetch_assoc()['total'];
$sales      = $conn->query("SELECT COUNT(*) AS total FROM sales")->fetch_assoc()['total'];

page_header("Dashboard");
?>
<div class="boxes">
    <div class="box"><h2><?= $categories ?></h2>Total Categories</div>
    <div class="box"><h2><?= $items ?></h2>Total Items</div>
    <div class="box"><h2><?= $customers ?></h2>Total Customers</div>
    <div class="box"><h2><?= $sales ?></h2>Total Sales</div>
</div>
<?php page_footer(); ?>
