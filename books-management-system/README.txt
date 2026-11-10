====================================================
BOOKS MANAGEMENT SYSTEM - SETUP INSTRUCTIONS
====================================================

FOLDER STRUCTURE
----------------
books-management-system/
  |- db.php
  |- index.php
  |- style.css
  |- books_management.sql
  |- README.txt

STEP 1: INSTALL XAMPP
----------------------
Download and install XAMPP if not yet installed:
https://www.apachefriends.org/

STEP 2: COPY THE PROJECT FOLDER
--------------------------------
Copy the entire "books-management-system" folder into:
C:\xampp\htdocs\

Final path should look like:
C:\xampp\htdocs\books-management-system\

STEP 3: START XAMPP
--------------------
1. Open the XAMPP Control Panel.
2. Click "Start" on Apache.
3. Click "Start" on MySQL.

STEP 4: CREATE THE DATABASE
-----------------------------
1. Open your browser and go to: http://localhost/phpmyadmin
2. Click "Import" at the top.
3. Click "Choose File" and select "books_management.sql" from the project folder.
4. Click "Go" at the bottom.
5. This will create the "books_management" database with the "books" table
   and 4 sample records.

STEP 5: RUN THE SYSTEM
------------------------
Open your browser and go to:

http://localhost/books-management-system/index.php

HOW TO USE THE SYSTEM
-----------------------
1. SEARCH   - Type an ISBN# and click SEARCH to load the record into the form.
2. EDIT     - After a successful search, change any field (except ISBN#)
              and click EDIT to save the changes.
3. DELETE   - After a successful search, click DELETE to remove the record.
4. ADD      - Fill in the ISBN# and other fields, then click ADD to insert
              a new record. If the ISBN# already exists, it will not be added.

The message box at the top of the page (PROMPT) will always show the
result of your last action (errors, confirmations, etc.).

The table at the bottom always displays all records, the TOTAL column
(Price x Quantity per book), and the overall Total Number of Books and
Grand Total at the bottom, even if the list is empty.

NOTES
-----
- This system uses PURE PHP, HTML, and CSS only (no JavaScript, no AJAX,
  no frameworks, no Bootstrap).
- Database connection settings are in db.php (default XAMPP settings:
  user "root", no password).
