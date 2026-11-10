-- =========================================================
-- BOOKS MANAGEMENT SYSTEM - DATABASE SCRIPT
-- Import this file in phpMyAdmin
-- =========================================================

CREATE DATABASE IF NOT EXISTS books_management;
USE books_management;

CREATE TABLE IF NOT EXISTS books (
    isbn VARCHAR(20) NOT NULL,
    title VARCHAR(255) NOT NULL,
    copyright_year VARCHAR(10) NOT NULL,
    edition VARCHAR(50) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL,
    PRIMARY KEY (isbn)
);

-- Sample data (based on the reference image)
INSERT INTO books (isbn, title, copyright_year, edition, price, quantity) VALUES
('1', 'Harry Potter', '2005', '1st', 95.00, 3),
('2', 'Lord of the Rings', '2004', '3rd', 99.00, 10),
('3', 'Java Programming', '2010', 'UPE', 49.00, 2),
('4', 'The Notebook', '1999', 'US', 750.00, 11);
