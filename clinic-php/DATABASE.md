CREATE DATABASE clinic_db;

 USE clinic_db;
 
  CREATE TABLE doctor ( docID INT PRIMARY KEY,
   docFName VARCHAR(50) NOT NULL,
    docLName VARCHAR(50) NOT NULL,
     docAddress VARCHAR(100), 
     docSpecial VARCHAR(50) ); 
  
  CREATE TABLE patient ( patID INT PRIMARY KEY,
   patFName VARCHAR(50) NOT NULL,
    patLName VARCHAR(50) NOT NULL,
     patBDate DATE,
      patTelNo VARCHAR(20) ); 
  
  CREATE TABLE consultation ( consultID INT PRIMARY KEY, 
  patID INT,
   docID INT,
    consultDate DATETIME,
     diagnosis TEXT,
      prescription TEXT,
       FOREIGN KEY (patID) REFERENCES patient(patID),
        FOREIGN KEY (docID) REFERENCES doctor(docID) );