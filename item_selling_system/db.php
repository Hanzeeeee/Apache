<?php
// db.php - database connection + shared page layout.
// Every other PHP file starts with:  require "db.php";

// Connect to MySQL (XAMPP default: user "root", empty password)
$conn = new mysqli("localhost", "root", "", "item_selling_db");
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// h() makes text safe to print in HTML (prevents HTML/script injection)
function h($text) {
    return htmlspecialchars((string)$text);
}

// Prints the top of every page: HTML head, menu, and any message.
// To add a new menu link later (5th table), add one line below.
function page_header($title) {
    $pages = array(
        "index.php"      => "Dashboard",
        "categories.php" => "Categories",
        "items.php"      => "Items",
        "customers.php"  => "Customers",
        "sales.php"      => "Sales"
    );
    echo "<!DOCTYPE html><html><head><meta charset='UTF-8'>";
    echo "<title>" . h($title) . "</title>";
    echo "<link rel='stylesheet' href='style.css'></head><body>";
    echo "<div class='nav'>";
    foreach ($pages as $file => $label) {
        echo "<a href='$file'>$label</a>";
    }
    echo "</div><div class='container'><h1>" . h($title) . "</h1>";

    // Show a message sent through the URL (?msg=...&type=error)
    if (isset($_GET['msg'])) {
        $class = (isset($_GET['type']) && $_GET['type'] == "error") ? "msg error" : "msg";
        echo "<div class='$class'>" . h($_GET['msg']) . "</div>";
    }
}

// Prints the bottom of every page.
function page_footer() {
    echo "</div></body></html>";
}
?>
