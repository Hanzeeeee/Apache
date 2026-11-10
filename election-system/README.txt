====================================================
ELECTION SYSTEM - SETUP INSTRUCTIONS
====================================================

FOLDER STRUCTURE
----------------
election-system/
  |- db.php
  |- header.php
  |- footer.php
  |- style.css
  |- index.php
  |
  |- positions.php
  |- add_position.php
  |- update_position.php
  |- deactivate_position.php
  |
  |- candidates.php
  |- add_candidate.php
  |- update_candidate.php
  |- deactivate_candidate.php
  |
  |- voters.php
  |- add_voter.php
  |- update_voter.php
  |- deactivate_voter.php
  |
  |- vote_login.php
  |- vote.php
  |- logout.php
  |
  |- results.php
  |- winners.php
  |
  |- election_system.sql
  |- README.txt

STEP 1: INSTALL XAMPP
----------------------
Download and install XAMPP if not yet installed:
https://www.apachefriends.org/

STEP 2: COPY THE PROJECT FOLDER
--------------------------------
Copy the entire "election-system" folder into:
C:\xampp\htdocs\

Final path should look like:
C:\xampp\htdocs\election-system\

STEP 3: START XAMPP
--------------------
1. Open the XAMPP Control Panel.
2. Click "Start" on Apache.
3. Click "Start" on MySQL.

STEP 4: CREATE THE DATABASE
-----------------------------
1. Open your browser and go to: http://localhost/phpmyadmin
2. Click "Import" at the top.
3. Click "Choose File" and select "election_system.sql" from the project folder.
4. Click "Go" at the bottom.
5. This will create the "election_system" database with the following tables:
   - positions   (preloaded with President, Vice President, Senator)
   - candidates  (preloaded with 9 sample candidates)
   - voters      (preloaded with 3 sample voters)
   - votes       (empty - will be filled once voters cast their votes)

STEP 5: RUN THE SYSTEM
------------------------
Open your browser and go to:

http://localhost/election-system/index.php

SAMPLE VOTER LOGIN ACCOUNTS (for testing the Voting module)
--------------------------------------------------------------
Voter ID: V001   Password: pass123   (Active - can vote)
Voter ID: V002   Password: pass456   (Active - can vote)
Voter ID: V003   Password: pass789   (Inactive/underage - cannot vote)

HOW TO USE THE SYSTEM
-----------------------
1. POSITIONS MANAGEMENT
   - Add a new position (name + number of vacant slots).
   - Edit an existing position.
   - Activate/Deactivate a position (toggle link in the table).

2. CANDIDATES MANAGEMENT
   - Add a new candidate and assign him/her to a position.
   - Edit an existing candidate's name or assigned position.
   - Activate/Deactivate a candidate.

3. VOTERS MANAGEMENT
   - Add a new voter. Enter the voter's Age - if 18 or above, the voter
     is automatically marked "Active" (eligible); otherwise "Inactive".
   - Edit a voter's password/name. Age is optional when editing - leave
     it blank to keep the voter's current status unchanged.
   - Activate/Deactivate a voter manually (admin override).

4. VOTER LOGIN / VOTING
   - A voter logs in using his/her Voter ID and Password.
   - Only "Active" voters who have NOT yet voted can log in and vote.
   - The ballot only shows Active positions and Active candidates.
   - The voter may select up to the "Number of Positions" (vacant slots)
     allowed for each position (e.g., up to 4 for Senator in the sample
     data). Selecting more than allowed will show an error message.
   - Once submitted, the voter's "voted" flag is set to 1 (Yes) and the
     voter can no longer vote again.

5. ELECTION RESULTS
   - Shows every candidate per position with their Total Votes (counted
     using SQL's COUNT() function) and their Voting Percentage relative
     to the total votes cast for that position.

6. ELECTION WINNERS
   - Shows the winner/s per position (top candidates equal to the number
     of vacant slots for that position), ordered by votes in descending
     order.

NOTES
-----
- This system uses PURE PHP, HTML, and CSS only (no JavaScript, no AJAX,
  no frameworks, no Bootstrap). PHP Sessions are used only to remember
  which voter is currently logged in (a core, built-in PHP feature).
- Passwords are stored in plain text in the database for beginner
  simplicity so they are easy to inspect in phpMyAdmin. For a real
  production system, passwords should always be hashed.
- Database connection settings are in db.php (default XAMPP settings:
  user "root", no password).
- Foreign keys are used to link candidates to positions, and votes to
  positions, voters, and candidates, to keep the data consistent.
