
CREATE DATABASE IF NOT EXISTS uc_main_intramurals;
USE uc_main_intramurals;

CREATE TABLE departments(
deptID INT AUTO_INCREMENT PRIMARY KEY,
deptName VARCHAR(100) NOT NULL
);

CREATE TABLE registration(
userName VARCHAR(50) PRIMARY KEY,
password VARCHAR(100),
confirmPassword VARCHAR(100),
role VARCHAR(50)
);

CREATE TABLE tournament_managers(
userName VARCHAR(50) PRIMARY KEY,
name VARCHAR(100),
mobile VARCHAR(20),
deptID INT,
FOREIGN KEY (deptID) REFERENCES departments(deptID)
);

CREATE TABLE coaches(
userName VARCHAR(50) PRIMARY KEY,
name VARCHAR(100),
mobile VARCHAR(20),
deptID INT,
FOREIGN KEY (deptID) REFERENCES departments(deptID)
);

CREATE TABLE deans(
userName VARCHAR(50) PRIMARY KEY,
name VARCHAR(100),
mobile VARCHAR(20),
deptID INT,
FOREIGN KEY (deptID) REFERENCES departments(deptID)
);

CREATE TABLE events(
eventID INT AUTO_INCREMENT PRIMARY KEY,
category VARCHAR(100),
eventName VARCHAR(100),
noOfParticipant INT,
tournamentManager VARCHAR(50),
FOREIGN KEY (tournamentManager) REFERENCES tournament_managers(userName)
);

CREATE TABLE athletes(
idNum VARCHAR(50) PRIMARY KEY,
eventID INT,
deptID INT,
lastName VARCHAR(100),
firstName VARCHAR(100),
middleInit VARCHAR(10),
course VARCHAR(100),
yearLevel VARCHAR(20),
civilStatus VARCHAR(20),
gender VARCHAR(20),
birthday DATE,
contactNo VARCHAR(20),
address TEXT,
coachID VARCHAR(50),
deanID VARCHAR(50),
FOREIGN KEY (eventID) REFERENCES events(eventID),
FOREIGN KEY (deptID) REFERENCES departments(deptID)
);

CREATE TABLE schedules(
scheduleID INT AUTO_INCREMENT PRIMARY KEY,
eventName VARCHAR(100),
scheduleDate DATE,
scheduleTime VARCHAR(50),
venue VARCHAR(100)
);
