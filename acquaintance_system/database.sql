-- ==========================================
-- database.sql  (import this in phpMyAdmin)
-- ==========================================
CREATE DATABASE IF NOT EXISTS acquaintance_db;
USE acquaintance_db;

-- USERS TABLE (for login)
CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- TABLE 1: ACQUAINTANCES
CREATE TABLE IF NOT EXISTS acquaintances (
    acquaintance_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    address VARCHAR(100),
    contact_number VARCHAR(20),
    email VARCHAR(100),
    age INT
);

-- TABLE 2: STUDENTS
CREATE TABLE IF NOT EXISTS students (
    student_id INT AUTO_INCREMENT PRIMARY KEY,
    student_name VARCHAR(100) NOT NULL,
    course VARCHAR(50),
    year_level INT,
    section VARCHAR(20)
);

-- TABLE 3: INSTRUCTORS
CREATE TABLE IF NOT EXISTS instructors (
    instructor_id INT AUTO_INCREMENT PRIMARY KEY,
    instructor_name VARCHAR(100) NOT NULL,
    specialization VARCHAR(100),
    contact_number VARCHAR(20)
);

-- DEFAULT LOGIN: admin / admin123 (password is stored hashed)
INSERT INTO users (username, password) VALUES
('admin', '$2b$10$Xif1jK8cgJVPGugLYVd70./U4kQIP6DlLcXoCWEo327SGFCwotb3u');

-- SAMPLE DATA
INSERT INTO acquaintances (first_name, last_name, address, contact_number, email, age) VALUES
('Juan', 'Dela Cruz', 'Cebu City', '09171234567', 'juan@email.com', 20),
('Maria', 'Santos', 'Mandaue City', '09181234567', 'maria@email.com', 21);

INSERT INTO students (student_name, course, year_level, section) VALUES
('Ana Reyes', 'BSIT', 3, 'A'),
('Pedro Cruz', 'BSCS', 2, 'B');

INSERT INTO instructors (instructor_name, specialization, contact_number) VALUES
('Mr. Garcia', 'Web Development', '09191234567'),
('Ms. Lopez', 'Databases', '09201234567');
