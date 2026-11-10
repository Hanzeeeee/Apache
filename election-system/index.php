<?php
include "db.php";
include "header.php";
?>
<h2>Welcome</h2>
<p>This is an imitation of the Philippine National Election System. Use the menu above to manage positions, candidates, and voters, cast a vote, or view the election results and winners.</p>

<table class="menu-table">
    <tr>
        <td><a href="positions.php">Positions Management</a></td>
        <td>Add, update, and activate/deactivate election positions.</td>
    </tr>
    <tr>
        <td><a href="candidates.php">Candidates Management</a></td>
        <td>Add, update, and activate/deactivate candidates per position.</td>
    </tr>
    <tr>
        <td><a href="voters.php">Voters Management</a></td>
        <td>Add, update, and activate/deactivate registered voters.</td>
    </tr>
    <tr>
        <td><a href="vote_login.php">Voter Login / Vote</a></td>
        <td>Login using your Voter ID and Password to cast your vote.</td>
    </tr>
    <tr>
        <td><a href="results.php">Election Results</a></td>
        <td>View the total votes and voting percentage per candidate.</td>
    </tr>
    <tr>
        <td><a href="winners.php">Election Winners</a></td>
        <td>View the winner/s per position.</td>
    </tr>
</table>

<?php include "footer.php"; $conn->close(); ?>
