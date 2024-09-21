-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 08, 2024 at 01:21 PM
-- Server version: 8.3.0
-- PHP Version: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `college_dashboard`
--
CREATE DATABASE IF NOT EXISTS `college_dashboard` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `college_dashboard`;

-- --------------------------------------------------------

--
-- Table structure for table `class_schedule`
--

CREATE TABLE IF NOT EXISTS `class_schedule` (
  `id` int NOT NULL AUTO_INCREMENT,
  `subject` varchar(100) NOT NULL,
  `date` date NOT NULL,
  `time_from` time NOT NULL,
  `time_to` time NOT NULL,
  `teacher` varchar(100) NOT NULL,
  `venue` varchar(100) NOT NULL,
  `course` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `class_schedule`
--

INSERT INTO `class_schedule` (`id`, `subject`, `date`, `time_from`, `time_to`, `teacher`, `venue`, `course`) VALUES
(4, 'Mathematics', '2024-09-02', '09:00:00', '11:00:00', 'Prof. John Doe', 'Room 101', 'BCA'),
(5, 'Programming', '2024-09-03', '10:00:00', '12:00:00', 'Dr. Jane Smith', 'Lab 2', 'BCA'),
(6, 'Business Management', '2024-09-04', '11:00:00', '01:00:00', 'Dr. Albert Green', 'Room 203', 'BBA'),
(7, 'Accounting', '2024-09-05', '12:00:00', '02:00:00', 'Prof. Michael White', 'Room 202', 'BBA');

-- --------------------------------------------------------

--
-- Table structure for table `extracurricular_activities`
--

CREATE TABLE IF NOT EXISTS `extracurricular_activities` (
  `id` int NOT NULL AUTO_INCREMENT,
  `activity_name` varchar(100) NOT NULL,
  `date` date NOT NULL,
  `time_from` time NOT NULL,
  `time_to` time NOT NULL,
  `supervising_teacher` varchar(100) NOT NULL,
  `venue` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `extracurricular_activities`
--

INSERT INTO `extracurricular_activities` (`id`, `activity_name`, `date`, `time_from`, `time_to`, `supervising_teacher`, `venue`) VALUES
(6, 'avc', '2024-09-14', '13:12:00', '13:15:00', 'abc', 'auditorium'),
(2, 'Coding Hackathon', '2024-09-20', '09:00:00', '17:00:00', 'Dr. Jane Smith', 'Computer Lab'),
(3, 'Sports Event', '2024-09-18', '08:00:00', '12:00:00', 'Prof. Albert Green', 'Sports Ground'),
(4, 'Music Fest', '2024-09-25', '17:00:00', '20:00:00', 'Dr. Emma Watson', 'Main Hall'),
(5, 'avc', '2024-09-14', '13:12:00', '13:15:00', 'abc', 'auditorium');

-- --------------------------------------------------------

--
-- Table structure for table `faculty`
--

CREATE TABLE IF NOT EXISTS `faculty` (
  `name` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `employee_id` int NOT NULL,
  `designation` varchar(50) NOT NULL,
  PRIMARY KEY (`employee_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `faculty`
--

INSERT INTO `faculty` (`name`, `password`, `employee_id`, `designation`) VALUES
('krishna khurana', '$2y$10$3OAfrytvgHuHBxSzjbYRkOFrXmXlgyWqh9w.l7Eh8zArmpHKFbxaK', 123, ''),
('krishna khurana', '$2y$10$CAUFbeT/GYwantR3neavJedXp9NIfvwqeLP6z0Dunpp8MwT7haXyO', 1234, ''),
('krishna khurana', '$2y$10$WymgcgvDfELzPgle6DKd0uCrP.ikR0SvMq9bcyaerbD/EATGJ6iaW', 12343, ''),
('admin', '$2y$10$nIt84t7bhmvKxSTQkRUvNeWFWXOuX2/NQBaN.7f8DP4qgsu9XlSle', 893, '');

-- --------------------------------------------------------

--
-- Table structure for table `faculty_info`
--

CREATE TABLE IF NOT EXISTS `faculty_info` (
  `name` int NOT NULL,
  `designation` varchar(10) NOT NULL,
  `employee_id` int NOT NULL,
  `course` varchar(10) NOT NULL,
  `year` int NOT NULL,
  PRIMARY KEY (`employee_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `faculty_info`
--

INSERT INTO `faculty_info` (`name`, `designation`, `employee_id`, `course`, `year`) VALUES
(0, 'HOD', 893, 'BCA', 2);

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE IF NOT EXISTS `feedback` (
  `id` int NOT NULL AUTO_INCREMENT,
  `enrollment_number` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `course` varchar(50) NOT NULL,
  `year` varchar(10) NOT NULL,
  `subject_complaint` varchar(10) NOT NULL,
  `subject_suggestion` varchar(100) DEFAULT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `enrollment_number`, `name`, `course`, `year`, `subject_complaint`, `subject_suggestion`, `description`) VALUES
(2, '13', 'krishna khurana', 'BCA', '2nd Year', 'no', '', 'gsfnthkmdb');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `date_posted` date NOT NULL,
  `course` varchar(50) DEFAULT 'all',
  `year` varchar(10) DEFAULT 'all',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `title`, `content`, `date_posted`, `course`, `year`) VALUES
(1, 'New Library Hours', 'The library will be open from 8 AM to 10 PM starting next week.', '2024-09-01', 'all', 'all'),
(2, 'Workshop on Data Science', 'Join the Data Science workshop this Friday.', '2024-09-03', 'BCA', '1st Year'),
(3, 'Important Exam Notice', 'Mid-term exams will begin next month.', '2024-09-01', 'all', 'all'),
(4, 'dbhk', 'dummmy', '2024-09-07', 'all', 'all');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE IF NOT EXISTS `students` (
  `enrollment_number` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`enrollment_number`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`enrollment_number`, `name`, `password`, `created_at`) VALUES
(13, 'krishnna khurana', '$2y$10$92lFPJhPkfZtQXSVFtIgQObB4st1wy3zb9Nc/rrXewsb0B4K814Ju', '2024-08-31 06:25:26'),
(14, 'daksh', '$2y$10$k4hqQd0KrQWw.l7p4lSmjuQin7u.J/ptOaSCdY8ZK7M5fkSuXBVhK', '2024-09-01 08:22:03'),
(20, 'abhiraj', '$2y$10$eZpIt6bpM85Ahtl4bjKFJedlmOJ.2h8zk2HRtMdGEJmTN8RAXbQ36', '2024-09-01 12:19:41'),
(21, 'devil', '$2y$10$HG881kwjkjGSD0HGuH.Cp.ZAFWI4vbwlHyMSo8XcIgJMEFtnqxMU6', '2024-09-01 12:20:40'),
(10, 'devil', '$2y$10$pxI5Zhw6NE2NPg/hEwy6dOjUkKnkFvt0QpwLL/ns9nuJxzNgUESgW', '2024-09-03 12:08:22'),
(1, 'abc', '$2y$10$71srw30gzOrqzyZK1EyvH.3pEJxHWPVudq4JjHaEBGT9rlP2U/VpG', '2024-09-07 08:09:44');

-- --------------------------------------------------------

--
-- Table structure for table `student_info`
--

CREATE TABLE IF NOT EXISTS `student_info` (
  `enrollment_number` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `course` varchar(50) DEFAULT NULL,
  `year` varchar(10) DEFAULT NULL,
  `section` varchar(10) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `aadhaar_number` bigint DEFAULT NULL,
  `gmail` varchar(100) DEFAULT NULL,
  `abc_id` varchar(50) DEFAULT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `guardian_phone_number` varchar(10) NOT NULL,
  PRIMARY KEY (`enrollment_number`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `student_info`
--

INSERT INTO `student_info` (`enrollment_number`, `name`, `course`, `year`, `section`, `dob`, `aadhaar_number`, `gmail`, `abc_id`, `phone_number`, `address`, `guardian_phone_number`) VALUES
(13, 'krishna khurana', 'BCA', '2nd', '', '2024-08-17', 910273872098, 'abc@gmail.com', '784574', '89749887489', '592 Harley Brook Lane', ''),
(14, 'daksh', 'BCA', '1st', '', '2024-09-17', 910273872098, 'abc@gmail.com', '784574', '89749887489', '592 Harley Brook Lane', ''),
(20, 'abhiraj', 'BCA', '3rd', '', '2024-09-26', 910273872098, 'abc@gmail.com', '784574', '89749887489', '592 Harley Brook Lane', ''),
(21, 'devil', 'BBA', '1st', '', '2024-09-27', 910273872098, 'abc@gmail.com', '784574', '89749887489', '592 Harley Brook Lane', ''),
(10, 'devil', 'BCA', '1st', '', '2024-09-09', 910273872098, 'abc@gmail.com', '784574', '89749887489', '592 Harley Brook Lane', ''),
(1, 'devil', 'BCA', '1st', 'A', '2024-09-14', 910273872098, 'abc@gmail.com', '784574', '89749887489', '592 Harley Brook Lane', '8974988748');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
