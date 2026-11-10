CREATE DATABASE IF NOT EXISTS employee_attendance; USE employee_attendance;
CREATE TABLE departments(
    departmentID INT PRIMARY KEY AUTO_INCREMENT,
    departmentName VARCHAR(100),
    departmentHead VARCHAR(100),
    telephone VARCHAR(50)
);
CREATE TABLE employees(
    employeeID INT PRIMARY KEY AUTO_INCREMENT,
    employeeFName VARCHAR(100),
    employeeLName VARCHAR(100),
    employeePosition VARCHAR(100),
    ratePerHour DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    departmentID INT,
    FOREIGN KEY(departmentID) REFERENCES departments(departmentID)
);
CREATE TABLE attendance(
    attendanceID INT PRIMARY KEY AUTO_INCREMENT,
    employeeID INT,
    attendanceDate DATE,
    timeIn TIME,
    timeOut TIME,
    FOREIGN KEY(employeeID) REFERENCES employees(employeeID)
);