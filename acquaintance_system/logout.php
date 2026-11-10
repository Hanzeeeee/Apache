<?php
// logout.php - clear the session and go back to login
session_start();
session_destroy();
header("Location: login.php");
exit;
?>
