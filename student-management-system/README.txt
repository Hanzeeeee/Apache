====================================================
STUDENT MANAGEMENT SYSTEM - SETUP INSTRUCTIONS
====================================================

FOLDER STRUCTURE
----------------
student-management-system/
  |- db.php
  |- header.php
  |- footer.php
  |- style.css
  |- index.php
  |
  |- courses.php
  |- add_course.php
  |- update_course.php
  |- delete_course.php
  |
  |- students.php
  |- add_student.php
  |- update_student.php
  |- delete_student.php
  |
  |- instructors.php
  |- add_instructor.php
  |- update_instructor.php
  |- delete_instructor.php
  |
  |- studyloads.php
  |- inquire_studyload.php
  |- add_studyload.php
  |
  |- student_management.sql
  |- README.txt

STEP 1: INSTALL XAMPP
----------------------
Download and install XAMPP if not yet installed:
https://www.apachefriends.org/

STEP 2: COPY THE PROJECT FOLDER
--------------------------------
Copy the entire "student-management-system" folder into:
C:\xampp\htdocs\

Final path should look like:
C:\xampp\htdocs\student-management-system\

STEP 3: START XAMPP
--------------------
1. Open the XAMPP Control Panel.
2. Click "Start" on Apache.
3. Click "Start" on MySQL.

STEP 4: CREATE THE DATABASE
-----------------------------
1. Open your browser and go to: http://localhost/phpmyadmin
2. Click "Import" at the top.
3. Click "Choose File" and select "student_management.sql" from the project folder.
4. Click "Go" at the bottom.
5. This will create the "student_management" database with the following tables:
   - course     (preloaded with 3 sample courses)
   - student    (preloaded with 3 sample students)
   - instructor (preloaded with 3 sample instructors)
   - studyload  (preloaded with 5 sample enrollment records)

STEP 5: RUN THE SYSTEM
------------------------
Open your browser and go to:

http://localhost/student-management-system/index.php

HOW TO USE THE SYSTEM
-----------------------
1. STUDENT MANAGEMENT
   - Add, search (by ID or name), update, delete, and view student records.

2. INSTRUCTOR MANAGEMENT
   - Add, search (by ID or name), update, delete, and view instructor records.

3. COURSE MANAGEMENT
   - Add, search (by ID or name), update, delete, and view course records.

4. STUDYLOAD
   - "StudyLoad Records" (studyloads.php) shows a plain view of ONLY the raw
     studyload table records (StudyLoad ID, Stud ID, Course ID, Inst ID, Status).
   - "Inquire StudyLoad" (inquire_studyload.php) lets you select a student and
     inquire his/her enrolled courses together with the assigned instructor
     for each course.
   - "Add StudyLoad" (add_studyload.php) lets you enroll a student to a course
     under a chosen instructor, with a status of Enrolled, Dropped, or Completed.
     The system prevents enrolling the same student in the same course twice.

NOTES
-----
- This system uses PURE PHP, HTML, and CSS only (no JavaScript, no AJAX,
  no frameworks, no Bootstrap).
- All list pages (Students, Instructors, Courses, StudyLoad) display a total
  record count computed using the SQL aggregate function COUNT().
- Deleting a Student, Instructor, or Course that still has linked StudyLoad
  records is blocked with a message, to protect data integrity (foreign keys).
- Database connection settings are in db.php (default XAMPP settings:
  user "root", no password).

NOTE ON THE EXAM SHEET
------------------------
The bottom section of the exam sheet ("Provide for Search and Report UI...
athlete or coach... college... event...") refers to fields/tables (college,
event, athlete, coach) that are not part of this system's data dictionary
(Course, Student, Instructor, StudyLoad) and appear to be leftover text from
a different exam version. It was not implemented since it does not match the
given database design, and the module weights already total 20/20 without it
(4 modules x 5 points each).
