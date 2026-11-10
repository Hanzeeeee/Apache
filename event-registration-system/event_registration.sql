-- =========================================================
-- EVENT REGISTRATION SYSTEM - DATABASE SCRIPT
-- Import this file in phpMyAdmin
-- =========================================================

CREATE DATABASE IF NOT EXISTS event_registration;
USE event_registration;

-- ---------------------------------------------------------
-- EVENTS TABLE
-- ---------------------------------------------------------
CREATE TABLE events (
    evCode INT AUTO_INCREMENT PRIMARY KEY,
    evName VARCHAR(150) NOT NULL,
    evDate DATE NOT NULL,
    evVenue VARCHAR(150) NOT NULL,
    evRFee DECIMAL(10,2) NOT NULL
);

-- ---------------------------------------------------------
-- PARTICIPANTS TABLE
-- ---------------------------------------------------------
CREATE TABLE participants (
    partID INT AUTO_INCREMENT PRIMARY KEY,
    evCode INT NOT NULL,
    partFName VARCHAR(50) NOT NULL,
    partLName VARCHAR(50) NOT NULL,
    partDRate DECIMAL(10,2) NOT NULL DEFAULT 0,
    FOREIGN KEY (evCode) REFERENCES events(evCode)
);

-- ---------------------------------------------------------
-- REGISTRATION TABLE
-- ---------------------------------------------------------
CREATE TABLE registration (
    regCode INT AUTO_INCREMENT PRIMARY KEY,
    partID INT NOT NULL,
    regDate DATE NOT NULL,
    regFPaid DECIMAL(10,2) NOT NULL,
    regPMode ENUM('Card','Cash') NOT NULL,
    FOREIGN KEY (partID) REFERENCES participants(partID)
);

-- ---------------------------------------------------------
-- SAMPLE DATA
-- ---------------------------------------------------------

-- Events
INSERT INTO events (evName, evDate, evVenue, evRFee) VALUES
('Tech Conference 2026', '2026-08-15', 'Grand Ballroom Hotel', 1500.00),
('Music Festival', '2026-09-10', 'City Park Amphitheater', 800.00),
('Business Summit', '2026-10-05', 'Convention Center Hall A', 2000.00);

-- Participants (evCode 1 = Tech Conference, 2 = Music Festival, 3 = Business Summit)
INSERT INTO participants (evCode, partFName, partLName, partDRate) VALUES
(1, 'Juan', 'Dela Cruz', 100.00),
(1, 'Maria', 'Santos', 0.00),
(2, 'Pedro', 'Reyes', 50.00),
(3, 'Ana', 'Garcia', 200.00);

-- Registration (sample transactions; regFPaid = evRFee - partDRate)
INSERT INTO registration (partID, regDate, regFPaid, regPMode) VALUES
(1, '2026-07-01', 1400.00, 'Cash'),
(3, '2026-07-02', 750.00, 'Card');
