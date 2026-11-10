====================================================
EVENTS REGISTRATION SYSTEM - SETUP INSTRUCTIONS
====================================================

FOLDER STRUCTURE
----------------
event-registration-system/
  |- db.php
  |- header.php
  |- footer.php
  |- style.css
  |- index.php
  |
  |- events.php
  |- add_event.php
  |- update_event.php
  |- delete_event.php
  |
  |- participants.php
  |- add_participant.php
  |- update_participant.php
  |- delete_participant.php
  |
  |- register.php
  |- monitoring.php
  |
  |- event_registration.sql
  |- README.txt

STEP 1: INSTALL XAMPP
----------------------
Download and install XAMPP if not yet installed:
https://www.apachefriends.org/

STEP 2: COPY THE PROJECT FOLDER
--------------------------------
Copy the entire "event-registration-system" folder into:
C:\xampp\htdocs\

Final path should look like:
C:\xampp\htdocs\event-registration-system\

STEP 3: START XAMPP
--------------------
1. Open the XAMPP Control Panel.
2. Click "Start" on Apache.
3. Click "Start" on MySQL.

STEP 4: CREATE THE DATABASE
-----------------------------
1. Open your browser and go to: http://localhost/phpmyadmin
2. Click "Import" at the top.
3. Click "Choose File" and select "event_registration.sql" from the project folder.
4. Click "Go" at the bottom.
5. This will create the "event_registration" database with the following tables:
   - events        (preloaded with 3 sample events)
   - participants  (preloaded with 4 sample participants)
   - registration  (preloaded with 2 sample registration transactions)

STEP 5: RUN THE SYSTEM
------------------------
Open your browser and go to:

http://localhost/event-registration-system/index.php

HOW TO USE THE SYSTEM
-----------------------
1. EVENTS MANAGEMENT (events.php)
   - View all events, with a search box (by Event Code or Event Name).
   - "Add New Event" (add_event.php) adds an event record.
   - "Update" (update_event.php) lets you search an event by its Ev Code,
     then edit and save its details.
   - "Delete" (delete_event.php) lets you search an event by its Ev Code,
     review its details, then confirm deletion. Deletion is blocked if
     participants are still linked to that event.

2. PARTICIPANTS MANAGEMENT (participants.php)
   - View all participants (with their assigned Event), with a search box
     (by Participant ID or Name).
   - "Add New Participant" (add_participant.php) adds a participant and
     assigns them to an event with a Discount Rate.
   - "Update" (update_participant.php) lets you search a participant by
     Part ID, then edit and save their details.
   - "Delete" (delete_participant.php) lets you search a participant by
     Part ID, review their details, then confirm deletion. Deletion is
     blocked if registration records are still linked to that participant.

3. PARTICIPANT REGISTRATION (register.php)
   - Select a participant and enter the Registration Date and Payment Mode
     (Card or Cash), then click "Register Participant".
   - The Registration Fee Paid is automatically computed as:
       Event's Registration Fee (evRFee) - Participant's Discount Rate (partDRate)
   - Below the form, all registration transactions are listed from
     LATEST to OLDEST.

4. EVENTS REGISTRATION MONITORING (monitoring.php)
   - Select an Event's Name from the dropdown filter and click "Filter".
   - Displays all registration records for that event with the columns:
     Event's Name, Participant's Full Name, Registration Date, Registration
     Fee Paid.
   - Also displays, using SQL aggregate functions:
       a. COUNT()  -> Number of Records
       b. SUM()    -> Total Registration Fees Paid
       c. (a * Event's Registration Fee) - b -> Total Amount of Discounts

NOTES
-----
- This system uses PURE PHP, HTML, and CSS only (no JavaScript, no AJAX,
  no frameworks, no Bootstrap). Pure PHP forms with page-reload (POST/GET)
  are used throughout.
- Foreign keys link participants -> events, and registration -> participants,
  to keep the data consistent. Deleting a record that still has linked
  records in another table is blocked with a clear message.
- The Events, Participants, and Monitoring pages make use of the SQL
  aggregate function COUNT(), as required.
- Database connection settings are in db.php (default XAMPP settings:
  user "root", no password).
