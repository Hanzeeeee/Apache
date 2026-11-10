
CREATE DATABASE IF NOT EXISTS veterinary_clinic;
USE veterinary_clinic;

CREATE TABLE veterinarians (
    vetID INT PRIMARY KEY AUTO_INCREMENT,
    vetFName VARCHAR(100),
    vetLName VARCHAR(100),
    vetAddress VARCHAR(255),
    vetSpecial VARCHAR(100)
);

CREATE TABLE petowners (
    petOwnerID INT PRIMARY KEY AUTO_INCREMENT,
    petOwnerFName VARCHAR(100),
    petOwnerLName VARCHAR(100),
    petOwnerBDate DATE,
    petOwnerTelNo VARCHAR(50)
);

CREATE TABLE pets (
    petID INT PRIMARY KEY AUTO_INCREMENT,
    petName VARCHAR(100),
    petType VARCHAR(100),
    petBreed VARCHAR(100),
    petBDate DATE,
    petOwnerID INT,
    FOREIGN KEY (petOwnerID) REFERENCES petowners(petOwnerID)
);

CREATE TABLE consultations (
    consultID INT PRIMARY KEY AUTO_INCREMENT,
    petID INT,
    vetID INT,
    consultDate DATETIME,
    diagnoses TEXT,
    prescription TEXT,
    FOREIGN KEY (petID) REFERENCES pets(petID),
    FOREIGN KEY (vetID) REFERENCES veterinarians(vetID)
);
