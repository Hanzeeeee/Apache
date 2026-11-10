-- ==========================================================
-- UC ICT Congress Registration System - Database Script
-- Import this file in phpMyAdmin (XAMPP)
-- ==========================================================

CREATE DATABASE IF NOT EXISTS ict_congress_db;
USE ict_congress_db;

-- ----------------------------------------------------------
-- Table: registration
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS registration (
    idNum VARCHAR(20) NOT NULL,
    campus VARCHAR(20) NOT NULL,
    studFName VARCHAR(50) NOT NULL,
    studLName VARCHAR(50) NOT NULL,
    amountPaid DECIMAL(10,2) NOT NULL,
    attended VARCHAR(3) NOT NULL DEFAULT 'No',
    PRIMARY KEY (idNum)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ==========================================================
-- Sample Data (optional - you may delete these if not needed)
-- Matches the sample data shown in the reference images
-- ==========================================================
INSERT INTO registration (idNum, campus, studFName, studLName, amountPaid, attended) VALUES
('1234567', 'LM', 'Paul', 'Paynito', 425.00, 'No'),
('2345678', 'Banilad', 'Janeth', 'Borja', 400.00, 'Yes'),
('3456789', 'LM', 'Sheila', 'Mendes', 450.00, 'No'),
('7654321', 'Main', 'Raul', 'Doctolero', 425.00, 'No'),
('8765432', 'Main', 'John', 'Tesoro', 400.00, 'No'),
('9876543', 'Banilad', 'Rey', 'Argalla', 400.00, 'No'),
('4567890', 'Banilad', 'Joseph', 'Salng', 450.00, 'No');
