<?php
// ===============================================
// BOOKS MANAGEMENT SYSTEM - MAIN FILE
// Handles: SEARCH, ADD, EDIT, DELETE, LIST
// ===============================================
include "db.php";

// Default (empty) field values
$isbn = "";
$title = "";
$copyright_year = "";
$edition = "";
$price = "";
$quantity = "";

$message = "";
$focus_isbn = false; // used to add "autofocus" attribute (pure HTML, no JS)

// -----------------------------------------------
// SEARCH
// -----------------------------------------------
if (isset($_POST['search'])) {

    $isbn = trim($_POST['isbn']);

    if ($isbn === "") {
        $message = "PLEASE ENTER AN ISBN# TO SEARCH";
        $focus_isbn = true;
    } else {
        $stmt = $conn->prepare("SELECT * FROM books WHERE isbn = ?");
        $stmt->bind_param("s", $isbn);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $title = $row['title'];
            $copyright_year = $row['copyright_year'];
            $edition = $row['edition'];
            $price = $row['price'];
            $quantity = $row['quantity'];
            $message = "ITEM IS FOUND";
        } else {
            // ISBN not found - clear the rest of the fields
            $title = "";
            $copyright_year = "";
            $edition = "";
            $price = "";
            $quantity = "";
            $message = "ITEM NOT FOUND";
        }
        $stmt->close();
    }
}

// -----------------------------------------------
// ADD
// -----------------------------------------------
elseif (isset($_POST['add'])) {

    $isbn = trim($_POST['isbn']);
    $title = trim($_POST['title']);
    $copyright_year = trim($_POST['copyright_year']);
    $edition = trim($_POST['edition']);
    $price = trim($_POST['price']);
    $quantity = trim($_POST['quantity']);

    if ($isbn === "" && $title === "" && $copyright_year === "" && $edition === "" && $price === "" && $quantity === "") {
        $message = "NO RECORD TO ADD";
        $focus_isbn = true;
    } elseif ($isbn === "") {
        $message = "NO RECORD TO ADD";
        $focus_isbn = true;
    } else {
        // Check if ISBN already exists
        $stmt = $conn->prepare("SELECT isbn FROM books WHERE isbn = ?");
        $stmt->bind_param("s", $isbn);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->fetch_assoc()) {
            $message = "RECORD ALREADY EXISTS";
            $focus_isbn = true;
        } else {
            $priceVal = ($price === "") ? 0 : $price;
            $qtyVal = ($quantity === "") ? 0 : $quantity;

            $stmt2 = $conn->prepare("INSERT INTO books (isbn, title, copyright_year, edition, price, quantity) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt2->bind_param("ssssdi", $isbn, $title, $copyright_year, $edition, $priceVal, $qtyVal);
            $stmt2->execute();
            $stmt2->close();

            $message = "RECORD SUCCESSFULLY SAVED";

            // Clear the form after a successful add
            $isbn = "";
            $title = "";
            $copyright_year = "";
            $edition = "";
            $price = "";
            $quantity = "";
        }
        $stmt->close();
    }
}

// -----------------------------------------------
// EDIT (ISBN is NOT editable - it is used to find the record)
// -----------------------------------------------
elseif (isset($_POST['edit'])) {

    $isbn = trim($_POST['isbn']);
    $title = trim($_POST['title']);
    $copyright_year = trim($_POST['copyright_year']);
    $edition = trim($_POST['edition']);
    $price = trim($_POST['price']);
    $quantity = trim($_POST['quantity']);

    if ($isbn === "" && $title === "" && $copyright_year === "" && $edition === "" && $price === "" && $quantity === "") {
        $message = "NO RECORD TO EDIT";
        $focus_isbn = true;
    } else {
        // Check if the ISBN exists
        $stmt = $conn->prepare("SELECT isbn FROM books WHERE isbn = ?");
        $stmt->bind_param("s", $isbn);
        $stmt->execute();
        $result = $stmt->get_result();

        if (!$result->fetch_assoc()) {
            $message = "ISBN# IS NOT FOUND";
            $focus_isbn = true;
        } else {
            $priceVal = ($price === "") ? 0 : $price;
            $qtyVal = ($quantity === "") ? 0 : $quantity;

            $stmt2 = $conn->prepare("UPDATE books SET title = ?, copyright_year = ?, edition = ?, price = ?, quantity = ? WHERE isbn = ?");
            $stmt2->bind_param("sssdis", $title, $copyright_year, $edition, $priceVal, $qtyVal, $isbn);
            $stmt2->execute();
            $stmt2->close();

            $message = "RECORD SUCCESSFULLY UPDATED";
        }
        $stmt->close();
    }
}

// -----------------------------------------------
// DELETE
// -----------------------------------------------
elseif (isset($_POST['delete'])) {

    $isbn = trim($_POST['isbn']);

    if ($isbn === "") {
        $message = "NO RECORD TO DELETE";
        $focus_isbn = true;
    } else {
        $stmt = $conn->prepare("SELECT isbn FROM books WHERE isbn = ?");
        $stmt->bind_param("s", $isbn);
        $stmt->execute();
        $result = $stmt->get_result();

        if (!$result->fetch_assoc()) {
            $message = "ISBN# IS NOT FOUND";
            $focus_isbn = true;
        } else {
            $stmt2 = $conn->prepare("DELETE FROM books WHERE isbn = ?");
            $stmt2->bind_param("s", $isbn);
            $stmt2->execute();
            $stmt2->close();

            $message = "RECORD SUCCESSFULLY DELETED";

            // Clear the form after a successful delete
            $isbn = "";
            $title = "";
            $copyright_year = "";
            $edition = "";
            $price = "";
            $quantity = "";
        }
        $stmt->close();
    }
}

// -----------------------------------------------
// FETCH ALL BOOKS FOR THE LIST (always shown)
// -----------------------------------------------
$books = [];
$totalQuantity = 0;
$grandTotal = 0;

$listResult = $conn->query("SELECT * FROM books ORDER BY isbn ASC");
while ($row = $listResult->fetch_assoc()) {
    $row['line_total'] = $row['price'] * $row['quantity'];
    $totalQuantity += $row['quantity'];
    $grandTotal += $row['line_total'];
    $books[] = $row;
}

$totalBooks = count($books);

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Books Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>BOOKS MANAGEMENT SYSTEM</h1>

        <!-- PROMPT BOX -->
        <div class="prompt-box">
            <?php echo $message !== "" ? htmlspecialchars($message) : "WELCOME! PLEASE ENTER AN ISBN# TO SEARCH."; ?>
        </div>

        <!-- FORM -->
        <form method="POST" action="index.php">
            <div class="form-section">
                <div class="form-fields">
                    <label for="isbn">ISBN #:</label>
                    <input type="text" id="isbn" name="isbn"
                           value="<?php echo htmlspecialchars($isbn); ?>"
                           <?php echo $focus_isbn ? "autofocus" : ""; ?>>

                    <label for="title">Title:</label>
                    <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>">

                    <label for="copyright_year">Copyright:</label>
                    <input type="text" id="copyright_year" name="copyright_year" value="<?php echo htmlspecialchars($copyright_year); ?>">

                    <label for="edition">Edition:</label>
                    <input type="text" id="edition" name="edition" value="<?php echo htmlspecialchars($edition); ?>">

                    <label for="price">Price:</label>
                    <input type="text" id="price" name="price" value="<?php echo htmlspecialchars($price); ?>">

                    <label for="quantity">Quantity:</label>
                    <input type="text" id="quantity" name="quantity" value="<?php echo htmlspecialchars($quantity); ?>">
                </div>

                <div class="buttons-section">
                    <button type="submit" name="search">SEARCH</button>
                    <button type="submit" name="edit">EDIT</button>
                    <button type="submit" name="delete">DELETE</button>
                    <button type="submit" name="add">ADD</button>
                </div>
            </div>
        </form>

        <!-- LIST -->
        <table>
            <thead>
                <tr>
                    <th>ISBN</th>
                    <th>Title</th>
                    <th>Copyright</th>
                    <th>Edition</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>TOTAL</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($totalBooks > 0) { ?>
                    <?php foreach ($books as $book) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($book['isbn']); ?></td>
                            <td><?php echo htmlspecialchars($book['title']); ?></td>
                            <td><?php echo htmlspecialchars($book['copyright_year']); ?></td>
                            <td><?php echo htmlspecialchars($book['edition']); ?></td>
                            <td>$<?php echo number_format($book['price'], 2); ?></td>
                            <td><?php echo htmlspecialchars($book['quantity']); ?></td>
                            <td>$<?php echo number_format($book['line_total'], 2); ?></td>
                        </tr>
                    <?php } ?>
                    <tr class="total-row">
                        <td colspan="5">TOTAL</td>
                        <td><?php echo $totalQuantity; ?></td>
                        <td>$<?php echo number_format($grandTotal, 2); ?></td>
                    </tr>
                <?php } else { ?>
                    <tr>
                        <td colspan="7">NO RECORDS FOUND</td>
                    </tr>
                    <tr class="total-row">
                        <td colspan="5">TOTAL</td>
                        <td>0</td>
                        <td>$0.00</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <!-- SUMMARY (always shown, with or without result) -->
        <div class="summary">
            Total Number of Books: <?php echo $totalBooks; ?> |
            Grand Total: $<?php echo number_format($grandTotal, 2); ?>
        </div>
    </div>
</body>
</html>
