<?php
include "db.php";
include "header.php";

$positions = $conn->query("SELECT * FROM positions ORDER BY posID ASC");
?>
<h2>Election Results</h2>
<p>This page shows the total votes and voting percentage of each candidate, per position.</p>

<table>
    <thead>
        <tr>
            <th>Position / Candidate</th>
            <th>Total Votes</th>
            <th>Voting %</th>
        </tr>
    </thead>
    <tbody>
        <?php
        while ($pos = $positions->fetch_assoc()) {
            echo '<tr class="section-row"><td colspan="3">' . htmlspecialchars($pos['posName']) . '</td></tr>';

            // Get all candidates for this position with their total votes using COUNT()
            $candStmt = $conn->prepare("
                SELECT c.candID, c.candFName, c.candMName, c.candLName,
                       COUNT(v.voteID) AS totalVotes
                FROM candidates c
                LEFT JOIN votes v ON v.candid = c.candID AND v.posID = c.posID
                WHERE c.posID = ?
                GROUP BY c.candID
                ORDER BY totalVotes DESC
            ");
            $candStmt->bind_param("i", $pos['posID']);
            $candStmt->execute();
            $candResult = $candStmt->get_result();

            $candidates = array();
            $positionTotalVotes = 0;
            while ($c = $candResult->fetch_assoc()) {
                $candidates[] = $c;
                $positionTotalVotes += intval($c['totalVotes']);
            }
            $candStmt->close();

            if (count($candidates) > 0) {
                foreach ($candidates as $c) {
                    $fullName = trim($c['candFName'] . " " . $c['candMName'] . " " . $c['candLName']);
                    $votes = intval($c['totalVotes']);
                    $percentage = ($positionTotalVotes > 0) ? ($votes / $positionTotalVotes) * 100 : 0;
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($fullName) . "</td>";
                    echo "<td>" . $votes . "</td>";
                    echo "<td>" . number_format($percentage, 2) . "%</td>";
                    echo "</tr>";
                }
            } else {
                echo '<tr><td colspan="3">No candidates for this position.</td></tr>';
            }
        }
        ?>
    </tbody>
</table>

<?php include "footer.php"; $conn->close(); ?>
