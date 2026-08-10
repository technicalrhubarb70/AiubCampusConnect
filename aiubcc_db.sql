-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 10, 2026 at 11:35 AM
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
-- Database: `aiubcc_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `a_id` varchar(30) NOT NULL,
  `a_name` varchar(30) NOT NULL,
  `a_email` varchar(100) NOT NULL,
  `a_password` varchar(100) NOT NULL,
  `role` int(11) NOT NULL,
  `status` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`a_id`, `a_name`, `a_email`, `a_password`, `role`, `status`) VALUES
('11-00000-1', 'Admin', '11-00000-1@student.aiub.edu', '$2y$10$m1zpSwXv4ehy6atobp/4JOGGMNGHyRw/2.KIMqLqIH9q5xP.gFsfC', 1, 1),
('11-00000-2', 'Admin', '11-00000-2@student.aiub.edu', '$2y$10$rGHPPyVr890l.riLC.q51e.ZgnIV7XoZ0OKe10/lAAANK25HnYKNG', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `connections`
--

CREATE TABLE `connections` (
  `peer_id` int(30) NOT NULL,
  `requester_id` varchar(30) NOT NULL,
  `receiver_id` varchar(30) NOT NULL,
  `status` int(10) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `connections`
--

INSERT INTO `connections` (`peer_id`, `requester_id`, `receiver_id`, `status`, `created_at`) VALUES
(2, '23-52476-2', '23-22222-2', 1, '2026-02-04 21:29:13'),
(3, '23-52476-2', '23-52222-1', 1, '2026-02-03 23:46:26'),
(5, '23-52476-2', '23-52476-2', 1, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `course` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`course`) VALUES
('Advance Database Management Systems'),
('Advanced Algorithm Techniques'),
('Advanced Computer Networks'),
('Advanced Operating Systems'),
('Advanced Programming in Web Technologies'),
('Advanced Programming with .NET'),
('Advanced Programming with JAVA'),
('Algorithms'),
('Artificial Intelligence and Expert System'),
('Bangladesh Studies'),
('Basic Graph Theory'),
('Basic Mechanical Engineering'),
('Bioinformatics'),
('Business Communication'),
('Business Intelligence and Decision Support System'),
('Chemistry'),
('Compiler Design'),
('Complex Variables, Laplace and Z-transformations'),
('Computational Statistics and Probability'),
('Computer Aided Design & Drafting Lab'),
('Computer Graphics'),
('Computer Networks'),
('Computer Organization & Architecture'),
('Computer Science Mathematics'),
('Computer Vision & Pattern Recognition'),
('Cyber Laws & Information Security'),
('Data Communication'),
('Data Structure'),
('Data Structure Lab'),
('Data Warehouse and Data Mining'),
('Differential Calculus and Coordinate Geometry'),
('Digital Design with System Verilog, VHDL & FPGAs'),
('Digital Logic and Circuits'),
('Digital Logic and Circuits Lab'),
('Digital Marketing'),
('Digital Signal Processing'),
('Digital System Design'),
('Discrete Mathematics'),
('E-Commerce, E-Governance & E-Series'),
('Electronic Devices'),
('Electronic Devices Lab'),
('Engineering Ethics'),
('Engineering Management'),
('English Reading Skills and Public Speaking'),
('English Writing Skills and Communication'),
('Enterprise Resource Planning'),
('Human Computer Interaction'),
('Image Processing'),
('Industrial Electronics, Drives & Instrumentation'),
('Integral Calculus and Ordinary Differential Equations'),
('Internship'),
('Introduction to Computer Studies'),
('Introduction to Data Science'),
('Introduction to Database'),
('Introduction to Electrical Circuits'),
('Introduction to Electrical Circuits Lab'),
('Introduction to Programming Language'),
('Introduction to Programming Language Lab'),
('Linear Programming'),
('Machine Learning'),
('Management Information Systems'),
('Matrices, Vectors and Fourier Analysis'),
('Microprocessor and Embedded Systems'),
('Mobile Application Development'),
('Multimedia Systems'),
('Natural Language Processing'),
('Network Resource Management & Organization'),
('Network Security'),
('Numerical Methods for Science and Engineering'),
('Object Oriented Analysis and Design'),
('Object Oriented Programming 1'),
('Object Oriented Programming 2'),
('Operating Systems'),
('Parallel Computing'),
('Physics 1'),
('Physics 1 Lab'),
('Physics 2'),
('Physics 2 Lab'),
('Principles of Accounting'),
('Principles of Economics'),
('Programming in Python'),
('Research Methodology'),
('Robotics Engineering'),
('Signals and Linear Systems'),
('Simulation and Modelling'),
('Software Architecture & Design Patterns'),
('Software Development Project Management'),
('Software Engineering'),
('Software Quality and Testing'),
('Software Requirement Engineering'),
('Telecommunications Engineering'),
('Theory of Computation'),
('Thesis / Project'),
('VLSI Circuit Design'),
('Web Technologies'),
('Wireless Sensor Network');

-- --------------------------------------------------------

--
-- Table structure for table `free_time`
--

CREATE TABLE `free_time` (
  `day` varchar(20) NOT NULL,
  `free_times` varchar(50) NOT NULL,
  `s_id` varchar(30) DEFAULT NULL,
  `t_id` varchar(30) DEFAULT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `free_time`
--

INSERT INTO `free_time` (`day`, `free_times`, `s_id`, `t_id`, `id`) VALUES
('Sunday', '08:00-09:30,11:00-12:30', '23-52476-2', NULL, 3),
('Monday', '08:00-09:30,09:30-11:00,11:00-12:30,12:30-02:00', '23-52476-2', NULL, 4),
('Tuesday', '08:00-09:30,09:30-11:00,11:00-12:30,12:30-02:00,02', '23-52476-2', NULL, 5),
('Wednesday', '08:00-09:30,09:30-11:00,11:00-12:30,12:30-02:00,02', '23-52476-2', NULL, 6),
('Thursday', '09:30-11:00,11:00-12:30,12:30-02:00', '23-52476-2', NULL, 7),
('Sunday', '11:00-12:30', '23-22222-2', NULL, 17),
('Monday', '11:00-12:30,12:30-02:00', '23-22222-2', NULL, 18),
('Tuesday', '09:30-11:00,02:00-03:30', '23-22222-2', NULL, 19),
('Wednesday', '08:00-09:30,11:00-12:30,02:00-03:30', '23-22222-2', NULL, 20),
('Thursday', '11:00-12:30,12:30-02:00,03:30-05:00', '23-22222-2', NULL, 21),
('Sunday', '11:00-12:30', '23-52222-1', NULL, 22),
('Monday', '08:00-09:30,11:00-12:30', '23-52222-1', NULL, 23),
('Tuesday', '11:00-12:30', '23-52222-1', NULL, 24),
('Wednesday', '08:00-09:30,11:00-12:30', '23-52222-1', NULL, 25),
('Thursday', '08:00-09:30,11:00-12:30', '23-52222-1', NULL, 26),
('Sunday', '08:00-09:30', '23-52939-2', NULL, 33),
('Monday', '09:30-11:00,11:00-12:30,02:00-03:30', '23-52939-2', NULL, 34),
('Tuesday', '08:00-09:30,09:30-11:00,11:00-12:30', '23-52939-2', NULL, 35),
('Wednesday', '09:30-11:00,12:30-02:00,02:00-03:30', '23-52939-2', NULL, 36),
('Thursday', '08:00-09:30,09:30-11:00,11:00-12:30', '23-52939-2', NULL, 37),
('Sunday', '09:30-11:00,11:00-12:30,12:30-02:00', '23-52794-2', NULL, 38),
('Monday', '09:30-11:00,11:00-12:30,12:30-02:00', '23-52794-2', NULL, 39),
('Wednesday', '09:30-11:00,11:00-12:30,12:30-02:00', '23-52794-2', NULL, 40),
('Thursday', '11:00-12:30,12:30-02:00', '23-52794-2', NULL, 41),
('Tuesday', '09:30-11:00,11:00-12:30,12:30-02:00', '23-52794-2', NULL, 47);

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `login_id` varchar(30) NOT NULL,
  `login_password` varchar(100) NOT NULL,
  `role` int(20) NOT NULL,
  `status` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`login_id`, `login_password`, `role`, `status`) VALUES
('11-00000-1', '$2y$10$m1zpSwXv4ehy6atobp/4JOGGMNGHyRw/2.KIMqLqIH9q5xP.gFsfC', 1, 1),
('11-00000-2', '$2y$10$m1zpSwXv4ehy6atobp/4JOGGMNGHyRw/2.KIMqLqIH9q5xP.gFsfC', 1, 1),
('11-11111-1', '$2y$10$HXt9h1uG7S51NRc2I2bdm.R8fDnjawgLzjtANZ/dvI2UYrkOEByhy', 1, 1),
('11-52476-1', '$2y$10$6GyiRc/jmKjvYHu4Wb9XW.zxclUoMLW8FzHs2CNNtHJWNpShHlHIy', 2, 1),
('15-11111-2', '$2y$10$XcyqEVLzKMR8weXSKLA3OOmQmQaFKiv5ZohwCWmCCW/YYDFhi7H12', 2, 1),
('23-22222-2', '$2y$10$iEJILO9L8azNWzP16UO2kuTlzzw2Q8xAcJwgdIFfaFjGITr9iBm7e', 2, 1),
('23-52222-1', '$2y$10$f3eTzx9didOQbGaoN9vpWeC3rvXHMr1gbCOiQUKBL/By7kceEUHgC', 2, 1),
('23-52476-2', '$2y$10$LPL/xTR/uXYLxHMqeW9q6.c5wo2opmeI9z7FosFWZnzxZt4YBmXRS', 2, 1),
('23-52794-2', '$2y$10$mAEhPmuWno31B0zDpfjCruTFjHensPdWQuEyO8uR0ReLTTVb06Qwy', 2, 1),
('23-52939-2', '$2y$10$RyYCf1aMlAZu3r.ojx5RUuDU/0nhpGeTXuj.YhJlxenD44mrfgVIm', 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `m_id` int(11) NOT NULL,
  `sender_id` varchar(30) NOT NULL,
  `receiver_id` varchar(30) NOT NULL,
  `message` varchar(1000) NOT NULL,
  `file` varchar(200) NOT NULL,
  `sent_at` datetime NOT NULL,
  `deleted_by_sender` tinyint(1) NOT NULL DEFAULT 0,
  `deleted_by_receiver` tinyint(1) NOT NULL DEFAULT 0,
  `deleted_for_everyone` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`m_id`, `sender_id`, `receiver_id`, `message`, `file`, `sent_at`, `deleted_by_sender`, `deleted_by_receiver`, `deleted_for_everyone`) VALUES
(1, '23-52476-2', '23-52222-1', 'hello', '', '2026-01-21 10:57:32', 0, 0, 0),
(6, '23-52476-2', '23-22222-2', 'hi its rafit', '', '2026-01-21 15:20:46', 0, 0, 0),
(8, '23-22222-2', '23-52476-2', 'its nafi', '', '2026-01-21 16:37:29', 0, 0, 0),
(9, '23-22222-2', '23-52476-2', 'hello', '', '2026-01-21 16:53:10', 0, 0, 0),
(10, '23-52222-1', '23-52476-2', 'ola', '', '2026-01-21 18:04:39', 0, 0, 0),
(11, '23-52476-2', '23-52222-1', 'hello is it working', '', '2026-01-21 18:05:41', 0, 0, 0),
(12, '23-52222-1', '23-52476-2', 'no it is not working', '', '2026-01-21 18:06:36', 0, 0, 0),
(13, '23-52476-2', '23-52222-1', 'now', '', '2026-01-21 18:11:48', 0, 0, 0),
(14, '23-52222-1', '23-52476-2', 'i think working', '', '2026-01-21 18:12:03', 0, 0, 0),
(15, '23-52222-1', '23-52476-2', '', '6970c7a75a82d_CoverPage.jpg', '2026-01-21 18:33:43', 0, 0, 0),
(16, '23-52476-2', '23-22222-2', 'hello', '', '2026-01-21 19:57:40', 0, 0, 0),
(17, '23-52476-2', '23-52222-1', 'hhh', '', '2026-01-21 19:58:18', 0, 0, 0),
(18, '23-52476-2', '15-11111-2', 'hello', '', '2026-01-21 19:59:33', 0, 0, 0),
(19, '23-52476-2', '23-22222-2', 'asadad', '', '2026-01-21 21:14:15', 0, 0, 0),
(20, '23-52476-2', '23-22222-2', '', '6970ed5168cf3_Datacom Mid paatern.png', '2026-01-21 21:14:25', 0, 0, 0),
(32, '23-52476-2', '23-52222-1', 'hello', '', '2026-02-01 09:05:46', 0, 0, 0),
(33, '23-52476-2', '23-52222-1', '', '697ec36469173_sampleimg5.jpg', '2026-02-01 09:07:16', 0, 0, 0),
(34, '23-52476-2', '23-52222-1', '', '6981f030b9458_sample4.jpg', '2026-02-03 18:55:12', 0, 0, 0),
(35, '23-52476-2', '23-52222-1', '', '6981f70e6969a_file.pdf', '2026-02-03 19:24:30', 0, 0, 0),
(36, '23-52476-2', '11-52476-1', 'hello', '', '2026-02-03 19:27:13', 1, 0, 0),
(40, '23-52476-2', '11-52476-1', 'hello', '', '2026-02-03 23:02:56', 0, 0, 0),
(43, '23-52476-2', '11-52476-1', 'hehehehehehhhhhhhhhhh heeeeeeeeeeee heeeeeeeeeeeeeeeeee heeeeeeeeeeeeeeeeeeeee ddddonsdnfjsdnfjsdfnjasdfjkfasdfknaskfnajksnf asdfjkabdsfjk', '', '2026-02-04 01:40:10', 0, 0, 0),
(49, '23-52939-2', '23-52476-2', 'vondo', '', '2026-02-04 21:26:52', 0, 0, 0),
(50, '23-52476-2', '23-52939-2', 'vondo', '', '2026-02-04 21:27:36', 0, 0, 0),
(53, '23-52794-2', '23-52476-2', 'hi', '', '2026-02-05 01:30:02', 1, 0, 0),
(54, '23-52794-2', '23-52476-2', '', '69839e4c65931_level2_overlay_grid_2.png', '2026-02-05 01:30:20', 0, 0, 0),
(55, '23-52794-2', '23-52939-2', 'why doesnt it showwwwwww', '', '2026-02-05 01:32:15', 0, 0, 0),
(56, '23-52476-2', '23-52794-2', '...........', '', '2026-02-05 01:33:32', 0, 1, 0),
(57, '23-52794-2', '23-52476-2', 'hi', '', '2026-02-05 02:15:19', 0, 0, 0),
(58, '23-52476-2', '23-52794-2', 'hfadsojs', '', '2026-02-05 02:30:43', 0, 0, 0),
(59, '23-52794-2', '23-52476-2', 'color changes', '', '2026-02-05 02:30:53', 0, 0, 0),
(60, '23-52476-2', '23-52794-2', 'reciever', '', '2026-02-05 02:31:17', 0, 0, 0),
(61, '23-52794-2', '23-52476-2', '', '6983ad6d6a30e_Media (8).jpg', '2026-02-05 02:34:53', 0, 0, 1),
(62, '23-52476-2', '23-52794-2', 'senfer', '', '2026-02-05 02:55:05', 0, 0, 1),
(63, '23-52794-2', '23-52476-2', 'something is wronh', '', '2026-02-05 02:57:55', 0, 0, 0),
(66, '23-52476-2', '23-52476-2', '', '6985c5a4c5d36_Screenshot 2025-04-22 173156.png', '2026-02-06 16:42:44', 0, 0, 0),
(67, '23-22222-2', '23-52794-2', 'hello', '', '2026-02-06 17:48:52', 0, 0, 0),
(68, '23-52794-2', '23-22222-2', 'hello', '', '2026-02-06 17:50:03', 0, 0, 0),
(69, '23-52476-2', '23-52222-1', 'hello', '', '2026-08-10 14:11:19', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

CREATE TABLE `skills` (
  `skill_id` varchar(30) NOT NULL,
  `skill_name` varchar(100) NOT NULL,
  `s_id` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `skills`
--

INSERT INTO `skills` (`skill_id`, `skill_name`, `s_id`) VALUES
('sk_6970db253936f1.87246893', 'C#', '23-52476-2'),
('sk_6983649a60e799.97337943', 'C#', '23-52939-2'),
('sk_698388db925d08.99484143', 'coding', '23-52794-2'),
('sk_698388e459f517.35072922', 'c#', '23-52794-2'),
('sk_6985d1bc8f9bd0.29425008', 'C++', '23-52222-1'),
('sk_6985d1ccf3b536.83965977', 'C#', '23-52222-1'),
('sk_6985d3ae71d447.61939869', 'C#', '23-22222-2');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `s_id` varchar(30) NOT NULL,
  `s_name` varchar(100) NOT NULL,
  `s_gender` varchar(20) NOT NULL,
  `s_email` varchar(100) NOT NULL,
  `s_password` varchar(100) NOT NULL,
  `role` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `s_propic` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`s_id`, `s_name`, `s_gender`, `s_email`, `s_password`, `role`, `status`, `created_at`, `s_propic`) VALUES
('11-11111-1', 'Admin', 'male', '11-11111-1@student.aiub.edu', '$2y$10$HXt9h1uG7S51NRc2I2bdm.R8fDnjawgLzjtANZ/dvI2UYrkOEByhy', 1, 1, '0000-00-00 00:00:00', ''),
('11-52476-1', 'asfa', 'female', '11-52476-1@student.aiub.edu', '$2y$10$6GyiRc/jmKjvYHu4Wb9XW.zxclUoMLW8FzHs2CNNtHJWNpShHlHIy', 2, 1, '2026-01-20 23:46:08', 'Resources/emptyImg.jpg'),
('15-11111-2', 'Riad', 'male', '15-11111-2@student.aiub.edu', '$2y$10$XcyqEVLzKMR8weXSKLA3OOmQmQaFKiv5ZohwCWmCCW/YYDFhi7H12', 2, 1, '2026-01-21 14:19:36', 'Resources/default.png'),
('23-22222-2', 'sums', 'male', '23-22222-2@student.aiub.edu', '$2y$10$iEJILO9L8azNWzP16UO2kuTlzzw2Q8xAcJwgdIFfaFjGITr9iBm7e', 2, 1, '2026-01-20 23:44:52', 'Resources/6985d29ab9e7c_Screenshot 2026-02-06 173743.jpg'),
('23-52222-1', 'Md  Nafees', 'male', '23-52222-1@student.aiub.edu', '$2y$10$f3eTzx9didOQbGaoN9vpWeC3rvXHMr1gbCOiQUKBL/By7kceEUHgC', 2, 1, '2026-01-20 23:43:15', 'Resources/6985d18fa92c3_Screenshot 2026-02-06 173324.jpg'),
('23-52476-2', 'Md  Rafit ', 'male', '23-52476-2@student.aiub.edu', '$2y$10$LPL/xTR/uXYLxHMqeW9q6.c5wo2opmeI9z7FosFWZnzxZt4YBmXRS', 2, 1, '2026-01-20 15:40:19', 'Resources/6981e01ed36a4_Screenshot 2025-08-13 201148.png'),
('23-52794-2', 'Ayesha', 'female', '23-52794-2@student.aiub.edu', '$2y$10$mAEhPmuWno31B0zDpfjCruTFjHensPdWQuEyO8uR0ReLTTVb06Qwy', 2, 1, '0000-00-00 00:00:00', ''),
('23-52939-2', 'Hamim ', 'male', '23-52939-2@student.aiub.edu', '$2y$10$RyYCf1aMlAZu3r.ojx5RUuDU/0nhpGeTXuj.YhJlxenD44mrfgVIm', 2, 1, '2026-02-04 16:22:57', 'Resources/default.png');

-- --------------------------------------------------------

--
-- Table structure for table `student_course`
--

CREATE TABLE `student_course` (
  `course` varchar(100) NOT NULL,
  `s_id` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_course`
--

INSERT INTO `student_course` (`course`, `s_id`) VALUES
('Web Technologies', '23-22222-2'),
('Web Technologies', '23-52476-2'),
('Web Technologies', '23-52939-2'),
('Web Technologies', '23-52794-2'),
('Software Engineering', '23-52794-2'),
('Web Technologies', '23-52222-1');

-- --------------------------------------------------------

--
-- Table structure for table `tutor`
--

CREATE TABLE `tutor` (
  `t_id` int(30) NOT NULL,
  `s_id` varchar(30) NOT NULL,
  `course` varchar(100) NOT NULL,
  `help_type` varchar(100) NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `ratings` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tutor`
--

INSERT INTO `tutor` (`t_id`, `s_id`, `course`, `help_type`, `status`, `created_at`, `ratings`) VALUES
(1, '23-52476-2', 'Web Technologies', 'Project, Exam', 1, '2026-02-04 12:50:02', 0),
(3, '23-52476-2', 'Object Oriented Programming 1', 'Project, Exam', 1, '2026-02-04 20:11:41', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD KEY `fk_admin_login` (`a_id`);

--
-- Indexes for table `connections`
--
ALTER TABLE `connections`
  ADD PRIMARY KEY (`peer_id`),
  ADD KEY `fk_connectionsRc_student` (`receiver_id`),
  ADD KEY `fk_connectionsRq_student` (`requester_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`course`);

--
-- Indexes for table `free_time`
--
ALTER TABLE `free_time`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_student_day` (`day`,`s_id`),
  ADD KEY `fk_freetime_student` (`s_id`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`login_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`m_id`),
  ADD KEY `fk_messagesR_student` (`receiver_id`),
  ADD KEY `fk_messagesS_student` (`sender_id`);

--
-- Indexes for table `skills`
--
ALTER TABLE `skills`
  ADD KEY `fk_skill_student` (`s_id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`s_id`);

--
-- Indexes for table `student_course`
--
ALTER TABLE `student_course`
  ADD KEY `fk_studen_course_to_student` (`s_id`),
  ADD KEY `fk_student_course_to_course` (`course`);

--
-- Indexes for table `tutor`
--
ALTER TABLE `tutor`
  ADD PRIMARY KEY (`t_id`),
  ADD KEY `fk_tutor_student` (`s_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `connections`
--
ALTER TABLE `connections`
  MODIFY `peer_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `free_time`
--
ALTER TABLE `free_time`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `m_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `tutor`
--
ALTER TABLE `tutor`
  MODIFY `t_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `fk_admin_login` FOREIGN KEY (`a_id`) REFERENCES `login` (`login_id`);

--
-- Constraints for table `connections`
--
ALTER TABLE `connections`
  ADD CONSTRAINT `fk_connectionsRc_student` FOREIGN KEY (`receiver_id`) REFERENCES `student` (`s_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_connectionsRq_student` FOREIGN KEY (`requester_id`) REFERENCES `student` (`s_id`) ON DELETE CASCADE;

--
-- Constraints for table `free_time`
--
ALTER TABLE `free_time`
  ADD CONSTRAINT `fk_freetime_student` FOREIGN KEY (`s_id`) REFERENCES `student` (`s_id`);

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `fk_messagesR_student` FOREIGN KEY (`receiver_id`) REFERENCES `student` (`s_id`),
  ADD CONSTRAINT `fk_messagesS_student` FOREIGN KEY (`sender_id`) REFERENCES `student` (`s_id`);

--
-- Constraints for table `skills`
--
ALTER TABLE `skills`
  ADD CONSTRAINT `fk_skill_student` FOREIGN KEY (`s_id`) REFERENCES `student` (`s_id`);

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `fk_student_login` FOREIGN KEY (`s_id`) REFERENCES `login` (`login_id`);

--
-- Constraints for table `student_course`
--
ALTER TABLE `student_course`
  ADD CONSTRAINT `fk_studen_course_to_student` FOREIGN KEY (`s_id`) REFERENCES `student` (`s_id`),
  ADD CONSTRAINT `fk_student_course_to_course` FOREIGN KEY (`course`) REFERENCES `courses` (`course`);

--
-- Constraints for table `tutor`
--
ALTER TABLE `tutor`
  ADD CONSTRAINT `fk_tutor_student` FOREIGN KEY (`s_id`) REFERENCES `student` (`s_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
