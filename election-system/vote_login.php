<?php
include "db.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $voterID = trim($_POST['voterID']);
    $voterPass = trim($_POST['voterPass']);

    if ($voterID === "" || $voterPass === "") {
        $message = "PLEASE ENTER YOUR VOTER ID AND PASSWORD";
    } else {
        $stmt = $conn->prepare("SELECT * FROM voters WHERE voterID = ? AND voterPass = ?");
        $stmt->bind_param("ss", $voterID, $voterPass);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            if ($row['voterStat'] !== 'Active') {
                $message = "YOU ARE NOT ELIGIBLE TO VOTE (INACTIVE OR UNDERAGE VOTER)";
            } elseif ($row['voted'] == 1) {
                $message = "YOU HAVE ALREADY VOTED. YOU CANNOT VOTE AGAIN";
            } else {
                $_SESSION['voterID'] = $row['voterID'];
                $_SESSION['voterName'] = $row['voterFName'] . " " . $row['voterLName'];
                $stmt->close();
                $conn->close();
                header("Location: vote.php");
                exit();
            }
        } else {
            $message = "INVALID VOTER ID OR PASSWORD";
        }
        $stmt->close();
    }
}

include "header.php";
?>
<h2>Voter Login</h2>

<?php if ($message !== "") { ?>
    <div class="prompt-box"><?php echo htmlspecialchars($message); ?></div>
<?php } ?>

<form method="POST" action="vote_login.php">
    <label for="voterID">Voter ID:</label>
    <input type="text" id="voterID" name="voterID" value="<?php echo isset($_POST['voterID']) ? htmlspecialchars($_POST['voterID']) : ""; ?>" required>

    <label for="voterPass">Password:</label>
    <input type="password" id="voterPass" name="voterPass" required>

    <button type="submit">Login</button>
</form>

<?php include "footer.php"; $conn->close(); ?>
