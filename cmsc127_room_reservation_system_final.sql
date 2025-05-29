-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 29, 2025 at 04:55 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cmsc127_room_reservation_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `academic_schedule`
--

CREATE TABLE `academic_schedule` (
  `sched_id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `room_name` varchar(255) NOT NULL,
  `faculty_id` varchar(20) NOT NULL,
  `day` varchar(255) NOT NULL,
  `time_start` time NOT NULL,
  `time_end` time NOT NULL,
  `sched_start` date NOT NULL,
  `sched_end` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `academic_schedule`
--

INSERT INTO `academic_schedule` (`sched_id`, `subject`, `room_name`, `faculty_id`, `day`, `time_start`, `time_end`, `sched_start`, `sched_end`) VALUES
(1, 'CMSC 127', 'CL2', 'fac001', 'Monday', '09:00:00', '11:00:00', '2025-06-03', '2025-10-15'),
(2, 'CMSC 150', 'CL2', 'fac001', 'Wednesday', '13:00:00', '15:00:00', '2025-06-03', '2025-10-15'),
(3, 'CMSC 100', 'CL2', 'fac001', 'Friday', '10:00:00', '12:00:00', '2025-06-03', '2025-10-15'),
(4, 'CMSC 128', 'CL2', 'fac001', 'Tuesday', '08:00:00', '10:00:00', '2025-06-03', '2025-10-15'),
(5, 'CMSC 198', 'CL2', 'fac001', 'Thursday', '14:00:00', '16:00:00', '2025-06-03', '2025-10-15'),
(6, 'CMSC 101', 'CL2', 'fac002', 'Wednesday', '09:00:00', '11:00:00', '2025-06-03', '2025-10-15');

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` varchar(20) NOT NULL,
  `admin_name` varchar(100) NOT NULL,
  `designation` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `admin_name`, `designation`, `username`, `password`) VALUES
('admin001', 'Kent Genilo', 'Site Developer', 'kentoy', '12345');

-- --------------------------------------------------------

--
-- Table structure for table `asset`
--

CREATE TABLE `asset` (
  `asset_id` int(11) NOT NULL,
  `asset_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `asset`
--

INSERT INTO `asset` (`asset_id`, `asset_name`, `description`) VALUES
(13, 'Computer', 'It is a computer. You know what that is.'),
(15, 'Aircon', 'It cools you off.');

-- --------------------------------------------------------

--
-- Table structure for table `contains`
--

CREATE TABLE `contains` (
  `room_name` varchar(255) NOT NULL,
  `asset_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL CHECK (`quantity` > 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contains`
--

INSERT INTO `contains` (`room_name`, `asset_id`, `quantity`) VALUES
('CL1', 15, 1),
('CL2', 13, 15),
('CL2', 15, 2),
('CL3', 15, 2),
('CL4', 15, 2);

-- --------------------------------------------------------

--
-- Table structure for table `faculty`
--

CREATE TABLE `faculty` (
  `faculty_id` varchar(20) NOT NULL,
  `faculty_name` varchar(100) NOT NULL,
  `division` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_available` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty`
--

INSERT INTO `faculty` (`faculty_id`, `faculty_name`, `division`, `username`, `password`, `is_available`) VALUES
('fac001', 'Jave Hulleza', 'Computer Science', 'jeb', '12345', 1),
('fac002', 'Jasmine Magadan', 'Computer Science', 'Min', 'meow', 1);

-- --------------------------------------------------------

--
-- Table structure for table `faculty_reservation`
--

CREATE TABLE `faculty_reservation` (
  `faculty_reservation_id` varchar(10) NOT NULL,
  `faculty_id` varchar(20) NOT NULL,
  `room_name` varchar(255) NOT NULL,
  `purpose` text NOT NULL,
  `request_date` date NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `is_archived` tinyint(1) NOT NULL,
  `time_start` datetime NOT NULL,
  `time_end` datetime NOT NULL,
  `admin_id` varchar(20) DEFAULT NULL,
  `is_admin_approved` tinyint(1) DEFAULT NULL,
  `admin_remark` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty_reservation`
--

INSERT INTO `faculty_reservation` (`faculty_reservation_id`, `faculty_id`, `room_name`, `purpose`, `request_date`, `is_active`, `is_archived`, `time_start`, `time_end`, `admin_id`, `is_admin_approved`, `admin_remark`) VALUES
('FR001', 'fac001', 'CL2', 'Department meeting', '2025-06-01', 1, 1, '2025-06-05 13:00:00', '2025-06-05 14:00:00', 'admin001', 1, 'Approved'),
('FR002', 'fac001', 'CL3', 'Research consultation', '2025-06-01', 1, 0, '2025-06-06 10:00:00', '2025-06-06 12:00:00', NULL, NULL, NULL),
('FR003', 'fac001', 'CL1', 'Special lecture', '2025-06-02', 1, 0, '2025-06-07 09:00:00', '2025-06-07 11:00:00', 'admin001', 0, 'Declined'),
('FR004', 'fac001', 'Room 201', 'Workshop prep', '2025-06-03', 1, 0, '2025-06-08 14:00:00', '2025-06-08 17:00:00', 'admin001', 0, 'Declined'),
('FR005', 'fac001', 'PL1', 'Team meeting', '2025-06-04', 1, 0, '2025-06-09 08:00:00', '2025-06-09 10:00:00', 'admin001', 1, 'Approved'),
('FR006', 'fac002', 'Room 101', 'Tutoring Session', '2025-05-26', 1, 0, '2025-05-26 13:00:00', '2025-05-26 15:00:00', NULL, NULL, NULL),
('RES-F-9089', 'fac001', 'CL1', 'Play yey', '2025-05-28', 1, 0, '2025-06-04 16:34:00', '2025-06-04 22:34:00', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE `room` (
  `room_name` varchar(255) NOT NULL,
  `is_available` tinyint(1) NOT NULL,
  `room_type` varchar(255) NOT NULL,
  `capacity` int(11) NOT NULL,
  `floor_number` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room`
--

INSERT INTO `room` (`room_name`, `is_available`, `room_type`, `capacity`, `floor_number`) VALUES
('CL1', 1, 'Laboratory', 15, 1),
('CL2', 1, 'Laboratory', 30, 1),
('CL3', 1, 'Laboratory', 30, 1),
('CL4', 1, 'Laboratory', 30, 1),
('PL1', 1, 'Laboratory', 20, 2),
('Room 101', 1, 'Lecture', 20, 1),
('Room 201', 1, 'Lecture', 20, 2);

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `student_number` varchar(20) NOT NULL,
  `student_name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `degree_program` varchar(100) NOT NULL,
  `year_level` int(11) NOT NULL,
  `college` enum('College of Arts and Sciences','College of Management','School of Technology','College of Fisheries and Ocean Sciences') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`student_number`, `student_name`, `username`, `password`, `degree_program`, `year_level`, `college`) VALUES
('stud001', 'Kent Genilo', 'kentoy', '12345', 'B.S. Computer Science', 2, 'College of Arts and Sciences');

-- --------------------------------------------------------

--
-- Table structure for table `student_reservation`
--

CREATE TABLE `student_reservation` (
  `student_reservation_id` varchar(10) NOT NULL,
  `student_number` varchar(20) NOT NULL,
  `room_name` varchar(255) NOT NULL,
  `purpose` text NOT NULL,
  `request_date` date NOT NULL,
  `time_start` datetime NOT NULL,
  `time_end` datetime NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `is_archived` tinyint(1) NOT NULL,
  `admin_id` varchar(20) DEFAULT NULL,
  `is_admin_approved` tinyint(1) DEFAULT NULL,
  `admin_remark` text DEFAULT NULL,
  `faculty_id` varchar(20) NOT NULL,
  `is_faculty_approved` tinyint(1) DEFAULT NULL,
  `faculty_remark` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_reservation`
--

INSERT INTO `student_reservation` (`student_reservation_id`, `student_number`, `room_name`, `purpose`, `request_date`, `time_start`, `time_end`, `is_active`, `is_archived`, `admin_id`, `is_admin_approved`, `admin_remark`, `faculty_id`, `is_faculty_approved`, `faculty_remark`) VALUES
('RES-S-6132', 'stud001', 'CL2', 'Bardagulan', '2025-05-27', '2025-06-07 08:00:00', '2025-06-07 12:00:00', 1, 0, NULL, NULL, NULL, 'fac001', NULL, NULL),
('RES-S-7494', 'stud001', 'CL1', 'Meditation na may kaunting sigaw ba raaaaaaa!!!', '2025-05-27', '2025-06-08 13:00:00', '2025-06-08 17:00:00', 1, 0, NULL, NULL, NULL, 'fac001', NULL, NULL),
('SR001', 'stud001', 'CL3', 'Group study session', '2025-06-01', '2025-06-07 10:00:00', '2025-06-07 12:00:00', 1, 0, 'admin001', 1, 'Approved', 'fac001', 1, 'OK'),
('SR002', 'stud001', 'Room 101', 'Thesis defense', '2025-06-02', '2025-06-08 09:00:00', '2025-06-08 12:00:00', 1, 0, NULL, NULL, NULL, 'fac001', NULL, NULL),
('SR003', 'stud001', 'CL1', 'Org meeting', '2025-06-03', '2025-06-09 15:00:00', '2025-06-09 17:00:00', 1, 0, NULL, NULL, NULL, 'fac001', 1, NULL),
('SR004', 'stud001', 'Room 201', 'Study group', '2025-06-04', '2025-06-10 08:00:00', '2025-06-10 10:00:00', 1, 0, 'admin001', 1, 'Approved', 'fac001', 0, 'Declined'),
('SR005', 'stud001', 'PL1', 'Project discussion', '2025-06-05', '2025-06-11 13:00:00', '2025-06-11 15:00:00', 1, 0, 'admin001', 1, 'Approved', 'fac001', 1, 'Approved');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `academic_schedule`
--
ALTER TABLE `academic_schedule`
  ADD PRIMARY KEY (`sched_id`),
  ADD KEY `room_name` (`room_name`),
  ADD KEY `faculty_id` (`faculty_id`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `asset`
--
ALTER TABLE `asset`
  ADD PRIMARY KEY (`asset_id`);

--
-- Indexes for table `contains`
--
ALTER TABLE `contains`
  ADD PRIMARY KEY (`room_name`,`asset_id`),
  ADD KEY `asset_id` (`asset_id`);

--
-- Indexes for table `faculty`
--
ALTER TABLE `faculty`
  ADD PRIMARY KEY (`faculty_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `faculty_reservation`
--
ALTER TABLE `faculty_reservation`
  ADD PRIMARY KEY (`faculty_reservation_id`),
  ADD KEY `faculty_id` (`faculty_id`),
  ADD KEY `room_name` (`room_name`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`room_name`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`student_number`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `student_reservation`
--
ALTER TABLE `student_reservation`
  ADD PRIMARY KEY (`student_reservation_id`),
  ADD KEY `room_name` (`room_name`),
  ADD KEY `admin_id` (`admin_id`),
  ADD KEY `faculty_id` (`faculty_id`),
  ADD KEY `student_number` (`student_number`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `academic_schedule`
--
ALTER TABLE `academic_schedule`
  MODIFY `sched_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `asset`
--
ALTER TABLE `asset`
  MODIFY `asset_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `academic_schedule`
--
ALTER TABLE `academic_schedule`
  ADD CONSTRAINT `academic_schedule_ibfk_1` FOREIGN KEY (`room_name`) REFERENCES `room` (`room_name`),
  ADD CONSTRAINT `academic_schedule_ibfk_2` FOREIGN KEY (`faculty_id`) REFERENCES `faculty` (`faculty_id`);

--
-- Constraints for table `contains`
--
ALTER TABLE `contains`
  ADD CONSTRAINT `contains_ibfk_1` FOREIGN KEY (`room_name`) REFERENCES `room` (`room_name`),
  ADD CONSTRAINT `contains_ibfk_2` FOREIGN KEY (`asset_id`) REFERENCES `asset` (`asset_id`);

--
-- Constraints for table `faculty_reservation`
--
ALTER TABLE `faculty_reservation`
  ADD CONSTRAINT `faculty_reservation_ibfk_1` FOREIGN KEY (`faculty_id`) REFERENCES `faculty` (`faculty_id`),
  ADD CONSTRAINT `faculty_reservation_ibfk_2` FOREIGN KEY (`room_name`) REFERENCES `room` (`room_name`),
  ADD CONSTRAINT `faculty_reservation_ibfk_3` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`admin_id`);

--
-- Constraints for table `student_reservation`
--
ALTER TABLE `student_reservation`
  ADD CONSTRAINT `student_reservation_ibfk_1` FOREIGN KEY (`room_name`) REFERENCES `room` (`room_name`),
  ADD CONSTRAINT `student_reservation_ibfk_2` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`admin_id`),
  ADD CONSTRAINT `student_reservation_ibfk_3` FOREIGN KEY (`faculty_id`) REFERENCES `faculty` (`faculty_id`),
  ADD CONSTRAINT `student_reservation_ibfk_4` FOREIGN KEY (`student_number`) REFERENCES `student` (`student_number`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
