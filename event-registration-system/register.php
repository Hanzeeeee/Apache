<?php
include "db.php";
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['register'])) {
    $partID = trim($_POST['partID']);
    $regDate = trim($_POST['regDate']);
    $regPMode = trim($_POST['regPMode']);

    if ($partID === "" || $regDate === "" || $regPMode === "") {
        $message = "PLEASE FILL OUT ALL FIELDS";
    } else {
        $partID = intval($partID);

        // Get the participant's event registration fee and discount rate
        $stmt = $conn->prepare("
            SELECT p.partDRate, e.evRFee
            FROM participants p
            JOIN events e ON p.evCode = e.evCode
            WHERE p.partID = ?
        ");
        $stmt->bind_param("i", $partID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            // Computing for the registration fee to be paid after applying discount rate
            $regFPaid = $row['evRFee'] - $row['partDRate'];
            if ($regFPaid < 0) {
                $regFPaid = 0;
            }

            $stmt2 = $conn->prepare("INSERT INTO registration (partID, regDate, regFPaid, regPMode) VALUES (?, ?, ?, ?)");
            $stmt2->bind_param("isds", $partID, $regDate, $regFPaid, $regPMode);
            $stmt2->execute();
            $stmt2->close();

            $message = "PARTICIPANT SUCCESSFULLY REGISTERED. REGISTRATION FEE PAID: " . number_format($regFPaid, 2);
        } else {
            $message = "PARTICIPANT NOT FOUND";
        }
        $stmt->close();
    }
}

$participants = $conn->query("
    SELECT p.*, e.evName
    FROM participants p
    LEFT JOIN events e ON p.evCode = e.evCode
    ORDER BY p.partID ASC
");

// Viewing of Registration transaction records arranged from latest to oldest
$registrations = $conn->query("
    SELECT r.*, p.partFName, p.partLName, e.evName
    FROM registration r
    JOIN participants p ON r.partID = p.partID
    JOIN events e ON p.evCode = e.evCode
    ORDER BY r.regDate DESC, r.regCode DESC
");

include "header.php";
?>
<h2>Participant Registration</h2>

<?php if ($message !== "") { ?>
    <div class="prompt-box"><?php echo htmlspecialchars($message); ?></div>
<?php } ?>

<form method="POST" action="register.php">
    <label for="partID">Participant:</label>
    <select id="partID" name="partID" required>
        <option value="">-- Select Participant --</option>
        <?php while ($p = $participants->fetch_assoc()) { ?>
            <option value="<?php echo $p['partID']; ?>" <?php echo (isset($_POST['partID']) && $_POST['partID'] == $p['partID']) ? "selected" : ""; ?>>
                <?php echo htmlspecialchars($p['partFName'] . " " . $p['partLName']); ?> - <?php echo htmlspecialchars($p['evName'] !== null ? $p['evName'] : "N/A"); ?>
            </option>
        <?php } ?>
    </select>

    <label for="regDate">Registration Date:</label>
    <input type="date" id="regDate" name="regDate" value="<?php echo isset($_POST['regDate']) ? htmlspecialchars($_POST['regDate']) : ""; ?>" required>

    <label for="regPMode">Payment Mode:</label>
    <select id="regPMode" name="regPMode" required>
        <option value="">-- Select Payment Mode --</option>
        <option value="Card" <?php echo (isset($_POST['regPMode']) && $_POST['regPMode'] == 'Card') ? "selected" : ""; ?>>Card</option>
        <option value="Cash" <?php echo (isset($_POST['regPMode']) && $_POST['regPMode'] == 'Cash') ? "selected" : ""; ?>>Cash</option>
    </select>

    <button type="submit" name="register">Register Participant</button>
</form>

<h3>Registration Transactions (Latest to Oldest)</h3>
<table>
    <thead>
        <tr>
            <th>Reg Code</th>
            <th>Participant</th>
            <th>Event</th>
            <th>Registration Date</th>
            <th>Fee Paid</th>
            <th>Payment Mode</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($registrations->num_rows > 0) { ?>
            <?php while ($r = $registrations->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($r['regCode']); ?></td>
                    <td><?php echo htmlspecialchars($r['partFName'] . " " . $r['partLName']); ?></td>
                    <td><?php echo htmlspecialchars($r['evName']); ?></td>
                    <td><?php echo htmlspecialchars($r['regDate']); ?></td>
                    <td>&#8369;<?php echo number_format($r['regFPaid'], 2); ?></td>
                    <td><?php echo htmlspecialchars($r['regPMode']); ?></td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="6">No registration transactions yet.</td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<a class="back-link" href="index.php">&laquo; Back to Home</a>

<?php include "footer.php"; $conn->close(); ?>
