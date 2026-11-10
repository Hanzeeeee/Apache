-- =========================================================
-- STUDENT MANAGEMENT SYSTEM - DATABASE SCRIPT
-- Import this file in phpMyAdmin
-- =========================================================

CREATE DATABASE IF NOT EXISTS student_management;
USE student_management;

-- ---------------------------------------------------------
-- COURSE TABLE
-- ---------------------------------------------------------
CREATE TABLE course (
    courseID INT AUTO_INCREMENT PRIMARY KEY,
    courseName VARCHAR(150) NOT NULL,
    courseUnit VARCHAR(10) NOT NULL,
    courseDescription TEXT
);

-- ---------------------------------------------------------
-- STUDENT TABLE
-- ---------------------------------------------------------
CREATE TABLE student (
    studID INT AUTO_INCREMENT PRIMARY KEY,
    studFName VARCHAR(50) NOT NULL,
    studMName VARCHAR(50),
    studLName VARCHAR(50) NOT NULL
);

-- ---------------------------------------------------------
-- INSTRUCTOR TABLE
-- ---------------------------------------------------------
CREATE TABLE instructor (
    instID INT AUTO_INCREMENT PRIMARY KEY,
    instFName VARCHAR(50) NOT NULL,
    instMName VARCHAR(50),
    instLName VARCHAR(50) NOT NULL
);

-- ---------------------------------------------------------
-- STUDYLOAD TABLE
-- ---------------------------------------------------------
CREATE TABLE studyload (
    studyLoadID INT AUTO_INCREMENT PRIMARY KEY,
    studID INT NOT NULL,
    courseID INT NOT NULL,
    instID INT NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'Enrolled',
    FOREIGN KEY (studID) REFERENCES student(studID),
    FOREIGN KEY (courseID) REFERENCES course(courseID),
    FOREIGN KEY (instID) REFERENCES instructor(instID)
);

-- ---------------------------------------------------------
-- SAMPLE DATA
-- ---------------------------------------------------------

-- Courses
INSERT INTO course (courseName, courseUnit, courseDescription) VALUES
('Introduction to Programming', '3', 'Basic concepts of computer programming using a high-level language.'),
('Data Structures and Algorithms', '3', 'Study of data organization and algorithm design.'),
('Database Management Systems', '3', 'Design and implementation of relational databases.');

-- Students
INSERT INTO student (studFName, studMName, studLName) VALUES
('Mark', 'Reyes', 'Villanueva'),
('Sophia', 'Cruz', 'Mendoza'),
('Daniel', 'Garcia', 'Santos');

-- Instructors
INSERT INTO instructor (instFName, instMName, instLName) VALUES
('Rosario', 'Pascual', 'Aquino'),
('Benjamin', 'Torres', 'Lim'),
('Carmela', 'Diaz', 'Fernandez');

-- StudyLoad (sample enrollment records)
INSERT INTO studyload (studID, courseID, instID, status) VALUES
(1, 1, 1, 'Enrolled'),
(1, 2, 2, 'Enrolled'),
(2, 1, 1, 'Enrolled'),
(2, 3, 3, 'Completed'),
(3, 2, 2, 'Dropped');
