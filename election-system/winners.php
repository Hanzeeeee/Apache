<?php
include "db.php";
include "header.php";

$positions = $conn->query("SELECT * FROM positions ORDER BY posID ASC");
?>
<h2>Election Winners</h2>
<p>This page shows the winner/s per position and their corresponding number of votes earned, in descending order.</p>

<table>
    <thead>
        <tr>
            <th>Elective Position</th>
            <th>Winner</th>
            <th>Total Votes</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $hasAnyWinner = false;

        while ($pos = $positions->fetch_assoc()) {
            $numWinners = intval($pos['numOfPositions']);

            // Get the top candidates (winners) for this position using COUNT()
            $candStmt = $conn->prepare("
                SELECT c.candFName, c.candMName, c.candLName,
                       COUNT(v.voteID) AS totalVotes
                FROM candidates c
                LEFT JOIN votes v ON v.candid = c.candID AND v.posID = c.posID
                WHERE c.posID = ?
                GROUP BY c.candID
                ORDER BY totalVotes DESC
                LIMIT ?
            ");
            $candStmt->bind_param("ii", $pos['posID'], $numWinners);
            $candStmt->execute();
            $candResult = $candStmt->get_result();

            if ($candResult->num_rows > 0) {
                while ($c = $candResult->fetch_assoc()) {
                    $fullName = trim($c['candFName'] . " " . $c['candMName'] . " " . $c['candLName']);
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($pos['posName']) . "</td>";
                    echo "<td>" . htmlspecialchars($fullName) . "</td>";
                    echo "<td>" . intval($c['totalVotes']) . "</td>";
                    echo "</tr>";
                    $hasAnyWinner = true;
                }
            } else {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($pos['posName']) . "</td>";
                echo '<td colspan="2">No candidates for this position.</td>';
                echo "</tr>";
            }
            $candStmt->close();
        }

        if (!$hasAnyWinner) {
            echo '<tr><td colspan="3">No votes have been cast yet.</td></tr>';
        }
        ?>
    </tbody>
</table>

<?php include "footer.php"; $conn->close(); ?>
