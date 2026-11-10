<?php
include "db.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['voterID'])) {
    header("Location: vote_login.php");
    exit();
}

$voterID = $_SESSION['voterID'];
$message = "";

// Re-check the voter's current status every time this page loads
$stmt = $conn->prepare("SELECT * FROM voters WHERE voterID = ?");
$stmt->bind_param("s", $voterID);
$stmt->execute();
$voterRow = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$voterRow || $voterRow['voterStat'] !== 'Active') {
    session_unset();
    session_destroy();
    header("Location: vote_login.php");
    exit();
}

$alreadyVoted = ($voterRow['voted'] == 1);

// Process the submitted ballot
if (!$alreadyVoted && $_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit_vote'])) {

    $activePositions = [];
    $posResult = $conn->query("SELECT * FROM positions WHERE posStat = 'Active' ORDER BY posID ASC");
    while ($p = $posResult->fetch_assoc()) {
        $activePositions[] = $p;
    }

    $valid = true;
    $selections = array();

    foreach ($activePositions as $pos) {
        $posID = $pos['posID'];
        $fieldName = "position_" . $posID;
        $selected = isset($_POST[$fieldName]) ? $_POST[$fieldName] : array();

        if (!is_array($selected)) {
            $selected = array($selected);
        }

        // Remove blank values
        $cleanSelected = array();
        foreach ($selected as $val) {
            if ($val !== "") {
                $cleanSelected[] = intval($val);
            }
        }

        if (count($cleanSelected) > intval($pos['numOfPositions'])) {
            $valid = false;
            $message = "YOU SELECTED TOO MANY CANDIDATES FOR " . strtoupper($pos['posName']) . " (MAXIMUM: " . $pos['numOfPositions'] . ")";
            break;
        }

        $selections[$posID] = $cleanSelected;
    }

    if ($valid) {
        foreach ($selections as $posID => $candIDs) {
            foreach ($candIDs as $candID) {
                $stmtV = $conn->prepare("INSERT INTO votes (posID, voterID, candid) VALUES (?, ?, ?)");
                $stmtV->bind_param("isi", $posID, $voterID, $candID);
                $stmtV->execute();
                $stmtV->close();
            }
        }

        $stmtU = $conn->prepare("UPDATE voters SET voted = 1 WHERE voterID = ?");
        $stmtU->bind_param("s", $voterID);
        $stmtU->execute();
        $stmtU->close();

        $alreadyVoted = true;
        $message = "YOUR VOTE HAS BEEN SUCCESSFULLY SUBMITTED. THANK YOU FOR VOTING!";
    }
}

include "header.php";
?>
<h2>Official Ballot</h2>

<?php if ($message !== "") { ?>
    <div class="prompt-box"><?php echo htmlspecialchars($message); ?></div>
<?php } ?>

<?php if ($alreadyVoted) { ?>
    <p>Hello <strong><?php echo htmlspecialchars($_SESSION['voterName']); ?></strong>, you have already cast your vote. Thank you for participating in the election!</p>
    <a class="back-link" href="results.php">View Election Results</a>
<?php } else { ?>
    <p>Hello <strong><?php echo htmlspecialchars($_SESSION['voterName']); ?></strong>, please select your candidate/s for each position below.</p>

    <form method="POST" action="vote.php">
        <?php
        $posResult = $conn->query("SELECT * FROM positions WHERE posStat = 'Active' ORDER BY posID ASC");
        while ($pos = $posResult->fetch_assoc()) {
            echo "<h3>" . htmlspecialchars($pos['posName']) . " (Vote up to " . $pos['numOfPositions'] . ")</h3>";

            $candStmt = $conn->prepare("SELECT * FROM candidates WHERE posID = ? AND candStat = 'Active' ORDER BY candLName ASC");
            $candStmt->bind_param("i", $pos['posID']);
            $candStmt->execute();
            $candList = $candStmt->get_result();

            if ($candList->num_rows > 0) {
                while ($cand = $candList->fetch_assoc()) {
                    $fullName = trim($cand['candFName'] . " " . $cand['candMName'] . " " . $cand['candLName']);
                    echo '<label class="candidate-option"><input type="checkbox" name="position_' . $pos['posID'] . '[]" value="' . $cand['candID'] . '"> ' . htmlspecialchars($fullName) . '</label>';
                }
            } else {
                echo "<p>No candidates available for this position.</p>";
            }
            $candStmt->close();
        }
        ?>
        <button type="submit" name="submit_vote">Submit Vote</button>
    </form>
<?php } ?>

<?php include "footer.php"; $conn->close(); ?>
