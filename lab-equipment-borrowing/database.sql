-- Four-table Laboratory Equipment Borrowing practice database for XAMPP.
CREATE DATABASE IF NOT EXISTS laboratory CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE laboratory;

CREATE TABLE IF NOT EXISTS student (
  stuID INT AUTO_INCREMENT PRIMARY KEY,
  stuFName VARCHAR(50) NOT NULL,
  stuLName VARCHAR(50) NOT NULL,
  stuCourse VARCHAR(80) NOT NULL,
  stuYear INT NOT NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS equipment (
  equipID INT AUTO_INCREMENT PRIMARY KEY,
  equipName VARCHAR(100) NOT NULL,
  equipCategory VARCHAR(80) NOT NULL,
  equipBrand VARCHAR(80) NOT NULL,
  equipRoom VARCHAR(80) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS laboratory_staff (
  staffID INT AUTO_INCREMENT PRIMARY KEY,
  staffFName VARCHAR(50) NOT NULL,
  staffLName VARCHAR(50) NOT NULL,
  staffRole VARCHAR(80) NOT NULL,
  staffTelNo VARCHAR(30) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS borrowing (
  borrowID INT AUTO_INCREMENT PRIMARY KEY,
  stuID INT NOT NULL,
  equipID INT NOT NULL,
  staffID INT NOT NULL,
  borrowDate DATETIME NOT NULL,
  dueDate DATE NOT NULL,
  returnDate DATE NULL,
  remarks TEXT NULL,
  CONSTRAINT fk_borrow_student FOREIGN KEY (stuID) REFERENCES student(stuID)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_borrow_equipment FOREIGN KEY (equipID) REFERENCES equipment(equipID)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_borrow_staff FOREIGN KEY (staffID) REFERENCES laboratory_staff(staffID)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  INDEX idx_borrow_date (borrowDate)
) ENGINE=InnoDB;

-- Example rows. Import into a fresh database for predictable IDs.
INSERT IGNORE INTO student (stuID, stuFName, stuLName, stuCourse, stuYear) VALUES
 (1, 'Vaugn', 'Garcia', 'BSIT', 3),
 (2, 'Shaun', 'Santos', 'BSCS', 2),
 (3, 'Mika', 'Reyes', 'BSIT', 1),
 (4, 'Ari', 'Cruz', 'BSIS', 4);
INSERT IGNORE INTO equipment (equipID, equipName, equipCategory, equipBrand, equipRoom) VALUES
 (1, 'Arduino Uno R3', 'Microcontroller', 'Arduino', 'Digital Lab 1'),
 (2, 'Digital Multimeter', 'Testing Tools', 'UNI-T', 'Digital Lab 2'),
 (3, 'LCD 16x2 Display', 'Display', 'Generic', 'Digital Lab 1'),
 (4, 'Raspberry Pi 4', 'Single Board Computer', 'Raspberry Pi', 'Digital Lab 3');
INSERT IGNORE INTO laboratory_staff
 (staffID, staffFName, staffLName, staffRole, staffTelNo) VALUES
 (1, 'Ana', 'Dela Cruz', 'Laboratory Assistant', '09171234567'),
 (2, 'Marco', 'Lim', 'Laboratory Technician', '09181234567'),
 (3, 'Joy', 'Ramos', 'Laboratory Supervisor', '09191234567');
INSERT IGNORE INTO borrowing
 (borrowID, stuID, equipID, staffID, borrowDate, dueDate, returnDate, remarks) VALUES
 (1, 1, 1, 1, '2026-10-01 09:00:00', '2026-10-05', '2026-10-04', 'Returned in good condition'),
 (2, 2, 2, 2, '2026-10-04 13:15:00', '2026-10-07', NULL, 'Laboratory activity'),
 (3, 3, 3, 1, '2026-10-09 10:20:00', '2026-10-12', '2026-10-11', 'Class demonstration'),
 (4, 1, 4, 3, '2026-10-12 08:30:00', '2026-10-15', NULL, 'Capstone practice'),
 (5, 4, 1, 2, '2026-10-14 15:45:00', '2026-10-18', NULL, 'Project prototype');
