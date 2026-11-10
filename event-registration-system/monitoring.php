<?php
include "db.php";
include "header.php";

$evCode = isset($_GET['evCode']) ? intval($_GET['evCode']) : 0;
$selectedEvent = null;
$rows = array();
$recordCount = 0;
$totalFeesPaid = 0;
$totalDiscounts = 0;

if ($evCode > 0) {
    // Get the selected event's details
    $evStmt = $conn->prepare("SELECT * FROM events WHERE evCode = ?");
    $evStmt->bind_param("i", $evCode);
    $evStmt->execute();
    $selectedEvent = $evStmt->get_result()->fetch_assoc();
    $evStmt->close();

    if ($selectedEvent) {
        // Displays all Registration records filtered by Event's Name
        $stmt = $conn->prepare("
            SELECT r.regDate, r.regFPaid, p.partFName, p.partLName
            FROM registration r
            JOIN participants p ON r.partID = p.partID
            WHERE p.evCode = ?
            ORDER BY r.regDate DESC, r.regCode DESC
        ");
        $stmt->bind_param("i", $evCode);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($r = $res->fetch_assoc()) {
            $rows[] = $r;
        }
        $stmt->close();

        // Aggregate function COUNT() for # of records
        $countStmt = $conn->prepare("
            SELECT COUNT(*) AS recCount
            FROM registration r
            JOIN participants p ON r.partID = p.partID
            WHERE p.evCode = ?
        ");
        $countStmt->bind_param("i", $evCode);
        $countStmt->execute();
        $recordCount = $countStmt->get_result()->fetch_assoc()['recCount'];
        $countStmt->close();

        // Aggregate function SUM() for Total Registration Fees Paid
        $sumStmt = $conn->prepare("
            SELECT SUM(r.regFPaid) AS totalPaid
            FROM registration r
            JOIN participants p ON r.partID = p.partID
            WHERE p.evCode = ?
        ");
        $sumStmt->bind_param("i", $evCode);
        $sumStmt->execute();
        $sumRow = $sumStmt->get_result()->fetch_assoc();
        $totalFeesPaid = $sumRow['totalPaid'] !== null ? $sumRow['totalPaid'] : 0;
        $sumStmt->close();

        // Total amount of discounts = (COUNT * evRFee) - SUM(regFPaid)
        $totalDiscounts = ($recordCount * $selectedEvent['evRFee']) - $totalFeesPaid;
    }
}

$events = $conn->query("SELECT * FROM events ORDER BY evName ASC");
?>
<h2>Events Registration Monitoring</h2>
<p>Filter registration records by Event's Name to view the full list of registrants and the computed totals.</p>

<form method="GET" action="monitoring.php" class="search-box">
    <select name="evCode" required>
        <option value="">-- Select Event's Name --</option>
        <?php while ($ev = $events->fetch_assoc()) { ?>
            <option value="<?php echo $ev['evCode']; ?>" <?php echo ($evCode == $ev['evCode']) ? "selected" : ""; ?>>
                <?php echo htmlspecialchars($ev['evName']); ?>
            </option>
        <?php } ?>
    </select>
    <button type="submit">Filter</button>
</form>

<?php if ($evCode > 0) { ?>
    <?php if (!$selectedEvent) { ?>
        <div class="prompt-box">EVENT NOT FOUND</div>
    <?php } else { ?>
        <table>
            <thead>
                <tr>
                    <th>Event's Name</th>
                    <th>Participant's Full Name</th>
                    <th>Registration Date</th>
                    <th>Registration Fee Paid</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($rows) > 0) { ?>
                    <?php foreach ($rows as $r) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($selectedEvent['evName']); ?></td>
                            <td><?php echo htmlspecialchars($r['partFName'] . " " . $r['partLName']); ?></td>
                            <td><?php echo htmlspecialchars($r['regDate']); ?></td>
                            <td>&#8369;<?php echo number_format($r['regFPaid'], 2); ?></td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="4">No registration records found for this event.</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <div class="totals-box">
            <p>Number of Records (COUNT): <?php echo $recordCount; ?></p>
            <p>Total Registration Fees Paid (SUM): &#8369;<?php echo number_format($totalFeesPaid, 2); ?></p>
            <p>Total Amount of Discounts ((COUNT &times; Event's Reg. Fee) - SUM): &#8369;<?php echo number_format($totalDiscounts, 2); ?></p>
        </div>
    <?php } ?>
<?php } ?>

<a class="back-link" href="index.php">&laquo; Back to Home</a>

<?php include "footer.php"; $conn->close(); ?>
