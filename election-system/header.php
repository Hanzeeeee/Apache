<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Election System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>PHILIPPINE NATIONAL ELECTION SYSTEM</h1>
        <div class="navbar">
            <a href="index.php">Home</a>
            <a href="positions.php">Positions</a>
            <a href="candidates.php">Candidates</a>
            <a href="voters.php">Voters</a>
            <?php if (isset($_SESSION['voterID'])) { ?>
                <a href="vote.php">Vote</a>
                <a href="logout.php">Logout (<?php echo htmlspecialchars($_SESSION['voterID']); ?>)</a>
            <?php } else { ?>
                <a href="vote_login.php">Voter Login</a>
            <?php } ?>
            <a href="results.php">Election Results</a>
            <a href="winners.php">Election Winners</a>
        </div>
