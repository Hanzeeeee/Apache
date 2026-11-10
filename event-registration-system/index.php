<?php
include "db.php";
include "header.php";
?>
<h2>Welcome</h2>
<p>This is a simple Events Registration System. Use the menu above to manage events and participants, register a participant to an event, and monitor registration transactions.</p>

<table class="menu-table">
    <tr>
        <td><a href="events.php">Events Management</a></td>
        <td>Add, search/update, search/delete, and view event records.</td>
    </tr>
    <tr>
        <td><a href="participants.php">Participants Management</a></td>
        <td>Add, search/update, search/delete, and view participant records.</td>
    </tr>
    <tr>
        <td><a href="register.php">Participant Registration</a></td>
        <td>Register a participant to their event and compute the registration fee to be paid.</td>
    </tr>
    <tr>
        <td><a href="monitoring.php">Registration Monitoring</a></td>
        <td>Filter registration records by Event's Name and view totals (COUNT, SUM, discounts).</td>
    </tr>
</table>

<?php include "footer.php"; $conn->close(); ?>
