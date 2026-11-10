-- =========================================================
-- ELECTION SYSTEM - DATABASE SCRIPT
-- Import this file in phpMyAdmin
-- =========================================================

CREATE DATABASE IF NOT EXISTS election_system;
USE election_system;

-- ---------------------------------------------------------
-- POSITIONS TABLE
-- ---------------------------------------------------------
CREATE TABLE positions (
    posID INT AUTO_INCREMENT PRIMARY KEY,
    posName VARCHAR(100) NOT NULL,
    numOfPositions INT NOT NULL,
    posStat ENUM('Active','Inactive') NOT NULL DEFAULT 'Active'
);

-- ---------------------------------------------------------
-- VOTERS TABLE
-- ---------------------------------------------------------
CREATE TABLE voters (
    voterID VARCHAR(20) PRIMARY KEY,
    voterPass VARCHAR(50) NOT NULL,
    voterFName VARCHAR(50) NOT NULL,
    voterMName VARCHAR(50),
    voterLName VARCHAR(50) NOT NULL,
    voterStat ENUM('Active','Inactive') NOT NULL DEFAULT 'Active',
    voted TINYINT(1) NOT NULL DEFAULT 0
);

-- ---------------------------------------------------------
-- CANDIDATES TABLE
-- ---------------------------------------------------------
CREATE TABLE candidates (
    candID INT AUTO_INCREMENT PRIMARY KEY,
    candFName VARCHAR(50) NOT NULL,
    candMName VARCHAR(50),
    candLName VARCHAR(50) NOT NULL,
    posID INT NOT NULL,
    candStat ENUM('Active','Inactive') NOT NULL DEFAULT 'Active',
    FOREIGN KEY (posID) REFERENCES positions(posID)
);

-- ---------------------------------------------------------
-- VOTES TABLE
-- ---------------------------------------------------------
CREATE TABLE votes (
    voteID INT AUTO_INCREMENT PRIMARY KEY,
    posID INT NOT NULL,
    voterID VARCHAR(20) NOT NULL,
    candid INT NOT NULL,
    FOREIGN KEY (posID) REFERENCES positions(posID),
    FOREIGN KEY (voterID) REFERENCES voters(voterID),
    FOREIGN KEY (candid) REFERENCES candidates(candID)
);

-- ---------------------------------------------------------
-- SAMPLE DATA
-- ---------------------------------------------------------

-- Positions
INSERT INTO positions (posName, numOfPositions, posStat) VALUES
('President', 1, 'Active'),
('Vice President', 1, 'Active'),
('Senator', 4, 'Active');

-- Candidates (posID 1 = President, 2 = Vice President, 3 = Senator)
INSERT INTO candidates (candFName, candMName, candLName, posID, candStat) VALUES
('Juan', 'Santos', 'Dela Cruz', 1, 'Active'),
('Maria', 'Reyes', 'Garcia', 1, 'Active'),
('Pedro', 'Lopez', 'Ramos', 2, 'Active'),
('Ana', 'Torres', 'Bautista', 2, 'Active'),
('Jose', 'Lim', 'Mendoza', 3, 'Active'),
('Carlos', 'Diaz', 'Fernandez', 3, 'Active'),
('Rosa', 'Cruz', 'Villanueva', 3, 'Active'),
('Elena', 'Pascual', 'Aquino', 3, 'Active'),
('Miguel', 'Santos', 'Rivera', 3, 'Active');

-- Voters (plain-text passwords for beginner simplicity)
-- voterStat is set based on age at the time of registration (18 or above = Active)
INSERT INTO voters (voterID, voterPass, voterFName, voterMName, voterLName, voterStat, voted) VALUES
('V001', 'pass123', 'Andres', 'Luna', 'Bonifacio', 'Active', 0),
('V002', 'pass456', 'Gabriela', 'Silang', 'Cortez', 'Active', 0),
('V003', 'pass789', 'Emilio', 'Jacinto', 'Aguinaldo', 'Inactive', 0);
